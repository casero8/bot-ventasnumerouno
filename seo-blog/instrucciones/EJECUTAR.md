# EcoGadget · cambios para aplicar · documento de ejecución

**27 de octubre de 2026.** Todo lo pedido por el dueño, con los textos exactos. Está
ordenado: **de arriba abajo, sin saltarse nada**.

Todo verificado leyendo el sitio en vivo el 9 y el 26-27 de octubre.

---

## Las seis normas que muerden en esta tanda

1. **Nunca leas una descripción de producto por `wc/v3`.** Devuelve los shortcodes ya
   ejecutados y al guardar los conviertes en texto fijo. Lee el bruto de `#content` en
   `post.php?post=ID&action=edit`. **Cuenta los `[eg_precio]` antes y después de cada
   guardado.** Ya se destrozaron dos fichas así.
2. **Un selector CSS de atributo parece un shortcode.** `[open]`, `[data-yt]`. Si cuentas
   shortcodes con una expresión ingenua, un `<style>` en línea te hará creer que has roto algo.
3. **Head & Footer Code y Opciones De Tema fallan al guardar una de cada dos veces.** Y el
   formulario del pie devuelve **403 del cortafuegos**: el pie se escribe por la ruta REST
   `eg/v1/pie` del fragmento #39. **Relee siempre del servidor después de guardar.**
4. **La caché de LiteSpeed sigue sirviendo páginas viejas tras purgar.** Comprueba todo con
   `?nc=1` antes de dar nada por roto.
5. **301 siempre, nunca 302.** Y ningún enlace interno debe pasar por una redirección.
6. **Ni un precio.** Siguen congelados hasta que el dueño pase el inventario con los EAN.

---

# 1 · La afirmación falsa · lo primero y lo más urgente

**EcoFlow les ha retirado la condición de servicio técnico.** Decir que lo son es
publicidad engañosa, y está publicado en las 326 URLs del sitio. **Siguen siendo
distribuidor oficial** — eso sí es cierto y no se toca.

### 1.1 · Quitar el JS que reescribe el menú · VA PRIMERO

En el JS del pie hay un bloque que **fuerza el texto del enlace del menú** en cada carga:

```js
...querySelectorAll("a[href*='servicio-tecnico']") ... if(a[i].textContent.trim()!=="Servicio técnico EcoFlow"){a[i].textContent="Servicio técnico EcoFlow";}
```

**Bórralo entero.** Si no, cualquier cambio que hagas en el menú se deshace solo en la
siguiente carga y parece que «no se guarda».

### 1.2 · Quitar el JS de sustituciones

Otro bloque del pie, con una lista `PARES`, que reescribe «Servicio Técnico Oficial de
EcoFlow» por «servicio técnico EcoGadget para equipos EcoFlow». Alguien ya intentó
suavizarlo. **Sigue diciendo «servicio técnico»: bórralo entero**, la frase se va del todo.

### 1.3 · El JSON-LD · el más grave

En el `<head>`, en los datos estructurados de la organización:

> **Antes:** `...paneles solares, arrancadores Lokithor y exoesqueletos HyperShell. Tienda física y taller propio para EcoFlow.`
> **Después:** `...paneles solares, arrancadores Lokithor y exoesqueletos HyperShell. Tienda física en Rivas-Vaciamadrid y distribuidor oficial en España.`

Es la descripción de la empresa que lee Google. Búscala en Yoast (Ajustes → Representas a)
o en el fragmento que genere el schema.

### 1.4 · El enlace del menú

«Servicio técnico EcoFlow», apuntando a `/servicio-tecnico-ecogadget/`, en el menú
principal **y en el móvil**. El rótulo pasa a **«Garantías»**, o se quita el enlace. Ver 1.7.

### 1.5 · El pie

Un JS inyecta `div.eg-pie-extra` con «**Servicio técnico oficial**» y «Pago fraccionado».
Quita la primera; deja la segunda.

### 1.6 · El CSS

Una regla con `content:'Taller propio en Rivas-Vaciamadrid'` sobre `body.page-id-9090`.
Bórrala.

### 1.7 · La página 9090

`/servicio-tecnico-ecogadget/`. **Decisión del dueño: 301 a los productos de EcoFlow.**

- Destino: la categoría de marca de EcoFlow si existe; si no,
  `/product-category/serie-delta/`. **Dime cuál eliges antes de activarla** y comprueba que
  responde 200 directo.
- La página, a **borrador**, nunca a la papelera. Y fuera del sitemap.
- Barre los enlaces internos que apuntaban a ella.

**Aviso para que no sorprenda:** esa página tiene **1.384 impresiones en posición 1-3** y
el 3,7 % de los clics del sitio. **Se van a perder.** No es un fallo de la decisión: es el
coste de haber perdido el servicio técnico. El 301 salva la autoridad de la URL.

---

# 2 · El bloque de garantías de la ficha

Es una rejilla de cuatro celdas, `div.eg-garantias`, que pinta un bloque global llamado
**`eg-fichas-garantias`**. **Un solo cambio vale para todos los productos EcoFlow.**

**Cambian tres textos. No toques las clases, ni los iconos, ni el número de celdas** — los
bordes los pintan reglas `nth-child` y se rompen.

| Celda | Título | Texto ahora | Texto nuevo |
|---|---|---|---|
| 1 | Distribuidor oficial EcoFlow | Producto nuevo con garantía oficial en España | **se queda** |
| 2 | Garantía gestionada por nosotros | Servicio técnico propio y tienda física, para compras en nuestra web | **Con tu número de pedido y en español, para compras en nuestra web** |
| 3 | Envío a la península | 14 días para devolverlo sin dar explicaciones | **Gratis a partir de 2.000 €** |
| 4 | Pago seguro | Tarjeta, Bizum o financiación a plazos | **Tarjeta o financiación a plazos** |

La celda 3 queda así coherente con el bloque que va justo debajo, que ya dice «Envío y
devoluciones: en todos los pedidos de más de 2.000,00 €».

---

# 3 · «14 días» fuera de los productos

**Decisión del dueño: desaparece de las fichas y se queda solo en la política.** No quiere
incitar a devolver.

**Es seguro, y está comprobado.** La política de devoluciones ya cumple la obligación legal
entera: dice el plazo, desde cuándo cuenta, cómo avisar, en qué estado, la dirección y
—literal— «el coste de enviárnoslo lo pagas tú. Es lo que marca la ley cuando el vendedor
lo ha informado de antemano, **y esto es ese aviso**». La información previa se da ahí.

### 3.1 · La celda 3 de la rejilla
Hecha en el punto 2.

### 3.2 · La línea global sobre «Ver más detalles»

> **Antes:** `Consulta nuestra política de devoluciones — 14 días para devolverlo sin usar y en su caja original.`

**El enlace se queda, la frase se va.** Mejor aún, cámbiala por algo que trabaje a favor:

> **Después:** `¿Dudas antes de comprar? Escríbenos y te decimos si es el que necesitas · Política de devoluciones`

Sustituye un mensaje que invita a devolver por uno que invita a preguntar. Búscala por la
cadena `en su caja original`.

### 3.3 · Los cinco Lokithor

Llevan el bloque viejo `ul.eg-trust` en su **descripción corta**. Solo cambia la tercera línea:

> **Antes:** `Envío a la península y 14 días para devolverlo sin dar explicaciones`
> **Después:** `Envío a la península, gratis a partir de 2.000 €`

Los cinco: `lokithor-j1350-pro`, `lokithor-j2250-pro`, `lokithor-j402-pro`,
`lokithor-j701hd`, `lokithor-ja3000-pro`.

**Por el bruto, nunca por `wc/v3`.**

---

# 4 · Bizum fuera de todo · no lo tienen

### 4.1 · La celda 4 de la rejilla
Hecha en el punto 2.

### 4.2 · El carrito

Un JS inyecta `div.eg-r-pagos`:

```js
nuevo('div','eg-r-pagos', ... 'VISA' ... 'Mastercard' ... 'Bizum' ... 'seQura · a plazos')
```

**Quita solo la pieza de Bizum.** VISA, Mastercard y seQura son reales.

### 4.3 · Los iconos en imagen

Busca logotipos de Bizum en `.png` o `.svg` en el pie, el carrito y finalizar compra. Una
búsqueda de texto no los encuentra.

### 4.4 · La pasarela

Se encontró en agosto **en modo de pruebas, con terminal y clave SHA-256 vacías**, y se
desactivó. Si sigue instalada, **desinstálala**, para que no reaparezca en una actualización.

---

# 5 · La URL rota

`/producto/cargador-mediante-alternador-ecoflow-plus-1000/` → **404**, sin redirección.

El producto existe, le cambiaron el slug:
`/producto/cargador-de-alternador-ecoflow-plus-1000-1000-w-12-24-v/` → **200**

**Crea el 301** en el plugin Redirection o en el fragmento #6. Estaba en el sitemap el 9 de
octubre, así que Google la tiene indexada y hoy manda a un error en vez de a un producto de
329 €.

**Comprueba de paso los otros siete slugs que cambiaron**, que sí redirigen bien hoy:
`adaptador-ecoflow-ev-x-stream-delta-pro`, `banco-de-energ-a-magn-tico-ecoflow-rapid-5-000mah`,
`bater-a-inteligente-hypershell`, `cable-de-carga-para-coche`, `cable-infinity`,
`hypershell-x-carbon`, `lampara-para-camping-ecoflow`.

---

# 6 · La capa de producto del servicio técnico

**30 de 149 productos** llevan alguna frase en su propia descripción. Lista exacta de qué
lleva cada uno: `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/mantenimiento/barrido-productos-2026-10-09.txt`

**Las urgentes son las falsas**, en la descripción larga de cinco productos:
«con tienda física y **servicio técnico propio**. Si hay avería **la reparamos nosotros**».

Sustituir por:

> con tienda física en Rivas-Vaciamadrid. Si algo falla, abres la incidencia con nosotros y
> con tu número de pedido, en español, en vez de pelearte con un formulario del fabricante.

Y la FAQ «¿Me gestionáis la garantía?», donde diga «como servicio técnico oficial de EcoFlow
te reparamos cualquier equipo de la marca»:

> Si compras el equipo aquí, **la gestionamos nosotros**: abres la incidencia con tu número
> de pedido y nos entendemos con el fabricante por ti, en español. Tienes la **garantía legal
> de tres años** que marca la ley española, y además la garantía comercial del fabricante
> cuando es más larga.
>
> Si lo compraste en otro sitio, la garantía la responde quien te lo vendió: es la ley y es
> a quien tienes que reclamar. Nosotros **no somos un taller de reparación**.

Los cinco: `ecoflow-delta-3-classic-1024wh`, `ecoflow-river-3-max-plus`,
`ecoflow-stream-ca-pro`, `estacion-energia-portatil-ecoflow-delta-3-plu`,
`inversor-ecoflow-powerstream`.

**Los textos completos ya están escritos** en `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/` (`*-larga.html` y `*-corta.html`).

> **Nota:** los `eg-trust` de los productos **EcoFlow ya no se ven** —el bloque nuevo los
> oculta—, así que ahí solo importa la descripción **larga**. En Lokithor sí se ve el viejo.

---

# 7 · Stock: lo que más vale de todo esto

Los cuatro HyperShell de la generación anterior **tienen ficha y están marcados como
agotados**, con 82 unidades en el almacén:

| Producto | Precio |
|---|---|
| `hypershell-x-ultra` | 1.799 € |
| `hypershell-x-carbon-exoesqueleto-de-1-8-kg-en-titanio-y-carbono` | 1.299 € |
| `hypershell-x-pro` | 899 € |
| `hypershell-x-go` | 699 € |

1. **Poner el stock real.** Falta el dato del dueño: cuántas de cada una.
2. Comprobar que salen en `/product-category/hypershell/`. Con stock cambia también la fila
   de pastillas, que filtra por producto comprable.
3. **Pegar el bloque de la diferencia entre generaciones**, ya escrito, al principio de la
   descripción larga. El párrafo final cambia por modelo.
   `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-generacion-anterior.html`

**El mejor argumento:** el X Carbon declara **1,8 kg en titanio**, por debajo de los ~2,5 kg
del X Ultra S actual. **Sigue siendo el más ligero del catálogo.**

---

# 8 · El PowerStream

`/producto/inversor-ecoflow-powerstream/` · agotado · **624 impresiones al año**.
**No se borra ni se redirige.** Se le pone arriba:

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Este modelo está descontinuado.</strong> EcoFlow lo ha sustituido por el <strong>microinversor STREAM</strong>, que hace el mismo trabajo dentro de la gama nueva. Si buscabas el PowerStream para comprarlo, <a href="/product-category/stream-series/">esto es lo que le corresponde hoy</a>. Si ya tienes uno y buscas su manual, está en <a href="/man/">los manuales en PDF</a>.</p>
</div>
```

---

# 9 · Lo que queda escrito y esperando

Textos completos listos para aplicar por REST, comparando bytes y sin tocar `<!--eg-corte-->`:

| Qué | Archivo |
|---|---|
| Fichas **X Max S** y **X Ultra S** | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/fichas/hypershell-x-max-s-larga.html` y `-corta`, `hypershell-x-ultra-s-*` |
| Categoría **`accesorios-hypershell`** | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/categorias/accesorios-hypershell.html` |
| Categoría **`stream-series`** + H1 a «EcoFlow STREAM» | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/categorias/stream-series.html` |
| Categoría **`paneles-solares`** con el aviso de «no instalamos» | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/categorias/paneles-solares.html` |
| Reaplicar **`hypershell`** por el título repetido | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/categorias/hypershell.html` |
| **Fragmento #38**: fuera los gramos y fuera el 63 % y el 20 % | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-10-decisiones.md` |
| El racimo solar: los 301 y los títulos | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/instrucciones/tanda-13b-stream-y-solar.md` |
| La ficha de Google | `https://raw.githubusercontent.com/casero8/bot-ventasnumerouno/claude/blog-seo-optimization-zyoa2k/seo-blog/google/ficha-google.md` |

---

# Comprobación final

Con `?nc=1`, en **una ficha EcoFlow, una Lokithor, una HyperShell, una categoría, el
carrito y la portada**:

1. `servicio técnico` → **cero**, incluidos los bloques de JS y de CSS.
2. `taller propio` → **cero**, **incluido el JSON-LD**. Ése es el que se olvida.
3. `14 días` y `caja original` → **cero** fuera de la política de devoluciones.
4. `Bizum` → **cero**, texto e imágenes.
5. **Recarga tres veces una ficha:** si «Servicio técnico EcoFlow» reaparece en el menú, el
   JS del 1.1 sigue vivo.
6. **El enlace a la política de devoluciones sigue en el pie** y es alcanzable antes de
   pagar. Esto es lo que sostiene legalmente el punto 3.
7. El 301 del punto 5 responde **301**, y su destino **200 directo**.
8. **En las fichas tocadas, el recuento de `[eg_precio]` es el mismo que antes.**
9. La rejilla de cuatro celdas no se descoloca en móvil.
10. Purgar LiteSpeed y WP Super Cache.
