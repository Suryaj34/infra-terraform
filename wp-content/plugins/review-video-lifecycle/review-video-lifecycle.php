
<?php
/**
 * Plugin Name: Review Video Lifecycle
 * Description: Handles end-to-end review video flow: upload (frontend), processing state, and update via a secure REST endpoint when AWS MediaConvert output is ready.
 * Version: 1.0.0
 * Author: Harshini J & M365 Copilot
 * License: GPLv2 or later
 * Text Domain: review-video-lifecycle
 */

if (!defined('ABSPATH')) { exit; }

// Constants
define('RVL_VERSION', '1.0.0');
define('RVL_SLUG', 'review-video-lifecycle');
define('RVL_OPTION_GROUP', 'rvl_settings');
define('RVL_OPTION_NAME', 'rvl_options');

// Default options
function rvl_default_options() {
    return [
        'cloudfront_domain'   => '',
        'ingest_secret'       => '',
        'use_custom_post_type'=> 0,
    ];
}

// Activation: ensure secret exists
register_activation_hook(__FILE__, function(){
    $opts = get_option(RVL_OPTION_NAME, []);
    $opts = wp_parse_args($opts, rvl_default_options());
    if (empty($opts['ingest_secret'])) {
        $opts['ingest_secret'] = wp_generate_password(32, false, false);
    }
    update_option(RVL_OPTION_NAME, $opts);
});

// Helper to get option
function rvl_get_option($key, $default='') {
    $opts = get_option(RVL_OPTION_NAME, []);
    $opts = wp_parse_args($opts, rvl_default_options());
    return isset($opts[$key]) ? $opts[$key] : $default;
}

// Option update sanitizer
function rvl_sanitize_options($input) {
    $out = rvl_default_options();
    $out['cloudfront_domain'] = isset($input['cloudfront_domain']) ? sanitize_text_field($input['cloudfront_domain']) : '';
    $out['ingest_secret'] = isset($input['ingest_secret']) ? sanitize_text_field($input['ingest_secret']) : '';
    $out['use_custom_post_type'] = isset($input['use_custom_post_type']) ? (int) !!$input['use_custom_post_type'] : 0;
    return $out;
}

// Optional: Register a simple Review CPT if toggled
add_action('init', function(){
    if (rvl_get_option('use_custom_post_type')) {
        register_post_type('review', [
            'label' => __('Reviews', 'review-video-lifecycle'),
            'public' => true,
            'supports' => ['title','editor','thumbnail','author','comments'],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-format-video',
        ]);
    }
});

// Includes
require_once __DIR__ . '/includes/rvl-admin.php';
require_once __DIR__ . '/includes/rvl-rest.php';
require_once __DIR__ . '/includes/rvl-frontend.php';

// Meta keys used
define('RVL_META_URL', '_review_video_url');
define('RVL_META_POSTER', '_review_video_poster');
define('RVL_META_STATUS', '_review_video_status'); // processing|ready|failed

define('RVL_META_SOURCE_ATTACHMENT', '_review_video_source_attachment');

author_register: // noop label to avoid accidental removal
