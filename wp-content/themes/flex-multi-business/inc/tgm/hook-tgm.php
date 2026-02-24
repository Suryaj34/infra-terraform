<?php
/**
 * Recommended plugins
 *
 * @package flex-multi-business
 */

if ( ! function_exists( 'flex_multi_business_recommended_plugins' ) ) :

    /**
     * Recommend plugins.
     *
     * @since 1.0.0
     */
    function flex_multi_business_recommended_plugins() {

        $plugins = array(  

            array(
                'name'     => esc_html__( 'Flex Import', 'flex-multi-business' ),
                'slug'     => 'flex-import',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Elementor Website Builder – More Than Just a Page Builder', 'flex-multi-business' ),
                'slug'     => 'elementor',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'ElementsKit Elementor Addons and Templates', 'flex-multi-business' ),
                'slug'     => 'elementskit-lite',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Gtranslate', 'flex-multi-business' ),
                'slug'     => 'gtranslate',
                'required' => false,
            ),            
        );

        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'flex_multi_business_recommended_plugins' );
