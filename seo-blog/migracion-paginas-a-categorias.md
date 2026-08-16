# Migración de páginas a categorías · registro

Cada vez que una categoría queda terminada, la página que cubría lo mismo se
redirige y se actualizan los enlaces que apuntaban a ella. Este fichero lleva
la cuenta de lo hecho y lo pendiente.

---

## Regla de tres pasos

Al cerrar una categoría, siempre en este orden:

1. **Redirigir** con 301 la página vieja hacia la categoría nueva.
2. **Actualizar los enlaces internos** que apuntaban a la página, para que
   vayan directos y no a través del salto.
3. **Comprobar** que ninguna URL interna del sitio devuelve un 301.

El paso 2 importa más de lo que parece: un enlace que pasa por una
redirección funciona, pero diluye la fuerza que traslada y hace más lento el
rastreo. Y si algún día se quita la redirección, se rompe.

---

## Una categoría, una sola URL

WooCommerce respondía **200 en las dos rutas** de cada categoría anidada:

```
/product-category/delta-3/                    ← duplicado
/product-category/serie-delta/delta-3/        ← la canónica
```

La canónica ya apuntaba bien, pero la plana seguía existiendo y rastreándose.
Ahora la plana manda un **301** a la que devuelve `get_term_link()`, que es la
que usan los enlaces del propio sitio.

Vale para toda la tienda, no solo para DELTA 3. Se conservan la paginación y
los parámetros: `/product-category/delta-3/?orderby=price` salta a
`/product-category/serie-delta/delta-3/?orderby=price`. Las categorías de
primer nivel (`serie-delta`, `river-3`, `accesorios`) no se tocan, porque en
ellas la ruta plana **es** la canónica.

---

## DELTA 3 · hecho el 16/08/2026

| Qué | Estado |
|---|---|
| `/serie-delta-ecoflow/serie-delta-3/` (página en borrador) | 301 a la categoría |
| `/product-category/delta-3/` y las de sus hijas | 301 a la ruta anidada |
| Enlace de la ficha DELTA 3 Plus | Apuntaba a la plana, corregido |
| Enlace en la página `/serie-delta-ecoflow/` | Apuntaba al borrador, corregido |

**La página «Serie Delta 3» que está en borrador ya no hace falta**: la
categoría cubre lo mismo y mejor. Conviene descartarla en vez de publicarla.
Ojo: si se publica, la redirección deja de actuar, porque solo salta cuando
la URL sería un 404, y los visitantes irían a la página en lugar de a la
categoría.

### Nota sobre las páginas de Elementor

El enlace de `/serie-delta-ecoflow/` no estaba en el contenido de la página
sino dentro del JSON de `_elementor_data`: cambiarlo por la API de WordPress
no habría surtido efecto. Hay que sustituirlo en esa meta, cubriendo **las
dos formas** en las que aparece la URL, con las barras normales y escapadas
(`https:\/\/`), y limpiar después la caché de Elementor.

Se guardó copia del valor anterior en la opción `eg_enlace_d3_copia` por si
hiciera falta volver atrás.

---

## Pendiente, por gama

| Página | Impresiones | Categoría que la sustituirá |
|---|---:|---|
| `/paneles-solares-portatiles/` | 9.394 | `paneles-solares` |
| `/placas-solares-ecoflow/` | 1.614 | `paneles-solares` |
| `/accesorios/` | 1.148 | `accesorios` |
| `/serie-river/` | 763 | `serie-river` |
| `/generadores/` | 313 | por decidir |
| `/serie-delta-ecoflow/` | 278 | `serie-delta` |
| `/serie-rapid/` | 218 | `serie-rapid` |
| `/stream-series/` | 222 | `stream-series` |
| `/baterias-extras/` | 25 | baterías adicionales |

`/paneles-solares-portatiles/` es la que más cuidado pide: con 9.394
impresiones es la segunda página del sitio, así que la categoría tiene que
estar terminada y con contenido **antes** de tocar la redirección.
