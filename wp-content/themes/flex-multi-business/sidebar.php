<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package flex-multi-business
 */

?>
<aside id="secondary" class="widget-area wow infinite zoomInDown" style="animation-duration:3s; animation-delay:1s; animation-iteration-count:1;">
    <?php if ( ! dynamic_sidebar( 'main-sidebar' ) ): ?>
    <section role="complementary" aria-label="<?php echo esc_attr__( 'sidebar1', 'flex-multi-business' ); ?>" id="Search" class="widget">
      <h2 class="widget-title" ><?php esc_html_e( 'Search', 'flex-multi-business' ); ?></h2>
      <?php get_search_form(); ?>
    </section>
    <section role="complementary" aria-label="<?php echo esc_attr__( 'sidebar2', 'flex-multi-business' ); ?>" id="archives" class="widget">
      <h2 class="widget-title" ><?php esc_html_e( 'Archives', 'flex-multi-business' ); ?></h2>
      <ul>
          <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
      </ul>
    </section>
    <section role="complementary" aria-label="<?php echo esc_attr__( 'sidebar3', 'flex-multi-business' ); ?>" id="meta" class="widget">
      <h2 class="widget-title"><?php esc_html_e( 'Meta', 'flex-multi-business' ); ?></h2>
      <ul>
        <?php wp_register(); ?>
        <li><?php wp_loginout(); ?></li>
        <?php wp_meta(); ?>
      </ul>
    </section>
    <?php endif; ?>
</aside><!-- #secondary -->