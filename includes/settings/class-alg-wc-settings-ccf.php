<?php
/**
 * Custom Checkout Fields for WooCommerce - Settings
 *
 * @version 1.6.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Settings_CCF' ) ) :

class Alg_WC_Settings_CCF extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = ALG_WC_CCF_ID;
		$this->label = __( 'Custom Checkout Fields', 'custom-checkout-fields-for-woocommerce' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'custom_sanitize' ), PHP_INT_MAX, 3 );
		// Sections
		require_once( 'alg-wc-ccf-options.php' );
		require_once( 'class-alg-wc-ccf-settings-section.php' );
		require_once( 'class-alg-wc-ccf-settings-field.php' );
		require_once( 'class-alg-wc-ccf-settings-general.php' );
		for ( $i = 1; $i <= apply_filters( 'alg_wc_ccf_total_fields', 1 ); $i++ ) {
			new Alg_WC_CCF_Settings_Field( $i );
		}
	}

	/**
	 * custom_sanitize.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function custom_sanitize( $value, $option, $raw_value ) {
		return ( ! empty( $option['alg_wc_ccf_sanitize'] ) && ( $func = $option['alg_wc_ccf_sanitize'] ) && function_exists( $func ) ? $func( $raw_value ) : $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return $this->add_wc_ccf_id( array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'custom-checkout-fields-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $current_section . '_' . 'reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'custom-checkout-fields-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'custom-checkout-fields-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'custom-checkout-fields-for-woocommerce' ),
				'id'        => $current_section . '_' . 'reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $current_section . '_' . 'reset_options',
			),
		) ) );
	}

	/**
	 * add_wc_ccf_id.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_wc_ccf_id( $settings ) {
		$settings_with_id = array();
		foreach ( $settings as $setting ) {
			$setting['id'] = ALG_WC_CCF_ID . '_' . $setting['id'];
			$settings_with_id[] = $setting;
		}
		return $settings_with_id;
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === alg_wc_ccf_get_option( $current_section . '_' . 'reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
		}
	}

	/**
	 * Save settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
		wp_safe_redirect( add_query_arg( '', '' ) );
		exit;
	}

}

endif;

return new Alg_WC_Settings_CCF();
