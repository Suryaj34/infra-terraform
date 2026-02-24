jQuery(document).ready(function($) {
	//date fields
	$(".wp-ec-datepicker").datepicker( {dateFormat:"yy-mm-dd"} );
	
	//reset details page form
	if($(".ec_admin_details_panel").length > 0) {
		var apply_to = '';
		if(document.getElementById( 'by_all_products' ).checked == true) apply_to = 'by_all_products';
		else if(document.getElementById( 'by_product_id' ).checked == true) apply_to = 'by_product_id';
		else if(document.getElementById( 'by_manufacturer_id' ).checked == true) apply_to = 'by_manufacturer_id';
		else if(document.getElementById( 'by_category_id' ).checked == true) apply_to = 'by_category_id';
		else apply_to = 'by_all_products';
		ec_admin_coupon_apply_to(apply_to);
		
		var coupon_type = '';
		if(document.getElementById( 'is_dollar_based' ).checked == true ) coupon_type = 'is_dollar_based';
		else if(document.getElementById( 'is_percentage_based' ).checked == true ) coupon_type = 'is_percentage_based';
		else if(document.getElementById( 'is_shipping_based' ).checked == true ) coupon_type = 'is_shipping_based';
		else if(document.getElementById( 'is_free_item_based' ).checked == true ) coupon_type = 'is_free_item_based';
		else if(document.getElementById( 'is_bogo_based' ).checked == true ) coupon_type = 'is_bogo_based';
		else coupon_type = 'is_dollar_based';
		ec_admin_coupon_type_change( coupon_type );
	}
});

function ec_admin_coupon_apply_to(coupon_type ){
	if( coupon_type == 'by_all_products' ){
		jQuery( document.getElementById( 'by_all_products' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'by_product_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_manufacturer_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_category_id' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_product_id' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_category_id' ) ).hide ( );
		
		document.getElementById( 'product_id' ).value = '0'; 
		document.getElementById( 'manufacturer_id' ).value = '0'; 
		document.getElementById( 'category_id' ).value = '0';
		
	} else if( coupon_type == 'by_product_id' ){
		jQuery( document.getElementById( 'by_all_products' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_product_id' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'by_manufacturer_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_category_id' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_product_id' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_category_id' ) ).hide ( );

		document.getElementById( 'manufacturer_id' ).value = '0';
		document.getElementById( 'category_id' ).value = '0';
		
	} else if( coupon_type == 'by_manufacturer_id' ){
		jQuery( document.getElementById( 'by_all_products' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_product_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_manufacturer_id' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'by_category_id' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_product_id' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id' ) ).show ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_category_id' ) ).hide ( );
		
		document.getElementById( 'product_id' ).value = '0';
		document.getElementById( 'category_id' ).value = '0';

	} else if( coupon_type == 'by_category_id' ){
		jQuery( document.getElementById( 'by_all_products' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_product_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_manufacturer_id' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'by_category_id' ) ).prop( 'checked', true );
		
		jQuery( document.getElementById( 'ec_admin_row_product_id' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_category_id' ) ).show ( );
		
		document.getElementById( 'product_id' ).value = '0'; 
		document.getElementById( 'manufacturer_id' ).value = '0';
	
	}
}

function ec_admin_coupon_type_change(coupon_type ){
	if( coupon_type == 'is_dollar_based' ){
		jQuery( document.getElementById( 'is_dollar_based' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'is_percentage_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_shipping_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_free_item_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_bogo_based' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_promo_dollar' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_shipping' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_apply_to_shipping' ) ).show ( );
		
		document.getElementById( 'promo_percentage' ).value = '0.00'; 
		document.getElementById( 'promo_shipping' ).value = '0.00';

	}else if( coupon_type == 'is_percentage_based' ){
		jQuery( document.getElementById( 'is_dollar_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_percentage_based' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'is_shipping_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_free_item_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_bogo_based' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_promo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_percentage' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_shipping' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_apply_to_shipping' ) ).show ( );
		
		document.getElementById( 'promo_dollar' ).value = '0.00';
		document.getElementById( 'promo_shipping' ).value = '0.00';

	}else if( coupon_type == 'is_shipping_based'  ){
		jQuery( document.getElementById( 'is_dollar_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_percentage_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_shipping_based' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'is_free_item_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_bogo_based' ) ).prop( 'checked', false );

		jQuery( document.getElementById( 'ec_admin_row_promo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_shipping' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_apply_to_shipping' ) ).hide ( );
		
		document.getElementById( 'promo_dollar' ).value = '0.00'; 
		document.getElementById( 'promo_percentage' ).value = '0.00'; 
		
	}else if ( coupon_type == 'is_free_item_based' ){
		jQuery( document.getElementById( 'is_dollar_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_percentage_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_shipping_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_free_item_based' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'is_bogo_based' ) ).prop( 'checked', false );
		
		jQuery( document.getElementById( 'ec_admin_row_promo_dollar' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_promo_percentage' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_promo_shipping' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_apply_to_shipping' ) ).hide ( );
		
		document.getElementById( 'promo_dollar' ).value = '0.00'; 
		document.getElementById( 'promo_percentage' ).value = '0.00'; 
		document.getElementById( 'promo_shipping' ).value = '0.00';  
		
	}else if ( coupon_type == 'is_bogo_based' ){
		jQuery( document.getElementById( 'is_dollar_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_percentage_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_shipping_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_free_item_based' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'is_bogo_based' ) ).prop( 'checked', true );
		
		jQuery( document.getElementById( 'ec_admin_row_promo_dollar' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_percentage' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_shipping' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_dollar' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_promo_bogo_percentage' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_apply_to_shipping' ) ).hide ( );
		
		document.getElementById( 'promo_dollar' ).value = '0.00'; 
		document.getElementById( 'promo_percentage' ).value = '0.00'; 
		document.getElementById( 'promo_shipping' ).value = '0.00';  
		
	}
}