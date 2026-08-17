# Servidor y rendimiento · 16/08/2026

Leído desde dentro de WordPress, no desde el panel del hosting.

## Lo que está bien

| | |
|---|---|
| PHP | **8.4.23** — la última, nada que tocar |
| `memory_limit` | 512 MB |
| `max_execution_time` | 3.600 s |
| Caché de página | Activa (`WP_CACHE`, `advanced-cache.php` instalado) |

## Los tres agujeros, por orden de impacto

### 1. OPcache está desactivado

Es lo más gordo. OPcache guarda el PHP ya compilado; sin él, **cada visita
recompila todo el código de WordPress, WooCommerce, Elementor y los 28
plugins**. Suele significar entre el doble y el triple de velocidad, y es
gratis: se activa en el servidor.

**Hay que pedírselo al hosting.** No se puede activar desde WordPress.

### 2. No hay caché de objetos, y no se puede instalar

Ni la extensión **Redis** ni **Memcached** están disponibles en el servidor.
WooCommerce hace cientos de consultas por página y sin caché de objetos las
repite en cada visita.

**Hay que pedir al hosting que instale Redis** (o Memcached). Es la segunda
mejora más grande para una tienda.

### 3. Base de datos de 330 MB para 158 productos

Muy por encima de lo normal. El desglose:

| Tabla | Tamaño | Filas |
|---|---:|---:|
| `wp_postmeta` | 148 MB | 62.264 |
| `wp_posts` | 113 MB | 5.851 |
| `wp_actionscheduler_actions` | 12 MB | 10.419 |
| `wp_options` | 10 MB | 2.949 |

`postmeta` y `posts` están hinchados por **3.714 revisiones**, casi todas de
Elementor: cada guardado de una página guarda una copia entera de su JSON.

## Limpieza hecha hoy

Solo cosas que se regeneran solas. **No se ha borrado contenido.**

| | Antes | Después |
|---|---:|---:|
| Tareas programadas completadas | 10.891 | 5.085 |
| Registros de tareas huérfanos | 32.620 | 18.623 |

Hecho **en lotes de 500 y con pausas**, porque el servidor acababa de caerse
por consumo y una consulta grande lo habría repetido.

Queda por terminar: el resto de registros de tareas, que van saliendo en
lotes. Y los transitorios: la consulta con `LIMIT` no funciona porque MySQL
no admite `LIMIT` en un `DELETE` de varias tablas.

## Pendiente de decisión

**Las 3.714 revisiones.** Borrarlas liberaría buena parte de esos 260 MB de
`posts` y `postmeta`, y es mantenimiento habitual, pero **es irreversible**:
se pierde el historial de cambios de las páginas. Conviene hacerlo con copia
de seguridad reciente y conservando las dos o tres últimas de cada página.

**Plugins duplicados.** Hay 28 activos y al menos dos parejas hacen lo mismo:

- `head-footer-code` **y** `insert-headers-and-footers`
- `wp-staging` **y** `wp-staging-pro`

Antes de desactivar ninguno hay que mirar qué contiene cada uno: el de
Head & Footer Code es justamente el que da 403 al guardar por la regla del
cortafuegos, así que puede tener código dentro.

## Lo que hay que pedirle al hosting

1. **Activar OPcache.**
2. **Instalar la extensión Redis** y activar la caché de objetos.
3. Quitar la regla de **ModSecurity que da 403** al guardar en Head & Footer Code.
4. Quitar el **429 en las URLs con `filter_product_cat`**.
5. **Excluir a Googlebot del limitador** de peticiones, verificándolo por DNS inverso.
6. Revisar los **límites de recursos**: hoy la web se cayó con PHP sin responder
   mientras los ficheros estáticos seguían sirviéndose.

---

## Sesión SSH — lote 2 (17/08/2026)

Datos confirmados en el servidor (`hl1341.dinaserver.com`, Debian 11):

| Dato | Valor |
|---|---|
| WP-CLI | 2.5.0, funciona con PHP 8.4 (`wp core version` → 7.0.4) |
| `user_ini.filename` | **`.php.ini`** (no `.user.ini`) → se puede subir ini por cuenta |
| `wp-content` | 8,7 G |
| ├ `uploads` | 7,7 G |
| ├ `plugins` | 448 M |
| ├ `themes` | 201 M |
| ├ `litespeed` (caché) | 179 M |
| ├ `compressx-nextgen` | 176 M |
| └ `languages` | 29 M |
| Copia de seguridad BD | `/home/ecogadgetoficial/copias/antes-limpieza-20260817-1234.sql` |

`mysqldump` solo avisa de que conviene usar `mariadb-dump`; el volcado se completó bien.

## Lote 3 — limpieza (pendiente de ejecutar)

Orden: **mirar → copia → limpiar → verificar**. La copia ya está hecha, así que toca limpiar.

1. Confirmar tamaño de la copia.
2. Probar OPcache para web con `.php.ini` en el docroot (`opcache.enable=1`, `opcache.memory_consumption=192`,
   `opcache.max_accelerated_files=20000`, `opcache.revalidate_freq=60`). PHP relee el `.php.ini` cada
   `user_ini.cache_ttl` (300 s por defecto), así que la comprobación se hace pasados unos minutos.
3. Borrar las 3.714 revisiones (**irreversible**, por eso va después de la copia), transitorios caducados,
   logs de Action Scheduler de más de 30 días y `wp db optimize`.
4. Verificar portada, categoría, ficha, carrito, checkout y "mi cuenta" con códigos HTTP y tiempos.

El prefijo de tablas se resuelve con `wp db prefix` en vez de asumir `wp_`.

## Lote 3 — resultado (17/08/2026)

Ruta real de WordPress: `/home/ecogadgetoficial/www`. Prefijo de tablas: `wp_`.

| Acción | Resultado |
|---|---|
| Copia BD previa | 251 MB, `~/copias/antes-limpieza-20260817-1234.sql` |
| Revisiones borradas | **3.714 → 0** |
| Transitorios caducados | 37 |
| Logs Action Scheduler +30 días | borrados |
| Acciones Action Scheduler | 5.074 `complete`, 6 `failed`, 27 `pending` — todas de menos de 30 días, no hay nada viejo que limpiar |
| `wp db optimize` | OK |
| **Tamaño BD tras limpieza** | **81 MB** |

Conclusión: **la base de datos no es el cuello de botella**. 81 MB es una BD sana para una tienda con 23 productos.

Tiempos desde el propio servidor (curl, con caché de LiteSpeed en juego):

| URL | Código | Tiempo |
|---|---|---|
| `/` | 200 | 3,84 s |
| `/product-category/serie-delta/delta-3/` | 200 | 2,78 s |
| `/product-category/paneles-solares/` | 200 | 2,51 s |
| `/shop/` | 200 | 2,04 s |
| `/cart/` | 200 | 2,17 s |
| `/checkout/` | 302 | 1,13 s (redirección normal con carrito vacío) |
| `/my-account/` | 200 | 2,16 s |

Todo responde, nada roto tras la limpieza. Pero 2–4 s es lento: si la caché de página
estuviera sirviendo, deberían ser décimas. Siguiente paso: mirar las cabeceras
`x-litespeed-cache` para saber si hay HIT o MISS.

`.php.ini` del docroot: ya tenía `max_execution_time = 3600`; se le añadió el bloque OPcache
sin tocar lo anterior. Pendiente de confirmar con `eg-check-9f3a2.php` desde navegador.

## Lote 4-10 — la causa real de la lentitud (17/08/2026)

### Diagnóstico

El servidor **no es LiteSpeed**: existe `/etc/apache2`, no existe `/usr/local/lsws`, y la respuesta
se identifica como `server: HTTPd`. Las reglas de caché que el plugin LiteSpeed Cache escribe en el
`.htaccess` están dentro de un bloque `<IfModule LiteSpeed>` (líneas 21-52) que **Apache ignora por completo**.

Resultado: el plugin creía que cacheaba (`litespeed.conf.cache = 1`), emitía la cabecera
`x-litespeed-tag`, y nadie la recogía. **No había ninguna caché de página.** Cada visita ejecutaba
WooCommerce entero.

Prueba: dos pasadas seguidas a la misma URL daban 2,70 s y 2,62 s. Con caché la segunda sería de décimas.

Descartados por medición, no por intuición:

| Sospechoso | Medición | Veredicto |
|---|---|---|
| Base de datos | 81 MB tras limpieza | inocente |
| Revisiones | 3.714 borradas, sin cambio en tiempos | inocente |
| OPcache | `extension_cargada=SI`, `memory_consumption=512` del php.ini del sistema | ya estaba activo |
| Proxy / red | estático en 0,03 s, `conn=0,003 s` | inocente |
| PHP generando la página | ttfb 2,4-3,8 s | **culpable** |

Nota sobre OPcache: `opcache.restrict_api = /var/www/htdocs/hl1341/v84/op-admin.php`. Dinahosting
restringe la API a su panel, así que `opcache_get_status()` devuelve `false` aunque OPcache funcione.
Un `opcache=NO` por esa vía es un **falso negativo**. Las directivas añadidas al `.php.ini` del docroot
son inertes: manda el php.ini del sistema.

### Solución aplicada

1. `litespeed.conf.cache` → `0` y retirado su `advanced-cache.php` (copia en `~/copias/`).
   El plugin LiteSpeed **sigue activo** para la optimización de CSS/JS (UCSS y MIN), de la que dependen
   los `data-no-optimize` de los filtros de categoría.
2. Instalado **WP Super Cache** en modo simple (`$wp_cache_mod_rewrite = 0`).
3. Configuración añadida al final de `wp-cache-config.php`, **antes del `?>`**:
   - `$cache_enabled = true`, `$super_cache_enabled = true`, `$cache_max_time = 3600`
   - `$wp_cache_not_logged_in = 2` — nada de caché para usuarios identificados
   - `$cache_rejected_uri` — cart, checkout, my-account, carrito, finalizar-compra, mi-cuenta, wc-ajax, add-to-cart
   - `$wpsc_rejected_cookies` — `woocommerce_items_in_cart`, `woocommerce_cart_hash`, `wp_woocommerce_session_`
   - `$wp_cache_no_cache_for_get = 1` — no cachear URLs con parámetros (evita repetir el problema de
     las 580.000 URLs de `?filter_product_cat=`)

### Resultado

| | Antes | Después |
|---|---|---|
| Portada (ttfb, por fuera) | 2,43 - 3,84 s | **0,063 s** |
| Primera visita sin caché | — | 3,57 s (solo la primera) |

**56 veces más rápido.**

### Vuelta atrás

```bash
cd /home/ecogadgetoficial/www
php -d memory_limit=1024M /usr/local/bin/wp plugin deactivate wp-super-cache --skip-plugins --skip-themes
cp ~/copias/advanced-cache-litespeed.php wp-content/advanced-cache.php
php -d memory_limit=1024M /usr/local/bin/wp option update litespeed.conf.cache 1 --skip-plugins --skip-themes
```

### Seguridad — copias de WP Staging públicas

`/wp-content/uploads/wp-staging/backups/*.wpstg` respondía **HTTP 206**: las 4 copias completas del sitio
(6,8 GB, con la base de datos y los datos personales de los clientes dentro) se podían descargar desde
internet. El `.htaccess` que trae el plugin solo desactiva el listado del directorio y cambia el MIME;
no bloquea nada. La única protección era el hash del nombre del fichero.

Corregido añadiendo a `wp-content/uploads/wp-staging/backups/.htaccess` (original en
`~/copias/htaccess-backups-original.txt`):

```apache
<FilesMatch "\.(wpstg|sql|log|zip|gz)$">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
  <IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
  </IfModule>
</FilesMatch>
```

Verificado: ahora responde **403**.

### Notas de trabajo

- **WP-CLI tiene 128 MB en línea de comandos** (la web tiene 512 MB). Cargar WooCommerce no cabe y
  da `Allowed memory size exhausted`. Hay que llamarlo como
  `php -d memory_limit=1024M /usr/local/bin/wp ...` o pasar `--skip-plugins --skip-themes`.
- `wp-cache-config.php` **termina en `?>`**: no se puede añadir configuración con `cat >>`, hay que
  insertarla antes del cierre.
- `cat >> fichero` **crea** el fichero si no existe. Un `grep` fallido en un `if` no impide que se ejecute
  el `else`. Combinando las dos cosas se crea un fichero de configuración falso; comprobar existencia
  antes, no solo contenido.

### Pendiente

- Decidir sobre las 3 copias antiguas de WP Staging (30 y 31 de enero, 25 de julio) → ~5 GB.
- `wp-staging` y `wp-staging-pro` están los dos activos; el gratuito sobra.
- 118 MB huérfanos en `uploads/mailster` y `uploads/wp-statistics` (plugins ya no instalados).
- Borrar `eg-check-9f3a2.php` del docroot.
