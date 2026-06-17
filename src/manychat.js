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

  // Reintentos ante fallos transitorios (timeout, red, 5xx) para no perder el mensaje.
  for (let intento = 1; intento <= 3; intento++) {
    try {
      const res = await fetch(API, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${config.manychatToken}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(body),
        signal: AbortSignal.timeout(15000),
      });
      if (res.ok) return true;
      const t = await res.text().catch(() => '');
      // 4xx (subscriber inválido, fuera de ventana 24h, token…) → no se arregla reintentando
      if (res.status >= 400 && res.status < 500) {
        console.error(`[ManyChat] Error ${res.status} enviando a ${subscriberId}: ${t}`);
        return false;
      }
      console.warn(`[ManyChat] ${res.status} (intento ${intento}/3) a ${subscriberId}: ${t}`);
    } catch (e) {
      console.warn(`[ManyChat] fallo de red (intento ${intento}/3) a ${subscriberId}: ${e.message}`);
    }
    await new Promise(r => setTimeout(r, 700 * intento));
  }
  console.error(`[ManyChat] No se pudo enviar a ${subscriberId} tras 3 intentos.`);
  return false;
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
