import { config } from './config.js';
import { getClient } from './openaiClient.js';
import { getAnthropic, isClaudeModel } from './anthropicClient.js';
import { getPrompt, renderPrompt } from './prompt.js';
import { rulesBlock } from './rules.js';
import { getHistory, recordUsage, allConversations } from './store.js';
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
Usa solo las parts que necesites y deja el resto como cadena vacía "". Usa COMO MUCHO 2 parts (máximo 2 mensajes seguidos); condensa, no sueltes 3 o más. No escribas variables ni placeholders.`;

// Nivel de emojis (se inyecta en el prompt y MANDA sobre lo que diga el prompt).
const EMOJI_NIVELES = {
  ninguno:   'No uses NUNCA emojis. Cero emojis en tus mensajes.',
  pocos:     'Emojis casi nunca: la mayoría de mensajes SIN emoji; alguno muy de vez en cuando solo si aporta.',
  medio:     'Emojis con moderación: alguno ocasional cuando encaje, pero no en todos los mensajes.',
  bastantes: 'Usa emojis con bastante frecuencia: aproximadamente un emoji en la mayoría de mensajes, sin pasarte.',
  muchos:    'Usa muchos emojis y expresivos: casi todos los mensajes con uno o dos emojis, tono muy desenfadado.',
};
function emojiBlock() {
  const txt = EMOJI_NIVELES[config.emojiNivel] || EMOJI_NIVELES.pocos;
  return `\n\n# USO DE EMOJIS (manda sobre cualquier otra indicación de emojis del prompt)\n${txt}`;
}

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

// Garantiza alternancia user/assistant (Claude la EXIGE): fusiona mensajes
// consecutivos del mismo rol y quita los "assistant" del principio.
// Evita que 2 seguimientos seguidos dejen la conversación bloqueada (error 400).
function alternar(list) {
  const out = [];
  for (const m of list) {
    if (!m || !String(m.content || '').trim()) continue;
    if (out.length && out[out.length - 1].role === m.role) {
      out[out.length - 1].content += '\n' + m.content;
    } else {
      out.push({ role: m.role, content: m.content });
    }
  }
  while (out.length && out[0].role === 'assistant') out.shift();
  return out;
}

/**
 * Procesa un mensaje entrante y devuelve un array de partes a enviar.
 * @param {string} subscriberId  id del suscriptor en ManyChat (clave de memoria)
 * @param {string} nombre        nombre del lead
 * @param {string} texto         mensaje ya agregado/transcrito
 */
export async function generarRespuesta(subscriberId, nombre, texto) {
  const system = renderPrompt(getPrompt(), { nombre }) + rulesBlock() + emojiBlock() + FORMATO;

  // Saneamos el historial: solo mensajes válidos (evita que una entrada corrupta
  // rompa TODAS las llamadas siguientes y el bot deje de contestar).
  const history = getHistory(subscriberId).filter(m =>
    m && (m.role === 'user' || m.role === 'assistant') &&
    typeof m.content === 'string' && m.content.trim().length);

  const userText = String(texto || '').trim() || 'Hola';
  // Conversación con alternancia garantizada (historial + mensaje nuevo del lead)
  const convo = alternar([...history, { role: 'user', content: userText }]);

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
      return await generarConClaude(system, convo, { subscriberId, nombre });
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
    ...convo,
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

/**
 * Genera un mensaje de SEGUIMIENTO para un lead que dejó "en visto".
 * Usa el historial + una instrucción para escribir un toque natural y corto.
 */
export async function generarSeguimiento(subscriberId, nombre) {
  const sys = renderPrompt(getPrompt(), { nombre }) + rulesBlock() +
`

# TAREA AHORA: ¿SEGUIMIENTO O YA TERMINÓ?
El lead no ha contestado a tu último mensaje. Decide con sentido común:
1) Si la conversación YA HA TERMINADO y no debe continuar —despedida, un "gracias" final, dijo que no le interesa o que lo verá más adelante, ya se le envió el enlace/agenda, ya cerró, o no hay nada natural que retomar— responde ÚNICAMENTE con esta palabra, sin nada más: TERMINADA
2) Si de verdad tiene sentido retomar (se quedó a medias cualificando) → responde ÚNICAMENTE con un JSON { "response": { "part_1": "mensaje corto, natural y cercano", "part_2": "" } }, sin reenviar links, sin agobiar, enganchando con una pregunta ligera sobre lo último que hablasteis.
Responde SOLO con la palabra TERMINADA o SOLO con el JSON. Ante la duda, prefiere TERMINADA (no molestar).` + emojiBlock();

  const history = getHistory(subscriberId).filter(m =>
    m && (m.role === 'user' || m.role === 'assistant') &&
    typeof m.content === 'string' && m.content.trim().length);

  const trigger = '(El lead sigue sin responder. ¿Toca seguimiento o ya terminó? Responde TERMINADA o el JSON.)';
  const convo = alternar([...history, { role: 'user', content: trigger }]);

  let raw = '';
  // Claude si hay key; si falla, respaldo a OpenAI
  if (isClaudeModel(config.model) && config.anthropicKey) {
    try {
      const resp = await getAnthropic().messages.create({
        model: config.model, max_tokens: 300, system: sys,
        messages: convo,
      });
      recordUsage(config.model, resp.usage?.input_tokens || 0, resp.usage?.output_tokens || 0);
      raw = (resp.content || []).filter(b => b.type === 'text').map(b => b.text).join('\n');
    } catch (e) { console.error('[seguimiento][Claude]', e.status || '', e.message); if (!config.openaiKey) return { terminada: false, parts: [] }; }
  }
  if (!raw) {
    const model = isClaudeModel(config.model) ? 'gpt-4o-mini' : config.model;
    try {
      const completion = await chatWithRetry({ model, messages: [{ role: 'system', content: sys }, ...convo] });
      recordUsage(model, completion.usage?.prompt_tokens || 0, completion.usage?.completion_tokens || 0);
      raw = completion.choices[0].message.content || '';
    } catch (e) { console.error('[seguimiento][OpenAI]', e.status || '', e.message); return { terminada: false, parts: [] }; }
  }

  // Si dice TERMINADA (y no es un JSON) → conversación cerrada, no se sigue.
  if (/\bTERMINADA\b/i.test(raw) && !raw.includes('{')) return { terminada: true, parts: [] };
  return { terminada: false, parts: parseParts(raw) };
}

/**
 * Analiza las conversaciones (comparando las que AGENDARON vs las que no) y
 * devuelve un informe + sugerencias accionables para mejorar el agente.
 * @returns {Promise<{informe:string, sugerencias:string[]}>}
 */
export async function analizarConversaciones() {
  const all = allConversations();
  const convos = Object.entries(all)
    .filter(([id, c]) => id !== '__test__' && Array.isArray(c.messages) && c.messages.length >= 2);

  if (convos.length < 3) {
    return { informe: 'Aún hay muy pocas conversaciones para analizar. Vuelve cuando tengas más actividad (mínimo 3).', sugerencias: [] };
  }

  const agendadas   = convos.filter(([, c]) => c.outcome === 'agenda');
  const noAgendadas = convos.filter(([, c]) => c.outcome !== 'agenda');

  const fmt = ([, c]) => {
    const msgs = (c.messages || []).slice(-12)
      .map(m => (m.role === 'user' ? 'LEAD' : 'DAVID') + ': ' + String(m.content || '').replace(/\s+/g, ' ').slice(0, 200))
      .join('\n');
    return `--- Conversación (${c.outcome === 'agenda' ? 'AGENDÓ' : 'NO agendó'}) ---\n${msgs}`;
  };
  const muestraA = agendadas.slice(-12).map(fmt).join('\n\n');
  const muestraN = noAgendadas.slice(-12).map(fmt).join('\n\n');

  const sys = `Eres un analista experto en ventas y closing por chat. Analizas conversaciones de un setter (David) que cualifica leads en Instagram y los lleva a agendar una llamada o rellenar un formulario.
Compara las conversaciones que ACABARON EN AGENDA con las que NO, y detecta patrones accionables y reales (no genéricos).
Responde ÚNICAMENTE con un JSON válido, sin texto antes ni después, con esta forma:
{
  "informe": "español, claro y breve, en viñetas con guiones: qué está funcionando en las que agendan, dónde y por qué se caen las que no, objeciones que se repiten, y qué camino conviene reforzar",
  "sugerencias": ["mejora concreta, corta y lista para pegar como instrucción al bot", "otra", "..."]
}
Las sugerencias: frases cortas, accionables, máximo 6.`;

  const user = `CONVERSACIONES QUE AGENDARON (${agendadas.length}):\n${muestraA || '(ninguna todavía)'}\n\n========\n\nCONVERSACIONES QUE NO AGENDARON (${noAgendadas.length}):\n${muestraN || '(ninguna)'}\n\nAnaliza y responde con el JSON.`;

  let raw = '';
  if (isClaudeModel(config.model) && config.anthropicKey) {
    try {
      const resp = await getAnthropic().messages.create({
        model: config.model, max_tokens: 1500,
        system: sys, messages: [{ role: 'user', content: user }],
      });
      recordUsage(config.model, resp.usage?.input_tokens || 0, resp.usage?.output_tokens || 0);
      raw = (resp.content || []).filter(b => b.type === 'text').map(b => b.text).join('\n');
    } catch (e) { console.error('[insights][Claude]', e.status || '', e.message); }
  }
  if (!raw) {
    const model = isClaudeModel(config.model) ? 'gpt-4o-mini' : config.model;
    const completion = await chatWithRetry({ model, messages: [{ role: 'system', content: sys }, { role: 'user', content: user }] });
    recordUsage(model, completion.usage?.prompt_tokens || 0, completion.usage?.completion_tokens || 0);
    raw = completion.choices[0].message.content || '';
  }

  try {
    const j = JSON.parse(raw.slice(raw.indexOf('{'), raw.lastIndexOf('}') + 1));
    return {
      informe: String(j.informe || '').trim() || 'Sin informe.',
      sugerencias: Array.isArray(j.sugerencias) ? j.sugerencias.map(String).map(s => s.trim()).filter(Boolean).slice(0, 6) : [],
    };
  } catch {
    return { informe: raw.trim() || 'No se pudo generar el análisis.', sugerencias: [] };
  }
}

// Genera la respuesta con Claude (Anthropic Messages API).
// `convo` ya viene alternado (user/assistant) desde generarRespuesta.
async function generarConClaude(system, convo, ctx) {
  const client = getAnthropic();
  const messages = [...convo];

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
