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

- `.eg-cat-lead` — dos o tres frases: qué es la gama y qué comparten todos
- `.eg-cat-regla` — la regla que resuelve la duda principal (en DELTA 3:
  «los Plus se amplían, los demás no»)
- `.eg-cat-nav` — tarjetas a las subcategorías, cada una con su gancho
- `.eg-cat-ayuda` — enlace a la comparativa y a contacto

**Abajo, después de la rejilla** — lo que convence y lo que posiciona:

- Tabla comparativa de la gama, con ancla `#eg-comparativa`
- «Cómo elegir», en tres preguntas ordenadas
- Preguntas frecuentes con schema `FAQPage`
- Tarjetas a las guías del blog y a `/man/`

El comprador ve producto enseguida y el texto largo no le estorba, que es
como lo montan las tiendas grandes.

---

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
