# Servidor MCP de Google Search Console

Servidor propio para consultar Search Console desde las sesiones de Claude sin
exportar CSV a mano y sin pagar un intermediario. La **API de Search Console de
Google es gratis** y no pide cuenta de facturación; lo que se paga en
herramientas tipo Windsor es el intermediario, no el dato.

Desplegado en Cloud Run entra de sobra en la capa gratuita: son unas pocas
consultas al día contra un límite de 2 millones de peticiones al mes.

## Qué hace

| Herramienta | Para qué |
|---|---|
| `listar_propiedades` | Comprueba a qué propiedades llega la cuenta de servicio |
| `rendimiento` | Informe general: clics, impresiones, CTR y posición, agrupado por las dimensiones que pidas |
| `consultas_de_una_pagina` | **La importante**: qué se busca para llegar a una URL concreta. Es lo que hace falta para escribir las preguntas frecuentes de cada ficha con datos reales |
| `paginas_con_mal_ctr` | Páginas con muchas impresiones y pocos clics, separando las que fallan por título de las que fallan por posición |

Es **solo lectura**: pide únicamente el ámbito `webmasters.readonly`, así que no
puede modificar nada en Search Console aunque quisiera.

---

## Puesta en marcha

### 1. Cuenta de servicio en Google Cloud

En [console.cloud.google.com](https://console.cloud.google.com), con el proyecto
que quieras usar:

1. **APIs y servicios → Biblioteca** → busca *Google Search Console API* →
   **Habilitar**.
2. **IAM y administración → Cuentas de servicio → Crear cuenta de servicio**.
   Nombre: `mcp-search-console`. No hace falta darle ningún rol de IAM: el acceso
   se concede después dentro de Search Console.
3. Entra en la cuenta creada → pestaña **Claves** → **Agregar clave → Crear clave
   nueva → JSON**. Se descarga un archivo. **Ese archivo es una credencial: no lo
   subas a git ni lo pegues en un chat.**
4. Apunta el correo de la cuenta, con la forma
   `mcp-search-console@TU-PROYECTO.iam.gserviceaccount.com`.

### 2. Darle acceso en Search Console

En [search.google.com/search-console](https://search.google.com/search-console),
con la propiedad de ecogadgetoficial.com abierta:

**Configuración → Usuarios y permisos → Añadir usuario**, pega el correo de la
cuenta de servicio y dale permiso **Restringido**, que es de solo lectura.

Este es el paso que más se olvida. Sin él, el servidor levanta pero
`listar_propiedades` devuelve la lista vacía.

### 3. Generar el token del endpoint

Al desplegarlo queda con URL pública, así que necesita su propia contraseña.
Genérala así y guárdala:

```bash
openssl rand -base64 32
```

### 4. Desplegar en Cloud Run

Desde esta carpeta, con `gcloud` ya autenticado:

```bash
# El JSON de la cuenta de servicio va en Secret Manager, nunca en una variable
# de entorno a la vista.
gcloud secrets create gsc-service-account --data-file=/ruta/a/tu-clave.json

gcloud run deploy mcp-search-console \
  --source . \
  --region europe-southwest1 \
  --allow-unauthenticated \
  --set-env-vars "MCP_AUTH_TOKEN=EL_TOKEN_QUE_GENERASTE" \
  --set-secrets "GOOGLE_SERVICE_ACCOUNT_JSON=gsc-service-account:latest"
```

Sobre `--allow-unauthenticated`: hace falta porque Claude se conecta desde fuera
y no tiene identidad de Google IAM. **No deja el servicio abierto**: quien no
mande el token correcto en la cabecera `Authorization` recibe un 401. Por eso el
token tiene que ser largo y no compartirse.

`europe-southwest1` es Madrid. Cualquier región vale, pero cerca reduce latencia.

Al terminar, Cloud Run devuelve una URL del tipo
`https://mcp-search-console-XXXXXX.europe-southwest1.run.app`.

### 5. Comprobar que responde

```bash
curl https://TU-URL.run.app/salud
# ok

curl -X POST https://TU-URL.run.app/mcp \
  -H "Authorization: Bearer EL_TOKEN" \
  -H "Accept: application/json, text/event-stream" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/list"}'
```

Tiene que devolver las cuatro herramientas. Si sale 401, el token no coincide.

### 6. Añadirlo en Claude

En **claude.ai → Configuración → Conectores → Añadir conector personalizado**:

- URL: `https://TU-URL.run.app/mcp`
- Cabecera: `Authorization: Bearer EL_TOKEN`

A partir de ahí está disponible en las sesiones, también en estas de Claude Code
en el navegador.

---

## Detalles que evitan sustos

**Search Console va con dos o tres días de retraso.** El servidor lo tiene en
cuenta: si no le das fechas, el rango termina hace tres días. Pedir hasta hoy
devuelve ceros y parece que está roto.

**La propiedad de dominio se escribe `sc-domain:ecogadgetoficial.com`**, sin
`https://`. Las de prefijo de URL van con la URL completa y barra final. Si te
equivocas, la API responde 404 y el servidor lo traduce a un mensaje que lo
explica.

**Modo sin estado.** Cloud Run levanta y apaga instancias sin avisar, así que el
servidor no guarda sesión entre peticiones. Guardarla daría fallos intermitentes
muy difíciles de rastrear.

**El token se compara en tiempo constante** (`hmac.compare_digest`), para que la
duración de la respuesta no revele cuántos caracteres son correctos.

## Probarlo en local

```bash
python -m venv venv && source venv/bin/activate
pip install -r requirements.txt

export GOOGLE_SERVICE_ACCOUNT_JSON="$(cat /ruta/a/tu-clave.json)"
export MCP_AUTH_TOKEN="cualquier-cosa-de-32-caracteres-o-mas"
python server.py
```

Queda escuchando en `http://127.0.0.1:8080`.

## Qué se comprobó antes de entregarlo

Probado en este entorno, no solo escrito:

- El servidor arranca y expone las **cuatro herramientas** con sus parámetros.
- El endpoint `/salud` responde sin token; `/mcp` **devuelve 401** sin token y
  también con un token incorrecto.
- Handshake MCP completo por HTTP (`initialize` y `tools/list`) contra el
  servidor en marcha: devuelve las cuatro herramientas.
- Los errores salen legibles: sin credenciales dice exactamente qué falta, y una
  dimensión inventada devuelve la lista de las válidas.

Lo único que no se pudo probar aquí son las llamadas reales a Google, porque
hacen falta las credenciales de la cuenta de servicio. Ese es el primer paso del
apartado 5.
