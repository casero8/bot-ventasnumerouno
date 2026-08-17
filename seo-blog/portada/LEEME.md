# Portada nueva · ecogadgetoficial.com

## Por qué

La portada actual (página 187, Elementor, 90.988 bytes de `_elementor_data`) arrastraba
**2.918 KB de CSS y JavaScript** y unos 9,5 MB de imágenes. Montar la portada nueva con los
mismos widgets habría quedado más bonita pesando lo mismo.

Esta versión no usa Elementor ni una sola línea de JavaScript: **~12 KB de CSS en línea** y nada más.

## Ficheros

| Fichero | Qué es |
|---|---|
| `portada.css` | La hoja de estilos. **Aquí se edita el diseño.** |
| `snippet-portada.php` | El código, con la marca `/*EG_CSS_AQUI*/` donde entra el CSS. **Aquí se edita el contenido.** |
| `construir.sh` | Mete el CSS dentro del PHP y valida la sintaxis. |
| `snippet-portada-listo.php` | **Generado.** Lo que se pega en Code Snippets. No se edita a mano. |
| `vista-previa.html` | **Generado.** Vista previa navegable. |

Después de tocar `portada.css` o `snippet-portada.php`, ejecutar `./construir.sh`.

## Instalación

1. **Code Snippets → Añadir nuevo**, título `EG · Portada`, pegar `snippet-portada-listo.php`
   (sin la etiqueta `<?php` inicial si el editor la añade sola). Guardar **sin activar** todavía.
2. Crear una página nueva, título `EcoGadget | Distribuidor Oficial EcoFlow España`,
   **con el editor de bloques o el clásico, nunca con Elementor**, y como contenido solo:
   `[eg_portada]`
3. Activar el snippet y ver esa página. Comprobar que salen categorías, productos y precios.
4. Imagen de portada (opcional): coger el ID del adjunto en la biblioteca de medios y
   `wp option update eg_portada_hero <ID>`. Sin ella, el bloque se pinta en azul sólido.
5. Solo cuando esté revisada: **Ajustes → Lectura → Página de inicio** → la nueva.
6. Purgar la caché y comprobar la portada sin sesión iniciada.

**La portada vieja no se borra.** Queda como borrador; volver atrás es cambiar Ajustes → Lectura.

## Estructura y por qué está en ese orden

1. **Bloque de portada** — H1 con la palabra clave y la promesa real (distribuidor oficial,
   servicio propio). Dos botones: comprar y preguntar.
2. **Barra de confianza** — cuatro hechos comprobables. Sin promesas que no se puedan cumplir.
3. **Compra por categoría** — 8 fichas. Es lo que hace Amazon nada más abrir: repartir tráfico
   hacia las categorías, que es donde está el SEO trabajado.
4. **¿Qué quieres alimentar?** — cuatro entradas por uso, no por producto. Para quien no sabe
   lo que necesita, que es la mayoría del tráfico frío.
5. **Disponibles ahora** — productos reales con stock y precio de WooCommerce.
6. **Tabla de series** — RAPID / RIVER / DELTA / STREAM. Sin cifras: describe usos, así no hay
   ni un dato que pueda quedarse obsoleto.
7. **Texto de posicionamiento** — prosa real con enlaces internos a las categorías.
8. **Preguntas frecuentes** — `<details>` nativo (cero JavaScript) + schema `FAQPage`.
9. **Cierre** — invitación a preguntar.

## Accesibilidad

- Enlace "saltar a los productos" para teclado.
- Un solo `<h1>`, jerarquía de encabezados sin saltos.
- `aria-labelledby` en cada sección; tabla con `<caption>`, `scope="col"` y `scope="row"`.
- Foco visible (`:focus-visible`) en todo lo navegable.
- Botones y enlaces de acción con 44-48 px de alto: se pueden pulsar con el dedo.
- La foto de cada producto lleva `tabindex="-1"` y `aria-hidden`: va al mismo sitio que el
  título de al lado, y sin eso un lector de pantalla lee cada producto dos veces.
- Las imágenes decorativas llevan `alt=""`. Ningún texto va dentro de una imagen.
- `prefers-reduced-motion` y `forced-colors` contemplados.

## Móvil

Categorías y productos pasan a carrusel horizontal con `scroll-snap`, como Amazon: se pasa el
dedo en lugar de hacer scroll por doce tarjetas apiladas. Los botones ocupan el ancho completo.
Sin JavaScript: es `overflow-x` y `scroll-snap-type`.

## Reglas que respeta

- Ni una reseña ni una valoración inventada.
- Nada de escasez falsa: el stock que se muestra es el real de WooCommerce.
- Ningún "envío gratis" — no se menciona.
- Sin cifras de capacidad ni de autonomía, para no afirmar nada inexacto.
