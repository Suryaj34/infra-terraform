
=== Review Video Lifecycle ===
Contributors: harshini, m365copilot
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

A small helper plugin to manage review videos: front-end uploader, processing state, and a secure REST endpoint for AWS to push the processed MP4 back to the review post.

== Description ==

**Flow**
1. User uploads a video via `[review_video_uploader post_id="123"]` on a Review page.
2. Plugin stores it under `/uploads/reviews/{post_id}/...` and marks the post as `processing`.
3. Your existing AWS S3 + MediaConvert pipeline processes it and stores the MP4 in your Output bucket/CDN.
4. Your Lambda calls `POST /wp-json/review-video/v1/ingest` with a shared secret to update the post to `ready` and set the MP4 URL.
5. The video renders via `[review_video post_id="123"]`.

**Shortcodes**
- `[review_video post_id="123" width="720" autoplay="0" muted="0" loop="0" controls="1"]`
- `[review_video_uploader post_id="123" max_mb="200"]`

**REST endpoint**
`POST /wp-json/review-video/v1/ingest`
Headers: `X-Review-Video-Secret: <secret from settings>`, `Content-Type: application/json`
Body example:
```
{
  "post_id": 123,
  "public_url": "https://cdn.example.com/reviews/123/video.mp4",
  "poster_url": "https://cdn.example.com/reviews/123/poster.jpg",
  "duration": 42.4,
  "width": 1280,
  "height": 720,
  "extra": {"jobId": "abcd"}
}
```

== Installation ==
1. Upload the ZIP via **Plugins → Add New → Upload Plugin**, then **Activate**.
2. Go to **Settings → Review Video** to copy the **Ingest Secret** and optionally enable the built-in **Review** post type.
3. Place the uploader shortcode on your review page: `[review_video_uploader post_id="123"]`.
4. Place the player shortcode where you want to show the processed video: `[review_video post_id="123"]`.
5. In your AWS Lambda (triggered by S3 Output), POST to the REST endpoint with the secret and the processed MP4 URL.

== Notes ==
- If you use WP Offload Media or similar, it will handle moving the original upload to S3 Input. This plugin just scopes the upload path and sets state.
- You can also update the video programmatically by calling `update_post_meta($post_id, '_review_video_url', $url); update_post_meta($post_id, '_review_video_status', 'ready');`.

== Changelog ==
= 1.0.0 =
* Initial release.
