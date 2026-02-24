jQuery( document ).ready( function() {
	if ( jQuery( document.getElementById( 'fee_type' ) ).length ) {
			wp_easycart_admin_pro_fee_details_update_display();
		jQuery( document.getElementById( 'fee_type' ) ).on( 'change', function() {
			wp_easycart_admin_pro_fee_details_update_display();
		} );
	}
} );

function wp_easycart_admin_pro_fee_details_update_display() {
	if( '1' == jQuery( document.getElementById( 'fee_type' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_fee_rate' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_fee_price' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_min' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_fee_max' ) ).show( );

	} else if( '2' == jQuery( document.getElementById( 'fee_type' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_fee_rate' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_price' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_fee_min' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_max' ) ).hide( );

	} else {
		jQuery( document.getElementById( 'ec_admin_row_fee_rate' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_price' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_min' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_fee_max' ) ).hide( );
	}
}
