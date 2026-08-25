/* ============================================================
   FACHADA DE VÍDEO · el JS que falta

   El CSS de la fachada se puso el 20 de agosto y llega a todas
   las páginas: .eg-video .eg-video-fachada .eg-video-img
   .eg-video-play .eg-video-iframe .eg-video-pie
   El JS que lo acompañaba NUNCA se instaló. El propio CSS lo
   anuncia en un comentario: «el iframe de youtube-nocookie.com
   lo crea el JS al pulsar». Nadie lo creó.

   Sin esto, una fachada es un botón de play que no hace nada.

   DÓNDE VA: al final del PIE de Herramientas -> Head & Footer
   Code, dentro de etiquetas script. No en Opciones De Tema: ese
   formulario reescribe los 1.904 campos al guardar y falla una
   de cada dos veces. El pie de Head & Footer Code hace el mismo
   trabajo sin ese riesgo.

   EL DISPARADOR ES UN <a>, NO UN <button>. Medido en vivo: el
   tema le impone al boton su propio padding y su propia altura,
   y la fachada se queda en 45 px en vez de 321. El <a> sale
   limpio. Y tiene una ventaja que hoy vale mas que ninguna: su
   href apunta al video en YouTube, asi que SI ESTE JS FALTA,
   SE PIERDE O UN OPTIMIZADOR LO TIRA, la fachada sigue
   funcionando y abre el video. Nunca vuelve a haber un boton de
   play que no hace nada.

   Un solo listener delegado para toda la web. No hay que
   registrar nada por vídeo, no toca el DOM al cargar y no hace
   ninguna petición hasta que alguien pulsa.
   ============================================================ */
(function () {
  'use strict';

  // El ID puede venir en el contenedor o en el propio disparador,
  // y bajo cualquiera de estos tres nombres. Así el mismo listener
  // vale para el marcado que se escriba hoy y para el que ya
  // hubiera escrito otro antes.
  var ATRIBUTOS = ['data-yt', 'data-video', 'data-youtube'];

  function sacarId(nodo) {
    for (var i = 0; i < ATRIBUTOS.length; i++) {
      var v = nodo.getAttribute(ATRIBUTOS[i]);
      // Solo IDs de YouTube bien formados: 11 caracteres de su
      // alfabeto. Si el atributo trae otra cosa, no se construye
      // ninguna URL.
      if (v && /^[A-Za-z0-9_-]{11}$/.test(v)) { return v; }
    }
    return null;
  }

  document.addEventListener('click', function (e) {
    var t = e.target;
    if (!t || typeof t.closest !== 'function') { return; }

    // Clic con Ctrl, Cmd, Shift, Alt o con el boton central: eso es
    // "abrelo en otra pestaña", y como el href apunta al video en
    // YouTube, se deja pasar. Solo se intercepta el clic normal.
    if (e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) { return; }

    var caja = t.closest('.eg-video');
    if (!caja) { return; }

    // El disparador normal es .eg-video-fachada. Si ese marcado no
    // estuviera, vale la propia caja: mejor que el clic funcione a
    // que se pierda por un envoltorio que falta.
    var fachada = t.closest('.eg-video-fachada') || caja.querySelector('.eg-video-fachada');
    if (!fachada) { return; }

    var id = sacarId(caja) || sacarId(fachada);
    if (!id) { return; }

    e.preventDefault();

    var marco = document.createElement('iframe');
    marco.className = 'eg-video-iframe';
    // rel=0 ya no quita los relacionados, pero si los limita al mismo canal
    // del video reproducido. Como los doce son del canal oficial, al terminar
    // solo se ofrecen mas videos de Hypershell. Por eso se queda.
    // modestbranding esta obsoleto y no hace nada: quitado para que no parezca
    // que hace algo.
    marco.src = 'https://www.youtube-nocookie.com/embed/' + id +
                '?autoplay=1&rel=0&playsinline=1&hl=es';
    marco.title = fachada.getAttribute('aria-label') || 'Vídeo';
    marco.setAttribute('allow',
      'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    marco.setAttribute('allowfullscreen', 'allowfullscreen');
    marco.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
    marco.setAttribute('loading', 'lazy');

    fachada.parentNode.replaceChild(marco, fachada);
  }, false);
})();
