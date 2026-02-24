<form action="<?php echo $this->action; ?>"  method="POST">
<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
<input type="hidden" name="subscription_id" id="subscription_id" value="<?php echo $this->subscription->subscription_id; ?>" />
<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->subscription->user_id; ?>" />
<input type="hidden" name="stripe_customer_id" id="stripe_customer_id" value="<?php echo $this->subscription->stripe_customer_id; ?>" />
<input type="hidden" name="stripe_subscription_id" id="stripe_subscription_id" value="<?php echo $this->subscription->stripe_subscription_id; ?>" />

<div class="ec_admin_settings_panel ec_admin_details_panel">
    <div class="ec_admin_important_numbered_list">
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_subscription_details" ); ?>
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-update"></div>
                    <span><?php _e( 'EDIT SUBSCRIPTIONS', 'wp-easycart-pro' ); ?></span>
                    <div class="ec_page_title_button_wrap">
                    	<?php 
						global $wpdb;
						if( $this->subscription->subscription_status != 'Canceled' && $this->subscription->subscription_type == 'stripe' ){
							$products = $wpdb->get_results( "SELECT product_id, title FROM ec_product WHERE is_subscription_item = 1 ORDER BY title ASC" ); ?>
                        	<select name="update_product_id" id="update_product_id">
                        		<option value="0"><?php _e( 'Select a Subscription Product', 'wp-easycart-pro' ); ?></option>
                        	    <?php foreach( $products as $product ){ ?>
                        	    <option value="<?php echo $product->product_id; ?>"<?php if( $product->product_id == $this->subscription->product_id ){ ?> selected="selected"<?php }?>><?php echo $product->title; ?></option>
                        	    <?php }?>
                        	</select>
                        <?php } ?>
                        <a href="<?php echo $this->docs_link; ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
                        </a>
                        <?php echo wp_easycart_admin( )->helpsystem->print_vids_url('orders', 'subscriptions', 'details');?>
                        <a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php _e( 'Back', 'wp-easycart-pro' ); ?></a>
                        <a href="https://dashboard.stripe.com/<?php echo ( ( get_option( 'ec_option_stripe_connect_use_sandbox' ) ) ? 'test/' : '' ); ?>subscriptions/<?php echo esc_attr( $this->subscription->stripe_subscription_id ); ?>" class="ec_page_title_button" target="_blank"><?php _e( 'View on Stripe', 'wp-easycart-pro' ); ?></a>
                        <input type="submit" value="<?php _e( 'Save', 'wp-easycart-pro' ); ?>" onclick="ec_admin_update_subscription( ); return false;" class="ec_page_title_button">
                    </div>
                </div>
            
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_refund_error" id="ec_admin_subscription_update_failed"><div><?php _e( 'There was an error switching your subscription product. Check your Stripe logs for more info.', 'wp-easycart-pro' ); ?></div></div>
                	<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title">
                        <?php _e( 'Subscription Orders', 'wp-easycart-pro' ); ?>
                        <?php if( $this->subscription->subscription_status != 'Canceled' && $this->subscription->subscription_type == 'stripe' ){ ?> 
                        <input type="submit" value="<?php _e( 'Cancel Subscription', 'wp-easycart-pro' ); ?>" id="ec_admin_cancel_subscription_button" onclick="ec_admin_cancel_subscription( ); return false;" class="ec_admin_order_edit_button">
                        <?php } ?>
                        <br>
                    </div>
                    <?php $orders = $wpdb->get_results( $wpdb->prepare( "SELECT order_id, grand_total, stripe_charge_id, DATE_FORMAT( order_date, %s ) AS order_date_formatted FROM ec_order WHERE subscription_id = %d ORDER BY order_date DESC", '%b %d, %Y', $this->subscription->subscription_id ) ); ?>
                    <table class="ec_admin_subscription_orders" cellpadding="0" cellspacing="0" border="0">
                    	<thead>
                        	<tr>
                            	<th><?php _e( 'Order ID', 'wp-easycart-pro' ); ?></th>
                                <th><?php _e( 'Amount Charged', 'wp-easycart-pro' ); ?></th>
                                <th><?php _e( 'Stripe Charge ID', 'wp-easycart-pro' ); ?></th>
                                <th><?php _e( 'Date Charged', 'wp-easycart-pro' ); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
							<?php foreach( $orders as $order ){ ?>
                            <tr>
                                <td><?php echo $order->order_id; ?></td>
                                <td><?php echo $GLOBALS['currency']->get_currency_display( $order->grand_total ); ?></td>
                                <td><?php echo $order->stripe_charge_id; ?></td>
                                <td><?php echo $order->order_date_formatted; ?></td>
                                <td><a href="admin.php?page=wp-easycart-orders&subpage=orders&order_id=<?php echo $order->order_id; ?>&ec_admin_form_action=edit"><?php _e( 'View Order', 'wp-easycart-pro' ); ?></a></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_first">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'Subscription Information', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <?php do_action( 'wp_easycart_admin_subscription_details_basic_fields' ); ?>
                </div>
            </div>
            
            <div class="ec_admin_list_line_item ec_admin_col_6 ">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'Subscription Terms & Dates', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <?php do_action( 'wp_easycart_admin_subscription_details_subscription_terms_fields' ); ?>
                </div>
            </div>
        </div>
        
        
        
         <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_first">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'Product Information', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <?php do_action( 'wp_easycart_admin_subscription_details_product_info_fields' ); ?>
                </div>
            </div>
            
            <div class="ec_admin_list_line_item ec_admin_col_6 ">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'Customer Information', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <?php do_action( 'wp_easycart_admin_subscription_details_customer_info_fields' ); ?>
                </div>
            </div>
        </div>
        
        <?php 
			if($this->subscription->subscription_type == 'stripe') {
		?>
        
         <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_first">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'Stripe Information', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <?php do_action( 'wp_easycart_admin_subscription_details_stripe_info_fields' ); ?>
                </div>
            </div>
        
        
        <?php
			} else if($this->subscription->subscription_type == 'paypal') {
		?>		
				
		 <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_first">
                <div class="ec_admin_settings_input">
                	<div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-admin-generic"></div>
                        <span><?php _e( 'PayPal Information', 'wp-easycart-pro' ); ?></span>
                    </div>
					
                    <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message">
                        <p><?php _e( 'Below is PayPal payment processor specific information regarding this recurring subscription.', 'wp-easycart-pro' ); ?></p>
                    </div>
                    
					<?php do_action( 'wp_easycart_admin_subscription_details_paypal_info_fields' ); ?>
                </div>
            </div>
           </div>
          </div>
        
        
        <?php		
			}  
			///////////////////////////////////////////////////
			//need orders for each subscription placed in here
			//////////////////////////////////////////////////
		?>
	    
    </div>
    <div class="ec_admin_details_footer">
      <div class="ec_page_title_button_wrap">
        <a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php _e( 'Back', 'wp-easycart-pro' ); ?></a>
        <input type="submit" value="<?php _e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
      </div>
    </div>
</div>
</form>