<?php
/**
 * flex-multi-business functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package flex-multi-business
 */

/* Breadcrumb Begin */
function flex_multi_business_the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
			echo esc_url( home_url() );
		echo '">';
			bloginfo('name');
		echo "</a> >> ";
		if (is_category() || is_single()) {
			the_category('>>');
			if (is_single()) {
				echo ">> <span> ";
					the_title();
				echo "</span> ";
			}
		} elseif (is_page()) {
			echo "<span> ";
				the_title();
		}
	}
}

if ( ! defined( 'FLEX_MULTI_BUSINESS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'FLEX_MULTI_BUSINESS_VERSION', '1.3' );
	define( 'FLEX_MULTI_BUSINESS_THEME_DIR', get_template_directory() . '/' );
	define( 'FLEX_MULTI_BUSINESS_THEME_URI', get_template_directory_uri() . '/' );
}

if ( ! function_exists( 'flex_multi_business_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function flex_multi_business_setup() {

		load_theme_textdomain( 'flex-multi-business', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		add_theme_support( 'responsive-embeds' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary-menu' => esc_html__( 'Primary Menu', 'flex-multi-business' ),
				'footer-menu' => esc_html__( 'Footer Menu', 'flex-multi-business' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'flex_multi_business_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'flex_multi_business_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function flex_multi_business_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'flex_multi_business_content_width', 640 );

	add_filter( 'elementor_enable_onboarding', '__return_false' );

	if ( ! defined( 'FLEX_MULTI_BUSINESS_DOCUMENTATION' ) ) {
        define( 'FLEX_MULTI_BUSINESS_DOCUMENTATION', __( 'https://demo.flextheme.net/doc/flex-free-multi-business-doc', 'flex-multi-business' ));
    }
    if ( ! defined( 'FLEX_MULTI_BUSINESS_SUPPORT' ) ) {
    define('FLEX_MULTI_BUSINESS_SUPPORT',__('https://wordpress.org/support/theme/flex-multi-business/','flex-multi-business'));
    }
    if ( ! defined( 'FLEX_MULTI_BUSINESS_REVIEW' ) ) {
    define('FLEX_MULTI_BUSINESS_REVIEW',__('https://wordpress.org/support/theme/flex-multi-business/reviews/','flex-multi-business'));
    }
    if ( ! defined( 'FLEX_MULTI_BUSINESS_BUY_NOW' ) ) {
    define('FLEX_MULTI_BUSINESS_BUY_NOW',__('https://www.flextheme.net/products/flex-pro-wordpress-theme','flex-multi-business'));
    }
    if ( ! defined( 'FLEX_MULTI_BUSINESS_LIVE_DEMO' ) ) {
    define('FLEX_MULTI_BUSINESS_LIVE_DEMO',__('https://demo.flextheme.net/flex-multi-business-pro/','flex-multi-business'));
    }
}
add_action( 'after_setup_theme', 'flex_multi_business_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function flex_multi_business_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'flex-multi-business' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'flex-multi-business' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widgets', 'flex-multi-business' ),
			'id'            => 'footer-widgets',
			'description'   => esc_html__( 'Add widgets here.', 'flex-multi-business' ),
			'before_widget' => '<div class="%2$s footer-widget col-md-3 col-sm-6 col-xs-12">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
		array(
			'name'          => esc_html__( 'WooCommerce Sidebar', 'flex-multi-business' ),
			'id'            => 'woocommerce-widgets',
			'description'   => esc_html__( 'Add widgets here.', 'flex-multi-business' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	}
}
add_action( 'widgets_init', 'flex_multi_business_widgets_init' );

function flex_multi_business_fonts_url() {
	$font_family   = array(
		'Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700',
		'Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Bad Script',
		'Bebas Neue',
		'Fjalla One',
		'PT Sans:ital,wght@0,400;0,700;1,400;1,700',
		'PT Serif:ital,wght@0,400;0,700;1,400;1,700',
		'Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900',
		'Roboto Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700',
		'Alex Brush',
		'Overpass:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Playball',
		'Alegreya:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900',
		'Julius Sans One',
		'Arsenal:ital,wght@0,400;0,700;1,400;1,700',
		'Slabo 13px',
		'Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900',
		'Overpass Mono:wght@300;400;500;600;700',
		'Source Sans Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900',
		'Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900',
		'Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
		'Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700',
		'Cabin:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
		'Arimo:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
		'Playfair Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900',
		'Quicksand:wght@300..700',
		'Padauk:wght@400;700',
		'Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000',
		'Inconsolata:wght@200;300;400;500;600;700;800;900&family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000',
		'Bitter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000',
		'Pacifico',
		'Indie Flower',
		'VT323',
		'Dosis:wght@200;300;400;500;600;700;800',
		'Frank Ruhl Libre:wght@300;400;500;700;900',
		'Fjalla One',
		'Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Oxygen:wght@300;400;700',
		'Arvo:ital,wght@0,400;0,700;1,400;1,700',
		'Noto Serif:ital,wght@0,400;0,700;1,400;1,700',
		'Lobster',
		'Crimson Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700',
		'Yanone Kaffeesatz:wght@200;300;400;500;600;700',
		'Anton',
		'Libre Baskerville:ital,wght@0,400;0,700;1,400',
		'Bree Serif',
		'Gloria Hallelujah',
		'Abril Fatface',
		'Varela Round',
		'Vampiro One',
		'Shadows Into Light',
		'Cuprum:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
		'Rokkitt:wght@100;200;300;400;500;600;700;800;900',
		'Vollkorn:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900',
		'Francois One',
		'Orbitron:wght@400;500;600;700;800;900',
		'Patua One',
		'Acme',
		'Satisfy',
		'Josefin Slab:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700',
		'Quattrocento Sans:ital,wght@0,400;0,700;1,400;1,700',
		'Architects Daughter',
		'Russo One',
		'Monda:wght@400;700',
		'Righteous',
		'Lobster Two:ital,wght@0,400;0,700;1,400;1,700',
		'Hammersmith One',
		'Courgette',
		'Permanent Marke',
		'Cherry Swash:wght@400;700',
		'Cormorant Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700',
		'Poiret One',
		'BenchNine:wght@300;400;700',
		'Economica:ital,wght@0,400;0,700;1,400;1,700',
		'Handlee',
		'Cardo:ital,wght@0,400;0,700;1,400',
		'Alfa Slab One',
		'Averia Serif Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700',
		'Cookie',
		'Chewy',
		'Great Vibes',
		'Coming Soon',
		'Philosopher:ital,wght@0,400;0,700;1,400;1,700',
		'Days One',
		'Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Shrikhand',
		'Tangerine:wght@400;700',
		'IM Fell English SC',
		'Boogaloo',
		'Bangers',
		'Fredoka One',
		'Volkhov:ital,wght@0,400;0,700;1,400;1,700',
		'Shadows Into Light Two',
		'Marck Script',
		'Sacramento',
		'Unica One',
		'Dancing Script:wght@400;500;600;700',
		'Exo 2:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Archivo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
		'DM Serif Display:ital@0;1',
		'Open Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800'
	);
	
	$query_args = array(
		'family'	=> rawurlencode(implode('|',$font_family)),
	);
	$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
	return $font_url;
	$contents = wptt_get_webfont_url( esc_url_raw( $fonts_url ) );
}

/**
 * Enqueue scripts and styles.
 */
function flex_multi_business_scripts() {
	
	wp_enqueue_style('dashicons');
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css');
	wp_enqueue_style('flex-multi-business-header-css', get_template_directory_uri() . '/assets/css/header.css');
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.css');
	wp_enqueue_style('flex-multi-business-responsive-css', get_template_directory_uri() . '/assets/css/responsive.css');
	wp_enqueue_style('flex-multi-business-custom-css', get_template_directory_uri() . '/assets/css/flex-multi-business-custom.css');
	wp_enqueue_style('flex-multi-business-woocommerce-css', get_template_directory_uri() . '/assets/css/flex-multi-business-woocommerce.css');

	if (get_theme_mod('flex_multi_business_animation', true) == true){
		wp_enqueue_script( 'flex-multi-business-wow-js', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'),'' ,true );
		wp_enqueue_style( 'flex-multi-business-animate-css', get_template_directory_uri().'/assets/css/animate.css' );
	}
	
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap', [], null );

	wp_enqueue_style( 'flex-multi-business-style', get_stylesheet_uri(), array(), FLEX_MULTI_BUSINESS_VERSION );
	wp_style_add_data( 'flex-multi-business-style', 'rtl', 'replace' );

	wp_enqueue_script( 'flex-multi-business-navigation', get_template_directory_uri() . '/js/navigation.js', array(), FLEX_MULTI_BUSINESS_VERSION, true );

	wp_enqueue_script( 'flex-multi-business-theme-js', get_template_directory_uri() . '/assets/js/theme.js',array('jquery'), FLEX_MULTI_BUSINESS_VERSION, true );

    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.js',array(), FLEX_MULTI_BUSINESS_VERSION, true );

    wp_enqueue_script( 'flex-multi-business-custom-js', get_template_directory_uri() . '/assets/js/custom.js',array(), FLEX_MULTI_BUSINESS_VERSION, true );  

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$fonts_url = flex_multi_business_fonts_url();
	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'flex-multi-business-google-fonts', $fonts_url, array(), null );
	}

	// body
	$flex_multi_business_body_font_family = get_theme_mod('flex_multi_business_body_font_family');

	// H1
	$flex_multi_business_h1_font_family = get_theme_mod('flex_multi_business_h1_font_family');
	$flex_multi_business_h1_font_size   = get_theme_mod('flex_multi_business_h1_font_size');

	// H2
	$flex_multi_business_h2_font_family = get_theme_mod('flex_multi_business_h2_font_family');
	$flex_multi_business_h2_font_size   = get_theme_mod('flex_multi_business_h2_font_size');

	// H3
	$flex_multi_business_h3_font_family = get_theme_mod('flex_multi_business_h3_font_family');
	$flex_multi_business_h3_font_size   = get_theme_mod('flex_multi_business_h3_font_size');

	// H4
	$flex_multi_business_h4_font_family = get_theme_mod('flex_multi_business_h4_font_family');
	$flex_multi_business_h4_font_size   = get_theme_mod('flex_multi_business_h4_font_size');

	// H5
	$flex_multi_business_h5_font_family = get_theme_mod('flex_multi_business_h5_font_family');
	$flex_multi_business_h5_font_size   = get_theme_mod('flex_multi_business_h5_font_size');

	// H6
	$flex_multi_business_h6_font_family = get_theme_mod('flex_multi_business_h6_font_family');
	$flex_multi_business_h6_font_size   = get_theme_mod('flex_multi_business_h6_font_size');

	$flex_multi_business_custom_css = '
		body{
		    font-family: '.esc_html($flex_multi_business_body_font_family).'!important;
		}

		h1{
		    font-family: '.esc_html($flex_multi_business_h1_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h1_font_size).'px!important;
		}
		h2{
		    font-family: '.esc_html($flex_multi_business_h2_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h2_font_size).'px!important;
		}
		h3{
		    font-family: '.esc_html($flex_multi_business_h3_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h3_font_size).'px!important;
		}
		h4{
		    font-family: '.esc_html($flex_multi_business_h4_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h4_font_size).'px!important;
		}
		h5{
		    font-family: '.esc_html($flex_multi_business_h5_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h5_font_size).'px!important;
		}
		h6{
		    font-family: '.esc_html($flex_multi_business_h6_font_family).'!important;
		    font-size: '.esc_html($flex_multi_business_h6_font_size).'px!important;
		}
	';
	wp_add_inline_style('flex-multi-business-style', $flex_multi_business_custom_css);

}
add_action( 'wp_enqueue_scripts', 'flex_multi_business_scripts' );

/**
 * Nav Walker fo Bootstrap Dropdown Menu.
 */
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/controls.php';

/**
 * Add feature in Customizer
 */
require get_template_directory() . '/inc/customizer/customizer.php';
load_template( trailingslashit( get_template_directory() ) . '/inc/go-pro/class-upgrade-pro.php' );

/*Plugin Recomme
ndation*/
require get_template_directory()  . '/inc/tgm/class-tgm-plugin-activation.php';
require get_template_directory(). '/inc/tgm/hook-tgm.php';

// Get Notice
require get_template_directory() . '/inc/dashboard/getting-started.php';

/**
 * Load init.
 */
require_once trailingslashit(FLEX_MULTI_BUSINESS_THEME_DIR).'inc/init.php';

/* Enqueue admin-notice-script js */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'appearance_page_flex-multi-business') return;

    wp_enqueue_script('admin-notice-script', get_template_directory_uri() . '/dashboard/js/plugin-activation.js', ['jquery'], null, true);
    wp_localize_script('admin-notice-script', 'pluginInstallerData', [
        'ajaxurl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('install_flex_import_nonce'), // Match this with PHP nonce check
        'redirectUrl' => admin_url('admin.php?page=fleximp-template-importer'),
    ]);
});

add_action('wp_ajax_check_flex_import_activation', function () {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    $flex_multi_business_plugin_file = 'flex-import/flex-import.php';

    if (is_plugin_active($flex_multi_business_plugin_file) ) {
        wp_send_json_success(['active' => true]);
    } else {
        wp_send_json_success(['active' => false]);
    }
});

add_action( 'activated_plugin', function( $plugin ) {
	if ( $plugin === 'elementor/elementor.php' ) {
		delete_transient( 'elementor_activation_redirect' );
		add_filter( 'elementor_enable_onboarding', '__return_false' );
	}
});

//custom function conditional check for blog page
function flex_multi_business_is_blog (){
    return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
}
// Admin notice code START
function flex_multi_business_dismissed_notice() {
	update_option( 'flex_multi_business_admin_notice', true );
}
add_action( 'wp_ajax_flex_multi_business_dismissed_notice', 'flex_multi_business_dismissed_notice' );

//After Switch theme function
add_action('after_switch_theme', 'flex_multi_business_getstart_setup_options');
function flex_multi_business_getstart_setup_options () {
    update_option('flex_multi_business_admin_notice', false );
}
use Aws\S3\S3Client;

add_action('rest_api_init', function(){

register_rest_route('review-video/v1','/upload/',[
'methods'=>'POST',
'callback'=>'upload_video_review'
]);

});

function upload_video_review(){

require 'vendor/autoload.php';

$s3 = new S3Client([
'version'=>'latest',
'region'=>'ap-south-1'
]);

$name=$_POST['name'];
$review=$_POST['review'];
$file=$_FILES['video']['tmp_name'];

$commentdata = [
'comment_content'=>$review,
'comment_author'=>$name,
'comment_approved'=>0
];

$comment_id = wp_insert_comment($commentdata);

$key='review-'.$comment_id.'-'.time().'.mov';

$s3->putObject([
'Bucket'=>'review-video-input',
'Key'=>'uploads/'.$key,
'SourceFile'=>$file,
'ACL'=>'private'
]);

add_comment_meta($comment_id,'s3_input_key',$key);

return ['status'=>'uploaded'];

}
register_rest_route('review-video/v1','/update/',[
'methods'=>'POST',
'callback'=>'update_review_video'
]);

function update_review_video($req){

update_comment_meta(
$req['comment_id'],
'converted_video',
$req['video_url']
);

return ['status'=>'attached'];

}
add_shortcode('show_video_reviews','show_video_reviews');

function show_video_reviews(){

$comments=get_comments();

$out='';

foreach($comments as $c){

$video=get_comment_meta($c->comment_ID,'converted_video',true);

$out.='<p>'.$c->comment_content.'</p>';

if($video){

$out.='<video width="400" controls>
<source src="'.$video.'" type="video/mp4">
</video>';

}

}

return $out;

}


// Admin notice code END
