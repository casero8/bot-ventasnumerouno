# Blog de EcoGadget · reescritura SEO

**Revisión del 15/08/2026, con acceso a la web y con Search Console real.**

Esta versión corrige la anterior, que se escribió a ciegas (el dominio estaba
bloqueado) y con estimaciones de Semrush. Ahora hay datos de verdad, y **cambian
las conclusiones**. Lo que sigue está verificado contra la web y contra la
exportación de Search Console de 16 meses.

---

## 0. APLICADO EN LA WEB EL 15/08/2026

Con la contraseña de aplicación se pudo trabajar contra la API REST. Esto ya
está **hecho y verificado en el front**, no es una propuesta:

| Qué | Antes | Ahora |
|---|---|---|
| **Título y meta del PowerStream** | 98 car. de plantilla, **sin meta** | 52 car. + meta de 145 |
| **Slug de `/nueva-entrada-blog/`** | `nueva-entrada-blog` | `placas-solares-piso-ciudad` con **301 verificado** |
| Título y meta de esa entrada | 104 car., sin meta | 51 + 138 |
| **Autor de las 22 entradas** | ECOFLOW ESPAÑA | **EcoGadget**, con biografía |
| Slug del autor | `info-ecosassgmail-com` *(publicaba el correo)* | `ecogadget` |
| **Etiquetas** | 76 | **0**, y `post_tag-sitemap.xml` da 404 |
| Títulos y metas de entrada | 3 de 22 en rango | **13 de 22** |
| **CSS del blog** | sin aplicar | ✅ **aplicado y sirviéndose** |

Los 9 títulos y metas escritos: DELTA 2 (4441), RIVER vs DELTA (2708), Power
Kits (2679), camperización (2672), cuántas placas (5976), panel portátil
(4652), RIVER (4155), inversor casa aislada (5938) y subvenciones (7719).

### Detalles que conviene saber

**El 301 de la entrada 9 ya existía.** Lo creó Redirection al cambiar el slug
(regla id 7). Comprobado: `/nueva-entrada-blog/` responde 301 hacia la URL nueva.

**El autor se cambió renombrando al usuario 1**, no reasignando entradas. Es
mejor: una sola operación, sin romper nada y sin tocar el histórico. De paso se
cambió su slug, porque `/author/info-ecosassgmail-com/` estaba publicando una
dirección de correo en la URL.

**La biografía habla de tienda física pero no dice de qué ciudad**, porque no
me consta. Conviene añadirla en Usuarios → Perfil: da señal local.

**Las etiquetas se borraron con copia previa**, en `etiquetas-borradas-backup.md`,
con qué entrada tenía cada una por si hay que recuperar alguna.

**El CSS del blog se aplicó esquivando el formulario roto.** Como Head & Footer
Code devuelve 403 (sección 7 bis) y esa opción no se puede escribir por API, se
metió en el **CSS personalizado del kit de Elementor** (`custom_css` dentro de
`_elementor_page_settings` del kit 7148), que sí es escribible por REST y se
sirve en todo el sitio. Los ajustes que ya tenía el kit se conservaron intactos.

Para que se publicara hicieron falta dos pasos más, y conviene apuntarlos porque
sin ellos parece que no funciona:

1. `DELETE /wp-json/elementor/v1/cache` — obliga a Elementor a regenerar su CSS.
2. Eso cambia el hash del CSS combinado de LiteSpeed, que se reconstruye solo.

Comprobado en las dos plantillas: en una entrada
(`.single-post .entry-content{max-width:72ch…}`) y en el listado
(`.page-id-3230 .elementor-post__card{…}`). Las reglas llegan enteras tras
minificar.

> Si algún día hay que **quitar** este CSS, no está en Head & Footer Code:
> está en Elementor → Ajustes del sitio → CSS personalizado.

**Falta el paso 3 de las etiquetas:** Yoast → Ajustes → Taxonomías → Etiquetas →
«Mostrar en resultados de búsqueda»: **No**. Ahora mismo el sitemap ya no las
lista porque no queda ninguna, pero si alguien crea una etiqueta nueva volverá a
aparecer. Ese ajuste no se puede tocar por API: son dos clics a mano.

### Lo que NO se tocó, y por qué

**Las 9 entradas que siguen sin meta.** Se comprobó en Search Console y todas
tienen **0 clics**, con entre 0 y 60 impresiones en 16 meses. Escribirles el
título no daría nada porque no las ve nadie. Son de 2023-2024 y de 400-700
palabras: lo que piden es fusionarse o desaparecer, no una meta nueva.

**Canibalización entre los dos posts de RIVER.** Hay dos artículos casi gemelos:

| URL | Palabras | Clics | Impr. | Pos. |
|---|---|---|---|---|
| `ecoflow-river-la-estacion-de-energia-portatil-ideal` | 2.706 | 10 | 846 | **9,10** |
| `ecoflow-river-energia-portatil-y-sostenible-para-tus-aventuras` | 2.683 | 0 | 20 | **44,1** |

No son copia literal (0 % de frases idénticas, 33 % de vocabulario común): son
dos redacciones distintas del mismo artículo. Misma intención, misma extensión,
mismo arranque. Compiten entre ellas y por eso una está en la posición 44.

Lo correcto es **301 del segundo al primero**, pero eso es tirar 2.683 palabras
y es decisión tuya, así que no lo he hecho.

---

## 0 bis. SEGUNDA TANDA (15/08/2026): diseño, enlazado y posts nuevos

### Diseño del blog · v2, aplicado

`diseno-blog.css` reescrito y **sirviéndose ya**. Vive en **Elementor → Ajustes
del sitio → CSS personalizado** (clave `custom_css` del kit 7148).

Lo que cambia respecto a antes:

- **Medida de línea de 68 caracteres** y cuerpo a 19 px con interlineado 1,75.
  Es la corrección que más se nota: con 100+ caracteres por renglón el ojo se
  pierde al volver a la izquierda.
- **Cabecera editorial**: titular grande con `text-wrap: balance`, metadatos en
  una línea sobria con punto de separación, e imagen destacada ancha y redondeada.
- **H2 con marca de color** encima y mucho más aire arriba que abajo, para que
  cada título quede pegado a su propio texto.
- **Enlaces que parecen enlaces**: azul de la casa, subrayado con separación.
  Todo el enlazado interno depende de esto.
- **Tablas que ruedan en móvil** en lugar de desbordarse (65 % del tráfico).
- **Listado en tarjetas** con proporción de imagen fija 16:10, para que la
  rejilla deje de bailar, y sin autor repetido doce veces.
- Foco visible para teclado y respeto a `prefers-reduced-motion`.

La paleta usa el azul de las fichas (`#1E73BE`) para que blog y tienda no
parezcan dos webs distintas.

### Plan B: hecho a medias, y explico por qué

**Lo que sí:** se comprobó que mover el CSS de fichas al kit de Elementor es
seguro. De sus 595 reglas, 271 llevan `!important` y solo 2 selectores
coincidían con algo que carga después — y esos dos eran `0%` y `100%` de
keyframes. Riesgo de cascada: cero. También hay cero solapamiento con los 36
selectores de `wp-custom-css`.

**Lo que no:** para vaciar el campo del pie hay que enviar el formulario, y en
ese envío viaja también el campo de cabecera, que lleva el `<script>` de
Analytics y **es lo que dispara el WAF**. O sea: no se puede vaciar solo el pie.

Habría que mover primero la cabecera (Analytics, Umami, verificación y **las
fuentes**) a Elementor → Código personalizado. Eso no se hizo: mover etiquetas
de seguimiento por API está bloqueado en este entorno, y con razón. Además es
la parte delicada: si se mueve mal, dejas de medir y no te enteras en semanas.

Se llegó a copiar el CSS de fichas al kit para probarlo y **se revirtió**, para
no dejar 133 KB de CSS duplicado en cada página.

**Procedimiento manual para cerrarlo** (15 minutos):

1. Elementor → Código personalizado → nuevo, ubicación *head*. Pega ahí el
   contenido actual del campo de cabecera de Head & Footer Code. Publica.
2. Comprueba en el front que siguen apareciendo el gtag, Umami y **el `<link>`
   de las fuentes** (Barlow Condensed y Noto Sans Display).
3. Copia el CSS de fichas (sin la etiqueta de estilo que lo envuelve) al final
   del CSS personalizado del kit.
4. Ahora sí: vacía **los dos** campos de Head & Footer Code y guarda. Sin
   etiquetas en el envío, el WAF deja pasar.
5. `DELETE /wp-json/elementor/v1/cache` y purga LiteSpeed.

Ganancia extra: esos 61 KB dejan de ir **inline en cada página** y pasan a un
archivo cacheado. Con los problemas de LCP que tenéis, no es poca cosa.

### Enlazado interno, con datos reales

El hallazgo: **las dos mejores entradas del blog no tenían ni un solo enlace
interno**, y `/man/`, con 19.583 impresiones, no lo enlazaba nadie.

| Entrada | Clics | Enlaces antes | Ahora |
|---|---|---|---|
| Starlink y ordenador (5650) | 85 | **0** | 8 |
| RIVER vs DELTA (2708) | 54 | **0** | 9 |
| Qué es EcoFlow (4257) | 78 | 11 (solo categorías) | 10 + guías |

Criterio: las páginas con muchas impresiones y mala posición son las que
necesitan enlaces. Por eso ahora reciben `/paneles-solares-portatiles/`
(9.394 impresiones, posición 16,29), `/kits-para-balcones/` (6.734, posición
12,11) y `/man/` (19.583). Y se enlaza `/pago-fraccionado/`, que tiene el mejor
CTR del sitio con diferencia: **10,1 %**.

### Dos entradas nuevas, en borrador

| id | Slug | Por qué |
|---|---|---|
| **8416** | `baliza-v16-obligatoria-2026-dgt` | La V16 es obligatoria desde enero de 2026 y la vendéis, pero la ficha tiene **68 impresiones**: es invisible. Este post ataca la demanda informativa, que es donde está el volumen |
| **8417** | `generador-solar-o-gasolina-cual-elegir` | Vuestra página `/generador-solar/` tiene 2.677 impresiones en posición 12,42 y ningún contenido que la sostenga |

**Están en borrador a propósito**, no por dejarlo a medias: el post de la baliza
hace afirmaciones normativas y ese tipo de contenido conviene que lo leas antes
de publicarlo. Los datos están contrastados contra notas de la propia DGT y de
la AEPD (obligación desde el 1/1/2026, exención de motos y cuadriciclos ligeros,
GPS y SIM no extraíble sin necesidad de app, y datos anonimizados sin registro
de velocidad ni matrícula), pero la última palabra es tuya.

El del generador lleva el cálculo de amortización **con la fórmula a la vista** y
dice claramente que, si tu única motivación es ahorrar, el cálculo no es
demoledor. Se compra por el silencio y por poder usarlo dentro de casa.

Para publicarlos: `/wp-admin/post.php?post=8416&action=edit` y `...post=8417...`.

### Cosas que encontré y no toqué

- **`/man/` no tiene H1.** Es la tercera página del sitio (19.583 impresiones,
  330 clics). Su título y su meta ya están bien, pero le falta el H1. Se arregla
  en Elementor.
- **Cinco categorías de demo del tema** sin usar: *Beauty, Fashion, Shopping,
  Sweaters, Trends*, todas con 0 entradas. Generan archivos vacíos y están en el
  sitemap de categorías. No las borro porque pediste no tocar categorías, pero
  son basura de la plantilla.
- **Ocho entradas en «Uncategorized»**, entre ellas varias de las que no se ven
  desde `/blog/`.
- **«ESTACIÓNES DE ENERGÍA»** está mal escrito (sobra la tilde).

---

## 1. Qué acceso hubo en esta sesión

| Vía | Estado |
|---|---|
| Lectura de la web (front, sitemaps, Store API de WooCommerce) | ✅ |
| Hojas de especificaciones oficiales de SpaceX | ✅ |
| Search Console | ✅ vía CSV aportado por David (16 meses) |
| **wp-admin** | ✅ **dentro, como David, administrador** |
| Guardar en Herramientas → Head & Footer Code | ❌ **403 del WAF** (ver 7 bis) |
| Navegador automatizado (Playwright) | ❌ no puede usar el proxy del entorno |
| Escritura automatizada en formularios del panel | ❌ bloqueada por el entorno |

Dos cosas frenaron la publicación, y conviene no confundirlas:

**Una es del servidor.** El formulario de Head & Footer Code devuelve 403 al
guardar, y no por lo que se añada: es el contenido que **ya está guardado** el
que dispara la regla del WAF. Está detallado en la sección **7 bis**, y le pasa
igual a quien lo intente a mano desde el navegador.

**La otra es del entorno de trabajo.** La capa de seguridad de esta sesión
bloquea que scripts míos rellenen y envíen formularios del panel de una web en
producción. Es una protección del entorno, no de la web, y no se debe rodear.
Para escribir en WordPress desde aquí hace falta o que David lo autorice
explícitamente en los permisos de la sesión, o una **contraseña de aplicación**
(Usuarios → Perfil → Contraseñas de aplicación) para trabajar contra la API
REST en lugar de contra los formularios del panel.

> **Aviso de seguridad.** Para un administrador de una tienda WooCommerce con
> pagos reales hace falta contraseña larga y doble factor: un administrador
> comprometido aquí es acceso a los pedidos y a los datos de clientes. Y las
> contraseñas conviene no pasarlas por chat. Una contraseña de aplicación es
> mejor idea: se revoca en un clic y no da acceso al panel.

Ninguna de las dos impidió el trabajo al final: con la **contraseña de
aplicación** se pudo ir por la API REST, que esquiva los dos problemas. Lo
aplicado está en la sección **0**. Lo que sigue sin hacer es lo que **solo**
se puede tocar por formulario: el CSS del blog (por el 403 del WAF) y el ajuste
de noindex de etiquetas en Yoast.

---

## 2. El blog no tiene 9 entradas. Tiene 22.

El sitemap (`/post-sitemap.xml`) devuelve **22 entradas publicadas**. El README
anterior trabajaba sobre 9. Las 13 restantes no estaban ni identificadas.

Y hay un problema estructural encima: **`/blog/` solo muestra 12 entradas y no
tiene paginación**. No es un archivo de WordPress, es una *página* (ID 3230) que
pinta las entradas con el widget de Elementor, con el límite puesto en 12. Las
otras **10 entradas no son alcanzables navegando**: solo existen en el sitemap.
Para Google eso son páginas huérfanas, sin enlaces internos entrantes.

**Arreglar el listado de `/blog/` es más urgente que reescribir el post 7.**
Se hace en Elementor: editar la página, el widget de entradas, y o subir el
límite o activar la paginación.

### Estado de las 22, antes y después de esta sesión

| Situación | Antes | Ahora |
|---|---|---|
| Autor «ECOFLOW ESPAÑA» | 22 de 22 | ✅ **0** (todas firman EcoGadget) |
| Sin meta description | 15 de 22 | ✅ **9 de 22** |
| Título fuera del rango 50-60 car. | 19 de 22 | ✅ **9 de 22** |
| Entradas con etiquetas | 6 (hasta 22 cada una) | ✅ **0** |
| **No alcanzables desde `/blog/`** | 10 | ⚠️ **10 — sigue igual** |

Las 9 que siguen sin título ni meta propios son las que tienen **0 clics** en 16
meses (ver sección 0). No se tocaron a propósito.

Los títulos actuales van de 97 a **167 caracteres**. Casi todos son el patrón de
relleno de Yoast: cuando el Título SEO se deja vacío, Yoast añade
`- Distribuidor EcoFlow España | Tienda Física y Servicio Técnico`, que son 62
caracteres, y Google parte el título. **Escribe siempre el título propio.**

---

## 3. Lo que ya estaba hecho (y el README anterior daba por pendiente)

Esto ahorra trabajo: tres de las cuatro prioridades del README anterior **ya
están resueltas en la web**.

| Tarea del README anterior | Estado real |
|---|---|
| «Título del post 2 mide 131 car., Google lo parte» | ✅ **Ya arreglado.** Renderiza a 53 car.: *EcoFlow para Starlink: qué estación de energía elegir*. Meta puesta (137 car.) |
| «Publicar el post 1 reescrito» | ✅ **Ya publicado.** Título 52 car., meta 140 car., y lleva el bloque `eg-enlazado` con enlaces a las cuatro gamas. `lastmod` de hoy |
| «Título de `/paneles-solares-portatiles/`» | ✅ Ya optimizado (54 car. + meta de 129) |
| «Título de `/producto/baliza-v16-homologada-dgt-3-0/`» | ✅ Ya optimizado (61 car. + meta de 147) |
| «Título de `/producto/inversor-ecoflow-powerstream/`» | ❌ **Sigue pendiente.** Título de 101 car. (plantilla de Yoast) y **sin meta** |
| «Cambiar slug de `/nueva-entrada-blog/`» | ❌ Pendiente. Título de 104 car., sin meta, sin un solo H2 |

---

## 4. Search Console real: el README anterior se equivocaba

Datos de la exportación de David, **últimos 16 meses**, 1.000 páginas:
4.816 clics y 342.392 impresiones.

### 4.1. El post 1 no es el 12 % del tráfico

Eso era una estimación de Semrush. El dato real:

| | Clics | Impresiones | CTR | Posición |
|---|---|---|---|---|
| `/ecoflow-soluciones-portatiles…/` | **78** | **16.070** | **0,49 %** | 7,55 |

Son el 1,6 % de los clics del sitio, no el 12 %. **Pero la conclusión práctica
es aún mejor de lo que decía el README anterior**, por otro motivo: es la página
del blog con **más impresiones de largo**, y convierte un ridículo 0,49 %. En
posición 7,55 lo esperable son 2-3 %. Solo con que el CTR suba a lo normal,
pasa de 78 a 300-400 clics sin ganar una sola posición.

El título y la meta ya se cambiaron (hoy). **Hay que medir dentro de 3-4 semanas**
si el CTR se mueve. Ese es el experimento más valioso del blog.

### 4.2. Las «3 páginas con cero clics» estaban mal diagnosticadas

| Página | Lo que decía el README | Lo real (16 meses) | Diagnóstico correcto |
|---|---|---|---|
| `/producto/inversor-ecoflow-powerstream/` | «16 keywords, 2 visitas» | **131 clics**, 3.818 imp, CTR 3,43 %, pos 11,29 | Es la **mejor ficha de producto del sitio** y la 5.ª página por clics. Y tiene el título vacío en Yoast. **Máxima prioridad** |
| `/paneles-solares-portatiles/` | «24 keywords, 3 visitas → reescribir título» | 28 clics, 9.394 imp, CTR 0,30 %, **pos 16,29** | **No es problema de título, es de ranking.** Está en página 2. Reescribir el título no hará nada. Necesita contenido y enlaces |
| `/producto/baliza-v16-homologada-dgt-3-0/` | «23 keywords, 0 clics, la más sangrante» | 0 clics, **68 impresiones**, pos 27,65 | No es que no la pulsen: **es que no la ve nadie**. 68 impresiones en 16 meses. No hay nada que rascar en el título |

La regla que faltaba: **posición ≤ 10 → problema de título y meta. Posición > 10
→ problema de ranking.** Reescribir títulos de páginas en posición 16 o 27 es
tiempo tirado.

### 4.3. Las 9 URLs del README, con clics reales

| # | URL | Clics | Impr. | CTR | Pos. |
|---|---|---|---|---|---|
| 1 | `/ecoflow-soluciones-portatiles…/` | 78 | **16.070** | 0,49 % | 7,55 |
| 2 | `/ecoflow-para-starlink-y-ordenador…/` | **85** | 6.323 | 1,34 % | 5,75 |
| 8 | `/ecoflow-delta-2-…-capacidad-ampliable/` | 19 | **4.736** | 0,40 % | 8,96 |
| 4 | `/camperizacion-de-una-furgoneta-ecoflow/` | 13 | 1.036 | 1,25 % | 8,10 |
| 7 | `/ecoflow-river-la-estacion-…-ideal/` | 10 | 846 | 1,18 % | 9,10 |
| 3 | `/instalacion-power-kits-independence-ecoflow/` | 10 | 439 | 2,28 % | 17,54 |
| 5 | `/como-calcular-cuantos-paneles-solares…/` | 4 | 369 | 1,08 % | 8,33 |
| 9 | `/nueva-entrada-blog/` | 1 | 151 | 0,66 % | 33,09 |
| 6 | `/paneles-solares-portatiles-…-aventuras/` | **0** | 367 | 0,00 % | 25,10 |

### 4.4. Dos páginas que el README ni mencionaba y que importan más que 6 de las 9

| Página | Clics | Impr. | CTR | Pos. |
|---|---|---|---|---|
| **`/man/`** (manuales en PDF) | **330** | **19.583** | 1,69 % | 7,80 |
| **`/comparacion-entre-las-baterias-ecoflow-river-y-delta/`** | **54** | 3.482 | 1,55 % | 7,32 |

`/man/` es la **tercera página del sitio por clics**, con 19.583 impresiones y
posición 7,80. Nadie la ha tocado nunca. Es la mayor oportunidad de CTR de toda
la web después de la home.

Y `comparacion-entre-las-baterias-ecoflow-river-y-delta` es el **tercer post del
blog por clics**, y no estaba en la lista de 9. Hay que incluirlo.

### 4.5. Un problema técnico que sale en los datos

`http://ecogadgetoficial.com/` (sin S) acumula **512 clics y 49.601 impresiones**
como URL propia, separada de la versión https. Son impresiones que se están
contando aparte en lugar de sumar a la versión buena. Hay que comprobar que el
301 de http a https está bien puesto y es directo, sin saltos intermedios.

### 4.6. Móvil

2.844 clics de móvil (62 %) frente a 1.778 de escritorio. Pero el CTR de móvil
es **peor** (1,46 % contra 1,68 %) aun con **mejor** posición media (7,83 contra
11,84). En móvil el título se corta antes: es un argumento más para bajar de 60
caracteres y poner lo importante al principio.

---

## 5. El problema más grave: casi todo lo que enlazan los posts está agotado

Verificado con la **Store API de WooCommerce** (`/wp-json/wc/store/v1/products`),
que es la fuente que usa el propio JavaScript del sitio, no el filtro del admin.

**Del catálogo: 158 productos, solo 75 comprables (47 %).**

**De los 17 productos que enlazaban los 9 posts: 16 estaban AGOTADOS.**
El único comprable era el panel rígido de 175 W.

| Producto enlazado | Estado | Sustituto comprable |
|---|---|---|
| DELTA 2 (1.024 Wh) | ❌ agotado | **DELTA 3 Classic 1.024 Wh — 599 €** (misma capacidad) |
| DELTA 2 Max (2.048 Wh) | ❌ agotado | DELTA Max Ultra 3.072 Wh — 2.199 € |
| DELTA Pro / DELTA Pro 3 | ❌ agotados | DELTA Max Ultra — 2.199 € |
| DELTA 3 Max + panel 400 W | ❌ agotado | DELTA 3 Plus — 849 € |
| RIVER 2 (256 Wh) | ❌ agotado | **RIVER 3 245 Wh — 259 €** |
| RIVER 2 Pro (768 Wh) | ❌ agotado | RIVER 3 Max Plus — 549 € *(ver aviso abajo)* |
| RIVER 2 Max / RIVER 3 Max | ❌ agotados | RIVER 3 Max Plus — 549 € |
| RIVER 3 Plus (286 Wh) | ❌ agotado | RIVER 3 — 259 € |
| Inversor PowerStream | ❌ agotado | STREAM CA Pro — 699 € · o la categoría STREAM |
| Batería adicional DELTA 2 Max | ❌ agotado | *(sin equivalente: enlazar a categoría)* |
| Panel rígido 520 W ×2 | ❌ agotado | Panel rígido 100 W ×2 — 179 € |
| Cable paralelo paneles | ❌ agotado | Cable solar XT60/XT60i 2,5 m — 25 € |
| Baliza V16 | ❌ agotado | — |
| Panel rígido 175 W | ✅ **comprable** | se mantiene |
| Panel portátil 400 W | ✅ comprable | **el enlace estaba roto**, ver abajo |

**Enlace roto encontrado y corregido en los 9 archivos:**
`/producto/panel-solar-portatil-ecoflow-de-400-w/` no existe. El slug real es
`/producto/panel-solar-portatil-ecoflow-de-400w/` (sin guion, 649 €, en stock).
Funcionaba solo porque WordPress lo adivinaba y redirigía.

**Segundo enlace roto corregido:** `/serie-delta-ecoflow/serie-delta-3/` devuelve
**404**. Estaba en el bloque de gamas del README anterior, o sea que se habría
copiado a los 9 posts. El correcto es `/product-category/serie-delta/`.

**Tercero:** `href="/paneles-solares"` redirige a `/paneles-solares-portatiles/`.
Cambiado al destino directo para no gastar un salto.

> Esto es lo que más dinero cuesta ahora mismo: **131 clics en 16 meses cayendo
> en la ficha del PowerStream, que está agotada.** Antes de reescribir un solo
> post, hay que decidir qué se repone y qué se redirige.

---

## 6. Datos verificados (la lista de «verificar antes de publicar»)

| Qué había que verificar | Resultado |
|---|---|
| **Consumo del Starlink Standard** | ❌ **El borrador estaba mal.** Decía 45-75 W. La hoja oficial de SpaceX dice **75-100 W** de media. El Mini, **25-40 W** (el borrador decía 20-40). Corregido y **recalculada toda la tabla de autonomía del post 2** |
| **Módulos de batería del Power Kit** | ✅ *Batería LFP EcoFlow*, producto variable con **2 kWh y 5 kWh**, apilable hasta 6 kWh (con las de 2) o 15 kWh (con las de 5). Desde **1.748 €**, en stock. Incluye soporte de montaje y cable |
| **Panel de distribución del Power Kit** | ✅ *Panel de distribución CA/CC inteligente*, 599 €, en stock |
| **RIVER 3 Max Plus** | ⚠️ **La ficha se contradice.** La descripción larga dice **768 Wh y hasta 1.600 W**; la corta dice «ampliable hasta 858 Wh», 600 W nominales y X-Boost 1.200 W. No se puede saber cuál vale, así que **sigue fuera de la tabla**. Precio 549 €, en stock. **Hay que arreglar la ficha** |
| **1.039 kWh/año y 415 € de ahorro** | ✅ **Siguen vigentes** en `/kits-para-balcones/`. Matiz importante: la página dice «**hasta** 1039 kWh al año» y «ahorrar un **máximo** de 415 €». Hay que mantener el «hasta» y el «máximo», no convertirlo en cifra plana |
| **Stock real de la DELTA 2** | ❌ **Agotada.** Como decía el plan, los enlaces van a la **DELTA 3 Classic** (599 €, mismos 1.024 Wh) y a la categoría |
| **DELTA Max Ultra** | ✅ 3.072 Wh, 3.600 W, 2.199 €, en stock |
| **DELTA 3 Classic** | ✅ 1.024 Wh, 1.800 W nominales, 3.600 W pico, X-Boost 2.600 W, LiFePO4, 599 € |
| **RIVER 3** | ✅ 245 Wh, 300 W nominales, 600 W con X-Boost, 259 € |

Fuentes de los consumos de Starlink, descargadas y leídas directamente:
`starlink.com/public-files/specification_sheet_standard.pdf` → *«Average: 75 - 100 W»*
`starlink.com/public-files/specification_sheet_mini.pdf` → *«Power Consumption Average: 25-40W»*

---

## 7. Diseño del blog: CSS ya verificado contra el DOM

`diseno-blog.css` está reescrito. **Ya no queda ningún selector a ciegas.**
Va dentro del bloque `<style id="eg-pdp-css">` que ya existe en
Herramientas → Head & Footer Code → Código del pie de página, **al final**.
No crear un `<style>` nuevo: el WAF bloquea guardar etiquetas nuevas ahí.

Lo que se encontró al comparar con el DOM real:

**Un bug activo, no solo reglas muertas.** La versión anterior aplicaba
`clamp(2rem, 4.5vw, 2.9rem)`, `text-align:center` y `max-width:20ch` a
`.single-post .post-title`, creyendo que era el H1. **No lo es**: `.post-title`
es el título de los **posts relacionados** del pie. Esa regla los habría dejado
gigantes y centrados. El H1 real es `.entry-title`. Eliminado.

**Selectores que no existen en esta web** (sus reglas no hacían nada):
`.post-content`, `.entry-meta`, `.entry-excerpt`, `.entry-summary`,
`.blog-wrapper`, `.tags-links`, `.post-tags`, `.entry-tags`,
`.post-categories-tags`.

**Toda la sección del listado estaba mal enfocada.** Se escribió con `.blog` y
`.archive`, pero `/blog/` es una **página** (`body.page.page-id-3230`), no un
archivo, así que esas reglas no se aplican nunca. Reescrita con las clases
reales de Elementor: `.elementor-post__card`, `__title`, `__excerpt`,
`__meta-data`.

**Estructura real del post, ya confirmada:**

```
body.single-post
  article.entry-wrapper
    .entry-header > h1.entry-title
    .entry-post-meta > .entry-post-meta__inner
    .entry-post-feature.post-thumbnail
    .entry-content            ← aquí va el contenido
    .entry-footer > .entry-post-tags > .tagcloud   ← las etiquetas
```

Después de guardar: **LiteSpeed Cache → Purgar todo.**

---

## 7 bis. AVISO GORDO: el formulario de Head & Footer Code no se puede guardar

Descubierto el 15/08/2026 entrando en el admin. **Esto te afecta también si lo
haces a mano**, así que léelo antes de intentar pegar el CSS.

**El CSS de las fichas vive en:** Herramientas → Head & Footer Code, campo
*Código del pie de página* (`auhfc_settings_sitewide[footer]`). Son 61.317
caracteres, todo un único bloque de estilos con el id `eg-pdp-css`.

Ojo, que la ruta de la skill estaba incompleta: la URL real es
`/wp-admin/tools.php?page=head-footer-code` **con guiones**. Con guiones bajos
(`head_footer_code`) da 403 de WordPress y parece un problema de permisos, pero
es que esa página no existe.

**El problema:** al guardar ese formulario, el servidor devuelve **403 Forbidden**
(el del servidor, tipo Apache, no el de WordPress). Y no es por lo que se añada:
se probó a reenviar **el contenido actual sin tocar una coma** y también da 403.

Comprobado con sondas contra `admin-ajax.php` (sin escribir nada):

| Contenido enviado | Respuesta |
|---|---|
| Texto inocuo | 400 *(pasa el WAF)* |
| **El CSS nuevo del blog entero** | **400** *(pasa el WAF)* |
| Una etiqueta de script suelta | 400 *(pasa el WAF)* |
| **El campo «cabecera» actual** (lleva el gtag de Google) | **403 — bloqueado** |
| **El campo «pie» actual** (el CSS de fichas) | **403 — bloqueado** |

O sea: **el CSS nuevo no es el problema, lo es el contenido que ya está
guardado.** Alguna regla del WAF del hosting cambió después de la última vez que
se guardó, y desde entonces ese formulario quedó de solo lectura. Cualquiera que
pulse «Guardar cambios» ahí se va a comer un 403 y va a perder lo que escriba.

**Qué hacer, por orden de preferencia:**

1. **Meter el CSS del blog en otro sitio.** No hace falta que esté en el mismo
   bloque: es CSS independiente y no colisiona con nada (comprobado, los 419
   selectores actuales son todos `.single-product`; los 45 nuevos son
   `.single-post` y `.page-id-3230`). Sitios buenos, los dos ya instalados:
   - **Apariencia → Personalizar → CSS adicional.** Es el sitio natural para
     esto y no pasa por el formulario roto.
   - **WPCode** (Insert Headers and Footers), que está activo y con los tres
     campos vacíos.
   - **Opciones de Tema → `custom_css`**, que ahora tiene 53 caracteres.
     Guarda por AJAX de Redux, que es otra ruta.
2. **Pedir al hosting que revise la regla del WAF**, porque mientras siga así no
   se puede tocar ni el gtag de Google ni el CSS de las fichas. Eso es un
   problema serio a medio plazo, más que el diseño del blog.

Mientras tanto el CSS de las fichas **sigue funcionando**: está guardado y se
sirve. Lo que no se puede es modificarlo.

---

## 8. Etiquetas: fuera

Hay entradas con **59 etiquetas** colgando. Sus archivos generan páginas casi
vacías que se comen presupuesto de rastreo, y esta web ya tiene ese problema
en grande.

Las tres cosas, no solo la primera:

1. **Ocultarlas** → ya está en `diseno-blog.css`, sección 8, ahora con el
   selector correcto (`.single-post .entry-post-tags`).
2. **Borrarlas** → Entradas → Etiquetas → seleccionar todas → Eliminar.
3. **Noindex** → Yoast → Ajustes → Taxonomías → Etiquetas → «Mostrar en
   resultados de búsqueda»: **No**, y fuera del sitemap.

El paso 3 importa aunque borres, porque evita que vuelva a pasar.
Comprobado: `post_tag-sitemap.xml` sigue existiendo en el índice de sitemaps.

**No toques las categorías.**

---

## 9. Autor: EcoGadget, no EcoFlow

Confirmado: **las 22 entradas van firmadas como «ECOFLOW ESPAÑA»**, que es el
fabricante. Un análisis de una DELTA firmado por quien la fabrica se lee como
publicidad; firmado por el distribuidor con tienda física y servicio técnico, se
lee como criterio.

1. Usuarios → nombre público **EcoGadget**, y **rellenar la biografía**
   (distribuidor oficial, tienda física, servicio técnico oficial, dos o tres
   líneas con enlace a la home). Ahora está vacía y esa caja es señal de autoría.
2. Entradas → seleccionar **las 22** → Acciones en lote → Editar → Autor: EcoGadget.

---

## 10. Fechas

Primero el contenido, después la fecha. Cambiar la fecha sin cambiar el texto no
funciona y Google lo detecta comparando entre rastreos.

Aquí sí procede porque el contenido cambia de verdad. Secuencia: pegar contenido
→ título y meta → **después** la fecha → guardar → pedir indexación en Search
Console.

Escalonar en dos o tres semanas. Nueve entradas con la misma fecha se ve raro.

---

## 11. Orden de trabajo corregido

El del README anterior se hizo con estimaciones. Este va con clics reales.

| Orden | Qué | Por qué, con el dato |
|---|---|---|
| 1.º | **Título + meta de `/producto/inversor-ecoflow-powerstream/`** | 131 clics y 3.818 impresiones con el título de relleno de Yoast (101 car.) y **sin meta**. La mejor ficha del sitio, abandonada |
| 2.º | **Decidir qué se repone y qué se redirige** | 16 de 17 productos enlazados agotados. 131 clics cayendo en una ficha que no se puede comprar |
| 3.º | **Arreglar el listado de `/blog/`** | 10 de 22 entradas no son alcanzables navegando |
| 4.º | **`/man/`: título y meta** | 19.583 impresiones, 330 clics, posición 7,80. Tercera página del sitio y nadie la ha tocado |
| 5.º | **Post 8 (DELTA 2)** | 4.736 impresiones con CTR 0,40 % en posición 8,96. Segunda mayor oportunidad del blog. Además el producto está agotado |
| 6.º | **Post 2 (contenido, no título)** | El título ya está bien. Pero el consumo de Starlink estaba mal y era el dato que sostenía el artículo. Ya corregido en el archivo |
| 7.º | **Medir el post 1** | Título y meta cambiados hoy. Ver en 3-4 semanas si el 0,49 % se mueve |
| 8.º | Autor, etiquetas y noindex de etiquetas | Barrido único sobre las 22 |
| 9.º | Slug de `/nueva-entrada-blog/` + 301 | Correcto y barato, pero son 151 impresiones y 1 clic: **no es la prioridad que decía el README anterior** |
| 10.º | `/paneles-solares-portatiles/` | Posición 16,29: necesita contenido y enlaces, **no** un título nuevo |
| 11.º | Los demás posts | Base para crecer |

---

## 12. Títulos y metas propuestos

Para los que siguen pendientes. Los de los posts 1 y 2 ya están puestos en la web
y **no hay que tocarlos**.

| # | Título SEO | Car. |
|---|---|---|
| 3 | `Power Kits EcoFlow: instalación paso a paso en casa` | 51 |
| 4 | `Instalación eléctrica de una camper: guía y equipos` | 51 |
| 5 | `Cuántas placas solares necesita tu casa: cálculo real` | 53 |
| 6 | `Panel solar portátil: cuál comprar y cuánto carga` | 49 |
| 7 | `EcoFlow RIVER: qué modelo elegir y qué aguanta` | 46 |
| 8 | `EcoFlow DELTA 3 Classic 1024Wh: autonomía real` | 46 |
| 9 | `Placas solares en un piso: sí se puede, así se hace` | 51 |

El del 8 cambia respecto al plan anterior: **la DELTA 2 está agotada**, así que
titular por un producto que no se puede comprar es tirar el clic.

Metas (135-155 caracteres):

3. `Cómo se instala un Power Kit Independence de EcoFlow: qué necesitas, en qué orden se monta, qué hace un electricista y cuánto cuesta la obra.` (139)
4. `Cuánta batería y cuántas placas necesita tu furgoneta camper, con consumos reales aparato por aparato y tres montajes cerrados según presupuesto.` (143)
5. `Calcula cuántos paneles solares necesitas con tu factura de la luz, paso a paso y con un ejemplo resuelto. Incluye qué cambia si añades batería.` (142)
6. `Qué panel solar portátil elegir según lo que quieras cargar, cuánta energía produce de verdad al día en España y cuándo conviene uno rígido.` (138)
7. `Comparativa de la serie RIVER de EcoFlow: capacidad, potencia y autonomía real de cada modelo, con la tabla de qué puedes enchufar en cada uno.` (141)
8. `Qué aguanta de verdad una estación de 1024 Wh, cuánto tarda en cargar y cuándo compensa subir de gama. Con la tabla de autonomía por aparato.` (139)
9. `Cómo poner placas solares en un piso sin tejado propio: kits de balcón, cuánto ahorran de verdad al año y qué dice la normativa en España.` (136)

---

## 13. Bloque de gamas (corregido)

El del README anterior llevaba una URL que da 404. Este está verificado, las
cuatro responden 200:

```html
<div class="eg-gamas">
  <a href="/product-category/river-3/"><b>Serie RIVER</b><span>245–768 Wh. Camping, portátiles y apagones cortos.</span></a>
  <a href="/product-category/serie-delta/"><b>Serie DELTA</b><span>1–3 kWh. Casa, camper y electrodomésticos.</span></a>
  <a href="/product-category/stream-series/"><b>Serie STREAM</b><span>Autoconsumo enchufable para bajar la factura.</span></a>
  <a href="/paneles-solares-portatiles/"><b>Paneles solares</b><span>Portátiles y rígidos para no depender del enchufe.</span></a>
</div>
```

---

## 14. Qué falta por hacer en los archivos

Sinceridad sobre el estado de esta carpeta:

- **`02-ecoflow-starlink-ordenador.html`** → ✅ reescrito entero con el consumo
  correcto, la tabla recalculada y solo productos comprables.
- **`diseno-blog.css`** → ✅ reescrito con selectores verificados.
- **Los 9 archivos** → ✅ corregidos los tres enlaces rotos.
- **`01`, `03`–`09`** → ⚠️ **siguen enlazando productos agotados** en sus tablas
  y textos. No se han reescrito en bloque a propósito: cambiar el enlace sin
  cambiar el modelo y la capacidad que hay al lado deja el texto incoherente
  («DELTA 2 · 1.024 Wh» apuntando a una DELTA 3). Cada uno necesita una pasada
  con la tabla de sustituciones de la sección 5.

Esa pasada es el siguiente trabajo, y es rápido con la tabla ya hecha.

---

## 15. Límites de la casa, respetados

- Ni una reseña ni una pregunta de cliente inventada.
- Nada de escasez falsa.
- Ninguna mención a «envío gratis» (todos los productos citados están por debajo
  de 2.000 €).
- Las estimaciones van en `eg-note` **con la fórmula a la vista**
  (`horas = capacidad × 0,85 ÷ consumo`).
- Objeción honesta en cada post. En el 2, ahora más fuerte y basada en el dato
  corregido: **ninguna estación portátil del catálogo aguanta Starlink Standard
  más ordenador 24 horas seguidas**, y **un solo panel de 400 W no cubre el
  consumo diario del Standard**, que es lo contrario de lo que se suele leer.
- Sobre garantía, sin cambios: se tramita para compras en la web y se repara
  cualquier equipo como servicio técnico oficial, de pago y con presupuesto.
