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
   CONFIGURACION 1 · Accesos montados sobre la portada
   array( slug, titulo, texto pequeno )
   ========================================================================== */

function eg_portada_atajos_cfg() {
	return array(
		array( 'hypershell',      'Hypershell',       'La novedad' ),
		array( 'serie-delta',     'Estaciones DELTA', 'Respaldo para casa' ),
		array( 'paneles-solares', 'Placas solares',   'Ahorra en la factura' ),
		array( 'serie-rapid',     'Powerbanks',       'Carga r&aacute;pida' ),
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
		. ( $marca ? '<p class="eg-prod-marca">' . esc_html( $marca ) . '</p>' : '' )
		. '<a class="eg-prod-nombre" href="' . $url . '">' . esc_html( $p->get_name() ) . '</a>'
		. '<div class="eg-prod-precio">' . wp_kses_post( $p->get_price_html() ) . '</div>'
		. '<p class="eg-prod-stock">' . esc_html( $texto_stock ) . '</p>'
		. '<a class="eg-prod-btn" href="' . esc_url( $p->add_to_cart_url() ) . '" rel="nofollow">'
		. esc_html( $p->add_to_cart_text() ) . '</a>'
		. '</article>';
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
	$h .= eg_portada_atajos();

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

	return '<div class="eg-hero"><div class="eg-hero-in"><div>'
		. '<span class="eg-pill eg-pill-nuevo">Distribuidor oficial</span>'
		. '<h1>Energ&iacute;a port&aacute;til, solar y movilidad, con servicio t&eacute;cnico en Espa&ntilde;a</h1>'
		. '<p>EcoFlow, Hypershell y el resto de marcas que trabajamos. Te asesoramos antes de comprar y, si algo falla, lo resolvemos nosotros.</p>'
		. '<div class="eg-hero-botones">'
		. '<a class="eg-btn eg-btn-naranja" href="' . $tienda . '">Ver el cat&aacute;logo</a>'
		. '<a class="eg-btn eg-btn-linea" href="/contacto/">Preguntar antes de comprar</a>'
		. '</div></div>'
		. ( $foto ? '<div class="eg-hero-foto">' . $foto . '</div>' : '' )
		. '</div></div>';
}

/* ==========================================================================
   Accesos rapidos
   ========================================================================== */

function eg_portada_atajos() {

	$piezas = '';

	foreach ( eg_portada_atajos_cfg() as $a ) {
		$t = eg_portada_term( $a[0] );
		if ( ! $t ) { continue; }
		$piezas .= '<a class="eg-atajo" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<span class="eg-atajo-mini">' . eg_portada_foto_term( $t, 'woocommerce_thumbnail', false ) . '</span>'
			. '<span><b>' . $a[1] . '</b><span>' . $a[2] . '</span></span>'
			. '</a>';
	}

	if ( ! $piezas ) { return '<div class="eg-ancho"></div>'; }

	return '<nav class="eg-atajos" aria-label="Accesos r&aacute;pidos">' . $piezas . '</nav>';
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
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( is_wp_error( $marcas ) || count( $marcas ) < 2 ) { return ''; }

	$h = '<section class="eg-seccion" aria-labelledby="eg-t-marcas">'
		. '<div class="eg-seccion-cab"><div><h2 id="eg-t-marcas">Marcas con las que trabajamos</h2></div></div>'
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

	$items = array(
		array( 'Distribuidor oficial',           'Con la garant&iacute;a del fabricante.' ),
		array( 'Tienda f&iacute;sica',           'Puedes verlo y recogerlo en persona.' ),
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
/* ==========================================================================
   Portada · ecogadgetoficial.com
   --------------------------------------------------------------------------
   HTML y CSS puros. Sin Elementor y sin JavaScript.
   Se imprime en linea desde el snippet "EG · Portada", solo en is_front_page().

   Criterio: es una tienda, no un folleto. Lo primero que se ve son productos
   con su precio; el texto largo baja al final, donde sigue contando para
   Google sin estorbar a quien viene a comprar.

   Color: el azul de marca es la estructura (cabeceras, bandas). El naranja
   es SOLO para novedad, oferta y precio rebajado: si se usa para adornar
   deja de significar nada.

   No se declara font-family a proposito: hereda la del tema, que ya es la
   que usa el resto de la web.

   Prefijo .eg-home en todo porque el kit de Elementor sigue cargandose para
   la cabecera y el pie, y sin el prefijo gana el otro.
   ========================================================================== */

.eg-home {
  --azul: #06213d;
  --azul-medio: #12609f;
  --naranja: #ff6a13;
  --naranja-oscuro: #c74400;
  --verde: #0b7a3f;
  --borde: #e4e9f0;
  --texto: #3a4350;
  --suave: #6e7987;
  --tinta: #0b1220;
  --fondo: #f2f5f9;
  --radio: 14px;
  color: var(--texto);
  font-size: 16px;
  line-height: 1.55;
  background: var(--fondo);
  padding-bottom: 10px;
}

.eg-saltar {
  position: absolute; left: -9999px; top: 0; z-index: 999;
  background: var(--azul); color: #fff; padding: 12px 20px;
  border-radius: 0 0 var(--radio) 0; font-weight: 700; text-decoration: none;
}
.eg-saltar:focus { left: 0; }

.eg-home a:focus-visible, .eg-home button:focus-visible {
  outline: 3px solid var(--naranja); outline-offset: 2px; border-radius: 6px;
}
.eg-home img { max-width: 100%; height: auto; display: block; }
.eg-ancho { max-width: 1320px; margin: 0 auto; padding: 0 16px; }
.eg-seccion { margin: 30px 0; }

.eg-home h2 {
  font-size: 23px; font-weight: 800; letter-spacing: -.025em;
  color: var(--tinta); margin: 0; line-height: 1.2;
}
.eg-seccion-cab {
  display: flex; align-items: center; justify-content: space-between;
  gap: 14px; flex-wrap: wrap; margin: 0 0 15px;
}
.eg-seccion-cab p { margin: 4px 0 0; color: var(--suave); font-size: 14px; }
.eg-vertodo {
  font-size: 14px; font-weight: 700; color: var(--azul-medio) !important;
  text-decoration: none !important; white-space: nowrap;
}
.eg-vertodo:hover { text-decoration: underline !important; }

/* Etiquetas. El naranja solo aqui y en los precios rebajados. */
.eg-pill {
  display: inline-block; font-size: 11.5px; font-weight: 800;
  letter-spacing: .07em; text-transform: uppercase;
  padding: 5px 11px; border-radius: 999px;
}
.eg-pill-nuevo  { background: var(--naranja); color: #fff; }
.eg-pill-top    { background: #ffeadb; color: var(--naranja-oscuro); }
.eg-pill-oferta { background: var(--naranja); color: #fff; }

/* ============================ 1. PORTADA ============================
   Banda ancha a dos columnas: mensaje a la izquierda, producto grande a
   la derecha. Debajo, la fila de accesos montada sobre el borde.
   =================================================================== */

.eg-hero {
  background: radial-gradient(120% 140% at 12% 20%, #0d3c6e 0%, #06213d 62%, #04182c 100%);
  color: #fff; overflow: hidden;
}
.eg-hero-in {
  max-width: 1320px; margin: 0 auto; padding: 40px 16px 88px;
  display: grid; grid-template-columns: 1.02fr .98fr; gap: 30px; align-items: center;
}
.eg-home .eg-hero h1 {
  font-size: 42px; line-height: 1.05; font-weight: 800; letter-spacing: -.035em;
  color: #fff; margin: 13px 0 12px; text-wrap: balance;
}
.eg-hero p { font-size: 17px; line-height: 1.45; color: #cfe0f2; margin: 0 0 22px; max-width: 520px; }
.eg-hero-botones { display: flex; flex-wrap: wrap; gap: 11px; }
.eg-hero-foto {
  border-radius: var(--radio); overflow: hidden; aspect-ratio: 16 / 11;
  background: rgba(255,255,255,.06);
}
.eg-hero-foto img { width: 100%; height: 100%; object-fit: cover; }

.eg-btn {
  display: inline-flex; align-items: center; justify-content: center;
  min-height: 48px; padding: 12px 26px; border-radius: 999px;
  font-size: 15.5px; font-weight: 800; text-decoration: none !important;
  border: 2px solid transparent; letter-spacing: -.01em;
}
.eg-btn-naranja { background: var(--naranja); color: #fff !important; }
.eg-btn-naranja:hover { background: var(--naranja-oscuro); }
.eg-btn-blanco { background: #fff; color: var(--azul) !important; }
.eg-btn-blanco:hover { background: #dfe9f4; }
.eg-btn-linea { background: transparent; color: #fff !important; border-color: rgba(255,255,255,.5); }
.eg-btn-linea:hover { background: rgba(255,255,255,.13); }

/* Accesos rapidos montados sobre la portada */
.eg-atajos {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 13px;
  margin-top: -58px; position: relative; z-index: 2;
}
.eg-atajo {
  background: #fff; border-radius: var(--radio); padding: 13px 15px;
  box-shadow: 0 8px 24px rgba(6,33,61,.16);
  text-decoration: none !important; color: inherit !important;
  display: flex; gap: 12px; align-items: center; min-height: 76px;
}
.eg-atajo:hover { box-shadow: 0 12px 30px rgba(6,33,61,.24); }
.eg-atajo-mini {
  width: 56px; height: 56px; flex: 0 0 56px; border-radius: 10px;
  background: var(--fondo); overflow: hidden;
}
.eg-atajo-mini img, .eg-atajo-mini svg { width: 100%; height: 100%; object-fit: cover; }
.eg-atajo b { display: block; font-size: 14.5px; font-weight: 800; color: var(--tinta); line-height: 1.2; }
.eg-atajo span { display: block; font-size: 12.5px; color: var(--suave); margin-top: 3px; line-height: 1.3; }

/* ======================== 2. FILA DE PRODUCTOS ======================= */

.eg-fila { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
.eg-prod {
  position: relative; display: flex; flex-direction: column; background: #fff;
  border-radius: var(--radio); padding: 13px; transition: box-shadow .15s, transform .15s;
}
.eg-prod:hover { box-shadow: 0 10px 26px rgba(6,33,61,.14); transform: translateY(-2px); }
.eg-prod-etiq { position: absolute; top: 12px; left: 12px; z-index: 2; }
.eg-prod-foto { display: block; aspect-ratio: 1; margin-bottom: 11px; border-radius: 8px; overflow: hidden; }
.eg-prod-foto img, .eg-prod-foto svg { width: 100%; height: 100%; object-fit: contain; }
.eg-prod-marca {
  font-size: 11px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase;
  color: var(--suave); margin: 0 0 4px;
}
.eg-prod-nombre {
  font-size: 14px; font-weight: 600; line-height: 1.35; color: var(--tinta) !important;
  text-decoration: none !important; display: block; margin-bottom: 9px;
}
.eg-prod-nombre:hover { color: var(--azul-medio) !important; text-decoration: underline !important; }
.eg-prod-precio {
  font-size: 24px; font-weight: 800; color: var(--tinta); margin: 0 0 2px;
  letter-spacing: -.035em; font-variant-numeric: tabular-nums; line-height: 1.1;
}
.eg-prod-precio del { font-size: 14px; font-weight: 500; color: var(--suave); margin-right: 7px; letter-spacing: 0; }
.eg-prod-precio ins { text-decoration: none; color: var(--naranja-oscuro); }
.eg-prod-stock { font-size: 12.5px; color: var(--verde); font-weight: 700; margin: 0 0 12px; }
.eg-prod-btn {
  margin-top: auto; display: block; text-align: center; min-height: 44px;
  line-height: 44px; border-radius: 999px; background: var(--azul);
  color: #fff !important; font-size: 14.5px; font-weight: 800; text-decoration: none !important;
}
.eg-prod-btn:hover { background: var(--azul-medio); }

/* ==================== 3. CATEGORIAS EN CIRCULO ======================
   Lo que hacen MediaMarkt y PcComponentes: foto redonda y nombre debajo.
   Ocupa poco, se escanea de un vistazo y reparte trafico a categorias.
   =================================================================== */

.eg-circulos {
  display: grid; grid-template-columns: repeat(8, 1fr); gap: 14px;
}
.eg-circulo { text-decoration: none !important; color: inherit !important; text-align: center; }
.eg-circulo-foto {
  aspect-ratio: 1; border-radius: 50%; overflow: hidden; background: #fff;
  margin-bottom: 9px; box-shadow: 0 3px 12px rgba(6,33,61,.09);
  transition: box-shadow .15s, transform .15s;
}
.eg-circulo-foto img, .eg-circulo-foto svg { width: 100%; height: 100%; object-fit: cover; }
.eg-circulo:hover .eg-circulo-foto { box-shadow: 0 8px 20px rgba(6,33,61,.2); transform: translateY(-3px); }
.eg-circulo b { display: block; font-size: 13.5px; font-weight: 700; color: var(--tinta); line-height: 1.3; }

/* ======================= 4. BANDAS DE MARCA ========================= */

.eg-banda {
  border-radius: var(--radio); overflow: hidden; display: grid;
  grid-template-columns: 1fr 1fr; background: #0b1220; color: #fff; min-height: 320px;
}
.eg-banda-txt { padding: 40px 38px; align-self: center; }
.eg-home .eg-banda h2 { color: #fff; font-size: 31px; letter-spacing: -.035em; margin: 12px 0 10px; }
.eg-banda p { color: #c3cddb; font-size: 16px; line-height: 1.55; margin: 0 0 16px; max-width: 460px; }
.eg-banda-lista { list-style: none; margin: 0 0 24px; padding: 0; display: grid; gap: 8px; }
.eg-banda-lista li { font-size: 14.5px; color: #dde4ed; padding-left: 24px; position: relative; }
.eg-banda-lista li::before {
  content: ""; position: absolute; left: 0; top: 7px; width: 10px; height: 10px;
  border-radius: 50%; background: var(--naranja);
}
.eg-banda-foto { position: relative; background: #141d2b; min-height: 250px; }
.eg-banda-foto img, .eg-banda-foto svg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.eg-banda-clara { background: #fff; color: var(--texto); }
.eg-home .eg-banda-clara h2 { color: var(--tinta); }
.eg-banda-clara p { color: var(--texto); }
.eg-banda-clara .eg-banda-lista li { color: var(--texto); }
.eg-banda-clara .eg-banda-foto { background: var(--fondo); }

/* =========================== 5. MARCAS ============================== */

.eg-marcas {
  background: #fff; border-radius: var(--radio); padding: 20px 22px;
  display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; align-items: center;
}
.eg-marca { display: flex; align-items: center; justify-content: center; min-height: 54px; text-decoration: none !important; }
.eg-marca img { max-height: 40px; width: auto; object-fit: contain; }
.eg-marca span { font-size: 15px; font-weight: 800; letter-spacing: -.01em; color: #64707f; }

/* =========================== 6. AVALES ============================== */

.eg-avales { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.eg-aval { background: #fff; border-radius: var(--radio); padding: 15px 17px; }
.eg-aval b { display: block; font-size: 14.5px; font-weight: 800; color: var(--tinta); margin-bottom: 3px; }
.eg-aval span { font-size: 12.5px; color: var(--suave); line-height: 1.45; display: block; }

/* ======================= 7. TEXTO, TABLA, FAQ ======================= */

.eg-texto, .eg-faq, .eg-tabla-caja { background: #fff; border-radius: var(--radio); }
.eg-texto { padding: 30px 32px; }
.eg-texto h2 { margin-bottom: 13px; }
.eg-texto h3 { font-size: 17px; font-weight: 800; color: var(--tinta); margin: 24px 0 8px; }
.eg-texto p { margin: 0 0 13px; font-size: 15px; line-height: 1.7; max-width: 78ch; }
.eg-texto p:last-child { margin-bottom: 0; }
.eg-texto a, .eg-faq-cuerpo a { color: var(--azul-medio) !important; font-weight: 700; }

.eg-faq { overflow: hidden; }
.eg-faq details { border-bottom: 1px solid var(--borde); }
.eg-faq details:last-child { border-bottom: 0; }
.eg-faq summary {
  cursor: pointer; padding: 16px 20px; font-weight: 700; font-size: 15px;
  color: var(--tinta); list-style: none; position: relative; padding-right: 50px;
}
.eg-faq summary::-webkit-details-marker { display: none; }
.eg-faq summary::after {
  content: "+"; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
  font-size: 22px; font-weight: 400; color: var(--naranja); line-height: 1;
}
.eg-faq details[open] summary::after { content: "\2212"; }
.eg-faq summary:hover { background: var(--fondo); }
.eg-faq-cuerpo { padding: 0 20px 18px; font-size: 14.5px; line-height: 1.65; max-width: 82ch; }
.eg-faq-cuerpo p { margin: 0; }

/* ============================ 8. CIERRE ============================= */

.eg-cierre {
  background: linear-gradient(100deg, #06213d 0%, #0d3c6e 100%); color: #fff;
  border-radius: var(--radio); padding: 32px 34px;
  display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.eg-home .eg-cierre h2 { color: #fff; margin-bottom: 6px; }
.eg-cierre p { margin: 0; color: #cfe0f2; font-size: 15px; max-width: 560px; }

/* ============================= MOVIL =============================== */

@media (max-width: 1150px) {
  .eg-fila { grid-template-columns: repeat(3, 1fr); }
  .eg-circulos { grid-template-columns: repeat(4, 1fr); }
  .eg-marcas { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 900px) {
  .eg-hero-in { grid-template-columns: 1fr; padding-bottom: 76px; }
  .eg-hero-foto { order: -1; aspect-ratio: 16 / 9; }
  .eg-atajos { grid-template-columns: repeat(2, 1fr); margin-top: -46px; }
  .eg-banda { grid-template-columns: 1fr; }
  .eg-banda-foto { min-height: 210px; order: -1; }
  .eg-avales { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 767px) {
  .eg-home { font-size: 15.5px; }
  .eg-ancho { padding: 0 12px; }
  .eg-seccion { margin: 24px 0; }
  .eg-home h2 { font-size: 20px; }

  .eg-hero-in { padding: 22px 12px 66px; gap: 20px; }
  .eg-home .eg-hero h1 { font-size: 28px; }
  .eg-hero p { font-size: 15.5px; }
  .eg-hero-botones { flex-direction: column; align-items: stretch; }
  .eg-btn { width: 100%; }

  .eg-atajos { gap: 9px; margin-top: -38px; }
  .eg-atajo { padding: 10px 11px; min-height: 62px; gap: 9px; }
  .eg-atajo-mini { width: 42px; height: 42px; flex-basis: 42px; }
  .eg-atajo b { font-size: 12.5px; }
  .eg-atajo span { display: none; }

  /* Productos y categorias en carrusel: se pasa el dedo, como en Amazon.
     Es scroll-snap, no JavaScript. */
  .eg-fila, .eg-circulos {
    display: flex; gap: 11px; overflow-x: auto; scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch; padding: 2px 12px 8px; margin: 0 -12px;
  }
  .eg-prod { flex: 0 0 62vw; max-width: 225px; scroll-snap-align: start; }
  .eg-circulo { flex: 0 0 27vw; max-width: 120px; scroll-snap-align: start; }
  .eg-prod-precio { font-size: 22px; }

  .eg-marcas { grid-template-columns: repeat(2, 1fr); padding: 14px; }
  .eg-avales { grid-template-columns: 1fr; }
  .eg-texto { padding: 22px 18px; }
  .eg-banda-txt { padding: 26px 20px; }
  .eg-home .eg-banda h2 { font-size: 23px; }
  .eg-cierre { padding: 24px 18px; flex-direction: column; align-items: flex-start; }
  .eg-cierre .eg-btn { width: 100%; }
}

@media (prefers-reduced-motion: reduce) {
  .eg-home *, .eg-home *::before, .eg-home *::after {
    transition-duration: .01ms !important; animation-duration: .01ms !important;
  }
  .eg-prod:hover, .eg-circulo:hover .eg-circulo-foto { transform: none; }
}
@media (forced-colors: active) {
  .eg-prod, .eg-atajo, .eg-aval, .eg-circulo-foto { border: 1px solid CanvasText; }
}
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
