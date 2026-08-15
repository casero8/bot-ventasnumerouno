<?php
/**
 * Texto de "pedido pendiente" en las fichas.
 *
 * "Disponible en pedido pendiente" es la cadena por defecto de WooCommerce y no
 * le dice nada a un comprador. Se sustituye por algo que explica lo que pasa:
 * no hay stock inmediato y hay que consultar el plazo.
 *
 * Se hace con filtros y no con CSS a proposito. Sustituir texto con content en
 * CSS deja el original en el arbol de accesibilidad, y los lectores de pantalla
 * acaban leyendo las dos versiones.
 *
 * Van dos vias porque el tema Minimog pinta su propio elemento de stock
 * (.entry-product-stock) y no siempre pasa por el filtro de WooCommerce:
 *   1. woocommerce_get_availability, por si usa la API del producto.
 *   2. gettext_woocommerce, que intercepta la traduccion en origen. Se usa la
 *      variante con dominio en el nombre del hook, no el gettext generico,
 *      porque asi solo se ejecuta con las cadenas de WooCommerce.
 */

define( 'EG_TEXTO_BACKORDER', 'Bajo pedido · consúltanos el plazo' );

add_filter( 'woocommerce_get_availability', 'eg_disponibilidad_bajo_pedido', 20, 2 );

function eg_disponibilidad_bajo_pedido( $disponibilidad, $producto ) {

	if ( ! is_array( $disponibilidad ) || ! is_object( $producto ) ) {
		return $disponibilidad;
	}

	if ( method_exists( $producto, 'get_stock_status' ) && 'onbackorder' === $producto->get_stock_status() ) {
		$disponibilidad['availability'] = EG_TEXTO_BACKORDER;
	}

	return $disponibilidad;
}

add_filter( 'gettext_woocommerce', 'eg_traduce_bajo_pedido', 20, 3 );

function eg_traduce_bajo_pedido( $traducido, $original, $dominio ) {

	if ( 'Available on backorder' === $original ) {
		return EG_TEXTO_BACKORDER;
	}


	return $traducido;
}

/**
 * Marca el stock realmente bajo para poder pintarlo distinto.
 *
 * El tema usa la clase que devuelve WooCommerce (in-stock, out-of-stock,
 * available-on-backorder), asi que anadiendo una aqui se puede estilar sin
 * tocar plantillas. Solo se marca si el producto gestiona inventario y quedan
 * 3 unidades o menos: es escasez real, tomada del stock, no un adorno.
 */
add_filter( 'woocommerce_get_availability', 'eg_marca_stock_bajo', 999, 2 );

function eg_marca_stock_bajo( $disponibilidad, $producto ) {

	if ( ! is_array( $disponibilidad ) || ! is_object( $producto ) ) {
		return $disponibilidad;
	}

	if ( ! method_exists( $producto, 'managing_stock' ) || ! $producto->managing_stock() ) {
		return $disponibilidad;
	}

	$quedan = $producto->get_stock_quantity();

	if ( is_numeric( $quedan ) && $quedan > 0 && $quedan <= 3 ) {
		$disponibilidad['class'] = trim( $disponibilidad['class'] . ' eg-stock-bajo' );

		// El tema reescribe el texto con su propia cadena mal traducida
		// ("Solo 1 elemento de la izquierda en stock"). Se corrige aqui, en
		// prioridad tardia, para pisarla despues de que la ponga.
		$disponibilidad['availability'] = sprintf(
			_n( 'Queda %s unidad', 'Quedan solo %s unidades', $quedan, 'woocommerce' ),
			$quedan
		);
	}

	return $disponibilidad;
}
