# Respuestas a los puntos de control · tanda 9

**18 de agosto de 2026** · ecogadgetoficial.com

Informe recibido y revisado. Cinco de tus correcciones son buenas y las incorporo; una
atribución está equivocada y la corrijo abajo. Vas ordenado por si te sirve de guion.

---

# TANDA 7

## 1 · HyperShell — **adelante, aplica los dos precios rebajados**

| Variante | Regular | Rebajado |
|---|---|---|
| Kit AeroFlex · Nylon (8359) | 159 € | **80 €** |
| Kit AeroFlex · AquaTex (8360) | 219 € | **110 €** |

Nada más. Los otros siete quedan como están.

**Tus tres correcciones son correctas y el error era mío**, así que queden anotadas:

- La Batería Anti-Frío Pro **sí existe**, como variante del producto variable 8350. Yo leí
  «119 €» de un informe anterior y di por hecho que faltaba la otra. Era el mínimo del
  rango de un producto variable.
- El Kit AeroFlex **no se vende al doble**: sus precios regulares ya eran los buenos y lo
  que faltaba era la promoción. No hay que averiguar el material.
- No hay generación anterior en catálogo.

Después de aplicarlo: comprueba `/ofertas/` y confirma que sigue sin salir ningún
«AGOTADO».

---

## 2 · Los ocho remiendos — **adelante, con un cambio de color**

El CSS es necesario, tienes razón: mandé una clase sin estilo y eso no es un bloque, es una
lista suelta. Dos cambios antes de guardarlo:

**a) La paleta.** El dueño lo dijo con estas palabras: *«los botones esos en color naranja
no me gustan, prefiero blanco y un reborde naranja»*, y también *«el azul ese lo odio»*. Tu
versión pone las pastillas en blanco con reborde gris y al pasar el ratón las invierte a
negro. Cámbialo al naranja de la casa, que es `#ff5a1f`:

```css
.eg-salidas a{
  display:flex;align-items:center;
  padding:9px 14px;min-height:44px;
  background:#fff;border:1px solid #ff5a1f;border-radius:999px;
  font-size:.95rem;line-height:1.3;color:#1d1b1a;text-decoration:none;
  transition:background .18s ease,color .18s ease;
}
.eg-salidas a:hover,
.eg-salidas a:focus-visible{background:#ff5a1f;color:#fff}
```

**b) Quita el `display:inline-block`.** Lo pisa el `display:flex` de la línea siguiente, no
hace nada y confunde a quien lea el CSS mañana.

El resto del bloque, tal cual lo has escrito. El objetivo táctil de 44 px, bien visto.

**Las siete restantes: adelante**, con los enlaces que has comprobado. Están bien elegidos.

---

## 3 · Categorías — el plan cambia, y lo cambian tus datos

Tus dos hallazgos obligan a replantear, así que no escribas todavía ni una línea de texto.

### A · `accesorios-delta-3` — el problema no es el texto, es el surtido

42 clics y 1.715 impresiones al año llegando a una categoría con **dos productos, uno
agotado, y el otro un panel solar genérico de 400 W que está en otras tres categorías**.

Escribir 10 KB de guía ahí sería poner un escaparate bonito delante de un local vacío.
**Lo que hay que arreglar primero es qué hay dentro.**

Tarea: recorre los 35 productos de `accesorios` y los 22 de `accesorios-delta-pro`, y
**añade a `accesorios-delta-3` todos los que sean compatibles con la DELTA 3**, según lo
que diga la ficha de cada uno. Cables, fundas, bolsas de transporte, adaptadores.

Dos avisos:

- **Añadir un producto a una categoría no cambia ninguna URL.** Es seguro. Lo que no se
  puede tocar es el padre de la categoría.
- **Solo si la ficha declara la compatibilidad.** Si no lo dice, no lo metas: un accesorio
  que no encaja es una devolución.

Cuando termines, dime cuántos productos tiene y cuántos se pueden comprar. Con eso decido
si toca guía o ficha corta.

### B · `baterias-adicionales` — seis de seis agotados y 3.715 impresiones

Es la página con más demanda del árbol y no vende nada. Aun así **se escribe**, y se escribe
ahora, por dos razones: la demanda no se va a esperar a que haya stock, y el texto puede
llevar a quien llega hacia lo que sí se puede comprar hoy.

Enfoque: **una tabla de compatibilidad**, no un catálogo. «Qué batería admite cada
estación, cuánto sube la capacidad y a cuánto llega el conjunto». Cada fila enlaza a la
**categoría de la estación**, que sí tiene producto. Nada de avisos de agotado en el texto,
nada de escasez: se enseña lo que hay.

**Las cifras oficiales del fabricante, ya contrastadas contra ecoflow.com el 18/08/2026.**
Úsalas solo si coinciden con la ficha del producto; si la ficha dice otra cosa, para y
dilo:

| Batería | Capacidad | Conjunto máximo | Ciclos / vida | Garantía | Peso y medidas |
|---|---|---|---|---|---|
| Serie DELTA 3 | 1.024 Wh | — | +4.000 ciclos · 10 años al 80 % · LFP | **5 años** | 9,6 kg · 398 × 200 × 198 mm |
| DELTA 2 Max | 2.046 Wh | 6.144 Wh con dos | 3.000 ciclos · 10 años al 80 % · LFP | **5 años** | — |
| DELTA 3 Max Plus | 2.048 Wh | — | LFP | — | Compatible con DELTA 3 Max Plus y 3 Ultra Plus |
| DELTA Pro | 3.600 Wh | **10,6 kWh con dos** | LFP | — | — |
| DELTA Pro 3 | 4.096 Wh | de 4 a 12 kWh | — | — | Salida 4.000 W · 30 dB |
| DELTA Pro Ultra | 6 kWh | 30 kWh en conjunto | — | — | 660 × 455 × 204 mm |

**Esto resuelve dos de las fichas rotas que encontraste:**

- **La contradicción de la DELTA Pro (2572), 10,6 frente a 10,8 kWh:** el fabricante dice
  **10,6 kWh**. La descripción corta tiene razón y la larga está mal. Corrige la larga.
- **La garantía de la Serie DELTA 3:** el fabricante da **5 años de garantía**. Los «10
  años» son de **vida útil** al 80 % de capacidad, no de garantía. Si la ficha dice
  «garantía 10 años», está mal, y no por casualidad: los 10 años de garantía son de los
  **paneles STREAM**, que es justo la tabla que se coló ahí.

---

## 4 · Decisiones

**Adelante sin esperar al dueño** (son afirmaciones falsas o incumplimientos, no gustos):

- **(2) DELTA Pro Ultra.** Quita «Disponible ahora con envío rápido» mientras esté agotada.
  Sustituye por la fórmula que ya usamos: «En stock en España, sin esperas de importación»
  **solo cuando vuelva a haberlo**; mientras tanto, ninguna promesa de plazo.
- **(3) Las dos metas de Yoast.** Quita «envío inmediato» del Seguidor solar (7461) y de la
  RIVER 3 Plus (4984). Bien localizadas. Reescríbelas sin promesa de plazo y **sin
  mencionar las vacaciones en la meta**: el aviso de vacaciones ya sale en la web y una meta
  se queda meses cacheada en Google.
- **(6) Condiciones generales.** Enlázala desde el pie. Es obligación legal, no una opción.
- **(10) La tabla del microinversor en la batería DELTA 3 (4951).** **Bórrala hoy**, aunque
  la sustitución venga después: publicar la ficha técnica de otro aparato es peor que no
  publicar ninguna. Luego móntala con las cifras oficiales de la tabla de arriba:
  1.024 Wh · LFP · +4.000 ciclos · 10 años al 80 % · garantía 5 años · 9,6 kg ·
  398 × 200 × 198 mm · compatible con DELTA 3 y DELTA 3 Plus · plug and play con la app
  EcoFlow.

**Estas necesitan al dueño y no las toques:** (1) los 13 precios borrados · (4) si el STREAM
Series Standard incluye paneles · (5) el enlace `/1381-2/` · (9) fecha de reposición de las
baterías.

**Estas son avisos al hosting, no trabajo tuyo:** (7) permisos de `functions.php` ·
(8) la pantalla de espera del anti-bot.

---

# TANDA 8

## 1 · Las letras que encogen — **diagnóstico impecable, y una corrección**

Lo de `[class*="badge"]` casando con `header-icon-badge-large` es el hallazgo de la tanda, y
haberlo comprobado quitando la clase en caliente es exactamente como se hace. **Adelante con
tu CSS, tal cual, `!important` incluido.** Lo has justificado y hace falta.

**La corrección: el bloque no está en el plugin «EG · Portada».** Está aquí:

> **Herramientas → Head & Footer Code**, campo *Código del pie de página*
> (opción `auhfc_settings_sitewide[footer]`). Son 61.317 caracteres.
> La URL es `/wp-admin/tools.php?page=head-footer-code`, **con guiones**. Con guiones
> bajos da un 403 de WordPress que parece un problema de permisos y no lo es.

Y por eso tu instinto de arreglarlo en origen, que era el correcto, **no se puede seguir**:
ese formulario devuelve **403 del WAF del servidor al guardar**, incluso reenviando su
propio contenido sin tocar una coma. Está de solo lectura desde antes de esta tanda. Se
comprobó con sondas: el CSS nuevo pasa, lo que bloquea es el contenido ya guardado.

Así que la vía del personalizador no es un parche por comodidad: **es la única que hay**.
Déjalo anotado en el CSS para quien lo lea dentro de seis meses.

Al comprobar después, añade una cosa a tu lista: que las **etiquetas de producto**
(AGOTADO, OFERTA) sigan viéndose a 11,5 px en las categorías. Es lo que la regla original
quería hacer, y no hay que romperlo.

## 2 · El menú — decidido: **opción 2, sin Elementor**

Has planteado bien las tres salidas. La respuesta es la **2: submenú de dos niveles con CSS
de columnas en el personalizador.**

Por qué no la 1, aunque sea la que el tema espera: meter Elementor en la cabecera lo carga
en **todas** las páginas del sitio. Este sitio pasó de 2,62 s a 0,056 s de tiempo de
respuesta con bastante trabajo detrás, y el CSS y el JS ya suman 2,9 MB. Nueve plantillas
de Elementor en la cabecera se comen esa mejora. Un menú bonito que hace la web más lenta
sale perdiendo: la velocidad la nota todo el mundo en todas las páginas, el menú solo quien
lo despliega.

Y hay un matiz que faltaba en tu opción 2, y que la mejora bastante: **el CSS de columnas
falla sin romper nada**. Si una actualización del tema cambia el marcado, el selector deja
de casar y el desplegable vuelve a ser una lista normal. Sigue funcionando, sigue
navegándose. No es el caso de un mega menú a medio construir.

### Cómo se montan las columnas

Sin tocar el marcado, con `column-count` sobre el submenú:

```css
/* Paneles del menu a varias columnas, como MediaMarkt, sin tocar el marcado.
   Si una actualizacion del tema cambia las clases, esto deja de aplicarse y el
   desplegable vuelve a ser una lista normal: se degrada, no se rompe. */
@media (min-width:1025px){
  SELECTOR_DEL_SUBMENU{
    column-count:2;column-gap:40px;
    min-width:520px;padding:32px;
  }
  SELECTOR_DEL_SUBMENU > li{break-inside:avoid}
  SELECTOR_DEL_SUBMENU > li > a{padding:7px 0;font-size:15px;line-height:1.6}
}
```

Tres columnas y `min-width:760px` solo en «Estaciones DELTA», que es el que tiene once
entradas. Los demás, con dos van sobrados.

Lo demás de la tanda 8 sigue en pie tal cual: 24 px entre los enlaces de arriba, máximo
8 enlaces por columna, y en móvil 48 px de alto de fila, 16 px de tipo y un nivel cada vez.

### Tus cuatro preguntas

**Baterías adicionales se queda como departamento de primer nivel, con un solo enlace y sin
panel.** 3.715 impresiones es la mayor demanda del árbol: que esté arriba y visible. Amazon
y MediaMarkt también tienen departamentos que son un enlace suelto. Y no, que estén
agotados no lo baja de sitio: la categoría se va a escribir y va a llevar a las estaciones.

**Los tres productos HyperShell: sí, entran, como excepción.** Amazon y MediaMarkt enlazan
productos estrella desde el menú. La regla queda así: **se puede enlazar un producto desde
el menú cuando su categoría tiene 10 productos o menos y el producto es un buque insignia**.
Con nueve productos, HyperShell entra de lleno. Y es la novedad de la tienda: que se vea en
el primer vistazo, con su etiqueta.

**Camping y nevera: como columna dentro de «Estaciones de energía»**, como propones.
Aplicaste bien la instrucción.

**Accesorios, columna «Por tipo»: fuera.** De los tres, solo existe `soportes` y con un
producto. Una columna de un enlace no es una columna. Queda solo «Por gama».

### Dos cosas más del menú actual

- **Arregla el duplicado**: «Casa y balcón» y su primera hija apuntan las dos a
  `/kits-para-balcones/`. El departamento debe apuntar a la categoría
  `/product-category/stream-series/`, y la hija «Kits para balcones» se queda con la página,
  que es un caso de uso y sí aporta algo que la categoría no da.
- **Ofertas**: separada del resto y en otro color. Es lo único del menú que va en color
  distinto.

**Y anotada tu corrección:** lo de «5 categorías y 8 páginas» venía del análisis del 16 de
agosto y ya no valía. Hoy son 32 de 35 apuntando a categorías. El menú está mucho mejor de
lo que yo daba por hecho, y eso reduce el trabajo: esto es sobre todo presentación.

---

## Orden sugerido

1. Los dos precios del Kit AeroFlex. Cinco minutos y es lo que pidió el dueño.
2. El CSS de la cabecera. Arregla un fallo visible en todas las categorías.
3. El CSS de `.eg-salidas` y las siete categorías restantes.
4. Borrar la tabla del microinversor de la batería 4951 y montarla bien.
5. Las dos metas de Yoast, el DELTA Pro Ultra y el enlace de Condiciones generales.
6. El surtido de `accesorios-delta-3`.
7. El menú: columnas, espaciado, duplicado y Ofertas.

Vacía la caché al terminar cada bloque, y avisa de lo que no haya salido como esperabas.
