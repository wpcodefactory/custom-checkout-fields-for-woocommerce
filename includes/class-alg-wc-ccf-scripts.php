<?php
/**
 * Custom Checkout Fields for WooCommerce - Scripts Class
 *
 * @version 1.8.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Scripts' ) ) :

class Alg_WC_CCF_Scripts {

	/**
	 * min_suffix.
	 *
	 * @version 1.8.1
	 * @since   1.4.5
	 */
	public $min_suffix;

	/**
	 * Constructor.
	 *
	 * @version 1.4.5
	 * @since   1.0.0
	 */
	function __construct() {
		$this->min_suffix = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min' );
		add_action( 'wp_enqueue_scripts',    array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * enqueue_admin_scripts.
	 *
	 * @version 1.6.3
	 * @since   1.4.0
	 */
	function enqueue_admin_scripts() {
		if ( isset( $_GET['page'], $_GET['tab'], $_GET['section'] ) && 'wc-settings' == $_GET['page'] && ALG_WC_CCF_ID == $_GET['tab'] && 'field_' == substr( $_GET['section'], 0, 6 ) ) {
			wp_enqueue_script( 'alg-wc-ccf-admin',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-admin' . $this->min_suffix . '.js',
				array( 'jquery' ),
				ALG_WC_CCF_VERSION,
				true
			);
		}
	}

	/**
	 * enqueue_frontend_scripts.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) store `//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css` etc. locally?
	 */
	function enqueue_frontend_scripts() {
		if ( ! is_checkout() ) {
			return;
		}
		$do_load = array(
			'select2'                     => false,
			'datepicker'                  => false,
			'datepicker_timepicker_addon' => false,
			'weekpicker'                  => false,
			'timepicker'                  => false,
			'fees'                        => false,
		);
		$select2_fields = array();
		$fees_fields    = array();
		for ( $i = 1; $i <= apply_filters( 'alg_wc_ccf_total_fields', 1 ); $i++ ) {
			if ( 'yes' === alg_wc_ccf_get_field_option( 'enabled', $i, 'no' ) ) {
				$field_type = alg_wc_ccf_get_field_option( 'type', $i, 'text' );
				// Select / datepicker, weekpicker, timepicker
				if ( 'select' === $field_type || 'multiselect' === $field_type ) {
					if ( 'yes' === alg_wc_ccf_get_field_option( 'type_select_select2', $i, 'no' ) ) {
						$do_load['select2'] = true;
						$is_i18n            = ( 'yes' === alg_wc_ccf_get_field_option( 'type_select_select2_is_i18n', $i, 'no' ) );
						$select2_field      = array(
							'field_id'           => alg_wc_ccf_get_field_option( 'section', $i, 'billing' ) . '_' . ALG_WC_CCF_KEY . '_' . $i,
							'minimumInputLength' => alg_wc_ccf_get_field_option( 'type_select_select2_min_input', $i, 0 ),
							'maximumInputLength' => alg_wc_ccf_get_field_option( 'type_select_select2_max_input', $i, 0 ),
							'is_tagging'         => ( 'yes' === alg_wc_ccf_get_field_option( 'type_select_select2_is_tagging', $i, 'no' ) ),
							'is_i18n'            => $is_i18n,
						);
						if ( $is_i18n ) {
							foreach ( alg_wc_ccf_get_select2_i18n_options() as $i18n_key => $i18n_value ) {
								$select2_field[ $i18n_key ] = alg_wc_ccf_get_field_option( "type_select_select2_{$i18n_key}", $i, $i18n_value );
							}
						}
						$select2_fields[] = $select2_field;
					}
				} elseif ( in_array( $field_type, array( 'datepicker', 'weekpicker', 'timepicker' ) ) ) {
					$do_load[ $field_type ] = true;
					if ( 'datepicker' === $field_type && 'yes' === alg_wc_ccf_get_field_option( 'type_datepicker_timepicker_addon', $i, 'no' ) ) {
						$do_load['datepicker_timepicker_addon'] = true;
					}
				}
				// Fees
				if ( 0 != alg_wc_ccf_get_field_option( 'fee_value', $i, 0 ) ) {
					$do_load['fees'] = true;
					$fees_fields[]   = alg_wc_ccf_get_field_option( 'section', $i, 'billing' ) . '_' . ALG_WC_CCF_KEY . '_' . $i;
				}
			}
		}
		// Select2
		if ( $do_load['select2'] ) {
			wp_enqueue_script( 'alg-wc-ccf-select2',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-select2' . $this->min_suffix . '.js',
				array( 'jquery' ),
				ALG_WC_CCF_VERSION,
				true
			);
			wp_localize_script( 'alg-wc-ccf-select2',
				'alg_wc_ccf_select2',
				array(
					'fields' => $select2_fields,
				)
			);
		}
		// Datepicker & Weekpicker
		if ( $do_load['datepicker'] || $do_load['weekpicker'] ) {
			// Script
			wp_enqueue_script( 'jquery-ui-datepicker' );
			// Style
			wp_enqueue_style( 'jquery-ui-style',
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',
				array(),
				ALG_WC_CCF_VERSION
			);
			// Datepicker script
			if ( $do_load['datepicker'] ) {
				wp_enqueue_script( 'alg-wc-ccf-datepicker',
					alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-datepicker' . $this->min_suffix . '.js',
					array( 'jquery' ),
					ALG_WC_CCF_VERSION,
					true
				);
				// Datepicker - Timepicker addon
				if ( $do_load['datepicker_timepicker_addon'] ) {
					wp_enqueue_script( 'alg-wc-ccf-datepicker-timepicker-addon',
						'//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js',
						array( 'jquery' ),
						ALG_WC_CCF_VERSION,
						true
					);
					wp_enqueue_style( 'alg-wc-ccf-datepicker-timepicker-addon',
						'//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css',
						array(),
						ALG_WC_CCF_VERSION
					);
				}
			}
			// Weekpicker script
			if ( $do_load['weekpicker'] ) {
				wp_enqueue_script( 'alg-wc-ccf-weekpicker',
					alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-weekpicker' . $this->min_suffix . '.js',
					array( 'jquery' ),
					ALG_WC_CCF_VERSION,
					true
				);
			}
		}
		// Timepicker
		if ( $do_load['timepicker'] ) {
			// Scripts
			wp_enqueue_script( 'jquery-ui-timepicker',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/lib/timepicker/jquery.timepicker.min.js',
				array( 'jquery' ),
				ALG_WC_CCF_VERSION,
				true
			);
			wp_enqueue_script( 'alg-wc-ccf-timepicker',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-timepicker' . $this->min_suffix . '.js',
				array( 'jquery' ),
				ALG_WC_CCF_VERSION,
				true
			);
			// Style
			wp_enqueue_style( 'alg-wc-ccf-timepicker-style',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/lib/timepicker/jquery.timepicker.min.css',
				array(),
				ALG_WC_CCF_VERSION
			);
		}
		// Fees
		if ( $do_load['fees'] ) {
			wp_enqueue_script( 'alg-wc-ccf-fees',
				alg_wc_custom_checkout_fields()->plugin_url() . '/includes/js/alg-wc-ccf-fees' . $this->min_suffix . '.js',
				array( 'jquery' ),
				ALG_WC_CCF_VERSION,
				true
			);
		}
	}

}

endif;

return new Alg_WC_CCF_Scripts();
