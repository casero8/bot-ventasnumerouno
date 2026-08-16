# Incidencia · 16/08/2026 · PHP no responde

## Síntoma

El servidor web sirve ficheros estáticos con normalidad, pero **todo lo que
necesita ejecutar PHP se queda sin respuesta**: la conexión se abre y no
llega ni un byte.

| Petición | Respuesta |
|---|---|
| `/robots.txt` | 200 |
| Una imagen de `/wp-content/uploads/` | 200 |
| `/` (portada) | sin respuesta a los 120 s |
| `/wp-login.php` | sin respuesta |
| `/wp-json/` | sin respuesta |

Tiempo de conexión: **0,0003 s**. Primer byte: **nunca**.

Confirmado también desde la conexión de David, así que no es un bloqueo de
IP: **la tienda está caída para los clientes**.

## Qué descarta esto

- **No es el limitador de peticiones.** Si lo fuera, tampoco se servirían el
  `robots.txt` ni las imágenes.
- **No es lentitud.** A los 120 segundos seguía sin devolver nada.
- **No es un error de PHP.** Un fallo de código daría un 500 con su
  respuesta, no silencio.

Queda: el servicio PHP parado o sin procesos libres, o el hosting limitando
la ejecución dinámica por consumo de recursos.

## Contexto honesto

La jornada acumuló **cientos de peticiones** contra un servidor cuyo
limitador ya sabíamos frágil: con seis peticiones simultáneas devolvía 429
en páginas normales, algo que ya estaba documentado como problema a tratar
con el hosting.

La última operación lanzada —repuntar el menú de Powerbanks y despublicar su
página— **se quedó sin tiempo a mitad** y no se sabe en qué punto se cortó.

No se puede afirmar que la actividad de esta sesión sea la causa, pero
tampoco descartarla: es un candidato razonable a haber sido el detonante,
por consumo, no por un error de código.

## Qué se hizo justo antes

1. Publicada la categoría `serie-rapid` con su contenido y su SEO.
   **Comprobada y funcionando**: título, 11 productos, franja con cuatro
   disponibles y seis preguntas.
2. Lanzado el lote que repuntaba las dos entradas de menú y despublicaba
   `/serie-rapid/`. **Se cortó por tiempo.**

## Cómo revertir el código de esta sesión

Todo lo añadido vive en el plugin **Code Snippets**. Los dos activos son:

- `EG · SEO · Redirecciones de URLs muertas`
- `EG · Descripcion de categorias (arriba y abajo)`

Desactivarlos desde Ajustes → Snippets deja el sitio como estaba. Si el
escritorio no carga, renombrar desde el gestor de archivos del hosting la
carpeta `wp-content/plugins/code-snippets`: eso los desactiva todos de golpe.

Las copias de seguridad de lo tocado fuera de snippets están en opciones de
la base de datos: `eg_copia_custom_js`, `eg_copia_custom_js_2` y
`eg_enlace_d3_copia`.

## Pendiente de verificar cuando el sitio vuelva

- [ ] Estado de las dos entradas de menú de Powerbanks
- [ ] Si `/serie-rapid/` quedó despublicada
- [ ] Que la redirección `/serie-rapid/` funciona
- [ ] Que las categorías ya migradas siguen respondiendo
