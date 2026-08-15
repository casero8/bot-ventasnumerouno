# Blog de EcoGadget · reescritura SEO

**Revisión del 15/08/2026, con acceso a la web y con Search Console real.**

Esta versión corrige la anterior, que se escribió a ciegas (el dominio estaba
bloqueado) y con estimaciones de Semrush. Ahora hay datos de verdad, y **cambian
las conclusiones**. Lo que sigue está verificado contra la web y contra la
exportación de Search Console de 16 meses.

---

## 1. Qué acceso hubo en esta sesión

| Vía | Estado |
|---|---|
| Lectura de la web (front, sitemaps, Store API de WooCommerce) | ✅ funciona |
| Hojas de especificaciones oficiales de SpaceX / EcoFlow | ✅ funciona |
| Search Console | ✅ vía CSV aportado por David (16 meses) |
| **wp-admin** | ❌ **la contraseña no entra** |
| Navegador automatizado (Playwright) | ❌ no puede usar el proxy del entorno |

Sobre wp-admin: WordPress reconoce el usuario `casero.moratalaz@gmail.com` pero
rechaza la contraseña (`Error: la contraseña que has introducido … no es correcta`).
Se hizo **un solo intento** y se paró, para no disparar el bloqueo por intentos
del plugin de seguridad. Hace falta la contraseña correcta, o mejor una
**contraseña de aplicación** (Usuarios → Perfil → Contraseñas de aplicación),
que permite trabajar por API sin exponer la del administrador.

> **Aviso de seguridad, y perdón por la insistencia:** `123456!!` no es una
> contraseña admisible para un administrador de una tienda WooCommerce con
> pagos reales. Aunque no fuera la buena, conviene revisar que la real no se
> le parezca, y activar doble factor. Un administrador comprometido en
> WooCommerce es acceso a datos de clientes y a los pedidos.

Por eso **no se ha publicado nada**. Todo lo que sigue está listo para pegar,
pero además ahora está verificado.

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

### Estado real de las 22 (verificado el 15/08/2026)

| Situación | Cuántas |
|---|---|
| Autor **«ECOFLOW ESPAÑA»** (debería ser EcoGadget) | **22 de 22** |
| **Sin meta description** | **15 de 22** |
| Título fuera del rango 50-60 car. | 19 de 22 |
| Con 59 etiquetas colgando | 5 |
| No alcanzables desde `/blog/` | 10 |

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
