import OpenAI from 'openai';
import { config } from './config.js';

let _client = null;
let _key = null;

// Devuelve un cliente de OpenAI usando la API key ACTUAL.
// Si la key cambia desde el panel, se reconstruye automáticamente.
export function getClient() {
  if (!_client || _key !== config.openaiKey) {
    _client = new OpenAI({ apiKey: config.openaiKey });
    _key = config.openaiKey;
  }
  return _client;
}
