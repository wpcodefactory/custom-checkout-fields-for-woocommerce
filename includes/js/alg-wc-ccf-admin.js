/**
 * alg-wc-ccf-admin.js
 *
 * @version 1.4.0
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {
	jQuery( "#alg-wc-ccf-select-all-countries" ).click( function( event ) {
		event.preventDefault();
		jQuery( "select.alg-wc-ccf-countries" ).select2( "destroy" ).find( "option" ).prop( "selected", "selected" ).end().select2();
		return false;
	} );
	jQuery( "#alg-wc-ccf-deselect-all-countries" ).click( function( event ) {
		event.preventDefault();
		jQuery( "select.alg-wc-ccf-countries" ).val( "" ).change();
		return false;
	} );
} );
