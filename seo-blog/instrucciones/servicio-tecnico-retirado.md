# URGENTE · Ya no somos servicio técnico de EcoFlow · quitar y redirigir

**9 de octubre de 2026.** EcoFlow les ha retirado la condición de servicio técnico.
**Decisión del dueño, tomada:** se quita todo de la web y la página se **redirige a los
productos de EcoFlow**.

Esto va **antes** que la tanda 12 y antes que cualquier otra cosa pendiente.

---

## Por qué es lo primero

Decir «servicio técnico oficial de EcoFlow» sin serlo es una **afirmación comercial falsa**,
y encima atribuyéndose una autorización de un tercero que ya no existe. Misma familia que
el «Envío Gratis Y Devoluciones» que se quitó de las 158 fichas en agosto, con el agravante.

Y hay un motivo práctico: mientras lo siga diciendo, seguirá llegando gente que compró en
Amazon pidiendo una reparación en garantía que no se le puede dar. Eso es lo que está
produciendo las reseñas de una estrella.

---

## Ya hecho en el repositorio · 23 archivos, cero menciones

Comprobado con un barrido: **cero** apariciones de «servicio técnico», «servicio posventa»,
«taller propio», «reparamos» o enlaces a `/servicio-tecnico-ecogadget/` en `categorias/`,
`fichas/`, `nuevos/`, `google/` y `correos/`.

| Qué decía | Qué dice ahora |
|---|---|
| Pastilla «Servicio técnico propio» (11 archivos) | **«Garantía gestionada aquí · Con tu número de pedido, en español»** |
| Azulejo «Servicio técnico · Reparamos aquí, sin intermediarios» | **«Tramitar garantía · Con tu número de pedido»** |
| «con tienda física y servicio técnico propio… la reparamos nosotros» | «con tienda física en Rivas-Vaciamadrid… abres la incidencia con nosotros y con tu número de pedido, en vez de pelearte con un formulario del fabricante» |
| «como servicio técnico oficial te reparamos cualquier equipo de la marca» | «la garantía la responde quien te lo vendió: es la ley. **Nosotros no somos un taller de reparación**» |
| Las 3 FAQ de categoría que decían «Somos servicio técnico oficial de EcoFlow» | Reescritas: garantía legal de tres años, gestionada aquí con el número de pedido |

**El argumento de venta no se ha perdido, se ha corregido.** Lo que el cliente compraba
nunca fue el taller: era **no tener que entenderse con el fabricante**. Factura en España,
garantía legal de tres años, la comercial del fabricante cuando es más larga, y una
incidencia que se abre con un número de pedido y en español. Eso es verdad y sigue siendo
la diferencia frente a un marketplace.

---

## La redirección

**301, nunca 302** (norma 9). Se añade al fragmento #6, `EG · SEO · Redirecciones de URLs
muertas`, que es donde viven las demás.

```
/servicio-tecnico-ecogadget/   →   301   →   [destino, ver abajo]
```

### El destino

El dueño ha dicho «a los productos de EcoFlow». **Comprueba primero qué existe**, en este
orden de preferencia:

1. Si hay una **categoría o página de marca de EcoFlow** que agrupe toda la gama, ésa.
2. Si no, **`/product-category/serie-delta/`**: es la gama principal, la que tiene stock y
   la que más tráfico recibe.

**Dime cuál has elegido antes de activarla.** Y comprueba que el destino responde **200
directo**, no otro 301: una redirección que apunta a una redirección es un salto doble y
los buscadores lo penalizan.

### Lo que hay que hacer con la redirección puesta

1. **Borrar la página, no dejarla publicada.** Si se queda publicada *y* redirigida, según
   cómo esté montado el redirector puede seguir siendo accesible por otra ruta. A
   **borrador**, nunca a la papelera (norma 11).
2. **Quitarla del sitemap** y comprobar que no queda en `wp-sitemap` ni en el de Yoast.
3. **Los enlaces internos ya están quitados** en el repositorio. En la web hay que barrer
   los que haya en el menú, el pie, la portada y los fragmentos: **ningún enlace interno
   debe pasar por el 301** (norma 9). Que funcione no basta: diluye y ralentiza el rastreo.
4. **El formulario de garantía (3446)** y su página de destino: revisar que no mencionen
   reparación, y que digan que es para compras hechas aquí, con número de pedido.

### Qué va a pasar, para que no sorprenda

Las **1.384 impresiones en posición 1-3** de «servicio técnico ecoflow» **se van a perder**,
y conviene saberlo por adelantado en vez de descubrirlo en Search Console dentro de un mes.
Un 301 traslada autoridad, pero cuando el destino no responde a la búsqueda, Google acaba
dejando de enseñarlo para esa consulta. Era el 3,7 % de los clics del sitio.

No es un error de la decisión: **es el coste de haber perdido el servicio técnico**, y no
hay forma de posicionarse para un servicio que no se presta. Lo que sí evita el 301 es
perder además la autoridad acumulada de la URL, y eso es justo lo que se gana.

**Mitigación barata, y es una línea:** quien llegue desde esa búsqueda va a aterrizar en una
rejilla de productos sin respuesta a su pregunta. Publicar
**«Compré mi EcoFlow en otro sitio»** —ya escrita en `google/no-somos-ecoflow.md`— y
enlazarla desde arriba de la categoría de destino recoge a esa gente en vez de dejarla
rebotar. Antes era recomendable; ahora es lo que evita la siguiente reseña.

---

## El barrido en la web · lo que falta

1. **Las 158 descripciones de producto.** Por REST, en `post_content` y `post_excerpt`,
   buscando: `servicio técnico`, `servicio tecnico`, `taller`, `reparamos`, `reparación`,
   `SAT`. **Mándame la lista antes de tocar nada**, para ver cuántas son y en qué contexto.
2. **Las 20 categorías con texto.** Las que están en el repositorio ya vienen corregidas;
   las demás, una a una.
3. **El pie, la portada y las pastillas de confianza del tema.** Ahí está repetido en todas
   las páginas a la vez, así que es el cambio que más superficie limpia de golpe.
4. **El fragmento #38** y cualquier otro que lo mencione.
5. **Los correos automáticos** de pedido y de garantía, y la firma.
6. **La ficha de Google:** la descripción nueva está en `google/ficha-google.md`, ya
   reescrita. Y la categoría **«Servicio de reparación de electrónica» que la tanda 11
   mandaba añadir, ya NO se puede añadir.** Si se añadió, quitarla.

---

## Una pregunta que hay que hacerle al dueño antes de aplicar nada

**¿Sigue en pie la condición de distribuidor oficial de EcoFlow?**

Ha dicho que les han quitado el servicio técnico, y **todo lo reescrito da por hecho que
«distribuidor oficial de EcoFlow en España» sigue siendo cierto**. Aparece en las veinte
páginas corregidas y es ahora el argumento principal del sitio. Si eso también ha caído,
hay que rehacerlo otra vez y la propuesta de valor cambia de arriba abajo.

De **Hypershell y Lokithor no hay nada que corregir**: de esas dos siempre se dijo
«garantía del fabricante», nunca servicio técnico propio.

---

## Para la auditoría SEO

El racimo «servicio técnico EcoFlow» **sale del plan de 90 días**. Era el punto 1 de la
semana 1 —«posición 2 con cero clics es lo más urgente del sitio»— y ya no se puede
trabajar: no era un problema de título, es que no tenemos el producto.

Eso **sube las placas solares al primer puesto de la auditoría**: 10.336 impresiones en
posición media 18,9 con 47 clics. Pasarlas a la primera página son 38-71 clics al mes, y
ahora es lo más importante que hay en la lista.
