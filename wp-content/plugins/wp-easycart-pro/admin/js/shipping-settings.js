function ec_admin_save_shipping_text_setting_pro( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );
    if( jQuery( this_ele ).attr( 'type' ) == 'checkbox' && !jQuery( this_ele ).is( ':checked' ) ){
        val = 0;
    }
    
	var data = {
		action: 'ec_admin_ajax_save_shipping_settings_pro',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
        
        if( data ){
            var data_json = JSON.parse( data );
            jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_connected' ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_error' ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_disabled' ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_' + data_json.response ) ).show( );
        }
	} } );
	
	return false;
}

function ec_admin_toggle_ups_pro( this_ele ) {
	var ec_option_ups_use_oauth = 0;
	if ( jQuery( '#ec_option_ups_use_oauth' ).is( ':checked' ) ) {
		jQuery( '#ec_admin_ups_access_license_number_row' ).hide();
		jQuery( '#ec_admin_ups_user_id_row' ).hide();
		jQuery( '#ec_admin_ups_password_row' ).hide();
		jQuery( '#ups_oauth_buttons' ).show();
		ec_option_ups_use_oauth = 1;
	} else {
		jQuery( '#ec_admin_ups_access_license_number_row' ).show();
		jQuery( '#ec_admin_ups_user_id_row' ).show();
		jQuery( '#ec_admin_ups_password_row' ).show();
		jQuery( '#ups_oauth_buttons' ).hide();
	}
	
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var data = {
		action: 'ec_admin_ajax_save_ups_oauth_pro',
		ec_option_ups_use_oauth: ec_option_ups_use_oauth
	}

	jQuery.ajax( {
		url: wpeasycart_admin_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ){ 
			jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
			jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
			jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
			if( data ){
				var data_json = JSON.parse( data );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_connected' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_error' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_disabled' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_' + data_json.response ) ).show( );
			}
		}
	} );
}

function ec_admin_toggle_fedex_pro( this_ele ) {
	var ec_option_fedex_use_oauth = 0;
	if ( jQuery( '#ec_option_fedex_use_oauth' ).is( ':checked' ) ) {
		jQuery( '#ec_fedex_help_v2' ).show();
		jQuery( '#ec_admin_fedex_api_key' ).show();
		jQuery( '#ec_admin_fedex_api_secret_key_row' ).show();
		ec_option_fedex_use_oauth = 1;
	} else {
		jQuery( '#ec_fedex_help_v2' ).hide();
		jQuery( '#ec_admin_fedex_api_key' ).hide();
		jQuery( '#ec_admin_fedex_api_secret_key_row' ).hide();
	}
	
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var data = {
		action: 'ec_admin_ajax_save_fedex_oauth_pro',
		ec_option_fedex_use_oauth: ec_option_fedex_use_oauth
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
		if( data ){
			var data_json = JSON.parse( data );
			jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_connected' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_error' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_disabled' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_' + data_json.response ) ).show( );
		}
	} } );
}

function ec_admin_toggle_usps_pro( this_ele ) {
	var ec_option_usps_v3_enable = 0;
	var ec_option_usps_v3_custom_rates = 0;
	var ec_option_usps_v3_custom = 0;
	var ec_option_usps_v3_custom_old = 0;
	if ( jQuery( '#ec_option_usps_v3_enable' ).is( ':checked' ) ) {
		jQuery( '#ec_option_usps_v3_custom_row' ).show();
		if ( jQuery( '#ec_option_usps_v3_custom' ).is( ':checked' ) ) {
			jQuery( '#ec_option_usps_v3_custom_old_row' ).show();
			if ( jQuery( '#ec_option_usps_v3_custom_old' ).is( ':checked' ) ) {
				jQuery( '#ec_option_usps_v3_client_id_row' ).hide();
				jQuery( '#ec_option_usps_v3_client_secret_row' ).hide();
				jQuery( '#ec_admin_usps_user_name_row' ).show();
				ec_option_usps_v3_custom_old = 1;
			} else {
				jQuery( '#ec_option_usps_v3_client_id_row' ).show();
				jQuery( '#ec_option_usps_v3_client_secret_row' ).show();
				jQuery( '#ec_admin_usps_user_name_row' ).hide();
			}
			ec_option_usps_v3_custom = 1;
		} else {
			jQuery( '#ec_option_usps_v3_custom_old_row' ).hide();
			jQuery( '#ec_admin_usps_user_name_row' ).hide();
			jQuery( '#ec_option_usps_v3_client_id_row' ).hide();
			jQuery( '#ec_option_usps_v3_client_secret_row' ).hide();
		}
		jQuery( '#ec_option_usps_v3_custom_rates_row' ).show();
		if ( jQuery( '#ec_option_usps_v3_custom_old' ).is( ':checked' ) ) {
			jQuery( '#ec_option_usps_v3_custom_rates_row' ).hide();
		} else {
			jQuery( '#ec_option_usps_v3_custom_rates_row' ).show();
		}
		if ( ! jQuery( '#ec_option_usps_v3_custom_old' ).is( ':checked' ) && jQuery( '#ec_option_usps_v3_custom_rates' ).is( ':checked' ) ) {
			jQuery( '#ec_option_usps_v3_price_type_row' ).show();
			jQuery( '#ec_option_usps_v3_account_type_row' ).show();
			jQuery( '#ec_option_usps_v3_account_number_row' ).show();
			jQuery( '#ec_option_usps_v3_crid_row' ).show();
			ec_option_usps_v3_custom_rates = 1;
		} else {
			jQuery( '#ec_option_usps_v3_price_type_row' ).hide();
			jQuery( '#ec_option_usps_v3_account_type_row' ).hide();
			jQuery( '#ec_option_usps_v3_account_number_row' ).hide();
			jQuery( '#ec_option_usps_v3_crid_row' ).hide();
		}
		ec_option_usps_v3_enable = 1;
	} else {
		jQuery( '#ec_option_usps_v3_custom_row' ).hide();
		jQuery( '#ec_option_usps_v3_custom_old_row' ).hide();
		jQuery( '#ec_admin_usps_user_name_row' ).show();
		jQuery( '#ec_option_usps_v3_client_id_row' ).hide();
		jQuery( '#ec_option_usps_v3_client_secret_row' ).hide();
		jQuery( '#ec_option_usps_v3_custom_rates_row' ).hide();
		jQuery( '#ec_option_usps_v3_price_type_row' ).hide();
		jQuery( '#ec_option_usps_v3_account_type_row' ).hide();
		jQuery( '#ec_option_usps_v3_account_number_row' ).hide();
		jQuery( '#ec_option_usps_v3_crid_row' ).hide();
	}
	
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
	
	var data = {
		action: 'ec_admin_ajax_save_usps_v3_pro',
		ec_option_usps_v3_enable: ec_option_usps_v3_enable,
		ec_option_usps_v3_custom: ec_option_usps_v3_custom,
		ec_option_usps_v3_custom_old: ec_option_usps_v3_custom_old,
		ec_option_usps_v3_custom_rates: ec_option_usps_v3_custom_rates
	}
	
	jQuery.ajax( {
		url: wpeasycart_admin_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ){ 
			jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
			jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
			jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
			if( data ){
				var data_json = JSON.parse( data );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_connected' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_error' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_disabled' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_' + data_json.type + '_status_' + data_json.response ) ).show( );
			}
		}
	} );
}
