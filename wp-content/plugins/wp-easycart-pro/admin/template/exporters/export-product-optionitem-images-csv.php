<?php 
if ( current_user_can( 'wpec_products' ) ) {
	global $wpdb;

	if( !isset( $_GET['product_id'] ) ){
		echo 'Missing Product ID';
		die( );
	}

	$product_id = (int) $_GET['product_id'];
	$optionitem_images = $wpdb->get_results( $wpdb->prepare( "SELECT ec_optionitemimage.*, ec_optionitem.optionitem_name FROM ec_optionitemimage LEFT JOIN ec_optionitem ON ec_optionitem.optionitem_id = ec_optionitemimage.optionitem_id WHERE ec_optionitemimage.product_id = %d", $product_id ) ); 
	
	$fh = fopen('php://output', 'w');

	header("Content-type: text/csv; charset=UTF-8");
	header("Content-Transfer-Encoding: binary"); 
	header("Content-Disposition: attachment; filename=product-optionitem-image-" . date( 'Y-m-d' ). ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	fputcsv( $fh, array( 
		'Option Item',
		'Option Item ID',
		'Product Images',
		'Image 1',
		'Image 2',
		'Image 3',
		'Image 4',
		'Image 5'
	) );
	if ( count( $optionitem_images ) > 0 ) {
		foreach ( $optionitem_images as $optionitem_image ) {
			fputcsv( $fh, array( 
				$optionitem_image->optionitem_name,
				$optionitem_image->optionitem_id,
				$optionitem_image->product_images,
				$optionitem_image->image1,
				$optionitem_image->image2,
				$optionitem_image->image3,
				$optionitem_image->image4,
				$optionitem_image->image5
			) );
		}
	}
	fclose($fh);

}else{
	echo 'Not Authenticated'; 
	die( );
}
