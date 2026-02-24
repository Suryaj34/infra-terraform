function wpeasycart_text_trigger_update_display( message_id ) {
	if( jQuery( '#message_trigger_type_row_' + message_id + ' > select' ).val() == 'order-status-update' ) {
		jQuery( '#message_order_status_id_row_' + message_id ).show();
	} else {
		jQuery( '#message_order_status_id_row_' + message_id ).hide();
		jQuery( '#message_order_status_id_row_' + message_id + ' > select' ).val( '' );
	}
}

function ec_admin_save_text_notification_settings_options(){
	jQuery( '#ec_option_enable_cloud_messages' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( '#ec_option_enable_cloud_messages' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var data = {
		action: 'ec_admin_ajax_update_enable_cloud_messages',
		ec_option_enable_cloud_messages: ec_admin_get_value( 'ec_option_enable_cloud_messages', 'checkbox' ),
		ec_option_cloud_messages_default_country: ec_admin_get_value( 'ec_option_cloud_messages_default_country', 'text' ),
		ec_option_cloud_messages_preferred_countries: ec_admin_get_value( 'ec_option_cloud_messages_preferred_countries', 'text' )
	};
	
	if ( jQuery( document.getElementById( 'ec_option_enable_cloud_messages' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_cloud_messages_default_country_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_cloud_messages_preferred_countries_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_cloud_messages_default_country_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_cloud_messages_preferred_countries_row' ) ).hide();
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( '#ec_option_enable_cloud_messages' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( '#ec_option_enable_cloud_messages' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( '#ec_option_enable_cloud_messages' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );

	return false;
}

function wpeasycart_add_cloud_message(){
	jQuery( document.getElementById( "ec_admin_text_notifications_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_add_cloud_message',
		trigger_type: jQuery( '#new_message_trigger_type' ).val( ),
		order_status_id: jQuery( '#new_message_order_status_id' ).val( ),
		message: jQuery( '#new_cloud_message' ).val( ),
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
		jQuery( '#cloud_message_list' ).html( response );
		if ( response.length > 0 ) {
			jQuery( '#cloud_message_list_none' ).hide( );
			jQuery( '#cloud_message_list' ).show( );
		} else {
			jQuery( '#cloud_message_list_none' ).show( );
			jQuery( '#cloud_message_list' ).hide( );
		}
		jQuery( '#new_message_trigger_type' ).val( '' );
		jQuery( '#new_message_order_status_id' ).val( '' );
		jQuery( '#new_cloud_message' ).val( '' );
		ec_admin_hide_loader( 'ec_admin_text_notifications_loader' );
	} } );

	return false;
}

function wpeasycart_update_cloud_message( message_id){
	jQuery( document.getElementById( "ec_admin_text_notifications_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_update_cloud_message',
		message_id: message_id,
		trigger_type: jQuery( '#message_trigger_type_' + message_id ).val( ),
		order_status_id: jQuery( '#message_order_status_id_' + message_id ).val( ),
		message: jQuery( '#cloud_message_' + message_id ).val( ),
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
		jQuery( '#cloud_message_list' ).html( response );
		if ( response.length > 0 ) {
			jQuery( '#cloud_message_list_none' ).hide( );
			jQuery( '#cloud_message_list' ).show( );
		} else {
			jQuery( '#cloud_message_list_none' ).show( );
			jQuery( '#cloud_message_list' ).hide( );
		}
		ec_admin_hide_loader( 'ec_admin_text_notifications_loader' );
	} } );

	return false;
}

function wpeasycart_delete_cloud_message( message_id){
	jQuery( document.getElementById( "ec_admin_text_notifications_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_delete_cloud_message',
		message_id: message_id,
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
		jQuery( '#cloud_message_list' ).html( response );
		if ( response.length > 0 ) {
			jQuery( '#cloud_message_list_none' ).hide( );
			jQuery( '#cloud_message_list' ).show( );
		} else {
			jQuery( '#cloud_message_list_none' ).show( );
			jQuery( '#cloud_message_list' ).hide( );
		}
		ec_admin_hide_loader( 'ec_admin_text_notifications_loader' );
	} } );

	return false;
}