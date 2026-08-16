/**
 * Pinta la descripcion de las categorias de producto.
 *
 * El tema no imprime term_description() en ninguna parte: el texto guardado
 * en la categoria solo acababa en la etiqueta og:description, invisible para
 * quien entra y sin valor para el buscador. Comprobado en /rapid-pro/, que
 * tiene descripcion desde hace meses y no la muestra.
 *
 * Se parte en dos por el marcador <!--eg-corte-->:
 *
 *   - lo de arriba va antes de la rejilla: una entrada corta y las tarjetas
 *     de subcategoria, que es lo que se espera al aterrizar en una categoria
 *   - lo de abajo va despues: comparativa, texto largo y preguntas
 *
 * Asi el comprador ve producto enseguida y el texto largo no le estorba,
 * que es como lo montan las tiendas grandes.
 */

/**
 * Permitir HTML en las descripciones de categoria.
 *
 * WordPress pasa la descripcion de un termino por wp_filter_kses tanto al
 * guardar como al mostrarla, y eso deja fuera tablas, <details> y los
 * atributos de schema. En una prueba se quedaron 57 caracteres de 334.
 *
 * El filtro de guardado solo se levanta para quien ya tiene la capacidad
 * unfiltered_html, es decir el administrador, que es quien puede publicar
 * HTML en cualquier entrada del sitio. Para el resto de perfiles la
 * limpieza sigue igual que antes.
 */
add_action( 'init', 'eg_permitir_html_en_categorias' );

function eg_permitir_html_en_categorias() {

	if ( current_user_can( 'unfiltered_html' ) ) {
		remove_filter( 'pre_term_description', 'wp_filter_kses' );
	}

	// Al mostrar hay que quitarlo siempre: si no, se limpia lo ya guardado.
	remove_filter( 'term_description', 'wp_kses_data' );
}


add_action( 'woocommerce_before_shop_loop', 'eg_categoria_texto_arriba', 5 );

function eg_categoria_texto_arriba() {

	$partes = eg_categoria_partes();

	if ( $partes ) {
		echo '<div class="eg-cat eg-cat-arriba">' . $partes[0] . '</div>';
	}
}

add_action( 'woocommerce_after_shop_loop', 'eg_categoria_texto_abajo', 20 );

function eg_categoria_texto_abajo() {

	$partes = eg_categoria_partes();

	if ( $partes && ! empty( $partes[1] ) ) {
		echo '<div class="eg-cat eg-cat-abajo">' . $partes[1] . '</div>';
	}
}

/**
 * Devuelve la descripcion de la categoria partida en dos, o false si no hay.
 *
 * Solo actua en la primera pagina: repetir el texto en /page/2/ seria
 * contenido duplicado dentro del propio sitio.
 */
function eg_categoria_partes() {

	if ( ! is_product_category() || is_paged() ) {
		return false;
	}

	$termino = get_queried_object();

	if ( empty( $termino->description ) ) {
		return false;
	}

	// Se parte antes de aplicar wpautop: si se hace al reves, wpautop envuelve
	// el marcador en un parrafo y la segunda mitad arranca con un </p> suelto.
	$partes = explode( '<!--eg-corte-->', $termino->description, 2 );

	$arriba = wpautop( do_shortcode( trim( $partes[0] ) ) );
	$abajo  = isset( $partes[1] ) ? wpautop( do_shortcode( trim( $partes[1] ) ) ) : '';

	return array( $arriba, $abajo );
}


/**
 * Estilos de las categorias.
 *
 * Se imprimen aqui y no en el kit de Elementor por dos motivos: solo hacen
 * falta en las paginas de categoria, y el kit guarda ya 61 KB de CSS del
 * blog y de las fichas que no conviene reescribir entero por un anadido.
 */
add_action( 'wp_head', 'eg_estilos_categorias', 99 );

function eg_estilos_categorias() {

	if ( ! is_product_category() ) {
		return;
	}

	echo "<style id='eg-cat-css'>" . <<<'EGCSS'
/* ==========================================================================
   Categorias de producto · ecogadgetoficial.com
   --------------------------------------------------------------------------
   El tema no imprime term_description(). Lo hace el snippet
   "EG · Descripcion de categorias", que parte el texto por <!--eg-corte-->:
   .eg-cat-arriba se pinta antes de la rejilla y .eg-cat-abajo despues.

   Igual que en las fichas, las reglas llevan prefijo body porque el CSS de
   producto se carga en linea en el pie, despues del kit de Elementor, y sin
   ese prefijo gana el otro.
   ========================================================================== */

body .eg-cat { max-width: 100%; }
body .eg-cat-arriba { margin: 0 0 26px; }
body .eg-cat-abajo { margin: 46px 0 0; padding-top: 34px; border-top: 1px solid #e6e9ee; }

/* --- Entrada --- */

body .eg-cat-lead {
  font-size: 16.5px !important; line-height: 1.62 !important;
  color: #2c3542 !important; margin: 0 0 12px !important; max-width: 74ch;
}
body .eg-cat-regla {
  font-size: 15px !important; line-height: 1.55 !important;
  color: #14532d !important; background: #f0faf3; border-left: 3px solid #22c55e;
  border-radius: 0 8px 8px 0; padding: 11px 16px !important; margin: 0 0 22px !important; max-width: 74ch;
}
body .eg-cat-ayuda {
  font-size: 14.5px !important; color: #5a6675 !important;
  margin: 18px 0 0 !important; max-width: 74ch;
}

/* --- Tarjetas de subcategoria: la navegacion visual --- */

body .eg-cat-nav,
body .eg-cat-guias {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(196px, 1fr)) !important;
  gap: 12px !important; margin: 0 !important; padding: 0 !important; list-style: none !important;
}
body .eg-cat-guias { margin-top: 6px !important; }

body .eg-cat-nav a,
body .eg-cat-guias a {
  display: block !important; text-decoration: none !important;
  background: #fff !important; border: 1px solid #dfe4ec !important;
  border-radius: 12px !important; padding: 15px 17px !important;
  transition: border-color .16s ease, box-shadow .16s ease, transform .16s ease;
}
body .eg-cat-nav a:hover,
body .eg-cat-guias a:hover {
  border-color: #185fa5 !important;
  box-shadow: 0 4px 14px rgba(4, 44, 83, .09) !important;
  transform: translateY(-1px);
}
body .eg-cat-nav a b,
body .eg-cat-guias a b {
  display: block !important; font-size: 15px !important; font-weight: 600 !important;
  color: #042c53 !important; margin-bottom: 3px !important; line-height: 1.3 !important;
}
body .eg-cat-nav a span,
body .eg-cat-guias a span {
  display: block !important; font-size: 13px !important; line-height: 1.45 !important;
  color: #667487 !important;
}

/* --- Bloque de abajo: comparativa y texto --- */

body .eg-cat-seo h2 {
  font-size: 22px !important; line-height: 1.3 !important; font-weight: 600 !important;
  color: #101a26 !important; margin: 34px 0 12px !important;
}
body .eg-cat-seo h2:first-child { margin-top: 0 !important; }
body .eg-cat-seo h3 {
  font-size: 16.5px !important; font-weight: 600 !important;
  color: #1b2734 !important; margin: 22px 0 7px !important;
}
body .eg-cat-seo p {
  font-size: 15.5px !important; line-height: 1.68 !important;
  color: #37404d !important; margin: 0 0 13px !important; max-width: 76ch;
}
body .eg-cat-nota {
  font-size: 13.5px !important; color: #6b7686 !important; font-style: italic;
}

/* --- Tabla comparativa --- */

body .eg-cat-tabla {
  width: 100% !important; border-collapse: collapse !important;
  margin: 6px 0 14px !important; font-size: 14.5px !important;
  display: block; overflow-x: auto; white-space: nowrap;
}
@media (min-width: 780px) {
  body .eg-cat-tabla { display: table; white-space: normal; }
}
body .eg-cat-tabla th,
body .eg-cat-tabla td {
  padding: 11px 14px !important; text-align: left !important;
  border-bottom: 1px solid #e6e9ee !important; vertical-align: top !important;
}
body .eg-cat-tabla tr:first-child th {
  background: #f4f7fb !important; font-weight: 600 !important;
  color: #042c53 !important; font-size: 13.5px !important;
  text-transform: uppercase; letter-spacing: .03em;
}
body .eg-cat-tabla tr:last-child td { border-bottom: 0 !important; }
body .eg-cat-tabla tbody tr:not(:first-child):hover { background: #fafbfd !important; }
body .eg-cat-tabla a { color: #185fa5 !important; text-decoration: none !important; }
body .eg-cat-tabla a:hover { text-decoration: underline !important; }

/* --- Preguntas frecuentes --- */

body .eg-cat-faq {
  border: 1px solid #e2e7ee !important; border-radius: 10px !important;
  margin: 0 0 9px !important; background: #fff !important; overflow: hidden;
}
body .eg-cat-faq > summary {
  cursor: pointer; list-style: none; padding: 14px 44px 14px 17px !important;
  font-size: 15.5px !important; font-weight: 600 !important; color: #16202c !important;
  position: relative; line-height: 1.45 !important;
}
body .eg-cat-faq > summary::-webkit-details-marker { display: none; }
body .eg-cat-faq > summary::after {
  content: "+"; position: absolute; right: 17px; top: 50%; transform: translateY(-50%);
  font-size: 20px; font-weight: 400; color: #7d8899; line-height: 1;
}
body .eg-cat-faq[open] > summary::after { content: "\2212"; }
body .eg-cat-faq[open] > summary { border-bottom: 1px solid #eef1f5 !important; }
body .eg-cat-faq:hover { border-color: #c9d4e2 !important; }
body .eg-cat-a { padding: 14px 17px 4px !important; }
body .eg-cat-a p { font-size: 15px !important; margin-bottom: 11px !important; }
body .eg-cat-a a { color: #185fa5 !important; }

@media (prefers-reduced-motion: reduce) {
  body .eg-cat-nav a, body .eg-cat-guias a { transition: none !important; }
  body .eg-cat-nav a:hover, body .eg-cat-guias a:hover { transform: none !important; }
}

@media (max-width: 600px) {
  body .eg-cat-lead { font-size: 15.5px !important; }
  body .eg-cat-seo h2 { font-size: 19.5px !important; }
  body .eg-cat-nav, body .eg-cat-guias { grid-template-columns: 1fr 1fr !important; gap: 9px !important; }
  body .eg-cat-nav a, body .eg-cat-guias a { padding: 12px 13px !important; }
  body .eg-cat-nav a b, body .eg-cat-guias a b { font-size: 14px !important; }
  body .eg-cat-nav a span, body .eg-cat-guias a span { font-size: 12px !important; }
}
EGCSS . "</style>";
}
