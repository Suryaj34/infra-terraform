<?php
/**
 * The template for displaying archive pages
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

	<div class="sp-100 bg-w">
        <div class="container">
            <div class="row <?php echo esc_attr($flex_multi_business_sidebar_layout); ?>">
            	<?php 
            	if (($flex_multi_business_sidebar_layout == 'has-left-sidebar') || ($flex_multi_business_sidebar_layout == 'has-right-sidebar') ) {
					echo '<div class="col-lg-8">';
				}
				else{
					echo '<div class="col-lg-12">';
				}?>
                
					<header class="page-header">
						<?php
						if ( have_posts() ) :
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
						else :
							printf( '<h1 class="page-title">%1$s</h1>', esc_html__( 'Nothing Found', 'flex-multi-business' ) );
						endif;
						?>
					</header><!-- .page-header -->
					<div class="row">
						<?php if ( have_posts() ) : ?>

							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
							<?php endwhile; ?>

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
	</div>

<?php
get_footer();