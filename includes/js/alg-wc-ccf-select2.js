/**
 * alg-wc-ccf-select2.js
 *
 * @version 1.4.7
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {
	/**
	 * Select2.
	 *
	 * @version 1.4.7
	 * @since   1.0.0
	 *
	 * @todo    (dev) use `selectWoo` instead of `select2` (optionally)
	 * @todo    (dev) load full translation files (optionally)
	 */
	for ( var i = 0, len = alg_wc_ccf_select2.fields.length; i < len; i++ ) {
		var alg_wc_ccf_select2_field = alg_wc_ccf_select2.fields[i];
		var atts = {
			minimumInputLength: alg_wc_ccf_select2_field.minimumInputLength,
			maximumInputLength: alg_wc_ccf_select2_field.maximumInputLength,
		};
		if ( alg_wc_ccf_select2_field.is_tagging ) {
			atts.tags = true;
		}
		if ( alg_wc_ccf_select2_field.is_i18n ) {
			atts.language = {
					errorLoading: function() {
						// Workaround for https://github.com/select2/select2/issues/4355 instead of i18n_ajax_error.
						return alg_wc_ccf_select2_field.i18n_searching;
					},
					inputTooLong: function( args ) {
						var overChars = args.input.length - args.maximum;

						if ( 1 === overChars ) {
							return alg_wc_ccf_select2_field.i18n_input_too_long_1;
						}

						return alg_wc_ccf_select2_field.i18n_input_too_long_n.replace( '%qty%', overChars );
					},
					inputTooShort: function( args ) {
						var remainingChars = args.minimum - args.input.length;

						if ( 1 === remainingChars ) {
							return alg_wc_ccf_select2_field.i18n_input_too_short_1;
						}

						return alg_wc_ccf_select2_field.i18n_input_too_short_n.replace( '%qty%', remainingChars );
					},
					loadingMore: function() {
						return alg_wc_ccf_select2_field.i18n_load_more;
					},
					noResults: function() {
						return alg_wc_ccf_select2_field.i18n_no_matches;
					},
					searching: function() {
						return alg_wc_ccf_select2_field.i18n_searching;
					}
				}
		}
		jQuery( "#" + alg_wc_ccf_select2_field.field_id ).select2( atts );
	}
} );
