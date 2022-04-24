/**
 * alg-wc-ccf-fees.js
 *
 * @version 1.6.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( 'body' ).on( 'change', 'input.alg_wc_ccf_fee', function() {
	jQuery( 'body' ).trigger( 'update_checkout' );
} );
