<?php
/**
 * Plugin Name: EG · Portada
 * Description: Portada de ecogadgetoficial.com en HTML y CSS puros, sin Elementor y sin JavaScript. Se pinta con el shortcode [eg_portada].
 * Version:     1.2
 * Author:      EcoGadget
 * License:     GPL-2.0-or-later
 */

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
		array(
			'slug'   => 'arrancadores',
			'titulo' => 'Arrancadores de coche',
			'texto'  => 'Lokithor: arranque, compresor y linterna',
			'enlace' => 'Ver arrancadores',
			'color'  => 'naranja',
		),
	);
}

/* ==========================================================================
   CONFIGURACION 1b · Caminos de entrada de la portada
   Los cuatro accesos de la mitad izquierda. Son enlaces internos con texto
   descriptivo: sirven a quien no sabe que busca y a Google para entender
   la estructura del catalogo.
   array( slug, titulo, para que sirve )
   ========================================================================== */

function eg_portada_caminos_cfg() {
	return array(
		array( 'serie-delta',     'Apagones en casa',   'Estaciones EcoFlow DELTA' ),
		array( 'paneles-solares', 'Bajar la factura',   'Placas solares y kits de balc&oacute;n' ),
		array( 'arrancadores',    'El coche no arranca','Arrancadores Lokithor' ),
		array( 'hypershell',      'Andar con menos esfuerzo', 'Exoesqueletos HyperShell' ),
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
		array( 'arrancadores',       'Arrancadores' ),
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
				// Verificado en las fichas de los tres modelos, apartado "Que incluye
				// la caja": "Bateria Inteligente de Litio de Alta Capacidad Extraible",
				// y la FAQ del X Ultra S: "se intercambia en cuestion de segundos".
				'Bater&iacute;a extra&iacute;ble, se cambia en segundos',
				// Autonomia de las fichas de X Max S y X Ultra S. El tiempo que se
				// tarda en ponerselo NO aparece en ninguna ficha: no se afirma.
				'Hasta 30 km de autonom&iacute;a seg&uacute;n modelo',
				'Te asesoramos antes de que lo compres',
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
   Iconos
   SVG en linea, dibujados a mano: ni una peticion mas ni una fuente de
   iconos de 200 KB para pintar cuatro flechas.
   ========================================================================== */

function eg_portada_icono( $n ) {

	$d = array(
		'flecha'  => '<path d="M4 10h12M11 5l5 5-5 5"/>',
		'carrito' => '<path d="M2 3h3l2 10h9l2-7H6"/><circle cx="9" cy="17" r="1.4"/><circle cx="16" cy="17" r="1.4"/>',
		'camion'  => '<path d="M2 5h10v9H2zM12 8h4l3 3v3h-7z"/><circle cx="6" cy="16" r="1.6"/><circle cx="15" cy="16" r="1.6"/>',
		'escudo'  => '<path d="M10 2l6 3v5c0 4-2.6 6.9-6 8-3.4-1.1-6-4-6-8V5z"/><path d="M7.5 10l1.8 1.8L13 8"/>',
		'llave'   => '<path d="M12.5 3a4 4 0 00-3.6 5.7L3 14.6V17h2.4l5.9-5.9A4 4 0 1012.5 3z"/>',
		'tarjeta' => '<path d="M2 5h16v10H2z"/><path d="M2 8h16"/><path d="M5 12h3"/>',
	);

	if ( ! isset( $d[ $n ] ) ) { return ''; }

	return '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"'
		. ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
		. $d[ $n ] . '</svg>';
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
		. eg_portada_icono( 'carrito' ) . esc_html( $p->add_to_cart_text() ) . '</a>'
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
   Mitad izquierda: titular, cuatro caminos de entrada y botones.
   Mitad derecha: una ficha de producto real, con su precio y su boton.
   Una foto grande decorativa no vende; una ficha con precio si.
   ========================================================================== */

function eg_portada_hero( $tienda ) {

	// --- caminos de entrada ---
	$caminos = '';

	foreach ( eg_portada_caminos_cfg() as $c ) {
		$t = eg_portada_term( $c[0] );
		if ( ! $t ) { continue; }
		$caminos .= '<a class="eg-camino" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<span class="eg-camino-foto">' . eg_portada_foto_term( $t, 'woocommerce_thumbnail', false ) . '</span>'
			. '<span><b>' . $c[1] . '</b><span>' . $c[2] . '</span></span>'
			. '</a>';
	}

	$caminos = $caminos ? '<div class="eg-caminos">' . $caminos . '</div>' : '';

	return '<div class="eg-hero"><div class="eg-hero-in">'
		. '<div class="eg-hero-txt">'
		. '<h1>Bater&iacute;as port&aacute;tiles, placas solares y arrancadores de coche</h1>'
		. '<p>EcoFlow, HyperShell y Lokithor. Te decimos qu&eacute; equipo encaja antes de que lo compres.</p>'
		. $caminos
		. '<div class="eg-hero-botones">'
		. '<a class="eg-btn eg-btn-principal" href="' . $tienda . '">Comprar ahora' . eg_portada_icono( 'flecha' ) . '</a>'
		. '<a class="eg-btn eg-btn-suave" href="#eg-comprar">Ver lo m&aacute;s vendido' . eg_portada_icono( 'flecha' ) . '</a>'
		. '</div></div>'
		. eg_portada_destacado()
		. '</div></div>';
}

/**
 * Ficha destacada de la portada.
 * Se puede fijar un producto concreto con la opcion "eg_portada_destacado".
 * Si no hay ninguno fijado, sale el mas vendido con stock y con precio.
 * Si no hay ni eso, no se pinta nada y la portada queda a una columna.
 */
function eg_portada_destacado() {

	$id = (int) get_option( 'eg_portada_destacado', 0 );
	$p  = $id ? wc_get_product( $id ) : false;

	if ( ! $p || ! $p->is_in_stock() || '' === $p->get_price() ) {

		$q = new WP_Query( array(
			'post_type'           => 'product',
			'posts_per_page'      => 1,
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

		if ( ! $q->have_posts() ) {
			wp_reset_postdata();
			return '';
		}

		$q->the_post();
		$p = wc_get_product( get_the_ID() );
		wp_reset_postdata();

		if ( ! $p ) { return ''; }
	}

	$url   = esc_url( get_permalink( $p->get_id() ) );
	$marca = eg_portada_marca_de( $p->get_id() );
	$stock = $p->get_stock_quantity();

	$texto_stock = ( $stock && $stock > 0 )
		? ( 1 === (int) $stock ? '1 disponible' : $stock . ' disponibles' )
		: 'Disponible';

	// Etiqueta: el descuento real si lo hay; si no, lo mas vendido.
	$regular  = (float) $p->get_regular_price();
	$actual   = (float) $p->get_price();
	$pastilla = '<span class="eg-pill eg-pill-top">Lo m&aacute;s vendido</span>';

	if ( $p->is_on_sale() && $regular > 0 && $actual > 0 && $actual < $regular ) {
		$pc = (int) round( ( ( $regular - $actual ) / $regular ) * 100 );
		if ( $pc >= 5 ) {
			$pastilla = '<span class="eg-pill eg-pill-oferta">-' . $pc . '%</span>';
		}
	}

	return '<article class="eg-destacado">'
		. '<a class="eg-destacado-foto" href="' . $url . '" tabindex="-1" aria-hidden="true">'
		. '<span class="eg-destacado-etiq">' . $pastilla . '</span>'
		. $p->get_image( 'woocommerce_single', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) )
		. '</a>'
		. '<div class="eg-destacado-txt">'
		. ( $marca ? '<p class="eg-destacado-marca">' . esc_html( $marca ) . '</p>' : '' )
		. '<a class="eg-destacado-nombre" href="' . $url . '">' . esc_html( $p->get_name() ) . '</a>'
		. '<div class="eg-destacado-precio">' . wp_kses_post( $p->get_price_html() ) . '</div>'
		. '<p class="eg-destacado-stock">' . esc_html( $texto_stock ) . '</p>'
		. '<a class="eg-btn eg-btn-principal" href="' . esc_url( $p->add_to_cart_url() ) . '" rel="nofollow">'
		. eg_portada_icono( 'carrito' ) . esc_html( $p->add_to_cart_text() ) . '</a>'
		. '</div></article>';
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
		$boton = 'eg-btn-principal';

		$h .= '<section class="eg-seccion"><div class="eg-banda' . ( $clara ? ' eg-banda-clara' : '' ) . '">'
			. '<div class="eg-banda-txt">'
			. '<span class="eg-pill eg-pill-nuevo">' . $c['etiqueta'] . '</span>'
			. '<h2>' . $c['titulo'] . '</h2>'
			. '<p>' . $c['texto'] . '</p>'
			. '<ul class="eg-banda-lista">' . $puntos . '</ul>'
			. '<a class="eg-btn ' . $boton . '" href="' . esc_url( get_term_link( $t ) ) . '">' . $c['boton'] . eg_portada_icono( 'flecha' ) . '</a>'
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
		. '<p>Trabajamos con estas marcas y las conocemos por dentro.</p></div></div>'
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
		array( 'camion',  'Env&iacute;o en 24-48 h',        'En los productos con stock confirmado.' ),
		array( 'escudo',  'Garant&iacute;a del fabricante', 'En todo lo que vendemos.' ),
		array( 'llave',   'Servicio t&eacute;cnico EcoFlow', 'Las incidencias de EcoFlow las gestionamos nosotros.' ),
		array( 'tarjeta', 'Pago a plazos',                  'Financiaci&oacute;n con SeQura al finalizar.' ),
	);

	$h = '<section class="eg-seccion"><div class="eg-avales">';
	foreach ( $items as $i ) {
		$h .= '<div class="eg-aval">' . eg_portada_icono( $i[0] )
			. '<div><b>' . $i[1] . '</b><span>' . $i[2] . '</span></div></div>';
	}
	return $h . '</div></section>';
}

/* ==========================================================================
   Texto de posicionamiento
   ========================================================================== */

function eg_portada_texto() {
	// <details> nativo: el primer parrafo se ve siempre, el resto se despliega.
	// Google indexa el contenido igual, este abierto o cerrado.
	return '<section class="eg-seccion"><details class="eg-texto">'
		. '<h2>Una tienda especializada, no un marketplace</h2>'
		. '<p>Trabajamos con EcoFlow, HyperShell y Lokithor. El equipo que compras aqu&iacute; llega con la garant&iacute;a del fabricante y con alguien detr&aacute;s a quien puedes llamar, que no es lo mismo que un formulario de contacto.</p>'
		. '<p>Esa es la diferencia que m&aacute;s nos preguntan. Cuando compras en un marketplace y el equipo falla, empieza un ir y venir de correos entre el vendedor, la plataforma y el fabricante. Aqu&iacute; la incidencia la abre y la sigue nuestro servicio t&eacute;cnico.</p>'
		. '<summary>Leer m&aacute;s sobre lo que vendemos</summary>'
		. '<h3>&iquest;Qu&eacute; necesitas?</h3>'
		. '<p>Si buscas energ&iacute;a, depende de cu&aacute;nto consume lo que quieres enchufar y de cu&aacute;nto tiempo quieres que aguante. Un m&oacute;vil y un port&aacute;til se resuelven con un <a href="/product-category/serie-rapid/">powerbank</a>. Una nevera de camping o unas luces para el fin de semana entran en la <a href="/product-category/serie-river/">serie RIVER</a>. Para aguantar un apag&oacute;n en casa con el frigor&iacute;fico y el router encendidos ya hablamos de la <a href="/product-category/serie-delta/">serie DELTA</a>.</p>'
		. '<p>Si lo que quieres es gastar menos luz cada mes, y no solo tener respaldo para una emergencia, lo tuyo son <a href="/product-category/paneles-solares/">placas solares</a> o un <a href="/kits-para-el-hogar/">kit para balc&oacute;n</a>: producen electricidad todos los d&iacute;as en lugar de guardarla.</p>'
		. '<p>Y si lo que buscas es moverte mejor, ah&iacute; est&aacute; <a href="/product-category/hypershell/">Hypershell</a>, la novedad de la tienda: un exoesqueleto que te ayuda al caminar y al subir.</p>'
		. '<p>Si dudas entre dos modelos, escr&iacute;benos y te decimos cu&aacute;l encaja. Preferimos venderte el que te sirve antes que el m&aacute;s caro.</p>'
		. '</details></section>';
}

/* ==========================================================================
   Preguntas frecuentes
   ========================================================================== */

function eg_portada_faq_datos() {
	return array(
		array(
			'&iquest;El producto lleva garant&iacute;a?',
			'S&iacute;. Todo lo que vendemos sale con la garant&iacute;a del fabricante. En el caso de EcoFlow, adem&aacute;s, la incidencia la gestiona nuestro propio servicio t&eacute;cnico y no tienes que hablar con nadie m&aacute;s.',
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
			'&iquest;Qu&eacute; pasa si un EcoFlow falla?',
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
		. '</div><a class="eg-btn eg-btn-principal" href="/contacto/">Escr&iacute;benos' . eg_portada_icono( 'flecha' ) . '</a></div></section>';
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
/* ==========================================================================
   Portada · ecogadgetoficial.com
   --------------------------------------------------------------------------
   HTML y CSS puros. Sin Elementor y sin JavaScript.
   Se imprime en linea desde el snippet "EG · Portada", solo en is_front_page().

   Criterio de diseno, y por que es asi:

   1. Las imagenes sangran hasta el borde de su caja. Una foto pequena
      centrada con margenes blancos alrededor es lo que hace que una tienda
      parezca de 2012. Aqui la foto ES el bloque.
   2. Bloques de color saturado, no cajas blancas sobre gris. MediaMarkt y
      PcComponentes son color; el blanco se reserva para las fichas de
      producto, donde el producto tiene que destacar sobre fondo neutro.
   3. El precio es el elemento tipografico mas grande de la ficha, por
      encima del nombre. En una tienda se compra por precio.
   4. El naranja significa dinero: novedad, oferta y precio rebajado. Si se
      usa para adornar deja de significar nada.

   No se declara font-family a proposito: hereda la del tema.
   Prefijo .eg-home porque el kit de Elementor sigue cargandose para la
   cabecera y el pie, y sin el prefijo gana el otro.
   ========================================================================== */

.eg-home {
  /* Paleta clara. El azul marino con degradados sobre las fotos es el
     lenguaje visual de 2018; hoy PcComponentes y MediaMarkt son blanco con
     mucho aire y el color reservado al acento. */
  --tinta: #101827;
  --texto: #4a5568;
  --suave: #7b8794;
  --borde: #e8ebf0;
  --fondo: #f7f8fa;
  --grafito: #1d1b1a;   /* carbon calido: el gris azulado tiraba a azul corporativo */
  --naranja: #ff5a1f;
  --naranja-osc: #e04400;
  --naranja-suave: #fff3ee;
  --verde: #0f7a45;
  --r: 18px;
  color: var(--texto);
  font-size: 16px;
  line-height: 1.55;
  background: #fff;
  padding-bottom: 14px;
}

/* ==========================================================================
   ARMADURA CONTRA EL TEMA
   --------------------------------------------------------------------------
   Esto va antes que nada. El contenido de una pagina vive dentro del
   contenedor del tema, que suele traer:
     - un ancho maximo estrecho para los hijos directos del contenido,
     - margenes propios en p, h2, ul,
     - box-sizing distinto, que descuadra cualquier rejilla con padding,
     - reglas de Elementor sobre img, a y section.
   Sin neutralizar todo eso, la portada se ve descuadrada aunque el CSS
   propio sea correcto.
   ========================================================================== */

.eg-home, .eg-home *, .eg-home *::before, .eg-home *::after { box-sizing: border-box; }

/* El contenedor del tema no debe estrechar ni centrar nuestros bloques */
.eg-home {
  max-width: none !important;
  width: auto !important;
  margin: 0 !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  float: none !important;
  clear: both;
  text-align: left;
}
.eg-home > *, .eg-home .eg-ancho > * { max-width: none; }

/* Margenes y listas del tema */
.eg-home p, .eg-home h1, .eg-home h2, .eg-home h3,
.eg-home ul, .eg-home ol, .eg-home li, .eg-home figure { margin: 0; padding: 0; }
.eg-home ul, .eg-home ol { list-style: none; }
.eg-home article, .eg-home section, .eg-home nav, .eg-home details { margin: 0; }

/* Enlaces e imagenes: algunos temas les meten subrayado, sombra o display */
.eg-home a { box-shadow: none !important; text-decoration: none; }
.eg-home img, .eg-home svg { display: block; max-width: 100%; height: auto; border: 0; }

/* El tema deja un hueco enorme entre la cabecera del sitio y el contenido */
body.page .eg-home { margin-top: 0 !important; }

/* La portada a todo el ancho aunque este dentro de un contenedor estrecho.
   calc(50% - 50vw) la saca del contenedor sin romper el flujo. */
.eg-hero {
  margin-left: calc(50% - 50vw) !important;
  margin-right: calc(50% - 50vw) !important;
  width: 100vw !important;
  max-width: 100vw !important;
}

.eg-saltar {
  position: absolute; left: -9999px; top: 0; z-index: 999;
  background: var(--grafito); color: #fff; padding: 12px 20px;
  border-radius: 0 0 var(--r) 0; font-weight: 700; text-decoration: none;
}
.eg-saltar:focus { left: 0; }
.eg-home a:focus-visible {
  outline: 3px solid var(--naranja); outline-offset: 3px; border-radius: 6px;
}
.eg-home img { max-width: 100%; height: auto; display: block; }
.eg-ancho { max-width: 1340px; margin: 0 auto; padding: 0 16px; }
.eg-seccion { margin: 28px 0; }

.eg-home h2 {
  font-size: 26px; font-weight: 800; letter-spacing: -.03em;
  color: var(--tinta); margin: 0; line-height: 1.15; text-wrap: balance;
}
.eg-seccion-cab {
  display: flex; align-items: center; justify-content: space-between;
  gap: 14px; flex-wrap: wrap; margin: 0 0 16px;
}
.eg-seccion-cab p { margin: 5px 0 0; color: var(--suave); font-size: 14.5px; }
.eg-vertodo {
  font-size: 14.5px; font-weight: 800; color: var(--naranja-osc) !important;
  text-decoration: none !important; white-space: nowrap;
}
.eg-vertodo:hover { text-decoration: underline !important; }

.eg-pill {
  display: inline-block; font-size: 11.5px; font-weight: 900;
  letter-spacing: .08em; text-transform: uppercase;
  padding: 6px 13px; border-radius: 999px;
}
.eg-pill-nuevo  { background: var(--naranja); color: #fff; }
.eg-pill-top    { background: #fff2e8; color: var(--naranja-osc); }
.eg-pill-oferta { background: var(--naranja); color: #fff; font-size: 13px; padding: 7px 13px; }

/* Botones. Rectangulo de esquina corta, nunca pastilla de 999 px: la
   pastilla es la forma que traen las plantillas y se reconoce enseguida.

   El principal va en blanco con reborde naranja y se RELLENA de naranja al
   pasar por encima. Es mas amable que un bloque naranja macizo y el relleno
   da la sensacion de respuesta que pedia el cliente. */
.eg-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 9px;
  min-height: 52px; padding: 14px 28px; border-radius: 12px;
  font-size: 16px; font-weight: 700; text-decoration: none !important;
  border: 2px solid transparent; letter-spacing: -.015em; position: relative;
  transition: background .2s ease, color .2s ease, border-color .2s ease,
              box-shadow .2s ease, transform .2s ease;
}
.eg-btn svg { width: 18px; height: 18px; flex: 0 0 18px; transition: transform .22s ease; }
.eg-btn:hover svg { transform: translateX(4px); }
.eg-btn:active { transform: translateY(1px) scale(.99); }

/* Principal: blanco con reborde naranja, se rellena al pasar por encima */
.eg-btn-principal {
  background: #fff; color: var(--naranja-osc) !important; border-color: var(--naranja);
  box-shadow: 0 2px 10px rgba(16,24,39,.06);
}
.eg-btn-principal:hover {
  background: var(--naranja); color: #fff !important; border-color: var(--naranja);
  box-shadow: 0 10px 26px rgba(255,90,31,.32); transform: translateY(-2px);
}

/* Secundario: reborde gris, se vuelve naranja al acercarse */
.eg-btn-suave {
  background: #fff; color: var(--tinta) !important; border-color: var(--borde);
}
.eg-btn-suave:hover {
  border-color: var(--naranja); color: var(--naranja-osc) !important;
  box-shadow: 0 8px 20px rgba(16,24,39,.1); transform: translateY(-2px);
}

/* Sobre fondo oscuro */
.eg-btn-linea {
  background: transparent; color: #fff !important; border-color: rgba(255,255,255,.45);
}
.eg-btn-linea:hover {
  background: #fff; color: var(--tinta) !important; border-color: #fff;
  box-shadow: 0 10px 26px rgba(0,0,0,.3); transform: translateY(-2px);
}

/* ========================= 1. PORTADA UTIL ==========================
   Una foto grande y bonita que no dice nada es espacio perdido justo donde
   se decide la compra. Aqui la mitad izquierda son los cuatro caminos de
   entrada al catalogo (enlaces internos con texto descriptivo: sirven al
   visitante y a Google) y la derecha una ficha real con precio y boton.
   =================================================================== */

.eg-hero { background: linear-gradient(125deg, #fff6f1 0%, #f8f9fb 55%, #f4f6f9 100%); }
.eg-hero-in {
  max-width: 1340px; margin: 0 auto; padding: 40px 16px 44px;
  display: grid; grid-template-columns: 1.12fr .88fr; gap: 40px; align-items: center;
}
.eg-home .eg-hero h1 {
  font-size: 46px; line-height: 1.04; font-weight: 800; letter-spacing: -.042em;
  color: var(--tinta); margin: 0 0 14px; text-wrap: balance;
}
.eg-hero > div > p, .eg-hero-txt > p {
  font-size: 17.5px; line-height: 1.5; color: var(--texto); margin: 0 0 24px; max-width: 520px;
}

/* Los cuatro caminos de entrada */
.eg-caminos { display: grid; grid-template-columns: 1fr 1fr; gap: 11px; margin-bottom: 24px; }
.eg-camino {
  display: flex; align-items: center; gap: 12px; background: #fff;
  border: 1px solid var(--borde); border-radius: 14px; padding: 13px 15px;
  text-decoration: none !important; color: inherit !important; min-height: 70px;
  transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
}
.eg-camino:hover {
  border-color: var(--naranja); transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(16,24,39,.1);
}
.eg-camino-foto {
  width: 46px; height: 46px; flex: 0 0 46px; border-radius: 10px;
  overflow: hidden; background: var(--fondo);
}
.eg-camino-foto img, .eg-camino-foto svg { width: 100%; height: 100%; object-fit: cover; }
.eg-camino b { display: block; font-size: 14.5px; font-weight: 800; color: var(--tinta); line-height: 1.2; }
.eg-camino span { display: block; font-size: 12.5px; color: var(--suave); margin-top: 3px; line-height: 1.3; }

.eg-hero-botones { display: flex; flex-wrap: wrap; gap: 12px; }

/* Ficha destacada de la derecha */
.eg-destacado {
  background: #fff; border: 1px solid var(--borde); border-radius: 22px;
  overflow: hidden; box-shadow: 0 18px 44px rgba(16,24,39,.1);
  display: flex; flex-direction: column;
}
.eg-destacado-foto { position: relative; aspect-ratio: 4 / 3; background: var(--fondo); overflow: hidden; }
.eg-destacado-foto img, .eg-destacado-foto svg {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .5s cubic-bezier(.2,.7,.3,1);
}
.eg-destacado:hover .eg-destacado-foto img, .eg-destacado:hover .eg-destacado-foto svg { transform: scale(1.04); }
.eg-destacado-etiq { position: absolute; top: 14px; left: 15px; z-index: 2; }
.eg-destacado-txt { padding: 20px 22px 22px; }
.eg-destacado-marca {
  font-size: 11px; font-weight: 900; letter-spacing: .1em; text-transform: uppercase;
  color: var(--suave); margin: 0 0 5px;
}
.eg-destacado-nombre {
  display: block; font-size: 19px; font-weight: 800; letter-spacing: -.025em;
  line-height: 1.25; color: var(--tinta) !important; text-decoration: none !important; margin-bottom: 10px;
}
.eg-destacado-nombre:hover { color: var(--naranja-osc) !important; }
.eg-destacado-precio {
  font-size: 34px; font-weight: 900; color: var(--tinta); letter-spacing: -.045em;
  font-variant-numeric: tabular-nums; line-height: 1; margin-bottom: 4px;
}
.eg-destacado-precio del { font-size: 17px; font-weight: 500; color: var(--suave); margin-right: 8px; letter-spacing: 0; }
.eg-destacado-precio ins { text-decoration: none; color: var(--naranja-osc); }
.eg-destacado-stock { font-size: 13px; color: var(--verde); font-weight: 800; margin: 0 0 16px; }
.eg-destacado .eg-btn { width: 100%; }

/* ==================== 2. MOSAICO DE PROMOCIONES =====================
   Tarjetas blancas: foto arriba a sangre y el texto debajo sobre blanco.
   La alternativa (velo oscuro encima de la foto) es lo que hacia que
   pareciese de 2018, y ademas se lee peor.
   =================================================================== */

.eg-promos { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.eg-promo {
  position: relative; overflow: hidden; border-radius: var(--r);
  text-decoration: none !important; color: inherit !important;
  background: #fff; border: 1px solid var(--borde);
  display: flex; flex-direction: column;
  transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.eg-promo:hover {
  transform: translateY(-4px); border-color: #dfe4ec;
  box-shadow: 0 16px 36px rgba(16,24,39,.11);
}
.eg-promo-foto { display: block; aspect-ratio: 16 / 10; overflow: hidden; background: var(--fondo); }
.eg-promo-foto img, .eg-promo-foto svg {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .55s cubic-bezier(.2,.7,.3,1);
}
.eg-promo:hover .eg-promo-foto img, .eg-promo:hover .eg-promo-foto svg { transform: scale(1.06); }
.eg-promo-txt { padding: 18px 20px 20px; display: flex; flex-direction: column; flex: 1; }
.eg-promo b {
  display: block; font-size: 19px; font-weight: 800; letter-spacing: -.03em;
  line-height: 1.18; margin-bottom: 6px; color: var(--tinta); text-wrap: balance;
}
.eg-promo span { display: block; font-size: 14px; color: var(--suave); line-height: 1.45; }
.eg-promo i {
  font-style: normal; display: inline-flex; align-items: center; gap: 6px;
  margin-top: 14px; font-size: 14.5px; font-weight: 800; color: var(--naranja-osc);
}
.eg-promo:hover i { text-decoration: underline; }
.eg-promo-etiq { position: absolute; top: 14px; left: 15px; z-index: 2; }

/* Los dos grandes: foto a la izquierda y texto a la derecha */
.eg-promo-xl { grid-column: span 2; grid-row: span 2; flex-direction: row; }
.eg-promo-xl .eg-promo-foto { aspect-ratio: auto; width: 52%; flex: 0 0 52%; }
.eg-promo-xl .eg-promo-txt { justify-content: center; padding: 28px 30px; }
.eg-promo-xl b { font-size: 27px; letter-spacing: -.038em; margin-bottom: 9px; }
.eg-promo-xl span { font-size: 15.5px; }

/* ======================= 3. FICHAS DE PRODUCTO ======================
   Fondo blanco solo aqui: el producto necesita fondo neutro. La foto
   sangra por arriba y por los lados de la ficha.
   =================================================================== */

.eg-fila { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
.eg-prod {
  position: relative; display: flex; flex-direction: column; background: #fff;
  border: 1px solid var(--borde); border-radius: var(--r); overflow: hidden;
  transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
}
.eg-prod:hover { box-shadow: 0 16px 34px rgba(16,24,39,.12); transform: translateY(-4px); border-color: #dfe4ec; }
.eg-prod-etiq { position: absolute; top: 12px; left: 12px; z-index: 2; }
.eg-prod-foto {
  display: block; aspect-ratio: 5 / 4; background: #f6f8fb; overflow: hidden;
}
.eg-prod-foto img, .eg-prod-foto svg {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .45s cubic-bezier(.2,.7,.3,1);
}
.eg-prod:hover .eg-prod-foto img, .eg-prod:hover .eg-prod-foto svg { transform: scale(1.06); }
.eg-prod-cuerpo { padding: 13px 15px 15px; display: flex; flex-direction: column; flex: 1; }
.eg-prod-marca {
  font-size: 11px; font-weight: 900; letter-spacing: .1em; text-transform: uppercase;
  color: var(--suave); margin: 0 0 5px;
}
.eg-prod-nombre {
  font-size: 14.5px; font-weight: 600; line-height: 1.32; color: var(--tinta) !important;
  text-decoration: none !important; display: block; margin-bottom: 11px;
}
.eg-prod-nombre:hover { color: var(--naranja-osc) !important; text-decoration: underline !important; }
.eg-prod-precio {
  font-size: 29px; font-weight: 900; color: var(--tinta); margin: auto 0 2px;
  letter-spacing: -.045em; font-variant-numeric: tabular-nums; line-height: 1;
}
.eg-prod-precio del { font-size: 15px; font-weight: 500; color: var(--suave); margin-right: 8px; letter-spacing: 0; }
.eg-prod-precio ins { text-decoration: none; color: var(--naranja-osc); }
.eg-prod-stock { font-size: 12.5px; color: var(--verde); font-weight: 800; margin: 6px 0 12px; }
.eg-prod-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  min-height: 48px; border-radius: 10px; background: var(--grafito);
  color: #fff !important; font-size: 15px; font-weight: 800;
  text-decoration: none !important; letter-spacing: -.015em;
  transition: background .16s ease, box-shadow .16s ease;
}
.eg-prod-btn svg { width: 17px; height: 17px; }
.eg-prod-btn:hover { background: var(--naranja); box-shadow: 0 6px 16px rgba(255,90,31,.4); }

/* ===================== 4. CATEGORIAS EN CIRCULO ===================== */

.eg-circulos { display: grid; grid-template-columns: repeat(8, 1fr); gap: 16px; }
.eg-circulo { text-decoration: none !important; color: inherit !important; text-align: center; }
.eg-circulo-foto {
  aspect-ratio: 1; border-radius: 50%; overflow: hidden; background: var(--fondo);
  margin-bottom: 11px; box-shadow: 0 4px 14px rgba(16,24,39,.09);
  transition: box-shadow .15s, transform .15s;
}
.eg-circulo-foto img, .eg-circulo-foto svg {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .4s cubic-bezier(.2,.7,.3,1);
}
.eg-circulo:hover .eg-circulo-foto img, .eg-circulo:hover .eg-circulo-foto svg { transform: scale(1.08); }
.eg-circulo:hover .eg-circulo-foto { box-shadow: 0 10px 24px rgba(7,42,77,.24); transform: translateY(-4px); }
.eg-circulo b { display: block; font-size: 14px; font-weight: 800; color: var(--tinta); line-height: 1.25; }

/* ======================== 5. BANDA A SANGRE ========================= */

.eg-banda {
  border-radius: var(--r); overflow: hidden; display: grid;
  grid-template-columns: 1fr 1fr; background: var(--grafito); color: #fff; min-height: 340px;
}
.eg-banda-txt { padding: 42px 40px; align-self: center; }
.eg-home .eg-banda h2 { color: #fff; font-size: 34px; letter-spacing: -.04em; margin: 14px 0 12px; }
.eg-banda p { color: #c0ccdb; font-size: 16.5px; line-height: 1.5; margin: 0 0 18px; max-width: 460px; }
.eg-banda-lista { list-style: none; margin: 0 0 26px; padding: 0; display: grid; gap: 9px; }
.eg-banda-lista li { font-size: 15px; color: #dbe3ee; padding-left: 26px; position: relative; }
.eg-banda-lista li::before {
  content: ""; position: absolute; left: 0; top: 7px; width: 11px; height: 11px;
  border-radius: 50%; background: var(--naranja);
}
.eg-banda-foto { position: relative; min-height: 260px; background: #2a2725; }
.eg-banda-foto img, .eg-banda-foto svg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.eg-banda-clara { background: #fff; color: var(--texto); }
.eg-home .eg-banda-clara h2 { color: var(--tinta); }
.eg-banda-clara p, .eg-banda-clara .eg-banda-lista li { color: var(--texto); }

/* ====================== 6. MARCAS Y CONFIANZA ======================= */

/* Muro de marcas: todas las que haya, en rejilla que se llena sola.
   Cada marca es una tarjeta blanca clicable a su pagina. */
.eg-marcas {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(158px, 1fr)); gap: 12px;
}
.eg-marca {
  background: #fff; border: 1px solid var(--borde); border-radius: 14px; min-height: 92px; padding: 16px 18px;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none !important; transition: box-shadow .15s, transform .15s;
}
.eg-marca:hover { box-shadow: 0 10px 24px rgba(7,42,77,.16); transform: translateY(-2px); }
.eg-marca img {
  max-height: 46px; max-width: 100%; width: auto; object-fit: contain;
  filter: grayscale(1); opacity: .62; transition: filter .2s ease, opacity .2s ease;
}
.eg-marca:hover img { filter: grayscale(0); opacity: 1; }
.eg-marca span {
  font-size: 16px; font-weight: 900; letter-spacing: -.02em; color: #4d5a6b;
  text-align: center; line-height: 1.2;
}

.eg-avales {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px;
  background: var(--borde); border: 1px solid var(--borde); border-radius: var(--r); overflow: hidden;
}
.eg-aval { background: #fff; padding: 17px 19px; display: flex; gap: 13px; align-items: flex-start; }
.eg-aval svg { width: 24px; height: 24px; flex: 0 0 24px; color: var(--naranja); margin-top: 1px; }
.eg-aval b { display: block; font-size: 15px; font-weight: 800; color: var(--tinta); margin-bottom: 3px; }
.eg-aval span { font-size: 13px; color: var(--suave); line-height: 1.45; display: block; }

/* Bloque de texto plegable: PcComponentes hace lo mismo con su texto de
   posicionamiento. Cuenta para Google y no le mete un muro de letra a quien
   viene a comprar. Es <details> nativo, sin JavaScript. */
.eg-texto > summary {
  cursor: pointer; list-style: none; display: inline-flex; align-items: center; gap: 8px;
  font-size: 15px; font-weight: 800; color: var(--naranja-osc); margin-top: 14px;
}
.eg-texto > summary::-webkit-details-marker { display: none; }
.eg-texto > summary::after { content: "\25be"; font-size: 15px; }
.eg-texto[open] > summary::after { content: "\25b4"; }
.eg-texto > summary:hover { text-decoration: underline; }

/* ========================= 7. TEXTO Y FAQ =========================== */

.eg-texto, .eg-faq { background: #fff; border: 1px solid var(--borde); border-radius: var(--r); }
.eg-texto { padding: 32px 34px; }
.eg-texto h2 { margin-bottom: 14px; }
.eg-texto h3 { font-size: 18px; font-weight: 800; color: var(--tinta); margin: 26px 0 9px; }
.eg-texto p { margin: 0 0 14px; font-size: 15.5px; line-height: 1.7; max-width: 78ch; }
.eg-texto p:last-child { margin-bottom: 0; }
.eg-texto a, .eg-faq-cuerpo a { color: var(--naranja-osc) !important; font-weight: 700; }

.eg-faq { overflow: hidden; }
.eg-faq details { border-bottom: 1px solid #e5eaf1; }
.eg-faq details:last-child { border-bottom: 0; }
.eg-faq summary {
  cursor: pointer; padding: 17px 22px; font-weight: 700; font-size: 15.5px;
  color: var(--tinta); list-style: none; position: relative; padding-right: 52px;
}
.eg-faq summary::-webkit-details-marker { display: none; }
.eg-faq summary::after {
  content: "+"; position: absolute; right: 22px; top: 50%; transform: translateY(-50%);
  font-size: 23px; font-weight: 400; color: var(--naranja); line-height: 1;
}
.eg-faq details[open] summary::after { content: "\2212"; }
.eg-faq summary:hover { background: #f6f8fb; }
.eg-faq-cuerpo { padding: 0 22px 19px; font-size: 15px; line-height: 1.65; max-width: 82ch; }
.eg-faq-cuerpo p { margin: 0; }

/* =========================== 8. CIERRE ============================== */

.eg-cierre {
  background: linear-gradient(115deg, #1d1b1a 0%, #3a3634 100%); color: #fff;
  border-radius: var(--r); padding: 34px 36px;
  display: flex; align-items: center; justify-content: space-between; gap: 22px; flex-wrap: wrap;
}
.eg-home .eg-cierre h2 { color: #fff; margin-bottom: 6px; }
.eg-cierre p { margin: 0; color: #c9dcf1; font-size: 15.5px; max-width: 560px; }

/* ============================= MOVIL ================================ */

@media (max-width: 1150px) {
  .eg-fila { grid-template-columns: repeat(3, 1fr); }
  .eg-circulos { grid-template-columns: repeat(4, 1fr); }
  .eg-promos { grid-auto-rows: 168px; }
}

@media (max-width: 900px) {
  .eg-hero-in { grid-template-columns: 1fr; gap: 26px; padding: 28px 16px 34px; }
  .eg-promos { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 160px; }
  .eg-promo-xl { grid-column: span 2; grid-row: span 1; flex-direction: column; }
  .eg-promo-xl .eg-promo-foto { width: auto; flex: none; aspect-ratio: 16 / 9; }
  .eg-promo-xl .eg-promo b { font-size: 24px; }
  .eg-banda { grid-template-columns: 1fr; }
  .eg-banda-foto { order: -1; min-height: 220px; }
  .eg-avales { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 767px) {
  .eg-home { font-size: 15.5px; }
  .eg-ancho { padding: 0 12px; }
  .eg-seccion { margin: 22px 0; }
  .eg-home h2 { font-size: 21px; }

  .eg-home .eg-hero h1 { font-size: 29px; }
  .eg-caminos { grid-template-columns: 1fr; gap: 9px; }
  .eg-camino { min-height: 62px; }
  .eg-destacado-precio { font-size: 30px; }
  .eg-hero p { font-size: 15.5px; }
  .eg-hero-botones { flex-direction: column; align-items: stretch; }
  .eg-btn { width: 100%; }

  .eg-promos { grid-auto-rows: 148px; gap: 10px; }
  .eg-promo-txt { padding: 14px 16px; }
  .eg-promo b { font-size: 17px; }

  .eg-promo-xl .eg-promo-txt { padding: 18px 18px; }

  /* Productos y categorias en carrusel: se pasa el dedo, como en la app
     de Amazon. Es scroll-snap, no JavaScript. */
  .eg-fila, .eg-circulos {
    display: flex; gap: 11px; overflow-x: auto; scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch; padding: 2px 12px 8px; margin: 0 -12px;
  }
  .eg-prod { flex: 0 0 63vw; max-width: 235px; scroll-snap-align: start; }
  .eg-circulo { flex: 0 0 27vw; max-width: 122px; scroll-snap-align: start; }
  .eg-prod-precio { font-size: 26px; }

  .eg-marcas { grid-template-columns: repeat(auto-fill, minmax(132px, 1fr)); gap: 9px; }
  .eg-marca { min-height: 70px; padding: 11px; }
  .eg-marca span { font-size: 14px; }
  .eg-avales { grid-template-columns: 1fr; }
  .eg-texto { padding: 22px 18px; }
  .eg-banda-txt { padding: 26px 20px; }
  .eg-home .eg-banda h2 { font-size: 24px; }
  .eg-cierre { padding: 24px 18px; flex-direction: column; align-items: flex-start; }
  .eg-cierre .eg-btn { width: 100%; }
}

@media (prefers-reduced-motion: reduce) {
  .eg-home *, .eg-home *::before, .eg-home *::after {
    transition-duration: .01ms !important; animation-duration: .01ms !important;
  }
  .eg-prod:hover, .eg-promo:hover, .eg-circulo:hover .eg-circulo-foto { transform: none; }
}
@media (forced-colors: active) {
  .eg-prod, .eg-aval, .eg-circulo-foto, .eg-promo { border: 1px solid CanvasText; }
}


/* ==========================================================================
   MOVIMIENTO
   Todo con CSS, sin una linea de JavaScript.

   - La entrada de la portada se anima al cargar, escalonada.
   - El resto aparece al hacer scroll con animation-timeline: view(), que es
     scroll nativo del navegador. Va dentro de @supports: donde no exista,
     no pasa nada y el contenido se ve desde el principio.
   ========================================================================== */

@keyframes eg-sube {
  from { opacity: 0; transform: translateY(22px); }
  to   { opacity: 1; transform: none; }
}
@keyframes eg-acerca {
  from { opacity: 0; transform: scale(1.04); }
  to   { opacity: 1; transform: none; }
}

.eg-hero-txt > * { animation: eg-sube .55s cubic-bezier(.2,.7,.3,1) backwards; }
.eg-hero-txt > *:nth-child(1) { animation-delay: .05s; }
.eg-hero-txt > *:nth-child(2) { animation-delay: .13s; }
.eg-hero-txt > *:nth-child(3) { animation-delay: .21s; }
.eg-hero-txt > *:nth-child(4) { animation-delay: .29s; }
.eg-hero-foto { animation: eg-acerca .7s cubic-bezier(.2,.7,.3,1) backwards; animation-delay: .1s; }

@supports (animation-timeline: view()) {
  .eg-promo, .eg-prod, .eg-circulo, .eg-banda, .eg-marca, .eg-aval, .eg-cierre {
    animation: eg-sube .6s cubic-bezier(.2,.7,.3,1) both;
    animation-timeline: view();
    animation-range: entry 0% cover 22%;
  }
  /* Escalonado dentro de cada fila: entran una detras de otra, no en bloque */
  .eg-promo:nth-child(2), .eg-prod:nth-child(2), .eg-circulo:nth-child(2) { animation-range: entry 2% cover 24%; }
  .eg-promo:nth-child(3), .eg-prod:nth-child(3), .eg-circulo:nth-child(3) { animation-range: entry 4% cover 26%; }
  .eg-promo:nth-child(4), .eg-prod:nth-child(4), .eg-circulo:nth-child(4) { animation-range: entry 6% cover 28%; }
  .eg-promo:nth-child(5), .eg-prod:nth-child(5), .eg-circulo:nth-child(5) { animation-range: entry 8% cover 30%; }
  .eg-circulo:nth-child(n+6) { animation-range: entry 10% cover 32%; }
}

/* Quien pide menos movimiento en su sistema, no tiene ninguno. */
@media (prefers-reduced-motion: reduce) {
  .eg-hero-txt > *, .eg-hero-foto, .eg-promo, .eg-prod, .eg-circulo,
  .eg-banda, .eg-marca, .eg-aval, .eg-cierre {
    animation: none !important;
  }
  .eg-promo:hover .eg-promo-foto svg, .eg-prod:hover .eg-prod-foto svg,
  .eg-circulo:hover .eg-circulo-foto svg,
  .eg-promo:hover .eg-promo-foto img, .eg-prod:hover .eg-prod-foto img,
  .eg-circulo:hover .eg-circulo-foto img { transform: none; }
}


/* ==========================================================================
   Margenes propios, despues de la armadura
   La armadura pone todo a cero; aqui se devuelve lo que si queremos.
   ========================================================================== */

.eg-home .eg-seccion { margin: 28px 0; }
.eg-home .eg-seccion-cab { margin: 0 0 16px; }
.eg-home .eg-seccion-cab p { margin: 4px 0 0; }
.eg-home .eg-hero h1 { margin: 0 0 14px; }
.eg-home .eg-hero p { margin: 0 0 24px; }
.eg-home .eg-texto p { margin: 0 0 14px; }
.eg-home .eg-texto p:last-child { margin: 0; }
.eg-home .eg-texto h3 { margin: 26px 0 9px; }
.eg-home .eg-texto h2 { margin: 0 0 14px; }
.eg-home .eg-banda h2 { margin: 14px 0 12px; }
.eg-home .eg-banda p { margin: 0 0 18px; }
.eg-home .eg-banda-lista { margin: 0 0 26px; }
.eg-home .eg-cierre h2 { margin: 0 0 6px; }
.eg-home .eg-cierre p { margin: 0; }
.eg-home .eg-prod-marca { margin: 0 0 5px; }
.eg-home .eg-prod-precio { margin: auto 0 2px; }
.eg-home .eg-prod-stock { margin: 6px 0 12px; }
.eg-home .eg-destacado-marca { margin: 0 0 5px; }
.eg-home .eg-destacado-stock { margin: 0 0 16px; }
.eg-home .eg-caminos { margin: 0 0 24px; }
.eg-home .eg-promo b { margin: 0 0 6px; }
.eg-home .eg-faq-cuerpo p { margin: 0; }
.eg-home .eg-aval b { margin: 0 0 3px; }
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
