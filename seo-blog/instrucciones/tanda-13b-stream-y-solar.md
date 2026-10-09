# Tanda 13b · STREAM escrita, y los cuatro arreglos del racimo solar

**9 de octubre de 2026.** Todo lo del parte anterior, ya escrito y listo para aplicar.

**Antes de nada, el orden importa.** El punto 1 va primero porque si no, lo demás se
aplica sobre dos categorías que siguen partiéndose la señal.

---

## 1 · Unir las dos categorías de STREAM · esto va primero

Hay **dos categorías para la misma gama**, y está anotado en
`analisis-categorias-2026-08.md` desde agosto sin hacerse:

| Categoría | Productos | Impresiones |
|---|---:|---:|
| `/stream-series/` «Batería para casa y balcón STREAM» | 6 | 1.288 |
| `/ecoflow-stream/` «EcoFlow STREAM» | 2, **agotados** | — |

**301 de `/ecoflow-stream/` → `/product-category/stream-series/`.** Al fragmento #6, con
los demás. Después:

- Mover los 2 productos a `stream-series` si no están ya ahí.
- La categoría vieja, a **borrador**, nunca a la papelera (norma 11).
- Quitarla del sitemap.
- Barrer los enlaces internos para que **ninguno pase por el 301** (norma 9).

> **Punto de control.** Confirma el 301 y que `/product-category/stream-series/` responde
> 200 directo antes de aplicar el texto del punto 2.

---

## 2 · La categoría `stream-series`, escrita

- `seo-blog/categorias/stream-series.html` — **12,0 KB, 1.509 palabras, 16 enlaces**
- `seo-blog/categorias/stream-series-meta.txt` — título, meta y las comprobaciones

**Por qué es nivel 1:** el racimo STREAM + balcón + autoconsumo son **8.456 impresiones y
127 clics** en 16 meses. Es la segunda bolsa de impresiones del sitio después del solar
genérico, y la categoría no tenía ni una línea.

**Y hay que cambiarle el H1.** Hoy se llama «Batería para casa y balcón STREAM». Eso no lo
busca nadie con esas palabras. **El H1 pasa a «EcoFlow STREAM»**, que es como se busca:
`ecoflow stream series` tiene 386 impresiones en posición 9,0, y «stream» aparece en 91
consultas distintas. **El slug no se toca**: `stream-series` ya coincide con la búsqueda.

### Lo que hace el texto, y por qué está así

- **Separa el microinversor de la batería.** Es la confusión real: uno mete en casa lo que
  producen las placas y no guarda nada; la otra guarda lo que sobra. Hay gente comprando
  uno creyendo que hace lo del otro, y eso es una devolución.
- **Tiene una sección para el PowerStream.** Son **~624 impresiones** repartidas en cinco
  consultas (`powerstream` 177, `ecoflow powerstream 800w` 158, `power stream` 126…) de un
  producto **descontinuado**. El texto dice que lo está y a qué corresponde hoy. Esa gente
  existe y hoy no encuentra respuesta.
- **Dice que no instalamos**, en un bloque destacado, y lo convierte en argumento: esta gama
  va a un enchufe, así que no necesita a nadie. Es la petición del dueño del 9/10 y encaja
  con el producto en vez de pelearse con él.
- **No lleva ni una cifra de ahorro.** La FAQ explica por qué: un ahorro sin el consumo del
  cliente delante es un número inventado, y manda a `/kits-para-balcones/`, que es donde
  está la cuenta a la vista. Esto es a propósito: **las dos páginas se reparten el trabajo,
  no compiten.**
- **No afirma nada sobre permisos de autoconsumo.** La normativa depende de la potencia y de
  la comunidad autónoma. La FAQ dice que lo confirme la comercializadora o el ayuntamiento,
  no una tienda. Es el tipo de promesa que luego se paga.
- **No lleva capacidades ni salidas por modelo.** Cambian entre versiones y no están
  verificadas una a una: se manda a cada ficha.

---

## 3 · El título y la meta de `/kits-para-balcones/`

**Esta es la de mejor relación esfuerzo-resultado de toda la tanda: dos campos.**

La página tiene **6.734 impresiones en posición 12,11 y 82 clics**. Es una de las más
grandes del sitio y está a un puesto de la primera página. Y **se queda como está** —el plan
de migración decidió bien: es una página de caso de uso, no una gama—, solo hay que
reescribirle el título y la meta.

Las consultas reales a las que tiene que responder:

| Consulta | Impresiones | Posición |
|---|---:|---:|
| `generador solar para balcones` | 189 | 13,4 |
| `generadores solares para balcones` | 179 | 22,0 |
| `ecoflow balcon` | 178 | 7,7 |
| `kit solar para balcón ecoflow 880 w` | 154 | 10,2 |
| `kit balcon ecoflow` | 145 | 6,8 |
| `paneles solares portatiles para balcones` | 136 | 16,3 |
| `kit solar balcon` | 123 | 15,8 |
| `kit ecoflow balcon` / `ecoflow kit balcon` | 224 | 7,1 / 9,8 |

Propuesta, a verificar que no pase de los límites de Yoast:

**Título (58):**
`Kit solar para balcón EcoFlow: placas, batería y montaje`

**Meta (152):**
`Kits solares de balcón EcoFlow con placas y batería. Qué llevan, cuánto producen y cómo se enchufan sin obra ni electricista. Distribuidor oficial en España.`

El título actual, cuál sea, está perdiendo el clic en posición 12 con 6.734 impresiones. Ese
es el problema, no la posición.

---

## 4 · Los dos 301 del racimo genérico

Lo del parte anterior, que es lo más grande de la auditoría:

1. **Comprobar que la categoría `paneles-solares` está aplicada.** El texto está en
   `categorias/paneles-solares.html` y **acaba de cambiar** (ver punto 5), así que hay que
   aplicarlo de nuevo igualmente.
2. **301 de `/paneles-solares-portatiles/`** (9.394 impresiones, posición 16,3, la segunda
   página del sitio) **→ categoría `paneles-solares`**.
3. **301 de `/placas-solares-ecoflow/`** (1.614 impresiones) **→ la misma categoría**.
4. Enlaces internos actualizados, páginas viejas a borrador, fuera del sitemap.
5. **El título SEO de la categoría tiene que llevar «panel solar portátil»**, en singular y
   plural si cabe. Las consultas son `placa solar portatil` (924), `panel solar portatil`
   (845), `placas solares portatiles` (471), `paneles solares portatiles` (367).

---

## 5 · El aviso de «no instalamos» en `paneles-solares`

Ya está añadido en el repositorio, justo al principio del texto que va debajo de la
rejilla:

> **Lo que hacemos y lo que no:** vendemos **placas solares de EcoFlow** y te asesoramos
> sobre cuál le va a tu equipo. **No hacemos instalaciones** en tejado ni montajes
> eléctricos. Los paneles portátiles y plegables no necesitan a nadie: se despliegan y se
> enchufan. Si tu caso necesita obra o tocar el cuadro eléctrico, eso lo tiene que ver un
> instalador.

La categoría son ahora **11,8 KB**. Hay que reaplicarla por REST comparando bytes.

**Comprobado de paso:** la categoría **no prometía instalación** en ningún sitio. Lo de «se
monta en un minuto» era el soporte del panel plegable. No había nada que corregir, solo
esto que añadir.

**Y las búsquedas de instalación no se persiguen:** `instalacion placas solares
rivas-vaciamadrid` (56 impr) e `instalar placas solares rivas-vaciamadrid` (40) quedan
fuera del plan. Las que sí se trabajan son `placas solares rivas-vaciamadrid` (256) y
`placas solares en rivas-vaciamadrid` (52), que son de compra.

---

## Resumen, por orden de aplicación

| | Qué | Impresiones en juego |
|---|---|---:|
| **1** | 301 `/ecoflow-stream/` → `/stream-series/` | — |
| **2** | Aplicar `stream-series` y cambiar su H1 a «EcoFlow STREAM» | **8.456** |
| **3** | Título y meta de `/kits-para-balcones/` | **6.734** |
| **4** | Reaplicar `paneles-solares` con el aviso nuevo | — |
| **5** | Los dos 301 del racimo genérico | **11.008** |
| **6** | Título SEO de `paneles-solares` con «panel solar portátil» | incluido |

**Entre los tres racimos hay más de 26.000 impresiones en juego**, casi todas entre la
posición 10 y la 20. Es la mitad del tráfico potencial del sitio.

---

## Lo único que sigue pendiente del dueño

**Las dos fotos del taller de Rivas y el horario.** Bloquean tres frentes a la vez: las
fichas de Hypershell, la ficha de Google y la parte local del solar.
