
<?php
if (!defined('ABSPATH')) { exit; }

add_action('rest_api_init', function(){
    register_rest_route('review-video/v1', '/ingest', [
        'methods' => 'POST',
        'callback' => 'rvl_rest_ingest',
        'permission_callback' => '__return_true', // We'll secure via shared secret header
        'args' => [
            'post_id' => [ 'required' => true, 'type' => 'integer' ],
            'public_url' => [ 'required' => true, 'type' => 'string' ],
            'poster_url' => [ 'required' => false, 'type' => 'string' ],
            'duration' => [ 'required' => false, 'type' => 'number' ],
            'width' => [ 'required' => false, 'type' => 'integer' ],
            'height' => [ 'required' => false, 'type' => 'integer' ],
            'extra' => [ 'required' => false ],
        ]
    ]);
});

function rvl_rest_ingest(\WP_REST_Request $req) {
    $secret_header = $req->get_header('X-Review-Video-Secret');
    $expected = rvl_get_option('ingest_secret');
    if (!$expected || !$secret_header || !hash_equals($expected, $secret_header)) {
        return new \WP_Error('unauthorized', 'Invalid or missing secret', ['status' => 401]);
    }

    $post_id = (int) $req->get_param('post_id');
    $url = esc_url_raw($req->get_param('public_url'));
    $poster = esc_url_raw($req->get_param('poster_url'));

    if (!$post_id || get_post_status($post_id) === false) {
        return new \WP_Error('not_found', 'Post not found', ['status' => 404]);
    }

    update_post_meta($post_id, RVL_META_URL, $url);
    if ($poster) update_post_meta($post_id, RVL_META_POSTER, $poster);
    update_post_meta($post_id, RVL_META_STATUS, 'ready');
    update_post_meta($post_id, '_review_video_updated', current_time('mysql'));

    do_action('rvl_video_ingested', $post_id, [
        'url' => $url,
        'poster' => $poster,
        'duration' => $req->get_param('duration'),
        'width' => $req->get_param('width'),
        'height' => $req->get_param('height'),
        'extra' => $req->get_param('extra'),
    ]);

    return [ 'ok' => true, 'post_id' => $post_id, 'url' => $url ];
}
