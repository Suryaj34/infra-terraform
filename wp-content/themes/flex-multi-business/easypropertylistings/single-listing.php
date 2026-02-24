<?php
/**
 * Template: Single Property Custom
 */
get_header(); ?>

<div class="box-image position-relative">
    <div class="single-page-img"></div>
    <div class="page-header">
        <h1><?php the_title();?></h1>
    </div>
</div>
    <div class="sp-100 bg-w">
        <div class="container">
            <div class="custom-single-property">
                <?php while (have_posts()) : the_post(); ?>
                    <h1 class="property-title"><?php the_title(); ?></h1>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="property-image my-4">
                            <?php echo get_the_post_thumbnail(get_the_ID(), 'large', array('class' => 'img-responsive')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="property-price my-1">
                        <?php esc_html_e( 'Price: ₹', 'flex-multi-business' ); ?><?php echo esc_html(get_post_meta(get_the_ID(), 'property_price', true)); ?>
                    </div>
                    <div class="property-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="property-meta">
                        <p><?php esc_html_e( 'Bedrooms: ', 'flex-multi-business' ); ?><?php echo get_post_meta(get_the_ID(), 'property_bedrooms', true); ?></p>
                        <p><?php esc_html_e( 'Bathrooms: ', 'flex-multi-business' ); ?><?php echo get_post_meta(get_the_ID(), 'property_bathrooms', true); ?></p>
                        <p><?php esc_html_e( 'Area: ', 'flex-multi-business' ); ?><?php echo get_post_meta(get_the_ID(), 'property_building_area', true); ?><?php esc_html_e( 'sqft', 'flex-multi-business' ); ?></p>
                        <p><?php esc_html_e( 'Location: ', 'flex-multi-business' ); ?><?php echo get_post_meta(get_the_ID(), 'property_address_street', true); ?></p>
                        <p><?php esc_html_e( 'Agent: ', 'flex-multi-business' ); ?><?php echo get_post_meta(get_the_ID(), 'property_agent', true); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>