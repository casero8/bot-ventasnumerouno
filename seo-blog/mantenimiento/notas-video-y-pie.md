# Dos cosas que no hay que volver a investigar

**25 de agosto de 2026.** Comprobado en vivo y en la documentación de Google, no de
memoria. Se escribe aquí para que nadie vuelva a gastar una tarde en lo mismo.

## 1 · Lo que NO se puede hacer con un vídeo incrustado de YouTube

| Lo que se querría | Se puede | Detalle |
|---|---|---|
| Quitar el título y el canal | **No** | Google: «el avatar del canal y el título del vídeo siempre se mostrarán antes de que empiece la reproducción, cuando la reproducción se pause y cuando termine». El parámetro `showinfo` está retirado |
| Quitar el botón «Ver en YouTube» | **No** | Lo mismo |
| `modestbranding=1` | **No hace nada** | Obsoleto: «no tendrá ningún efecto». **Quitado del JS**, para que no aparente trabajar |
| `rel=0` | **Sí, y sirve** | Ya no quita los relacionados, pero los limita al canal del vídeo. Como todos los nuestros son del canal oficial de Hypershell, al terminar solo se ofrece más Hypershell. **Se queda** |
| Forzar la calidad | **No** | Ni `vq` ni `hd` existen. La decide YouTube por ancho de banda y por el tamaño en que se pinta el reproductor |
| Tapar el reproductor con una capa | **No** | Las condiciones de YouTube lo prohíben expresamente |

**Lo que sí se puede: todo lo que pasa antes del clic.** Ahí está la fachada, la miniatura
y la capa propia. Después del clic, el reproductor es suyo.

**La única vía para que nadie salga** es alojar los vídeos en nuestro servidor, con el
material y el permiso por escrito del fabricante. Correo redactado en
`seo-blog/correos/hypershell-material-video.md`.

### Y sobre las miniaturas

**Se piden a `maxresdefault`, 1.280 × 720, y se suben a ese tamaño.** La fachada se pinta a
unos 1.000 px de ancho: subirlas a 480 —como decía un parte anterior— era tirar dos tercios
de la resolución y verlas blandas, más aún en pantallas Retina.

`loading="lazy"` y `decoding="async"` **no se tocan**: WordPress los repone él solo al
servir el contenido, así que quitarlos del HTML no sirve de nada.

## 2 · El pie de Head & Footer Code se escribe por REST, no por el formulario

`options.php` falló dos veces seguidas: la primera sin aviso, la segunda con un **403 del
cortafuegos**. La norma 7 ya decía que ese formulario falla una de cada dos veces; ahora
además sabemos que hay un WAF que rechaza ciertos envíos.

**Se escribe por la ruta `eg/v1/pie`** del fragmento #39, que escribe la opción en texto
plano y devuelve los bytes antes y después. Igual que con todo lo demás: se relee del
servidor antes de dar el cambio por bueno.

**Un detalle que evita un susto:** PHP cuenta unos 900 bytes más que el navegador en ese
campo, porque el navegador normaliza los saltos de línea y PHP cuenta también los retornos
de carro. No falta ni sobra contenido: es la misma cadena contada de dos maneras.

## 3 · Trampas nuevas para la lista

- **Un selector de atributo CSS parece un shortcode.** `[open]`, `[type="text"]`,
  `[data-yt]`. Si cuentas shortcodes con una expresión ingenua, un `<style>` en línea te
  hace creer que has destruido dos.
- **Con desplazamiento programático (`window.scrollTo`) la carga diferida no se dispara de
  forma fiable.** Antes de dar por rota una imagen, comprueba con el ratón.
