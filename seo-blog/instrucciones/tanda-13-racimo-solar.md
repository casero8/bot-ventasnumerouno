# Tanda 13 · el racimo solar · lo que ahora es el punto 1 de la auditoría

**9 de octubre de 2026.** Dos cosas antes de empezar.

**Confirmado por el dueño: siguen siendo distribuidor oficial de EcoFlow.** Solo han
perdido el servicio técnico. Así que **todo lo reescrito en los 23 archivos se queda tal
cual y no hay que rehacer nada.** «Distribuidor oficial de EcoFlow en España» es cierto y
es ahora el argumento principal del sitio.

**Y con el racimo de servicio técnico fuera del plan, éste pasa a ser el primero.** Es el
mayor hueco de clics que tiene la web.

---

## El tamaño del agujero

**165 consultas, 14.326 impresiones, 55 clics.** Posición media 18,9. Es la segunda
página de Google con casi todo.

Desglosado por intención, que es lo que dice qué hay que hacer:

| Racimo | Impresiones | Pos. media | Clics | Qué pasa |
|---|---:|---:|---:|---|
| **Genérico «portátil / plegable»** | 6.508 | 19,6 | **4** | Tres URLs se reparten la señal |
| **Marca: «ecoflow + solar»** | 5.622 | 11,6 | 46 | Lo único medio vivo |
| **Balcón / enchufable** | ~1.000 | 10–72 | 4 | **No hay página. Ninguna** |
| **Vatios concretos** | 1.182 | 21,4 | 7 | Sin página de aterrizaje |
| **Local (Rivas)** | ~400 | 16,4 | **0** | Su propio pueblo |

---

## Causa nº 1 · Tres URLs peleándose por la misma búsqueda

Esto ya estaba diagnosticado en `migracion-paginas-a-categorias.md` y **nunca se ejecutó.**
Los datos de esas tres URLs:

| URL | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `/paneles-solares-portatiles/` | **9.394** | 16,3 | 28 |
| `/placas-solares-ecoflow/` | 1.614 | 9,5 | 8 |
| Categoría `paneles-solares` | — | — | — |

**`/paneles-solares-portatiles/` es la segunda página del sitio por impresiones** y está en
el puesto 16. No es que no rankee: es que **la fuerza está partida en tres** y ninguna de
las tres llega a primera página.

### Qué hacer, y el orden importa

1. **Comprobar que la categoría `paneles-solares` está aplicada y completa.** El texto está
   escrito en `categorias/paneles-solares.html`. Si no está en la web, se aplica primero.
   **No se redirige nada hacia una categoría vacía.**
2. **301 de `/paneles-solares-portatiles/` → categoría `paneles-solares`.**
3. **301 de `/placas-solares-ecoflow/` → la misma categoría.**
4. **Actualizar los enlaces internos** que apuntaban a las dos páginas viejas, para que
   vayan directos y no por el salto (norma 9).
5. **Comprobar con `?nc=1`** que la categoría responde 200 y que ninguna URL interna
   devuelve 301.

**Qué esperar:** la consolidación no sube la posición de un día para otro —Google tarda
semanas en recalcular— pero es la única vía. Hoy se compite contra uno mismo.

**Y el título de la categoría es la mitad del trabajo.** Las consultas reales son `placa
solar portatil` (924), `panel solar portatil` (845), `placas solares portatiles` (471) y
`paneles solares portatiles` (367). El título SEO tiene que contener **«panel solar
portátil»** en singular y plural si cabe, no «paneles solares» a secas.

---

## Causa nº 2 · El balcón sí tiene página, y me equivoqué al decir que no

**Corrección mía, antes de que nadie actúe sobre la versión anterior de este parte.** Dije
que el racimo de balcón no tenía página. **La tiene: `/kits-para-balcones/`, y es una de
las más grandes del sitio** — 6.734 impresiones en posición 12,11, con 82 clics.

Lo que vi en las consultas —posiciones del 5,9 al 72,5— no era «no hay página». Era que
**las consultas de balcón se reparten entre esa página, la categoría `stream-series` y las
fichas**, que es la misma enfermedad de la causa nº 1 con otro nombre.

Y el plan de migración ya decidió, con criterio, que **esa página no se toca**: «es una
página de caso de uso, no una gama». Eso sigue siendo correcto. Una categoría enseña
productos; una página de caso de uso explica si te sirve para tu balcón y qué piezas
necesitas. No se duplican.

### Lo que hay que hacer entonces, que no es escribir una página nueva

1. **El título SEO y la meta de `/kits-para-balcones/`.** Está en posición 12 con 6.734
   impresiones: es exactamente el mismo problema de CTR que el resto de la auditoría, y se
   arregla en dos campos. Las consultas reales son `kit solar balcon`, `generador solar para
   balcones`, `kit solar balcón con batería`, `placa solar balcón`.
2. **Escribir la categoría `stream-series`** — pero **como gama de producto, no como página
   de balcón**. Es nivel 1 del plan (6-7 productos, 1.288 impresiones) y sigue sin texto.
   Su trabajo es enseñar el microinversor y la batería; el de convencer para un balcón es de
   `/kits-para-balcones/`. **Las dos se enlazan, no compiten.**
3. **Arreglar el desdoble de STREAM**, que es lo que parte la señal: hay **dos categorías**,
   `/stream-series/` (6 productos, 1.288 impresiones) y `/ecoflow-stream/` (2 productos,
   agotados). La segunda sobra: 301 a la primera. Está anotado en
   `analisis-categorias-2026-08.md` y nunca se hizo.

## Causa nº 3 · Rivas, con la nave en Rivas

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `placas solares rivas-vaciamadrid` | 256 | 12,6 | **0** |
| `instalacion placas solares rivas-vaciamadrid` | 56 | 22,2 | 0 |
| `placas solares en rivas-vaciamadrid` | 52 | 6,2 | 0 |
| `instalar placas solares rivas-vaciamadrid` | 40 | 24,8 | 0 |

Cuatrocientas impresiones de gente buscando placas solares **en el pueblo donde está la
nave**, y cero clics. Compáralo con `ecoflow madrid`, que en posición 1,6 saca un **23 % de
CTR**: el mejor número de todo el archivo.

Esto se arregla con dos cosas, y ninguna es contenido nuevo largo:

1. **La ficha de Google**, que ya está toda escrita en `google/`. El renombrado, la
   descripción y las categorías. El posicionamiento local depende más de la ficha que de la
   web.
2. **Una sección local de verdad** dentro de la categoría `paneles-solares`: que diga que
   hay tienda física en Rivas, que se puede ver el panel antes de comprarlo y que se puede
   recoger sin portes. **Y para eso hacen falta las dos fotos del taller, que sigo
   esperando desde hace semanas.**

**Tres avisos sobre lo local, y el primero ya está contestado por el dueño:**

- **NO se ofrece instalación. Solo venta de placas solares de EcoFlow.** Así que
  `instalacion placas solares rivas-vaciamadrid` (56 impr) e `instalar placas solares
  rivas-vaciamadrid` (40) **no se persiguen, y hay que asegurarse de no atraerlas sin
  querer**. Es el mismo error que acabamos de pagar con el servicio técnico: traer a alguien
  buscando un servicio que no se presta produce una reseña de una estrella, no una venta.
  Las que sí se trabajan son `placas solares rivas-vaciamadrid` (256) y `placas solares en
  rivas-vaciamadrid` (52), que son de compra.
- **Y hay que decirlo en la web, no solo evitarlo.** Una línea en la categoría
  `paneles-solares`: «Vendemos el panel; la instalación en tejado no la hacemos nosotros.»
  Dicho así ahorra la llamada, la decepción y la reseña. Es lo mismo que hace la tabla de
  «para quién no es» de Hypershell, y funciona por el mismo motivo.
- **Málaga no se persigue.** `paneles solares portatiles malaga` (350 impr, posición 33) y
  su variante (282, posición 53) son la misma búsqueda dos veces, y en posición 33 y 53 no
  hay nada que rascar sin presencia real allí.

**Comprobado de paso, y está bien:** la categoría `paneles-solares` **no promete
instalación** en ningún sitio. Lo de «se monta en un minuto» se refiere al soporte del
propio panel plegable, que es cierto. No hay nada que corregir ahí, solo que añadir la
línea que lo deja claro.

---

## Y una fuga nueva, pequeña pero del mismo tipo

`comprar placa arduino` · **113 impresiones, posición 29,2**.

Arduino. La palabra «placa» engancha búsquedas de electrónica que no tienen nada que ver.
Va a la lista de identidad borrosa junto a Xiaomi, DJI y Autel: no vale nada, y mientras
Google crea que esta web tiene algo que ver con Arduino, sigue sin tenerla clasificada.

---

## Resumen, por orden

| | Qué | Impresiones en juego | Esfuerzo |
|---|---|---:|---|
| **1** | Aplicar la categoría `paneles-solares` si no está | — | Un guardado |
| **2** | Los dos 301 y los enlaces internos | **11.008** | Media hora |
| **3** | Título SEO de la categoría con «panel solar portátil» | incluido arriba | Dos campos |
| **4** | Título y meta de `/kits-para-balcones/` | **6.734** | Dos campos |
| **5** | 301 de `/ecoflow-stream/` a `/stream-series/` | — | Diez minutos |
| **6** | Escribir `stream-series` como gama de producto | 1.288 | Lo escribo yo |
| **7** | La ficha de Google, ya escrita | ~400 locales | Está en `google/` |
| **8** | Sección local en `paneles-solares`, con la línea de «no instalamos» | incluido arriba | Falta la foto |

**Lo que vale el racimo si llega a primera página**, con la fórmula delante:

```
clics = impresiones × CTR medio de sector en la posición objetivo
```

- A posición 5: 14.326 × 0,06 = **~860 clics en 16 meses, ~54 al mes**
- A posición 3: 14.326 × 0,11 = **~1.576 clics, ~98 al mes**

Hoy son 55 clics en 16 meses. **Tres al mes.**

---

## Lo que necesito del dueño

**Contestado ya:** no se ofrece instalación, solo venta de placas solares de EcoFlow.
Recogido arriba, y además se va a decir en la web para no atraer esa búsqueda por error.

**Queda una sola cosa: las dos fotos del taller de Rivas y el horario.** Van por el tercer
frente que bloquean —las fichas de Hypershell, la ficha de Google y ahora lo local del
solar—, y es lo único que impide cerrar los tres.
