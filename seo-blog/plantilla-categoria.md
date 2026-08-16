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
- [ ] Los primeros productos de la rejilla son los que tienen stock
- [ ] La barra lateral sigue en una sola columna (ojo: ponerle `max-height`
      a las listas de filtro las parte en dos columnas superpuestas, porque
      el tema las maqueta con columnas)
