<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package flex-multi-business
 */

get_header();
?>
<div class="box-image position-relative">
  <div class="single-page-img"></div>
  <div class="page-header">
    <?php
      the_archive_title( '<h1 class="entry-title">', '</h1>' );
      the_archive_description( '<div class="taxonomy-description">', '</div>' );
    ?>
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
						<?php if ( $flex_multi_business_sidebar_layout == 'has-left-sidebar' || $flex_multi_business_sidebar_layout == 'has-right-sidebar' ) { ?>
								<div class="col-lg-8">
						<?php }else{ ?>
								<div class="col-lg-12">
						<?php } ?>
							<div class="row"> 	
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
								<?php endwhile; ?>	
							</div>	
							<div class="row">
		                        <div class="col-12 text-center px-4">
		                            <div class="pagination mt-5">
		                            	<?php the_posts_pagination( array(
											'prev_text' => esc_html__( '<<', 'flex-multi-business' ),
											'next_text' => esc_html__( '>>', 'flex-multi-business' ),
										) ); ?>
		                            </div>
		                        </div>
		                    </div>
							
						</div>	
						
						<?php
		                if (($flex_multi_business_sidebar_layout == 'has-left-sidebar') || ($flex_multi_business_sidebar_layout == 'has-right-sidebar')) { ?>
							<div class="col-lg-4">
								<?php get_sidebar(); ?>
							</div>
						<?php } ?>
					</div>
					</div>
				</div>
			</div>
		<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

<?php
get_footer();
