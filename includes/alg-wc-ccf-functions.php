<?php
/**
 * Custom Checkout Fields for WooCommerce - Functions
 *
 * @version 1.8.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alg_wc_ccf_get_option' ) ) {
	/**
	 * alg_wc_ccf_get_option.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_ccf_get_option( $option, $default = false ) {
		return get_option( ALG_WC_CCF_ID . '_' . $option, $default );
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_field_option' ) ) {
	/**
	 * alg_wc_ccf_get_field_option.
	 *
	 * @version 1.6.1
	 * @since   1.0.0
	 *
	 * @todo    (dev) add more fields to `do_shortcode()`, e.g. `type_select_options`, `type_select_select2_i18n_no_matches` etc.?
	 */
	function alg_wc_ccf_get_field_option( $option, $field_nr, $default = false, $context = '' ) {
		$result = alg_wc_ccf_get_option( $option . '_' . $field_nr, $default );
		if ( in_array( $option, array(
			'label',
			'placeholder',
			'default',
			'description',
			'fee_title',
			'type_checkbox_yes',
			'type_checkbox_no',
			'type_datepicker_mindate',
			'type_datepicker_maxdate',
		) ) ) {
			$result = do_shortcode( $result );
		}
		return apply_filters( 'alg_wc_ccf_get_field_option', $result, $option, $field_nr, $default, $context );
	}
}

if ( ! function_exists( 'alg_wc_ccf_update_order_fields_data' ) ) {
	/*
	 * alg_wc_ccf_update_order_fields_data.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 *
	 * @return  array
	 */
	function alg_wc_ccf_update_order_fields_data( $order_id, $fields_data ) {
		if ( ( $order = wc_get_order( $order_id ) ) ) {
			$order->update_meta_data( '_' . ALG_WC_CCF_ID . '_data', $fields_data );
			$order->save();
		}
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_order_fields_data' ) ) {
	/*
	 * alg_wc_ccf_get_order_fields_data.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 *
	 * @return  array
	 */
	function alg_wc_ccf_get_order_fields_data( $order_id ) {
		return ( ( $order = wc_get_order( $order_id ) ) ? $order->get_meta( '_' . ALG_WC_CCF_ID . '_data' ) : '' );
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_select_options' ) ) {
	/*
	 * alg_wc_ccf_get_select_options.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  array
	 */
	function alg_wc_ccf_get_select_options( $select_options_raw, $do_sanitize_keys = false ) {
		if ( '' === $select_options_raw ) {
			return array();
		}
		$select_options_raw = array_map( 'trim', explode( PHP_EOL, $select_options_raw ) );
		$select_options = array();
		foreach ( $select_options_raw as $select_options_title ) {
			$select_options_key = ( $do_sanitize_keys ? sanitize_title( $select_options_title ) : $select_options_title );
			$select_options[ $select_options_key ] = $select_options_title;
		}
		return $select_options;
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_product_terms' ) ) {
	/**
	 * alg_wc_ccf_get_product_terms.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_ccf_get_product_terms( $taxonomy = 'product_cat' ) {
		$product_terms  = array();
		$_product_terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
		if ( ! empty( $_product_terms ) && ! is_wp_error( $_product_terms ) ){
			foreach ( $_product_terms as $_product_term ) {
				$product_terms[ $_product_term->term_id ] = $_product_term->name;
			}
		}
		return $product_terms;
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_products' ) ) {
	/**
	 * alg_wc_ccf_get_products.
	 *
	 * @version 1.6.5
	 * @since   1.0.0
	 *
	 * @todo    (feature) `product_variation`: make it optional? (also in AJAX replace `woocommerce_json_search_products_and_variations` with `woocommerce_json_search_products`)
	 */
	function alg_wc_ccf_get_products( $products = array(), $post_status = 'any' ) {
		$offset     = 0;
		$block_size = 1024;
		while ( true ) {
			$args = array(
				'post_type'      => array( 'product', 'product_variation' ),
				'post_status'    => $post_status,
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products[ $post_id ] = get_the_title( $post_id ) . ' [ID:' . $post_id . ']';
			}
			$offset += $block_size;
		}
		return $products;
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_shipping_classes' ) ) {
	/**
	 * alg_wc_ccf_get_shipping_classes.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function alg_wc_ccf_get_shipping_classes() {
		$shipping_classes = array();
		if ( class_exists( 'WC_Shipping' ) ) {
			$wc_shipping              = WC_Shipping::instance();
			$shipping_classes_terms   = $wc_shipping->get_shipping_classes();
			$shipping_classes         = array( -1 => __( 'No shipping class', 'woocommerce' ) );
			foreach ( $shipping_classes_terms as $shipping_classes_term ) {
				$shipping_classes[ $shipping_classes_term->term_id ] = $shipping_classes_term->name;
			}
		}
		return $shipping_classes;
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_user_roles' ) ) {
	/**
	 * alg_wc_ccf_get_user_roles.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_ccf_get_user_roles() {
		global $wp_roles;
		$all_roles = ( isset( $wp_roles ) && is_object( $wp_roles ) ) ? $wp_roles->roles : array();
		$all_roles = apply_filters( 'editable_roles', $all_roles );
		$all_roles = array_merge( array(
			'guest' => array(
				'name'         => __( 'Guest', 'custom-checkout-fields-for-woocommerce' ),
				'capabilities' => array(),
			) ), $all_roles );
		$all_roles_options = array();
		foreach ( $all_roles as $_role_key => $_role ) {
			$all_roles_options[ $_role_key ] = $_role['name'];
		}
		return $all_roles_options;
	}
}

if ( ! function_exists( 'alg_wc_ccf_is_user_role' ) ) {
	/**
	 * alg_wc_ccf_is_user_role.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  bool
	 */
	function alg_wc_ccf_is_user_role( $user_roles, $user_id = 0 ) {
		$_user = ( 0 == $user_id ? wp_get_current_user() : get_user_by( 'id', $user_id ) );
		if ( ! isset( $_user->roles ) || empty( $_user->roles ) ) {
			$_user->roles = array( 'guest' );
		}
		if ( ! is_array( $_user->roles ) ) {
			return false;
		}
		if ( is_array( $user_roles ) ) {
			if ( in_array( 'administrator', $user_roles ) ) {
				$user_roles[] = 'super_admin';
			}
			$_intersect = array_intersect( $user_roles, $_user->roles );
			return ( ! empty( $_intersect ) );
		} else {
			return ( 'administrator' == $user_roles ?
				( in_array( 'administrator', $_user->roles ) || in_array( 'super_admin', $_user->roles ) ) :
				( in_array( $user_roles, $_user->roles ) )
			);
		}
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_default_date_format' ) ) {
	/**
	 * alg_wc_ccf_get_default_date_format.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_ccf_get_default_date_format() {
		return get_option( 'date_format', 'F j, Y' );
	}
}

if ( ! function_exists( 'alg_wc_ccf_date_format_php_to_js' ) ) {
	/*
	 * alg_wc_ccf_date_format_php_to_js.
	 *
	 * Matches each symbol of PHP date format standard with jQuery equivalent codeword.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @author  Tristan Jahier
	 *
	 * @see     http://stackoverflow.com/questions/16702398/convert-a-php-date-format-to-a-jqueryui-datepicker-date-format
	 *
	 * @todo    (dev) time
	 */
	function alg_wc_ccf_date_format_php_to_js( $php_format ) {
		$symbols_matching = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
		);
		$jqueryui_format = "";
		$escaping = false;
		for ( $i = 0; $i < strlen( $php_format ); $i++ ) {
			$char = $php_format[ $i ];
			if ( $char === '\\' ) { // PHP date format escaping character
				$i++;
				$jqueryui_format .= ( $escaping ) ? $php_format[ $i ] : '\'' . $php_format[ $i ];
				$escaping = true;
			} else {
				if ( $escaping ) {
					$jqueryui_format .= "'";
					$escaping = false;
				}
				$jqueryui_format .= ( isset( $symbols_matching[ $char ] ) ) ? $symbols_matching[ $char ] : $char;
			}
		}
		return $jqueryui_format;
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_select2_i18n_options' ) ) {
	/**
	 * alg_wc_ccf_get_select2_i18n_options.
	 *
	 * @version 1.4.1
	 * @since   1.4.1
	 *
	 * @todo    (dev) `i18n_ajax_error` (JS: `errorLoading`)
	 * @todo    (dev) `i18n_selection_too_long_1`, `i18n_selection_too_long_n` (JS: `maximumSelected`) (for multiselect)
	 */
	function alg_wc_ccf_get_select2_i18n_options() {
		return array(
			'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'custom-checkout-fields-for-woocommerce' ),
		);
	}
}

if ( ! function_exists( 'alg_wc_ccf_get_datepicker_timepicker_addon_i18n_options' ) ) {
	/**
	 * alg_wc_ccf_get_datepicker_timepicker_addon_i18n_options.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 *
	 * @see     https://trentrichardson.com/examples/timepicker/#tp-options
	 *
	 * @todo    (dev) add more options
	 */
	function alg_wc_ccf_get_datepicker_timepicker_addon_i18n_options() {
		return array(
			'i18n_current' => __( 'Now', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_close'   => __( 'Done', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_time'    => __( 'Time', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_hour'    => __( 'Hour', 'custom-checkout-fields-for-woocommerce' ),
			'i18n_minute'  => __( 'Minute', 'custom-checkout-fields-for-woocommerce' ),
		);
	}
}
