
<?php
if (!defined('ABSPATH')) { exit; }

// --- Shortcode: [review_video post_id="" width="720" autoplay="0" muted="0" loop="0" controls="1"] ---
add_shortcode('review_video', function($atts){
    $a = shortcode_atts([
        'post_id' => get_the_ID(),
        'width' => '720',
        'autoplay' => '0',
        'muted' => '0',
        'loop' => '0',
        'controls' => '1',
        'class' => '',
    ], $atts, 'review_video');

    $post_id = (int) $a['post_id'];
    if (!$post_id) return '';

    $status = get_post_meta($post_id, RVL_META_STATUS, true);
    $url = esc_url(get_post_meta($post_id, RVL_META_URL, true));
    $poster = esc_url(get_post_meta($post_id, RVL_META_POSTER, true));

    if ($status !== 'ready' || empty($url)) {
        return '<em>'.esc_html__('Processing your review video. Please check back soon.', 'review-video-lifecycle').'</em>';
    }

    $attrs = [];
    if (!empty($a['width'])) $attrs[] = 'width="'.esc_attr($a['width']).'"';
    if ($a['autoplay'] === '1') $attrs[] = 'autoplay';
    if ($a['muted'] === '1') $attrs[] = 'muted';
    if ($a['loop'] === '1') $attrs[] = 'loop';
    if ($a['controls'] === '1') $attrs[] = 'controls';
    if (!empty($poster)) $attrs[] = 'poster="'.esc_attr($poster).'"';
    if (!empty($a['class'])) $attrs[] = 'class="'.esc_attr($a['class']).'"';

    $html = '<video '.implode(' ', $attrs).' preload="metadata">';
    $html .= '<source src="'.esc_url($url).'" type="video/mp4">';
    $html .= esc_html__('Your browser does not support the video tag.', 'review-video-lifecycle');
    $html .= '</video>';

    return $html;
});

// --- Upload path scoping: put uploads under /reviews/{post_id} when using our uploader ---
add_filter('upload_dir', function($dirs){
    if (!empty($_REQUEST['rvl_for_post'])) {
        $pid = (int) $_REQUEST['rvl_for_post'];
        if ($pid > 0) {
            $subdir = trailingslashit($dirs['subdir']) . 'reviews/' . $pid;
            $dirs['subdir'] = $subdir;
            $dirs['path'] = $dirs['basedir'] . $subdir;
            $dirs['url']  = $dirs['baseurl'] . $subdir;
        }
    }
    return $dirs;
});

// --- Shortcode: [review_video_uploader post_id=""] : shows a simple front-end video uploader ---
add_shortcode('review_video_uploader', function($atts){
    $a = shortcode_atts([
        'post_id' => get_the_ID(),
        'max_mb' => 200,
    ], $atts, 'review_video_uploader');

    $post_id = (int) $a['post_id'];
    if (!$post_id) return '';

    if (!is_user_logged_in()) {
        return '<em>'.esc_html__('Please log in to upload a review video.', 'review-video-lifecycle').'</em>';
    }

    $max_bytes = ((int)$a['max_mb']) * 1024 * 1024;
    $action = esc_url(admin_url('admin-post.php'));
    $nonce = wp_create_nonce('rvl_upload_'.$post_id);

    ob_start();
    ?>
    <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="rvl_upload">
        <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
        <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo esc_attr($max_bytes); ?>">
        <input type="file" name="rvl_file" accept="video/*" required>
        <button type="submit"><?php echo esc_html__('Upload Review Video', 'review-video-lifecycle'); ?></button>
    </form>
    <?php
    return ob_get_clean();
});

// Handle upload POST (logged-in & guests routes)
add_action('admin_post_rvl_upload', 'rvl_handle_upload');
add_action('admin_post_nopriv_rvl_upload', 'rvl_handle_upload');

function rvl_handle_upload(){
    $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    if (!$post_id || !wp_verify_nonce($_POST['_wpnonce'] ?? '', 'rvl_upload_'.$post_id)) {
        wp_die(__('Invalid request', 'review-video-lifecycle'));
    }

    if (!isset($_FILES['rvl_file'])) {
        wp_redirect( add_query_arg('rvl_msg','nofile', get_permalink($post_id)) );
        exit;
    }

    // Scope the upload dir for this request
    $_REQUEST['rvl_for_post'] = $post_id;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    $overrides = [
        'test_form' => false,
        'mimes' => [
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'webm' => 'video/webm',
            'avi' => 'video/x-msvideo',
        ],
    ];

    $file = wp_handle_upload($_FILES['rvl_file'], $overrides);

    if (isset($file['error'])) {
        wp_redirect( add_query_arg('rvl_msg', 'error', get_permalink($post_id)) );
        exit;
    }

    // Create attachment
    $filetype = wp_check_filetype($file['file']);
    $attachment = [
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name(basename($file['file'])),
        'post_content' => '',
        'post_status' => 'inherit'
    ];
    $attach_id = wp_insert_attachment($attachment, $file['file'], $post_id);
    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $file['file']));

    // Mark the review as processing and link the source attachment
    update_post_meta($post_id, RVL_META_STATUS, 'processing');
    update_post_meta($post_id, RVL_META_URL, '');
    update_post_meta($post_id, RVL_META_SOURCE_ATTACHMENT, $attach_id);

    // Optional hook for offloaders / S3 metadata tagging
    do_action('rvl_review_video_uploaded', $post_id, $attach_id);

    wp_redirect( add_query_arg('rvl_msg','uploaded', get_permalink($post_id)) );
    exit;
}
