import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { config } from './config.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const DATA = p => path.join(__dirname, '../data', p);

function read(file, def) {
  try { return fs.existsSync(DATA(file)) ? JSON.parse(fs.readFileSync(DATA(file), 'utf-8')) : def; }
  catch { return def; }
}
function write(file, data) {
  if (!fs.existsSync(DATA(''))) fs.mkdirSync(DATA(''), { recursive: true });
  fs.writeFileSync(DATA(file), JSON.stringify(data, null, 2));
}

/* ───────────────── Memoria de conversación (equivale a Postgres Chat Memory) ─────────────────
   conversations.json:  { [subscriberId]: { name, messages: [{role, content}], updatedAt } }            */
const CONV = 'conversations.json';

export function getHistory(id) {
  const all = read(CONV, {});
  return all[id]?.messages || [];
}

export function addMessages(id, name, newMsgs, channel) {
  const all = read(CONV, {});
  const conv = all[id] || { name, messages: [] };
  if (name) conv.name = name;
  if (channel) conv.channel = channel;
  // Nunca guardamos mensajes vacíos/ inválidos (rompen las llamadas futuras a OpenAI)
  const valid = newMsgs.filter(m => m && (m.role === 'user' || m.role === 'assistant') && String(m.content || '').trim());
  conv.messages.push(...valid);
  // Ventana de memoria: conservamos los últimos N mensajes
  if (conv.messages.length > config.memoryWindow) {
    conv.messages = conv.messages.slice(-config.memoryWindow);
  }
  conv.updatedAt = new Date().toISOString();
  // Seguimiento: si el lead ha escrito en este turno, reseteamos el contador de seguimientos
  if (valid.some(m => m.role === 'user')) {
    conv.lastLeadAt = conv.updatedAt;
    conv.followupsSent = 0;
  }
  const last = conv.messages[conv.messages.length - 1];
  conv.lastRole = last ? last.role : conv.lastRole;
  // Resultado: si el bot envió un enlace (agenda/formulario), marcamos la conversación como "agenda".
  if (valid.some(m => m.role === 'assistant' && /https?:\/\//i.test(m.content || ''))) {
    conv.outcome = 'agenda';
    conv.agendaAt = conv.updatedAt;
  }
  all[id] = conv;
  write(CONV, all);
}

// Resumen de resultados para el módulo de aprendizaje.
export function outcomesSummary() {
  const all = read(CONV, {});
  let total = 0, agenda = 0;
  for (const [id, c] of Object.entries(all)) {
    if (id === '__test__' || !Array.isArray(c.messages) || !c.messages.length) continue;
    total++;
    if (c.outcome === 'agenda') agenda++;
  }
  return { total, agenda, sinAgenda: total - agenda, tasa: total ? Math.round((agenda / total) * 100) : 0 };
}

// Registra un mensaje de seguimiento (lo envía el bot, sin que el lead haya escrito).
export function recordFollowup(id, text) {
  const all = read(CONV, {});
  const conv = all[id];
  if (!conv) return;
  conv.messages.push({ role: 'assistant', content: text });
  if (conv.messages.length > config.memoryWindow) conv.messages = conv.messages.slice(-config.memoryWindow);
  conv.followupsSent = (conv.followupsSent || 0) + 1;
  conv.lastFollowupAt = new Date().toISOString();
  conv.updatedAt = conv.lastFollowupAt;
  conv.lastRole = 'assistant';
  all[id] = conv;
  write(CONV, all);
}

// Devuelve los leads "en visto" a los que toca mandar seguimiento ahora.
// delaysMin: array de minutos desde el último msg del lead (ej. [180, 1200]).
export function followupCandidates(nowMs, delaysMin) {
  const all = read(CONV, {});
  const out = [];
  const WINDOW_MS = 24 * 60 * 60 * 1000; // ventana de 24h de Instagram
  for (const [id, conv] of Object.entries(all)) {
    if (!conv || conv.lastRole !== 'assistant') continue;        // el bot debe haber hablado el último
    const sent = conv.followupsSent || 0;
    if (sent >= delaysMin.length) continue;                       // ya mandados todos los seguimientos
    if (!conv.lastLeadAt) continue;
    const leadMs = Date.parse(conv.lastLeadAt);
    if (isNaN(leadMs)) continue;
    if (nowMs - leadMs >= WINDOW_MS) continue;                    // fuera de la ventana de 24h
    const dueMs = leadMs + delaysMin[sent] * 60 * 1000;
    if (nowMs < dueMs) continue;                                  // aún no toca
    // No reenganchar a leads cerrados por país (no-España)
    const lastTxt = String(conv.messages[conv.messages.length - 1]?.content || '');
    if (/trabajando con gente en Espa/i.test(lastTxt)) continue;
    out.push({ id, name: conv.name || '', channel: conv.channel || 'instagram', sent });
  }
  return out;
}

export function resetConversation(id) {
  const all = read(CONV, {});
  delete all[id];
  write(CONV, all);
}

export function getConversation(id) {
  return read(CONV, {})[id] || null;
}
export function allConversations() {
  return read(CONV, {});
}

/* ───────────────── Buffer de mensajes (equivale a la tabla muchos_msj) ─────────────────
   Agrupa varios mensajes seguidos del mismo lead antes de responder una sola vez.        */
const buffers = new Map(); // id -> { parts:[], timer, resolve }

export function bufferMessage(id, text, waitMs, onFlush, maxWaitMs = 20000) {
  let b = buffers.get(id);
  if (!b) { b = { parts: [], timer: null, first: Date.now() }; buffers.set(id, b); }
  if (text && text.trim()) b.parts.push(text.trim());
  b.onFlush = onFlush;                       // guardado para poder vaciar al cerrar
  if (b.timer) clearTimeout(b.timer);
  // Tope: aunque el lead siga escribiendo, respondemos como muy tarde a los maxWaitMs.
  const wait = Math.min(waitMs, Math.max(0, maxWaitMs - (Date.now() - b.first)));
  b.timer = setTimeout(() => {
    const joined = b.parts.join(' ').trim();
    buffers.delete(id);
    onFlush(joined);
  }, wait);
}

// Vacía YA todos los buffers pendientes (al apagar el servidor con gracia).
// Devuelve las promesas de los onFlush para poder esperarlas.
export function flushAllBuffers() {
  const pend = [];
  for (const [id, b] of [...buffers]) {
    if (b.timer) clearTimeout(b.timer);
    buffers.delete(id);
    const joined = b.parts.join(' ').trim();
    if (joined && b.onFlush) { try { pend.push(b.onFlush(joined)); } catch {} }
  }
  return pend;
}

/* ───────────────── Estadísticas diarias (equivale a agent_daily_stats) ───────────────── */
const STATS = 'stats.json';
const today = () => new Date().toISOString().slice(0, 10);

export function bumpStat(field, n = 1) {
  const all = read(STATS, {});
  const d = all[today()] || {};
  d[field] = (d[field] || 0) + n;
  all[today()] = d;
  write(STATS, all);
}
export function getStats() { return read(STATS, {}); }

/* ───────────────── Gasto de IA (coste aproximado por tokens) ─────────────────
   Precios por 1M de tokens (entrada/salida). Claude: oficiales. OpenAI: aprox.   */
const PRICES = {
  'gpt-5.1':            { in: 1.25, out: 10 },   // aprox. (consulta OpenAI)
  'gpt-4o':             { in: 2.5,  out: 10 },    // aprox.
  'gpt-4o-mini':        { in: 0.15, out: 0.6 },   // aprox.
  'claude-haiku-4-5':   { in: 1,    out: 5 },
  'claude-sonnet-4-6':  { in: 3,    out: 15 },
  'claude-opus-4-8':    { in: 5,    out: 25 },
};

// Registra el uso de tokens y suma el coste aproximado (hoy + total acumulado).
export function recordUsage(model, inTok = 0, outTok = 0) {
  const p = PRICES[model] || { in: 0, out: 0 };
  const cost = (inTok / 1e6) * p.in + (outTok / 1e6) * p.out;
  const all = read(STATS, {});
  for (const key of [today(), '__total__']) {
    const d = all[key] || {};
    d.tokens_in  = (d.tokens_in  || 0) + inTok;
    d.tokens_out = (d.tokens_out || 0) + outTok;
    d.costo_usd  = (d.costo_usd  || 0) + cost;
    all[key] = d;
  }
  write(STATS, all);
}

export function getUsage() {
  const all = read(STATS, {});
  const blank = { tokens_in: 0, tokens_out: 0, costo_usd: 0 };
  return { hoy: all[today()] || blank, total: all.__total__ || blank };
}
