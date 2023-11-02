/**
 * alg-wc-ccf-timepicker.js
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {
	/**
	 * Timepicker.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) localization
	 */
	jQuery( "input[display='time']" ).each( function() {
		jQuery( this ).timepicker( {
			timeFormat: jQuery( this ).attr( "timeformat" ),
			interval:   jQuery( this ).attr( "interval" ),
			minTime:    jQuery( this ).attr( "mintime" ),
			maxTime:    jQuery( this ).attr( "maxtime" ),
		} );
	} );
} );
