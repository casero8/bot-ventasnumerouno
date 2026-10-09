# URGENTE · Ya no somos servicio técnico de EcoFlow

**9 de octubre de 2026.** EcoFlow les ha retirado la condición de servicio técnico. A
partir de ahora **solo tramitan la garantía de lo que venden, como cualquier vendedor**.

Esto deja de ser un cambio de redacción y pasa a ser **publicidad engañosa publicada en
toda la web**. Va primero, antes que la tanda 12 y antes que cualquier otra cosa.

---

## Por qué es urgente y no puede esperar

Decir «servicio técnico oficial de EcoFlow» sin serlo es una **afirmación comercial falsa**.
Es la misma familia que el «Envío Gratis Y Devoluciones» que aparecía en las 158 fichas en
agosto y que se quitó por lo mismo: se puede denunciar, se puede sancionar, y aquí hay un
agravante — **lo que se atribuye es una autorización de un tercero que ya no existe**.

Y hay un segundo motivo, más práctico: **mientras lo siga diciendo, seguirá llegando gente
que compró en Amazon pidiendo una reparación en garantía que no se le puede dar.** Eso es
exactamente lo que está produciendo las reseñas de una estrella.

---

## Lo que ya está hecho en el repositorio

**Veinte archivos. Cero afirmaciones de servicio técnico o de reparación** en
`categorias/`, `fichas/`, `nuevos/`, `google/` y `correos/`. Comprobado con un barrido.

| Qué decía | Qué dice ahora |
|---|---|
| Pastilla «Servicio técnico propio · La garantía la tramitamos nosotros» (11 archivos) | **«Garantía gestionada aquí · Con tu número de pedido, en español»** |
| Azulejo «Servicio técnico · Reparamos aquí, sin intermediarios» | **«Tramitar garantía · Con tu número de pedido»** |
| «con tienda física y servicio técnico propio… si hay avería la reparamos nosotros» | «con tienda física en Rivas-Vaciamadrid… si algo falla, abres la incidencia con nosotros y con tu número de pedido, en español, en vez de pelearte con un formulario del fabricante» |
| «como servicio técnico oficial de EcoFlow te reparamos cualquier equipo de la marca, con presupuesto previo» | «la garantía la responde quien te lo vendió: es la ley y es a quien tienes que reclamar. **Nosotros no somos un taller de reparación**» |
| Descripción de Google: «Contamos con servicio técnico propio de EcoFlow» | «Si compras aquí, la garantía la gestionamos nosotros… **No somos un taller de reparación**» |

**El argumento nuevo, que sigue siendo bueno:** lo que el cliente compra al comprar aquí no
era nunca el taller. Es **no tener que entenderse con el fabricante**. Factura en España,
garantía legal de tres años, la comercial del fabricante cuando es más larga, y una
incidencia que se abre con un número de pedido y en español. Eso es verdad, se puede
sostener, y es lo que de verdad diferencia de un marketplace.

---

## Lo que hay que hacer en la web, por orden

### Bloque A · Hoy, porque es lo que está publicado y es falso

1. **La página `/servicio-tecnico-ecogadget/`.** Es el problema más grande y necesita
   decisión del dueño, así que está abajo en su propio apartado.
2. **Las 158 descripciones de producto.** Busca por REST, en `post_content` y en
   `post_excerpt`, estas cadenas: `servicio técnico`, `servicio tecnico`, `taller propio`,
   `reparamos`, `SAT`, `reparación`. Mándame la lista antes de tocar nada: quiero ver
   cuántas son y en qué contexto.
3. **Las 20 categorías con texto.** Lo mismo. Las que están en el repositorio ya vienen
   corregidas; las demás hay que mirarlas una a una.
4. **El pie de página, la portada y cualquier pastilla de confianza del tema.** Ahí suele
   estar repetido en todas las páginas a la vez.
5. **El fragmento #38** y cualquier otro fragmento que lo mencione.
6. **La ficha de Google:** la descripción nueva está en `google/ficha-google.md`, ya
   reescrita. Y hay que revisar las **categorías de la ficha**: «Servicio de reparación de
   electrónica», que en la tanda 11 se iba a añadir, **ya no se puede añadir**.
7. **Los correos automáticos** de pedido y de garantía, y la firma del correo, si lo dicen.

### Bloque B · Esta semana

8. **El formulario de garantía (3446)** y los textos que lo acompañan: tienen que decir que
   es para compras hechas aquí, con número de pedido.
9. **Publicar «Compré mi EcoFlow en otro sitio»**, ya reescrita en
   `google/no-somos-ecoflow.md`. Antes era recomendable; ahora es **necesaria**, porque es
   la página que recoge a toda la gente que va a seguir llegando con la expectativa vieja.

---

## La página `/servicio-tecnico-ecogadget/` · tres opciones y mi recomendación

Esto es lo que hay que decidir, y conviene saber lo que se está decidiendo:

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `servicio tecnico ecoflow españa` | 545 | **1,2** | 27 |
| `servicio tecnico ecoflow` | 511 | **2,2** | 22 |
| `servicio tecnico oficial ecoflow` | 328 | **2,0** | 0 |

**1.384 impresiones en posición 1-3 y 49 clics: el 3,7 % de todos los clics del sitio.** Es
una de las dos únicas cosas por las que esta web sale primera en Google. Y ahora **ese
tráfico llega buscando algo que ya no se ofrece**.

**Opción 1 · Borrar la página.** Honesto y rápido. Se pierden las 1.384 impresiones y los
49 clics, y se queda un 404 o un 301 a la portada. Es tirar el activo.

**Opción 2 · Reescribirla como «Garantías y posventa».** Misma URL, mismo contenido
reconvertido: cómo se tramita una garantía comprada aquí, qué cubre la ley, qué plazos, y
qué hacer si compraste en otro sitio. Se pierde posición en «servicio técnico» —
inevitable, porque ya no se puede competir ahí— pero **se retiene la URL, los enlaces
internos y parte de la autoridad**, y se gana una página que resuelve dudas reales de
compradores reales.

**Opción 3 · Mantener la palabra «servicio técnico» en la página explicando que no lo
son.** Ni se te ocurra. Google la seguiría enseñando para esa búsqueda y el usuario
llegaría a una página que le dice que no, lo que produce exactamente la reseña de una
estrella que estamos intentando evitar.

**Recomiendo la 2.** Es la única que no tira el activo y la única que convierte el problema
en algo útil: la gente que busca «garantía EcoFlow» o «cómo reclamar mi EcoFlow» es la
misma que luego compra el recambio.

> **Punto de control, y es el único de esta tanda.** No toques esa página hasta que el
> dueño elija. Lo demás del bloque A se puede ir haciendo ya.

---

## Dos cosas que hay que confirmar antes de seguir

1. **¿Sigue en pie la condición de distribuidor oficial?** David ha dicho que les han
   quitado el servicio técnico, no la distribución, y todo lo reescrito **da por hecho que
   «distribuidor oficial de EcoFlow en España» sigue siendo cierto**. Si eso también ha
   caído, hay que rehacer otra vez las mismas veinte páginas, y entonces la propuesta de
   valor del sitio cambia de arriba abajo. **Hay que preguntárselo antes de aplicar nada.**

2. **¿Y Hypershell y Lokithor?** De esas dos nunca se dijo «servicio técnico propio» —
   siempre «garantía del fabricante»—, así que ahí no hay nada que corregir. Es el único
   sitio donde la norma 10 nos salvó de tener que rehacer el trabajo.

---

## Para la auditoría SEO

El racimo «servicio técnico EcoFlow» **sale del plan de 90 días**. Era el punto 1 de la
semana 1 —«posición 2 con cero clics es lo más urgente del sitio»— y ya no se puede
trabajar: no es un problema de título, es que no tenemos el producto.

Eso deja el plan de la semana 1 en tres puntos en vez de cuatro, y **sube el racimo de
placas solares a lo más importante de la auditoría**: 10.336 impresiones en posición media
18,9. Era el segundo; ahora es el primero.
