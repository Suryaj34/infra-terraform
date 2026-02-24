<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package flex-multi-business
 */

get_header();
?>

<div class="box-image position-relative">
   <div class="single-page-img"></div>
   <div class="page-header">
      <?php echo '<h1 class="mb-2">' . esc_html__('Results For: ', 'flex-multi-business') . get_search_query() . '</h1>'; ?>
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
	<div class="mt-5 bg-w">
        <div class="container">
            <div class="row <?php echo esc_attr($flex_multi_business_sidebar_layout); ?>">
                <div class="col-lg-8">

					<header class="page-header">
						<h1 class="page-title">
							<?php
							printf( esc_html__( 'Search Results for: %s', 'flex-multi-business' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header> 
					<div class="row">
						<?php if ( have_posts() ) : ?>

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
						<?php endwhile; ?>

						<div class="row">
	                        <div class="col-12 text-center px-4">
	                            <div class="pagination mt-5 mb-5">
	                                <?php the_posts_pagination( array(
										'prev_text' => esc_html__( '<<', 'flex-multi-business' ),
										'next_text' => esc_html__( '>>', 'flex-multi-business' ),
									) ); ?>
	                            </div>
	                        </div>
	                    </div>
						
						<?php else : ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php endif;?>
					</div>
				</div>

					<?php
		        	if (($flex_multi_business_sidebar_layout == 'has-left-sidebar') || ($flex_multi_business_sidebar_layout == 'has-right-sidebar')) { ?>
						<div class="col-lg-4">
							<aside class="sidebar mt-5 mt-lg-0">
	                        	<?php get_sidebar(); ?>
	                    	</aside>
						</div>
					<?php } ?>
			</div> 
		</div> 
	</section>

<?php
get_footer();