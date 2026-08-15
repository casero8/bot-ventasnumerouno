# Limpieza de rastros de IA en las fichas

`limpiar-rastros-ia.py` es el limpiador que se pasó por las 158 fichas el
15/08/2026. Se deja aquí porque **esto va a volver a pasar**: cada vez que
alguien pegue texto directamente desde ChatGPT en el editor, vuelven los
marcadores.

## Qué quita

| Rastro | Qué es |
|---|---|
| `data-start="N"` / `data-end="N"` | Marcadores de streaming de ChatGPT. Salen en **cada** párrafo y cada negrita |
| `data-message-id`, `data-message-model-slug`, `data-turn-start-message` | Trozos del DOM del chat pegados enteros. Uno de ellos decía literalmente `gpt-5-3` |
| `hover:entity-accent`, `entity-underline`, `whitespace-normal`, `text-token-*` | Clases de la interfaz de ChatGPT |
| `:contentReference[oaicite:N]{index=N}` | Marcadores de cita que **se ven en la página** |
| Etiquetas vacías y cadenas de `<br>` | Restos de pegar y despegar |

## Qué NO toca, a propósito

- **`.sr-only` y `.flex`**: el tema las define de verdad (`.sr-only` seis veces).
  Borrarlas a ciegas desocultaría texto pensado para lectores de pantalla o
  descolocaría cajas. Las utilidades tipo `flex` o `w-full` solo se quitan si el
  mismo elemento lleva además un marcador de ChatGPT, que es lo que prueba de
  dónde vienen.
- **Los bloques `<style>` de las fichas HyperShell**: parecían CSS volcado como
  texto, pero se comprobó en el front y se renderizan como estilos de verdad.
  Falsa alarma, no se tocaron.
- **Ni una palabra del texto.** Se verificó comparando el texto plano antes y
  después en las 158: cero diferencias, salvo las seis citas
  `:contentReference` del kit de ducha, que sí eran texto visible y sobraban.

## ⚠️ Dos fallos que costaron romper el bloque "Comprado conjuntamente"

Pasaron el 15/08/2026 en la DELTA Max Ultra y la STREAM Ultra X. Están
corregidos, pero quedan escritos porque son fáciles de repetir.

### 1. No borres etiquetas vacías que lleven clase o id

La primera versión del limpiador quitaba cualquier etiqueta vacía y se llevó
por delante `<div class="eg-fbt-aviso"></div>`. **Ese div está vacío a
propósito**: lo rellena el JavaScript del bloque de comprado conjuntamente.

Regla: una etiqueta vacía **con clase o con id** casi siempre es un punto de
anclaje para JavaScript, no basura. Ahora solo se borran las que no llevan
ningún atributo.

### 2. NO uses la API de WooCommerce para guardar descripciones con HTML

Esto es lo que de verdad rompió el diseño. `POST /wp-json/wc/v3/products/batch`
pasa la descripción por **KSES** y **elimina las etiquetas `<input>`**, que no
están en su lista de permitidas.

El bloque de comprado conjuntamente se monta así:

```html
<li><label>
  <input type="checkbox" checked data-precio="649" data-id="2592">
  <span class="eg-fbt-nom">Panel Solar Portátil EcoFlow de 400 W</span>
  <span class="eg-fbt-pre">649,00 €</span>
</label></li>
```

Sin el `<input>`, el `<label>` pierde su ancla, los dos `<span>` se apilan y el
precio se monta encima del nombre. Es exactamente el destrozo que se vio: los
nombres partidos letra a letra y los precios solapados.

**Usa `POST /wp-json/wp/v2/product/<id>` con el campo `content`.** Esa ruta
respeta el HTML tal cual para un usuario con permiso de `unfiltered_html`.
Comprobado: los tres `<input>` sobreviven.

La API de WooCommerce sí vale para meta (`meta_data`, títulos y metas de
Yoast), que es texto plano. Solo falla con HTML enriquecido.

### Cómo comprobar que no has roto nada

Compara el recuento de etiquetas críticas antes y después, no solo la longitud:

```python
TAGS = ['<input','type="checkbox"','<button','<label','<table','<img','<form','<select']
# si alguna baja respecto a la copia de seguridad, algo se ha perdido
```

---

## Cómo volver a pasarlo

```bash
# 1. descargar en crudo
curl -u 'usuario:CONTRASEÑA DE APLICACIÓN' \
  'https://ecogadgetoficial.com/wp-json/wc/v3/products?per_page=100&page=1&status=any' -o wc1.json
# 2. limpiar en seco y revisar el informe
# 3. aplicar por lotes contra /wp-json/wc/v3/products/batch
```

**Haz siempre copia antes.** La de esta pasada está en
`../backup-fichas/fichas-original-2026-08-15.json`, con las 158 fichas enteras.
