<?php
/*
Plugin Name: Custom Checkout Fields for WooCommerce
Plugin URI: https://wpfactory.com/item/custom-checkout-fields-for-woocommerce/
Description: Add custom fields to WooCommerce checkout page.
Version: 1.8.3
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: custom-checkout-fields-for-woocommerce
Domain Path: /langs
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'custom-checkout-fields-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	$plugin = 'custom-checkout-fields-for-woocommerce-pro/custom-checkout-fields-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_CCF_FILE_FREE' ) || define( 'ALG_WC_CCF_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_CCF_VERSION' ) || define( 'ALG_WC_CCF_VERSION', '1.8.3' );

defined( 'ALG_WC_CCF_FILE' ) || define( 'ALG_WC_CCF_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-ccf.php' );

if ( ! function_exists( 'alg_wc_custom_checkout_fields' ) ) {
	/**
	 * Returns the main instance of Alg_WC_CCF to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_custom_checkout_fields() {
		return Alg_WC_CCF::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_custom_checkout_fields' );
