import { getClient } from './openaiClient.js';
import { config } from './config.js';
import { getSetup } from './setup.js';

// pdf-parse: importamos la lib interna para evitar el bloque de test del index
import pdfParse from 'pdf-parse/lib/pdf-parse.js';

const MAX_CHARS = 60000; // límite de texto que mandamos al modelo

// Extrae texto de un archivo subido (PDF, TXT, MD, JSON...).
export async function extractText(buffer, filename = '') {
  const name = filename.toLowerCase();
  if (name.endsWith('.pdf')) {
    const data = await pdfParse(buffer);
    return data.text || '';
  }
  // texto plano por defecto
  return buffer.toString('utf-8');
}

// Forma del setup que pedimos al modelo (guía para el JSON).
const ESQUEMA = `{
  "agente": { "nombre": "", "habla_como": "experto|equipo", "idioma": "", "tono": "", "max_palabras": 25, "emojis": "" },
  "negocio": { "marca": "", "experto": "", "que_vende": "", "credenciales": [] },
  "oferta": { "una_linea": "", "mecanismo": "", "promesa": "", "condicion_minima": "", "puntos_clave": [], "no_mencionar_de_entrada": [] },
  "segmentos": [ { "nombre": "", "detectar_si": "", "criterios_encaje": [], "preguntas": [], "cta_palabra": "", "como_cerrar": "" } ],
  "red_flags": [],
  "mensaje_no_encaje": "",
  "filtro_pais": { "activo": false, "paises_validos": [], "mensaje_rechazo": "" },
  "precio": { "dar_por_chat": false, "guion": [] },
  "derivacion": { "contacto_nombre": "", "pedir_telefono": true, "cuando": [] },
  "reglas_extra": []
}`;

const SYSTEM = `Eres un asistente que configura un agente "setter" de ventas por chat a partir de la documentación de un negocio.
Lee la documentación y RELLENA esta estructura JSON con la información del negocio:
${ESQUEMA}

Reglas:
- Devuelve ÚNICAMENTE el objeto JSON, sin texto alrededor.
- Usa el idioma de la documentación (por defecto, español de España).
- Si un dato no aparece, déjalo vacío ("" o []), NO te lo inventes con datos falsos.
- Para "segmentos": identifica los tipos de cliente del negocio. Por cada uno, escribe 3-6 "preguntas" de diagnóstico naturales (una idea por pregunta) y sus "criterios_encaje". "cta_palabra" es una palabra clave del recurso/link que se le envía (ej: "agenda", "formulario").
- "habla_como": "experto" si el agente habla en primera persona como el dueño; "equipo" si habla como su equipo.
- Infiere "red_flags" (a quién NO encaja) y "reglas_extra" si la documentación lo sugiere.`;

// Convierte el texto de la documentación en un objeto setup.
export async function docsToSetup(text) {
  const recorte = text.slice(0, MAX_CHARS);
  const completion = await getClient().chat.completions.create({
    model: config.model,
    messages: [
      { role: 'system', content: SYSTEM },
      { role: 'user', content: `DOCUMENTACIÓN DEL NEGOCIO:\n\n${recorte}` },
    ],
    response_format: { type: 'json_object' },
  });

  const raw = completion.choices[0]?.message?.content || '{}';
  let parsed;
  try { parsed = JSON.parse(raw); }
  catch { parsed = JSON.parse(raw.slice(raw.indexOf('{'), raw.lastIndexOf('}') + 1)); }

  // Mezcla con el setup actual: lo nuevo rellena, pero no borra lo que ya había.
  return mergeSetup(getSetup(), parsed);
}

// Merge superficial inteligente: usa el valor nuevo si tiene contenido; si no, conserva el viejo.
function mergeSetup(base = {}, nuevo = {}) {
  const out = { ...base };
  for (const [k, v] of Object.entries(nuevo)) {
    if (Array.isArray(v)) {
      out[k] = v.length ? v : (base[k] || []);
    } else if (v && typeof v === 'object') {
      out[k] = mergeSetup(base[k] || {}, v);
    } else if (v !== '' && v != null) {
      out[k] = v;
    } else if (!(k in out)) {
      out[k] = v;
    }
  }
  return out;
}
