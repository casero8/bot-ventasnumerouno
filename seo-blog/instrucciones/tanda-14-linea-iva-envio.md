# ANULADA · ver los dos partes que la sustituyen

**Este parte partía de una premisa falsa y no hay que usarlo.** Lo dejo aquí con el aviso
en vez de borrarlo, porque está enlazado desde el historial.

Decía que la línea «IVA incluido · Envío gratis · 14 días para devolverlo» estaba en un
único sitio imprimiéndose en los 158 productos, y mandaba buscarla en los fragmentos, el
tema y las opciones. **Eso es perder una tarde.**

El 9 de octubre cayó el bloqueo del hosting, se pudo leer el sitio en vivo y la realidad es
otra: **está en dos capas a la vez**, y además el «Envío gratis» **no era una afirmación
falsa** —es la barra de progreso del carrito—.

**Usa estos dos en su lugar:**

| Parte | Qué cubre |
|---|---|
| `mapa-servicio-tecnico-en-vivo.md` | **La capa global.** Los seis sitios donde vive la afirmación falsa de servicio técnico: el JSON-LD, el menú, el pie, una regla CSS y dos bloques de JavaScript, uno de los cuales reescribe el menú en cada carga. **Va primero**, porque afecta a las 326 URLs del sitio |
| `hallazgo-stock-generacion-anterior.md` | **La capa de producto**, con la lista exacta: 30 de 149 productos y qué frase lleva cada uno. Y los dos hallazgos del barrido |

El barrido completo está en `mantenimiento/barrido-productos-2026-10-09.txt`.
