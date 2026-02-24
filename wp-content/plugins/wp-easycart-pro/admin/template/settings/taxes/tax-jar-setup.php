<?php
/**************************
Tax Jar
***************************/
?>
<div class="ec_admin_list_line_item" style="float:left;">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_tax_jar_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-location"></div>
		<span><?php esc_attr_e( 'TaxJar', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'tax-jar-setup' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'taxes', 'tax-jar-setup' );?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group_text' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_tax_jar_enable', 'ec_admin_save_tax_jar_settings_options', get_option( 'ec_option_tax_jar_enable' ), __( 'Enable TaxJar', 'wp-easycart-pro' ), __( 'This will TaxJar for your site once you enter your TaxJar API token.', 'wp-easycart-pro' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_tax_jar_sandbox', 'ec_admin_save_tax_jar_settings_options', get_option( 'ec_option_tax_jar_sandbox' ), __( 'Enable Sandbox', 'wp-easycart-pro' ), __( 'This will set your TaxJar integration to sandbox mode NOT LIVE MODE.', 'wp-easycart-pro' ), 'ec_option_tax_jar_sandbox_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_live_token', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_live_token' ), __( 'Live Token', 'wp-easycart-pro' ), __( 'Get this from your TaxJar account.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_live_token_row', get_option( 'ec_option_tax_jar_enable' ) && ! get_option( 'ec_option_tax_jar_sandbox' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_sandbox_token', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_sandbox_token' ), __( 'Sandbox Token', 'wp-easycart-pro' ), __( 'Get this from your TaxJar account.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_sandbox_token_row', get_option( 'ec_option_tax_jar_enable' ) && get_option( 'ec_option_tax_jar_sandbox' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_tax_jar_enable_address_verification', 'ec_admin_save_tax_jar_settings_options', get_option( 'ec_option_tax_jar_enable_address_verification' ), __( 'Enable Address Verification', 'wp-easycart-pro' ), __( 'This requires a TaxJar Professional plan and only applies to US based addresses at this time.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_enable_address_verification_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_address', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_address' ), __( 'Origin Address', 'wp-easycart-pro' ), __( 'Address you are shipping from.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_address_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_city', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_city' ), __( 'Origin City', 'wp-easycart-pro' ), __( 'City you are shipping from.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_city_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_state', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_state' ), __( 'Origin State', 'wp-easycart-pro' ), __( 'State you are shipping from.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_state_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tax_jar_zip', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_zip' ), __( 'Origin Zip', 'wp-easycart-pro' ), __( 'Zip you are shipping from.', 'wp-easycart-pro' ), '', 'ec_option_tax_jar_zip_row', get_option( 'ec_option_tax_jar_enable' ) ); ?>

			<?php
			$country_options = array();
			$allowed_countries = array( 'US', 'CA', 'AU', 'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB' );
			foreach( wp_easycart_admin( )->countries as $country ){
				if ( in_array( $country->iso2_cnt, $allowed_countries ) ) {
					$country_options[] = (object) array(
						'value'	=> $country->iso2_cnt,
						'label'	=> $country->name_cnt
					);
				}
			}
			?>
			<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_tax_jar_country', 'ec_admin_save_tax_jar_text_setting', get_option( 'ec_option_tax_jar_country' ), __( 'Origin Country Code', 'wp-easycart-pro' ), __( 'Country you are shipping from.', 'wp-easycart-pro' ), $country_options, 'ec_option_tax_jar_country_row', get_option( 'ec_option_tax_jar_enable' ), false ); ?>

		<?php }else{ ?>

			<?php esc_attr_e( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

	</div>

</div>