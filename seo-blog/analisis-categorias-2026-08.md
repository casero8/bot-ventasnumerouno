# Análisis de la estructura de categorías · 16/08/2026

158 productos publicados, **60 categorías de producto** y **39 páginas** que
en buena parte cubren lo mismo. Datos de Search Console de 16 meses.

---

## 1. El problema de fondo: hay dos estructuras compitiendo

Para casi cada tema hay **una página y una categoría**. Google elige una de
las dos sin criterio aparente, y la que pierde se queda a cero:

| Tema | Página | Impr. | Categoría | Impr. |
|---|---|---:|---|---:|
| Paneles solares | `/paneles-solares-portatiles/` | **9.394** | `/paneles-solares/` | 269 |
| Placas solares | `/placas-solares-ecoflow/` | **1.614** | `/paneles-solares/` | 269 |
| Accesorios | `/accesorios/` | **1.148** | `/accesorios/` | **1.135** |
| STREAM | `/stream-series/` | 222 | `/stream-series/` | **1.288** |
| Serie RIVER | `/serie-river/` | **763** | `/serie-river/` | 0 |
| Serie DELTA | `/serie-delta-ecoflow/` | **278** | `/serie-delta/` | 0 |
| Generadores | `/generadores/` | **313** | — | — |

El caso de **Accesorios** es canibalización de manual: 1.148 contra 1.135.
Dos URLs partiéndose la misma consulta y ninguna de las dos gana.

Y el menú mezcla las dos estructuras: enlaza a **5 categorías** y a
**8 páginas**. Quien navega no puede saber cuál es el sitio "de verdad".

---

## 2. Las categorías que Google ya premia son las que están vacías

Ordenadas por impresiones, con lo que tienen dentro:

| Categoría | Impr. | Clics | Productos | Con stock |
|---|---:|---:|---:|---:|
| `/bateria-adicional-delta-pro/` | **2.271** | 25 | 1 | **0** |
| `/delta-3/accesorios-delta-3/` | 1.704 | **42** | 4 | 1 |
| `/delta-3-max-plus/` | 1.475 | 12 | 6 | **0** |
| `/delta-3/bateria-adicional-delta-3/` | 1.437 | 21 | 2 | **0** |
| `/stream-series/` | 1.288 | 11 | 6 | 1 |
| `/accesorios/` | 1.135 | 13 | 35 | 27 |
| `/delta-2-max/accesorios-delta-2-max/` | 952 | 26 | 15 | 6 |
| `/delta-3/` | 669 | 3 | 11 | 3 |

La categoría con más impresiones de toda la tienda tiene **un producto y
está agotado**. La cuarta, dos productos y los dos agotados.

Encaja con las consultas: `bateria adicional delta 3` acumula 376
impresiones, `batería ecoflow delta 3 plus` 55, `bateria extra ecoflow
delta 3` 24. La demanda de baterías adicionales es de las más claras que
tiene el sitio.

---

## 3. Veintitrés categorías sin un solo producto comprable

De 60 categorías, **23 no tienen nada que se pueda comprar hoy**. Entre
ellas las principales de gama:

`DELTA 2` · `DELTA 2 Max` · `DELTA 3 Max` · `DELTA 3 Max Plus` ·
`DELTA PRO` · `Delta Pro 3` · `Power Kits` · `EcoFlow STREAM` ·
`EcoFloe Wave` · `Wave 3` · `RIVER 2` · `RIVER 2 Max` · `RIVER 2 Pro` ·
`Automoción` · `EcoFlow GLACIER Classic` · `DELTA PRO ULTRA` y las cinco
de baterías adicionales.

---

## 4. "Batería adicional" está partida en cinco categorías

Es el tema con más demanda del catálogo y está repartido en:

| Categoría | Ruta | Productos |
|---|---|---:|
| BATERIA ADICIONAL DELTA PRO | suelta, primer nivel | 1 |
| BATERÍAS ADICIONALES | suelta, primer nivel | 1 |
| BATERIA ADICIONAL DELTA 3 | bajo DELTA 3 | 2 |
| BATERIAS ADICIONALES DELTA 2 MAX | bajo DELTA 2 Max | 1 |
| Batería Adicional | bajo DELTA 3 Max Plus | 1 |

Más una página aparte, `/baterias-extras/`, con 25 impresiones. Seis URLs
para seis productos.

---

## 5. Once categorías de "Accesorios"

`Accesorios y cables` (35) · `Accesorios DELTA Pro` (22) ·
`Accesorios DELTA 2 Max` (15) · `Accesorios DELTA 2` (15) ·
`Accesorios RIVER 2` (10) · `Accesorios RIVER 2 Max` (9) ·
`Accesorios RIVER 2 Pro` (9) · `Accesorios Serie X` (6) ·
`ACCESORIOS DELTA 3` (4) · `Accesorios Power Kits` (4) ·
`Accesorios Rapid Series` (3) · `Accesorios Wave 3` (1)

Los números no suman 158 porque **el mismo cable está en seis categorías a
la vez**. El reparto de categorías por producto lo confirma:

| Categorías que tiene un producto | Productos |
|---:|---:|
| 1 | 79 |
| 2 | 54 |
| 3 | 11 |
| 4 | 5 |
| 5 a 9 | 9 |

Un producto está en **nueve** categorías. Eso multiplica las rutas por las
que se llega a la misma ficha y es parte de por qué el sitio genera tantas
URLs.

---

## 6. Errores concretos que hay que corregir

- **`EcoFloe Wave`** — está mal escrito: es EcoFlow. Y cuelga de ella
  `Wave 3`, y de esa `Accesorios Wave 3`. **Tres categorías y un producto**,
  agotado.
- **Dos categorías de STREAM**: `Batería para casa y balcón STREAM`
  (`/stream-series/`, 6 productos, 1.288 impresiones) y `EcoFlow STREAM`
  (`/ecoflow-stream/`, 2 productos, agotados).
- **RIVER 3 partido**: `RIVER 3 Plus` (3 productos) cuelga de la serie
  RIVER, mientras `RIVER 3` (1 producto) está suelta en el primer nivel.
  La RIVER 3 Max Plus, que es la que tiene stock, no tiene categoría propia.
- **`SIN CATEGORÍA`** sigue viva con un producto dentro (la cubierta del
  DELTA 2 Max).
- **Profundidad excesiva**: `Hypershell → Serie X → Max S` son tres niveles
  para llegar a **un** producto. Lo mismo con `Pro S` y `Ultra S`.
- **Nomenclatura inconsistente**: `DELTA PRO`, `Delta 3 Plus`,
  `DELTA 3 Max`, `Delta Max Ultra`. Mayúsculas, minúsculas y mezcla, a veces
  dentro de la misma gama.
- **Cada categoría vive en dos URLs**: `/product-category/delta-3/` y
  `/product-category/serie-delta/delta-3/` responden las dos 200. La
  canónica apunta a la anidada, pero la plana sigue existiendo.

---

## 7. Cincuenta y siete de sesenta categorías no tienen ni una línea de texto

Solo tres tienen descripción: `RAPID PRO`, `Power Kits` y
`Accesorios Power Kits`. Las demás son una rejilla de productos sin nada que
explique qué es esa gama, para quién es o en qué se diferencia de la de al
lado. Para Google son páginas sin contenido propio; para quien entra desde
una búsqueda, un catálogo sin contexto.

---

## 8. De dónde viene el tráfico hoy

| Tipo de URL | Impresiones | Clics | URLs | CTR |
|---|---:|---:|---:|---:|
| Páginas y entradas | 96.632 | 1.133 | 65 | 1,17 % |
| Portada | 91.473 | 1.806 | 1 | 1,97 % |
| Fichas de producto | 86.027 | 1.167 | 153 | 1,36 % |
| **Categorías** | **13.480** | **162** | 35 | 1,20 % |

Las categorías son el **3,9 %** de las impresiones. Hoy la tienda se
sostiene sobre la portada, las páginas de aterrizaje y las fichas. La
estructura de categorías, tal como está, casi no trabaja.

---

## 9. Resumen del diagnóstico

1. Dos estructuras paralelas —páginas y categorías— compitiendo por las
   mismas consultas, y un menú que mezcla las dos.
2. Las categorías mejor posicionadas son las que no tienen producto en
   stock, justo en el tema de más demanda: baterías adicionales.
3. 23 de 60 categorías no venden nada porque no tienen stock.
4. Un mismo tema repartido en cinco o seis categorías (baterías
   adicionales, accesorios, STREAM, RIVER 3).
5. Productos en hasta nueve categorías, lo que multiplica rutas y URLs.
6. Sin textos: 57 de 60 categorías están vacías de contenido.
