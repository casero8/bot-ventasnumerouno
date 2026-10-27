# La URL rota, y ocho slugs que han cambiado desde el 9 de octubre

**27 de octubre de 2026.** Comprobado en vivo comparando el sitemap de hoy con el que
guardé el 9 de octubre.

---

## La URL que preguntaba el dueño: está rota, y es la única

`/producto/cargador-mediante-alternador-ecoflow-plus-1000/` → **HTTP 404**, sin redirección.

El producto **no ha desaparecido**: le han cambiado el slug. Ahora vive en

`/producto/cargador-de-alternador-ecoflow-plus-1000-1000-w-12-24-v/` → **HTTP 200**

**El arreglo es una línea:** un **301** del slug viejo al nuevo. Va en el plugin Redirection
—está instalado, aparece como `redirection/v1` en la API— o en el fragmento #6 con las
demás. **301, nunca 302.**

Importa porque esa URL **estaba en el sitemap el 9 de octubre**, así que Google la conoce y
la tiene indexada. Un 404 en una URL indexada tira lo que tuviera acumulado y deja al
visitante en una página de error en lugar de en un producto de 329 €.

---

## Han cambiado ocho slugs, y siete están bien

Comparando los dos sitemaps: **149 productos el 9 de octubre, 144 hoy.** Ocho URLs
desaparecieron y tres aparecieron. Esto es lo que pasó con cada una:

| Slug viejo | Estado | Destino |
|---|---|---|
| `adaptador-ecoflow-ev-x-stream-delta-pro` | **301** ✔ | `adaptador-ev-x-stream-ecoflow-para-delta-pro` |
| `banco-de-energ-a-magn-tico-ecoflow-rapid-5-000mah` | **301** ✔ | `banco-de-energia-magnetico-ecoflow-rapid` |
| `bater-a-inteligente-hypershell` | **301** ✔ | `bateria-inteligente-hypershell` |
| `cable-de-carga-para-coche` | **301** ✔ | `cable-de-carga-para-coche-ecoflow` |
| `cable-infinity` | **301** ✔ | `cable-infinity-ecoflow` |
| `hypershell-x-carbon` | **301** ✔ | `hypershell-x-carbon-exoesqueleto-de-1-8-kg-en-titanio-y-carbono` |
| `lampara-para-camping-ecoflow` | **301** ✔ | `lampara-de-camping-ecoflow-led-recargable` |
| **`cargador-mediante-alternador-ecoflow-plus-1000`** | **404** ✘ | **ninguno. Hay que crearlo** |

Los siete que funcionan es casi seguro que los está resolviendo WordPress solo, que guarda
el slug anterior de cada entrada y redirige. Eso **funciona para un cambio, pero no si el
slug se cambia dos veces**, y es la explicación más probable de por qué el octavo se quedó
fuera.

**Conclusión: hay que crear el 301 a mano, y no fiarse de que WordPress lo haga.**

---

## Y una observación que conviene decir

**Se han cambiado ocho slugs de producto.** Las normas de la casa tienen los slugs y las
URLs en la lista de «nunca se toca», y aquí se ve por qué: de ocho cambios, **uno se rompió
en silencio** y nadie se enteró hasta que el dueño pasó una captura por otro motivo.

No digo que los cambios fueran malos —los nuevos son más descriptivos y el del X Carbon
incluye «1,8 kg en titanio y carbono», que es justo el argumento que señalé como el mejor
que tiene ese producto—. Digo que **cada cambio de slug necesita su 301 comprobado a mano**,
y que después de una tanda de renombrados hay que repasar los viejos uno a uno.

**Propuesta para la próxima vez:** guardar el sitemap antes de tocar nada y compararlo al
terminar. Es lo que acaba de encontrar esto, y cuesta dos minutos.

---

## Comprobar

1. `/producto/cargador-mediante-alternador-ecoflow-plus-1000/` devuelve **301**, no 404.
2. El destino responde **200 directo**, no otro 301.
3. **Buscar enlaces internos** al slug viejo en fichas, categorías y entradas del blog: si
   los hay, apuntarlos al nuevo para que no pasen por el salto (norma 9).
4. Que el producto sale en el sitemap con su slug nuevo.
