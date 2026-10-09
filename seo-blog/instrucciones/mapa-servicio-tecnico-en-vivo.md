# El mapa real de «servicio técnico» en la web, leído en vivo

**9 de octubre de 2026.** El bloqueo del hosting ha caído y he podido leer el sitio
directamente. **Esto sustituye a lo que supuse en la tanda 14.**

Y empiezo por mis dos errores, porque los dos han costado tiempo:

1. **Dije que la línea estaba en un solo sitio imprimiéndose en 158 productos.** Falso.
2. **Luego dije lo contrario: que estaba escrita a mano producto a producto.** También
   falso.

**Está en las dos capas a la vez**, y la global es la que importa.

---

## Capa 1 · Lo que sale en TODAS las páginas del sitio

Comprobado en dos productos distintos, uno optimizado y otro no. Está en los dos, así que
es global. **Y aquí está lo urgente**, porque son afirmaciones falsas que Google lee:

| # | Qué dice | Dónde vive | Gravedad |
|---|---|---|---|
| **1** | `Tienda física y taller propio para EcoFlow.` | **JSON-LD de organización**, al principio del `<head>` | **La peor. Es lo que lee Google como descripción de la empresa** |
| **2** | `Servicio técnico EcoFlow` | Enlace del **menú principal** a `/servicio-tecnico-ecogadget/`, y repetido en el menú móvil | Alta: está en todas las páginas |
| **3** | `Servicio técnico oficial` | **Pie**, inyectado por JS en un `div.eg-pie-extra` | Alta |
| **4** | `Taller propio en Rivas-Vaciamadrid` | **CSS, como `content:`**, sobre `body.page-id-9090` | Alta, y es la trampa de la norma 1 |
| **5** | Un JS que **reescribe** «Servicio Técnico Oficial de EcoFlow» por «servicio técnico EcoGadget para equipos EcoFlow» | JS del pie, bloque `PARES` | Alguien ya intentó suavizarlo. **Sigue diciendo «servicio técnico»**, así que no vale |
| **6** | Un JS que **fuerza** el texto del enlace del menú a `Servicio técnico EcoFlow` | JS del pie | **Hay que quitarlo o volverá a escribirlo solo** |

**El punto 6 es el que puede hacer perder una tarde:** aunque se cambie el menú a mano, hay
un JavaScript que comprueba el texto del enlace y lo reescribe en cada carga. Si no se quita
ese bloque primero, el cambio «no se guarda» sin que se entienda por qué.

**La página del servicio técnico es la `page-id-9090`.** Ese dato hace falta para el 301.

### Y una buena noticia, que corrige otro error mío

**El «Envío GRATIS» que vio el dueño NO es una afirmación falsa.** Es la barra de progreso
del carrito: «te faltan X € **más para disfrutar de Envío GRATIS**». Está bien dicho y en
contexto. Lo único que conviene es **comprobar que el umbral configurado son 2.000 €** y no
otra cifra.

---

## Capa 2 · Lo que sale solo en los productos optimizados

En la descripción corta y larga de los productos que se reescribieron en agosto. Literal,
de `/producto/ecoflow-river-3-max-plus/`:

| Dónde | Qué dice |
|---|---|
| `eg-taxline` (descripción corta) | `IVA incluido · Envío a toda España · 14 días para devolverlo` |
| `eg-trust` (descripción corta) | `La garantía te la gestionamos nosotros — servicio técnico propio con tienda física, solo para compras en nuestra web` |
| `eg-trust` (descripción corta) | `Envío a toda España y 14 días para devolverlo sin dar explicaciones` |
| `eg-trust` (descripción corta) | `Pago seguro con tarjeta, Bizum o financiación a plazos` |
| Descripción larga | `con tienda física y servicio técnico propio. Si hay avería la reparamos nosotros` |

**Estos textos ya están corregidos en el repositorio**, en `fichas/*-corta.html` y
`fichas/*-larga.html`. Hay un segundo barrido en marcha para dar la lista exacta de qué
productos llevan cada cadena; cuando acabe, se añade aquí.

### Y una tercera, que no es falsa pero el dueño quiere fuera

| Dónde | Qué dice |
|---|---|
| Bloque global, cerca del producto | `Consulta nuestra política de devoluciones — 14 días para devolverlo sin usar y en su caja original.` |

**Ésta sale en los 149 productos** y es la que hace que el recuento diera 149 de 149. Es
cierta y es informativa —y la ley obliga a informar—, pero **el dueño ha pedido que los 14
días dejen de usarse como argumento**. Mi criterio: **esta se queda**, porque es un enlace a
la política, no un reclamo, y quitarla nos deja sin la información previa que la ley exige
para poder cobrar el porte de vuelta al cliente. Las que se van son las de `eg-taxline` y
`eg-trust`, que sí son publicidad. **Decisión del dueño si quiere también ésta fuera.**

---

## Orden de ejecución

**Primero la capa 1, porque es la que afecta a las 326 URLs del sitio y la que lee Google.**

1. **Quitar el JS del punto 6**, el que reescribe el enlace del menú. Si no, lo demás no se
   sostiene.
2. **Quitar el JS del punto 5**, el de las sustituciones `PARES`. Ya no hace falta suavizar
   nada: la frase se va entera.
3. **El JSON-LD del punto 1.** Cambiar «Tienda física y taller propio para EcoFlow» por
   algo cierto: *«Tienda física en Rivas-Vaciamadrid. Distribuidor oficial de EcoFlow,
   HyperShell y Lokithor.»* Hay que encontrar de dónde sale: Yoast, un fragmento o el tema.
4. **El enlace del menú** (punto 2): cambiar el rótulo a «Garantías» o quitarlo, según lo
   que se decida con la página 9090.
5. **El pie** (punto 3): quitar «Servicio técnico oficial» del `eg-pie-extra`.
6. **El CSS del punto 4**: quitar la regla `content:'Taller propio en Rivas-Vaciamadrid'`.
7. **El 301 de la página 9090** a los productos de EcoFlow, como decidió el dueño.

**Después la capa 2**, con los textos del repositorio, que ya están escritos.

> **Punto de control.** Pégame el código de los puntos 1, 2 y 3 antes de tocarlos, y dime en
> qué fragmento o en qué campo está cada uno. El 3 y el 4 son los que tienen más formas de
> estar puestos.

---

## Comprobación final

Con `?nc=1`, y **en una página de producto, una de categoría y la portada**:

1. **Buscar `servicio técnico` en el HTML servido.** Cero resultados, incluidos los bloques
   de JS y de CSS.
2. **Buscar `taller propio`.** Cero, **incluido el JSON-LD** — ése es el que se olvida.
3. **Buscar `taller`** a secas, por si queda alguna variante.
4. **Recargar tres veces** una página de producto: si «Servicio técnico EcoFlow» reaparece
   en el menú, el JS del punto 6 sigue vivo.
5. Que el **umbral de la barra de envío gratis** sean 2.000 €.
6. Purgar LiteSpeed y WP Super Cache.
