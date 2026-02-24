// JavaScript Document
jQuery(document).ready(function($) {
	//date fields
	$(".wp-ec-datepicker").datepicker();
	
	//reset details page form
	if($(".ec_admin_details_panel").length > 0) {
		if( document.getElementById( 'is_amazon_download').checked == true ){
			jQuery( document.getElementById( 'ec_admin_row_download_file_name' )).hide();
			jQuery( document.getElementById( 'ec_admin_row_amazon_key' )).show();
			document.getElementById( 'download_file_name' ).selected = 0;
		} else {
			jQuery( document.getElementById( 'ec_admin_row_download_file_name' )).show();
			jQuery( document.getElementById( 'ec_admin_row_amazon_key' )).hide();
			document.getElementById( 'amazon_key' ).selected = 0;
		}
	}
	
	
});

function ec_admin_download_is_amazon( is_amazon_download) {
	if (document.getElementById( 'is_amazon_download').checked == true) {
		jQuery( document.getElementById( 'ec_admin_row_download_file_name' )).hide();
		jQuery( document.getElementById( 'ec_admin_row_amazon_key' )).show();
		document.getElementById( 'download_file_name' ).selectedIndex = 0;
	} else {
		jQuery( document.getElementById( 'ec_admin_row_download_file_name' )).show();
		jQuery( document.getElementById( 'ec_admin_row_amazon_key' )).hide();
		document.getElementById( 'amazon_key' ).selectedIndex = 0;
	}
}