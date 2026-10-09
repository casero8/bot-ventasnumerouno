# Tanda 12 · devoluciones, Bizum y «servicio posventa»

**9 de octubre de 2026.** Cuatro cambios pedidos por el dueño. **Dos se aplican tal cual.
Dos llevan un aviso delante**, y los dos avisos importan.

---

## 1 · Bizum: fuera, sin matices

Ya está quitado de los cuatro archivos del repositorio donde se mencionaba
(`fichas/*-corta.html`): «Pago seguro con tarjeta, Bizum o financiación» pasa a «Pago
seguro con tarjeta o financiación a plazos».

**En la web hay que barrer tres sitios más**, porque Bizum no aparece solo en el texto:

1. **Las descripciones cortas de los 158 productos.** Busca `Bizum` en `post_excerpt` y en
   `post_content` por REST, no a ojo.
2. **Los iconos de método de pago** del pie, de la página de carrito y de la de finalizar
   compra. Suelen ser una imagen o una lista de iconos del tema, no texto.
3. **Las preguntas frecuentes y la página de formas de pago**, si existe.

Recordatorio de agosto: la pasarela de Bizum estaba **instalada en modo de pruebas, con
terminal y clave SHA-256 vacías**, y se desactivó antes de que pudiera cobrar nada. Así que
esto es solo limpiar las menciones: no hay nada que configurar ni que desinstalar con
cuidado.

---

## 2 · «Servicio técnico» → «Servicio posventa»: sí, pero no en todas partes

**El aviso primero, porque cuesta dinero.** Esto es lo que dice Search Console:

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `servicio tecnico ecoflow españa` | 545 | **1,2** | 27 |
| `servicio tecnico ecoflow` | 511 | **2,2** | 22 |
| `servicio tecnico oficial ecoflow` | 328 | **2,0** | 0 |
| `ecoflow servicio tecnico` | 19 | 4,6 | 3 |

**1.384 impresiones en posiciones 1-3 y 49 clics: el 3,7 % de todos los clics del sitio.**
Es una de las dos únicas cosas por las que esta web sale primera en Google.

Y la otra mitad del dato: **«posventa» y «post venta» tienen CERO consultas** en los
dieciséis meses del archivo. Nadie busca eso. Ni una vez.

### Lo que se hace, entonces

**Cambiar la etiqueta que lee el cliente. Dejar la que lee Google.** Cuesta cero
impresiones y hace exactamente lo que el dueño quiere.

**Ya cambiado en el repositorio (11 archivos):**

- La pastilla de confianza: «Servicio técnico propio» → **«Servicio posventa propio»**
- El enlace: «ver cómo funciona el servicio técnico» → **«…el servicio posventa»**
- El azulejo de navegación: «Servicio técnico» → **«Servicio posventa»**

**En la web, cambiar también:** el menú, los botones, los títulos de bloque y cualquier
rótulo visible. Todo a «Servicio posventa».

### Lo que NO se toca, y es una decisión

| Qué | Por qué |
|---|---|
| **La URL `/servicio-tecnico-ecogadget/`** | Cambiarla es un 301 y perder posición mientras Google reevalúa, a cambio de nada |
| **El título SEO y la meta de esa página** | Son los campos por los que sale en posición 1,2 |
| **El `h1` de esa página** | Igual |
| **La frase «servicio técnico oficial de EcoFlow»** en el cuerpo | Es la credencial real y es el término que se busca. Se queda en los cuatro sitios del repositorio donde está |

### Y una cosa que creo que es el motivo real, por si acierto

Lo de ayer con las reseñas y esto puede ser el mismo problema. **«Servicio técnico
oficial» se lee como «esto es EcoFlow»**, y entonces llega gente que compró en Amazon
esperando una garantía gratis. «Posventa» suena a tienda, no a fabricante, y rebaja esa
expectativa.

Si es por eso, el cambio de etiqueta ayuda, pero lo que de verdad lo arregla es **publicar
la página «Compré mi EcoFlow en otro sitio»** que está escrita en
`seo-blog/google/no-somos-ecoflow.md` y sigue sin publicar. Esa es la que pone las
expectativas antes de que alguien se enfade.

---

## 3 · Envío gratis a partir de 2.000 €: sin cambios

Ya estaba así desde agosto. Y ahora **ocupa el hueco que deja la frase de las
devoluciones** en las pastillas de confianza: «Envío a toda España · Gratis a partir de
2.000 €». Dice algo cierto y vendedor en el sitio donde antes había una promesa que el
dueño no quiere hacer.

---

## 4 · Las devoluciones: quitado de la publicidad, pero hay que hacer lo contrario en lo legal

**Esto es lo importante de la tanda y pido que se lea entero antes de tocar la web.**

### Lo que ya está hecho en el repositorio

Quitadas **todas** las menciones de los 14 días como argumento de venta, en 24 archivos:

- **13 categorías:** la pastilla «Envío a toda España · Y 14 días para devolverlo» pasa a
  «Envío a toda España · Gratis a partir de 2.000 €». Y en `hypershell` y
  `accesorios-hypershell`, la pastilla entera «14 días para devolverlo» se sustituye por la
  del envío.
- **4 descripciones cortas de ficha:** fuera de la línea de impuestos y fuera del punto
  «14 días para devolverlo sin dar explicaciones».
- **La guía del exoesqueleto:** fuera «y tienes catorce días para devolverlo».
- **4 categorías** donde el texto decía «Tienes 14 días para desistir»: reescrito sin la
  promesa y **diciendo que el porte de vuelta corre por cuenta del cliente**, con enlace a
  la política de devoluciones.

En la web hay que barrer lo mismo: las **158 descripciones cortas**, el pie, la portada y
cualquier banner o pastilla de confianza del tema.

### Y ahora el aviso, que va en la dirección contraria

**El derecho de desistimiento de 14 días no se puede quitar.** Es un derecho legal de
cualquier compra a distancia en España (Real Decreto Legislativo 1/2007). El cliente lo
tiene aunque no lo pongamos en ningún sitio, y **la página de política de devoluciones
tiene que seguir existiendo y explicándolo**. Lo que se quita es usarlo como reclamo
comercial, que es lo que ha pedido el dueño y es perfectamente legítimo.

**Y sobre no pagar los gastos de devolución, hay una trampa que juega en contra:**

> La ley permite que **el cliente** pague el porte de vuelta, **pero solo si se le ha
> informado de eso antes de comprar**. Si no se le informa, **lo paga el vendedor**.

O sea: **quitar la información de la web no hace que el cliente pague. Hace que pagues
tú.** Es justo lo contrario de lo que se busca.

### Lo que hay que hacer, entonces

| Dónde | Qué |
|---|---|
| Pastillas, banners, descripciones cortas, portada | **Fuera** cualquier mención a los 14 días. Hecho en el repositorio |
| **Página de política de devoluciones** | **Se queda, y se refuerza.** Tiene que decir, con esas palabras, que **los gastos de devolución corren por cuenta del cliente** |
| **Finalizar compra** | Esa misma información tiene que estar accesible antes de pagar, no solo en el pie |
| Condiciones generales de contratación | Igual, el mismo texto |

**El resultado es el que quiere el dueño:** no se anuncia, y los portes los paga el
cliente. Pero se consigue **informando mejor**, no informando menos. Si se quita la
información, el resultado es el contrario.

> **Punto de control.** Antes de tocar la política de devoluciones, dime qué dice hoy
> exactamente sobre quién paga el porte de vuelta. Si ya lo dice, no hay nada que hacer
> ahí y solo queda el barrido publicitario. Si no lo dice, hay que añadirlo, y eso es más
> urgente que todo lo demás de esta tanda.

---

## Resumen de lo que hay en el repositorio ya cambiado

| Cambio | Archivos |
|---|---:|
| Pastilla de devolución → envío gratis desde 2.000 € | 13 categorías |
| Devoluciones fuera de las descripciones cortas | 4 fichas |
| «Tienes 14 días para desistir» reescrito con el porte a cargo del cliente | 4 categorías |
| Devoluciones fuera de la guía del exoesqueleto | 1 |
| Bizum fuera | 4 fichas |
| Etiqueta «servicio posventa» | 11 archivos |

**Cero menciones de «14 días» y cero de «Bizum»** en todo `categorias/`, `fichas/` y
`nuevos/`. Comprobado.
