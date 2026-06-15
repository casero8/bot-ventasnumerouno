import { config } from './config.js';

const API = 'https://api.manychat.com/fb/sending/sendContent';

// Envía un mensaje de texto a un suscriptor de ManyChat.
// Réplica de los nodos "Enviar Parte1..10" del workflow.
// channel: "instagram" | "whatsapp" | "telegram" (por defecto, el del .env)
export async function sendText(subscriberId, text, channel = config.manychatChannel) {
  // El workflow elimina los signos de apertura ¿ ¡ antes de enviar
  const clean = String(text).replace(/[¿¡]/g, '');
  const body = {
    subscriber_id: Number(subscriberId),
    data: {
      version: 'v2',
      content: {
        type: channel,
        messages: [{ type: 'text', text: clean }],
      },
    },
  };

  const res = await fetch(API, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${config.manychatToken}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(body),
  });

  if (!res.ok) {
    const t = await res.text().catch(() => '');
    console.error(`[ManyChat] Error ${res.status} enviando a ${subscriberId}: ${t}`);
    return false;
  }
  return true;
}

// Obtiene info del suscriptor (réplica de "Obtengo Info de Lead").
export async function getSubscriber(subscriberId) {
  try {
    const res = await fetch(
      `https://api.manychat.com/fb/subscriber/getInfo?subscriber_id=${subscriberId}`,
      { headers: { 'Authorization': `Bearer ${config.manychatToken}` } },
    );
    const json = await res.json();
    return json?.data || null;
  } catch (e) {
    console.error('[ManyChat] getSubscriber:', e.message);
    return null;
  }
}
