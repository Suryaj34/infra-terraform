<div id="wpeasycart_shopify_input_fields">
	<?php wp_easycart_admin( )->load_toggle_group_text( 'wpeasycart_shopify_domain', 'wpeasycart_shopify_domain', '', __( 'Shopify Domain', 'wp-easycart-pro' ), __( 'This is a domain for your Shopify account that will give us access to import your data.', 'wp-easycart-pro' ), '', 'ec_admin_verify_shopify_token_row', true, false ); ?>

	<?php wp_easycart_admin( )->load_toggle_group_text( 'wpeasycart_shopify_api_key', 'ec_admin_verify_shopify_key', '', __( 'Shopify API Key', 'wp-easycart-pro' ), __( 'This is a token from your Shopify account that will give us access to import your data.', 'wp-easycart-pro' ), '', 'ec_admin_verify_shopify_token_row', true, false ); ?>

	<?php wp_easycart_admin( )->load_toggle_group_text( 'wpeasycart_shopify_api_password', 'ec_admin_verify_shopify_password', '', __( 'Shopify API Password', 'wp-easycart-pro' ), __( 'This is a token from your Shopify account that will give us access to import your data.', 'wp-easycart-pro' ), '', 'ec_admin_verify_shopify_token_row', true, false ); ?>
</div>

<div id="wpeasycart_shopify_import_progress_bar" style="display:none; margin:15px 0;">
	<div class="ec_admin_progress_bar"><div style="width:10%;"></div></div>
	<div class="ec_admin_process_status"><span><?php _e( 'Importing Products', 'wp-easycart-pro' ); ?></span></div>
</div>

<div class="ec_admin_settings_input" style="padding:0px;">
	<input type="submit" id="wpeasycart_shopify_start_button" value="<?php esc_attr_e( 'IMPORT Shopify DATA NOW', 'wp-easycart-pro' ); ?>" class="ec_admin_settings_simple_button" onclick="wpeasycart_start_shopify_import( ); return false;" style="float:left; width:auto; font-size:13px; text-transform:uppercase;" />
	<button type="button" class="ec_admin_settings_simple_button" style="font-weight:normal; padding:20px; border-radius:10px; font-size:18px; display:none; color:#AAA; border:none;" id="wpeasycart_shopify_processing_button"><?php _e( 'PROCESSING', 'wp-easycart-pro' ); ?></button>
</div>