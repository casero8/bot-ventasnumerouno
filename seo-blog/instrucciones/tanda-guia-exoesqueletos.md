# Instrucciones para Claude en Chrome — tanda 10 · la guía del exoesqueleto

**Fecha:** 25 de agosto de 2026 · **Sitio:** ecogadgetoficial.com

Lee antes «Normas antes de tocar nada». De ellas, las que se van a cruzar en esta
tanda son la **2** (nunca leer descripciones de `wc/v3`), la **3** (los nombres de
archivo de imagen llevan sufijo de WordPress), la **7** (Opciones De Tema falla al
guardar una de cada dos veces), la **8** (barrido de rastros de IA), la **9**
(enlace directo, nunca a través de un 301), la **10** (garantía) y la **11** (`?nc=1`
antes de dar nada por roto).

---

## El encargo

Hypershell tiene una página propia, *Exoskeleton 101*, que explica qué es un
exoesqueleto. El dueño quiere lo mismo en su web, **con los vídeos**, y quiere salir
el primero en Google en español. Con una condición suya: **el texto tiene que ser
distinto para que Google no lo penalice por duplicado.**

El texto ya está escrito y está en el repositorio:

- `seo-blog/nuevos/10-que-es-un-exoesqueleto.html` — 27,0 KB, unas 3.000 palabras, 6 vídeos
- `seo-blog/nuevos/10-que-es-un-exoesqueleto-meta.txt` — título, meta, slug, autor
- `seo-blog/diseno-video.css` — el estilo de la fachada de vídeo
- `seo-blog/herramientas/video-fachada.js` — el JS de la fachada

**Tu trabajo no es reescribirlo. Es publicarlo bien.**

---

## Reglas de esta tanda

- **No copies nada de `eu.hypershell.tech`.** Ni un párrafo, ni un titular, ni el
  orden de sus secciones, ni sus testimonios: son personas reales con nombre, y
  reproducirlos sería duplicado *y* atribuirnos algo que no es nuestro. Si en algún
  momento piensas «esto lo explican mejor ellos», la respuesta es no.
- **Ni un precio.** Siguen congelados hasta que llegue el inventario con los EAN.
- **Ningún vídeo anterior a mayo de 2026.** Es la trampa en la que ya caímos: el canal
  oficial tiene tutoriales de la generación anterior que parecen actuales. Los seis IDs
  del paso 2 están verificados uno a uno contra su fecha de publicación. **No añadas ni
  sustituyas ninguno por tu cuenta.**
- **Garantía (norma 10).** En esta página **no se dice «servicio técnico propio» ni se
  enlaza `/servicio-tecnico-ecogadget/`**: eso es solo de EcoFlow. En Hypershell es
  **garantía del fabricante, 24 meses**, y lo único nuestro es que el trámite lo abrimos
  nosotros por ser distribuidor oficial. El texto ya está escrito así. **No lo
  «mejores» devolviéndolo a como estaba.**
- **Nada de `FAQPage` ni de `VideoObject`.** El `FAQPage` Google lo restringió a
  organismos públicos y sanitarios. El `VideoObject` sería reclamar como nuestros unos
  vídeos del fabricante, y eso sí es un problema de verdad. El `Article` que ya emite
  Yoast es todo lo que lleva.
- **Para en los tres puntos de control** y espera confirmación.

---

## Paso 1 · Dónde vive (ya está decidido; esto es el porqué)

Va como **entrada del blog**, slug `que-es-un-exoesqueleto`. No como página y no dentro
de la categoría. Dos motivos:

1. Todo el diseño del blog cuelga de `.single-post`. Publicado como página saldría sin
   formato y habría que duplicar 17 KB de CSS.
2. La intención de búsqueda es informativa («qué es», «cómo funciona»).
   `/product-category/hypershell/` ataca la de compra. Metidas en la misma URL, las dos
   intenciones se hacen daño. Separadas, la guía capta la búsqueda amplia y **empuja** a
   la categoría.

«Meterlo en Hypershell», que es lo que pidió el dueño, se resuelve en el paso 5.

---

## Paso 2 · Las seis miniaturas (va primero, y no es opcional)

Cada vídeo se pinta como **fachada**: una imagen y un botón. El `<iframe>` de YouTube no
existe hasta que alguien pulsa.

Las miniaturas **no pueden servirse desde `i.ytimg.com`**. La auditoría del banner de
cookies dejó comprobado que la web no hace ni una petición a terceros antes de que el
visitante acepte, y una imagen traída de un servidor de Google filtra su IP. Se
descargan y se suben a la biblioteca de medios.

Para cada uno, descarga `https://i.ytimg.com/vi/<ID>/maxresdefault.jpg`, conviértelo a
**WebP de 480 px de ancho** y súbelo con el nombre indicado:

| # | ID | Publicado | Nombre del archivo | Sección donde va |
|---|---|---|---|---|
| 1 | `8OypUvpzQ80` | 01/06/2026 | `exoesqueleto-hypershell-serie-x.webp` | Arriba del todo |
| 2 | `V_t5wTvyPEM` | 10/06/2026 | `exoesqueleto-asistencia-cadera.webp` | «Cómo te ayuda, paso a paso» |
| 3 | `NTQiukEf5kM` | 25/05/2026 | `exoesqueleto-como-se-pone.webp` | «Qué se siente el primer día» |
| 4 | `mhwxMT_LyYo` | 29/05/2026 | `exoesqueleto-pruebas-laboratorio.webp` | «Qué mirar antes de comprar» |
| 5 | `Hm3GWR9kiXE` | 23/05/2026 | `exoesqueleto-uso-diario.webp` | «Los modelos que hay» |
| 6 | `YKl0EP_xqoQ` | 23/06/2026 | `exoesqueleto-alta-montana.webp` | «Hasta dónde aguanta» |

Los seis son de la serie X actual y del canal oficial `youtube.com/@Hypershell_Tech`.

**Norma 3:** WordPress añade sufijos a los nombres al subir. **Copia la URL real de la
ficha de cada medio, no la escribas a ojo.** Ya rompimos dos fichas así.

Después sustituye en el HTML cada
`[[VERIFICAR: URL de la miniatura subida a la biblioteca de medios]]` por su URL real,
**en el orden de la tabla**, que es el orden en que aparecen. Los `alt` ya están
escritos: no los toques.

> **Punto de control 1.** Las seis URL finales y una captura de una ficha de medios.

---

## Paso 3 · El CSS y el JS de la fachada

- `seo-blog/diseno-video.css` → al final de **Apariencia → Personalizar → CSS
  adicional**. Es el sitio que gana por especificidad (norma 1).
- `seo-blog/herramientas/video-fachada.js` → al final de **Opciones De Tema → Código
  Personalizado → Custom JS**.

**Norma 7, que aquí es la que muerde:** guardar Opciones De Tema reescribe los 1.904
campos y falla una de cada dos veces. Antes de tocar, huella de todos los campos y copia
del valor actual de `custom_js`. Después de guardar, **relee del servidor** y comprueba
dos cosas: que el bloque nuevo está, y que **lo que había antes sigue estando**.

Es **un solo listener delegado para toda la web**: no hay que registrar nada por vídeo.
Si mañana se pone un vídeo en una ficha, funciona sin tocar nada.

Si al mirar el CSS descubres que **ya existe** una clase `.eg-vid` de un trabajo
anterior, no dupliques: quédate con la que está, dímelo y adaptamos el HTML.

---

## Paso 4 · Publicar la entrada

1. Entrada nueva. Título, slug, meta y autor están en el archivo `-meta.txt`.
   **Autor: EcoGadget**, nunca David.
2. **Sin imagen destacada**, como los otros nueve posts.
3. **Sin etiquetas**: las del blog se borraron porque generaban archivos vacíos indexables.
4. El contenido se pega **en modo HTML/código**, nunca en el editor visual: el visual se
   come los `<details>` y los `<figure>`.
5. **El comentario `<!-- ... -->` del principio del archivo NO se pega.** Son notas del
   repositorio. Empieza a copiar desde `<div class="eg-desc">`. (Norma 8.)
6. Busca `VERIFICAR` en lo pegado. **Si aparece una sola vez, no publiques.** Quedan
   ocho marcas en la tabla de modelos, además de las seis de las miniaturas.

### Las ocho marcas de la tabla de modelos

La tabla sale con huecos **a propósito**. Confirmado y sin confirmar:

| Dato | X Ultra S | X Max S | X Pro S |
|---|---|---|---|
| Potencia | 1.000 W ✔ | 800 W ✔ | **falta** |
| Autonomía por batería | 30 km ✔ | 17,5 km ✔ | **falta** |
| Baterías que admite | dos ✔ | **falta** | **falta** |
| Modos | 12 ✔ | 10 ✔ | **falta** |
| Palancas | fibra de carbono ✔ | PPA ✔ | PPA ✔ |
| Peso | **falta** | **falta** | **falta** |

Se rellenan **leyendo las fichas reales de la web**, no de memoria y no de la web del
fabricante. Si un dato no está en la ficha, **borra la fila entera**: una tabla con menos
filas es correcta, una tabla con un dato inventado es un problema (norma 10). Dime qué
filas has borrado.

**Norma 2:** lee el bruto desde `post.php?post=ID&action=edit`, **nunca por `wc/v3`**.
La API devuelve los shortcodes ya resueltos y guardarlos así destruye los `[eg_precio]`.
Ya pasó el 24 de agosto en dos fichas.

**Norma 2 bis:** si alguno de los tres modelos es producto variable, `regular_price`
vendrá vacío y no significa nada. Mira `price` y `stock_status`.

> **Punto de control 2.** La tabla rellena y la lista de filas borradas.

---

## Paso 5 · Meterlo «en Hypershell»

Sin esto la entrada nace huérfana. Tres sitios:

**5.1 · La categoría `/product-category/hypershell/`.** Al principio del texto SEO, que
va **debajo de la rejilla** (encima solo van migas, H1 y la fila de pastillas). Por REST
comparando bytes, y sin tocar el `<!--eg-corte-->` (norma 4):

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>¿Es la primera vez que miras un exoesqueleto?</strong> Antes de comparar modelos merece la pena entender qué hace de verdad el aparato y en qué casos no compensa. Lo hemos explicado con vídeos y sin tecnicismos: <a href="/que-es-un-exoesqueleto/">qué es un exoesqueleto y cómo ayuda al caminar</a>.</p>
</div>
```

Si después tocas el título o la meta de Yoast de esa categoría, **vuelve a aplicar la
descripción por REST y comprueba los bytes** (norma 5).

**5.2 · Las tres fichas con stock.** Una línea al final de la descripción larga de cada
una. **Que no sea la misma frase en las tres** —Google lee texto idéntico en tres URLs
como lo que es—, así que varía el arranque:

- «Si es tu primer exoesqueleto, aquí explicamos [qué hace y qué no hace un exoesqueleto](/que-es-un-exoesqueleto/), con vídeos.»
- «Antes de decidirte, en [esta guía](/que-es-un-exoesqueleto/) contamos cómo asiste cada paso y para quién no merece la pena.»
- «Dudas de si te encaja: [la guía del exoesqueleto](/que-es-un-exoesqueleto/) resuelve las ocho preguntas que más nos hacen.»

**5.3 · El fragmento #38**, el que pinta «Nunca has llevado un exoesqueleto: por dónde se
empieza». Añade un enlace a la guía al final del bloque, dentro del propio fragmento. Y
**quita del fragmento lo que se solape**: si repite las cifras del 63 % y el 20 %, déjalas
solo en un sitio. **El aviso de que no es producto sanitario se queda en los dos** — eso
sí se repite a propósito.

---

## Paso 6 · Comprobar

Todo con `?nc=1` detrás, que LiteSpeed sigue sirviendo páginas viejas después de purgar
(norma 11):

1. La entrada se ve maquetada. Si sale texto plano, el CSS de `.single-post` no la alcanza.
2. **Los seis vídeos:** con la pestaña de Red abierta y filtro `youtube`, cargar la página
   **no debe generar ni una petición**. Al pulsar uno, aparece la de `youtube-nocookie.com`
   y el vídeo arranca solo.
3. Pulsa un segundo vídeo con el primero abierto: los dos deben convivir.
4. En móvil, que los vídeos no se salgan del ancho y las tablas se puedan deslizar.
5. **Los cinco enlaces internos** de la entrada: ninguno puede devolver un 301 (norma 9).
   Son `/product-category/hypershell/`, `/product-category/accesorios-hypershell/`,
   `/contacto/` (dos veces) y `/man/`.
6. `/que-es-un-exoesqueleto/` responde 200 y Yoast tiene el título y la meta del archivo,
   sin recortar.
7. **Barrido de rastros de IA** (norma 8) sobre la entrada publicada y sobre la categoría:
   cero resultados. Recuerda el falso positivo: «Inteligencia Artificial» en Hypershell es
   la HyperIntuition™ del producto y **se queda**.
8. Busca `VERIFICAR` en el HTML del front. Cero.
9. Vaciar LiteSpeed y WP Super Cache.

> **Punto de control 3.** El informe con los nueve puntos y una captura del panel de Red
> antes y después de pulsar el primer vídeo.

---

## Lo que NO se hace en esta tanda

- No tocar ninguna ficha más allá de la línea del paso 5.2.
- No crear categorías ni cambiar el padre de ninguna.
- **No meter la guía en el menú principal todavía.** Primero que Google la indexe y veamos
  qué posición coge. Metida ahora, se lleva enlaces internos que hoy necesitan las
  categorías.
- No mandar la URL a indexar a mano más de una vez.
- No abrir nada con Elementor.

---

## Lo que queda pendiente de esto

- Los **82 exoesqueletos de la generación anterior** siguen sin ficha. La guía habla de la
  serie actual; cuando esas cuatro fichas existan hará falta un párrafo explicando la
  diferencia entre generaciones, que es justo lo que va a buscar quien vea dos precios.
- **Dos fotos reales del taller de Rivas.** La sección «Pruébatelo antes de decidir» es el
  mayor diferenciador frente a la web del fabricante —ellos no tienen dónde probarlo en
  España— y va sin una sola foto.
- **El horario de la tienda.** El texto dice «sin cita» pero no dice cuándo se puede ir.
