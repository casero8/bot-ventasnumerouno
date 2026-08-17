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
 * LO UNICO QUE HAY QUE TOCAR PARA ADAPTARLO: los tres bloques de
 * configuracion de aqui abajo (atajos, tarjetas y banda destacada). Todo se
 * define por slug de categoria; las categorias que no existan se saltan solas
 * y nunca dejan un hueco roto.
 *
 * Precios, stock, imagenes y marcas se leen de WooCommerce en cada
 * generacion. Nada escrito a mano.
 *
 * Sobre las tildes: los textos fijos van en entidades HTML (&aacute;,
 * &ntilde;...). Los snippets viajan por copia-pega entre editores y los
 * acentos en UTF-8 se han perdido ya dos veces en este proyecto. Las
 * entidades no se pierden.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ==========================================================================
   CONFIGURACION 1 · Los cuatro accesos montados sobre el banner
   array( slug de categoria, titulo, texto pequeno )
   ========================================================================== */

function eg_portada_atajos_cfg() {
	return array(
		array( 'hypershell',       'Hypershell',        'Exoesqueletos' ),
		array( 'serie-delta',      'Estaciones DELTA',  'Respaldo para casa' ),
		array( 'paneles-solares',  'Placas solares',    'Port&aacute;tiles y balc&oacute;n' ),
		array( 'serie-rapid',      'Powerbanks',        'Carga r&aacute;pida' ),
	);
}

/* ==========================================================================
   CONFIGURACION 2 · Tarjetas con mosaico de cuatro fotos
   Es la pieza central: casi todo son imagenes, como en Amazon.

   array(
     'titulo'  => rotulo de la tarjeta,
     'ver'     => slug al que lleva el enlace de abajo,
     'texto'   => texto de ese enlace,
     'piezas'  => hasta 4 slugs con su rotulo,
     'grande'  => true para una sola foto grande en vez de mosaico
   )

   Una tarjeta con menos de 2 piezas validas no se pinta.
   ========================================================================== */

function eg_portada_tarjetas_cfg() {
	return array(
		array(
			'titulo' => 'Energ&iacute;a port&aacute;til',
			'ver'    => 'serie-delta',
			'texto'  => 'Ver todas las estaciones',
			'piezas' => array(
				array( 'serie-delta',     'Estaciones DELTA' ),
				array( 'serie-river',     'Bater&iacute;as RIVER' ),
				array( 'generador-solar', 'Generadores solares' ),
				array( 'serie-rapid',     'Powerbanks RAPID' ),
			),
		),
		array(
			'titulo' => 'Solar para casa y balc&oacute;n',
			'ver'    => 'paneles-solares',
			'texto'  => 'Ver todo lo solar',
			'piezas' => array(
				array( 'paneles-solares',    'Placas solares' ),
				array( 'kits-para-el-hogar', 'Kits para balc&oacute;n' ),
				array( 'stream-series',      'EcoFlow STREAM' ),
				array( 'generador-solar',    'Kits todo en uno' ),
			),
		),
		array(
			'titulo' => 'Hypershell &middot; lo m&aacute;s nuevo',
			'ver'    => 'hypershell',
			'texto'  => 'Descubrir Hypershell',
			'grande' => true,
			'piezas' => array(
				array( 'hypershell', 'Exoesqueletos Hypershell' ),
			),
		),
		array(
			'titulo' => 'Accesorios y carga',
			'ver'    => 'accesorios',
			'texto'  => 'Ver accesorios',
			'piezas' => array(
				array( 'accesorios',  'Cables y adaptadores' ),
				array( 'serie-rapid', 'Cargadores y powerbanks' ),
			),
		),
	);
}

/* ==========================================================================
   CONFIGURACION 3 · Banda destacada (la novedad de temporada)
   ========================================================================== */

function eg_portada_banda_cfg() {
	return array(
		'slug'     => 'hypershell',
		'etiqueta' => 'Novedad',
		'titulo'   => 'Hypershell: el exoesqueleto que te quita peso de las piernas',
		'texto'    => 'Un motor te acompa&ntilde;a al andar y al subir. Pensado para caminatas largas, monta&ntilde;a y para quien pasa el d&iacute;a de pie.',
		'puntos'   => array(
			'Se pone y se quita en segundos',
			'Bater&iacute;a intercambiable',
			'Distribuidor oficial en Espa&ntilde;a',
		),
		'boton'    => 'Ver los modelos',
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
	if ( ! $id ) {
		return '';
	}
	return wp_get_attachment_image( $id, $tam, false, array(
		'alt'     => '',
		'loading' => $lazy ? 'lazy' : 'eager',
	) );
}

/* ==========================================================================
   Shortcode
   ========================================================================== */

add_shortcode( 'eg_portada', 'eg_portada_html' );

function eg_portada_html() {

	$tienda = esc_url( wc_get_page_permalink( 'shop' ) );

	$h  = '<div class="eg-home">';
	$h .= '<a class="eg-saltar" href="#eg-comprar">Saltar a los productos</a>';

	$h .= eg_portada_banner( $tienda );

	$h .= '<div class="eg-ancho">';
	$h .= eg_portada_atajos();

	$h .= '<section class="eg-seccion" id="eg-comprar" aria-labelledby="eg-t-cat">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-cat">Compra por categor&iacute;a</h2>'
		. '</div><a class="eg-vertodo" href="' . $tienda . '">Ver toda la tienda &rarr;</a></div>'
		. eg_portada_tarjetas()
		. '</section>';

	$h .= eg_portada_banda();
	$h .= eg_portada_productos( $tienda );
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
   Banner
   ========================================================================== */

function eg_portada_banner( $tienda ) {

	$id   = (int) get_option( 'eg_portada_hero', 0 );
	$foto = '';

	if ( $id ) {
		// Sin lazy y con prioridad alta: es lo primero que se ve.
		$foto = wp_get_attachment_image( $id, 'full', false, array(
			'class'         => 'eg-banner-foto',
			'alt'           => '',
			'aria-hidden'   => 'true',
			'loading'       => 'eager',
			'decoding'      => 'sync',
			'fetchpriority' => 'high',
		) );
	}

	return '<div class="eg-banner">' . $foto
		. '<div class="eg-ancho"><div class="eg-banner-txt">'
		. '<span class="eg-etiqueta">Distribuidor oficial</span>'
		. '<h1>Energ&iacute;a port&aacute;til, solar y movilidad, con servicio t&eacute;cnico en Espa&ntilde;a</h1>'
		. '<p>EcoFlow, Hypershell y el resto de marcas que trabajamos. Te asesoramos antes de comprar y, si algo falla, lo resolvemos nosotros.</p>'
		. '<div class="eg-banner-botones">'
		. '<a class="eg-btn eg-btn-principal" href="' . $tienda . '">Ver el cat&aacute;logo</a>'
		. '<a class="eg-btn eg-btn-secundario" href="/contacto/">Preguntar antes de comprar</a>'
		. '</div></div></div></div>';
}

/* ==========================================================================
   Atajos sobre el banner
   ========================================================================== */

function eg_portada_atajos() {

	$piezas = '';
	$n = 0;

	foreach ( eg_portada_atajos_cfg() as $a ) {
		$t = eg_portada_term( $a[0] );
		if ( ! $t ) { continue; }
		$n++;
		$piezas .= '<a class="eg-atajo" href="' . esc_url( get_term_link( $t ) ) . '">'
			. '<span class="eg-atajo-mini">' . eg_portada_foto_term( $t, 'woocommerce_gallery_thumbnail', false ) . '</span>'
			. '<span><b>' . $a[1] . '</b><span>' . $a[2] . '</span></span>'
			. '</a>';
	}

	if ( ! $n ) {
		return '<div class="eg-ancho"></div>';
	}

	return '<div class="eg-ancho"><nav class="eg-atajos" aria-label="Accesos r&aacute;pidos">' . $piezas . '</nav></div>';
}

/* ==========================================================================
   Tarjetas con mosaico
   ========================================================================== */

function eg_portada_tarjetas() {

	$html = '<div class="eg-cards">';

	foreach ( eg_portada_tarjetas_cfg() as $c ) {

		$grande = ! empty( $c['grande'] );
		$piezas = '';
		$n      = 0;

		foreach ( $c['piezas'] as $p ) {
			$t = eg_portada_term( $p[0] );
			if ( ! $t ) { continue; }
			$n++;
			$piezas .= '<a class="eg-pieza" href="' . esc_url( get_term_link( $t ) ) . '">'
				. '<span class="eg-pieza-foto">' . eg_portada_foto_term( $t, 'woocommerce_thumbnail', $n > 2 ) . '</span>'
				. '<span>' . $p[1] . '</span></a>';
		}

		// Una tarjeta a medias queda peor que no ponerla.
		$minimo = $grande ? 1 : 2;
		if ( $n < $minimo ) { continue; }

		$ver  = eg_portada_term( $c['ver'] );
		$link = $ver
			? '<a class="eg-vertodo" href="' . esc_url( get_term_link( $ver ) ) . '">' . $c['texto'] . ' &rarr;</a>'
			: '';

		$html .= '<div class="eg-card' . ( $grande ? ' eg-card-grande' : '' ) . '">'
			. '<h3>' . $c['titulo'] . '</h3>'
			. '<div class="eg-mosaico">' . $piezas . '</div>'
			. $link . '</div>';
	}

	return $html . '</div>';
}

/* ==========================================================================
   Banda destacada
   ========================================================================== */

function eg_portada_banda() {

	$c = eg_portada_banda_cfg();
	$t = eg_portada_term( $c['slug'] );

	if ( ! $t ) {
		return ''; // si la categoria no existe, la banda no se pinta
	}

	$puntos = '';
	foreach ( $c['puntos'] as $p ) {
		$puntos .= '<li>' . $p . '</li>';
	}

	$foto = eg_portada_foto_term( $t, 'full' );

	return '<section class="eg-seccion"><div class="eg-banda">'
		. '<div class="eg-banda-txt">'
		. '<span class="eg-etiqueta">' . $c['etiqueta'] . '</span>'
		. '<h2>' . $c['titulo'] . '</h2>'
		. '<p>' . $c['texto'] . '</p>'
		. '<ul class="eg-banda-lista">' . $puntos . '</ul>'
		. '<a class="eg-btn eg-btn-principal" href="' . esc_url( get_term_link( $t ) ) . '">' . $c['boton'] . '</a>'
		. '</div>'
		. '<div class="eg-banda-foto">' . $foto . '</div>'
		. '</div></section>';
}

/* ==========================================================================
   Productos
   Solo con stock y con precio. Menos de cinco, no se pinta la fila.
   ========================================================================== */

function eg_portada_productos( $tienda ) {

	$q = new WP_Query( array(
		'post_type'           => 'product',
		'posts_per_page'      => 10,
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

	if ( $q->post_count < 5 ) {
		wp_reset_postdata();
		return '';
	}

	$h = '<section class="eg-seccion" aria-labelledby="eg-t-prod">'
		. '<div class="eg-seccion-cab"><div>'
		. '<h2 id="eg-t-prod">Los m&aacute;s vendidos, disponibles ahora</h2>'
		. '<p>Con stock confirmado hoy.</p>'
		. '</div><a class="eg-vertodo" href="' . $tienda . '">Ver todos &rarr;</a></div>'
		. '<div class="eg-productos">';

	$i = 0;

	while ( $q->have_posts() ) {

		$q->the_post();
		$p = wc_get_product( get_the_ID() );
		if ( ! $p ) { continue; }

		$i++;
		$url   = esc_url( get_permalink() );
		$stock = $p->get_stock_quantity();
		$texto_stock = ( $stock && $stock > 0 )
			? ( 1 === (int) $stock ? '1 disponible' : $stock . ' disponibles' )
			: 'Disponible';

		// La marca solo se muestra si existe de verdad como taxonomia.
		$marca = eg_portada_marca_de( get_the_ID() );

		// La foto lleva tabindex="-1" y aria-hidden: va al mismo sitio que el
		// titulo de al lado y sin esto el lector de pantalla repite cada producto.
		$h .= '<article class="eg-prod">'
			. '<a class="eg-prod-foto" href="' . $url . '" tabindex="-1" aria-hidden="true">'
			. $p->get_image( 'woocommerce_thumbnail', array( 'loading' => $i <= 5 ? 'eager' : 'lazy' ) )
			. '</a>'
			. ( $marca ? '<p class="eg-prod-marca">' . esc_html( $marca ) . '</p>' : '' )
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

/**
 * Marca de un producto. Se busca en las taxonomias que suelen usarse; si no
 * hay ninguna, se devuelve vacio y no se pinta nada. Nunca se inventa.
 */
function eg_portada_marca_de( $id ) {

	foreach ( array( 'product_brand', 'pwb-brand', 'pa_marca', 'yith_product_brand' ) as $tax ) {

		if ( ! taxonomy_exists( $tax ) ) { continue; }

		$terminos = wp_get_post_terms( $id, $tax, array( 'fields' => 'names' ) );

		if ( ! is_wp_error( $terminos ) && ! empty( $terminos ) ) {
			return $terminos[0];
		}
	}

	return '';
}

/* ==========================================================================
   Marcas con las que trabajamos
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
		. '</div><a class="eg-btn eg-btn-principal" href="/contacto/">Escr&iacute;benos</a></div></section>';
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

   Estructura visual: mosaicos de fotos, como Amazon. El texto largo baja al
   final, donde no estorba a quien viene a comprar y sigue contando para Google.

   Prefijo .eg-home en todo porque el kit de Elementor sigue cargandose para
   la cabecera y el pie, y sin el prefijo gana el otro.
   ========================================================================== */

.eg-home {
  --azul: #042c53;
  --azul-medio: #185fa5;
  --azul-claro: #eef4fb;
  --verde: #0f8a4a;
  --ambar: #f0a202;
  --borde: #e1e6ee;
  --texto: #37404d;
  --suave: #6b7686;
  --tinta: #0d1520;
  --fondo: #f4f7fb;
  --radio: 10px;
  color: var(--texto);
  font-size: 16px;
  line-height: 1.6;
  background: var(--fondo);
  padding-bottom: 8px;
}

.eg-saltar {
  position: absolute; left: -9999px; top: 0; z-index: 999;
  background: var(--azul); color: #fff; padding: 12px 20px;
  border-radius: 0 0 var(--radio) 0; font-weight: 600; text-decoration: none;
}
.eg-saltar:focus { left: 0; }

.eg-home a:focus-visible, .eg-home button:focus-visible {
  outline: 3px solid var(--azul-medio); outline-offset: 2px; border-radius: 4px;
}
.eg-home img { max-width: 100%; height: auto; display: block; }
.eg-ancho { max-width: 1300px; margin: 0 auto; padding: 0 16px; }
.eg-seccion { margin: 34px 0; }

.eg-home h2 {
  font-size: 22px; font-weight: 700; letter-spacing: -.02em;
  color: var(--tinta); margin: 0; line-height: 1.25;
}
.eg-seccion-cab {
  display: flex; align-items: baseline; justify-content: space-between;
  gap: 14px; flex-wrap: wrap; margin: 0 0 14px;
}
.eg-seccion-cab p { margin: 5px 0 0; color: var(--suave); font-size: 14px; }
.eg-vertodo {
  font-size: 14px; font-weight: 600; color: var(--azul-medio) !important;
  text-decoration: none !important; white-space: nowrap;
}
.eg-vertodo:hover { text-decoration: underline !important; }

/* ====================== 1. BANNER Y TARJETAS ENCIMA =====================
   El patron de Amazon: imagen ancha arriba y una fila de tarjetas blancas
   montadas sobre su borde inferior.
   ====================================================================== */

.eg-banner {
  position: relative; min-height: 340px; overflow: hidden;
  background: linear-gradient(105deg, #04294d 0%, #0b4176 55%, #12629f 100%);
  display: flex; align-items: center;
}
.eg-banner-foto { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .5; }
.eg-banner-txt { position: relative; max-width: 620px; padding: 40px 30px 96px; }
.eg-etiqueta {
  display: inline-block; background: var(--ambar); color: #2b1d00;
  font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
  padding: 5px 12px; border-radius: 999px; margin-bottom: 12px;
}
.eg-home .eg-banner h1 {
  font-size: 38px; line-height: 1.08; font-weight: 800; letter-spacing: -.03em;
  color: #fff; margin: 0 0 12px;
}
.eg-banner p { font-size: 17px; line-height: 1.45; color: #d5e3f3; margin: 0 0 20px; }
.eg-banner-botones { display: flex; flex-wrap: wrap; gap: 10px; }

.eg-btn {
  display: inline-flex; align-items: center; justify-content: center;
  min-height: 46px; padding: 11px 24px; border-radius: 999px;
  font-size: 15px; font-weight: 700; text-decoration: none !important;
  border: 2px solid transparent;
}
.eg-btn-principal { background: #fff; color: var(--azul) !important; }
.eg-btn-principal:hover { background: #e3ecf7; }
.eg-btn-secundario { background: transparent; color: #fff !important; border-color: rgba(255,255,255,.5); }
.eg-btn-secundario:hover { background: rgba(255,255,255,.14); }

/* Fila de accesos montada sobre el banner */
.eg-atajos {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
  margin-top: -66px; position: relative; z-index: 2;
}
.eg-atajo {
  background: #fff; border-radius: var(--radio); padding: 14px 16px;
  box-shadow: 0 6px 20px rgba(4,44,83,.13);
  text-decoration: none !important; color: inherit !important;
  display: flex; gap: 12px; align-items: center; min-height: 74px;
}
.eg-atajo:hover { box-shadow: 0 8px 26px rgba(4,44,83,.2); }
.eg-atajo-mini {
  width: 54px; height: 54px; flex: 0 0 54px; border-radius: 8px;
  background: var(--fondo); overflow: hidden; display: flex;
  align-items: center; justify-content: center;
}
.eg-atajo-mini img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
.eg-atajo b { display: block; font-size: 14.5px; color: var(--tinta); line-height: 1.25; }
.eg-atajo span { display: block; font-size: 12.5px; color: var(--suave); margin-top: 2px; line-height: 1.35; }

/* ==================== 2. TARJETAS CON MOSAICO DE 4 ====================
   La pieza central de Amazon: titulo, cuatro fotos con su rotulo y un
   enlace de "ver mas" abajo. Todo son fotos, casi nada de texto.
   ===================================================================== */

.eg-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.eg-card {
  background: #fff; border-radius: var(--radio); padding: 18px 18px 14px;
  display: flex; flex-direction: column;
}
.eg-card > h3 {
  font-size: 17.5px; font-weight: 700; color: var(--tinta);
  margin: 0 0 13px; line-height: 1.25;
}
.eg-mosaico { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 13px; }
.eg-pieza { text-decoration: none !important; color: inherit !important; display: block; }
.eg-pieza-foto {
  aspect-ratio: 1; background: var(--fondo); border-radius: 6px;
  overflow: hidden; display: flex; align-items: center; justify-content: center;
}
.eg-pieza-foto img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
.eg-pieza span {
  display: block; font-size: 12.5px; color: var(--texto); margin-top: 6px;
  line-height: 1.3;
}
.eg-pieza:hover span { color: var(--azul-medio); text-decoration: underline; }
.eg-card > .eg-vertodo { margin-top: auto; }

/* Variante de una sola foto grande, para categorias con poca profundidad */
.eg-card-grande .eg-pieza-foto { aspect-ratio: 4 / 3; }
.eg-card-grande .eg-mosaico { grid-template-columns: 1fr; }

/* ===================== 3. BANDA DE MARCA DESTACADA ==================== */

.eg-banda {
  border-radius: var(--radio); overflow: hidden; display: grid;
  grid-template-columns: 1.05fr .95fr; background: #10161f; color: #fff;
  min-height: 300px;
}
.eg-banda-txt { padding: 38px 36px; align-self: center; }
.eg-home .eg-banda h2 { color: #fff; font-size: 30px; letter-spacing: -.03em; margin-bottom: 10px; }
.eg-banda p { color: #c2ccd9; font-size: 16px; line-height: 1.55; margin: 0 0 12px; max-width: 470px; }
.eg-banda-lista { list-style: none; margin: 0 0 22px; padding: 0; display: grid; gap: 7px; }
.eg-banda-lista li { font-size: 14.5px; color: #dbe3ec; padding-left: 22px; position: relative; }
.eg-banda-lista li::before {
  content: ""; position: absolute; left: 0; top: 8px; width: 9px; height: 9px;
  border-radius: 50%; background: var(--ambar);
}
.eg-banda-foto { position: relative; background: #1a2330; min-height: 240px; }
.eg-banda-foto img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }

/* ========================= 4. PRODUCTOS ============================== */

.eg-productos { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
.eg-prod {
  display: flex; flex-direction: column; background: #fff;
  border-radius: var(--radio); padding: 13px;
}
.eg-prod-foto { display: block; aspect-ratio: 1; margin-bottom: 10px; }
.eg-prod-foto img { width: 100%; height: 100%; object-fit: contain; }
.eg-prod-marca {
  font-size: 11px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase;
  color: var(--suave); margin-bottom: 4px;
}
.eg-prod-nombre {
  font-size: 14px; font-weight: 600; line-height: 1.35; color: var(--tinta) !important;
  text-decoration: none !important; display: block; margin-bottom: 7px;
}
.eg-prod-nombre:hover { color: var(--azul-medio) !important; text-decoration: underline !important; }
.eg-prod-precio { font-size: 19px; font-weight: 800; color: var(--tinta); margin: 0 0 3px; letter-spacing: -.02em; }
.eg-prod-precio del { font-size: 13.5px; font-weight: 400; color: var(--suave); margin-right: 5px; }
.eg-prod-precio ins { text-decoration: none; }
.eg-prod-stock { font-size: 12px; color: var(--verde); font-weight: 700; margin-bottom: 11px; }
.eg-prod-btn {
  margin-top: auto; display: block; text-align: center; min-height: 44px;
  line-height: 44px; border-radius: 999px; background: var(--azul);
  color: #fff !important; font-size: 14px; font-weight: 700; text-decoration: none !important;
}
.eg-prod-btn:hover { background: var(--azul-medio); }

/* ========================== 5. MARCAS =============================== */

.eg-marcas {
  background: #fff; border-radius: var(--radio); padding: 22px 24px;
  display: grid; grid-template-columns: repeat(6, 1fr); gap: 18px; align-items: center;
}
.eg-marca {
  display: flex; align-items: center; justify-content: center; min-height: 56px;
  text-decoration: none !important;
}
.eg-marca img { max-height: 42px; width: auto; object-fit: contain; }
.eg-marca span {
  font-size: 15px; font-weight: 800; letter-spacing: -.01em; color: #5c6878;
}

/* ========================== 6. AVALES =============================== */

.eg-avales {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px;
}
.eg-aval { background: #fff; border-radius: var(--radio); padding: 15px 17px; }
.eg-aval b { display: block; font-size: 14.5px; color: var(--tinta); margin-bottom: 2px; }
.eg-aval span { font-size: 12.5px; color: var(--suave); line-height: 1.45; display: block; }

/* ====================== 7. TEXTO, TABLA Y FAQ ======================== */

.eg-texto, .eg-faq, .eg-tabla-caja {
  background: #fff; border-radius: var(--radio);
}
.eg-texto { padding: 28px 30px; }
.eg-texto h2 { margin-bottom: 12px; }
.eg-texto h3 { font-size: 17px; font-weight: 700; color: var(--tinta); margin: 22px 0 8px; }
.eg-texto p { margin: 0 0 13px; font-size: 15px; line-height: 1.7; }
.eg-texto p:last-child { margin-bottom: 0; }
.eg-texto a, .eg-tabla a, .eg-faq-cuerpo a { color: var(--azul-medio) !important; font-weight: 600; }

.eg-tabla-caja { overflow-x: auto; }
.eg-home table.eg-tabla { width: 100%; border-collapse: collapse; font-size: 14.5px; min-width: 600px; }
.eg-home table.eg-tabla caption { text-align: left; padding: 15px 18px 0; color: var(--suave); font-size: 13.5px; }
.eg-home table.eg-tabla th, .eg-home table.eg-tabla td {
  padding: 12px 18px; text-align: left; border-bottom: 1px solid var(--borde);
}
.eg-home table.eg-tabla thead th {
  background: var(--fondo); font-size: 12px; text-transform: uppercase;
  letter-spacing: .06em; color: #5f6b7c; font-weight: 700;
}
.eg-home table.eg-tabla tbody th { font-weight: 700; color: var(--tinta); }
.eg-home table.eg-tabla tr:last-child td, .eg-home table.eg-tabla tr:last-child th { border-bottom: 0; }

.eg-faq { overflow: hidden; }
.eg-faq details { border-bottom: 1px solid var(--borde); }
.eg-faq details:last-child { border-bottom: 0; }
.eg-faq summary {
  cursor: pointer; padding: 15px 20px; font-weight: 600; font-size: 15px;
  color: var(--tinta); list-style: none; position: relative; padding-right: 48px;
}
.eg-faq summary::-webkit-details-marker { display: none; }
.eg-faq summary::after {
  content: "+"; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
  font-size: 21px; font-weight: 400; color: var(--azul-medio); line-height: 1;
}
.eg-faq details[open] summary::after { content: "\2212"; }
.eg-faq summary:hover { background: var(--fondo); }
.eg-faq-cuerpo { padding: 0 20px 17px; font-size: 14.5px; line-height: 1.65; }
.eg-faq-cuerpo p { margin: 0; }

/* =========================== 8. CIERRE ============================== */

.eg-cierre {
  background: var(--azul); color: #fff; border-radius: var(--radio);
  padding: 30px 32px; display: flex; align-items: center;
  justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.eg-home .eg-cierre h2 { color: #fff; margin-bottom: 5px; }
.eg-cierre p { margin: 0; color: #d5e3f3; font-size: 15px; max-width: 560px; }

/* ============================ MOVIL ================================ */

@media (max-width: 1150px) {
  .eg-cards { grid-template-columns: repeat(2, 1fr); }
  .eg-productos { grid-template-columns: repeat(3, 1fr); }
  .eg-marcas { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 900px) {
  .eg-atajos { grid-template-columns: repeat(2, 1fr); margin-top: -30px; }
  .eg-banner-txt { padding-bottom: 62px; }
  .eg-banda { grid-template-columns: 1fr; }
  .eg-banda-foto { min-height: 220px; order: -1; }
  .eg-avales { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 767px) {
  .eg-home { font-size: 15.5px; }
  .eg-ancho { padding: 0 12px; }
  .eg-seccion { margin: 26px 0; }
  .eg-home h2 { font-size: 19.5px; }

  .eg-banner { min-height: 0; }
  .eg-banner-txt { padding: 30px 18px 54px; }
  .eg-home .eg-banner h1 { font-size: 26px; }
  .eg-banner p { font-size: 15.5px; }
  .eg-banner-botones { flex-direction: column; align-items: stretch; }
  .eg-btn { width: 100%; }

  .eg-atajos { grid-template-columns: 1fr 1fr; gap: 10px; margin-top: -26px; }
  .eg-atajo { padding: 10px 11px; min-height: 62px; }
  .eg-atajo-mini { width: 42px; height: 42px; flex-basis: 42px; }
  .eg-atajo b { font-size: 13px; }
  .eg-atajo span { display: none; }

  .eg-cards { grid-template-columns: 1fr; }

  /* Productos en carrusel horizontal, como Amazon: se pasa el dedo en vez
     de bajar por diez tarjetas apiladas. Es scroll-snap, no JavaScript. */
  .eg-productos {
    display: flex; gap: 11px; overflow-x: auto; scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch; padding: 0 12px 6px;
    margin: 0 -12px;
  }
  .eg-prod { flex: 0 0 64vw; max-width: 230px; scroll-snap-align: start; }

  .eg-marcas { grid-template-columns: repeat(2, 1fr); padding: 16px; }
  .eg-avales { grid-template-columns: 1fr; }
  .eg-texto { padding: 20px 17px; }
  .eg-banda-txt { padding: 26px 20px; }
  .eg-home .eg-banda h2 { font-size: 23px; }
  .eg-cierre { padding: 24px 18px; flex-direction: column; align-items: flex-start; }
  .eg-cierre .eg-btn { width: 100%; }
}

@media (prefers-reduced-motion: reduce) {
  .eg-home *, .eg-home *::before, .eg-home *::after {
    transition-duration: .01ms !important; animation-duration: .01ms !important;
  }
}
@media (forced-colors: active) {
  .eg-card, .eg-prod, .eg-atajo, .eg-aval { border: 1px solid CanvasText; }
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
