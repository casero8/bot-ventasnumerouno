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
  conv.messages.push(...newMsgs);
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

export function bufferMessage(id, text, waitMs, onFlush) {
  let b = buffers.get(id);
  if (!b) { b = { parts: [], timer: null }; buffers.set(id, b); }
  if (text && text.trim()) b.parts.push(text.trim());
  if (b.timer) clearTimeout(b.timer);
  b.timer = setTimeout(() => {
    const joined = b.parts.join(' ').trim();
    buffers.delete(id);
    onFlush(joined);
  }, waitMs);
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
