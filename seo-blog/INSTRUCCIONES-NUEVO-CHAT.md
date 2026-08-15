# Instrucciones para el nuevo chat

Copia todo lo que hay debajo de la línea y pégalo como primer mensaje.

---

Trabajo sobre el blog de **ecogadgetoficial.com** (WordPress + WooCommerce, distribuidor
oficial de EcoFlow en España). Usa la skill **`ficha-producto-ecogadget`**: recoge el
entorno técnico de esta web y las reglas de la casa. Léela antes de tocar nada.

## Lo que ya está hecho

En el repo **`casero8/bot-ventasnumerouno`**, rama **`claude/blog-seo-optimization-qqx0ja`**,
carpeta **`seo-blog/`**:

- `01`…`09-*.html` — los 9 posts del blog reescritos, en HTML listo para pegar, con las
  clases `eg-*` que ya están instaladas globalmente. Cada archivo lleva arriba, en
  comentario, su título SEO, su meta y lo que hay que verificar.
- `diseno-blog.css` — el diseño nuevo del blog.
- `README.md` — títulos y metas de los 9, mapa de enlazado interno, y el paso a paso de
  etiquetas, autor y fechas.

**Empieza leyendo `seo-blog/README.md` entero.** Todo lo que sigue está detallado ahí.

Ese trabajo se hizo sin acceso a la web (el dominio estaba bloqueado por la política de
red del entorno), así que **está sin publicar y sin verificar contra la web real**.

## Lo que hay que hacer

### 1. Comprobar que tienes acceso

Abre `https://ecogadgetoficial.com/blog/`. Si da 403 en el CONNECT, el dominio sigue
bloqueado: dilo y para, no intentes rodearlo por proxies de terceros.

### 2. Publicar, por este orden de prioridad

1. **Cambiar el slug de `/nueva-entrada-blog/`** a `/placas-solares-piso-ciudad/` con
   redirección 301. Es el peor slug de la web y se arregla en cinco minutos.
2. **Título SEO del post de Starlink**: ahora renderiza a 131 caracteres y Google lo
   parte por la mitad. El nuevo está en el README.
3. **Publicar el post 1** (`/ecoflow-soluciones-portatiles-para-energia-sostenible-en-espana/`).
   Es el 12 % del tráfico orgánico de todo el sitio. **No le cambies el slug** y **no lo
   reorientes a venta**: rankea por intención informativa ("qué es ecoflow", "para qué
   sirve ecoflow") en posición 5-7.
4. **Títulos y metas de las 3 páginas que rankean con cero clics**:
   `/paneles-solares-portatiles/` (24 keywords, 3 visitas),
   `/producto/baliza-v16-homologada-dgt-3-0/` (23 keywords, **0 clics**) y
   `/producto/inversor-ecoflow-powerstream/` (16 keywords, 2 visitas).
   Con posición media 8,6 y CTR del 1,5 %, esto duplica tráfico sin ganar posiciones.
5. **Los otros 7 posts.**

### 3. Diseño del blog

Pega `diseno-blog.css` **dentro del bloque `<style id="eg-pdp-css">` que ya existe** en
Herramientas → Head & Footer Code → Código del pie de página, al final.
**No crees un `<style>` nuevo**: el WAF bloquea guardar etiquetas nuevas ahí y rompe el
guardado entero.

Los selectores marcados con `/* ? */` son del tema Minimog y **están sin verificar**:
confírmalos contra el DOM real antes de dar el trabajo por bueno.

### 4. Etiquetas: fuera

Las tres cosas, no solo la primera:
1. Ocultarlas (ya está en el CSS, sección 8).
2. Entradas → Etiquetas → seleccionar todas → Eliminar.
3. Yoast → Ajustes → Taxonomías → Etiquetas → "Mostrar en resultados de búsqueda": **No**,
   y fuera del sitemap.

**No toques las categorías.**

### 5. Autor: EcoGadget, no EcoFlow

- Usuarios → nombre público **EcoGadget**, con biografía rellena (distribuidor oficial,
  tienda física, servicio técnico). Ahora está vacía y esa caja es señal de autoría.
- Entradas → seleccionar las 9 → Acciones en lote → Editar → Autor: EcoGadget.

### 6. Fechas

**Primero el contenido nuevo, después la fecha.** Cambiar la fecha sin cambiar el texto
no funciona y Google lo detecta. Escalona las 9 fechas en dos o tres semanas, no todas
el mismo día. Pide indexación de cada URL en Search Console al terminar.

## Verifica antes de publicar

No inventes especificaciones. Si un dato no lo encuentras, déjalo fuera.

- **Stock real de cada producto enlazado, en el front.** El filtro del admin no es fiable:
  de 24 productos de más de 500 € marcados con stock, solo 12 eran comprables. Si la
  DELTA 2 está agotada, los enlaces van a la DELTA 3 y a la categoría.
- Consumo real del Starlink Standard (sostiene el post 2 entero).
- Capacidad de los módulos de batería del Power Kit que vendemos (post 3).
- Capacidad y potencia del RIVER 3 Max Plus (post 7, lo dejé fuera de la tabla).
- Que sigan vigentes los 1.039 kWh/año y los 415 € de ahorro de la página de kits de
  balcón (post 9).

## Trampas del entorno que cuestan tiempo

Están todas en `references/entorno.md` de la skill, pero estas tres son las que rompen cosas:

- **Valida la sintaxis del JavaScript antes de guardar.** Un error tumba todos los
  módulos a la vez y no salta ningún aviso.
- **Nunca comentes un módulo con `/* */`** para desactivarlo. Bórralo entero.
- **Purga LiteSpeed después de cada cambio**, o seguirás viendo la versión antigua.

Y además: los **productos** usan editor clásico (hay que pulsar la pestaña "Código" antes
de escribir o TinyMCE machaca el HTML) y las **páginas** usan Gutenberg. En Yoast, los
campos son de React: escribir por JS no dispara los eventos, hay que hacer clic y escribir.

## Límites que no se negocian

- Ni una reseña ni una pregunta de cliente inventada (RDL 24/2021).
- Nada de escasez falsa.
- Nada de "envío gratis" por debajo de 2.000 €.
- Las estimaciones se marcan como estimaciones, en `eg-note`, con la fórmula a la vista.
- Sobre garantía, nunca "no gestionamos garantías" a secas.

## Search Console

Si tengo la exportación en CSV (Rendimiento → 3 meses → Exportar), úsala para confirmar
o corregir la prioridad de enlazado del README, que ahora está calculada con datos
estimados de Semrush y no con clics reales.

Las tres consultas que importan:
1. Páginas, últimos 3 meses, filtrando las 9 URLs del blog.
2. Consultas del post 1: confirmar que sigue siendo intención informativa. Si ha virado a
   consultas de compra, hay que reorientar su título.
3. Páginas con muchas impresiones y CTR por debajo del 1 %.
