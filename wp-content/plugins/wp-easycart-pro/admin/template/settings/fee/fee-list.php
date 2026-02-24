<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_fee', 'fee_id' );
$table->set_default_sort( 'fee_label', 'ASC' );
$table->set_header( __( 'Manage Flex-Fees', 'wp-easycart-pro' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'fees' );
$table->enable_mobile_column();
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'fee_label', 
			'label'	=> __( 'Label', 'wp-easycart-pro' ),
			'format'=> 'string',
			'linked'=> true,
			'is_mobile' => true,
			'subactions'=> array(
				array(
					'click'         => 'return false',
					'name'          => __( 'Delete', 'wp-easycart-pro' ),
					'action_type'   => 'delete',
					'action'        => 'delete-fee'
				),
			),
		),
		array( 
			'name' 	=> 'fee_admin_description',
			'is_mobile' => true,
			'label'	=> __( 'Fee Criteria', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'fee_rate',
			'is_mobile' => true,
			'label'	=> __( 'Rate', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'fee_price',
			'is_mobile' => true,
			'label'	=> __( 'Price', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
	)
);
$table->set_search_columns(
	array( 'ec_fee.fee_label' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-fee',
			'label'	=> __( 'Delete', 'wp-easycart-pro' )
		),
	)
);
$table->set_actions(
	array(
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart-pro' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'delete-fee',
			'label'	=> __( 'Delete', 'wp-easycart-pro' ),
			'icon'	=> 'trash'
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Fee', 'wp-easycart-pro' ), __( 'Fees', 'wp-easycart-pro' ) );
$table->print_table();
