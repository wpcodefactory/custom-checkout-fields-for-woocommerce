<?php
/**
 * Custom Checkout Fields for WooCommerce - Order Details Class
 *
 * @version 1.8.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Order_Details' ) ) :

class Alg_WC_CCF_Order_Details {

	/**
	 * is_wc_version_below_3_0_0.
	 *
	 * @version 1.8.1
	 * @since   1.0.0
	 */
	public $is_wc_version_below_3_0_0;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'woocommerce_admin_billing_fields',                    array( $this, 'add_custom_billing_fields_to_admin_order_display' ), PHP_INT_MAX );
		add_action( 'woocommerce_admin_shipping_fields',                   array( $this, 'add_custom_shipping_fields_to_admin_order_display' ), PHP_INT_MAX );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'add_custom_order_and_account_fields_to_admin_order_display' ), PHP_INT_MAX );
		add_action( 'woocommerce_email_after_order_table',                 array( $this, 'add_custom_fields_to_emails' ), PHP_INT_MAX, 2 );
		if ( 'yes' === alg_wc_ccf_get_option( 'add_to_order_received', 'yes' ) ) {
			add_action( 'woocommerce_order_details_after_order_table',     array( $this, 'add_custom_fields_to_view_order_and_thankyou_pages' ), PHP_INT_MAX );
		}
	}

	/**
	 * get_order_id.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_order_id( $_order ) {
		if ( ! $_order || ! is_object( $_order ) ) {
			return 0;
		}
		if ( ! isset( $this->is_wc_version_below_3_0_0 ) ) {
			$this->is_wc_version_below_3_0_0 = version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );
		}
		return ( $this->is_wc_version_below_3_0_0 ? $_order->id : $_order->get_id() );
	}

	/**
	 * add_custom_fields_to_order_display.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `if ( '' != $field_data['label'] || '' != $field_data['_value'] ) { ... }`?
	 */
	function add_custom_fields_to_order_display( $order, $section = '', $templates = array( 'before' => '', 'field' => '', 'after' => '' ) ) {
		if ( ! ( $fields_data = alg_wc_ccf_get_order_fields_data( $this->get_order_id( $order ) ) ) ) {
			return;
		}
		$html = '';
		foreach ( $fields_data as $field_data ) {
			if ( '' != $section && $field_data['section'] != $section ) {
				continue;
			}
			if ( '' != $field_data['label'] || '' != $field_data['_value'] ) {
				$replaced_values = array(
					'%label%' => $field_data['label'] .
						( ! empty( $field_data['_label_suffix'] ) ? alg_wc_ccf_get_option( 'duplicate_label_glue', ': ' ) . $field_data['_label_suffix'] : '' ),
					'%value%' => ( is_array( $field_data['_value'] ) ? implode( ', ', $field_data['_value'] ) : $field_data['_value'] ),
				);
				$html .= str_replace( array_keys( $replaced_values ), $replaced_values, $templates['field'] );
			}
		}
		if ( '' != $html ) {
			echo $templates['before'] . $html . $templates['after'];
		}
	}

	/**
	 * add_woocommerce_admin_fields.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) `$fields[ $key ]['show'] = false;`
	 */
	function add_woocommerce_admin_fields( $fields, $section ) {
		if ( ! ( $order_id = get_the_ID() ) ) {
			return $fields;
		}
		if ( ! ( $fields_data = alg_wc_ccf_get_order_fields_data( $order_id ) ) ) {
			return $fields;
		}
		foreach ( $fields_data as $field_data ) {
			if ( $field_data['section'] != $section ) {
				continue;
			}
			$options = '';
			switch ( $field_data['type'] ) {
				case 'select':
				case 'multiselect':
				case 'radio':
					$type    = 'select';
					$class   = 'first';
					$options = alg_wc_ccf_get_select_options( $field_data['type_select_options'] );
					break;
				case 'country':
					$type    = 'select';
					$class   = 'js_field-country select short';
					$options = WC()->countries->get_allowed_countries();
					break;
				case 'checkbox':
					$type    = 'select';
					$class   = 'first';
					$options = array(
						$field_data['type_checkbox_no']  => $field_data['type_checkbox_no'],
						$field_data['type_checkbox_yes'] => $field_data['type_checkbox_yes'],
					);
					break;
				default:
					$type    = 'text';
					$class   = 'short';
					break;
			}
			$key = $field_data['_key'] . '_' . $field_data['_field_nr'] . ( ! empty( $field_data['_key_suffix'] ) ? '_' . $field_data['_key_suffix'] : '' );
			$fields[ $key ] = array(
				'type'          => $type,
				'label'         => strip_tags( $field_data['label'] ) .
					( ! empty( $field_data['_label_suffix'] ) ? alg_wc_ccf_get_option( 'duplicate_label_glue', ': ' ) . $field_data['_label_suffix'] : '' ),
				'show'          => true,
				'class'         => $class,
				'wrapper_class' => 'form-field-wide',
			);
			if ( 'multiselect' === $field_data['type'] ) {
				$fields[ $key ]['name']              = $field_data['_value_meta_key'] . '[]';
				$fields[ $key ]['show']              = false;
				$fields[ $key ]['custom_attributes'] = array( 'multiple' => 'multiple' );
			}
			if ( ! empty( $options ) ) {
				$fields[ $key ]['options'] = $options;
			}
		}
		return $fields;
	}

	/**
	 * add_custom_billing_fields_to_admin_order_display.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_billing_fields_to_admin_order_display( $fields ) {
		return $this->add_woocommerce_admin_fields( $fields, 'billing' );
	}

	/**
	 * add_custom_shipping_fields_to_admin_order_display.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_shipping_fields_to_admin_order_display( $fields ) {
		return $this->add_woocommerce_admin_fields( $fields, 'shipping' );
	}

	/**
	 * add_custom_order_and_account_fields_to_admin_order_display
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) somehow use `$this->add_woocommerce_admin_fields()` instead of `$this->add_custom_fields_to_order_display()` (otherwise these fields are not editable)
	 */
	function add_custom_order_and_account_fields_to_admin_order_display( $order ) {
		$templates = array(
			'before' => '<div class="clear"></div><p>',
			'field'  => '<strong>%label%: </strong>%value%<br>',
			'after'  => '</p>',
		);
		$this->add_custom_fields_to_order_display( $order, 'order',   $templates );
		$this->add_custom_fields_to_order_display( $order, 'account', $templates );
	}

	/**
	 * add_custom_fields_to_emails.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_fields_to_emails( $order, $sent_to_admin ) {
		if (
			(   $sent_to_admin && 'yes' === alg_wc_ccf_get_option( 'email_all_to_admin', 'yes' ) ) ||
			( ! $sent_to_admin && 'yes' === alg_wc_ccf_get_option( 'email_all_to_customer', 'yes' ) )
		) {
			$templates = array(
				'before' => alg_wc_ccf_get_option( 'emails_template_before', '' ),
				'field'  => alg_wc_ccf_get_option( 'emails_template_field', '<p><strong>%label%:</strong> %value%</p>' ),
				'after'  => alg_wc_ccf_get_option( 'emails_template_after', '' ),
			);
			$this->add_custom_fields_to_order_display( $order, '', $templates );
		}
	}

	/**
	 * add_custom_fields_to_view_order_and_thankyou_pages.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_fields_to_view_order_and_thankyou_pages( $order ) {
		$templates = array(
			'before' => alg_wc_ccf_get_option( 'order_received_template_before', '' ),
			'field'  => alg_wc_ccf_get_option( 'order_received_template_field', '<p><strong>%label%:</strong> %value%</p>' ),
			'after'  => alg_wc_ccf_get_option( 'order_received_template_after', '' ),
		);
		$this->add_custom_fields_to_order_display( $order, '', $templates );
	}

}

endif;

return new Alg_WC_CCF_Order_Details();
