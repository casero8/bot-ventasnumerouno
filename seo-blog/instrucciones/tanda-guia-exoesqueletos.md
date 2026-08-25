# Instrucciones para Claude en Chrome — tanda 10 · la guía del exoesqueleto

**Revisión B, 25 de agosto de 2026.** Esta versión sustituye a la anterior. Los tres
frenos del informe eran correctos y **los tres están resueltos aquí**: dos eran errores
míos en el texto, ya corregidos en el archivo, y el tercero cambia un paso entero.

Lee antes «Normas antes de tocar nada». Las que se cruzan en esta tanda: la **2** (nunca
leer descripciones por `wc/v3`), la **3** (los nombres de archivo llevan sufijo de
WordPress), la **4** y la **5** (descripciones de categoría por REST, y Yoast después),
la **8** (barrido de rastros de IA), la **9** (nunca a través de un 301), la **10**
(garantía) y la **11** (`?nc=1` antes de dar nada por roto).

---

## Paso 0 · Los archivos

El repositorio es público. **Ábrelos en una pestaña, no hacen falta descargas:**

- Texto del artículo:
  `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto.html`
- Título, meta, slug y autor:
  `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto-meta.txt`

Ya no hay un tercer ni un cuarto archivo. **`diseno-video.css` y `video-fachada.js`
están borrados a propósito**: ver el paso 3.

---

## El encargo

Hypershell tiene una página propia, *Exoskeleton 101*, que explica qué es un
exoesqueleto. El dueño quiere lo mismo en su web, **con los vídeos**, y quiere salir
primero en Google en español. Con una condición suya: **el texto tiene que ser distinto
para que Google no lo penalice por duplicado.**

El texto ya está escrito: 26,6 KB, unas 3.000 palabras, seis vídeos. **Tu trabajo no es
reescribirlo, es publicarlo bien.**

---

## Reglas de esta tanda

- **No copies nada de `eu.hypershell.tech`.** Ni un párrafo, ni un titular, ni el orden
  de sus secciones, ni sus testimonios: son personas reales con nombre, y reproducirlos
  sería duplicado *y* atribuirnos algo que no es nuestro.
- **Ni un precio.** Congelados hasta que llegue el inventario con los EAN.
- **Ningún vídeo anterior a mayo de 2026.** El canal oficial tiene tutoriales de la
  generación anterior que parecen actuales. Los seis IDs del paso 2 están verificados
  contra su fecha de publicación. **No añadas ni sustituyas ninguno.**
- **Garantía (norma 10).** Aquí no se dice «servicio técnico propio» ni se enlaza
  `/servicio-tecnico-ecogadget/`: eso es solo de EcoFlow. En Hypershell es **garantía del
  fabricante, 24 meses**, y lo único nuestro es que abrimos el trámite por ser
  distribuidor oficial. El texto ya está escrito así. **No lo «mejores» devolviéndolo a
  como estaba.**
- **Nada de `FAQPage` ni de `VideoObject`.** El primero Google lo restringió a organismos
  públicos y sanitarios. El segundo sería reclamar como nuestros unos vídeos del
  fabricante, y eso sí es un problema real. Basta el `Article` de Yoast.
- **Para en los tres puntos de control.**

---

## Paso 1 · Dónde vive

**Entrada del blog**, slug `que-es-un-exoesqueleto` (comprobado: da 404, está libre).
No como página y no dentro de la categoría. Dos motivos:

1. El diseño del blog cuelga de `.single-post`. Como página saldría sin formato.
2. La intención es informativa; `/product-category/hypershell/` ataca la de compra.
   Juntas en una URL se hacen daño; separadas, la guía capta la búsqueda amplia y
   **empuja** a la categoría.

«Meterlo en Hypershell» se resuelve en el paso 5.

---

## Paso 2 · Las seis miniaturas (primero, y no es opcional)

Las miniaturas **no pueden servirse desde `i.ytimg.com`**: la auditoría del banner de
cookies dejó comprobado que la web no hace ni una petición a terceros antes de aceptar, y
una imagen de un servidor de Google filtra la IP del visitante.

Descarga `https://i.ytimg.com/vi/<ID>/maxresdefault.jpg`, conviértelo a **WebP de 480 px
de ancho** y súbelo con este nombre:

| # | ID | Publicado | Nombre del archivo | Sección |
|---|---|---|---|---|
| 1 | `8OypUvpzQ80` | 01/06/2026 | `exoesqueleto-hypershell-serie-x.webp` | Arriba del todo |
| 2 | `V_t5wTvyPEM` | 10/06/2026 | `exoesqueleto-asistencia-cadera.webp` | «Cómo te ayuda, paso a paso» |
| 3 | `NTQiukEf5kM` | 25/05/2026 | `exoesqueleto-como-se-pone.webp` | «Qué se siente el primer día» |
| 4 | `mhwxMT_LyYo` | 29/05/2026 | `exoesqueleto-pruebas-laboratorio.webp` | «Qué mirar antes de comprar» |
| 5 | `Hm3GWR9kiXE` | 23/05/2026 | `exoesqueleto-uso-diario.webp` | «Los modelos que hay» |
| 6 | `YKl0EP_xqoQ` | 23/06/2026 | `exoesqueleto-alta-montana.webp` | «Hasta dónde aguanta» |

**Norma 3:** WordPress añade sufijos al subir. **Copia la URL real de la ficha de cada
medio, no la escribas a ojo.** Ya rompimos dos fichas así.

> **Punto de control 1.** Las seis URL finales.

---

## Paso 3 · Los vídeos van con la fachada QUE YA EXISTE

**Este paso cambia entero respecto a la revisión A.** Tenías razón: `.eg-video`,
`.eg-video-fachada`, `.eg-video-img`, `.eg-video-play`, `.eg-video-iframe` y
`.eg-video-pie` ya viven en el Personalizador desde el 20 de agosto, y el JS del
disparador también. Meter un CSS nuevo duplicaría seis clases en un campo de 129 KB
servido en el `<head>` de las 158 fichas, y abrir Opciones De Tema —el guardado que
falla una de cada dos veces y reescribe 1.904 campos— para añadir un listener que ya
existe sería asumir el mayor riesgo de la tanda a cambio de nada.

**Los dos archivos están borrados del repositorio. No se toca ni el Personalizador ni
Opciones De Tema en toda esta tanda.**

En su lugar, el artículo trae seis líneas así:

```
[[VERIFICAR: VÍDEO 1 · sustituir esta línea entera por el bloque de vídeo que YA usa la
web (.eg-video / .eg-video-fachada), copiado de una página en vivo · ID: 8OypUvpzQ80 ·
aria-label: «...» · alt de la miniatura: «...» · pie: «...»]]
```

Cómo se rellenan:

1. Abre una página en vivo que ya tenga un vídeo (portada o una entrada del blog) y
   **copia el bloque HTML completo** de ese vídeo, tal cual, con sus clases y sus
   atributos `data-`.
2. Úsalo como plantilla para los seis. En cada uno sustituye **solo** cuatro cosas: el ID
   del vídeo, la URL de la miniatura del paso 2, el `alt` y el pie. Los tres textos están
   escritos dentro del propio marcador.
3. Si el bloque de la web no admite pie de foto, dímelo antes de inventarte uno: los seis
   pies dicen «Vídeo del fabricante», y **esa atribución no se puede perder**.

> **Punto de control 2.** Pégame el bloque plantilla que has copiado y el primero de los
> seis ya montado, antes de hacer los otros cinco.

---

## Paso 4 · La tabla de modelos

**Corregida.** Tenías razón: yo tenía el Max S y el Pro S cruzados. El archivo ya sale
con los números de las fichas:

| | X Ultra S | X Max S | X Pro S |
|---|---|---|---|
| Potencia | 1.000 W | 1.000 W | 800 W |
| Par | **falta** | 22 Nm | 18 Nm |
| Autonomía | 30 km | 30 km | 17,5 km |
| Palancas | fibra de carbono | PPA | PPA |
| Peso | ~2,5 kg | **falta** | **falta** |

Las filas de **modos de terreno** y de **baterías que admite** las he borrado: no están en
ninguna ficha y no voy a publicar un dato que no puedo sostener.

Quedan **tres huecos**, y se rellenan **solo desde las fichas reales**:

- El par del Ultra S.
- El peso del Max S y el del Pro S.

**Sobre los pesos al gramo del fragmento #38** (2.585 / 2.571 / 2.538 g): decisión del
dueño, y mientras no la dé, **la fila del peso se queda como está**, con el «~2,5 kg» del
Ultra S que sí sale de su ficha y los otros dos vacíos. Si el dueño dice que valen,
compruébalo primero: el #38 tiene que **mapear cada cifra a su modelo sin ambigüedad**. Si
no lo hace, borra la fila entera. Una tabla con menos filas es correcta; una tabla con un
peso asignado al modelo equivocado es exactamente el fallo que acabas de encontrar.

**Hay un cuarto hueco, fuera de la tabla:** la respuesta sobre llevarlo en avión dice
«las baterías declaran [[VERIFICAR: capacidad en Wh]]». Toda esa respuesta se apoya en ese
número. Si no está en las fichas ni en la ficha técnica oficial, sustituye la frase por:

> «La capacidad exacta la tienes en la ficha de tu modelo, en vatios-hora. La norma
> general de aviación permite baterías de litio de menos de 100 Wh en cabina sin
> autorización previa.»

**Norma 2:** lee el bruto desde `post.php?post=ID&action=edit`, **nunca por `wc/v3`**: la
API devuelve los shortcodes resueltos y guardarlos así destruye los `[eg_precio]`.
**Norma 2 bis:** si algún modelo es variable, `regular_price` vendrá vacío y no significa
nada; mira `price` y `stock_status`.

---

## Paso 5 · Publicar

1. Entrada nueva. Título, slug, meta y autor, en el `-meta.txt`. **Autor: EcoGadget**,
   nunca David.
2. **Sin imagen destacada** y **sin etiquetas** (las del blog se borraron porque generaban
   archivos vacíos indexables).
3. Se pega **en modo HTML**, nunca en el editor visual: se come los `<details>`.
4. **El comentario `<!-- ... -->` del principio del archivo NO se pega.** Son notas del
   repositorio. Empieza en `<div class="eg-desc">`. (Norma 8.)
5. Busca `VERIFICAR` en lo pegado. **Si aparece una sola vez, no publiques.**

---

## Paso 6 · Meterlo «en Hypershell»

**6.1 · La categoría `/product-category/hypershell/`.** Al principio del texto SEO, que va
**debajo de la rejilla** (encima solo migas, H1 y fila de pastillas). Por REST comparando
bytes y sin tocar el `<!--eg-corte-->` (norma 4):

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>¿Es la primera vez que miras un exoesqueleto?</strong> Antes de comparar modelos merece la pena entender qué hace de verdad el aparato y en qué casos no compensa. Lo hemos explicado con vídeos y sin tecnicismos: <a href="/que-es-un-exoesqueleto/">qué es un exoesqueleto y cómo ayuda al caminar</a>.</p>
</div>
```

Si después tocas el Yoast de esa categoría, **vuelve a aplicar la descripción por REST y
comprueba los bytes** (norma 5).

**6.2 · Las tres fichas.** Una línea al final de la descripción larga de cada una. **Que
no sea la misma frase en las tres**:

- «Si es tu primer exoesqueleto, aquí explicamos [qué hace y qué no hace un exoesqueleto](/que-es-un-exoesqueleto/), con vídeos.»
- «Antes de decidirte, en [esta guía](/que-es-un-exoesqueleto/) contamos cómo asiste cada paso y para quién no merece la pena.»
- «Dudas de si te encaja: [la guía del exoesqueleto](/que-es-un-exoesqueleto/) resuelve las ocho preguntas que más nos hacen.»

**6.3 · El fragmento #38.** Un enlace a la guía al final del bloque. Y **quita lo que se
solape**: si repite las cifras del 63 % y el 20 %, déjalas en un sitio. **El aviso de que
no es producto sanitario se queda en los dos** — eso se repite a propósito.

---

## Paso 7 · Comprobar

Todo con `?nc=1` detrás (norma 11):

1. La entrada se ve maquetada. Si sale texto plano, el CSS de `.single-post` no la alcanza.
2. **Los seis vídeos:** con Red abierta y filtro `youtube`, cargar la página **no genera
   ni una petición**. Al pulsar uno, aparece la de `youtube-nocookie.com` y arranca solo.
3. Pulsa un segundo con el primero abierto: deben convivir.
4. En móvil, que los vídeos no se salgan del ancho y las tablas se deslicen.
5. **Los cuatro enlaces internos**, ninguno puede devolver un 301 (norma 9):
   `/product-category/hypershell/`,
   `/product-category/hypershell/accesorios-hypershell/` ← **ya corregido, era el del
   301 que encontraste**, `/contacto/` (dos veces) y `/man/`.
6. `/que-es-un-exoesqueleto/` responde 200 y Yoast tiene título y meta sin recortar.
7. **Barrido de rastros de IA** (norma 8) en la entrada y en la categoría. Falso positivo
   conocido: «Inteligencia Artificial» en Hypershell es la HyperIntuition™ y **se queda**.
8. `VERIFICAR` en el HTML del front: cero.
9. Vaciar LiteSpeed y WP Super Cache.

> **Punto de control 3.** El informe de los nueve puntos y una captura del panel de Red
> antes y después de pulsar el primer vídeo.

---

## Lo que NO se hace

- **No se toca el CSS del Personalizador ni Opciones De Tema.** En ningún paso.
- No se toca ninguna ficha más allá de la línea del 6.2.
- No se crean categorías ni se cambia el padre de ninguna.
- **La guía no entra en el menú principal todavía.** Primero que Google la indexe y veamos
  qué posición coge; metida ahora se lleva enlaces internos que hoy necesitan las
  categorías.
- No mandar la URL a indexar a mano más de una vez.
- Nada de Elementor.

---

## Pendiente, para después

- Los **82 exoesqueletos de la generación anterior** siguen sin ficha. Cuando existan hará
  falta un párrafo sobre la diferencia entre generaciones, que es lo que va a buscar quien
  vea dos precios distintos.
- **Dos fotos del taller de Rivas.** «Pruébatelo antes de decidir» es el diferenciador que
  el fabricante no puede copiar —no tienen dónde probarlo en España— y va sin una foto.
- **El horario de la tienda.** El texto dice «sin cita» pero no dice cuándo se puede ir.
