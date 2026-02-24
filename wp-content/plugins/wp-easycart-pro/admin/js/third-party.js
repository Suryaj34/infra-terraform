function ec_admin_save_shareasale_settings( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	if ( jQuery( document.getElementById( 'ec_option_enable_shareasale' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_admin_ec_option_shareasale_merchant_id_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_shareasale_send_details_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_shareasale_currency_conversion_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_admin_ec_option_shareasale_merchant_id_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_shareasale_send_details_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_shareasale_currency_conversion_row' ) ).hide();
	}
	
	var data = {
		action: 'ec_admin_ajax_save_shareasale_settings',
		ec_option_enable_shareasale: ec_admin_get_value( 'ec_option_enable_shareasale', 'checkbox' ),
		ec_option_shareasale_merchant_id: ec_admin_get_value( 'ec_option_shareasale_merchant_id', 'text' ),
		ec_option_shareasale_send_details: ec_admin_get_value( 'ec_option_shareasale_send_details', 'checkbox' ),
		ec_option_shareasale_currency_conversion: ec_admin_get_value( 'ec_option_shareasale_currency_conversion', 'checkbox' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_mailerlite_settings( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	if ( jQuery( document.getElementById( 'ec_option_enable_mailerlite' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_admin_save_mailerlite_settings_key_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_admin_save_mailerlite_settings_key_row' ) ).hide();
	}
	
	var data = {
		action: 'ec_admin_ajax_save_mailerlite_settings',
		ec_option_enable_mailerlite: ec_admin_get_value( 'ec_option_enable_mailerlite', 'checkbox' ),
		ec_option_mailerlite_api_key: ec_admin_get_value( 'ec_option_mailerlite_api_key', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_convertkit_settings( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	if ( jQuery( document.getElementById( 'ec_option_enable_convertkit' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_convertkit_api_key_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_convertkit_api_secret_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_convertkit_form_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_convertkit_api_key_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_convertkit_api_secret_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_convertkit_form_row' ) ).hide();
	}
	
	var data = {
		action: 'ec_admin_ajax_save_convertkit_settings',
		ec_option_enable_convertkit: ec_admin_get_value( 'ec_option_enable_convertkit', 'checkbox' ),
		ec_option_convertkit_api_key: ec_admin_get_value( 'ec_option_convertkit_api_key', 'text' ),
		ec_option_convertkit_api_secret: ec_admin_get_value( 'ec_option_convertkit_api_secret', 'text' ),
		ec_option_convertkit_form:  ec_admin_get_value( 'ec_option_convertkit_form', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_activecampaign_settings( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	if ( jQuery( document.getElementById( 'ec_option_enable_activecampaign' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_activecampaign_api_url_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_activecampaign_api_key_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_activecampaign_list_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_activecampaign_api_url_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_activecampaign_api_key_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_activecampaign_list_row' ) ).hide();
	}
	
	var data = {
		action: 'ec_admin_ajax_save_activecampaign_settings',
		ec_option_enable_activecampaign: ec_admin_get_value( 'ec_option_enable_activecampaign', 'checkbox' ),
		ec_option_activecampaign_api_url: ec_admin_get_value( 'ec_option_activecampaign_api_url', 'text' ),
		ec_option_activecampaign_api_key: ec_admin_get_value( 'ec_option_activecampaign_api_key', 'text' ),
		ec_option_activecampaign_list: ec_admin_get_value( 'ec_option_activecampaign_list', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_google_ga4_pro_text( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_google_ga4_pro',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_google_ga4_pro( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_google_ga4_pro',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val
	}

	if ( jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_direct' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_measurement_id_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_api_secret_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_server_url_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_measurement_id_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_api_secret_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_google_ga4_tag_manager_server_url_row' ) ).hide();
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_google_adwords_pro( ){
	jQuery( document.getElementById( "ec_admin_google_adwords_loader" ) ).fadeIn( 'fast' );
	
	var ec_option_google_adwords_conversion_id = jQuery( document.getElementById( 'ec_option_google_adwords_conversion_id' ) ).val( );
	var ec_option_google_adwords_tag_id = jQuery( document.getElementById( 'ec_option_google_adwords_tag_id' ) ).val( );
	var ec_option_google_adwords_language = jQuery( document.getElementById( 'ec_option_google_adwords_language' ) ).val( );
	var ec_option_google_adwords_format = jQuery( document.getElementById( 'ec_option_google_adwords_format' ) ).val( );
	var ec_option_google_adwords_color = jQuery( document.getElementById( 'ec_option_google_adwords_color' ) ).val( );
	var ec_option_google_adwords_currency = jQuery( document.getElementById( 'ec_option_google_adwords_currency' ) ).val( );
	var ec_option_google_adwords_label = jQuery( document.getElementById( 'ec_option_google_adwords_label' ) ).val( );
	
	var ec_option_google_adwords_remarketing_only = "false";
	if( jQuery( document.getElementById( 'ec_option_google_adwords_remarketing_only' ) ).is( ':checked' ) )
		ec_option_google_adwords_remarketing_only = "true";
	
	var data = {
		action: 'ec_admin_ajax_save_google_adwords_pro',
		ec_option_google_adwords_conversion_id: ec_option_google_adwords_conversion_id,
		ec_option_google_adwords_tag_id: ec_option_google_adwords_tag_id,
		ec_option_google_adwords_language: ec_option_google_adwords_language,
		ec_option_google_adwords_format: ec_option_google_adwords_format,
		ec_option_google_adwords_color: ec_option_google_adwords_color,
		ec_option_google_adwords_currency: ec_option_google_adwords_currency,
		ec_option_google_adwords_label: ec_option_google_adwords_label,
		ec_option_google_adwords_remarketing_only: ec_option_google_adwords_remarketing_only
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_google_adwords_loader' );
	} } );
	
	return false;
}

function ec_admin_save_facebook_settings_pro( ){
	jQuery( document.getElementById( "ec_admin_facebook_settings_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_facebook_settings_pro',
		ec_option_fb_pixel:  ec_admin_get_value( 'ec_option_fb_pixel', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_facebook_settings_loader' );
	} } );
	
	return false;
}

function ec_admin_save_deconetwork_settings_pro( ){
	jQuery( document.getElementById( "ec_admin_deconetwork_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_deconetwork_settings_pro',
		ec_option_deconetwork_url:  ec_admin_get_value( 'ec_option_deconetwork_url', 'text' ),
		ec_option_deconetwork_password:  ec_admin_get_value( 'ec_option_deconetwork_password', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_deconetwork_loader' );
	} } );
	
	return false;
}

function ec_admin_save_amazon_settings_pro( ){
	jQuery( document.getElementById( "ec_admin_amazon_settings_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_amazon_settings_pro',
		ec_option_amazon_key:  ec_admin_get_value( 'ec_option_amazon_key', 'text' ),
		ec_option_amazon_secret:  ec_admin_get_value( 'ec_option_amazon_secret', 'text' ),
		ec_option_amazon_bucket:  ec_admin_get_value( 'ec_option_amazon_bucket', 'text' ),
		ec_option_amazon_bucket_region:  ec_admin_get_value( 'ec_option_amazon_bucket_region', 'select' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_amazon_settings_loader' );
	} } );
	
	return false;
}
