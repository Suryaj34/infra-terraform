<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_convertkit_settings_loader" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'ConvertKit Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'convertkit' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'third-party', 'convertkit' );?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_convertkit', 'ec_admin_save_convertkit_settings', get_option( 'ec_option_enable_convertkit' ), __( 'Enable ConvertKit', 'wp-easycart-pro' ), __( 'This will allow you to connect your store to a ConvertKit.', 'wp-easycart-pro' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_convertkit_api_key', 'ec_admin_save_convertkit_settings', get_option( 'ec_option_convertkit_api_key' ), __( 'ConvertKit API Key', 'wp-easycart-pro' ), __( 'This is the API Key required to send data to your ConvertKit account from WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_option_convertkit_api_key_row', get_option( 'ec_option_enable_convertkit' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_convertkit_api_secret', 'ec_admin_save_convertkit_settings', get_option( 'ec_option_convertkit_api_secret' ), __( 'ConvertKit API Secret', 'wp-easycart-pro' ), __( 'This is the API Secret required to send data to your ConvertKit account from WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_option_convertkit_api_secret_row', get_option( 'ec_option_enable_convertkit' ), false ); ?>
		
		<?php $data = (object) array();
		$forms_data = array(
			(object) array(
				'value' => '0',
				'label' => __( 'Select a Form', 'wp-easycart-pro' )
			)
		);
		if ( get_option( 'ec_option_convertkit_api_key' ) ) {
			$forms_response = wp_easycart_admin_pro()->call_convertkit( $data, 'https://api.convertkit.com/v3/forms', 'GET' );
			if( $forms_response && isset( $forms_response->forms ) ) {
				foreach( $forms_response->forms as $form ) {
					$forms_data[] = (object) array(
						'value' => $form->id,
						'label' => $form->name
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
		} else {
			$forms_data = array(
				(object) array(
					'value' => '0',
					'label' => __( 'Setup First and Refresh / Setup Error', 'wp-easycart-pro' )
				)
			);
		}
		wp_easycart_admin( )->load_toggle_group_select( 'ec_option_convertkit_form', 'ec_admin_save_convertkit_settings', get_option( 'ec_option_convertkit_form' ), __( 'ConvertKit Form', 'wp-easycart-pro' ), __( 'ConvertKit requires that a subscriber is added to a form. They cannot just be added as a subscriber.', 'wp-easycart-pro' ), $forms_data, 'ec_option_convertkit_form_row', get_option( 'ec_option_enable_convertkit' ), false ); ?>
	</div>
</div>