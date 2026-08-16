# Plantilla de categoría · cómo se monta una

Primera aplicada: **DELTA 3** (16/08/2026). Este es el patrón para repetir
en el resto de gamas.

---

## Estructura de la taxonomía

Tres niveles como máximo, y la categoría padre muestra todo lo de las hijas
(WordPress incluye los descendientes en el archivo, por eso DELTA 3 lista 23
productos aunque solo 7 estén asignados directamente).

```
Estaciones de energía portátiles DELTA        (serie-delta)
└── DELTA 3                                   (delta-3)
    ├── DELTA 3 Plus
    ├── DELTA 3 Max
    ├── DELTA 3 Max Plus
    ├── Batería adicional DELTA 3
    └── Accesorios DELTA 3
```

Antes de esto, DELTA 3 Plus, Max y Max Plus eran **hermanas** de DELTA 3, no
hijas: quien entraba en «DELTA 3» no veía la Plus, que es la que tiene stock
a 849 €.

---

## Reglas al ordenar una gama

1. **Los modelos cuelgan de su serie.** Si la gama se llama DELTA 3, todo lo
   que empiece por «DELTA 3» va debajo.
2. **Una sola categoría por concepto.** Las baterías adicionales de toda la
   serie van juntas, no una por modelo.
3. **Nombres coherentes.** `DELTA 3 Plus`, no `Delta 3 Plus` ni
   `DELTA 3 PLUS`. El nombre se edita sin tocar el slug, así no se rompe
   ninguna URL.
4. **Cada producto en la categoría que le corresponde.** Una DELTA Pro 3 no
   va en DELTA 3, y un microinversor no es un accesorio de DELTA 3.
5. **No inventar subcategorías de un producto.** Si una subcategoría tiene un
   solo artículo, sobra.

---

## Contenido de la categoría

Se guarda en la descripción del término, partida en dos por el marcador
`<!--eg-corte-->`:

**Arriba, antes de la rejilla** — lo que necesita quien acaba de aterrizar:

- `.eg-hero` — panel de entrada: dos frases y cuatro datos en pastillas
  (potencia, ciclos, SAI, garantía). Es el gancho visual
- `.eg-regla` — la regla que resuelve la duda principal, en verde (en
  DELTA 3: «los Plus se amplían, los demás no»)
- `.eg-cat-nav` — tarjetas **con foto de producto** a cada subcategoría,
  con su dato y su botón. Es lo que más cambia la percepción de la página
- `.eg-cat-ayuda` — enlace a la comparativa y a contacto

**Abajo, después de la rejilla** — lo que convence y lo que posiciona:

- Tabla comparativa con cabecera azul, la fila recomendada destacada y
  etiquetas («Disponible», «La que más se lleva»). Ancla `#eg-comparativa`
- «Cómo elegir», en tres tarjetas numeradas
- Preguntas frecuentes con schema `FAQPage`
- Tarjetas a las guías del blog y a `/man/`
- Franja de confianza: oficial, servicio técnico, envío, asesoramiento

Solo se enlazan productos que se pueden comprar. Los agotados van como
«Consúltanos» hacia contacto, que genera aviso en vez de callejón sin salida.

El comprador ve producto enseguida y el texto largo no le estorba, que es
como lo montan las tiendas grandes.

---

## La cabecera de la categoría

El tema deja **100 px de margen** entre la cabecera del sitio y el contenido
(`.page-content { margin-top: 100px }`). En una categoría eso es media
pantalla en blanco antes de ver nada. Se baja a 28 px.

El `<h1>` viene en **34 px con peso 400**, exactamente los mismos que el
rótulo «Filtros» de la barra lateral: los dos compiten y ninguno parece el
título de la página. El H1 pasa a peso 600 con interletraje ajustado, y
«Filtros» a rótulo de interfaz: 12,5 px, mayúsculas, gris y con una línea
debajo.

**No hay migas de pan.** El tema solo las pone en el schema, y la plantilla
trae `--breadcrumb-height` a 0. Las imprime el snippet como hermano del
`<h1>` y se suben con `order: -1`, porque el gancho de WooCommerce corre
después de que el tema pinte el título.

El contador de productos usa `$wp_query->found_posts`, no `$termino->count`:
el segundo solo cuenta lo asignado directamente y en DELTA 3 daba 6 en vez
de 23.

## Lo que se puede comprar, primero

Por defecto la rejilla ordena por menú y en DELTA 3 llenaba la primera fila
entera de «AGOTADO». Un filtro sobre `posts_clauses` sube los productos con
stock, respetando el orden dentro de cada grupo y sin tocar nada si el
visitante elige un orden concreto en el desplegable.

## El filtro lateral, acotado

El widget del tema listaba **las 59 categorías de la tienda dentro de
cualquier categoría**: estando en DELTA 3 ofrecía Lokithor, TRAIL DC o
Automoción. Ahora enseña la rama en la que estás:

```
Dentro de DELTA 3
‹ Estaciones de energía portátiles DELTA
DELTA 3                        ← la actual, en negrita
Accesorios DELTA 3          2
Batería adicional DELTA 3   2
DELTA 3 Max                 4
DELTA 3 Max Plus            6
DELTA 3 Plus                5
```

Si la categoría no tiene hijas se muestran **las hermanas** («Más de
DELTA 3»), para saltar entre modelos de la misma gama sin volver atrás.

Se hace con `widget_display_callback`, que corre **antes** de que el widget
pinte nada: se imprime lo nuestro con los `before_widget` / `after_widget`
que da WordPress y se devuelve `false` para que el original no salga. Así
queda en su mismo sitio y en `/shop/` sigue apareciendo el listado completo,
que ahí sí tiene sentido.

## El panel de filtros, y el móvil

En escritorio cada bloque de filtro es una **tarjeta con borde**: antes eran
listas de texto una detrás de otra y no se veía dónde acababa una y empezaba
la siguiente. Las filas tienen área de pulsación amplia y fondo al pasar por
encima, y el contador va alineado a la derecha.

**En móvil el tema dejaba los filtros al final de la página**, después de
toda la rejilla: para filtrar había que pasar varias pantallas. Ahora suben
por encima de los productos y van plegados detrás de un botón **«Filtrar y
afinar»**, que es como lo resuelven las tiendas grandes.

Tres cosas que costaron encontrar:

**LiteSpeed retrasa el JavaScript en línea.** Convertía el script del botón
en `type="litespeed/javascript"` y no lo ejecutaba hasta que había
interacción: mientras tanto los filtros quedaban ocultos y sin botón que los
abriera. Se arregla con `data-no-optimize="1"` y `data-no-defer="1"` en la
etiqueta.

**Cuidado con plegar desde JavaScript: se ve el parpadeo.** El primer
montaje ocultaba el panel añadiendo una clase desde el script, y como el
script corre al final del documento, los filtros se pintaban y desaparecían
medio segundo después, justo encima de los productos. Muy visible.

Lo correcto es **pintarlo ya plegado desde el CSS** y dejar el respaldo en un
`<noscript>` que los muestra y esconde el botón. Comprobado midiendo el
`display` del panel durante toda la carga: con JavaScript sale `none` desde
el primer fotograma, y sin JavaScript los filtros se ven enteros.

**Ocultar el texto de un widget no basta.** Al esconder solo el `h6` del
rótulo «Filtros», su envoltorio seguía ocupando casi 50 px en blanco. Hay
que ocultar el widget entero.

Y ojo con el orden de las reglas: la regla general del lateral pone los
enlaces en `display: block`, y eso aplastaba el contador contra el nombre en
el filtro acotado. La corrección tiene que ir **después**, y fuera de la
consulta de medios, o solo se arregla en un tamaño de pantalla.

## Dónde vive el JavaScript propio del sitio

No todo está en Code Snippets. El tema Minimog guarda un campo
**`custom_js` dentro de la opción `minimog_options`** con casi 39 KB de
JavaScript acumulado: unos 30 bloques que tocan el precio, las pestañas de
la ficha, el pie, el menú móvil y el «comprado conjuntamente».

Cuando algo se comporte de forma rara y no aparezca en ningún snippet,
**hay que mirar ahí**. Para localizar quién manipula un elemento, lo más
rápido es interceptar `removeChild` y `remove` con un `addInitScript` y
volcar la traza: eso da el nombre de la función culpable en un intento.

Antes de tocar `custom_js` hay que guardar copia íntegra —son ajustes del
tema— y borrar después `minimog_options-transients`, que es su caché.

## La franja «Disponible ahora»

Encima de la rejilla, cuatro tarjetas con **foto, nombre, precio y botón de
añadir al carrito**. Es lo que hacen las tiendas grandes: el producto y el
precio arriba, no a tres pantallas de scroll.

Se genera **leyendo el stock y el precio reales en cada carga**, no escrito
a mano en la descripción. Escribirlo a mano sería más rápido, pero un precio
desfasado en la web no es un detalle: es un problema.

Detalles que importan:

- **Ordenada de menor a mayor precio.** La primera tarjeta es la puerta de
  entrada más accesible de la gama, no la más cara. En DELTA 3 abre con la
  Classic de 599 € en vez de con el pack de 1.148 €.
- **`include_children` explícito** en la consulta: sin él se quedaban fuera
  los productos que solo están en una subcategoría, que en DELTA 3 son
  justamente dos de los cuatro disponibles.
- **Solo aparece si la categoría tiene 6 productos o más.** En una
  subcategoría pequeña la rejilla ya cabe en pantalla y la franja sería
  repetir lo mismo dos veces.
- El botón usa `add_to_cart_url()` y `add_to_cart_text()` de WooCommerce, que
  ya distinguen entre producto simple y variable.

## Escribir para vender, no solo para posicionar

El texto informativo posiciona pero no empuja. Tres cambios que sí:

**Abrir por la escena, no por la ficha técnica.** En vez de «las DELTA 3 son
estaciones de energía de 1.024 a 2.048 Wh», ahora abre con *«se va la luz y
en tu casa no pasa nada: la nevera sigue, el router sigue y el ordenador ni
parpadea»*. El dato viene después, cuando ya sabes para qué lo quieres.

**Precio en las tarjetas de subcategoría**, con el shortcode
`[eg_desde cat="slug"]`. Calcula el más bajo entre los productos comprables
de esa categoría y sus hijas, con media hora de caché. **Si no hay nada
disponible no devuelve nada**: no se anuncia un precio de algo que no se
puede comprar.

**Un cierre que pide algo.** Antes acababa explicando quiénes somos. Ahora
acaba con *«antes de gastarte 600 u 800 euros, hablemos»* y un botón. Y dice
en voz alta que a veces la recomendación es la más barata —«muchas veces es
la Classic y te ahorras 250 €»—, que es lo que hace creíble el resto.

Lo que **no** se hace, y conviene recordarlo: nada de reseñas inventadas,
nada de escasez falsa, y las estimaciones marcadas como tales con la fórmula
a la vista.

## Cosas del sitio que hay que saber

**El tema no imprime `term_description()`.** El texto guardado solo acababa
en `og:description`. Lo pinta el snippet *EG · Descripcion de categorias*,
enganchado a `woocommerce_before_shop_loop` y `woocommerce_after_shop_loop`.
Ese snippet también carga el CSS, solo en las categorías.

**Ni `wc/v3` ni `wp/v2` dejan guardar HTML en la descripción.** Los dos
controladores pasan el texto por `wp_filter_kses` antes de que ningún filtro
pueda intervenir: en una prueba quedaron 57 caracteres de 334, sin tablas ni
schema. Hay que guardar con `wp_update_term()` desde un snippet, quitando
antes `pre_term_description`.

**El SEO de una categoría no está en el meta del término.** Yoast lo guarda
en la opción `wpseo_taxonomy_meta`. Y hay que escribir las tres claves de
una sola vez: llamando a `set_value()` tres veces seguidas, cada llamada
relee la opción desde la caché y solo sobrevive la última.

**Después de tocar esa opción hay que borrar la fila del indexable**
(`wp_yoast_indexable`, `object_type = 'term'`). Es la tabla que manda al
pintar el `<title>` y no se entera de un cambio hecho por debajo.

**El texto no se repite en `/page/2/`.** El snippet lo corta con `is_paged()`
para no duplicar contenido dentro del propio sitio.

**Nada de `wpautop`.** El texto de las categorías ya viene con sus propios
`<p>`, y `wpautop` convierte los saltos de línea del maquetado en `<br />`
que se cuelan dentro de las tarjetas y las descuadran.

**Las imágenes las hace lazy LiteSpeed**, sustituyendo el `src` por un SVG
en base64 y dejando el real en `data-src`. Es lo normal; no hay que pelearse
con ello, pero conviene saberlo al comprobar el HTML.

---

## Al terminar: no dejar restos de prueba

Para averiguar qué HTML sobrevive al guardado hubo que escribir descripciones
de prueba en una categoría real (`accesorios-delta-3`), y se quedaron
publicadas: una tabla con «a» y «b» y un desplegable con una «P». Se veían
en la tienda.

Antes de cerrar, repasar que ninguna categoría tenga restos:

```
GET /wp-json/wc/v3/products/categories?per_page=100&hide_empty=false&_fields=id,slug,description
```

y buscar `class="eg-t"`, `class="eg-faq"`, `>a</th>` o la palabra «prueba».
Mejor todavía: hacer los experimentos sobre una categoría creada para eso y
borrarla después, no sobre una que está publicada.

## Comprobación antes de dar una categoría por hecha

- [ ] Las subcategorías responden 200 en su ruta nueva
- [ ] El bloque de arriba sale entre el H1 y la rejilla
- [ ] El bloque de abajo sale después de la paginación
- [ ] No hay un `</p>` suelto al principio del bloque de abajo
- [ ] La tabla comparativa solo enlaza productos que se pueden comprar
- [ ] El `<title>` de Yoast es el nuevo, no el del tema
- [ ] La meta description lleva los acentos puestos
- [ ] Las FAQ salen con `FAQPage` en el HTML
- [ ] No hay `<br />` sueltos dentro de las tarjetas
- [ ] Las fotos de las tarjetas son del producto suelto, no de un pack
- [ ] Se ve bien a 390 px de ancho
- [ ] En móvil el botón «Filtrar y afinar» aparece **encima** de los
      productos, abre y cierra, y los contadores quedan a la derecha
- [ ] Con el JavaScript desactivado los filtros siguen viéndose
- [ ] Al recargar, el panel **no parpadea**: nace plegado, no se pliega
      después
- [ ] Los primeros productos de la rejilla son los que tienen stock
- [ ] La barra lateral sigue en una sola columna. **El tema maqueta las
      listas del lateral en varias columnas**: cualquier lista propia que se
      meta ahí necesita `columns: 1 !important`, y ponerles `max-height` con
      scroll las parte en dos columnas superpuestas
- [ ] El filtro lateral solo ofrece categorías de la rama actual
- [ ] En `/shop/` el filtro sigue mostrando el listado completo
- [ ] Ninguna categoría ha quedado con HTML de prueba en su descripción
