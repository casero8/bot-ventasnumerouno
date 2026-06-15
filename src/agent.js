import { config } from './config.js';
import { getClient } from './openaiClient.js';
import { getPrompt, renderPrompt } from './prompt.js';
import { rulesBlock } from './rules.js';
import { resourcesBlock } from './resources.js';
import { getHistory } from './store.js';
import { toolDefs, runTool } from './tools.js';

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
  const system = renderPrompt(getPrompt(), { nombre }) + resourcesBlock() + rulesBlock() + FORMATO;

  const messages = [
    { role: 'system', content: system },
    ...getHistory(subscriberId),
    { role: 'user', content: texto },
  ];

  // Bucle de herramientas (máx. 5 iteraciones)
  for (let i = 0; i < 5; i++) {
    const completion = await getClient().chat.completions.create({
      model: config.model,
      messages,
      tools: toolDefs,
      tool_choice: 'auto',
    });

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
