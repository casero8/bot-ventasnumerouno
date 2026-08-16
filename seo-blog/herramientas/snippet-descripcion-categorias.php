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


/**
 * Migas de pan y numero de productos, encima del titulo.
 *
 * El tema no imprime migas en las categorias: solo estan en el schema, y el
 * ajuste de la plantilla trae --breadcrumb-height en 0. Sin ellas, quien
 * llega desde el buscador no sabe donde esta ni como subir un nivel, que es
 * justo lo que resuelven las tiendas grandes con la linea de arriba.
 *
 * Se imprime como hermano del <h1> y se coloca encima con order en CSS,
 * porque el gancho de WooCommerce corre despues de que el tema pinte el
 * titulo y no hay forma limpia de adelantarlo.
 */
add_action( 'woocommerce_before_shop_loop', 'eg_categoria_cabecera', 3 );

function eg_categoria_cabecera() {

	if ( ! is_product_category() ) {
		return;
	}

	$termino = get_queried_object();
	$ruta    = array();

	foreach ( array_reverse( get_ancestors( $termino->term_id, 'product_cat' ) ) as $id ) {
		$padre = get_term( $id, 'product_cat' );

		if ( $padre && ! is_wp_error( $padre ) ) {
			$ruta[] = '<a href="' . esc_url( get_term_link( $padre ) ) . '">' . esc_html( $padre->name ) . '</a>';
		}
	}

	// El count del termino solo cuenta lo asignado directamente: en DELTA 3
	// daba 6 en vez de 23. El total real lo tiene la consulta principal, que
	// si incluye los productos de las subcategorias.
	$total = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : (int) $termino->count;

	echo '<nav class="eg-ruta" aria-label="Migas de pan"><span class="eg-ruta-links">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">Inicio</a>';

	foreach ( $ruta as $enlace ) {
		echo '<i>&rsaquo;</i>' . $enlace;
	}

	echo '<i>&rsaquo;</i><b>' . esc_html( $termino->name ) . '</b>';
	echo '</span>';

	if ( $total > 0 ) {
		echo '<span class="eg-ruta-total">' . $total . ' ' . ( 1 === $total ? 'producto' : 'productos' ) . '</span>';
	}

	echo '</nav>';
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

	// Sin wpautop: el texto de las categorias ya viene con sus propios <p>, y
	// wpautop convierte los saltos de linea del maquetado en <br />, que se
	// cuelan dentro de las tarjetas y las descuadran.
	$arriba = do_shortcode( trim( $partes[0] ) );
	$abajo  = isset( $partes[1] ) ? do_shortcode( trim( $partes[1] ) ) : '';

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

   Las reglas llevan prefijo body porque el CSS de producto se carga en linea
   en el pie, despues del kit de Elementor, y sin ese prefijo gana el otro.
   ========================================================================== */

body .eg-cat {
  --eg-azul: #042c53;
  --eg-azul-medio: #185fa5;
  --eg-verde: #0f8a4a;
  --eg-borde: #e1e6ee;
  --eg-texto: #37404d;
  --eg-suave: #6b7686;
  max-width: 100%;
}
body .eg-cat-arriba { margin: 0 0 30px; }
body .eg-cat-abajo { margin: 52px 0 0; padding-top: 38px; border-top: 1px solid var(--eg-borde); }

/* ====================== CABECERA DE LA CATEGORIA ======================
   Ajustes sobre el tema. Van sin el prefijo .eg-cat porque tocan elementos
   que pinta la plantilla, y solo se cargan en paginas de categoria.
   ===================================================================== */

/* El tema deja 100 px de margen entre la cabecera del sitio y el contenido.
   En una categoria eso es media pantalla en blanco antes de ver un producto. */
body.tax-product_cat .page-content { margin-top: 28px !important; }

/* Migas de pan: se imprimen despues del <h1> y se suben con order. */
body.tax-product_cat .shop-archive-block { display: flex; flex-direction: column; }
body.tax-product_cat .shop-archive-block > .eg-ruta { order: -1; }

body .eg-ruta {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 10px; margin: 0 0 9px;
  font-size: 13px; line-height: 1.5; color: #7a8595;
}
body .eg-ruta-links { color: #7a8595; }
body .eg-ruta a { color: #7a8595 !important; text-decoration: none !important; }
body .eg-ruta a:hover { color: #185fa5 !important; text-decoration: underline !important; }
body .eg-ruta i { font-style: normal; margin: 0 7px; color: #b9c2ce; }
body .eg-ruta b { color: #16202c; font-weight: 600; }
body .eg-ruta-total {
  font-size: 12.5px; color: #5f6b7c; background: #f2f5f9;
  border-radius: 999px; padding: 4px 12px; white-space: nowrap;
}

/* El titulo: el tema lo deja en 34 px con peso 400, igual que el rotulo
   "Filtros" de la barra lateral, y los dos compiten. */
body.tax-product_cat h1.page-title {
  font-size: 34px !important; font-weight: 600 !important;
  letter-spacing: -.02em !important; color: #0d1520 !important;
  line-height: 1.15 !important; margin: 0 0 18px !important;
}

/* La barra lateral de filtros: rotulo de interfaz, no titulo de pagina. */
body.tax-product_cat .sidebar-top-heading {
  font-size: 12.5px !important; font-weight: 700 !important;
  text-transform: uppercase !important; letter-spacing: .09em !important;
  color: #5f6b7c !important; margin: 0 0 14px !important;
  padding-bottom: 11px !important; border-bottom: 1px solid #e4e8ee !important;
  line-height: 1.3 !important;
}
body.tax-product_cat .page-sidebar .widget > h2,
body.tax-product_cat .page-sidebar .widget-title {
  font-size: 13.5px !important; font-weight: 700 !important;
  color: #16202c !important; letter-spacing: .01em !important;
  margin-bottom: 9px !important;
}
body.tax-product_cat .page-sidebar .widget { margin-bottom: 26px !important; }
body.tax-product_cat .page-sidebar li { line-height: 1.35 !important; }
body.tax-product_cat .page-sidebar li a {
  font-size: 14px !important; padding: 5px 0 !important;
  display: inline-block !important; color: #48535f !important;
}
body.tax-product_cat .page-sidebar li a:hover { color: #185fa5 !important; }

/* --- Filtro de categorias acotado (estilo tienda grande) --- */

body.tax-product_cat .eg-refina {
  list-style: none !important; margin: 0 !important; padding: 0 !important;
  /* El tema maqueta las listas de la barra lateral en varias columnas y
     partia esta por la mitad, con los nombres superpuestos. Es la misma
     causa por la que un max-height con scroll tampoco funciona aqui. */
  display: block !important;
  columns: 1 !important; column-count: 1 !important; column-width: auto !important;
}
body.tax-product_cat .eg-refina li { display: block !important; break-inside: avoid; }
body.tax-product_cat .eg-refina li { margin: 0 !important; line-height: 1.35 !important; }

body.tax-product_cat .eg-refina-arriba a {
  display: inline-block !important; font-size: 13.5px !important;
  color: #7a8595 !important; padding: 4px 0 !important; font-weight: 500 !important;
}
body.tax-product_cat .eg-refina-arriba a:hover { color: #185fa5 !important; }

body.tax-product_cat .eg-refina-actual {
  font-size: 14.5px !important; font-weight: 700 !important;
  color: #0d1520 !important; padding: 6px 0 8px !important;
  margin-bottom: 4px !important; border-bottom: 1px solid #eceff4 !important;
}

body.tax-product_cat .eg-refina li > a {
  display: flex !important; align-items: baseline !important;
  justify-content: space-between !important; gap: 10px !important;
  font-size: 14px !important; color: #48535f !important;
  padding: 6px 0 !important; text-decoration: none !important;
}
body.tax-product_cat .eg-refina li > a:hover { color: #185fa5 !important; }
body.tax-product_cat .eg-refina li > a em {
  font-style: normal !important; font-size: 12px !important;
  color: #97a1af !important; flex-shrink: 0 !important;
}

@media (max-width: 991px) {
  body.tax-product_cat .page-content { margin-top: 20px !important; }
  body.tax-product_cat h1.page-title { font-size: 27px !important; margin-bottom: 14px !important; }
}

/* ============================== ENTRADA ============================== */

body .eg-hero {
  background: linear-gradient(135deg, #f4f8fd 0%, #eef4fb 100%);
  border: 1px solid #dbe6f3; border-radius: 16px;
  padding: 24px 26px; margin: 0 0 16px;
}
body .eg-hero-lead {
  font-size: 17px !important; line-height: 1.6 !important;
  color: #16283d !important; margin: 0 0 18px !important; max-width: 80ch;
}
body .eg-hero-lead strong { color: var(--eg-azul) !important; font-weight: 600 !important; }

body .eg-hero-specs {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(168px, 1fr)) !important;
  gap: 10px !important; margin: 0 !important; padding: 0 !important; list-style: none !important;
}
body .eg-hero-specs li {
  background: #fff; border: 1px solid #dde6f1; border-radius: 11px;
  padding: 12px 14px; margin: 0 !important; list-style: none !important;
  font-size: 12.5px; line-height: 1.4; color: var(--eg-suave);
  display: block;
}
body .eg-hero-specs li span { font-size: 17px; display: block; margin-bottom: 5px; line-height: 1; }
body .eg-hero-specs li b {
  display: block; font-size: 15px; font-weight: 600;
  color: var(--eg-azul); margin-bottom: 2px; letter-spacing: -.01em;
}

/* --- La regla destacada --- */

body .eg-regla {
  display: flex; align-items: flex-start; gap: 12px;
  background: #f2fbf5; border: 1px solid #c9ecd5;
  border-radius: 12px; padding: 14px 17px; margin: 0 0 30px;
}
body .eg-regla-icono { font-size: 19px; line-height: 1.3; flex-shrink: 0; }
body .eg-regla p {
  margin: 0 !important; font-size: 15px !important; line-height: 1.55 !important;
  color: #14532d !important;
}
body .eg-regla strong { color: #0b3f22 !important; font-weight: 600 !important; }

/* --- Titulo de la navegacion --- */

body .eg-h-nav {
  font-size: 19px !important; font-weight: 600 !important;
  color: #101a26 !important; margin: 0 0 14px !important;
}

/* ================== TARJETAS DE SUBCATEGORIA ================== */

body .eg-cat-nav {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(178px, 1fr)) !important;
  gap: 14px !important; margin: 0 !important; padding: 0 !important; list-style: none !important;
}
body .eg-cat-nav a {
  display: flex !important; flex-direction: column !important; align-items: center !important;
  text-align: center !important; text-decoration: none !important;
  background: #fff !important; border: 1px solid var(--eg-borde) !important;
  border-radius: 14px !important; padding: 16px 14px 15px !important;
  transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
}
body .eg-cat-nav a:hover {
  border-color: var(--eg-azul-medio) !important;
  box-shadow: 0 8px 22px rgba(4, 44, 83, .11) !important;
  transform: translateY(-3px);
}
body .eg-nav-img {
  display: flex !important; align-items: center; justify-content: center;
  width: 100%; height: 118px; margin-bottom: 11px;
  background: #f7f9fc; border-radius: 10px; overflow: hidden;
}
body .eg-cat-nav a img {
  max-width: 88% !important; max-height: 100px !important;
  width: auto !important; height: auto !important; object-fit: contain !important;
  mix-blend-mode: multiply;
}
body .eg-cat-nav a b {
  display: block !important; font-size: 15px !important; font-weight: 600 !important;
  color: var(--eg-azul) !important; line-height: 1.3 !important; margin-bottom: 4px !important;
}
body .eg-nav-dato {
  display: block !important; font-size: 12.5px !important; line-height: 1.42 !important;
  color: var(--eg-suave) !important; margin-bottom: 10px !important;
}
body .eg-nav-mas {
  display: inline-block !important; margin-top: auto !important;
  font-size: 12.5px !important; font-weight: 600 !important;
  color: var(--eg-azul-medio) !important;
  border: 1px solid #cfdcec !important; border-radius: 999px !important;
  padding: 5px 14px !important; background: #f6f9fd !important;
  transition: background .18s ease, border-color .18s ease, color .18s ease;
}
body .eg-cat-nav a:hover .eg-nav-mas {
  background: var(--eg-azul-medio) !important; border-color: var(--eg-azul-medio) !important;
  color: #fff !important;
}

body .eg-cat-ayuda {
  font-size: 14.5px !important; color: var(--eg-suave) !important;
  margin: 20px 0 0 !important; max-width: 78ch;
}
body .eg-cat-ayuda a { color: var(--eg-azul-medio) !important; }

/* ====================== BLOQUE DE ABAJO ====================== */

body .eg-cat-seo h2 {
  font-size: 23px !important; line-height: 1.28 !important; font-weight: 600 !important;
  color: #101a26 !important; margin: 40px 0 12px !important;
  letter-spacing: -.015em;
}
body .eg-cat-seo h2:first-child { margin-top: 0 !important; }
body .eg-cat-seo p {
  font-size: 15.5px !important; line-height: 1.68 !important;
  color: var(--eg-texto) !important; margin: 0 0 13px !important; max-width: 78ch;
}
body .eg-cat-nota {
  font-size: 13.5px !important; color: var(--eg-suave) !important;
  background: #fafbfc; border-left: 3px solid #dfe5ed;
  padding: 11px 15px !important; border-radius: 0 8px 8px 0;
  margin-top: 10px !important; max-width: none !important;
}

/* --- Tabla comparativa --- */

body .eg-tabla-scroll { overflow-x: auto; margin: 8px 0 6px; -webkit-overflow-scrolling: touch; }
body .eg-cat-tabla {
  width: 100% !important; border-collapse: separate !important; border-spacing: 0 !important;
  font-size: 14.5px !important; min-width: 620px;
  border: 1px solid var(--eg-borde) !important; border-radius: 12px !important; overflow: hidden;
}
body .eg-cat-tabla th,
body .eg-cat-tabla td {
  padding: 13px 15px !important; text-align: left !important;
  border-bottom: 1px solid #eef1f6 !important; vertical-align: middle !important;
}
body .eg-cat-tabla tr:first-child th {
  background: var(--eg-azul) !important; color: #fff !important;
  font-weight: 600 !important; font-size: 12.5px !important;
  text-transform: uppercase; letter-spacing: .05em; border-bottom: 0 !important;
}
body .eg-cat-tabla tr:last-child td { border-bottom: 0 !important; }
body .eg-cat-tabla .eg-fila-ok td { background: #fcfdff !important; }
body .eg-cat-tabla .eg-fila-destacada td { background: #f1f7fe !important; }
/* Solo la primera celda lleva la barra: en todas dibuja una linea entre columnas. */
body .eg-cat-tabla .eg-fila-destacada td:first-child { box-shadow: inset 3px 0 0 var(--eg-azul-medio); }
body .eg-cat-tabla a { color: var(--eg-azul-medio) !important; text-decoration: none !important; }
body .eg-cat-tabla a:hover { text-decoration: underline !important; }
body .eg-cat-tabla strong { color: var(--eg-azul) !important; }

body .eg-si { color: var(--eg-verde) !important; font-weight: 700 !important; font-size: 15px; }
body .eg-no { color: #b6bfcc !important; font-weight: 700 !important; }
body .eg-precio { color: var(--eg-azul) !important; font-size: 15.5px !important; font-weight: 700 !important; }
body .eg-consulta { font-size: 13.5px !important; }

body .eg-tag {
  display: inline-block; font-size: 10.5px; font-weight: 600;
  text-transform: uppercase; letter-spacing: .04em;
  padding: 3px 8px; border-radius: 999px; margin-left: 5px; white-space: nowrap;
}
body .eg-tag-verde { background: #e4f6ea; color: #0f6b39; }
body .eg-tag-azul { background: #e4eefb; color: #10457c; }

/* --- Los tres pasos --- */

body .eg-pasos {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(258px, 1fr)) !important;
  gap: 15px !important; margin: 6px 0 0 !important;
}
body .eg-paso {
  background: #fff; border: 1px solid var(--eg-borde);
  border-radius: 14px; padding: 20px 20px 14px; position: relative;
}
body .eg-paso-num {
  display: flex; align-items: center; justify-content: center;
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--eg-azul); color: #fff;
  font-size: 15px; font-weight: 700; margin-bottom: 11px;
}
body .eg-paso h3 {
  font-size: 16px !important; font-weight: 600 !important;
  color: #101a26 !important; margin: 0 0 8px !important; line-height: 1.35 !important;
}
body .eg-paso p { font-size: 14.5px !important; line-height: 1.6 !important; margin: 0 !important; }
body .eg-paso a { color: var(--eg-azul-medio) !important; }

/* --- Preguntas frecuentes --- */

body .eg-cat-faq {
  border: 1px solid var(--eg-borde) !important; border-radius: 11px !important;
  margin: 0 0 9px !important; background: #fff !important; overflow: hidden;
  transition: border-color .16s ease, box-shadow .16s ease;
}
body .eg-cat-faq > summary {
  cursor: pointer; list-style: none; padding: 15px 46px 15px 18px !important;
  font-size: 15.5px !important; font-weight: 600 !important; color: #16202c !important;
  position: relative; line-height: 1.45 !important;
}
body .eg-cat-faq > summary::-webkit-details-marker { display: none; }
body .eg-cat-faq > summary::after {
  content: "+"; position: absolute; right: 16px; top: 50%;
  transform: translateY(-50%); width: 24px; height: 24px;
  display: flex; align-items: center; justify-content: center;
  background: #f2f5f9; border-radius: 50%;
  font-size: 16px; font-weight: 400; color: #56637a; line-height: 1;
}
body .eg-cat-faq[open] > summary::after { content: "\2212"; background: #e4eefb; color: var(--eg-azul-medio); }
body .eg-cat-faq[open] > summary { border-bottom: 1px solid #eef1f5 !important; }
body .eg-cat-faq:hover { border-color: #c3d2e4 !important; box-shadow: 0 2px 8px rgba(4,44,83,.05) !important; }
body .eg-cat-a { padding: 15px 18px 5px !important; }
body .eg-cat-a p { font-size: 15px !important; margin-bottom: 11px !important; }
body .eg-cat-a a { color: var(--eg-azul-medio) !important; }

/* --- Guias --- */

body .eg-cat-guias {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(212px, 1fr)) !important;
  gap: 13px !important; margin: 4px 0 0 !important; padding: 0 !important;
}
body .eg-cat-guias a {
  display: block !important; text-decoration: none !important;
  background: #fff !important; border: 1px solid var(--eg-borde) !important;
  border-radius: 13px !important; padding: 17px 18px !important;
  transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
}
body .eg-cat-guias a:hover {
  border-color: var(--eg-azul-medio) !important;
  box-shadow: 0 6px 18px rgba(4,44,83,.09) !important; transform: translateY(-2px);
}
body .eg-cat-guias a span { font-size: 21px; display: block; margin-bottom: 8px; line-height: 1; }
body .eg-cat-guias a b {
  display: block !important; font-size: 14.5px !important; font-weight: 600 !important;
  color: var(--eg-azul) !important; margin-bottom: 3px !important; line-height: 1.3 !important;
}
body .eg-cat-guias a em {
  display: block !important; font-style: normal !important;
  font-size: 12.5px !important; line-height: 1.45 !important; color: var(--eg-suave) !important;
}

/* --- Franja de confianza --- */

body .eg-confianza {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(206px, 1fr)) !important;
  gap: 1px !important; margin: 34px 0 0 !important;
  background: var(--eg-borde); border: 1px solid var(--eg-borde);
  border-radius: 13px; overflow: hidden;
}
body .eg-confianza div { background: #fbfcfe; padding: 16px 18px; }
body .eg-confianza b {
  display: block; font-size: 14px; font-weight: 600;
  color: var(--eg-azul); margin-bottom: 3px;
}
body .eg-confianza span { display: block; font-size: 12.5px; line-height: 1.45; color: var(--eg-suave); }

/* ============================ AJUSTES ============================ */

@media (prefers-reduced-motion: reduce) {
  body .eg-cat-nav a, body .eg-cat-guias a, body .eg-cat-faq { transition: none !important; }
  body .eg-cat-nav a:hover, body .eg-cat-guias a:hover { transform: none !important; }
}

@media (max-width: 700px) {
  body .eg-hero { padding: 19px 18px; border-radius: 13px; }
  body .eg-hero-lead { font-size: 15.5px !important; }
  body .eg-hero-specs { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }
  body .eg-hero-specs li { padding: 10px 12px; }
  body .eg-cat-seo h2 { font-size: 20px !important; }
  body .eg-cat-nav { grid-template-columns: 1fr 1fr !important; gap: 10px !important; }
  body .eg-nav-img { height: 92px; }
  body .eg-cat-nav a { padding: 12px 10px 13px !important; }
  body .eg-cat-nav a b { font-size: 14px !important; }
  body .eg-nav-dato { font-size: 11.5px !important; }
  body .eg-confianza { grid-template-columns: 1fr 1fr !important; }
}
EGCSS . "</style>";
}


/**
 * Lo que se puede comprar, primero.
 *
 * Por defecto la rejilla saca los productos por orden de menu, y en DELTA 3
 * eso llenaba la primera fila entera de "AGOTADO": quien entra desde una
 * busqueda ve cuatro productos que no puede comprar antes que ninguno
 * disponible. El orden dentro de cada grupo no se toca, solo se suben los
 * que tienen stock.
 *
 * Se apoya en wc_product_meta_lookup, la tabla que WooCommerce ya cruza en
 * las consultas de la tienda, en vez de anadir un JOIN propio a postmeta.
 */
add_filter( 'posts_clauses', 'eg_stock_primero', 20, 2 );

function eg_stock_primero( $clauses, $consulta ) {

	if ( is_admin() || ! $consulta->is_main_query() || ! is_product_category() ) {
		return $clauses;
	}

	// Si el usuario ha pedido un orden concreto, manda el suyo.
	if ( ! empty( $_GET['orderby'] ) ) {
		return $clauses;
	}

	global $wpdb;

	if ( false !== strpos( $clauses['join'], 'wc_product_meta_lookup' ) ) {
		$orden = "wc_product_meta_lookup.stock_status = 'instock' DESC";
	} else {
		// Segun la version de WooCommerce la tabla de consulta no siempre se
		// cruza, asi que se anade el cruce a postmeta. La clave _stock_status
		// es unica por producto, de modo que no duplica filas.
		$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS eg_stock"
			. " ON ( {$wpdb->posts}.ID = eg_stock.post_id AND eg_stock.meta_key = '_stock_status' )";
		$orden = "eg_stock.meta_value = 'instock' DESC";
	}

	$clauses['orderby'] = empty( $clauses['orderby'] ) ? $orden : $orden . ', ' . $clauses['orderby'];

	return $clauses;
}

/**
 * El filtro de categorias, acotado a donde estas.
 *
 * El widget del tema listaba las 59 categorias de la tienda dentro de
 * cualquier categoria: estando en DELTA 3 ofrecia Lokithor, TRAIL DC o
 * Automocion. Las tiendas grandes no hacen eso: te enseñan la rama en la
 * que estas y en que puedes afinar dentro de ella.
 *
 * Se sustituye la salida del widget en su mismo sitio: widget_display_callback
 * corre antes de que el widget pinte nada, asi que se imprime lo nuestro y se
 * devuelve false para que el original no salga. Fuera de las categorias de
 * producto no se toca, y en la tienda general sigue apareciendo el listado
 * completo, que ahi si tiene sentido.
 */
add_filter( 'widget_display_callback', 'eg_filtro_categorias_acotado', 10, 3 );

function eg_filtro_categorias_acotado( $instancia, $widget, $args ) {

	if ( ! is_product_category() || ! is_object( $widget ) ) {
		return $instancia;
	}

	if ( false === strpos( $widget->id_base, 'product-categories-layered-nav' ) ) {
		return $instancia;
	}

	$actual = get_queried_object();

	if ( ! $actual || empty( $actual->term_id ) ) {
		return $instancia;
	}

	$hijas = get_terms( array(
		'taxonomy'   => 'product_cat',
		'parent'     => $actual->term_id,
		'hide_empty' => true,
	) );

	// Sin hijas se muestran las hermanas: asi se salta entre modelos de la
	// misma gama sin tener que volver atras.
	$lista   = ( ! is_wp_error( $hijas ) && $hijas ) ? $hijas : array();
	$titulo  = 'Dentro de ' . $actual->name;

	if ( ! $lista && $actual->parent ) {
		$hermanas = get_terms( array(
			'taxonomy'   => 'product_cat',
			'parent'     => $actual->parent,
			'hide_empty' => true,
			'exclude'    => array( $actual->term_id ),
		) );

		if ( ! is_wp_error( $hermanas ) && $hermanas ) {
			$padre  = get_term( $actual->parent, 'product_cat' );
			$lista  = $hermanas;
			$titulo = $padre && ! is_wp_error( $padre ) ? 'Más de ' . $padre->name : 'Categorías relacionadas';
		}
	}

	$ancestros = array_reverse( get_ancestors( $actual->term_id, 'product_cat' ) );

	echo $args['before_widget'];
	echo $args['before_title'] . esc_html( $titulo ) . $args['after_title'];

	echo '<ul class="eg-refina">';

	foreach ( $ancestros as $id ) {
		$padre = get_term( $id, 'product_cat' );

		if ( $padre && ! is_wp_error( $padre ) ) {
			echo '<li class="eg-refina-arriba"><a href="' . esc_url( get_term_link( $padre ) ) . '">'
				. '&lsaquo; ' . esc_html( $padre->name ) . '</a></li>';
		}
	}

	echo '<li class="eg-refina-actual">' . esc_html( $actual->name ) . '</li>';

	foreach ( $lista as $hija ) {
		echo '<li><a href="' . esc_url( get_term_link( $hija ) ) . '">'
			. esc_html( $hija->name ) . ' <em>' . (int) $hija->count . '</em></a></li>';
	}

	echo '</ul>';
	echo $args['after_widget'];

	return false;
}
