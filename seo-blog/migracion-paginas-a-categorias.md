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

## Por dónde se llega a una categoría

Esto es lo que más se olvida: una categoría puede estar perfecta y no
recibir una visita porque no la enlaza nadie. Estado de DELTA 3:

| Desde | ¿Enlaza? |
|---|---|
| Menú CATEGORÍAS | Sí, colgando de «Estaciones de energía DELTA» |
| Menú principal (cabecera) | **No.** Sigue apuntando a `/serie-delta-ecoflow/` |
| Página `/serie-delta-ecoflow/` | Sí |
| Ficha DELTA 3 Plus | Sí |
| Migas y filtro lateral | Sí |
| Sitemap | Sí |

**La navegación del sitio sigue construida sobre páginas.** El menú
principal tiene seis entradas y solo una apunta a una categoría («Casa y
balcón»); las otras cinco van a páginas:

| Entrada del menú | Apunta a | Debería apuntar a |
|---|---|---|
| Estaciones DELTA | `/serie-delta-ecoflow/` | categoría `serie-delta` |
| Baterías RIVER | `/serie-river/` | categoría `serie-river` |
| Casa y balcón | categoría `stream-series` | ya está bien |
| Powerbanks | `/serie-rapid/` | categoría `serie-rapid` |
| Placas solares | `/placas-solares-ecoflow/` | categoría `paneles-solares` |

Cada entrada del menú solo se cambia **cuando su categoría esté terminada**,
no antes: repuntar el menú a una categoría vacía de contenido sería peor que
dejarlo como está.

---

## ¿Y si la página trae visitas?

Es la duda razonable, y los datos de 16 meses la despejan:

| Página | Clics | Impresiones | Posición |
|---|---:|---:|---:|
| `/paneles-solares-portatiles/` | 28 | 9.394 | 16,3 |
| `/accesorios/` | 19 | 1.148 | 14,8 |
| `/placas-solares-ecoflow/` | 8 | 1.614 | 9,5 |
| `/serie-river/` | 4 | 763 | 10,7 |
| `/serie-delta-ecoflow/` | 4 | 278 | 7,2 |
| `/generadores/` | 2 | 313 | 10,0 |
| `/serie-rapid/` | 1 | 218 | 15,3 |
| `/baterias-extras/` | 1 | 25 | 17,3 |
| `/stream-series/` | **0** | 222 | 7,5 |
| **Total** | **67** | **13.975** | |

**67 clics en 16 meses entre las nueve: cuatro al mes.** No traen visitas,
traen impresiones: Google las enseña y casi nadie las pincha, porque en
posición 15 y con un título flojo no se gana el clic.

Tres cosas que conviene tener claras:

1. **No se borra nada, se redirige.** Un 301 traslada las señales de
   posicionamiento a la categoría. Borrar sin redirigir sí perdería lo poco
   que hay.
2. **El contenido tampoco se pierde: se muda.** Lo que dice la página pasa a
   ser la descripción de la categoría, como se hizo con DELTA 3.
3. **El riesgo no es lo que dejan de traer, es lo que cuestan.** Hoy página y
   categoría compiten por la misma consulta: en «accesorios» son 1.148
   impresiones contra 1.135, partidas por la mitad. Al unirlas, las señales
   se concentran en una sola URL.

### Dos páginas que NO se tocan

| Página | Clics | Por qué se queda |
|---|---:|---|
| `/man/` | **330** | La segunda del sitio tras la portada. No duplica ninguna categoría |
| `/kits-para-balcones/` | **82** | Es una página de caso de uso, no una gama |

La regla: se migra la página que **duplica una categoría**. La que aporta algo
que una categoría no puede dar —manuales, un kit por caso de uso, una guía—
se queda.

---

## Serie DELTA · hecho el 16/08/2026

| Qué | Estado |
|---|---|
| Categoría `serie-delta` con contenido, comparativa y FAQ | Hecho |
| Subcategoría redundante «Estaciones de energía DELTA» | Borrada |
| Menú principal → «Estaciones DELTA» | Ahora apunta a la categoría |
| Menú CATEGORÍAS → «Estaciones de energía DELTA» | Ahora apunta a la categoría |
| 301 de la página `/serie-delta-ecoflow/` | Hecho |

La página hija `/serie-delta-ecoflow/serie-delta-2/` sigue respondiendo 200:
despublicar la madre no rompe su URL. Se migrará cuando le toque a DELTA 2.

---

## Serie RIVER · hecho el 16/08/2026

RIVER 3 estaba **partida en dos**: una categoría suelta en primer nivel con
la RIVER 3 base, y otra llamada «RIVER 3 Plus» colgando de la serie con las
tres variantes. Unificadas en una sola `RIVER 3` dentro de la serie.

| Qué | Estado |
|---|---|
| Categoría `serie-river` con contenido, comparativa y FAQ | Hecho |
| `RIVER 3` unificada y colgando de la serie | Hecho |
| Categoría duplicada «RIVER 3 Plus» | Borrada |
| Menú principal y menú CATEGORÍAS | Apuntan a la categoría |
| 301 de `/serie-river/` | Hecho |

### Las preguntas salen de Search Console, no de la cabeza

Dos huecos que solo se ven mirando los datos:

- **`ecoflow river 3 plus + bat eb300`** (200 impresiones) y **`+ bat eb600`**
  (177) — la gente busca las combinaciones de batería sin saber que son los
  modelos Max y Max Plus. Tiene su propia pregunta explicando que
  286 + 286 = 572 Wh y 286 + 572 = 858 Wh.
- **Los manuales de RIVER suman 957 impresiones** repartidas en cinco
  consultas (`river 2 manual español`, `river manual`, `river 2 pro
  manual`…). La categoría enlaza a `/man/` desde una pregunta propia.

### Los enlaces de Elementor

Los enlaces a las dos páginas migradas estaban repartidos por la portada, el
pie y la página `/ecoflow/`, todos dentro de `_elementor_data`. Se
sustituyeron de una pasada.

Ojo con una cosa: **Elementor guarda su JSON también en las revisiones**, así
que una sustitución global toca cientos de registros. Es inocuo —las
revisiones no se sirven— pero conviene saberlo para no asustarse al ver el
informe.

Y al comprobar, cuidado con contar `/serie-river/` a pelo: la URL nueva
`/product-category/serie-river/` **termina igual**, así que un contador
ingenuo da falsos positivos. Hay que anclar la comprobación al dominio.

---

## Pendiente, por gama

| Página | Impresiones | Categoría que la sustituirá |
|---|---:|---|
| `/paneles-solares-portatiles/` | 9.394 | `paneles-solares` |
| `/placas-solares-ecoflow/` | 1.614 | `paneles-solares` |
| `/accesorios/` | 1.148 | `accesorios` |
| `/generadores/` | 313 | por decidir |
| `/serie-rapid/` | 218 | `serie-rapid` |
| `/stream-series/` | 222 | `stream-series` |
| `/baterias-extras/` | 25 | baterías adicionales |

`/paneles-solares-portatiles/` es la que más cuidado pide: con 9.394
impresiones es la segunda página del sitio, así que la categoría tiene que
estar terminada y con contenido **antes** de tocar la redirección.

---

## Código heredado que sigue vivo

En `minimog_options → custom_js` hay unos 30 bloques de JavaScript de
sesiones anteriores. Uno se ha eliminado; queda uno señalado:

| Bloque | Qué hace | Estado |
|---|---|---|
| `quitar()` | Borraba el widget de filtro de precios del DOM | **Eliminado** el 16/08/2026 |
| `marcar()` | Añade `rel="nofollow"` a los enlaces de filtro | Vivo, revisable |

El primero venía de cuando intentábamos frenar las URLs de filtro por
JavaScript. Desde que el `robots.txt` bloquea `filter_product_cat` y
`filtering` ya no hacía falta, y lo único que provocaba era que el filtro de
precios se pintara y desapareciera medio segundo después.

El segundo hace lo mismo por otra vía y tampoco es necesario ya. Además
monta un `MutationObserver` sobre todo el `body` que, en cada cambio del
DOM, recorre **todos** los enlaces de la página. Es el candidato más claro a
quitar si se busca velocidad, pero se deja hasta que David lo confirme.

Copia íntegra del `custom_js` anterior guardada en la opción
`eg_copia_custom_js` (39.451 caracteres).
