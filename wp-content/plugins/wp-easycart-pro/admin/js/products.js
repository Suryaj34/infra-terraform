jQuery( document ).ready( function( ){
	if( jQuery( '.ec_admin_product_details_media' ).length ){
		jQuery( '.ec_admin_product_details_media' ).each( function() {
			var uploaderWindow = new wp.media.view.UploaderWindow({
				controller: {
					trigger: function() { },
					on: function() { }
				},
				uploader: {
					container: jQuery( this ),
					dropzone: jQuery( this )
				}
			}).render();
			uploaderWindow.ready();
			jQuery( this ).append( uploaderWindow.el ).on( 'drop', function() {
				var optionitem_id = jQuery( this ).attr( 'data-optionitem-id' );
				ec_admin_image_gallery( 'wpeasycart_admin_product_gallery_' + optionitem_id, optionitem_id );
			} );
		} );
	}
	if ( jQuery( '.wp-easycart-pro-option-table' ).length ) {
		jQuery( '.wp-easycart-pro-option-table' ).on( 'click', '.wp-easycart-pro-option-table-item-action-trigger', function() {
			if( jQuery( this ).parent().find( '.wp-easycart-pro-option-table-item-action-items' ).hasClass( 'is-active' ) ) {
				jQuery( '.wp-easycart-pro-option-table-item-action-items' ).removeClass( 'is-active' );
			} else {
				jQuery( '.wp-easycart-pro-option-table-item-action-items' ).removeClass( 'is-active' );
				jQuery( this ).parent().find( '.wp-easycart-pro-option-table-item-action-items' ).addClass( 'is-active' );
			}
		} );
		jQuery( document ).mouseup( function( e ) {
			if ( ! jQuery( e.target ).hasClass( 'wp-easycart-pro-option-table-item-action-trigger' ) ) {
				jQuery( '.wp-easycart-pro-option-table-item-action-items' ).removeClass( 'is-active' );
			}
		} );
		
		jQuery( '.wp-easycart-pro-option-table' ).on( 'change', '.wp-easycart-pro-option-variant-row input', function() {
			var optionitemquantity_id = jQuery( this ).parent().parent().parent().attr( 'data-optionitem-quantity-id' );
			var data = {
				action: 'ec_admin_ajax_pro_save_variation',
				product_id: ec_admin_get_value( 'product_id', 'hidden' ),
				optionitemquantity_id: optionitemquantity_id,
				sku: ec_admin_get_value( 'wpec_variant_sku_' + optionitemquantity_id, 'text' ),
				price: ec_admin_get_value( 'wpec_variant_price_' + optionitemquantity_id, 'text' ),
				quantity: ec_admin_get_value( 'wpec_variant_quantity_' + optionitemquantity_id, 'text' ),
			};
			jQuery.ajax(
				{
					url: wpeasycart_admin_ajax_object.ajax_url,
					type: 'post',
					data: data,
				}
			);
		} );
		jQuery( '#wp-easycart-pro-basic-options' ).sortable( {
			handle: '.wp-easycart-pro-option-table-item-drag',
			items: '.wp-easycart-pro-option-table-row-sortable',
			tolerance: 'intersect',
			update: function() {
				var option1 = 0, option2 = 0, option3 = 0, option4 = 0, option5 = 0;
				jQuery( '#wp-easycart-pro-basic-options .wp-easycart-pro-option-table-row-sortable' ).each( function() {
					if ( jQuery( this ).attr( 'data-option-id' ) ) {
						if ( option1 == 0 ) {
							option1 = jQuery( this ).attr( 'data-option-id' );
						} else if ( option2 == 0 ) {
							option2 = jQuery( this ).attr( 'data-option-id' );
						} else if ( option3 == 0 ) {
							option3 = jQuery( this ).attr( 'data-option-id' );
						} else if ( option4 == 0 ) {
							option4 = jQuery( this ).attr( 'data-option-id' );
						} else if ( option5 == 0 ) {
							option5 = jQuery( this ).attr( 'data-option-id' );
						}
					}
				} );
				if( ! confirm( wp_easycart_pro_admin_products_language['confirm-option-change'] ) ) {
					jQuery( '#wp-easycart-pro-basic-options' ).sortable( 'cancel' );
					return false;
				} else {
					jQuery( '#option1' ).val( option1 );
					jQuery( '#option2' ).val( option2 );
					jQuery( '#option3' ).val( option3 );
					jQuery( '#option4' ).val( option4 );
					jQuery( '#option5' ).val( option5 );
					wp_easycart_pro_refresh_options();
				}
			}
		} );
		jQuery( '#wp-easycart-pro-modifiers' ).sortable( {
			handle: '.wp-easycart-pro-option-table-item-drag',
			items: '.wp-easycart-pro-option-table-row-sortable',
			tolerance: 'intersect',
			update: function() {
				var option_to_product_ids = new Array();
				jQuery( '#wp-easycart-pro-modifiers .wp-easycart-pro-option-table-row-sortable' ).each( function() {
					option_to_product_ids.push( jQuery( this ).attr( 'data-option-id' ) );
				} );
				var data = {
					action: 'ec_admin_ajax_pro_save_advanced_options_sort',
					option_to_product_ids: option_to_product_ids,
				};

				jQuery.ajax(
					{
						url: wpeasycart_admin_ajax_object.ajax_url,
						type: 'post',
						data: data
					}
				);
			}
		} );
		jQuery( '.wp-easycart-pro-conditional-logic-add-button' ).on( 'click', function() {
			var row_copy = jQuery( '.wp-easycart-pro-conditional-logic-container:first' ).clone();
			console.log( row_copy );
			row_copy.find( '.wp-easycart-pro-conditional-logic-option' ).val( '' );
			row_copy.find( '.wp-easycart-pro-conditional-logic-is' ).val( '=' );
			row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem' ).val( '' ).hide();
			row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem:first' ).show();
			row_copy.find( '.wp-easycart-pro-conditional-logic-remove-row' ).show();
			jQuery( '#wp-easycart-pro-conditional-logic-rules-list' ).append( row_copy );
			return false;
		} );
		jQuery( '#wp-easycart-pro-conditional-logic' ).on( 'click', '.wp-easycart-pro-conditional-logic-remove-row', function() {
			jQuery( this ).parent().remove();
			return false;
		} );
		jQuery( '#wp-easycart-pro-conditional-logic' ).on( 'change', '.wp-easycart-pro-conditional-logic-option', function() {
			var val = jQuery( this ).val();
			jQuery( this ).parent().find( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
			jQuery( this ).parent().find( '.wp-easycart-pro-conditional-logic-optionitem-' + val ).show();
		} );
		jQuery( '#wpeasycart_product_options_pro' ).on( 'click', '.wp-easycart-pro-variant-tracking-disabled', function() {
			if ( '2' == jQuery( document.getElementById( 'stock_quantity_type' ) ).val() ) {
				return wp_easycart_pro_enable_variation_tracking( jQuery( this ).parent().parent().attr( 'data-optionitem-quantity-id' ) );
			} else {
				jQuery( '#wp-easycart-pro-change-tracking' ).show();
			}
		} );
		jQuery( document.getElementById( 'stock_quantity_type' ) ).on( 'change', function() {
			jQuery( '#wp-easycart-pro-quantity-tracking-type' ).val( jQuery( document.getElementById( 'stock_quantity_type' ) ).val() );
		} );
		jQuery( '#wpeasycart_product_variants' ).on( 'click', '.wp-easycart-pro-option-table-paging-button', function() {
			jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
			var data = {
				action: 'ec_admin_ajax_pro_get_variant_page',
				product_id: ec_admin_get_value( 'product_id', 'hidden' ),
				page_num: Number( jQuery( this ).attr( 'data-page' ) ),
				option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
				option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
				option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
				option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
				option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
				is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
			};

			jQuery.ajax(
				{
					url: wpeasycart_admin_ajax_object.ajax_url,
					type: 'post',
					data: data,
					success: function( data ) {
						var json = JSON.parse( data );
						jQuery( '#wp-easycart-pro-variants' ).html( json.variant_html );
						jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).hide();
					}
				}
			);
		} );
		jQuery( '#wp-easycart-pro-variants' ).on( 'change', '.wpec_pro_variant_filter', function() {
			jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
			var data = {
				action: 'ec_admin_ajax_pro_get_variant_page',
				product_id: ec_admin_get_value( 'product_id', 'hidden' ),
				page_num: 1,
				option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
				option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
				option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
				option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
				option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
				is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
			};

			jQuery.ajax(
				{
					url: wpeasycart_admin_ajax_object.ajax_url,
					type: 'post',
					data: data,
					success: function( data ) {
						var json = JSON.parse( data );
						jQuery( '#wp-easycart-pro-variants' ).html( json.variant_html );
						jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).hide();
					}
				}
			);
		} );
	}
} );

function wp_easycart_pro_update_tracking() {
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( 'stock_quantity_type' ) ).val( jQuery( '#wp-easycart-pro-quantity-tracking-type' ).val() );
	ec_admin_product_details_quantity_type_change( jQuery( document.getElementById( 'stock_quantity_type' ) ) );
	jQuery( '#wp-easycart-pro-change-tracking' ).hide();
	return false;
}

function wp_easycart_pro_close_add_change_tracking() {
	jQuery( '#wp-easycart-pro-change-tracking' ).hide();
	return false;
}

function wp_easycart_pro_add_new_basic_option_insert( option_id ) {
	console.log( 'adding option ' + option_id );
	if ( jQuery( '#option1' ).val() == 0 ) {
		jQuery( '#option1' ).val( option_id );
	} else if ( jQuery( '#option2' ).val() == 0 ) {
		jQuery( '#option2' ).val( option_id );
	} else if ( jQuery( '#option3' ).val() == 0 ) {
		jQuery( '#option3' ).val( option_id );
	} else if ( jQuery( '#option4' ).val() == 0 ) {
		jQuery( '#option4' ).val( option_id );
	} else if ( jQuery( '#option5' ).val() == 0 ) {
		jQuery( '#option5' ).val( option_id );
	}
	wp_easycart_pro_refresh_options();
}

function wp_easycart_pro_add_new_advanced_option_insert( option_id ) {
	var data = {
		action: 'ec_admin_ajax_pro_add_advanced_option',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option_id: option_id,
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				wp_easycart_pro_refresh_options_handle( data );
			}
		}
	);
}

function wp_easycart_pro_add_basic_option() {
	if( jQuery( '#option5' ).val() != '0' ) {
		alert( wp_easycart_pro_admin_products_language['max-5-options'] );
	} else {
		jQuery( '#wp-easycart-pro-new-basic-option' ).removeClass( 'error' );
		jQuery( '#wp-easycart-pro-new-option' ).show();
		jQuery( '#wp-easycart-pro-basic-option-modal-loader' ).fadeIn( 'fast' );
		var data = {
			action: 'ec_admin_ajax_pro_get_option_sets',
		};

		jQuery.ajax(
			{
				url: wpeasycart_admin_ajax_object.ajax_url,
				type: 'post',
				data: data,
				success: function( data ) {
					jQuery( '#wp-easycart-pro-new-basic-option > option' ).remove();
					var json_response = JSON.parse( data );
					for ( var i = 0; i < json_response.length; i++ ) {
						jQuery( '#wp-easycart-pro-new-basic-option' ).append( '<option value="' + json_response[i].option_id + '">' + json_response[i].option_label + '</option>' );
					}
					jQuery( '#wp-easycart-pro-basic-option-modal-loader' ).hide();
				}
			}
		);
	}
	return false;
}

function wp_easycart_pro_close_add_basic_option() {
	jQuery( '#wp-easycart-pro-new-option' ).hide();
	return false;
}

function wp_easycart_pro_new_basic_option() {
	jQuery( '#wp-easycart-pro-new-option' ).hide();
	ec_admin_open_new_basic_option();
	return false;
}

function wp_easycart_use_advanced_optionset_pro( ele ) {
	jQuery( '#ec_admin_product_details_images_pro_loader' ).show();
	var data = {
		action: 'ec_admin_ajax_pro_update_advanced_optionset',
		use_advanced_optionset: ( jQuery( '#use_advanced_optionset' ).is( ':checked' ) ) ? 1 : 0,
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
	};
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function() {
				ec_admin_product_details_refresh_option_images();
			},
		}
	);
	return false;
}

function wp_easycart_pro_enable_variation( optionitemquantity_id ) {
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	jQuery( '#wpec_variant_row_' + optionitemquantity_id ).removeClass( 'is-disabled' );
	jQuery( '#wpec_variant_enable_' + optionitemquantity_id ).hide();
	jQuery( '#wpec_variant_disable_' + optionitemquantity_id ).show();
	var data = {
		action: 'ec_admin_ajax_pro_enable_variation',
		optionitemquantity_id: optionitemquantity_id,
		page_num: ( ( jQuery( '.wp-easycart-pro-option-table-paging-button' ).length ) ? Number( jQuery( '#wpeasycart_option_table_current_page' ).html() ) : 1 ),
		option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
		option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
		option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
		option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
		option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
		is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
	};
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				var json = JSON.parse( data );
				jQuery( '#wp-easycart-pro-variants' ).html( json.variant_html );
				jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).hide();
			}
		}
	);
	return false;
}

function wp_easycart_pro_disable_variation( optionitemquantity_id ) {
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	jQuery( '#wpec_variant_row_' + optionitemquantity_id ).addClass( 'is-disabled' );
	jQuery( '#wpec_variant_enable_' + optionitemquantity_id ).show();
	jQuery( '#wpec_variant_disable_' + optionitemquantity_id ).hide();
	var data = {
		action: 'ec_admin_ajax_pro_disable_variation',
		optionitemquantity_id: optionitemquantity_id,
		page_num: ( ( jQuery( '.wp-easycart-pro-option-table-paging-button' ).length ) ? Number( jQuery( '#wpeasycart_option_table_current_page' ).html() ) : 1 ),
		option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
		option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
		option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
		option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
		option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
		is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
	};
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				var json = JSON.parse( data );
				jQuery( '#wp-easycart-pro-variants' ).html( json.variant_html );
				jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).hide();
			}
		}
	);
	return false;
}

function wp_easycart_pro_enable_variation_tracking( optionitemquantity_id ) {
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_pro_enable_variation_tracking',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: optionitemquantity_id,
		page_num: ( ( jQuery( '.wp-easycart-pro-option-table-paging-button' ).length ) ? Number( jQuery( '#wpeasycart_option_table_current_page' ).html() ) : 1 ),
		option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
		option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
		option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
		option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
		option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
		is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
	};
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ) {
				wp_easycart_pro_refresh_options_handle( response );
			}
		}
	);
	return false;
}

function wp_easycart_pro_disable_variation_tracking( optionitemquantity_id ) {
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_pro_disable_variation_tracking',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: optionitemquantity_id,
		page_num: ( ( jQuery( '.wp-easycart-pro-option-table-paging-button' ).length ) ? Number( jQuery( '#wpeasycart_option_table_current_page' ).html() ) : 1 ),
		option_item_id_1: ( ( jQuery( '#wpec_variant_filter_1' ).length ) ? Number( jQuery( '#wpec_variant_filter_1' ).val() ) : 0 ),
		option_item_id_2: ( ( jQuery( '#wpec_variant_filter_2' ).length ) ? Number( jQuery( '#wpec_variant_filter_2' ).val() ) : 0 ),
		option_item_id_3: ( ( jQuery( '#wpec_variant_filter_3' ).length ) ? Number( jQuery( '#wpec_variant_filter_3' ).val() ) : 0 ),
		option_item_id_4: ( ( jQuery( '#wpec_variant_filter_4' ).length ) ? Number( jQuery( '#wpec_variant_filter_4' ).val() ) : 0 ),
		option_item_id_5: ( ( jQuery( '#wpec_variant_filter_5' ).length ) ? Number( jQuery( '#wpec_variant_filter_5' ).val() ) : 0 ),
		is_enabled: ( ( jQuery( '#wpec_variant_filter_enabled' ).length ) ? jQuery( '#wpec_variant_filter_enabled' ).val() : 'all' ),
	};
	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ) {
				wp_easycart_pro_refresh_options_handle( response );
			}
		}
	);
	return false;
}

function wp_easycart_pro_add_new_basic_option() {
	var new_option = jQuery( '#wp-easycart-pro-new-basic-option' ).val();
	if ( new_option == '' ) {
		jQuery( '#wp-easycart-pro-new-basic-option' ).addClass( 'error' );
		return false;
	} else if( jQuery( '#option1' ).val() != '0' && ! confirm( wp_easycart_pro_admin_products_language['confirm-option-change'] ) ) {
		return false;
	}
	jQuery( '#wp-easycart-pro-new-basic-option' ).removeClass( 'error' );
	
	if ( jQuery( '#option1' ).val() == 0 ) {
		jQuery( '#option1' ).val( new_option );
	} else if ( jQuery( '#option2' ).val() == 0 ) {
		jQuery( '#option2' ).val( new_option );
	} else if ( jQuery( '#option3' ).val() == 0 ) {
		jQuery( '#option3' ).val( new_option );
	} else if ( jQuery( '#option4' ).val() == 0 ) {
		jQuery( '#option4' ).val( new_option );
	} else if ( jQuery( '#option5' ).val() == 0 ) {
		jQuery( '#option5' ).val( new_option );
	} else {
		// show an error
	}

	wp_easycart_pro_refresh_options();
}

function wp_easycart_pro_get_updated_options() {
	jQuery( document.getElementById( 'wp-easycart-pro-basic-option-modal-loader' ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_get_pro_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				wp_easycart_pro_refresh_options_handle( data );
			}
		}
	);
}

function wp_easycart_pro_refresh_options() {
	jQuery( document.getElementById( 'wp-easycart-pro-basic-option-modal-loader' ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_pro_basic_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'hidden' ),
		option2: ec_admin_get_value( 'option2', 'hidden' ),
		option3: ec_admin_get_value( 'option3', 'hidden' ),
		option4: ec_admin_get_value( 'option4', 'hidden' ),
		option5: ec_admin_get_value( 'option5', 'hidden' ),
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				wp_easycart_pro_refresh_options_handle( data );
			}
		}
	);
}

function wp_easycart_pro_refresh_options_handle( data ) {
	var json = JSON.parse( data );
	// Update option combo
	jQuery( '#wp-easycart-pro-basic-options' ).html( json.option_html );
	jQuery( '#wp-easycart-pro-variants' ).html( json.variant_html );
	jQuery( '#wp-easycart-pro-modifiers' ).html( json.modifier_html );
	jQuery( document.getElementById( 'wp-easycart-pro-basic-option-modal-loader' ) ).hide();
	jQuery( document.getElementById( 'wp-easycart-pro-option-loader' ) ).hide();
	jQuery( document.getElementById( 'wp-easycart-pro-modifier-loader' ) ).hide();
	jQuery( document.getElementById( 'wp-easycart-pro-advanced-option-modal-loader' ) ).hide();
	jQuery( '#wp-easycart-pro-new-basic-option' ).val( '' );
	jQuery( '#wp-easycart-pro-new-advanced-option' ).val( '' );
	jQuery( '#wp-easycart-pro-new-option' ).hide();
	jQuery( '#wp-easycart-pro-new-modifier' ).hide();
	if( json.variant_list.length > 0 ) {
		jQuery( '#wpeasycart_product_variants' ).show();
		jQuery( '#wpeasycart_product_variants_none' ).hide();
	} else {
		jQuery( '#wpeasycart_product_variants' ).hide();
		jQuery( '#wpeasycart_product_variants_none' ).show();
	}
}

function wp_easycart_pro_remove_option( option_num ) {
	if( ! confirm( wp_easycart_pro_admin_products_language['confirm-option-change'] ) ) {
		return false;
	}
	
	var option2 = jQuery( '#option2' ).val();
	var option3 = jQuery( '#option3' ).val();
	var option4 = jQuery( '#option4' ).val();
	var option5 = jQuery( '#option5' ).val();
	if ( option_num < 5 ) {
		jQuery( '#option4' ).val( option5 );
	}
	if ( option_num < 4 ) {
		jQuery( '#option3' ).val( option4 );
	}
	if ( option_num < 3 ) {
		jQuery( '#option2' ).val( option3 );
	}
	if ( option_num < 2 ) {
		jQuery( '#option1' ).val( option2 );
	}
	jQuery( '#option5' ).val( '0' );
	wp_easycart_pro_refresh_options();
	if ( jQuery( '#option1' ).val() == '0' ) {
		jQuery( '#wpeasycart_product_variants' ).hide();
		jQuery( '#wpeasycart_product_variants_none' ).show();
	} else {
		jQuery( '#wpeasycart_product_variants' ).show();
		jQuery( '#wpeasycart_product_variants_none' ).hide();
	}
	return false;
}

function wp_easycart_pro_add_advanced_option() {
	jQuery( '#wp-easycart-pro-new-modifier' ).show();
	jQuery( '#wp-easycart-pro-advanced-option-modal-loader' ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_pro_get_modifiers',
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				jQuery( '#wp-easycart-pro-new-advanced-option > option' ).remove();
				var json_response = JSON.parse( data );
				for ( var i = 0; i < json_response.length; i++ ) {
					jQuery( '#wp-easycart-pro-new-advanced-option' ).append( '<option value="' + json_response[i].option_id + '">' + json_response[i].option_label + '</option>' );
				}
				jQuery( '#wp-easycart-pro-advanced-option-modal-loader' ).hide();
			}
		}
	);
	return false;
}

function wp_easycart_pro_close_add_advanced_option() {
	jQuery( '#wp-easycart-pro-new-modifier' ).hide();
	return false;
}

function wp_easycart_pro_new_advanced_option() {
	jQuery( '#wp-easycart-pro-new-modifier' ).hide();
	ec_admin_open_new_advanced_option();
	return false;
}

function wp_easycart_pro_add_new_advanced_option() {
	var new_option = jQuery( '#wp-easycart-pro-new-advanced-option' ).val();
	if ( new_option == '' ) {
		// show an error
		return false;
	}
	
	jQuery( document.getElementById( 'wp-easycart-pro-advanced-option-modal-loader' ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( 'wp-easycart-pro-modifier-loader' ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_pro_add_advanced_option',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option_id: new_option,
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				wp_easycart_pro_refresh_options_handle( data );
			}
		}
	);
}

function wp_easycart_pro_remove_modifier( option_to_product_id ) {
	jQuery( '#wpec_modifier_row_' + option_to_product_id ).remove();
	
	var data = {
		action: 'ec_admin_ajax_pro_remove_advanced_option',
		option_to_product_id: option_to_product_id,
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
		}
	);
	return false;
}

function wp_easycart_pro_modifier_conditional_logic( option_to_product_id ) {
	var enabled = false;
	if ( jQuery( '#wpec_modifier_' + option_to_product_id ).is( ':checked' ) ) {
		jQuery( '#wpec_edit_conditional_logic_' + option_to_product_id ).removeClass( 'is-disabled' );
		enabled = true;
	} else {
		jQuery( '#wpec_edit_conditional_logic_' + option_to_product_id ).addClass( 'is-disabled' );
	}
	var data = {
		action: 'ec_admin_ajax_pro_enable_disable_product_option_logic',
		option_to_product_id: option_to_product_id,
		enabled: enabled
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
		}
	);
}

function wp_easycart_pro_edit_conditional_logic( option_to_product_id ) {
	jQuery( '.wp-easycart-pro-option-conditional-logic-product > span' ).html( jQuery( '#wpec_modifier_row_' + option_to_product_id ).find( '.wp-easycart-pro-option-table-modifier-label' ).html() );
	jQuery( '#wp-easycart-pro-conditional-logic-option-id' ).val( option_to_product_id );
	if ( jQuery( '#wpec_modifier_' + option_to_product_id ).is( ':checked' ) ) {
		jQuery( '#wp-easycart-pro-conditional-logic-enabled' ).val( 1 )
	} else {
		jQuery( '#wp-easycart-pro-conditional-logic-enabled' ).val( 0 )
	}
	jQuery( '#wp-easycart-pro-conditional-logic' ).show();
	jQuery( document.getElementById( 'wp-easycart-pro-conditional-logic-loader' ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_pro_get_product_option_logic',
		option_to_product_id: option_to_product_id,
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				jQuery( document.getElementById( 'wp-easycart-pro-conditional-logic-loader' ) ).hide();
				var full_json_response = JSON.parse( data );
				var json_response = full_json_response.conditional_logic;
				jQuery( '.wp-easycart-pro-conditional-logic-option > option' ).remove();
				for ( var j=0; j<full_json_response.available_options.length; j++ ) {
					jQuery( '.wp-easycart-pro-conditional-logic-option' ).append( '<option value="' + full_json_response.available_options[j].option_to_product_id + '">' + full_json_response.available_options[j].option_label + '</option>' );
					if ( '' != full_json_response.available_options[j].option_to_product_id && jQuery( '.wp-easycart-pro-conditional-logic-optionitem-' + full_json_response.available_options[j].option_to_product_id ).length == 0 ) {
						jQuery( '.wp-easycart-pro-conditional-logic-optionitem' ).last().after( full_json_response.available_options[j].optionset_html );
					}
				}
				if ( json_response.show_field ) {
					jQuery( '#wp-easycart-pro-conditional-logic-type' ).val( 'show' );
				} else {
					jQuery( '#wp-easycart-pro-conditional-logic-type' ).val( 'hide' );
				}
				if ( 'OR' == json_response.and_rules ) {
					jQuery( '#wp-easycart-pro-conditional-logic-method' ).val( 'OR' );
				} else {
					jQuery( '#wp-easycart-pro-conditional-logic-method' ).val( 'AND' );
				}
				jQuery( '.wp-easycart-pro-conditional-logic-container:not(:first)' ).remove();
				if ( ! json_response.rules || json_response.rules.length <= 0 ) {
					jQuery( '.wp-easycart-pro-conditional-logic-option' ).val( '' );
					jQuery( '.wp-easycart-pro-conditional-logic-is' ).val( '=' );
					jQuery( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
					jQuery( '.wp-easycart-pro-conditional-logic-optionitem:first' ).show();
				} else {
					for ( var i=0; i<json_response.rules.length; i++ ) {
						if ( 0 == i ) {
							if ( jQuery( '.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).length ) {
								jQuery( '.wp-easycart-pro-conditional-logic-option' ).val( json_response.rules[i].option_id );
								jQuery( '.wp-easycart-pro-conditional-logic-is' ).val( json_response.rules[i].operator );
								jQuery( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
								jQuery( '.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).show();
								jQuery( 'select.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).val( json_response.rules[i].optionitem_id );
								jQuery( 'input.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).val( json_response.rules[i].optionitem_value );
							} else {
								jQuery( '.wp-easycart-pro-conditional-logic-option' ).val( '' );
								jQuery( '.wp-easycart-pro-conditional-logic-is' ).val( '=' );
								jQuery( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
								jQuery( '.wp-easycart-pro-conditional-logic-optionitem:first' ).show();
							}
						} else {
							var row_copy = jQuery( '.wp-easycart-pro-conditional-logic-container:first' ).clone();
							if ( row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).length ) {
								row_copy.find( '.wp-easycart-pro-conditional-logic-option' ).val( json_response.rules[i].option_id );
								row_copy.find( '.wp-easycart-pro-conditional-logic-is' ).val( json_response.rules[i].operator );
								row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
								row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).show();
								row_copy.find( 'select.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).val( json_response.rules[i].optionitem_id );
								row_copy.find( 'input.wp-easycart-pro-conditional-logic-optionitem-' + json_response.rules[i].option_id ).val( json_response.rules[i].optionitem_value );
							} else {
								row_copy.find( '.wp-easycart-pro-conditional-logic-option' ).val( '' );
								row_copy.find( '.wp-easycart-pro-conditional-logic-is' ).val( '=' );
								row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem' ).hide();
								row_copy.find( '.wp-easycart-pro-conditional-logic-optionitem:first' ).show();
							}
							row_copy.find( '.wp-easycart-pro-conditional-logic-remove-row' ).show();
							jQuery( '#wp-easycart-pro-conditional-logic-rules-list' ).append( row_copy );
						}
					}
				}
			}
		}
	);
	
	return false;
}

function wp_easycart_pro_close_conditional_logic() {
	jQuery( '#wp-easycart-pro-conditional-logic' ).hide();
	return false;
}

function wp_easycart_pro_update_conditional_logic() {
	jQuery( document.getElementById( 'wp-easycart-pro-conditional-logic-loader' ) ).fadeIn( 'fast' );
	
	var option_id = jQuery( '#wp-easycart-pro-conditional-logic-option-id' ).val();
	var enabled = ( '1' == jQuery( '#wp-easycart-pro-conditional-logic-enabled' ).val() ) ? true : false;
	var show_field = ( 'show' == jQuery( '#wp-easycart-pro-conditional-logic-type' ).val() ) ? true : false;
	var and_rules = jQuery( '#wp-easycart-pro-conditional-logic-method' ).val();
	var rules = [];
	jQuery( '.wp-easycart-pro-conditional-logic-container' ).each( function() {
		var row_option_id = jQuery( this ).find( '.wp-easycart-pro-conditional-logic-option' ).val();
		var row_optionitem_id = ( jQuery( this ).find( 'select.wp-easycart-pro-conditional-logic-optionitem-' + row_option_id ).length ) ? jQuery( this ).find( '.wp-easycart-pro-conditional-logic-optionitem-' + row_option_id ).val() : '';
		var row_optionitem_value = ( jQuery( this ).find( 'input.wp-easycart-pro-conditional-logic-optionitem-' + row_option_id ).length ) ? jQuery( this ).find( '.wp-easycart-pro-conditional-logic-optionitem-' + row_option_id ).val() : '';
		rules.push( {
			option_id: row_option_id,
			operator: jQuery( this ).find( '.wp-easycart-pro-conditional-logic-is' ).val(),
			optionitem_id: row_optionitem_id,
			optionitem_value: row_optionitem_value,
		} );
	} );
	
	var data = {
		action: 'ec_admin_ajax_pro_save_product_option_logic',
		conditional_logic: JSON.stringify( {
			enabled: enabled,
			show_field: show_field,
			and_rules: and_rules,
			rules: rules,
		} ),
		option_to_product_id: option_id,
	};

	jQuery.ajax(
		{
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				jQuery( '#wp-easycart-pro-conditional-logic' ).hide();
				jQuery( document.getElementById( 'wp-easycart-pro-conditional-logic-loader' ) ).hide();
			}
		}
	);
}

function ec_admin_product_details_images_pro_list_change( ele ) {
	var data = {
		action: 'ec_admin_ajax_save_product_details_images_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitem_id: ec_admin_get_value( 'ec_optionitem_images_options', 'text' ),
		use_optionitem_images: ec_admin_get_value( 'use_optionitem_images_pro', 'checkbox' ),
		images: jQuery( '#' + ele ).val()
	};
	jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
}

function ec_admin_save_product_details_googlemerchant_pro( ){
	jQuery( document.getElementById( "ec_admin_product_details_google_merchant_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_google_merchant_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		enabled: ec_admin_get_value( 'gm_enabled', 'text' ),
		title: ec_admin_get_value( 'gm_title', 'text' ),
		google_product_category: ec_admin_get_value( 'gm_google_product_category', 'text' ),
		product_type: ec_admin_get_value( 'gm_product_type', 'text' ),
		identifier_exists: ec_admin_get_value( 'gm_identifier_exists', 'text' ),
		gtin: ec_admin_get_value( 'gm_gtin', 'text' ),
		mpn: ec_admin_get_value( 'gm_mpn', 'text' ),
		availability: ec_admin_get_value( 'gm_availability', 'text' ),
		condition: ec_admin_get_value( 'gm_condition', 'text' ),
		availability_date: ec_admin_get_value( 'gm_availability_date', 'text' ),
		expiration_date: ec_admin_get_value( 'gm_expiration_date', 'text' ),
		gender: ec_admin_get_value( 'gm_gender', 'text' ),
		age_group: ec_admin_get_value( 'gm_age_group', 'text' ),
		size_type: ec_admin_get_value( 'gm_size_type', 'text' ),
		size_system: ec_admin_get_value( 'gm_size_system', 'text' ),
		item_group_id: ec_admin_get_value( 'gm_item_group_id', 'text' ),
		color: ec_admin_get_value( 'gm_color', 'text' ),
		material: ec_admin_get_value( 'gm_material', 'text' ),
		pattern: ec_admin_get_value( 'gm_pattern', 'text' ),
		size: ec_admin_get_value( 'gm_size', 'text' ),
		weight_type: ec_admin_get_value( 'gm_weight_type', 'text' ),
		shipping_weight: ec_admin_get_value( 'gm_shipping_weight', 'text' ),
		unit_pricing_base_measure: ec_admin_get_value( 'gm_unit_pricing_base_measure', 'text' ),
		unit_pricing_measure: ec_admin_get_value( 'gm_unit_pricing_measure', 'text' ),
		shipping_label: ec_admin_get_value( 'gm_shipping_label', 'text' ),
		shipping_unit: ec_admin_get_value( 'gm_shipping_unit', 'text' ),
		shipping_length: ec_admin_get_value( 'gm_shipping_length', 'text' ),
		shipping_width: ec_admin_get_value( 'gm_shipping_width', 'text' ),
		shipping_height: ec_admin_get_value( 'gm_shipping_height', 'text' ),
		min_handling_time: ec_admin_get_value( 'gm_min_handling_time', 'text' ),
		max_handling_time: ec_admin_get_value( 'gm_max_handling_time', 'text' ),
		adult: ec_admin_get_value( 'gm_adult', 'text' ),
		multipack: ec_admin_get_value( 'gm_multipack', 'text' ),
		is_bundle: ec_admin_get_value( 'gm_is_bundle', 'text' ),
		certification: ec_admin_get_value( 'gm_certification', 'text' ),
		certification_code: ec_admin_get_value( 'gm_certification_code', 'text' ),
		energy_efficiency_class: ec_admin_get_value( 'gm_energy_efficiency_class', 'text' ),
		min_energy_efficiency_class: ec_admin_get_value( 'gm_min_energy_efficiency_class', 'text' ),
		max_energy_efficiency_class: ec_admin_get_value( 'gm_max_energy_efficiency_class', 'text' ),
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_google_merchant_loader' );
	} } );

	return false;
}

function wp_easycart_optionitem_images_pro() {
	if ( jQuery( '#use_optionitem_images_pro' ).is( ':checked' ) ) {
		jQuery( '#wp_easycart_gallery_optionset' ).show();
		jQuery( '#wpeasycart-pro-image-set-importer' ).show();
		wp_easycart_optionitem_images_change_pro();
	} else {
		jQuery( '#wp_easycart_gallery_optionset' ).hide();
		jQuery( '#wpeasycart-pro-image-set-importer' ).hide();
		jQuery( '.ec_admin_product_details_optiontiem_images_group' ).hide();
		jQuery( '#optionitem_images_basic' ).show();
	}
	var data = {
		action: 'ec_admin_ajax_save_product_details_is_optionitem_images_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_optionitem_images: ec_admin_get_value( 'use_optionitem_images_pro', 'checkbox' )
	};
	jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
}

function wp_easycart_optionitem_images_change_pro() {
	var selected_optionitem = jQuery( '#ec_optionitem_images_options' ).val();
	jQuery( '.ec_admin_product_details_optiontiem_images_group' ).hide();
	jQuery( '#optionitem_images_' + selected_optionitem ).show();
}

function ec_admin_product_details_location_pickup_change() {
	var data = {
		action: 'ec_admin_ajax_save_product_details_location_pickup_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		pickup_locations: jQuery( '#pickup_locations' ).val() || [],
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};
	jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
}

function ec_admin_product_image_menu_open( field ) {
	jQuery( '#' + field ).show();
}

function ec_admin_product_image_menu_close( field ) {
	jQuery( '#' + field ).hide();
}

function ec_admin_product_image_url_open( field ) {
	jQuery( '#' + field ).show();
}

function ec_admin_product_image_url_close( field ) {
	jQuery( '#' + field ).hide();
}

function ec_admin_product_video_url_open( field ) {
	jQuery( '#' + field ).show();
}

function ec_admin_product_video_url_close( field ) {
	jQuery( '#' + field ).hide();
}

function ec_admin_product_youtube_url_open( field ) {
	jQuery( '#' + field ).show();
}

function ec_admin_product_youtube_url_close( field ) {
	jQuery( '#' + field ).hide();
}

function ec_admin_product_vimeo_url_open( field ) {
	jQuery( '#' + field ).show();
}

function ec_admin_product_vimeo_url_close( field ) {
	jQuery( '#' + field ).hide();
}

function ec_admin_product_image_url_add( add_field, gallery_field, optionitem_id ) {
	var attachment_ids = jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( );
	var image_url = jQuery( '#' + add_field ).val();
	attachment_ids = attachment_ids ? attachment_ids + ',image:' + image_url : 'image:' + image_url;
	jQuery( document.getElementById( gallery_field ) ).append(
		'<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="image:' + image_url + '"><img src="' + image_url +
		'" /><ul class="actions"><li><a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a></li></ul></div>'
	);
	jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( attachment_ids ).trigger( 'change' );
	jQuery( '#' + add_field ).val( '' );
}

function ec_admin_product_video_url_add( add_field, thumb_field, gallery_field, optionitem_id ) {
	var attachment_ids = jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( );
	var video_url = jQuery( '#' + add_field ).val();
	var video_thumb_url = jQuery( '#' + thumb_field ).val();
	attachment_ids = attachment_ids ? attachment_ids + ',video:' + video_url + ':::' + video_thumb_url : 'video:' + video_url + ':::' + video_thumb_url;
	jQuery( document.getElementById( gallery_field ) ).append(
		'<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="video:' + video_url + ':::' + video_thumb_url + '"><div class="ec_admin_product_image_video_cover"></div><img src="' + video_thumb_url + '" /><a class="ec_admin_product_image_video_button" href="' + video_url + '" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a><ul class="actions"><li><a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a></li></ul></div>'
		
		
	);
	jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( attachment_ids ).trigger( 'change' );
	jQuery( '#' + add_field ).val( '' );
	jQuery( '#' + thumb_field ).val( '' );
}

function ec_admin_product_youtube_url_add( add_field, thumb_field, gallery_field, optionitem_id ) {
	var attachment_ids = jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( );
	var youtube_url = jQuery( '#' + add_field ).val();
	var youtube_thumb_url = jQuery( '#' + thumb_field ).val();
	attachment_ids = attachment_ids ? attachment_ids + ',youtube:' + youtube_url + ':::' + youtube_thumb_url : 'youtube:' + youtube_url + ':::' + youtube_thumb_url;
	jQuery( document.getElementById( gallery_field ) ).append(
		'<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="youtube:' + youtube_url + ':::' + youtube_thumb_url + '"><div class="ec_admin_product_image_video_cover"></div><img src="' + youtube_thumb_url + '" /><a class="ec_admin_product_image_video_button" href="' + youtube_url + '" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a><ul class="actions"><li><a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a></li></ul></div>'
	);
	jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( attachment_ids ).trigger( 'change' );
	jQuery( '#' + add_field ).val( '' );
	jQuery( '#' + thumb_field ).val( '' );
}

function ec_admin_product_vimeo_url_add( add_field, thumb_field, gallery_field, optionitem_id ) {
	var attachment_ids = jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( );
	var vimeo_url = jQuery( '#' + add_field ).val();
	var vimeo_thumb_url = jQuery( '#' + thumb_field ).val();
	attachment_ids = attachment_ids ? attachment_ids + ',vimeo:' + vimeo_url + ':::' + vimeo_thumb_url : 'vimeo:' + vimeo_url + ':::' + vimeo_thumb_url;
	jQuery( document.getElementById( gallery_field ) ).append(
		'<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="vimeo:' + vimeo_url + ':::' + vimeo_thumb_url + '"><div class="ec_admin_product_image_video_cover"></div><img src="' + vimeo_thumb_url + '" /><a class="ec_admin_product_image_video_button" href="' + vimeo_url + '" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a><ul class="actions"><li><a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a></li></ul></div>'
	);
	jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( attachment_ids ).trigger( 'change' );
	jQuery( '#' + add_field ).val( '' );
	jQuery( '#' + thumb_field ).val( '' );
}

function ec_admin_image_video_thumb( field ) {
	var gallery_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id = jQuery( document.getElementById( 'product_post_id' ) ).val( );

	if( gallery_frame ){
		gallery_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		gallery_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	gallery_frame = wp.media.frames.product_gallery = wp.media( {
		title: 'Select Image',
		button:{
			text: 'Use Image',
		},
		states: [
			new wp.media.controller.Library({
				title: 'Select Your Images',
				filterable: 'all',
				multiple: false
			})
		]
	});
	
	gallery_frame.on( 'select', function( ){
		var selection = gallery_frame.state( ).get( 'selection' );
		selection.map( function( attachment ) {
			var attachment_image = attachment.attributes && attachment.attributes.sizes && attachment.attributes.sizes.large ? attachment.attributes.sizes.large.url : attachment.attributes.url;
			jQuery( '#' + field ).val( attachment_image );
		} );
	} );
	gallery_frame.open( );
	return false;
}

function ec_admin_image_gallery( field, optionitem_id ){
	var gallery_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id = jQuery( document.getElementById( 'product_post_id' ) ).val( );

	if( gallery_frame ){
		gallery_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		gallery_frame.uploader.uploader.param( 'optionitem_id', optionitem_id );
		gallery_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
		wp.media.model.settings.post.optionitem_id = optionitem_id;
	}
	
	gallery_frame = wp.media.frames.product_gallery = wp.media( {
		title: 'Select Image',
		button:{
			text: 'Use Image',
		},
		states: [
			new wp.media.controller.Library({
				title: 'Select Your Images',
				filterable: 'all',
				multiple: true
			})
		]
	});
	
	gallery_frame.on( 'select', function( ){
		var selection = gallery_frame.state( ).get( 'selection' );
		var attachment_ids = jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( );
		selection.map( function( attachment ) {
			attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.attributes.id : attachment.attributes.id;
			var attachment_image = attachment.attributes && attachment.attributes.sizes && attachment.attributes.sizes.large ? attachment.attributes.sizes.large.url : attachment.attributes.url;
			jQuery( document.getElementById( field ) ).append(
				'<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image +
				'" /><ul class="actions"><li><a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a></li></ul></div>'
			);
		} );
		jQuery( document.getElementById( 'wpeasycart_admin_product_gallery_ids_' + optionitem_id ) ).val( attachment_ids ).trigger( 'change' );
	} );
	gallery_frame.open( );
}

function wpeasycart_admin_product_remove_image( ele ){
	var parent_ele = jQuery( ele ).parent( ).parent( ).parent( ).parent( );
	var image_list_ele = jQuery( ele ).parent( ).parent( ).parent( ).parent( ).parent( ).parent( ).find( 'input[type="hidden"]' );
	jQuery( ele ).parent( ).parent( ).parent( ).remove( );
	var image_list = '';
	parent_ele.find( '.ec_admin_product_image' ).each( function( ){
		if( jQuery( this ).attr( 'data-attachment_id' ) != '-1' )
			image_list = ( image_list == '' ) ? jQuery( this ).attr( 'data-attachment_id' ) : image_list + ',' + jQuery( this ).attr( 'data-attachment_id' );
	} );
	jQuery( image_list_ele ).val( image_list ).trigger( 'change' );
}

function wp_easycart_pro_product_square_sync( product_id ) {
	jQuery( document.getElementById( "ec_admin_product_details_basic_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_square_sync_product',
		product_id: product_id
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		location.reload(true);
	} } );

	return false;
}

function wp_easycart_pro_open_google_merchant_variant( optionitemquantity_id ) {
	jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant' ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( "wp-easycart-pro-variant-google-merchant-loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_get_product_details_variant_google_merchant_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: optionitemquantity_id,
	};

	jQuery.ajax( {
		url: wpeasycart_admin_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( result_data ){
			var result = JSON.parse( result_data );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-optionitemquantity-id' ) ).val( result.optionitemquantity_id );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-enabled' ) ).val( result.enabled );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-title' ) ).val( result.title );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-availability' ) ).val( result.availability );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-color' ) ).val( result.color );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-pattern' ) ).val( result.pattern );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-material' ) ).val( result.material );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-age-group' ) ).val( result.age_group );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-gender' ) ).val( result.gender );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-size' ) ).val( result.size );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-mpn' ) ).val( result.mpn );
			jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-gtin' ) ).val( result.gtin );
			jQuery( document.getElementById( "wp-easycart-pro-variant-google-merchant-loader" ) ).fadeOut( 'fast' );
		}
	} );
	
	return false;
}

function wp_easycart_pro_close_variant_google_merchant() {
	jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant' ) ).fadeOut( 'fast' );
	return false;
}

function wp_easycart_pro_update_variant_google_merchant() {
	jQuery( document.getElementById( "wp-easycart-pro-variant-google-merchant-loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_variant_google_merchant_pro',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-optionitemquantity-id' ) ).val(),
		enabled: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-enabled' ) ).val(),
		title: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-title' ) ).val(),
		availability: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-availability' ) ).val(),
		color: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-color' ) ).val(),
		pattern: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-pattern' ) ).val(),
		material: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-material' ) ).val(),
		age_group: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-age-group' ) ).val(),
		gender: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-gender' ) ).val(),
		size: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-size' ) ).val(),
		gtin: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-gtin' ) ).val(),
		mpn: jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant-mpn' ) ).val(),
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( document.getElementById( 'wp-easycart-pro-variant-google-merchant' ) ).fadeOut( 'fast' );
		jQuery( document.getElementById( "wp-easycart-pro-variant-google-merchant-loader" ) ).fadeOut( 'fast' );
	} } );

	return false;
}
