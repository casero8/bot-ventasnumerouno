/* ============================================================
   FACHADA DE VÍDEO · va al final de "Custom JS del tema"
   (Opciones de Tema -> minimog_options[custom_js])

   Un solo listener delegado para toda la web. No hay que
   registrar nada por vídeo: basta con que el HTML lleve
   .eg-vid[data-yt] y dentro un botón .eg-vid-btn.

   No usa librerías, no toca el DOM en la carga y no hace
   ninguna petición hasta que alguien pulsa.
   ============================================================ */
(function () {
  'use strict';

  document.addEventListener('click', function (e) {
    var t = e.target;
    if (!t || typeof t.closest !== 'function') { return; }

    var boton = t.closest('.eg-vid-btn');
    if (!boton) { return; }

    var caja = boton.closest('.eg-vid');
    if (!caja) { return; }

    var id = caja.getAttribute('data-yt');
    // Solo IDs de YouTube bien formados: 11 caracteres del alfabeto
    // que usa YouTube. Si alguien mete otra cosa en el atributo,
    // no se construye ninguna URL.
    if (!id || !/^[A-Za-z0-9_-]{11}$/.test(id)) { return; }

    e.preventDefault();
    caja.classList.add('eg-vid-cargando');

    var marco = document.createElement('iframe');
    marco.src = 'https://www.youtube-nocookie.com/embed/' + id +
                '?autoplay=1&rel=0&modestbranding=1&playsinline=1&hl=es';
    marco.title = boton.getAttribute('aria-label') || 'Vídeo';
    marco.setAttribute('allow',
      'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    marco.setAttribute('allowfullscreen', 'allowfullscreen');
    marco.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
    marco.loading = 'lazy';

    caja.replaceChild(marco, boton);
    caja.classList.remove('eg-vid-cargando');
  }, false);
})();
