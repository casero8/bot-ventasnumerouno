# Instrucciones para Claude en Chrome — tanda 10 · la guía del exoesqueleto

**Fecha:** 24 de agosto de 2026 · **Sitio:** ecogadgetoficial.com

## El encargo, en una línea

Hypershell tiene una página propia, *Exoskeleton 101*, que explica qué es un
exoesqueleto y para qué sirve. El dueño quiere **lo mismo pero en su web y con los
vídeos**, y quiere ser el primer resultado de Google cuando alguien busque esto en
español. Con una condición que él mismo puso: **el texto tiene que ser distinto, para
que Google no lo penalice por duplicado.**

El texto ya está escrito. Está en el repositorio, en
`seo-blog/nuevos/10-que-es-un-exoesqueleto.html`, son 26,6 KB y unas 3.000 palabras.
Está redactado de cero: no comparte con la página del fabricante ni una frase, ni un
titular, ni el orden de las secciones. **Tu trabajo aquí no es reescribirlo, es
publicarlo bien.**

---

## Las reglas de esta tanda

- **No copies nada de `eu.hypershell.tech`.** Ni un párrafo, ni un titular, ni sus
  testimonios (son de personas reales con nombre y apellido: reproducirlos sería
  duplicado *y* atribuirnos algo que no es nuestro). Si en algún momento piensas «esto
  se explicaría mejor como lo dicen ellos», la respuesta es no.
- **Ni un precio, en ninguna parte.** Siguen congelados hasta que el dueño pase el
  inventario con los EAN.
- **Ningún vídeo anterior a mayo de 2026.** Es la trampa en la que ya caímos una vez: el
  canal tiene tutoriales de la generación anterior que parecen actuales. Los IDs que
  aparecen más abajo están verificados uno a uno contra la fecha de publicación. **No
  añadas ni sustituyas ninguno por tu cuenta.**
- **Nada de `FAQPage` ni de `VideoObject`.** El `FAQPage` Google lo restringió a
  organismos públicos y sanitarios. El `VideoObject` sería reclamar como nuestros unos
  vídeos que son del fabricante, y eso sí es un problema real. El `Article` que ya emite
  Yoast es todo lo que lleva esta entrada.
- **Ni una huella de que esto se ha hecho con IA.** Ni firma, ni comentario, ni
  `data-start`, ni nombres de archivo raros. Los archivos de miniatura se llaman como
  dice el paso 2.
- **Para en los puntos de control** y espera confirmación.

---

## Paso 1 · Decidir dónde vive (ya está decidido, esto es el porqué)

Va como **entrada del blog**, no como página, y con slug `que-es-un-exoesqueleto`.

Dos motivos:

1. Todo el diseño del blog está en CSS colgado de `.single-post`. Publicado como página,
   el texto saldría sin formato y habría que duplicar 17 KB de CSS.
2. La intención de búsqueda es informativa («qué es», «cómo funciona»). La categoría
   `/product-category/hypershell/` ataca la transaccional («comprar», «precio»). Si se
   mete este texto dentro de la categoría, las dos intenciones compiten en la misma URL y
   se hacen daño. Separadas, la guía capta la búsqueda amplia y **empuja** a la categoría.

«Meterlo en Hypershell», que es lo que pidió el dueño, se resuelve en el paso 5: la
categoría y las tres fichas enlazan a la guía en un sitio visible.

---

## Paso 2 · Las seis miniaturas (esto va primero, y no es opcional)

El HTML trae seis vídeos. Cada uno se pinta como **fachada**: una imagen y un botón. El
`<iframe>` de YouTube no existe hasta que alguien pulsa.

Las miniaturas **no pueden servirse desde `i.ytimg.com`**. La auditoría del banner de
cookies dejó comprobado que la web no hace ni una sola petición a terceros antes de que
el visitante acepte. Una imagen traída de un servidor de Google filtra la IP del
visitante y rompe eso. Así que se descargan y se suben a la biblioteca de medios.

Para cada uno de estos seis, descarga
`https://i.ytimg.com/vi/<ID>/maxresdefault.jpg`, conviértelo a **WebP de 480 px de ancho**
y súbelo con el nombre de archivo indicado:

| # | ID del vídeo | Publicado | Nombre del archivo | Dónde va |
|---|---|---|---|---|
| 1 | `8OypUvpzQ80` | 01/06/2026 | `exoesqueleto-hypershell-serie-x.webp` | Arriba del todo |
| 2 | `V_t5wTvyPEM` | 10/06/2026 | `exoesqueleto-asistencia-cadera.webp` | «Cómo te ayuda, paso a paso» |
| 3 | `NTQiukEf5kM` | 25/05/2026 | `exoesqueleto-como-se-pone.webp` | «Qué se siente el primer día» |
| 4 | `mhwxMT_LyYo` | 29/05/2026 | `exoesqueleto-pruebas-laboratorio.webp` | «Qué mirar antes de comprar» |
| 5 | `Hm3GWR9kiXE` | 23/05/2026 | `exoesqueleto-uso-diario.webp` | «Los modelos que hay» |
| 6 | `YKl0EP_xqoQ` | 23/06/2026 | `exoesqueleto-alta-montana.webp` | «Hasta dónde aguanta» |

Los seis son de la serie X actual. Los seis son del canal oficial
`youtube.com/@Hypershell_Tech`.

Después, en el HTML, sustituye cada
`[[VERIFICAR: URL de la miniatura subida a la biblioteca de medios]]`
por la URL real del archivo subido, **en el orden en que aparecen**, que es el de la
tabla. Los textos `alt` ya están escritos: no los toques.

> **Punto de control 1.** Enséñame las seis URL finales y una captura de una de las
> fichas de medios. Antes de seguir.

---

## Paso 3 · El CSS y el JavaScript de la fachada

Dos archivos en el repositorio:

- `seo-blog/diseno-video.css` → va al final de **Apariencia → Personalizar → CSS
  adicional**. Es el sitio que gana por especificidad, ya lo sabemos.
- `seo-blog/herramientas/video-fachada.js` → va al final de **Opciones de Tema → Custom
  JS** (`minimog_options[custom_js]`).

Dos avisos sobre el JS, aprendidos por las malas:

- **Opciones de Tema reescribe los 1.904 campos al guardar y falla una de cada dos
  veces.** Antes de tocarlo, guarda una copia del valor actual de `custom_js`. Después de
  guardar, comprueba que el bloque nuevo está *y* que lo que había antes sigue estando.
- Es **un solo listener delegado para toda la web**. No hay que registrar nada por vídeo.
  Si mañana se pone un vídeo en una ficha, funciona sin tocar nada más.

Si al mirar el CSS actual descubres que **ya existe** una clase `.eg-vid` de un trabajo
anterior, no dupliques: quédate con la que ya está y dímelo, y adaptamos el HTML.

---

## Paso 4 · Publicar la entrada

1. Entrada nueva. Título, slug, meta y autor: todo está en
   `seo-blog/nuevos/10-que-es-un-exoesqueleto-meta.txt`. **Autor: EcoGadget**, nunca David.
2. **Sin imagen destacada**, como los otros nueve posts.
3. **Sin etiquetas.** Las del blog se borraron en su día porque generaban archivos vacíos
   indexables.
4. El contenido se pega en el editor **en modo HTML/código**, nunca en el visual: el
   editor visual se come los `<details>` y los `<figure>`.
5. Busca la palabra `VERIFICAR` en el contenido pegado. **Si aparece una sola vez, no
   publiques.** Quedan ocho marcas en la tabla comparativa de modelos, además de las seis
   de las miniaturas.

### Las ocho marcas de la tabla

La tabla de «Los modelos que hay y en qué se diferencian» sale con huecos **a propósito**.
Estos son los datos que tengo confirmados y los que no:

| Dato | X Ultra S | X Max S | X Pro S |
|---|---|---|---|
| Potencia | 1.000 W ✔ | 800 W ✔ | **falta** |
| Autonomía por batería | 30 km ✔ | 17,5 km ✔ | **falta** |
| Baterías que admite | dos ✔ | **falta** | **falta** |
| Modos | 12 ✔ | 10 ✔ | **falta** |
| Palancas | fibra de carbono ✔ | PPA ✔ | PPA ✔ |
| Peso | **falta** | **falta** | **falta** |

Los huecos se rellenan **leyendo las fichas reales de la web**, no de memoria y no de la
web del fabricante. Si un dato no aparece en la ficha, **borra la fila entera** de la
tabla: una tabla con menos filas es correcta, una tabla con un dato inventado es un
problema. Dime qué filas has borrado.

Recuerda leer el contenido en crudo desde `post.php?post=ID&action=edit`, **nunca por
`wc/v3`**: la API devuelve los shortcodes ya resueltos y guardarlos así destruye los
`[eg_precio]` de la ficha. Ya pasó.

> **Punto de control 2.** Enséñame la tabla rellena y la lista de filas borradas, si las
> hay. Antes de publicar.

---

## Paso 5 · Meterlo «en Hypershell»

Sin esto, la entrada nace huérfana y no la encuentra nadie. Tres sitios:

**5.1 · La categoría `/product-category/hypershell/`.** Al principio del texto SEO, que va
**debajo de la rejilla de productos** (recuerda: encima solo van migas, H1 y la fila de
pastillas), añade este bloque:

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>¿Es la primera vez que miras un exoesqueleto?</strong> Antes de comparar modelos, merece la pena entender qué hace realmente el aparato y en qué casos no compensa. Lo hemos explicado con vídeos y sin tecnicismos: <a href="/que-es-un-exoesqueleto/">qué es un exoesqueleto y cómo ayuda al caminar</a>.</p>
</div>
```

**5.2 · Las tres fichas con stock.** Una línea al final de la descripción larga de cada
una. Que no sea la misma frase en las tres —Google lee texto repetido en tres URLs como lo
que es—, así que varía el arranque:

- *«Si es tu primer exoesqueleto, aquí explicamos [qué hace y qué no hace un exoesqueleto](/que-es-un-exoesqueleto/), con vídeos.»*
- *«Antes de decidirte, en [esta guía](/que-es-un-exoesqueleto/) contamos cómo asiste cada paso y para quién no merece la pena.»*
- *«Dudas de si te encaja: [la guía del exoesqueleto](/que-es-un-exoesqueleto/) resuelve las ocho preguntas que más nos hacen.»*

**5.3 · El fragmento #38**, el que pinta «Nunca has llevado un exoesqueleto: por dónde se
empieza» en las tres fichas. Añade un enlace al final del bloque, dentro del propio
fragmento. Y **quita del fragmento lo que ahora se solape con la guía**: si el bloque
repite las cifras del 63 % y el 20 %, o el aviso de que no es producto sanitario, déjalo
solo en un sitio. El aviso legal se queda en **los dos** —eso sí se repite a propósito.

---

## Paso 6 · Comprobar

Con `?nc=1` al final de la URL, que LiteSpeed sigue sirviendo páginas viejas después de
purgar:

1. La entrada se ve maquetada (si sale texto plano, el CSS de `.single-post` no la alcanza).
2. **Los seis vídeos:** con la pestaña de Red abierta y filtro `youtube`, cargar la
   página **no debe generar ni una petición**. Al pulsar uno, aparece la petición a
   `youtube-nocookie.com` y el vídeo arranca solo.
3. Pulsa un segundo vídeo con el primero ya abierto: los dos deben poder convivir.
4. En móvil, que los vídeos no se salgan del ancho y que las tablas se puedan deslizar.
5. Los seis enlaces internos de la entrada: ninguno puede devolver un 301.
6. Que `/que-es-un-exoesqueleto/` responde 200 y que Yoast le ha puesto el título y la
   meta del archivo, sin recortar.
7. Busca `VERIFICAR` en el HTML del front. Cero resultados.

> **Punto de control 3.** El informe con los siete puntos. Y una captura del panel de Red
> antes y después de pulsar el primer vídeo.

---

## Lo que NO hay que hacer en esta tanda

- No toques la ficha de ningún producto más allá de la línea del paso 5.2.
- No crees categorías nuevas ni cambies el padre de ninguna.
- No añadas la guía al menú principal todavía: primero que Google la indexe y veamos qué
  posición coge. Si la metemos en el menú ahora, se lleva enlaces internos que hoy
  necesitan las categorías.
- No mandes la URL a indexar a mano más de una vez.

---

## Lo que queda pendiente de esto, para la siguiente

- Los **82 exoesqueletos de la generación anterior** que hay en el almacén siguen sin
  ficha. La guía habla de la serie actual; cuando esas cuatro fichas existan, habrá que
  añadir un párrafo que explique la diferencia entre generaciones, que es justo lo que la
  gente va a buscar al ver dos precios distintos.
- El dueño tiene que pasar **dos fotos reales del taller de Rivas**. La sección «Pruébatelo
  antes de decidir» es el mayor diferenciador frente a la web del fabricante —ellos no
  tienen dónde probarlo en España— y ahora mismo va sin una sola foto.
- **Horario de la tienda.** La guía dice «sin cita» pero no dice cuándo se puede ir.
