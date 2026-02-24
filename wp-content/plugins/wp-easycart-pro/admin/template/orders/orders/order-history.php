<div class="ec_admin_settings_input" id="wpeasycart_order_history">
	<div>
		<div class="wpeasycart-timeline-container">
			<div class="wpeasycart-timeline-container-inner">
				<h4><?php esc_attr_e( 'Order History', 'wp-easycart-pro' ); ?><div id="wpeasycart_order_history_refresh" onclick="ec_order_history_refresh(); return false;"><span class="dashicons dashicons-image-rotate"></span></div></h4>
				<div class="wpeasycart-timline-scrollbox" data-simplebar="init">
					<div class="wpeasycart-timline-scrollbox-content">
						<div class="wpeasycart-timline-scrollbox-content-mask">
							<div class="wpeasycart-timline-scrollbox-content-mask-offset">
								<div class="wpeasycart-timline-scrollbox-content-mask-container">
									<div class="wpeasycart-timeline-content-container">
										<div class="wpeasycart-timeline">
											<?php if( count( $order_history ) == 0 ) { ?>
											<div class="wpeasycart-timeline-item">
												<span class="dashicons dashicons-plus-alt"></span>
												<div class="wpeasycart-timeline-item-info">
													<a href="#"><?php echo sprintf( esc_attr__( 'Order %d was created!', 'wp-easycart-pro' ), ( ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : '' ) ); ?></a>
													<small><?php esc_attr_e( 'This order has no log data, it may have been created before logging started.', 'wp-easycart-pro' ); ?></small>
													<p></p>
												</div>
											</div>
											
											<?php } else { ?>
												<?php foreach( $order_history as $order_history_item ) { ?>
													<div class="wpeasycart-timeline-item">
														<span class="dashicons <?php $this->print_order_history_dashicon( $order_history_item ); ?>"></span>
														<div class="wpeasycart-timeline-item-info">
															<a href="#"><?php $this->print_order_history_title( $order_history_item ); ?></a>
															<small><?php $this->print_order_history_subtitle( $order_history_item ); ?></small>
															<p><?php $this->get_order_history_date_diff( $order_history_item ); ?></p>
														</div>
													</div>
												<?php }?>
											<?php }?>
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