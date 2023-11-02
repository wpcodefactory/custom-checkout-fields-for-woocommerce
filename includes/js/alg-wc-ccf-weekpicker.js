/**
 * alg-wc-ccf-weekpicker.js
 *
 * @version 1.4.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {
	/**
	 * Weekpicker.
	 *
	 * @version 1.4.3
	 * @since   1.0.0
	 *
	 * @todo    (test) `minDate` and `maxDate` (check "Datepicker")
	 * @todo    (dev) localization
	 * @todo    (dev) more options from "Datepicker", i.e. "Exclude days", "Exclude months", "Exclude dates", (maybe) "Timepicker addon"
	 */
	jQuery( "input[display='week']" ).each( function() {
		jQuery( this ).datepicker( {
			dateFormat:        jQuery( this ).attr( "dateformat" ),
			minDate:           jQuery( this ).attr( "mindate" ),
			maxDate:           jQuery( this ).attr( "maxdate" ),
			firstDay:          jQuery( this ).attr( "firstday" ),
			changeYear:        jQuery( this ).attr( "changeyear" ),
			yearRange:         jQuery( this ).attr( "yearrange" ),
			showOtherMonths:   true,
			selectOtherMonths: true,
			changeMonth:       true,
			showWeek:          true,
			beforeShow:        function( dateText, inst ) {
				// for week highlighting
				jQuery( "body" ).on( "mousemove", ".ui-datepicker-calendar tbody tr", function() {
					jQuery( this ).find( "td a" ).addClass( "ui-state-hover" );
					jQuery( this ).find( ".ui-datepicker-week-col" ).addClass( "ui-state-hover" );
				} );
				jQuery( "body" ).on( "mouseleave", ".ui-datepicker-calendar tbody tr", function() {
					jQuery( this ).find( "td a" ).removeClass( "ui-state-hover" );
					jQuery( this ).find( ".ui-datepicker-week-col" ).removeClass( "ui-state-hover" );
				} );
			},
			onClose:           function( dateText, inst ) {
				var date = jQuery( this ).datepicker( "getDate" );
				if ( null != date ) {
					var dateFormat       = inst.settings.dateFormat || jQuery( this ).datepicker._defaults.dateFormat;
					var endDate          = new Date( date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6 );
					var endDateFormatted = jQuery.datepicker.formatDate( dateFormat, endDate, inst.settings );
					jQuery( this ).val( dateText + " - " + endDateFormatted );
				}
				// disable live listeners so they don't impact other instances
				jQuery( "body" ).off( "mousemove",  ".ui-datepicker-calendar tbody tr" );
				jQuery( "body" ).off( "mouseleave", ".ui-datepicker-calendar tbody tr" );
			},
		} );
	} );
} );
