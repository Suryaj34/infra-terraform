<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_promocode', 'promocode_id' );
$table->set_table_id( 'ec_admin_coupon_list' );
$table->set_default_sort( 'promocode_id', 'ASC' );
$table->set_header( __( 'Manage Coupons', 'wp-easycart-pro' ) );
$table->set_icon( 'feedback' );
$table->set_docs_link( 'marketing', 'coupons' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'promocode_id', 
			'label' => __( 'Coupon Code', 'wp-easycart-pro' ),
			'format' => 'string',
			'linked' => true,
			'is_mobile' => true,
			'subactions' => array(
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-coupon',
				),
			),
		),
		array(
			'name' => 'is_dollar_based',
			'label' => __( 'Dollar', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'checkbox',
		),
		array( 
			'name' => 'is_percentage_based',
			'label' => __( 'Percentage', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'checkbox',
		),
		array( 
			'name' => 'is_shipping_based',
			'label' => __( 'Shipping', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'checkbox',
		),
		array( 
			'name' => 'is_free_item_based',
			'label' => __( 'Free Item', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'checkbox',
		),
	)
);
$table->set_search_columns(
	array( 'ec_promocode.promocode_id' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-coupon',
			'label' => __( 'Delete', 'wp-easycart-pro' )
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
			'name' => 'delete-coupon',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Coupon Code', 'wp-easycart-pro' ), __( 'Coupon Codes', 'wp-easycart-pro' ) );
$table->print_table();
