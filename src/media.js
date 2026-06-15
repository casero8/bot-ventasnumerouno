import { config } from './config.js';
import { getClient } from './openaiClient.js';

// ¿El texto entrante es realmente una URL de media? (ManyChat manda audios/imágenes como URL)
export function isUrl(text) {
  return typeof text === 'string' && /^https?:\/\/\S+$/i.test(text.trim());
}

function looksLikeImage(url) {
  return /\.(jpg|jpeg|png|webp|gif)(\?|$)/i.test(url) || /image|photo|cdninstagram/i.test(url);
}

// Transcribe un audio desde una URL (réplica de "Obtener Audio" + "Transcribe a recording").
export async function transcribeAudio(url) {
  const audioRes = await fetch(url);
  if (!audioRes.ok) throw new Error('No se pudo descargar el audio');
  const arrayBuf = await audioRes.arrayBuffer();
  const file = new File([arrayBuf], 'audio.ogg', { type: 'audio/ogg' });
  const tr = await getClient().audio.transcriptions.create({
    file,
    model: config.transcribeModel,
    language: 'es',
  });
  return corregirTranscripcion(tr.text || '');
}

// Describe una imagen desde una URL (réplica de "Obtener Imagen" + "Analizar Imagen").
export async function describeImage(url) {
  const res = await getClient().chat.completions.create({
    model: config.visionModel,
    messages: [{
      role: 'user',
      content: [
        { type: 'text', text: 'Describe en español, de forma breve y útil, qué muestra esta imagen que ha enviado un lead por Instagram.' },
        { type: 'image_url', image_url: { url } },
      ],
    }],
  });
  return `[El lead envió una imagen] ${res.choices[0]?.message?.content || ''}`.trim();
}

// Procesa media entrante: decide si es audio o imagen y devuelve texto utilizable.
export async function processMedia(url) {
  if (looksLikeImage(url)) return describeImage(url);
  try { return await transcribeAudio(url); }
  catch { return describeImage(url); } // fallback si no era audio
}

// Correcciones básicas de transcripciones (réplica del nodo Code13).
const corrections = {
  ola: 'hola', komo: 'como', ke: 'que', q: 'que', xq: 'porque', tb: 'también',
  tmb: 'también', bn: 'bien', dnd: 'donde', pq: 'porque', porke: 'porque',
  aora: 'ahora', ay: 'hay', osea: 'o sea', nose: 'no sé', talvez: 'tal vez',
  asta: 'hasta', ablar: 'hablar', acer: 'hacer',
};
function corregirTranscripcion(text) {
  return text
    .split(/\b/)
    .map(tok => corrections[tok.toLowerCase()] ? tok.replace(tok, corrections[tok.toLowerCase()]) : tok)
    .join('')
    .replace(/(.)\1{3,}/g, '$1$1')   // sin repeticiones excesivas
    .replace(/\s{2,}/g, ' ')
    .trim();
}
