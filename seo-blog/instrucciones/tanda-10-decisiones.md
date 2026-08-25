# Tanda 10 · las dos decisiones del punto de control 2

**25 de agosto de 2026.** Contesta al informe del JS instalado y la ficha del X Pro S.

Antes de nada: **la ficha del X Pro S está bien y sigue con las otras dos.** No hay nada
que rehacer. Lo de abajo son las dos decisiones que pediste y cuatro cosas de método.

---

## Decisión 1 · El peso del fragmento #38: se quita

**Se quita la cifra del peso del bloque, no se arregla la línea de fuentes.**

El razonamiento es el mismo que has aplicado tú tres veces hoy y no puede cambiar según
dónde esté escrito el dato. Hemos borrado 2.585 g y 2.571 g de la tabla comparativa porque
no salen de ninguna ficha. Hemos borrado «el más ligero de la serie» porque era esa misma
comparación con otras palabras. Dejar la cifra en el #38 y limitarnos a corregir la
atribución sería seguir publicando un dato que ya decidimos que no podemos sostener, solo
que con una nota al pie más honesta. Eso no arregla el problema: lo documenta.

Concretamente, en el fragmento #38:

1. **Quita el dato «2.585 g · lo que pesa, con batería»** y sus equivalentes en los otros
   dos modelos. Si el bloque queda con un hueco de rejilla, redistribuye lo que queda; no
   metas otro dato para rellenar.
2. **La línea de fuentes se queda**, y entonces sí es verdad: potencia y autonomía salen de
   las fichas de los tres modelos. Compruébalo antes de darla por buena.
3. Cuando llegue la ficha técnica del fabricante con el inventario de los EAN, el peso
   vuelve —a la tabla y al bloque— con las tres cifras y una fuente de verdad.

## Decisión 2 · El 63 % y el 20 %: se quedan en la descripción, se van del #38

Es lo que decía el paso 5.3 y ahora está claro por qué. En la descripción larga van
**con su matización completa**: que son datos del fabricante, medidos en laboratorio, en el
escenario más favorable, que la palabra que importa es «hasta» y que en ruta real la
mejora es menor y desigual. En el bloque del #38 van sueltas, como si fueran una
característica del producto.

Un porcentaje sin su matización, unos centímetros por encima del mismo porcentaje ya
matizado, es la versión mala compitiendo con la buena en la misma página.

**Quítalos del #38.** El bloque se queda con lo que hace bien: orientar a quien nunca ha
llevado uno. **El aviso de que no es producto sanitario sí se queda en los dos**, que es lo
acordado y es deliberado.

---

## Y una tercera que no habías preguntado: el enlace a YouTube se queda

Lo planteabas como duda abierta. No lo es: tu propio argumento la cierra. El `href` solo
se dispara si el JS falta o si se pulsa con Ctrl o Cmd, y es exactamente la red que habría
evitado el problema de esta mañana. Quitarlo devuelve el riesgo del botón de play muerto a
cambio de nada, porque por donde se va la gente es por el reproductor de YouTube una vez
arrancado, y eso no se puede tocar.

**Tu propuesta de alojar los vídeos en nuestro servidor va al dueño**, no la decides tú ni
yo: es pedirle al fabricante el material y su permiso por escrito. Tienes razón en que
siendo distribuidor oficial es un correo. Anotada como tal.

---

## Cuatro cosas de método que salen de tu informe

**1 · Las miniaturas a 480 px eran un error mío, y tu corrección es la buena.** La fachada
se pinta a 1.000 px de ancho: pedir 480 era tirar dos tercios de la resolución de origen
por seguir un número que puse yo sin medir. **Ya está corregido en el repositorio:** los
dieciséis `width="480" height="270"` pasan a `1280` y `720`. Misma proporción, así que no
cambia nada visualmente ni provoca saltos de maqueta; simplemente ahora los atributos
dicen la verdad.

Sobre `exoesqueleto-estabilidad.webp` y sus 225 KB: **déjala.** Va con carga diferida y no
es la primera imagen de ninguna página. Si algún día molesta, la salida no es pelearse con
el codificador WebP de ese servidor, es guardar esa una en JPEG de calidad 75, que a
1.280 × 720 suele quedarse por debajo de los 100 KB.

**2 · `modestbranding` fuera.** Tienes razón y ya está quitado del JS del repositorio.
Estaba obsoleto y no hacía nada: código que aparenta trabajar es peor que código ausente.
`rel=0` se queda, por el motivo que das —limita los relacionados al canal del vídeo, y los
doce son del canal oficial—. Cuando actualices el JS del pie, coge la versión nueva.

**3 · `loading="lazy"` y `decoding="async"` no se tocan.** WordPress los repone él solo.
Anotado para que nadie vuelva a quitarlos creyendo que sirve de algo.

**4 · El `<style>` en línea que desaparece.** La ficha vieja llevaba
`.hypershell-accordion details[open] summary::after` embebido. Al pasar a las clases del
sitio, ese bloque se va — y bien ido. Pero antes de terminar las tres, **comprueba si
`hypershell-accordion` se usa en algún otro sitio**: otra ficha, un fragmento, el CSS del
Personalizador o el pie. Si alguien más depende de esa clase, se queda sin estilo sin que
nadie se entere. Si no la usa nadie, a la lista de código muerto.

Y el susto del recuento: **un selector de atributo CSS parece un shortcode.** `[open]`,
`[type="text"]`, `[data-yt]`. A la lista de trampas, junto al `wc/v3` y al
`regular_price` vacío.

---

## Lo que sigue

Las otras dos fichas, tal cual están escritas. Después la guía, la categoría y el #38 con
las dos decisiones de arriba. Y el punto de control 3 con los once puntos.

## Para el inventario con los EAN

- **El peso de los tres modelos al gramo.** Es lo que más falta le hace ahora mismo a la
  ficha del X Ultra S, que argumenta en contra suya con números y a favor con adjetivos.
- El **par del X Ultra S**.
- La **capacidad en Wh** de la batería: la ficha da 5.000 mAh y no da voltaje.

## Pendiente del dueño

- **Dos fotos del taller de Rivas.**
- **El horario de la tienda.**
- **Si pide al fabricante los vídeos en bruto** y el permiso por escrito para alojarlos.
