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

export function addMessages(id, name, newMsgs) {
  const all = read(CONV, {});
  const conv = all[id] || { name, messages: [] };
  if (name) conv.name = name;
  // Nunca guardamos mensajes vacíos/ inválidos (rompen las llamadas futuras a OpenAI)
  conv.messages.push(...newMsgs.filter(m => m && (m.role === 'user' || m.role === 'assistant') && String(m.content || '').trim()));
  // Ventana de memoria: conservamos los últimos N mensajes
  if (conv.messages.length > config.memoryWindow) {
    conv.messages = conv.messages.slice(-config.memoryWindow);
  }
  conv.updatedAt = new Date().toISOString();
  all[id] = conv;
  write(CONV, all);
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
  if (b.timer) clearTimeout(b.timer);
  // Tope: aunque el lead siga escribiendo, respondemos como muy tarde a los maxWaitMs.
  const wait = Math.min(waitMs, Math.max(0, maxWaitMs - (Date.now() - b.first)));
  b.timer = setTimeout(() => {
    const joined = b.parts.join(' ').trim();
    buffers.delete(id);
    onFlush(joined);
  }, wait);
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
