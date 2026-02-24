<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_third_party_pro' ) ) :

final class wp_easycart_admin_third_party_pro{
	
	protected static $_instance = null;
	
	public $amazon_file;
	public $deconetwork_file;
	public $facebook_file;
	public $google_ga4_file;
	public $google_adwords_file;
	public $google_merchant_file;
	public $mailerlite_file;
	public $convertkit_file;
	public $activecampaign_file;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		// Setup File Names 
		$this->amazon_file	 				 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/amazon.php';
		$this->deconetwork_file	 			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/deconetwork.php';
		$this->facebook_file	 			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/facebook.php';
		$this->google_ga4_file				 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/google-ga4.php';
		$this->google_adwords_file			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/google-adwords.php';
		$this->google_merchant_file			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/google-merchant.php';
		$this->mailerlite_file	 			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/mailerlite.php';
		$this->convertkit_file	 			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/convertkit.php';
		$this->activecampaign_file 			 = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/third-party/activecampaign.php';
		
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			// Actions
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_amazon_settings' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_deconetwork_settings' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_facebook_settings' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_google_ga4_design' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_google_adwords_design' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_google_merchant' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_mailerlite_settings' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_convertkit_settings' ) );
			remove_action( 'wpeasycart_admin_third_party', array( wp_easycart_admin_third_party( ), 'load_activecampaign_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_amazon_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_deconetwork_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_facebook_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_ga4' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_adwords' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_merchant' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_mailerlite_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_convertkit_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_activecampaign_settings' ) );
			add_action( 'init', array( $this, 'save_settings' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_upload_feed' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_download_csv' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_download_feed' ) );
		}
	}
	
	public function load_amazon_settings( ){
		include( $this->amazon_file );
	}
	
	public function load_deconetwork_settings( ){
		include( $this->deconetwork_file );
	}
	
	public function load_facebook_settings( ){
		include( $this->facebook_file );
	}
	
	public function load_google_ga4( ){
		include( $this->google_ga4_file );
	}
	
	public function load_google_adwords( ){
		include( $this->google_adwords_file );
	}
	
	public function load_google_merchant( ){
		include( $this->google_merchant_file );
	}
	
	public function load_mailerlite_settings( ){
		include( $this->mailerlite_file );
	}
	
	public function load_convertkit_settings( ){
		include( $this->convertkit_file );
	}
	
	public function load_activecampaign_settings( ){
		include( $this->activecampaign_file );
	}
	
	public function save_amazon_settings( ) {
		$ec_option_amazon_key = stripslashes_deep( $_POST['ec_option_amazon_key'] );
		$ec_option_amazon_secret = stripslashes_deep( $_POST['ec_option_amazon_secret'] );
		$ec_option_amazon_bucket = stripslashes_deep( $_POST['ec_option_amazon_bucket'] );
		$ec_option_amazon_bucket_region = $_POST['ec_option_amazon_bucket_region'];
		
		if( isset( $_POST['ec_option_amazon_key'] ) )
			$ec_option_amazon_key = $_POST['ec_option_amazon_key'];
		if( isset( $_POST['ec_option_amazon_secret'] ) )
			$ec_option_amazon_secret = $_POST['ec_option_amazon_secret'];
		if( isset( $_POST['ec_option_amazon_bucket'] ) )
			$ec_option_amazon_bucket = $_POST['ec_option_amazon_bucket'];
		if( isset( $_POST['ec_option_amazon_bucket_region'] ) )
			$ec_option_amazon_bucket_region = $_POST['ec_option_amazon_bucket_region'];

		
		update_option( 'ec_option_amazon_key', $ec_option_amazon_key );
		update_option( 'ec_option_amazon_secret', $ec_option_amazon_secret );
		update_option( 'ec_option_amazon_bucket', $ec_option_amazon_bucket );
		update_option( 'ec_option_amazon_bucket_region', $ec_option_amazon_bucket_region );
	}
	
	public function save_deconetwork_settings( ) {
		$ec_option_deconetwork_url = stripslashes_deep( $_POST['ec_option_deconetwork_url'] );
		$ec_option_deconetwork_password = stripslashes_deep( $_POST['ec_option_deconetwork_password'] );
		
		if( isset( $_POST['ec_option_deconetwork_url'] ) )
			$ec_option_deconetwork_url = $_POST['ec_option_deconetwork_url'];
		if( isset( $_POST['ec_option_deconetwork_password'] ) )
			$ec_option_deconetwork_password = $_POST['ec_option_deconetwork_password'];

		
		update_option( 'ec_option_deconetwork_url', $ec_option_deconetwork_url );
		update_option( 'ec_option_deconetwork_password', $ec_option_deconetwork_password );
	}
	
	public function save_facebook_settings( ) {
		$ec_option_fb_pixel = stripslashes_deep( $_POST['ec_option_fb_pixel'] );
		update_option( 'ec_option_fb_pixel', $ec_option_fb_pixel );
	}
	
	public function save_mailerlite_settings( ) {
		$ec_option_enable_mailerlite = (int) $_POST['ec_option_enable_mailerlite'];
		$ec_option_mailerlite_api_key = sanitize_text_field( $_POST['ec_option_mailerlite_api_key'] );
		update_option( 'ec_option_enable_mailerlite', $ec_option_enable_mailerlite );
		update_option( 'ec_option_mailerlite_api_key', $ec_option_mailerlite_api_key );
	}

	public function save_convertkit_settings( ) {
		$ec_option_enable_convertkit = (int) $_POST['ec_option_enable_convertkit'];
		$ec_option_convertkit_api_key = sanitize_text_field( $_POST['ec_option_convertkit_api_key'] );
		$ec_option_convertkit_api_secret = sanitize_text_field( $_POST['ec_option_convertkit_api_secret'] );
		$ec_option_convertkit_form = sanitize_text_field( $_POST['ec_option_convertkit_form'] );
		update_option( 'ec_option_enable_convertkit', $ec_option_enable_convertkit );
		update_option( 'ec_option_convertkit_api_key', $ec_option_convertkit_api_key );
		update_option( 'ec_option_convertkit_api_secret', $ec_option_convertkit_api_secret );
		update_option( 'ec_option_convertkit_form', $ec_option_convertkit_form );
	}
	
	public function save_activecampaign_settings( ) {
		$ec_option_enable_activecampaign = (int) $_POST['ec_option_enable_activecampaign'];
		$ec_option_activecampaign_api_url = sanitize_text_field( $_POST['ec_option_activecampaign_api_url'] );
		$ec_option_activecampaign_api_key = sanitize_text_field( $_POST['ec_option_activecampaign_api_key'] );
		$ec_option_activecampaign_list = sanitize_text_field( $_POST['ec_option_activecampaign_list'] );
		update_option( 'ec_option_enable_activecampaign', $ec_option_enable_activecampaign );
		update_option( 'ec_option_activecampaign_api_url', $ec_option_activecampaign_api_url );
		update_option( 'ec_option_activecampaign_api_key', $ec_option_activecampaign_api_key );
		update_option( 'ec_option_activecampaign_list', $ec_option_activecampaign_list );
	}

	public function save_google_ga4() {
		$options = array( 'ec_option_google_ga4_tag_manager', 'ec_option_google_ga4_tag_manager_direct' );
		$options_text = array( 'ec_option_google_ga4_property_id', 'ec_option_google_ga4_tag_manager_measurement_id', 'ec_option_google_ga4_tag_manager_api_secret', 'ec_option_google_ga4_tag_manager_server_url' );
		if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
			$val = wp_easycart_admin_verification()->filter_checkbox( 'val' );
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), (int) $val );
		} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $_POST['val'] );
		}
	}

	public function save_google_adwords() {
		$ec_option_google_adwords_conversion_id = '';
		$ec_option_google_adwords_tag_id = '';
		$ec_option_google_adwords_language = 'en';
		$ec_option_google_adwords_format = '3';
		$ec_option_google_adwords_color = 'FFFFFF';
		$ec_option_google_adwords_currency = 'USD';
		$ec_option_google_adwords_remarketing_only = 'false';

		if ( isset( $_POST['ec_option_google_adwords_conversion_id'] ) ) {
			$ec_option_google_adwords_conversion_id = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_conversion_id'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_tag_id'] ) ) {
			$ec_option_google_adwords_tag_id = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_tag_id'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_language'] ) ) {
			$ec_option_google_adwords_language = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_language'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_format'] ) ) {
			$ec_option_google_adwords_format = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_format'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_color'] ) ) {
			$ec_option_google_adwords_color = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_color'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_currency'] ) ) {
			$ec_option_google_adwords_currency = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_currency'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_label'] ) ) {
			$ec_option_google_adwords_label = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_label'] ) );
		}
		if ( isset( $_POST['ec_option_google_adwords_remarketing_only'] ) ) {
			$ec_option_google_adwords_remarketing_only = sanitize_text_field( wp_unslash( $_POST['ec_option_google_adwords_remarketing_only'] ) );
		}

		update_option( 'ec_option_google_adwords_conversion_id', $ec_option_google_adwords_conversion_id );
		update_option( 'ec_option_google_adwords_tag_id', $ec_option_google_adwords_tag_id );
		update_option( 'ec_option_google_adwords_language', $ec_option_google_adwords_language );
		update_option( 'ec_option_google_adwords_format', $ec_option_google_adwords_format );
		update_option( 'ec_option_google_adwords_color', $ec_option_google_adwords_color );
		update_option( 'ec_option_google_adwords_currency', $ec_option_google_adwords_currency );
		update_option( 'ec_option_google_adwords_label', $ec_option_google_adwords_label );
		update_option( 'ec_option_google_adwords_remarketing_only', $ec_option_google_adwords_remarketing_only );
	}
	
	public function save_settings( ){
		if( current_user_can( 'wpec_settings' ) && isset( $_POST['ec_admin_form_action'] ) && $_POST['ec_admin_form_action'] == "save-thirdparty-setup" ){
			$this->save_amazon_settings( );
			$this->save_deconetwork_settings( );
		}
	}

	public function process_download_csv() {
		if ( isset( $_GET['ec_admin_form_action'] ) && 'download-google-csv' == $_GET['ec_admin_form_action'] ) {
			global $wpdb;
			$products = $wpdb->get_results( 'SELECT ec_product.product_id, ec_product.model_number, ec_product.title, ec_product.price, ec_product.list_price, ec_manufacturer.name as manufacturer_name FROM ec_product LEFT JOIN ec_manufacturer ON ec_manufacturer.manufacturer_id = ec_product.manufacturer_id ORDER BY ec_product.title ASC' );
			$optionitems = $wpdb->get_results( 'SELECT * FROM ec_optionitem ORDER BY optionitem_id ASC' );

			header( 'Content-type: text/csv; charset=UTF-8' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Disposition: attachment; filename=google-feed.csv' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			$base_attributes = array(
				'enabled' => 'yes',
				'title' => '',
				'google_product_category' => '',
				'product_type' => '',
				'condition' => '',
				'identifier_exists' => '',
				'gtin' => '',
				'mpn' => '',
				'availability' => '',
				'condition' => '',
				'availability_date' => '',
				'expiration_date' => '',
				'gender' => '',
				'age_group' => '',
				'size_type' => '',
				'size_system' => '',
				'item_group_id' => '',
				'color' => '',
				'material' => '',
				'pattern' => '',
				'size' => '',
				'weight_type' => '',
				'shipping_weight' => '',
				'unit_pricing_base_measure' => '',
				'unit_pricing_measure' => '',
				'shipping_label' => '',
				'shipping_unit' => '',
				'shipping_length' => '',
				'shipping_width' => '',
				'shipping_height' => '',
				'min_handling_time' => '',
				'max_handling_time' => '',
				'adult' => '',
				'multipack' => '',
				'is_bundle' => '',
				'certification' => '',
				'certification_code' => '',
				'energy_efficiency_class' => '',
				'min_energy_efficiency_class' => '',
				'max_energy_efficiency_class' => '',
			);
			echo 'product_id,variant_id';
			foreach ( $base_attributes as $key => $value ) {
				echo ',' . esc_attr( $key );
			}
			echo "\n";
			foreach ( $products as $product ) {
				$attributes_result = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product_google_attributes WHERE product_id = %d', $product->product_id ) );
				if ( $attributes_result ) {
					$attributes = json_decode( $attributes_result->attribute_value, true );
					if ( ! isset( $attributes['title'] ) ) {
						$attributes['title'] = $product->title;
					}
					if ( ! isset( $attributes['item_group_id'] ) ) {
						$attributes['item_group_id'] = $product->model_number;
					}
				} else {
					$attributes = array(
						'title' => $product->title,
						'item_group_id' => $product->model_number,
					);
				}
				foreach ( $base_attributes as $key => $value ) {
					if ( ! isset( $attributes[ $key ] ) ) {
						$attributes[ $key ] = $value;
					}
				}

				echo '"' . esc_attr( str_replace( '"', '""', $product->product_id ) ) . '",';
				echo '""'; // No Variant ID for Main Product
				foreach ( $base_attributes as $key => $value ) {
					echo ',"' . esc_attr( str_replace( '"', '""', $attributes[ $key ] ) ) . '"';
				}
				echo "\n";
				// Get Variants
				$product_title = $attributes['title'];
				$product_group_id = $attributes['item_group_id'];
				$optionitem_quantity_rows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d', $product->product_id ) );
				if ( is_array( $optionitem_quantity_rows ) && count( $optionitem_quantity_rows ) > 0 ) {
					foreach ( $optionitem_quantity_rows as $optionitem_quantity_row ) {
						$google_merchant_variant_vals = json_decode( $optionitem_quantity_row->google_merchant );
						if ( ! is_object( $google_merchant_variant_vals ) ) {
							$google_merchant_variant_vals = (object) array();
						}
						if ( ! isset( $google_merchant_variant_vals->title ) || '' == $google_merchant_variant_vals->title ) {
							$google_merchant_variant_vals->title = $product_title;
							$optionitem_1_title = ( 0 == $optionitem_quantity_row->optionitem_id_1 ) ? '' : ' - ';
							$optionitem_2_title = ( 0 == $optionitem_quantity_row->optionitem_id_2 ) ? '' : ' - ';
							$optionitem_3_title = ( 0 == $optionitem_quantity_row->optionitem_id_3 ) ? '' : ' - ';
							$optionitem_4_title = ( 0 == $optionitem_quantity_row->optionitem_id_4 ) ? '' : ' - ';
							$optionitem_5_title = ( 0 == $optionitem_quantity_row->optionitem_id_5 ) ? '' : ' - ';
							for( $i = 0; $i < count( $optionitems ); $i++ ){
								if ( $optionitems[ $i ]->optionitem_id == $optionitem_quantity_row->optionitem_id_1 ) {
									$optionitem_1_title .= $optionitems[ $i ]->optionitem_name;
								} else if ( $optionitems[ $i ]->optionitem_id == $optionitem_quantity_row->optionitem_id_2 ) {
									$optionitem_2_title .= $optionitems[ $i ]->optionitem_name;
								} else if ( $optionitems[ $i ]->optionitem_id == $optionitem_quantity_row->optionitem_id_3 ) {
									$optionitem_3_title .= $optionitems[ $i ]->optionitem_name;
								} else if ( $optionitems[ $i ]->optionitem_id == $optionitem_quantity_row->optionitem_id_4 ) {
									$optionitem_4_title .= $optionitems[ $i ]->optionitem_name;
								} else if ( $optionitems[ $i ]->optionitem_id == $optionitem_quantity_row->optionitem_id_5 ) {
									$optionitem_5_title .= $optionitems[ $i ]->optionitem_name;
								}
							}
							$google_merchant_variant_vals->title .= $optionitem_1_title . $optionitem_2_title . $optionitem_3_title . $optionitem_4_title . $optionitem_5_title;
						}
						if ( ! isset( $google_merchant_variant_vals->item_group_id ) || '' == $google_merchant_variant_vals->item_group_id ) {
							$google_merchant_variant_vals->item_group_id = $product_group_id;
						}
						foreach ( $base_attributes as $key => $value ) {
							if ( ! isset( $google_merchant_variant_vals->{ $key } ) ) {
								$google_merchant_variant_vals->{ $key } = $value;
							}
						}
						echo '"' . esc_attr( str_replace( '"', '""', $product->product_id ) ) . '",';
						echo '"' . esc_attr( str_replace( '"', '""', $optionitem_quantity_row->optionitemquantity_id ) ) . '"';
						foreach ( $base_attributes as $key => $value ) {
							echo ',"' . esc_attr( str_replace( '"', '""', $google_merchant_variant_vals->{ $key } ) ) . '"';
						}
						echo "\n";
					}
				}
			}
			die();
		}
	}

	public function process_download_feed() {
		if ( isset( $_GET['ec_admin_form_action'] ) && 'download-feed' == $_GET['ec_admin_form_action'] ) {
			global $wpdb;
			$db = new ec_db();
			$products = $db->get_product_list( 'WHERE 1=1', '', '', '', '' );
			$file_contents = '<?xml version="1.0"?>' . "\r\n";
			$file_contents .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\r\n";
			$file_contents .= '<channel>' . "\r\n";
			$file_contents .= '<title>' . __( 'WP EasyCart Data Feed', 'wp-easycart-pro' ) . '</title>' . "\r\n";
			$file_contents .= '<link>' . site_url() . '</link>' . "\r\n";
			$file_contents .= '<description>' . __( 'My Site Products', 'wp-easycart-pro' ) . '</description>' . "\r\n";
			foreach ( $products as $product_row ) {
				$product = new ec_product( $product_row );
				if ( ! get_option( 'ec_option_use_old_linking_style' ) && '0' != $product->post_id ) {
					$link = get_permalink( $product->post_id );
				} else {
					$storepageid = get_option( 'ec_option_storepage' );
					if ( function_exists( 'icl_object_id' ) ) {
						$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
					}
					$store_page = get_permalink( $storepageid );
					if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
						$https_class = new WordPressHTTPS();
						$store_page = $https_class->makeUrlHttps( $store_page );
					}
					if ( substr_count( $store_page, '?' ) ) {
						$permalink_divider = '&';
					} else {
						$permalink_divider = '?';
					}
					$link = $store_page . $permalink_divider . 'model_number=' . $product->model_number;
				}

				$image_link = '';
				$additional_image_links = array();
				$first_image_found = false;
				if ( $product->use_optionitem_images ) {
					$first_optionitem_id = false;
					if( $product->use_advanced_optionset ) {
						if( count( $product->advanced_optionsets ) > 0 ) {
							$valid_optionset = false;
							foreach( $product->advanced_optionsets as $adv_optionset ) {
								if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
									$valid_optionset = $adv_optionset;
								}
							}
							if ( $valid_optionset ) {
								$optionitems = $product->get_advanced_optionitems( $valid_optionset->option_id );
								if ( count( $optionitems ) > 0 ) {
									$first_optionitem_id = $optionitems[0]->optionitem_id;
								}
							}
						}
					} else {
						if( count( $product->options->optionset1->optionset ) > 0 ){
							for ( $j = 0; $j < count( $product->options->optionset1->optionset ) && ! $first_optionitem_id; $j++ ) {
								if ( $product->allow_backorders ) {
									$optionitem_in_stock = true;
								} else if ( $product->use_optionitem_quantity_tracking && ( $product->option1quantity[ $product->options->optionset1->optionset[ $j ]->optionitem_id ] <= 0 ) ) {
									$optionitem_in_stock = false;
								} else {
									$optionitem_in_stock = true;
								}
								if ( $product->options->verify_optionitem( 1, $product->options->optionset1->optionset[ $j ]->optionitem_id ) ) {
									if ( ! $product->use_optionitem_quantity_tracking || $product->option1quantity[ $product->options->optionset1->optionset[ $j ]->optionitem_id ] > 0 || $optionitem_in_stock ){
										for ( $k = 0; $k < count( $product->images->imageset ) && ! $first_optionitem_id; $k++ ) {
											if ( $product->images->imageset[ $k ]->optionitem_id == $product->options->optionset1->optionset[ $j ]->optionitem_id ) {
												$first_optionitem_id = $product->options->optionset1->optionset[ $j ]->optionitem_id;
											}
										}
									}
								}
							}
						}
					}
					if ( $first_optionitem_id ) {
						for ( $i = 0; $i < count( $product->images->imageset ); $i++ ) {
							if ( (int) $product->images->imageset[$i]->optionitem_id == (int) $first_optionitem_id ){
								if ( count( $product->images->imageset[$i]->product_images ) > 0 ) {
									for ( $j = 0; $j < count( $product->images->imageset[$i]->product_images ); $j++ ) {
										if( 'video:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
											$video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 );
											$video_arr = explode( ':::', $video_str );
											if ( count( $video_arr ) >= 2 ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $video_arr[1] );
												} else {
													$additional_image_links[] = esc_attr( $video_arr[1] );
												}
												$first_image_found = true;
											}
										} else if( 'youtube:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 8 ) ) {
											$youtube_video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 8, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 8 );
											$youtube_video_arr = explode( ':::', $youtube_video_str );
											if ( count( $youtube_video_arr ) >= 2 ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $youtube_video_arr[1] );
												} else {
													$additional_image_links[] = esc_attr( $youtube_video_arr[1] );
												}
												$first_image_found = true;
											}
										} else if( 'vimeo:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
											$vimeo_video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 );
											$vimeo_video_arr = explode( ':::', $vimeo_video_str );
											if ( count( $vimeo_video_arr ) >= 2 ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $vimeo_video_arr[1] );
												} else {
													$additional_image_links[] = esc_attr( $vimeo_video_arr[1] );
												}
												$first_image_found = true;
											}
										} else {
											if ( 'image1' == $product->images->imageset[$i]->product_images[ $j ] ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $product->get_first_image_url( ) );
												} else {
													$additional_image_links[] = esc_attr( $product->get_first_image_url( ) );
												}
											} else if( 'image2' == $product->images->imageset[$i]->product_images[ $j ] ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $product->get_second_image_url( ) );
												} else {
													$additional_image_links[] = esc_attr( $product->get_second_image_url( ) );
												}
											} else if( 'image3' == $product->images->imageset[$i]->product_images[ $j ] ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $product->get_third_image_url( ) );
												} else {
													$additional_image_links[] = esc_attr( $product->get_third_image_url( ) );
												}
											} else if( 'image4' == $product->images->imageset[$i]->product_images[ $j ] ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $product->get_fourth_image_url( ) );
												} else {
													$additional_image_links[] = esc_attr( $product->get_fourth_image_url( ) );
												}
											} else if( 'image5' == $product->images->imageset[$i]->product_images[ $j ] ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( $product->get_fifth_image_url( ) );
												} else {
													$additional_image_links[] = esc_attr( $product->get_fifth_image_url( ) );
												}
											} else if( 'image:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
												if ( ! $first_image_found ) {
													$image_link = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 ) ) );
												} else {
													$additional_image_links[] = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 ) ) );
												}
											} else {
												$product_image_media = wp_get_attachment_image_src( $product->images->imageset[$i]->product_images[ $j ], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
												if( $product_image_media && isset( $product_image_media[0] ) ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product_image_media[0] );
													} else {
														$additional_image_links[] = esc_attr( $product_image_media[0] );
													}
												}
											}
											$first_image_found = true;
										}
									}
								} else {
									$image_link = esc_attr( $product->get_first_image_url() );
								}
							}
						}
					}
				} else { // Close check for option item images
					if( count( $product->images->product_images ) > 0 ) {
						for ( $j = 0; $j < count( $product->images->product_images ); $j++ ) {
							if( 'video:' == substr( $product->images->product_images[ $j ], 0, 6 ) ) {
								$video_str = substr( $product->images->product_images[ $j ], 6, strlen( $product->images->product_images[ $j ] ) - 6 );
								$video_arr = explode( ':::', $video_str );
								if ( count( $video_arr ) >= 2 ) {
									if ( ! $first_image_found ) {
										$image_link = esc_attr( $video_arr[1] );
									} else {
										$additional_image_links[] = esc_attr( $video_arr[1] );
									}
									$first_image_found = true;
								}
							} else if( 'youtube:' == substr( $product->images->product_images[ $j ], 0, 8 ) ) {
								$youtube_video_str = substr( $product->images->product_images[ $j ], 8, strlen( $product->images->product_images[ $j ] ) - 8 );
								$youtube_video_arr = explode( ':::', $youtube_video_str );
								if ( count( $youtube_video_arr ) >= 2 ) {
									if ( ! $first_image_found ) {
										$image_link = esc_attr( $youtube_video_arr[1] );
									} else {
										$additional_image_links[] = esc_attr( $youtube_video_arr[1] );
									}
									$first_image_found = true;
								}
							} else if( 'vimeo:' == substr( $product->images->product_images[ $j ], 0, 6 ) ) {
								$vimeo_video_str = substr( $product->images->product_images[ $j ], 6, strlen( $product->images->product_images[ $j ] ) - 6 );
								$vimeo_video_arr = explode( ':::', $vimeo_video_str );
								if ( count( $vimeo_video_arr ) >= 2 ) {
									if ( ! $first_image_found ) {
										$image_link = esc_attr( $vimeo_video_arr[1] );
									} else {
										$additional_image_links[] = esc_attr( $vimeo_video_arr[1] );
									}
									$first_image_found = true;
								}
							} else {
								if( count( $product->images->product_images ) > 0 ) { 
									if ( 'image1' == $product->images->product_images[ $j ] ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( $product->get_first_image_url( ) );
										} else {
											$additional_image_links[] = esc_attr( $product->get_first_image_url( ) );
										}
									} else if( 'image2' == $product->images->product_images[ $j ] ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( $product->get_second_image_url( ) );
										} else {
											$additional_image_links[] = esc_attr( $product->get_second_image_url( ) );
										}
									} else if( 'image3' == $product->images->product_images[ $j ] ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( $product->get_third_image_url( ) );
										} else {
											$additional_image_links[] = esc_attr( $product->get_third_image_url( ) );
										}
									} else if( 'image4' == $product->images->product_images[ $j ] ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( $product->get_fourth_image_url( ) );
										} else {
											$additional_image_links[] = esc_attr( $product->get_fourth_image_url( ) );
										}
									} else if( 'image5' == $product->images->product_images[ $j ] ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( $product->get_fifth_image_url( ) );
										} else {
											$additional_image_links[] = esc_attr( $product->get_fifth_image_url( ) );
										}
									} else if( 'image:' == substr( $product->images->product_images[ $j ], 0, 6 ) ) {
										if ( ! $first_image_found ) {
											$image_link = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->product_images[ $j ], 6, strlen( $product->images->product_images[ $j ] ) - 6 ) ) );
										} else {
											$additional_image_links[] = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->product_images[ $j ], 6, strlen( $product->images->product_images[ $j ] ) - 6 ) ) );
										}
									} else {
										$product_image_media = wp_get_attachment_image_src( $product->images->product_images[ $j ], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
										if( $product_image_media && isset( $product_image_media[0] ) ) {
											if ( ! $first_image_found ) {
												$image_link = esc_attr( $product_image_media[0] );
											} else {
												$additional_image_links[] = esc_attr( $product_image_media[0] );
											}
										}
									}
									$first_image_found = true;
								}
							}
						}
					} else { 
						$image_link = esc_attr( $product->get_first_image_url() );
					}
				}

				$is_valid = true;
				$attributes_result = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product_google_attributes WHERE ec_product_google_attributes.product_id = %d', $product->product_id ) );
				if ( ! $attributes_result ) {
					$is_valid = false;
				}
				$attributes = json_decode( $attributes_result->attribute_value, true );
				$product_group_id = ( isset( $attributes['item_group_id'] ) && '' != $attributes['item_group_id'] ) ? $attributes['item_group_id'] : $product->model_number;
				$product_title = ( isset( $attributes['title'] ) ) ? $attributes['title'] : $product->title;
				$availability = 'in stock';
				if ( isset( $attributes['availability'] ) && '' != $attributes['availability'] ) {
					$availability = esc_attr( $attributes['availability'] );
				} else if ( ! $product->show_stock_quantity || $product->stock_quantity > 0 ) {
					$availability = 'in stock';
				} else if ( $product-> allow_backorders ) {
					$availability = 'backorder';
				} else {
					$availability = 'out of stock';
				}

				if ( ! $product->activate_in_store ) {
					$is_valid = false;
				} else if ( ! is_array( $attributes ) || ( isset( $attributes['enabled'] ) && 'no' == strtolower( $attributes['enabled'] ) ) ) {
					$is_valid = false;
				}
				if ( $is_valid ) {
					$file_contents .= '<item>' . "\r\n";
					$file_contents .= '<g:id>' . htmlspecialchars( $product_group_id ) . '</g:id>' . "\r\n";
					$file_contents .= '<g:title>' . htmlspecialchars( preg_replace( '/[[:^ascii:]]/', '', $product_title ) ) . '</g:title>' . "\r\n";
					$file_contents .= '<g:description>' . htmlspecialchars( $product->description ) . '</g:description>' . "\r\n";
					$file_contents .= '<g:link>' . htmlspecialchars( $link ) . '</g:link>' . "\r\n";
					if ( '' != $image_link ) {
						$file_contents .= '<g:image_link>' . htmlspecialchars( $image_link ) . '</g:image_link>' . "\r\n";
					}
					if ( count( $additional_image_links ) > 0 ) {
						for ( $k = 0; $k < count( $additional_image_links ) && $k < 10; $k++ ) {
							$file_contents .= '<g:additional_image_link>' . htmlspecialchars( $additional_image_links[ $k ] ) . '</g:additional_image_link>' . "\r\n";
						}
					}
					$file_contents .= '<g:availability>' . esc_attr( $availability ) . '</g:availability>' . "\r\n";
					if ( $product->list_price > 0 ) {
						$file_contents .= '<g:price>' . number_format( $product->list_price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:price>' . "\r\n";
						$file_contents .= '<g:sale_price>' . number_format( $product->price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:sale_price>' . "\r\n";
					} else {
						$file_contents .= '<g:price>' . number_format( $product->price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:price>' . "\r\n";
					}
					if ( $product->is_subscription_item ) {
						$subscription_period = '';
						if ( 'Y' == $product->subscription_bill_period ) {
							$subscription_period = 'year';
						} else if ( 'M' == $product->subscription_bill_period ) {
							$subscription_period = 'month';
						}
						if ( '' != $subscription_period ) {
							$file_contents .= '<g:subscription_cost>' . "\r\n";
								$file_contents .= '<g:period>' . esc_attr( $subscription_period ) . '</g:period>' . "\r\n";
								$file_contents .= '<g:period_length>' . (int) $product->subscription_bill_length . '</g:period_length>' . "\r\n";
								$file_contents .= '<g:amount>' . number_format( $product->price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:amount>' . "\r\n";
							$file_contents .= '</subscription_cost>' . "\r\n";
						}
					}
					$file_contents .= '<g:brand>' . htmlspecialchars( $product->manufacturer_name ) . '</g:brand>' . "\r\n";
					foreach ( $attributes as $key => $value ) {
						if ( 'title' == $key || 'item_group_id' == $key || 'availability' == $key || 'enabled' == $key || 'optionitemquantity_id' == $key || 'shipping_unit' == $key ) {
							// Skip this
						} else if ( 'weight_type' == $key ) {
							$file_contents .= '<g:shipping_weight>' . $product->weight . ' ' . $value . '</g:shipping_weight>' . "\r\n";
						} else if ( 'shipping_length' == $key || 'shipping_width' == $key || 'shipping_height' == $key ) {
							$file_contents .= '<g:' . $key . '>' . htmlspecialchars( $value ) . ' ' . ( ( isset( $attributes['shipping_unit'] ) ) ? $attributes['shipping_unit'] : 'in' ) . '</g:' . $key . '>' . "\r\n";
						} else if ( '' != $value && 'None Selected' != $value ) {
							$file_contents .= '<g:' . $key . '>' . htmlspecialchars( $value ) . '</g:' . $key . '>' . "\r\n";
						}
					}
					$file_contents .= '</item>' . "\r\n";
				}
				
				// Get Variants
				$optionitem_quantity_rows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND google_merchant IS NOT NULL', $product->product_id ) );
				if ( is_array( $optionitem_quantity_rows ) && count( $optionitem_quantity_rows ) > 0 ) {
					foreach ( $optionitem_quantity_rows as $optionitem_quantity_row ) {
						if ( $product->use_optionitem_images && ! $product->use_advanced_optionset ) {
							$image_link = '';
							$additional_image_links = array();
							$first_image_found = false;
							for ( $i = 0; $i < count( $product->images->imageset ); $i++ ) {
								if ( (int) $product->images->imageset[$i]->optionitem_id == (int) $optionitem_quantity_row->optionitem_id_1 ){
									if ( count( $product->images->imageset[$i]->product_images ) > 0 ) {
										for ( $j = 0; $j < count( $product->images->imageset[$i]->product_images ); $j++ ) {
											if( 'video:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
												$video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 );
												$video_arr = explode( ':::', $video_str );
												if ( count( $video_arr ) >= 2 ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $video_arr[1] );
													} else {
														$additional_image_links[] = esc_attr( $video_arr[1] );
													}
													$first_image_found = true;
												}
											} else if( 'youtube:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 8 ) ) {
												$youtube_video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 8, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 8 );
												$youtube_video_arr = explode( ':::', $youtube_video_str );
												if ( count( $youtube_video_arr ) >= 2 ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $youtube_video_arr[1] );
													} else {
														$additional_image_links[] = esc_attr( $youtube_video_arr[1] );
													}
													$first_image_found = true;
												}
											} else if( 'vimeo:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
												$vimeo_video_str = substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 );
												$vimeo_video_arr = explode( ':::', $vimeo_video_str );
												if ( count( $vimeo_video_arr ) >= 2 ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $vimeo_video_arr[1] );
													} else {
														$additional_image_links[] = esc_attr( $vimeo_video_arr[1] );
													}
													$first_image_found = true;
												}
											} else {
												if ( 'image1' == $product->images->imageset[$i]->product_images[ $j ] ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product->get_first_image_url( ) );
													} else {
														$additional_image_links[] = esc_attr( $product->get_first_image_url( ) );
													}
												} else if( 'image2' == $product->images->imageset[$i]->product_images[ $j ] ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product->get_second_image_url( ) );
													} else {
														$additional_image_links[] = esc_attr( $product->get_second_image_url( ) );
													}
												} else if( 'image3' == $product->images->imageset[$i]->product_images[ $j ] ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product->get_third_image_url( ) );
													} else {
														$additional_image_links[] = esc_attr( $product->get_third_image_url( ) );
													}
												} else if( 'image4' == $product->images->imageset[$i]->product_images[ $j ] ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product->get_fourth_image_url( ) );
													} else {
														$additional_image_links[] = esc_attr( $product->get_fourth_image_url( ) );
													}
												} else if( 'image5' == $product->images->imageset[$i]->product_images[ $j ] ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( $product->get_fifth_image_url( ) );
													} else {
														$additional_image_links[] = esc_attr( $product->get_fifth_image_url( ) );
													}
												} else if( 'image:' == substr( $product->images->imageset[$i]->product_images[ $j ], 0, 6 ) ) {
													if ( ! $first_image_found ) {
														$image_link = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 ) ) );
													} else {
														$additional_image_links[] = esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $product->images->imageset[$i]->product_images[ $j ], 6, strlen( $product->images->imageset[$i]->product_images[ $j ] ) - 6 ) ) );
													}
												} else {
													$product_image_media = wp_get_attachment_image_src( $product->images->imageset[$i]->product_images[ $j ], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
													if( $product_image_media && isset( $product_image_media[0] ) ) {
														if ( ! $first_image_found ) {
															$image_link = esc_attr( $product_image_media[0] );
														} else {
															$additional_image_links[] = esc_attr( $product_image_media[0] );
														}
													}
												}
												$first_image_found = true;
											}
										}
									} else {
										$image_link = esc_attr( $product->images->imageset[$i]->image1 );
									}
								}
							}
						}
						$is_valid_variant = true;
						$google_merchant_variant_vals = json_decode( $optionitem_quantity_row->google_merchant );
						$variant_sku = ( '' != $optionitem_quantity_row->sku ) ? $optionitem_quantity_row->sku : $product->model_number . $optionitem_quantity_row->optionitemquantity_id;
						$variant_title = ( is_object( $google_merchant_variant_vals ) && isset( $google_merchant_variant_vals->title ) && '' != $google_merchant_variant_vals->title ) ? $google_merchant_variant_vals->title : $product_title;
						$variant_price = ( '' != $optionitem_quantity_row->price ) ? $optionitem_quantity_row->price : $product->price;
						if ( ! $product->activate_in_store ) {
							$is_valid = false;
						} else if ( ! $optionitem_quantity_row->is_enabled ) {
							$is_valid = false;
						} else if ( ! is_array( $attributes ) || ( isset( $google_merchant_variant_vals->enabled ) && 'no' == strtolower( $google_merchant_variant_vals->enabled ) ) ) {
							$is_valid_variant = false;
						} else if ( ! $is_valid ) {
							$is_valid_variant = false;
						}
						if ( $is_valid_variant ) {
							$file_contents .= '<item>' . "\r\n";
							$file_contents .= '<g:item_group_id>' . htmlspecialchars( $product_group_id ) . '</g:item_group_id>' . "\r\n";
							$file_contents .= '<g:id>' . htmlspecialchars( $variant_sku ) . '</g:id>' . "\r\n";
							$file_contents .= '<g:title>' . htmlspecialchars( preg_replace( '/[[:^ascii:]]/', '', $variant_title ) ) . '</g:title>' . "\r\n";
							$file_contents .= '<g:description>' . htmlspecialchars( $product->description ) . '</g:description>' . "\r\n";
							$file_contents .= '<g:link>' . htmlspecialchars( $link ) . '</g:link>' . "\r\n";
							if ( '' != $image_link ) {
								$file_contents .= '<g:image_link>' . htmlspecialchars( $image_link ) . '</g:image_link>' . "\r\n";
							}
							if ( count( $additional_image_links ) > 0 ) {
								for ( $k = 0; $k < count( $additional_image_links ) && $k < 10; $k++ ) {
									$file_contents .= '<g:additional_image_link>' . htmlspecialchars( $additional_image_links[ $k ] ) . '</g:additional_image_link>' . "\r\n";
								}
							}
							if ( $product->use_optionitem_quantity_tracking ) {
								$variant_availability = 'in stock';
								if ( ( is_object( $google_merchant_variant_vals ) && isset( $google_merchant_variant_vals->availability ) && '' != $google_merchant_variant_vals->availability ) ) {
									$variant_availability = esc_attr( $google_merchant_variant_vals->availability );
								} else if ( $optionitem_quantity_row->quantity > 0 ) {
									$variant_availability = 'in stock';
								} else if ( $product-> allow_backorders ) {
									$variant_availability = 'backorder';
								} else {
									$variant_availability = 'out of stock';
								}
								$file_contents .= '<g:availability>' . esc_attr( $variant_availability ). '</g:availability>' . "\r\n";
							} else {
								$file_contents .= '<g:availability>' . esc_attr( $availability ). '</g:availability>' . "\r\n";
							}
							if ( $product->list_price > 0 ) {
								$file_contents .= '<g:price>' . number_format( $product->list_price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:price>' . "\r\n";
								$file_contents .= '<g:sale_price>' . number_format( $variant_price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:sale_price>' . "\r\n";
							} else {
								$file_contents .= '<g:price>' . number_format( $variant_price, 2, '.', '' ) . ' ' . get_option( 'ec_option_base_currency' ) . '</g:price>' . "\r\n";
							}
							$file_contents .= '<g:brand>' . htmlspecialchars( $product->manufacturer_name ) . '</g:brand>' . "\r\n";
							foreach ( $google_merchant_variant_vals as $key => $value ) {
								if ( ! isset( $attributes[ $key ] ) ) {
									$attributes[ $key ] = $value;
								}
							}
							foreach ( $attributes as $key => $value ) {
								if ( isset( $google_merchant_variant_vals->{ $key } ) ) {
									$value = $google_merchant_variant_vals->{ $key };
								}
								if ( 'title' == $key || 'item_group_id' == $key || 'availability' == $key || 'enabled' == $key || 'optionitemquantity_id' == $key || 'shipping_unit' == $key ) {
									// Skip
								} else if ( 'weight_type' == $key ) {
									$file_contents .= '<g:shipping_weight>' . $product->weight . ' ' . $value . '</g:shipping_weight>' . "\r\n";
								} else if ( 'shipping_length' == $key || 'shipping_width' == $key || 'shipping_height' == $key ) {
									$file_contents .= '<g:' . $key . '>' . htmlspecialchars( $value ) . ' ' .( ( isset( $attributes->shipping_unit ) ) ? $attributes->shipping_unit : 'in' ) . '</g:' . $key . '>' . "\r\n";
								} else if ( '' != $value && 'None Selected' != $value ) {
									$file_contents .= '<g:' . $key . '>' . htmlspecialchars( $value ) . '</g:' . $key . '>' . "\r\n";
								}
							}
							$file_contents .= '</item>' . "\r\n";
						}
					}
				}
			}
			$file_contents .= '</channel>' . "\r\n";
			$file_contents .= '</rss>' . "\r\n";

			$xml_shortname = 'Google_Merchant_Feed_' . date( 'Y_m_d' ) . '.xml';
			$xmlname = EC_PLUGIN_DATA_DIRECTORY . '/' . $xml_shortname;

			file_put_contents( $xmlname, $file_contents );

			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Content-type: text/xml' );
			header( 'Content-Disposition: attachment; filename=' . $xml_shortname );
			header( 'Content-Length: ' . (string) ( filesize( $xmlname ) ) );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: private' );
			header( 'Pragma: private' );

			readfile( $xmlname );
			unlink( $xmlname );

			die();
		}
	}

	public function process_upload_feed() {
		if ( isset( $_GET['ec_admin_form_action'] ) && 'upload-google-csv' == $_GET['ec_admin_form_action'] ) {
			if ( ! isset( $_FILES['csv_file']['tmp_name'] ) ) {
				return;
			}
			global $wpdb;
			$errors_list = array();
			$file = fopen( sanitize_text_field( wp_unslash( $_FILES['csv_file']['tmp_name'] ) ), 'r' );
			$headers = fgetcsv( $file );
			if ( 'product_id' != $headers[0] ) {
				esc_attr_e( 'You must upload a CSV with the first column product_id', 'wp-easycart-pro' );
				die();
			} else if ( 41 != count( $headers ) ) {
				esc_attr_e( 'You must have 41 columns in your CSV file. You should download and add content, do not delete columns or rows.', 'wp-easycart-pro' );
				die();
			} else {
				$line_number = 1;
				$eof_reached = false;
				while ( ! feof( $file ) && ! $eof_reached ) {
					$row = fgetcsv( $file );
					if ( strlen( trim( $row[0] ) ) <= 0 ) {
						$eof_reached = true;
					} else {
						$product_id = (int) $row[0];
						$variant_id = ( isset( $row[1] ) && '' != $row[1] && (int) $row[1] > 0 ) ? $row[1] : 0;
						$attribute_array = array();
						if ( $variant_id > 0 ) {
							$attribute_array['optionitemquantity_id'] = $variant_id;
						}
						if ( isset( $row[2] ) && '' != $row[2] ) {
							$attribute_array['enabled'] = $row[2];
						}
						if ( isset( $row[3] ) && '' != $row[3] ) {
							$attribute_array['title'] = $row[3];
						}
						if ( isset( $row[4] ) && '' != $row[4] ) {
							$attribute_array['google_product_category'] = $row[4];
						}
						if ( isset( $row[5] ) && '' != $row[5] ) {
							$attribute_array['product_type'] = $row[5];
						}
						if ( isset( $row[6] ) && '' != $row[6] ) {
							$attribute_array['condition'] = $row[6];
						}
						if ( isset( $row[7] ) && '' != $row[7] ) {
							$attribute_array['identifier_exists'] = $row[7];
						}
						if ( isset( $row[8] ) && '' != $row[8] ) {
							$attribute_array['gtin'] = $row[8];
						}
						if ( isset( $row[9] ) && '' != $row[9] ) {
							$attribute_array['mpn'] = $row[9];
						}
						if ( isset( $row[10] ) && '' != $row[10] ) {
							$attribute_array['availability'] = $row[10];
						}
						if ( isset( $row[11] ) && '' != $row[11] ) {
							$attribute_array['availability_date'] = $row[11];
						}
						if ( isset( $row[12] ) && '' != $row[12] ) {
							$attribute_array['expiration_date'] = $row[12];
						}
						if ( isset( $row[13] ) && '' != $row[13] ) {
							$attribute_array['gender'] = $row[13];
						}
						if ( isset( $row[14] ) && '' != $row[14] ) {
							$attribute_array['age_group'] = $row[14];
						}
						if ( isset( $row[15] ) && '' != $row[15] ) {
							$attribute_array['size_type'] = $row[15];
						}
						if ( isset( $row[16] ) && '' != $row[16] ) {
							$attribute_array['size_system'] = $row[16];
						}
						if ( isset( $row[17] ) && '' != $row[17] ) {
							$attribute_array['item_group_id'] = $row[17];
						}
						if ( isset( $row[18] ) && '' != $row[18] ) {
							$attribute_array['color'] = $row[18];
						}
						if ( isset( $row[19] ) && '' != $row[19] ) {
							$attribute_array['material'] = $row[19];
						}
						if ( isset( $row[20] ) && '' != $row[20] ) {
							$attribute_array['pattern'] = $row[20];
						}
						if ( isset( $row[21] ) && '' != $row[21] ) {
							$attribute_array['size'] = $row[21];
						}
						if ( isset( $row[22] ) && '' != $row[22] ) {
							$attribute_array['weight_type'] = $row[22];
						}
						if ( isset( $row[23] ) && '' != $row[23] ) {
							$attribute_array['shipping_weight'] = $row[23];
						}
						if ( isset( $row[24] ) && '' != $row[24] ) {
							$attribute_array['unit_pricing_base_measure'] = $row[24];
						}
						if ( isset( $row[25] ) && '' != $row[25] ) {
							$attribute_array['unit_pricing_measure'] = $row[25];
						}
						if ( isset( $row[26] ) && '' != $row[26] ) {
							$attribute_array['shipping_label'] = $row[26];
						}
						if ( isset( $row[27] ) && '' != $row[27] ) {
							$attribute_array['shipping_unit'] = $row[27];
						}
						if ( isset( $row[28] ) && '' != $row[28] ) {
							$attribute_array['shipping_length'] = $row[28];
						}
						if ( isset( $row[29] ) && '' != $row[29] ) {
							$attribute_array['shipping_width'] = $row[29];
						}
						if ( isset( $row[30] ) && '' != $row[30] ) {
							$attribute_array['shipping_height'] = $row[30];
						}
						if ( isset( $row[31] ) && '' != $row[31] ) {
							$attribute_array['min_handling_time'] = $row[31];
						}
						if ( isset( $row[32] ) && '' != $row[32] ) {
							$attribute_array['max_handling_time'] = $row[32];
						}
						if ( isset( $row[33] ) && '' != $row[33] ) {
							$attribute_array['adult'] = $row[33];
						}
						if ( isset( $row[34] ) && '' != $row[34] ) {
							$attribute_array['multipack'] = $row[34];
						}
						if ( isset( $row[35] ) && '' != $row[35] ) {
							$attribute_array['is_bundle'] = $row[35];
						}
						if ( isset( $row[36] ) && '' != $row[36] ) {
							$attribute_array['certification'] = $row[36];
						}
						if ( isset( $row[37] ) && '' != $row[37] ) {
							$attribute_array['certification_code'] = $row[37];
						}
						if ( isset( $row[38] ) && '' != $row[38] ) {
							$attribute_array['energy_efficiency_class'] = $row[38];
						}
						if ( isset( $row[39] ) && '' != $row[39] ) {
							$attribute_array['min_energy_efficiency_class'] = $row[39];
						}
						if ( isset( $row[40] ) && '' != $row[40] ) {
							$attribute_array['max_energy_efficiency_class'] = $row[40];
						}

						if ( ! $variant_id ) {
							$attribute_json = json_encode( $attribute_array );
							$product = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.product_id FROM ec_product WHERE ec_product.product_id = %d', $product_id ) );
							if ( $product ) {
								$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_product_google_attributes WHERE product_id = %d', $product_id ) );
								$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product_google_attributes( product_id, attribute_value ) VALUES( %d, %s )', $product_id, $attribute_json ) );
							} else {
								// translators: placeholder 1 is a product id and placeholder 2 is a line number that an error occurred.
								$errors_list[] = sprintf( esc_attr__( 'No product found with product_id %1$d on line %2$d', 'wp-easycart-pro' ), esc_attr( $product_id ), esc_attr( $line_number ) );
							}
						} else {
							$attribute_json = json_encode( (object) $attribute_array );
							$variant_item = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitemquantity_id = %d', $product_id, $variant_id ) );
							if ( $variant_item ) {
								$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET google_merchant = %s WHERE product_id = %d AND optionitemquantity_id = %d', $attribute_json, $product_id, $variant_id ) );
							} else {
								// translators: placeholder 1 is a product id and placeholder 2 is a line number that an error occurred.
								$errors_list[] = sprintf( esc_attr__( 'No product/variant found with product_id %1$d and variant_id %2$d on line %3$d', 'wp-easycart-pro' ), esc_attr( $product_id ), esc_attr( $variant_id ), esc_attr( $line_number ) );
							}
						}
						$line_number++;
					}
				}
				fclose( $file );
			}
			if ( count( $errors_list ) > 0 ) {
				echo '<ul>';
				foreach ( $errors_list as $error_item ) {
					echo '<li>' . esc_attr( $error_item ) . '</li>';
				}
				echo '</ul>';
			} else {
				header( 'location:admin.php?page=wp-easycart-settings&subpage=third-party&success=google-import-complete' );
			}
			die();
		}
	}
}
endif; // End if class_exists check

function wp_easycart_admin_third_party_pro( ){
	return wp_easycart_admin_third_party_pro::instance( );
}
wp_easycart_admin_third_party_pro( );

add_action( 'wp_ajax_ec_admin_ajax_save_deconetwork_settings_pro', 'ec_admin_ajax_save_deconetwork_settings_pro' );
function ec_admin_ajax_save_deconetwork_settings_pro( ){
	wp_easycart_admin_third_party_pro( )->save_deconetwork_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_amazon_settings_pro', 'ec_admin_ajax_save_amazon_settings_pro' );
function ec_admin_ajax_save_amazon_settings_pro( ){
	wp_easycart_admin_third_party_pro( )->save_amazon_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_facebook_settings_pro', 'ec_admin_ajax_save_facebook_settings_pro' );
function ec_admin_ajax_save_facebook_settings_pro( ){
	wp_easycart_admin_third_party_pro( )->save_facebook_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_mailerlite_settings', 'ec_admin_ajax_save_mailerlite_settings' );
function ec_admin_ajax_save_mailerlite_settings( ){
	wp_easycart_admin_third_party_pro( )->save_mailerlite_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_convertkit_settings', 'ec_admin_ajax_save_convertkit_settings' );
function ec_admin_ajax_save_convertkit_settings( ){
	wp_easycart_admin_third_party_pro( )->save_convertkit_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_activecampaign_settings', 'ec_admin_ajax_save_activecampaign_settings' );
function ec_admin_ajax_save_activecampaign_settings( ){
	wp_easycart_admin_third_party_pro( )->save_activecampaign_settings( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_google_ga4_pro', 'ec_admin_ajax_save_google_ga4_pro' );
function ec_admin_ajax_save_google_ga4_pro() {
	wp_easycart_admin_third_party_pro()->save_google_ga4();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_google_adwords_pro', 'ec_admin_ajax_save_google_adwords_pro' );
function ec_admin_ajax_save_google_adwords_pro() {
	wp_easycart_admin_third_party_pro()->save_google_adwords();
	die();
}
