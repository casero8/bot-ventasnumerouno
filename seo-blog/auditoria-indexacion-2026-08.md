# Auditoría de indexación · Search Console · 16/08/2026

Fuente: exportación de Search Console (Indexación → Páginas) del 18/05/2026
al 07/08/2026, más comprobaciones hechas en directo contra el sitio.

---

## 1. La caída de páginas indexadas NO es una penalización

Este es el dato que asusta al abrir el informe:

| Periodo | Sin indexar | Indexadas | Impresiones/día |
|---|---:|---:|---:|
| 18 may – 12 jun | 731.744 | 21.433 | 769 |
| 13 jun – 30 jun | 793.235 | 1.407 | 1.046 |
| 1 jul – 24 jul | 843.636 | 375 | 1.493 |
| 25 jul – 7 ago | 800.875 | 382 | 1.184 |

El 13 de junio las indexadas pasaron de 21.433 a 1.407 de golpe (−20.026).
**Y en ese mismo momento las impresiones subieron un 94 %.**

La explicación es simple: el sitemap tiene **326 URLs reales** y hay **382
indexadas**. Está indexado todo lo que existe. Las 27.000 que desaparecieron
eran URLs de filtro y basura que no aportaban ninguna impresión. Google hizo
limpieza y el resultado fue mejor, no peor.

**Conclusión: no hay que "recuperar" nada de esas 27.000.**

---

## 2. El problema real: 801.257 URLs conocidas para 159 productos

Ratio de basura: **2.458 URLs conocidas por cada URL real.** Google gasta su
presupuesto de rastreo en URLs de filtro en vez de en las fichas. Por eso una
ficha recién actualizada tarda semanas en refrescarse en el buscador.

| Motivo del informe | Páginas | Veredicto |
|---|---:|---|
| Rastreada: actualmente sin indexar | 366.194 | **Problema real.** Quema de rastreo |
| Bloqueada por robots.txt | 220.913 | Correcto, es lo que queremos |
| Página alternativa con canónica adecuada | 152.883 | **Problema real.** Ver corrección del punto 10 |
| Duplicada: sin canónica indicada | 58.689 | **Problema real.** Ver punto 3 |
| Página con redirección | 1.861 | Normal (http→https, www, barra final) |
| No se ha encontrado (404) | 277 | Revisar, hace falta el listado |
| Soft 404 | 39 | **Corregible hoy.** Ver punto 5 |
| Excluida por noindex | 13 | Intencionado |
| Error de servidor (5xx) | 6 | Ligado al punto 4 |

Al exportar el detalle de cada motivo (punto 10) resultó que casi todos son
el mismo fallo repetido. Lo único que funciona como debe son los 220.913
bloqueados por robots.txt.

---

## 3. Causa nº 1 · El cortafuegos devuelve 429 a las URLs de filtro

Comprobado en directo, con el servidor en reposo y 12 segundos entre
peticiones para descartar que fuera saturación mía:

| URL probada | Respuesta |
|---|---|
| `/product-category/delta-3/` | 200 |
| `/product-category/delta-3/?min_price=5&max_price=99` | 200 |
| `/shop/?orderby=rating` | 200 |
| `/shop/?query_type_product_cat=or` | 200 |
| `/product-category/delta-3/?filter_product_cat=delta-3` | **429** |
| `/product-category/delta-3/?filter_product_cat=zzz` | **429** |

El 429 llega con **`content-length: 0`**: cuerpo vacío, sin etiqueta
canónica, sin cabecera `retry-after`.

Y aquí está la contradicción. El `robots.txt` actual desbloquea `filter_`
a propósito, con esta nota:

> *"los filtros de tienda están DESBLOQUEADOS a propósito. Tienen canonical
> apuntando a /shop/ y necesitamos que Google entre a leerla para consolidar
> las ~58.000 URLs duplicadas."*

**Ese plan no puede funcionar.** Le abrimos la puerta a Google para que lea
una canónica que el servidor nunca le entrega: le devuelve un 429 vacío.
Eso explica buena parte de los 58.689 "duplicada sin canónica" y de los
366.194 "rastreada sin indexar".

Es el mismo cortafuegos que devuelve 403 al guardar en Head & Footer Code.

---

## 4. Causa nº 2 · El limitador de peticiones es demasiado agresivo

Con **6 peticiones simultáneas**, 113 de 185 URLs del sitemap devolvieron
429: la home, `/man/`, `/shop/`, las categorías de producto, las páginas
legales. Páginas reales, no filtros.

Googlebot rastreando 800.000 URLs choca con ese límite constantemente. La
reacción de Google es bajar la frecuencia de rastreo de todo el sitio. Los
6 errores 5xx del informe encajan aquí.

---

## 5. Causa nº 3 · Restos de la plantilla de demostración

Todo esto sigue publicado, indexable y en el sitemap:

**Categorías de producto vacías (0 productos):**
`aparatos-inteligentes`, `estaciones-de-energia`, `sincategoria`,
`uncategorized`

**Etiquetas de producto vacías, de la demo Minimog:**
`boot`, `cadigan`, `hot`, `sweater`, `women`

**Categorías del blog vacías, de la demo:**
`beauty`, `fashion`, `shopping`, `sweaters`, `trends`

**Páginas de la demo publicadas:** `/all-collections/`, `/homepage/`,
`/categories/`.

Ojo con las que parecen de la demo y no lo son. Consultando la configuración
de WooCommerce, **`/cart/`, `/checkout/`, `/my-account/` y `/shop/` son las
páginas reales de la tienda** (IDs 7071, 7072, 7073 y 7070). Las huérfanas
son las de nombre español: `/mi-cuenta/`, que estaba vacía. Despublicar las
que parecían sobrar habría roto el carrito.

**Páginas duplicadas entre sí:** `/contacto/` y `/contacto-2/`

**Etiquetas de producto:** 35 en total, de las cuales 7 tienen un solo
producto. Son páginas finas que compiten con las categorías.

Son páginas finas que no aportan nada, aunque **no** son la causa de los
39 soft 404: la exportación detallada demostró que esos son también URLs de
filtro (punto 10).

---

## 6. Causa nº 4 · Cada categoría vive en dos URLs

`/product-category/delta-3/` responde 200 y su canónica apunta a
`/product-category/serie-delta/delta-3/`, que también responde 200.

La canónica lo resuelve, así que no es un fallo grave, pero duplica el
rastreo de las 62 categorías y engorda el saco de "página alternativa".

---

## 7. Hallazgo suelto: hay una página de la Serie DELTA 3 en borrador

Existe una página **"Serie Delta 3" sin publicar**. Mientras tanto:

| Consulta | Impresiones | Clics | Posición |
|---|---:|---:|---:|
| `ecoflow delta 3` | 577 | 3 | 19,2 |
| `delta 3` | 117 | 0 | 12,6 |
| `estación de energía portátil serie ecoflow delta 3` | 45 | 0 | 11,7 |

Existe `/serie-delta-ecoflow/serie-delta-2/` publicada pero no la de la
serie 3, que es la que se vende. Es la corrección de mayor retorno y menor
esfuerzo de toda la lista.

---

## 8. Comprobación en directo de las 326 URLs del sitemap

Rastreadas una a una el 16/08/2026, con pausa entre peticiones para no
disparar el limitador:

| Respuesta | URLs |
|---|---:|
| 200 | **326** |
| 404 | 0 |
| 301 / 302 | 0 |
| 5xx | 0 |

**El sitemap está limpio al 100 %.** Nada de lo que enlazamos hoy está roto.

Esto acota los 277 errores 404 y los 39 soft 404 del informe: no están en
el sitemap, son URLs antiguas que Google recuerda de antes (productos
borrados, slugs viejos, restos de la plantilla). No hacen daño a las
páginas que venden, pero conviene mandarlas con un 301 al sitio correcto.
Sigue haciendo falta la exportación detallada del punto 11 para saber
cuáles son.

---

## 9. Qué hay que hacer

### Bloque A · Hosting (hay que pedirlo, no se puede hacer por API)

1. **Quitar la regla que devuelve 429 a `filter_product_cat`.** Mientras
   siga, las 58.689 duplicadas no se pueden consolidar.
2. **Excluir a Googlebot del limitador de peticiones**, verificándolo por
   DNS inverso, o subir el límite. Ahora mismo penaliza el rastreo de todo
   el sitio.
3. De paso, **la regla de ModSecurity que da 403 al guardar en Head &
   Footer Code** (pendiente de antes).

### Bloque B · Se puede hacer hoy por API

4. **Volver a bloquear `filter_` en robots.txt.** El plan de dejarlo abierto
   depende de que el punto 1 se arregle; si no se arregla, es mejor cerrar la
   puerta que dejar a Google chocando contra 429. `min_price`, `max_price` y
   `orderby` se quedan abiertos: esos sí entregan canónica correcta.
5. **Poner las 35 etiquetas de producto en noindex** y sacar
   `product_tag-sitemap.xml` del índice de sitemaps.
6. **Borrar** las 5 etiquetas de producto de la demo, las 4 categorías de
   producto vacías y las 5 categorías de blog vacías.
7. **Despublicar** las 7 páginas de la demo en inglés.
8. **Unificar los dos CONTACTO** con un 301 de `/contacto-2/` a `/contacto/`.
9. **Publicar la página de la Serie DELTA 3** y enlazarla desde las fichas
   de la serie.
10. **Reasignar las 8 entradas en "uncategorized"** a categorías reales.

### Bloque C · Hace falta un dato que no está en el CSV

11. Los CSV traen los totales pero no las URLs. Para los **277 errores 404**
    y los **39 soft 404** hace falta la exportación detallada: Search Console
    → Indexación → Páginas → pinchar en el motivo → Exportar. Con esa lista
    monto los 301 que hagan falta.

### Lo que NO hay que tocar

- Los 220.913 bloqueados por robots.txt: es el sistema funcionando.
- Los 152.883 con canónica correcta: Google ya los ha entendido.
- Los 1.861 con redirección: son los saltos normales a https y sin www.
- Las 27.000 páginas "perdidas" en junio: eran basura y su desaparición
  vino acompañada de un 94 % más de impresiones.

---

## 10. La exportación detallada: no eran ocho problemas, era uno

Con el detalle de los ocho motivos exportado, se puede contar cuántas de las
URLs de cada saco llevan `filter_product_cat` o `filtering=`:

| Motivo | Páginas | Con parámetro de filtro |
|---|---:|---:|
| Rastreada: actualmente sin indexar | 366.194 | **99 %** |
| Página alternativa con canónica adecuada | 152.883 | **100 %** |
| Duplicada: sin canónica indicada | 58.689 | **100 %** |
| Página con redirección | 1.861 | **93 %** |
| No se ha encontrado (404) | 277 | **94 %** |
| Soft 404 | 39 | **100 %** |
| Excluida por noindex | 13 | 0 % |

Todas son `/shop/?filter_product_cat=...`, sin excepción en las muestras de
1.000 URLs de cada informe.

**De las ~580.000 URLs que no bloquea el robots.txt, el 99 % son la misma
cosa.** Los ocho motivos del informe eran un solo fallo visto desde ocho
ángulos distintos.

Dos cosas que había dado por buenas y no lo eran:

- **Los 152.883 de "página alternativa con canónica adecuada" no son sanos.**
  Se han **triplicado** entre mayo y agosto (51.784 → 152.883) y Google los
  rastreaba el 8 de agosto. Son rastreo vivo, no un archivo histórico.
- **Los 39 soft 404 no vienen de las categorías vacías.** Son el 100 % URLs
  de filtro. La hipótesis de las páginas vacías era razonable y era falsa.

### Los 404 que sí son reales

De los 277, solo **18** no llevan parámetros de filtro, y casi todos tienen
un destino evidente: slugs de fichas que se guardaron sin acentos y luego
cambiaron, categorías de la serie DELTA sin el prefijo `product-category`,
y dos entradas de la plantilla de demostración que no tienen equivalente y
deben seguir devolviendo 404.

---

## 11. Lo que se ha hecho (16/08/2026)

### robots.txt

Se bloquean `filter_product_cat`, `filtering` y `query_type_`. Copia del
anterior en `robots-txt-backup-2026-08-16.txt`.

`min_price`, `max_price` y `orderby` se quedan abiertos: esos sí entregan
canónica correcta y Google ya los ha consolidado; cerrarlos ahora solo
congelaría el informe en un estado peor.

De paso, dos fallos del robots.txt anterior:

- Los patrones estaban escritos como `/*?add-to-cart=`, que solo casan
  cuando el parámetro es **el primero** de la cadena. Ahora van como
  `/*add-to-cart=` y casan en cualquier posición.
- La lista de deseos se bloqueaba como `add_to_wishlist` con guiones bajos,
  pero el parámetro real del sitio es `add-to-wishlist` con guiones. No
  estaba bloqueando nada. Ahora están los dos.

### Redirecciones (snippet 6, `EG · SEO · Redirecciones de URLs muertas`)

301 para los 18 errores 404 reales. Se enganchan a `template_redirect` y
**solo actúan cuando WordPress ya ha decidido que la URL es un 404**: si
algún día se publica la página de la Serie DELTA 3 en su ruta original, la
página gana y la redirección deja de dispararse sola.

Las dos entradas de la demo (moda y maquillaje) se dejan en 404 a propósito.

### Etiquetas de producto

Las 35 pasan a `noindex, follow` y `product_tag-sitemap.xml` sale del índice
de sitemaps. Se hace con los filtros `wpseo_robots_array` y
`wpseo_sitemap_exclude_taxonomy`, no solo con el ajuste de Yoast: el ajuste
escribe en la tabla de indexables, que no se regenera hasta reindexar el
sitio entero, y por eso al principio no surtía efecto.

### Restos de la plantilla borrados

- 5 etiquetas de producto: `boot`, `cadigan`, `hot`, `sweater`, `women`
- 2 categorías de producto vacías: `aparatos-inteligentes`,
  `estaciones-de-energia`
- 5 categorías de blog vacías: `beauty`, `fashion`, `shopping`, `sweaters`,
  `trends`

`sincategoria` no se borra: parecía vacía pero tiene un producto dentro
(la cubierta del DELTA 2 Max). `uncategorized` es la categoría por defecto
de WooCommerce y no se puede borrar.

### Páginas

Despublicadas: `/categories/`, `/all-collections/`, `/homepage/`,
`/contacto-2/` y `/mi-cuenta/`. Las dos últimas, además, con 301 a
`/contacto/` y `/my-account/`.

`/wishlist/` y `/wishlist-2/` se quedan publicadas pero en `noindex`: llevan
el shortcode del plugin y podría necesitarlas.

### Blog

Las 8 entradas que estaban en "Uncategorized" repartidas entre
`kits-hogar`, `ahorro-energetico`, `estaciones-de-energia`, `placas-solares`
y `guias-y-consejos`. La categoría por defecto se queda a cero.

### Comprobado después de tocar

Home, `/shop/`, ficha de producto, `/cart/`, `/checkout/`, `/my-account/`,
`/contacto/`, `/blog/`, `/man/` y `/product-category/delta-3/`: **200 las
diez**. El sitemap baja de 326 a 292 URLs.

---

## 12. Lo que queda

**Del hosting**, sin lo cual el rastreo seguirá limitado:

1. La regla que devuelve 429 a `filter_product_cat`.
2. El limitador de peticiones, que responde 429 a páginas reales con 6
   peticiones simultáneas. Conviene excluir a Googlebot verificándolo por
   DNS inverso.
3. La regla de ModSecurity que da 403 al guardar en Head & Footer Code.

**Pendiente de decisión de David:**

4. Publicar la página de la Serie DELTA 3, que sigue en borrador. Mientras
   tanto su URL redirige a `/product-category/delta-3/`; el día que se
   publique, la redirección se apaga sola.
