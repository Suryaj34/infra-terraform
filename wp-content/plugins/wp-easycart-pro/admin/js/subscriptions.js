function ec_admin_update_subscription( ){
	jQuery( document.getElementById( 'ec_admin_subscription_details' ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_subscription_details',
		subscription_id: ec_admin_get_value( 'subscription_id', 'hidden' ),
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		update_product_id: ec_admin_get_value( 'update_product_id', 'select' ),
		stripe_subscription_id: ec_admin_get_value( 'stripe_subscription_id', 'hidden' ),
		stripe_customer_id: ec_admin_get_value( 'stripe_customer_id', 'hidden' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){
		data = JSON.parse( data );
		ec_admin_hide_loader( 'ec_admin_subscription_details' );
		jQuery( document.getElementById( 'ec_admin_subscription_update_failed' ) ).hide( );
		if( data ){
			if( data.error ){
				jQuery( document.getElementById( 'ec_admin_subscription_update_failed' ) ).show( );
				jQuery( document.getElementById( 'update_product_id' ) ).val( data.product_id );
			}else{
				jQuery( document.getElementById( 'title' ) ).val( data.title );
				jQuery( document.getElementById( 'product_id' ) ).val( data.product_id );
				jQuery( document.getElementById( 'price' ) ).val( data.price );
				jQuery( document.getElementById( 'payment_length' ) ).val( data.payment_length );
				jQuery( document.getElementById( 'payment_period' ) ).val( data.payment_period );
			}
		}
	} } );
}
function ec_admin_cancel_subscription( ){
	jQuery( document.getElementById( "ec_admin_subscription_details" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_cancel_subscription',
		stripe_subscription_id: ec_admin_get_value( 'stripe_subscription_id', 'text' ),
		stripe_customer_id: ec_admin_get_value( 'stripe_customer_id', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_subscription_details' );
		jQuery( document.getElementById( 'update_product_id' ) ).remove( );
		jQuery( document.getElementById( 'subscription_status' ) ).val( wp_easycart_pro_admin_subscriptions_language['canceled'] );
		jQuery( document.getElementById( 'ec_admin_cancel_subscription_button' ) ).remove( );
	} } );
}