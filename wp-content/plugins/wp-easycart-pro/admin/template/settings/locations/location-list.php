<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_location', 'location_id' );
$table->set_default_sort( 'location_label', 'ASC' );
$table->set_header( __( 'Manage Locations', 'wp-easycart-pro' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'locations' );
$table->enable_mobile_column();
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'location_label', 
			'label'	=> __( 'Label', 'wp-easycart-pro' ),
			'format'=> 'string',
			'linked'=> true,
			'is_mobile' => true,
			'subactions'=> array(
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-location',
					'min_id' => 8,
				),
			),
		),
		array( 
			'name' 	=> 'address_line_1',
			'is_mobile' => true,
			'label'	=> __( 'Address', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'city',
			'is_mobile' => true,
			'label'	=> __( 'City', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'state',
			'is_mobile' => true,
			'label'	=> __( 'State', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'zip',
			'is_mobile' => true,
			'label'	=> __( 'Zip', 'wp-easycart-pro' ),
			'format'=> 'string'
		),
	)
);
$table->set_search_columns(
	array( 'ec_location.location_label' )
);
$table->set_bulk_actions( array() );
$table->set_actions(
	array(
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart-pro' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'delete-location',
			'label'	=> __( 'Delete', 'wp-easycart-pro' ),
			'icon'	=> 'trash',
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Location', 'wp-easycart-pro' ), __( 'Locations', 'wp-easycart-pro' ) );
$table->print_table();
