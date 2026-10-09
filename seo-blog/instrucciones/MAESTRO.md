# EcoGadget · estado y orden de trabajo

**9 de octubre de 2026.** Todo lo pendiente, en el orden en que hay que hacerlo.
Cada bloque enlaza a su parte detallado. **Lee «Normas antes de tocar nada» primero.**

El criterio del orden: primero lo que es **falso y está publicado**, después **el dinero
parado**, después **los clics**, y al final el contenido nuevo.

---

# BLOQUE 0 · Lo que está publicado y es falso. Hoy

**EcoFlow les ha retirado la condición de servicio técnico.** Decir que lo son es una
afirmación comercial falsa, atribuyéndose una autorización de un tercero que ya no existe.
Está en **seis sitios globales**, en las 326 URLs del sitio.

Parte completo: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/mapa-servicio-tecnico-en-vivo.md`

| Orden | Qué | Dónde |
|---|---|---|
| **0.1** | Quitar el **JS que reescribe el enlace del menú** en cada carga | JS del pie |
| **0.2** | Quitar el **JS de sustituciones `PARES`** que intentaba suavizar la frase | JS del pie |
| **0.3** | El **JSON-LD de organización**: dice «Tienda física y taller propio para EcoFlow» | `<head>`, sitewide |
| **0.4** | El **enlace del menú** «Servicio técnico EcoFlow» | Menú principal y móvil |
| **0.5** | El **pie**: «Servicio técnico oficial» en `div.eg-pie-extra` | JS del pie |
| **0.6** | La regla **CSS** `content:'Taller propio en Rivas-Vaciamadrid'` | CSS, `body.page-id-9090` |
| **0.7** | **301 de la página 9090** a los productos de EcoFlow | Fragmento #6 |

**El 0.1 va primero o nada se sostiene:** hay un JavaScript que comprueba el texto de ese
enlace y lo reescribe en cada carga. Si no se quita antes, el cambio en el menú «no se
guarda» y no se entiende por qué.

**El 0.3 es el más grave:** es la descripción de la empresa que lee Google. Cambiarlo por
algo cierto: *«Tienda física en Rivas-Vaciamadrid. Distribuidor oficial de EcoFlow,
HyperShell y Lokithor.»*

**Confirmado por el dueño: siguen siendo distribuidor oficial.** Eso no se toca.

---

# BLOQUE 1 · Dinero parado. Hoy, y son minutos

**Los cuatro HyperShell de la generación anterior tienen ficha y están marcadas como
agotadas**, con 82 unidades en el almacén.

| Producto | Precio | Estado hoy |
|---|---|---|
| `/producto/hypershell-x-ultra/` | 1.799 € | Agotado |
| `/producto/hypershell-x-carbon/` | 1.299 € | Agotado |
| `/producto/hypershell-x-pro/` | 899 € | Agotado |
| `/producto/hypershell-x-go/` | 699 € | Agotado |

1. **Poner el stock real.** Falta el dato del dueño: cuántas unidades de cada uno.
2. **Comprobar que salen** en `/product-category/hypershell/`. Con stock cambia también la
   fila de pastillas, que filtra por producto comprable (norma 14).
3. **Pegar el bloque de la diferencia entre generaciones**, ya escrito, al principio de la
   descripción larga de cada una. El párrafo final cambia por modelo: están los cuatro
   marcados en el archivo.
   Archivo: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-generacion-anterior.html`
4. **Enlazarlas** desde la guía `/que-es-un-exoesqueleto/` y desde la categoría.

**El mejor argumento de venta de las cuatro:** el X Carbon declara **1,8 kg en titanio**,
por debajo de los ~2,5 kg del X Ultra S actual. **Sigue siendo el más ligero del catálogo.**

---

# BLOQUE 2 · El resto de la afirmación falsa, producto a producto

**30 de 149 productos** llevan alguna frase que hay que corregir en su propia descripción.
Lista exacta de qué lleva cada uno: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/mantenimiento/barrido-productos-2026-10-09.txt`

Parte: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/hallazgo-stock-generacion-anterior.md`

| Frase | Productos | Gravedad |
|---|---:|---|
| Larga: «si hay avería **la reparamos nosotros**» | 4 | **Falsa** |
| Larga: «**servicio técnico propio**» | 5 | **Falsa** |
| `eg-trust`: «servicio técnico propio con tienda física» | 7 | **Falsa** |
| `eg-trust`: «y 14 días para devolverlo sin dar explicaciones» | 29 | El dueño la quiere fuera |
| `eg-taxline`: «· 14 días para devolverlo» | 16 | El dueño la quiere fuera |
| «Bizum» | 10 | Pasarela desactivada en agosto |

**Empieza por los seis más cargados**, que llevan cinco o seis frases cada uno:
`ecoflow-delta-3-classic-1024wh`, `ecoflow-river-3-max-plus`, `ecoflow-stream-ca-pro`,
`estacion-energia-portatil-ecoflow-delta-3-plu`, `ecoflow-delta-max-ultra`,
`ecoflow-stream-ultra-x`.

**Los textos corregidos ya están escritos** en `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/` (`*-corta.html` y `*-larga.html`).

**Norma 2, la que más duele:** lee el bruto desde `post.php?post=ID&action=edit`, **nunca
por `wc/v3`**, que devuelve los shortcodes resueltos y los destruye al guardar. **Cuenta los
`[eg_precio]` antes y después de cada guardado.**

### Lo que NO se quita de las devoluciones

El bloque global «Consulta nuestra política de devoluciones — 14 días para devolverlo sin
usar y en su caja original» **se queda**. Es un enlace a la política, no un reclamo. Y la
ley solo permite cargar el porte de vuelta al cliente **si se le ha informado antes de
comprar**: si se quita esa información, **el porte lo paga el vendedor**.

Y hay que **reforzar la política de devoluciones** para que diga, con esas palabras, que los
gastos de devolución corren por cuenta del cliente.
Parte: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-12-devoluciones-bizum-posventa.md`

**Punto de control:** antes de tocar la política, dime qué dice hoy sobre quién paga el porte.

---

# BLOQUE 3 · El PowerStream: una ficha agotada que puede vender

`/producto/inversor-ecoflow-powerstream/` · 219 € · agotado. **No se borra ni se redirige:**
son unas **624 impresiones al año** de gente que busca ese nombre.

Se le pone arriba un aviso que mande al microinversor STREAM. El bloque HTML está en
`https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/hallazgo-stock-generacion-anterior.md`, apartado 2.

---

# BLOQUE 4 · El racimo solar · el mayor hueco de clics del sitio

**26.000 impresiones en juego** entre los tres racimos, casi todas entre la posición 10 y la
20. Parte: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-13b-stream-y-solar.md`

**En este orden exacto:**

| | Qué | Impresiones |
|---|---|---:|
| **4.1** | **301 de `/ecoflow-stream/` a `/stream-series/`.** Hay dos categorías para la misma gama partiéndose la señal | — |
| **4.2** | Aplicar la categoría **`stream-series`** y cambiar su H1 a **«EcoFlow STREAM»** (hoy se llama «Batería para casa y balcón STREAM», que no lo busca nadie) | **8.456** |
| **4.3** | **Título y meta de `/kits-para-balcones/`.** Dos campos, y es la mejor relación esfuerzo-resultado de todo | **6.734** |
| **4.4** | Reaplicar **`paneles-solares`**, que lleva el aviso nuevo de «no instalamos» | — |
| **4.5** | **301 de `/paneles-solares-portatiles/`** (la segunda página del sitio) y de **`/placas-solares-ecoflow/`** a la categoría `paneles-solares` | **11.008** |
| **4.6** | Título y meta de `paneles-solares`, ya escritos | incluido |

**El 4.5 va después del 4.4:** no se redirige nada hacia una categoría a medias.

Textos y metas: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/categorias/stream-series.html`, `stream-series-meta.txt`,
`paneles-solares.html`, `paneles-solares-meta.txt`.

**No se ofrece instalación**, solo venta. Las búsquedas de «instalación placas solares
Rivas» **no se persiguen**, y la categoría ya lleva la línea que lo dice.

---

# BLOQUE 5 · Lo de HyperShell que quedó a medias

Parte: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-11-publicar-y-marca.md`

| | Qué | Estado |
|---|---|---|
| **5.1** | La guía `/que-es-un-exoesqueleto/` | **Publicada** ✔ |
| **5.2** | La categoría `hypershell` | **Aplicada** ✔ · pendiente reaplicar por el título repetido |
| **5.3** | Ficha **X Pro S** | **Aplicada** ✔ |
| **5.4** | Fichas **X Max S (8317)** y **X Ultra S (8300)** | **Pendientes** |
| **5.5** | Categoría **`accesorios-hypershell`** | Escrita, sin aplicar |
| **5.6** | **Fragmento #38**: quitar el peso en gramos, quitar el 63 % y el 20 %, y enlazar la guía | Pendiente |

**El 5.6 tiene una afirmación falsa viva:** el #38 publica 2.585 g y 2.571 g para el Pro S y
el Max S, y dice que salen de la ficha del producto. **No están en ninguna ficha.** Se quita
la cifra; la línea de fuentes se queda, y entonces sí es verdad.

**El título repetido (5.2):** «Si buscabas otra cosa» arriba y abajo. El de abajo pasa a
**«Para seguir mirando»**, ya corregido en el repositorio.
Parte: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-11b-titulo-repetido.md`

---

# BLOQUE 6 · Las ganancias rápidas de la auditoría SEO

Auditoría completa: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/auditoria-seo-2026-10.md`

**El diagnóstico en una frase:** no hay problema de posiciones. El **77 %** de las
impresiones ya están en la primera página. El **78 %** son búsquedas con la palabra
«ecoflow», donde el usuario quiere `ecoflow.com`. Y la marca propia, que es el **0,4 %** de
las impresiones, convierte al **31,26 %**.

Quitando la navegacional de marca ajena, **arreglar solo títulos y metas vale ~120 clics al
mes sin mover una posición.** Hoy entran 83 al mes en total.

| | Qué | Impresiones | Posición |
|---|---|---:|---:|
| **6.1** | Título y meta del post **«qué es un EcoFlow»** | 7.268 | 6,8 |
| **6.2** | Título de **`/man/`**, que debe llevar «manual en español» y el modelo | 6.798 | 7,9 |
| **6.3** | Las **diez consultas en posición 1-3 con CTR bajo**, una a una | — | — |
| **6.4** | Las páginas que atraen **Xiaomi, DJI, Autel y «placa arduino»**: reescribir o 301 | 13.100 | — |

**El racimo «servicio técnico» SALE del plan.** Era el punto 1 y ya no se puede trabajar: no
es un problema de título, es que no tenemos el producto.

---

# BLOQUE 7 · La ficha de Google

Partes: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/google/ficha-google.md` y `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/google/no-somos-ecoflow.md`

**Un cambio por sesión.** La ficha acaba de verificarse y tocar varios campos de golpe es la
forma más rápida de que vuelva a revisión.

| | Qué |
|---|---|
| **7.1** | **El nombre a `EcoGadget`.** Hoy es relleno de palabras clave y es motivo de suspensión. Falta la **foto del cartel de la nave** para confirmarlo |
| **7.2** | La **descripción**, ya escrita y corregida — 669 caracteres, sin el servicio técnico |
| **7.3** | Quitar el **Facebook de otra marca** (`facebook.com/gadgetiberia`) |
| **7.4** | Contestar la **reseña de 1 estrella**. Molde en `no-somos-ecoflow.md` |
| **7.5** | Mañana: quitar la categoría «Tienda de accesorios para automóviles». **La de «Servicio de reparación de electrónica» YA NO se puede añadir** |
| **7.6** | Al final: **quitar la ficha duplicada** «Ecoflow SASS Servicio Oficial España». La pulsa el dueño |
| **7.7** | **Publicar «Compré mi EcoFlow en otro sitio»**, ya escrita. Ahora es necesaria, no opcional |

**La fecha de 1997 NO se toca.** La ficha pasó la verificación con esa fecha, así que no era
el problema, y no influye en el posicionamiento local.

**Y el QR de reseñas:** el Place ID **no cambia** al renombrar ni al quitar la duplicada, así
que se puede imprimir ya. Hacen falta **entre 32 y 40 reseñas de cinco** para pasar de 3,9 a
4,3, no 25. Textos para pedirlas: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/google/pedir-resen%CC%83as.md`

---

# BLOQUE 8 · Mantenimiento, cuando lo anterior esté hecho

- **El pie está hecho con Elementor** (plantilla 3082, `location footer`), así que Elementor
  se pinta en las 326 URLs. **Para quitar Elementor hay que rehacer el pie primero**, o la
  web se queda sin pie en todas las páginas a la vez. Sus CSS y JS **no se cargan por
  separado**, así que el ahorro es menor de lo que parece.
- **AI Engine (`mwai`) está activo y no deja nada en el front.** No hay indicio de IA
  visible que quitar. **Preguntar al dueño si lo usa** antes de desactivarlo.
- **El umbral del envío gratis son 2.000 €**, confirmado. Nada que corregir.
- **El pie de Head & Footer Code se escribe por la ruta REST `eg/v1/pie`**, no por el
  formulario, que devuelve 403 del cortafuegos. Notas: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/mantenimiento/notas-video-y-pie.md`
- Las **27 categorías sin texto** que quedan del plan, y los **seis accesorios HyperShell**.
- **19 fragmentos a la papelera** y los **223 selectores CSS candidatos a muertos**.

---

# Lo que hace falta del dueño, todo junto

| Qué | Qué bloquea |
|---|---|
| **Unidades de cada uno de los cuatro HyperShell antiguos** | El bloque 1, que es lo de más valor por minuto |
| **Dos fotos del taller de Rivas** | Las fichas de HyperShell, la ficha de Google y lo local del solar |
| **El horario de la tienda** | Lo mismo |
| **Foto del cartel de la nave** | El nombre de la ficha de Google (7.1) |
| **Qué dice la reseña de 1 estrella** | La respuesta (7.4) |
| **Un correo de contacto público** | Las respuestas a reseñas |
| **¿Usa AI Engine para algo?** | El bloque 8 |
| **El inventario con los EAN** | Los precios siguen congelados hasta entonces |
| **Mandar el correo al fabricante**, ya escrito en `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/correos/hypershell-material-video.md` | Los vídeos propios, y los pesos, el par del Ultra S y los Wh de la batería |
