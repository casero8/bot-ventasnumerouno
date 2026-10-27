# Tanda 16 · fuera «14 días» de las fichas, y fuera Bizum de todas partes

**27 de octubre de 2026.** Decisión del dueño: la frase de los 14 días **desaparece de los
productos** y se queda **solo en la política de devoluciones**. Y **Bizum fuera de todo el
sitio: no lo tienen.**

---

## Esto es seguro, y está comprobado

En las tandas anteriores avisé de que quitar la información de devoluciones podía dejar al
vendedor pagando el porte de vuelta. **He leído la política en vivo y no es el caso: ya
hace el trabajo legal entero.** Dice, con estas palabras:

> «Si has comprado a distancia dispones de **14 días naturales** para desistir de la compra
> sin dar ninguna explicación y sin penalización.»

> «Cuando devuelves porque te has arrepentido, **el coste de enviárnoslo lo pagas tú**. Es
> lo que marca la ley cuando el vendedor lo ha informado de antemano, **y esto es ese
> aviso**.»

Está todo: el plazo, desde cuándo cuenta, cómo avisar, en qué estado, la dirección, quién
paga el transporte y qué se reembolsa. **La obligación de informar se cumple ahí, no en
cada ficha.** Así que se puede quitar de los productos sin ningún riesgo.

**La única condición, y hay que comprobarla:** que la política **siga enlazada desde el pie
y sea alcanzable antes de pagar**. Si el enlace del pie desaparece, entonces sí hay un
agujero. Compruébalo al terminar.

---

## 1 · «14 días» · los tres sitios de donde sale

| # | Dónde | Qué dice | Qué hacer |
|---|---|---|---|
| **1.1** | Bloque **`eg-fichas-garantias`**, celda 3 | `Envío a la península` / «14 días para devolverlo sin dar explicaciones» | El texto pasa a **«Gratis a partir de 2.000 €»** |
| **1.2** | Línea global encima de «Ver más detalles» | «**Consulta nuestra política de devoluciones** — 14 días para devolverlo sin usar y en su caja original.» | **Quitar la frase de los 14 días. Dejar el enlace** |
| **1.3** | `ul.eg-trust` de los **cinco Lokithor** | «Envío a la península y 14 días para devolverlo sin dar explicaciones» | **«Envío a la península, gratis a partir de 2.000 €»** |

**El 1.2 tiene matiz y es el que pidió el dueño en esta tanda.** No se borra el bloque
entero: **el enlace a la política se queda**, porque es lo que lleva a la información que sí
hay que dar. Lo que se va es el resumen que incita a devolver. Queda así:

> **Consulta nuestra política de devoluciones.**

O, si se prefiere algo más neutro y que además venda:

> **¿Dudas antes de comprar?** [Escríbenos](/contacto/) y te decimos si es el que
> necesitas · [Política de devoluciones](/desestimiento-devolucion/)

La segunda versión es mejor: sustituye un mensaje que invita a devolver por uno que invita
a preguntar, que es lo que de verdad evita la devolución.

**Dónde está el 1.2:** sale en todos los productos, así que es global. Búscalo por la cadena
`en su caja original` en los fragmentos y en el pie de Head & Footer Code.

---

## 2 · Bizum · los dos sitios

| # | Dónde | Qué dice |
|---|---|---|
| **2.1** | Bloque **`eg-fichas-garantias`**, celda 4 | `Pago seguro` / «Tarjeta, **Bizum** o financiación a plazos» → **«Tarjeta o financiación a plazos»** |
| **2.2** | **Carrito**, JS que inyecta `div.eg-r-pagos` | «Pago 100 % seguro y cifrado · VISA · Mastercard · **Bizum** · seQura · a plazos» → quitar **Bizum**, dejar el resto |

El 2.2 está en un JavaScript, en una línea del tipo
`nuevo('div','eg-r-pagos', ... 'VISA' ... 'Mastercard' ... 'Bizum' ... 'seQura · a plazos')`.
**Quita solo la pieza de Bizum**, no toques VISA, Mastercard ni seQura, que sí son reales
—seQura aparece registrada en la API del sitio—.

**Y comprueba si hay iconos de pago en imagen**, no solo texto: en el pie, en el carrito y
en finalizar compra. Un logotipo de Bizum en un `.png` o un `.svg` no lo encuentra una
búsqueda de texto. Si existe, fuera también.

**Recordatorio:** la pasarela de Bizum se encontró en agosto **instalada en modo de pruebas,
con terminal y clave SHA-256 vacías**, y se desactivó antes de que pudiera cobrar nada. Así
que esto es solo limpiar las menciones; no hay pasarela que tocar. Si sigue instalada y
desactivada, **desinstálala**, para que no vuelva a aparecer sola en una actualización.

---

## 3 · Un hallazgo suelto: una URL rota en el sitemap

`/producto/cargador-mediante-alternador-ecoflow-plus-1000/` **está en el sitemap de
productos y devuelve «Página no encontrada»**. Es el producto de la captura del dueño, el
de 329 €.

O ha cambiado de slug, o se ha despublicado. **Hay que mirarlo:** si existe con otro slug,
toca un 301 del viejo al nuevo; si se ha despublicado, hay que sacarlo del sitemap. Una URL
muerta en el sitemap se la come el rastreo y además es una venta que no se puede hacer.

---

## Comprobar al terminar

Con `?nc=1`, en **una ficha EcoFlow, una Lokithor, una HyperShell, el carrito y finalizar
compra**:

1. Buscar **`14 días`**: cero en las fichas y en el carrito. **Solo debe aparecer dentro de
   la política de devoluciones.**
2. Buscar **`caja original`**: cero fuera de la política.
3. Buscar **`Bizum`**: cero en todo el sitio, texto e imágenes.
4. **El enlace a la política de devoluciones sigue estando** en el pie y es alcanzable antes
   de pagar. Esto es lo que sostiene legalmente todo lo anterior.
5. La rejilla de cuatro celdas **no se descoloca** en móvil. **No cambies el número de
   celdas**, solo los textos: los bordes los pintan reglas `nth-child`.
6. Purgar LiteSpeed y WP Super Cache.
