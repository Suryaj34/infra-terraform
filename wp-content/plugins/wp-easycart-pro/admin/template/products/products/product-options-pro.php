<?php global $wpdb; ?>
<?php $product_id = ( isset( $_GET['product_id'] ) ) ? (int) $_GET['product_id'] : 0; ?>
<?php $product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $product_id ) ); ?>
<?php ?>

<div id="wpeasycart_product_options_pro">
	<div class="ec_admin_product_details_section">
		<?php do_action( 'wp_easycart_admin_product_details_options_pro_start', $product ); ?>
		<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-option-loader" ); ?>

		<h3 class="wp-easycart-pro-option-title1"><?php esc_attr_e( 'Basic Product Option Sets', 'wp-easycart-pro' ); ?> <a href="#" class="wpeasycart_product_options_pro_new_button" onclick="return wp_easycart_pro_new_basic_option();">+ <?php esc_attr_e( 'Create New Option Set', 'wp-easycart-pro' ); ?></a></h3>
		<h5 class="wp-easycart-pro-option-subtitle"><?php esc_attr_e( 'Add a custom set of options to an item to create variations. For example, a size option set can create variations of small, medium, and large. Product variations may have unique SKUs and stock inventory.', 'wp-easycart-pro' ); ?></h5>

		<div class="wp-easycart-pro-option-table">
			<div class="wp-easycart-pro-option-table-body wp-easycart-pro-option-table-body-border" id="wp-easycart-pro-basic-options">
				<?php echo wp_easycart_admin_products_pro()->get_basic_option_rows( $product ); ?>
			</div>
		</div>

		<?php $optionitem_quanity_has_rows = $wpdb->get_var( $wpdb->prepare( 'SELECT optionitemquantity_id FROM ec_optionitemquantity WHERE product_id = %d LIMIT 1', $product_id ) ); ?>
		<div id="wpeasycart_product_variants"<?php echo ( $optionitem_quanity_has_rows ) ? '' : ' style="display:none"'; ?>>
			<h3 class="wp-easycart-pro-option-title2" style="width:50%;"><?php _e( 'Product Variations', 'wp-easycart-pro' ); ?> <a href="admin.php?page=wp-easycart-products&subpage=products&product_id=<?php echo esc_attr( $product_id ); ?>&ec_admin_form_action=export-option-item-quantities" target="_blank" class="wp-easycart-pro-option-modal-add-button" style="float:none; display:inline-block; padding:6px 18px;"><?php esc_attr_e( 'Export Variation Set', 'wp-easycart-pro' ); ?></a></h3>
			<h5 class="wp-easycart-pro-option-subtitle" style="margin-top:10px; margin-bottom:0px;"><?php esc_attr_e( 'Product variations represent all combinations of your basic product option sets. For example, a size and color option might create blue small, blue medium, green large, red medium, and red large variation sets.', 'wp-easycart-pro' ); ?></h5>

			<form action="" method="POST" enctype="multipart/form-data" style="float:right; border:1px solid #CCC; padding:5px; max-width:100%; margin-top:30px;">
				<input type="hidden" name="ec_admin_form_action" value="import-option-item-quantities" />
				<input type="hidden" name="product_id" id="product_id" value="<?php echo esc_attr( $product_id ); ?>" />
				<input type="file" placeholder="<?php esc_attr_e( 'Choose Quantity File', 'wp-easycart-pro' ); ?>" name="import_file" />
				<input type="submit" value="<?php esc_attr_e( 'Import Variations', 'wp-easycart-pro' ); ?>" />
			</form>

			<div class="wp-easycart-pro-option-table">
				<div class="wp-easycart-pro-option-table-body" id="wp-easycart-pro-variants">
					<?php echo wp_easycart_admin_products_pro()->get_variant_rows( $product ); ?>
				</div>
			</div>
		</div>

		<div id="wpeasycart_product_variants_none"<?php echo ( $optionitem_quanity_has_rows ) ? ' style="display:none"' : ''; ?>>
			<h3 class="wp-easycart-pro-option-title2" style="width:50%;"><?php _e( 'Product Variations', 'wp-easycart-pro' ); ?></h3>
			<h5 class="wp-easycart-pro-option-subtitle" style="margin-top:10px; margin-bottom:0px;"><?php esc_attr_e( 'Product variations represent all combinations of your basic product option sets. For example, a size and color option might create blue small, blue medium, green large, red medium, and red large variation sets. To get started, add an option set to your product and variations will be automatically generated from those option sets.', 'wp-easycart-pro' ); ?></h5>
		</div>
	</div>
	<div class="ec_admin_product_details_section">
		<?php do_action( 'wp_easycart_admin_product_details_modifiers_pro_start', $product ); ?>
		<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-modifier-loader" ); ?>

		<h3 class="wp-easycart-pro-option-title3"><?php _e( 'Advanced Product Modifiers', 'wp-easycart-pro' ); ?> <a href="#" class="wpeasycart_product_options_pro_new_button" onclick="return wp_easycart_pro_new_advanced_option();">+ <?php esc_attr_e( 'Create New Modifier', 'wp-easycart-pro' ); ?></a></h3>
		<h5 class="wp-easycart-pro-option-subtitle"><?php esc_attr_e( 'Product modifiers are advanced options that can not adjust inventory, but they do let you modify a product with things like text boxes, check boxes, radio groups, file uploads, combo boxes, image swatches, and more.', 'wp-easycart-pro' ); ?></h5>

		<div class="wp-easycart-pro-option-table">
			<div class="wp-easycart-pro-option-table-body" id="wp-easycart-pro-modifiers">
				<?php echo wp_easycart_admin_products_pro()->get_modifier_rows( $product ); ?>
			</div>
		</div>
	</div>
</div>

<?php $option_sets = $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type = "basic-combo" OR option_type = "basic-swatch" ORDER BY option_name ASC' ); ?>
<div class="wp-easycart-pro-option-modal" id="wp-easycart-pro-new-option">
	<div class="wp-easycart-pro-option-modal-container">
		<div class="wp-easycart-pro-option-modal-box">
			<div class="wp-easycart-pro-option-modal-inner">
				<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-basic-option-modal-loader" ); ?>
				<div class="wp-easycart-pro-option-modal-header">
					<div class="wp-easycart-pro-option-modal-header-content">
						<div class="wp-easycart-pro-option-modal-close">
							<a href="#" onclick="return wp_easycart_pro_close_add_basic_option();" class="wp-easycart-pro-option-modal-close-button"><span class="dashicons dashicons-no"></span></a>
						</div>
						<h3><?php esc_attr_e( 'Add Basic Product Option Set', 'wp-easycart-pro' ); ?></h3>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-content">
					<div class="wp-easycart-pro-option-modal-content-container">
						<div class="wp-easycart-pro-option-modal-info">
							<?php esc_attr_e( 'To add a new option, either select an option set from the list below or click the add new button to create an entirely new option set.', 'wp-easycart-pro' ); ?>
						</div>
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Select Option Set', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select id="wp-easycart-pro-new-basic-option" class="select2">
												<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
												<?php foreach( $option_sets as $option_set ) {?>
												<option value="<?php echo esc_attr( $option_set->option_id ); ?>"><?php echo esc_attr( $option_set->option_name ); ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding-top:15px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="justify-content:center;">
											<input type="button" value="<?php esc_attr_e( 'Add Selected Option', 'wp-easycart-pro' ); ?>" class="wp-easycart-pro-option-modal-add-button" onclick="wp_easycart_pro_add_new_basic_option()" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-footer">
					<a href="#" onclick="return wp_easycart_pro_new_basic_option();"><?php esc_attr_e( 'Need a New Option Set? Click Here.', 'wp-easycart-pro' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $option_sets = $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type != "basic-combo" AND option_type != "basic-swatch" ORDER BY option_name ASC' ); ?>
<div class="wp-easycart-pro-option-modal" id="wp-easycart-pro-new-modifier">
	<div class="wp-easycart-pro-option-modal-container">
		<div class="wp-easycart-pro-option-modal-box">
			<div class="wp-easycart-pro-option-modal-inner">
				<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-advanced-option-modal-loader" ); ?>
				<div class="wp-easycart-pro-option-modal-header">
					<div class="wp-easycart-pro-option-modal-header-content">
						<div class="wp-easycart-pro-option-modal-close">
							<a href="#" onclick="return wp_easycart_pro_close_add_advanced_option();" class="wp-easycart-pro-option-modal-close-button"><span class="dashicons dashicons-no"></span></a>
						</div>
						<h3><?php esc_attr_e( 'Add Advanced Product Modifier', 'wp-easycart-pro' ); ?></h3>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-content">
					<div class="wp-easycart-pro-option-modal-content-container">
						<div class="wp-easycart-pro-option-modal-info">
							<?php esc_attr_e( 'To add a new modifier, either select a modiifer from the list below or click the add new button to create an entirely new modifier (advanced option set).', 'wp-easycart-pro' ); ?>
						</div>
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Select Modifier', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select id="wp-easycart-pro-new-advanced-option" class="select2">
												<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
												<?php foreach( $option_sets as $option_set ) {?>
												<option value="<?php echo esc_attr( $option_set->option_id ); ?>"><?php echo esc_attr( $option_set->option_name ); ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding-top:15px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="justify-content:center;">
											<input type="button" value="<?php esc_attr_e( 'Add Selected Modifier', 'wp-easycart-pro' ); ?>" class="wp-easycart-pro-option-modal-add-button" onclick="wp_easycart_pro_add_new_advanced_option()" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-footer">
					<a href="#" onclick="return wp_easycart_pro_new_advanced_option();"><?php esc_attr_e( 'Need a New Modifier? Click Here.', 'wp-easycart-pro' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $option_to_product_sets = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_option_to_product.option_to_product_id, ec_option.* FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option.option_name ASC', $product->product_id ) ); ?>
<div class="wp-easycart-pro-option-modal" id="wp-easycart-pro-conditional-logic">
	<div class="wp-easycart-pro-option-modal-container">
		<div class="wp-easycart-pro-option-modal-box">
			<div class="wp-easycart-pro-option-modal-inner">
				<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-conditional-logic-loader" ); ?>
				<div class="wp-easycart-pro-option-modal-header">
					<div class="wp-easycart-pro-option-modal-header-content">
						<div class="wp-easycart-pro-option-modal-close">
							<a href="#" onclick="return wp_easycart_pro_close_conditional_logic();" class="wp-easycart-pro-option-modal-close-button"><span class="dashicons dashicons-no"></span></a>
						</div>
						<h3 style="padding-bottom:5px;"><?php esc_attr_e( 'Set Conditional Logic', 'wp-easycart-pro' ); ?></h3>
						
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-content">
					<div class="wp-easycart-pro-option-modal-content-container">
						<div class="wp-easycart-pro-option-conditional-logic-product">
							<?php esc_attr_e( 'Editing Rules for', 'wp-easycart-pro' ); ?>: <span><?php esc_attr_e( 'ITEM HERE', 'wp-easycart-pro' ); ?></span>
						</div>
						<div class="wp-easycart-pro-option-modal-info">
							<?php esc_attr_e( 'Conditional logic allows you to hide or show modifiers based on other selections.', 'wp-easycart-pro' ); ?>
						</div>
						<input type="hidden" id="wp-easycart-pro-conditional-logic-option-id" value="" />
						<input type="hidden" id="wp-easycart-pro-conditional-logic-enabled" value="" />
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Select Type', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select id="wp-easycart-pro-conditional-logic-type" class="select2">
												<option value="show"><?php esc_attr_e( 'Show', 'wp-easycart-pro' ); ?></option>
												<option value="hide"><?php esc_attr_e( 'Hide', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Select Method', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select id="wp-easycart-pro-conditional-logic-method" class="select2">
												<option value="OR"><?php esc_attr_e( 'If ANY match', 'wp-easycart-pro' ); ?></option>
												<option value="AND"><?php esc_attr_e( 'If ALL match', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="wp-easycart-pro-conditional-logic-label"><?php esc_attr_e( 'Set Your Rules', 'wp-easycart-pro' ); ?>:</div>
						<div id="wp-easycart-pro-conditional-logic-rules-list">
							<div class="wp-easycart-pro-conditional-logic-container">
								<select class="select2 wp-easycart-pro-conditional-logic-option">
									<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
									<?php foreach( $option_to_product_sets as $option_set ) {?>
									<option value="<?php echo esc_attr( $option_set->option_to_product_id ); ?>"><?php echo esc_attr( $option_set->option_name ); ?></option>
									<?php }?>
								</select>
								<select class="select2 wp-easycart-pro-conditional-logic-is" style="min-width:75px !important;">
									<option value="="><?php esc_attr_e( 'is', 'wp-easycart-pro' ); ?></option>
									<option value="!="><?php esc_attr_e( 'is not', 'wp-easycart-pro' ); ?></option>
								</select>
								<?php $is_first = true; ?>
								<?php foreach( $option_to_product_sets as $option_set ) { ?>
									<?php if( 'combo' == $option_set->option_type || 'swatch' == $option_set->option_type || 'radio' == $option_set->option_type || 'checkbox' == $option_set->option_type || 'grid' == $option_set->option_type ) { ?>
									<select class="select2 wp-easycart-pro-conditional-logic-optionitem wp-easycart-pro-conditional-logic-optionitem-<?php echo esc_attr( $option_set->option_to_product_id ); ?>"<?php echo ( ! $is_first ) ? ' style="display:none;"' : ''; ?>>
										<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
										<?php $optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order', $option_set->option_id ) ); foreach( $optionitems as $optionitem ) { ?>
											<option value="<?php echo esc_attr( $optionitem->optionitem_id ); ?>"><?php echo esc_attr( $optionitem->optionitem_name ); ?></option>
										<?php }?>
									</select>
									<?php } else { ?>
									<input class="wp-easycart-pro-conditional-logic-optionitem wp-easycart-pro-conditional-logic-optionitem-<?php echo esc_attr( $option_set->option_to_product_id ); ?>"<?php echo ( ! $is_first ) ? ' style="display:none;"' : ''; ?> value="" />
									<?php } ?>
									<?php $is_first = false; ?>
								<?php } ?>
								<a href="#" class="wp-easycart-pro-conditional-logic-remove-row" style="display:none">-</a>
							</div>
						</div>
						<div style="margin:5px 0 0;">
							<a href="#" class="wp-easycart-pro-conditional-logic-add-button"><?php esc_attr_e( 'Add Rule', 'wp-easycart-pro' ); ?></a>
						</div>
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding-top:15px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="justify-content:center;">
											<input type="button" value="<?php esc_attr_e( 'Update Conditional Logic', 'wp-easycart-pro' ); ?>" class="wp-easycart-pro-option-modal-add-button" onclick="wp_easycart_pro_update_conditional_logic()" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="wp-easycart-pro-option-modal" id="wp-easycart-pro-variant-google-merchant">
	<div class="wp-easycart-pro-option-modal-container">
		<div class="wp-easycart-pro-option-modal-box">
			<div class="wp-easycart-pro-option-modal-inner">
				<input type="hidden" id="wp-easycart-pro-variant-google-merchant-optionitemquantity-id" value="" />
				<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-variant-google-merchant-loader" ); ?>
				<div class="wp-easycart-pro-option-modal-header">
					<div class="wp-easycart-pro-option-modal-header-content">
						<div class="wp-easycart-pro-option-modal-close">
							<a href="#" onclick="return wp_easycart_pro_close_variant_google_merchant();" class="wp-easycart-pro-option-modal-close-button"><span class="dashicons dashicons-no"></span></a>
						</div>
						<h3><?php esc_attr_e( 'Update Google Merchant Settings', 'wp-easycart-pro' ); ?></h3>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-content">
					<div class="wp-easycart-pro-option-modal-content-container" style="padding:0px 0px 25px 0px; margin-top:-10px;">
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Enabled', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select class="select2 wp-easycart-pro-conditional-logic-optionitem" id="wp-easycart-pro-variant-google-merchant-enabled">
												<option value="yes"><?php esc_attr_e( 'Enabled', 'wp-easycart-pro' ); ?></option>
												<option value="no"><?php esc_attr_e( 'Disabled', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Variant Title', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-title" value="" maxlength="150" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Availability', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select class="select2 wp-easycart-pro-conditional-logic-optionitem" id="wp-easycart-pro-variant-google-merchant-availability">
												<option value=""><?php esc_attr_e( 'Use current stock settings', 'wp-easycart-pro' ); ?></option>
												<option value="in_stock"><?php esc_attr_e( 'In Stock', 'wp-easycart-pro' ); ?></option>
												<option value="out_of_stock"><?php esc_attr_e( 'Out of Stock', 'wp-easycart-pro' ); ?></option>
												<option value="preorder"><?php esc_attr_e( 'Preorder', 'wp-easycart-pro' ); ?></option>
												<option value="backorder"><?php esc_attr_e( 'Backorder', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Color', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-color" value="" placeholder="<?php esc_attr_e( 'Red', 'wp-easycart-pro' ); ?>" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Pattern', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-pattern" value="" placeholder="<?php esc_attr_e( 'Striped', 'wp-easycart-pro' ); ?>" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Material', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-material" value="" placeholder="<?php esc_attr_e( 'Leather', 'wp-easycart-pro' ); ?>" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Age Group', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select class="select2 wp-easycart-pro-conditional-logic-optionitem" id="wp-easycart-pro-variant-google-merchant-age-group">
												<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
												<option value="newborn"><?php esc_attr_e( 'Newborn', 'wp-easycart-pro' ); ?></option>
												<option value="infant"><?php esc_attr_e( 'Infant', 'wp-easycart-pro' ); ?></option>
												<option value="toddler"><?php esc_attr_e( 'Toddler', 'wp-easycart-pro' ); ?></option>
												<option value="kids"><?php esc_attr_e( 'Kids', 'wp-easycart-pro' ); ?></option>
												<option value="adult"><?php esc_attr_e( 'Adult', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Gender', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select class="select2 wp-easycart-pro-conditional-logic-optionitem" id="wp-easycart-pro-variant-google-merchant-gender">
												<option value=""><?php esc_attr_e( 'Select One', 'wp-easycart-pro' ); ?></option>
												<option value="male"><?php esc_attr_e( 'Male', 'wp-easycart-pro' ); ?></option>
												<option value="female"><?php esc_attr_e( 'Female', 'wp-easycart-pro' ); ?></option>
												<option value="unisex"><?php esc_attr_e( 'Unisex', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Size', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-size" value="" placeholder="<?php esc_attr_e( 'Large', 'wp-easycart-pro' ); ?>" maxlength="100" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'MPN', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-mpn" value="" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'GTIN', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<input type="text" id="wp-easycart-pro-variant-google-merchant-gtin" value="" />
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding-top:15px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="justify-content:center;">
											<input type="button" value="<?php esc_attr_e( 'Update Google Merchant Settings', 'wp-easycart-pro' ); ?>" class="wp-easycart-pro-option-modal-add-button" onclick="wp_easycart_pro_update_variant_google_merchant()" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$quantity_type = 0;
if ( $product->use_optionitem_quantity_tracking ) {
	$quantity_type = 2;
} else if ( $product->show_stock_quantity ) {
	$quantity_type = 1;
}
?>
<div class="wp-easycart-pro-option-modal" id="wp-easycart-pro-change-tracking">
	<div class="wp-easycart-pro-option-modal-container">
		<div class="wp-easycart-pro-option-modal-box">
			<div class="wp-easycart-pro-option-modal-inner">
				<?php wp_easycart_admin( )->preloader->print_preloader( "wp-easycart-pro-advanced-option-modal-loader" ); ?>
				<div class="wp-easycart-pro-option-modal-header">
					<div class="wp-easycart-pro-option-modal-header-content">
						<div class="wp-easycart-pro-option-modal-close">
							<a href="#" onclick="return wp_easycart_pro_close_add_change_tracking();" class="wp-easycart-pro-option-modal-close-button"><span class="dashicons dashicons-no"></span></a>
						</div>
						<h3><?php esc_attr_e( 'Change Stock Tracking Type', 'wp-easycart-pro' ); ?></h3>
					</div>
				</div>
				<div class="wp-easycart-pro-option-modal-content">
					<div class="wp-easycart-pro-option-modal-content-container">
						<div class="wp-easycart-pro-option-modal-info">
							<?php esc_attr_e( 'Choose the quantity tracking style you prefer for this product.', 'wp-easycart-pro' ); ?>
						</div>
						<div class="wp-easycart-pro-option-table" style="padding:0px; margin-top:10px;">
							<div class="wp-easycart-pro-option-table-body">
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding:0px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-30" style="padding-left:10px;">
											<label><?php esc_attr_e( 'Select Quantity Tracking', 'wp-easycart-pro' ); ?>:</label>
										</div>
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-70" style="padding-left:10px;">
											<select id="wp-easycart-pro-quantity-tracking-type" class="select2">
												<option value="0"<?php echo ( 0 == $quantity_type ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'Do NOT Track Quantity', 'wp-easycart-pro' ); ?></option>
												<option value="1"<?php echo ( 1 == $quantity_type ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'Track Overall Quantity', 'wp-easycart-pro' ); ?></option>
												<option value="2"<?php echo ( 2 == $quantity_type ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'Track Variation Quantity', 'wp-easycart-pro' ); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="wp-easycart-pro-option-table-row" style="border:none; padding-top:15px;">
									<div class="wp-easycart-pro-option-table-column-group">
										<div class="wp-easycart-pro-option-table-column wp-easycart-pro-option-table-column-bold wp-easycart-pro-option-table-column-100" style="justify-content:center;">
											<input type="button" value="<?php esc_attr_e( 'Update Tracking Type', 'wp-easycart-pro' ); ?>" class="wp-easycart-pro-option-modal-add-button" onclick="wp_easycart_pro_update_tracking()" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
