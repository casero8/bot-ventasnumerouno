import { config } from './config.js';
import { getClient } from './openaiClient.js';
import { getAnthropic, isClaudeModel } from './anthropicClient.js';
import { recordUsage } from './store.js';

// ¿El texto entrante es realmente una URL de media? (ManyChat manda audios/imágenes como URL)
export function isUrl(text) {
  return typeof text === 'string' && /^https?:\/\/\S+$/i.test(text.trim());
}

function looksLikeImage(url) {
  return /\.(jpg|jpeg|png|webp|gif)(\?|$)/i.test(url) || /image|photo|cdninstagram/i.test(url);
}

// ¿Usamos Claude para la visión? (modelo claude-* + key de Claude presente)
function visionUsaClaude() {
  return isClaudeModel(config.model) && !!config.anthropicKey;
}

const PROMPT_IMG = 'Describe en español, de forma breve y útil, qué muestra esta imagen que ha enviado un lead por Instagram.';

// Transcribe un audio desde una URL.
// OJO: Claude no transcribe audio. Si no hay key de OpenAI, devolvemos un aviso
// para que el agente pida el mensaje por texto en vez de romperse.
export async function transcribeAudio(url) {
  if (!config.openaiKey) {
    return '[El lead envió un audio de voz, pero no se pudo transcribir automáticamente. Pídele con amabilidad que te lo escriba por texto.]';
  }
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

// Describe una imagen desde una URL. Usa Claude o OpenAI según el proveedor activo.
export async function describeImage(url) {
  const texto = visionUsaClaude() ? await describeImageClaude(url) : await describeImageOpenAI(url);
  return `[El lead envió una imagen] ${texto}`.trim();
}

// Visión con OpenAI (gpt-4o-mini por defecto).
async function describeImageOpenAI(url) {
  const res = await getClient().chat.completions.create({
    model: config.visionModel,
    messages: [{
      role: 'user',
      content: [
        { type: 'text', text: PROMPT_IMG },
        { type: 'image_url', image_url: { url } },
      ],
    }],
  });
  recordUsage(config.visionModel, res.usage?.prompt_tokens || 0, res.usage?.completion_tokens || 0);
  return res.choices[0]?.message?.content || '';
}

// Visión con Claude (usa el mismo modelo de chat; todos los Claude actuales ven imágenes).
async function describeImageClaude(url) {
  const model = isClaudeModel(config.model) ? config.model : 'claude-haiku-4-5';
  const resp = await getAnthropic().messages.create({
    model,
    max_tokens: 300,
    messages: [{
      role: 'user',
      content: [
        { type: 'image', source: { type: 'url', url } },
        { type: 'text', text: PROMPT_IMG },
      ],
    }],
  });
  recordUsage(model, resp.usage?.input_tokens || 0, resp.usage?.output_tokens || 0);
  return (resp.content || []).filter(b => b.type === 'text').map(b => b.text).join(' ').trim();
}

// Procesa media entrante: decide si es audio o imagen y devuelve texto utilizable.
export async function processMedia(url) {
  try {
    if (looksLikeImage(url)) return await describeImage(url);
    try { return await transcribeAudio(url); }
    catch { return await describeImage(url); } // si no era audio, intentamos como imagen
  } catch (e) {
    console.error('[media]', e.status || '', e.message);
    return '[El lead envió un archivo (foto o audio) que no se pudo procesar. Pídele que te lo cuente por texto.]';
  }
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
