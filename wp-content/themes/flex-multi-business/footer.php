<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package flex-multi-business
 */

?>
</div>

    <footer class="footer footer-one">
    <?php if (get_theme_mod('flex_multi_business_footer_widget', true) == true){?>
    <div class="foot-top">
      <div class="container">
        <div class="row">
          <?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
            <div class="footer-top wow infinite zoomInUp" style="animation-duration:2s; animation-iteration-count:1;">
              <div class="row clearfix">
                <?php dynamic_sidebar( 'footer-widgets' ); ?>
              </div>
            </div>
          <?php else : ?>
            <div class="footer-top default-footer-widgets wow infinite zoomInUp" style="animation-duration:2s; animation-iteration-count:1;">
              <div class="row clearfix">
                <div class="footer-widget col-md-3 col-sm-6 col-xs-12">
                  <h2 class="widget-title"><?php esc_html_e( 'About Us', 'flex-multi-business' ); ?></h2>
                  <p><?php esc_html_e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text', 'flex-multi-business' ); ?></p>
                </div>
                <div class="footer-widget col-md-3 col-sm-6 col-xs-12">
                  <h2 class="widget-title"><?php esc_html_e( 'Quick Links', 'flex-multi-business' ); ?></h2>
                  <ul class="footer-menu">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'flex-multi-business' ); ?></a></li>
                    <li><a href="#"><?php esc_html_e( 'About', 'flex-multi-business' ); ?></a></li>
                    <li><a href="#"><?php esc_html_e( 'Services', 'flex-multi-business' ); ?></a></li>
                    <li><a href="#"><?php esc_html_e( 'Contact', 'flex-multi-business' ); ?></a></li>
                  </ul>
                </div>
                <div class="footer-widget col-md-3 col-sm-6 col-xs-12">
                  <h2 class="widget-title"><?php esc_html_e( 'Recent Posts', 'flex-multi-business' ); ?></h2>
                  <ul>
                    <?php
                    $flex_multi_business_recent_posts = wp_get_recent_posts( array( 'numberposts' => 3 ) );
                    foreach ( $flex_multi_business_recent_posts as $flex_multi_business_post ) :
                      echo '<li><a href="' . esc_url( get_permalink( $flex_multi_business_post['ID'] ) ) . '">' . esc_html( $flex_multi_business_post['post_title'] ) . '</a></li>';
                    endforeach;
                    wp_reset_query();
                    ?>
                  </ul>
                </div>
                <div class="footer-widget col-md-3 col-sm-6 col-xs-12">
                  <h2 class="widget-title"><?php esc_html_e( 'Contact Info', 'flex-multi-business' ); ?></h2>
                  <ul class="contact-info">
                    <li><i class="fa fa-map-marker"></i> <?php esc_html_e( '123 Business Street, City, Country', 'flex-multi-business' ); ?></li>
                    <li><i class="fa fa-phone"></i> <?php esc_html_e( '+1 234 567 890', 'flex-multi-business' ); ?></li>
                    <li><i class="fa fa-envelope"></i> <?php esc_html_e( 'info@example.com', 'flex-multi-business' ); ?></li>
                  </ul>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if (get_theme_mod('flex_multi_business_footer_copyright', true) == true){?>
      <div class="footer-copyright-column">
          <div class="container">
            <div class="row text-center">
              <div class="col-md-12"> 
                <p class="footer-copyright mb-0">&copy;
                  <?php
                  echo esc_html(date_i18n(
                    /* translators: Copyright date format, see https://www.php.net/manual/datetime.format.php */
                    _x( 'Y', 'copyright date format', 'flex-multi-business' )
                  ));
                  ?>
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                  <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'flex-multi-business' ) ); ?>">
                    <?php esc_html_e( 'Powered by WordPress', 'flex-multi-business' ); ?>
                  </a>
                </p>
              </div>
            </div>
          </div>
      </div>
    <?php } ?>
    </footer>

    <!-- ====== Go to top ====== -->
    
    <?php if (get_theme_mod('flex_multi_business_footer_scroll_top', true) == true){?>
      <a id="c-scroll" title="<?php esc_attr__('Go to top','flex-multi-business' ); ?>" href="javascript:void(0)">
        <i class="fa fa-arrow-up" aria-hidden="true"></i>
      </a>
    <?php } ?>
    
</div>

<?php wp_footer(); ?>

</body>
</html>
