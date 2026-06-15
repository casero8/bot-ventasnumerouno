import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const RULES_FILE = path.join(__dirname, '../data/rules.json');

// rules.json: [{ id, text, active, createdAt }]
export function getRules() {
  try { return JSON.parse(fs.readFileSync(RULES_FILE, 'utf-8')); }
  catch { return []; }
}

export function saveRules(rules) {
  if (!fs.existsSync(path.dirname(RULES_FILE))) fs.mkdirSync(path.dirname(RULES_FILE), { recursive: true });
  fs.writeFileSync(RULES_FILE, JSON.stringify(rules, null, 2));
}

// Bloque que se inyecta en el system prompt con los ajustes activos (máxima prioridad).
export function rulesBlock() {
  const active = getRules().filter(r => r.active && r.text?.trim());
  if (!active.length) return '';
  return `

---

# AJUSTES DEL NEGOCIO (PRIORIDAD MÁXIMA)
Instrucciones añadidas por el responsable del negocio. MANDAN sobre cualquier regla anterior si hay conflicto:
${active.map(r => '- ' + r.text.trim()).join('\n')}`;
}
