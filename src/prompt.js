import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const DEFAULT_PROMPT_FILE = path.join(__dirname, '../prompt.md');       // por defecto (viene con la app)
const PROMPT_FILE         = path.join(__dirname, '../data/prompt.md');  // editable y persistido (volumen)

// Si aún no hay prompt editable, lo sembramos desde el por defecto.
function ensurePrompt() {
  if (fs.existsSync(PROMPT_FILE)) return;
  try {
    fs.mkdirSync(path.dirname(PROMPT_FILE), { recursive: true });
    if (fs.existsSync(DEFAULT_PROMPT_FILE)) fs.copyFileSync(DEFAULT_PROMPT_FILE, PROMPT_FILE);
  } catch {}
}

// Lee el prompt SIEMPRE desde disco, así editarlo tiene efecto sin reiniciar.
export function getPrompt() {
  ensurePrompt();
  try { return fs.readFileSync(PROMPT_FILE, 'utf-8'); }
  catch {
    try { return fs.readFileSync(DEFAULT_PROMPT_FILE, 'utf-8'); } catch { return 'Eres un asistente útil.'; }
  }
}

export function savePrompt(text) {
  fs.mkdirSync(path.dirname(PROMPT_FILE), { recursive: true });
  fs.writeFileSync(PROMPT_FILE, text, 'utf-8');
}

// Sustituye las variables dinámicas del workflow n8n por valores reales.
export function renderPrompt(text, { nombre = '', ahora = new Date() } = {}) {
  return text
    .replace(/\{\{\s*\$\('Manychat'\)[^}]*name\s*\}\}/g, nombre || 'el cliente')
    .replace(/\{\{\s*\$now\s*\}\}/g, ahora.toLocaleString('es-ES', { timeZone: 'Europe/Madrid' }))
    // cualquier otra expresión n8n {{ ... }} que quede, se elimina para no confundir al modelo
    .replace(/\{\{[^}]*\}\}/g, '');
}
