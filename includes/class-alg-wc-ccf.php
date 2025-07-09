<?php
/**
 * Custom Checkout Fields for WooCommerce - Main Class
 *
 * @version 1.9.2
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
	 * Main Alg_WC_CCF Instance.
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
	 * @version 1.9.0
	 * @since   1.0.0
	 *
	 * @access  public
	 *
	 * @todo    (desc) update readme, e.g., "duplicate", etc.
	 * @todo    (feature) customizable `ALG_WC_CCF_ID`?
	 * @todo    (feature) settings reset (options and/or order meta)
	 * @todo    (dev) text domain: remove 'woocommerce' *everywhere*
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Load libs
		if ( is_admin() ) {
			require_once plugin_dir_path( ALG_WC_CCF_FILE ) . 'vendor/autoload.php';
		}

		// Constants
		defined( 'ALG_WC_CCF_ID' )  || define( 'ALG_WC_CCF_ID',  'alg_wc_ccf' );
		defined( 'ALG_WC_CCF_KEY' ) || define( 'ALG_WC_CCF_KEY', sanitize_key( get_option( 'alg_wc_ccf_key', 'alg_wc_checkout_field' ) ) );

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'custom-checkout-fields-for-woocommerce-pro.php' === basename( ALG_WC_CCF_FILE ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-ccf-pro.php';
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
		load_plugin_textdomain(
			'custom-checkout-fields-for-woocommerce',
			false,
			dirname( plugin_basename( ALG_WC_CCF_FILE ) ) . '/langs/'
		);
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 *
	 * @see     https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = (
				defined( 'ALG_WC_CCF_FILE_FREE' ) ?
				array( ALG_WC_CCF_FILE, ALG_WC_CCF_FILE_FREE ) :
				array( ALG_WC_CCF_FILE )
			);
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					$file,
					true
				);
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function includes() {
		// Functions
		require_once plugin_dir_path( __FILE__ ) . 'alg-wc-ccf-functions.php';
		// Core
		if ( 'yes' === alg_wc_ccf_get_option( 'enabled', 'yes' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-frontend.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-scripts.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-customer-details.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-order-details.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-shortcodes.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccf-compatibility.php';
		}
	}

	/**
	 * admin.
	 *
	 * @version 1.9.0
	 * @since   1.1.0
	 */
	function admin() {

		// Action links
		add_filter(
			'plugin_action_links_' . plugin_basename( ALG_WC_CCF_FILE ),
			array( $this, 'action_links' )
		);

		// "Recommendations" page
		add_action( 'init', array( $this, 'add_cross_selling_library' ) );

		// WC Settings tab as WPFactory submenu item
		add_action( 'init', array( $this, 'move_wc_settings_tab_to_wpfactory_menu' ) );

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
	 * @version 1.9.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();

		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . ALG_WC_CCF_ID ) . '">' .
			__( 'Settings', 'custom-checkout-fields-for-woocommerce' ) .
		'</a>';

		if ( 'custom-checkout-fields-for-woocommerce.php' === basename( ALG_WC_CCF_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/custom-checkout-fields-for-woocommerce/">' .
				__( 'Go Pro', 'custom-checkout-fields-for-woocommerce' ) .
			'</a>';
		}

		return array_merge( $custom_links, $links );
	}

	/**
	 * add_cross_selling_library.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function add_cross_selling_library() {

		if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
			return;
		}

		$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
		$cross_selling->setup( array( 'plugin_file_path' => ALG_WC_CCF_FILE ) );
		$cross_selling->init();

	}

	/**
	 * move_wc_settings_tab_to_wpfactory_menu.
	 *
	 * @version 1.9.2
	 * @since   1.9.0
	 */
	function move_wc_settings_tab_to_wpfactory_menu() {

		if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
			return;
		}

		$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

		if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
			return;
		}

		$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu( array(
			'wc_settings_tab_id' => ALG_WC_CCF_ID,
			'menu_title'         => __( 'Custom Checkout Fields', 'custom-checkout-fields-for-woocommerce' ),
			'page_title'         => __( 'WooCommerce Additional Checkout Fields Customizer', 'custom-checkout-fields-for-woocommerce' ),
			'plugin_icon'        => array(
				'get_url_method'    => 'wporg_plugins_api',
				'wporg_plugin_slug' => 'custom-checkout-fields-for-woocommerce',
			),
		) );

	}

	/**
	 * Add Custom Checkout Fields settings tab to WooCommerce settings.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-settings-ccf.php';
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
