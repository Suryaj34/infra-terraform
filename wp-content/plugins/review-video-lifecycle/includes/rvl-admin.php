
<?php
if (!defined('ABSPATH')) { exit; }

// Settings page
add_action('admin_menu', function(){
    add_options_page(
        __('Review Video', 'review-video-lifecycle'),
        __('Review Video', 'review-video-lifecycle'),
        'manage_options',
        'rvl-settings',
        'rvl_render_settings_page'
    );
});

add_action('admin_init', function(){
    register_setting(RVL_OPTION_GROUP, RVL_OPTION_NAME, 'rvl_sanitize_options');

    add_settings_section('rvl_main', __('General', 'review-video-lifecycle'), function(){
        echo '<p>'.esc_html__('Configure how processed videos are pushed back into WordPress.', 'review-video-lifecycle').'</p>';
    }, 'rvl-settings');

    add_settings_field('cloudfront_domain', __('CloudFront/CDN Domain', 'review-video-lifecycle'), function(){
        $o = rvl_get_option('cloudfront_domain');
        echo '<input type="text" name="'.RVL_OPTION_NAME.'[cloudfront_domain]" value="'.esc_attr($o).'" class="regular-text" placeholder="dxxxxx.cloudfront.net or media.example.com">';
        echo '<p class="description">'.esc_html__('Used only for display convenience; AWS will typically send full URLs.', 'review-video-lifecycle').'</p>';
    }, 'rvl-settings', 'rvl_main');

    add_settings_field('ingest_secret', __('Ingest Secret', 'review-video-lifecycle'), function(){
        $o = rvl_get_option('ingest_secret');
        echo '<input type="text" name="'.RVL_OPTION_NAME.'[ingest_secret]" value="'.esc_attr($o).'" class="regular-text code" readonly onclick="this.select();">';
        echo '<p class="description">'.esc_html__('Send this in the X-Review-Video-Secret header from your AWS Lambda when posting to the REST endpoint.', 'review-video-lifecycle').'</p>';
    }, 'rvl-settings', 'rvl_main');

    add_settings_field('use_custom_post_type', __('Register Review post type', 'review-video-lifecycle'), function(){
        $checked = rvl_get_option('use_custom_post_type') ? 'checked' : '';
        echo '<label><input type="checkbox" name="'.RVL_OPTION_NAME.'[use_custom_post_type]" value="1" '.$checked.'> ' . esc_html__('Enable built-in "review" post type', 'review-video-lifecycle') . '</label>';
    }, 'rvl-settings', 'rvl_main');
});

function rvl_render_settings_page(){
    if (!current_user_can('manage_options')) return;
    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Review Video Settings','review-video-lifecycle').'</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields(RVL_OPTION_GROUP);
    do_settings_sections('rvl-settings');
    submit_button();
    echo '</form>';

    echo '<hr><h2>'.esc_html__('REST Ingest Endpoint','review-video-lifecycle').'</h2>';
    $endpoint = esc_url_raw( get_rest_url(null, 'review-video/v1/ingest') );
    echo '<code>'.$endpoint.'</code>';
    echo '<p>'.esc_html__('POST JSON with headers:', 'review-video-lifecycle').'</p>';
    echo '<pre>{"X-Review-Video-Secret": "'.esc_html(rvl_get_option('ingest_secret')).'", "Content-Type": "application/json"}</pre>';
    echo '<p>'.esc_html__('Body example:', 'review-video-lifecycle').'</p>';
    echo '<pre>'.esc_html(json_encode([
        'post_id' => 123,
        'public_url' => 'https://cdn.example.com/reviews/123/video.mp4',
        'poster_url' => 'https://cdn.example.com/reviews/123/poster.jpg',
        'duration' => 42.4,
        'width' => 1280,
        'height' => 720,
        'extra' => ['jobId' => 'abcd']
    ], JSON_PRETTY_PRINT)).'</pre>';

    echo '</div>';
}
