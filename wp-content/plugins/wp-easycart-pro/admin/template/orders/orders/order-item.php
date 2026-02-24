<div class="ec_admin_order_details_item_title_edit ec_admin_initial_hide" id="ec_admin_order_details_title_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Product Title', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_title_<?php echo $line_item->orderdetail_id;?>" id="line_item_title_<?php echo $line_item->orderdetail_id;?>" value="<?php echo $line_item->title;?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php if( $line_item->is_giftcard ){ ?>
<div class="ec_admin_order_details_item_giftcard_id_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_giftcard_id_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Gift Card ID', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_giftcard_id_<?php echo $line_item->orderdetail_id;?>" id="line_item_giftcard_id_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->giftcard_id ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>

<div class="ec_admin_order_details_item_gift_card_email_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_gift_card_email_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Gift Card Email', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_gift_card_email_<?php echo $line_item->orderdetail_id;?>" id="line_item_gift_card_email_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->gift_card_email ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>

<div class="ec_admin_order_details_item_gift_card_from_name_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_gift_card_from_name_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Gift Card From Name', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_gift_card_from_name_<?php echo $line_item->orderdetail_id;?>" id="line_item_gift_card_from_name_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->gift_card_from_name ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>

<div class="ec_admin_order_details_item_gift_card_to_name_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_gift_card_to_name_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Gift Card To Name', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_gift_card_to_name_<?php echo $line_item->orderdetail_id;?>" id="line_item_gift_card_to_name_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->gift_card_to_name ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<div class="ec_admin_order_details_item_gift_card_message_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_gift_card_message_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Gift Card Message', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_gift_card_message_<?php echo $line_item->orderdetail_id;?>" id="line_item_gift_card_message_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->gift_card_message ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php if( $line_item->optionitem_label_1 || $line_item->optionitem_name_1 ){ ?>
<div class="ec_admin_order_details_item_optionitem_name_1_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_optionitem_name_1_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Option 1', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_optionitem_name_1_<?php echo $line_item->orderdetail_id;?>" id="line_item_optionitem_name_1_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->optionitem_name_1 ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php if( $line_item->optionitem_label_2 || $line_item->optionitem_name_2 ){ ?>
<div class="ec_admin_order_details_item_optionitem_name_2_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_optionitem_name_2_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Option 2', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_optionitem_name_2_<?php echo $line_item->orderdetail_id;?>" id="line_item_optionitem_name_2_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->optionitem_name_2 ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php if( $line_item->optionitem_label_3 || $line_item->optionitem_name_3 ){ ?>
<div class="ec_admin_order_details_item_optionitem_name_3_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_optionitem_name_3_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Option 3', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_optionitem_name_3_<?php echo $line_item->orderdetail_id;?>" id="line_item_optionitem_name_3_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->optionitem_name_3 ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php if( $line_item->optionitem_label_4 || $line_item->optionitem_name_4 ){ ?>
<div class="ec_admin_order_details_item_optionitem_name_4_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_optionitem_name_4_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Option 4', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_optionitem_name_4_<?php echo $line_item->orderdetail_id;?>" id="line_item_optionitem_name_4_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->optionitem_name_4 ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php if( $line_item->optionitem_label_5 || $line_item->optionitem_name_5 ){ ?>
<div class="ec_admin_order_details_item_optionitem_name_5_edit ec_admin_order_details_item_option_edit ec_admin_initial_hide" id="ec_admin_order_details_optionitem_name_5_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Option 5', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_optionitem_name_5_<?php echo $line_item->orderdetail_id;?>" id="line_item_optionitem_name_5_<?php echo $line_item->orderdetail_id;?>" value="<?php echo htmlentities( stripslashes( $line_item->optionitem_name_5 ), ENT_NOQUOTES );?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php }?>

<?php
global $wpdb;
$advanced_options = $wpdb->get_results( $wpdb->prepare( "SELECT ec_order_option.* FROM ec_order_option WHERE ec_order_option.orderdetail_id = %s ORDER BY order_option_id", $line_item->orderdetail_id ));
if( count( $advanced_options ) > 0 ){
	foreach( $advanced_options as $advanced_option ){
		if( $advanced_option->option_type != 'file' && $advanced_option->option_type != 'grid' ){
?>
<div class="ec_admin_order_details_item_adv_opt_edit_<?php echo $line_item->orderdetail_id; ?> ec_admin_order_details_item_option_edit ec_admin_initial_hide" data-order-option-id="<?php echo $advanced_option->order_option_id; ?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php echo htmlentities( stripslashes( $advanced_option->option_name ), ENT_NOQUOTES ); ?></label>
				<input type="text" value="<?php echo htmlentities( stripslashes( $advanced_option->option_value ), ENT_NOQUOTES ); ?>" />
			</fieldset>
		</div>
	</div>
</div>
<?php 
		} 
	}
}?>

<div class="ec_admin_order_details_item_model_number_edit ec_admin_initial_hide" id="ec_admin_order_details_model_number_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'SKU', 'wp-easycart-pro' ); ?></label>
				<input type="text" name="line_item_model_number_<?php echo $line_item->orderdetail_id;?>" id="line_item_model_number_<?php echo $line_item->orderdetail_id;?>" value="<?php echo $line_item->model_number;?>" />
			</fieldset>
		</div>
	</div>
</div>
<div class="ec_admin_order_details_item_price_edit ec_admin_initial_hide" id="ec_admin_order_details_item_price_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Quantity Purchased', 'wp-easycart-pro' ); ?></label>
				<input type="number" min="1" step="1" name="line_item_quantity_<?php echo $line_item->orderdetail_id;?>" id="line_item_quantity_<?php echo $line_item->orderdetail_id;?>" value="<?php echo $line_item->quantity;?>" onchange="ec_admin_update_line_item_total( '<?php echo $line_item->orderdetail_id; ?>' );" />
			</fieldset>
		</div>
	</div>

	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Unit Price', 'wp-easycart-pro' ); ?></label>
				<input type="number" min="0" step=".01" name="line_item_unit_price_<?php echo $line_item->orderdetail_id;?>" id="line_item_unit_price_<?php echo $line_item->orderdetail_id;?>" value="<?php echo number_format( $line_item->unit_price, 2 ); ?>" onchange="ec_admin_update_line_item_total( '<?php echo $line_item->orderdetail_id; ?>' );" />
			</fieldset>
		</div>
	</div>
</div>

<div class="ec_admin_order_details_item_total_edit ec_admin_initial_hide" id="ec_admin_order_details_item_total_edit_<?php echo $line_item->orderdetail_id;?>">
	<div class="wp_easycart_admin_no_padding">
		<div class="wp-easycart-admin-toggle-group-text">
			<fieldset class="wp-easycart-admin-field-container">
				<label><?php esc_attr_e( 'Total Price', 'wp-easycart-pro' ); ?></label>
				<input type="number" min="0" step=".01" name="line_item_total_price_<?php echo $line_item->orderdetail_id;?>" id="line_item_total_price_<?php echo $line_item->orderdetail_id;?>" value="<?php echo number_format( $line_item->total_price, 2 );?>" />
			</fieldset>
		</div>
	</div>
</div>
<div class="ec_admin_order_details_line_item_save ec_admin_initial_hide" id="ec_admin_order_details_item_save_display_<?php echo $line_item->orderdetail_id;?>" onclick="ec_order_edit_line_item( '<?php echo $line_item->orderdetail_id;?>' ); return false;( '<?php echo $line_item->orderdetail_id; ?>' ); return false;"><?php esc_attr_e( 'SAVE CHANGES', 'wp-easycart-pro' ); ?></div>