<?php
/**
 * Related posts based on categories and tags.
 * 
 */

$flex_multi_business_post_args = array(
    'posts_per_page'    => 3,
    'orderby'           => 'rand',
    'post__not_in'      => array( get_the_ID() ),
);

$flex_multi_business_tax_terms = wp_get_post_terms( get_the_ID(), 'category' );
$flex_multi_business_terms_ids = array();
foreach( $flex_multi_business_tax_terms as $tax_term ) {
	$flex_multi_business_terms_ids[] = $tax_term->term_id;
}

$flex_multi_business_post_args['category__in'] = $flex_multi_business_terms_ids;

$flex_multi_business_related_posts = new WP_Query( $flex_multi_business_post_args );

if ( $flex_multi_business_related_posts->have_posts() ) : ?>
<div class="related-post mt-4">
    <h3><?php echo esc_html__('Related Post' ,'flex-multi-business' );?></h3>
    <div class="row">
        <?php while ( $flex_multi_business_related_posts->have_posts() ) : $flex_multi_business_related_posts->the_post(); ?>
            <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                <article class="blog-item blog-2 wow infinite zoomInDown" style="animation-duration:3s; animation-delay:1s; animation-iteration-count:1;" id="post-<?php the_ID(); ?>">
                    <div class="post-img">
                    <div class="post-thumbnail">   
                        <?php if ( has_post_thumbnail() ) { ?>
                        <?php the_post_thumbnail(); ?>
                        <?php } else { ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/post-dummy.png' ); ?>" alt="<?php esc_attr_e( 'Post Image', 'flex-multi-business' ); ?>">
                        <?php }?>
                    </div>
                    </div>
                    <ul class="post-meta px-2">
                        <li>
                            <i class="fa fa-user"></i>
                            <?php flex_multi_business_posted_by(); ?>
                        </li>
                        <li>
                            <i class="fa fa-comments"></i>
                            <?php echo esc_html(get_comments_number());  ?>
                        </li>
                    </ul>
                    <div class="post-content p-4 text-center">
                        <h5>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>     
                        <?php echo wp_trim_words( get_the_content(), '15'); ?>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif;

wp_reset_postdata();