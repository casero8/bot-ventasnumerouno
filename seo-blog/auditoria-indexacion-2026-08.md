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
| Página alternativa con canónica adecuada | 152.883 | Correcto, Google lo ha entendido |
| Duplicada: sin canónica indicada | 58.689 | **Problema real.** Ver punto 3 |
| Página con redirección | 1.861 | Normal (http→https, www, barra final) |
| No se ha encontrado (404) | 277 | Revisar, hace falta el listado |
| Soft 404 | 39 | **Corregible hoy.** Ver punto 5 |
| Excluida por noindex | 13 | Intencionado |
| Error de servidor (5xx) | 6 | Ligado al punto 4 |

De los nueve motivos, **tres son fallos reales y dos están funcionando como
deben**. No hay que tocar los 220.913 bloqueados por robots.txt ni los
152.883 con canónica: esos son el sistema haciendo su trabajo.

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

**Páginas de la demo publicadas**, que duplican a las españolas:
`/all-collections/`, `/homepage/`, `/categories/`, `/wishlist-2/`,
`/my-account/`, `/cart/`, `/checkout/`

**Páginas duplicadas entre sí:** `/contacto/` y `/contacto-2/`

**Etiquetas de producto:** 35 en total, de las cuales 7 tienen un solo
producto. Son páginas finas que compiten con las categorías.

Nueve páginas literalmente vacías explican de sobra los 39 soft 404.

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

## 8. Qué hay que hacer

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
