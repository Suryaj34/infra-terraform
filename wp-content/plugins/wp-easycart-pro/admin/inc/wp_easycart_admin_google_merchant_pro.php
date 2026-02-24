<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_google_merchant_pro' ) ) :

	final class wp_easycart_admin_google_merchant_pro {

		protected static $_instance = null;

		public $google_merchant_categories_list_file;
		public $google_merchant_categories;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->google_merchant_categories_list_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/third-party/google-merchant-list.txt';
			$this->process_list();
		}

		public function process_list() {
			$this->google_merchant_categories = array();
			ob_start();
			include( $this->google_merchant_categories_list_file );
			$contents = ob_get_contents();
			ob_end_clean();
			$lines = explode(PHP_EOL, $contents );
			foreach ( $lines as $line ) {
				$values = explode( ' - ', $line );
				if ( is_array( $values ) && count( $values ) == 2 ) {
					$this->google_merchant_categories[] = (object) array(
						'id' => $values[0],
						'value' => $values[1],
					);
				}
			}
		}
	}

endif;

function wp_easycart_admin_google_merchant_pro() {
	return wp_easycart_admin_google_merchant_pro::instance();
}
wp_easycart_admin_google_merchant_pro();
