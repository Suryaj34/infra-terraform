<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_activecampaign_settings_loader" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Active Campaign Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'activecampaign' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'third-party', 'activecampaign' );?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_activecampaign', 'ec_admin_save_activecampaign_settings', get_option( 'ec_option_enable_activecampaign' ), __( 'Enable Active Campaign', 'wp-easycart-pro' ), __( 'This will allow you to connect your store to a Active Campaign.', 'wp-easycart-pro' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_activecampaign_api_url', 'ec_admin_save_activecampaign_settings', get_option( 'ec_option_activecampaign_api_url' ), __( 'Active Campaign API URL', 'wp-easycart-pro' ), __( 'This is the API URL required to send data to your Active Campaign account from WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_option_activecampaign_api_url_row', get_option( 'ec_option_enable_activecampaign' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_activecampaign_api_key', 'ec_admin_save_activecampaign_settings', get_option( 'ec_option_activecampaign_api_key' ), __( 'Active Campaign API Key', 'wp-easycart-pro' ), __( 'This is the API Key required to send data to your Active Campaign account from WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_option_activecampaign_api_key_row', get_option( 'ec_option_enable_activecampaign' ), false ); ?>
		
		<?php $data = (object) array();
		$lists_data = array(
			(object) array(
				'value' => '0',
				'label' => __( 'Select a List', 'wp-easycart-pro' )
			)
		);
		if ( get_option( 'ec_option_activecampaign_api_key' ) && '' != get_option( 'ec_option_activecampaign_api_key' ) ) {
			$lists_response = wp_easycart_admin_pro()->call_activecampaign( $data, 'lists', 'GET' );
			if ( $lists_response && isset( $lists_response->lists ) ) {
				foreach( $lists_response->lists as $list ) {
					if ( '' != $list->name ) {
						$lists_data[] = (object) array(
							'value' => $list->id,
							'label' => $list->name
						);
					}
				}
			} else {
				$forms_data = array(
					(object) array(
						'value' => '0',
						'label' => __( 'Setup First and Refresh / Setup Error', 'wp-easycart-pro' )
					)
				);
			}
		} else {
			$forms_data = array(
				(object) array(
					'value' => '0',
					'label' => __( 'Setup First and Refresh / Setup Error', 'wp-easycart-pro' )
				)
			);
		}
		wp_easycart_admin( )->load_toggle_group_select( 'ec_option_activecampaign_list', 'ec_admin_save_activecampaign_settings', get_option( 'ec_option_activecampaign_list' ), __( 'Active Campaign List', 'wp-easycart-pro' ), __( 'Active Campaign works best when a WP EasyCart subscriber is added to a specific list. Create one in your account and select it below.', 'wp-easycart-pro' ), $lists_data, 'ec_option_activecampaign_list_row', get_option( 'ec_option_enable_activecampaign' ), false ); ?>
	</div>
</div>