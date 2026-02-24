<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_promotion', 'promotion_id' );
$table->set_default_sort( 'start_date', 'DESC' );
$table->set_header( __( 'Manage Promotions', 'wp-easycart-pro' ) );
$table->set_icon( 'tag' );
$table->set_docs_link ( 'marketing', 'promotions' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'name',
			'label' => __( 'Promotion Name', 'wp-easycart-pro' ),
			'format' => 'text',
			'linked' => true,
			'is_mobile' => true,
			'subactions' => array(
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-promotion',
				),
			),
		),
		array(
			'name' => 'start_date',
			'label' => __( 'Start Date', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'date',
		),
		array( 
			'name' => 'end_date',
			'label' => __( 'End Date', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'date',
		),
	)
);
$table->set_search_columns(
	array( 'ec_promotion.name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-promotion',
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
			'name' => 'delete-promotion',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Promotion', 'wp-easycart-pro' ), __( 'Promotions', 'wp-easycart-pro' ) );
$table->print_table();
