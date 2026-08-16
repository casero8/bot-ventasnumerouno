/**
 * SEO · Redirecciones de URLs muertas y noindex de etiquetas de producto.
 *
 * Contexto (agosto de 2026). Search Console daba 801.257 URLs conocidas para
 * un catalogo de 159 productos. Al exportar los ocho motivos del informe y
 * mirarlos uno a uno resulto que casi todos eran la misma cosa:
 *
 *   Rastreada sin indexar ....... 366.194 ... 99 % con filter_product_cat
 *   Pagina alternativa .......... 152.883 ... 100 %
 *   Duplicada sin canonica ....... 58.689 ... 100 %
 *   Pagina con redireccion ........ 1.861 ... 93 %
 *   No encontrada (404) ............. 277 ... 94 %
 *   Soft 404 ......................... 39 ... 100 %
 *
 * Eso se corrige en robots.txt. Aqui queda lo que sobra: los 18 errores 404
 * reales.
 *
 * Las redirecciones se enganchan a template_redirect y solo actuan cuando
 * WordPress ya ha decidido que la URL es un 404. Asi, si algun dia se publica
 * la pagina de la Serie DELTA 3 en su ruta original, la pagina gana y la
 * redireccion deja de dispararse sola, sin tener que tocar este codigo.
 */

add_action( 'template_redirect', 'eg_redirigir_urls_muertas' );

function eg_redirigir_urls_muertas() {

	if ( ! is_404() ) {
		return;
	}

	$ruta = strtok( $_SERVER['REQUEST_URI'], '?' );
	$ruta = '/' . trim( $ruta, '/' ) . '/';

	$destinos = array(

		// Paginas y categorias que cambiaron de ruta.
		'/serie-delta-ecoflow/serie-delta-3/'                    => '/product-category/serie-delta/delta-3/',
		'/generadores-solares/'                                  => '/generador-solar/',
		'/product-category/hogar/'                               => '/kits-para-el-hogar/',
		'/1381-2/'                                               => '/politicas-de-privacidad/',
		'/blog-2/'                                               => '/blog/',
		'/pedidos/'                                              => '/my-account/',

		// Paginas duplicadas que se han despublicado. La de contacto buena es
		// /contacto/, que es la que enlaza el menu; /mi-cuenta/ estaba vacia y
		// la de WooCommerce configurada de verdad es /my-account/.
		'/contacto-2/'                                           => '/contacto/',
		'/mi-cuenta/'                                            => '/my-account/',

		// Categorias de la serie DELTA sin el prefijo product-category.
		'/serie-delta/estacion-energia-delta/'                   => '/product-category/estacion-energia-delta/',
		'/serie-delta/delta-pro/delta-pro-ultra/'                => '/product-category/delta-pro-ultra/',
		'/serie-delta/delta-2-max/baterias-adicionales-delta-2-max/' => '/product-category/baterias-adicionales-delta-2-max/',
		'/serie-delta/delta-2/accesorios-delta-2/'               => '/product-category/accesorios-delta-2/',

		// Fichas cuyo slug se guardo sin acentos y luego cambio.
		'/producto/banco-de-energia-magnetico-ecoflow-rapid-10-000-mah/' => '/producto/banco-de-energ-a-magn-tico-ecoflow-rapid-10-000-mah/',
		'/producto/banco-de-energia-magnetico-ecoflow-rapid-5-000mah/'   => '/producto/banco-de-energ-a-magn-tico-ecoflow-rapid-5-000mah/',
		'/producto/bateria-adicional-inteligente-serie-ecoflow-delta-3/' => '/producto/bater-a-adicional-inteligente-serie-ecoflow-delta-3/',
		'/producto/rapid-pro-20k-power-bank/'                    => '/producto/banco-de-energ-a-ecoflow-rapid-pro-20-000-mah-230-w-cable-integrado-de-100-w/',

		// Ficha que ya no existe: se manda a la categoria que la sustituye.
		'/producto/bolsa-de-transporte-para-ecoflow-delta-max/'   => '/product-category/accesorios/',
	);

	// Las dos entradas de la plantilla de demostracion (moda y maquillaje) no
	// se redirigen a proposito: no existen y no tienen equivalente. Un 404 es
	// la respuesta correcta y Google las acaba olvidando.

	if ( isset( $destinos[ $ruta ] ) ) {
		wp_safe_redirect( home_url( $destinos[ $ruta ] ), 301 );
		exit;
	}
}

/**
 * Las etiquetas de producto, fuera del indice y fuera del sitemap.
 *
 * Hay 35, de las cuales 7 tienen un solo producto y 5 son restos de la
 * plantilla de demostracion (boot, cadigan, hot, sweater, women). Compiten
 * con las categorias, que si estan trabajadas, y no aportan una sola
 * impresion en dieciseis meses de datos.
 *
 * Se hace con los filtros de Yoast y no solo con su ajuste porque el ajuste
 * escribe en la tabla de indexables, que no se regenera hasta que se reindexa
 * el sitio entero. Los filtros actuan en cada peticion y no dependen de eso.
 */
add_filter( 'wpseo_robots_array', 'eg_noindex_etiquetas_producto' );

function eg_noindex_etiquetas_producto( $robots ) {

	if ( is_tax( 'product_tag' ) ) {
		$robots['index'] = 'noindex';
	}

	return $robots;
}

add_filter( 'wpseo_sitemap_exclude_taxonomy', 'eg_sacar_etiquetas_del_sitemap', 10, 2 );

function eg_sacar_etiquetas_del_sitemap( $excluir, $taxonomia ) {

	return ( 'product_tag' === $taxonomia ) ? true : $excluir;
}

/**
 * Las dos listas de deseos, fuera del indice.
 *
 * /wishlist/ y /wishlist-2/ son paginas funcionales: solo llevan el shortcode
 * del plugin y se ven vacias para quien llega desde el buscador. No se
 * despublican porque el plugin puede necesitarlas; basta con que no compitan
 * en las busquedas.
 */
add_filter( 'wpseo_robots_array', 'eg_noindex_listas_de_deseos' );

function eg_noindex_listas_de_deseos( $robots ) {

	if ( is_page( array( 'wishlist', 'wishlist-2' ) ) ) {
		$robots['index'] = 'noindex';
	}

	return $robots;
}

/**
 * Cada categoria, en una sola URL.
 *
 * WooCommerce responde 200 tanto en la ruta plana como en la anidada:
 * /product-category/delta-3/ y /product-category/serie-delta/delta-3/ son
 * la misma pagina. La canonica apunta a la anidada, asi que la plana es un
 * duplicado que se rastrea para nada, justo lo que estamos quitando del
 * informe de indexacion.
 *
 * Se manda con un 301 a la que devuelve get_term_link(), que es la que
 * usan los enlaces del propio sitio. Se conservan la paginacion y los
 * parametros, y no se toca nada si la URL ya es la correcta.
 */
add_action( 'template_redirect', 'eg_categoria_una_sola_url', 1 );

function eg_categoria_una_sola_url() {

	if ( ! is_product_category() || is_paged() ) {
		return;
	}

	$termino = get_queried_object();

	if ( ! $termino || empty( $termino->term_id ) ) {
		return;
	}

	$buena = get_term_link( $termino );

	if ( is_wp_error( $buena ) ) {
		return;
	}

	$ruta_buena  = trailingslashit( wp_parse_url( $buena, PHP_URL_PATH ) );
	$ruta_actual = trailingslashit( strtok( $_SERVER['REQUEST_URI'], '?' ) );

	if ( $ruta_buena === $ruta_actual ) {
		return;
	}

	// La consulta se conserva: filtros y orden siguen funcionando.
	$consulta = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
	$destino  = $buena . ( $consulta ? '?' . $consulta : '' );

	wp_safe_redirect( $destino, 301 );
	exit;
}
