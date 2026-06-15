import express from 'express';
import multer from 'multer';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';
import { config, getConfigSafe, updateConfig, SECRETS } from './config.js';
import { getPrompt, savePrompt } from './prompt.js';
import { getSetup, saveSetup, buildPrompt, generarPromptDesdeSetup } from './setup.js';
import { extractText, docsToSetup } from './ingest.js';
import { getRules, saveRules } from './rules.js';
import { getDerivados, updateDerivado, deleteDerivado } from './derivados.js';
import { generarRespuesta } from './agent.js';
import { deliver } from './delivery.js';
import { processMedia, isUrl } from './media.js';
import {
  bufferMessage, addMessages, resetConversation,
  allConversations, getStats, bumpStat,
} from './store.js';

const __dirname  = path.dirname(fileURLToPath(import.meta.url));
const PUBLIC_DIR  = path.join(__dirname, '../public');
const CTAS_FILE   = path.join(__dirname, '../data/ctas.json');
const sleep = ms => new Promise(r => setTimeout(r, ms));

const app = express();
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));

/* ───────────────── Login del panel (Basic Auth) ─────────────────
   Protege el panel y la API. El webhook de ManyChat y /health quedan
   abiertos. Si no defines ADMIN_PASS, el panel queda SIN protección
   (úsalo solo en local; en un dominio público define ADMIN_PASS).      */
const ADMIN_USER = process.env.ADMIN_USER || 'admin';
const ADMIN_PASS = process.env.ADMIN_PASS || '';
if (!ADMIN_PASS) console.warn('⚠️  Panel SIN contraseña. Define ADMIN_PASS en el .env para protegerlo en producción.');

app.use((req, res, next) => {
  // Rutas públicas: ManyChat llama al webhook; health para el hosting
  if (req.path.startsWith('/webhook/') || req.path === '/health') return next();
  if (!ADMIN_PASS) return next();
  const [scheme, encoded] = (req.get('authorization') || '').split(' ');
  if (scheme === 'Basic' && encoded) {
    const [u, p] = Buffer.from(encoded, 'base64').toString().split(':');
    if (u === ADMIN_USER && p === ADMIN_PASS) return next();
  }
  res.set('WWW-Authenticate', 'Basic realm="Panel Agente"').status(401).send('Autenticación requerida');
});

/* ───────────────── Panel para editar el prompt ───────────────── */
app.use(express.static(PUBLIC_DIR));
app.get('/', (_req, res) => res.sendFile(path.join(PUBLIC_DIR, 'admin.html')));
app.get('/api/prompt', (_req, res) => res.json({ prompt: getPrompt() }));
app.post('/api/prompt', (req, res) => {
  if (typeof req.body.prompt !== 'string') return res.status(400).json({ error: 'prompt requerido' });
  savePrompt(req.body.prompt);
  res.json({ ok: true });
});
// ── Subir documentación → rellenar el agente con IA ──
const upload = multer({ storage: multer.memoryStorage(), limits: { fileSize: 25 * 1024 * 1024 } });
app.post('/api/setup/from-docs', upload.array('files', 10), async (req, res) => {
  try {
    const files = req.files || [];
    if (!files.length) return res.status(400).json({ error: 'Sube al menos un archivo' });
    let texto = '';
    for (const f of files) {
      try { texto += `\n\n===== ${f.originalname} =====\n` + await extractText(f.buffer, f.originalname); }
      catch (e) { console.error('[ingest]', f.originalname, e.message); }
    }
    if (!texto.trim()) return res.status(422).json({ error: 'No se pudo extraer texto de los archivos (¿PDF escaneado/imagen?)' });
    const setup = await docsToSetup(texto);
    res.json({ ok: true, setup, chars: texto.length });
  } catch (e) {
    console.error('[from-docs]', e);
    res.status(500).json({ error: e.message });
  }
});

// ── Configuración del negocio (setup → genera el prompt) ──
app.get('/api/setup', (_req, res) => res.json(getSetup()));
app.post('/api/setup', (req, res) => {
  if (typeof req.body !== 'object' || Array.isArray(req.body)) return res.status(400).json({ error: 'setup inválido' });
  saveSetup(req.body);
  res.json({ ok: true });
});
// Vista previa del prompt generado sin guardarlo
app.post('/api/setup/preview', (req, res) => res.json({ prompt: buildPrompt(req.body || getSetup()) }));
// Genera el prompt desde el setup recibido (o el guardado) y lo escribe en prompt.md
app.post('/api/setup/generate', (req, res) => {
  const setup = (req.body && Object.keys(req.body).length) ? req.body : getSetup();
  if (req.body && Object.keys(req.body).length) saveSetup(setup);
  const prompt = generarPromptDesdeSetup(setup);
  res.json({ ok: true, prompt });
});

// ── Configuración (editable desde el panel) ──
app.get('/api/config', (_req, res) => res.json(getConfigSafe()));
app.post('/api/config', (req, res) => {
  // No machacamos un secreto si llega enmascarado (contiene "…") o vacío
  const patch = { ...req.body };
  for (const k of SECRETS) {
    if (typeof patch[k] === 'string' && (patch[k].includes('…') || patch[k] === '')) delete patch[k];
  }
  res.json(updateConfig(patch));
});

// ── CTAs / recursos que envía el agente ──
app.get('/api/ctas', (_req, res) => {
  try { res.json(JSON.parse(fs.readFileSync(CTAS_FILE, 'utf-8'))); }
  catch { res.json([]); }
});
app.post('/api/ctas', (req, res) => {
  if (!Array.isArray(req.body)) return res.status(400).json({ error: 'se espera un array' });
  fs.writeFileSync(CTAS_FILE, JSON.stringify(req.body, null, 2));
  res.json({ ok: true });
});

// ── Ajustes rápidos (reglas en lenguaje natural, prioridad máxima) ──
app.get('/api/rules', (_req, res) => res.json(getRules()));
app.post('/api/rules', (req, res) => {
  if (!Array.isArray(req.body)) return res.status(400).json({ error: 'se espera un array' });
  saveRules(req.body);
  res.json({ ok: true });
});

// ── Banco de pruebas: habla con el agente sin pasar por ManyChat ──
const TEST_ID = '__test__';
app.post('/api/test', async (req, res) => {
  const text = String(req.body?.text || '').trim();
  if (!text) return res.status(400).json({ error: 'texto requerido' });
  try {
    const parts = await generarRespuesta(TEST_ID, req.body?.name || 'Lead de prueba', text);
    addMessages(TEST_ID, 'Lead de prueba', [
      { role: 'user', content: text },
      { role: 'assistant', content: parts.join('\n') },
    ]);
    res.json({ parts });
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
});
app.post('/api/test/reset', (_req, res) => { resetConversation(TEST_ID); res.json({ ok: true }); });

// ── Leads derivados al equipo ──
app.get('/api/derivados', (_req, res) => res.json(getDerivados()));
app.post('/api/derivados/:id', (req, res) => { updateDerivado(req.params.id, req.body || {}); res.json({ ok: true }); });
app.delete('/api/derivados/:id', (req, res) => { deleteDerivado(req.params.id); res.json({ ok: true }); });

app.get('/api/stats', (_req, res) => res.json(getStats()));
app.get('/api/conversations', (_req, res) => res.json(allConversations()));
app.post('/api/reset/:id', (req, res) => { resetConversation(req.params.id); res.json({ ok: true }); });
app.get('/health', (_req, res) => res.json({ ok: true }));

/* ───────────────── Webhook de ManyChat (réplica del nodo "Manychat") ─────────────────
   ManyChat (External Request / Dynamic Block) hace POST aquí con el mensaje del lead.   */
app.post('/webhook/manychat', (req, res) => {
  // Auth por cabecera (equivale al headerAuth del webhook n8n)
  if (config.webhookToken && req.get('x-webhook-token') !== config.webhookToken) {
    return res.status(401).json({ error: 'no autorizado' });
  }

  const body = req.body?.body || req.body || {};
  const id      = body.id ?? req.body.id;
  const name    = body.name ?? req.body.name ?? '';
  const text    = body.last_input_text ?? req.body.last_input_text ?? body.text ?? '';
  const channel = body.channel ?? req.body.channel ?? config.manychatChannel;

  if (!id) return res.status(400).json({ error: 'falta id del suscriptor' });

  // Respondemos rápido a ManyChat; el procesamiento sigue en segundo plano.
  res.json({ ok: true });

  handleIncoming(String(id), String(name), String(text), String(channel)).catch(e =>
    console.error('[handleIncoming]', e));
});

async function handleIncoming(id, name, rawText, channel) {
  bumpStat('mensajes_entrantes');

  // Si llega media (audio/imagen como URL), la convertimos a texto primero
  let text = rawText;
  if (isUrl(rawText)) {
    try { text = await processMedia(rawText); }
    catch (e) { console.error('[media]', e.message); text = ''; }
  }
  if (!text.trim()) return;

  // Buffer: agrupamos mensajes seguidos antes de responder una sola vez
  bufferMessage(id, text, config.bufferSeconds * 1000, async (joined) => {
    try {
      await responder(id, name, joined, channel);
    } catch (e) {
      console.error('[responder]', e);
    }
  });
}

// Serializa las respuestas por usuario: nunca dos a la vez para el mismo lead
// (evita respuestas solapadas que corromperían el historial).
const userLocks = new Map();
async function responder(id, name, joined, channel) {
  const prev = userLocks.get(id) || Promise.resolve();
  const run = prev.catch(() => {}).then(() => responderInner(id, name, joined, channel));
  userLocks.set(id, run);
  try { await run; }
  finally { if (userLocks.get(id) === run) userLocks.delete(id); }
}

async function responderInner(id, name, joined, channel) {
  console.log(`💬 [${channel}·${name || id}] → ${joined}`);
  let parts;
  try {
    parts = await generarRespuesta(id, name, joined);
  } catch (e) {
    console.error(`[agente] Error generando respuesta para ${id}:`, e.status || '', e.message);
    return;
  }
  if (!parts.length) { console.warn(`[agente] Sin respuesta (0 partes) para ${id}`); return; }

  // 1) Pausa de "lectura" antes de empezar a responder (como una persona real)
  await sleep(readingDelay());

  for (let i = 0; i < parts.length; i++) {
    const part = parts[i];
    // 2) Tiempo de "escritura" proporcional a la longitud del mensaje
    await sleep(typingDelay(part));
    const ok = await deliver(channel, id, part);
    if (ok) bumpStat('mensajes_salientes');
    console.log(`🤖 [${name || id}] ← ${part}`);
    // 3) Pausa natural entre un mensaje y el siguiente
    if (i < parts.length - 1) await sleep(betweenPartsDelay());
  }

  // Guardamos en memoria el turno completo
  addMessages(id, name, [
    { role: 'user', content: joined },
    { role: 'assistant', content: parts.join('\n') },
  ]);
}

const rand = (min, max) => (min + Math.random() * Math.max(0, max - min)) * 1000;

// Pausa de lectura antes de la primera respuesta
function readingDelay() {
  return rand(config.readingMinSeconds, config.readingMaxSeconds);
}

// Pausa entre mensajes consecutivos
function betweenPartsDelay() {
  return rand(config.partPauseMinSeconds, config.partPauseMaxSeconds);
}

// Tiempo de "tipeo" por parte: nº de letras * segundos/letra (+ algo de variación)
function typingDelay(text) {
  const letters = (text || '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜçÇ]/g, '').length;
  const base = letters * config.secondsPerLetter * (0.85 + Math.random() * 0.3);
  const ms = base * 1000;
  return Math.min(Math.max(ms, config.typingMinSeconds * 1000), config.typingMaxSeconds * 1000);
}

export function startServer() {
  app.listen(config.port, () => {
    console.log(`\n✅ Agente Instagram activo en http://localhost:${config.port}`);
    console.log(`   • Webhook ManyChat:  POST http://localhost:${config.port}/webhook/manychat`);
    console.log(`   • Editar el prompt:  http://localhost:${config.port}/\n`);
  });
}
