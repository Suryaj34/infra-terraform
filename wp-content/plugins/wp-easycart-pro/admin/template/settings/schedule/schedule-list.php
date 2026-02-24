<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_schedule', 'schedule_id' );
$table->set_default_sort( 'schedule_id', 'ASC' );
$table->set_header( __( 'Manage Schedules', 'wp-easycart-pro' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'schedules' );
$table->enable_mobile_column();
$table->set_list_columns( 
	array(
		array( 
			'name' => 'schedule_label', 
			'label' => __( 'Label', 'wp-easycart-pro' ),
			'format' => 'string',
			'linked' => true,
			'is_mobile' => true,
			'subactions' => array(
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-schedule',
					'min_id' => 8,
				),
			),
		),
		array( 
			'name' => 'day_of_week',
			'is_mobile' => true,
			'label' => __( 'Day of Week', 'wp-easycart-pro' ),
			'format' => 'string',
		),
		array( 
			'name' => 'is_holiday',
			'is_mobile' => true,
			'label' => __( 'Is Holiday?', 'wp-easycart-pro' ),
			'format' => 'bool',
		),
		array( 
			'name' => 'apply_to_retail',
			'is_mobile' => true,
			'label' => __( 'Apply to Retail', 'wp-easycart-pro' ),
			'format' => 'bool',
		),
		array( 
			'name' => 'apply_to_preorder',
			'is_mobile' => true,
			'label' => __( 'Apply to Preorder', 'wp-easycart-pro' ),
			'format' => 'bool',
		),
		array( 
			'name' => 'apply_to_restaurant',
			'is_mobile' => true,
			'label' => __( 'Apply to Restaurant', 'wp-easycart-pro' ),
			'format' => 'bool',
		),
	)
);
$table->set_search_columns(
	array( 'ec_schedule.schedule_label' )
);
$table->set_bulk_actions( array() );
$table->set_actions(
	array(
		array(
			'name' => 'edit',
			'label' => __( 'Edit', 'wp-easycart-pro' ),
			'icon' => 'edit',
		),
		array(
			'name' => 'delete-schedule',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
			'min_id' => 8,
		),
	)
);

$table->set_filters(
	array()
);
$table->set_label( __( 'Schedule', 'wp-easycart-pro' ), __( 'Schedules', 'wp-easycart-pro' ) );
$table->print_table();
