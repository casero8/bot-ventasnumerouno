# Instrucciones para Claude en Chrome — tanda 7

**Fecha:** 18 de agosto de 2026 · **Sitio:** ecogadgetoficial.com

Reglas que no se rompen en ninguna tarea:

- **No instales ningún plugin.** Todo se hace desde el escritorio de WordPress o con los
  snippets que ya existen en Code Snippets.
- **No toques slugs, URLs ni el padre de ninguna categoría.** Las URLs de categoría son
  jerárquicas: cambiar el padre cambia la URL y tira el posicionamiento.
- **No toques el logo ni la cabecera del tema.**
- Ni una reseña ni una pregunta de cliente inventada (RDL 24/2021). Nada de escasez falsa.
  Ninguna cifra que no esté en la ficha del producto.
- Si algo se borra o se sustituye, **comprueba después que el resto sigue funcionando**.
- **Para en el punto de control** que marca cada tarea y espera confirmación antes de
  seguir. Si algo no cuadra con lo que dice este documento, para y cuéntalo: no improvises.

---

## Tarea 1 — Precios de HyperShell iguales a la tienda oficial (prioridad)

Petición literal del dueño: *"de hypershell me tienes que poner los mismos precios que
esta web https://eu.hypershell.tech/es-es/"*.

Ya he leído el catálogo oficial. Estos son los **nueve productos que la tienda europea de
HyperShell vende hoy** con sus precios reales, sacados de su propio catálogo el 18/08/2026:

| Producto oficial | Variante | Precio | Antes |
|---|---|---|---|
| Nueva Serie Hypershell X | X Ultra S | **1.999 €** | — |
| Nueva Serie Hypershell X | X Max S | **1.499 €** | — |
| Nueva Serie Hypershell X | X Pro S | **999 €** | — |
| Serie X (generación anterior) | X Ultra | **1.799 €** | 1.999 € |
| Serie X (generación anterior) | X Carbon | **1.299 €** | 1.799 € |
| Serie X (generación anterior) | X Pro | **899 €** | 1.199 € |
| Serie X (generación anterior) | X Go | 699 € | 999 € · **agotado** |
| Kit AeroFlex | Nylon — resistente y ligero | **80 €** | 159 € |
| Kit AeroFlex | AquaTex — impermeable y transpirable | **110 €** | 219 € |
| Batería Inteligente | Estándar | **119 €** | — |
| Batería Inteligente | Anti-Frío Pro | **139 €** | — |
| Banda de Hombro AeroFlex | Negro | **69 €** | — |
| Cinturón Pélvico AeroFlex | Negro | **2,00 €** | 39,00 € |
| Hub de Carga de 4 Puertos | — | **69 €** | — |
| Cargador Múltiple 4 en 1 GaN 65W | Negro | **59 €** | — |

### Lo que ya está bien y no hay que tocar

Seis de los nueve productos de la tienda **ya coinciden al céntimo** con el oficial:
X Ultra S 1.999 €, X Max S 1.499 €, X Pro S 999 €, Banda de Hombro 69 €, Hub de Carga
69 € y Cargador 4 en 1 GaN 59 €. Déjalos como están.

Que seis de nueve coincidan exactos confirma además que ambas tiendas manejan la misma
base: euros con IVA. No hay que convertir nada.

### Corrección importante sobre el Cinturón Pélvico

En la tanda anterior marqué el **Cinturón Pélvico AeroFlex a 2,00 € rebajado desde 39,00 €**
como probable error de precio, de la misma familia que los 13 productos que se quedaron sin
precio el 15 de agosto. **Me equivoqué.** La tienda oficial lo vende exactamente igual:
2,00 € con precio anterior 39,00 €. Es una promoción deliberada del fabricante. **No lo
toques y no lo subas.**

### Lo que sí hay que cambiar

**1.1 · Kit AeroFlex — es el único desajuste real, y es grande**

La tienda lo tiene a **159 €** en un solo producto. El oficial lo vende en **dos versiones**
y **159 € es el precio tachado de la barata**:

- Nylon (resistente y ligero): **80 €**, antes 159 €.
- AquaTex (impermeable y transpirable): **110 €**, antes 219 €.

Es decir, se está vendiendo la versión Nylon **al doble** de lo que cuesta en la tienda
oficial. Qué hacer, por orden:

1. Abre el producto Kit AeroFlex en la tienda y mira si tiene variantes. Si es un producto
   simple sin variantes, comprueba en su descripción y en su ficha **qué material es**.
2. Si es la versión Nylon (o no lo especifica): precio normal **159 €**, precio rebajado
   **80 €**.
3. Si es la versión AquaTex: precio normal **219 €**, precio rebajado **110 €**.
4. Si no se puede determinar el material desde la ficha, **para y pregunta al dueño**. No
   adivines: son 30 € de diferencia por unidad.

Ojo al efecto secundario, que es el que el dueño quería: al ponerle precio rebajado, el
filtro `eg_ofertas_solo_con_stock` lo mete **solo** en `/ofertas/` si además tiene stock.
Comprueba después que aparece ahí y que sigue sin salir ningún "AGOTADO".

**1.2 · Batería Inteligente — falta la versión Anti-Frío Pro**

La tienda tiene una sola batería a **119 €**, que coincide con la **Estándar** oficial. El
oficial vende también una **Anti-Frío Pro a 139 €** que aquí no existe.

No la crees por tu cuenta. **Anota el dato y pregunta al dueño** si la distribuye: si la
tiene en catálogo, es un producto más que vender en la categoría de accesorios de
HyperShell, que hoy tiene 6 productos.

**1.3 · Generación anterior de la Serie X — comprobar si está en catálogo**

El oficial sigue vendiendo la serie X anterior (Ultra, Carbon, Pro, Go) con rebajas fuertes.
Recorre la categoría HyperShell de la tienda y **comprueba si hay algún producto que sea de
esta generación anterior** — cuidado, se llaman casi igual que los nuevos y la única
diferencia en el nombre es la **S** final.

Si existe alguno, ponle el precio oficial de la tabla (precio normal = la columna "Antes",
precio rebajado = la columna "Precio"). Si el producto es el **X Go**, que está agotado en
el oficial, no lo pongas en oferta: revisa su stock aquí antes.

Si no hay ninguno, dilo y no hagas nada.

### Punto de control 1

Antes de tocar un solo precio, **escribe la lista de los 9 productos HyperShell de la
tienda con su precio actual al lado del oficial** y espera confirmación. Solo después
aplica los cambios. Cuando termines, vuelve a listar los 9 con el precio final.

---

## Tarea 2 — Los ocho remiendos de enlaces (barato, seguro, hazlo entero)

Ocho categorías **ya tienen texto** y sin embargo **no enlazan ni a su categoría madre ni a
sus hermanas**. Quien entra desde Google y no encuentra lo que buscaba se va, porque no
tiene a dónde ir dentro del sitio.

`power-kits` · `baterias-adicionales` · `accesorios-power-kits` · `rapid-pro` ·
`ecoflow-wave` · `arrancador-de-coche` · `stream-series` · `lokithor`

El caso más sangrante es `arrancador-de-coche`: 10 KB de texto y 12 enlaces, **los doce a
fichas de producto**, ninguno de vuelta al árbol.

Para cada una, **añade al final de la descripción** este bloque, rellenando los enlaces con
la madre y las hermanas reales de esa categoría:

```html
<div class="eg-salidas">
  <p><strong>¿No es esto lo que buscabas?</strong></p>
  <ul>
    <li><a href="/product-category/RUTA-DE-LA-MADRE/">Volver a NOMBRE DE LA MADRE</a></li>
    <li><a href="/product-category/RUTA-HERMANA-1/">NOMBRE HERMANA 1</a></li>
    <li><a href="/product-category/RUTA-HERMANA-2/">NOMBRE HERMANA 2</a></li>
  </ul>
</div>
```

Reglas de este bloque:

- **Añadir, no reescribir.** Copia la descripción actual, pégale el bloque al final y
  guarda. No reordenes ni recortes nada de lo que ya hay.
- Máximo **cuatro enlaces**: la madre y hasta tres hermanas, las que más productos tengan.
- Los enlaces **tienen que existir**. Ábrelos uno a uno antes de guardar. En una tanda
  anterior se inventaron dos URLs legales que daban 404: no vuelva a pasar.
- Si una categoría no tiene madre (es de primer nivel), enlaza a la tienda y a dos
  categorías del mismo tipo.

### Método obligatorio para escribir descripciones de categoría

Esto ya nos ha mordido dos veces. **El formulario de WordPress recorta HTML en silencio**
(12.138 bytes entraron y salieron 10.796). Por eso:

1. Escribe la descripción **por la API REST**, no por el formulario.
2. **Compara el número de bytes** de lo que mandaste con lo que quedó guardado. Si no son
   idénticos, no está bien, aunque a la vista lo parezca.
3. Si además hay que tocar el SEO de Yoast de esa categoría, el orden es: REST → formulario
   para Yoast → **volver a aplicar por REST** → verificar que vuelve a ser idéntico. El SEO
   de taxonomías vive en la opción `wpseo_taxonomy_meta`, no en meta del término, así que
   no se puede tocar por REST y el formulario te machaca el texto al guardar.

### Punto de control 2

Después de la **primera** categoría, enseña el recuento de bytes antes y después y la lista
de enlaces comprobados. Con el visto bueno, sigue con las otras siete de una tacada.

---

## Tarea 3 — Recoger datos de las tres categorías que más se buscan

No escribas todavía. **Solo recoger**, para poder escribirlas bien en la siguiente tanda.

El reparto de categorías estaba hecho contando productos, y al cruzarlo con Search Console
(16 meses, 47 categorías, 167 clics y 13.600 impresiones) resulta que el criterio era el
equivocado. Cambian tres:

| Categoría | Datos | Qué cambia |
|---|---|---|
| `bateria-adicional-delta-pro` | 2.271 impresiones | **No estaba en el plan.** Es la que más impresiones tiene de todo el árbol |
| `accesorios-delta-3` | 42 clics · 1.715 impresiones · CTR 2,4 % | Estaba clasificada como hoja con 917 bytes. Es la **segunda categoría con más clics del sitio** |
| `bateria-adicional-delta-3` | 1.444 impresiones | **No estaba en el plan** |

De cada una de las tres, dame:

1. Cuántos productos tiene y **cuántos se pueden comprar hoy** (con stock y con precio).
2. El nombre, el precio y el enlace de cada producto.
3. **Las cifras literales de sus fichas**: capacidad en Wh, potencia en W, ciclos, garantía,
   compatibilidades. Copiadas de la ficha, no de memoria: si no está en la ficha, no existe.
4. Si algún producto tiene la ficha rota — sin tabla de especificaciones, con inglés sin
   traducir, con copia y pega mal hecho o con cifras que se contradicen.
5. Su categoría madre y sus hermanas, con las URLs exactas.

---

## Tarea 4 — Decisiones que necesito del dueño

No hagas nada con esto. **Preséntalo como una lista de preguntas** y recoge lo que falte.

1. **13 productos se quedaron sin precio el 15 de agosto entre las 16:23 y las 16:24**, en
   una sola operación. Uno de ellos es la estación DELTA 2, que tiene 418 impresiones y hoy
   no se puede comprar. ¿Se restauran? Si sí, hacen falta los precios.
2. **DELTA Pro Ultra**, 4.999 €, agotado, y su ficha dice "Disponible ahora con envío
   rápido". Hay que quitar esa frase mientras no haya stock.
3. **Dos metadescripciones de Yoast siguen prometiendo "envío inmediato"** estando de
   vacaciones hasta el 28 de agosto. Localízalas y dilas.
4. **STREAM Series Standard**, 1.349 €, se vende como "sistema solar plug & play" con
   "paneles monocristalinos" y "garantía 10 años (paneles)" en la tabla, pero **en la caja
   solo declara 1 STREAM Ultra + 1 STREAM AC Pro + 1 cable**. En ningún sitio pone que los
   paneles no van incluidos. Esto bloquea escribir `stream-series`, que tiene 1.330
   impresiones. ¿Los paneles van o no van?
5. El enlace `/1381-2/` del pie está dentro de una plantilla de Elementor.
6. **Condiciones generales** no está enlazada desde el pie.
7. El `functions.php` del tema hijo **no tiene permisos de escritura**. Por eso
   `eco-vacaciones-clean` hubo que neutralizarlo desde Code Snippets en vez de borrarlo.
8. Queda abierto el **aviso a Dinahosting**: el anti-bot mete una pantalla de espera de 3
   segundos con tres puntitos a visitantes reales antes de servir la página.

---

## Al terminar del todo

Recuérdale al dueño que **revoque la contraseña de aplicación de WordPress**:
`Usuarios → tu perfil → Contraseñas de aplicación → Revocar`.
