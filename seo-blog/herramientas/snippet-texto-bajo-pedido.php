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
