<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package flex-multi-business
 */

get_header();
?>

<div class="box-image position-relative">
    <div class="single-page-img"></div>
    <div class="page-header">
        <h1><?php the_title();?></h1>
        <span><?php flex_multi_business_the_breadcrumb(); ?></span>
    </div>
</div>

<?php
    $flex_multi_business_sidebar_layout = get_theme_mod('flex_multi_business_sidebar_layout_section', 'right');
    if ($flex_multi_business_sidebar_layout == 'left') {
        $flex_multi_business_sidebar_layout = 'has-left-sidebar';
    } elseif ($flex_multi_business_sidebar_layout == 'right') {
        $flex_multi_business_sidebar_layout = 'has-right-sidebar';
    } elseif ($flex_multi_business_sidebar_layout == 'no') {
        $flex_multi_business_sidebar_layout = 'no-sidebar';
    }
?>
    <?php
    if ( have_posts() ) : ?>

	<!-- blog detail start-->
    <div class="sp-100 bg-w">
        <div class="container">
            <div class="row <?php echo esc_attr($flex_multi_business_sidebar_layout); ?>">
                
                <?php if( class_exists( 'WooCommerce' ) && is_product() ){ ?>
                        <div class="col-lg-12">
                <?php } elseif (($flex_multi_business_sidebar_layout == 'has-left-sidebar') || ($flex_multi_business_sidebar_layout == 'has-right-sidebar') ){ ?>
                    <div class="col-lg-8">
                <?php } else { ?>
                    <div class="col-lg-12">
                <?php } ?>
                    <?php while ( have_posts() ) : the_post();?>
						<?php get_template_part( 'template-parts/content', 'single' );
						if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                        get_template_part( 'template-parts/content-related' );
                        ?>
					<?php endwhile; ?>
                </div>
                <?php
                if (($flex_multi_business_sidebar_layout == 'has-left-sidebar') || ($flex_multi_business_sidebar_layout == 'has-right-sidebar') ) { ?>
                    <div class="col-lg-4">
                        <aside class="sidebar mt-5 mt-lg-0">
                            <?php get_sidebar(); ?>
                        </aside>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- blog detail ends-->

<?php 	
endif;
?>		

<?php
get_footer();
