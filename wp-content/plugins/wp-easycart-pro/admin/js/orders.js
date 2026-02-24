var ec_admin_order_details_billing_show = false;
var ec_admin_order_details_shipping_show = false;
var ec_admin_order_details_totals_show = false;
var ec_admin_order_details_save_show = false;
var ec_admin_order_details_save_bottom_show = false;
var ec_admin_order_details_date_show = false;

jQuery( document ).ready( function( ){
	jQuery( ".wp-ec-datepicker" ).datepicker( );
	jQuery( document.getElementById( 'ec_admin_order_total_save' ) ).on( 'click', ec_order_show_hide_edit_totals );
	jQuery( document.getElementById( 'ec_admin_order_details_shipping_info_save' ) ).on( 'click', ec_order_show_hide_edit_shipping );
	jQuery( document.getElementById( 'ec_admin_order_details_billing_info_save' ) ).on( 'click', ec_order_show_hide_edit_billing );
	jQuery( document.getElementById( 'ec_admin_order_details_save' ) ).on( 'click', ec_order_show_hide_edit_order_information );
	jQuery( document.getElementById( 'ec_admin_order_details_save_bottom' ) ).on( 'click', ec_order_show_hide_edit_order_information_bottom );
	jQuery( document.getElementById( 'ec_admin_order_date_save' ) ).on( 'click', ec_order_show_hide_edit_order_date );
	jQuery( document.getElementById( 'sub_total' ) ).on( 'change', ec_order_update_totals );
	if( jQuery( document.getElementById( 'tax_total' ) ) ) {
		jQuery( document.getElementById( 'tax_total' ) ).on( 'change', ec_order_update_totals );
	}
	jQuery( document.getElementById( 'shipping_total' ) ).on( 'change', ec_order_update_totals );
	jQuery( document.getElementById( 'discount_total' ) ).on( 'change', ec_order_update_totals );
	if( jQuery( document.getElementById( 'duty_total' ) ) ) {
		jQuery( document.getElementById( 'duty_total' ) ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( document.getElementById( 'gst_total' ) ) ) {
		jQuery( document.getElementById( 'gst_total' ) ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( document.getElementById( 'pst_total' ) ) ) {
		jQuery( document.getElementById( 'pst_total' ) ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( document.getElementById( 'hst_total' ) ) ) {
		jQuery( document.getElementById( 'hst_total' ) ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( document.getElementById( 'vat_total' ) ) ) {
		jQuery( document.getElementById( 'vat_total' ) ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( '.ec_admin_order_details_flex_fee_row input' ) ) {
		jQuery( '.ec_admin_order_details_flex_fee_row input' ).on( 'change', ec_order_update_totals );
	}
	if( jQuery( '.wp-easycart-copy-address' ).length ) {
		jQuery( '.wp-easycart-copy-address' ).on( 'click', function() {
			var address = '';
			if ( 'shipping' == jQuery( this ).data( 'type' ) ) {
				if ( jQuery( '#ec_admin_order_details_shipping_name' ).length && '' != jQuery( '#ec_admin_order_details_shipping_name' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_name' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_shipping_company' ).length && '' != jQuery( '#ec_admin_order_details_shipping_company' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_company' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_shipping_address1' ).length && '' != jQuery( '#ec_admin_order_details_shipping_address1' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_address1' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_shipping_address2' ).length && '' != jQuery( '#ec_admin_order_details_shipping_address2' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_address2' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_shipping_address3' ).length && '' != jQuery( '#ec_admin_order_details_shipping_address3' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_address3' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_shipping_country' ).length && '' != jQuery( '#ec_admin_order_details_shipping_country' ).html() ) {
					address += jQuery( '#ec_admin_order_details_shipping_country' ).html();
				}
			} else {
				if ( jQuery( '#ec_admin_order_details_billing_name' ).length && '' != jQuery( '#ec_admin_order_details_billing_name' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_name' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_billing_company' ).length && '' != jQuery( '#ec_admin_order_details_billing_company' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_company' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_billing_address1' ).length && '' != jQuery( '#ec_admin_order_details_billing_address1' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_address1' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_billing_address2' ).length && '' != jQuery( '#ec_admin_order_details_billing_address2' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_address2' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_billing_address3' ).length && '' != jQuery( '#ec_admin_order_details_billing_address3' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_address3' ).html() + "\r\n";
				}
				if ( jQuery( '#ec_admin_order_details_billing_country' ).length && '' != jQuery( '#ec_admin_order_details_billing_country' ).html() ) {
					address += jQuery( '#ec_admin_order_details_billing_country' ).html();
				}
			}
			navigator.clipboard.writeText( address );
			jQuery( this ).find( '.wp-easycart-admin-copied' ).fadeIn( 'fast' ).delay( 1000 ).fadeOut( 'slow' );
			return false;
		} );
	}
	if ( jQuery( '#ec_order_pickup_date' ).length ) {
		jQuery( '#ec_order_pickup_date, #ec_order_pickup_date_time' ).on( 'change', function() {
			var data = {
				action: 'ec_admin_ajax_update_order_pickup_date',
				order_id: ec_admin_get_value( 'order_id', 'text' ),
				pickup_date: jQuery( '#ec_order_pickup_date' ).datepicker( 'getDate' ),
				pickup_date_time: jQuery( '#ec_order_pickup_date_time' ).val(),
				wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_order_details_nonce', 'text' ),
			};
			jQuery.ajax( { url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
		} );
	}
	if ( jQuery( '#ec_order_pickup_time_date' ).length ) {
		jQuery( '#ec_order_pickup_time_date, #ec_order_pickup_time_time' ).on( 'change', function() {
			var data = {
				action: 'ec_admin_ajax_update_order_pickup_time',
				order_id: ec_admin_get_value( 'order_id', 'text' ),
				pickup_time_date: jQuery( '#ec_order_pickup_time_date' ).datepicker( 'getDate' ),
				pickup_time_time: jQuery( '#ec_order_pickup_time_time' ).val(),
				wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_order_details_nonce', 'text' ),
			};
			jQuery.ajax( { url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
		} );
	}
	if ( jQuery( '#ec_order_location_id' ).length ) {
		jQuery( '#ec_order_location_id' ).on( 'change', function() {
			var data = {
				action: 'ec_admin_ajax_update_order_pickup_location',
				order_id: ec_admin_get_value( 'order_id', 'text' ),
				location_id: ec_admin_get_value( 'ec_order_location_id' ),
				wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_order_details_nonce', 'text' ),
			};
			jQuery.ajax( { url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
		} );
	}
} );

function ec_order_update_totals( ){
	var sub_total = Number( jQuery( document.getElementById( 'sub_total' ) ).val( ) );
	var shipping_total = Number( jQuery( document.getElementById( 'shipping_total' ) ).val( ) );
	var discount_total = Number( jQuery( document.getElementById( 'discount_total' ) ).val( ) );
	var tax_total = 0;
	var duty_total = 0;
	var vat_total = 0;
	var gst_total = 0;
	var hst_total = 0;
	var pst_total = 0;
	var fees_total = 0;
	
	if ( jQuery( document.getElementById( 'tax_total' ) ).length ) {
		tax_total = Number( jQuery( document.getElementById( 'tax_total' ) ).val( ) );
	}
	if ( jQuery( document.getElementById( 'vat_total' ) ).length ) {
		vat_total = Number( jQuery( document.getElementById( 'vat_total' ) ).val( ) );
	}
	if ( jQuery( document.getElementById( 'gst_total' ) ).length ) {
		gst_total = Number( jQuery( document.getElementById( 'gst_total' ) ).val( ) );
	}
	if ( jQuery( document.getElementById( 'hst_total' ) ).length ) {
		hst_total = Number( jQuery( document.getElementById( 'hst_total' ) ).val( ) );
	}
	if ( jQuery( document.getElementById( 'pst_total' ) ).length ) {
		pst_total = Number( jQuery( document.getElementById( 'pst_total' ) ).val( ) );
	}
	if ( jQuery( document.getElementById( 'duty_total' ) ).length ) {
		duty_total = Number( jQuery( document.getElementById( 'duty_total' ) ).val( ) );
	}
	jQuery ( '.ec_admin_order_details_flex_fee_row input' ).each( function() {
		fees_total += Number( jQuery( this ).val() );
	} );
	var grand_total = Number( sub_total + shipping_total + tax_total + duty_total + vat_total + gst_total + hst_total + pst_total + fees_total - discount_total ).toFixed( 2 );
	jQuery( document.getElementById( 'grand_total' ) ).val( grand_total );
}

function ec_order_view_as_customer( ){
    return true;
}

function ec_order_add_new_line( ){
	jQuery( document.getElementById( 'ec_admin_add_new_order_item' ) ).show( );
}

function ec_order_add_new_line_cancel( ){
	jQuery( document.getElementById( 'ec_admin_add_new_order_item' ) ).hide( );
	ec_order_add_new_line_reset( );
}

function ec_order_add_new_line_submit( ){
	jQuery( document.getElementById( 'ec_admin_order_management' ) ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_add_new_order_detail_line_item',
		order_id: ec_admin_get_value( 'order_id', 'text' ),
		order_line_add_product_id: ec_admin_get_value( 'order_line_add_product_id', 'select' ),
		order_line_add_quantity: ec_admin_get_value( 'order_line_add_quantity', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( document.getElementById( 'ec_admin_add_new_order_item' ) ).hide( );
		jQuery( data ).insertBefore( jQuery( document.getElementById( 'ec_admin_add_new_order_item' ) ) );
		ec_order_add_new_line_reset( );
		ec_admin_hide_loader( 'ec_admin_order_management' );
		ec_order_history_refresh( );
	} } );
}

function ec_order_add_new_line_reset( ){
	jQuery( document.getElementById( 'order_line_add_product_id' ) ).val( '0' ).trigger( 'change' );
	jQuery( document.getElementById( 'order_line_add_quantity' ) ).val( '' );
}

function ec_admin_update_line_item_total( orderdetail_id ){
	var quantity = Math.round( jQuery( document.getElementById( 'line_item_quantity_' + orderdetail_id ) ).val( ) );
    jQuery( document.getElementById( 'line_item_quantity_' + orderdetail_id ) ).val( quantity );
	var unit_price = jQuery( document.getElementById( 'line_item_unit_price_' + orderdetail_id ) ).val( );
	var total_price = parseFloat( Math.round( quantity * unit_price * 100 ) / 100 ).toFixed(2);
	jQuery( document.getElementById( 'line_item_total_price_' + orderdetail_id ) ).val( total_price );
}

function ec_order_edit_line_item( orderdetail_id ){
	if( jQuery( document.getElementById( 'ec_admin_order_line_edit_' + orderdetail_id ) ).attr( 'data-editing' ) && jQuery( document.getElementById( 'ec_admin_order_line_edit_' + orderdetail_id ) ).attr( 'data-editing' ) == '1' ){
		jQuery( document.getElementById( 'ec_admin_order_details_item_price_display_' + orderdetail_id ) ).html( jQuery( document.getElementById( 'line_item_quantity_' + orderdetail_id ) ).val( ) + '<span> x </span>' + wp_easycart_admin_vars.ec_option_currency + jQuery( document.getElementById( 'line_item_unit_price_' + orderdetail_id ) ).val( ) );
		jQuery( document.getElementById( 'ec_admin_order_details_item_total_display_' + orderdetail_id ) ).html( wp_easycart_admin_vars.ec_option_currency + jQuery( document.getElementById( 'line_item_total_price_' + orderdetail_id ) ).val( ) );
		jQuery( document.getElementById( 'ec_admin_order_details_item_title_display_' + orderdetail_id ) ).html( jQuery( document.getElementById( 'line_item_title_' + orderdetail_id ) ).val( ) );
		jQuery( document.getElementById( 'ec_admin_order_details_item_model_number_display_' + orderdetail_id ) ).html( jQuery( document.getElementById( 'line_item_model_number_' + orderdetail_id ) ).val( ) );
		
		jQuery( document.getElementById( 'ec_admin_order_management' ) ).fadeIn( 'fast' );
		var quantity_element_name = 'line_item_quantity_' + orderdetail_id;
		var unit_price_element_name = 'line_item_unit_price_' + orderdetail_id;
		var total_price_element_name = 'line_item_total_price_' + orderdetail_id;
		var title_element_name = 'line_item_title_' + orderdetail_id;
		var model_number_element_name = 'line_item_model_number_' + orderdetail_id;
        var giftcard_id_element_name = 'line_item_giftcard_id_' + orderdetail_id;
        var gift_card_email_element_name = 'line_item_gift_card_email_' + orderdetail_id;
        var gift_card_from_name_element_name = 'line_item_gift_card_from_name_' + orderdetail_id;
        var gift_card_to_name_element_name = 'line_item_gift_card_to_name_' + orderdetail_id;
        var gift_card_message_element_name = 'line_item_gift_card_message_' + orderdetail_id;
        var optionitem1_element_name = 'line_item_optionitem_name_1_' + orderdetail_id;
        var optionitem2_element_name = 'line_item_optionitem_name_2_' + orderdetail_id;
        var optionitem3_element_name = 'line_item_optionitem_name_3_' + orderdetail_id;
        var optionitem4_element_name = 'line_item_optionitem_name_4_' + orderdetail_id;
        var optionitem5_element_name = 'line_item_optionitem_name_5_' + orderdetail_id;
		var data = {
			action: 'ec_admin_ajax_edit_order_detail_line_item',
			order_id: ec_admin_get_value( 'order_id', 'text' ),
			orderdetail_id: orderdetail_id
		};
		data[quantity_element_name] = ec_admin_get_value( quantity_element_name, 'text' );
		data[unit_price_element_name] = ec_admin_get_value( unit_price_element_name, 'text' );
		data[total_price_element_name] = ec_admin_get_value( total_price_element_name, 'text' );
		data[title_element_name] = ec_admin_get_value( title_element_name, 'text' );
		data[model_number_element_name] = ec_admin_get_value( model_number_element_name, 'text' );
		
        if( jQuery( document.getElementById( giftcard_id_element_name ) ).length ){
            data[giftcard_id_element_name] = ec_admin_get_value( giftcard_id_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_giftcard_id_display_' + orderdetail_id ) ).html( ec_admin_get_value( giftcard_id_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( gift_card_email_element_name ) ).length ){
            data[gift_card_email_element_name] = ec_admin_get_value( gift_card_email_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_gift_card_email_display_' + orderdetail_id ) ).html( ec_admin_get_value( gift_card_email_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( gift_card_from_name_element_name ) ).length ){
            data[gift_card_from_name_element_name] = ec_admin_get_value( gift_card_from_name_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_gift_card_from_name_display_' + orderdetail_id ) ).html( ec_admin_get_value( gift_card_from_name_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( gift_card_to_name_element_name ) ).length ){
            data[gift_card_to_name_element_name] = ec_admin_get_value( gift_card_to_name_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_gift_card_to_name_display_' + orderdetail_id ) ).html( ec_admin_get_value( gift_card_to_name_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( gift_card_message_element_name ) ).length ){
            data[gift_card_message_element_name] = ec_admin_get_value( gift_card_message_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_gift_card_message_display_' + orderdetail_id ) ).html( ec_admin_get_value( gift_card_message_element_name, 'text' ) );
        }
		
        if( jQuery( document.getElementById( optionitem1_element_name ) ).length ){
            data[optionitem1_element_name] = ec_admin_get_value( optionitem1_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_optionitem_name_1_display_' + orderdetail_id ) ).html( ec_admin_get_value( optionitem1_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( optionitem2_element_name ) ).length ){
            data[optionitem2_element_name] = ec_admin_get_value( optionitem2_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_optionitem_name_2_display_' + orderdetail_id ) ).html( ec_admin_get_value( optionitem2_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( optionitem3_element_name ) ).length ){
            data[optionitem3_element_name] = ec_admin_get_value( optionitem3_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_optionitem_name_3_display_' + orderdetail_id ) ).html( ec_admin_get_value( optionitem3_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( optionitem4_element_name ) ).length ){
            data[optionitem4_element_name] = ec_admin_get_value( optionitem4_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_optionitem_name_4_display_' + orderdetail_id ) ).html( ec_admin_get_value( optionitem4_element_name, 'text' ) );
        }
        
        if( jQuery( document.getElementById( optionitem5_element_name ) ).length ){
            data[optionitem5_element_name] = ec_admin_get_value( optionitem5_element_name, 'text' );
            jQuery( document.getElementById( 'ec_admin_order_details_item_optionitem_name_5_display_' + orderdetail_id ) ).html( ec_admin_get_value( optionitem5_element_name, 'text' ) );
        }
        
        if( jQuery( '.ec_admin_order_details_item_adv_opt_edit_' + orderdetail_id ).length ){
            data['adv_items'] = Array( );
            jQuery( '.ec_admin_order_details_item_adv_opt_edit_' + orderdetail_id ).each( function( ){
                data['adv_items'].push( { 
                    id: jQuery( this ).attr( 'data-order-option-id' ), 
                    value: jQuery( this ).find( 'input' ).val( )
                } );
                jQuery( document.getElementById( 'ec_admin_order_details_item_adv_optionitem_' + orderdetail_id + '_' + jQuery( this ).attr( 'data-order-option-id' ) ) ).html( jQuery( this ).find( 'input' ).val( ) );
            } );
        }
		
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			jQuery( document.getElementById( 'ec_admin_order_details_item_price_display_' + orderdetail_id ) ).show( );
			jQuery( document.getElementById( 'ec_admin_order_details_item_total_display_' + orderdetail_id ) ).show( );
			jQuery( document.getElementById( 'ec_admin_order_line_edit_' + orderdetail_id ) ).removeClass( 'dashicons-yes' ).addClass( 'dashicons-edit' ).attr( 'data-editing', 0 );
			jQuery( document.getElementById( 'ec_admin_order_details_item_price_edit_' + orderdetail_id ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_order_details_item_total_edit_' + orderdetail_id ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_order_details_title_edit_' + orderdetail_id ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_order_details_giftcard_id_edit_' + orderdetail_id ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_order_details_gift_card_email_edit_' + orderdetail_id ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_order_details_gift_card_from_name_edit_' + orderdetail_id ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_order_details_gift_card_to_name_edit_' + orderdetail_id ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_order_details_gift_card_message_edit_' + orderdetail_id ) ).hide( );
		    jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_1_edit_' + orderdetail_id ) ).hide( );
		    jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_2_edit_' + orderdetail_id ) ).hide( );
		    jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_3_edit_' + orderdetail_id ) ).hide( );
		    jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_4_edit_' + orderdetail_id ) ).hide( );
		    jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_5_edit_' + orderdetail_id ) ).hide( );
            jQuery( '.ec_admin_order_details_item_adv_opt_edit_' + orderdetail_id ).hide( );
			jQuery( document.getElementById( 'ec_admin_order_details_model_number_edit_' + orderdetail_id ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_order_details_item_save_display_' + orderdetail_id ) ).hide( );
			ec_admin_hide_loader( 'ec_admin_order_management' );
			ec_order_history_refresh( );
		} } );
		
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_item_price_display_' + orderdetail_id ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_details_item_total_display_' + orderdetail_id ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_line_edit_' + orderdetail_id ) ).removeClass( 'dashicons-edit' ).addClass( 'dashicons-yes' ).attr( 'data-editing', '1' );
		jQuery( document.getElementById( 'ec_admin_order_details_item_price_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_item_total_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_title_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_giftcard_id_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_gift_card_email_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_gift_card_from_name_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_gift_card_to_name_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_gift_card_message_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_1_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_2_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_3_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_4_edit_' + orderdetail_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_optionitem_name_5_edit_' + orderdetail_id ) ).show( );
        jQuery( '.ec_admin_order_details_item_adv_opt_edit_' + orderdetail_id ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_model_number_edit_' + orderdetail_id ) ).show( );
        jQuery( document.getElementById( 'ec_admin_order_details_item_save_display_' + orderdetail_id ) ).show( );
	}
}

function ec_order_delete_line_item( orderdetail_id ){
	if( confirm( 'Are you sure you want to delete this order line item?' ) ){
		jQuery( document.getElementById( 'ec_admin_order_management' ) ).fadeIn( 'fast' );
		var data = {
			action: 'ec_admin_ajax_delete_order_detail_line_item',
			order_id: ec_admin_get_value( 'order_id', 'text' ),
			orderdetail_id: orderdetail_id
		};
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			ec_admin_hide_loader( 'ec_admin_order_management' );
			jQuery( document.getElementById( 'ec_admin_order_details_line_item_' + orderdetail_id ) ).remove( );
			ec_order_history_refresh( );
		} } );
	}
}

function ec_order_show_hide_edit_order_date( ){
	if( ec_admin_order_details_date_show ){
		jQuery( document.getElementById( 'ec_admin_order_management' ) ).fadeIn( 'fast' );
		
		jQuery( document.getElementById( 'ec_admin_order_details_order_date_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_order_date_edit' ) ).hide( );
		ec_admin_order_details_date_show = false;
		jQuery( document.getElementById( 'ec_admin_order_details_order_date' ) ).html( ec_admin_get_value( 'order_date', 'text' ) );
		
		var data = {
			action: 'ec_admin_ajax_save_order_date',
			order_id: ec_admin_get_value( 'order_id', 'text' ),
			order_date: ec_admin_get_value( 'order_date', 'text' )
		};
		
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			ec_admin_hide_loader( 'ec_admin_order_management' );
			ec_order_history_refresh( );
		} } );
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_order_date_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_details_order_date_edit' ) ).show( );
		ec_admin_order_details_date_show = true;
	}
}

function ec_admin_save_order_date( ){
	jQuery( document.getElementById( 'ec_admin_order_details_order_date' ) ).html( ec_admin_get_value( 'order_date', 'text' ) );
}

function ec_order_show_hide_edit_order_information( ){
	if( ec_admin_order_details_save_show ){
		jQuery( document.getElementById( 'ec_admin_view_order_information' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_edit_order_information' ) ).hide( );
		ec_admin_save_order_information( );
		ec_admin_order_details_save_show = false;
	}else{
		jQuery( document.getElementById( 'ec_admin_view_order_information' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_edit_order_information' ) ).show( );
		ec_admin_order_details_save_show = true;
	}
}

function ec_admin_save_order_information( ){
	jQuery( document.getElementById( 'ec_admin_shipping_details' ) ).fadeIn( 'fast' );
	
	jQuery( document.getElementById( 'ec_admin_order_details_card_holder_name' ) ).html( ec_admin_get_value( 'card_holder_name', 'text' ) );
	jQuery( document.getElementById( 'ec_admin_order_details_user_email' ) ).html( '<a href="mailto: ' + ec_admin_get_value( 'user_email', 'text' ) + '">' + ec_admin_get_value( 'user_email', 'text' ) + '</a>' );
	if ( '' != ec_admin_get_value( 'email_other', 'text' ) ) {
		jQuery( document.getElementById( 'ec_admin_order_details_email_other' ) ).html( '<a href="mailto: ' + ec_admin_get_value( 'email_other', 'text' ) + '">' + ec_admin_get_value( 'email_other', 'text' ) + '</a><br />' );
	} else {
		jQuery( document.getElementById( 'ec_admin_order_details_email_other' ) ).html( '' );
	}
	
	if( ec_admin_get_value( 'creditcard_digits', 'text' ) != "" ){
		jQuery( document.getElementById( 'ec_admin_order_details_creditcard_digits' ) ).html( "**** **** **** " + ec_admin_get_value( 'creditcard_digits', 'text' ) + "<br />" );
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_creditcard_digits' ) ).html( "" );
	}
	
	if( ec_admin_get_value( 'cc_exp_month', 'text' ) != "" ){
		jQuery( document.getElementById( 'ec_admin_order_details_cc_exp' ) ).html( ec_admin_get_value( 'cc_exp_month', 'text' ) + ' / ' + ec_admin_get_value( 'cc_exp_year', 'text' ) );
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_cc_exp' ) ).html( "" );
	}
	
	var data = {
		action: 'ec_admin_ajax_save_order_management_details',
		order_id: ec_admin_get_value( 'order_id', 'text' ),
		user_email: ec_admin_get_value( 'user_email', 'text' ),
		email_other: ec_admin_get_value( 'email_other', 'text' ),
		card_holder_name: ec_admin_get_value( 'card_holder_name', 'text' ),
		creditcard_digits: ec_admin_get_value( 'creditcard_digits', 'text' ),
		cc_exp_month: ec_admin_get_value( 'cc_exp_month', 'text' ),
		cc_exp_year: ec_admin_get_value( 'cc_exp_year', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_details' );
		ec_order_history_refresh( );
	} } );
	
	return false;
}

function ec_order_show_hide_edit_order_information_bottom( ){
	if( ec_admin_order_details_save_bottom_show ){
		jQuery( document.getElementById( 'ec_admin_view_order_information_bottom' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_edit_order_information_bottom' ) ).hide( );
		ec_admin_save_order_information_bottom( );
		ec_admin_order_details_save_bottom_show = false;
	}else{
		jQuery( document.getElementById( 'ec_admin_view_order_information_bottom' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_edit_order_information_bottom' ) ).show( );
		ec_admin_order_details_save_bottom_show = true;
	}
}

function ec_admin_save_order_information_bottom( ){
	jQuery( document.getElementById( 'ec_admin_shipping_details' ) ).fadeIn( 'fast' );
	
	if( ec_admin_get_value( 'order_ip_address', 'text' ) != '' ){
		jQuery( document.getElementById( 'ec_admin_order_details_ip_address' ) ).html( 'IP: ' + ec_admin_get_value( 'order_ip_address', 'text' ) + '<br />' );
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_ip_address' ) ).html( '' );
	}
	if( ec_admin_get_value( 'agreed_to_terms', 'select' ) == '1' )
		jQuery( document.getElementById( 'ec_admin_order_details_agreed_to_terms' ) ).html( wp_easycart_pro_admin_orders_language['agree-terms-yes'] );
	else
		jQuery( document.getElementById( 'ec_admin_order_details_agreed_to_terms' ) ).html( wp_easycart_pro_admin_orders_language['agree-terms-no'] );
	
	var data = {
		action: 'ec_admin_ajax_save_order_management_details_bottom',
		order_id: ec_admin_get_value( 'order_id', 'text' ),
		agreed_to_terms: ec_admin_get_value( 'agreed_to_terms', 'select' ),
		order_ip_address: ec_admin_get_value( 'order_ip_address', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_details' );
		ec_order_history_refresh( );
	} } );
	
	return false;
}

function ec_order_show_hide_edit_shipping( ){
	
	if( ec_admin_order_details_shipping_show ){
		jQuery( document.getElementById( "ec_admin_shipping_details" ) ).fadeIn( 'fast' );
		
		/* Update Address Display */
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_name' ) ).html( ec_admin_get_value( 'shipping_first_name', 'text' ) + " " + ec_admin_get_value( 'shipping_last_name', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_company' ) ).html( ec_admin_get_value( 'shipping_company_name', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_address1' ) ).html( ec_admin_get_value( 'shipping_address_line_1', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_address2' ) ).html( ec_admin_get_value( 'shipping_address_line_2', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_address3' ) ).html( ec_admin_get_value( 'shipping_city', 'text' ) + ' ' + ec_admin_get_value( 'shipping_state', 'text' ) + ' ' + ec_admin_get_value( 'shipping_zip', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_country' ) ).html( jQuery( '#shipping_country option:selected' ).text( ) );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_phone' ) ).html( ec_admin_get_value( 'shipping_phone', 'text' ) );
			
		/* Update in DB */
		var data = {
			action: 'ec_admin_ajax_save_order_shipping_address',
			order_id: ec_admin_get_value( 'order_id', 'hidden' ),
			shipping_first_name: ec_admin_get_value( 'shipping_first_name', 'text' ),
			shipping_last_name: ec_admin_get_value( 'shipping_last_name', 'text' ),
			shipping_company_name: ec_admin_get_value( 'shipping_company_name', 'text' ),
			shipping_address_line_1: ec_admin_get_value( 'shipping_address_line_1', 'text' ),
			shipping_address_line_2: ec_admin_get_value( 'shipping_address_line_2', 'text' ),
			shipping_city: ec_admin_get_value( 'shipping_city', 'text' ),
			shipping_state: ec_admin_get_value( 'shipping_state', 'text' ),
			shipping_zip: ec_admin_get_value( 'shipping_zip', 'text' ),
			shipping_country: ec_admin_get_value( 'shipping_country', 'select' ),
			shipping_phone: ec_admin_get_value( 'shipping_phone', 'text' )
		};
		
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			jQuery( document.getElementById( 'ec_admin_order_details_shipping_content' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_order_details_shipping_form' ) ).hide( );
			ec_admin_hide_loader( 'ec_admin_shipping_details' );
			ec_order_history_refresh( );
		} } );
		
		ec_admin_order_details_shipping_show = false;
		
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_content' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_details_shipping_form' ) ).show( );
		ec_admin_order_details_shipping_show = true;
	
	}
}

function ec_order_show_hide_edit_billing( ){
	
	if( ec_admin_order_details_billing_show ){
		jQuery( document.getElementById( "ec_admin_shipping_details" ) ).fadeIn( 'fast' );
		
		/* Update Address Display */
		jQuery( document.getElementById( 'ec_admin_order_details_billing_name' ) ).html( ec_admin_get_value( 'billing_first_name', 'text' ) + " " + ec_admin_get_value( 'billing_last_name', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_company' ) ).html( ec_admin_get_value( 'billing_company_name', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_address1' ) ).html( ec_admin_get_value( 'billing_address_line_1', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_address2' ) ).html( ec_admin_get_value( 'billing_address_line_2', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_address3' ) ).html( ec_admin_get_value( 'billing_city', 'text' ) + ' ' + ec_admin_get_value( 'billing_state', 'text' ) + ' ' + ec_admin_get_value( 'billing_zip', 'text' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_country' ) ).html( jQuery( '#billing_country option:selected' ).text( ) );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_phone' ) ).html( ec_admin_get_value( 'billing_phone', 'text' ) );
			
		/* Update in DB */
		var data = {
			action: 'ec_admin_ajax_save_order_billing_address',
			order_id: ec_admin_get_value( 'order_id', 'hidden' ),
			billing_first_name: ec_admin_get_value( 'billing_first_name', 'text' ),
			billing_last_name: ec_admin_get_value( 'billing_last_name', 'text' ),
			billing_company_name: ec_admin_get_value( 'billing_company_name', 'text' ),
			billing_address_line_1: ec_admin_get_value( 'billing_address_line_1', 'text' ),
			billing_address_line_2: ec_admin_get_value( 'billing_address_line_2', 'text' ),
			billing_city: ec_admin_get_value( 'billing_city', 'text' ),
			billing_state: ec_admin_get_value( 'billing_state', 'text' ),
			billing_zip: ec_admin_get_value( 'billing_zip', 'text' ),
			billing_country: ec_admin_get_value( 'billing_country', 'select' ),
			billing_phone: ec_admin_get_value( 'billing_phone', 'text' )
		};
		
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			jQuery( document.getElementById( 'ec_admin_order_details_billing_content' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_order_details_billing_form' ) ).hide( );
			ec_admin_hide_loader( 'ec_admin_shipping_details' );
			ec_order_history_refresh( );
		} } );
		
		ec_admin_order_details_billing_show = false;
		
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_billing_content' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_details_billing_form' ) ).show( );
		ec_admin_order_details_billing_show = true;
	
	}
}

function ec_order_show_hide_edit_totals( ){
	
	if( ec_admin_order_details_totals_show ){
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_content' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_form' ) ).hide( );
		ec_admin_order_details_totals_show = false;
		
		/* Update Totals Display */
		jQuery( document.getElementById( 'ec_admin_order_details_totals_sub_total' ) ).html( Number( ec_admin_get_value( 'sub_total', 'number' ) ).toFixed( 2 ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_tax_total' ) ).html( Number( ec_admin_get_value( 'tax_total', 'number' ) ).toFixed( 2 ) );
		if( Number( ec_admin_get_value( 'tax_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_tax_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_tax_total_row' ) ).hide( );
		}
		
		jQuery( '.ec_admin_order_details_flex_fee_row' ).each( function() {
			jQuery( document.getElementById( 'ec_admin_order_details_totals_flex_fee_' + jQuery( this ).attr( 'data-flex-fee-id' ) ) ).html( Number( ec_admin_get_value( 'flex_fee_' + jQuery( this ).attr( 'data-flex-fee-id' ), 'number' ) ).toFixed( 2 ) );
		} );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_discount_total' ) ).html( Number( ec_admin_get_value( 'discount_total', 'number' ) ).toFixed( 2 ) );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_discount_total' ) ).html( Number( ec_admin_get_value( 'discount_total', 'number' ) ).toFixed( 2 ) );
		if( Number( ec_admin_get_value( 'discount_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_discount_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_discount_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_shipping_total' ) ).html( Number( ec_admin_get_value( 'shipping_total', 'number' ) ).toFixed( 2 ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_total' ) ).html( Number( ec_admin_get_value( 'vat_total', 'number' ) ).toFixed( 2 ) );
		if( ec_admin_get_value( 'vat_total', 'number' ) && Number( ec_admin_get_value( 'vat_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_duty_total' ) ).html( Number( ec_admin_get_value( 'duty_total', 'number' ) ).toFixed( 2 ) );
		if( ec_admin_get_value( 'duty_total', 'number' ) && Number( ec_admin_get_value( 'duty_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_duty_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_duty_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_grand_total' ) ).html( Number( ec_admin_get_value( 'grand_total', 'number' ) ).toFixed( 2 ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_refund_total' ) ).html( Number( ec_admin_get_value( 'refund_total', 'number' ) ).toFixed( 2 ) );
		if( Number( ec_admin_get_value( 'refund_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_refund_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_refund_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_gst_total' ) ).html( Number( ec_admin_get_value( 'gst_total', 'number' ) ).toFixed( 2 ) );
		if( ec_admin_get_value( 'gst_total', 'number' ) && Number( ec_admin_get_value( 'gst_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_gst_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_gst_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_gst_rate' ) ).html( ec_admin_get_value( 'gst_rate', 'number' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_gst_total_rate' ) ).html( ec_admin_get_value( 'gst_rate', 'number' ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_pst_total' ) ).html( Number( ec_admin_get_value( 'pst_total', 'number' ) ).toFixed( 2 ) );
		if( ec_admin_get_value( 'pst_total', 'number' ) &&  Number( ec_admin_get_value( 'pst_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_pst_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_pst_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_pst_rate' ) ).html( ec_admin_get_value( 'pst_rate', 'number' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_pst_total_rate' ) ).html( ec_admin_get_value( 'pst_rate', 'number' ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_hst_total' ) ).html( Number( ec_admin_get_value( 'hst_total', 'number' ) ).toFixed( 2 ) );
		if( ec_admin_get_value( 'hst_total', 'number' ) && Number( ec_admin_get_value( 'hst_total', 'number' ) ) != 0 ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_hst_total_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_hst_total_row' ) ).hide( );
		}
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_hst_rate' ) ).html( ec_admin_get_value( 'hst_rate', 'number' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_hst_total_rate' ) ).html( ec_admin_get_value( 'hst_rate', 'number' ) )
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_rate' ) ).html( ec_admin_get_value( 'vat_rate', 'number' ) );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_total_rate' ) ).html( ec_admin_get_value( 'vat_rate', 'number' ) );
		
		jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_registration_number' ) ).html( ec_admin_get_value( 'vat_registration_number', 'text' ) );
		if( ec_admin_get_value( 'vat_registration_number', 'text' ) && ec_admin_get_value( 'vat_registration_number', 'text' ) != '' ){
			jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_registration_number_row' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_order_details_totals_vat_registration_number_row' ) ).hide( );
		}
		
		/* Update in DB */
		jQuery( document.getElementById( "ec_admin_order_management" ) ).fadeIn( 'fast' );
		
		var data = {
			action: 'ec_admin_ajax_edit_order_totals',
			order_id: ec_admin_get_value( 'order_id', 'hidden' ),
			sub_total: ec_admin_get_value( 'sub_total', 'number' ),
			tax_total: ec_admin_get_value( 'tax_total', 'text' ),
			shipping_total: ec_admin_get_value( 'shipping_total', 'number' ),
			discount_total: ec_admin_get_value( 'discount_total', 'number' ),
			vat_total: ec_admin_get_value( 'vat_total', 'number' ),
			duty_total: ec_admin_get_value( 'duty_total', 'number' ),
			grand_total: ec_admin_get_value( 'grand_total', 'number' ),
			refund_total: ec_admin_get_value( 'refund_total', 'number' ),
			gst_total: ec_admin_get_value( 'gst_total', 'number' ),
			gst_rate: ec_admin_get_value( 'gst_rate', 'number' ),
			pst_total: ec_admin_get_value( 'pst_total', 'number' ),
			pst_rate: ec_admin_get_value( 'pst_rate', 'number' ),
			hst_total: ec_admin_get_value( 'hst_total', 'number' ),
			hst_rate: ec_admin_get_value( 'hst_rate', 'number' ),
			vat_rate: ec_admin_get_value( 'vat_rate', 'number' ),
			vat_registration_number: ec_admin_get_value( 'vat_registration_number', 'text' )
		};
		
		jQuery( '.ec_admin_order_details_flex_fee_row' ).each( function() {
			data['flex_fee_' + jQuery( this ).attr( 'data-flex-fee-id' )] = ec_admin_get_value( 'flex_fee_' + jQuery( this ).attr( 'data-flex-fee-id' ) );
		} );
		
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			ec_admin_hide_loader( 'ec_admin_order_management' );
			ec_order_history_refresh( );
		} } );
		
	}else{
		jQuery( document.getElementById( 'ec_admin_order_details_totals_content' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_order_details_totals_form' ) ).show( );
		ec_admin_order_details_totals_show = true;
	}
}

function ec_admin_process_refund( ){
	jQuery( document.getElementById( "ec_admin_order_management" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_process_refund',
		order_id: ec_admin_get_value( 'order_id', 'text' ),
		refund_amount: ec_admin_get_value( 'refund_amount', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_management' );
		var result = JSON.parse( data );
		if( result.error ){
			jQuery( document.getElementById( 'ec_admin_refund_failed' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_edit_order_refund' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_refund_failed' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_refund_button' ) ).html( 'Refund' );
			if( result.is_full_refund ){
				jQuery( document.getElementById( 'ec_admin_refund_button' ) ).hide( );
			}
			jQuery( document.getElementById( 'ec_admin_order_details_totals_refund_total' ) ).html( result.refund_total );
			jQuery( document.getElementById( 'ec_admin_order_details_totals_refund_total_row' ) ).show( );
			jQuery( document.getElementById( 'refund_amount' ) ).val( result.refund_remaining );
			jQuery( document.getElementById( 'orderstatus_id' ) ).val( result.orderstatus_id );
			jQuery( document.getElementById( 'order_notes' ) ).val( result.order_notes );
			
			/* Maybe send google tag info */
			if ( '' != wpeasycart_admin_ajax_object.ga4_id || '' != wpeasycart_admin_ajax_object.ga4_conv_id ) {
				if ( '1' == wpeasycart_admin_ajax_object.is_tag_manager ) {
					dataLayer.push( { ecommerce: null } );  // Clear the previous ecommerce object.
					dataLayer.push( {
						event: 'refund',
						ecommerce: {
							transaction_id: ec_admin_get_value( 'order_id', 'text' ),
							value: ec_admin_get_value( 'refund_amount', 'text' ),
						}
					} );
				} else {
					gtag( 'event', 'refund', {
						transaction_id: ec_admin_get_value( 'order_id', 'text' ),
					} );
				}
			}
		}
		ec_order_history_refresh( );
	} } );
	
	return false;
	
}

function ec_admin_order_details_full_refund_change( ){
	jQuery( document.getElementById( 'partial_refund' ) ).attr( 'checked', false );
}

function ec_admin_order_details_partial_refund_change( ){
	jQuery( document.getElementById( 'full_refund' ) ).attr( 'checked', false );
}

function ec_order_show_hide_edit_refund(button ){
	if( jQuery( document.getElementById( 'ec_admin_edit_order_refund' ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_admin_edit_order_refund' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_refund_button' ) ).html( 'Refund' );
	
	}else{
		jQuery( document.getElementById( 'ec_admin_refund_failed' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_edit_order_refund' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_refund_button' ) ).html( 'Cancel Refund' );
	}
}

function ec_order_history_refresh( ){
	jQuery( document.getElementById( 'wpeasycart_order_history_refresh' ) ).addClass( 'wpeasycart-refresh-processing' );
	var data = {
		action: 'ec_admin_ajax_refresh_order_history',
		order_id: ec_admin_get_value( 'order_id', 'text' )
	};
	
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ) { 
				jQuery( document.getElementById( 'wpeasycart_order_history' ) ).replaceWith( response );
				jQuery( document.getElementById( 'wpeasycart_order_history_refresh' ) ).removeClass( 'wpeasycart-refresh-processing' );
			}
		}
	);
}

function wp_easycart_admin_subscribe_text_notification( order_id ) {
	jQuery( document.getElementById( "ec_admin_text_notifications" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_subscribe_text_notification',
		order_id: order_id,
		phone_number: jQuery( document.getElementById( 'text_phone_new' ) ).val( )
	};
	
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ) { 
				jQuery( document.getElementById( 'wpeasycart_admin_text_subscriber_list' ) ).replaceWith( response );
				ec_admin_hide_loader( 'ec_admin_text_notifications' );
				jQuery( document.getElementById( 'text_phone_new' ) ).val( '' );
				ec_order_history_refresh();
			}
		}
	);
}

function wp_easycart_admin_unsubscribe_text_notification( order_id, phone_number ) {
	jQuery( document.getElementById( "ec_admin_text_notifications" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_unsubscribe_text_notification',
		order_id: order_id,
		phone_number: phone_number
	};
	
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ) { 
				jQuery( document.getElementById( 'wpeasycart_admin_text_subscriber_list' ) ).replaceWith( response );
				ec_admin_hide_loader( 'ec_admin_text_notifications' );
				ec_order_history_refresh();
			}
		}
	);
}