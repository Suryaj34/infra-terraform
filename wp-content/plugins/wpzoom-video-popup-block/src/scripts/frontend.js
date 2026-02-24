'use strict';

import magnificPopup from 'magnific-popup';

( function ( $ ) {
	$( function () {
		$( '.wpzoom-video-popup-block[href]' ).each(function() {
			const $this = $(this);
			const popupWidth = $this.data('popup-width') || '900px';
			const isMP4 = $this.attr('href').toLowerCase().endsWith('.mp4');
			// Detect if this is a YouTube Shorts URL
			const isYoutubeShorts = $this.attr('href').indexOf('youtube.com/shorts/') !== -1;
			// Detect if this is a TikTok URL
			const isTikTok = $this.attr('href').indexOf('tiktok.com') !== -1;
			// For portrait videos (TikTok & Shorts)
			const isPortrait = isYoutubeShorts || isTikTok;
			// For portrait videos, use 450px as width regardless of popupWidth setting
			const effectiveWidth = isPortrait ? '450px' : popupWidth;

			$this.magnificPopup( {
				type: 'iframe',
				mainClass: 'wpzoom-video-popup-block-modal' + (isPortrait ? ' wpzoom-video-popup-portrait' : ''),
				callbacks: {
					open: function() {
						// Set width on mfp-content - for portrait videos, always use portrait width
						this.contentContainer.css('max-width', effectiveWidth);
						
						// Add special styling for portrait videos (YouTube Shorts & TikTok)
						if (isPortrait) {
							// Add CSS for portrait orientation
							$('<style>')
								.prop('type', 'text/css')
								.html(`
									.wpzoom-video-popup-portrait .mfp-iframe-scaler {
										padding-top: 177.7778% !important; /* 16:9 inverted for portrait */
										max-width: 325px !important;
										margin: 0 auto;
									}
								`)
								.appendTo('head');
						}
					},
					elementParse: function(item) {
						// For MP4 videos, we need to create the video element
						if (isMP4) {
							const videoUrl = item.src;
							item.type = 'inline';
							item.src = $('<div class="mfp-iframe-scaler" style="max-width: ' + effectiveWidth + ';">' +
								'<div class="mfp-close">&#215;</div>' +
								'<video class="mfp-iframe" controls autoplay playsinline style="position: absolute; display: block; top: 0; left: 0; width: 100%; height: 100%; background: #000;">' +
									'<source src="' + videoUrl + '" type="video/mp4">' +
								'</video>' +
							'</div>');
						}
					}
				},
				iframe: {
					patterns: {
						youtube: {
							index: 'youtu', 
							id: function ( url ) {
								// Check if this is a YouTube Shorts URL
								const isShorts = url.indexOf('youtube.com/shorts/') !== -1;
								
								// Use appropriate regex based on URL type
								let m;
								if (isShorts) {
									m = url.match(/youtube\.com\/shorts\/([^#\&\?]*)/);
								} else {
									m = url.match( /^.*(?:youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/ );
								}
								
								if ( ! m || ! m[1] ) return null;

								let start = 0;

								if ( url.indexOf( 't=' ) != -1 ) {
									const split = url.split( 't=' );
									const hms = split[1].replace( 'h', ':' ).replace( 'm', ':' ).replace( 's', '' );
									const a = hms.split( ':' );

									if ( a.length == 1 ) {
										start = a[0];
									} else if ( a.length == 2 ) {
										start = (+a[0]) * 60 + (+a[1]);
									} else if ( a.length == 3 ) {
										start = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
									}
								}

								let suffix = '?autoplay=1';

								if (start > 0) {
									suffix = `?start=${start}&autoplay=1`;
								}

								return m[1] + suffix;
							},
							src: '//www.youtube.com/embed/%id%'
						},
						vimeo: {
							index: 'vimeo.com/', 
							id: function ( url ) {
								var m = url.match(/(?:https?:\/\/)?(?:www\.)?(?:player\.)?vimeo\.com\/(?:[a-z]*\/)*([0-9]{6,11})(?:\/([a-zA-Z0-9]+))?/);
								if (!m || !m[1]) return null;
								var videoId = m[1];
								var hash = m[2] ? 'h=' + m[2] : '';
								var params = hash ? '' : '?autoplay=' + 1;
								return hash ? videoId + '?' + hash + params : videoId + params;
							},
							src: '//player.vimeo.com/video/%id%'
						},
						tiktok: {
							index: 'tiktok.com',
							id: function(url) {
								// Match different TikTok URL formats
								// Format 1: https://www.tiktok.com/@username/video/1234567890123456789
								// Format 2: https://www.tiktok.com/@username/video/1234567890123456789?param=value
								const regex = /tiktok\.com\/@([^\/]+)\/video\/(\d+)/i;
								const match = url.match(regex);
								
								if (!match || !match[2]) return null;
								
								return match[2];
							},
							src: '//www.tiktok.com/embed/v2/%id%'
						}
					},
					markup: '<div class="mfp-iframe-scaler" style="max-width: ' + (isPortrait ? '325px' : effectiveWidth) + ';">' +
							'<div class="mfp-close">&#215;</div>' +
							'<iframe class="mfp-iframe"' + (isPortrait ? ' width="325" height="580"' : '') + ' frameborder="0" allowfullscreen></iframe>' +
							'</div>'
				}
			} );
		});
	} );
} ( jQuery ) );
