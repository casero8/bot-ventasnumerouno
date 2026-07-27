# La profesión más demandada del sector digital

Réplica de la estética de Netflix para una serie de 4 clases. Es un proyecto
**totalmente independiente**: un único archivo `index.html` que funciona solo,
sin servidor ni instalación.

## Cómo verla
Haz doble clic en `index.html` y se abre en el navegador. Nada más.

## Cómo poner tus vídeos de YouTube
Abre `index.html` con cualquier editor de texto, busca el bloque `const CLASES`
(al principio del `<script>`) y pega el **ID** del vídeo en `youtubeId`:

```js
youtubeId: "dQw4w9WgXcQ",   // youtu.be/dQw4w9WgXcQ  ->  "dQw4w9WgXcQ"
```

El ID es lo que va después de `v=` o de `youtu.be/`. Mientras esté vacío (`""`),
esa clase muestra "Clase X próximamente". Opcional: `thumb: "url-de-imagen"`
para la portada de cada clase.

## Cuándo/dónde subirla
Cuando quieras, sube la carpeta a cualquier hosting estático
(Netlify, Vercel, GitHub Pages, tu propio dominio…). No necesita backend.
