# Blog de EcoGadget · reescritura SEO

Reescritura completa de los 9 posts del blog de ecogadgetoficial.com, más el diseño
del blog y los cambios de autor, etiquetas y fechas.

---

## Antes de nada: por qué no está publicado

**No he podido conectarme a ecogadgetoficial.com.** La política de red de esta sesión
deniega el dominio a nivel de proxy (403 en el CONNECT). No es un fallo de la web ni
de credenciales: es la lista de dominios permitidos del entorno remoto, y no se debe
rodear.

```
ecogadgetoficial.com:443 → connect_rejected  (403 policy denial)
search.google.com:443    → connect_rejected  (403 policy denial)
www.ecoflow.com:443      → connect_rejected  (403 policy denial)
google.com:443           → connect_rejected  (403 policy denial)
```

He probado también el enlace directo a Search Console
(`search.google.com/search-console?resource_id=sc-domain:ecogadgetoficial.com`) y
está denegado igual. Y aunque no lo estuviera, Search Console exige sesión iniciada
de Google: para leerlo hace falta o el dominio desbloqueado con una sesión activa, o
que me peguéis la exportación en CSV.

De hecho está bloqueada toda la salida a internet salvo las herramientas de búsqueda
y los conectores SEO. Eso significa que **en esta sesión no he podido**:

- entrar en WordPress ni publicar nada
- leer el contenido actual de los posts palabra por palabra
- abrir Search Console
- quitar etiquetas, cambiar el autor ni tocar fechas

Para que lo haga yo directamente, hay que añadir `ecogadgetoficial.com` a los dominios
permitidos del entorno (Claude Code on the web → el entorno de este proyecto → política
de red). Con eso, en la siguiente sesión aplico todo esto en la web en un rato.

Mientras tanto, aquí está todo hecho y listo para pegar.

---

## Lo que sí he podido usar

- **Semrush**, informe de páginas orgánicas del dominio (base España). Es dato real
  y es lo que sostiene las prioridades de enlazado de más abajo.
- **Búsqueda web**, para localizar los 9 posts y su contenido actual a grandes rasgos.
- Las referencias de la skill de ficha de producto: clases CSS ya instaladas, patrón
  de título y meta, fórmulas de autonomía y los datos de Search Console que ya
  habíais recogido antes (CTR 1,5 %, posición media 8,6, 65 % móvil).

Ahrefs y las unidades de API de Semrush se agotaron a mitad, así que no hay volúmenes
de búsqueda nuevos. Lo digo para que nadie dé por hecho que hay datos frescos detrás
de cada decisión: donde no los hay, he tirado de los datos que ya teníais documentados.

---

## Los 9 posts

| # | Archivo | URL actual | Estado |
|---|---|---|---|
| 1 | `01-que-es-ecoflow.html` | `/ecoflow-soluciones-portatiles-para-energia-sostenible-en-espana/` | Slug intacto (es el que más tracciona) |
| 2 | `02-ecoflow-starlink-ordenador.html` | `/ecoflow-para-starlink-y-ordenador-que-estacion-de-energia-elegir/` | Título de 131 → 56 caracteres |
| 3 | `03-power-kits-instalacion.html` | `/instalacion-power-kits-independence-ecoflow/` | Slug intacto |
| 4 | `04-camperizacion-furgoneta.html` | `/camperizacion-de-una-furgoneta-ecoflow/` | Contenido genérico → guía con números |
| 5 | `05-cuantas-placas-solares-casa.html` | `/como-calcular-cuantos-paneles-solares-necesita-tu-vivienda/` | Slug intacto |
| 6 | `06-panel-solar-portatil.html` | `/paneles-solares-portatiles-energia-limpia-y-versatil-para-tus-aventuras/` | **Cambiar slug** + 301 |
| 7 | `07-ecoflow-river-cual-elegir.html` | `/ecoflow-river-la-estacion-de-energia-portatil-ideal/` | Slug intacto |
| 8 | `08-ecoflow-delta-2.html` | `/ecoflow-delta-2-energia-portatil-sostenible-con-capacidad-ampliable/` | Slug intacto |
| 9 | `09-placas-solares-piso-ciudad.html` | `/nueva-entrada-blog/` | **Cambiar slug** + 301 |

Cada archivo lleva arriba, en comentario, su título SEO, su meta y lo que hay que
verificar antes de publicar.

---

## Títulos y metas para Yoast

Recordatorio del fallo global: si dejas el Título SEO vacío, Yoast añade
`- Distribuidor EcoFlow España | Tienda Física y Servicio Técnico`, que son 62
caracteres, y Google parte el título por la mitad. **Escribe siempre el título propio.**

| # | Título SEO | Car. |
|---|---|---|
| 1 | `Qué es EcoFlow y para qué sirve: guía en español` | 48 |
| 2 | `EcoFlow para Starlink: qué batería aguanta un día` | 49 |
| 3 | `Power Kits EcoFlow: instalación paso a paso en casa` | 51 |
| 4 | `Instalación eléctrica de una camper: guía y equipos` | 51 |
| 5 | `Cuántas placas solares necesita tu casa: cálculo real` | 53 |
| 6 | `Panel solar portátil: cuál comprar y cuánto carga` | 49 |
| 7 | `EcoFlow RIVER: qué modelo elegir y qué aguanta` | 46 |
| 8 | `EcoFlow DELTA 2 1024Wh: autonomía real y análisis` | 49 |
| 9 | `Placas solares en un piso: sí se puede, así se hace` | 51 |

Metas (135-155 caracteres, todas dentro de rango):

1. `Qué es EcoFlow, cómo funcionan sus estaciones de energía y cuál elegir según lo que quieras enchufar. Guía del distribuidor oficial en España.` (140)
2. `Cuánto consume Starlink de verdad, qué estación EcoFlow lo mantiene 24 h y cuál elegir si además usas el ordenador. Con tabla de autonomía y precios.` (147)
3. `Cómo se instala un Power Kit Independence de EcoFlow: qué necesitas, en qué orden se monta, qué hace un electricista y cuánto cuesta la obra.` (139)
4. `Cuánta batería y cuántas placas necesita tu furgoneta camper, con consumos reales aparato por aparato y tres montajes cerrados según presupuesto.` (143)
5. `Calcula cuántos paneles solares necesitas con tu factura de la luz, paso a paso y con un ejemplo resuelto. Incluye qué cambia si añades batería.` (142)
6. `Qué panel solar portátil elegir según lo que quieras cargar, cuánta energía produce de verdad al día en España y cuándo conviene uno rígido.` (138)
7. `Comparativa de la serie RIVER de EcoFlow: capacidad, potencia y autonomía real de cada modelo, con la tabla de qué puedes enchufar en cada uno.` (141)
8. `Qué aguanta de verdad una EcoFlow DELTA 2 de 1024 Wh, cuánto tarda en cargar y cuándo compensa subir a la DELTA 2 Max o a la serie DELTA 3.` (137)
9. `Cómo poner placas solares en un piso sin tejado propio: kits de balcón, cuánto ahorran de verdad al año y qué dice la normativa en España.` (136)

---

## Enlazado interno: a qué post darle relevancia

**Aviso sobre la fuente.** No he podido abrir Search Console. Esta priorización sale
del informe de páginas orgánicas de **Semrush** (base España), que es dato real pero
no es exactamente lo mismo: Semrush estima tráfico a partir de posiciones, y Search
Console te da los clics de verdad. Confirmadlo con las tres consultas del final.

Datos de Semrush, dominio completo:

| URL | Keywords | Tráfico | % del total |
|---|---|---|---|
| Home | 25 | 1.004 | 80,1 % |
| **`/ecoflow-soluciones-portatiles…/`** | 6 | **150** | **12,0 %** |
| `/serie-delta-ecoflow/serie-delta-2/` | 5 | 25 | 2,0 % |
| `/producto/…delta-2-max/` | 8 | 12 | 1,0 % |
| `/producto/ecoflow-river-3-plus/` | 5 | 5 | 0,4 % |
| `/paneles-solares-portatiles/` | **24** | 3 | 0,2 % |
| `/producto/inversor-ecoflow-powerstream/` | **16** | 2 | 0,2 % |
| `/producto/baliza-v16-homologada-dgt-3-0/` | **23** | 0 | 0 % |

### Conclusión: hay un solo post que importa de verdad

**`/ecoflow-soluciones-portatiles-para-energia-sostenible-en-espana/` (el post 1) es
el 12 % de todo el tráfico orgánico del sitio y el 100 % del tráfico del blog.**
Todos los demás posts juntos no llegan a 2 visitas. Fuera de la home, es la página
más fuerte que tenéis.

Eso decide la estrategia de enlazado, y es lo contrario de lo que suele hacerse:

**1. El post 1 es el que REPARTE, no el que recibe.**
Es la única página del blog con autoridad que dar. Sus enlaces salientes valen algo.
Por eso en la reescritura le he puesto enlaces contextuales a las cuatro gamas
(RIVER, DELTA, STREAM, Power Kits) y una tabla final de "qué modelo según tu caso"
con seis enlaces a producto. No lo llenes de más: cuantos más enlaces, menos vale cada uno.

**2. Los demás 8 posts son los que RECIBEN.**
Enlázalos desde el post 1 solo cuando venga a cuento, y desde entre ellos siempre que
el tema encaje. Ya lo he dejado montado: el 5 enlaza al 9, el 9 enlaza al 5, el 2 y el
4 enlazan a paneles, etc.

**3. Tres páginas con muchas keywords y cero clics: ahí está el dinero rápido.**
No es problema de enlazado, es de título y meta.

- `/paneles-solares-portatiles/` — 24 keywords, 3 visitas
- `/producto/baliza-v16-homologada-dgt-3-0/` — 23 keywords, **0 visitas**
- `/producto/inversor-ecoflow-powerstream/` — 16 keywords, 2 visitas

Rankean y nadie hace clic. Con la posición media de 8,6 y el CTR de 1,5 % que ya
teníais medido, reescribir esos tres títulos puede duplicar su tráfico sin ganar una
sola posición. La baliza V16 es la más sangrante: 23 keywords y ni un clic, en un año
en el que la baliza es obligatoria.

**4. Prioridad de trabajo, por orden:**

| Orden | Qué | Por qué |
|---|---|---|
| 1.º | Slug de `/nueva-entrada-blog/` | No dice nada. Máximo impacto, cinco minutos |
| 2.º | Título del post 2 (131 car.) | Google lo está partiendo por la mitad |
| 3.º | Publicar el post 1 reescrito | Es el 12 % del tráfico del sitio |
| 4.º | Títulos de las 3 páginas con clics a cero | Tráfico sin ganar posiciones |
| 5.º | Los otros 7 posts | Base para crecer |

### Bloque de gamas para el final de cada post

HTML reutilizable. Usa las clases del CSS nuevo:

```html
<div class="eg-gamas">
  <a href="/product-category/river-3/"><b>Serie RIVER</b><span>245–768 Wh. Camping, portátiles y apagones cortos.</span></a>
  <a href="/serie-delta-ecoflow/serie-delta-3/"><b>Serie DELTA</b><span>1–4 kWh. Casa, camper y electrodomésticos.</span></a>
  <a href="/product-category/stream-series/"><b>Serie STREAM</b><span>Autoconsumo enchufable para bajar la factura.</span></a>
  <a href="/paneles-solares"><b>Paneles solares</b><span>Portátiles y rígidos para no depender del enchufe.</span></a>
</div>
```

### Las tres consultas que hay que hacer en Search Console

Cuando entréis, mirad esto y me lo pasáis (o lo miro yo si desbloqueáis el dominio):

1. **Rendimiento → Páginas**, últimos 3 meses, filtrando por las 9 URLs del blog.
   Clics e impresiones reales de cada una.
2. **Filtrar por la URL del post 1** y mirar sus consultas. Confirma que sigue siendo
   intención informativa ("qué es ecoflow", "para qué sirve ecoflow"). Si ha cambiado
   hacia consultas de compra, el título del post 1 hay que reorientarlo.
3. **Páginas con más impresiones y CTR por debajo del 1 %.** Ahí están los títulos que
   más rinden al reescribirlos.

---

## Etiquetas: fuera

Las etiquetas del blog no aportan nada al lector y sus archivos generan páginas casi
vacías que se comen presupuesto de rastreo. Y esta web tiene ya un problema serio de
eso: Google conoce 801.000 URLs de filtros y solo indexa 382 páginas.

Hay que hacer las tres cosas, no solo la primera:

1. **Ocultarlas visualmente** → ya está en `diseno-blog.css`, sección 8.
2. **Borrarlas de verdad** → Entradas → Etiquetas → seleccionar todas → Eliminar.
   Al borrar la etiqueta desaparece también su archivo. No afecta al contenido.
3. **Poner los archivos de etiqueta en noindex** → Yoast SEO → Ajustes → Taxonomías →
   Etiquetas → "Mostrar en resultados de búsqueda": **No**. Y desactivar su sitemap.

El paso 3 es el importante aunque borres las etiquetas, porque evita que vuelva a
pasar si alguien crea una nueva sin pensarlo.

**No toques las categorías.** Esas sí sirven: estructuran el blog y pueden posicionar.

---

## Autor: EcoGadget, no EcoFlow

Ahora mismo los posts van firmados como EcoFlow, que es el fabricante. Es un error
que resta: quien lee un análisis de una DELTA 2 firmado por el que la fabrica lo lee
como publicidad. Firmado por el distribuidor con tienda física y servicio técnico, lo
lee como criterio. Y para Google, la señal de autoría propia es la que cuenta.

**Cómo cambiarlo en todos los posts a la vez:**

1. Usuarios → añadir o editar el usuario que vais a usar.
   - Nombre para mostrar públicamente: **EcoGadget**
   - Rellenad la biografía: distribuidor oficial EcoFlow en España, tienda física en
     [ciudad], servicio técnico oficial. Dos o tres líneas, con enlace a la home.
     Esa caja de autor es una señal de autoría real y ahora mismo está vacía.
2. Entradas → Todas las entradas → seleccionar las 9 → Acciones en lote → **Editar** →
   Aplicar → cambiar **Autor** a EcoGadget → Actualizar.

Comprobad después que en Yoast, en Ajustes → Formatos de contenido, los archivos de
autor no estén generando URLs indexables si solo hay un autor: con un único autor, el
archivo de autor duplica la portada del blog.

---

## Fechas: actualizar a hoy

Ojo con esto, que se hace mal muy a menudo. **Cambiar la fecha sin cambiar el
contenido no funciona** y Google lo detecta: compara el contenido entre rastreos. Un
post con fecha de hoy y el mismo texto de 2024 no gana nada y puede perder confianza.

Pero aquí sí procede, porque el contenido cambia de verdad: estos 9 posts se reescriben
enteros. Así que la secuencia correcta es:

1. Pegar el contenido nuevo.
2. Actualizar el título SEO y la meta.
3. **Después**, cambiar la fecha de publicación a la de hoy.
4. Guardar y pedir indexación de esa URL en Search Console.

En el editor: panel de la derecha → Publicar → Inmediatamente → fecha de hoy.

Recomendación adicional: dejad visible la fecha de **modificación**, no solo la de
publicación. Yoast la incluye en el schema de artículo, y para el lector "actualizado
el 15 de agosto de 2026" vale más que una fecha de publicación reciente que no cuadra
con los comentarios de hace dos años.

Escalonad las fechas: nueve posts publicados exactamente el mismo día se ve raro.
Repartidlos a lo largo de dos o tres semanas conforme los vayáis subiendo.

---

## Diseño del blog

`diseno-blog.css` va **dentro del bloque `<style id="eg-pdp-css">` que ya existe** en
Herramientas → Head & Footer Code → Código del pie de página, al final. No creéis un
`<style>` nuevo: el WAF bloquea guardar etiquetas nuevas ahí y rompe el guardado.

El problema real del blog actual no es que sea feo, es que la línea de texto es
demasiado ancha y demasiado pequeña. Con más de 100 caracteres por línea el ojo
pierde el renglón al volver a la izquierda. Lo que arregla eso:

- **Medida de línea de 72 caracteres.** Es la corrección que más se nota.
- **18 px con interlineado 1,75** en escritorio, 17 px en móvil.
- **Jerarquía de títulos real**, con línea separadora y mucho más espacio arriba que
  abajo, para que cada H2 quede pegado a su propio texto.
- **Enlaces subrayados y en azul.** Ahora no se distinguen del texto, y un enlace que
  no parece enlace no se pulsa: todo el enlazado interno que estamos montando depende
  de esto.
- **Entradilla más grande**, que da jerarquía y engancha.
- **Listado en tarjetas** con imagen, título, entradilla y fecha. Sin etiquetas.
- **Móvil primero de facto**, que es el 65 % del tráfico.

**Los selectores marcados con `/* ? */` hay que confirmarlos contra el DOM real de
Minimog.** Los he escrito con los nombres estándar de WordPress más los habituales del
tema, pero no he podido abrir la web para verificarlos. Si alguno no coincide, esa
regla simplemente no se aplica: no rompe nada, pero tampoco arregla nada.

Después de guardar: **LiteSpeed Cache → Purgar todo**, o seguiréis viendo lo de antes.

---

## Datos a verificar antes de publicar

No he inventado ninguna especificación. Donde no tenía el dato confirmado, lo he
dejado fuera en lugar de rellenarlo. Estos son los puntos que hay que comprobar:

| Post | Qué verificar |
|---|---|
| 2 | Consumo real del Starlink Standard. Sostiene todo el artículo |
| 3 | Capacidad de los módulos de batería del Power Kit que vendéis; si el kit incluye cuadro de conmutación |
| 7 | Capacidad y potencia del RIVER 3 Max Plus (lo he dejado fuera de la tabla) |
| 8 | **Stock real de la DELTA 2.** Si está agotada, los enlaces van a DELTA 3 y a la categoría |
| 9 | Que sigan vigentes los 1.039 kWh/año y los 415 € de ahorro de vuestra página de kits |
| Todos | Stock real de cada producto enlazado, en el front y no en el filtro del admin |

Sobre el apartado de normativa del post 9: lo he redactado sin afirmar que los kits
enchufables estén expresamente amparados, porque en España no existe una exención
equivalente a la alemana. Si tenéis criterio jurídico propio, ajustadlo.

Y una nota sobre expectativas: Google reescribe el título por su cuenta en torno al
60 % de las veces, y los cambios tardan entre unos días y tres semanas en verse.
Se puede acelerar pidiendo indexación de cada URL en Search Console.

---

## Qué he respetado de las reglas de la casa

- Ni una reseña ni una pregunta de cliente inventada.
- Nada de escasez falsa.
- Ninguna mención a "envío gratis" en artículos que enlazan a productos por debajo
  de 2.000 €.
- Las estimaciones van marcadas como estimaciones, en `eg-note`, con la fórmula a la
  vista (85 % de rendimiento del inversor).
- Cada post lleva una **objeción honesta**: qué no puede hacer el producto y para
  quién no es. En el post 7 se dice claramente que ninguna RIVER mantiene la nevera
  de casa, y en el 8 que la DELTA 2 no cubre un apagón de día entero.
- Sobre garantía, la redacción es la correcta: la tramitamos para compras en la web y
  reparamos cualquier equipo como servicio técnico oficial, de pago y con presupuesto.
  En ningún sitio se dice "no gestionamos garantías" a secas.
