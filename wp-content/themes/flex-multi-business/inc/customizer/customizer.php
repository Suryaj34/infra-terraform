<?php
/**
 * Flex Multi Business Theme Customizer
 *
 * @subpackage flex-multi-business
 * @since 1.0 
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function flex_multi_business_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'flex_multi_business_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'flex_multi_business_customize_partial_blogdescription',
		) );
	}

    $wp_customize->add_setting('flex_multi_business_site_tagline', array(
        'default'           => false,
        'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_site_tagline', array(
        'label'    => __('Show / Hide Site tagline', 'flex-multi-business'),
        'section'  => 'title_tagline',
        'settings' => 'flex_multi_business_site_tagline',
        'type'     => 'checkbox',
    ));

	$wp_customize->add_section('flex_multi_business_header_section', array(
        'title'       => __('Header Section', 'flex-multi-business'),
        'priority'    => 30,
    ));

    // Add a Setting
    $wp_customize->add_setting('flex_multi_business_display_phone_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add a Control (Text Field)
    $wp_customize->add_control('flex_multi_business_display_phone_number', array(
        'label'    => __('Enter Phone Number', 'flex-multi-business'),
        'section'  => 'flex_multi_business_header_section',
        'settings' => 'flex_multi_business_display_phone_number',
        'type'     => 'text',
    ));

    /* TYPOGRAPHY SETTING */
	$flex_multi_business_font_array = array(
		''                       => 'No Fonts',
		'Abril Fatface'          => 'Abril Fatface',
		'Acme'                   => 'Acme',
		'Anton'                  => 'Anton',
		'Architects Daughter'    => 'Architects Daughter',
		'Arimo'                  => 'Arimo',
		'Arsenal'                => 'Arsenal',
		'Arvo'                   => 'Arvo',
		'Alegreya'               => 'Alegreya',
		'Alfa Slab One'          => 'Alfa Slab One',
		'Averia Serif Libre'     => 'Averia Serif Libre',
		'Bangers'                => 'Bangers',
		'Boogaloo'               => 'Boogaloo',
		'Bad Script'             => 'Bad Script',
		'Bitter'                 => 'Bitter',
		'Bree Serif'             => 'Bree Serif',
		'BenchNine'              => 'BenchNine',
		'Cabin'                  => 'Cabin',
		'Cardo'                  => 'Cardo',
		'Courgette'              => 'Courgette',
		'Cherry Swash'           => 'Cherry Swash',
		'Cormorant Garamond'     => 'Cormorant Garamond',
		'Crimson Text'           => 'Crimson Text',
		'Cuprum'                 => 'Cuprum',
		'Cookie'                 => 'Cookie',
		'Chewy'                  => 'Chewy',
		'Days One'               => 'Days One',
		'Dosis'                  => 'Dosis',
		'Droid Sans'             => 'Droid Sans',
		'Economica'              => 'Economica',
		'Fredoka One'            => 'Fredoka One',
		'Fjalla One'             => 'Fjalla One',
		'Francois One'           => 'Francois One',
		'Frank Ruhl Libre'       => 'Frank Ruhl Libre',
		'Gloria Hallelujah'      => 'Gloria Hallelujah',
		'Great Vibes'            => 'Great Vibes',
		'Handlee'                => 'Handlee',
		'Hammersmith One'        => 'Hammersmith One',
		'Inconsolata'            => 'Inconsolata',
		'Indie Flower'           => 'Indie Flower',
		'IM Fell English SC'     => 'IM Fell English SC',
		'Julius Sans One'        => 'Julius Sans One',
		'Josefin Slab'           => 'Josefin Slab',
		'Josefin Sans'           => 'Josefin Sans',
		'Kanit'                  => 'Kanit',
		'Lobster'                => 'Lobster',
		'Lato'                   => 'Lato',
		'Lora'                   => 'Lora',
		'Libre Baskerville'      => 'Libre Baskerville',
		'Lobster Two'            => 'Lobster Two',
		'Merriweather'           => 'Merriweather',
		'Monda'                  => 'Monda',
		'Montserrat'             => 'Montserrat',
		'Muli'                   => 'Muli',
		'Marck Script'           => 'Marck Script',
		'Noto Serif'             => 'Noto Serif',
		'Open Sans'              => 'Open Sans',
		'Overpass'               => 'Overpass',
		'Overpass Mono'          => 'Overpass Mono',
		'Oxygen'                 => 'Oxygen',
		'Orbitron'               => 'Orbitron',
		'Patua One'              => 'Patua One',
		'Pacifico'               => 'Pacifico',
		'Padauk'                 => 'Padauk',
		'Playball'               => 'Playball',
		'Playfair Display'       => 'Playfair Display',
		'PT Sans'                => 'PT Sans',
		'Philosopher'            => 'Philosopher',
		'Permanent Marker'       => 'Permanent Marker',
		'Poiret One'             => 'Poiret One',
		'Quicksand'              => 'Quicksand',
		'Quattrocento Sans'      => 'Quattrocento Sans',
		'Raleway'                => 'Raleway',
		'Rubik'                  => 'Rubik',
		'Rokkitt'                => 'Rokkitt',
		'Russo One'              => 'Russo One',
		'Righteous'              => 'Righteous',
		'Slabo'                  => 'Slabo',
		'Source Sans Pro'        => 'Source Sans Pro',
		'Shadows Into Light Two' => 'Shadows Into Light Two',
		'Shadows Into Light'     => 'Shadows Into Light',
		'Sacramento'             => 'Sacramento',
		'Shrikhand'              => 'Shrikhand',
		'Tangerine'              => 'Tangerine',
		'Ubuntu'                 => 'Ubuntu',
		'VT323'                  => 'VT323',
		'Varela Round'           => 'Varela Round',
		'Vampiro One'            => 'Vampiro One',
		'Vollkorn'               => 'Vollkorn',
		'Volkhov'                => 'Volkhov',
		'Yanone Kaffeesatz'      => 'Yanone Kaffeesatz'
	);

    $wp_customize->add_section('flex_multi_business_typography_settings', array(
        'title'       => __('Typography', 'flex-multi-business'),
        'priority'    => 30,
    ));

    $wp_customize->add_setting('flex_multi_business_body_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_body_font_family', array(
        'label'    => __('Body font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_body_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_body_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_body_font_family', array(
        'label'    => __('Body font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_body_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h1_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h1_font_family', array(
        'label'    => __('H1 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h1_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h2_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h2_font_family', array(
        'label'    => __('H2 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h2_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h3_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h3_font_family', array(
        'label'    => __('H3 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h3_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h4_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h4_font_family', array(
        'label'    => __('H4 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h4_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h5_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h5_font_family', array(
        'label'    => __('H5 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h5_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    $wp_customize->add_setting('flex_multi_business_h6_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'flex_multi_business_sanitize_choices',
    ));

    $wp_customize->add_control('flex_multi_business_h6_font_family', array(
        'label'    => __('H6 font family', 'flex-multi-business'),
        'section'  => 'flex_multi_business_typography_settings',
        'settings' => 'flex_multi_business_h6_font_family',
        'type'     => 'select',
        'choices'  => $flex_multi_business_font_array,
    ));

    /* GENERAL SETTINGS */

    $wp_customize->add_section('flex_multi_business_general_settings', array(
        'title'       => __('General Settings', 'flex-multi-business'),
        'priority'    => 30,
    ));

    // Wow Animation //

    $wp_customize->add_setting('flex_multi_business_animation', array(
        'default'           => true,
        'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_animation', array(
      'label'    => __('Enable/Disable Animation', 'flex-multi-business'),
      'section'  => 'flex_multi_business_general_settings',
      'settings' => 'flex_multi_business_animation',
      'type'     => 'checkbox',
    ));

    // Preloader //

    $wp_customize->add_setting('flex_multi_business_preloader', array(
    'default'           => false,
    'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_preloader', array(
      'label'    => __('Enable/Disable Preloader', 'flex-multi-business'),
      'section'  => 'flex_multi_business_general_settings',
      'settings' => 'flex_multi_business_preloader',
      'type'     => 'checkbox',
    ));

    // SIDEBAR LAYOUT //

     $wp_customize->add_setting('flex_multi_business_sidebar_layout_section', array(
        'default'           => 'has-right-sidebar',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('flex_multi_business_sidebar_layout_section', array(
        'label'    => __('Sidebar Layout', 'flex-multi-business'),
        'section'  => 'flex_multi_business_general_settings',
        'settings' => 'flex_multi_business_sidebar_layout_section',
        'type'     => 'select',
        'choices'  => [
            'has-left-sidebar'  => __('Left Sidebar', 'flex-multi-business'),
            'has-right-sidebar' => __('Right Sidebar', 'flex-multi-business'),
            'one-column'        => __('One Column', 'flex-multi-business'),
        ],
    ));

    /* FOOTER SETTINGS */

    $wp_customize->add_section('flex_multi_business_footer_settings', array(
        'title'       => __('Footer Settings', 'flex-multi-business'),
        'priority'    => 30,
    ));

    $wp_customize->add_setting('flex_multi_business_footer_widget', array(
    'default'           => true,
    'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_footer_widget', array(
      'label'    => __('Enable/Disable Footer Widget', 'flex-multi-business'),
      'section'  => 'flex_multi_business_footer_settings',
      'settings' => 'flex_multi_business_footer_widget',
      'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('flex_multi_business_footer_copyright', array(
        'default'           => true,
        'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_footer_copyright', array(
      'label'    => __('Enable/Disable Footer Copyright', 'flex-multi-business'),
      'section'  => 'flex_multi_business_footer_settings',
      'settings' => 'flex_multi_business_footer_copyright',
      'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('flex_multi_business_footer_scroll_top', array(
        'default'           => true,
        'sanitize_callback' => 'flex_multi_business_sanitize_checkbox',
    ));

    $wp_customize->add_control('flex_multi_business_footer_scroll_top', array(
      'label'    => __('Enable/Disable Scroll To Top', 'flex-multi-business'),
      'section'  => 'flex_multi_business_footer_settings',
      'settings' => 'flex_multi_business_footer_scroll_top',
      'type'     => 'checkbox',
    ));
}
add_action( 'customize_register', 'flex_multi_business_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */

function flex_multi_business_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function flex_multi_business_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

// Load customize sanitize.
include get_template_directory() . '/inc/customizer/sanitize.php';