<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_download', 'download_id' );
$table->set_table_id( 'ec_admin_download_list' );
$table->set_default_sort( 'order_id', 'DESC' );
$table->set_icon( 'download' );
$table->set_docs_link( 'orders','manage-downloads' );
$table->set_join( 'LEFT JOIN ec_product ON ec_product.product_id = ec_download.product_id' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'order_id', 
			'label' => __( 'Order ID', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'int',
		),
		array(
			'select' => 'ec_product.title AS product_title',
			'name' => 'product_title', 
			'label' => __( 'Product Title', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'string',
		),
		array(
			'name' => 'download_count',
			'label' => __( 'Times Downloaded', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'int',
		),
		array(
			'name' => 'is_amazon_download',
			'label' => __( 'Amazon S3 File?', 'wp-easycart-pro' ),
			'is_mobile' => false,
			'format' => 'checkbox',
		),
	)
);
$table->set_search_columns(
	array( 'ec_download.download_id', 'ec_download.order_id', 'ec_download.product_id', 'ec_download.download_file_name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-download',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
		),
	)
);
$table->set_actions(
	array(
		array(
			'name' => 'edit',
			'label' => __( 'Edit', 'wp-easycart-pro' ),
			'icon' => 'edit',
		),
		array(
			'name' => 'delete-download',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
		),
	)
);
$table->set_filters(
	array()
);
$table->set_label( __( 'Download', 'wp-easycart-pro' ), __( 'Downloads', 'wp-easycart-pro' ) );
$table->print_table();
