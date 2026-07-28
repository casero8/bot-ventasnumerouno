# La profesión más demandada del sector digital · Ventas N1

Serie estilo Netflix con 4 clases y **modo goteo** (vas desbloqueando las
clases cuando quieres). Proyecto **independiente y estático**: no necesita
servidor ni base de datos, solo subir los archivos.

## Archivos
- **index.html** — la web completa.
- **estado.js** — controla el goteo (la única línea que tocarás).
- **README.md** — este archivo.

---

## 🔓 Modo goteo: cómo desbloquear las clases
Abre **estado.js** y cambia el número:

```js
window.CLASES_DISPONIBLES = 1;   // 1 = solo Clase 1
                                 // 2 = Clases 1 y 2
                                 // 3 = Clases 1, 2 y 3
                                 // 4 = las 4 clases
```

Guarda y sube el archivo (o edítalo directamente en el gestor de archivos de
Hostinger). El cambio es inmediato para todos. Las clases bloqueadas se ven con
candado, un “?” y “Próximamente”, sin revelar título ni contenido.

## 🎬 Poner tus vídeos de YouTube
En **index.html**, busca `const CLASES` y pega el ID del vídeo en `youtubeId`:

```js
youtubeId: "dQw4w9WgXcQ",   // youtu.be/dQw4w9WgXcQ  ->  "dQw4w9WgXcQ"
```

(Opcional) `thumb: "url-de-imagen"` para poner una portada real en vez de la de color.

---

## 🚀 Subirlo a serie.ventasnumerouno.com (Hostinger / hPanel)

**1) Crear el subdominio**
1. Entra en **hPanel** → tu web → sección **Dominios → Subdominios**.
2. En “Crear un subdominio nuevo” escribe: **serie**  (el dominio ya es
   ventasnumerouno.com). Pulsa **Crear**.
3. Hostinger crea automáticamente una carpeta, normalmente
   `public_html/serie` (o `domains/serie.ventasnumerouno.com/public_html`).

**2) Subir los archivos**
1. hPanel → **Archivos → Administrador de archivos**.
2. Entra en la carpeta del subdominio que se creó en el paso anterior.
3. Sube **index.html** y **estado.js** ahí dentro (botón Subir).
4. Listo. Abre **https://serie.ventasnumerouno.com** en el navegador.

**3) SSL (candado https)**
hPanel → **Seguridad → SSL** → activa el certificado gratuito para el
subdominio si no se activó solo (puede tardar unos minutos).

> Para desbloquear una clase más adelante: Administrador de archivos →
> abrir **estado.js** → cambiar el número → Guardar. Sin tocar nada más.
