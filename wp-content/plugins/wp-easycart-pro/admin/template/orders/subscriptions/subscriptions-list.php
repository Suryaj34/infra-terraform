<?php
$table = new wp_easycart_admin_table();
$table->set_table( 'ec_subscription', 'subscription_id' );
$table->set_table_id( 'ec_admin_subscription_list' );
$table->set_default_sort( 'next_payment_date', 'DESC' );
$table->set_docs_link( 'orders','subscriptions' );
$table->set_icon( 'update' );
$table->set_add_new( false, '', '' );
$table->enable_mobile_column();
$table->set_list_columns(
	array(
		array(
			'name' => 'title', 
			'label' => __( 'Subscription Title', 'wp-easycart-pro' ),
			'format' => 'string',
			'linked' => true,
			'is_mobile' => true,
			'subactions' => array(
				array(
					'url' => 'https://dashboard.stripe.com/'. ( ( get_option( 'ec_option_stripe_connect_use_sandbox' ) ) ? 'test/' : '' ) . 'subscriptions/{custom_key}',
					'name' => __( 'View on Stripe', 'wp-easycart-pro' ),
					'custom_key' => 'stripe_subscription_id',
					'action_type' => 'url',
					'action' => 'stripe',
					'target' => '_blank',
				),
				array(
					'click' => 'return false',
					'name' => __( 'Delete', 'wp-easycart-pro' ),
					'action_type' => 'delete',
					'action' => 'delete-subscription'
				)
			)
		),
		array(
			'name' => 'subscription_status',
			'label' => __( 'Status', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'string'
		),
		array( 
			'name' => 'stripe_subscription_id',
			'label' => __( 'Stripe ID', 'wp-easycart-pro' ),
			'is_mobile' => false,
			'format' => 'string'
		),
		array( 
			'name' => 'first_name',
			'label' => __( 'First Name', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'string'
		),
		array( 
			'name' => 'last_name',
			'label' => __( 'Last Name', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'string'
		),
		array( 
			'name' => 'price',
			'label' => __( 'Price', 'wp-easycart-pro' ),
			'is_mobile' => true,
			'format' => 'currency'
		)
	)
);
$table->set_search_columns(
	array( 'ec_subscription.title', 'ec_subscription.email', 'ec_subscription.first_name', 'ec_subscription.last_name', 'ec_subscription.subscription_status' )
);
$table->set_bulk_actions(
	array(
		array(
			'name' => 'delete-subscription',
			'label' => __( 'Delete', 'wp-easycart-pro' )
		)
	)
);
$table->set_actions(
	array(
		array(
			'name' => 'edit',
			'label' => __( 'Edit', 'wp-easycart-pro' ),
			'icon' => 'edit'
		),
		array(
			'name' => 'delete-subscription',
			'label' => __( 'Delete', 'wp-easycart-pro' ),
			'icon' => 'trash'
		)
	)
);
$table->set_filters(
	array()
);
$table->set_label( __( 'Subscription', 'wp-easycart-pro' ), __( 'Subscriptions', 'wp-easycart-pro' ) );
$table->print_table();
