# Imagen del chatbot (agente instagram)
FROM node:20-alpine

WORKDIR /app

# Instala dependencias primero (mejor cacheo)
COPY package*.json ./
RUN npm install --omit=dev

# Copia el resto del código
COPY . .

ENV NODE_ENV=production
ENV PORT=3000
EXPOSE 3000

# Health check a /health (responde sin login). Evita reinicios por "unhealthy".
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD node -e "fetch('http://127.0.0.1:'+(process.env.PORT||3000)+'/health').then(r=>process.exit(r.ok?0:1)).catch(()=>process.exit(1))"

# IMPORTANTE: arrancamos Node directamente (NO 'npm start').
# Así Node es el proceso principal (PID 1) y recibe SIGTERM → apagado con gracia
# (con 'npm start', npm se comía la señal y cortaba los mensajes de golpe).
CMD ["node", "src/index.js"]
