<?php
/**
 * Custom Checkout Fields for WooCommerce - All Products Section Settings - Field
 *
 * @version 1.8.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Settings_Field' ) ) :

class Alg_WC_CCF_Settings_Field extends Alg_WC_CCF_Settings_Section {

	/**
	 * field_nr.
	 *
	 * @version 1.8.1
	 * @since   1.0.0
	 */
	public $field_nr;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct( $field_nr ) {
		$this->id       = 'field_' . $field_nr;
		$this->desc     = __( 'Field', 'custom-checkout-fields-for-woocommerce' ) . ' #' . $field_nr;
		$this->field_nr = $field_nr;
		parent::__construct();
	}

	/**
	 * admin_script.
	 *
	 * @version 1.7.0
	 * @since   1.6.3
	 *
	 * @todo    (dev) move this to a JS file
	 */
	function admin_script() {
		?><script>
		jQuery( document ).ready( function() {
			// Variables
			var alg_wc_ccf_field_nr = <?php echo $this->field_nr; ?>;
			var alg_wc_ccf_sections = [ 'select', 'checkbox', 'datepicker', 'timepicker' ];
			// On init
			alg_wc_ccf_handle_sections( jQuery( '#alg_wc_ccf_type_' + alg_wc_ccf_field_nr ).val() );
			// On change
			jQuery( '#alg_wc_ccf_type_' + alg_wc_ccf_field_nr ).on( 'change', function() { alg_wc_ccf_handle_sections( jQuery( this ).val() ) } );
			// alg_wc_ccf_handle_sections() function
			function alg_wc_ccf_handle_sections( val ) {
				// Hide all sections
				alg_wc_ccf_sections.forEach( function( section ) {
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).prev( 'h2' ).hide();
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).hide();
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).next( 'table' ).hide();
				} );
				// Show corresponding section if necessary
				var section = false;
				switch ( val ) {
					case 'checkbox':
						section = 'checkbox';
						break;
					case 'datepicker':
					case 'weekpicker':
						section = 'datepicker';
						break;
					case 'timepicker':
						section = 'timepicker';
						break;
					case 'multiselect':
					case 'select':
					case 'radio':
						section = 'select';
						break;
				}
				if ( section ) {
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).prev( 'h2' ).show();
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).show();
					jQuery( '#alg_wc_ccf_field_type_' + section + '_options_' + alg_wc_ccf_field_nr + '-description' ).next( 'table' ).show();
				}
			}
		} );
		</script><?php
	}

	/**
	 * get_settings.
	 *
	 * @version 1.6.5
	 * @since   1.0.0
	 */
	function get_settings() {
		if ( 'yes' === alg_wc_ccf_get_option( 'hide_unrelated_type_options', 'no' ) ) {
			add_action( 'admin_footer', array( $this, 'admin_script' ) );
		}
		$options  = alg_get_wc_ccf_options();
		$settings = array();
		foreach ( $options as $option ) {
			if ( 'field_general_options' === $option['id'] && 'title' === $option['type'] ) {
				$option['title'] .= ' #' . $this->field_nr;
				$option['desc']  .= ' <code>' .
					'_' . alg_wc_ccf_get_field_option( 'section', $this->field_nr, 'billing' ) . '_' . ALG_WC_CCF_KEY . '_' . $this->field_nr . '</code>.' . '</em>';
			}
			if ( 'multiselect' === $option['type'] && 'wc-product-search' === $option['class'] ) {
				$product_ids = alg_wc_ccf_get_field_option( $option['id'], $this->field_nr, array() );
				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						$option['options'][ esc_attr( $product_id ) ] = esc_html( wp_strip_all_tags( $product->get_formatted_name() ) );
					}
				}
			}
			$option['id'] = $option['id'] . '_' . $this->field_nr;
			$settings[] = $option;
		}
		return $settings;
	}

}

endif;
