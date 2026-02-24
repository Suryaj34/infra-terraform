<?php
/**************************
Tax Cloud
***************************/
global $wpdb;
$us_states = $wpdb->get_results( "SELECT name_sta AS label, code_sta AS value FROM ec_state WHERE idcnt_sta = 223 ORDER BY name_sta ASC" );
?>
<div class="ec_admin_list_line_item" style="float:left;">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_tax_cloud_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-cloud"></div>
		<span><?php esc_attr_e( 'Tax Cloud for USA', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'tax-cloud-setup' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'taxes', 'tax-cloud-setup' );?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group_text' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_cloud_api_id', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_api_id' ), __( 'API ID', 'wp-easycart-pro' ), __( 'Get this from your Tax Cloud account.', 'wp-easycart-pro' ), '', '', true ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_cloud_api_key', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_api_key' ), __( 'API Key', 'wp-easycart-pro' ), __( 'Get this from your Tax Cloud account.', 'wp-easycart-pro' ), '', '', true ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_cloud_address', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_address' ), __( 'Origin Address', 'wp-easycart-pro' ), __( 'Address you are shipping from.', 'wp-easycart-pro' ), '', '', true ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_cloud_city', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_city' ), __( 'Origin City', 'wp-easycart-pro' ), __( 'City you are shipping from.', 'wp-easycart-pro' ), '', '', true ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_tax_cloud_state', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_state' ), __( 'Origin State', 'wp-easycart-pro' ), __( 'State you are shipping from.', 'wp-easycart-pro' ), $us_states, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_cloud_zip', 'ec_admin_save_tax_cloud_text_setting', get_option( 'ec_option_tax_cloud_zip' ), __( 'Origin Zip', 'wp-easycart-pro' ), __( 'Zip you are shipping from.', 'wp-easycart-pro' ), '', '', true ); ?>

		<?php }else{ ?>

			<?php esc_attr_e( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

	</div>

</div>