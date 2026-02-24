<?php global $wpdb; ?>
<?php $product_id = ( isset( $_GET['product_id'] ) ) ? (int) $_GET['product_id'] : 0; ?>
<?php $product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $product_id ) ); ?>
<?php $advanced_options = $wpdb->get_results( $wpdb->prepare( "SELECT ec_option.*, ec_option_to_product.product_id, ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option_to_product.option_order ASC, ec_option.option_name ASC", $product_id ) ); ?>
<?php $option_item_images = $wpdb->get_results( $wpdb->prepare( "SELECT ec_optionitemimage.* FROM ec_optionitemimage WHERE product_id = %d", $product_id ) ); ?>
<div class="ec_admin_product_details_section" id="wpeasycart_product_images_pro">

	<?php do_action( 'wp_easycart_admin_product_details_images_pro_start', $product ); ?>
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_images_pro_loader" ); ?>

	<h3 style="margin-top:0px;"><?php _e( 'Product Images', 'wp-easycart-pro' ); ?></h3>

	<div style="float:left; width:100%; margin-top:-10px; margin-bottom:10px;">
		<?php wp_easycart_admin( )->load_toggle_group( 'use_optionitem_images_pro', 'wp_easycart_optionitem_images_pro', $product->use_optionitem_images, __( 'Option Set Images', 'wp-easycart-pro' ), __( 'Enter a set of images for each combination of option items.', 'wp-easycart-pro' ) ); ?>
	</div>

	<div style="<?php echo ( ! $product->use_optionitem_images ) ? 'display:none; ' : ''; ?>float:left; width:100%; border:2px dashed #333; border-color:#333; border-radius:5px; padding:10px; margin:10px 0;" id="wp_easycart_gallery_optionset">
		<h3 style="margin:0 0 5px;"><?php esc_attr_e( 'Select an Item', 'wp-easycart-pro' ); ?></h3>
		<?php $option_items = false;
		if( $product->use_advanced_optionset ) {
			$advanced_option = false;
			for( $i=0; $i<count( $advanced_options ); $i++ ) {
				if ( ! $advanced_option && ( 'combo' == $advanced_options[$i]->option_type || 'swatch' == $advanced_options[$i]->option_type || 'radio' == $advanced_options[$i]->option_type ) ) {
					$advanced_option = $advanced_options[$i];
				}
			}
			if ( $advanced_option ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $advanced_option->option_id ) );
			}
		} else { 
			if ( 0 != $product->option_id_1 ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $product->option_id_1 ) );
			} else if ( 0 != $product->option_id_2 ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $product->option_id_1 ) );
			} else if ( 0 != $product->option_id_3 ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $product->option_id_1 ) );
			} else if ( 0 != $product->option_id_4 ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $product->option_id_1 ) );
			} else if ( 0 != $product->option_id_5 ) {
				$option_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $product->option_id_1 ) );
			}
		}
		if ( $option_items ) {
			$option_item_default = array(
				(object) array(
					'optionitem_id' => '0',
					'optionitem_name' => esc_attr__( 'Default', 'wp-easycart-pro' ),
				),
			);
			$option_items = array_merge( $option_item_default, $option_items );
			echo '<select id="ec_optionitem_images_options" style="margin:0;" onchange="wp_easycart_optionitem_images_change_pro();">';
			foreach( $option_items as $optionitem ) {
				echo '<option value="' . $optionitem->optionitem_id . '">' . $optionitem->optionitem_name . '</option>';
			}
			echo '</select>';
		} else {
			echo '<span style="float:left; width:45%;">' . esc_attr__( 'You must have a valid product option (basic option) or modifier (advanced option) selected. If a modifier (advanced option), it must be a swatch, combo, or radio type. The first valid product option or modifier will be used.', 'wp-easycart-pro' ) . '</span>';
		} ?>
		<div style="float:right; max-width:450px; width:50%; margin:-20px 0 5px;">
			<?php wp_easycart_admin( )->load_toggle_group( 'use_advanced_optionset', 'wp_easycart_use_advanced_optionset_pro', $product->use_advanced_optionset, __( 'Use First Modifier (Advanced Option)', 'wp-easycart-pro' ), __( 'Enable to use the first modifier (advanced option) for item images, disable to use with the first product option (basic option).', 'wp-easycart-pro' ) ); ?>
		</div>
	</div>
	
	<div class="ec_admin_product_details_optiontiem_images_group" id="optionitem_images_basic"<?php echo ( $product->use_optionitem_images ) ? ' style="display:none"' : ''; ?>>
		<?php $product_images = ( $product->product_images ) ? $product->product_images : ''; ?>
		<?php 
		if( $product_images == '' ){
			$product_images_arr = array( );
			if( $product->image1 != '' ){
				$product_images_arr[] = 'image1';
			}
			if( $product->image2 != '' ){
				$product_images_arr[] = 'image2';
			}
			if( $product->image3 != '' ){
				$product_images_arr[] = 'image3';
			}
			if( $product->image4 != '' ){
				$product_images_arr[] = 'image4';
			}
			if( $product->image5 != '' ){
				$product_images_arr[] = 'image5';
			}
			$product_images = implode( ',', $product_images_arr );
		}
		$arr_product_images = ( $product_images != '' ) ? explode( ',', $product_images ) : array( );
		?>
		<input type="hidden" value="<?php echo $product_images; ?>" id="wpeasycart_admin_product_gallery_ids_basic" class="wpeasycart_admin_product_gallery_ids" onchange="ec_admin_product_details_images_pro_list_change( 'wpeasycart_admin_product_gallery_ids_basic' )" />
		<div class="ec_admin_product_details_media" data-optionitem-id="basic">
			<div style="display:flex;">
				<div class="ec_admin_product_image" data-attachment_id="-1" style="width:100%; height:150px;">
					<div class="ec_admin_product_image_container">
						<div class="dashicons dashicons-plus-alt ec_admin_product_details_media_add" onClick="ec_admin_product_image_menu_open( 'wpeasycart_admin_product_image_add_basic' );"></div>
						<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_image_add_basic">
							<div class="ec_admin_product_image_menu_bg"></div>
							<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><div class="dashicons dashicons-dismiss"></div></div>
							<div class="ec_admin_product_image_menu_group">
								<ul class="ec_admin_product_image_menu_list">
									<li onclick="ec_admin_image_gallery( 'wpeasycart_admin_product_gallery_basic', 'basic' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><?php esc_attr_e( 'Media Library', 'wp-easycart-pro' ); ?></li>
									<li onclick="ec_admin_product_image_url_open( 'wpeasycart_admin_product_image_url_add_basic' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><?php esc_attr_e( 'Image URL', 'wp-easycart-pro' ); ?></li>
									<li onclick="ec_admin_product_video_url_open( 'wpeasycart_admin_product_video_url_add_basic', 'basic' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><?php esc_attr_e( 'Video URL', 'wp-easycart-pro' ); ?></li>
									<li onclick="ec_admin_product_youtube_url_open( 'wpeasycart_admin_product_youtube_url_add_basic', 'basic' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><?php esc_attr_e( 'YouTube Embed URL', 'wp-easycart-pro' ); ?></li>
									<li onclick="ec_admin_product_vimeo_url_open( 'wpeasycart_admin_product_vimeo_url_add_basic', 'basic' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_basic' );"><?php esc_attr_e( 'Vimeo Embed URL', 'wp-easycart-pro' ); ?></li>
								</ul>
							</div>
						</div>
						
						<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_image_url_add_basic">
							<div class="ec_admin_product_image_menu_bg"></div>
							<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_image_url_close( 'wpeasycart_admin_product_image_url_add_basic' );"><div class="dashicons dashicons-dismiss"></div></div>
							<div class="ec_admin_product_image_menu_group">
								<div class="ec_admin_product_image_input_group">
									<label for="wpeasycart_admin_product_image_url_add_basic_input"><?php esc_attr_e( 'Enter full URL starting with https:// or http://', 'wp-easycart-pro' ); ?></label>
									<input type="text" id="wpeasycart_admin_product_image_url_add_basic_input" value="" placeholder="https://yoursite.com/image.jpg" />
									<button onclick="ec_admin_product_image_url_add( 'wpeasycart_admin_product_image_url_add_basic_input', 'wpeasycart_admin_product_gallery_basic', 'basic' ); ec_admin_product_image_url_close( 'wpeasycart_admin_product_image_url_add_basic' )">Add Image</button>
								</div>
							</div>
						</div>
						
						<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_video_url_add_basic">
							<div class="ec_admin_product_image_menu_bg"></div>
							<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_video_url_close( 'wpeasycart_admin_product_video_url_add_basic' );"><div class="dashicons dashicons-dismiss"></div></div>
							<div class="ec_admin_product_image_menu_group">
								<div class="ec_admin_product_image_input_group">
									<label for="wpeasycart_admin_product_video_url_add_basic_input"><?php esc_attr_e( 'Enter full URL starting with https:// or http://', 'wp-easycart-pro' ); ?></label>
									<input type="text" id="wpeasycart_admin_product_video_url_add_basic_input" value="" placeholder="https://yoursite.com/video.mp4" />
									<input type="text" id="wpeasycart_admin_product_video_thumb_url_add_basic_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
									<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_video_thumb_url_add_basic_input' ); return false;">Media Library</a>
									<button onclick="ec_admin_product_video_url_add( 'wpeasycart_admin_product_video_url_add_basic_input', 'wpeasycart_admin_product_video_thumb_url_add_basic_input', 'wpeasycart_admin_product_gallery_basic', 'basic' ); ec_admin_product_video_url_close( 'wpeasycart_admin_product_video_url_add_basic' )">Add Video</button>
								</div>
							</div>
						</div>
						
						<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_youtube_url_add_basic">
							<div class="ec_admin_product_image_menu_bg"></div>
							<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_youtube_url_close( 'wpeasycart_admin_product_youtube_url_add_basic' );"><div class="dashicons dashicons-dismiss"></div></div>
							<div class="ec_admin_product_image_menu_group">
								<div class="ec_admin_product_image_input_group">
									<label for="wpeasycart_admin_product_youtube_url_add_basic_input"><?php esc_attr_e( 'Enter full embed URL from YouTube and thumbnail URL.', 'wp-easycart-pro' ); ?></label>
									<input type="text" id="wpeasycart_admin_product_youtube_url_add_basic_input" value="" placeholder="https://www.youtube.com/embed/AAKH3jJRaDk" />
									<input type="text" id="wpeasycart_admin_product_youtube_thumb_url_add_basic_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
									<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_youtube_thumb_url_add_basic_input' ); return false;">Media Library</a>
									<button onclick="ec_admin_product_youtube_url_add( 'wpeasycart_admin_product_youtube_url_add_basic_input', 'wpeasycart_admin_product_youtube_thumb_url_add_basic_input', 'wpeasycart_admin_product_gallery_basic', 'basic' ); ec_admin_product_youtube_url_close( 'wpeasycart_admin_product_youtube_url_add_basic' )">Add YouTube Video</button>
								</div>
							</div>
						</div>
						
						<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_vimeo_url_add_basic">
							<div class="ec_admin_product_image_menu_bg"></div>
							<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_vimeo_url_close( 'wpeasycart_admin_product_vimeo_url_add_basic' );"><div class="dashicons dashicons-dismiss"></div></div>
							<div class="ec_admin_product_image_menu_group">
								<div class="ec_admin_product_image_input_group">
									<label for="wpeasycart_admin_product_vimeo_url_add_basic_input"><?php esc_attr_e( 'Enter full embed URL from Vimeo.', 'wp-easycart-pro' ); ?></label>
									<input type="text" id="wpeasycart_admin_product_vimeo_url_add_basic_input" value="" placeholder="https://player.vimeo.com/video/1568156516" />
									<input type="text" id="wpeasycart_admin_product_vimeo_thumb_url_add_basic_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
									<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_vimeo_thumb_url_add_basic_input' ); return false;">Media Library</a>
									<button onclick="ec_admin_product_vimeo_url_add( 'wpeasycart_admin_product_vimeo_url_add_basic_input', 'wpeasycart_admin_product_vimeo_thumb_url_add_basic_input', 'wpeasycart_admin_product_gallery_basic', 'basic' ); ec_admin_product_vimeo_url_close( 'wpeasycart_admin_product_vimeo_url_add_basic' )">Add Vimeo Video</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="wpeasycart_admin_product_gallery_basic" class="wpeasycart_admin_product_gallery sortable" data-hidden-input="wpeasycart_admin_product_gallery_ids_basic">
				<?php foreach( $arr_product_images as $product_image ){ ?>
					<?php if( substr( $product_image, 0, 7 ) == 'http://' || substr( $product_image, 0, 8 ) == 'https://' ){ // external ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo $product_image; ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php }else if( $product_image == 'image1' ){ // easycart folders pic 1 ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo ( substr( $product->image1, 0, 7 ) == 'http://' || substr( $product->image1, 0, 8 ) == 'https://' )  ? $product->image1 : plugins_url( '/wp-easycart-data/products/pics1/' . $product->image1 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php }else if( $product_image == 'image2' ){ // easycart folders pic 2 ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo ( substr( $product->image2, 0, 7 ) == 'http://' || substr( $product->image2, 0, 8 ) == 'https://' )  ? $product->image2 : plugins_url( '/wp-easycart-data/products/pics2/' . $product->image2 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php }else if( $product_image == 'image3' ){ // easycart folders pic 3 ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo ( substr( $product->image3, 0, 7 ) == 'http://' || substr( $product->image3, 0, 8 ) == 'https://' )  ? $product->image3 : plugins_url( '/wp-easycart-data/products/pics3/' . $product->image3 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php }else if( $product_image == 'image4' ){ // easycart folders pic 4 ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo ( substr( $product->image4, 0, 7 ) == 'http://' || substr( $product->image4, 0, 8 ) == 'https://' )  ? $product->image4 : plugins_url( '/wp-easycart-data/products/pics4/' . $product->image4 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php }else if( $product_image == 'image5' ){ // easycart folders pic 5 ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo ( substr( $product->image5, 0, 7 ) == 'http://' || substr( $product->image5, 0, 8 ) == 'https://' )  ? $product->image5 : plugins_url( '/wp-easycart-data/products/pics5/' . $product->image5 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php } else if( substr( $product_image, 0, 6 ) == 'image:' ) { // image url ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo substr( $product_image, 6, strlen( $product_image ) - 6 ); ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php } else if( substr( $product_image, 0, 6 ) == 'video:' ) { // video url
						$video = substr( $product_image, 6, strlen( $product_image ) - 6 );
						$video_arr = explode( ':::', $video ); ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<div class="ec_admin_product_image_video_cover"></div>
							<img src="<?php echo $video_arr[1]; ?>" />
							<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php } else if( substr( $product_image, 0, 8 ) == 'youtube:' ) { // youtube 
						$video = substr( $product_image, 8, strlen( $product_image ) - 8 );
						$video_arr = explode( ':::', $video ); ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<div class="ec_admin_product_image_video_cover"></div>
							<img src="<?php echo $video_arr[1]; ?>" />
							<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
							<div class="dashicons dashicons-controls-play"></div>
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php } else if( substr( $product_image, 0, 6 ) == 'vimeo:' ) { // vimeo 
						$video = substr( $product_image, 6, strlen( $product_image ) - 6 );
						$video_arr = explode( ':::', $video ); ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<div class="ec_admin_product_image_video_cover"></div>
							<img src="<?php echo $video_arr[1]; ?>" />
							<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
					<?php } else { // media number ?>
						<?php $product_image_media = wp_get_attachment_image_src( $product_image, 'large' ); ?>
						<?php if( $product_image_media ){ ?>
						<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
							<img src="<?php echo $product_image_media[0]; ?>" />
							<ul class="actions">
								<li>
									<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
								</li>
							</ul>
						</div>
						<?php }?>
					<?php }?>
				<?php }?>
			</div>

		</div>
	</div>
	
	<?php if ( $option_items ) {
		$first_optionitem = true;
		foreach( $option_items as $optionitem ) {
			$product_images = '';
			$arr_product_images = array();
			$optionitem_image_set = false;
			foreach( $option_item_images as $option_item_image_group ) {
				if( $option_item_image_group->optionitem_id == $optionitem->optionitem_id ) {
					$optionitem_image_set = $option_item_image_group;
				}
			}
			if ( $optionitem_image_set ) {
				$product_images = ( $optionitem_image_set->product_images ) ? $optionitem_image_set->product_images : '';
				if( $product_images == '' ){
					$product_images_arr = array( );
					if( $optionitem_image_set->image1 != '' ){
						$product_images_arr[] = 'image1';
					}
					if( $optionitem_image_set->image2 != '' ){
						$product_images_arr[] = 'image2';
					}
					if( $optionitem_image_set->image3 != '' ){
						$product_images_arr[] = 'image3';
					}
					if( $optionitem_image_set->image4 != '' ){
						$product_images_arr[] = 'image4';
					}
					if( $optionitem_image_set->image5 != '' ){
						$product_images_arr[] = 'image5';
					}
					$product_images = implode( ',', $product_images_arr );
				}
				$arr_product_images = ( $product_images != '' ) ? explode( ',', $product_images ) : array( );
			}
			?>
			<div class="ec_admin_product_details_optiontiem_images_group" id="optionitem_images_<?php echo $optionitem->optionitem_id; ?>"<?php echo ( ! $product->use_optionitem_images || ! $first_optionitem ) ? ' style="display:none"' : ''; ?>>
				<input type="hidden" value="<?php echo $product_images; ?>" id="wpeasycart_admin_product_gallery_ids_<?php echo $optionitem->optionitem_id; ?>" class="wpeasycart_admin_product_gallery_ids" onchange="ec_admin_product_details_images_pro_list_change( 'wpeasycart_admin_product_gallery_ids_<?php echo $optionitem->optionitem_id; ?>' )" />
				<div class="ec_admin_product_details_media" data-optionitem-id="<?php echo esc_attr( $optionitem->optionitem_id ); ?>">
					<h4 style="margin:10px; text-align:center; font-size:2em; line-height:2em;"><?php echo esc_attr( $optionitem->optionitem_name ); ?></h4>
					<div style="display:flex;">
						<div class="ec_admin_product_image" data-attachment_id="-1" style="width:100%; height:150px;">
							<div class="ec_admin_product_image_container">
								<div class="dashicons dashicons-plus-alt ec_admin_product_details_media_add" onClick="ec_admin_product_image_menu_open( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"></div>
								<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>">
									<div class="ec_admin_product_image_menu_bg"></div>
									<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><div class="dashicons dashicons-dismiss"></div></div>
									<div class="ec_admin_product_image_menu_group">
										<ul class="ec_admin_product_image_menu_list">
											<li onclick="ec_admin_image_gallery( 'wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><?php esc_attr_e( 'Media Library', 'wp-easycart-pro' ); ?></li>
											<li onclick="ec_admin_product_image_url_open( 'wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><?php esc_attr_e( 'Image URL', 'wp-easycart-pro' ); ?></li>
											<li onclick="ec_admin_product_video_url_open( 'wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><?php esc_attr_e( 'Video URL', 'wp-easycart-pro' ); ?></li>
											<li onclick="ec_admin_product_youtube_url_open( 'wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><?php esc_attr_e( 'YouTube Embed URL', 'wp-easycart-pro' ); ?></li>
											<li onclick="ec_admin_product_vimeo_url_open( 'wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_menu_close( 'wpeasycart_admin_product_image_add_<?php echo $optionitem->optionitem_id; ?>' );"><?php esc_attr_e( 'Vimeo Embed URL', 'wp-easycart-pro' ); ?></li>
										</ul>
									</div>
								</div>

								<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>">
									<div class="ec_admin_product_image_menu_bg"></div>
									<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_image_url_close( 'wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>' );"><div class="dashicons dashicons-dismiss"></div></div>
									<div class="ec_admin_product_image_menu_group">
										<div class="ec_admin_product_image_input_group">
											<label for="wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>_input"><?php esc_attr_e( 'Enter full URL starting with https:// or http://', 'wp-easycart-pro' ); ?></label>
											<input type="text" id="wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://yoursite.com/image.jpg" />
											<button onclick="ec_admin_product_image_url_add( 'wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_image_url_close( 'wpeasycart_admin_product_image_url_add_<?php echo $optionitem->optionitem_id; ?>' )">Add Image</button>
										</div>
									</div>
								</div>

								<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>">
									<div class="ec_admin_product_image_menu_bg"></div>
									<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_video_url_close( 'wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>' );"><div class="dashicons dashicons-dismiss"></div></div>
									<div class="ec_admin_product_image_menu_group">
										<div class="ec_admin_product_image_input_group">
											<label for="wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>_input"><?php esc_attr_e( 'Enter full URL starting with https:// or http://', 'wp-easycart-pro' ); ?></label>
											<input type="text" id="wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://yoursite.com/video.mp4" />
											<input type="text" id="wpeasycart_admin_product_video_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
											<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_video_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input' ); return false;">Media Library</a>
											<button onclick="ec_admin_product_video_url_add( 'wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_video_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_video_url_close( 'wpeasycart_admin_product_video_url_add_<?php echo $optionitem->optionitem_id; ?>' )">Add Video</button>
										</div>
									</div>
								</div>

								<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>">
									<div class="ec_admin_product_image_menu_bg"></div>
									<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_youtube_url_close( 'wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>' );"><div class="dashicons dashicons-dismiss"></div></div>
									<div class="ec_admin_product_image_menu_group">
										<div class="ec_admin_product_image_input_group">
											<label for="wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>_input"><?php esc_attr_e( 'Enter full embed URL from YouTube and thumbnail URL.', 'wp-easycart-pro' ); ?></label>
											<input type="text" id="wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://www.youtube.com/embed/AAKH3jJRaDk" />
											<input type="text" id="wpeasycart_admin_product_youtube_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
											<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_youtube_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input' ); return false;">Media Library</a>
											<button onclick="ec_admin_product_youtube_url_add( 'wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_youtube_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_youtube_url_close( 'wpeasycart_admin_product_youtube_url_add_<?php echo $optionitem->optionitem_id; ?>' )">Add YouTube Video</button>
										</div>
									</div>
								</div>

								<div class="ec_admin_product_image_menu" id="wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>">
									<div class="ec_admin_product_image_menu_bg"></div>
									<div class="ec_admin_product_image_menu_close" onClick="ec_admin_product_vimeo_url_close( 'wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>' );"><div class="dashicons dashicons-dismiss"></div></div>
									<div class="ec_admin_product_image_menu_group">
										<div class="ec_admin_product_image_input_group">
											<label for="wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>_input"><?php esc_attr_e( 'Enter full embed URL from Vimeo.', 'wp-easycart-pro' ); ?></label>
											<input type="text" id="wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://player.vimeo.com/video/1568156516" />
											<input type="text" id="wpeasycart_admin_product_vimeo_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input" value="" placeholder="https://yoursite.com/image.jpg" style="width:69%;" />
											<a href="#" class="wp-easycart-product-group-media" onclick="return ec_admin_image_video_thumb( 'wpeasycart_admin_product_vimeo_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input' ); return false;">Media Library</a>
											<button onclick="ec_admin_product_vimeo_url_add( 'wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_vimeo_thumb_url_add_<?php echo $optionitem->optionitem_id; ?>_input', 'wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>', '<?php echo $optionitem->optionitem_id; ?>' ); ec_admin_product_vimeo_url_close( 'wpeasycart_admin_product_vimeo_url_add_<?php echo $optionitem->optionitem_id; ?>' )">Add Vimeo Video</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div id="wpeasycart_admin_product_gallery_<?php echo $optionitem->optionitem_id; ?>" class="wpeasycart_admin_product_gallery sortable" data-hidden-input="wpeasycart_admin_product_gallery_ids_<?php echo $optionitem->optionitem_id; ?>">
						<?php foreach ( $arr_product_images as $product_image ) { ?>
							<?php if ( substr( $product_image, 0, 7 ) == 'http://' || substr( $product_image, 0, 8 ) == 'https://' ) { // external ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo $product_image; ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( $product_image == 'image1' ){ // easycart folders pic 1 ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo ( substr( $product->image1, 0, 7 ) == 'http://' || substr( $product->image1, 0, 8 ) == 'https://' )  ? $product->image1 : plugins_url( '/wp-easycart-data/products/pics1/' . $product->image1 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( $product_image == 'image2' ){ // easycart folders pic 2 ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo ( substr( $product->image2, 0, 7 ) == 'http://' || substr( $product->image2, 0, 8 ) == 'https://' )  ? $product->image2 : plugins_url( '/wp-easycart-data/products/pics2/' . $product->image2 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( $product_image == 'image3' ){ // easycart folders pic 3 ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo ( substr( $product->image3, 0, 7 ) == 'http://' || substr( $product->image3, 0, 8 ) == 'https://' )  ? $product->image3 : plugins_url( '/wp-easycart-data/products/pics3/' . $product->image3 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( $product_image == 'image4' ){ // easycart folders pic 4 ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo ( substr( $product->image4, 0, 7 ) == 'http://' || substr( $product->image4, 0, 8 ) == 'https://' )  ? $product->image4 : plugins_url( '/wp-easycart-data/products/pics4/' . $product->image4 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( $product_image == 'image5' ){ // easycart folders pic 5 ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo ( substr( $product->image5, 0, 7 ) == 'http://' || substr( $product->image5, 0, 8 ) == 'https://' )  ? $product->image5 : plugins_url( '/wp-easycart-data/products/pics5/' . $product->image5 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( substr( $product_image, 0, 6 ) == 'image:' ) { // image url ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo substr( $product_image, 6, strlen( $product_image ) - 6 ); ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( substr( $product_image, 0, 6 ) == 'video:' ) { // video url
								$video = substr( $product_image, 6, strlen( $product_image ) - 6 );
								$video_arr = explode( ':::', $video );
								$video = $video_arr[0]; ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<div class="ec_admin_product_image_video_cover"></div>
									<img src="<?php echo $video_arr[1]; ?>" />
									<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( substr( $product_image, 0, 8 ) == 'youtube:' ) { // youtube 
								$video = substr( $product_image, 8, strlen( $product_image ) - 8 );
								$video_arr = explode( ':::', $video );
								$video = $video_arr[0]; ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<div class="ec_admin_product_image_video_cover"></div>
									<img src="<?php echo $video_arr[1]; ?>" />
									<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else if( substr( $product_image, 0, 6 ) == 'vimeo:' ) { // vimeo 
								$video = substr( $product_image, 6, strlen( $product_image ) - 6 );
								$video_arr = explode( ':::', $video );
								$video = $video_arr[0]; ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<div class="ec_admin_product_image_video_cover"></div>
									<img src="<?php echo $video_arr[1]; ?>" />
									<a class="ec_admin_product_image_video_button" href="<?php echo $video_arr[0]; ?>" target="_blank" /><div class="dashicons dashicons-controls-play"></div></a>
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
							<?php } else { // media ?>
								<?php $product_image_media = wp_get_attachment_image_src( $product_image, 'large' ); ?>
								<?php if( $product_image_media ){ ?>
								<div class="ec_admin_product_image ec_admin_product_image-sortable" data-attachment_id="<?php echo $product_image; ?>">
									<img src="<?php echo $product_image_media[0]; ?>" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="Delete" onclick="wpeasycart_admin_product_remove_image( jQuery( this ) ); return false;"><div class="dashicons dashicons-trash"></div></a>
										</li>
									</ul>
								</div>
								<?php }?>
							<?php }?>
						<?php }?>
					</div>
				</div>
			</div>
		<?php $first_optionitem = false; } ?>
	<?php } ?>

	<div id="wpeasycart-pro-image-set-importer"<?php if ( ! $product->use_optionitem_images ) { ?> style="display:none;"<?php }?>>
		<a href="admin.php?page=wp-easycart-products&subpage=products&product_id=<?php echo esc_attr( $product_id ); ?>&ec_admin_form_action=export-option-item-images" target="_blank" class="wp-easycart-pro-option-modal-add-button" style="float:right; display:inline-block; padding:6px 18px; margin:34px 0 0 10px;"><?php esc_attr_e( 'Export Image Set', 'wp-easycart-pro' ); ?></a>
	
		<form action="" method="POST" enctype="multipart/form-data" style="float:right; border:1px solid #CCC; padding:5px; max-width:100%; margin-top:30px;">
			<input type="hidden" name="ec_admin_form_action" value="import-option-item-images" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>" />
			<input type="file" placeholder="<?php esc_attr_e( 'Choose Image Set File', 'wp-easycart-pro' ); ?>" name="import_file" />
			<input type="submit" value="<?php esc_attr_e( 'Import Image Set', 'wp-easycart-pro' ); ?>" />
		</form>
	</div>

</div>
<script>
jQuery( '.wpeasycart_admin_product_gallery' ).sortable( {
		classes: {
			'ui-state-default': '.ec_admin_product_image-sortable'
		},
		stop: function(event, ui) {
			var vals = [];
			jQuery( this ).find( '.ec_admin_product_image-sortable' ).each( function() {
				vals.push( jQuery( this ).attr( 'data-attachment_id' ) );
			} );
			jQuery( '#' + jQuery( this ).attr( 'data-hidden-input' ) ).val( vals.join( ',' ) ).trigger( 'change' );
		} 
	} );
</script>