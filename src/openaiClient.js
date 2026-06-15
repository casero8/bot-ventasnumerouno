import OpenAI from 'openai';
import { config } from './config.js';

let _client = null;
let _key = null;

// Devuelve un cliente de OpenAI usando la API key ACTUAL.
// Si la key cambia desde el panel, se reconstruye automáticamente.
export function getClient() {
  if (!_client || _key !== config.openaiKey) {
    // timeout para que una llamada lenta nunca congele al bot; reintentos los hacemos nosotros
    _client = new OpenAI({ apiKey: config.openaiKey, timeout: 60000, maxRetries: 0 });
    _key = config.openaiKey;
  }
  return _client;
}
