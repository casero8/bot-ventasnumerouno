# Dataset y hallazgos

## Metodología

**Qué se midió:** 706 vídeos de 6 canales hispanos, extraídos de los catálogos públicos
de YouTube el 2026-09-21.

**Métrica:** múltiplo sobre la mediana del **propio canal** (`views / mediana_del_canal`).

Por qué esta métrica y no las visitas absolutas: las visitas absolutas miden el tamaño del
canal, no la calidad del gancho. Un vídeo de 500.000 visitas en un canal cuya mediana son
2,2M es un fracaso; uno de 4,5M donde la mediana es 14.500 es un fenómeno. Solo el
múltiplo aísla la decisión editorial del tamaño de la audiencia.

**Regla derivada:** nunca compares vídeos entre canales distintos. El contraste con valor
predictivo es siempre intra-canal, entre el mismo creador consigo mismo.

## Canales del dataset

| Canal | n | Mediana | Nicho | Dispersión (max/mediana) |
|---|---|---|---|---|
| Farid Dieck | 120 | 2.200.000 | Desarrollo personal vía cine | x4.4 |
| Margarita Pasos | 118 | 55.000 | Ventas y mentalidad | x32.7 |
| Pedro Buerbaum | 120 | 48.000 | Negocio y disciplina | x14.3 |
| Chris Núñez | 108 | 14.500 | Psicología clínica divulgada | x310 |
| Víctor Heras | 120 | 14.500 | Crecimiento en redes (meta) | x7.4 |
| Xavier Pirla | 120 | 1.800 | Persuasión / PNL | x312 |

Nota sobre dispersión: los canales con mediana baja muestran múltiplos extremos (x310) en
parte por efecto aritmético del denominador pequeño. El múltiplo ordena bien dentro de un
canal; no compares múltiplos entre canales de tamaños muy distintos.

## Hallazgo 1 — El patrón desnudo fracasa (Farid Dieck, n=120)

| Vídeo | Múltiplo |
|---|---|
| Tommy Shelby, ¿ejemplo a seguir o señal de alerta? | **x4.4** |
| El secreto que forzó la renuncia del Papa | x3.4 |
| ¿Tenía razón Thanos? | x3.0 |
| *"¿Tu vida será siempre una batalla tras otra?"* (sin vehículo) | **x0.25** |
| ¿Por qué cambiamos para encajar? (George de la Selva) | x0.26 |
| ¿Cuánta verdad hay en Indiana Jones? | x0.23 |

Dos conclusiones distintas, no una:
1. El patrón sin vehículo se hunde (x0.25).
2. El vehículo **tibio** también se hunde (x0.23–x0.26). No basta con poner una película:
   hace falta carga emocional previa alta en la audiencia concreta.

## Hallazgo 2 — "Yo" contra "tú" (Buerbaum n=120, + Heras n=120)

Buerbaum, sus tres peores de 120: su paso como speaker en un foro (x0.21), entrevista con
su cofundador (x0.17), preparación del drop de su marca de ropa (**x0.09**).
Sus tres mejores: daño nombrado al espectador (x14.3, x11.9, x10.1).

Víctor Heras replica el patrón de forma independiente, en otro nicho:
"Me hice millonario a los 24" (x0.33), "Cómo escalé mi negocio a 500.000€" (x0.43).
Ambos por debajo de su mediana, en un canal donde las guías prácticas hacen x5–x7.

Dos canales distintos, dos nichos distintos, mismo resultado: la autobiografía del creador
rinde por debajo de la mediana incluso cuando el creador tiene resultados reales que contar.

## Hallazgo 3 — Síntoma + pasos (Chris Núñez, n=108)

| Vídeo | Múltiplo |
|---|---|
| Cómo combatir pensamientos de ansiedad y depresión (en 4 pasos) | **x310** |
| Cómo dejar de tener miedo (técnica de 5 pasos) | x159 |
| Cómo gestionar la ira (técnica de 3 pasos) | x49 |
| Disciplina vs. motivación: ¿qué te lleva más lejos? | x0.19 |
| Beneficios del pensamiento negativo | x0.14 |

Los tres primeros comparten arquitectura exacta: **síntoma autodiagnosticable + número
explícito de pasos**. Los dos últimos son temas para pensar, no diagnósticos con salida.

Observación secundaria: los dos vídeos más largos y conversacionales del canal (37 y 38
min, formato entrevista/mixto) están en el fondo absoluto (x0.21, x0.09).

## Hallazgo 4 — Culpable externo + consecuencia (Margarita Pasos, n=118)

Mayor outlier del canal: *"12 mentiras que te dijeron sobre el dinero y que te mantienen
quebrado"* — **x32.7** (1,8M sobre mediana de 55.000).

Anatomía: `[12] + [mentiras] + [te dijeron] + [te mantienen quebrado]`
cantidad cerrada · naturaleza del engaño · agente externo · estado presente del espectador.

Sus peores: entrevistas a terceros famosos (x0.11–x0.21) y el anuncio de su propia gira
(x0.11). Contenido donde el espectador no es el protagonista.

## Hallazgo 5 — Utilidad buscada ≠ viralidad (Víctor Heras, n=120)

Sus mejores vídeos son guías largas de 43–66 minutos sobre el algoritmo de Instagram
(x7.4, x6.5, x5.9). Esto **no** es contenido viral: es contenido de utilidad que la gente
busca activamente. Responde a una intención de búsqueda existente, no crea el deseo.

Relevancia para el nicho de patrón: son dos motores de distribución distintos. La utilidad
buscada se optimiza para el buscador; el contenido de patrón se optimiza para el scroll.
No mezcles las reglas de uno con las del otro.

Su serie de formato entretenimiento ("De 0 a 100K", Ep. 7 y 8) rinde x0.42 y x0.18,
confirmando que el formato serie sin promesa de utilidad no sostiene a un canal educativo.

## Limitaciones (leer antes de citar estos datos)

- **Sin transcripciones.** YouTube bloquea por IP desde datacenter (HTTP 429 y "confirma
  que no eres un bot"), tanto en yt-dlp como en la API de subtítulos. Todo el análisis es
  de **metadatos**: título, duración, visitas. No hay análisis del guion hablado ni del
  gancho en audio. Para texto: herramienta vidIQ `vidiq_video_transcript` (5 créditos).
- **Sin datos de miniatura.** Título y miniatura se deciden juntos y aquí solo se midió el
  título. Parte del efecto atribuido al título puede ser de la miniatura.
- **Sin retención ni CTR.** No hay acceso a analíticas internas. Las visitas son resultado
  de empaquetado + distribución, no solo de gancho.
- **Visitas redondeadas** por la fuente (ej. 20.000, 2,2M). Los múltiplos son aproximados.
- **Correlación, no causalidad.** Los patrones son consistentes en 6 canales y 706 vídeos,
  lo que es señal fuerte, pero no son un experimento controlado.
- **Sin shorts.** El canal de Víctor Heras no publica shorts en YouTube; el resto no se
  muestreó en formato corto. Las conclusiones aplican a formato largo y a la lógica de
  titulación, que sí transfiere parcialmente a formato corto.

## Reproducir o ampliar

```bash
scripts/analizar-canal.sh @elhandle        # un canal
scripts/analizar-canal.sh UCxxxxxxxxxxxx   # o su channel ID
```
