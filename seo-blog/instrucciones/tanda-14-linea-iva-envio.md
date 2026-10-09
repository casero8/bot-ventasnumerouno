# Tanda 14 · la línea «IVA incluido · Envío gratis · 14 días para devolverlo»

**9 de octubre de 2026.** El dueño ha pasado la línea tal y como sale en el front:

> `IVA incluido · Envío gratis · 14 días para devolverlo`

Pide quitar **lo de los 14 días**. Pero esa línea tiene **dos problemas, no uno**, y el
segundo es más grave que el que ha visto.

---

## Problema 1 · «14 días para devolverlo» · lo que ha pedido

Fuera de todos los productos. Es el mismo criterio de la tanda 12: el derecho de
desistimiento sigue existiendo y la política de devoluciones sigue explicándolo, pero
**deja de usarse como argumento de venta**.

## Problema 2 · «Envío gratis» a secas · esto es una afirmación falsa

**El envío es gratis a partir de 2.000 €.** Decir «Envío gratis» sin la condición, en la
ficha de un producto de 25 €, es publicidad engañosa — y es **exactamente la misma
incidencia que ya se corrigió en agosto**, cuando las 158 fichas mostraban «Envío Gratis Y
Devoluciones» y se quitó por este motivo.

Es muy posible que esta línea sea el **reemplazo que se puso entonces** y que arrastrara el
mismo error. Conviene mirarlo con eso en la cabeza.

**La línea correcta, que es la que ya usan las cuatro fichas del repositorio:**

```html
<p class="eg-taxline">IVA incluido &middot; <b>Envío a toda España</b> &middot; Gratis a partir de 2.000 €</p>
```

Dice tres cosas ciertas y además **informa del umbral**, que es útil: quien está en 1.800 €
puede decidir añadir algo. «Envío gratis» a secas no informa de nada y expone a una
sanción.

---

## Lo primero: encontrar de dónde sale. **No se editan 158 fichas.**

La línea sale en **todos** los productos, así que no está escrita 158 veces: está en un
sitio y se imprime en todos. En el repositorio solo hay cuatro fichas que la llevan a mano,
y esas cuatro **ya están corregidas**.

Búscala en este orden, que va de lo más probable a lo menos:

| # | Dónde | Qué buscar |
|---|---|---|
| **1** | **Fragmentos de código** (los 40) | `eg-taxline`, `IVA incluido`, `Envío gratis`. El prefijo `eg-` es nuestro, así que lo más probable es que sea un fragmento con un `add_action` a `woocommerce_single_product_summary` o a `woocommerce_product_meta_start` |
| **2** | **Un filtro `gettext`** | Es la vía que ya se usó en agosto para este mismo texto. Mira `gettext`, `gettext_woocommerce` y `ngettext`. Ojo: puede estar reemplazando una cadena del tema |
| **3** | **Tema hijo** `minimog-child/functions.php` | 29 KB y **no es escribible**. Si está ahí, no se edita: se neutraliza desde un fragmento con un filtro o quitando el `add_action` con `remove_action` |
| **4** | **Opciones de Tema** | Minimog puede tener un campo de «información extra de producto». Si es ahí, acuérdate de la norma 7: huella de los 1.904 campos antes y después |
| **5** | **CSS, como `content:`** | Es la última posibilidad y la peor. Hay dos textos del sitio metidos así, según la norma 1. Si fuera el caso, se cambia en el mismo sitio donde esté |
| **6** | Las descripciones cortas | **Comprueba dos o tres por REST** para descartarlo. Si estuviera en las 158, hay que hacer un reemplazo en bloque, no a mano |

> **Punto de control 1.** Dime **dónde está** y **pégame el código** antes de tocarlo. Según
> cuál de los seis sea, la forma de arreglarlo cambia, y el 3 y el 4 tienen trampa.

---

## El arreglo, una vez localizada

Sustituir el texto completo por:

> `IVA incluido · Envío a toda España · Gratis a partir de 2.000 €`

Y conservar el marcado: la clase `eg-taxline` tiene estilo propio, así que **no se cambia
el `<p class="eg-taxline">`**, solo lo que hay dentro. Si se quita la clase, la línea se
descoloca en las 158 fichas a la vez.

**Si estuviera en el tema hijo** (caso 3), que no es escribible: no se toca el archivo. Se
hace un fragmento nuevo que quite el `add_action` original con `remove_action` y pinte el
suyo, o un filtro sobre el texto. Y se deja anotado en el fragmento por qué existe.

---

## El barrido que va con esto

Mientras estés en ello, el mismo texto puede estar en más sitios:

1. **La portada y las páginas de categoría**, si llevan la misma pastilla.
2. **El carrito y la página de finalizar compra.** Ahí un «envío gratis» falso es peor,
   porque está al lado del importe.
3. **El pie de página.**
4. **Los correos de pedido**, que suelen repetir las condiciones.
5. **Las etiquetas de envío de WooCommerce** (Ajustes → Envío): si hay un método llamado
   «Envío gratis» sin condición configurada, ahí está el origen real y hay que mirar la
   configuración, no solo el texto.

**El punto 5 es importante:** si existe un método de envío gratuito sin mínimo configurado,
el problema no es el texto, es que **el envío está siendo gratis de verdad** en pedidos por
debajo de 2.000 €. Compruébalo antes de cambiar nada: con un producto de 25 € en el
carrito, mira qué cobra de portes. Si cobra cero, hay un agujero de dinero y no solo de
redacción.

---

## Comprobar al terminar

Todo con `?nc=1` (norma 11):

1. **Tres fichas de precios distintos** —una barata, una media y una de más de 2.000 €— y
   que las tres digan la línea nueva.
2. **Buscar «14 días» en el front.** Cero resultados.
3. **Buscar «Envío gratis»** sin la condición. Cero resultados.
4. **Un producto de 25 € en el carrito:** que cobre portes. Si no los cobra, para y dime.
5. Que la línea **no se ha descolocado** en móvil ni en escritorio.
6. Purgar LiteSpeed y WP Super Cache.

> **Punto de control 2.** El informe de los seis puntos, con capturas de las tres fichas y
> del carrito con el producto barato.

---

## Para la lista de lecciones

Esto es la tercera vez en dos meses que aparece el mismo tipo de fallo: **una afirmación
comercial puesta en los 158 productos a la vez desde un solo sitio**. Primero «Envío Gratis
Y Devoluciones», luego «servicio técnico propio», ahora «Envío gratis».

La lección operativa: **cuando se pone una frase que se va a imprimir en las 158 fichas, se
revisa como si fuera un contrato**, porque es lo que es. Y conviene que alguna vez se haga
el camino contrario: coger las frases que hoy se imprimen en todas las fichas, listarlas, y
comprobar una por una que siguen siendo verdad. Hay tres sitios de los cinco donde vive el
código que pueden estar imprimiendo algo así sin que nadie se acuerde.
