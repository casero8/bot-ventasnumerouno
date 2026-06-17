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
import { generarRespuesta, generarSeguimiento, analizarConversaciones } from './agent.js';
import { getAnthropic, isClaudeModel } from './anthropicClient.js';
import { deliver } from './delivery.js';
import { processMedia, isUrl } from './media.js';
import {
  bufferMessage, addMessages, resetConversation,
  allConversations, getStats, bumpStat, getUsage, flushAllBuffers,
  followupCandidates, recordFollowup, outcomesSummary, marcarCerrada,
  getInsightsMeta, setInsightsMeta,
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
app.get('/api/usage', (_req, res) => res.json(getUsage()));

// ── Reportes: métricas agregadas para el panel ──
app.get('/api/reportes', (_req, res) => {
  const stats = getStats();
  const dias = Object.keys(stats).filter(k => k !== '__total__').sort().slice(-14);
  const serie = dias.map(d => ({ dia: d, ...stats[d] }));
  res.json({
    agenda:    outcomesSummary(),
    derivados: getDerivados().length,
    gasto:     getUsage(),
    total:     stats.__total__ || {},
    dias:      serie,
  });
});

// ── Aprendizaje: resumen de resultados + análisis con IA ──
app.get('/api/insights/resumen', (_req, res) => res.json(outcomesSummary()));
app.get('/api/insights/ultimo', (_req, res) => res.json(getInsightsMeta().lastReport || null));
app.post('/api/insights', async (_req, res) => {
  try {
    const resumen = outcomesSummary();
    const r = await analizarConversaciones();
    const saved = { ...r, resumen, fecha: new Date().toISOString() };
    setInsightsMeta({ lastAt: saved.fecha, lastReport: saved }); // queda guardado en la app
    res.json(saved);
  } catch (e) { console.error('[insights]', e.message); res.status(500).json({ error: e.message }); }
});

// Diagnóstico: hace una llamada mínima a Claude y devuelve el error EXACTO si falla.
app.get('/api/diag/claude', async (_req, res) => {
  if (!config.anthropicKey) return res.json({ ok: false, error: 'No hay API key de Claude puesta en el panel.' });
  const model = isClaudeModel(config.model) ? config.model : 'claude-haiku-4-5';
  try {
    const r = await getAnthropic().messages.create({
      model, max_tokens: 16,
      messages: [{ role: 'user', content: 'Responde solo: hola' }],
    });
    const texto = (r.content || []).filter(b => b.type === 'text').map(b => b.text).join(' ').trim();
    res.json({ ok: true, model, texto });
  } catch (e) {
    res.json({ ok: false, model, status: e.status || null, tipo: e.error?.error?.type || e.name || '', error: e.message });
  }
});
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

  // Comando para reiniciar la conversación (escribiendo "/reset" por el chat)
  if (String(rawText).trim().toLowerCase() === '/reset') {
    resetConversation(id);
    await deliver(channel, id, '🔄 Conversación reiniciada. Empezamos de cero.');
    console.log(`♻️  Conversación reiniciada para ${id}`);
    return;
  }

  // Si llega media (audio/imagen como URL), la convertimos a texto primero
  let text = rawText;
  if (isUrl(rawText)) {
    try { text = await processMedia(rawText); }
    catch (e) { console.error('[media]', e.message); text = ''; }
  }
  if (!text.trim()) return;

  // Buffer: agrupamos mensajes seguidos antes de responder una sola vez
  bufferMessage(id, text, T().buffer * 1000, (joined) => runResponder(id, name, joined, channel));
}

// Envíos en curso: los rastreamos para terminarlos antes de apagar (evita cortes en reinicios).
const inflight = new Set();
function runResponder(id, name, joined, channel) {
  const p = (async () => {
    try { await responder(id, name, joined, channel); }
    catch (e) { console.error('[responder]', e); }
  })();
  inflight.add(p);
  p.finally(() => inflight.delete(p));
  return p;
}

async function responder(id, name, joined, channel) {
  console.log(`💬 [${channel}·${name || id}] → ${joined}`);
  let parts;
  try {
    parts = await generarRespuesta(id, name, joined);
  } catch (e) {
    console.error(`[agente] Error generando respuesta para ${id}:`, e.status || '', e.message);
    parts = [];
  }
  // Nunca dejamos al lead colgado: si no hay respuesta, mandamos un mensaje de cortesía
  // (así la conversación no se "queda parada" aunque falle el modelo).
  if (!parts.length) {
    console.warn(`[agente] Sin respuesta (0 partes) para ${id} → envío mensaje de cortesía`);
    await deliver(channel, id, 'Perdona, se me cruzaron los cables un segundo 😅 ¿me lo repites?');
    return;
  }

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
  ], channel);
}

// ─────────── Seguimiento a leads "en visto" ───────────
// Planes: minutos (desde el último msg del lead) para cada toque. 'off' = desactivado.
const FOLLOWUP_PLANS = {
  off:        [],
  suave:      [240],            // 1 toque (~4h)
  normal:     [180, 1200],      // 2 toques (3h y 20h)
  insistente: [60, 360, 1320],  // 3 toques (1h, 6h, 22h)
};
function followupDelays() {
  return FOLLOWUP_PLANS[config.followupPlan] || FOLLOWUP_PLANS.normal;
}

async function tickFollowups() {
  const delays = followupDelays();
  if (!delays.length) return; // plan 'off'
  let cands;
  try { cands = followupCandidates(Date.now(), delays); }
  catch (e) { console.error('[seguimiento]', e.message); return; }
  if (!cands.length) return;

  const derivados = new Set(getDerivados().map(d => String(d.leadId)));
  for (const c of cands) {
    if (c.id === TEST_ID) continue;            // no tocar el banco de pruebas
    if (derivados.has(String(c.id))) continue; // no molestar a leads ya derivados
    try {
      const r = await generarSeguimiento(c.id, c.name);
      if (r.terminada) {                       // la IA detecta que la conversación ya acabó
        marcarCerrada(c.id);
        console.log(`🔕 Conversación cerrada (no se sigue): ${c.name || c.id}`);
        continue;
      }
      const parts = r.parts || [];
      if (!parts.length) continue;
      // Espera de "tipeo" ligera y envío
      for (let i = 0; i < parts.length; i++) {
        await sleep(typingDelay(parts[i]));
        const ok = await deliver(c.channel, c.id, parts[i]);
        if (ok) bumpStat('mensajes_salientes');
      }
      recordFollowup(c.id, parts.join('\n'));
      bumpStat('seguimientos');
      console.log(`🔁 Seguimiento #${c.sent + 1} → ${c.name || c.id}: ${parts.join(' ')}`);
    } catch (e) {
      console.error('[seguimiento]', c.id, e.status || '', e.message);
    }
  }
}

const rand = (min, max) => (min + Math.random() * Math.max(0, max - min)) * 1000;

// Presets de ritmo. Cada uno define: buffer, lectura, tipeo (por letra y topes) y pausa.
const RITMOS = {
  instantaneo: { buffer: 0.5, readMin: 0,   readMax: 0,   perLetter: 0,     typeMin: 0,   typeMax: 0,  pauseMin: 0.2, pauseMax: 0.4 },
  rapido:      { buffer: 2,   readMin: 0.4, readMax: 1.2, perLetter: 0.02,  typeMin: 0.4, typeMax: 3,  pauseMin: 0.3, pauseMax: 0.7 },
  natural:     { buffer: 3,   readMin: 0.8, readMax: 2.2, perLetter: 0.03,  typeMin: 0.7, typeMax: 5,  pauseMin: 0.5, pauseMax: 1.1 },
  lento:       { buffer: 6,   readMin: 2,   readMax: 5,   perLetter: 0.045, typeMin: 1.5, typeMax: 10, pauseMin: 0.8, pauseMax: 2 },
};

// Tiempos efectivos: si hay preset (ritmo != 'manual') manda; si no, los campos manuales.
function T() {
  const r = config.ritmo && config.ritmo !== 'manual' ? RITMOS[config.ritmo] : null;
  if (r) return r;
  return {
    buffer: config.bufferSeconds,
    readMin: config.readingMinSeconds, readMax: config.readingMaxSeconds,
    perLetter: config.secondsPerLetter, typeMin: config.typingMinSeconds, typeMax: config.typingMaxSeconds,
    pauseMin: config.partPauseMinSeconds, pauseMax: config.partPauseMaxSeconds,
  };
}

// Pausa de lectura antes de la primera respuesta
function readingDelay() {
  const t = T();
  return rand(t.readMin, t.readMax);
}

// Pausa entre mensajes consecutivos
function betweenPartsDelay() {
  const t = T();
  return rand(t.pauseMin, t.pauseMax);
}

// Tiempo de "tipeo" por parte: nº de letras * segundos/letra (+ algo de variación)
function typingDelay(text) {
  const t = T();
  const letters = (text || '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜçÇ]/g, '').length;
  const base = letters * t.perLetter * (0.85 + Math.random() * 0.3);
  const ms = base * 1000;
  return Math.min(Math.max(ms, t.typeMin * 1000), t.typeMax * 1000);
}

export function startServer() {
  const server = app.listen(config.port, () => {
    console.log(`\n✅ Agente Instagram activo en http://localhost:${config.port}`);
    console.log(`   • Webhook ManyChat:  POST http://localhost:${config.port}/webhook/manychat`);
    console.log(`   • Editar el prompt:  http://localhost:${config.port}/\n`);
  });

  // Apagado con gracia: al recibir SIGTERM (reinicio/deploy de EasyPanel) NO cortamos
  // de golpe — vaciamos buffers y terminamos los envíos en curso antes de salir.
  let cerrando = false;
  const shutdown = async (sig) => {
    if (cerrando) return; cerrando = true;
    console.log(`\n🛑 ${sig} recibido — apagando con gracia (${inflight.size} en curso). Terminando envíos…`);
    server.close();
    try { flushAllBuffers(); } catch {}
    await Promise.race([
      Promise.allSettled([...inflight]),
      new Promise(r => setTimeout(r, 9000)),   // como mucho 9s (dentro del margen de Docker)
    ]);
    console.log('✅ Apagado limpio.');
    process.exit(0);
  };
  process.on('SIGTERM', () => shutdown('SIGTERM'));
  process.on('SIGINT',  () => shutdown('SIGINT'));

  // Vigilante de seguimientos: revisa cada 5 min los leads "en visto" (sin solaparse).
  let running = false;
  setInterval(async () => {
    if (running || cerrando) return;
    running = true;
    try { await tickFollowups(); } catch (e) { console.error('[seguimiento tick]', e.message); }
    running = false;
  }, 5 * 60 * 1000);

  // Informe semanal de aprendizaje por email: revisa cada 6h si toca (persistente).
  setTimeout(() => tickWeeklyInsights().catch(e => console.error('[insights semanal]', e.message)), 30 * 1000);
  setInterval(() => { if (!cerrando) tickWeeklyInsights().catch(e => console.error('[insights semanal]', e.message)); }, 6 * 60 * 60 * 1000);
}

// Genera el informe semanal de aprendizaje y lo guarda EN LA APP (se ve en el panel).
async function tickWeeklyInsights() {
  const meta = getInsightsMeta();
  // Primera vez: dejamos el contador en marcha desde ahora (no generamos de golpe).
  if (!meta.lastAt) { setInsightsMeta({ lastAt: new Date().toISOString() }); return; }
  if (Date.now() - Date.parse(meta.lastAt) < 7 * 24 * 60 * 60 * 1000) return;

  const resumen = outcomesSummary();
  const r = await analizarConversaciones();
  const fecha = new Date().toISOString();
  setInsightsMeta({ lastAt: fecha, lastReport: { ...r, resumen, fecha } });
  console.log('📈 Informe semanal de aprendizaje generado (visible en el panel)');
}
