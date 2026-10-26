# Tanda 15 · el bloque de garantías de la ficha · 14 días y Bizum fuera

**26 de octubre de 2026.** Leído en vivo. El dueño pide quitar **«14 días para devolverlo»**
y **«Bizum»** del bloque de cuatro celdas que sale bajo el botón de comprar.

**Buena noticia: casi todo está en un solo sitio.** Pero el bloque tiene dos cosas más que
el dueño no ha señalado y que hay que arreglar en la misma pasada.

---

## Dónde está

Desde la última vez, alguien sustituyó el bloque viejo por uno nuevo:

| Tipo de producto | Qué bloque pinta | Dónde vive |
|---|---|---|
| **EcoFlow** | `div.eg-garantias`, rejilla de 4 celdas | Un bloque llamado **`eg-fichas-garantias`**, global |
| **Lokithor** | `ul.eg-trust`, el viejo | **Descripción corta** de cada producto |
| **HyperShell** | ninguno de los dos | — |

El bloque `eg-fichas-garantias` **también esconde el viejo**, con
`body.eg-marca-ecoflow .summary ul.eg-trust{display:none!important}`. Por eso el viejo
sigue visible en Lokithor: **la regla que lo oculta solo aplica a los productos de EcoFlow.**

> Esto corrige el bloque 2 del documento maestro: los textos `eg-trust` de los productos de
> EcoFlow **ya no se ven**, así que editarlos uno a uno no sirve de nada. Lo que se ve es el
> bloque nuevo.

---

## 1 · El bloque `eg-garantias` · un solo cambio para todos los EcoFlow

Busca **`eg-fichas-garantias`** en los fragmentos de código. Ahí está el CSS y el marcado.

**Lo que dice hoy, y los cuatro cambios:**

| Celda | Título | Texto actual | Qué hacer |
|---|---|---|---|
| 1 | Distribuidor oficial EcoFlow | Producto nuevo con garantía oficial en España | **Se queda.** Es cierto |
| 2 | Garantía gestionada por nosotros | ~~Servicio técnico propio y tienda física, para compras en nuestra web~~ | **FALSO.** Ya no son servicio técnico |
| 3 | Envío a la península | ~~14 días para devolverlo sin dar explicaciones~~ | **Fuera**, y además el título habla de envío y el texto de devoluciones |
| 4 | Pago seguro | ~~Tarjeta, Bizum o financiación a plazos~~ | **Bizum fuera** |

**La celda 2 es la urgente y no la ha visto el dueño:** la afirmación falsa de servicio
técnico está publicada **justo debajo del botón de comprar**, en todos los productos
EcoFlow. Es lo mismo que venimos arrastrando desde el 9 de octubre.

**Y la celda 3 está rota de antes:** el título dice «Envío a la península» y el texto habla
de devoluciones. Alguien cambió el título y dejó el cuerpo viejo.

### El marcado corregido, para pegar

Solo cambian tres `<span>`. **No toques las clases ni los iconos:**

```html
<div class="eg-garantia" role="listitem">
<span class="eg-garantia__ico">[deja el icono que ya está]</span>
<span class="eg-garantia__txt">
<strong>Garantía gestionada por nosotros</strong>
<span>Con tu número de pedido y en español, para compras en nuestra web</span>
</span>
</div>

<div class="eg-garantia" role="listitem">
<span class="eg-garantia__ico">[deja el icono que ya está]</span>
<span class="eg-garantia__txt">
<strong>Envío a la península</strong>
<span>Gratis a partir de 2.000 €</span>
</span>
</div>

<div class="eg-garantia" role="listitem">
<span class="eg-garantia__ico">[deja el icono que ya está]</span>
<span class="eg-garantia__txt">
<strong>Pago seguro</strong>
<span>Tarjeta o financiación a plazos</span>
</span>
</div>
```

La celda 3 queda coherente con el bloque que va justo debajo, que ya dice «Envío y
devoluciones: en todos los pedidos de más de 2.000,00 €».

---

## 2 · El bloque viejo, que sigue vivo en Lokithor

En los cinco Lokithor, la descripción corta lleva el `ul.eg-trust` antiguo. Literal:

> - Distribuidor oficial Lokithor en España — producto nuevo, con garantía del fabricante
> - Tienda física — si tienes dudas de si te vale para tu coche, pregúntanos antes de comprar
> - **Envío a la península y 14 días para devolverlo sin dar explicaciones**
> - Pago seguro con tarjeta o financiación a plazos

**Solo hay que cambiar la tercera línea** por:

> - Envío a la península, gratis a partir de 2.000 €

Las otras tres están bien: el Bizum ya no está, y lo de Lokithor dice «garantía del
fabricante», que es lo correcto.

**Los cinco:** `lokithor-j1350-pro`, `lokithor-j2250-pro`, `lokithor-j402-pro`,
`lokithor-j701hd`, `lokithor-ja3000-pro`.

**Norma 2:** por el bruto desde `post.php?post=ID&action=edit`, nunca por `wc/v3`.

---

## 3 · Barrer el resto

Con los dos anteriores hechos, repasa si queda el bloque viejo visible en algún producto
que no sea EcoFlow ni Lokithor. La forma rápida: buscar **`sin dar explicaciones`** en el
HTML servido de media docena de fichas de marcas distintas. Donde aparezca, mismo cambio.

---

## Comprobar

Con `?nc=1`, en **una ficha EcoFlow, una Lokithor y una HyperShell**:

1. Buscar **`14 días`** en el HTML. Solo debe quedar la línea global de «Consulta nuestra
   política de devoluciones — 14 días para devolverlo sin usar y en su caja original», que
   **se queda**: es el enlace a la política, no un reclamo, y la ley obliga a informar.
2. Buscar **`Bizum`**. Cero.
3. Buscar **`servicio técnico`**. Cero en la ficha.
4. Que la rejilla de cuatro celdas **no se descoloque** en móvil: a partir de 479 px pasa a
   una columna, y los bordes los pintan reglas `nth-child`. Si se quita o se añade una
   celda, esos bordes se rompen. **No cambies el número de celdas, solo los textos.**
5. Purgar LiteSpeed y WP Super Cache.
