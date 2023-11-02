<?php
/**
 * Custom Checkout Fields for WooCommerce - Customer Details Class
 *
 * @version 1.7.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Customer_Details' ) ) :

class Alg_WC_CCF_Customer_Details {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `duplicate`: default field value
	 */
	function __construct() {
		add_filter( 'woocommerce_customer_meta_fields', array( $this, 'add_checkout_custom_fields_customer_meta_fields' ), PHP_INT_MAX );
		for ( $i = 1; $i <= apply_filters( 'alg_wc_ccf_total_fields', 1 ); $i++ ) {
			if ( 'yes' === alg_wc_ccf_get_field_option( 'enabled', $i, 'no' ) ) {
				add_filter( 'default_checkout_' . alg_wc_ccf_get_field_option( 'section', $i, 'billing' ) . '_' . ALG_WC_CCF_KEY . '_' . $i,
					array( $this, 'add_default_checkout_custom_fields' ), PHP_INT_MAX, 2 );
			}
		}
	}

	/**
	 * add_checkout_custom_fields_customer_meta_fields.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `duplicate`: user profile fields
	 */
	function add_checkout_custom_fields_customer_meta_fields( $fields ) {
		for ( $i = 1; $i <= apply_filters( 'alg_wc_ccf_total_fields', 1 ); $i++ ) {
			if ( 'yes' === alg_wc_ccf_get_field_option( 'enabled', $i, 'no' ) ) {
				if ( 'multiselect' === alg_wc_ccf_get_field_option( 'type', $i, 'text' ) ) {
					continue;
				}
				if ( 'no' === alg_wc_ccf_get_field_option( 'customer_meta_fields', $i, 'yes' ) ) {
					continue;
				}
				$section = alg_wc_ccf_get_field_option( 'section', $i, 'billing' );
				$fields[ $section ]['fields'][ $section . '_' . ALG_WC_CCF_KEY . '_' . $i ] = array(
					'label'       => alg_wc_ccf_get_field_option( 'label', $i, '' ),
					'description' => alg_wc_ccf_get_field_option( 'description', $i, '' ),
				);
			}
		}
		return $fields;
	}

	/**
	 * add_default_checkout_custom_fields.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 */
	function add_default_checkout_custom_fields( $default, $field_key ) {
		// Maybe disable prepopulation
		$i = explode( '_', $field_key );
		$i = $i[ count( $i ) - 1 ];
		if ( 'multiselect' === alg_wc_ccf_get_field_option( 'type', $i, 'text' ) ) {
			return null;
		}
		if ( 'no' === alg_wc_ccf_get_field_option( 'default_prepopulate', $i, 'yes' ) ) {
			return null;
		}
		// If the value is empty, try to get it from user meta
		if ( null === $default && is_user_logged_in() && ( $user = wp_get_current_user() ) && ( $meta = get_user_meta( $user->ID, $field_key, true ) ) ) {
			return $meta;
		}
		return $default;
	}

}

endif;

return new Alg_WC_CCF_Customer_Details();
