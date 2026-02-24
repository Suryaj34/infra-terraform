jQuery( document ).ready( function() {
	if ( jQuery('#is_holiday' ).length ) {
		wp_easycart_admin_pro_schedule_details_update_display();
		jQuery( '#is_holiday, #apply_to_retail, #apply_to_preorder, #apply_to_restaurant, #retail_closed, #preorder_closed, #restaurant_closed' ).on( 'change', function() {
			wp_easycart_admin_pro_schedule_details_update_display();
		} );
	}
	if ( jQuery( '#ec_admin_row_preorder_start' ).length ) {
		jQuery( '#ec_admin_row_preorder_start' ).find( 'select' ).on( 'change', function() {
			var new_date = wp_eascart_admin_subtract_from_date( jQuery( '#preorder_start_preview' ).attr( 'data-future-pickup-date' ), Number( jQuery( '#preorder_start_month' ).val() ), Number( jQuery( '#preorder_start_day' ).val() ), Number( jQuery( '#preorder_start_hour' ).val() ), Number( jQuery( '#preorder_start_minute' ).val() ) );
			var adjusted_info = '';
			if ( Number( jQuery( '#preorder_start_month' ).val() ) > 0 ) {
				adjusted_info += Number( jQuery( '#preorder_start_month' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_start_month' ).val() ) > 1 ) ? jQuery( '#preorder_start_preview' ).attr( 'data-months' ) :  jQuery( '#preorder_start_preview' ).attr( 'data-month' ) );
			}
			if ( Number( jQuery( '#preorder_start_day' ).val() ) > 0 ){
				adjusted_info += ' ' + Number( jQuery( '#preorder_start_day' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_start_day' ).val() ) > 1 ) ? jQuery( '#preorder_start_preview' ).attr( 'data-days' ) :  jQuery( '#preorder_start_preview' ).attr( 'data-day' ) );
			}
			if ( Number( jQuery( '#preorder_start_hour' ).val() ) > 0 ) {
				adjusted_info += ' ' + Number( jQuery( '#preorder_start_hour' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_start_hour' ).val() ) > 1 ) ? jQuery( '#preorder_start_preview' ).attr( 'data-hours' ) :  jQuery( '#preorder_start_preview' ).attr( 'data-hour' ) );
			}
			if ( Number( jQuery( '#preorder_start_minute' ).val() ) > 0 ) {
				adjusted_info += ' ' + Number( jQuery( '#preorder_start_minute' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_start_minute' ).val() ) > 1 ) ? jQuery( '#preorder_start_preview' ).attr( 'data-minutes' ) :  jQuery( '#preorder_start_preview' ).attr( 'data-minute' ) );
			}
			jQuery( '#preorder_start_preview .wpec-preview-before-info' ).html( adjusted_info );
			jQuery( '#preorder_start_preview .wpec-preview-adjusted-date' ).html( wp_easycart_admin_format_schedule_date( new_date ) );
		} );
	}
	if ( jQuery( '#ec_admin_row_preorder_end' ).length ) {
		jQuery( '#ec_admin_row_preorder_end' ).find( 'select' ).on( 'change', function() {
			var new_date = wp_eascart_admin_subtract_from_date( jQuery( '#preorder_end_preview' ).attr( 'data-future-pickup-date' ), Number( jQuery( '#preorder_end_month' ).val() ), Number( jQuery( '#preorder_end_day' ).val() ), Number( jQuery( '#preorder_end_hour' ).val() ), Number( jQuery( '#preorder_end_minute' ).val() ) );
			var adjusted_info = '';
			if ( Number( jQuery( '#preorder_end_month' ).val() ) > 0 ) {
				adjusted_info += Number( jQuery( '#preorder_end_month' ).val() ) + ' ' + ( ( Number( jQuery( '#preorder_end_month' ).val() ) > 1 ) ? jQuery( '#preorder_end_preview' ).attr( 'data-months' ) :  jQuery( '#preorder_end_preview' ).attr( 'data-month' ) );
			}
			if ( Number( jQuery( '#preorder_end_day' ).val() ) > 0 ){
				adjusted_info += ' ' + Number( jQuery( '#preorder_end_day' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_end_day' ).val() ) > 1 ) ? jQuery( '#preorder_end_preview' ).attr( 'data-days' ) :  jQuery( '#preorder_end_preview' ).attr( 'data-day' ) );
			}
			if ( Number( jQuery( '#preorder_end_hour' ).val() ) > 0 ) {
				adjusted_info += ' ' + Number( jQuery( '#preorder_end_hour' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_end_hour' ).val() ) > 1 ) ? jQuery( '#preorder_end_preview' ).attr( 'data-hours' ) :  jQuery( '#preorder_end_preview' ).attr( 'data-hour' ) );
			}
			if ( Number( jQuery( '#preorder_end_minute' ).val() ) > 0 ) {
				adjusted_info += ' ' + Number( jQuery( '#preorder_end_minute' ).val() ) + ' ' +( ( Number( jQuery( '#preorder_end_minute' ).val() ) > 1 ) ? jQuery( '#preorder_end_preview' ).attr( 'data-minutes' ) :  jQuery( '#preorder_end_preview' ).attr( 'data-minute' ) );
			}
			jQuery( '#preorder_end_preview .wpec-preview-before-info' ).html( adjusted_info );
			jQuery( '#preorder_end_preview .wpec-preview-adjusted-date' ).html( wp_easycart_admin_format_schedule_date( new_date ) );
		} );
	}
	if ( jQuery( '#holiday_date' ).length ) {
		jQuery( '#holiday_date' ).on( 'change', function() {
			var month_names = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
			var selected_date = jQuery( '#holiday_date' ).datepicker('getDate');
			if ( selected_date ) {
				var year = selected_date.getFullYear();
				var month = String( selected_date.getMonth() + 1 ).padStart( 2, '0' );
				var month_abbr = month_names[ selected_date.getMonth() ];
				var day = String( selected_date.getDate() ).padStart( 2, '0' );
				var date_time_update = year + '-' + month + '-' + day + 'T00:00:00';
				var hours_24 = selected_date.getHours();
				var minutes = String( selected_date.getMinutes() ).padStart( 2, '0' );
				var ampm = hours_24 >= 12 ? 'pm' : 'am';
				var hours_12 = hours_24 % 12;
				hours_12 = hours_12 ? hours_12 : 12;
				var date_time_update_display = month_abbr + ' ' + day + ', ' + year + ' ' + hours_12 + ':' + minutes + ampm;
				jQuery( '#preorder_start_preview' ).attr( 'data-future-pickup-date', date_time_update );
				jQuery( '#preorder_end_preview' ).attr( 'data-future-pickup-date', date_time_update );
				jQuery( '#preorder_start_preview .wpec-preview-start-date' ).html( date_time_update_display );
				jQuery( '#preorder_end_preview .wpec-preview-start-date' ).html( date_time_update_display );
				jQuery( '#ec_admin_row_preorder_start' ).find( 'select' ).trigger( 'change' );
				jQuery( '#ec_admin_row_preorder_end' ).find( 'select' ).trigger( 'change' );
			}
		} );
	}
} );

function wp_eascart_admin_subtract_from_date( startDate, monthsToSubtract = 0, daysToSubtract = 0, hoursToSubtract = 0, minutesToSubtract = 0 ) {
	let initialDate;
	if ( startDate instanceof Date ) {
		initialDate = startDate;
	} else if (typeof startDate === 'number') {
		initialDate = new Date( startDate ); // Assume timestamp in milliseconds
	} else if (typeof startDate === 'string') {
		initialDate = new Date( startDate ); // Attempt string parsing (use ISO 8601 format for reliability)
	} else {
		return '';
	}
	if (isNaN(initialDate.getTime())) {
		return '';
	}
	const resultDate = new Date( initialDate.getTime() );
	if (monthsToSubtract > 0) {
		resultDate.setMonth(resultDate.getMonth() - monthsToSubtract);
	}
	if (daysToSubtract > 0) {
		resultDate.setDate(resultDate.getDate() - daysToSubtract);
	}
	if (hoursToSubtract > 0) {
		resultDate.setHours(resultDate.getHours() - hoursToSubtract);
	}
	if (minutesToSubtract > 0) {
		resultDate.setMinutes(resultDate.getMinutes() - minutesToSubtract);
	}
	return resultDate;
}

function wp_easycart_admin_format_schedule_date( date ) {
	if ( ! ( date instanceof Date ) || isNaN( date.getTime() ) ) {
		return date;
	}
	const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	const monthAbbr = months[ date.getMonth() ];
	const day = date.getDate();
	const year = date.getFullYear();
	let hours = date.getHours();
	const minutes = date.getMinutes();
	const ampm = hours >= 12 ? 'pm' : 'am';
	hours = hours % 12;
	hours = hours ? hours : 12;
	const paddedMinutes = minutes < 10 ? '0' + minutes : minutes;
	const formattedString = `${monthAbbr} ${day}, ${year} ${hours}:${paddedMinutes} ${ampm}`;
	return formattedString;
}

function wp_easycart_admin_pro_schedule_details_update_display() {
	if( '1' == jQuery( document.getElementById( 'is_holiday' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_day_of_week' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_holiday_date' ) ).show( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_day_of_week' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_holiday_date' ) ).hide( );
	}
	if( '1' == jQuery( document.getElementById( 'apply_to_preorder' ) ).val() ) {
		jQuery( document.getElementById( 'preorder_closed' ) ).show( );
	} else {
		jQuery( document.getElementById( 'preorder_closed' ) ).hide( );
	}
	if( '0' == jQuery( document.getElementById( 'apply_to_preorder' ) ).val() || '1' == jQuery( document.getElementById( 'preorder_closed' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_preorder_start' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_end' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_open_time' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_close_time' ) ).hide( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_preorder_start' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_end' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_open_time' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_preorder_close_time' ) ).show( );
	}
	if( '1' == jQuery( document.getElementById( 'apply_to_retail' ) ).val() ) {
		jQuery( document.getElementById( 'retail_closed' ) ).show( );
	} else {
		jQuery( document.getElementById( 'retail_closed' ) ).hide( );
	}
	if( '0' == jQuery( document.getElementById( 'apply_to_retail' ) ).val() || '1' == jQuery( document.getElementById( 'retail_closed' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_retail_start' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_retail_end' ) ).hide( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_retail_start' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_retail_end' ) ).show( );
	}
	if( '1' == jQuery( document.getElementById( 'apply_to_restaurant' ) ).val() ) {
		jQuery( document.getElementById( 'restaurant_closed' ) ).show( );
	} else {
		jQuery( document.getElementById( 'restaurant_closed' ) ).hide( );
	}
	if( '0' == jQuery( document.getElementById( 'apply_to_restaurant' ) ).val() || '1' == jQuery( document.getElementById( 'restaurant_closed' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_restaurant_start' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_restaurant_end' ) ).hide( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_restaurant_start' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_restaurant_end' ) ).show( );
	}
}
