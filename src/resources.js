import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const CTAS_FILE = path.join(__dirname, '../data/ctas.json');

// Recursos/enlaces que el agente puede enviar (calendario, formulario, etc.)
// Estructura: { nombre, accionable (palabras clave, separadas por coma), cuando, mensaje, recurso }
export function getResources() {
  try { return JSON.parse(fs.readFileSync(CTAS_FILE, 'utf-8')); } catch { return []; }
}

export function saveResources(list) {
  if (!fs.existsSync(path.dirname(CTAS_FILE))) fs.mkdirSync(path.dirname(CTAS_FILE), { recursive: true });
  fs.writeFileSync(CTAS_FILE, JSON.stringify(list, null, 2));
}

const keywords = r => String(r.accionable || '').split(',').map(s => s.trim().toLowerCase()).filter(Boolean);

// Bloque que se inyecta en el prompt para que el agente sepa qué enviar y cuándo.
export function resourcesBlock() {
  const list = getResources().filter(r => r.recurso || r.accionable);
  if (!list.length) return '';
  const lineas = list.map(r => {
    const nombre = r.nombre || keywords(r)[0] || 'recurso';
    const cuando = r.cuando ? ` Envíalo cuando ${r.cuando}.` : '';
    const msg = r.mensaje ? ` ${r.mensaje}` : '';
    const link = r.recurso ? ` Link a enviar: ${r.recurso}` : '';
    return `- **${nombre}**:${cuando}${msg}${link}`;
  }).join('\n');
  return `\n\n---\n\n# RECURSOS / ENLACES QUE PUEDES ENVIAR
Envía cada enlace SOLO en el momento indicado. NUNCA repitas un enlace ya enviado (salvo que el lead lo pida).
${lineas}`;
}

// Busca un recurso por palabra clave (para la herramienta del agente).
export function findResource(query) {
  const q = String(query || '').toLowerCase().trim();
  if (!q) return null;
  return getResources().find(r =>
    keywords(r).some(k => q.includes(k) || k.includes(q)) ||
    String(r.nombre || '').toLowerCase().includes(q)
  ) || null;
}
