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

CMD ["npm", "start"]
