# Tanda 10 · revisión E · esto ya es para aplicar

**25 de agosto de 2026.** Tu punto de control 0 está contestado y **todo lo que
preguntabas está resuelto en los archivos**. Ya no hay nada que confirmar antes de tocar:
esta revisión es de ejecución.

## Lo que cambia respecto a la D

- **La etiqueta es `<a>`, no `<button>`.** Tu medición manda: 321 px frente a 45. Y tu
  argumento del `href` real es mejor que el mío — si el JS falta, la fachada abre el vídeo
  en YouTube en vez de no hacer nada. Adoptado tal cual, con `target="_blank"`,
  `rel="noopener"` y `data-yt`.
- **El JS ya no intercepta los clics con Ctrl, Cmd, Shift, Alt ni con el botón central.**
  Eso es «ábrelo en otra pestaña», y como el `href` es de verdad, se deja pasar.
- **Los nueve marcadores del artículo ya son marcado real.** No hay que montar nada a
  mano: solo sustituir la URL de la miniatura.
- **Las tres fichas están escritas enteras**, cortas y largas. Es lo que el dueño pedía
  desde hace cuatro rondas y lo que faltaba de verdad.

## Los archivos

Repositorio público, se abren en una pestaña:

| Qué | Ruta |
|---|---|
| JS de la fachada | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/herramientas/video-fachada.js` |
| Plantilla del marcado | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/herramientas/video-fachada-marcado.html` |
| **Ficha X Pro S** · larga | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-pro-s-larga.html` |
| **Ficha X Pro S** · corta | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-pro-s-corta.html` |
| **Ficha X Max S** · larga | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-max-s-larga.html` |
| **Ficha X Max S** · corta | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-max-s-corta.html` |
| **Ficha X Ultra S** · larga | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-ultra-s-larga.html` |
| **Ficha X Ultra S** · corta | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-ultra-s-corta.html` |
| Guía del blog | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto.html` |
| Título, meta y slug de la guía | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto-meta.txt` |

---

## Paso 1 · El JS

Al final del **pie de Herramientas → Head & Footer Code**, entre etiquetas `script`.
CodeMirror encima: escribir en `textarea.value` no sirve. Guardar, **recargar entera y
releer del servidor** (norma 7).

---

## Paso 2 · Las once miniaturas

Descarga `https://i.ytimg.com/vi/<ID>/maxresdefault.jpg` —**maxresdefault, no
hqdefault**: con 480×360 las bandas negras se colarían en el recorte de `object-fit`—,
convierte a **WebP de 480 px de ancho** y sube con este nombre exacto:

| ID | Publicado | Nombre del archivo | Dónde se usa |
|---|---|---|---|
| `AbWq1d1Tdxc` | 18/05/2026 | `exoesqueleto-que-hace.webp` | Guía + ficha Pro S |
| `qou6ih-ezfs` | 27/05/2026 | `exoesqueleto-paso-estable.webp` | Ficha Pro S |
| `NTQiukEf5kM` | 25/05/2026 | `exoesqueleto-como-se-pone.webp` | Guía + ficha Max S |
| `gIezj5rhFBc` | 24/05/2026 | `exoesqueleto-subida.webp` | Guía + ficha Max S |
| `8OypUvpzQ80` | 01/06/2026 | `exoesqueleto-hypershell-serie-x.webp` | Guía + ficha Ultra S |
| `utmvvQ2F5S8` | 22/05/2026 | `exoesqueleto-everest-corto.webp` | Ficha Ultra S |
| `4VxAobnf6Z4` | 26/05/2026 | `exoesqueleto-estabilidad.webp` | Guía |
| `V_t5wTvyPEM` | 10/06/2026 | `exoesqueleto-asistencia-cadera.webp` | Guía |
| `mhwxMT_LyYo` | 29/05/2026 | `exoesqueleto-pruebas-laboratorio.webp` | Guía |
| `Hm3GWR9kiXE` | 23/05/2026 | `exoesqueleto-uso-diario.webp` | Guía |
| `YKl0EP_xqoQ` | 23/06/2026 | `exoesqueleto-alta-montana.webp` | Guía |

**Norma 3:** WordPress añade sufijos. **Copia la URL real de cada ficha de medio.**

Luego, en cada archivo, sustituye `[[VERIFICAR: URL de <nombre>.webp]]` por la URL real.
El nombre del archivo va escrito dentro de cada marca, así que no hay que adivinar cuál va
dónde.

---

## Paso 3 · Las tres fichas

Este es el paso que importa. **Sustituyen la descripción entera**, corta y larga.

| ID | Producto | Corta | Larga |
|---|---|---|---|
| 8331 | X Pro S | 759 B | 15,3 KB · 2.014 palabras |
| 8317 | X Max S | 821 B | 13,8 KB · 1.764 palabras |
| 8300 | X Ultra S | 885 B | 14,1 KB · 1.807 palabras |

Cada una lleva: barra de datos, dos vídeos, cómo funciona paso a paso, cuánto ahorra con
la cifra atribuida al fabricante, para quién **no** es, modos y aplicación, contenido de la
caja, comparativa con los otros dos, seis o siete preguntas frecuentes, aviso de que no es
producto sanitario, y el bloque de por qué comprarlo aquí con la tienda de Rivas.

Están escritas con **ángulos distintos a propósito** —el Pro S es la entrada, el Max S es
el de las rutas de verdad, el Ultra S es el ligero para varios días—, no son la misma ficha
con los números cambiados. Tres URLs con el mismo texto es contenido duplicado.

**Antes de guardar cada una, tres cosas:**

1. **Lee el bruto** desde `post.php?post=ID&action=edit`, nunca por `wc/v3` (norma 2).
   Guarda una copia del texto anterior.
2. **Compara la vieja con la nueva y rescata lo que se pierda.** Están escritas con los
   datos que confirmaste —potencia, par, kilómetros, materiales— pero si la descripción
   antigua tiene alguna especificación que no esté en la nueva, **avísame antes de
   guardar**; no la tires.
3. **Comprueba el contenido de la caja contra el manual en PDF** de `/man/`. La lista sale
   de la documentación del fabricante; si el manual dice otra cosa, manda la corrección.

Los tres productos tienen **cero shortcodes**, como comprobaste, así que aquí no hay
`[eg_precio]` que perder. Aun así, compara bytes antes y después.

> **Punto de control 1.** La ficha del X Pro S terminada y vista en el front, antes de
> tocar las otras dos. Si algo del diseño no encaja, se arregla una vez y no tres.

---

## Paso 4 · La guía del blog

Entrada nueva, slug `que-es-un-exoesqueleto`. Autor **EcoGadget**, sin imagen destacada,
sin etiquetas, pegada **en modo HTML**. El comentario `<!-- ... -->` de cabecera **no se
pega**: empieza en `<div class="eg-desc">`.

Queda **una casilla** por rellenar en todo el texto: el **par motor del X Ultra S**, en la
tabla comparativa. Si no está en su ficha, **borra la fila del par** y dímelo. Esa misma
fila aparece en las tres fichas: si la borras en una, bórrala en las cuatro.

---

## Paso 5 · La categoría

Solo el bloque de enlace, sin vídeo, como quedó zanjado. Al principio del texto SEO, que va
debajo de la rejilla. Por REST comparando bytes y sin tocar `<!--eg-corte-->` (norma 4):

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>¿Es la primera vez que miras un exoesqueleto?</strong> Antes de comparar modelos merece la pena verlo funcionando y entender en qué casos no compensa. Lo hemos explicado con nueve vídeos y sin tecnicismos: <a href="/que-es-un-exoesqueleto/">qué es un exoesqueleto y cómo ayuda al caminar</a>.</p>
</div>
```

Y en el **fragmento #38**, un enlace a la guía al final del bloque. Quita lo que se solape
—si repite el 63 % y el 20 %, déjalo en un sitio—, pero **el aviso de que no es producto
sanitario se queda en los dos**.

---

## Paso 6 · Comprobar

Con `?nc=1` detrás (norma 11):

1. **Pulsa un vídeo en una ficha y comprueba que se abre incrustado.** Es la comprobación
   número uno.
2. **Pulsa uno con Ctrl (o Cmd).** Debe abrir YouTube en otra pestaña, no incrustarse.
3. **Con Red abierta y filtro `youtube`:** cargar cualquiera de las cuatro páginas **no
   genera ni una petición**. Solo aparece al pulsar.
4. Las tres fichas se ven maquetadas, con la fachada a 16:9 y sin aplastar.
5. En móvil, que los vídeos no se salgan del ancho y las tablas se deslicen.
6. La guía se ve maquetada. Si sale texto plano, el CSS de `.single-post` no la alcanza.
7. Enlaces internos, ninguno con 301 (norma 9): `/product-category/hypershell/`,
   `/product-category/hypershell/accesorios-hypershell/`, `/producto/hypershell-x-max-s/`,
   `/que-es-un-exoesqueleto/`, `/contacto/` y `/man/`.
8. `/que-es-un-exoesqueleto/` responde 200, con título y meta de Yoast sin recortar.
9. **Barrido de rastros de IA** (norma 8) en las tres fichas, la guía y la categoría.
   Falso positivo conocido: «Inteligencia Artificial» es la HyperIntuition™ y **se queda**.
10. `VERIFICAR` en el HTML del front: cero.
11. Vaciar LiteSpeed y WP Super Cache.

---

## Lo que NO se hace

- **Ni Personalizador ni Opciones De Tema.** El JS va al pie de Head & Footer Code.
- **Ni un precio**, en ninguna parte. Congelados hasta el inventario con los EAN.
- **Nunca «servicio técnico propio»** en Hypershell: es garantía del fabricante, 24 meses.
- Sin `FAQPage` ni `VideoObject`. Las preguntas van en `details` pelado, sin schema: las
  fichas viejas de EcoFlow sí lo llevan, pero eso es anterior a la norma 10.
- **Los vídeos no entran en la categoría.**
- La guía no entra en el menú principal todavía.
- Nada de Elementor.

---

## Para la lista del inventario con los EAN

- El **fragmento #38 publica ahora mismo 2.585 g y 2.571 g** para el Pro S y el Max S, y
  esas cifras no salen de ninguna ficha. Confirmar o quitar cuando llegue la ficha técnica.
- El **par del X Ultra S** y el **peso del Pro S y del Max S**, que hoy faltan.
- La **capacidad en Wh** de la batería: la ficha da 5.000 mAh y no da voltaje.

## Pendiente del dueño

- **Dos fotos del taller de Rivas.** Las tres fichas y la guía venden la prueba en tienda
  —lo único que el fabricante no puede copiar— y va sin una sola foto.
- **El horario de la tienda.** Dice «avísanos antes de venir» pero no dice cuándo se abre.
