<?php
/**
 * Header Default
 * 
 * slug: ecommerce-gadget-store/header-default
 * title: Header Default
 * categories: ecommerce-gadget-store
 */

return array(
    'title'      =>__( 'Header Default', 'ecommerce-gadget-store' ),
    'categories' => array( 'ecommerce-gadget-store' ),
    'content'    => '<!-- wp:group {"className":"header-box-upper","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"100%","justifyContent":"center"}} -->
<div class="wp-block-group header-box-upper" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"className":"ecommerce-top-header","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"right":"0","left":"0","top":"0","bottom":"var:preset|spacing|20"}},"border":{"bottom":{"width":"1px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group ecommerce-top-header" style="border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:var(--wp--preset--spacing--20);padding-left:0"><!-- wp:columns {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"0","left":"0"}}}} -->
<div class="wp-block-columns" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"25%","className":"top-right"} -->
<div class="wp-block-column is-vertically-aligned-center top-right" style="flex-basis:25%"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"0"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"150px","className":"hedaer-social-icon"} -->
<div class="wp-block-column is-vertically-aligned-center hedaer-social-icon" style="flex-basis:150px"><!-- wp:social-links {"customIconColor":"#8c8c8c","iconColorValue":"#8c8c8c","openInNewTab":true,"size":"has-small-icon-size","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"left":"0"}}},"layout":{"type":"flex"}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"www.facebook.com","service":"facebook"} /-->

<!-- wp:social-link {"url":"www.pintrest.com","service":"pinterest"} /-->

<!-- wp:social-link {"url":"www.instagram.com","service":"instagram"} /-->

<!-- wp:social-link {"url":"www.twitter.com","service":"x"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"100px","className":"currency-text"} -->
<div class="wp-block-column is-vertically-aligned-center currency-text" style="flex-basis:100px"><!-- wp:shortcode -->
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"50%","className":"advertising-text","backgroundColor":"secaccent"} -->
<div class="wp-block-column is-vertically-aligned-top advertising-text has-secaccent-background-color has-background" style="flex-basis:50%"><!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"#1f1f1f"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"text":"#1f1f1f"}},"fontSize":"extra-small","fontFamily":"poppins"} -->
<p class="has-text-align-center has-text-color has-link-color has-poppins-font-family has-extra-small-font-size" style="color:#1f1f1f;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30);font-style:normal;font-weight:500">'. esc_html__('Free Shipping on Orders Over $50! Limited Time Offer','ecommerce-gadget-store').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"className":"top-col03"} -->
<div class="wp-block-column top-col03"><!-- wp:paragraph {"align":"left","className":"store-locator","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"small","fontFamily":"poppins"} -->
<p class="has-text-align-left store-locator has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-small-font-size"><a href="https://maps.app.goo.gl/SowZ8v8kN4uFRoVr6" target="_blank" ><img class="wp-image-74" style="width: 20px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/location.png" alt="">'. esc_html__('Store Locator','ecommerce-gadget-store').'</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"left","className":"track-order","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}},"textColor":"thirdaccent","fontSize":"small","fontFamily":"poppins"} -->
<p class="has-text-align-left track-order has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><a href="#"><img class="wp-image-75" style="width: 20px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/truck.png" alt="">'. esc_html__('Track your Order','ecommerce-gadget-store').'</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ecommerce-header-middle","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":"6px"}},"backgroundColor":"background","layout":{"type":"constrained","contentSize":"80%","justifyContent":"center"}} -->
<div class="wp-block-group ecommerce-header-middle has-background-background-color has-background" style="border-radius:6px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:columns {"align":"wide","className":"menu-group-top","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"left":"var:preset|spacing|20"}},"border":{"radius":{"bottomLeft":"10px","bottomRight":"10px","topLeft":"10px","topRight":"10px"}}}} -->
<div class="wp-block-columns alignwide menu-group-top" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"230px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:230px"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"230px","className":"header-logo"} -->
<div class="wp-block-column is-vertically-aligned-center header-logo" style="flex-basis:230px"><!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"textColor":"primary","fontSize":"content-heading","fontFamily":"poppins"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"","className":"middle-left","style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-column is-vertically-aligned-center middle-left" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":""} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","widthUnit":"%","buttonText":"Search Now","buttonPosition":"button-inside","buttonUseIcon":true,"query":{"post_type":"product"},"style":{"border":{"radius":"6px"},"spacing":{"margin":{"right":"0","left":"0"}}},"fontSize":"tiny","fontFamily":"quicksand"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"190px","className":"middle-right"} -->
<div class="wp-block-column is-vertically-aligned-center middle-right" style="flex-basis:190px"><!-- wp:columns {"className":"woo-icon","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns woo-icon"><!-- wp:column {"verticalAlignment":"center","width":"60px","className":"wishlist-column"} -->
<div class="wp-block-column is-vertically-aligned-center wishlist-column" style="flex-basis:60px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"tiny","fontFamily":"quicksand"} -->
<p class="has-text-align-center has-quicksand-font-family has-tiny-font-size" style="font-style:normal;font-weight:600"><a href="#"><img class="wp-image-82" style="width: 19px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/heart.png" alt=""></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"60px","className":"cart-column"} -->
<div class="wp-block-column is-vertically-aligned-center cart-column" style="flex-basis:60px"><!-- wp:woocommerce/mini-cart {"iconColor":{"color":"#8C8C8C","name":"Thirdaccent","slug":"thirdaccent","class":"has-thirdaccent-icon-color"},"productCountVisibility":"never","fontSize":"upper-heading"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"60px","className":"account-column"} -->
<div class="wp-block-column is-vertically-aligned-center account-column" style="flex-basis:60px"><!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconClass":"wc-block-customer-account__account-icon","align":"center","textColor":"thirdaccent","fontFamily":"quicksand","fontSize":"upper-heading","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}}} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ecommerce-header-bottom","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"80%","wideSize":"","justifyContent":"center"}} -->
<div class="wp-block-group ecommerce-header-bottom" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:columns {"className":"menu-group","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"0","left":"var:preset|spacing|20"}},"border":{"radius":{"bottomLeft":"10px","bottomRight":"10px","topLeft":"10px","topRight":"10px"}}}} -->
<div class="wp-block-columns menu-group" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--20);padding-right:0;padding-bottom:var(--wp--preset--spacing--20);padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"250px","className":"product-categories-button"} -->
<div class="wp-block-column is-vertically-aligned-center product-categories-button" style="flex-basis:250px"><!-- wp:image {"id":97,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/category.png" alt="" class="wp-image-97"/></figure>
<!-- /wp:image -->

<!-- wp:woocommerce/product-categories {"hasCount":false,"isDropdown":true,"isHierarchical":false} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"","className":"header-inner-menu","style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-column is-vertically-aligned-center header-inner-menu"><!-- wp:navigation {"textColor":"thirdaccent","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account","woocommerce/mini-cart"]},"className":"is-head-menu","style":{"typography":{"textTransform":"capitalize"},"spacing":{"blockGap":"var:preset|spacing|60"}},"fontSize":"small","fontFamily":"poppins","layout":{"type":"flex","justifyContent":"center"}} --> <!-- wp:navigation-link {"label":"Home","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->
        
    <!-- wp:navigation-link {"label":"About","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->
    
    <!-- wp:navigation-link {"label":"FeaturedProducts","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

    <!-- wp:navigation-link {"label":"Get Pro","type":"","url":"https://www.wpradiant.net/products/ecommerce-store-wordpress-theme","kind":"custom","isTopLevelLink":true,"className":"getpro","opensInNewTab":true} /-->
    
    <!-- /wp:navigation --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"250px","className":"bottom-call"} -->
<div class="wp-block-column is-vertically-aligned-center bottom-call" style="flex-basis:250px"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|20"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"40px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40px"><!-- wp:image {"id":100,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/headphones.png" alt="" class="wp-image-100"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"","style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-column"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent"} -->
<p class="has-thirdaccent-color has-text-color has-link-color"><a href="tel:12345678">'. esc_html__('Support:1-234-567-89','ecommerce-gadget-store').'</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"small"} -->
<p class="has-primary-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:600"><a href="mailto:info@example.com">'. esc_html__('info@exapmle.com','ecommerce-gadget-store').'</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);