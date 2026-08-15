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
