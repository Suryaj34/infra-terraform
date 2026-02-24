<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_products_pro' ) ) :

final class wp_easycart_admin_products_pro{
	
	public $images_pro_file;
	public $options_pro_file;
	public $export_product_optionitem_quantities_csv;
	public $export_product_optionitem_images_csv;
	
	protected static $_instance = null;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function __construct( ){ 
		$this->images_pro_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/products/products/product-images-pro.php';
		$this->options_pro_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/products/products/product-options-pro.php';
		$this->export_product_optionitem_quantities_csv = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/exporters/export-product-optionitems-csv.php';
		$this->export_product_optionitem_images_csv = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/exporters/export-product-optionitem-images-csv.php';
		
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			/* Product Buttons */
			add_action( 'wp_easycart_admin_product_details_buttons_pre', array( $this, 'maybe_add_square_sync_button' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_qr_code', array( $this, 'maybe_add_qr_code' ), 10, 1 );

			/* Option Item Images */
			add_filter( 'wp_easycart_admin_product_details_images_fields_list', array( $this, 'add_optionset_images' ) );
			add_action( 'wp_easycart_admin_product_details_after_images', array( $this, 'load_images_pro' ) );

			/* Variants */
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_import_product_optionitem_quantities' ) );
			add_action( 'wp_easycart_admin_product_details_after_images', array( $this, 'load_options_pro' ) );

			/* Modifiers */
			add_filter( 'wp_easycart_admin_product_details_options_fields_list', array( $this, 'add_advanced_options' ) );
			add_action( 'wp_easycart_admin_product_advanced_option_row_end', array( $this, 'add_advanced_option_logic' ) );

			/* Option Item Quantity Tracking */
			add_filter( 'wp_easycart_admin_optionitem_quantity_add_click', array( $this, 'allow_add_optionitem_quantity_tracking' ) );
			add_filter( 'wp_easycart_admin_optionitem_quantity_update_click', array( $this, 'allow_update_optionitem_quantity_tracking' ) );
			add_filter( 'wp_easycart_admin_optionitem_quantity_delete_click', array( $this, 'allow_delete_optionitem_quantity_tracking' ) );

			/* Custom Price Label */
			add_filter( 'wp_easycart_admin_product_custom_price_label_change', array( $this, 'allow_custom_price_label_change' ) );
			add_filter( 'wp_easycart_admin_product_custom_price_label_save', array( $this, 'allow_custom_price_label_save' ), 10, 2 );

			/* Volume Pricing */
			add_filter( 'wp_easycart_admin_tiered_pricing_add_click', array( $this, 'allow_add_tiered_pricing' ) );
			add_filter( 'wp_easycart_admin_tiered_pricing_edit_click', array( $this, 'allow_edit_tiered_pricing' ) );
			add_filter( 'wp_easycart_admin_tiered_pricing_delete_click', array( $this, 'allow_delete_tiered_pricing' ) );

			/* B2B Pricing */
			add_filter( 'wp_easycart_admin_b2b_pricing_add_click', array( $this, 'allow_add_b2b_pricing' ) );
			add_filter( 'wp_easycart_admin_b2b_pricing_delete_click', array( $this, 'allow_delete_b2b_pricing' ) );

			/* General Options (Pro Only) */
			add_action( 'wpeasycart_admin_product_details_general_saved', array( $this, 'update_general_options' ), 10, 1 );
			add_filter( 'wp_easycart_admin_product_details_general_options_fields_list', array( $this, 'add_general_options' ), 10, 2 );
			add_filter( 'wp_easycart_admin_product_details_pricing_fields_list', array( $this, 'add_pricing_options' ) );
			add_filter( 'wp_easycart_admin_product_details_user_roles', array( $this, 'add_user_roles' ), 10, 1 );

			/* Shipping Options (Pro Only) */
			add_filter( 'wp_easycart_admin_product_details_shipping_fields_list', array( $this, 'add_shipping_options' ), 10, 2 );

			/* Tax Options (Pro Only) */
			add_filter( 'wp_easycart_admin_product_details_tax_fields_list', array( $this, 'add_tax_options' ) );

			/* Deconetwork */
			add_filter( 'wp_easycart_admin_product_details_deconetwork_fields_list', array( $this, 'add_deconetwork' ) );

			/* Subscription */
			add_filter( 'wp_easycart_admin_product_details_subscription_fields_list', array( $this, 'add_subscription' ) );

			/* Downloads */
			add_filter( 'wp_easycart_admin_product_details_downloads_fields_list', array( $this, 'add_downloads' ) );

			/* Inventory Options */
			remove_action( 'wp_easycart_admin_settings_product_inventory_end', array( wp_easycart_admin_products( ), 'add_inventory_notification_setting' ) );
			add_action( 'wp_easycart_admin_settings_product_inventory_end', array( $this, 'add_inventory_notification_setting' ) );
			add_action( 'wp_easycart_admin_product_details_optionitem_quantity_fields', array( $this, 'add_inventory_notification_management' ) );

			/* Google Merchant */
			remove_action( 'wp_easycart_admin_product_details_googlemerchant_fields', array( wp_easycart_admin_products( ), 'google_merchant_fields' ) );
			add_action( 'wp_easycart_admin_product_details_googlemerchant_fields', array( $this, 'google_merchant_fields' ) );

			/* List Settings */
			add_action( 'wpeasycart_admin_products_list_options', array( $this, 'add_product_list_settings' ) );
			add_filter( 'wp_easycart_admin_product_list_filters', array( $this, 'maybe_add_location_filter' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_product_optionitem_quantities' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_product_optionitem_images' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_import_product_optionitem_images' ) );
		}
	}
	
	/* Extra Product Buttons */
	public function maybe_add_square_sync_button( $product ) {
		if ( get_option( 'ec_option_payment_process_method' ) == 'square' && '' != $product->square_id ) {
			echo '<a href="#" class="ec_page_title_button" onclick="wp_easycart_pro_product_square_sync( ' . $product->product_id . ' );">' . esc_attr__( 'Sync From Square', 'wp-easycart-pro' ) . '</a>';
		}
	}

	public function maybe_add_qr_code( $product_id ) {
		try {
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_phpqrcode.php' );
			if ( class_exists( 'QRcode' ) ) {
				ob_start();
				QRcode::png( esc_url_raw( wp_easycart_admin_products( )->get_product_link( $product_id ) ) );
				$qr_image_string = base64_encode( ob_get_contents() );
				ob_end_clean();
				echo '<img src="data:image/png;base64,' . $qr_image_string . '" data-img-blob="' . $qr_image_string . '" style="float:right; max-width:50px; height:auto;" />';
			}
		} catch ( ErrorException $ex ) {
			// Cannot load QR code, Prevent Errors
		}
	}

	/* Advanced Options */
	public function add_advanced_options( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'use_advanced_optionset' ){
				$fields[$i]['onclick'] = 'advanced_options_change';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		$fields[] = array(
			"name"				=> "advanced_options",
			"type"				=> "advanced_options"
		);
		return $fields;
	}
	
	public function add_advanced_option_logic( $option_to_product_row ){
		global $wpdb;
		$optionsets = $wpdb->get_results( $wpdb->prepare( "SELECT ec_option_to_product.option_to_product_id, ec_option.option_id, ec_option.option_name, ec_option.option_type, ec_option.option_required FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option_to_product.option_order ASC", $option_to_product_row->product_id ) );
		$enabled = false; $show_field = 1; $and_rules = 1; $rules = array( (object) array( 'option_id' => 0, 'operator' => '=', 'optionitem_id' => 0 ) );
		$rule_meta = $option_to_product_row->conditional_logic;
		$rules = array( );
		if( $rule_meta ){
			$logic = json_decode( $rule_meta );
			$enabled = ( isset( $logic->enabled ) ) ? $logic->enabled : false;
			$show_field = ( isset( $logic->show_field ) ) ? $logic->show_field : false;
			$and_rules = ( isset( $logic->and_rules ) ) ? $logic->and_rules : false;
			$rules = ( isset( $logic->rules ) && is_array( $logic->rules ) ) ? $logic->rules : array();
		}
		
		if( count( $rules ) == 0 ){
			$rules[] = (object) array(
				'option_id'			=> 0,
				'operator'			=> '=',
				'optionitem_id' 	=> 0,
				'optionitem_value'	=> ''
			);
		}
		
		echo '<div class="ec_admin_option_logic"><input type="checkbox" class="ec_admin_enable_conditional_logic" onclick="ec_admin_product_details_enable_logic( jQuery( this ), ' . $option_to_product_row->option_to_product_id . ' );"'.( ( $enabled ) ? ' checked="checked"' : '' ).' /> ' . __( 'Enable Conditional Logic', 'wp-easycart-pro' ) . '</div>';
		echo '<div class="ec_admin_option_logic_content" data-option-to-product-id="'.$option_to_product_row->option_to_product_id.'" id="ec_logic_item_'.$option_to_product_row->option_to_product_id.'"' . ( ( $enabled ) ? ' style="display:block"' : '' ) . '>';
			echo '<div class="ec_admin_option_logic_main_row">';
				echo '<select class="logic-show" onchange="ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' );">';
					echo '<option value="1"' . ( ( $show_field ) ? ' selected="selected"' : '' ) . '>' . __( 'Show', 'wp-easycart-pro' ) . '</option>';
					echo '<option value="0"' . ( ( !$show_field ) ? ' selected="selected"' : '' ) . '>' . __( 'Hide', 'wp-easycart-pro' ) . '</option>';
				echo '</select>';
				echo '<span> ' . __( 'this field if', 'wp-easycart-pro' ) . ' </span>';
				echo '<select class="logic-and" onchange="ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' );">';
					echo '<option value="AND"'.( ( $and_rules == 'AND' ) ? ' selected="selected"' : '').'>' . __( 'All', 'wp-easycart-pro' ) . '</option>';
					echo '<option value="OR"'.( ( $and_rules == 'OR' ) ? ' selected="selected"' : '').'>' . __( 'Any', 'wp-easycart-pro' ) . '</option>';
				echo '</select>';
				echo '<span> ' . __( 'of the following match', 'wp-easycart-pro' ) . ':</span>';
			echo '</div>';
			foreach( $rules as $rule ){
				$option_selected = false;
				echo '<div class="ec_admin_option_logic_item">';
					echo '<select class="logic-option" onchange="ec_admin_product_details_change_logic( jQuery( this ), '.$option_to_product_row->option_to_product_id.' );">';
					foreach( $optionsets as $optionset ){
						if( $optionset->option_type != 'file' && $optionset->option_type != 'grid' && $optionset->option_type != 'dimensions1' && $optionset->option_type != 'dimensions2' ){
							if( $rule->option_id == $optionset->option_to_product_id )
								$option_selected = true;
							echo '<option value="' . $optionset->option_to_product_id . '"' . ( ( $rule->option_id == $optionset->option_to_product_id ) ? 'selected="selected"' : '' ) . '>' . $optionset->option_name . '</option>';
						}
					}
					echo '</select>';
					
					echo '<select class="logic-operator" onchange="ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' );">';
						echo '<option value="="' . ( ( $rule->operator == '=' ) ? 'selected="selected"' : '' ) . '>' . __( 'is', 'wp-easycart-pro' ) . '</option>';
						echo '<option value="!="' . ( ( $rule->operator == '!=' ) ? 'selected="selected"' : '' ) . '>' . __( 'is not', 'wp-easycart-pro' ) . '</option>';
						//echo '<option value=">"' . ( ( $rule->operator == '>' ) ? 'selected="selected"' : '' ) . '>greater than</option>';
						//echo '<option value="<"' . ( ( $rule->operator == '<' ) ? 'selected="selected"' : '' ) . '>less than</option>';
						//echo '<option value="LIKE"' . ( ( $rule->operator == 'LIKE' ) ? 'selected="selected"' : '' ) . '>contains</option>';
						//echo '<option value="LIKE%"' . ( ( $rule->operator == 'LIKE%' ) ? 'selected="selected"' : '' ) . '>starts with</option>';
						//echo '<option value="LIKE%%"' . ( ( $rule->operator == 'LIKE%%' ) ? 'selected="selected"' : '' ) . '>ends with</option>';
					echo '</select>';
					
					for( $i=0; $i<count( $optionsets ); $i++ ){
						if( $optionsets[$i]->option_type == 'combo' || $optionsets[$i]->option_type == 'swatch' || $optionsets[$i]->option_type == 'radio' || $optionsets[$i]->option_type == 'checkbox' ){
							$optionitems = $wpdb->get_results( $wpdb->prepare( "SELECT optionitem_id, optionitem_name FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC", $optionsets[$i]->option_id ) );
							echo '<select class="logic-optionitem" onchange="ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' );" data-option-id="' . $optionsets[$i]->option_to_product_id . '"' . ( ( ( $option_selected && $rule->option_id && $rule->option_id != $optionsets[$i]->option_to_product_id ) || ( !$rule->option_id && $i > 0 ) ) ? ' style="display:none;"' : '' ) . '>';
							foreach( $optionitems as $optionitem ){
								echo '<option value="' . $optionitem->optionitem_id . '"' . ( ( $rule->optionitem_id == $optionitem->optionitem_id ) ? 'selected="selected"' : '' ) . '>' . $optionitem->optionitem_name . '</option>';
							}
							echo '</select>';
						}else if( $optionset->option_type != 'file' && $optionsets[$i]->option_type != 'grid' && $optionsets[$i]->option_type != 'dimensions1' && $optionsets[$i]->option_type != 'dimensions2' ){
							echo '<input type="text" onkeyup="ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' );" class="logic-optionitem" value="' . ( ( $rule->optionitem_value != ''  ) ? $rule->optionitem_value : '' ) . '" data-option-id="' . $optionsets[$i]->option_to_product_id . '"' . ( ( ( $rule->option_id && $rule->option_id != $optionsets[$i]->option_to_product_id ) || ( !$rule->option_id && $i > 0 ) ) ? ' style="display:none;"' : '' ) . ' />';	
						}
					}
					
					echo '<a class="remove" href="#" onclick="ec_admin_product_details_remove_logic( this ); ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' ); return false;">-</a>';
					echo '<a class="add" href="#" onclick="ec_admin_product_details_add_logic( this ); ec_admin_product_details_save_logic( '.$option_to_product_row->option_to_product_id.' ); return false;">+</a>';
				echo '</div>';
			}
		echo '</div>';
	}
	
	/* Option Item Images */
	public function add_optionset_images( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'use_optionitem_images' ){
				$fields[$i]['onclick'] = 'optionitem_images_change';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		$fields[] = array(
			"name"				=> "optionitem_images",
			"type"				=> "optionitem_images"
		);
		return $fields;
	}
	
	public function load_images_pro() {
		include $this->images_pro_file;
	}

	public function load_options_pro() {
		include $this->options_pro_file;
	}

	public function process_import_product_optionitem_quantities() {
		if ( isset( $_POST['ec_admin_form_action'] ) && 'import-option-item-quantities' == $_POST['ec_admin_form_action'] ) {
			$this->import_optionitem_quantities();
			wp_redirect( 'admin.php?page=wp-easycart-products&subpage=products&product_id=' . (int) $_POST['product_id'] . '&ec_admin_form_action=edit&success=option-items-imported#quantities' );
			die();
		}
	}

	public function import_optionitem_quantities() {
		global $wpdb;
		$product_id = (int) $_POST['product_id'];
		$total_quantity = 0;

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );

		$file_name = sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) );
		$first = true;
		if ( ( $handle = fopen( $file_name, 'r' ) ) !== false ) {
			while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
				if ( ! $first ) {
					if ( count( $data ) >= 7 ) {
						$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5, quantity, sku, price ) VALUES( %d, %d, %d, %d, %d, %d, %d, %s, %s )', $product_id, $data[2], $data[3], $data[4], $data[5], $data[6], $data[1], ( ( isset( $data[7] ) ) ? $data[7] : '' ), ( ( isset( $data[8] ) ) ? $data[8] : '-1.00' ) ) );
						$total_quantity += $data[1];
					}
				} else {
					$first = false;
				}
			}
			fclose( $handle );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE product_id = %d', $total_quantity, $product_id ) );
		}
		wp_cache_flush();
	}
	
	/* Option Item Quantity Tracking */
	public function allow_add_optionitem_quantity_tracking( $action ){
		return "ec_admin_product_details_add_optionitem_quantity";
	}
	
	public function allow_update_optionitem_quantity_tracking( $action ){
		return "ec_admin_product_details_update_optionitem_quantity";
	}
	
	public function allow_delete_optionitem_quantity_tracking( $action ){
		return "ec_admin_product_details_delete_optionitem_quantity";
	}
	
	/* Custom Price Labels */
	public function allow_custom_price_label_change( $label ) {
		return 'show_custom_price_label';
	}
	
	public function allow_custom_price_label_save( $zero_val, $actual_val ) {
		return $actual_val;
	}
	
	/* Tiered Pricing */
	public function allow_add_tiered_pricing( $action ){
		return "ec_admin_product_details_add_price_tier";
	}
	
	public function allow_edit_tiered_pricing( $action ){
		return "ec_admin_product_details_edit_price_tier";
	}
	
	public function allow_delete_tiered_pricing( $action ){
		return "ec_admin_product_details_delete_price_tier";
	}
	
	/* B2B Pricing */
	public function allow_add_b2b_pricing( $action ){
		return "ec_admin_product_details_add_role_price";
	}
	
	public function allow_delete_b2b_pricing( $action ){
		return "ec_admin_product_details_delete_role_price";
	}
	
	/* General Options */
	public function update_general_options( $product_id ) {
		if ( get_option( 'ec_option_enable_mailerlite' ) && isset( $_POST['mailerlite_group_name'] ) ) {
			$mailerlite_groups = get_option( 'ec_option_mailerlite_groups' );
			if ( ! $mailerlite_groups ) {
				$mailerlite_groups = (object) array();
			}
			$mailerlite_groups->{ strval( $product_id ) } = sanitize_text_field( wp_unslash( $_POST['mailerlite_group_name'] ) );
			update_option( 'ec_option_mailerlite_groups', $mailerlite_groups );
		}
		if ( get_option( 'ec_option_enable_activecampaign' ) && isset( $_POST['activecampaign_group_name'] ) ) {
			$activecampaign_groups = get_option( 'ec_option_activecampaign_groups' );
			if ( ! $activecampaign_groups ) {
				$activecampaign_groups = (object) array();
			}
			$activecampaign_groups->{ strval( $product_id ) } = sanitize_text_field( wp_unslash( $_POST['activecampaign_group_name'] ) );
			update_option( 'ec_option_activecampaign_groups', $activecampaign_groups );
		}
	}

	public function add_pricing_options( $fields ) {
		for ( $i=0; $i<count( $fields ); $i++ ) {
			if ( $fields[$i]['name'] == 'login_for_pricing' ) {
				$fields[$i]['onclick'] = 'show_required_user_level';
				$fields[$i]['read-only'] = false;
			} else if ( $fields[$i]['name'] == 'show_custom_price_range' ) {
				$fields[$i]['onclick'] = 'show_custom_price_range';
				$fields[$i]['read-only'] = false;
			}
		}
		return $fields;
	}

	public function add_general_options( $fields, $product = false ) {
		global $wpdb;
		for ( $i=0; $i<count( $fields ); $i++ ) {
			if ( $fields[$i]['name'] == 'is_donation' ) {
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'is_giftcard' ) {
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'inquiry_mode' ) {
				$fields[$i]['onclick'] = 'ec_admin_product_details_inquiry_change';
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'catalog_mode' ) {
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'is_preorder_type' ) {
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'is_restaurant_type' ) {
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;

			} else if ( $fields[$i]['name'] == 'role_id' ) {
				unset( $fields[$i]['onchange'] );
				$fields[$i]['read-only'] = false;
			}
		}
		if ( get_option( 'ec_option_enable_mailerlite' ) ) {
			$mailerlite_groups = get_option( 'ec_option_mailerlite_groups' );
			$product_id = ( isset( $_GET['product_id'] ) ) ? (int) $_GET['product_id'] : 0;
			if ( $product_id ) {
				$mailerlite_group_name = ( $mailerlite_groups && isset( $mailerlite_groups->{ strval( $product_id ) } ) ) ? $mailerlite_groups->{ strval( $product_id ) } : '';
				$fields[] = array(
					"name"				=> "mailerlite_group_name",
					"type"				=> "text",
					"label"				=> __( "Mailer Lite Subscriber Group", 'wp-easycart-pro' ),
					"required" 			=> false,
					"validation_type" 	=> 'text',
					"visible"			=> true,
					"value"				=> $mailerlite_group_name
				);
			}
		}
		if ( get_option( 'ec_option_enable_activecampaign' ) ) {
			$activecampaign_groups = get_option( 'ec_option_activecampaign_groups' );
			$activecampaign_lists_response = wp_easycart_admin_pro()->call_activecampaign( (object) array(), 'lists', 'GET' );
			$activecampaign_lists = array();
			if ( $activecampaign_lists_response && isset( $activecampaign_lists_response->lists ) ) {
				foreach ( $activecampaign_lists_response->lists as $activecampaign_list ) {
					if ( '' != $activecampaign_list->name ) {
						$activecampaign_lists[] = (object) array(
							'id' => $activecampaign_list->id,
							'value' => $activecampaign_list->name
						);
					}
				}
			}
			$product_id = ( isset( $_GET['product_id'] ) ) ? (int) $_GET['product_id'] : 0;
			if ( $product_id ) {
				$activecampaign_group_name = ( $activecampaign_groups && isset( $activecampaign_groups->{ strval( $product_id ) } ) ) ? $activecampaign_groups->{ strval( $product_id ) } : '';
				$fields[] = array(
					"name"				=> "activecampaign_group_name",
					"type"				=> "select",
					"select2"			=> "basic",
					"label"				=> __( 'ActiveCampaign List', 'wp-easycart-pro' ),
					"data"				=> $activecampaign_lists,
					"data_label"		=> __( "Select One (Optional)", 'wp-easycart-pro' ),
					"required" 			=> false,
					"validation_type" 	=> 'select',
					"visible"			=> true,
					"value"				=> $activecampaign_group_name
				);
			}
		}
		if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
			$locations = $wpdb->get_results( "SELECT ec_location.location_label as value, ec_location.location_id as id FROM ec_location ORDER BY location_label ASC" );
			$selected_locations = explode( ',', ( ( is_object( $product ) && isset( $product->pickup_locations ) && is_string( $product->pickup_locations ) ) ? $product->pickup_locations : '' ) );
			$fields[] = array(
				"name"				=> "pickup_locations",
				"type"				=> "select",
				"select2"			=> "basic",
				"label"				=> __( "Pickup Locations", 'wp-easycart-pro' ),
				"data"				=> $locations,
				"data_label"		=> __( "Select Locations", 'wp-easycart-pro' ),
				"required" 			=> false,
				"onchange"			=> 'ec_admin_product_details_location_pickup_change',
				"validation_type" 	=> 'select',
				"visible"			=> true,
				"multiple"			=> true,
				"value"				=> $selected_locations
			);
		}
		return $fields;
	}
	
	public function add_user_roles( $userroles ) {
		global $wpdb;
		$user_roles = $wpdb->get_results( "SELECT ec_role.role_id AS id, ec_role.role_label AS value FROM ec_role ORDER BY role_label ASC" );
		$user_roles[] = (object) array( "id" => -1, "value" => "Logged Out Users Only" );
		return $user_roles;
	}

	/* Shipping Options */
	public function add_shipping_options( $fields, $product = false ) {
		global $wpdb;
		$zones = $wpdb->get_results( "SELECT ec_zone.zone_name as value, ec_zone.zone_id as id FROM ec_zone ORDER BY zone_name ASC" );
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'shipping_restriction' ){
				$fields[$i]['type'] = "select";
				$fields[$i]['select2'] = "basic";
				$fields[$i]['label'] = __( 'Restrict Shipping for this Product by Zone', 'wp-easycart-pro' );
				$fields[$i]['data'] = $zones;
				$fields[$i]['data_label'] = __( "Allow Shipping to All Locations", 'wp-easycart-pro' );
				$fields[$i]['required'] = false;
				$fields[$i]['validation_type'] = 'select';
				$fields[$i]['onclick'] = '';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		return $fields;
	}

	/* Tax Options */
	public function add_tax_options( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'TIC' ){
				unset( $fields[$i]['onclick'] );
				$fields[$i]['read-only'] = false;
			}
		}
		return $fields;
	}
	
	/* Deconetwork */
	public function add_deconetwork( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'is_deconetwork' ){
				$fields[$i]['onclick'] = 'ec_admin_product_details_deconetwork_toggle';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		return $fields;
	}
	
	/* Subscription */
	public function add_subscription( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'is_subscription_item' ){
				$fields[$i]['onclick'] = 'ec_admin_product_details_subscription_change';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		return $fields;
	}
	
	/* Downloads */
	public function add_downloads( $fields ){
		for( $i=0; $i<count( $fields ); $i++ ){
			if( $fields[$i]['name'] == 'is_download' ){
				$fields[$i]['onclick'] = 'ec_admin_product_details_download_toggle';
				$fields[$i]['read-only'] = false;
				break;
			}
		}
		return $fields;
	}
	
	/* Inventory */
	public function add_inventory_notification_setting( ){
		if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){
            wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_inventory_notification', 'ec_admin_save_product_options', get_option( 'ec_option_enable_inventory_notification' ), __( 'Stock Notifications: Customers', 'wp-easycart-pro' ), __( 'Enabling this allows your customers to subscribe to low stock notifications.', 'wp-easycart-pro' ) );
        }else{
            echo __( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue', 'wp-easycart-pro' );
        }
	}
	
	public function add_inventory_notification_management( ){
		if( get_option( 'ec_option_enable_inventory_notification' ) ){
			echo '<div class="ec_admin_stock_notification_view">';
				echo '<div class="ec_out_of_stock_notify_loader_cover" style="display:none;"></div>';
				echo '<div class="ec_out_of_stock_notify_loader" style="display:none;"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
				echo '<h4>' . __( 'Instock Subscribers', 'wp-easycart-pro' ) . ' <a href="#" onclick="ec_admin_notify_all_subscribers( ' . ( int ) $_GET['product_id'] . ' ); return false;">' . __( 'Notify All', 'wp-easycart-pro' ) . '</a></h4>';
				echo '<div class="ec_admin_stock_notification_add_new"><label>' . __( 'Add Email', 'wp-easycart-pro' ) . ':</label> <input type="text" id="ec_notify_new_email" value="" /> <input type="button" onclick="ec_admin_add_notify_subscriber( ' . (int) $_GET['product_id'] . ' );" value="' . __( 'Add New', 'wp-easycart-pro' ) . '" /></div>';
				echo '<div class="ec_admin_message_success" id="ec_admin_notify_success" style="display:none; float:left; width:100%; margin:0 0 5px;">' . __( 'Customer(s) have been notified that this product is in stock.', 'wp-easycart-pro' ) . '</div>';
				$this->print_notify_subscriber_table( ( int ) $_GET['product_id'] );
			echo '</div>'; 
		}
	}
	
	private function print_notify_subscriber_table( $product_id ){
		global $wpdb;
		$date_format = '%b %d, %Y';
		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT product_subscriber_id, email, status, DATE_FORMAT( last_notified, %s ) AS last_notified FROM ec_product_subscriber WHERE product_id = %d ORDER BY email ASC", $date_format, $product_id ) );
		echo '<table class="wp-list-table widefat fixed striped">';
			echo '<thead>';
				echo '<tr>';
					echo '<th>' . __( 'Email', 'wp-easycart-pro' ) . '</th>';
					echo '<th>' . __( 'Status', 'wp-easycart-pro' ) . '</th>';
					echo '<th>' .__( 'Last Notified', 'wp-easycart-pro' ) . '</th>';
					echo '<th width="35%;"></th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				if( count( $subscribers ) > 0 ){
					foreach( $subscribers as $subscriber ){
				echo '<tr>';
					echo '<td>' . $subscriber->email . '</td>';
					echo '<td>' . $subscriber->status . '</td>';
					echo '<td>' . ( ( $subscriber->last_notified ) ? $subscriber->last_notified : __( 'Never', 'wp-easycart-pro' ) ) . '</td>';
					echo '<td align="right">';
						echo '<a href="#" onclick="ec_admin_' . ( ( $subscriber->status == 'subscribed' ) ? 'unsubscribe' : 'subscribe' ) . '_notify_subscriber( ' . $subscriber->product_subscriber_id . ', ' . $product_id . ' ); return false;">' . ( ( $subscriber->status == 'subscribed' ) ? __( 'Unsubscribe', 'wp-easycart-pro' ) : __( 'Subscribe', 'wp-easycart-pro' ) ) . '</a> | ';
						echo '<a href="#" onclick="ec_admin_delete_notify_subscriber( ' . $subscriber->product_subscriber_id . ', ' . $product_id . ' ); return false;">' . __( 'Delete', 'wp-easycart-pro' ) . '</a> | ';
						echo '<a href="#" onclick="ec_admin_notify_subscriber( ' . $subscriber->product_subscriber_id . ', ' . $product_id . ' ); return false;">' . __( 'Notify', 'wp-easycart-pro' ) . '</a>';
					echo '</td>';
				echo '</tr>';
					}
				}else{
					echo '<tr><td colspan="4" style="text-align:center">' . __( 'No Subscribers Available', 'wp-easycart-pro' ) . '</td></tr>';	
				}
			echo '</tbody>';
		echo '</table>';
	}

	public function google_merchant_fields() {
		echo '<div class="ec_admin_row_heading_title">' . esc_attr__( 'Google Merchant', 'wp-easycart-pro' ) . ' - <a href="https://support.google.com/merchants/answer/7052112?hl=en" target="_blank">' . esc_attr__( 'PLEASE REVIEW HERE FOR VALID VALUES!', 'wp-easycart-pro' ) . '</a></div>';
		$product_details = new wp_easycart_admin_details_products_pro();
		$product_details->print_google_merchant_fields();
		echo '<div class="ec_admin_products_submit"><input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_googlemerchant_pro( );" value="' . esc_attr__( 'Update Google Merchant', 'wp-easycart-pro' ) . '" /></div>';
	}
	
	public function add_product_list_settings() {
		wp_easycart_admin()->load_toggle_group( 'ec_option_product_add_to_cart_enable_quantity', 'ec_admin_save_product_options', get_option( 'ec_option_product_add_to_cart_enable_quantity' ), __( 'Product List: Add quantity box when add to cart button shows', 'wp-easycart-pro' ), __( 'Enabling this will add a quantity box with the add to cart button and applies when a product has no options to select.', 'wp-easycart-pro' ) );
	}

	public function maybe_add_location_filter( $filters ) {
		if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
			global $wpdb;
			$locations = $wpdb->get_results( 'SELECT ec_location.location_id AS value, ec_location.location_label AS label FROM ec_location ORDER BY ec_location.location_label ASC' );
			if ( is_array( $locations ) && count( $locations ) > 0 ) {
				$filters[] = array(
					'data' => $locations,
					'label' => __( 'All Locations', 'wp-easycart-pro' ),
					'select' => 'ec_location_to_product.location_id',
					'join' => 'LEFT JOIN ec_location_to_product ON (ec_location_to_product.product_id = ec_product.product_id)',
					'where' => 'ec_location_to_product.location_id = %d',
				);
			}
		}
		return $filters;
	}

	public function process_export_product_optionitem_quantities() {
		if ( isset( $_GET['ec_admin_form_action'] ) && 'export-option-item-quantities' == $_GET['ec_admin_form_action'] ) {
			include( $this->export_product_optionitem_quantities_csv );
			die();
		}
	}

	public function process_export_product_optionitem_images() {
		if ( isset( $_GET['ec_admin_form_action'] ) && 'export-option-item-images' == $_GET['ec_admin_form_action'] ) {
			include( $this->export_product_optionitem_images_csv );
			die();
		}
	}

	public function process_import_product_optionitem_images() {
		if ( isset( $_POST['ec_admin_form_action'] ) && 'import-option-item-images' == $_POST['ec_admin_form_action'] ) {
			$this->import_optionitem_images();
			wp_redirect( 'admin.php?page=wp-easycart-products&subpage=products&product_id=' . (int) $_POST['product_id'] . '&ec_admin_form_action=edit&success=option-item-images-imported#images' );
			die();
		}
	}
	
	public function import_optionitem_images() {
		global $wpdb;
		$product_id = (int) $_POST['product_id'];
		$file_name = sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) );
		$first = true;
		if ( ( $handle = fopen( $file_name, 'r' ) ) !== false ) {
			while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
				if ( ! $first ) {
					if ( count( $data ) >= 7 ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemimage SET product_images = %s, image1 = %s, image2 = %s, image3 = %s, image4 = %s, image5 = %s WHERE product_id = %d AND optionitem_id = %d', $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $product_id, $data[1] ) );
					}
				} else {
					$first = false;
				}
			}
			fclose( $handle );
		}
	}
	
	public function add_new_stock_notification_user( ){
		global $wpdb;
		$found = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_product_subscriber WHERE email = %s AND product_id = %d", $_POST['email'], $_POST['product_id'] ) );
		if( !$found )
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_product_subscriber( email, product_id ) VALUES( %s, %d )", $_POST['email'], $_POST['product_id'] ) );
		$this->print_notify_subscriber_table( ( int ) $_POST['product_id'] );
	}
	
	public function delete_stock_notification_item( ){
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "DELETE FROM ec_product_subscriber WHERE product_subscriber_id = %d", $_POST['product_subscriber_id'] ) );
		$this->print_notify_subscriber_table( ( int ) $_POST['product_id'] );
	}
	
	public function subscribe_stock_notification_item( ){
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET status = 'subscribed' WHERE product_subscriber_id = %d", $_POST['product_subscriber_id'] ) );
		$this->print_notify_subscriber_table( ( int ) $_POST['product_id'] );
	}
	
	public function unsubscribe_stock_notification_item( ){
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET status = 'unsubscribed' WHERE product_subscriber_id = %d", $_POST['product_subscriber_id'] ) );
		$this->print_notify_subscriber_table( ( int ) $_POST['product_id'] );
	}
	
	public function notify_subscriber( ){
		global $wpdb;
		$product_id = $_POST['product_id'];
		$product_subscriber_id = $_POST['product_subscriber_id'];
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET last_notified = NOW( ) WHERE product_subscriber_id = %d", $product_subscriber_id ) );
		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_product_subscriber WHERE product_subscriber_id = %d AND status = 'subscribed'", $product_subscriber_id ) );
		$this->send_notification( $subscribers, $product_id );
		$this->print_notify_subscriber_table( $product_id );
	}
	
	public function notify_all_subscribers( ){
		global $wpdb;
		$product_id = ( int ) $_POST['product_id'];
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET last_notified = NOW( ) WHERE product_id = %d", $product_id ) );
		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_product_subscriber WHERE product_id = %d AND status = 'subscribed'", $product_id ) );
		$this->send_notification( $subscribers, $product_id );
		$this->print_notify_subscriber_table( $product_id );
	}
	
	public function send_notification( $subscribers, $product_id ){
		$db = new ec_db( );
		$result = $db->get_product_list( "WHERE product.product_id = " . (int) $product_id, '', '', '', '' );
		
		$product = new ec_product( $result[0], 0, 1, 0, 0, 0 );
		$email_logo_url = get_option( 'ec_option_email_logo' );
	 	
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		foreach( $subscribers as $subscriber ){
			ob_start();
			if( file_exists( WP_PLUGIN_DIR . '/wp-easycart-data/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_stock_notify_email.php' ) )	
				include WP_PLUGIN_DIR . '/wp-easycart-data/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_stock_notify_email.php';	
			else
				include WP_PLUGIN_DIR . '/wp-easycart/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product_stock_notify_email.php';
			$message = ob_get_clean();
			ob_start();
			
			$email_send_method = get_option( 'ec_option_use_wp_mail' );
			$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );
			
			if( $email_send_method == "1" ){
				wp_mail( $subscriber->email, $GLOBALS['language']->get_text( 'ec_stock_notify_email', 'email_title' ), $message, implode( "\r\n", $headers ) );
				
			}else if( $email_send_method == "0" ){
				$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
				$subject = $GLOBALS['language']->get_text( 'ec_stock_notify_email', 'email_title' );
				$mailer = new wpeasycart_mailer( );
				$mailer->send_order_email( $subscriber->email, $subject, $message );
				
			}else{
				do_action( 'wpeasycart_custom_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $subscriber->email, '', $GLOBALS['language']->get_text( 'ec_stock_notify_email', 'email_title' ), $message );
			}
		}
	}

	public function save_product_details_google_merchant() {
		if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
			global $wpdb;
			$product_id = (int) $_POST['product_id'];

			$array = array(
				'enabled' => ( ( isset( $_POST['enabled'] ) && ( 'yes' == $_POST['enabled'] || 'no' == $_POST['enabled'] ) ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : 'yes' ),
				'title' => sanitize_text_field( wp_unslash( $_POST['title'] ) ),
				'google_product_category' => sanitize_text_field( wp_unslash( $_POST['google_product_category'] ) ),
				'product_type' => sanitize_text_field( wp_unslash( $_POST['product_type'] ) ),
				'identifier_exists' => sanitize_text_field( wp_unslash( $_POST['identifier_exists'] ) ),
				'gtin' => sanitize_text_field( wp_unslash( $_POST['gtin'] ) ),
				'mpn' => sanitize_text_field( wp_unslash( $_POST['mpn'] ) ),
				'availability' => sanitize_text_field( wp_unslash( $_POST['availability'] ) ),
				'condition' => sanitize_text_field( wp_unslash( $_POST['condition'] ) ),
				'availability_date' => sanitize_text_field( wp_unslash( $_POST['availability_date'] ) ),
				'expiration_date' => sanitize_text_field( wp_unslash( $_POST['expiration_date'] ) ),
				'gender' => sanitize_text_field( wp_unslash( $_POST['gender'] ) ),
				'age_group' => sanitize_text_field( wp_unslash( $_POST['age_group'] ) ),
				'size_type' => sanitize_text_field( wp_unslash( $_POST['size_type'] ) ),
				'size_system' => sanitize_text_field( wp_unslash( $_POST['size_system'] ) ),
				'item_group_id' => sanitize_text_field( wp_unslash( $_POST['item_group_id'] ) ),
				'color' => sanitize_text_field( wp_unslash( $_POST['color'] ) ),
				'material' => sanitize_text_field( wp_unslash( $_POST['material'] ) ),
				'pattern' => sanitize_text_field( wp_unslash( $_POST['pattern'] ) ),
				'size' => sanitize_text_field( wp_unslash( $_POST['size'] ) ),
				'weight_type' => sanitize_text_field( wp_unslash( $_POST['weight_type'] ) ),
				'shipping_weight' => sanitize_text_field( wp_unslash( $_POST['shipping_weight'] ) ),
				'unit_pricing_base_measure' => sanitize_text_field( wp_unslash( $_POST['unit_pricing_base_measure'] ) ),
				'unit_pricing_measure' => sanitize_text_field( wp_unslash( $_POST['unit_pricing_measure'] ) ),
				'shipping_label' => sanitize_text_field( wp_unslash( $_POST['shipping_label'] ) ),
				'shipping_unit' => sanitize_text_field( wp_unslash( $_POST['shipping_unit'] ) ),
				'shipping_length' => sanitize_text_field( wp_unslash( $_POST['shipping_length'] ) ),
				'shipping_width' => sanitize_text_field( wp_unslash( $_POST['shipping_width'] ) ),
				'shipping_height' => sanitize_text_field( wp_unslash( $_POST['shipping_height'] ) ),
				'min_handling_time' => sanitize_text_field( wp_unslash( $_POST['min_handling_time'] ) ),
				'max_handling_time' => sanitize_text_field( wp_unslash( $_POST['max_handling_time'] ) ),
				'adult' => sanitize_text_field( wp_unslash( $_POST['adult'] ) ),
				'multipack' => sanitize_text_field( wp_unslash( $_POST['multipack'] ) ),
				'is_bundle' => sanitize_text_field( wp_unslash( $_POST['is_bundle'] ) ),
				'certification' => sanitize_text_field( wp_unslash( $_POST['certification'] ) ),
				'certification_code' => sanitize_text_field( wp_unslash( $_POST['certification_code'] ) ),
				'energy_efficiency_class' => sanitize_text_field( wp_unslash( $_POST['energy_efficiency_class'] ) ),
				'min_energy_efficiency_class' => sanitize_text_field( wp_unslash( $_POST['min_energy_efficiency_class'] ) ),
				'max_energy_efficiency_class' => sanitize_text_field( wp_unslash( $_POST['max_energy_efficiency_class'] ) ),
			);

			$json_array = json_encode( $array );
			$array_exists = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_product_google_attributes WHERE product_id = %d', $product_id ) );
			if ( $array_exists ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product_google_attributes SET attribute_value = %s WHERE product_id = %d', $json_array, $product_id ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product_google_attributes( product_id, attribute_value ) VALUES( %d, %s )', $product_id, $json_array ) );
			}
			wp_cache_flush();
		}
	}
	
	public function save_product_images() {
		if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
			$product_id = ( isset( $_POST['product_id'] ) ) ? $_POST['product_id'] : 0;
			$optionitem_id = ( isset( $_POST['optionitem_id'] ) ) ? $_POST['optionitem_id'] : 0;
			$use_optionitem_images = ( isset( $_POST['use_optionitem_images'] ) && $_POST['use_optionitem_images'] ) ? 1 : 0;
			$images = ( isset( $_POST['images'] ) ) ? $_POST['images'] : array();
			
			global $wpdb;
			if ( $use_optionitem_images ) {
				echo '1';
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product set use_optionitem_images = %d WHERE product_id = %d', $use_optionitem_images, $product_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemimage WHERE optionitem_id = %d AND product_id =%d', $optionitem_id, $product_id ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemimage( optionitem_id, product_id, product_images ) VALUES( %d, %d, %s )', $optionitem_id, $product_id, $images ) );
			} else {
				echo '2';
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product set product_images = %s, use_optionitem_images = %d WHERE product_id = %d', $images, $use_optionitem_images, $product_id ) );
			}
			wp_cache_flush();
		}
	}
	
	public function save_product_images_is_optionitem() {
		if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
			global $wpdb;
			$product_id = ( isset( $_POST['product_id'] ) ) ? $_POST['product_id'] : 0;
			$use_optionitem_images = ( isset( $_POST['use_optionitem_images'] ) && $_POST['use_optionitem_images'] ) ? 1 : 0;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_optionitem_images = %d WHERE product_id = %d', $use_optionitem_images, $product_id ) );
			wp_cache_flush();
		}
	}
	
	public function get_updated_images_panel() {
		if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
			$this->load_images_pro();
		}
	}
	
	public function update_basic_options() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET option_id_1 = %d, option_id_2 = %d, option_id_3 = %d, option_id_4 = %d, option_id_5 = %d WHERE product_id = %d', (int) $_POST['option1'], (int) $_POST['option2'], (int) $_POST['option3'], (int) $_POST['option4'], (int) $_POST['option5'], (int) $_POST['product_id'] ) );
		$option_items_1 = ( 0 != (int) $_POST['option1'] ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $_POST['option1'] ) ) : array();
		$option_items_2 = ( 0 != (int) $_POST['option2'] ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $_POST['option2'] ) ) : array();
		$option_items_3 = ( 0 != (int) $_POST['option3'] ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $_POST['option3'] ) ) : array();
		$option_items_4 = ( 0 != (int) $_POST['option4'] ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $_POST['option4'] ) ) : array();
		$option_items_5 = ( 0 != (int) $_POST['option5'] ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $_POST['option5'] ) ) : array();
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE product_id = %d', (int) $_POST['product_id'] ) );
		$list_count = 0;
		$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
		$first = true;
		for ( $a = 0; $a<count( $option_items_1 ); $a++ ) {
			if ( count( $option_items_2 ) <= 0 ) {
				if ( ! $first ) {
					$query .= ',';
				}
				$query .= $wpdb->prepare( '( %d, %d, 0, 0, 0, 0 )', (int) $_POST['product_id'], $option_items_1[$a]->optionitem_id );
				$list_count++;
				$first = false;
				if ( $list_count >= 100 ) {
					$wpdb->query( $query );
					$first = true;
					$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
					$list_count = 0;
				}
			} else {
				for ( $b = 0; $b<count( $option_items_2 ); $b++ ) {
					if ( count( $option_items_3 ) <= 0 ) {
						if ( ! $first ) {
							$query .= ',';
						}
						$query .= $wpdb->prepare( '( %d, %d, %d, 0, 0, 0 )', (int) $_POST['product_id'], $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id );
						$list_count++;
						$first = false;
						if ( $list_count >= 100 ) {
							$wpdb->query( $query );
							$first = true;
							$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
							$list_count = 0;
						}
					} else {
						for ( $c = 0; $c<count( $option_items_3 ); $c++ ) {
							if ( count( $option_items_4 ) <= 0 ) {
								if ( ! $first ) {
									$query .= ',';
								}
								$query .= $wpdb->prepare( '( %d, %d, %d, %d, 0, 0 )', (int) $_POST['product_id'], $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id );
								$list_count++;
								$first = false;
								if ( $list_count >= 100 ) {
									$wpdb->query( $query );
									$first = true;
									$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
									$list_count = 0;
								}
							} else {
								for ( $d = 0; $d<count( $option_items_4 ); $d++ ) {
									if ( count( $option_items_5 ) <= 0 ) {
										if ( ! $first ) {
											$query .= ',';
										}
										$query .= $wpdb->prepare( '( %d, %d, %d, %d, %d, 0 )', (int) $_POST['product_id'], $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id, $option_items_4[$d]->optionitem_id );
										$list_count++;
										$first = false;
										if ( $list_count >= 100 ) {
											$wpdb->query( $query );
											$first = true;
											$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
											$list_count = 0;
										}
									} else {
										for ( $e = 0; $e<count( $option_items_5 ); $e++ ) {
											if ( ! $first ) {
												$query .= ',';
											}
											$query .= $wpdb->prepare( '( %d, %d, %d, %d, %d, %d )', (int) $_POST['product_id'], $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id, $option_items_4[$d]->optionitem_id, $option_items_5[$e]->optionitem_id );
											$list_count++;
											$first = false;
											if ( $list_count >= 100 ) {
												$wpdb->query( $query );
												$first = true;
												$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
												$list_count = 0;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if ( ! $first ) {
			$wpdb->query( $query );
		}
		wp_cache_flush();
	}
	
	public function update_variant() {
		global $wpdb;
		$price = ( isset( $_POST['price'] ) && '' != $_POST['price'] ) ? $_POST['price'] : '-1.00';
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET sku = %s, price = %s, quantity = %s WHERE optionitemquantity_id = %d AND product_id = %d', $_POST['sku'], $price, $_POST['quantity'], $_POST['optionitemquantity_id'], $_POST['product_id'] ) );
		$this->calculate_variant_to_total_stock();
	}
	
	public function enable_variant() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET is_enabled = 1 WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$this->calculate_variant_to_total_stock( $optionitemquantity_row->product_id );
	}
	
	public function disable_variant() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET is_enabled = 0 WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$this->calculate_variant_to_total_stock( $optionitemquantity_row->product_id );
	}
	
	public function enable_variant_tracking() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET is_stock_tracking_enabled = 1 WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$this->calculate_variant_to_total_stock( $optionitemquantity_row->product_id );
	}
	
	public function disable_variant_tracking() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET is_stock_tracking_enabled = 0 WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', $_POST['optionitemquantity_id'] ) );
		$this->calculate_variant_to_total_stock( $optionitemquantity_row->product_id );
	}

	public function calculate_variant_to_total_stock( $product_id = false ) {
		global $wpdb;
		$product_id = ( ! $product_id && isset( $_POST['product_id'] ) ) ? (int) $_POST['product_id'] : $product_id;
		$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $product_id ) );
		if ( $product && $product->use_optionitem_quantity_tracking ) {
			$stock_count = 0;
			$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );
			foreach ( $optionitems as $optionitem ) {
				if ( $optionitem->is_enabled && $optionitem->is_stock_tracking_enabled ) {
					$stock_count += $optionitem->quantity;
				} else if ( $optionitem->is_enabled ) {
					$stock_count += 1;
				}
			}
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE product_id = %d', $stock_count, $product->product_id ) );
		}
		wp_cache_flush();
	}

	public function add_modifier() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option_to_product( option_id, product_id ) VALUES( %d, %d )', $_POST['option_id'], $_POST['product_id'] ) );
		do_action( 'wp_easycart_modifier_to_product_created', (int) $_POST['option_id'], (int) $_POST['product_id'] );
		wp_cache_flush();
	}
	
	public function remove_modifier() {
		global $wpdb;
		do_action( 'wp_easycart_modifier_to_product_deleted', (int) $_POST['option_to_product_id'] );
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option_to_product WHERE option_to_product_id = %d', $_POST['option_to_product_id'] ) );
		wp_cache_flush();
	}
	
	public function save_modifier_sort() {
		global $wpdb;
		$sort_order = 1;
		foreach ( $_POST['option_to_product_ids'] as $option_to_product_id ) {
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET option_order = %d WHERE option_to_product_id = %d', $sort_order, $option_to_product_id ) );
			$sort_order++;
		}
		wp_cache_flush();
	}
	
	public function enable_modifier_logic() {
		global $wpdb;
		$option_to_product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE option_to_product_id = %d', (int) $_POST['option_to_product_id'] ) );
		$logic = json_decode( $option_to_product_row->conditional_logic );
		$logic->enabled = true;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET conditional_logic = %s WHERE option_to_product_id = %d', json_encode( $logic ), (int) $_POST['option_to_product_id'] ) );
		wp_cache_flush();
	}
	
	public function disable_modifier_logic() {
		global $wpdb;
		$option_to_product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE option_to_product_id = %d', (int) $_POST['option_to_product_id'] ) );
		$logic = json_decode( $option_to_product_row->conditional_logic );
		$logic->enabled = false;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET conditional_logic = %s WHERE option_to_product_id = %d', json_encode( $logic ), (int) $_POST['option_to_product_id'] ) );
		wp_cache_flush();
	}
	
	public function save_modifier_logic() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET conditional_logic = %s WHERE option_to_product_id = %d', wp_unslash( $_POST['conditional_logic'] ), (int) $_POST['option_to_product_id'] ) );
		wp_cache_flush();
	}
	
	public function get_basic_option_rows( $product ) {
		global $wpdb;
		$option_set_1 = ( $product && 0 != $product->option_id_1 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_1 ) ) : false;
		$option_items_1 = ( $product && 0 != $product->option_id_1 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_1 ) ) : false;
		$option_set_2 = ( $product && 0 != $product->option_id_2 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_2 ) ) : false;
		$option_items_2 = ( $product && 0 != $product->option_id_2 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_2 ) ) : false;
		$option_set_3 = ( $product && 0 != $product->option_id_3 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_3 ) ) : false;
		$option_items_3 = ( $product && 0 != $product->option_id_3 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_3 ) ) : false;
		$option_set_4 = ( $product && 0 != $product->option_id_4 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_4 ) ) : false;
		$option_items_4 = ( $product && 0 != $product->option_id_4 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_4 ) ) : false;
		$option_set_5 = ( $product && 0 != $product->option_id_5 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_5 ) ) : false;
		$option_items_5 = ( $product && 0 != $product->option_id_5 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_5 ) ) : false;
		$ret_string = '';
		if ( $option_set_1 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable" data-option-id="' . esc_attr( $option_set_1->option_id ) . '">
				<div class="wp-easycart-pro-option-table-item-drag">
					<span class="dashicons dashicons-menu-alt3"></span>
				</div>
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20  wp-easycart-pro-option-table-label">
						' . esc_attr( $option_set_1->option_name ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-80  wp-easycart-pro-option-table-label">';
			$ret_string .= ( count( $option_items_1 ) == 0 ) ? __( 'No Items', 'wp-easycart-pro' ) : '';
			for ( $i = 0; $i < count( $option_items_1 ); $i++ ) {
				$ret_string .= ( $i > 0 ) ? ', ' : '';
				$ret_string .= esc_attr( $option_items_1[$i]->optionitem_name );
			}
			$ret_string .= '
					</div>
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $option_set_1->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $option_set_1->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="#" onclick="return wp_easycart_pro_remove_option( 1 );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
		}
		if ( $option_set_2 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable" data-option-id="' . esc_attr( $option_set_2->option_id ) . '">
				<div class="wp-easycart-pro-option-table-item-drag">
					<span class="dashicons dashicons-menu-alt3"></span>
				</div>
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20  wp-easycart-pro-option-table-label">
						' . esc_attr( $option_set_2->option_name ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-80  wp-easycart-pro-option-table-label">';
			$ret_string .= ( count( $option_items_2 ) == 0 ) ? __( 'No Items', 'wp-easycart-pro' ) : '';
			for ( $i = 0; $i < count( $option_items_2 ); $i++ ) {
				$ret_string .= ( $i > 0 ) ? ', ' : '';
				$ret_string .= esc_attr( $option_items_2[$i]->optionitem_name );
			}
			$ret_string .= '
					</div>
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $option_set_2->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $option_set_2->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="#" onclick="return wp_easycart_pro_remove_option( 2 );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
		}
		if ( $option_set_3 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable" data-option-id="' . esc_attr( $option_set_3->option_id ) . '">
				<div class="wp-easycart-pro-option-table-item-drag">
					<span class="dashicons dashicons-menu-alt3"></span>
				</div>
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20  wp-easycart-pro-option-table-label">
						' . esc_attr( $option_set_3->option_name ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-80  wp-easycart-pro-option-table-label">';
			$ret_string .= ( count( $option_items_3 ) == 0 ) ? __( 'No Items', 'wp-easycart-pro' ) : '';
			for ( $i = 0; $i < count( $option_items_3 ); $i++ ) {
				$ret_string .= ( $i > 0 ) ? ', ' : '';
				$ret_string .= esc_attr( $option_items_3[$i]->optionitem_name );
			}
			$ret_string .= '
					</div>
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $option_set_3->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $option_set_3->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="#" onclick="return wp_easycart_pro_remove_option( 3 );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
		}
		if ( $option_set_4 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable" data-option-id="' . esc_attr( $option_set_4->option_id ) . '">
				<div class="wp-easycart-pro-option-table-item-drag">
					<span class="dashicons dashicons-menu-alt3"></span>
				</div>
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20  wp-easycart-pro-option-table-label">
						' . esc_attr( $option_set_4->option_name ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-80  wp-easycart-pro-option-table-label">';
			$ret_string .= ( count( $option_items_4 ) == 0 ) ? __( 'No Items', 'wp-easycart-pro' ) : '';
			for ( $i = 0; $i < count( $option_items_4 ); $i++ ) {
				$ret_string .= ( $i > 0 ) ? ', ' : '';
				$ret_string .= esc_attr( $option_items_4[$i]->optionitem_name );
			}
			$ret_string .= '
					</div>
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $option_set_4->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $option_set_4->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="#" onclick="return wp_easycart_pro_remove_option( 4 );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
		}
		if ( $option_set_5 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable" data-option-id="' . esc_attr( $option_set_5->option_id ) . '">
				<div class="wp-easycart-pro-option-table-item-drag">
					<span class="dashicons dashicons-menu-alt3"></span>
				</div>
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20  wp-easycart-pro-option-table-label">
						' . esc_attr( $option_set_5->option_name ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-80  wp-easycart-pro-option-table-label">';
			$ret_string .= ( count( $option_items_5 ) == 0 ) ? __( 'No Items', 'wp-easycart-pro' ) : '';
			for ( $i = 0; $i < count( $option_items_5 ); $i++ ) {
				$ret_string .= ( $i > 0 ) ? ', ' : '';
				$ret_string .= esc_attr( $option_items_5[$i]->optionitem_name );
			}
			$ret_string .= '
					</div>
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $option_set_5->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $option_set_5->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
							<li><a href="#" onclick="return wp_easycart_pro_remove_option( 5 );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
		}
		$ret_string .= '
			<div class="wp-easycart-pro-option-table-row" style="border:none; padding:5px 0;">
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="padding-left:56px;">
						<a href="#" class="wp-easycart-pro-option-modal-add-button" onclick="return wp_easycart_pro_add_basic_option();" style="margin:0; padding:6px 18px;">' . esc_attr__( 'Add New', 'wp-easycart-pro' ) . '</a>
					</div>
				</div>
			</div>';
		return $ret_string;
	}
	
	public function get_variant_rows( $product, $curr_page = 1, $option_item_id_1 = 0, $option_item_id_2 = 0, $option_item_id_3 = 0, $option_item_id_4 = 0, $option_item_id_5 = 0, $is_enabled = 1 ) {
		global $wpdb;
		$perpage = 20;
		$optionitem_quantity = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d', $product->product_id ) . ( ( 0 != $option_item_id_1 ) ? $wpdb->prepare( ' AND optionitem_id_1 = %d', $option_item_id_1 ) : '' ) . ( ( 0 != $option_item_id_2 ) ? $wpdb->prepare( ' AND optionitem_id_2 = %d', $option_item_id_2 ) : '' ) . ( ( 0 != $option_item_id_3 ) ? $wpdb->prepare( ' AND optionitem_id_3 = %d', $option_item_id_3 ) : '' ) . ( ( 0 != $option_item_id_4 ) ? $wpdb->prepare( ' AND optionitem_id_4 = %d', $option_item_id_4 ) : '' ) . ( ( 0 != $option_item_id_5 ) ? $wpdb->prepare( ' AND optionitem_id_5 = %d', $option_item_id_5 ) : '' ) . ( ( '1' == $is_enabled ) ? ' AND is_enabled = 1' : '' ) . ( ( '0' == $is_enabled ) ? ' AND is_enabled = 0' : '' ) . $wpdb->prepare( ' ORDER BY optionitem_id_1 ASC, optionitem_id_2 ASC, optionitem_id_3 ASC, optionitem_id_4 ASC, optionitem_id_5 ASC LIMIT %d, %d', ( ( $curr_page - 1 ) * $perpage ), $perpage ) );
		$optionitem_quantity_total = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT( optionitemquantity_id ) FROM ec_optionitemquantity WHERE product_id = %d', $product->product_id ) . ( ( 0 != $option_item_id_1 ) ? $wpdb->prepare( ' AND optionitem_id_1 = %d', $option_item_id_1 ) : '' ) . ( ( 0 != $option_item_id_2 ) ? $wpdb->prepare( ' AND optionitem_id_2 = %d', $option_item_id_2 ) : '' ) . ( ( 0 != $option_item_id_3 ) ? $wpdb->prepare( ' AND optionitem_id_3 = %d', $option_item_id_3 ) : '' ) . ( ( 0 != $option_item_id_4 ) ? $wpdb->prepare( ' AND optionitem_id_4 = %d', $option_item_id_4 ) : '' ) . ( ( 0 != $option_item_id_5 ) ? $wpdb->prepare( ' AND optionitem_id_5 = %d', $option_item_id_5 ) : '' ) . ( ( '1' == $is_enabled ) ? ' AND is_enabled = 1' : '' ) . ( ( '0' == $is_enabled ) ? ' AND is_enabled = 0' : '' ) );
		$pages_total = ceil( $optionitem_quantity_total / $perpage );

		$option_set_1 = ( $product && 0 != $product->option_id_1 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_1 ) ) : false;
		$option_set_2 = ( $product && 0 != $product->option_id_2 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_2 ) ) : false;
		$option_set_3 = ( $product && 0 != $product->option_id_3 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_3 ) ) : false;
		$option_set_4 = ( $product && 0 != $product->option_id_4 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_4 ) ) : false;
		$option_set_5 = ( $product && 0 != $product->option_id_5 ) ? $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $product->option_id_5 ) ) : false;

		$option_items_1 = ( $product && 0 != $product->option_id_1 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_1 ) ) : array();
		$option_items_2 = ( $product && 0 != $product->option_id_2 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_2 ) ) : array();
		$option_items_3 = ( $product && 0 != $product->option_id_3 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_3 ) ) : array();
		$option_items_4 = ( $product && 0 != $product->option_id_4 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_4 ) ) : array();
		$option_items_5 = ( $product && 0 != $product->option_id_5 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $product->option_id_5 ) ) : array();

		$ret_string = '';
		if ( $optionitem_quantity_total > 500 ) {
			$ret_string .= '<div class="wp-easycart-pro-table-limits-note">' . esc_attr__( 'You seem to have a lot of variants. If you are not tracking stock, SKU, or price by variant, we recommend switching your option sets into modifiers.', 'wp-easycart-pro' ) . '</div>';
		}
		if ( $option_set_1 || $option_set_2 || $option_set_3 || $option_set_4 || $option_set_5 ) {
			$ret_string .= '<div class="wp-easycart-pro-variant-filters">';
			$ret_string .= '<span class="wp-easycart-pro-variant-filters-label">' . esc_attr__( 'Filter View: ', 'wp-easycart-pro' ) . '</span>';
			$ret_string .= '<select id="wpec_variant_filter_enabled" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="1"' . ( ( '1' == $is_enabled ) ? ' selected="selected"' : '' ) . '>' . esc_attr__( 'Enabled Only', 'wp-easycart-pro' ) . '</option>';
				$ret_string .= '<option value="0"' . ( ( '0' == $is_enabled ) ? ' selected="selected"' : '' ) . '>' . esc_attr__( 'Disabled Only', 'wp-easycart-pro' ) . '</option>';
				$ret_string .= '<option value="all"' . ( ( 'all' == $is_enabled ) ? ' selected="selected"' : '' ) . '>' . esc_attr__( 'Enabled and Disabled', 'wp-easycart-pro' ) . '</option>';
			$ret_string .= '</select>';
			if ( $option_set_1 && is_array( $option_items_1 ) && count( $option_items_1 ) > 0 ) {
				$ret_string .= '<select id="wpec_variant_filter_1" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="0">' . esc_attr( $option_set_1->option_name ) . '</option>';
				foreach ( $option_items_1 as $option_item ) {
					$ret_string .= '<option value="' . esc_attr( (int) $option_item->optionitem_id ) . '"' . ( ( $option_item_id_1 == $option_item->optionitem_id ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $option_item->optionitem_name ) . '</option>';
				}
				$ret_string .= '</select>';
			}
			if ( $option_set_2 && is_array( $option_items_2 ) && count( $option_items_2 ) > 0 ) {
				$ret_string .= '<select id="wpec_variant_filter_2" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="0">' . esc_attr( $option_set_2->option_name ) . '</option>';
				foreach ( $option_items_2 as $option_item ) {
					$ret_string .= '<option value="' . esc_attr( (int) $option_item->optionitem_id ) . '"' . ( ( $option_item_id_2 == $option_item->optionitem_id ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $option_item->optionitem_name ) . '</option>';
				}
				$ret_string .= '</select>';
			}
			if ( $option_set_3 && is_array( $option_items_3 ) && count( $option_items_3 ) > 0 ) {
				$ret_string .= '<select id="wpec_variant_filter_3" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="0">' . esc_attr( $option_set_3->option_name ) . '</option>';
				foreach ( $option_items_3 as $option_item ) {
					$ret_string .= '<option value="' . esc_attr( (int) $option_item->optionitem_id ) . '"' . ( ( $option_item_id_3 == $option_item->optionitem_id ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $option_item->optionitem_name ) . '</option>';
				}
				$ret_string .= '</select>';
			}
			if ( $option_set_4 && is_array( $option_items_4 ) && count( $option_items_4 ) > 0 ) {
				$ret_string .= '<select id="wpec_variant_filter_4" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="0">' . esc_attr( $option_set_4->option_name ) . '</option>';
				foreach ( $option_items_4 as $option_item ) {
					$ret_string .= '<option value="' . esc_attr( (int) $option_item->optionitem_id ) . '"' . ( ( $option_item_id_4 == $option_item->optionitem_id ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $option_item->optionitem_name ) . '</option>';
				}
				$ret_string .= '</select>';
			}
			if ( $option_set_5 && is_array( $option_items_5 ) && count( $option_items_5 ) > 0 ) {
				$ret_string .= '<select id="wpec_variant_filter_5" class="wpec_pro_variant_filter">';
				$ret_string .= '<option value="0">' . esc_attr( $option_set_5->option_name ) . '</option>';
				foreach ( $option_items_5 as $option_item ) {
					$ret_string .= '<option value="' . esc_attr( (int) $option_item->optionitem_id ) . '"' . ( ( $option_item_id_5 == $option_item->optionitem_id ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $option_item->optionitem_name ) . '</option>';
				}
				$ret_string .= '</select>';
			}
			$ret_string .= '</div>';
		}
		$ret_string .= '<div class="wp-easycart-pro-option-table-paging">
			<div class="wp-easycart-pro-option-table-paging-count">' . esc_attr__( 'Showing', 'wp-easycart-pro' ) . ' <span class="wpeasycart_option_table_count_start">' . esc_attr( ( ( $curr_page - 1 ) * $perpage ) + 1 ) . '</span> - <span class="wpeasycart_option_table_count_end">' . esc_attr( ( $perpage * $curr_page < $optionitem_quantity_total ) ? ( $perpage * $curr_page ) : $optionitem_quantity_total ) . '</span> ' . esc_attr__( 'of', 'wp-easycart-pro' ) . ' ' . esc_attr( number_format( $optionitem_quantity_total, 0, '', ',' ) ) . ' ' . esc_attr__( 'Variants', 'wp-eascart-pro' ) . '</div>';
		if ( $pages_total > 1 ) {
			$ret_string .= '<div class="wp-easycart-pro-option-table-paging-pages">';
			$ret_string .= '<span class="wpeasycart_option_table_current_page">' . esc_attr__( 'Page', 'wp-easycart-pro' ) . ' <span id="wpeasycart_option_table_current_page">' . esc_attr( $curr_page ) . '</span> ' . esc_attr__( 'of', 'wp-easycart-pro' ) . ' ' . esc_attr( number_format( $pages_total, 0, '', ',' ) ) . '</span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-skipback' . esc_attr( ( $curr_page == 1 ) ? ' disabled' : '' ) . '" data-page="1"></span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-back' . esc_attr( ( $curr_page == 1 ) ? ' disabled' : '' ) . '" data-page="' . esc_attr( ( $curr_page - 6 > 0 ) ? ( $curr_page - 6 ) : 1 ) . '"></span>';

			if ( $curr_page + 4 > $pages_total ) {
				$start_i = ( $pages_total > 5 ) ? $pages_total - 4 : 1;
			} else if ( $curr_page - 2 < 1 ) {
				$start_i = 1;
			} else {
				$start_i = $curr_page - 2;
			}

			for ( $i = $start_i; $i < $start_i + 5 && $i <= $pages_total; $i++ ) {
				$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button" data-page="' . esc_attr( $i ) . '">' . esc_attr( $i ) . '</span>';
			}
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-forward' . esc_attr( ( $curr_page == $pages_total ) ? ' disabled' : '' ) . '" data-page="' . esc_attr( ( $curr_page + 6 <= $pages_total ) ? ( $curr_page + 6 ) : $pages_total ) . '"></span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-skipforward' . esc_attr( ( $curr_page == $pages_total ) ? ' disabled' : '' ) . '" data-page="' . $pages_total . '"></span>';
			$ret_string .= '</div>';
		}
		$ret_string .= '
		</div>';
		$ret_string .= '
		<div class="wp-easycart-pro-option-table-header">
			<div class="wp-easycart-pro-option-table-column-group">
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-45">
					' . esc_attr__( 'Variation', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-input">
					' . esc_attr__( 'SKU', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-input">
					' . esc_attr__( 'Price', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-input">
					' . esc_attr__( 'Stock', 'wp-easycart-pro' ) . '
				</div>
			</div>
		</div>';
		if( count( $optionitem_quantity ) <= 0 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row">
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="padding:25px 0; justify-content:center;">
						<strong>' . esc_attr__( 'No Variants Setup. Add Product Options to Start.', 'wp-easycart-pro' ) . '</strong>
					</div>
				</div>
			</div>';
		} else {
			$use_optionitem_stock_tracking = false;
			$use_basic_stock_tracking = false;
			if ( $product->use_optionitem_quantity_tracking ) {
				$use_optionitem_stock_tracking = true;
			} else if ( $product->show_stock_quantity ) {
				$use_basic_stock_tracking = true;
			}
			foreach( $optionitem_quantity as $optionitem_quantity_row ) {
				$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-variant-row' . ( ( ! $optionitem_quantity_row->is_enabled ) ? ' is-disabled' : '' ) . '" data-optionitem-quantity-id="' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" id="wpec_variant_row_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '">
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-45 wp-easycart-pro-option-table-label">
						' . esc_attr( $this->wp_easycart_pro_options_get_optionitem_name( $option_items_1, $optionitem_quantity_row->optionitem_id_1 ) ) . '
						' . ( ( 0 != $optionitem_quantity_row->optionitem_id_2 ) ? ', ' . esc_attr( $this->wp_easycart_pro_options_get_optionitem_name( $option_items_2, $optionitem_quantity_row->optionitem_id_2 ) ) : '' ) . '
						' . ( ( 0 != $optionitem_quantity_row->optionitem_id_3 ) ? ', ' . esc_attr( $this->wp_easycart_pro_options_get_optionitem_name( $option_items_3, $optionitem_quantity_row->optionitem_id_3 ) ) : '' ) . '
						' . ( ( 0 != $optionitem_quantity_row->optionitem_id_4 ) ? ', ' . esc_attr( $this->wp_easycart_pro_options_get_optionitem_name( $option_items_4, $optionitem_quantity_row->optionitem_id_4 ) ) : '' ) . '
						' . ( ( 0 != $optionitem_quantity_row->optionitem_id_5 ) ? ', ' . esc_attr( $this->wp_easycart_pro_options_get_optionitem_name( $option_items_5, $optionitem_quantity_row->optionitem_id_5 ) ) : '' ) . '
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-input">
						<input type="text" value="' . esc_attr( ( '' != $optionitem_quantity_row->sku ) ? $optionitem_quantity_row->sku : '' ) . '" placeholder="' . esc_attr__( 'Default Settings', 'wp-easycart-pro' ) . '" id="wpec_variant_sku_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-input">
						<input type="text" value="' . esc_attr( ( -1 != $optionitem_quantity_row->price ) ? number_format( $optionitem_quantity_row->price, 2, '.', '' ) : '' ) . '" placeholder="' . esc_attr__( 'Default Settings', 'wp-easycart-pro' ) . '" id="wpec_variant_price_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>';
				if ( ! $optionitem_quantity_row->is_stock_tracking_enabled ) {
				$ret_string .= '
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-input wp-easycart-pro-variant-tracking-disabled">
						<input type="text" readonly="readonly" value="" placeholder="∞" id="wpec_variant_quantity_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>';
					
				} else if ( $use_optionitem_stock_tracking ) {
				$ret_string .= '
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-input">
						<input type="number" step="1" value="' . esc_attr( $optionitem_quantity_row->quantity ) . '" id="wpec_variant_quantity_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>';
				} else if ( $use_basic_stock_tracking ) {
				$ret_string .= '
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-input wp-easycart-pro-variant-tracking-disabled">
						<input type="text" readonly="readonly" value="" placeholder="' . esc_attr__( 'Tracking Items Disabled', 'wp-easycart-pro' ) . '" id="wpec_variant_quantity_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>';
				} else {
				$ret_string .= '
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-input wp-easycart-pro-variant-tracking-disabled">
						<input type="text" readonly="readonly" value="" placeholder="' . esc_attr__( 'Item Stock Disabled', 'wp-easycart-pro' ) . '" id="wpec_variant_quantity_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '" />
					</div>';
				}
				$ret_string .= '
				</div>
				<div class="wp-easycart-pro-option-table-item-actions">
					<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
					<div class="wp-easycart-pro-option-table-item-action-items">
						<ul>
							<li' . ( ( $optionitem_quantity_row->is_enabled ) ? ' style="display:none;"' : '' ) . ' id="wpec_variant_enable_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '"><a href="#" onclick="return wp_easycart_pro_enable_variation( ' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . ' );">' . esc_attr__( 'Enable', 'wp-easycart-pro' ) . '</a></li>
							<li' . ( ( ! $optionitem_quantity_row->is_enabled ) ? ' style="display:none;"' : '' ) . ' id="wpec_variant_disable_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '"><a href="#" onclick="return wp_easycart_pro_disable_variation( ' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . ' );">' . esc_attr__( 'Disable', 'wp-easycart-pro' ) . '</a></li>
							<li' . ( ( $optionitem_quantity_row->is_stock_tracking_enabled ) ? ' style="display:none;"' : '' ) . ' id="wpec_variant_tracking_enable_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '"><a href="#" onclick="return wp_easycart_pro_enable_variation_tracking( ' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . ' );">' . esc_attr__( 'Enable Stock Tracking', 'wp-easycart-pro' ) . '</a></li>
							<li' . ( ( ! $optionitem_quantity_row->is_stock_tracking_enabled ) ? ' style="display:none;"' : '' ) . ' id="wpec_variant_tracking_disable_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '"><a href="#" onclick="return wp_easycart_pro_disable_variation_tracking( ' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . ' );">' . esc_attr__( 'Disable Stock Tracking', 'wp-easycart-pro' ) . '</a></li>
							<li id="wpec_variant_google_merchant_' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . '"><a href="#" onclick="return wp_easycart_pro_open_google_merchant_variant( ' . esc_attr( $optionitem_quantity_row->optionitemquantity_id ) . ' );">' . esc_attr__( 'Google Merchant', 'wp-easycart-pro' ) . '</a></li>
						</ul>
					</div>
				</div>
			</div>';
			}
		}
		$ret_string .= '<div class="wp-easycart-pro-option-table-paging">
			<div class="wp-easycart-pro-option-table-paging-count">' . esc_attr__( 'Showing', 'wp-easycart-pro' ) . ' <span class="wpeasycart_option_table_count_start">' . esc_attr( ( ( $curr_page - 1 ) * $perpage ) + 1 ) . '</span> - <span class="wpeasycart_option_table_count_end">' . esc_attr( ( $perpage * $curr_page < $optionitem_quantity_total ) ? ( $perpage * $curr_page ) : $optionitem_quantity_total ) . '</span> ' . esc_attr__( 'of', 'wp-easycart-pro' ) . ' ' . esc_attr( number_format( $optionitem_quantity_total, 0, '', ',' ) ) . ' ' . esc_attr__( 'Variants', 'wp-eascart-pro' ) . '</div>';
		if ( $pages_total > 1 ) {
			$ret_string .= '<div class="wp-easycart-pro-option-table-paging-pages">';
			$ret_string .= '<span class="wpeasycart_option_table_current_page">' . esc_attr__( 'Page', 'wp-easycart-pro' ) . ' ' . esc_attr( $curr_page ) . ' ' . esc_attr__( 'of', 'wp-easycart-pro' ) . ' ' . esc_attr( number_format( $pages_total, 0, '', ',' ) ) . '</span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-skipback' . esc_attr( ( $curr_page == 1 ) ? ' disabled' : '' ) . '" data-page="1"></span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-back' . esc_attr( ( $curr_page == 1 ) ? ' disabled' : '' ) . '" data-page="' . esc_attr( ( $curr_page - 6 > 0 ) ? ( $curr_page - 6 ) : 1 ) . '"></span>';

			if ( $curr_page + 4 > $pages_total ) {
				$start_i = ( $pages_total > 5 ) ? $pages_total - 4 : 1;
			} else if ( $curr_page - 2 < 1 ) {
				$start_i = 1;
			} else {
				$start_i = $curr_page - 2;
			}

			for ( $i = $start_i; $i < $start_i + 5 && $i <= $pages_total; $i++ ) {
				$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button" data-page="' . esc_attr( $i ) . '">' . esc_attr( $i ) . '</span>';
			}
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-forward' . esc_attr( ( $curr_page == $pages_total ) ? ' disabled' : '' ) . '" data-page="' . esc_attr( ( $curr_page + 6 <= $pages_total ) ? ( $curr_page + 6 ) : $pages_total ) . '"></span>';
			$ret_string .= '<span class="wp-easycart-pro-option-table-paging-button dashicons dashicons-controls-skipforward' . esc_attr( ( $curr_page == $pages_total ) ? ' disabled' : '' ) . '" data-page="' . $pages_total . '"></span>';
			$ret_string .= '</div>';
		}
		$ret_string .= '
		</div>';
		return $ret_string;
	}
	public function get_modifier_rows( $product ) {
		global $wpdb;
		$advanced_options = $wpdb->get_results( $wpdb->prepare( "SELECT ec_option.*, ec_option_to_product.product_id, ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option_to_product.option_order ASC, ec_option.option_name ASC", $product->product_id ) );
		$ret_string = '<div class="wp-easycart-pro-option-table-header">
			<div class="wp-easycart-pro-option-table-column-group">
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-45">
					' . esc_attr__( 'Modifier Name', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20">
					' . esc_attr__( 'Conditional Logic', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-20">
					' . esc_attr__( 'Modifier Type', 'wp-easycart-pro' ) . '
				</div>
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15">
					' . esc_attr__( 'Required', 'wp-easycart-pro' ) . '
				</div>
			</div>
		</div>';
		if( count( $advanced_options ) == 0 ) {
			$ret_string .= '
			<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-modifer-row">
				<div class="wp-easycart-pro-option-table-column-group">
					<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-center wp-easycart-pro-option-table-column-100" style="padding:25px 0; justify-content:center;">
						' . esc_attr__( 'No Modifiers (Advanced Options) Selected', 'wp-easycart-pro' ) . '
					</div>
				</div>
			</div>';
		} else {
			foreach( $advanced_options as $advanced_option ) {
				$conditional_logic = ( isset( $advanced_option->conditional_logic ) && $advanced_option->conditional_logic ) ? $advanced_option->conditional_logic : false;
				if ( $conditional_logic ) {
					$conditional_logic_json = json_decode( $conditional_logic );
					$conditional_logic = $conditional_logic_json->enabled;
				}
				$ret_string .= '
				<div class="wp-easycart-pro-option-table-row wp-easycart-pro-option-table-row-sortable wp-easycart-pro-option-modifer-row" id="wpec_modifier_row_' . esc_attr( $advanced_option->option_to_product_id ) . '" data-option-id="' . esc_attr( $advanced_option->option_to_product_id ) . '">
					<div class="wp-easycart-pro-option-table-item-drag">
						<span class="dashicons dashicons-menu-alt3"></span>
					</div>
					<div class="wp-easycart-pro-option-table-column-group">
						<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-45 wp-easycart-pro-option-table-label">
							<span class="wp-easycart-pro-option-table-modifier-label">' . esc_attr( $advanced_option->option_name ) . '</span>
							<span class="wp-easycart-pro-option-table-conditional-logic' . ( ( ! $conditional_logic ) ? ' is-disabled' : '' ) . '" id="wpec_edit_conditional_logic_' . esc_attr( $advanced_option->option_to_product_id ) . '" onclick="return wp_easycart_pro_edit_conditional_logic( ' . esc_attr( $advanced_option->option_to_product_id ) . ' );">Edit Conditional Logic Rules</span>
						</div>
						<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-label">
							<div class="wp-easycart-admin-toggle-group" style="top:4px;">
								<input type="checkbox" id="wpec_modifier_' . esc_attr( $advanced_option->option_to_product_id ) . '" onchange="wp_easycart_pro_modifier_conditional_logic( ' . esc_attr( $advanced_option->option_to_product_id ) . ' );"' . ( ( $conditional_logic ) ? ' checked="checked"' : '' ) . ' /> 
								<label for="wpec_modifier_' . esc_attr( $advanced_option->option_to_product_id ) . '">
									<span class="wp-easycart-admin-aural">' . esc_attr__( 'Enable', 'wp-easycart-pro' ) . '</span>
								</label>
								<div class="wp-easycart-admin-onoffswitch wp-easycart-admin-pull-right" aria-hidden="true">
									<div class="wp-easycart-admin-onoffswitch-label">
										<div class="wp-easycart-admin-onoffswitch-inner"></div>
										<div class="wp-easycart-admin-onoffswitch-switch">
											<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
											<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-20 wp-easycart-pro-option-table-label">
							' . esc_attr( $advanced_option->option_type ) . '
						</div>
						<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-15 wp-easycart-pro-option-table-label">
							' . esc_attr( ( $advanced_option->option_required ) ? __( 'Yes', 'wp-easycart-pro' ) : __( 'No', 'wp-easycart-pro' ) ) . '
						</div>
					</div>
					<div class="wp-easycart-pro-option-table-item-actions">
						<div class="wp-easycart-pro-option-table-item-action-trigger">
						<span class="dashicons dashicons-ellipsis"></span>
					</div>
						<div class="wp-easycart-pro-option-table-item-action-items">
							<ul>
								<li><a href="admin.php?page=wp-easycart-products&subpage=option&option_id=' . esc_attr( $advanced_option->option_id ) . '&ec_admin_form_action=edit&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-action-edit' ) ) . '" target="_blank">' . esc_attr__( 'Edit', 'wp-easycart-pro' ) . '</a></li>
								<li><a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=' . esc_attr( $advanced_option->option_id ) . '" target="_blank">' . esc_attr__( 'Manage Items', 'wp-easycart-pro' ) . '</a></li>
								<li><a href="#" onclick="return wp_easycart_pro_remove_modifier( ' . esc_attr( $advanced_option->option_to_product_id ) . ' );">' . esc_attr__( 'Remove', 'wp-easycart-pro' ) . '</a></li>
							</ul>
						</div>
					</div>
				</div>';
			}
		}
		$ret_string .= '
		<div class="wp-easycart-pro-option-table-row" style="border:none; padding:5px 0;">
			<div class="wp-easycart-pro-option-table-column-group">
				<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="padding-left:56px;">
					<a href="#" class="wp-easycart-pro-option-modal-add-button" onclick="return wp_easycart_pro_add_advanced_option();" style="margin:0; padding:6px 18px;">' . esc_attr__( 'Add New', 'wp-easycart-pro' ) . '</a>
				</div>
			</div>
		</div>';
		return $ret_string;
	}
	
	private function wp_easycart_pro_options_get_optionitem_name( $option_items, $id ) {
		for ( $i=0; $i < count( $option_items ); $i++ ) {
			if ( $option_items[$i]->optionitem_id == $id ) {
				return $option_items[$i]->optionitem_name;
			}
		}
		return __( 'Name Missing', 'wp-easycart-pro' );
	}
	
	public function get_product_option_logic() {
		if ( ! isset( $_POST['option_to_product_id'] ) ) {
			return '';
		}
		
		global $wpdb;
		$conditional_logic_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE option_to_product_id = %d', $_POST['option_to_product_id'] ) );
		$conditional_logic = ( $conditional_logic_row ) ? json_decode( $conditional_logic_row->conditional_logic ) : false;
		$select_one = array( 
			(object) array(
				'option_id' =>'',
				'option_to_product_id' =>'',
				'option_label' => __( 'Select One', 'wp-easycart-pro' ),
			)
		);
		$available_options = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_option_to_product.option_to_product_id, ec_option.option_name as option_label, ec_option.option_type, ec_option.option_id FROM ec_option, ec_option_to_product WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id', $conditional_logic_row->product_id ) );
		for ( $i=0; $i < count( $available_options ); $i++ ) {
			if ( 'combo' == $available_options[$i]->option_type || 'swatch' == $available_options[$i]->option_type || 'radio' == $available_options[$i]->option_type || 'checkbox' == $available_options[$i]->option_type || 'grid' == $available_options[$i]->option_type ) {
				$available_options[$i]->optionset_html = '<select class="select2 wp-easycart-pro-conditional-logic-optionitem wp-easycart-pro-conditional-logic-optionitem-' . $available_options[$i]->option_to_product_id . '" style="display:none;"><option value="">' . esc_attr__( 'Select One', 'wp-easycart-pro' ) . '</option>';
				$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order', $available_options[$i]->option_id ) );
				foreach( $optionitems as $optionitem ) {
					$available_options[$i]->optionset_html .= '<option value="' . $optionitem->optionitem_id . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
				}
				$available_options[$i]->optionset_html .= '</select>';
			} else {
				$available_options[$i]->optionset_html = '<input class="wp-easycart-pro-conditional-logic-optionitem wp-easycart-pro-conditional-logic-optionitem-' . $available_options[$i]->option_to_product_id . '" style="display:none;" value="" />';
			}
		}
		$return_obj = (object) array(
			'conditional_logic' => $conditional_logic,
			'available_options' => array_merge( $select_one, $available_options )
		);
		return json_encode( $return_obj );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_products_pro( ){
	return wp_easycart_admin_products_pro::instance( );
}
wp_easycart_admin_products_pro( );

add_action( 'wp_ajax_ec_admin_ajax_save_product_details_images_pro', 'ec_admin_ajax_save_product_details_images_pro' );
function ec_admin_ajax_save_product_details_images_pro( ){
	wp_easycart_admin_products_pro( )->save_product_images( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_product_details_is_optionitem_images_pro', 'ec_admin_ajax_save_product_details_is_optionitem_images_pro' );
function ec_admin_ajax_save_product_details_is_optionitem_images_pro() {
	wp_easycart_admin_products_pro( )->save_product_images_is_optionitem( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_subscribe_to_stock_notification', 'ec_ajax_admin_subscribe_to_stock_notification' );
function ec_ajax_admin_subscribe_to_stock_notification( ){
	wp_easycart_admin_products_pro( )->add_new_stock_notification_user( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_delete_stock_notification_item', 'ec_ajax_admin_delete_stock_notification_item' );
function ec_ajax_admin_delete_stock_notification_item( ){
	wp_easycart_admin_products_pro( )->delete_stock_notification_item( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_subscribe_stock_notification_item', 'ec_ajax_admin_subscribe_stock_notification_item' );
function ec_ajax_admin_subscribe_stock_notification_item( ){
	wp_easycart_admin_products_pro( )->subscribe_stock_notification_item( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_unsubscribe_stock_notification_item', 'ec_ajax_admin_unsubscribe_stock_notification_item' );
function ec_ajax_admin_unsubscribe_stock_notification_item( ){
	wp_easycart_admin_products_pro( )->unsubscribe_stock_notification_item( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_notify_subscriber', 'ec_ajax_admin_notify_subscriber' );
function ec_ajax_admin_notify_subscriber( ){
	wp_easycart_admin_products_pro( )->notify_subscriber( );
	die( );
}

add_action( 'wp_ajax_ec_ajax_admin_notify_all_subscribers', 'ec_ajax_admin_notify_all_subscribers' );
function ec_ajax_admin_notify_all_subscribers( ){
	wp_easycart_admin_products_pro( )->notify_all_subscribers( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_google_merchant_pro', 'ec_admin_ajax_save_product_details_google_merchant_pro' );
function ec_admin_ajax_save_product_details_google_merchant_pro() {
	wp_easycart_admin_products_pro()->save_product_details_google_merchant();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_optionitem_images_content_pro', 'ec_admin_ajax_get_optionitem_images_content_pro' );
function ec_admin_ajax_get_optionitem_images_content_pro() {
	wp_easycart_admin_products_pro()->get_updated_images_panel();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_pro_basic_options', 'ec_admin_ajax_save_pro_basic_options' );
function ec_admin_ajax_save_pro_basic_options() {
	wp_easycart_admin_products_pro()->update_basic_options();
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'option_list' => $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type = "basic-combo" OR option_type = "basic-swatch" ORDER BY option_name ASC' ),
		'option_html' => wp_easycart_admin_products_pro()->get_basic_option_rows( $product ),
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product ),
		'modifier_html' => wp_easycart_admin_products_pro()->get_modifier_rows( $product ),
		'variant_list' => $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d LIMIT 50', (int) $_POST['product_id'] ) ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_save_variation', 'ec_admin_ajax_pro_save_variation' );
function ec_admin_ajax_pro_save_variation() {
	wp_easycart_admin_products_pro()->update_variant();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_enable_variation', 'ec_admin_ajax_pro_enable_variation' );
function ec_admin_ajax_pro_enable_variation() {
	wp_easycart_admin_products_pro()->enable_variant();
	global $wpdb;
	$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', (int) $_POST['optionitemquantity_id'] ) );
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $optionitemquantity_row->product_id ) );
	$response = (object) array(
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product, (int) $_POST['page_num'], (int) $_POST['option_item_id_1'], (int) $_POST['option_item_id_2'], (int) $_POST['option_item_id_3'], (int) $_POST['option_item_id_4'], (int) $_POST['option_item_id_5'], $_POST['is_enabled'] ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_disable_variation', 'ec_admin_ajax_pro_disable_variation' );
function ec_admin_ajax_pro_disable_variation() {
	wp_easycart_admin_products_pro()->disable_variant();
	global $wpdb;
	$optionitemquantity_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', (int) $_POST['optionitemquantity_id'] ) );
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $optionitemquantity_row->product_id ) );
	$response = (object) array(
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product, (int) $_POST['page_num'], (int) $_POST['option_item_id_1'], (int) $_POST['option_item_id_2'], (int) $_POST['option_item_id_3'], (int) $_POST['option_item_id_4'], (int) $_POST['option_item_id_5'], $_POST['is_enabled'] ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_enable_variation_tracking', 'ec_admin_ajax_pro_enable_variation_tracking' );
function ec_admin_ajax_pro_enable_variation_tracking() {
	wp_easycart_admin_products_pro()->enable_variant_tracking();
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product, (int) $_POST['page_num'], (int) $_POST['option_item_id_1'], (int) $_POST['option_item_id_2'], (int) $_POST['option_item_id_3'], (int) $_POST['option_item_id_4'], (int) $_POST['option_item_id_5'], $_POST['is_enabled'] ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_disable_variation_tracking', 'ec_admin_ajax_pro_disable_variation_tracking' );
function ec_admin_ajax_pro_disable_variation_tracking() {
	wp_easycart_admin_products_pro()->disable_variant_tracking();
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product, (int) $_POST['page_num'], (int) $_POST['option_item_id_1'], (int) $_POST['option_item_id_2'], (int) $_POST['option_item_id_3'], (int) $_POST['option_item_id_4'], (int) $_POST['option_item_id_5'], $_POST['is_enabled'] ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_add_advanced_option', 'ec_admin_ajax_pro_add_advanced_option' );
function ec_admin_ajax_pro_add_advanced_option() {
	wp_easycart_admin_products_pro()->add_modifier();
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'option_list' => $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type = "basic-combo" OR option_type = "basic-swatch" ORDER BY option_name ASC' ),
		'option_html' => wp_easycart_admin_products_pro()->get_basic_option_rows( $product ),
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product ),
		'modifier_html' => wp_easycart_admin_products_pro()->get_modifier_rows( $product ),
		'variant_list' => $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d LIMIT 50', (int) $_POST['product_id'] ) ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_remove_advanced_option', 'ec_admin_ajax_pro_remove_advanced_option' );
function ec_admin_ajax_pro_remove_advanced_option() {
	wp_easycart_admin_products_pro()->remove_modifier();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_save_advanced_options_sort', 'ec_admin_ajax_pro_save_advanced_options_sort' );
function ec_admin_ajax_pro_save_advanced_options_sort() {
	wp_easycart_admin_products_pro()->save_modifier_sort();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_get_product_option_logic', 'ec_admin_ajax_pro_get_product_option_logic' );
function ec_admin_ajax_pro_get_product_option_logic() {
	echo wp_easycart_admin_products_pro()->get_product_option_logic();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_enable_disable_product_option_logic', 'ec_admin_ajax_pro_enable_disable_product_option_logic' );
function ec_admin_ajax_pro_enable_disable_product_option_logic() {
	if ( isset( $_POST['enabled'] ) && 'true' == $_POST['enabled'] ) {
		wp_easycart_admin_products_pro()->enable_modifier_logic();
	} else {
		wp_easycart_admin_products_pro()->disable_modifier_logic();
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_save_product_option_logic', 'ec_admin_ajax_pro_save_product_option_logic' );
function ec_admin_ajax_pro_save_product_option_logic() {
	wp_easycart_admin_products_pro()->save_modifier_logic();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_pro_options', 'ec_admin_ajax_get_pro_options' );
function ec_admin_ajax_get_pro_options() {
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'option_list' => $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type = "basic-combo" OR option_type = "basic-swatch" ORDER BY option_name ASC' ),
		'option_html' => wp_easycart_admin_products_pro()->get_basic_option_rows( $product ),
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product ),
		'modifier_html' => wp_easycart_admin_products_pro()->get_modifier_rows( $product ),
		'variant_list' => $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d LIMIT 50', (int) $_POST['product_id'] ) ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_get_option_sets', 'ec_admin_ajax_pro_get_option_sets' );
function ec_admin_ajax_pro_get_option_sets() {
	global $wpdb;
	$select_one = array( 
		(object) array(
			'option_id' =>'',
			'option_label' => __( 'Select One', 'wp-easycart-pro' ),
		)
	);
	$option_sets = $wpdb->get_results( 'SELECT option_id, option_name as option_label FROM ec_option WHERE option_type = "basic-combo" OR option_type = "basic-swatch" ORDER BY option_name ASC' );
	echo json_encode( array_merge( $select_one, $option_sets ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_get_modifiers', 'ec_admin_ajax_pro_get_modifiers' );
function ec_admin_ajax_pro_get_modifiers() {
	global $wpdb;
	$select_one = array( 
		(object) array(
			'option_id' =>'',
			'option_label' => __( 'Select One', 'wp-easycart-pro' ),
		)
	);
	$modifiers = $wpdb->get_results( 'SELECT option_id, option_name as option_label FROM ec_option WHERE option_type != "basic-combo" AND option_type != "basic-swatch" ORDER BY option_name ASC' );
	echo json_encode( array_merge( $select_one, $modifiers ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_update_advanced_optionset', 'ec_admin_ajax_pro_update_advanced_optionset' );
function ec_admin_ajax_pro_update_advanced_optionset() {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_advanced_optionset = %d WHERE product_id = %d', (int) $_POST['use_advanced_optionset'], (int) $_POST['product_id'] ) );
	wp_cache_flush();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_square_sync_product', 'ec_admin_ajax_product_details_square_sync_product' );
function ec_admin_ajax_product_details_square_sync_product() {
	global $wpdb;
	
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	
	if ( $product && '' != $product->square_id && class_exists( 'ec_square' ) ) {
		$square = new ec_square( );
		$response = $square->get_catalog_object( $product->square_id );
		$square->update_product( $response, $product );
	}
	
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_pro_get_variant_page', 'ec_admin_ajax_pro_get_variant_page' );
function ec_admin_ajax_pro_get_variant_page() {
	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $_POST['product_id'] ) );
	$response = (object) array(
		'variant_html' => wp_easycart_admin_products_pro()->get_variant_rows( $product, (int) $_POST['page_num'], (int) $_POST['option_item_id_1'], (int) $_POST['option_item_id_2'], (int) $_POST['option_item_id_3'], (int) $_POST['option_item_id_4'], (int) $_POST['option_item_id_5'], $_POST['is_enabled'] ),
	);
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_product_details_variant_google_merchant_pro', 'ec_admin_ajax_get_product_details_variant_google_merchant_pro' );
function ec_admin_ajax_get_product_details_variant_google_merchant_pro() {
	global $wpdb;
	$optionitem_info = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_optionitemquantity.google_merchant, ec_optionitemquantity.optionitemquantity_id, ec_product.title FROM ec_optionitemquantity, ec_product WHERE ec_optionitemquantity.product_id = %d AND ec_optionitemquantity.optionitemquantity_id = %d AND ec_product.product_id = ec_optionitemquantity.product_id', (int) $_POST['product_id'], (int) $_POST['optionitemquantity_id'] ) );
	if ( $optionitem_info && isset( $optionitem_info->google_merchant ) && '' != $optionitem_info->google_merchant ) {
		$json = $optionitem_info->google_merchant;
		$json_decode = json_decode( $json );
		if ( ! isset( $json_decode->optionitemquantity_id ) ) {
			$json_decode->optionitemquantity_id = $optionitem_info->optionitemquantity_id;
		}
		echo json_encode( $json_decode );
	} else {
		echo json_encode( (object) array(
			'optionitemquantity_id' => $optionitem_info->optionitemquantity_id,
			'enabled' => 'yes',
			'title' => $optionitem_info->title,
			'availability' => '',
			'color' => '',
			'pattern' => '',
			'material' => '',
			'age_group' => '',
			'gender' => '',
			'size' => '',
			'mpn' => '',
			'gtin' => '',
		) );
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_variant_google_merchant_pro', 'ec_admin_ajax_save_product_details_variant_google_merchant_pro' );
function ec_admin_ajax_save_product_details_variant_google_merchant_pro() {
	global $wpdb;
	$google_merchant = json_encode( (object) array(
		'optionitemquantity_id' => (int) $_POST['optionitemquantity_id'],
		'enabled' => esc_attr( $_POST['enabled'] ),
		'title' => esc_attr( $_POST['title'] ),
		'availability' => esc_attr( $_POST['availability'] ),
		'color' => esc_attr( $_POST['color'] ),
		'pattern' => esc_attr( $_POST['pattern'] ),
		'material' => esc_attr( $_POST['material'] ),
		'age_group' => esc_attr( $_POST['age_group'] ),
		'gender' => esc_attr( $_POST['gender'] ),
		'size' => esc_attr( $_POST['size'] ),
		'mpn' => esc_attr( $_POST['mpn'] ),
		'gtin' => esc_attr( $_POST['gtin'] ),
	) );
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET google_merchant = %s WHERE product_id = %d AND optionitemquantity_id = %d', $google_merchant, (int) $_POST['product_id'], $_POST['optionitemquantity_id'] ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_product_details_location_pickup_pro', 'ec_admin_ajax_save_product_details_location_pickup_pro' );
function ec_admin_ajax_save_product_details_location_pickup_pro() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}
	if ( ! isset( $_POST['product_id'] ) ) {
		return false;
	}
	if ( ! isset( $_POST['pickup_locations'] ) ) {
		return false;
	}
	if ( ! is_array( $_POST['pickup_locations'] ) ) {
		return false;
	}

	global $wpdb;
	$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $_POST['product_id'] ) );
	if ( ! $product ) {
		return false;
	}
	$list_filtered = array();
	foreach ( $_POST['pickup_locations'] as $pickup_location ) {
		$list_filtered[] = (int) $pickup_location;
	}
	$list_stored = implode( ',', $list_filtered );
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET pickup_locations = %s WHERE product_id = %d', $list_stored, $product->product_id ) );
	$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_location_to_product WHERE product_id = %d', $product->product_id ) );
	$insert_query = 'INSERT INTO ec_location_to_product( product_id, location_id ) VALUES';
	for ( $i = 0; $i < count( $list_filtered ); $i++ ) {
		if ( $i > 0 ) {
			$insert_query .= ',';
		}
		$insert_query .= $wpdb->prepare( '(%d,%d)', $product->product_id, $list_filtered[ $i ] );
	}
	$wpdb->query( $insert_query );
	die();
}
