import { config } from './config.js';
import { getClient } from './openaiClient.js';
import { getAnthropic, isClaudeModel } from './anthropicClient.js';
import { getPrompt, renderPrompt } from './prompt.js';
import { rulesBlock } from './rules.js';
import { getHistory, recordUsage } from './store.js';
import { toolDefs, toolDefsClaude, runTool } from './tools.js';

// Instrucción de formato que se añade al prompt en tiempo de ejecución.
// Equivale al "Structured Output Parser" del workflow (response.part_1 ... part_10).
const FORMATO = `

---

# FORMATO DE SALIDA (OBLIGATORIO)
Responde ÚNICAMENTE con un objeto JSON válido, sin texto antes ni después, con esta forma:
{
  "response": {
    "part_1": "primera parte del mensaje",
    "part_2": "segunda parte (opcional)",
    "part_3": "... hasta part_10 (todas opcionales)"
  }
}
Cada "part_N" es un mensaje independiente que llegará por separado al lead (simula escribir como una persona real).
Usa solo las parts que necesites y deja el resto como cadena vacía "". No escribas variables ni placeholders.`;

// Divide la respuesta del modelo en partes (array de strings no vacíos).
function parseParts(content) {
  // 1. Intentar JSON { response: { part_1.. } }
  try {
    const jsonStr = content.slice(content.indexOf('{'), content.lastIndexOf('}') + 1);
    const obj = JSON.parse(jsonStr);
    const r = obj.response || obj;
    const parts = [];
    for (let i = 1; i <= 10; i++) {
      const v = r['part_' + i];
      if (typeof v === 'string' && v.trim()) parts.push(v.trim());
    }
    if (parts.length) return parts;
  } catch { /* cae al fallback */ }

  // 2. Fallback: usar el texto plano como una sola parte
  const clean = content.trim();
  return clean ? [clean] : [];
}

/**
 * Procesa un mensaje entrante y devuelve un array de partes a enviar.
 * @param {string} subscriberId  id del suscriptor en ManyChat (clave de memoria)
 * @param {string} nombre        nombre del lead
 * @param {string} texto         mensaje ya agregado/transcrito
 */
export async function generarRespuesta(subscriberId, nombre, texto) {
  const system = renderPrompt(getPrompt(), { nombre }) + rulesBlock() + FORMATO;

  // Saneamos el historial: solo mensajes válidos (evita que una entrada corrupta
  // rompa TODAS las llamadas siguientes y el bot deje de contestar).
  const history = getHistory(subscriberId).filter(m =>
    m && (m.role === 'user' || m.role === 'assistant') &&
    typeof m.content === 'string' && m.content.trim().length);

  const userText = String(texto || '').trim() || 'Hola';

  // ── Claude (Anthropic) ──
  // Solo usamos Claude si hay key de Claude. Si eligen un modelo claude-* pero
  // no han pegado la key, NO dejamos morir el bot: respaldo automático a OpenAI.
  let usarClaude = isClaudeModel(config.model);
  if (usarClaude && !config.anthropicKey) {
    console.warn('[Agente] Modelo Claude sin ANTHROPIC_API_KEY → respaldo automático a OpenAI (gpt-4o-mini). Pega la key de Claude en el panel para usarlo.');
    usarClaude = false;
  }
  if (usarClaude) {
    try {
      return await generarConClaude(system, history, userText, { subscriberId, nombre });
    } catch (e) {
      // Si Claude falla, NO dejamos mudo al bot: respaldo a OpenAI si hay key.
      console.error('[Claude] falló →', e.status || '', e.message, config.openaiKey ? '· respaldo a OpenAI' : '· sin OpenAI de respaldo');
      if (!config.openaiKey) throw e;
    }
  }

  // ── OpenAI ──
  // Si el modelo configurado es de Claude (respaldo), usamos un modelo OpenAI válido.
  const openaiModel = isClaudeModel(config.model) ? 'gpt-4o-mini' : config.model;
  const messages = [
    { role: 'system', content: system },
    ...history,
    { role: 'user', content: userText },
  ];

  // Bucle de herramientas (máx. 5 iteraciones)
  for (let i = 0; i < 5; i++) {
    const completion = await chatWithRetry({
      model: openaiModel,
      messages,
      tools: toolDefs,
      tool_choice: 'auto',
    });
    recordUsage(openaiModel, completion.usage?.prompt_tokens || 0, completion.usage?.completion_tokens || 0);

    const msg = completion.choices[0].message;

    if (msg.tool_calls?.length) {
      messages.push(msg);
      for (const call of msg.tool_calls) {
        let args = {};
        try { args = JSON.parse(call.function.arguments || '{}'); } catch {}
        const result = runTool(call.function.name, args, { subscriberId, nombre });
        messages.push({ role: 'tool', tool_call_id: call.id, content: result });
      }
      continue; // vuelve a pedir al modelo con los resultados de las tools
    }

    return parseParts(msg.content || '');
  }

  return [];
}

// Genera la respuesta con Claude (Anthropic Messages API).
async function generarConClaude(system, history, userText, ctx) {
  const client = getAnthropic();
  const messages = [
    ...history.map(m => ({ role: m.role, content: m.content })),
    { role: 'user', content: userText },
  ];

  for (let i = 0; i < 5; i++) {
    let resp;
    try {
      resp = await client.messages.create({
        model: config.model,
        max_tokens: 600,
        // Caché de prompt: el system (prompt + reglas + formato) es idéntico en cada
        // mensaje de la conversación → se cachea y a partir del 2º cuesta ~10%.
        system: [{ type: 'text', text: system, cache_control: { type: 'ephemeral' } }],
        messages,
        tools: toolDefsClaude,
      });
    } catch (e) {
      console.error('[Claude]', e.status || '', e.message);
      throw e;
    }
    // Coste real con caché: lecturas de caché cuestan 0,1x y la escritura 1,25x.
    const u = resp.usage || {};
    const inputEfectivo = (u.input_tokens || 0)
      + (u.cache_creation_input_tokens || 0) * 1.25
      + (u.cache_read_input_tokens || 0) * 0.10;
    recordUsage(config.model, Math.round(inputEfectivo), u.output_tokens || 0);

    if (resp.stop_reason === 'tool_use') {
      messages.push({ role: 'assistant', content: resp.content });
      const results = [];
      for (const block of resp.content) {
        if (block.type === 'tool_use') {
          const out = runTool(block.name, block.input || {}, ctx);
          results.push({ type: 'tool_result', tool_use_id: block.id, content: out });
        }
      }
      messages.push({ role: 'user', content: results });
      continue;
    }

    const text = (resp.content || []).filter(b => b.type === 'text').map(b => b.text).join('\n');
    return parseParts(text);
  }
  return [];
}

// Llama a OpenAI con reintentos ante errores transitorios (429, 5xx, red).
async function chatWithRetry(params, tries = 3) {
  let lastErr;
  for (let i = 0; i < tries; i++) {
    try {
      return await getClient().chat.completions.create(params);
    } catch (e) {
      lastErr = e;
      const status = e?.status;
      // No reintentar errores de cliente no transitorios (400, 401, 403, 404...)
      if (status && status >= 400 && status < 500 && status !== 429) {
        console.error('[OpenAI]', status, e.message);
        break;
      }
      console.warn(`[OpenAI] reintento ${i + 1}/${tries} (${status || e.message})`);
      await new Promise(r => setTimeout(r, 800 * (i + 1)));
    }
  }
  throw lastErr;
}
