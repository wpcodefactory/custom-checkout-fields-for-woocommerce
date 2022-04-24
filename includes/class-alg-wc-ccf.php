<?php
/**
 * Custom Checkout Fields for WooCommerce - Main Class
 *
 * @version 1.6.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF' ) ) :

final class Alg_WC_CCF {

	/**
	 * @var   Alg_WC_CCF The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_CCF Instance
	 *
	 * Ensures only one instance of Alg_WC_CCF is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_CCF - Main instance
	 */
	static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_CCF Constructor.
	 *
	 * @version 1.6.3
	 * @since   1.0.0
	 *
	 * @access  public
	 *
	 * @todo    [now] (desc) update readme, e.g. "duplicate", etc.
	 * @todo    [now] (feature) customizable `ALG_WC_CCF_ID`?
	 * @todo    [later] (feature) settings reset (options and/or order meta)
	 * @todo    [maybe] (dev) text domain: remove 'woocommerce' *everywhere*
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Constants
		defined( 'ALG_WC_CCF_ID' )  || define( 'ALG_WC_CCF_ID',  'alg_wc_ccf' );
		defined( 'ALG_WC_CCF_KEY' ) || define( 'ALG_WC_CCF_KEY', sanitize_key( get_option( 'alg_wc_ccf_key', 'alg_wc_checkout_field' ) ) );

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'custom-checkout-fields-for-woocommerce-pro.php' === basename( ALG_WC_CCF_FILE ) ) {
			require_once( 'pro/class-alg-wc-ccf-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * localize.
	 *
	 * @version 1.6.0
	 * @since   1.5.0
	 */
	function localize() {
		load_plugin_textdomain( 'custom-checkout-fields-for-woocommerce', false, dirname( plugin_basename( ALG_WC_CCF_FILE ) ) . '/langs/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function includes() {
		// Functions
		require_once( 'alg-wc-ccf-functions.php' );
		// Core
		if ( 'yes' === alg_wc_ccf_get_option( 'enabled', 'yes' ) ) {
			require_once( 'class-alg-wc-ccf-frontend.php' );
			require_once( 'class-alg-wc-ccf-scripts.php' );
			require_once( 'class-alg-wc-ccf-customer-details.php' );
			require_once( 'class-alg-wc-ccf-order-details.php' );
			require_once( 'class-alg-wc-ccf-shortcodes.php' );
			require_once( 'class-alg-wc-ccf-compatibility.php' );
		}
	}

	/**
	 * admin.
	 *
	 * @version 1.6.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_CCF_FILE ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( alg_wc_ccf_get_option( 'version', '' ) !== ALG_WC_CCF_VERSION ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . ALG_WC_CCF_ID ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'custom-checkout-fields-for-woocommerce.php' === basename( ALG_WC_CCF_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/custom-checkout-fields-for-woocommerce/">' .
				__( 'Go Pro', 'custom-checkout-fields-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Custom Checkout Fields settings tab to WooCommerce settings.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'settings/class-alg-wc-settings-ccf.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.4.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( ALG_WC_CCF_ID . '_' . 'version', ALG_WC_CCF_VERSION );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_CCF_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_CCF_FILE ) );
	}

}

endif;
