# Dos hallazgos del barrido en vivo, y el primero vale más que todo el SEO de hoy

**9 de octubre de 2026.** Leyendo los 149 productos del sitemap han salido dos cosas que
contradicen lo que llevamos semanas dando por hecho.

---

## 1 · Los cuatro HyperShell de la generación anterior SÍ tienen ficha. Están AGOTADOS

Durante todo este trabajo he repetido que había «82 unidades en el almacén **sin una sola
página** que las venda», y lo he puesto en cuatro partes distintas. **Es falso. Las páginas
existen, están escritas y tienen precio:**

| Producto | URL | Precio | Estado en la web |
|---|---|---:|---|
| Hypershell X Ultra | `/producto/hypershell-x-ultra/` | 1.799 € | **Agotado** |
| Hypershell X Carbon | `/producto/hypershell-x-carbon/` | 1.299 € | **Agotado** |
| Hypershell X Pro | `/producto/hypershell-x-pro/` | 899 € | **Agotado** |
| Hypershell X Go | `/producto/hypershell-x-go/` | 699 € | **Agotado** |

Y tienen título trabajado: «Hypershell X Ultra — 1.000 W y 65 km en bicicleta», «X Carbon —
exoesqueleto de 1,8 kg en titanio», «X Go — exoesqueleto ligero de 400 W para ciudad».

**Así que el problema nunca fue escribir cuatro fichas. El problema es que hay stock en el
almacén y la web dice que no hay.**

Si las 82 unidades existen y están repartidas entre estos cuatro modelos, a estos precios
estamos hablando de **decenas de miles de euros de producto marcado como no disponible**.
Cambiar un desplegable de «Agotado» a «Hay existencias» es el trabajo de más valor por
minuto de toda esta lista, y no es SEO: es que la tienda deje de esconder lo que tiene.

**Lo que hay que hacer, en este orden:**

1. **Preguntarle al dueño cuántas unidades hay de cada uno de los cuatro.** Es el único dato
   que falta y no está en ningún sitio al que yo llegue.
2. **Poner el stock real** en cada ficha. Si WooCommerce gestiona inventario, con la
   cantidad; si no, marcar «Hay existencias».
3. **Comprobar que salen en la categoría** `/product-category/hypershell/` una vez con
   stock. Ahora mismo no salen, y además eso **cambia la fila de pastillas**, que filtra por
   producto comprable (norma 14).
4. **Añadir a cada una un párrafo que explique la diferencia con la serie S actual.** Es lo
   primero que va a preguntar quien vea dos precios de «Hypershell X Pro». Eso sí lo escribo
   yo, y ahora tiene sentido escribirlo porque las fichas existen.
5. **Enlazarlas desde la guía** `/que-es-un-exoesqueleto/` y desde la categoría, con una
   línea del tipo «generación anterior, con precio de salida».

**Y hay que corregir lo que yo escribí mal**, en estos sitios:
`instrucciones/tanda-11-publicar-y-marca.md`, `instrucciones/tanda-guia-exoesqueletos.md`,
`categorias/hypershell-meta.txt` y la auditoría. Donde dicen «sin ficha» tienen que decir
«con ficha, pero marcadas como agotadas».

---

## 2 · El inversor PowerStream tiene ficha y está agotado

`/producto/inversor-ecoflow-powerstream/` · **219 €** · **Agotado** ·
«Inversor EcoFlow PowerStream 800 W para balcón solar»

Encaja con que esté descontinuado, así que el texto que escribí para `stream-series` —que
dice que lo está y manda al microinversor STREAM— es correcto.

**Pero esa página no se debe borrar ni redirigir.** En Search Console, el PowerStream suma
unas **624 impresiones** repartidas en cinco consultas (`powerstream` 177,
`ecoflow powerstream 800w` 158, `power stream` 126, `ecoflow powerstream` 85,
`inversor ecoflow powerstream 600 w/800 w` 78). Esa gente busca ese nombre, y la página que
responde a ese nombre es ésta.

**Lo que hay que hacer es ponerle arriba un aviso que convierta la visita en venta:**

```html
<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Este modelo está descontinuado.</strong> EcoFlow lo ha sustituido por el <strong>microinversor STREAM</strong>, que hace el mismo trabajo dentro de la gama nueva. Si buscabas el PowerStream para comprarlo, <a href="/product-category/stream-series/">esto es lo que le corresponde hoy</a>. Si ya tienes uno y buscas su manual, está en <a href="/man/">los manuales en PDF</a>.</p>
</div>
```

Una ficha agotada sin ese aviso es un callejón sin salida para 624 impresiones al año.
Con el aviso, es una puerta.

---

## 3 · La lista exacta de productos con texto que hay que corregir

Barrido completo guardado en `mantenimiento/barrido-productos-2026-10-09.txt`.
**30 de 149 productos** llevan alguna de las frases en su propia descripción:

| Frase | Productos |
|---|---:|
| `eg-trust` «y 14 días para devolverlo sin dar explicaciones» | **29** |
| `eg-taxline` «· 14 días para devolverlo» | **16** |
| «Bizum» | **10** |
| `eg-trust` «servicio técnico propio con tienda física» | **7** |
| Descripción larga «servicio técnico propio» | **5** |
| Descripción larga «si hay avería la reparamos nosotros» | **4** |

**Los seis más cargados**, que llevan cinco o seis frases cada uno y por los que conviene
empezar: `ecoflow-delta-3-classic-1024wh`, `ecoflow-river-3-max-plus`, `ecoflow-stream-ca-pro`,
`estacion-energia-portatil-ecoflow-delta-3-plu`, `ecoflow-delta-max-ultra` y
`ecoflow-stream-ultra-x`.

**Los cinco con la afirmación falsa de servicio técnico en la descripción larga**, que son
los urgentes: los cuatro primeros de la lista anterior más `inversor-ecoflow-powerstream`.

Los once productos de HyperShell y los cinco Lokithor solo llevan la frase de los 14 días,
que es la menos grave.

**Recuerda que esto es solo la capa de producto.** La capa global —el JSON-LD, el menú, el
pie, el CSS y los dos JS— está en `mapa-servicio-tecnico-en-vivo.md` y **se arregla
primero**, porque afecta a las 326 URLs del sitio.
