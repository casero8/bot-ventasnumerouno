"""
Servidor MCP de Google Search Console para EcoGadget.

Expone los datos de Search Console por HTTP para poder consultarlos desde las
sesiones de Claude sin exportar CSV a mano cada vez.

Dos decisiones de diseño que conviene entender antes de tocar nada:

1. AUTENTICACIÓN CON GOOGLE POR CUENTA DE SERVICIO, NO POR OAUTH.
   Un servidor sin persona delante no puede completar un flujo de OAuth: no hay
   navegador donde aceptar, y los tokens de refresco caducan y hay que renovarlos
   a mano. Con una cuenta de servicio basta con añadir su correo como usuario en
   Search Console y ya no caduca nada. Menos piezas y menos cosas que se rompan.

2. EL ENDPOINT VA PROTEGIDO CON UN TOKEN.
   Al desplegarlo queda con URL pública. Sin token, cualquiera que la adivine lee
   tus datos de posicionamiento. El token va en la cabecera Authorization y se
   compara en tiempo constante para no filtrar información por el tiempo de
   respuesta.

Variables de entorno necesarias:

    GOOGLE_SERVICE_ACCOUNT_JSON   El JSON de la cuenta de servicio, entero.
    MCP_AUTH_TOKEN               El token que tendrá que enviar el cliente.
    PORT                         Lo inyecta Cloud Run. En local, 8080.

Permiso de solo lectura: se pide únicamente el ámbito webmasters.readonly, así
que este servidor NO puede modificar nada en Search Console.
"""

from __future__ import annotations

import hmac
import json
import logging
import os
from datetime import date, timedelta
from typing import Any

from google.oauth2 import service_account
from googleapiclient.discovery import build
from googleapiclient.errors import HttpError
from mcp.server import MCPServer
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import JSONResponse, PlainTextResponse

log = logging.getLogger("mcp-gsc")
logging.basicConfig(level=logging.INFO)

# Solo lectura. No ampliar sin motivo: con este ámbito el servidor no puede
# tocar nada, y eso es justo lo que queremos de algo expuesto a internet.
AMBITOS = ["https://www.googleapis.com/auth/webmasters.readonly"]

DIMENSIONES_VALIDAS = {"query", "page", "country", "device", "date", "searchAppearance"}


# --------------------------------------------------------------------------- #
#  Conexión con Google
# --------------------------------------------------------------------------- #

_servicio = None


def servicio_gsc():
    """Cliente de la API de Search Console. Se construye una vez y se reutiliza."""
    global _servicio

    if _servicio is not None:
        return _servicio

    crudo = os.environ.get("GOOGLE_SERVICE_ACCOUNT_JSON", "").strip()
    if not crudo:
        raise RuntimeError(
            "Falta GOOGLE_SERVICE_ACCOUNT_JSON. Tiene que contener el JSON "
            "completo de la cuenta de servicio."
        )

    try:
        info = json.loads(crudo)
    except json.JSONDecodeError as e:
        raise RuntimeError(
            f"GOOGLE_SERVICE_ACCOUNT_JSON no es JSON válido: {e}"
        ) from e

    credenciales = service_account.Credentials.from_service_account_info(
        info, scopes=AMBITOS
    )
    _servicio = build("searchconsole", "v1", credentials=credenciales, cache_discovery=False)
    return _servicio


def _rango(dias: int | None, desde: str | None, hasta: str | None) -> tuple[str, str]:
    """
    Resuelve el rango de fechas.

    Search Console va con dos o tres días de retraso, así que el rango termina
    hace tres días. Pedir hasta hoy devuelve ceros y parece que algo falla.
    """
    if desde and hasta:
        return desde, hasta

    fin = date.today() - timedelta(days=3)
    inicio = fin - timedelta(days=(dias or 90))
    return inicio.isoformat(), fin.isoformat()


def _error(e: Exception) -> dict[str, Any]:
    """Convierte los fallos de la API en algo que se pueda leer y accionar."""
    if isinstance(e, HttpError):
        codigo = e.resp.status
        if codigo == 403:
            return {
                "error": "sin_permiso",
                "detalle": (
                    "La cuenta de servicio no tiene acceso a esa propiedad. "
                    "Añade su correo como usuario en Search Console → Configuración "
                    "→ Usuarios y permisos."
                ),
            }
        if codigo == 404:
            return {
                "error": "propiedad_no_encontrada",
                "detalle": (
                    "Revisa el identificador. Para propiedades de dominio es "
                    "'sc-domain:ecogadgetoficial.com'; para las de prefijo de URL, "
                    "la URL completa con barra final."
                ),
            }
        return {"error": f"http_{codigo}", "detalle": str(e)}
    return {"error": "fallo", "detalle": str(e)}


# --------------------------------------------------------------------------- #
#  Servidor MCP
# --------------------------------------------------------------------------- #

servidor = MCPServer(
    name="search-console-ecogadget",
    title="Search Console de EcoGadget",
    instructions=(
        "Datos de Google Search Console en modo solo lectura. La propiedad "
        "habitual es 'sc-domain:ecogadgetoficial.com'. Ten en cuenta que Search "
        "Console publica los datos con dos o tres días de retraso: si pides hasta "
        "hoy, saldrán ceros."
    ),
    version="1.0.0",
)


@servidor.tool(
    name="listar_propiedades",
    description=(
        "Lista las propiedades de Search Console a las que tiene acceso la cuenta "
        "de servicio. Útil para comprobar que el acceso está bien dado y para "
        "conocer el identificador exacto de cada propiedad."
    ),
)
async def listar_propiedades() -> dict[str, Any]:
    try:
        respuesta = servicio_gsc().sites().list().execute()
    except Exception as e:  # noqa: BLE001
        return _error(e)

    sitios = [
        {"propiedad": s.get("siteUrl"), "permiso": s.get("permissionLevel")}
        for s in respuesta.get("siteEntry", [])
    ]
    if not sitios:
        return {
            "propiedades": [],
            "aviso": (
                "La cuenta de servicio no tiene ninguna propiedad asignada. Añade "
                "su correo en Search Console → Configuración → Usuarios y permisos."
            ),
        }
    return {"propiedades": sitios}


@servidor.tool(
    name="rendimiento",
    description=(
        "Consulta el informe de rendimiento de Search Console: clics, impresiones, "
        "CTR y posición media. Es la herramienta general; agrupa por las dimensiones "
        "que le pases. Dimensiones admitidas: query, page, country, device, date y "
        "searchAppearance."
    ),
)
async def rendimiento(
    propiedad: str = "sc-domain:ecogadgetoficial.com",
    dimensiones: list[str] | None = None,
    dias: int = 90,
    fecha_desde: str | None = None,
    fecha_hasta: str | None = None,
    limite: int = 200,
    filtro_pagina: str | None = None,
    filtro_consulta: str | None = None,
) -> dict[str, Any]:
    dims = dimensiones or ["query"]

    invalidas = [d for d in dims if d not in DIMENSIONES_VALIDAS]
    if invalidas:
        return {
            "error": "dimension_invalida",
            "detalle": f"No existen: {invalidas}. Válidas: {sorted(DIMENSIONES_VALIDAS)}",
        }

    desde, hasta = _rango(dias, fecha_desde, fecha_hasta)

    filtros = []
    if filtro_pagina:
        filtros.append({"dimension": "page", "operator": "contains", "expression": filtro_pagina})
    if filtro_consulta:
        filtros.append({"dimension": "query", "operator": "contains", "expression": filtro_consulta})

    cuerpo: dict[str, Any] = {
        "startDate": desde,
        "endDate": hasta,
        "dimensions": dims,
        "rowLimit": min(max(limite, 1), 25000),
    }
    if filtros:
        cuerpo["dimensionFilterGroups"] = [{"filters": filtros}]

    try:
        respuesta = (
            servicio_gsc()
            .searchanalytics()
            .query(siteUrl=propiedad, body=cuerpo)
            .execute()
        )
    except Exception as e:  # noqa: BLE001
        return _error(e)

    filas = []
    for f in respuesta.get("rows", []):
        fila = dict(zip(dims, f.get("keys", [])))
        fila.update(
            {
                "clics": f.get("clicks", 0),
                "impresiones": f.get("impressions", 0),
                "ctr": round(f.get("ctr", 0) * 100, 2),
                "posicion": round(f.get("position", 0), 2),
            }
        )
        filas.append(fila)

    return {
        "propiedad": propiedad,
        "desde": desde,
        "hasta": hasta,
        "dimensiones": dims,
        "total_filas": len(filas),
        "filas": filas,
    }


@servidor.tool(
    name="consultas_de_una_pagina",
    description=(
        "Devuelve las consultas por las que Google muestra una página concreta, "
        "ordenadas por impresiones. Es la herramienta para saber qué se pregunta "
        "de verdad sobre un producto antes de escribir sus preguntas frecuentes. "
        "Pásale la ruta, por ejemplo '/producto/ecoflow-delta-3-classic-1024wh/'."
    ),
)
async def consultas_de_una_pagina(
    ruta: str,
    propiedad: str = "sc-domain:ecogadgetoficial.com",
    dias: int = 180,
    limite: int = 100,
) -> dict[str, Any]:
    resultado = await rendimiento(
        propiedad=propiedad,
        dimensiones=["query"],
        dias=dias,
        limite=limite,
        filtro_pagina=ruta,
    )
    if "error" in resultado:
        return resultado

    resultado["ruta"] = ruta
    if not resultado["filas"]:
        resultado["aviso"] = (
            "Sin datos para esa ruta. O no recibe impresiones, o la ruta no coincide. "
            "Se filtra por 'contiene', así que prueba con un fragmento más corto."
        )
    return resultado


@servidor.tool(
    name="paginas_con_mal_ctr",
    description=(
        "Localiza las páginas que tienen muchas impresiones y pocos clics, que son "
        "las que más rinden al reescribirles el título y la meta. Devuelve solo las "
        "que superan el mínimo de impresiones y bajan del CTR indicado, separando "
        "las que están en primera página (problema de título) de las que no "
        "(problema de posicionamiento)."
    ),
)
async def paginas_con_mal_ctr(
    propiedad: str = "sc-domain:ecogadgetoficial.com",
    dias: int = 90,
    impresiones_minimas: int = 500,
    ctr_maximo: float = 1.5,
    limite: int = 500,
) -> dict[str, Any]:
    resultado = await rendimiento(
        propiedad=propiedad, dimensiones=["page"], dias=dias, limite=limite
    )
    if "error" in resultado:
        return resultado

    candidatas = [
        f
        for f in resultado["filas"]
        if f["impresiones"] >= impresiones_minimas and f["ctr"] < ctr_maximo
    ]
    candidatas.sort(key=lambda f: -f["impresiones"])

    for f in candidatas:
        f["diagnostico"] = "titulo_y_meta" if f["posicion"] <= 10 else "posicionamiento"

    return {
        "propiedad": propiedad,
        "desde": resultado["desde"],
        "hasta": resultado["hasta"],
        "criterio": f"{impresiones_minimas}+ impresiones y CTR por debajo del {ctr_maximo} %",
        "total": len(candidatas),
        "nota": (
            "Posición 10 o mejor significa que te ven y no te pulsan: se arregla con "
            "título y meta. Peor que 10 es un problema de posicionamiento y el título "
            "no lo va a resolver."
        ),
        "paginas": candidatas,
    }


# --------------------------------------------------------------------------- #
#  Protección del endpoint
# --------------------------------------------------------------------------- #


class TokenRequerido(BaseHTTPMiddleware):
    """
    Exige un token en la cabecera Authorization.

    Se compara con hmac.compare_digest y no con ==, para que el tiempo de
    respuesta no revele cuántos caracteres del token son correctos.
    """

    def __init__(self, app, token: str):
        super().__init__(app)
        self.token = token

    async def dispatch(self, request: Request, call_next):
        if request.url.path in ("/", "/salud"):
            return await call_next(request)

        cabecera = request.headers.get("authorization", "")
        enviado = cabecera[7:] if cabecera.lower().startswith("bearer ") else cabecera

        if not hmac.compare_digest(enviado, self.token):
            return JSONResponse(
                {"error": "no_autorizado", "detalle": "Falta la cabecera Authorization o el token no es correcto."},
                status_code=401,
            )

        return await call_next(request)


def crear_app():
    token = os.environ.get("MCP_AUTH_TOKEN", "").strip()
    if not token:
        raise RuntimeError(
            "Falta MCP_AUTH_TOKEN. Sin token el endpoint queda abierto a "
            "internet y cualquiera podría leer tus datos de Search Console."
        )
    if len(token) < 24:
        raise RuntimeError("MCP_AUTH_TOKEN es demasiado corto: usa 32 caracteres o más.")

    # stateless_http: cada petición se resuelve sola. Cloud Run levanta y apaga
    # instancias sin avisar, así que guardar sesión entre peticiones daría fallos
    # intermitentes muy difíciles de rastrear.
    app = servidor.streamable_http_app(
        streamable_http_path="/mcp",
        stateless_http=True,
        host="0.0.0.0",
    )

    async def salud(_request: Request):
        return PlainTextResponse("ok")

    app.add_route("/salud", salud, methods=["GET"])
    app.add_middleware(TokenRequerido, token=token)
    return app


if __name__ == "__main__":
    import uvicorn

    puerto = int(os.environ.get("PORT", "8080"))
    log.info("Servidor MCP de Search Console escuchando en el puerto %s", puerto)
    uvicorn.run(crear_app(), host="0.0.0.0", port=puerto, log_level="info")
