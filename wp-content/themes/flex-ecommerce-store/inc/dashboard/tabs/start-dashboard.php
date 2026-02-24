<?php
/**
 * Start Elementor.
 *
 */

$flex_ecommerce_store_theme = wp_get_theme();

?>
<!-- Start Elementor -->
<div id="flex-ecommerce-store-importer" class="tabcontent open">
    <div class="tab-outer-box-container tab-outer-box">
        <div class="flex-main-container">
            <div class="flex-inner-box">
                <img src="<?php echo esc_url(get_theme_file_uri()); ?>/screenshot.png" />
            </div>
            <div class="flex-inner-content">
                <h3><?php esc_html_e('🎉 Thank you for activating the Flex Free WordPress Theme!', 'flex-ecommerce-store'); ?></h3>
                <p class="start-text"><?php esc_html_e('Get started quickly by importing the demo content or explore more powerful options below.', 'flex-ecommerce-store'); ?></p>
                <div class="info-link">
                    <a href="javascript:void(0);" id="install-activate-button" class="button admin-button info-button">
                        <?php esc_html_e('Import Demo', 'flex-ecommerce-store'); ?>
                    </a>
                    <a href="<?php echo esc_url( FLEX_ECOMMERCE_STORE_BUY_NOW ); ?>" class="button info-button" target="_blank">
                        <?php esc_html_e('Upgrade Pro', 'flex-ecommerce-store'); ?>
                    </a>
                    <a href="<?php echo esc_url( FLEX_ECOMMERCE_STORE_LIVE_DEMO ); ?>" class="button info-button" target="_blank">
                        <?php esc_html_e('Live Demo', 'flex-ecommerce-store'); ?>
                    </a>
                    <a href="<?php echo esc_url( FLEX_ECOMMERCE_STORE_DOCUMENTATION ); ?>" class="button info-button" target="_blank">
                        <?php esc_html_e('Documentation', 'flex-ecommerce-store'); ?>
                    </a>
                    <script type="text/javascript">
                    document.getElementById('install-activate-button').addEventListener('click', function () {
                        const flex_ecommerce_store_button = this;
                        const flex_ecommerce_store_redirectUrl = '<?php echo esc_url(admin_url("admin.php?page=fleximp-template-importer")); ?>';
                        // First, check if plugin is already active
                        jQuery.post(ajaxurl, { action: 'check_flex_import_activation' }, function (response) {
                            if (response.success && response.data.active) {
                                // Plugin already active — just redirect
                                window.location.href = flex_ecommerce_store_redirectUrl;
                            } else {
                                // Show Installing & Activating only if not already active
                                flex_ecommerce_store_button.textContent = 'Installing & Activating...';

                                jQuery.post(ajaxurl, {
                                    action: 'install_and_activate_flex_import_plugin_lite',
                                    nonce: '<?php echo wp_create_nonce("install_activate_nonce"); ?>'
                                }, function (response) {
                                    if (response.success) {
                                        window.location.href = flex_ecommerce_store_redirectUrl;
                                    } else {
                                        alert('Failed to activate the plugin.');
                                        flex_ecommerce_store_button.textContent = 'Try Again';
                                    }
                                });
                            }
                        });
                    });
                    </script>
                </div>
                <div class="about-text">
                    <?php
                        $description_raw = $flex_ecommerce_store_theme->display( 'Description' );
                        $main_description = explode( 'Official', $description_raw );
                        ?>
                    <?php echo wp_kses_post( $main_description[0] ); ?>
                </div>
            </div>
        </div>
    </div>
</div>
