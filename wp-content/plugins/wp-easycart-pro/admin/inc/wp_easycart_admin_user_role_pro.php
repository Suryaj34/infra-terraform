<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_user_role_pro' ) ) :

final class wp_easycart_admin_user_role_pro{
	
	protected static $_instance = null;
	
	public $tax_cloud_file;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			add_filter( 'wp_easycart_admin_user_details_remote_access_fields_list', array( $this, 'remote_access_fields' ), 10, 2 );
		}
	}
	
	public function remote_access_fields( $fields, $role_id ){
		
		global $wpdb;
		$user_role = $wpdb->get_row( $wpdb->prepare( "SELECT role_label, admin_access FROM ec_role WHERE role_id = %d", $role_id ) );
		if( isset( $user_role->role_label ) ){
			$role_label = $user_role->role_label;
			$admin_access = $user_role->admin_access;
		}else{
			$role_label = '';
			$admin_access = 0;
		}
		$access_rows = $wpdb->get_results( $wpdb->prepare( "SELECT admin_panel FROM ec_roleaccess WHERE role_label = %s", $role_label ) );
		$access_panels = array( );
		for( $i=0; $i<count( $access_rows ); $i++ ){
			$access_panels[] = $access_rows[$i]->admin_panel;
		}
		$fields = array(
			array(
				"name"				=> "admin_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Admin Access", 'wp-easycart-pro' ),
				"required" 			=> false,
				"message" 			=> __( "Please select whether to allow remote admin access or not.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'checkbox',
				"onclick"			=> 'ec_admin_toggle_remote_access',
				"value"				=> $admin_access
			),
			array(
				"name"				=> "orders_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Store Orders", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'orders', $access_panels ),
			),
			array(
				"name"				=> "downloads_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Downloads", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'downloads', $access_panels )
			),
			array(
				"name"				=> "subscriptions_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Subscriptions", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'subscriptions', $access_panels )
			),
			
			array(
				"name"				=> "products_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Products", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'products', $access_panels )
			),
			
			array(
				"name"				=> "options_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Options", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'options', $access_panels )
			),
			
			array(
				"name"				=> "menus_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Menus", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'menus', $access_panels )
			),
			
			array(
				"name"				=> "manufacturers_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Manufacturers", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'manufacturers', $access_panels )
			),
			
			array(
				"name"				=> "categories_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Categories", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'categories', $access_panels )
			),
			
			array(
				"name"				=> "reviews_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Reviews", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'reviews', $access_panels )
			),
			
			array(
				"name"				=> "plans_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Subscription Plans", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'plans', $access_panels )
			),
			
			array(
				"name"				=> "users_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Users", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'users', $access_panels )
			),
			
			array(
				"name"				=> "giftcards_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Gift Cards", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'giftcards', $access_panels )
			),
			
			array(
				"name"				=> "news_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Newsletter Subscribers", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'news', $access_panels )
			),
			
			array(
				"name"				=> "newsletter_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Send a Newsletter", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'newsletter', $access_panels )
			),
			
			array(
				"name"				=> "coupons_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Coupons", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'coupons', $access_panels )
			),
			
			array(
				"name"				=> "promotions_access",
				"type"				=> "checkbox",
				"label"				=> __( "Allow Remote Access to Promotions", 'wp-easycart-pro' ),
				"required" 			=> false,
				"requires"			=> array(
					"name"			=> "admin_access",
					"value"			=> 1,
					"default_show"	=> false
				),
				"visible"			=> false,
				"value"				=> in_array( 'promotions', $access_panels )
			)
			
		);
		return $fields;
		
	}
	
}
endif; // End if class_exists check

function wp_easycart_admin_user_role_pro( ){
	return wp_easycart_admin_user_role_pro::instance( );
}
wp_easycart_admin_user_role_pro( );