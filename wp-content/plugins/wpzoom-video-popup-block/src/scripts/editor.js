'use strict';

import json from '../../block.json';
import classnames from 'classnames';
import styled from '@emotion/styled';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useState, useEffect, RawHTML } from '@wordpress/element';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	__experimentalColorGradientSettingsDropdown as ColorGradientSettingsDropdown
} from '@wordpress/block-editor';
import {
	BaseControl,
	TextControl,
	Button,
	Spinner,
	Flex,
	FlexItem,
	RadioControl,
	__experimentalUnitControl as UnitControl,
	__experimentalAlignmentMatrixControl as AlignmentMatrixControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOptionIcon as ToggleGroupControlOptionIcon,
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
	__experimentalVStack as VStack
} from '@wordpress/components';
import * as playIcons from './icons';
import urlParser from 'js-video-url-parser/lib/base';
import 'js-video-url-parser/lib/provider/youtube';
import 'js-video-url-parser/lib/provider/vimeo';
import embed from 'embed-video';

// Add YouTube Shorts support directly
const originalParse = urlParser.parse;
urlParser.parse = function(url) {
    // Support for YouTube Shorts
    if (typeof url === 'string' && url.indexOf('youtube.com/shorts/') !== -1) {
        const match = url.match(/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i);
        if (match && match[1]) {
            return {
                mediaType: 'video',
                id: match[1],
                provider: 'youtube'
            };
        }
    }
    
    // Support for TikTok
    if (typeof url === 'string' && url.indexOf('tiktok.com') !== -1) {
        const match = url.match(/tiktok\.com\/@([^\/]+)\/video\/(\d+)/i);
        if (match && match[2]) {
            return {
                mediaType: 'video',
                id: match[2],
                provider: 'tiktok'
            };
        }
    }
    
    // Call the original parser for non-Shorts URLs
    if (originalParse) {
        return originalParse.call(this, url);
    }
    
    return undefined;
};

const translateAlignments = i => i.replace( 'top', 'start' ).replace( 'left', 'start' ).replace( 'bottom', 'end' ).replace( 'right', 'end' );

const htmlString = html => RawHTML( { children: html } );

const contentOutput = ( attributes, save = false ) => {
	const { source, url, libraryId, text, position, icon, iconColor, iconSize } = attributes;
	const libId      = parseInt( libraryId, 10 );
	const ico        = icon && icon > 0 && icon < 5 ? playIcons[`icon${icon}`] : playIcons.icon1;
	const iClr       = iconColor ? iconColor : undefined;
	const iSize      = iconSize ? iconSize : undefined;
	const iconStyle  = typeof iClr !== 'undefined' || typeof iSize !== 'undefined' ? { color: iClr, height: iSize, width: iSize } : undefined;
	const posArgs    = position && typeof position === 'string' && position.trim().length > 0 ? translateAlignments( position ).split( ' ' ) : [];
	const alignVert  = posArgs.length > 0 ? posArgs[0] : undefined;
	const alignHorz  = posArgs.length > 1 ? posArgs[1] : undefined;
	const buttonHref = url && typeof url === 'string' && url.length > 0 && ( ( 'service' === source && typeof urlParser.parse( url ) !== 'undefined' ) || 'local' === source ) ? url : undefined;
	const blkProps   = { 
		className: 'wpzoom-video-popup-block', 
		href: buttonHref, 
		style: { alignItems: alignVert, justifyContent: alignHorz }
	};
	if ( ! save ) blkProps.onClick = e => e.preventDefault();
	const blockProps = save ? useBlockProps.save( blkProps ) : useBlockProps( blkProps );

	return (
		<a { ...blockProps }>
			<span className="wpzoom-video-popup-block_icon" style={ iconStyle }>{ ico }</span>
			{ typeof text !== 'undefined' && text }
		</a>
	);
};

const contentOutputNew = ( attributes, save = false ) => {
	const { source, url, libraryId, text, position, icon, iconColor, iconSize, popupWidth } = attributes;
	const libId      = parseInt( libraryId, 10 );
	const ico        = icon && icon > 0 && icon < 5 ? playIcons[`icon${icon}`] : playIcons.icon1;
	const iClr       = iconColor ? iconColor : undefined;
	const iSize      = iconSize ? iconSize : undefined;
	const iconStyle  = typeof iClr !== 'undefined' || typeof iSize !== 'undefined' ? { color: iClr, height: iSize, width: iSize } : undefined;
	const posArgs    = position && typeof position === 'string' && position.trim().length > 0 ? translateAlignments( position ).split( ' ' ) : [];
	const alignVert  = posArgs.length > 0 ? posArgs[0] : undefined;
	const alignHorz  = posArgs.length > 1 ? posArgs[1] : undefined;
	const buttonHref = url && typeof url === 'string' && url.length > 0 && ( ( 'service' === source && typeof urlParser.parse( url ) !== 'undefined' ) || 'local' === source ) ? url : undefined;
	const blkProps   = { 
		className: 'wpzoom-video-popup-block', 
		href: buttonHref, 
		style: { alignItems: alignVert, justifyContent: alignHorz },
		'data-popup-width': popupWidth
	};
	if ( ! save ) blkProps.onClick = e => e.preventDefault();
	const blockProps = save ? useBlockProps.save( blkProps ) : useBlockProps( blkProps );

	return (
		<a { ...blockProps }>
			<span className="wpzoom-video-popup-block_icon" style={ iconStyle }>{ ico }</span>
			{ typeof text !== 'undefined' && text }
		</a>
	);
};

registerBlockType(
	json.name,
	{
		edit: ( props ) => {
			const { attributes, setAttributes, clientId } = props;
			const { source, url, libraryId, text, position, icon, iconColor, iconSize, popupWidth } = attributes;
			const [ state, setState ] = useState( { thumbnail: '', loading: false } );
			const libId = parseInt( libraryId, 10 );
			const Root = styled.div`box-sizing: border-box; max-width: 235px; padding-bottom: 12px; width: 100%;`;
			const Header = styled( Flex )`margin-bottom: 8px;`;
			const HeaderControlWrapper = styled( Flex )`min-height: 30px; gap: 0;`;

			const getVideoThumbnail = value => {
				if ( value.length > 0 ) {
					// Check if this is a YouTube Shorts URL
					const isYoutubeShorts = value.indexOf('youtube.com/shorts/') !== -1;
					
					if (isYoutubeShorts) {
						// For YouTube Shorts, extract the video ID and manually construct the thumbnail URL
						const shortsMatch = value.match(/youtube\.com\/shorts\/([^#\&\?]*)/);
						if (shortsMatch && shortsMatch[1]) {
							const videoId = shortsMatch[1];
							const thumbnailUrl = `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`;
							setState({ thumbnail: thumbnailUrl, loading: false });
							return;
						}
					}
					
					// Check if this is a TikTok URL
					const isTikTok = value.indexOf('tiktok.com') !== -1;
					
					if (isTikTok) {
						// For TikTok, we'll use a placeholder thumbnail as TikTok doesn't provide direct thumbnail access
						// We could potentially use a server-side approach to fetch real thumbnails, but that's beyond the scope here
						const tiktokMatch = value.match(/tiktok\.com\/@([^\/]+)\/video\/(\d+)/i);
						if (tiktokMatch && tiktokMatch[2]) {
							// Create an SVG with both a background and the TikTok logo
							const thumbnailUrl = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5MDAgNTAwIj4KICAgIDxyZWN0IHdpZHRoPSI5MDAiIGhlaWdodD0iNTAwIiBmaWxsPSIjZjBmMGYwIiByeD0iMTAiIHJ5PSIxMCIvPgogICAgPGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMzAwLDg1KSBzY2FsZSgwLjY1KSI+CiAgICAgICAgPHRleHQgeD0iMTgwIiB5PSI0MDAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzMzMyI+VGlrVG9rIFZpZGVvPC90ZXh0PgogICAgICAgIDxwYXRoIGZpbGw9IiMwMDAiIGQ9Ik00NDgsMjA5LjkxYTIxMC4wNiwyMTAuMDYsMCwwLDEtMTIyLjc3LTM5LjI1VjM0OS4zOEExNjIuNTUsMTYyLjU1LDAsMSwxLDE4NSwxODguMzFWMjc4LjJhNzQuNjIsNzQuNjIsMCwxLDAsNTIuMjMsNzEuMThWMGw4OCwwYTEyMS4xOCwxMjEuMTgsMCwwLDAsMS44NiwyMi4xN2gwQTEyMi4xOCwxMjIuMTgsMCwwLDAsMzgxLDEwMi4zOWExMjEuNDMsMTIxLjQzLDAsMCwwLDY3LDIwLjE0WiIvPgogICAgPC9nPgo8L3N2Zz4=';
							setState({ 
								thumbnail: thumbnailUrl, 
								loading: false 
							});
							return;
						}
					}
					
					// Standard URL parsing for regular videos
					const urlParsed = urlParser.parse( value );

					if ( typeof urlParsed !== 'undefined' ) {
						if ( '' !== state.thumbnail || true !== state.loading ) {
							setState( { thumbnail: '', loading: true } );
						}

						const thumbSize = 'vimeo' === urlParsed.provider
							? 'thumbnail_large'
							: ( 'dailymotion' === urlParsed.provider
								? 'thumbnail_1080_url'
								: 'maxresdefault' );

						embed.image( value, { image: thumbSize }, ( err, thumb ) => {
							if ( err ) {
								if ( '' !== state.thumbnail || false !== state.loading ) {
									setState( { thumbnail: '', loading: false } );
								}

								console.warn('Error loading thumbnail:', err);
							} else {
								if (thumb && thumb.src && thumb.src !== state.thumbnail || false !== state.loading) {
									setState( { thumbnail: thumb.src, loading: false } );
								}
							}
						} );

						return;
					}
				}

				if ( '' !== state.thumbnail || false !== state.loading ) {
					setState( { thumbnail: '', loading: false } );
				}
			};

			useEffect( () => getVideoThumbnail( url || '' ), [ url ] );

			return (
				<>
					<InspectorControls>
						<div className="wpzoom-video-popup-block-inspector">
							<ToolsPanel
								label={ __( 'Content', 'wpzoom-video-popup-block' ) }
								resetAllFilter={ ( attrs ) => ( { ...attrs, source: undefined, url: undefined, libraryId: undefined, icon: undefined, text: undefined } ) }
							>
								<ToolsPanelItem
									label={ __( 'Video URL', 'wpzoom-video-popup-block' ) }
									hasValue={ () => !! url }
									onDeselect={ () => setAttributes( { source: undefined, url: undefined, libraryId: undefined } ) }
									isShownByDefault
									resetAllFilter={ attrs => ( { ...attrs, source: undefined, url: undefined, libraryId: undefined } ) }
								>
									<VStack className="video-url-input">
										<RadioControl
											className="video-source-radio"
											label={ __( 'Video Source', 'wpzoom-video-popup-block' ) }
											selected={ source || 'service' }
											options={ [
												{
													value: 'service',
													label: htmlString( __( 'External <small><em>(i.e. YouTube, Vimeo, etc.)</em></small>', 'wpzoom-video-popup-block' ) )
												},
												{
													value: 'local',
													label: htmlString( __( 'Self-hosted MP4 <small><em>(i.e. Media Library)</em></small>', 'wpzoom-video-popup-block' ) )
												},
											] }
											onChange={ value => {
												setAttributes( { source: value, url: undefined, libraryId: undefined } );
												setState( { thumbnail: '', loading: false } );

												if ( 'service' === value ) {
													getVideoThumbnail( url || '' );
												}
											} }
										/>

										{ 'service' === source && <TextControl
											label={ __( 'Video URL', 'wpzoom-video-popup-block' ) }
											placeholder={ __( 'e.g. https://youtu.be/GRMSwnJzRDA or https://youtube.com/shorts/abCD123', 'wpzoom-video-popup-block' ) }
											value={ url || '' }
											onChange={ value => {
												// Detect if this is a portrait video (YouTube Shorts or TikTok)
												const isYoutubeShorts = value.indexOf('youtube.com/shorts/') !== -1;
												const isTikTok = value.indexOf('tiktok.com') !== -1;
												const isPortrait = isYoutubeShorts || isTikTok;
												
												// Set the width to 450px for portrait videos, or keep the existing width
												// Only change the width if switching to a portrait video format or from a portrait to landscape
												const currentIsPortrait = (url && (url.indexOf('youtube.com/shorts/') !== -1 || url.indexOf('tiktok.com') !== -1));
												
												if (isPortrait !== currentIsPortrait) {
													setAttributes({ 
														url: value,
														popupWidth: isPortrait ? '450px' : '900px'
													});
												} else {
													setAttributes({ url: value });
												}
												
												getVideoThumbnail( value );
											} }
										/> }

										{ 'local' === source && <MediaUploadCheck>
											<MediaUpload
												onSelect={ media => {
													setAttributes( { url: media.url, libraryId: media.id } );
												} }
												onClose={ () => setState( { loading: false } ) }
												allowedTypes={ [ 'video' ] }
												value={ libraryId }
												render={ ( { open } ) => (
													<div>
														<Button variant="primary" onClick={ () => { setState( { loading: true } ); open() } }>
															{ libId > 0 ? __( 'Replace', 'wpzoom-video-popup-block' ) : __( 'Select', 'wpzoom-video-popup-block' ) }
														</Button>
														&nbsp;
														{ libId > 0 && (
															<Button
																onClick={ () => {
																	setAttributes( { url: undefined, libraryId: undefined } );
																	setState( { loading: false } );
																} }
																variant="secondary"
															>
																{ __( 'Remove', 'wpzoom-video-popup-block' ) }
															</Button>

														) }
													</div>
												) }
											/>
										</MediaUploadCheck> }

										<div className={ classnames( 'preview-image', { 'show-preview': ( ( 'local' === source && typeof url !== 'undefined' && '' !== ( '' + url ).trim() ) || state.loading || '' !== state.thumbnail ) } ) }>
											{ state.loading ?
												<Spinner />
											:
												( 'local' === source && typeof url !== 'undefined' && '' !== ( '' + url ).trim() ?
													<video muted={ true } preload="auto">
														<source src={ url } type="video/mp4" />
													</video>
												:
													<img src={ state.thumbnail } />
												)
											}
										</div>
									</VStack>
								</ToolsPanelItem>

								<ToolsPanelItem
									label={ __( 'Icon', 'wpzoom-video-popup-block' ) }
									hasValue={ () => !! icon }
									onDeselect={ () => setAttributes( { icon: undefined } ) }
									isShownByDefault
									resetAllFilter={ attrs => ( { ...attrs, icon: undefined } ) }
								>
									<ToggleGroupControl
										label={ __( 'Icon', 'wpzoom-video-popup-block' ) }
										value={ icon }
										onChange={ value => setAttributes( { icon: value } ) }
										__experimentalIsIconGroup
									>
										<ToggleGroupControlOptionIcon
											value={ 1 }
											icon={ playIcons.icon1 }
											label={ __( 'Icon 1', 'wpzoom-video-popup-block' ) }
										/>

										<ToggleGroupControlOptionIcon
											value={ 2 }
											icon={ playIcons.icon2 }
											label={ __( 'Icon 2', 'wpzoom-video-popup-block' ) }
										/>

										<ToggleGroupControlOptionIcon
											value={ 3 }
											icon={ playIcons.icon3 }
											label={ __( 'Icon 3', 'wpzoom-video-popup-block' ) }
										/>

										<ToggleGroupControlOptionIcon
											value={ 4 }
											icon={ playIcons.icon4 }
											label={ __( 'Icon 4', 'wpzoom-video-popup-block' ) }
										/>
									</ToggleGroupControl>
								</ToolsPanelItem>

								<ToolsPanelItem
									label={ __( 'Text', 'wpzoom-video-popup-block' ) }
									hasValue={ () => !! text }
									onDeselect={ () => setAttributes( { text: undefined } ) }
									isShownByDefault
									resetAllFilter={ attrs => ( { ...attrs, text: undefined } ) }
								>
									<TextControl
										label={ __( 'Text', 'wpzoom-video-popup-block' ) }
										placeholder={ __( 'e.g. Play', 'wpzoom-video-popup-block' ) }
										value={ text }
										onChange={ value => setAttributes( { text: value } ) }
									/>
								</ToolsPanelItem>
							</ToolsPanel>

							<ToolsPanel
								label={ __( 'Popup Settings', 'wpzoom-video-popup-block' ) }
								resetAllFilter={ ( attrs ) => ( { ...attrs, popupWidth: '900px' } ) }
							>
								<ToolsPanelItem
									label={ __( 'Popup Width', 'wpzoom-video-popup-block' ) }
									hasValue={ () => popupWidth !== '900px' }
									onDeselect={ () => setAttributes( { popupWidth: '900px' } ) }
									isShownByDefault
									resetAllFilter={ attrs => ( { ...attrs, popupWidth: '900px' } ) }
								>
									<UnitControl
										label={ __( 'Popup Width', 'wpzoom-video-popup-block' ) }
										value={ popupWidth }
										onChange={ value => setAttributes( { popupWidth: value } ) }
										units={[
											{ value: 'px', label: 'px', default: 900 },
											{ value: '%', label: '%', default: 100 }
										]}
										help={ __( 'Note: Width is automatically set to 450px for YouTube Shorts and TikTok videos.', 'wpzoom-video-popup-block' ) }
									/>
								</ToolsPanelItem>
							</ToolsPanel>
						</div>
					</InspectorControls>

					<InspectorControls group="color">
						<ToolsPanelItem
							label={ __( 'Icon', 'wpzoom-video-popup-block' ) }
							hasValue={ () => !! iconColor }
							onDeselect={ () => setAttributes( { iconColor: undefined } ) }
							panelId={ clientId }
							isShownByDefault={ false }
							resetAllFilter={ attrs => ( { ...attrs, iconColor: undefined } ) }
						>
							<ColorGradientSettingsDropdown
								__experimentalHasMultipleOrigins
								__experimentalIsRenderedInSidebar
								settings={ [
									{
										colorValue: iconColor,
										label: __( 'Icon', 'wpzoom-video-popup-block' ),
										onColorChange: value => setAttributes( { iconColor: value } ),
										isShownByDefault: false,
										disableCustomGradients: true,
										enableAlpha: true,
										resetAllFilter: () => ( { iconColor: undefined } )
									},
								] }
								panelId={ clientId }
							/>
						</ToolsPanelItem>
					</InspectorControls>

					<InspectorControls group="dimensions">
						<ToolsPanelItem
							label={ __( 'Content Position', 'wpzoom-video-popup-block' ) }
							hasValue={ () => !! position }
							onDeselect={ () => setAttributes( { position: undefined } ) }
							panelId={ clientId }
							isShownByDefault={ false }
							resetAllFilter={ attrs => ( { ...attrs, position: undefined } ) }
						>
							<Root role="group">
								<Header className="component-box-control__header">
									<FlexItem>
										<BaseControl.VisualLabel>
											{ __( 'Content Position', 'wpzoom-video-popup-block' ) }
										</BaseControl.VisualLabel>
									</FlexItem>
								</Header>

								<HeaderControlWrapper className="component-box-control__header-control-wrapper">
									<AlignmentMatrixControl
										label={ __( 'Change content position', 'wpzoom-video-popup-block' ) }
										value={ position }
										onChange={ value => setAttributes( { position: value } ) }
									/>
								</HeaderControlWrapper>
							</Root>
						</ToolsPanelItem>

						<ToolsPanelItem
							label={ __( 'Icon Size', 'wpzoom-video-popup-block' ) }
							hasValue={ () => !! iconSize }
							onDeselect={ () => setAttributes( { iconSize: undefined } ) }
							panelId={ clientId }
							isShownByDefault={ false }
							resetAllFilter={ attrs => ( { ...attrs, iconSize: undefined } ) }
						>
							<Root role="group">
								<Header className="component-box-control__header">
									<FlexItem>
										<BaseControl.VisualLabel>
											{ __( 'Icon Size', 'wpzoom-video-popup-block' ) }
										</BaseControl.VisualLabel>
									</FlexItem>
								</Header>

								<HeaderControlWrapper className="component-box-control__header-control-wrapper">
									<UnitControl
										label={ __( 'Change icon size', 'wpzoom-video-popup-block' ) }
										hideLabelFromVision={ true }
										value={ iconSize }
										onChange={ value => setAttributes( { iconSize: value } ) }
										units={ [
											{ value: 'px', label: 'px', default: 64 },
											{ value: 'em', label: 'em', default: 2 },
											{ value: 'rem', label: 'rem', default: 4 },
										] }
									/>
								</HeaderControlWrapper>
							</Root>
						</ToolsPanelItem>
					</InspectorControls>

					{ contentOutput( attributes, false ) }
				</>
			);
		},

		save: props => contentOutputNew( props.attributes, true ),

		deprecated: [
			{
				attributes: {
					source: {
						type: 'string',
						default: 'service'
					},
					url: {
						type: 'string',
						default: ''
					},
					libraryId: {
						type: 'integer',
						default: -1
					},
					text: {
						type: 'string',
						default: 'Play'
					},
					position: {
						type: 'string',
						default: ''
					},
					icon: {
						type: 'integer',
						default: 1
					},
					iconColor: {
						type: 'string',
						default: ''
					},
					iconSize: {
						type: 'string',
						default: ''
					}
				},
				save: props => contentOutput( props.attributes, true ),
				migrate: ( attributes ) => {
					return {
						...attributes,
						popupWidth: '900px'
					};
				}
			}
		]
	}
);
