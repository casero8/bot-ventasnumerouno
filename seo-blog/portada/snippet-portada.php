<?php
/**
 * EG · Portada
 * ---------------------------------------------------------------------------
 * Portada en HTML y CSS puros: sin Elementor y sin una sola linea de
 * JavaScript. El motivo es medible: la portada con Elementor arrastraba
 * 2,9 MB de CSS y JS. Esta carga una hoja de estilos de ~12 KB en linea.
 *
 * Se pinta con el shortcode [eg_portada] en una pagina normal (editor clasico
 * o de bloques). NUNCA editar esa pagina con Elementor: en cuanto se abre con
 * el constructor vuelve a cargarse todo su CSS y se pierde el motivo de esto.
 *
 * Precios, stock, imagenes y enlaces se leen de WooCommerce en cada
 * generacion. Con la cache de pagina activa se refrescan al caducar la cache
 * o al editar un producto; nunca quedan escritos a mano.
 *
 * Sobre las tildes: todos los textos fijos van en entidades HTML
 * (&aacute;, &ntilde;...). Los snippets viajan por copia-pega entre editores y
 * los acentos en UTF-8 se han perdido ya dos veces en este proyecto. Las
 * entidades no se pierden. Los textos dinamicos (nombres de producto) si van
 * por esc_html, que es lo correcto para contenido que no controlamos.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ==========================================================================
   1. Categorias de la rejilla principal
   slug => array( titulo, descripcion corta, texto del enlace )
   Las que no existan todavia se saltan solas.
   ========================================================================== */

function eg_portada_categorias() {
	return array(
		'serie-delta'        => array( 'Estaciones DELTA',    'Para casa, apagones y consumos grandes',  'Ver estaciones DELTA' ),
		'serie-river'        => array( 'Bater&iacute;as RIVER', 'Ligeras, para camping y furgoneta',     'Ver bater&iacute;as RIVER' ),
		'paneles-solares'    => array( 'Placas solares',      'Port&aacute;tiles y para balc&oacute;n',  'Ver placas solares' ),
		'serie-rapid'        => array( 'Powerbanks RAPID',    'M&oacute;vil, port&aacute;til y carga r&aacute;pida', 'Ver powerbanks' ),
		'kits-para-el-hogar' => array( 'Kits para el hogar',  'Balc&oacute;n, autoconsumo y respaldo',   'Ver kits' ),
		'stream-series'      => array( 'EcoFlow STREAM',      'Microinversor y bater&iacute;a de balc&oacute;n', 'Ver STREAM' ),
		'accesorios'         => array( 'Accesorios',          'Cables, fundas y adaptadores',            'Ver accesorios' ),
		'generador-solar'    => array( 'Generadores solares', 'Bater&iacute;a y placa, listo para usar', 'Ver generadores' ),
	);
}

/* ==========================================================================
   2. Shortcode
   ========================================================================== */

add_shortcode( 'eg_portada', 'eg_portada_html' );

function eg_portada_html() {

	$tienda = esc_url( wc_get_page_permalink( 'shop' ) );

	$h  = '<div class="eg-home">';
	$h .= '<a class="eg-saltar" href="#eg-comprar">Saltar a los productos</a>';

	$h .= eg_portada_hero( $tienda );

	$h .= '<div class="eg-ancho">';
	$h .= eg_portada_avales();

	$h .= '<section class="eg-seccion" id="eg-comprar" aria-labelledby="eg-t-cat">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-cat">Compra por categor&iacute;a</h2>'
		. '<p>Todo el cat&aacute;logo EcoFlow ordenado por tipo de equipo.</p>'
		. '</div><a class="eg-vertodo" href="' . $tienda . '">Ver toda la tienda</a></div>'
		. eg_portada_rejilla_categorias()
		. '</section>';

	$h .= '<section class="eg-seccion" aria-labelledby="eg-t-uso">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-uso">&iquest;Qu&eacute; quieres alimentar?</h2>'
		. '<p>Si no sabes qu&eacute; modelo necesitas, empieza por aqu&iacute;.</p>'
		. '</div></div>'
		. eg_portada_usos()
		. '</section>';

	$h .= eg_portada_productos( $tienda );

	$h .= '<section class="eg-seccion" aria-labelledby="eg-t-series">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-series">Las series de EcoFlow, en corto</h2>'
		. '<p>Cuatro familias y para qu&eacute; usa la gente cada una.</p>'
		. '</div></div>'
		. eg_portada_tabla_series()
		. '</section>';

	$h .= eg_portada_texto();

	$h .= '<section class="eg-seccion" aria-labelledby="eg-t-faq">'
		. '<div class="eg-seccion-cab"><div><h2 id="eg-t-faq">Preguntas frecuentes</h2></div></div>'
		. eg_portada_faq()
		. '</section>';

	$h .= eg_portada_cierre();
	$h .= '</div></div>';

	return $h;
}

/* ==========================================================================
   3. Bloque de portada
   ========================================================================== */

function eg_portada_hero( $tienda ) {

	$id   = (int) get_option( 'eg_portada_hero', 0 );
	$foto = '';

	if ( $id ) {
		// Sin lazy y con prioridad alta: es lo primero que ve el visitante y
		// es justo la imagen que no debe hacerse esperar.
		$foto = wp_get_attachment_image( $id, 'full', false, array(
			'class'         => 'eg-hero-foto',
			'alt'           => '',
			'aria-hidden'   => 'true',
			'loading'       => 'eager',
			'decoding'      => 'sync',
			'fetchpriority' => 'high',
		) );
	}

	return '<div class="eg-ancho"><div class="eg-hero">'
		. $foto
		. '<div class="eg-hero-texto">'
		. '<h1>Bater&iacute;as y placas solares EcoFlow con servicio oficial en Espa&ntilde;a</h1>'
		. '<p>Somos distribuidor oficial. Te asesoramos antes de comprar y, si algo falla, lo resolvemos nosotros sin intermediarios.</p>'
		. '<div class="eg-hero-botones">'
		. '<a class="eg-btn eg-btn-principal" href="' . $tienda . '">Ver el cat&aacute;logo</a>'
		. '<a class="eg-btn eg-btn-secundario" href="/contacto/">Preguntar antes de comprar</a>'
		. '</div></div></div></div>';
}

/* ==========================================================================
   4. Barra de confianza
   Cuatro hechos comprobables. Nada que luego no se pueda cumplir.
   ========================================================================== */

function eg_portada_avales() {

	$items = array(
		array( 'Distribuidor oficial',     'Producto EcoFlow con garant&iacute;a de fabricante.' ),
		array( 'Tienda f&iacute;sica',     'Puedes verlo y recogerlo en persona.' ),
		array( 'Servicio t&eacute;cnico propio', 'La incidencia la gestionamos nosotros.' ),
		array( 'Pago a plazos',            'Financiaci&oacute;n con SeQura al finalizar la compra.' ),
	);

	$h = '<div class="eg-avales">';
	foreach ( $items as $i ) {
		$h .= '<div class="eg-aval"><b>' . $i[0] . '</b><span>' . $i[1] . '</span></div>';
	}
	return $h . '</div>';
}

/* ==========================================================================
   5. Rejilla de categorias
   ========================================================================== */

function eg_portada_rejilla_categorias() {

	$h = '<div class="eg-rejilla">';
	$n = 0;

	foreach ( eg_portada_categorias() as $slug => $d ) {

		$t = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $t || is_wp_error( $t ) ) {
			continue;
		}

		$n++;
		$foto   = '';
		$img_id = (int) get_term_meta( $t->term_id, 'thumbnail_id', true );

		if ( $img_id ) {
			// Las cuatro primeras entran en pantalla: sin lazy. El resto si.
			$foto = wp_get_attachment_image( $img_id, 'woocommerce_thumbnail', false, array(
				'alt'     => '',
				'loading' => $n <= 4 ? 'eager' : 'lazy',
			) );
		}

		$h .= '<a class="eg-tarjeta" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<span class="eg-tarjeta-foto">' . $foto . '</span>'
			. '<b>' . $d[0] . '</b>'
			. '<small>' . $d[1] . '</small>'
			. '<i>' . $d[2] . ' &rarr;</i>'
			. '</a>';
	}

	return $h . '</div>';
}

/* ==========================================================================
   6. Eleccion por uso
   Cada tarjeta es un enlace interno con ancla descriptiva: ayuda al visitante
   que no sabe que comprar y a Google a entender la estructura del catalogo.
   ========================================================================== */

function eg_portada_usos() {

	$usos = array(
		array( 'M&oacute;vil, port&aacute;til y viaje', 'Cabe en la mochila y carga el tel&eacute;fono varias veces.', 'serie-rapid',     'Ver powerbanks RAPID' ),
		array( 'Camping, furgo y nevera',               'Peso contenido y suficiente para una nevera o una noche de luces.', 'serie-river', 'Ver bater&iacute;as RIVER' ),
		array( 'Apagones en casa',                      'Mantiene el frigor&iacute;fico, el router y algunas luces.', 'serie-delta',  'Ver estaciones DELTA' ),
		array( 'Bajar la factura',                      'Placas y kits de balc&oacute;n para producir tu propia electricidad.', 'paneles-solares', 'Ver placas solares' ),
	);

	$h = '<div class="eg-usos">';

	foreach ( $usos as $u ) {
		$t = get_term_by( 'slug', $u[2], 'product_cat' );
		if ( ! $t || is_wp_error( $t ) ) {
			continue;
		}
		$h .= '<a class="eg-uso" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<b>' . $u[0] . '</b>'
			. '<span>' . $u[1] . '</span>'
			. '<em>' . $u[3] . ' &rarr;</em>'
			. '</a>';
	}

	return $h . '</div>';
}

/* ==========================================================================
   7. Productos disponibles
   Solo con stock y con precio. Si no llegan a cuatro no se pinta la seccion:
   mejor no ensenar nada que ensenar una fila coja.
   ========================================================================== */

function eg_portada_productos( $tienda ) {

	$q = new WP_Query( array(
		'post_type'           => 'product',
		'posts_per_page'      => 8,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'orderby'             => 'meta_value_num',
		'meta_key'            => 'total_sales',
		'order'               => 'DESC',
		'meta_query'          => array(
			array( 'key' => '_stock_status', 'value' => 'instock' ),
			array( 'key' => '_price', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ),
		),
	) );

	if ( $q->post_count < 4 ) {
		wp_reset_postdata();
		return '';
	}

	$h = '<section class="eg-seccion" aria-labelledby="eg-t-prod">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-prod">Disponibles ahora</h2>'
		. '<p>Con stock confirmado hoy, listos para salir.</p>'
		. '</div><a class="eg-vertodo" href="' . $tienda . '">Ver todos</a></div>'
		. '<div class="eg-productos">';

	$i = 0;

	while ( $q->have_posts() ) {

		$q->the_post();
		$p = wc_get_product( get_the_ID() );
		if ( ! $p ) { continue; }

		$i++;
		$url   = esc_url( get_permalink() );
		$stock = $p->get_stock_quantity();

		if ( $stock && $stock > 0 ) {
			$texto_stock = $stock === 1 ? '1 disponible' : $stock . ' disponibles';
		} else {
			$texto_stock = 'Disponible';
		}

		// La foto lleva tabindex="-1" y aria-hidden: es el mismo destino que
		// el titulo de al lado, y sin esto un lector de pantalla lee cada
		// producto dos veces.
		$h .= '<article class="eg-prod">'
			. '<a class="eg-prod-foto" href="' . $url . '" tabindex="-1" aria-hidden="true">'
			. $p->get_image( 'woocommerce_thumbnail', array( 'loading' => $i <= 4 ? 'eager' : 'lazy' ) )
			. '</a>'
			. '<a class="eg-prod-nombre" href="' . $url . '">' . esc_html( $p->get_name() ) . '</a>'
			. '<div class="eg-prod-precio">' . wp_kses_post( $p->get_price_html() ) . '</div>'
			. '<p class="eg-prod-stock">' . esc_html( $texto_stock ) . '</p>'
			. '<a class="eg-prod-btn" href="' . esc_url( $p->add_to_cart_url() ) . '" rel="nofollow">'
			. esc_html( $p->add_to_cart_text() ) . '</a>'
			. '</article>';
	}

	wp_reset_postdata();

	return $h . '</div></section>';
}

/* ==========================================================================
   8. Tabla de series
   Describe usos, no especificaciones: asi no hay ni una cifra que pueda
   quedarse obsoleta o ser inexacta.
   ========================================================================== */

function eg_portada_tabla_series() {

	$filas = array(
		array( 'RAPID',  'Powerbanks de bolsillo',           'M&oacute;vil, tablet y port&aacute;til ligero', 'serie-rapid' ),
		array( 'RIVER',  'Estaciones ligeras',               'Camping, furgoneta, nevera port&aacute;til',    'serie-river' ),
		array( 'DELTA',  'Estaciones de mayor capacidad',    'Apagones en casa, obra, teletrabajo',           'serie-delta' ),
		array( 'STREAM', 'Balc&oacute;n solar y microinversor', 'Producir y almacenar en casa',              'stream-series' ),
	);

	$h  = '<div class="eg-tabla-caja"><table class="eg-tabla">'
		. '<caption>Cada serie cubre un uso distinto. El enlace lleva a la categor&iacute;a completa.</caption>'
		. '<thead><tr><th scope="col">Serie</th><th scope="col">Qu&eacute; es</th>'
		. '<th scope="col">Para qu&eacute; la usa la gente</th><th scope="col">Ver</th></tr></thead><tbody>';

	foreach ( $filas as $f ) {

		$t = get_term_by( 'slug', $f[3], 'product_cat' );
		$enlace = ( $t && ! is_wp_error( $t ) )
			? '<a href="' . esc_url( get_term_link( $t ) ) . '">Ver ' . $f[0] . '</a>'
			: '&mdash;';

		$h .= '<tr><th scope="row">' . $f[0] . '</th>'
			. '<td>' . $f[1] . '</td><td>' . $f[2] . '</td><td>' . $enlace . '</td></tr>';
	}

	return $h . '</tbody></table></div>';
}

/* ==========================================================================
   9. Texto de posicionamiento
   ========================================================================== */

function eg_portada_texto() {
	return '<section class="eg-seccion"><div class="eg-texto">'
		. '<h2>Comprar EcoFlow en Espa&ntilde;a con respaldo real</h2>'
		. '<p>EcoFlow fabrica estaciones de energ&iacute;a port&aacute;til y placas solares. Nosotros somos distribuidor oficial en Espa&ntilde;a: el equipo que compras aqu&iacute; llega con la garant&iacute;a del fabricante y con alguien detr&aacute;s a quien puedes llamar.</p>'
		. '<p>Esa es la diferencia que m&aacute;s nos preguntan. Cuando compras en un marketplace y el equipo falla, empieza un ir y venir de correos entre el vendedor, la plataforma y el fabricante. Aqu&iacute; la incidencia la abre y la sigue nuestro servicio t&eacute;cnico.</p>'
		. '<h3>&iquest;Qu&eacute; modelo necesitas?</h3>'
		. '<p>Depende de dos cosas: cu&aacute;nto consume lo que quieres enchufar y cu&aacute;nto tiempo quieres que aguante. Un m&oacute;vil y un port&aacute;til se resuelven con un <a href="/product-category/serie-rapid/">powerbank de la serie RAPID</a>. Una nevera de camping o unas luces para el fin de semana entran en la <a href="/product-category/serie-river/">serie RIVER</a>. Si lo que buscas es aguantar un apag&oacute;n en casa con el frigor&iacute;fico y el router encendidos, ah&iacute; ya hablamos de la <a href="/product-category/serie-delta/">serie DELTA</a>.</p>'
		. '<p>Y si el objetivo es gastar menos luz cada mes, y no solo tener respaldo para una emergencia, lo que necesitas son <a href="/product-category/paneles-solares/">placas solares</a> o un <a href="/kits-para-el-hogar/">kit para balc&oacute;n</a>: producen electricidad todos los d&iacute;as en lugar de guardarla.</p>'
		. '<p>Si dudas entre dos modelos, escr&iacute;benos y te decimos cu&aacute;l encaja. Preferimos venderte el que te sirve antes que el m&aacute;s caro.</p>'
		. '</div></section>';
}

/* ==========================================================================
   10. Preguntas frecuentes
   ========================================================================== */

function eg_portada_faq_datos() {
	return array(
		array(
			'&iquest;Sois distribuidor oficial de EcoFlow?',
			'S&iacute;. Somos distribuidor oficial de EcoFlow en Espa&ntilde;a, con tienda f&iacute;sica y servicio t&eacute;cnico propio. El producto sale de nuestro almac&eacute;n con la garant&iacute;a del fabricante.',
		),
		array(
			'&iquest;Cu&aacute;nto tarda el env&iacute;o?',
			'Los pedidos con stock confirmado salen en 24-48 horas laborables. En la ficha de cada producto ves si est&aacute; disponible en ese momento. Si algo va a tardar m&aacute;s, te avisamos antes de cobrar.',
		),
		array(
			'&iquest;Qu&eacute; pasa si el equipo falla?',
			'Abres la incidencia con nosotros, no con el fabricante. Nuestro servicio t&eacute;cnico la gestiona de principio a fin y te vamos contando en qu&eacute; punto est&aacute;.',
		),
		array(
			'&iquest;Puedo pagar a plazos?',
			'S&iacute;. Al finalizar la compra puedes elegir el pago fraccionado con SeQura. Las condiciones y el coste te los muestra SeQura antes de que confirmes nada.',
		),
		array(
			'&iquest;Qu&eacute; bater&iacute;a necesito para un apag&oacute;n en casa?',
			'Depende de qu&eacute; quieras mantener encendido. Para frigor&iacute;fico, router y algunas luces, la serie DELTA suele ser la que encaja. Si nos dices qu&eacute; aparatos son, te lo calculamos.',
		),
		array(
			'&iquest;Sirven las placas solares para el balc&oacute;n de un piso?',
			'S&iacute;, hay kits pensados para balc&oacute;n que no necesitan obra. Lo que cambia entre un piso y una casa es la potencia que puedes instalar y c&oacute;mo se conecta. Preg&uacute;ntanos y te decimos qu&eacute; opciones tienes.',
		),
	);
}

function eg_portada_faq() {
	$h = '<div class="eg-faq">';
	foreach ( eg_portada_faq_datos() as $f ) {
		$h .= '<details><summary>' . $f[0] . '</summary>'
			. '<div class="eg-faq-cuerpo"><p>' . $f[1] . '</p></div></details>';
	}
	return $h . '</div>';
}

/* ==========================================================================
   11. Cierre
   ========================================================================== */

function eg_portada_cierre() {
	return '<section class="eg-seccion"><div class="eg-cierre"><div>'
		. '<h2>&iquest;No lo tienes claro?</h2>'
		. '<p>Cu&eacute;ntanos qu&eacute; quieres alimentar y cu&aacute;nto tiempo, y te decimos qu&eacute; equipo encaja. Sin compromiso.</p>'
		. '</div><a class="eg-btn eg-btn-principal" href="/contacto/">Escr&iacute;benos</a></div></section>';
}

/* ==========================================================================
   12. Estilos, solo en la portada
   El CSS va en linea: una peticion menos y nada que bloquee el pintado.
   ========================================================================== */

add_action( 'wp_head', 'eg_portada_estilos', 99 );

function eg_portada_estilos() {

	if ( ! is_front_page() ) {
		return;
	}

	echo "<style id='eg-home-css'>";
	echo eg_portada_css();
	echo "</style>\n";
}

function eg_portada_css() {
	return <<<'CSS'
/*EG_CSS_AQUI*/
CSS;
}

/* ==========================================================================
   13. Datos estructurados de las preguntas frecuentes
   ========================================================================== */

add_action( 'wp_footer', 'eg_portada_schema' );

function eg_portada_schema() {

	if ( ! is_front_page() ) {
		return;
	}

	$preguntas = array();

	foreach ( eg_portada_faq_datos() as $f ) {
		$preguntas[] = array(
			'@type'          => 'Question',
			'name'           => html_entity_decode( wp_strip_all_tags( $f[0] ), ENT_QUOTES, 'UTF-8' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => html_entity_decode( wp_strip_all_tags( $f[1] ), ENT_QUOTES, 'UTF-8' ),
			),
		);
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $preguntas,
			),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		)
		. '</script>' . "\n";
}
