<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_subscription_plan', 'subscription_plan_id' );
$table->set_default_sort( 'ec_subscription_plan.plan_title', 'ASC' );
$table->set_icon( 'exerpt-view' );
$table->set_docs_link( 'products','subscription-plans' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'plan_title',
			'label' => __( 'Plan Title', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'string',
		),
		array( 
			'name' => 'can_downgrade', 
			'label' => __( 'Can Upgrade/Downgrade', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'bool',
		),
	)
);
$table->set_search_columns(
	array( 'ec_subscription_plan.plan_title' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-subscription-plan',
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
			'name' => 'delete-subscription-plan',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash',
		),
	)
);
$table->set_filters(
	array()
);
$table->set_label( __( 'Subscription Plan', 'wp-easycart-pro' ), __( 'Subscription Plans', 'wp-easycart-pro' ) );
$table->print_table();
