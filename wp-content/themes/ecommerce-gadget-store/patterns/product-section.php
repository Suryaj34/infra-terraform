<?php
/**
 * Category Section
 * 
 * slug: ecommerce-gadget-store/product-section
 * title: Project Section
 * categories: ecommerce-gadget-store
 */


 $ecommerce_gadget_store_plugins_list = get_option('active_plugins');
 $ecommerce_gadget_store_woocommerce_plugin = 'woocommerce/woocommerce.php';
 $ecommerce_gadget_store_stackable_plugin = 'stackable-ultimate-gutenberg-blocks/plugin.php';
 $ecommerce_gadget_store_results = in_array($ecommerce_gadget_store_woocommerce_plugin, $ecommerce_gadget_store_plugins_list) && in_array($ecommerce_gadget_store_stackable_plugin, $ecommerce_gadget_store_plugins_list);
 
 if ($ecommerce_gadget_store_results) {

    return array(
        'title'      =>__( 'Product Section', 'ecommerce-gadget-store' ),
        'categories' => array( 'ecommerce-gadget-store' ),
        'content'    => '<!-- wp:spacer {"height":"40px"} -->
        <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->

        <!-- wp:group {"className":"services-box dynamic-product-section","layout":{"type":"constrained","contentSize":"80%"}} -->
        <div class="wp-block-group services-box dynamic-product-section"><!-- wp:group {"className":"service-group","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"100%"}} -->
        <div class="wp-block-group service-group"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}}} -->
        <div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:column {"width":"27%","className":"info-section wow tada","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"blockGap":"0"},"border":{"radius":"15px"}},"backgroundColor":"forthaccent"} -->
        <div class="wp-block-column info-section wow tada has-forthaccent-background-color has-background" style="border-radius:15px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:27%"><!-- wp:cover {"url":"'.esc_url(get_template_directory_uri()) .'/assets/images/category-img.png","id":7,"dimRatio":0,"customOverlayColor":"#433a3a","isUserOverlayColor":false,"minHeight":100,"minHeightUnit":"%","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
        <div class="wp-block-cover" style="border-radius:10px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:100%"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#433a3a"></span><img class="wp-block-cover__image-background wp-image-7" alt="" src="'.esc_url(get_template_directory_uri()) .'/assets/images/category-img.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
        <p class="has-text-align-center has-large-font-size"></p>
        <!-- /wp:paragraph --></div></div>
        <!-- /wp:cover --></div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"general-product-section wow bounceIn","style":{"spacing":{"blockGap":"0"}}} -->
        <div class="wp-block-column general-product-section wow bounceIn"><!-- wp:columns {"className":"product-heading","style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"var:preset|spacing|40"}}}} -->
        <div class="wp-block-columns product-heading" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40);padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":""} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"textAlign":"left","style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"elements":{"link":{"color":{"text":"var:preset|color|tertiary"}}},"spacing":{"padding":{"bottom":"var:preset|spacing|40"}}},"textColor":"tertiary","fontSize":"content-heading","fontFamily":"poppins"} -->
        <h2 class="wp-block-heading has-text-align-left has-tertiary-color has-text-color has-link-color has-poppins-font-family has-content-heading-font-size" style="padding-bottom:var(--wp--preset--spacing--40);font-style:normal;font-weight:700">'. esc_html__('Today’s Top Picks','ecommerce-gadget-store').'</h2>
        <!-- /wp:heading --></div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"93px"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:93px"><!-- wp:paragraph {"align":"right","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"thirdaccent","fontSize":"small","fontFamily":"poppins"} -->
        <p class="has-text-align-right has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:600"><a href="#">'. esc_html__('View all','ecommerce-gadget-store').'</a></p>
        <!-- /wp:paragraph --></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->

        <!-- wp:stackable/carousel {"uniqueId":"d5e6e41","autoplay":false,"autoplaySpeed":3000,"slidesToShow":3,"slideColumnGap":18,"infiniteScroll":true,"arrowJustify":"center","arrowAlign":"flex-end","arrowButtonColor":"#ED174A","arrowIconColor":"#fff","arrowBorderRadius":8,"showDots":false,"slidesToShowTablet":2,"slidesToShowMobile":1,"slideColumnGapMobile":16,"arrowButtonOffsetTablet":-54} -->
        <div class="wp-block-stackable-carousel stk-block-carousel stk--is-slide stk--infinite-scroll stk--arrows-justify-center stk--arrows-align-flex-end stk-block stk-d5e6e41" data-slides-to-show="3" data-block-id="d5e6e41"><style>.stk-d5e6e41 {--slides-to-show:3 !important;--gap:18px !important;}.stk-d5e6e41 .stk-block-carousel__buttons{justify-content:center !important;align-items:flex-end !important;}.stk-d5e6e41 .stk-block-carousel__button{background:#ED174A !important;fill:#fff !important;border-radius:8px !important;}.stk-d5e6e41 .stk-block-carousel__button svg.ugb-custom-icon :is(g, path, rect, polygon, ellipse){fill:#fff !important;}@media screen and (max-width: 1023px){.stk-d5e6e41 {--slides-to-show:2 !important;--button-offset:-54px !important;}}@media screen and (max-width: 767px){.stk-d5e6e41 {--slides-to-show:1 !important;--gap:16px !important;}}</style><div class="stk-block-carousel__content-wrapper"><div class="stk-row stk-inner-blocks stk-block-content stk-block-carousel__slider-wrapper stk-content-align stk-d5e6e41-column"><div class="stk-block-carousel__slider" role="list" data-label-slide-of="Slide %%d of %%d"><!-- wp:stackable/column {"uniqueId":"0bc118a","columnAdjacentCount":3,"columnSpacing":{"top":0,"right":0,"bottom":0,"left":0}} -->
        <div class="wp-block-stackable-column stk-block-column stk-column stk-block stk-0bc118a" data-v="4" data-block-id="0bc118a"><style>.stk-0bc118a-container{margin-top:0px !important;margin-right:0px !important;margin-bottom:0px !important;margin-left:0px !important;}</style><div class="stk-column-wrapper stk-block-column__content stk-container stk-0bc118a-container stk--no-background stk--no-padding"><div class="stk-block-content stk-inner-blocks stk-0bc118a-inner-blocks"><!-- wp:woocommerce/product-collection {"queryId":4,"query":{"perPage":1,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","search":"","exclude":[],"inherit":false,"taxQuery":{"product_cat":[]},"isProductCollectionBlock":true,"featured":false,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[],"filterable":true,"relatedBy":{"categories":true,"tags":true}},"tagName":"div","displayLayout":{"type":"list","columns":2,"shrinkColumns":true},"dimensions":{"widthType":"fill"},"queryContextIncludes":["collection"],"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the page being viewed."}} -->
        <div class="wp-block-woocommerce-product-collection"><!-- wp:woocommerce/product-template -->
        <!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->

        <!-- wp:columns {"className":"review-column","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
        <div class="wp-block-columns review-column" style="margin-top:0;margin-bottom:0"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:post-terms {"term":"product_cat","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small"} /-->

        <!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"textAlign":"right","style":{"elements":{"link":{"color":{"text":"#ffc000"}}},"color":{"text":"#ffc000"}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->

        <!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|20","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium","fontFamily":"poppins","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

        <!-- wp:columns {"className":"product-info-column"} -->
        <div class="wp-block-columns product-info-column"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","textColor":"accent","fontSize":"normal","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}}} /-->

        <!-- wp:woocommerce/product-button {"textAlign":"right","isDescendentOfQueryLoop":true,"className":"is-style-fill","backgroundColor":"background","textColor":"accent","fontSize":"small","style":{"border":{"radius":"5px"},"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"},"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->
        <!-- /wp:woocommerce/product-template --></div>
        <!-- /wp:woocommerce/product-collection --></div></div></div>
        <!-- /wp:stackable/column -->

        <!-- wp:stackable/column {"uniqueId":"a21ca2c","columnAdjacentCount":3,"columnSpacing":{"top":0,"right":0,"bottom":0,"left":0}} -->
        <div class="wp-block-stackable-column stk-block-column stk-column stk-block stk-a21ca2c" data-v="4" data-block-id="a21ca2c"><style>.stk-a21ca2c-container{margin-top:0px !important;margin-right:0px !important;margin-bottom:0px !important;margin-left:0px !important;}</style><div class="stk-column-wrapper stk-block-column__content stk-container stk-a21ca2c-container stk--no-background stk--no-padding"><div class="stk-block-content stk-inner-blocks stk-a21ca2c-inner-blocks"><!-- wp:woocommerce/product-collection {"queryId":8,"query":{"perPage":1,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","search":"","exclude":[],"inherit":false,"taxQuery":{"product_cat":[]},"isProductCollectionBlock":true,"featured":false,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[],"filterable":true,"relatedBy":{"categories":true,"tags":true}},"tagName":"div","displayLayout":{"type":"list","columns":2,"shrinkColumns":true},"dimensions":{"widthType":"fill"},"queryContextIncludes":["collection"],"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the page being viewed."}} -->
        <div class="wp-block-woocommerce-product-collection"><!-- wp:woocommerce/product-template -->
        <!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->

        <!-- wp:columns {"className":"review-column","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
        <div class="wp-block-columns review-column" style="margin-top:0;margin-bottom:0"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:post-terms {"term":"product_cat","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small"} /-->

        <!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"textAlign":"right","style":{"elements":{"link":{"color":{"text":"#ffc000"}}},"color":{"text":"#ffc000"}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->

        <!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|20","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium","fontFamily":"poppins","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

        <!-- wp:columns {"className":"product-info-column"} -->
        <div class="wp-block-columns product-info-column"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","textColor":"accent","fontSize":"normal","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}}} /-->

        <!-- wp:woocommerce/product-button {"textAlign":"right","isDescendentOfQueryLoop":true,"className":"is-style-outline","backgroundColor":"fourthaccent","textColor":"accent","fontSize":"small","style":{"border":{"radius":"5px"},"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"},"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->
        <!-- /wp:woocommerce/product-template --></div>
        <!-- /wp:woocommerce/product-collection --></div></div></div>
        <!-- /wp:stackable/column -->

        <!-- wp:stackable/column {"uniqueId":"27e2ed0","columnAdjacentCount":3,"columnSpacing":{"top":0,"right":0,"bottom":0,"left":0}} -->
        <div class="wp-block-stackable-column stk-block-column stk-column stk-block stk-27e2ed0" data-v="4" data-block-id="27e2ed0"><style>.stk-27e2ed0-container{margin-top:0px !important;margin-right:0px !important;margin-bottom:0px !important;margin-left:0px !important;}</style><div class="stk-column-wrapper stk-block-column__content stk-container stk-27e2ed0-container stk--no-background stk--no-padding"><div class="stk-block-content stk-inner-blocks stk-27e2ed0-inner-blocks"><!-- wp:woocommerce/product-collection {"queryId":9,"query":{"perPage":1,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","search":"","exclude":[],"inherit":false,"taxQuery":{"product_cat":[]},"isProductCollectionBlock":true,"featured":false,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[],"filterable":true,"relatedBy":{"categories":true,"tags":true}},"tagName":"div","displayLayout":{"type":"list","columns":2,"shrinkColumns":true},"dimensions":{"widthType":"fill"},"queryContextIncludes":["collection"],"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the page being viewed."}} -->
        <div class="wp-block-woocommerce-product-collection"><!-- wp:woocommerce/product-template -->
        <!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->

        <!-- wp:columns {"className":"review-column","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
        <div class="wp-block-columns review-column" style="margin-top:0;margin-bottom:0"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:post-terms {"term":"product_cat","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small"} /-->

        <!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"textAlign":"right","style":{"elements":{"link":{"color":{"text":"#ffc000"}}},"color":{"text":"#ffc000"}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->

        <!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|20","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium","fontFamily":"poppins","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

        <!-- wp:columns {"className":"product-info-column"} -->
        <div class="wp-block-columns product-info-column"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","textColor":"accent","fontSize":"normal","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}}} /-->

        <!-- wp:woocommerce/product-button {"textAlign":"right","isDescendentOfQueryLoop":true,"className":"is-style-outline","backgroundColor":"fourthaccent","textColor":"accent","fontSize":"small","style":{"border":{"radius":"5px"},"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"},"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}}}} /--></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns -->
        <!-- /wp:woocommerce/product-template --></div>
        <!-- /wp:woocommerce/product-collection --></div></div></div>
        <!-- /wp:stackable/column --></div><div class="stk-block-carousel__buttons"><button class="stk-block-carousel__button stk-block-carousel__button__prev" aria-label="Previous slide"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left" class="svg-inline--fa fa-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32" height="32"><path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path></svg></button><button class="stk-block-carousel__button stk-block-carousel__button__next" aria-label="Next slide"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32" height="32"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg></button></div></div></div></div>
        <!-- /wp:stackable/carousel --></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns --></div>
        <!-- /wp:group --></div>
        <!-- /wp:group -->

        <!-- wp:spacer {"height":"60px"} -->
        <div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->',
    );

} else {
 
    return array(
        'title'      =>__( 'Product Section', 'ecommerce-gadget-store' ),
        'categories' => array( 'ecommerce-gadget-store' ),
        'content'    => '<!-- wp:spacer {"height":"40px"} -->
            <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
            <!-- /wp:spacer -->

            <!-- wp:group {"className":"services-box static-product-section","layout":{"type":"constrained","contentSize":"80%"}} -->
            <div class="wp-block-group services-box static-product-section"><!-- wp:group {"className":"service-group","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"100%"}} -->
            <div class="wp-block-group service-group"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}}} -->
            <div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:column {"width":"27%","className":"info-section wow tada","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"blockGap":"0"},"border":{"radius":"15px"}},"backgroundColor":"forthaccent"} -->
            <div class="wp-block-column info-section wow tada has-forthaccent-background-color has-background" style="border-radius:15px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:27%"><!-- wp:cover {"url":"'.esc_url(get_template_directory_uri()) .'/assets/images/category-img.png","id":7,"dimRatio":0,"customOverlayColor":"#433a3a","isUserOverlayColor":false,"minHeight":100,"minHeightUnit":"%","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-cover" style="border-radius:10px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:100%"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#433a3a"></span><img class="wp-block-cover__image-background wp-image-7" alt="" src="'.esc_url(get_template_directory_uri()) .'/assets/images/category-img.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
            <p class="has-text-align-center has-large-font-size"></p>
            <!-- /wp:paragraph --></div></div>
            <!-- /wp:cover --></div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"general-product-section wow bounceIn","style":{"spacing":{"blockGap":"0"}}} -->
            <div class="wp-block-column general-product-section wow bounceIn"><!-- wp:columns {"className":"product-heading","style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"var:preset|spacing|40"}}}} -->
            <div class="wp-block-columns product-heading" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40);padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":""} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"textAlign":"left","style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"elements":{"link":{"color":{"text":"var:preset|color|tertiary"}}},"spacing":{"padding":{"bottom":"var:preset|spacing|40"}}},"textColor":"tertiary","fontSize":"content-heading","fontFamily":"poppins"} -->
            <h2 class="wp-block-heading has-text-align-left has-tertiary-color has-text-color has-link-color has-poppins-font-family has-content-heading-font-size" style="padding-bottom:var(--wp--preset--spacing--40);font-style:normal;font-weight:700">'. esc_html__('Today’s Top Picks','ecommerce-gadget-store').'</h2>
            <!-- /wp:heading --></div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"93px"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:93px"><!-- wp:paragraph {"align":"right","style":{"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"thirdaccent","fontSize":"small","fontFamily":"poppins"} -->
            <p class="has-text-align-right has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:600"><a href="#">'. esc_html__('View all','ecommerce-gadget-store').'</a></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->

            <!-- wp:columns {"className":"static-product-section"} -->
            <div class="wp-block-columns static-product-section"><!-- wp:column {"className":"product-column01","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-column product-column01" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:image {"id":42,"width":"200px","height":"200px","scale":"contain","sizeSlug":"full","linkDestination":"media","align":"center"} -->
            <figure class="wp-block-image aligncenter size-full is-resized"><a href="'.esc_url(get_template_directory_uri()) .'/assets/images/product01.png"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/product01.png" alt="" class="wp-image-42" style="object-fit:contain;width:200px;height:200px"/></a></figure>
            <!-- /wp:image -->

            <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","bottom":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"className":"review-col","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"0"}}}} -->
            <div class="wp-block-columns review-col"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small","fontFamily":"poppins"} -->
            <p class="has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-extra-small-font-size" style="font-style:normal;font-weight:400">'. esc_html__('Electronics','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"align":"right"} -->
            <p class="has-text-align-right"><img class="wp-image-48" style="width: 90px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/review.png" alt=""></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->

            <!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"poppins"} -->
            <h2 class="wp-block-heading has-primary-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:600">'. esc_html__('Watch with different color (42mm)','ecommerce-gadget-store').'</h2>
            <!-- /wp:heading -->

            <!-- wp:columns {"verticalAlignment":"center","className":"product-inner-col01","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-columns are-vertically-aligned-center product-inner-col01"><!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"accent","fontSize":"normal","fontFamily":"poppins"} -->
            <p class="has-accent-color has-text-color has-link-color has-poppins-font-family has-normal-font-size" style="font-style:normal;font-weight:600">'. esc_html__('$450','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"95px"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:95px"><!-- wp:buttons {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"0"}}},"layout":{"type":"flex","justifyContent":"right"}} -->
            <div class="wp-block-buttons" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"6px"},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"extra-small","fontFamily":"quicksand"} -->
            <div class="wp-block-button has-custom-font-size has-quicksand-font-family has-extra-small-font-size" style="font-style:normal;font-weight:600"><a class="wp-block-button__link wp-element-button" style="border-radius:6px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">'. esc_html__('Add To Bag','ecommerce-gadget-store').'</a></div>
            <!-- /wp:button --></div>
            <!-- /wp:buttons --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:group --></div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"product-column01","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-column product-column01" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:image {"id":43,"width":"200px","height":"200px","scale":"contain","sizeSlug":"full","linkDestination":"media","align":"center"} -->
            <figure class="wp-block-image aligncenter size-full is-resized"><a href="'.esc_url(get_template_directory_uri()) .'/assets/images/product02.png"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/product02.png" alt="" class="wp-image-43" style="object-fit:contain;width:200px;height:200px"/></a></figure>
            <!-- /wp:image -->

            <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","bottom":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"className":"review-col","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"0"}}}} -->
            <div class="wp-block-columns review-col"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small","fontFamily":"poppins"} -->
            <p class="has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-extra-small-font-size" style="font-style:normal;font-weight:400">'. esc_html__('Electronics','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"align":"right"} -->
            <p class="has-text-align-right"><img class="wp-image-48" style="width: 90px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/review.png" alt=""></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->

            <!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"poppins"} -->
            <h2 class="wp-block-heading has-primary-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:600">'. esc_html__('Headphones with dolby atmos sound','ecommerce-gadget-store').'</h2>
            <!-- /wp:heading -->

            <!-- wp:columns {"verticalAlignment":"center","className":"product-inner-col01","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-columns are-vertically-aligned-center product-inner-col01"><!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"accent","fontSize":"normal","fontFamily":"poppins"} -->
            <p class="has-accent-color has-text-color has-link-color has-poppins-font-family has-normal-font-size" style="font-style:normal;font-weight:600">'. esc_html__('$450','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"95px"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:95px"><!-- wp:buttons {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"0"}}},"layout":{"type":"flex","justifyContent":"right"}} -->
            <div class="wp-block-buttons" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"6px"},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"extra-small","fontFamily":"quicksand"} -->
            <div class="wp-block-button has-custom-font-size has-quicksand-font-family has-extra-small-font-size" style="font-style:normal;font-weight:600"><a class="wp-block-button__link wp-element-button" style="border-radius:6px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">'. esc_html__('Add To Bag','ecommerce-gadget-store').'</a></div>
            <!-- /wp:button --></div>
            <!-- /wp:buttons --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:group --></div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"product-column01","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-column product-column01" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:image {"id":44,"width":"200px","height":"200px","scale":"contain","sizeSlug":"full","linkDestination":"media","align":"center"} -->
            <figure class="wp-block-image aligncenter size-full is-resized"><a href="'.esc_url(get_template_directory_uri()) .'/assets/images/product03.png"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/product03.png" alt="" class="wp-image-44" style="object-fit:contain;width:200px;height:200px"/></a></figure>
            <!-- /wp:image -->

            <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","bottom":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"className":"review-col","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"0"}}}} -->
            <div class="wp-block-columns review-col"><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|thirdaccent"}}}},"textColor":"thirdaccent","fontSize":"extra-small","fontFamily":"poppins"} -->
            <p class="has-thirdaccent-color has-text-color has-link-color has-poppins-font-family has-extra-small-font-size" style="font-style:normal;font-weight:400">'. esc_html__('Electronics','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:paragraph {"align":"right"} -->
            <p class="has-text-align-right"><img class="wp-image-48" style="width: 90px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/review.png" alt=""></p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns -->

            <!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"small","fontFamily":"poppins"} -->
            <h2 class="wp-block-heading has-primary-color has-text-color has-link-color has-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:600">'. esc_html__('Laptop with 4k uhd display (120hrtz)','ecommerce-gadget-store').'</h2>
            <!-- /wp:heading -->

            <!-- wp:columns {"verticalAlignment":"center","className":"product-inner-col01","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <div class="wp-block-columns are-vertically-aligned-center product-inner-col01"><!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"accent","fontSize":"normal","fontFamily":"poppins"} -->
            <p class="has-accent-color has-text-color has-link-color has-poppins-font-family has-normal-font-size" style="font-style:normal;font-weight:600">'. esc_html__('$450','ecommerce-gadget-store').'</p>
            <!-- /wp:paragraph --></div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"95px"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:95px"><!-- wp:buttons {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"0"}}},"layout":{"type":"flex","justifyContent":"right"}} -->
            <div class="wp-block-buttons" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"6px"},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"extra-small","fontFamily":"quicksand"} -->
            <div class="wp-block-button has-custom-font-size has-quicksand-font-family has-extra-small-font-size" style="font-style:normal;font-weight:600"><a class="wp-block-button__link wp-element-button" style="border-radius:6px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">'. esc_html__('Add To Bag','ecommerce-gadget-store').'</a></div>
            <!-- /wp:button --></div>
            <!-- /wp:buttons --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:group --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:column --></div>
            <!-- /wp:columns --></div>
            <!-- /wp:group --></div>
            <!-- /wp:group -->

            <!-- wp:spacer {"height":"50px"} -->
            <div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
            <!-- /wp:spacer -->',
    );
}