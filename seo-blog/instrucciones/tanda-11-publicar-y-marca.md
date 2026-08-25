# Tanda 11 · publicar, y luego la marca Hypershell entera

**25 de agosto de 2026.** El dueño ha dicho: **publica todo**. Y después, a por toda la
marca: categoría y productos.

---

## Decisión pendiente · la imagen destacada: **sí, la 9147**

Tenías razón y el archivo de meta estaba mal. Decía «sin imagen destacada, como el resto
del blog» y **las 24 entradas llevan una**, con su CSS a 980 px y borde redondeado. Fue un
error mío: confundí una petición antigua del dueño —quitar las fotos de portada del blog—
con algo que se hubiera aplicado, y no se aplicó.

**Ponle `exoesqueleto-que-hace.webp`, adjunto 9147.** Es la misma imagen que abre el
artículo y ya está en la biblioteca a 1.280 × 720. Una entrada que se comporta distinto a
las otras 24 es peor que cualquiera de las dos opciones que planteabas.

*(Lo de quitar las portadas del blog sigue anotado. Si el dueño lo quiere, es un cambio de
las 25 entradas a la vez, no de ésta sola. No es de esta tanda.)*

---

## Orden de publicación. **Este orden y no otro**

Tu freno era correcto: la categoría enlaza dos veces a la guía, y si la guía está en
borrador son dos enlaces rotos en la página con más intención de compra de la gama.

1. **Publicar la guía** `/que-es-un-exoesqueleto/`, con la 9147 de destacada.
   Comprobar que responde 200 con `?nc=1`.
2. **Aplicar la categoría** `hypershell`, por REST comparando bytes, sin tocar
   `<!--eg-corte-->`. Después, si tocas el Yoast del término, **reaplicar por REST y
   comprobar bytes** (norma 5).
3. **Las fichas del X Max S (8317) y del X Ultra S (8300)**, igual que hiciste con la del
   Pro S: bruto por `post.php`, nunca `wc/v3`, bytes comparados, y rescatando cualquier
   especificación de la descripción vieja que no esté en la nueva.
4. **El fragmento #38**, con las dos decisiones ya tomadas: fuera el peso en gramos, fuera
   el 63 % y el 20 %, y un enlace a la guía al final del bloque. El aviso de que no es
   producto sanitario **se queda en los dos sitios**.
5. **Purgar** LiteSpeed y WP Super Cache, y el punto de control final con los once puntos.

**Antes de terminar, dos comprobaciones que solo se pueden hacer con todo publicado:**

- Los dos enlaces de la categoría a `/que-es-un-exoesqueleto/` ya deben dar **200 directo**.
- **La banda negra de las fachadas.** La arreglaste en el blog con `margin:0` en
  `.eg-video-img`. La categoría también sirve imágenes dentro de contenido: comprueba que
  ahí no reaparece. Si reaparece, el arreglo tiene que alcanzar también a la categoría, no
  solo a `.single-post`.

Buen diagnóstico, por cierto: el margen de 36 px del tema sobre las imágenes de contenido
es justo el tipo de cosa que no se ve leyendo el HTML. Y bien hecho dejar escrito que la
explicación de los `<br>` era la equivocada.

---

## Y después: la marca Hypershell entera

Lo que hay hecho cubre **tres productos de nueve**, la categoría madre y la guía. Falta:

- Los **seis productos restantes** de la marca (accesorios y recambios).
- La categoría **`accesorios-hypershell`**, que hoy no tiene texto propio.
- Las **cuatro fichas de la generación anterior** —X Ultra, X Carbon, X Pro y X Go—, que
  no existen. Hay **82 unidades en el almacén sin una sola página** que las venda. Es lo
  que más dinero parado tiene de todo esto.

### Lo que necesito para escribirlo, y es una sola cosa

Mándame la **lista de los nueve productos Hypershell**, con esto por cada uno:

| Campo | Por qué |
|---|---|
| ID y nombre exacto | Para enlazar sin equivocarme de modelo |
| Slug | Para que ningún enlace pase por un 301 |
| `price` y `stock_status` | Norma 2 bis: `regular_price` viene vacío en los variables |
| Bytes de la descripción larga y corta | Para saber cuáles están vacías y cuáles hay que rescatar |
| Nº de shortcodes | Para no repetir lo del 24 de agosto |
| Si es variable, sus variaciones | Puede haber modelos escondidos dentro, como pasó con la batería 8350 |

Y, aparte, **las unidades en almacén de los cuatro modelos de la generación anterior**, si
constan en algún sitio.

Con eso escribo de una vez los seis accesorios, la categoría `accesorios-hypershell` y las
cuatro fichas antiguas, y no hacemos más rondas.

### Dos cosas que ya sé que van a hacer falta, para que las mires de paso

- **Las cuatro fichas antiguas salen en borrador**, no publicadas, y necesitan un párrafo
  que explique la diferencia entre generaciones. Es lo primero que va a buscar quien vea
  dos precios distintos de «Hypershell X Pro».
- **La batería (8350) es un producto variable** con variantes dentro. Su ficha es la que
  tiene que contestar la pregunta de los vatios-hora y la del avión, y hoy solo da
  5.000 mAh sin voltaje.

---

## Sigue pendiente del dueño

- **Dos fotos del taller de Rivas.** Ahora ya son **cinco páginas** las que venden la
  prueba en tienda, y ninguna tiene una foto.
- **El horario.** Todas dicen «avísanos antes de venir» y ninguna dice cuándo se abre.
- **El correo al fabricante**, escrito y listo en `seo-blog/correos/hypershell-material-video.md`.
  Pide los vídeos en bruto con permiso para alojarlos y, de paso, los tres datos que nos
  faltan: peso de los tres modelos, par del X Ultra S y capacidad en Wh de la batería.
