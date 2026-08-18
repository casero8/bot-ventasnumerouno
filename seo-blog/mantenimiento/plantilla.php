<?php
/**
 * Plugin Name: EG · Mantenimiento (agosto 2026)
 * Description: Ejecuta UNA sola vez, al activarlo, las correcciones pendientes: apagar la cache fantasma de LiteSpeed, activar WP Super Cache, borrar el HTML congelado y restaurar las descripciones de categoria. Deja un informe en el escritorio. Se puede desactivar y borrar despues.
 * Version:     1.0
 * Author:      EcoGadget
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Corre UNA vez, en la primera carga del escritorio despues de activar.
 *
 * No se usa register_activation_hook a proposito: uno de los pasos activa
 * otro plugin, y activar un plugin desde dentro de la activacion de otro
 * puede dejar el estado a medias. En admin_init estamos en una peticion
 * normal, con el usuario administrador cargado y sin anidamientos.
 */
add_action( 'admin_init', 'eg_mant_quiza_ejecutar' );

function eg_mant_quiza_ejecutar() {

	if ( get_option( 'eg_mant_hecho' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Se marca ANTES de empezar: si algo fallara a mitad, no se reintenta
	// solo en cada carga de pagina.
	update_option( 'eg_mant_hecho', current_time( 'mysql' ), false );

	eg_mant_ejecutar();
}

function eg_mant_log( $texto ) {
	$r = get_option( 'eg_mant_informe', array() );
	$r[] = $texto;
	update_option( 'eg_mant_informe', $r, false );
}

function eg_mant_ejecutar() {

	update_option( 'eg_mant_informe', array(), false );
	eg_mant_log( 'Ejecutado el ' . current_time( 'd/m/Y H:i' ) );

	eg_mant_paso_litespeed();
	eg_mant_paso_supercache();
	eg_mant_paso_borrar_cache();
	eg_mant_paso_categorias();

	eg_mant_log( 'Terminado.' );
}

/* ==========================================================================
   1. Apagar la cache de pagina de LiteSpeed
   Este servidor es Apache: las reglas de LiteSpeed viven dentro de un
   <IfModule LiteSpeed> que Apache ignora. El plugin cree que cachea y no
   cachea nada. Se le apaga solo la cache de pagina; su optimizacion de
   CSS y JS se queda como esta.
   ========================================================================== */

function eg_mant_paso_litespeed() {

	$antes = get_option( 'litespeed.conf.cache', null );

	if ( null === $antes ) {
		eg_mant_log( '1. LiteSpeed: no encuentro la opcion, no toco nada.' );
		return;
	}

	if ( '0' === (string) $antes ) {
		eg_mant_log( '1. LiteSpeed: la cache de pagina ya estaba apagada.' );
		return;
	}

	update_option( 'litespeed.conf.cache', 0 );
	eg_mant_log( '1. LiteSpeed: cache de pagina apagada (estaba en "' . $antes . '").' );
}

/* ==========================================================================
   2. Activar WP Super Cache
   Es el que si funciona en Apache. Medido: 2,62 s sin el, 0,056 s con el.
   ========================================================================== */

function eg_mant_paso_supercache() {

	$ruta = 'wp-super-cache/wp-cache.php';

	if ( ! function_exists( 'activate_plugin' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $ruta ) ) {
		eg_mant_log( '2. WP Super Cache: NO esta instalado. Hay que instalarlo desde Plugins.' );
		return;
	}

	if ( is_plugin_active( $ruta ) ) {
		eg_mant_log( '2. WP Super Cache: ya estaba activo.' );
	} else {
		$r = activate_plugin( $ruta );
		eg_mant_log( is_wp_error( $r )
			? '2. WP Super Cache: NO he podido activarlo (' . $r->get_error_message() . ').'
			: '2. WP Super Cache: activado.' );
	}

	// Los ajustes que le pusimos, por si la desactivacion los revirtio.
	$conf = WP_CONTENT_DIR . '/wp-cache-config.php';

	if ( ! file_exists( $conf ) || ! is_writable( $conf ) ) {
		eg_mant_log( '   wp-cache-config.php no existe o no se puede escribir.' );
		return;
	}

	$c = file_get_contents( $conf );

	if ( false !== strpos( $c, 'ajustes ecogadgetoficial' ) ) {
		eg_mant_log( '   ajustes de WooCommerce ya presentes en la configuracion.' );
		return;
	}

	$add = "\n// --- ajustes ecogadgetoficial ---\n"
		. "\$cache_enabled = true;\n"
		. "\$super_cache_enabled = true;\n"
		. "\$wp_cache_mod_rewrite = 0;\n"
		. "\$wp_cache_not_logged_in = 2;\n"
		. "\$wp_cache_no_cache_for_get = 1;\n"
		. "\$cache_max_time = 3600;\n"
		. "\$cache_rejected_uri = array( 'wp-.*\\\\.php', 'index\\\\.php', 'cart', 'checkout', 'my-account', 'carrito', 'finalizar-compra', 'mi-cuenta', 'wc-ajax', 'add-to-cart' );\n"
		. "\$wpsc_rejected_cookies = array( 'woocommerce_items_in_cart', 'woocommerce_cart_hash', 'wp_woocommerce_session_' );\n";

	/* El fichero termina con la etiqueta de cierre de PHP: hay que insertar
	   ANTES de ella, no al final del fichero. Y este comentario va en bloque
	   a proposito: una etiqueta de cierre dentro de un comentario de dos
	   barras cierra PHP de verdad y parte el fichero en dos. */
	$n = preg_replace( '/\?>\s*$/', $add . "?>\n", $c, 1, $cuenta );

	if ( 1 === $cuenta ) {
		file_put_contents( $conf, $n );
		eg_mant_log( '   ajustes de WooCommerce escritos en la configuracion.' );
	} else {
		eg_mant_log( '   NO he encontrado el cierre del fichero de configuracion, no lo toco.' );
	}
}

/* ==========================================================================
   3. Borrar el HTML congelado
   Con WP Super Cache desactivado, las reglas del .htaccess seguian
   sirviendo los HTML guardados: precios y stock de hace dias.
   ========================================================================== */

function eg_mant_paso_borrar_cache() {

	$dir = WP_CONTENT_DIR . '/cache/supercache';

	if ( ! is_dir( $dir ) ) {
		eg_mant_log( '3. Cache: no hay carpeta supercache.' );
		return;
	}

	$n = 0;
	$it = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ( $it as $f ) {
		if ( $f->isFile() && preg_match( '/\.html(\.gz)?$/', $f->getFilename() ) ) {
			@unlink( $f->getPathname() );
			$n++;
		}
	}

	eg_mant_log( '3. Cache: ' . $n . ' paginas congeladas borradas. Se regeneran solas.' );
}

/* ==========================================================================
   4. Restaurar las descripciones de categoria
   El campo term_description pasa por wp_filter_kses y se lleva por delante
   tablas, details y atributos. Hay que quitar ese filtro antes de escribir.
   Solo se escribe si lo que hay ahora es MAS CORTO que lo nuestro: asi no
   se machaca nunca contenido bueno.
   ========================================================================== */

function eg_mant_paso_categorias() {

	$textos = eg_mant_textos();

	remove_filter( 'pre_term_description', 'wp_filter_kses' );
	remove_filter( 'term_description', 'wp_kses_data' );

	foreach ( $textos as $slug => $html ) {

		$t = get_term_by( 'slug', $slug, 'product_cat' );

		if ( ! $t || is_wp_error( $t ) ) {
			eg_mant_log( '4. ' . $slug . ': no existe la categoria.' );
			continue;
		}

		$actual = (int) strlen( $t->description );
		$nuevo  = (int) strlen( $html );

		if ( $actual >= $nuevo ) {
			eg_mant_log( '4. ' . $slug . ': ya tiene ' . $actual . ' caracteres, no lo toco.' );
			continue;
		}

		wp_update_term( $t->term_id, 'product_cat', array( 'description' => $html ) );

		$c = get_term( $t->term_id, 'product_cat' );
		eg_mant_log( '4. ' . $slug . ': ' . $actual . ' -> ' . strlen( $c->description ) . ' caracteres.' );
	}
}

/* ==========================================================================
   Aviso en el escritorio con lo que ha hecho
   ========================================================================== */

add_action( 'admin_notices', 'eg_mant_aviso' );

function eg_mant_aviso() {

	$r = get_option( 'eg_mant_informe', array() );

	if ( empty( $r ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<div class="notice notice-info"><p><strong>EG · Mantenimiento</strong></p><ol style="margin-left:18px">';

	foreach ( $r as $linea ) {
		echo '<li>' . esc_html( $linea ) . '</li>';
	}

	echo '</ol><p>Cuando lo hayas leido puedes desactivar y borrar este plugin. '
		. 'No volvera a ejecutarse solo.</p></div>';
}

/* ==========================================================================
   Los textos de las categorias
   ========================================================================== */

function eg_mant_textos() {
	return array(
EG_TEXTOS_AQUI
	);
}
