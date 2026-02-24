<?php
/**
 * 404 Section
 * 
 * slug: ecommerce-gadget-store/404-page
 * title: 404 Page
 * categories: ecommerce-gadget-store
 */

return array(
    'title'      =>__( '404 Page', 'ecommerce-gadget-store' ),
    'categories' => array( 'ecommerce-gadget-store' ),
    'content'    => '<!-- wp:heading {"textAlign":"center","className":"not-found-heading","style":{"typography":{"fontSize":"200px"},"elements":{"link":{"color":{"text":"var:preset|color|accent"}}}},"textColor":"accent"} -->
    <h2 class="wp-block-heading has-text-align-center not-found-heading has-accent-color has-text-color has-link-color" style="font-size:200px">'. esc_html__('404','ecommerce-gadget-store') .'</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","fontSize":"content-heading"} -->
    <p class="has-text-align-center has-content-heading-font-size"><strong>'. esc_html__('Unfortunately we can’t find that page.','ecommerce-gadget-store') .'</strong></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center">'. esc_html__('The page you are looking for doesnt exist or has been moved. Try another url or go to the site homepage.','ecommerce-gadget-store') .'</p>
    <!-- /wp:paragraph -->

    <!-- wp:search {"label":"Search","showLabel":false,"width":75,"widthUnit":"%","buttonText":"Search","align":"center","style":{"color":{"background":"var(--wp--preset--color--accent)"}}} /-->',
);