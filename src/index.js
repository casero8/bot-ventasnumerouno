import { startServer } from './server.js';

// Que un error suelto NUNCA tire el proceso (evita reinicios que cortan conversaciones)
process.on('unhandledRejection', (e) => console.error('[unhandledRejection]', e?.message || e));
process.on('uncaughtException',  (e) => console.error('[uncaughtException]',  e?.message || e));

startServer();
