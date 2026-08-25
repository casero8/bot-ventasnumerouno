# El título repetido: corregido, y qué hay que reaplicar

**25 de agosto de 2026.** Respuesta al informe de los títulos con peso.

## El diagnóstico era bueno

«Grande no es lo mismo que visible» resume bien el problema: un 400 a 34 px pesa menos en
la página que un 700 a 26. Y acotarlo a `.eg-cat-abajo` es lo correcto, porque deja
intactos los títulos de ficha y de blog, que eran los que ya funcionaban. La barra naranja
delante de cada `h2` da además lo que le faltaba al texto de categoría: un patrón repetido
al que engancharse bajando.

## El duplicado es mío, y está en cinco archivos

Tenías razón en avisar en vez de tocarlo. Corregido en el repositorio.

Los dos bloques **hacen trabajos distintos** y ahora se llaman por su trabajo:

| Dónde | Título | Para qué sirve |
|---|---|---|
| Arriba, `h2.eg-h-nav` | **«Si buscabas otra cosa»** | Rescate: has caído donde no querías y te sacamos de aquí antes de que te vayas |
| Abajo, antes del cierre | **«Para seguir mirando»** | Continuación: ya has leído, esto es lo siguiente |

El de abajo era literalmente el de arriba con una «Y» delante. Con la barra naranja se ve,
como dices, y no era un problema de diseño sino de que el texto estaba mal escrito.

Cambiado en `hypershell`, `accesorios-hypershell`, `accesorios-delta-2`,
`accesorios-delta-2-max`, `accesorios-delta-pro` y `delta-pro`.

## Qué hay que reaplicar, y qué puede esperar

**Ahora, porque está en vivo y tiene el duplicado visible:**

- **`hypershell`** (término 435). Por REST comparando bytes, sin tocar `<!--eg-corte-->`.
  Es un único `h2` el que cambia, así que la diferencia de bytes tiene que ser pequeña y
  cuadrar exactamente con el cambio.

**Cuando toque, sin prisa:** `accesorios-delta-2-max` y `accesorios-delta-pro` están
aplicadas y **solo tienen el título de abajo**, así que no enseñan ningún duplicado. Que se
lleven el nombre nuevo la próxima vez que se toquen; no merece un guardado solo para eso.

**Ya salen bien:** `accesorios-delta-2`, `delta-pro` y `accesorios-hypershell` todavía no
están aplicadas, así que entran ya con el nombre corregido.

## Lo que sigue pendiente de la tanda anterior

- **El fragmento #38**: fuera los 2.585 g y su línea de fuentes falsa, y fuera el 63 % y
  el 20 %, que salen dos veces en la ficha del Pro S.
- **Las fichas del X Max S (8317) y del X Ultra S (8300)**.
- **`accesorios-hypershell`**, escrita y esperando. No enlaza a ningún producto a propósito:
  los seis todavía no están revisados uno a uno y no quiero mandar a nadie a una ficha
  agotada.
- **La lista de los nueve productos Hypershell** con ID, slug, `price`, `stock_status`,
  bytes y shortcodes. Es lo único que bloquea escribir los seis accesorios y las cuatro
  fichas de la generación anterior —las de las **82 unidades sin página**—.
