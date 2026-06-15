# Agente Instagram · Setter (réplica del workflow n8n "Setter")

App en Node.js que replica el workflow de n8n **Setter** como código interno. Funciona junto con **ManyChat**, que es quien recibe los mensajes de Instagram y "manda las órdenes" llamando a este webhook. Tú **solo tienes que cambiar el prompt**.

## Qué hace (igual que el workflow original)

1. **Recibe** el mensaje del lead desde ManyChat (`POST /webhook/manychat`).
2. Si es **audio** lo transcribe (Whisper); si es **imagen** la describe (visión).
3. **Agrupa** los mensajes seguidos del lead antes de responder (buffer, como la tabla `muchos_msj`).
4. Llama al **agente IA** (modelo `gpt-5.1`) con tu prompt y **memoria** de la conversación (últimos 20 mensajes).
5. El agente puede usar **herramientas**: `buscar_recurso_por_cta` y `derivar` (los tool-workflows originales).
6. Divide la respuesta en **partes** (`part_1`…`part_10`) y **envía cada una** por la API de ManyChat con un retardo de tipeo realista (nº de letras × 0,030 s).
7. Lleva **estadísticas** diarias (entrantes, salientes, CTAs…).

## Puesta en marcha

```bash
cd "agente instagram"
npm install
npm start
```

- El `.env` ya viene con tu `OPENAI_API_KEY` y el token de ManyChat.
- Abre **http://localhost:3000** para editar el prompt.

## Crear el agente de cada infoproductor (configuración inicial)

Abre **http://localhost:3000** → sección **🏢 Configuración del negocio**.

### Opción rápida: subir la documentación (PDF) y que la IA rellene el agente
Arriba de la sección hay un **cargador de documentos**: arrastra los PDF/TXT del negocio (oferta, web, notas, guion…) y pulsa **⚡ Rellenar agente con los documentos**. La IA lee todo y **rellena automáticamente** el formulario (negocio, oferta, segmentos, preguntas de cualificación, etc.). Luego revisas, ajustas lo que quieras y pulsas **Generar agente**.

- Acepta varios archivos a la vez (PDF, TXT, MD).
- PDFs escaneados (imágenes sin texto) no se pueden leer; usa un PDF con texto real.
- Necesita una `OPENAI_API_KEY` válida.

### Opción manual: rellenar a mano
Cada infoproductor rellena su setup:

- **Datos del agente:** nombre, idioma, tono, si habla como el experto o como el equipo.
- **Negocio:** marca, experto, qué vende, credenciales.
- **Oferta:** una línea, mecanismo, promesa, condición mínima, puntos clave.
- **Segmentos:** cada tipo de lead con sus criterios de encaje, sus preguntas de diagnóstico y la palabra del CTA que se le envía.
- **Filtros:** red flags, filtro por país, manejo del precio.
- **Derivación:** a quién pasa el lead y cuándo.

Al pulsar **⚡ Generar agente**, el prompt del agente se crea automáticamente desde esa configuración. La estructura de "motor" (anti-bucle, no repetir links, no dar clases, formato de mensajes en partes, uso de herramientas) viene incluida y es reutilizable; solo cambia lo del negocio.

- El setup se guarda en `setup.json` (parte de un ejemplo en [`setup.example.json`](setup.example.json) con los datos de David ya rellenos).
- Para empezar un negocio nuevo: cambia los campos y pulsa Generar.

## Ajustar el agente sobre la marcha (sin tocar el prompt)

A medida que ves conversaciones reales, afina el agente desde el panel:

- **🛠️ Ajustes rápidos:** escribe la corrección en lenguaje normal (ej: *"cuando pregunten el precio, no des rangos"*, *"sé más directo en el primer mensaje"*). Se añade una línea, se aplica con **prioridad máxima** y al instante, sin editar el prompt. Puedes activar/desactivar o borrar cada ajuste.
- **🧪 Probar el agente:** escríbele como si fueras un lead y ves la respuesta al momento (no se envía a nadie). Ideal para probar cada ajuste antes de dejarlo activo.

Flujo recomendado: ves un fallo en una conversación → añades un ajuste rápido → lo pruebas en el banco de pruebas → si va bien, lo dejas activo.

## Agendar una llamada / que el lead haga una acción

El agente no gestiona la agenda: **envía un link** y el lead reserva en tu calendario (es lo más robusto y lo que hacía el workflow original).

1. Crea un calendario de reservas (**Cal.com** es gratis y simple, o tu **GoHighLevel / LeadConnector**).
2. En **🔗 CTAs**, pon ese link en la palabra `agenda` (y el formulario en `formulario`).
3. En cada **segmento**, el campo *"Cómo cerrar"* le dice al agente cuándo proponer la llamada. El agente: cualifica → pide permiso ("¿te va bien una llamada?") → envía el link → confirma que ha reservado.
4. El calendario se encarga de recordatorios y confirmaciones.

> Para cualquier otra acción (rellenar formulario, pagar, descargar algo) es el mismo mecanismo: un CTA con su link e instrucciones.

## Cambiar el prompt a mano (avanzado)

- En el panel, despliega **📝 Prompt del agente** y edita; Guardar con Cmd/Ctrl+S.
- O edita [`prompt.md`](prompt.md) directamente.

> El prompt cargado de fábrica es el del workflow `Setter` (David Casero / Método VentasNúmero1), ya completo. Si pulsas "Generar agente", se sustituye por el generado desde la configuración.

## Conectar ManyChat (Instagram y WhatsApp)

**Cómo funciona:** alguien escribe → ManyChat llama a tu webhook → la app piensa la respuesta y **la envía ella misma** al lead por la API de ManyChat. ManyChat solo dispara el webhook; no tiene que esperar ni mostrar nada.

### 1. Pon la app en internet
ManyChat está en la nube y no puede llamar a `localhost`. Opciones:
- **Probar:** `ngrok http 3000` → te da una URL pública `https://xxxx.ngrok.app`.
- **Definitivo:** despliega la app en tu servidor (EasyPanel, un VPS…). Tu webhook será `https://TU-DOMINIO/webhook/manychat`.

### 2. Token de ManyChat
En ManyChat: **Settings → API** → copia el token → ponlo en el panel (⚙️ Configuración técnica) o en `.env` (`MANYCHAT_TOKEN`).

### 3. Conecta los canales
En ManyChat conecta tu **Instagram** y tu **WhatsApp** (es nativo de ManyChat).

### 4. Crea el flujo (uno por canal)
Automation → New Automation. **Trigger:** *Default Reply* (para que responda a todo) o un Keyword.
**Acción:** añade un paso **External Request** (función Pro de ManyChat):
- **Method:** `POST`
- **URL:** `https://TU-DOMINIO/webhook/manychat`
- **Headers:** `Content-Type: application/json` (opcional: `x-webhook-token: <WEBHOOK_TOKEN>`)
- **Body (JSON) — flujo de Instagram:**
  ```json
  { "id": "{{user_id}}", "name": "{{first_name}}", "last_input_text": "{{last_input_text}}", "channel": "instagram" }
  ```
- **Body (JSON) — flujo de WhatsApp:** igual pero `"channel": "whatsapp"`.

No hay que "mapear la respuesta": la app envía los mensajes sola.

### WhatsApp por tu CRM a medida (en vez de ManyChat)
Si WhatsApp lo gestionas en tu propio CRM, hay dos enganches:
1. **Entrante:** tu CRM, cuando llega un WhatsApp, hace `POST https://TU-DOMINIO/webhook/manychat` con:
   ```json
   { "id": "<id_del_contacto>", "name": "<nombre>", "last_input_text": "<texto>", "channel": "whatsapp" }
   ```
2. **Saliente:** en el panel (⚙️ Configuración técnica) pon la **URL del endpoint de envío de tu CRM**. La app le hará `POST` por cada parte de la respuesta:
   ```json
   { "to": "<id_del_contacto>", "text": "<mensaje>", "channel": "whatsapp" }
   ```
   Tu CRM solo tiene que recibir eso y enviar el WhatsApp. (Opcional: token Bearer.)

Así: **Instagram → ManyChat** y **WhatsApp → tu CRM**, con el mismo agente.

## Un agente por cliente (montaje rápido y repetible)
Cada cliente = una copia de esta carpeta con su propia configuración. Checklist por cliente:
1. Copia la carpeta `agente instagram` (o despliega otra instancia).
2. `npm install` y `npm start`.
3. Abre el panel → **🏢 Configuración del negocio** → rellena y pulsa **⚡ Generar agente**.
4. **⚙️ Configuración técnica** → pega la `OPENAI_API_KEY`, el **token de ManyChat** (IG) y, si usas CRM, la **URL de envío de WhatsApp**.
5. **🔗 CTAs** → pon los links reales del cliente.
6. En ManyChat (IG) crea el flujo con `channel: "instagram"`. En tu CRM (WhatsApp) engancha entrante + saliente.

Mismo procedimiento siempre. Cada instancia es independiente (su prompt, su memoria, sus claves).

> `{{user_id}}` es el subscriber_id que la API necesita · `{{last_input_text}}` el texto · `{{first_name}}` el nombre.
> Audios/imágenes: pasa la URL del adjunto en `last_input_text` y la app la transcribe/describe.
> Si Instagram y WhatsApp están en **cuentas de ManyChat distintas**, cada una tiene su propio token (avísame y añado un token por canal).

## Configuración (`.env`)

| Variable | Para qué |
|---|---|
| `OPENAI_API_KEY` | Clave de OpenAI |
| `OPENAI_MODEL` | Modelo del agente (por defecto `gpt-5.1`) |
| `MANYCHAT_TOKEN` | Token de la API de ManyChat |
| `MANYCHAT_CHANNEL` | `instagram` / `whatsapp` / `telegram` |
| `BUFFER_SECONDS` | Segundos para agrupar mensajes seguidos |
| `TYPING_SECONDS_PER_LETTER` | Retardo de tipeo por letra (`0.030`) |
| `WEBHOOK_TOKEN` | Secreto opcional de la cabecera del webhook |

## CTAs / recursos

Los links que envía el agente cuando usa la herramienta de CTA se configuran en [`data/ctas.json`](data/ctas.json). Cambia los `recurso` por tus links reales (formulario, agenda, academia…).

## Archivos

| Archivo | Qué hace |
|---|---|
| `prompt.md` | **El prompt del agente (esto es lo que editas).** |
| `src/server.js` | Webhook de ManyChat + panel web |
| `src/agent.js` | Llamada al modelo + salida en partes + herramientas |
| `src/manychat.js` | Envío de mensajes por la API de ManyChat |
| `src/media.js` | Transcripción de audio y descripción de imágenes |
| `src/tools.js` | Herramientas del agente (CTA / Derivar) |
| `src/store.js` | Memoria, buffer de mensajes y estadísticas |
| `data/` | Memoria y estadísticas (se crean solas) |
