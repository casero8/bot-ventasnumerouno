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

## Causa nº 2 · El racimo de balcón no tiene página, y es el hallazgo nuevo

Esto no estaba en la auditoría y es lo que más me ha sorprendido del análisis:

| Consulta | Impresiones | Posición |
|---|---:|---:|
| `generador solar para balcones` | 189 | 13,4 |
| `generadores solares para balcones` | 179 | 22,0 |
| `kit solar para balcón ecoflow 880 w` | 154 | 10,2 |
| `paneles solares portatiles para balcones` | 136 | 16,3 |
| `kit solar balcon` | 123 | 15,8 |
| `placa solar balcón` | 96 | **72,5** |
| `kit solar balcón con batería` | 71 | 13,2 |
| `kit solar balcon ecoflow` | 57 | 5,9 |

**Mil impresiones y las posiciones van del 5,9 al 72,5.** Ese baile es la firma de que
Google no encuentra una página para esto y va probando la que le parece. Cuatro clics.

**El solar de balcón enchufable es una de las categorías que más está creciendo en España**,
y hay producto para venderlo: el **microinversor STREAM**, que sustituyó al PowerStream.
La categoría `stream-series` tiene **7 productos y 1.330 impresiones**, está en la lista de
nivel 1 del plan y **sigue sin texto escrito**.

**Propuesta:** el racimo de balcón apunta a `stream-series`, no a `paneles-solares`. Son
intenciones distintas —un kit enchufable para un balcón no es un panel portátil para el
campo— y mezclarlas es repetir el error de las tres URLs.

Si el dueño da el visto bueno, **escribo la categoría `stream-series` atacando «kit solar
balcón»** y queda cubierto. Es una categoría de nivel 1 que había que escribir de todas
formas.

---

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

**Dos avisos sobre lo local:**

- **Las de «instalación» hay que pensarlas antes de perseguirlas.** `instalacion placas
  solares rivas` e `instalar placas solares rivas` son de alguien que busca un instalador,
  no una tienda. Si no se ofrece instalación, atraer esa búsqueda es fabricar la siguiente
  reseña de una estrella, igual que pasó con el servicio técnico. **Hay que preguntarle al
  dueño si se ofrece instalación o no**, y si no, no se toca.
- **Málaga no se persigue.** `paneles solares portatiles malaga` (350 impr, posición 33) y
  su variante (282, posición 53) son la misma búsqueda dos veces, y en posición 33 y 53 no
  hay nada que rascar sin una presencia real allí.

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
| **4** | Escribir `stream-series` atacando «kit solar balcón» | ~2.300 | Lo escribo yo |
| **5** | La ficha de Google, ya escrita | ~400 locales | Está en `google/` |
| **6** | Sección local en `paneles-solares` | incluido arriba | Falta la foto |

**Lo que vale el racimo si llega a primera página**, con la fórmula delante:

```
clics = impresiones × CTR medio de sector en la posición objetivo
```

- A posición 5: 14.326 × 0,06 = **~860 clics en 16 meses, ~54 al mes**
- A posición 3: 14.326 × 0,11 = **~1.576 clics, ~98 al mes**

Hoy son 55 clics en 16 meses. **Tres al mes.**

---

## Lo que necesito del dueño

1. **¿Escribo `stream-series` atacando el balcón?** Es el hueco más limpio que hay y no
   tiene página nadie.
2. **¿Se ofrece instalación de placas solares o no?** Decide si se persiguen esas búsquedas
   o se dejan correr. Si no se ofrece, no se tocan.
3. **Las dos fotos del taller de Rivas** y **el horario**. Van ya por el tercer frente que
   bloquean: las fichas de Hypershell, la ficha de Google y ahora lo local del solar.
