<?php
/**
 * Custom Checkout Fields for WooCommerce - General Section Settings
 *
 * @version 1.6.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Settings_General' ) ) :

class Alg_WC_CCF_Settings_General extends Alg_WC_CCF_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'custom-checkout-fields-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * placeholders_desc.
	 *
	 * @version 1.5.1
	 * @since   1.0.0
	 */
	function placeholders_desc( $values ) {
		return sprintf( __( 'Placeholders: %s', 'custom-checkout-fields-for-woocommerce' ), '<code>' . implode( '</code>, <code>', $values ) . '</code>' );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.6.3
	 * @since   1.0.0
	 *
	 * @todo    (desc) move everything except `enabled` and `total_number` to a separate settings section, e.g. "Advanced"?
	 * @todo    (desc) `key`
	 * @todo    (dev) `hide_unrelated_type_options`: default to `yes`
	 * @todo    (desc) Label template for duplicated fields: better descriptions
	 * @todo    (desc) `total_number`: better title?
	 * @todo    (feature) add optional different fields settings view (i.e. by option types, instead of by fields)
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Custom Checkout Fields Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'options',
			),
			array(
				'title'    => __( 'Custom Checkout Fields', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'custom-checkout-fields-for-woocommerce' ) . '</strong>',
				'id'       => 'enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Custom fields number', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'Click "%s" after you set this number - new settings sections will be displayed for each field.', 'custom-checkout-fields-for-woocommerce' ),
						'<strong>' . __( 'Save changes', 'custom-checkout-fields-for-woocommerce' ) . '</strong>' ) .
					apply_filters( 'alg_wc_ccf_settings', '<br>' . sprintf( 'You will need %s plugin to add more than one custom field.',
						'<a target="_blank" href="https://wpfactory.com/item/custom-checkout-fields-for-woocommerce/">' . 'Custom Checkout Fields for WooCommerce Pro' . '</a>' ) ),
				'id'       => 'total_number',
				'default'  => 1,
				'type'     => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_ccf_settings', array( 'readonly' => 'readonly' ), 'atts' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'options',
			),
			array(
				'title'    => __( 'General Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'general_options',
			),
			array(
				'title'    => __( 'Add all fields to admin emails', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Add', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'email_all_to_admin',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Add all fields to customers emails', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Add', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'email_all_to_customer',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Before the fields', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'emails_template_before',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'desc'     => __( 'Each field', 'custom-checkout-fields-for-woocommerce' ) . '. ' . $this->placeholders_desc( array( '%label%', '%value%' ) ),
				'id'       => 'emails_template_field',
				'default'  => '<p><strong>%label%:</strong> %value%</p>',
				'type'     => 'textarea',
			),
			array(
				'desc'     => __( 'After the fields', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'emails_template_after',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'title'    => __( 'Add all fields to "Order Received" (i.e. "Thank You") and "View Order" pages', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Add', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'add_to_order_received',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Before the fields', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'order_received_template_before',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'desc'     => __( 'Each field', 'custom-checkout-fields-for-woocommerce' ) . '. ' . $this->placeholders_desc( array( '%label%', '%value%' ) ),
				'id'       => 'order_received_template_field',
				'default'  => '<p><strong>%label%:</strong> %value%</p>',
				'type'     => 'textarea',
			),
			array(
				'desc'     => __( 'After the fields', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'order_received_template_after',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'general_options',
			),
			array(
				'title'    => __( 'Advanced Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'advanced_options',
			),
			array(
				'title'    => __( 'Force fields sort by priority', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you are having theme related issues with "Priority (i.e. order)" options.', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'force_sort_by_priority',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Label template for duplicated fields', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'For each product in cart', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'text',
				'id'       => 'duplicate_label_template_each_product',
				'default'  => '%product_title%',
			),
			array(
				'desc'     => __( 'For each product item in cart', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'text',
				'id'       => 'duplicate_label_template_each_product_item',
				'default'  => '%product_title% (%item_nr%)',
			),
			array(
				'desc'     => __( 'Glue', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'text',
				'id'       => 'duplicate_label_glue',
				'default'  => ': ',
				'alg_wc_ccf_sanitize' => 'wp_kses_post',
			),
			array(
				'title'    => __( 'Hide unrelated type options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'hide_unrelated_type_options',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Fields ID', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'Will be used in all fields as the middle part of ID, wrapped in field section and number, e.g. %s.', 'custom-checkout-fields-for-woocommerce' ),
					'<code>_billing_<span style="color:black;">' . ALG_WC_CCF_KEY . '</span>_1</code>' ),
				'type'     => 'text',
				'id'       => 'key',
				'default'  => 'alg_wc_checkout_field',
				'alg_wc_ccf_sanitize' => 'sanitize_key',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'advanced_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_CCF_Settings_General();
