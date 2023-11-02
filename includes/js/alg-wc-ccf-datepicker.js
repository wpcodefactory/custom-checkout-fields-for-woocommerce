/**
 * alg-wc-ccf-datepicker.js
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {
	/**
	 * Datepicker.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @see     https://jqueryui.com/datepicker/
	 * @see     https://api.jqueryui.com/datepicker/
	 * @see     timepicker addon: https://github.com/trentrichardson/jQuery-Timepicker-Addon
	 * @see     timepicker addon: https://trentrichardson.com/examples/timepicker/
	 *
	 * @todo    (dev) datepicker: localization
	 * @todo    (dev) timepicker addon: use `wp_localize_script()` instead of custom HTML attributes
	 * @todo    (dev) hide weekends with CSS: https://stackoverflow.com/questions/501943/can-the-jquery-ui-datepicker-be-made-to-disable-saturdays-and-sundays-and-holid
	 */
	jQuery( "input[display='date']" ).each( function() {
		var mindate = jQuery( this ).attr( "mindate" );
		if ( 'zero' === mindate ) {
			mindate = 0;
		}
		var maxdate = jQuery( this ).attr( "maxdate" );
		if ( 'zero' === maxdate ) {
			maxdate = 0;
		}
		var atts = {
			dateFormat:    jQuery( this ).attr( "dateformat" ),
			minDate:       mindate,
			maxDate:       maxdate,
			firstDay:      jQuery( this ).attr( "firstday" ),
			changeYear:    jQuery( this ).attr( "changeyear" ),
			yearRange:     jQuery( this ).attr( "yearrange" ),
			beforeShowDay: exclude_dates,
		};
		var addon = jQuery( this ).attr( "addon" );
		if ( typeof addon !== typeof undefined && 'time' === addon ) {
			var mintime = jQuery( this ).attr( "mintime" );
			var maxtime = jQuery( this ).attr( "maxtime" );
			if ( typeof mintime !== typeof undefined ) {
				atts['minTime'] = mintime;
			}
			if ( typeof maxtime !== typeof undefined ) {
				atts['maxTime'] = maxtime;
			}
			atts['timeFormat'] = jQuery( this ).attr( "timeformat" );
			var i18n_current = jQuery( this ).attr( "i18n_current" );
			var i18n_close   = jQuery( this ).attr( "i18n_close" );
			var i18n_time    = jQuery( this ).attr( "i18n_time" );
			var i18n_hour    = jQuery( this ).attr( "i18n_hour" );
			var i18n_minute  = jQuery( this ).attr( "i18n_minute" );
			if ( typeof i18n_current !== typeof undefined ) {
				atts['currentText'] = i18n_current;
			}
			if ( typeof i18n_close !== typeof undefined ) {
				atts['closeText'] = i18n_close;
			}
			if ( typeof i18n_time !== typeof undefined ) {
				atts['timeText'] = i18n_time;
			}
			if ( typeof i18n_hour !== typeof undefined ) {
				atts['hourText'] = i18n_hour;
			}
			if ( typeof i18n_minute !== typeof undefined ) {
				atts['minuteText'] = i18n_minute;
			}
			jQuery( this ).datetimepicker( atts );
		} else {
			jQuery( this ).datepicker( atts );
		}

		/**
		 * exclude_dates.
		 *
		 * @version 1.4.4
		 * @since   1.4.3
		 */
		function exclude_dates( date ) {
			var exclude_days = jQuery( this ).attr( "excludedays" );
			if ( typeof exclude_days !== typeof undefined && false !== exclude_days ) {
				exclude_days = exclude_days.split( ',' );
				var day = date.getDay() + 1;
				for ( var i = 0; i < exclude_days.length; i++ ) {
					if ( day == exclude_days[i] ) {
						return [false];
					}
				}
			}
			var exclude_months = jQuery( this ).attr( "excludemonths" );
			if ( typeof exclude_months !== typeof undefined && false !== exclude_months ) {
				exclude_months = exclude_months.split( ',' );
				var month = date.getMonth() + 1;
				for ( var i = 0; i < exclude_months.length; i++ ) {
					if ( month == exclude_months[i] ) {
						return [false];
					}
				}
			}
			var exclude_dates = jQuery( this ).attr( "excludedates" );
			if ( typeof exclude_dates !== typeof undefined && false !== exclude_dates ) {
				exclude_dates = exclude_dates.split( ',' );
				var full_date = format_date( date );
				for ( var i = 0; i < exclude_dates.length; i++ ) {
					if ( compare_dates( full_date, exclude_dates[i] ) ) {
						return [false];
					}
				}
			}
			return [true];
		}

		/**
		 * format_date.
		 *
		 * @version 1.4.4
		 * @since   1.4.4
		 */
		function format_date( date ) {
			var month = ( date.getMonth() + 1 ).toString();
			var day   = date.getDate().toString();
			var year  = date.getFullYear().toString();
			if ( month.length < 2 ) {
				month = '0' + month;
			}
			if ( day.length < 2 ) {
				day = '0' + day;
			}
			return [year, month, day].join( '-' );
		}

		/**
		 * compare_dates.
		 *
		 * @version 1.4.4
		 * @since   1.4.4
		 */
		function compare_dates( date_str, template_str ) {
			if ( date_str == template_str ) {
				return true;
			} else if ( date_str.length == template_str.length ) {
				for ( var i = 0; i < date_str.length; i++ ) {
					if ( '*' != template_str[i] && date_str[i] != template_str[i] ) {
						return false;
					}
				}
				return true;
			}
			return false;
		}

	} );
} );
