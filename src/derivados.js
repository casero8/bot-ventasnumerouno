import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const FILE = path.join(__dirname, '../data/derivados.json');

function read() {
  try { return JSON.parse(fs.readFileSync(FILE, 'utf-8')); } catch { return []; }
}
function write(list) {
  if (!fs.existsSync(path.dirname(FILE))) fs.mkdirSync(path.dirname(FILE), { recursive: true });
  fs.writeFileSync(FILE, JSON.stringify(list, null, 2));
}

export function getDerivados() { return read(); }

// Añade un lead derivado al principio de la lista
export function addDerivado(lead) {
  const list = read();
  const item = {
    id: Math.random().toString(36).slice(2, 10),
    estado: 'nuevo',
    fecha: new Date().toISOString(),
    ...lead,
  };
  list.unshift(item);
  write(list);
  return item;
}

// Actualiza un derivado (p. ej. marcarlo atendido)
export function updateDerivado(id, patch) {
  const list = read();
  const i = list.findIndex(d => d.id === id);
  if (i < 0) return null;
  list[i] = { ...list[i], ...patch };
  write(list);
  return list[i];
}

export function deleteDerivado(id) {
  write(read().filter(d => d.id !== id));
}
