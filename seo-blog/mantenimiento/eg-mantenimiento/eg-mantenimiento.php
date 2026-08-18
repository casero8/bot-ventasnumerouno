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
		'accesorios-delta-3' => <<<'HTML'
<div class="eg-cat-intro">
<p class="eg-cat-lead">Todo lo que amplía lo que puede hacer tu <a href="/product-category/serie-delta/delta-3/">DELTA 3</a>: paneles solares para cargarla donde no hay enchufe, cables de conexión y cargadores para el coche. Son accesorios oficiales de EcoFlow, así que encajan sin adaptadores raros.</p>
<div class="eg-regla">
<span class="eg-regla-icono">&#9728;</span>
<p><strong>Lo primero que suele hacer falta es un panel:</strong> la DELTA 3 Plus admite hasta <strong>1.000 W de entrada solar</strong> en dos puertos, así que se llena con sol en poco más de una hora.</p>
</div>
<p class="eg-cat-ayuda">¿Buscas ampliar capacidad en vez de cargarla? Eso va en <a href="/product-category/serie-delta/delta-3/bateria-adicional-delta-3/">baterías adicionales DELTA 3</a>. Y si no sabes qué te encaja, <a href="/contacto/">dinos qué equipo tienes</a> y te lo decimos.</p>
</div>
HTML,
		'delta-3' => <<<'HTML'
<div class="eg-cat-intro">

<div class="eg-hero">
<p class="eg-hero-lead">Se va la luz y en tu casa no pasa nada: la nevera sigue, el router sigue y el ordenador ni parpadea. Eso es una <strong>DELTA 3</strong>. De <strong>1.024 a 2.048 Wh</strong>, se enchufa y ya está: sin obras, sin instalador y sin permisos.</p>
<ul class="eg-hero-specs">
<li><span>&#9889;</span><b>1.800&#8239;–&#8239;2.400 W</b>Mueve cualquier electrodoméstico</li>
<li><span>&#128267;</span><b>4.000 ciclos</b>Celdas LFP, más de 10 años</li>
<li><span>&#128268;</span><b>SAI &lt;10 ms</b>El apagón no te apaga nada</li>
<li><span>&#128737;</span><b>5 años</b>Garantía que tramitamos aquí</li>
</ul>
</div>

<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>La regla para no perderte:</strong> los modelos <strong>Plus</strong> admiten baterías adicionales y llegan hasta 5&nbsp;kWh. Los demás se quedan con la capacidad que traen de fábrica.</p>
</div>

<h2 class="eg-h-nav">Elige tu modelo</h2>

<div class="eg-cat-nav">
  <a href="/product-category/serie-delta/delta-3/delta-3-plus/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/11/estacion-de-energia-portatil-serie-ecoflow-delta-3-59367987315035_1500x-1.webp" alt="EcoFlow DELTA 3 Plus" loading="lazy" width="150" height="150"></span>
    <b>DELTA 3 Plus</b>
    <span class="eg-nav-dato">1.024 Wh · ampliable a 5 kWh</span>[eg_desde cat="delta-3-plus"]
    <span class="eg-nav-mas">Ver modelos</span>
  </a>
  <a href="/product-category/serie-delta/delta-3/delta-3-max/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2026/02/estacion-de-energia-portatil-serie-ecoflow-delta-3-max-2048wh-1214368090_1066x.webp" alt="EcoFlow DELTA 3 Max" loading="lazy" width="150" height="150"></span>
    <b>DELTA 3 Max</b>
    <span class="eg-nav-dato">2.048 Wh · 2.400 W</span>[eg_desde cat="delta-3-max"]
    <span class="eg-nav-mas">Ver modelos</span>
  </a>
  <a href="/product-category/serie-delta/delta-3/delta-3-max-plus/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2026/02/estacion-de-energia-portatil-serie-ecoflow-delta-3-max-2048wh-1214368088_1066x.webp" alt="EcoFlow DELTA 3 Max Plus" loading="lazy" width="150" height="150"></span>
    <b>DELTA 3 Max Plus</b>
    <span class="eg-nav-dato">2.048 Wh · ampliable</span>[eg_desde cat="delta-3-max-plus"]
    <span class="eg-nav-mas">Ver modelos</span>
  </a>
  <a href="/product-category/serie-delta/delta-3/bateria-adicional-delta-3/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/03/bateria-adicional-inteligente-se-2.webp" alt="Batería adicional EcoFlow DELTA 3" loading="lazy" width="150" height="150"></span>
    <b>Baterías adicionales</b>
    <span class="eg-nav-dato">Para subir hasta 5 kWh</span>[eg_desde cat="bateria-adicional-delta-3"]
    <span class="eg-nav-mas">Ver baterías</span>
  </a>
  <a href="/product-category/serie-delta/delta-3/accesorios-delta-3/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/12/panel-solar-portatil-ecoflow-de-400-w-1186052668_1066x.webp" alt="Accesorios EcoFlow DELTA 3" loading="lazy" width="150" height="150"></span>
    <b>Accesorios</b>
    <span class="eg-nav-dato">Paneles, cables y cargadores</span>[eg_desde cat="accesorios-delta-3"]
    <span class="eg-nav-mas">Ver accesorios</span>
  </a>
</div>

<p class="eg-cat-ayuda">¿Dudas entre dos modelos? Tienes la <a href="#eg-comparativa">comparativa de toda la gama</a> debajo de los productos. Y si prefieres que te lo digamos nosotros, <a href="/contacto/">cuéntanos qué quieres enchufar</a> y cuántas horas.</p>

</div>

<!--eg-corte-->

<div class="eg-cat-seo">

<h2 id="eg-comparativa">Comparativa de la serie DELTA 3</h2>

<p>Es la duda más repetida de la gama. La <strong>potencia</strong> marca qué puedes enchufar; la <strong>capacidad</strong>, cuántas horas aguanta.</p>

<div class="eg-tabla-scroll">
<table class="eg-cat-tabla"><tbody>
<tr><th>Modelo</th><th>Capacidad</th><th>¿Se amplía?</th><th>Potencia</th><th>Desde</th></tr>
<tr class="eg-fila-ok"><td><a href="/producto/ecoflow-delta-3-classic-1024wh/"><strong>DELTA 3 Classic</strong></a> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>1.024 Wh</td><td><span class="eg-no">—</span></td><td>1.800 W</td><td><b class="eg-precio">599 €</b></td></tr>
<tr class="eg-fila-ok eg-fila-destacada"><td><a href="/producto/estacion-energia-portatil-ecoflow-delta-3-plus/"><strong>DELTA 3 Plus</strong></a> <span class="eg-tag eg-tag-azul">La que más se lleva</span></td><td>1.024 Wh</td><td><span class="eg-si">&#10003;</span> hasta 5 kWh</td><td>1.800 W</td><td><b class="eg-precio">849 €</b></td></tr>
<tr><td><strong>DELTA 3 1500</strong></td><td>1.536 Wh</td><td><span class="eg-si">&#10003;</span></td><td>1.800 W</td><td><a href="/contacto/" class="eg-consulta">Consúltanos</a></td></tr>
<tr><td><strong>DELTA 3 Max</strong></td><td>2.048 Wh</td><td><span class="eg-no">—</span></td><td>2.400 W</td><td><a href="/contacto/" class="eg-consulta">Consúltanos</a></td></tr>
<tr><td><strong>DELTA 3 Max Plus</strong></td><td>2.048 Wh</td><td><span class="eg-si">&#10003;</span> hasta 5 kWh</td><td>2.400 W</td><td><a href="/contacto/" class="eg-consulta">Consúltanos</a></td></tr>
</tbody></table>
</div>

<p class="eg-cat-nota">Los modelos marcados como «consúltanos» entran y salen de stock según la reposición de fábrica. Escríbenos y te decimos plazo real antes de que te decidas.</p>

<h2>Cómo elegir sin equivocarte</h2>

<div class="eg-pasos">
<div class="eg-paso">
<span class="eg-paso-num">1</span>
<h3>¿Qué vas a enchufar?</h3>
<p>Esto decide la <strong>potencia</strong>, no la capacidad. Con <strong>1.800 W</strong> mueves nevera, router, ordenador, microondas, cafetera e incluso una placa de inducción portátil: prácticamente todo lo de una casa. Los <strong>2.400 W</strong> de las Max tienen sentido si quieres varios electrodomésticos grandes a la vez o herramienta de obra.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">2</span>
<h3>¿Cuántas horas aguantar?</h3>
<p>Aquí entra la <strong>capacidad</strong>. Una nevera doméstica consume de media unos 50 W reales, porque arranca y para: con 1.024 Wh tienes unas <strong>17 horas de nevera</strong>, y con 2.048 Wh el doble. Un puesto de teletrabajo completo ronda los 150 W, o sea unas seis horas.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">3</span>
<h3>¿Vas a ampliar algún día?</h3>
<p>La que más gente pasa por alto y la que más dinero cuesta después. Si crees que dentro de un año querrás más autonomía, empieza por un modelo <strong>Plus</strong>: le añades una <a href="/product-category/serie-delta/delta-3/bateria-adicional-delta-3/">batería adicional</a> y subes hasta 5 kWh sin vender nada ni volver a empezar. Y si tienes claro que no vas a ampliar, la Classic te da exactamente lo mismo por 250 € menos.</p>
</div>
</div>

<h2>Preguntas frecuentes de la serie DELTA 3</h2>

<div itemscope itemtype="https://schema.org/FAQPage">

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué diferencia hay entre DELTA 3, Classic, Plus, 1500, Max y Max Plus?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Los nombres van de dos cosas. El número grande es la capacidad: <strong>Classic y Plus son 1.024 Wh</strong>, <strong>la 1500 son 1.536 Wh</strong> y <strong>las Max, 2.048 Wh</strong>. Y la palabra <strong>Plus</strong> significa que admite baterías adicionales para llegar hasta 5 kWh. Así que «Max Plus» es la de 2.048 Wh que además se puede ampliar. La potencia es 1.800 W en toda la gama salvo en las Max, que suben a 2.400 W.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué batería adicional le puedo poner?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Los modelos Plus admiten baterías adicionales de las series <strong>DELTA 3, DELTA 2, DELTA 2 Max y DELTA Pro 3</strong>. Con una batería DELTA 3 se pasa de 1.024 a 2.048 Wh; con una DELTA Pro 3 se llega a los 5 kWh. Se conecta con su cable, la estación la reconoce sola y a partir de ahí se gestionan como una sola batería desde la aplicación. Las tienes en <a href="/product-category/serie-delta/delta-3/bateria-adicional-delta-3/">baterías adicionales DELTA 3</a>.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué panel solar le va bien a una DELTA 3?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Toda la gama acepta bastante panel. La DELTA 3 Plus admite hasta <strong>1.000 W repartidos en dos puertos de 500 W</strong>, así que se llena con sol en poco más de una hora. El <a href="/producto/panel-solar-portatil-ecoflow-de-400w/">panel portátil de 400 W</a> es el que mejor equilibra potencia y transporte, y el <a href="/producto/panel-solar-portatil-bifacial-ecoflow-de-220w/">bifacial de 220 W</a> el más cómodo de montar y recoger a diario. Puedes conectar dos a la vez y sus potencias suman.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Sirven de SAI para la nevera o el ordenador en un apagón?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, y es uno de los motivos por los que más se compran. Conmutan en <strong>menos de 10 milisegundos</strong>, que es nivel profesional: dejas la estación enchufada a la red y los aparatos enchufados a ella, y cuando se va la luz no llegan a apagarse. La nevera ni se entera del corte, y el ordenador tampoco.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Dónde descargo el manual en español?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>En nuestra <a href="/man/">página de manuales de EcoFlow en PDF</a> están los de toda la gama, incluida la serie DELTA 3, en español y descargables. Si no encuentras el de tu modelo, escríbenos y te lo pasamos.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Cuánto duran y qué garantía tienen?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Las celdas son <strong>LFP con 4.000 ciclos</strong> hasta bajar al 80 % de su capacidad: con un ciclo completo al día son más de diez años, y en respaldo doméstico mucho más. La garantía del fabricante es de <strong>5 años</strong> y, si compras aquí, te la tramitamos nosotros: somos distribuidor oficial con tienda física y servicio técnico propio.</p>
</div></div>
</details>

</div>

<h2>Guías para decidir</h2>

<div class="eg-cat-guias">
  <a href="/comparacion-entre-las-baterias-ecoflow-river-y-delta/"><span>&#9878;</span><b>RIVER o DELTA</b><em>Cuál te conviene, con números</em></a>
  <a href="/ecoflow-para-starlink-y-ordenador-que-estacion-de-energia-elegir/"><span>&#128225;</span><b>Para Starlink y ordenador</b><em>Qué estación aguanta una jornada</em></a>
  <a href="/man/"><span>&#128214;</span><b>Manuales en PDF</b><em>Toda la gama, en español</em></a>
  <a href="/tramitar-garantia/"><span>&#128736;</span><b>Servicio técnico</b><em>Reparamos aquí, sin intermediarios</em></a>
</div>

<div class="eg-cierre">
<p><b>Antes de gastarte 600 u 800 euros, hablemos.</b> Cuéntanos qué quieres enchufar y cuántas horas, y te decimos cuál te encaja de verdad: muchas veces es la Classic y te ahorras 250 €, y otras compensa la Plus para no tener que vender nada dentro de un año.</p>
<a class="eg-cierre-btn" href="/contacto/">Que me asesoren gratis</a>
</div>

<div class="eg-confianza">
<div><b>Distribuidor oficial</b><span>EcoFlow España, con tienda física</span></div>
<div><b>Servicio técnico propio</b><span>La garantía la tramitamos nosotros</span></div>
<div><b>Envío a toda España</b><span>Y 14 días para devolverlo</span></div>
<div><b>Te asesoramos antes</b><span>Cuéntanos qué quieres enchufar</span></div>
</div>

</div>
HTML,
		'paneles-solares' => <<<'HTML'
<div class="eg-cat-intro">

<div class="eg-hero">
<p class="eg-hero-lead">Un panel convierte tu estación en algo que <strong>no depende de ningún enchufe</strong>. Lo despliegas, lo apuntas al sol y deja de haber cuenta atrás: ni en el camping, ni en la furgoneta, ni en un apagón que dura más de lo previsto.</p>
<ul class="eg-hero-specs">
<li><span>&#128260;</span><b>Se pliega solo</b>Con soporte propio, se monta en un minuto</li>
<li><span>&#9728;</span><b>De 45 a 400 W</b>Desde cargar el móvil a llenar una DELTA</li>
<li><span>&#127786;</span><b>Aguantan la intemperie</b>Protegidos contra polvo y agua</li>
<li><span>&#128295;</span><b>Enchufa y listo</b>Conector directo, sin configurar nada</li>
</ul>
</div>

<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Lo único que hay que saber antes de comprar:</strong> cada estación admite un máximo de entrada solar. Poner un panel más potente que ese tope <strong>no carga más rápido</strong>: sobra dinero. Abajo tienes la tabla de qué panel le va a cada equipo.</p>
</div>

<h2 class="eg-h-nav">Elige por tipo</h2>

<div class="eg-cat-nav">
  <a href="/product-category/paneles-solares/panel-solar-portatil/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/12/panel-solar-portatil-ecoflow-de-400-w-1186052668_1066x.webp" alt="Panel solar portátil EcoFlow" loading="lazy" width="150" height="150"></span>
    <b>Portátiles</b>
    <span class="eg-nav-dato">Se pliegan y se llevan. Para camping y furgoneta</span>[eg_desde cat="panel-solar-portatil"]
    <span class="eg-nav-mas">Ver portátiles</span>
  </a>
  <a href="/product-category/paneles-solares/panel-solar-rigido/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/01/panel-solar-rigido-ecoflow-de-17.webp" alt="Panel solar rígido EcoFlow" loading="lazy" width="150" height="150"></span>
    <b>Rígidos</b>
    <span class="eg-nav-dato">Para dejar fijos en el techo o la caseta</span>[eg_desde cat="panel-solar-rigido"]
    <span class="eg-nav-mas">Ver rígidos</span>
  </a>
  <a href="/producto/seguidor-solar-de-un-eje-ecoflow/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2026/02/wn-single-axis-solar-tracker-x2-50993890885979_1066x.webp" alt="Seguidor solar EcoFlow" loading="lazy" width="150" height="150"></span>
    <b>Seguidor solar</b>
    <span class="eg-nav-dato">Sigue al sol solo y saca hasta un 30 % más</span>
    <span class="eg-nav-mas">Ver seguidor</span>
  </a>
</div>

<p class="eg-cat-ayuda">¿No sabes cuál le va al tuyo? Baja a la <a href="#eg-comparativa">tabla por estación</a>, o <a href="/contacto/">dinos qué equipo tienes</a> y te lo decimos en un minuto.</p>

</div>

<!--eg-corte-->

<div class="eg-cat-seo">

<h2 id="eg-comparativa">Qué panel le va a tu estación</h2>

<p>Esta es la duda que trae aquí a casi todo el mundo, y tiene respuesta exacta: <strong>cada estación admite un máximo de vatios de entrada solar</strong>. Por encima de ese tope, el panel no carga más rápido.</p>

<div class="eg-tabla-scroll">
<table class="eg-cat-tabla"><tbody>
<tr><th>Si tienes…</th><th>Admite hasta</th><th>El panel que la aprovecha</th></tr>
<tr><td><a href="/product-category/serie-river/river-3/"><strong>RIVER 3</strong> y toda su gama</a></td><td>220 W</td><td><a href="/producto/panel-solar-portatil-bifacial-ecoflow-de-220w/">Bifacial de 220 W</a></td></tr>
<tr><td><strong>RIVER 2 / Max / Pro</strong></td><td>110 – 220 W</td><td><a href="/producto/panel-solar-portatil-ecoflow-de-160-w/">Portátil de 160 W</a></td></tr>
<tr class="eg-fila-ok eg-fila-destacada"><td><a href="/product-category/serie-delta/delta-3/"><strong>DELTA 3 y DELTA 3 Plus</strong></a></td><td><strong>1.000 W</strong> (2 puertos)</td><td><a href="/producto/panel-solar-portatil-ecoflow-de-400w/">Portátil de 400 W</a>, o dos a la vez</td></tr>
<tr><td><strong>DELTA 2 / DELTA 2 Max</strong></td><td>500 – 1.000 W</td><td><a href="/producto/panel-solar-portatil-ecoflow-de-400w/">Portátil de 400 W</a></td></tr>
<tr><td><strong>DELTA Pro</strong></td><td>1.600 W</td><td>Varios de 400 W en paralelo</td></tr>
</tbody></table>
</div>

<p class="eg-cat-nota">¿Tu modelo no está en la tabla o tienes dudas con el conector? Escríbenos con el nombre exacto del equipo y te confirmamos qué panel encaja antes de que compres nada.</p>

<h2>Portátil, rígido o seguidor</h2>

<div class="eg-pasos">
<div class="eg-paso">
<span class="eg-paso-num">1</span>
<h3>Portátil, si te mueves</h3>
<p>Se pliega como una carpeta, lleva su propio soporte y se monta en un minuto. Es el que quieres si el panel va a viajar contigo: camping, playa, furgoneta o una obra. Los <strong>bifaciales</strong> además recogen la luz que rebota del suelo, así que rinden más en arena, nieve o cemento claro.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">2</span>
<h3>Rígido, si lo dejas puesto</h3>
<p>Para atornillar al techo de la furgoneta, a una caseta o a un cobertizo. Lo montas una vez y tu estación se mantiene llena sola, sin que tengas que acordarte. Menos cómodo de transportar, pero mucho más resistente al día a día a la intemperie.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">3</span>
<h3>Seguidor, si quieres exprimirlo</h3>
<p>Gira solo siguiendo al sol durante el día, en lugar de quedarse fijo en un ángulo. Saca bastante más de los mismos vatios de panel. Tiene sentido si la instalación es fija y quieres el máximo rendimiento sin comprar más placas.</p>
</div>
</div>

<h2>Preguntas frecuentes de los paneles solares</h2>

<div itemscope itemtype="https://schema.org/FAQPage">

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Puedo poner un panel más potente del que admite mi estación?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Conectarlo no la rompe —la estación limita la entrada—, pero <strong>no cargará más rápido</strong>: estarías pagando vatios que no se usan. Lo sensato es igualar el panel al tope de entrada de tu equipo. Si quieres margen para el futuro, la excepción es cuando piensas cambiar de estación pronto: entonces sí compensa comprar el panel pensando en la siguiente.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Cuánto tarda en cargar con sol?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Una regla que funciona: <strong>horas = capacidad ÷ (vatios del panel × 0,7)</strong>. Ese 0,7 es lo que se pierde por ángulo, temperatura y nubes. Con una RIVER 3 Max Plus de 858 Wh y un panel de 220 W salen algo más de hora y media de buen sol; con una DELTA 3 Plus de 1.024 Wh y 400 W, unas tres horas y media.</p>
<p class="eg-cat-nota">Es una estimación nuestra con la fórmula a la vista, no un dato de laboratorio: el resultado real depende del sol que haga y de cómo orientes el panel.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Puedo conectar dos paneles a la vez?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, si tu estación tiene dos entradas solares. La <strong>DELTA 3 Plus</strong>, por ejemplo, admite dos puertos de 500 W y sus potencias suman hasta los 1.000 W. En los equipos de una sola entrada se conectan en serie o en paralelo con un cable adaptador, respetando siempre el voltaje máximo. Si nos dices tu modelo te confirmamos qué combinación admite.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Aguantan la lluvia y el sol todo el año?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí. Los paneles de EcoFlow van protegidos contra polvo y agua, así que un chaparrón o el rocío de la noche no son problema. Los <strong>rígidos</strong> están pensados justo para eso: quedarse puestos a la intemperie durante años. Con los portátiles, lo único recomendable es recogerlos si viene viento fuerte, más por el soporte que por el panel.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Sirven con un balcón o para bajar la factura de casa?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Estos paneles están pensados para cargar una estación portátil. Si lo que quieres es <strong>producir para tu casa y aprovechar el excedente</strong>, lo tuyo es un sistema con microinversor y batería: te lo contamos en <a href="/kits-para-balcones/">kits solares para balcón</a> y en la gama <a href="/product-category/stream-series/">EcoFlow STREAM</a>. Cuéntanos tu caso y te decimos cuál de los dos caminos te conviene.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Me gestionáis la garantía?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Si compras aquí, sí, de principio a fin. Somos <strong>distribuidor oficial de EcoFlow en España</strong>, con tienda física y servicio técnico propio. Si lo compraste en otro sitio, la garantía legal la responde quien te lo vendió, pero te lo revisamos igualmente en nuestro taller con presupuesto previo.</p>
</div></div>
</details>

</div>

<h2>Guías para decidir</h2>

<div class="eg-cat-guias">
  <a href="/product-category/serie-delta/"><span>&#127968;</span><b>Estaciones DELTA</b><em>Para casa, de 1 a 4 kWh</em></a>
  <a href="/product-category/serie-river/"><span>&#127958;</span><b>Baterías RIVER</b><em>Portátiles, de 245 a 858 Wh</em></a>
  <a href="/kits-para-balcones/"><span>&#127959;</span><b>Kit solar de balcón</b><em>Si lo que quieres es bajar la factura</em></a>
  <a href="/man/"><span>&#128214;</span><b>Manuales en PDF</b><em>Toda la gama, en español</em></a>
</div>

<div class="eg-cierre">
<p><b>Dinos qué estación tienes y te decimos qué panel le va.</b> Es la consulta que más nos llega, y contestarla bien te ahorra comprar de más: muchas veces con un panel de 220 € tienes de sobra y el de 649 € no te va a cargar más rápido.</p>
<a class="eg-cierre-btn" href="/contacto/">Que me asesoren gratis</a>
</div>

<div class="eg-confianza">
<div><b>Distribuidor oficial</b><span>EcoFlow España, con tienda física</span></div>
<div><b>Servicio técnico propio</b><span>La garantía la tramitamos nosotros</span></div>
<div><b>Envío a toda España</b><span>Y 14 días para devolverlo</span></div>
<div><b>Te asesoramos antes</b><span>Dinos qué equipo tienes</span></div>
</div>

</div>
HTML,
		'serie-delta' => <<<'HTML'
<div class="eg-cat-intro">

<div class="eg-hero">
<p class="eg-hero-lead">La serie <strong>DELTA</strong> es la gama de EcoFlow pensada para <strong>la casa</strong>: de 1 a 4 kWh, potencia para mover electrodomésticos de verdad y respaldo instantáneo cuando se va la luz. Si lo que quieres es llevártela a cuestas, esa es la <a href="/product-category/serie-river/">serie RIVER</a>.</p>
<ul class="eg-hero-specs">
<li><span>&#127968;</span><b>De 1 a 4 kWh</b>Desde una noche hasta varios días</li>
<li><span>&#9889;</span><b>1.800&#8239;–&#8239;4.000 W</b>Nevera, lavadora, inducción, herramienta</li>
<li><span>&#128268;</span><b>SAI instantáneo</b>El apagón no te apaga nada</li>
<li><span>&#128267;</span><b>Ampliables</b>Se les añade batería sin cambiar de equipo</li>
</ul>
</div>

<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Cómo leer los nombres:</strong> el número es la generación (2 es la anterior, 3 la actual), <strong>Max</strong> significa más capacidad, <strong>Pro</strong> más potencia y <strong>Plus</strong> que admite baterías adicionales.</p>
</div>

<h2 class="eg-h-nav">Elige tu gama</h2>

<div class="eg-cat-nav">
  <a href="/product-category/serie-delta/delta-3/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2026/04/estacion-de-energia-portatil-ecoflow-delta-3-classic-1024wh-1218692268_1066x.webp" alt="EcoFlow DELTA 3" loading="lazy" width="150" height="150"></span>
    <b>DELTA 3</b>
    <span class="eg-nav-dato">1 a 2 kWh · la generación actual</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-delta/delta-2/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/11/estacion-de-energia-portatil-ecoflow-delta-2-56251822997851_530x530.webp" alt="EcoFlow DELTA 2" loading="lazy" width="150" height="150"></span>
    <b>DELTA 2</b>
    <span class="eg-nav-dato">1.024 Wh · y sus accesorios</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-delta/delta-2-max/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/11/estacion-de-energia-portatil-ecoflow-delta-2-max-56251857273179_530x530.webp" alt="EcoFlow DELTA 2 Max" loading="lazy" width="150" height="150"></span>
    <b>DELTA 2 Max</b>
    <span class="eg-nav-dato">2.048 Wh · y sus accesorios</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-delta/delta-pro/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/10/estacion-de-energia-portatil-ecoflow-delta-pro-38912877986014_1066x-1.webp" alt="EcoFlow DELTA Pro" loading="lazy" width="150" height="150"></span>
    <b>DELTA Pro</b>
    <span class="eg-nav-dato">3,6 kWh · casa entera</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/producto/ecoflow-delta-max-ultra/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2026/07/WhatsApp-Image-2026-07-27-at-23.15.49.jpeg" alt="EcoFlow DELTA Max Ultra" loading="lazy" width="150" height="150"></span>
    <b>DELTA Max Ultra</b>
    <span class="eg-nav-dato">Lo más capaz de la serie</span>
    <span class="eg-nav-mas">Ver producto</span>
  </a>
</div>

<p class="eg-cat-ayuda">¿No sabes por dónde empezar? Baja a la <a href="#eg-comparativa">tabla de la serie</a>, o <a href="/contacto/">cuéntanos qué quieres enchufar</a> y cuántas horas y te decimos cuál te encaja.</p>

</div>

<!--eg-corte-->

<div class="eg-cat-seo">

<h2 id="eg-comparativa">Qué gama DELTA te conviene</h2>

<p>Toda la serie comparte lo esencial: celdas <strong>LFP</strong> de larga vida, enchufes schuko y <strong>SAI</strong> para que un corte de luz no apague nada. Lo que cambia es cuánto aguanta y cuánta potencia da.</p>

<div class="eg-tabla-scroll">
<table class="eg-cat-tabla"><tbody>
<tr><th>Gama</th><th>Capacidad</th><th>Potencia</th><th>Para qué es</th></tr>
<tr class="eg-fila-ok eg-fila-destacada"><td><a href="/product-category/serie-delta/delta-3/"><strong>DELTA 3</strong></a> <span class="eg-tag eg-tag-azul">La actual</span></td><td>1.024 – 2.048 Wh</td><td>1.800 – 2.400 W</td><td>Respaldo de casa, furgoneta y teletrabajo</td></tr>
<tr><td><a href="/product-category/serie-delta/delta-2/"><strong>DELTA 2</strong></a></td><td>1.024 Wh</td><td>1.800 W</td><td>La generación anterior, con todos sus accesorios</td></tr>
<tr><td><a href="/product-category/serie-delta/delta-2-max/"><strong>DELTA 2 Max</strong></a></td><td>2.048 Wh</td><td>2.400 W</td><td>El doble de autonomía que la DELTA 2</td></tr>
<tr><td><a href="/product-category/serie-delta/delta-pro/"><strong>DELTA Pro</strong></a></td><td>3.600 Wh</td><td>3.600 W</td><td>Casa entera, obra y instalaciones fijas</td></tr>
<tr class="eg-fila-ok"><td><a href="/producto/ecoflow-delta-max-ultra/"><strong>DELTA Max Ultra</strong></a> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>Lo más alto de la serie</td><td>Hasta 3.600 W</td><td>Autonomía doméstica de varios días</td></tr>
</tbody></table>
</div>

<p class="eg-cat-nota">Las gamas entran y salen de stock según la reposición de fábrica. Si no ves disponible el modelo que buscas, escríbenos y te decimos plazo real antes de que te decidas.</p>

<h2>Tres preguntas para acertar</h2>

<div class="eg-pasos">
<div class="eg-paso">
<span class="eg-paso-num">1</span>
<h3>¿Qué vas a enchufar?</h3>
<p>Decide la <strong>potencia</strong>. Con 1.800 W mueves nevera, router, ordenador, microondas y hasta una inducción portátil, que es casi todo lo de una casa. A partir de 3.000 W entras en terreno de varios electrodomésticos grandes a la vez y herramienta de obra.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">2</span>
<h3>¿Cuántas horas?</h3>
<p>Decide la <strong>capacidad</strong>. Una nevera doméstica consume de media unos 50 W reales: con 1 kWh son unas 17 horas, con 2 kWh el doble y con 3,6 kWh cubres un fin de semana entero de apagón con lo básico encendido.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">3</span>
<h3>¿Fija o de viaje?</h3>
<p>Si va a vivir en casa haciendo de respaldo, mira <strong>DELTA Pro</strong> o <strong>Max Ultra</strong>. Si va a moverse entre casa y furgoneta, la <a href="/product-category/serie-delta/delta-3/">DELTA 3</a> es el punto dulce. Y si de verdad la vas a llevar a cuestas, tu sitio es la <a href="/product-category/serie-river/">serie RIVER</a>.</p>
</div>
</div>

<h2>Preguntas frecuentes de la serie DELTA</h2>

<div itemscope itemtype="https://schema.org/FAQPage">

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué diferencia hay entre DELTA y RIVER?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>En una frase: <strong>RIVER es para llevártela, DELTA es para casa</strong>. La serie RIVER llega hasta 858 Wh y 600 W, suficiente para camping, furgoneta y teletrabajo, y pesa lo que puedes cargar sin esfuerzo. La serie DELTA empieza en 1.024 Wh y 1.800 W, que es lo que hace falta para mantener la nevera o mover electrodomésticos. Lo comparamos con números en la <a href="/comparacion-entre-las-baterias-ecoflow-river-y-delta/">guía de RIVER frente a DELTA</a>.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué significan Max, Pro y Plus en los nombres?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>El número es la generación: la <strong>2</strong> es la anterior y la <strong>3</strong> la actual. <strong>Max</strong> significa más capacidad —normalmente el doble—, <strong>Pro</strong> más potencia y capacidad para instalaciones serias, y <strong>Plus</strong> que el equipo admite <strong>baterías adicionales</strong> para ampliarlo más adelante. Por eso una «DELTA 3 Max Plus» es la de más capacidad de la serie 3 y además ampliable.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Puedo conectarla al cuadro eléctrico de casa?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Para el uso normal no hace falta: enchufas los aparatos directamente a la estación y funciona sin instalación ni permisos. Si lo que quieres es que la casa entera pase a la batería sola cuando se va la luz, eso sí lleva instalación fija y equipo específico. Cuéntanos tu caso y te decimos qué hace falta y si te compensa.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Mantiene la nevera en un apagón?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, y es de los motivos por los que más se compran. Una nevera doméstica arranca y para, así que su consumo medio real ronda los 50 W: con 1 kWh tienes unas 17 horas y con 2 kWh el doble. Y como la conmutación es instantánea, la nevera ni se entera del corte, no llega a pararse.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Dónde descargo los manuales en español?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>En nuestra <a href="/man/">página de manuales de EcoFlow en PDF</a> están los de toda la gama DELTA en español y descargables. Si no encuentras el de tu modelo, escríbenos y te lo pasamos.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Me gestionáis la garantía?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Si compras aquí, la tramitamos nosotros de principio a fin: somos <strong>distribuidor oficial de EcoFlow en España</strong>, con tienda física y servicio técnico propio. Si lo compraste en otro sitio, la garantía legal la responde quien te lo vendió, pero te reparamos igualmente cualquier equipo de la marca, con presupuesto previo.</p>
</div></div>
</details>

</div>

<h2>Guías para decidir</h2>

<div class="eg-cat-guias">
  <a href="/comparacion-entre-las-baterias-ecoflow-river-y-delta/"><span>&#9878;</span><b>RIVER o DELTA</b><em>Cuál te conviene, con números</em></a>
  <a href="/ecoflow-para-starlink-y-ordenador-que-estacion-de-energia-elegir/"><span>&#128225;</span><b>Para Starlink y ordenador</b><em>Qué estación aguanta una jornada</em></a>
  <a href="/paneles-solares-portatiles/"><span>&#9728;</span><b>Paneles solares</b><em>Para cargarla sin enchufe</em></a>
  <a href="/man/"><span>&#128214;</span><b>Manuales en PDF</b><em>Toda la gama, en español</em></a>
</div>

<div class="eg-confianza">
<div><b>Distribuidor oficial</b><span>EcoFlow España, con tienda física</span></div>
<div><b>Servicio técnico propio</b><span>La garantía la tramitamos nosotros</span></div>
<div><b>Envío a toda España</b><span>Y 14 días para devolverlo</span></div>
<div><b>Te asesoramos antes</b><span>Cuéntanos qué quieres enchufar</span></div>
</div>

</div>
HTML,
		'serie-rapid' => <<<'HTML'
<div class="eg-cat-intro">

<div class="eg-hero">
<p class="eg-hero-lead">Los <strong>RAPID</strong> son los bancos de energía de EcoFlow, y traen el detalle que se agradece todos los días: <strong>el cable ya viene dentro</strong>. Nada de acordarse, nada de buscarlo en la mochila. Lo sacas, lo enchufas y cargas.</p>
<ul class="eg-hero-specs">
<li><span>&#128268;</span><b>Cable integrado</b>Retráctil y siempre contigo</li>
<li><span>&#9889;</span><b>Hasta 300 W</b>Carga hasta un portátil, no solo el móvil</li>
<li><span>&#129522;</span><b>Magnéticos</b>Se pegan al móvil y cargan sin cable</li>
<li><span>&#9992;</span><b>Aptos para avión</b>Por debajo del límite de cabina</li>
</ul>
</div>

<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Cómo elegir en diez segundos:</strong> los <strong>MAG</strong> se pegan al móvil y son para el bolsillo; los <strong>Pro</strong> llevan cable integrado y potencia para cargar también el portátil. Si dudas, el Pro sirve para las dos cosas.</p>
</div>

<h2 class="eg-h-nav">Elige tu tipo</h2>

<div class="eg-cat-nav">
  <a href="/product-category/serie-rapid/rapid-pro/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/11/banco-de-energia-ecoflow-rapid-pro-27-650-mah-300-w-cable-integrado-de-140-w-1171749964_1066x.webp" alt="EcoFlow RAPID Pro" loading="lazy" width="150" height="150"></span>
    <b>RAPID Pro</b>
    <span class="eg-nav-dato">Cable integrado y hasta 300 W</span>[eg_desde cat="rapid-pro"]
    <span class="eg-nav-mas">Ver los Pro</span>
  </a>
  <a href="/product-category/serie-rapid/rapid-mag/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/01/banco-de-energia-magnetico-ecoflow-rapid-58324707377499_1066x.webp" alt="EcoFlow RAPID MAG magnético" loading="lazy" width="150" height="150"></span>
    <b>RAPID MAG</b>
    <span class="eg-nav-dato">Magnéticos, se pegan al móvil</span>[eg_desde cat="rapid-mag"]
    <span class="eg-nav-mas">Ver los MAG</span>
  </a>
  <a href="/product-category/serie-rapid/accesorios-rapid-series/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/01/cargador-gan-ecoflow-rapid-de-65-w-con-cable-de-100-w-56273865245019_1066x.webp" alt="Cargador EcoFlow RAPID" loading="lazy" width="150" height="150"></span>
    <b>Cargadores y cables</b>
    <span class="eg-nav-dato">GaN de 65 a 140 W, y cables</span>[eg_desde cat="accesorios-rapid-series"]
    <span class="eg-nav-mas">Ver accesorios</span>
  </a>
</div>

<p class="eg-cat-ayuda">¿Para el móvil, para el portátil o para los dos? Baja a la <a href="#eg-comparativa">tabla de la gama</a> o <a href="/contacto/">dinos qué quieres cargar</a> y te decimos cuál te sobra y cuál se te queda corto.</p>

</div>

<!--eg-corte-->

<div class="eg-cat-seo">

<h2 id="eg-comparativa">Cuál te conviene</h2>

<p>Los miliamperios dicen cuánta carga guarda; los <strong>vatios</strong>, a qué velocidad la entrega y qué aparatos puede alimentar. Para el móvil vale casi cualquiera; para un portátil hacen falta vatios de verdad.</p>

<div class="eg-tabla-scroll">
<table class="eg-cat-tabla"><tbody>
<tr><th>Modelo</th><th>Capacidad</th><th>Potencia</th><th>Para qué es</th></tr>
<tr class="eg-fila-ok"><td><strong>RAPID magnético 5.000</strong> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>5.000 mAh</td><td>Carga inalámbrica</td><td>Se pega al móvil y va en el bolsillo</td></tr>
<tr class="eg-fila-ok"><td><strong>RAPID Pro 20.000</strong> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>20.000 mAh</td><td>230 W</td><td>Dos móviles y una tablet, con cable dentro</td></tr>
<tr class="eg-fila-ok eg-fila-destacada"><td><strong>RAPID Pro 27.650</strong> <span class="eg-tag eg-tag-azul">El más completo</span></td><td>27.650 mAh</td><td>300 W</td><td>Carga también el portátil, y rápido</td></tr>
<tr class="eg-fila-ok"><td><strong>Cargador GaN de 65 W</strong> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>—</td><td>65 W</td><td>El cargador de pared, con su cable de 100 W</td></tr>
</tbody></table>
</div>

<p class="eg-cat-nota">La gama tiene más modelos que entran y salen de stock. Si buscas uno concreto que no ves disponible, escríbenos y te decimos plazo real.</p>

<h2>Tres cosas que marcan la diferencia</h2>

<div class="eg-pasos">
<div class="eg-paso">
<span class="eg-paso-num">1</span>
<h3>El cable ya viene puesto</h3>
<p>Es lo que más se nota en el día a día. Los modelos <strong>Pro</strong> llevan el cable integrado y retráctil: no hay que acordarse de meterlo, no se enreda y no se queda en casa. Parece un detalle hasta el primer viaje en el que no te hace falta buscar nada.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">2</span>
<h3>Vatios, no solo miliamperios</h3>
<p>El número grande de la caja dice cuánta energía guarda, pero no a qué velocidad la da. Con <strong>230 o 300 W</strong> cargas un portátil de trabajo a velocidad normal, no goteando. Ahí está la diferencia entre una batería de móvil y una que te salva una jornada.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">3</span>
<h3>Puedes volar con ellos</h3>
<p>Están por debajo del límite de las 100 Wh que piden las aerolíneas para llevar baterías en cabina, así que viajan contigo sin problema. Conviene llevarlos siempre en el equipaje de mano: en la bodega no están permitidos.</p>
</div>
</div>

<h2>Preguntas frecuentes</h2>

<div itemscope itemtype="https://schema.org/FAQPage">

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Cuál es la diferencia entre RAPID MAG y RAPID Pro?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Los <strong>MAG</strong> son magnéticos: se pegan a la parte de atrás del móvil y cargan sin cable. Son pequeños, para el bolsillo y para salir del paso. Los <strong>Pro</strong> son mayores, llevan el <strong>cable integrado</strong> y dan mucha más potencia, hasta 300 W, con lo que cargan también portátiles. Si solo quieres que el móvil llegue al final del día, un MAG basta; si viajas con portátil, ve al Pro.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Puedo cargar un portátil?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, con los modelos <strong>Pro de 230 y 300 W</strong>. Un portátil normal pide entre 45 y 100 W, así que van sobrados y además cargan a velocidad completa en vez de a goteo. Con 27.650 mAh puedes recargar del todo un portátil de trabajo y aún queda para el móvil.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Se pueden llevar en el avión?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí. Están por debajo del límite de <strong>100 Wh</strong> que permiten las aerolíneas sin autorización previa, que es donde está la frontera habitual. Eso sí: las baterías siempre van en el <strong>equipaje de mano</strong>, nunca en la bodega. Es norma de todas las compañías, no de la marca.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué significan los mAh y los vatios?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Los <strong>mAh</strong> dicen cuánta energía guarda: a más mAh, más veces cargas el móvil. Los <strong>vatios</strong> dicen a qué velocidad la entrega y qué aparatos puede alimentar. Una batería de 20.000 mAh y 20 W carga el móvil varias veces pero no mueve un portátil; la misma capacidad con 230 W sí. Por eso conviene mirar los dos números y no solo el grande de la caja.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Sirven para una estación EcoFlow?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Son cosas distintas y conviene no confundirlas. Un RAPID carga <strong>aparatos pequeños</strong>: móvil, tablet, portátil. Si lo que quieres es mantener una nevera, un router o herramienta, eso es una estación de energía: mira la <a href="/product-category/serie-river/">serie RIVER</a> si te la vas a llevar encima, o la <a href="/product-category/serie-delta/">serie DELTA</a> si es para casa.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Me gestionáis la garantía?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Si compras aquí, sí, de principio a fin. Somos <strong>distribuidor oficial de EcoFlow en España</strong>, con tienda física y servicio técnico propio. Si lo compraste en otro sitio, la garantía legal la responde quien te lo vendió, pero te lo revisamos igualmente con presupuesto previo.</p>
</div></div>
</details>

</div>

<h2>Y si necesitas más</h2>

<div class="eg-cat-guias">
  <a href="/product-category/serie-river/"><span>&#127958;</span><b>Baterías RIVER</b><em>Para nevera, luces y portátil</em></a>
  <a href="/product-category/serie-delta/"><span>&#127968;</span><b>Estaciones DELTA</b><em>Para casa y electrodomésticos</em></a>
  <a href="/product-category/paneles-solares/"><span>&#9728;</span><b>Paneles solares</b><em>Para cargar sin enchufe</em></a>
  <a href="/man/"><span>&#128214;</span><b>Manuales en PDF</b><em>Toda la gama, en español</em></a>
</div>

<div class="eg-cierre">
<p><b>Dinos qué quieres cargar y te decimos cuál te sobra.</b> Es habitual que alguien venga a por el de 130 € cuando con el de 100 € va sobrado, o al revés: que compre uno pequeño y a la semana descubra que no mueve el portátil.</p>
<a class="eg-cierre-btn" href="/contacto/">Que me asesoren gratis</a>
</div>

<div class="eg-confianza">
<div><b>Distribuidor oficial</b><span>EcoFlow España, con tienda física</span></div>
<div><b>Servicio técnico propio</b><span>La garantía la tramitamos nosotros</span></div>
<div><b>Envío a toda España</b><span>Y 14 días para devolverlo</span></div>
<div><b>Te asesoramos antes</b><span>Dinos qué quieres cargar</span></div>
</div>

</div>
HTML,
		'serie-river' => <<<'HTML'
<div class="eg-cat-intro">

<div class="eg-hero">
<p class="eg-hero-lead">La serie <strong>RIVER</strong> es la gama de EcoFlow que <strong>te llevas contigo</strong>: de 245 a 858 Wh en equipos que se cargan con una mano y caben en el maletero. Para camping, furgoneta, fotografía y teletrabajo fuera de casa. Si lo que buscas es mantener la nevera en un apagón, esa es la <a href="/product-category/serie-delta/">serie DELTA</a>.</p>
<ul class="eg-hero-specs">
<li><span>&#127958;</span><b>245 – 858 Wh</b>De un fin de semana a dos días</li>
<li><span>&#9889;</span><b>Hasta 1.200 W</b>Con X-Boost, para cafetera o hervidor</li>
<li><span>&#128337;</span><b>Llena en 1 hora</b>Desde un enchufe normal</li>
<li><span>&#128266;</span><b>Menos de 30 dB</b>Duermes al lado sin oírla</li>
</ul>
</div>

<div class="eg-regla">
<span class="eg-regla-icono">&#128161;</span>
<p><strong>Toda la gama Plus da la misma potencia:</strong> 600 W continuos y 1.200 W con X-Boost. Lo único que cambia entre modelos es <strong>cuántas horas aguanta</strong>, así que eliges por capacidad, no por lo que puedes enchufar.</p>
</div>

<h2 class="eg-h-nav">Elige tu modelo</h2>

<div class="eg-cat-nav">
  <a href="/product-category/serie-river/river-3/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2025/04/estacion-de-energia-portatil-eco-4.webp" alt="EcoFlow RIVER 3" loading="lazy" width="150" height="150"></span>
    <b>RIVER 3</b>
    <span class="eg-nav-dato">245 a 858 Wh · la generación actual</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-river/river-2/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/10/estacion-de-energia-portatil-ecoflow-river-2-38912835191006_1066x-1.webp" alt="EcoFlow RIVER 2" loading="lazy" width="150" height="150"></span>
    <b>RIVER 2</b>
    <span class="eg-nav-dato">256 Wh · y sus accesorios</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-river/river-2-max/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/10/estacion-de-energia-portatil-ecoflow-river-2-max-38912830439646_1066x-1.webp" alt="EcoFlow RIVER 2 Max" loading="lazy" width="150" height="150"></span>
    <b>RIVER 2 Max</b>
    <span class="eg-nav-dato">512 Wh · y sus accesorios</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
  <a href="/product-category/serie-river/river-2-pro/">
    <span class="eg-nav-img"><img src="https://ecogadgetoficial.com/wp-content/uploads/2023/10/estacion-de-energia-portatil-ecoflow-river-2-pro-38912807829726_1066x-1.webp" alt="EcoFlow RIVER 2 Pro" loading="lazy" width="150" height="150"></span>
    <b>RIVER 2 Pro</b>
    <span class="eg-nav-dato">768 Wh · y sus accesorios</span>
    <span class="eg-nav-mas">Ver la gama</span>
  </a>
</div>

<p class="eg-cat-ayuda">¿Dudas entre dos? Baja a la <a href="#eg-comparativa">comparativa de la serie</a>, o <a href="/contacto/">cuéntanos qué quieres enchufar</a> y cuántas horas y te decimos cuál te encaja.</p>

</div>

<!--eg-corte-->

<div class="eg-cat-seo">

<h2 id="eg-comparativa">Comparativa de la serie RIVER</h2>

<p>La <strong>capacidad</strong> decide cuántas horas aguanta; la <strong>potencia</strong>, qué puedes enchufar. En la gama RIVER 3 Plus la potencia es la misma en todos, así que la decisión es solo de horas.</p>

<div class="eg-tabla-scroll">
<table class="eg-cat-tabla"><tbody>
<tr><th>Modelo</th><th>Capacidad</th><th>Potencia</th><th>Para qué da</th><th>Desde</th></tr>
<tr class="eg-fila-ok"><td><a href="/producto/estacion-de-energia-portatil-ecoflow-river-3/"><strong>RIVER 3</strong></a> <span class="eg-tag eg-tag-verde">Disponible</span></td><td>245 Wh</td><td>300 W</td><td>Móviles, portátil, luces, un finde ligero</td><td><b class="eg-precio">259 €</b></td></tr>
<tr><td><strong>RIVER 3 Plus</strong></td><td>286 Wh</td><td>600 W</td><td>Lo mismo, con más potencia y ampliable</td><td>299 €</td></tr>
<tr><td><strong>RIVER 3 Max</strong></td><td>572 Wh</td><td>600 W</td><td>Nevera portátil y dos días de camping</td><td>449 €</td></tr>
<tr class="eg-fila-ok eg-fila-destacada"><td><a href="/producto/ecoflow-river-3-max-plus/"><strong>RIVER 3 Max Plus</strong></a> <span class="eg-tag eg-tag-azul">El tope de gama</span></td><td>858 Wh</td><td>600 W</td><td>Dos días largos, o una jornada de trabajo</td><td><b class="eg-precio">549 €</b></td></tr>
<tr><td><a href="/product-category/serie-river/river-2/"><strong>RIVER 2</strong></a></td><td>256 Wh</td><td>300 W</td><td>La generación anterior</td><td>229 €</td></tr>
<tr><td><a href="/product-category/serie-river/river-2-max/"><strong>RIVER 2 Max</strong></a></td><td>512 Wh</td><td>500 W</td><td>La generación anterior, más capacidad</td><td>399 €</td></tr>
<tr><td><a href="/product-category/serie-river/river-2-pro/"><strong>RIVER 2 Pro</strong></a></td><td>768 Wh</td><td>800 W</td><td>La más potente de la generación anterior</td><td>549 €</td></tr>
</tbody></table>
</div>

<p class="eg-cat-nota">Los modelos sin precio marcado entran y salen de stock según la reposición de fábrica. Escríbenos y te decimos plazo real antes de que te decidas.</p>

<h2>Cómo elegir</h2>

<div class="eg-pasos">
<div class="eg-paso">
<span class="eg-paso-num">1</span>
<h3>¿Cuántos días fuera?</h3>
<p>Es la pregunta que más pesa. Un fin de semana con móviles, luces y portátil se va a unos 150-250 Wh al día: con <strong>245-286 Wh</strong> vas justo para un día y con <strong>858 Wh</strong> cubres dos largos. Si llevas nevera portátil, súmale unos 45 W constantes y sube de modelo.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">2</span>
<h3>¿Vas a llevar panel solar?</h3>
<p>Cambia la ecuación por completo: con panel dejas de contar horas. La gama RIVER 3 admite hasta <strong>220 W de entrada solar</strong>, así que un <a href="/producto/panel-solar-portatil-bifacial-ecoflow-de-220w/">panel de 220 W</a> la llena en poco más de hora y media con buen sol. Poner un panel más potente no acelera nada: el límite lo marca la estación.</p>
</div>
<div class="eg-paso">
<span class="eg-paso-num">3</span>
<h3>¿La vas a mover de verdad?</h3>
<p>Si la respuesta es sí, RIVER es tu sitio: son equipos que se llevan con una mano. Si en realidad va a quedarse en casa haciendo de respaldo, con la misma inversión tienes más capacidad en la <a href="/product-category/serie-delta/">serie DELTA</a>, aunque pese el triple.</p>
</div>
</div>

<h2>Preguntas frecuentes de la serie RIVER</h2>

<div itemscope itemtype="https://schema.org/FAQPage">

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué diferencia hay entre RIVER 3, RIVER 3 Plus, Max y Max Plus?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Va casi todo de capacidad: la <strong>RIVER 3</strong> son 245 Wh, la <strong>RIVER 3 Plus</strong> 286 Wh, la <strong>RIVER 3 Max</strong> 572 Wh y la <strong>RIVER 3 Max Plus</strong> 858 Wh. La potencia de salida es de 600 W continuos y 1.200 W con X-Boost en toda la gama Plus, así que eliges por cuántas horas quieres, no por qué aparatos puedes enchufar. Las tienes todas en <a href="/product-category/serie-river/river-3/">la categoría RIVER 3</a>.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Qué son las baterías EB300 y EB600 que aparecen en los nombres?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Son las <strong>baterías adicionales</strong> de la serie RIVER 3, y explican los nombres de los modelos grandes. La <strong>EB300 son 286 Wh</strong> y la <strong>EB600, 572 Wh</strong>. Sumadas a una RIVER 3 Plus de 286 Wh dan los modelos que ves en la tienda:</p>
<ul>
<li><strong>RIVER 3 Max</strong> = RIVER 3 Plus + EB300 → 286 + 286 = <strong>572 Wh</strong></li>
<li><strong>RIVER 3 Max Plus</strong> = RIVER 3 Plus + EB600 → 286 + 572 = <strong>858 Wh</strong></li>
</ul>
<p>Por eso la Max Plus aparece a veces escrita como «RIVER 3 Plus + Bat EB600»: son el mismo equipo.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Dónde descargo el manual en español?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>En nuestra <a href="/man/">página de manuales de EcoFlow en PDF</a> están los de toda la serie RIVER en español y descargables: RIVER 2, RIVER 2 Max, RIVER 2 Pro y la gama RIVER 3. Si no encuentras el de tu modelo, escríbenos y te lo pasamos.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿RIVER o DELTA? ¿Cuál me conviene?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>En una frase: <strong>RIVER es para llevártela, DELTA es para casa</strong>. La RIVER llega hasta 858 Wh y 600 W, suficiente para camping, furgoneta y teletrabajo, y pesa lo que puedes cargar sin esfuerzo. La <a href="/product-category/serie-delta/">DELTA</a> empieza en 1.024 Wh y 1.800 W, que es lo que hace falta para mantener la nevera de casa o mover electrodomésticos. Lo comparamos con números en la <a href="/comparacion-entre-las-baterias-ecoflow-river-y-delta/">guía de RIVER frente a DELTA</a>.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Sirve de SAI para el ordenador?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, y con conmutación por debajo de 10 milisegundos, que es nivel profesional. Con el ordenador enchufado a la estación y la estación a la red, un corte de luz no llega a apagarlo. Es uno de los motivos por los que mucha gente que teletrabaja tiene una RIVER debajo de la mesa todo el año, aunque nunca salga de casa.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Aguanta una nevera portátil de camping?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Sí, y es el uso más habitual. Una nevera portátil ronda los <strong>45 W</strong> en marcha, aunque no funciona todo el rato: con 858 Wh cubres dos días largos de nevera más luces y la carga de dos móviles y una cámara. Con los modelos de 245-286 Wh vas bien para un día. Y si llevas panel solar, deja de haber límite.</p>
</div></div>
</details>

<details class="eg-cat-faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
<summary itemprop="name">¿Me gestionáis la garantía?</summary>
<div class="eg-cat-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text">
<p>Si compras aquí, la tramitamos nosotros de principio a fin. Somos <strong>distribuidor oficial de EcoFlow en España</strong>, con tienda física y servicio técnico propio. Si lo compraste en otro sitio, la garantía legal la responde quien te lo vendió, pero te reparamos igualmente cualquier equipo de la marca, con presupuesto previo.</p>
</div></div>
</details>

</div>

<h2>Guías para decidir</h2>

<div class="eg-cat-guias">
  <a href="/comparacion-entre-las-baterias-ecoflow-river-y-delta/"><span>&#9878;</span><b>RIVER o DELTA</b><em>Cuál te conviene, con números</em></a>
  <a href="/ecoflow-para-starlink-y-ordenador-que-estacion-de-energia-elegir/"><span>&#128225;</span><b>Para Starlink y ordenador</b><em>Qué estación aguanta una jornada</em></a>
  <a href="/paneles-solares-portatiles/"><span>&#9728;</span><b>Paneles solares</b><em>Hasta 220 W en la gama RIVER 3</em></a>
  <a href="/man/"><span>&#128214;</span><b>Manuales en PDF</b><em>Toda la serie, en español</em></a>
</div>

<div class="eg-confianza">
<div><b>Distribuidor oficial</b><span>EcoFlow España, con tienda física</span></div>
<div><b>Servicio técnico propio</b><span>La garantía la tramitamos nosotros</span></div>
<div><b>Envío a toda España</b><span>Y 14 días para devolverlo</span></div>
<div><b>Te asesoramos antes</b><span>Cuéntanos qué quieres enchufar</span></div>
</div>

</div>
HTML,

	);
}
