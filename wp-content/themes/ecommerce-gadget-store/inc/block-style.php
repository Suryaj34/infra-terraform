<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package WordPress
 * @subpackage ecommerce-gadget-store
 * @since ecommerce-gadget-store 1.0
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 *
	 * @since ecommerce-gadget-store 1.0
	 *
	 * @return void
	 */
	function ecommerce_gadget_store_register_block_styles() {
		

		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'ecommerce-gadget-store-border',
				'label' => esc_html__( 'Borders', 'ecommerce-gadget-store' ),
			)
		);

		
	}
	add_action( 'init', 'ecommerce_gadget_store_register_block_styles' );
}