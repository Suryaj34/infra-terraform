<?php
/**
 * Customizer
 * 
 * @package WordPress
 * @subpackage ecommerce-gadget-store
 * @since ecommerce-gadget-store 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ecommerce_gadget_store_customize_register( $wp_customize ) {
	$wp_customize->add_section( new Ecommerce_Gadget_Store_Upsell_Section($wp_customize,'upsell_section',array(
		'title'            => __( 'Ecommerce Gadget Store Pro', 'ecommerce-gadget-store' ),
		'button_text'      => __( 'Upgrade Pro', 'ecommerce-gadget-store' ),
		'url'              => 'https://www.wpradiant.net/products/ecommerce-store-wordpress-theme',
		'priority'         => 0,
	)));
}
add_action( 'customize_register', 'ecommerce_gadget_store_customize_register' );

/**
 * Enqueue script for custom customize control.
 */
function ecommerce_gadget_store_custom_control_scripts() {
	wp_enqueue_script( 'ecommerce-gadget-store-custom-controls-js', get_template_directory_uri() . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0', true );
	wp_enqueue_style( 'ecommerce-gadget-store-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'ecommerce_gadget_store_custom_control_scripts' );