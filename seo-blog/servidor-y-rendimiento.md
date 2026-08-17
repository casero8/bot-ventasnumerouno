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
