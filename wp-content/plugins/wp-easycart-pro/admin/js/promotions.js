// JavaScript Document
jQuery( document ).ready( function( $ ){
	$( ".wp-ec-datepicker" ).datepicker( {dateFormat:"yy-mm-dd"} );

	//reset form
	if( document.getElementById( 'type' ) ){
		if( document.getElementById( 'type' ).value == 1 ){
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );

		}else if(document.getElementById( 'type' ).value == 4 ){
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
			
		}else if(document.getElementById( 'type' ).value == 6 ){
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
			
		}else if( document.getElementById( 'type' ).value == 7 ){
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
			
		}else if(document.getElementById( 'type' ).value == 8 ){
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
			
		}else if(document.getElementById( 'type' ).value == 9 ){
            jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
            jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
            jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show( ) ;
            jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).show( );

        }else{
			jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
			jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
			jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
			jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
            jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );

		}
	}
} );

function ec_admin_promotion_reset_selections( selection_id ){
	if( selection_id == 'product_id_1' ){
		document.getElementById( 'manufacturer_id_1' ).value = '0';
		document.getElementById( 'category_id_1' ).value = '0';
		
	}else if( selection_id == 'manufacturer_id_1' ){
		document.getElementById( 'product_id_1' ).value = '0';
		document.getElementById( 'category_id_1' ).value = '0';
		
	}else if( selection_id == 'category_id_1' ){
		document.getElementById( 'product_id_1' ).value = '0';
		document.getElementById( 'manufacturer_id_1' ).value = '0';
		
	}
}

function ec_admin_promotion_reset_discount( selection_id) {
	if( selection_id == 'price1' ){
		document.getElementById( 'percentage1' ).value = '';
		
	}else if( selection_id == 'percentage1' ){
		document.getElementById( 'price1' ).value = '';
		
	}
}

function ec_admin_promotion_type_change( selection_id ){
	promotion_type = document.getElementById( selection_id ).value;
    document.getElementById( 'number1' ).value = ''; 
    document.getElementById( 'number2' ).value = '1'; 
    document.getElementById( 'price2' ).value = ''; 
    document.getElementById( 'product_id_1' ).value = '0'; 
    document.getElementById( 'manufacturer_id_1' ).value = '0';
    document.getElementById( 'category_id_1' ).value = '0';
    document.getElementById( 'price1' ).value = ''; 
    document.getElementById( 'percentage1' ).value = ''; 
		
	if( promotion_type == 0 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}else if( promotion_type == 1 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show ( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}else if( promotion_type == 4 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}else if( promotion_type == 6 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).hide ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).hide ( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}else if( promotion_type == 7 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}else if( promotion_type == 9 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_number2' ) ).show( );
		
	}else if(document.getElementById( 'type' ).value == 8 ){
		jQuery( document.getElementById( 'ec_admin_row_number1' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_price2' ) ).hide ( );
		jQuery( document.getElementById( 'ec_admin_row_product_id_1' ) ).show ( ) ;
		jQuery( document.getElementById( 'ec_admin_row_manufacturer_id_1' ) ).show ( );
		jQuery( document.getElementById( 'ec_admin_row_category_id_1' ) ).show ( );
		jQuery( document.getElementById( 'wpeasycart_promotions_step_4' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_row_number2' ) ).hide( );
		
	}
}