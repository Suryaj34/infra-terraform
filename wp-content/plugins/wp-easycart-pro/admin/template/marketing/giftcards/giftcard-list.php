<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_giftcard', 'giftcard_id' );
$table->set_default_sort( 'giftcard_id', 'ASC' );
$table->set_header( __( 'Manage Gift Cards', 'wp-easycart-pro' ) );
$table->set_icon( 'products' );
$table->set_docs_link( 'marketing', 'gift-cards' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'giftcard_id', 
			'label' => __( 'Gift Card ID', 'wp-easycart-pro' ),
			'format' => 'string',
			'linked' => true,
			'is_mobile' => true,
			'subactions' => array(
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-giftcard',
				),
			),
		),
		array(
			'name' => 'amount',
			'label' => __( 'Amount Remaining', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'currency',
		),
	)
);
$table->set_search_columns(
	array( 'ec_giftcard.giftcard_id' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-giftcard',
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
			'name' => 'delete-giftcard',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Gift Card', 'wp-easycart-pro' ), __( 'Gift Cards', 'wp-easycart-pro' ) );
$table->print_table();
