import { bumpStat, getHistory } from './store.js';
import { addDerivado } from './derivados.js';
import { sendDerivationEmail } from './email.js';
import { findResource } from './resources.js';

// Definición de herramientas que el agente puede llamar (function calling).
// Réplica de los tool-workflows "Buscar Recursos por CTA" y "Derivar".
export const toolDefs = [
  {
    type: 'function',
    function: {
      name: 'derivar',
      description: 'Llama esta herramienta cuando un lead solicite un plan personalizado, sea un closer buscando trabajo, tenga problemas de agenda, o mande mensajes idénticos repetidos. Primero obtén su número de teléfono/móvil. Comunica que el Director Comercial le va a escribir.',
      parameters: {
        type: 'object',
        properties: {
          telefono: { type: 'string', description: 'Teléfono o móvil del lead' },
          motivo:   { type: 'string', description: 'Motivo breve de la derivación' },
        },
        required: ['telefono'],
      },
    },
  },
];

// Mismas herramientas en formato Anthropic (Claude usa input_schema en vez de parameters).
export const toolDefsClaude = toolDefs.map(t => ({
  name: t.function.name,
  description: t.function.description,
  input_schema: t.function.parameters,
}));

// Ejecuta la herramienta y devuelve un string (lo que el modelo recibe como tool result).
export function runTool(name, args, ctx = {}) {
  if (name === 'buscar_recurso_por_cta') {
    const hit = findResource(args.palabra);
    if (!hit) return JSON.stringify({ encontrado: false, mensaje: 'No hay un recurso para eso. No envíes ningún link.' });
    bumpStat('ctas_enviados');
    return JSON.stringify({
      encontrado: true,
      nombre: hit.nombre || '',
      cuando: hit.cuando || '',
      instrucciones: hit.mensaje || hit.instrucciones || '',
      recurso: hit.recurso || '',
    });
  }

  if (name === 'derivar') {
    bumpStat('derivaciones');
    // Guardamos el lead derivado y avisamos por email (sin bloquear la respuesta)
    const lead = addDerivado({
      leadId: ctx.subscriberId || null,
      name:   ctx.nombre || '',
      phone:  args.telefono || '',
      motivo: args.motivo || '',
      mensajes: ctx.subscriberId ? getHistory(ctx.subscriberId).slice(-6) : [],
    });
    sendDerivationEmail(lead).catch(e => console.error('[email] derivar:', e.message));
    return JSON.stringify({
      ok: true,
      instrucciones: 'Confirma al lead que el equipo le va a escribir en breve. No des largas charlas.',
      telefono_registrado: args.telefono || null,
    });
  }

  return JSON.stringify({ error: 'herramienta_desconocida' });
}
