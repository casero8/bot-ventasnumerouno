import 'dotenv/config';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const SETTINGS_FILE = path.join(__dirname, '../data/settings.json');

// Valores base desde .env
const base = {
  openaiKey:        process.env.OPENAI_API_KEY || '',
  anthropicKey:     process.env.ANTHROPIC_API_KEY || '',  // para usar modelos Claude
  model:            process.env.OPENAI_MODEL            || 'gpt-5.1',
  visionModel:      process.env.OPENAI_VISION_MODEL     || 'gpt-4o-mini',
  transcribeModel:  process.env.OPENAI_TRANSCRIBE_MODEL || 'whisper-1',

  manychatToken:    process.env.MANYCHAT_TOKEN || '',
  manychatChannel:  process.env.MANYCHAT_CHANNEL        || 'instagram',

  // WhatsApp por CRM a medida: si pones esta URL, los mensajes de WhatsApp
  // se envían con un POST a tu CRM en vez de por ManyChat.
  whatsappOutboundUrl:   process.env.WHATSAPP_OUTBOUND_URL   || '',
  whatsappOutboundToken: process.env.WHATSAPP_OUTBOUND_TOKEN || '',

  port:             parseInt(process.env.PORT || '3000', 10),
  webhookToken:     process.env.WEBHOOK_TOKEN || '',

  // Aviso por email cuando se deriva un lead al equipo
  notifyEmail:  process.env.NOTIFY_EMAIL || 'dcasero8@gmail.com',
  smtpHost:     process.env.SMTP_HOST || '',
  smtpPort:     parseInt(process.env.SMTP_PORT || '465', 10),
  smtpUser:     process.env.SMTP_USER || '',
  smtpPass:     process.env.SMTP_PASS || '',
  smtpFrom:     process.env.SMTP_FROM || '',

  // Ritmo de respuesta (preset): instantaneo | rapido | natural | lento | manual.
  // Si NO es 'manual', manda sobre los tiempos de abajo (buffer, lectura, tipeo…).
  ritmo:            process.env.RITMO || 'natural',

  bufferSeconds:    parseFloat(process.env.BUFFER_SECONDS || '8'),
  secondsPerLetter: parseFloat(process.env.TYPING_SECONDS_PER_LETTER || '0.045'),

  // Comportamiento humano: tiempos de respuesta
  readingMinSeconds: parseFloat(process.env.READING_MIN_SECONDS || '2'),   // "leyendo" antes de escribir
  readingMaxSeconds: parseFloat(process.env.READING_MAX_SECONDS || '5'),
  typingMinSeconds:  parseFloat(process.env.TYPING_MIN_SECONDS  || '1.5'), // mín. por mensaje
  typingMaxSeconds:  parseFloat(process.env.TYPING_MAX_SECONDS  || '12'),  // máx. por mensaje
  partPauseMinSeconds: parseFloat(process.env.PART_PAUSE_MIN_SECONDS || '0.8'), // pausa entre mensajes
  partPauseMaxSeconds: parseFloat(process.env.PART_PAUSE_MAX_SECONDS || '2'),

  // Memoria de conversación: nº de mensajes que recuerda (el workflow usa 20)
  memoryWindow: 20,
};

// Overlay desde data/settings.json (lo que se edita en el panel manda sobre .env)
function readSettingsFile() {
  try { return JSON.parse(fs.readFileSync(SETTINGS_FILE, 'utf-8')); }
  catch { return {}; }
}

export const config = { ...base, ...readSettingsFile() };

// Campos que se pueden editar desde el panel (NO el puerto, requiere reinicio)
export const EDITABLE = [
  'openaiKey', 'anthropicKey', 'model', 'visionModel', 'transcribeModel',
  'manychatToken', 'manychatChannel',
  'whatsappOutboundUrl', 'whatsappOutboundToken',
  'webhookToken', 'ritmo', 'bufferSeconds', 'secondsPerLetter', 'memoryWindow',
  'readingMinSeconds', 'readingMaxSeconds', 'typingMinSeconds', 'typingMaxSeconds',
  'partPauseMinSeconds', 'partPauseMaxSeconds',
];

// Campos que se enmascaran al mostrarlos (no se devuelven en claro)
export const SECRETS = ['openaiKey', 'anthropicKey', 'manychatToken', 'whatsappOutboundToken'];

// Guarda cambios: persiste en settings.json y los aplica en caliente
export function updateConfig(patch) {
  const numeric = new Set(['bufferSeconds', 'secondsPerLetter', 'memoryWindow',
    'readingMinSeconds', 'readingMaxSeconds', 'typingMinSeconds', 'typingMaxSeconds',
    'partPauseMinSeconds', 'partPauseMaxSeconds']);
  const clean = {};
  for (const k of EDITABLE) {
    if (patch[k] === undefined) continue;
    clean[k] = numeric.has(k) ? parseFloat(patch[k]) : String(patch[k]);
    config[k] = clean[k]; // aplicar en caliente
  }
  const current = readSettingsFile();
  const merged = { ...current, ...clean };
  if (!fs.existsSync(path.dirname(SETTINGS_FILE))) fs.mkdirSync(path.dirname(SETTINGS_FILE), { recursive: true });
  fs.writeFileSync(SETTINGS_FILE, JSON.stringify(merged, null, 2));
  return getConfigSafe();
}

// Devuelve la config editable (los secretos se enmascaran)
export function getConfigSafe() {
  const out = {};
  for (const k of EDITABLE) out[k] = config[k];
  for (const k of SECRETS) if (out[k]) out[k] = mask(out[k]);
  return out;
}

function mask(v) { return v.length > 10 ? v.slice(0, 5) + '…' + v.slice(-4) : '••••'; }

if (!config.openaiKey) {
  console.warn('⚠️  Falta OPENAI_API_KEY (puedes ponerla desde el panel en Configuración).');
}
