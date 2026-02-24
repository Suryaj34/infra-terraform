<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package flex-multi-business
 */

?>
<div class="col-lg-6 col-md-6">
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
        
        <ul class="post-meta">
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
                    
            <?php the_excerpt(); ?>
            <div class="read-more">
            <a href="<?php the_permalink(); ?>"><?php echo esc_html(get_theme_mod('flex_multi_business_readmore_general_section', 'Read More')); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </article>
</div>