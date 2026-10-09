# Auditoría SEO de ecogadgetoficial.com · octubre de 2026

**De dónde salen estos números.** De la exportación real de Search Console que hay en el
repositorio: **1.000 consultas, 16 meses, 150.992 impresiones y 1.327 clics**. Todo lo que
sigue está calculado sobre ese archivo, no estimado.

**Sus dos límites, dichos por delante.** La exportación acaba en agosto y hoy es octubre,
así que faltan dos meses —entre ellos lo de Hypershell, que es nuevo—. Y son las **1.000
consultas principales**: la cola larga real es mayor, aunque aporta poco. Intenté sacar
Search Console en vivo por Ahrefs y la cuenta no tiene plan para eso. Si quieres los
números al día, hace falta una exportación nueva: son dos clics en Search Console →
Rendimiento → Exportar.

---

## El diagnóstico, en una frase

**No tienes un problema de posiciones: tienes un problema de para qué apareces.** El 77 %
de tus impresiones ya están en la primera página de Google. Lo que pasa es que la mayoría
son búsquedas en las que tú no eres la respuesta que la gente quiere.

---

## Los cinco números que resumen todo

| | |
|---|---|
| **CTR global** | **0,88 %** · lo normal en una tienda es 2–4 % |
| **Impresiones de marca ajena («ecoflow»)** | **78,1 %** del total, con un CTR del 0,85 % |
| **Impresiones de tu propia marca («ecogadget»)** | **0,4 %** del total, con un CTR del **31,26 %** |
| **Impresiones comerciales sin marca** | 15,1 %, en **posición media 19** |
| **Impresiones de Hypershell / exoesqueleto** | **0** |

El segundo y el tercero, juntos, son el hallazgo de esta auditoría. **Cuando alguien te
busca a ti, entra uno de cada tres.** Tu problema no es que no sepas convencer: es que casi
nadie te está buscando a ti.

---

## Problema 1 · Eres un satélite de la marca EcoFlow

**117.983 de tus 150.992 impresiones son búsquedas con la palabra «ecoflow»**. Y la peor de
todas es la propia palabra sola:

| Consulta | Impresiones | Posición | Clics | CTR |
|---|---:|---:|---:|---:|
| `ecoflow` | **45.678** | 8,6 | 76 | **0,17 %** |
| `ecoflow españa` | 6.686 | 2,9 | 147 | 2,20 % |
| `eco flow` | 1.699 | 9,3 | 1 | 0,06 % |
| `ecoflow spain` | 1.278 | 6,5 | 12 | 0,94 % |
| `ecoflow es` | 828 | 6,0 | 1 | 0,12 % |

Casi 58.000 impresiones —el 38 % de todo— en consultas donde **el usuario quiere llegar a
`ecoflow.com`**, no a una tienda. Esa gente te ve en el puesto 8 y pasa de largo, y hace
bien: no te estaba buscando.

**Esto no se arregla.** Nadie le gana a una marca su propio nombre, y perseguirlo es tirar
el trabajo. Lo importante es **dejar de medirte con esas impresiones**: inflan el
denominador y hacen que todo parezca peor de lo que es. Tu CTR real, quitando esas
búsquedas, no es 0,88 % — pero sigue siendo malo, y eso es el problema 2.

---

## Problema 2 · Estás en la primera página y no te hacen clic

Aquí está el dinero. Reparto de tus impresiones por posición:

| Posición | Impresiones | % | Clics | CTR |
|---|---:|---:|---:|---:|
| 1–3 | 12.652 | 8,4 % | 474 | **3,75 %** |
| **4–10** | **103.616** | **68,6 %** | **606** | **0,58 %** |
| 11–20 | 22.040 | 14,6 % | 232 | 1,05 % |
| 21+ | 12.684 | 8,4 % | 15 | 0,12 % |

**Casi siete de cada diez impresiones están entre el puesto 4 y el 10 de la primera
página, y solo rascan un 0,58 %.** En posición 4 el CTR normal de sector es un 8 %; en
posición 8, un 3,3 %.

**Cuánto vale arreglarlo, con la cuenta delante.** Tomando el CTR medio de sector por
posición y aplicándolo a las impresiones que ya tienes en primera página, **sin subir un
solo puesto**:

```
clics potenciales = Σ (impresiones de la consulta × CTR medio de su posición)
```

| | Clics hoy | Techo | Margen |
|---|---:|---:|---:|
| Contando todo | 1.081 | ~5.184 | ~256/mes |
| **Quitando la navegacional pura de marca ajena** | **835** | **~2.761** | **~120/mes** |

La segunda fila es la honesta, y es la que hay que usar: en `ecoflow` a secas, en posición
8, ningún título va a sacar el CTR de sector, porque el usuario quiere otra web. **Pero
120 clics al mes de margen, sin mover una posición, es más del doble de lo que ingresas
hoy** (1.327 clics en 16 meses son unos 83 al mes).

### Las anomalías que delatan dónde está el fallo

Estas no son un problema de posición: son un problema de **título y descripción**.

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `servicio tecnico oficial ecoflow` | 328 | **2,0** | **0** |
| `ecoflow logo` | 144 | 1,7 | 0 |
| `product` | 239 | 2,2 | 0 |
| `distribuidor autel madrid` | 267 | 1,5 | 2 |
| `servicio tecnico ecoflow españa` | 545 | 1,2 | 27 (4,95 %) |

**Posición 2 con cero clics en «servicio técnico oficial ecoflow» es el peor dato de todo
el archivo.** Es una búsqueda con intención perfecta —alguien busca exactamente el servicio
que vendes—, estás el segundo y no entra nadie. Eso solo pasa por una de tres: el título no
dice lo que el usuario busca, la descripción no promete nada, o arriba hay algo que se lleva
el clic. Hay que mirar esa página antes que ninguna otra.

Y `servicio tecnico ecoflow españa` en **posición 1,2 con un 4,95 %** apunta a lo mismo:
siendo el primer resultado, el CTR debería estar entre el 20 % y el 30 %.

---

## Problema 3 · 13.000 impresiones de un negocio que ya no es tuyo

| Qué | Impresiones | Clics |
|---|---:|---:|
| Xiaomi | 4.945 | 2 |
| «gadget» / «gadgets» genérico | 4.492 | 19 |
| DJI y reparación de drones | 3.095 | 12 |
| Autel | 455 | 3 |
| **Total** | **12.987 (8,6 %)** | **36** |

Casi el 9 % de tus impresiones son ruido: marcas que no vendes, el negocio antiguo de
alarmas y reparación, y el genérico «gadget» que se cuela por tu propio nombre. Treinta y
seis clics en dieciséis meses.

**No es solo que no sirva: es que distorsiona.** Mientras Google crea que tienes algo que
ver con drones DJI y con Xiaomi, sigue sin tenerte clasificado como lo que eres. Y es
exactamente el mismo problema que las malas reseñas: una identidad borrosa.

Estas impresiones vienen de páginas viejas que siguen vivas. Hay que encontrarlas y
decidir: o se reescriben para lo que vendes hoy, o se van con un 301 a donde corresponda.

---

## La mina · los cuatro racimos donde están los clics

Calculado igual: impresiones reales × CTR medio de sector en la posición objetivo.

### 1 · Placas y paneles solares — el más grande con diferencia

**10.336 impresiones, posición media 18,9, 47 clics.**

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `placa solar portatil` | 924 | 17,5 | **0** |
| `panel solar portatil` | 845 | 17,8 | 1 |
| `placas solares portatiles` | 471 | 16,0 | 0 |
| `paneles solares portatiles` | 367 | 16,1 | 0 |
| `panel solar portatil 45w type c` | 243 | 20,2 | 0 |

Diez mil impresiones de intención comercial pura, **sin marca**, atascadas en la segunda
página. Esto es lo que más margen tiene de todo el sitio:

- **Pasando a posición 5:** ~620 clics en 16 meses → **~38 al mes**
- **Pasando a posición 3:** ~1.136 clics → **~71 al mes**

Hoy sacas 3 al mes. Y hay un post, `06-panel-solar-portatil.html`, escrito para esto. Está
en el puesto 17: o no se aplicó, o está canibalizado por `/paneles-solares` y
`/placas-solares-ecoflow/`, que es justo el riesgo que ya anotamos cuando se escribió.

### 2 · «Qué es un EcoFlow» — el más fácil

**7.268 impresiones, posición media 6,8, 41 clics.**

| Consulta | Impresiones | Posición |
|---|---:|---:|
| `que es un ecoflow` | 1.794 | 6,9 |
| `que es un ecoflow y para que sirve` | 1.125 | 5,8 |
| `ecoflow que es` | 1.111 | 6,9 |
| `que es ecoflow` | 968 | 7,2 |

Ya estás en primera página en las cuatro. El post `01-que-es-ecoflow.html` existe y
funciona a medias: **con un 0,5 % de CTR en posición 6-7, el problema es el título y la
descripción, no el contenido.**

- **A posición 5:** ~436 clics → **~27 al mes**
- **A posición 3:** ~799 clics → **~49 al mes**

Es el racimo con mejor relación entre lo que cuesta y lo que da: son dos campos de Yoast.

### 3 · Manuales — tráfico de propietario, que compra accesorios

**6.798 impresiones, posición media 7,9, 50 clics.**

`ecoflow delta 3 classic manual español` (1.003) · `manual ecoflow delta pro español`
(963) · `ecoflow app manual` (879).

No vende en el primer clic, pero trae a **gente que ya tiene el aparato** — exactamente
quien compra baterías adicionales, cables y fundas. Tienes `/man/`, y en el puesto 8.

- **A posición 3:** ~747 clics → **~46 al mes** de público propietario

### 4 · Local — el que más convierte y el más descuidado

**2.571 impresiones, posición media 16,4.**

| Consulta | Impresiones | Posición | Clics |
|---|---:|---:|---:|
| `placas solares rivas-vaciamadrid` | 256 | **12,6** | **0** |
| `placas solares portatiles malaga` | 350 | 33,0 | 0 |
| `placas solares portatiles malaga` (var.) | 282 | 53,1 | 0 |
| `ecoflow madrid` | 137 | 1,6 | 32 (23 %) |

Mira la última fila y compárala con la primera. **`ecoflow madrid`, en posición 1,6, saca
un 23 % de CTR** — el mejor número de todo el archivo. Y `placas solares
rivas-vaciamadrid`, que es tu propio pueblo, donde tienes la nave, **está en el puesto 12,6
con cero clics**.

Eso se arregla con la ficha de Google y con una página local, y es el tráfico que más
convierte que existe: alguien que busca placas solares en tu pueblo está a diez minutos de
tu puerta.

---

## Problema 4 · Hypershell no existe para Google

**Cero impresiones.** Ni «hypershell», ni «exoesqueleto», ni nada.

No es un fallo: es que hasta hoy no había contenido. Lo de esta semana —la guía
`/que-es-un-exoesqueleto/`, la categoría reescrita, las tres fichas— es precisamente lo que
crea esa visibilidad desde cero. **No esperes nada antes de seis u ocho semanas**, y cuando
empiece, mídelo contra cero, no contra EcoFlow.

Lo mismo con Lokithor: **112 impresiones en 16 meses.** Invisible. Y ahí no hay excusa de
novedad: la gama lleva tiempo.

---

## Plan de 90 días, ordenado por clics por hora de trabajo

### Semana 1 — títulos y descripciones. Cero riesgo, efecto en dos semanas

1. **La página de servicio técnico.** Posición 2 con cero clics es lo más urgente del
   sitio. Mirar título, descripción y qué sale por encima en el resultado.
2. **El post «qué es un EcoFlow».** Título y meta reescritos atacando las cuatro variantes
   reales: «qué es», «qué es y para qué sirve», «ecoflow que es».
3. **`/man/`.** El título tiene que incluir «manual en español» y el modelo, que es como lo
   busca la gente.
4. **Las diez consultas de posición 1–3 con CTR bajo**, una por una.

Techo del bloque: del orden de **120 clics al mes**, sin tocar una posición.

### Semanas 2 a 4 — el racimo solar

5. **Resolver la canibalización** entre `/paneles-solares`, `/placas-solares-ecoflow/` y el
   post. Una URL para la intención comercial, otra para la informativa, y la que sobre con
   un 301.
6. **Reforzar la que se quede** con enlaces internos desde las fichas de estación, que son
   las que tienen autoridad.

Techo: **38–71 clics al mes.**

### Semanas 4 a 8 — local y limpieza

7. **Ficha de Google:** el renombrado a `EcoGadget`, la descripción, las categorías. Ya
   está todo escrito en `google/`.
8. **Una página local de verdad** para Rivas y sur de Madrid, con las dos fotos del taller
   que sigo esperando.
9. **Las páginas que atraen Xiaomi, DJI y Autel:** reescribir o 301.
10. **Empezar a pedir reseñas** por sistema. Afecta al posicionamiento local, no solo a la
    nota.

### Semanas 8 a 12 — lo que queda de la marca

11. Las **27 categorías sin texto** del plan.
12. **Poner el stock de los cuatro HyperShell de la generación anterior.** Corregido el
    9/10/2026 leyendo la web en vivo: las fichas **existen** —X Ultra 1.799 €, X Carbon
    1.299 €, X Pro 899 €, X Go 699 €— y están **marcadas como agotadas**. Las 82 unidades no
    están sin página: están escondidas por un desplegable. Es lo de más valor por minuto de
    toda esta auditoría y no es trabajo de SEO.
13. Los **seis accesorios Hypershell** que faltan.

---

## Lo que NO hay que hacer

- **No perseguir «ecoflow» a secas.** 45.678 impresiones que no son tuyas. Ni un euro ahí.
- **No escribir más contenido nuevo todavía.** Tienes 7.268 impresiones en «qué es un
  ecoflow» con el post ya escrito y sin clics. Primero cobrar lo que ya está sembrado.
- **No tocar la indexación.** Está resuelto: 326 URLs en el sitemap, 382 indexadas. Las
  27.000 que desaparecieron en junio eran basura y las impresiones subieron un 94 % al
  irse.
- **No mirar el CTR global como una sola cifra.** Con el 78 % de marca ajena dentro, no
  dice nada. Hay que medirlo separando marca ajena, marca propia y sin marca.

---

## Qué medir a partir de ahora, y no es el CTR global

Cuatro números, mensuales. Si estos cuatro van bien, todo lo demás va bien:

| Métrica | Hoy | Objetivo a 90 días |
|---|---:|---:|
| Clics de consultas **sin marca** | ~7/mes | **40/mes** |
| Clics de **tu propia marca** («ecogadget») | ~12/mes | **25/mes** |
| CTR en posiciones **4–10**, sin navegacional | 0,58 % | **2 %** |
| Impresiones de **Hypershell** | 0 | **>1.500/mes** |

El primero es el que de verdad importa: **mide a la gente que no te conocía y te
encontró.** Es el único que crece sin depender de la marca de otro.

---

## Lo que necesito para afinar esto

1. **Una exportación nueva de Search Console**, con los dos meses que faltan. Rendimiento →
   Exportar → CSV, y la de **Páginas** además de la de Consultas: con las páginas puedo
   decir qué URL concreta se come cada impresión, que es lo que aquí he tenido que deducir.
2. **Las dos fotos del taller de Rivas** y **el horario**. Bloquean la parte local, que es
   la que más convierte.
