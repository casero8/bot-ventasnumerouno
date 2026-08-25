# Tanda 10 · «Que se vean funcionando» · revisión D

**25 de agosto de 2026.** Sustituye a las revisiones A, B y C. Los frenos 4, 5 y 6 están
cerrados en el archivo. **Y el hallazgo del JS es correcto: matar el paso 3 fue un error
mío**, tomado de tu informe anterior. El CSS está; el JS no. Se restituye abajo.

Dos cosas tuyas que se aceptan sin discusión:

- **Los vídeos no van en la categoría.** Tu razón es mejor que la norma: quien llega al
  texto de la categoría ya está eligiendo producto y lo que necesita es pinchar, no una
  cuarta salida. Cerrado.
- **Las tres fichas no llevan ni un `[eg_precio]`.** Se editan igual por el bruto y
  comparando bytes, pero el desastre del 24 no se puede repetir ahí.

## Lo que ha cambiado de encargo

El dueño lo ha dicho claro: *«son aparatos que necesitan verse bien para que puedan
comprar»*. Eso reordena la tanda. **El objetivo ya no es un artículo con vídeos: es que
se vean funcionando, y que se vean donde está el botón de comprar.**

Traducido a trabajo, y por orden de lo que más vende:

| | Dónde | Qué lleva | Por qué |
|---|---|---|---|
| **A** | Las **tres fichas** | 2 vídeos cada una | Ahí está el botón de comprar. Es lo que más mueve la aguja |
| **B** | La **guía** `/que-es-un-exoesqueleto/` | 9 vídeos | Capta la búsqueda de quien aún no sabe qué es esto |
| **C** | La **categoría** `/product-category/hypershell/` | El enlace a la guía, sin vídeo | Ver más abajo: choca con la norma 10 |

**Sobre la C.** Zanjado: sin vídeos. Lo dice la norma 10 y, sobre todo, lo dice el motivo
que hay debajo de la norma — quien llega al texto de la categoría ya está eligiendo
producto y necesita pinchar, no una cuarta salida.

---

## Paso 0 · Los archivos

Repositorio público, se abren en una pestaña:

- `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto.html`
- `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/nuevos/10-que-es-un-exoesqueleto-meta.txt`

Y los dos del paso 1, que **vuelven** (`video-fachada.js` estaba borrado por mi error de
ayer; `diseno-video.css` sigue borrado y con razón, porque el CSS ya está puesto).

---

## Reglas

- **No se toca el CSS del Personalizador ni Opciones De Tema.** El CSS de la fachada ya
  está y no hay que añadirle ni una regla. El JS que falta va al **pie de Head & Footer
  Code**, donde ya escribiste esta mañana sin problema, no a Opciones De Tema.
- **Ningún vídeo anterior al 15 de mayo de 2026.** Los doce IDs de esta tanda están
  verificados contra su fecha de publicación. **No añadas ni sustituyas ninguno.**
- **No copies nada de `eu.hypershell.tech`.** Ni texto, ni titulares, ni sus testimonios.
- **Ni un precio.** Congelados hasta el inventario con los EAN.
- **Garantía (norma 10):** «garantía del fabricante, 24 meses». Nunca «servicio técnico
  propio», que es solo de EcoFlow, ni enlace a `/servicio-tecnico-ecogadget/`.
- **Nada de `FAQPage` ni de `VideoObject`.**
- **Para en los tres puntos de control.**

---

## Paso 1 · El JS de la fachada (antes que todo lo demás)

Tenías razón, y es el hallazgo más importante de la tanda. El CSS del 20 de agosto se puso
y **el JS nunca se instaló**: lo que aparecía en el HTML era el propio CSS anunciando en un
comentario un JS que no existe. Publicar sin él serían doce botones de play que no hacen
nada, tres de ellos justo encima del botón de comprar.

Recuperado del historial y **reescrito para las clases que sí están vivas** —escuchaba
`.eg-vid`, que me había inventado yo; ahora escucha `.eg-video` / `.eg-video-fachada` y le
pone `.eg-video-iframe` al iframe que crea:

- `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/herramientas/video-fachada.js`

Va al **final del pie de Herramientas → Head & Footer Code**, envuelto en etiquetas
`script`. Tu propuesta, y es la buena: hace el mismo trabajo que Opciones De Tema sin
reescribir los 1.904 campos. Recuerda que ese campo lleva **CodeMirror** encima —escribir
en `textarea.value` no sirve— y que después hay que **recargar entera y releer del
servidor** (norma 7).

### Y el marcado, porque tampoco hay de dónde copiarlo

Segunda consecuencia del mismo hallazgo: el paso que decía «copia el bloque de una página
en vivo» no se puede cumplir, porque no hay ninguna. La plantilla está aquí:

- `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/herramientas/video-fachada-marcado.html`

**Antes de aplicarla, pásame las seis reglas del CSS** (`.eg-video`, `.eg-video-fachada`,
`.eg-video-img`, `.eg-video-play`, `.eg-video-iframe`, `.eg-video-pie`) tal y como están
en el Personalizador. La plantilla usa `button`, `img` y `figcaption`; si alguna regla
espera otra etiqueta, la ajusto yo. **No montes doce vídeos sobre un marcado sin
confirmar.**

> **Punto de control 0.** Las seis reglas del CSS, y el JS ya guardado y releído del
> servidor. Con eso te confirmo el marcado y sigues.

---

## Paso 2 · Las miniaturas

**Doce vídeos en total**: nueve para la guía y tres para las fichas. Las miniaturas **no
pueden servirse desde `i.ytimg.com`** — la auditoría de cookies dejó comprobado que la web
no llama a terceros antes de aceptar, y una imagen de un servidor de Google filtra la IP.

Descarga `https://i.ytimg.com/vi/<ID>/maxresdefault.jpg`, convierte a **WebP de 480 px de
ancho** y sube con este nombre:

**Para la guía (9):**

| Marca en el HTML | ID | Publicado | Nombre del archivo |
|---|---|---|---|
| VÍDEO A | `AbWq1d1Tdxc` | 18/05/2026 | `exoesqueleto-que-hace.webp` |
| VÍDEO B | `4VxAobnf6Z4` | 26/05/2026 | `exoesqueleto-estabilidad.webp` |
| VÍDEO C | `gIezj5rhFBc` | 24/05/2026 | `exoesqueleto-subida.webp` |
| VÍDEO 1 | `8OypUvpzQ80` | 01/06/2026 | `exoesqueleto-hypershell-serie-x.webp` |
| VÍDEO 2 | `V_t5wTvyPEM` | 10/06/2026 | `exoesqueleto-asistencia-cadera.webp` |
| VÍDEO 3 | `NTQiukEf5kM` | 25/05/2026 | `exoesqueleto-como-se-pone.webp` |
| VÍDEO 4 | `mhwxMT_LyYo` | 29/05/2026 | `exoesqueleto-pruebas-laboratorio.webp` |
| VÍDEO 5 | `Hm3GWR9kiXE` | 23/05/2026 | `exoesqueleto-uso-diario.webp` |
| VÍDEO 6 | `YKl0EP_xqoQ` | 23/06/2026 | `exoesqueleto-alta-montana.webp` |

**Para las fichas (3 más):**

| ID | Publicado | Nombre del archivo |
|---|---|---|
| `qou6ih-ezfs` | 27/05/2026 | `exoesqueleto-paso-estable.webp` |
| `utmvvQ2F5S8` | 22/05/2026 | `exoesqueleto-everest-corto.webp` |
| `DjntSqn7X7o` | 04/08/2026 | `exoesqueleto-larga-distancia.webp` |

**Norma 3:** WordPress añade sufijos al subir. **Copia la URL real de cada ficha de medio,
no la escribas a ojo.** Ya rompimos dos fichas así.

**Sobre las fechas que no pudiste verificar.** El 429 de YouTube es real y desde ahí no lo
saltas, así que lo dejo por escrito para que quede auditable: los doce IDs y sus fechas
salen del listado de vídeos del canal `UCi_hZIiaByu5LjLyuuKcnjw` (`@Hypershell_Tech`), con
la fecha de publicación de cada uno. Los doce son del 15 de mayo de 2026 en adelante. Los
tutoriales de la generación anterior que hay en ese mismo canal —`W4omUaf5nRw` de enero de
2025 y `ZPTJxNbKiFY` de septiembre de 2025— están deliberadamente fuera de la lista.
**Si algún vídeo, al abrirlo, enseña un aparato que no es la serie X actual, para y
dímelo.** Ésa sí es una comprobación que puedes hacer.

> **Punto de control 1.** Las doce URL finales.

---

## Paso 3 · A · Los vídeos en las tres fichas

**Es el paso que más vende y por eso va antes que la guía.** Dos vídeos por ficha, al final
de la descripción larga, antes de la línea que enlaza a la guía.

| Ficha | Vídeo 1 | Vídeo 2 |
|---|---|---|
| **X Pro S** (8331) | `AbWq1d1Tdxc` qué hace | `qou6ih-ezfs` paso estable |
| **X Max S** (8317) | `NTQiukEf5kM` cómo se pone | `gIezj5rhFBc` subiendo |
| **X Ultra S** (8300) | `8OypUvpzQ80` la serie X | `utmvvQ2F5S8` alta montaña |

Reparto pensado: al de entrada se le enseña **qué hace**, al de en medio **cómo se usa**, y
al de arriba **hasta dónde llega**. Ninguno repite vídeo con otro.

Antes de cada pareja, una frase de entrada distinta en cada ficha —nunca la misma en las
tres— del tipo «Así se ve funcionando» / «Cómo se pone y cómo se nota» / «Hasta dónde
llega».

**Norma 2, la que más duele:** lee el bruto desde `post.php?post=ID&action=edit`, **nunca
por `wc/v3`**. La API devuelve los shortcodes resueltos y guardarlos así destruye los
`[eg_precio]` de la ficha. Ya pasó el 24 de agosto en dos fichas.
**Cuenta los shortcodes antes y después de cada guardado.** Si el recuento baja, para.

> **Punto de control 2.** La primera ficha terminada, con el recuento de shortcodes antes
> y después, antes de tocar las otras dos.

---

## Paso 4 · B · La guía

Entrada del blog, slug `que-es-un-exoesqueleto` (comprobado: 404, libre). **No** como
página: el diseño del blog cuelga de `.single-post`.

1. Título, meta y autor en el `-meta.txt`. **Autor: EcoGadget**, nunca David.
2. **Sin imagen destacada** y **sin etiquetas.**
3. Se pega **en modo HTML**, nunca en el visual: se come los `<details>`.
4. **El comentario `<!-- ... -->` de cabecera NO se pega.** Empieza en `<div class="eg-desc">`.
5. Los **nueve marcadores de vídeo**: se rellenan con la plantilla del paso 1, ya
   confirmada, cambiando solo cuatro cosas —ID, URL de la miniatura, `alt` y pie—, que van
   escritas dentro del propio marcador. **El `figcaption` no se quita nunca:** es la
   atribución al fabricante.
6. Los tres primeros (A, B y C) van apilados y a todo ancho, sin envoltorio. Es
   deliberado: para un aparato que hay que ver, tres vídeos grandes valen más que tres
   miniaturas pequeñas. Ponerlos en fila es un trabajo de CSS para otra tanda.
7. Busca `VERIFICAR` en lo pegado. **Si aparece una sola vez, no publiques.**

### Lo que queda por rellenar en el texto

Ya solo **una casilla**: el **par motor del X Ultra S**, en la tabla comparativa. Si no
está en su ficha, borra la fila del par entera y dímelo.

Todo lo demás está cerrado con lo que dijiste:

- **PPA fuera.** La fila se llama ahora «Materiales» y dice lo que dicen las fichas:
  aleación aeroespacial (Pro S), aleación de magnesio y aluminio aeroespacial (Max S),
  materiales aeroespaciales y fibra de carbono (Ultra S).
- **Los Wh fuera.** Adoptada tu redacción: «su capacidad viene impresa en la propia
  batería». Resuelve la duda del avión sin afirmar una cifra que no tenemos.
- **La fila del peso, borrada.** Tenías razón: que el mapeo del #38 sea sólido no
  convierte la cifra en dato. Queda el «~2,5 kg» del Ultra S en el texto, que sí sale de
  su ficha.
- **`eg-toc`** ahora es `class="eg-note eg-toc"`, para que se apoye en un estilo que
  existe. `eg-consulta`, `.si` y `.no` se quedan en texto plano, como dijiste.

---

## Paso 5 · C · La categoría

Solo el bloque de texto, **sin vídeo** (ver arriba el porqué). Al principio del texto SEO,
que va **debajo de la rejilla**. Por REST comparando bytes y sin tocar el
`<!--eg-corte-->` (norma 4):

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>¿Es la primera vez que miras un exoesqueleto?</strong> Antes de comparar modelos merece la pena verlo funcionando y entender en qué casos no compensa. Lo hemos explicado con nueve vídeos y sin tecnicismos: <a href="/que-es-un-exoesqueleto/">qué es un exoesqueleto y cómo ayuda al caminar</a>.</p>
</div>
```

Si después tocas el Yoast de la categoría, **vuelve a aplicar la descripción por REST y
comprueba los bytes** (norma 5).

**Y el fragmento #38:** un enlace a la guía al final del bloque. Quita lo que se solape
—si repite el 63 % y el 20 %, déjalo en un sitio—, pero **el aviso de que no es producto
sanitario se queda en los dos**.

---

## Paso 6 · Comprobar

Todo con `?nc=1` (norma 11):

1. **Pulsa un vídeo y comprueba que se abre.** Es la comprobación número uno: sin el JS
   del paso 1, todo lo demás es decorado.
2. La guía se ve maquetada. Si sale texto plano, el CSS de `.single-post` no la alcanza.
3. **Los doce vídeos:** con Red abierta y filtro `youtube`, cargar la guía y cargar cada
   ficha **no genera ni una petición**. Al pulsar uno aparece la de `youtube-nocookie.com`
   y arranca solo.
4. Dos vídeos abiertos a la vez en la misma página deben convivir.
5. En móvil, que los vídeos no se salgan del ancho y las tablas se deslicen.
6. **En las tres fichas, el recuento de `[eg_precio]` es el mismo que antes de empezar.**
7. Los cuatro enlaces internos de la guía, ninguno con 301:
   `/product-category/hypershell/`, `/product-category/hypershell/accesorios-hypershell/`,
   `/contacto/` (×2) y `/man/`.
8. `/que-es-un-exoesqueleto/` responde 200, con el título y la meta de Yoast sin recortar.
9. **Barrido de rastros de IA** (norma 8) en la guía, la categoría y las tres fichas. Falso
   positivo conocido: «Inteligencia Artificial» es la HyperIntuition™ y **se queda**.
10. `VERIFICAR` en el HTML del front: cero.
11. Vaciar LiteSpeed y WP Super Cache.

> **Punto de control 3.** Los once puntos, y una captura del panel de Red antes y después
> de pulsar el primer vídeo de una ficha.

---

## Lo que NO se hace

- **Ni Personalizador ni Opciones De Tema.** El JS va al pie de Head & Footer Code.
- **Ningún vídeo dentro del texto de la categoría** mientras el dueño no levante su norma 10.
- No crear categorías ni cambiar el padre de ninguna.
- **La guía no entra en el menú principal todavía.** Primero que Google la indexe.
- Nada de Elementor. No mandar la URL a indexar más de una vez.

---

## Para la lista del inventario con los EAN

Lo que levantaste y no se cierra hoy: **el fragmento #38 está publicando ahora mismo
2.585 g y 2.571 g** en las fichas del Pro S y del Max S, y esas cifras no salen de ninguna
ficha ni de ninguna fuente entregada. Cuando llegue la ficha técnica del fabricante hay
que confirmarlas o quitarlas de la web. Anotado.

## Pendiente del dueño

- **Dos fotos del taller de Rivas.** «Pruébatelo antes de decidir» es lo único que el
  fabricante no puede copiar —no tienen dónde probarlo en España— y va sin una foto.
- **El horario de la tienda.** El texto dice «sin cita» pero no dice cuándo se puede ir.
- **Si quiere vídeo en la categoría**, saltándose su norma 10.
