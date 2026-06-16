import nodemailer from 'nodemailer';
import { config } from './config.js';

let _transport = null;
let _key = null;

function getTransport() {
  if (!config.smtpHost || !config.smtpUser || !config.smtpPass) return null;
  const key = [config.smtpHost, config.smtpPort, config.smtpUser, config.smtpPass].join('|');
  if (!_transport || _key !== key) {
    _transport = nodemailer.createTransport({
      host: config.smtpHost,
      port: config.smtpPort,
      secure: config.smtpPort === 465,
      auth: { user: config.smtpUser, pass: config.smtpPass },
    });
    _key = key;
  }
  return _transport;
}

const esc = s => String(s || '').replace(/[<>&]/g, c => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]));

// Envía el aviso de un lead derivado a NOTIFY_EMAIL.
export async function sendDerivationEmail(lead) {
  const t = getTransport();
  if (!t) {
    console.warn('[email] SMTP no configurado — el derivado se guardó pero no se envió correo. Configura SMTP_* en el .env.');
    return false;
  }
  const fecha = new Date(lead.fecha || Date.now()).toLocaleString('es-ES', { timeZone: 'Europe/Madrid' });
  const histo = (lead.mensajes || [])
    .map(m => `<p style="margin:4px 0"><b>${m.role === 'user' ? 'Lead' : 'Agente'}:</b> ${esc(m.content)}</p>`)
    .join('');

  const html = `
    <div style="font-family:sans-serif;max-width:560px">
      <h2 style="margin:0 0 8px">🔔 Nuevo lead derivado al equipo</h2>
      <p style="color:#555;margin:0 0 16px">El agente ha derivado a un lead. Aquí tienes los datos:</p>
      <table style="border-collapse:collapse;font-size:14px">
        <tr><td style="padding:4px 12px 4px 0;color:#888">Nombre</td><td><b>${esc(lead.name) || '—'}</b></td></tr>
        <tr><td style="padding:4px 12px 4px 0;color:#888">Teléfono</td><td><b>${esc(lead.phone) || '—'}</b></td></tr>
        <tr><td style="padding:4px 12px 4px 0;color:#888">Motivo</td><td>${esc(lead.motivo) || '—'}</td></tr>
        <tr><td style="padding:4px 12px 4px 0;color:#888">ID lead</td><td>${esc(lead.leadId) || '—'}</td></tr>
        <tr><td style="padding:4px 12px 4px 0;color:#888">Fecha</td><td>${fecha}</td></tr>
      </table>
      ${histo ? `<h3 style="margin:18px 0 6px;font-size:14px">Últimos mensajes</h3><div style="font-size:13px;background:#f6f6f8;padding:10px;border-radius:8px">${histo}</div>` : ''}
    </div>`;

  await t.sendMail({
    from: config.smtpFrom || `Agente Instagram <${config.smtpUser}>`,
    to: config.notifyEmail,
    subject: `🔔 Lead derivado: ${lead.name || lead.phone || lead.leadId || 'sin nombre'}`,
    html,
  });
  console.log(`[email] Aviso de derivado enviado a ${config.notifyEmail}`);
  return true;
}

// Envía el informe semanal de aprendizaje a NOTIFY_EMAIL.
export async function sendInsightsEmail({ informe = '', sugerencias = [], resumen = {} } = {}) {
  const t = getTransport();
  if (!t) { console.warn('[email] SMTP no configurado — no se envió el informe semanal.'); return false; }

  const sugHtml = (sugerencias || []).length
    ? `<h3 style="margin:18px 0 6px;font-size:14px">Mejoras propuestas</h3><ul style="font-size:14px;padding-left:18px">${sugerencias.map(s => `<li style="margin:4px 0">${esc(s)}</li>`).join('')}</ul>`
    : '';

  const html = `
    <div style="font-family:sans-serif;max-width:600px">
      <h2 style="margin:0 0 8px">📈 Informe semanal del agente</h2>
      <p style="color:#555;margin:0 0 12px">${esc(resumen.total ?? 0)} conversaciones · ${esc(resumen.agenda ?? 0)} llegaron a agenda · ${esc(resumen.tasa ?? 0)}% de agenda.</p>
      <div style="font-size:14px;background:#f6f6f8;padding:12px;border-radius:8px;white-space:pre-wrap">${esc(informe)}</div>
      ${sugHtml}
      <p style="color:#888;font-size:12px;margin-top:16px">Para aplicar mejoras, entra en el panel → 📈 Aprendizaje y pulsa "Añadir" en las que te convenzan.</p>
    </div>`;

  await t.sendMail({
    from: config.smtpFrom || `Agente Instagram <${config.smtpUser}>`,
    to: config.notifyEmail,
    subject: `📈 Informe semanal del agente (${resumen.agenda ?? 0} agendas / ${resumen.total ?? 0} convs)`,
    html,
  });
  console.log(`[email] Informe semanal enviado a ${config.notifyEmail}`);
  return true;
}
