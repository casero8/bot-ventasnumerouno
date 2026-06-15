import { config } from './config.js';
import { sendText as manychatSend } from './manychat.js';

/**
 * Entrega un mensaje al lead por el canal correcto.
 * - instagram / telegram  → API de ManyChat
 * - whatsapp              → tu CRM a medida (si WHATSAPP_OUTBOUND_URL está configurado);
 *                           si no, también por ManyChat.
 */
export async function deliver(channel, to, text) {
  if (channel === 'whatsapp' && config.whatsappOutboundUrl) {
    return sendToCrm(to, text, channel);
  }
  return manychatSend(to, text, channel);
}

// Envía el mensaje a tu CRM con un POST sencillo: { to, text, channel }.
// Tu CRM solo tiene que recibir esto y mandar el WhatsApp.
async function sendToCrm(to, text, channel) {
  const clean = String(text).replace(/[¿¡]/g, '');
  const headers = { 'Content-Type': 'application/json' };
  if (config.whatsappOutboundToken) headers['Authorization'] = `Bearer ${config.whatsappOutboundToken}`;
  try {
    const res = await fetch(config.whatsappOutboundUrl, {
      method: 'POST', headers, body: JSON.stringify({ to, text: clean, channel }),
    });
    if (!res.ok) {
      console.error(`[CRM] Error ${res.status} enviando a ${to}: ${await res.text().catch(() => '')}`);
      return false;
    }
    return true;
  } catch (e) {
    console.error('[CRM] outbound:', e.message);
    return false;
  }
}
