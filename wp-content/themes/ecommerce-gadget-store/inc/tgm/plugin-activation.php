<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Ecommerce Gadget Store for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'ecommerce_gadget_store_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function ecommerce_gadget_store_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'      => esc_html__( 'Stackable – Page Builder Gutenberg Blocks', 'ecommerce-gadget-store' ),
			'slug'      => 'stackable-ultimate-gutenberg-blocks',
			'required'  => false,
		),
		array(
			'name'      => esc_html__( 'WooCommerce', 'ecommerce-gadget-store' ),
			'slug'      => 'woocommerce',
			'required'  => false,
		),
		array(
			'name'      => esc_html__( 'Translate WordPress with GTranslate', 'ecommerce-gadget-store' ),
			'slug'      => 'gtranslate',
			'required'  => false,
		),
		array(
			'name'      => esc_html__( 'WordClever – AI Content Writer', 'ecommerce-gadget-store' ),
			'slug'      => 'wordclever-ai-content-writer',
			'required'  => false,
        ),
        array(
			'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'ecommerce-gadget-store' ),
			'slug'      => 'yith-woocommerce-wishlist',
			'required'  => false,
		),
	);
    
	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'ecommerce-gadget-store', // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

// WordClever – AI Content Writer plugin activation
add_action('wp_ajax_install_and_activate_required_plugin', 'install_and_activate_required_plugin');

function install_and_activate_required_plugin() {
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'install_activate_nonce')) {
        wp_send_json_error(['message' => 'Nonce verification failed.']);
    }

    // Include necessary WordPress files
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    $ecommerce_gadget_store_upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());

    // Define required plugins
    $plugins = [
        [
            'slug'     => 'woocommerce',
            'file'     => 'woocommerce/woocommerce.php',
            'url'      => 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
        ],
        [
            'slug'     => 'wordclever-ai-content-writer',
            'file'     => 'wordclever-ai-content-writer/wordclever.php',
            'url'      => 'https://downloads.wordpress.org/plugin/wordclever-ai-content-writer.latest-stable.zip',
        ],
        [
            'slug'     => 'yith-woocommerce-wishlist',
            'file'     => 'yith-woocommerce-wishlist/init.php',
            'url'      => 'https://downloads.wordpress.org/plugin/yith-woocommerce-wishlist.latest-stable.zip',
        ],
        [
            'slug'     => 'stackable-ultimate-gutenberg-blocks',
            'file'     => 'stackable-ultimate-gutenberg-blocks/plugin.php',
            'url'      => 'https://downloads.wordpress.org/plugin/stackable-ultimate-gutenberg-blocks.latest-stable.zip',
        ],
        [
            'slug'     => 'gtranslate',
            'file'     => 'gtranslate/gtranslate.php',
            'url'      => 'https://downloads.wordpress.org/plugin/gtranslate.latest-stable.zip',
        ]
    ];

    $ecommerce_gadget_store_installed_plugins = get_plugins();

    foreach ($plugins as $plugin) {
        // Install if not present
        if (!isset($ecommerce_gadget_store_installed_plugins[$plugin['file']])) {
            $ecommerce_gadget_store_install_result = $ecommerce_gadget_store_upgrader->install($plugin['url']);
            if (is_wp_error($ecommerce_gadget_store_install_result)) {
                wp_send_json_error(['message' => "Failed to install {$plugin['slug']}"]);
            }
        }

        // Activate if not active
        if (!is_plugin_active($plugin['file'])) {
            $ecommerce_gadget_store_activate_result = activate_plugin($plugin['file']);
            if (is_wp_error($ecommerce_gadget_store_activate_result)) {
                wp_send_json_error([
                    'message' => "Failed to activate {$plugin['slug']}",
                    'error'   => $$ecommerce_gadget_store_activate_result->get_error_message(),
                ]);
            }
        }
    }

    // Success response
    wp_send_json_success(['message' => 'WooCommerce and WordClever plugins are activated successfully.']);
}

