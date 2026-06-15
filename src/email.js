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
