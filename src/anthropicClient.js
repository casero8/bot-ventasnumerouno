import Anthropic from '@anthropic-ai/sdk';
import { config } from './config.js';

let _client = null;
let _key = null;

// Cliente de Claude (Anthropic) usando la API key ACTUAL; se reconstruye si cambia.
export function getAnthropic() {
  if (!_client || _key !== config.anthropicKey) {
    _client = new Anthropic({ apiKey: config.anthropicKey, timeout: 60000 });
    _key = config.anthropicKey;
  }
  return _client;
}

// ¿El modelo configurado es de Claude? (los ids de Claude empiezan por "claude")
export function isClaudeModel(model) {
  return /^claude/i.test(String(model || ''));
}
