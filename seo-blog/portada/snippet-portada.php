<?php
/**
 * EG · Portada
 * ---------------------------------------------------------------------------
 * Portada en HTML y CSS puros: sin Elementor y sin una sola linea de
 * JavaScript. El motivo es medible: la portada con Elementor arrastraba
 * 2.918 KB de CSS y JS. Esta carga ~15 KB de CSS en linea y nada mas.
 *
 * Se pinta con el shortcode [eg_portada] en una pagina normal (editor clasico
 * o de bloques). NUNCA editar esa pagina con Elementor: en cuanto se abre con
 * el constructor vuelve a cargarse todo su CSS y se pierde el motivo de esto.
 *
 * Orden de la pagina, y el porque: novedades y mas vendidos van arriba del
 * todo, antes que cualquier texto. Quien entra en una tienda quiere ver
 * producto y precio, no un parrafo. El texto de posicionamiento va al final,
 * donde sigue contando para Google sin estorbar a quien viene a comprar.
 *
 * LO UNICO QUE HAY QUE TOCAR PARA ADAPTARLO son los bloques de configuracion
 * de aqui abajo. Todo va por slug de categoria: las que no existan se saltan
 * solas y nunca dejan un hueco roto.
 *
 * Precios, stock, ofertas y marcas se leen de WooCommerce en cada generacion.
 * Nada escrito a mano. El porcentaje de descuento se calcula del precio real.
 *
 * Sobre las tildes: los textos fijos van en entidades HTML (&aacute;,
 * &ntilde;...). Los snippets viajan por copia-pega entre editores y los
 * acentos en UTF-8 se han perdido ya dos veces en este proyecto.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ==========================================================================
   CONFIGURACION 1 · Mosaico de promociones
   Los dos primeros son grandes (ocupan cuatro huecos), el resto pequenos.

   array(
     'slug'     => categoria a la que lleva,
     'xl'       => true para el bloque grande,
     'etiqueta' => pastilla de arriba a la izquierda (opcional),
     'titulo'   => rotulo grande,
     'texto'    => linea pequena (opcional),
     'enlace'   => texto del enlace,
     'color'    => 'azul' | 'naranja' | 'verde', fondo si la categoria no
                   tiene imagen asignada
   )
   ========================================================================== */

function eg_portada_promos_cfg() {
	return array(
		array(
			'slug'     => 'hypershell',
			'xl'       => true,
			'etiqueta' => 'Novedad',
			'titulo'   => 'Hypershell: camina con ayuda',
			'texto'    => 'El exoesqueleto que te quita peso de las piernas en cuestas y caminatas largas.',
			'enlace'   => 'Descubrir Hypershell',
			'color'    => 'naranja',
		),
		array(
			'slug'     => 'serie-delta',
			'xl'       => true,
			'titulo'   => 'Que un apag&oacute;n no te pare',
			'texto'    => 'Estaciones DELTA para mantener el frigor&iacute;fico, el router y las luces.',
			'enlace'   => 'Ver estaciones DELTA',
			'color'    => 'azul',
		),
		array(
			'slug'   => 'paneles-solares',
			'titulo' => 'Placas solares',
			'texto'  => 'Port&aacute;tiles y para balc&oacute;n',
			'enlace' => 'Ver placas',
			'color'  => 'verde',
		),
		array(
			'slug'   => 'kits-para-el-hogar',
			'titulo' => 'Kits de balc&oacute;n',
			'texto'  => 'Produce sin obra',
			'enlace' => 'Ver kits',
			'color'  => 'verde',
		),
		array(
			'slug'   => 'serie-river',
			'titulo' => 'Camping y furgo',
			'texto'  => 'Bater&iacute;as RIVER, ligeras',
			'enlace' => 'Ver RIVER',
			'color'  => 'azul',
		),
		array(
			'slug'   => 'serie-rapid',
			'titulo' => 'Carga r&aacute;pida',
			'texto'  => 'Powerbanks para el d&iacute;a a d&iacute;a',
			'enlace' => 'Ver powerbanks',
			'color'  => 'azul',
		),
	);
}

/* ==========================================================================
   CONFIGURACION 2 · Circulos de categoria
   array( slug, rotulo )
   ========================================================================== */

function eg_portada_circulos_cfg() {
	return array(
		array( 'serie-delta',        'Estaciones DELTA' ),
		array( 'serie-river',        'Bater&iacute;as RIVER' ),
		array( 'paneles-solares',    'Placas solares' ),
		array( 'serie-rapid',        'Powerbanks' ),
		array( 'hypershell',         'Hypershell' ),
		array( 'kits-para-el-hogar', 'Kits hogar' ),
		array( 'stream-series',      'STREAM' ),
		array( 'accesorios',         'Accesorios' ),
		array( 'generador-solar',    'Generadores' ),
	);
}

/* ==========================================================================
   CONFIGURACION 3 · Bandas destacadas
   'clara' => true la pinta en blanco en vez de en oscuro.
   ========================================================================== */

function eg_portada_bandas_cfg() {
	return array(
		array(
			'slug'     => 'hypershell',
			'etiqueta' => 'Novedad',
			'titulo'   => 'Hypershell: el exoesqueleto que te quita peso de las piernas',
			'texto'    => 'Un motor te acompa&ntilde;a al andar y al subir. Para caminatas largas, monta&ntilde;a y para quien pasa el d&iacute;a de pie.',
			'puntos'   => array(
				'Se pone y se quita en segundos',
				'Bater&iacute;a intercambiable',
				'Distribuidor oficial en Espa&ntilde;a',
			),
			'boton'    => 'Ver los modelos',
		),
		array(
			'slug'     => 'kits-para-el-hogar',
			'etiqueta' => 'Para casa',
			'clara'    => true,
			'titulo'   => 'Kits solares de balc&oacute;n: produce tu propia luz sin obra',
			'texto'    => 'Se instalan en un balc&oacute;n o una terraza y empiezan a producir desde el primer d&iacute;a. Te decimos qu&eacute; potencia encaja en tu caso.',
			'puntos'   => array(
				'Sin obra y sin permisos de comunidad en la mayor&iacute;a de casos',
				'Se amplian despues con m&aacute;s paneles o bater&iacute;a',
				'Te calculamos el ahorro con tu factura delante',
			),
			'boton'    => 'Ver los kits',
		),
	);
}

/* ==========================================================================
   Utilidades
   ========================================================================== */

function eg_portada_term( $slug ) {
	$t = get_term_by( 'slug', $slug, 'product_cat' );
	return ( $t && ! is_wp_error( $t ) ) ? $t : false;
}

function eg_portada_foto_term( $t, $tam = 'woocommerce_thumbnail', $lazy = true ) {
	$id = (int) get_term_meta( $t->term_id, 'thumbnail_id', true );
	if ( ! $id ) { return ''; }
	return wp_get_attachment_image( $id, $tam, false, array(
		'alt'     => '',
		'loading' => $lazy ? 'lazy' : 'eager',
	) );
}

/**
 * Marca de un producto. Solo si existe de verdad como taxonomia; si no,
 * cadena vacia y no se pinta nada. Nunca se inventa.
 */
function eg_portada_marca_de( $id ) {
	foreach ( array( 'product_brand', 'pwb-brand', 'pa_marca', 'yith_product_brand' ) as $tax ) {
		if ( ! taxonomy_exists( $tax ) ) { continue; }
		$t = wp_get_post_terms( $id, $tax, array( 'fields' => 'names' ) );
		if ( ! is_wp_error( $t ) && ! empty( $t ) ) { return $t[0]; }
	}
	return '';
}

/**
 * Una tarjeta de producto. $etiqueta pinta la pastilla de arriba a la
 * izquierda; el descuento, si lo hay, la sustituye porque es mas potente.
 */
function eg_portada_tarjeta_producto( $p, $prioridad = false, $etiqueta = '' ) {

	$url   = esc_url( get_permalink( $p->get_id() ) );
	$marca = eg_portada_marca_de( $p->get_id() );
	$stock = $p->get_stock_quantity();

	$texto_stock = ( $stock && $stock > 0 )
		? ( 1 === (int) $stock ? '1 disponible' : $stock . ' disponibles' )
		: 'Disponible';

	// Descuento calculado del precio real. Si no hay rebaja, no hay pastilla.
	$pastilla = '';
	$regular  = (float) $p->get_regular_price();
	$actual   = (float) $p->get_price();

	if ( $p->is_on_sale() && $regular > 0 && $actual > 0 && $actual < $regular ) {
		$pc = (int) round( ( ( $regular - $actual ) / $regular ) * 100 );
		if ( $pc >= 5 ) {
			$pastilla = '<span class="eg-pill eg-pill-oferta">-' . $pc . '%</span>';
		}
	}

	if ( ! $pastilla && $etiqueta ) {
		$pastilla = $etiqueta;
	}

	// La foto lleva tabindex="-1" y aria-hidden: va al mismo sitio que el
	// titulo de al lado y sin esto un lector de pantalla repite cada producto.
	return '<article class="eg-prod">'
		. ( $pastilla ? '<span class="eg-prod-etiq">' . $pastilla . '</span>' : '' )
		. '<a class="eg-prod-foto" href="' . $url . '" tabindex="-1" aria-hidden="true">'
		. $p->get_image( 'woocommerce_thumbnail', array( 'loading' => $prioridad ? 'eager' : 'lazy' ) )
		. '</a>'
		. '<div class="eg-prod-cuerpo">'
		. ( $marca ? '<p class="eg-prod-marca">' . esc_html( $marca ) . '</p>' : '' )
		. '<a class="eg-prod-nombre" href="' . $url . '">' . esc_html( $p->get_name() ) . '</a>'
		. '<div class="eg-prod-precio">' . wp_kses_post( $p->get_price_html() ) . '</div>'
		. '<p class="eg-prod-stock">' . esc_html( $texto_stock ) . '</p>'
		. '<a class="eg-prod-btn" href="' . esc_url( $p->add_to_cart_url() ) . '" rel="nofollow">'
		. esc_html( $p->add_to_cart_text() ) . '</a>'
		. '</div></article>';
}

/**
 * Fila de productos. $orden: 'nuevos' o 'ventas'.
 * Menos de cuatro productos y la fila no se pinta: una fila coja da peor
 * impresion que no tenerla.
 */
function eg_portada_fila( $titulo, $subtitulo, $orden, $etiqueta, $id_titulo, $tienda ) {

	$args = array(
		'post_type'           => 'product',
		'posts_per_page'      => 10,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'meta_query'          => array(
			array( 'key' => '_stock_status', 'value' => 'instock' ),
			array( 'key' => '_price', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ),
		),
	);

	if ( 'ventas' === $orden ) {
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = 'total_sales';
		$args['order']    = 'DESC';
	} else {
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	}

	$q = new WP_Query( $args );

	if ( $q->post_count < 4 ) {
		wp_reset_postdata();
		return '';
	}

	$h = '<section class="eg-seccion" aria-labelledby="' . $id_titulo . '">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="' . $id_titulo . '">' . $titulo . '</h2>'
		. ( $subtitulo ? '<p>' . $subtitulo . '</p>' : '' )
		. '</div><a class="eg-vertodo" href="' . $tienda . '">Ver todos &rarr;</a></div>'
		. '<div class="eg-fila">';

	$i = 0;

	while ( $q->have_posts() ) {
		$q->the_post();
		$p = wc_get_product( get_the_ID() );
		if ( ! $p ) { continue; }
		$i++;
		$h .= eg_portada_tarjeta_producto( $p, $i <= 5, $etiqueta );
	}

	wp_reset_postdata();

	return $h . '</div></section>';
}

/* ==========================================================================
   Shortcode
   ========================================================================== */

add_shortcode( 'eg_portada', 'eg_portada_html' );

function eg_portada_html() {

	$tienda = esc_url( wc_get_page_permalink( 'shop' ) );

	$h  = '<div class="eg-home">';
	$h .= '<a class="eg-saltar" href="#eg-comprar">Saltar a los productos</a>';
	$h .= eg_portada_hero( $tienda );

	$h .= '<div class="eg-ancho">';
	$h .= eg_portada_promos();

	$h .= '<span id="eg-comprar"></span>';

	$h .= eg_portada_fila(
		'Novedades',
		'Lo &uacute;ltimo que ha entrado en la tienda.',
		'nuevos',
		'<span class="eg-pill eg-pill-nuevo">Nuevo</span>',
		'eg-t-nuevo',
		$tienda
	);

	$h .= eg_portada_fila(
		'Los m&aacute;s vendidos',
		'Lo que m&aacute;s sale, con stock confirmado hoy.',
		'ventas',
		'<span class="eg-pill eg-pill-top">Top ventas</span>',
		'eg-t-top',
		$tienda
	);

	$h .= eg_portada_circulos( $tienda );
	$h .= eg_portada_bandas();
	$h .= eg_portada_marcas();
	$h .= eg_portada_avales();
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
   Portada
   ========================================================================== */

function eg_portada_hero( $tienda ) {

	$id   = (int) get_option( 'eg_portada_hero', 0 );
	$foto = '';

	if ( $id ) {
		// Sin lazy y con prioridad alta: es lo primero que se ve.
		$foto = wp_get_attachment_image( $id, 'large', false, array(
			'alt'           => '',
			'aria-hidden'   => 'true',
			'loading'       => 'eager',
			'decoding'      => 'sync',
			'fetchpriority' => 'high',
		) );
	}

	// El segundo boton lleva a la novedad de temporada. Si esa categoria no
	// existe, lleva a los mas vendidos, que estan en la misma pagina.
	$destacada = eg_portada_term( 'hypershell' );
	$segundo   = $destacada
		? '<a class="eg-btn eg-btn-linea" href="' . esc_url( get_term_link( $destacada ) ) . '">Ver Hypershell</a>'
		: '<a class="eg-btn eg-btn-linea" href="#eg-comprar">Ver lo m&aacute;s vendido</a>';

	return '<div class="eg-hero"><div class="eg-hero-in"><div class="eg-hero-txt">'
		. '<span class="eg-pill eg-pill-nuevo">Distribuidor oficial</span>'
		. '<h1>Energ&iacute;a port&aacute;til, solar y movilidad, con servicio t&eacute;cnico en Espa&ntilde;a</h1>'
		. '<p>EcoFlow, Hypershell y el resto de marcas que trabajamos. Te asesoramos antes de comprar y, si algo falla, lo resolvemos nosotros.</p>'
		. '<div class="eg-hero-botones">'
		. '<a class="eg-btn eg-btn-naranja" href="' . $tienda . '">Comprar ahora</a>'
		. $segundo
		. '</div></div>'
		. ( $foto ? '<div class="eg-hero-foto">' . $foto . '</div>' : '' )
		. '</div></div>';
}

/* ==========================================================================
   Mosaico de promociones
   ========================================================================== */

function eg_portada_promos() {

	$piezas = '';
	$n      = 0;

	foreach ( eg_portada_promos_cfg() as $c ) {

		$t = eg_portada_term( $c['slug'] );
		if ( ! $t ) { continue; } // categoria inexistente: se salta sola

		$n++;
		$xl    = ! empty( $c['xl'] );
		$color = isset( $c['color'] ) ? $c['color'] : 'azul';

		// Las dos primeras entran en pantalla: se cargan sin lazy.
		$foto = eg_portada_foto_term( $t, 'large', $n > 2 );

		$piezas .= '<a class="eg-promo eg-promo-' . $color . ( $xl ? ' eg-promo-xl' : '' ) . '"'
			. ' href="' . esc_url( get_term_link( $t ) ) . '">'
			. ( $foto ? '<span class="eg-promo-foto">' . $foto . '</span>' : '' )
			. '<span class="eg-promo-velo"></span>'
			. ( ! empty( $c['etiqueta'] )
				? '<span class="eg-promo-etiq"><span class="eg-pill eg-pill-nuevo">' . $c['etiqueta'] . '</span></span>'
				: '' )
			. '<span class="eg-promo-txt">'
			. '<b>' . $c['titulo'] . '</b>'
			. ( ! empty( $c['texto'] ) ? '<span>' . $c['texto'] . '</span>' : '' )
			. '<i>' . $c['enlace'] . ' &rarr;</i>'
			. '</span></a>';
	}

	if ( $n < 3 ) { return ''; }

	return '<section class="eg-seccion"><div class="eg-promos">' . $piezas . '</div></section>';
}

/* ==========================================================================
   Circulos de categoria
   ========================================================================== */

function eg_portada_circulos( $tienda ) {

	$piezas = '';
	$n      = 0;

	foreach ( eg_portada_circulos_cfg() as $c ) {
		$t = eg_portada_term( $c[0] );
		if ( ! $t ) { continue; }
		$n++;
		$piezas .= '<a class="eg-circulo" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<span class="eg-circulo-foto">' . eg_portada_foto_term( $t ) . '</span>'
			. '<b>' . $c[1] . '</b></a>';
	}

	if ( $n < 4 ) { return ''; }

	return '<section class="eg-seccion" aria-labelledby="eg-t-cat">'
		. '<div class="eg-seccion-cab"><div><h2 id="eg-t-cat">Compra por categor&iacute;a</h2></div>'
		. '<a class="eg-vertodo" href="' . $tienda . '">Ver toda la tienda &rarr;</a></div>'
		. '<div class="eg-circulos">' . $piezas . '</div></section>';
}

/* ==========================================================================
   Bandas destacadas
   ========================================================================== */

function eg_portada_bandas() {

	$h = '';

	foreach ( eg_portada_bandas_cfg() as $c ) {

		$t = eg_portada_term( $c['slug'] );
		if ( ! $t ) { continue; } // si la categoria no existe, no se pinta

		$puntos = '';
		foreach ( $c['puntos'] as $p ) {
			$puntos .= '<li>' . $p . '</li>';
		}

		$clara = ! empty( $c['clara'] );
		$boton = $clara ? 'eg-btn-naranja' : 'eg-btn-blanco';

		$h .= '<section class="eg-seccion"><div class="eg-banda' . ( $clara ? ' eg-banda-clara' : '' ) . '">'
			. '<div class="eg-banda-txt">'
			. '<span class="eg-pill eg-pill-nuevo">' . $c['etiqueta'] . '</span>'
			. '<h2>' . $c['titulo'] . '</h2>'
			. '<p>' . $c['texto'] . '</p>'
			. '<ul class="eg-banda-lista">' . $puntos . '</ul>'
			. '<a class="eg-btn ' . $boton . '" href="' . esc_url( get_term_link( $t ) ) . '">' . $c['boton'] . '</a>'
			. '</div>'
			. '<div class="eg-banda-foto">' . eg_portada_foto_term( $t, 'full' ) . '</div>'
			. '</div></section>';
	}

	return $h;
}

/* ==========================================================================
   Marcas
   Se pinta solo si existe una taxonomia de marcas con terminos. Sin inventar.
   ========================================================================== */

function eg_portada_marcas() {

	$tax = '';

	foreach ( array( 'product_brand', 'pwb-brand', 'yith_product_brand', 'pa_marca' ) as $t ) {
		if ( taxonomy_exists( $t ) ) { $tax = $t; break; }
	}

	if ( ! $tax ) { return ''; }

	$marcas = get_terms( array(
		'taxonomy'   => $tax,
		'hide_empty' => true,
		'number'     => 0,      // todas las que tenga, como hace MediaMarkt
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( is_wp_error( $marcas ) || count( $marcas ) < 2 ) { return ''; }

	$h = '<section class="eg-seccion" aria-labelledby="eg-t-marcas">'
		. '<div class="eg-seccion-cab"><div><h2 id="eg-t-marcas">Nuestras marcas</h2>'
		. '<p>Somos distribuidor autorizado de las marcas que vendemos.</p></div></div>'
		. '<div class="eg-marcas">';

	foreach ( $marcas as $m ) {

		$img_id = (int) get_term_meta( $m->term_id, 'thumbnail_id', true );
		$dentro = $img_id
			? wp_get_attachment_image( $img_id, 'medium', false, array( 'alt' => $m->name, 'loading' => 'lazy' ) )
			: '<span>' . esc_html( $m->name ) . '</span>';

		$enlace = get_term_link( $m );

		$h .= is_wp_error( $enlace )
			? '<div class="eg-marca">' . $dentro . '</div>'
			: '<a class="eg-marca" href="' . esc_url( $enlace ) . '" aria-label="' . esc_attr( $m->name ) . '">' . $dentro . '</a>';
	}

	return $h . '</div></section>';
}

/* ==========================================================================
   Barra de confianza
   ========================================================================== */

function eg_portada_avales() {

	// Nada de recogida en tienda: hay productos que salen directos de
	// almacen y no pasan por la tienda fisica.
	$items = array(
		array( 'Env&iacute;o en 24-48 h',        'En los productos con stock confirmado.' ),
		array( 'Garant&iacute;a oficial',        'Distribuidor autorizado de las marcas que vendemos.' ),
		array( 'Servicio t&eacute;cnico propio', 'La incidencia la gestionamos nosotros.' ),
		array( 'Pago a plazos',                  'Financiaci&oacute;n con SeQura al finalizar.' ),
	);

	$h = '<section class="eg-seccion"><div class="eg-avales">';
	foreach ( $items as $i ) {
		$h .= '<div class="eg-aval"><b>' . $i[0] . '</b><span>' . $i[1] . '</span></div>';
	}
	return $h . '</div></section>';
}

/* ==========================================================================
   Texto de posicionamiento
   ========================================================================== */

function eg_portada_texto() {
	return '<section class="eg-seccion"><div class="eg-texto">'
		. '<h2>Una tienda especializada, no un marketplace</h2>'
		. '<p>Trabajamos con marcas de energ&iacute;a port&aacute;til, solar y movilidad, y somos distribuidor oficial de las que vendemos. El equipo que compras aqu&iacute; llega con la garant&iacute;a del fabricante y con alguien detr&aacute;s a quien puedes llamar.</p>'
		. '<p>Esa es la diferencia que m&aacute;s nos preguntan. Cuando compras en un marketplace y el equipo falla, empieza un ir y venir de correos entre el vendedor, la plataforma y el fabricante. Aqu&iacute; la incidencia la abre y la sigue nuestro servicio t&eacute;cnico.</p>'
		. '<h3>&iquest;Qu&eacute; necesitas?</h3>'
		. '<p>Si buscas energ&iacute;a, depende de cu&aacute;nto consume lo que quieres enchufar y de cu&aacute;nto tiempo quieres que aguante. Un m&oacute;vil y un port&aacute;til se resuelven con un <a href="/product-category/serie-rapid/">powerbank</a>. Una nevera de camping o unas luces para el fin de semana entran en la <a href="/product-category/serie-river/">serie RIVER</a>. Para aguantar un apag&oacute;n en casa con el frigor&iacute;fico y el router encendidos ya hablamos de la <a href="/product-category/serie-delta/">serie DELTA</a>.</p>'
		. '<p>Si lo que quieres es gastar menos luz cada mes, y no solo tener respaldo para una emergencia, lo tuyo son <a href="/product-category/paneles-solares/">placas solares</a> o un <a href="/kits-para-el-hogar/">kit para balc&oacute;n</a>: producen electricidad todos los d&iacute;as en lugar de guardarla.</p>'
		. '<p>Y si lo que buscas es moverte mejor, ah&iacute; est&aacute; <a href="/product-category/hypershell/">Hypershell</a>, la novedad de la tienda: un exoesqueleto que te ayuda al caminar y al subir.</p>'
		. '<p>Si dudas entre dos modelos, escr&iacute;benos y te decimos cu&aacute;l encaja. Preferimos venderte el que te sirve antes que el m&aacute;s caro.</p>'
		. '</div></section>';
}

/* ==========================================================================
   Preguntas frecuentes
   ========================================================================== */

function eg_portada_faq_datos() {
	return array(
		array(
			'&iquest;Sois distribuidor oficial?',
			'S&iacute;, de las marcas que vendemos, con tienda f&iacute;sica y servicio t&eacute;cnico propio. El producto sale de nuestro almac&eacute;n con la garant&iacute;a del fabricante.',
		),
		array(
			'&iquest;Qu&eacute; es un exoesqueleto Hypershell?',
			'Es un soporte que se lleva en la cintura y las piernas y que, con un peque&ntilde;o motor, te acompa&ntilde;a al andar y al subir. Se nota sobre todo en caminatas largas, en cuestas y si pasas el d&iacute;a de pie.',
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
   Cierre
   ========================================================================== */

function eg_portada_cierre() {
	return '<section class="eg-seccion"><div class="eg-cierre"><div>'
		. '<h2>&iquest;No lo tienes claro?</h2>'
		. '<p>Cu&eacute;ntanos qu&eacute; necesitas y te decimos qu&eacute; equipo encaja. Sin compromiso.</p>'
		. '</div><a class="eg-btn eg-btn-naranja" href="/contacto/">Escr&iacute;benos</a></div></section>';
}

/* ==========================================================================
   Estilos, solo en la portada
   ========================================================================== */

add_action( 'wp_head', 'eg_portada_estilos', 99 );

function eg_portada_estilos() {
	if ( ! is_front_page() ) { return; }
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
   Datos estructurados de las preguntas frecuentes
   ========================================================================== */

add_action( 'wp_footer', 'eg_portada_schema' );

function eg_portada_schema() {

	if ( ! is_front_page() ) { return; }

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
			array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $preguntas ),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		)
		. '</script>' . "\n";
}
