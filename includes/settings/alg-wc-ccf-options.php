<?php
/**
 * Custom Checkout Fields for WooCommerce - Options
 *
 * @version 1.8.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alg_get_wc_ccf_options' ) ) {
	/**
	 * alg_get_wc_ccf_options.
	 *
	 * @version 1.8.3
	 * @since   1.0.0
	 *
	 * @todo    (dev) `Select2`: `radio` (move it to a separate subsection, e.g. "Select2 Options")?
	 * @todo    (dev) `$products`: always use AJAX (i.e. not only on `WC()->version, '5.7.1', '>='`)
	 * @todo    (feature) `type`: non-editable static text?
	 * @todo    (desc) `$section_message`
	 * @todo    (dev) `placeholder`: make `text` (now is `textarea`)?
	 * @todo    (desc) `default_prepopulate`: better title and desc
	 * @todo    (desc) `customer_meta_fields`: better desc?
	 * @todo    (dev) add new "Localization" section?
	 * @todo    (dev) restyle: e.g. select2: from desc to title?
	 * @todo    (dev) store all in array, e.g. `alg_wc_ccf_1[type]` instead of `alg_wc_ccf_type_1` || move from options to custom post types
	 * @todo    (dev) type: `datalist` (https://www.w3schools.com/tags/tag_datalist.asp)
	 * @todo    (dev) editable `select` option with jquery (https://stackoverflow.com/questions/5650457/html-select-form-with-option-to-enter-custom-value) (i.e. instead of `is_tagging` in `select2` (https://select2.org/tagging))?
	 * @todo    (feature) Visibility Options: By another field: predefined fields
	 * @todo    (feature) Visibility Options: By another field: multiple fields
	 * @todo    (dev) Select2: Custom text: better descriptions
	 * @todo    (desc) `priority`: better description (i.e. list more current (core) fields priorities)
	 * @todo    (dev) `priority`: `'custom_attributes' => array( 'min' => 0 )`?
	 * @todo    (dev) standard HTML date/time picker(s)
	 * @todo    (feature) (important) Visibility Options - Categories, Tags, Products - comma separated IDs instead of multiselect
	 * @todo    (feature) `pattern` attribute (https://www.w3schools.com/tags/att_input_pattern.asp)
	 * @todo    (feature) (WC) `validate`
	 * @todo    (feature) Visibility Options - by payment gateways
	 * @todo    (feature) Visibility Options - by shipping method
	 * @todo    (feature) Visibility Options - by users (i.e. not user roles)
	 * @todo    (feature) Visibility Options - ... to *hide*
	 */
	function alg_get_wc_ccf_options() {

		$section_message    = ( 'yes' === alg_wc_ccf_get_option( 'hide_unrelated_type_options', 'no' ) ?
			__( 'For %s field type.', 'custom-checkout-fields-for-woocommerce' ) :
			__( 'Fill this section only if %s type is selected.', 'custom-checkout-fields-for-woocommerce' ) );
		$price_step         = ( 0 == wc_get_price_decimals() ? 1 : str_pad( '0.', 1 + wc_get_price_decimals(), '0' ) . '1' );
		$product_cats       = alg_wc_ccf_get_product_terms( 'product_cat' );
		$product_tags       = alg_wc_ccf_get_product_terms( 'product_tag' );
		$products           = ( ! empty( WC()->version ) && version_compare( WC()->version, '5.7.1', '>=' ) ? false : alg_wc_ccf_get_products() );
		$user_roles         = alg_wc_ccf_get_user_roles();
		$shipping_classes   = alg_wc_ccf_get_shipping_classes();

		$options = array(

			// General
			array(
				'title'    => __( 'Field', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => '<em>' . __( 'Meta key:', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'field_general_options',
			),
			array(
				'title'    => __( 'Enable/disable field', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable', 'custom-checkout-fields-for-woocommerce' ) . '</strong>',
				'id'       => 'enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Type', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type',
				'default'  => 'text',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'text'        => __( 'Text', 'custom-checkout-fields-for-woocommerce' ),
					'textarea'    => __( 'Textarea', 'custom-checkout-fields-for-woocommerce' ),
					'number'      => __( 'Number', 'custom-checkout-fields-for-woocommerce' ),
					'checkbox'    => __( 'Checkbox', 'custom-checkout-fields-for-woocommerce' ),
					'datepicker'  => __( 'Datepicker', 'custom-checkout-fields-for-woocommerce' ),
					'weekpicker'  => __( 'Weekpicker', 'custom-checkout-fields-for-woocommerce' ),
					'timepicker'  => __( 'Timepicker', 'custom-checkout-fields-for-woocommerce' ),
					'select'      => __( 'Select', 'custom-checkout-fields-for-woocommerce' ),
					'multiselect' => __( 'Multiselect', 'custom-checkout-fields-for-woocommerce' ),
					'radio'       => __( 'Radio', 'custom-checkout-fields-for-woocommerce' ),
					'password'    => __( 'Password', 'custom-checkout-fields-for-woocommerce' ),
					'country'     => __( 'Country', 'custom-checkout-fields-for-woocommerce' ),
					'state'       => __( 'State', 'custom-checkout-fields-for-woocommerce' ),
					'email'       => __( 'Email', 'custom-checkout-fields-for-woocommerce' ),
					'tel'         => __( 'Phone', 'custom-checkout-fields-for-woocommerce' ),
					'color'       => __( 'Color', 'custom-checkout-fields-for-woocommerce' ),
					'search'      => __( 'Search', 'custom-checkout-fields-for-woocommerce' ),
					'url'         => __( 'URL', 'custom-checkout-fields-for-woocommerce' ),
					'range'       => __( 'Range', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Duplicate', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'duplicate',
				'default'  => 'no',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'no'                => __( 'Do not duplicate', 'custom-checkout-fields-for-woocommerce' ),
					'each_product'      => __( 'For each product in cart', 'custom-checkout-fields-for-woocommerce' ),
					'each_product_item' => __( 'For each product item in cart', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Label', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'label',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'title'    => __( 'Placeholder', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'placeholder',
				'default'  => '',
				'type'     => 'textarea',
			),
			array(
				'title'    => __( 'Default value', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Enter default field value here. Use 1 or 0 for the checkbox type.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'default',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Prepopulate default value', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'For logged in customers, pull data from their account.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					sprintf( __( 'Ignored for the "%s" type.', 'custom-checkout-fields-for-woocommerce' ), __( 'Multiselect', 'custom-checkout-fields-for-woocommerce' ) ),
				'desc'     => __( 'Enable', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'default_prepopulate',
				'type'     => 'checkbox',
				'default'  => 'yes',
			),
			array(
				'title'    => __( 'Description', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'description',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Required', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Required', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'required',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'User profile', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Add', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Adds the field to user profile pages.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					sprintf( __( 'Ignored for the "%s" type.', 'custom-checkout-fields-for-woocommerce' ), __( 'Multiselect', 'custom-checkout-fields-for-woocommerce' ) ),
				'id'       => 'customer_meta_fields',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_general_options',
			),

			// Position
			array(
				'title'    => __( 'Position Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'field_position_options',
			),
			array(
				'title'    => __( 'Section', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'section',
				'default'  => 'billing',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'billing'  => __( 'Billing', 'custom-checkout-fields-for-woocommerce' ),
					'shipping' => __( 'Shipping', 'custom-checkout-fields-for-woocommerce' ),
					'account'  => __( 'Account', 'custom-checkout-fields-for-woocommerce' ),
					'order'    => __( 'Order notes', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Priority (i.e. order)', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Sets field position in the section.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'For example, "First name" field priority is 10, "Email address" field priority is 110.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'priority',
				'default'  => 200,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_position_options',
			),

			// Select/multiselect/radio
			array(
				'title'    => __( 'Select/Multiselect/Radio Type Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( $section_message, '<strong>' . __( 'select/multiselect/radio', 'custom-checkout-fields-for-woocommerce' ) . '</strong>' ),
				'type'     => 'title',
				'id'       => 'field_type_select_options',
			),
			array(
				'title'    => __( 'Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'One option per line', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_options',
				'default'  => '',
				'type'     => 'textarea',
				'css'      => 'height:150px;',
			),
			array(
				'title'    => __( 'Select/multiselect: "Select2"', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Use Select2', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_select2',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Select2: Min input', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Select2: Number of characters necessary to start a search.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Ignored if set to zero.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_select2_min_input',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'desc'     => __( 'Select2: Max input', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Select2: Maximum number of characters that can be entered for an input.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Ignored if set to zero.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_select2_max_input',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'desc'     => __( 'Select2: Text input by user', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'In addition to a prepopulated menu of options, Select2 can dynamically create new options from text input by the user in the search box.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_select2_is_tagging',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Select2: Custom text', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Custom text, e.g. translations.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_select_select2_is_i18n',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
		);
		foreach ( alg_wc_ccf_get_select2_i18n_options() as $i18n_key => $i18n_value ) {
			$options = array_merge( $options, array(
				array(
					'id'       => "type_select_select2_{$i18n_key}",
					'desc_tip' => str_replace( array( 'i18n_', '_' ), array( '', ' ' ), $i18n_key ),
					'default'  => $i18n_value,
					'type'     => 'text',
				),
			) );
		}
		$options = array_merge( $options, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'field_type_select_options',
			),

			// Checkbox
			array(
				'title'    => __( 'Checkbox Type Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( $section_message, '<strong>' . __( 'checkbox', 'custom-checkout-fields-for-woocommerce' ) . '</strong>' ),
				'type'     => 'title',
				'id'       => 'field_type_checkbox_options',
			),
			array(
				'title'    => __( 'Value for ON', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_checkbox_yes',
				'type'     => 'text',
				'default'  => __( 'Yes', 'custom-checkout-fields-for-woocommerce' ),
			),
			array(
				'title'    => __( 'Value for OFF', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_checkbox_no',
				'type'     => 'text',
				'default'  => __( 'No', 'custom-checkout-fields-for-woocommerce' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_type_checkbox_options',
			),

			// Datepicker/Weekpicker
			array(
				'title'    => __( 'Datepicker/Weekpicker Type Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( $section_message, '<strong>' . __( 'datepicker/weekpicker', 'custom-checkout-fields-for-woocommerce' ) . '</strong>' ),
				'type'     => 'title',
				'id'       => 'field_type_datepicker_options',
			),
			array(
				'title'    => __( 'Date format', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'Visit <a href="%s" target="_blank">documentation on date and time formatting</a> for valid date formats', 'custom-checkout-fields-for-woocommerce' ),
					'https://codex.wordpress.org/Formatting_Date_and_Time' ),
				'desc_tip' => __( 'Leave blank to use your current WordPress format', 'custom-checkout-fields-for-woocommerce' ) . ': ' . alg_wc_ccf_get_default_date_format(),
				'id'       => 'type_datepicker_format',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Min date', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'days', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Number of days.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'You can also use shortcodes here.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_mindate',
				'type'     => 'text',
				'default'  => -365,
			),
			array(
				'title'    => __( 'Max date', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'days', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Number of days.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'You can also use shortcodes here.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_maxdate',
				'type'     => 'text',
				'default'  => 365,
			),
			array(
				'title'    => __( 'Year selector', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Add', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_changeyear',
				'type'     => 'checkbox',
				'default'  => 'no',
			),
			array(
				'desc'     => __( 'Year selector: year range', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'The range of years displayed in the year drop-down: either relative to today\'s year ("-nn:+nn"), relative to the currently selected year ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these formats ("nnnn:-nn"). Note that this option only affects what appears in the drop-down, to restrict which dates may be selected use the "Min date" and/or "Max date" options.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_yearrange',
				'type'     => 'text',
				'default'  => 'c-10:c+10',
			),
			array(
				'title'    => __( 'First week day', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_firstday',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'default'  => 0,
				'options'  => array(
					__( 'Sunday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Monday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Tuesday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Wednesday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Thursday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Friday', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Saturday', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Datepicker', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Exclude days', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_excludedays',
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'default'  => array(),
				'options'  => array(
					'1' => __( 'Sunday', 'custom-checkout-fields-for-woocommerce' ),
					'2' => __( 'Monday', 'custom-checkout-fields-for-woocommerce' ),
					'3' => __( 'Tuesday', 'custom-checkout-fields-for-woocommerce' ),
					'4' => __( 'Wednesday', 'custom-checkout-fields-for-woocommerce' ),
					'5' => __( 'Thursday', 'custom-checkout-fields-for-woocommerce' ),
					'6' => __( 'Friday', 'custom-checkout-fields-for-woocommerce' ),
					'7' => __( 'Saturday', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Datepicker', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Exclude months', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_excludemonths',
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'default'  => array(),
				'options'  => array(
					'1'  => __( 'January', 'custom-checkout-fields-for-woocommerce' ),
					'2'  => __( 'February', 'custom-checkout-fields-for-woocommerce' ),
					'3'  => __( 'March', 'custom-checkout-fields-for-woocommerce' ),
					'4'  => __( 'April', 'custom-checkout-fields-for-woocommerce' ),
					'5'  => __( 'May', 'custom-checkout-fields-for-woocommerce' ),
					'6'  => __( 'June', 'custom-checkout-fields-for-woocommerce' ),
					'7'  => __( 'July', 'custom-checkout-fields-for-woocommerce' ),
					'8'  => __( 'August', 'custom-checkout-fields-for-woocommerce' ),
					'9'  => __( 'September', 'custom-checkout-fields-for-woocommerce' ),
					'10' => __( 'October', 'custom-checkout-fields-for-woocommerce' ),
					'11' => __( 'November', 'custom-checkout-fields-for-woocommerce' ),
					'12' => __( 'December', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Datepicker', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Exclude dates', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'In %s format, separated by comma. You can use wildcard (%s) in dates. E.g.: %s', 'custom-checkout-fields-for-woocommerce' ),
						'<code>YYYY-MM-DD</code>', '<code>*</code>', '<code>****-12-25,****-01-01</code>' ),
				'id'       => 'type_datepicker_excludedates',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Datepicker', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_timepicker_addon',
				'type'     => 'checkbox',
				'default'  => 'no',
			),
			array(
				'desc'     => __( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Time format', 'custom-checkout-fields-for-woocommerce' ) .
					'<br>' . sprintf( __( 'Visit <a href="%s" target="_blank">timepicker addon options page</a> for valid time formats', 'custom-checkout-fields-for-woocommerce' ),
						'https://trentrichardson.com/examples/timepicker/#tp-formatting' ),
				'desc_tip' => sprintf( __( 'Please note that time formatting here differs from the formatting in "%s" option.', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Timepicker Type Options', 'custom-checkout-fields-for-woocommerce' ) . ' > ' .
						__( 'Time format', 'custom-checkout-fields-for-woocommerce' ) ),
				'id'       => 'type_datepicker_timepicker_addon_timeformat',
				'type'     => 'text',
				'default'  => 'HH:mm',
			),
			array(
				'desc'     => __( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Min time', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'In %s format, e.g.: %s', 'custom-checkout-fields-for-woocommerce' ), 'HH:MM', '15:00' ),
				'id'       => 'type_datepicker_timepicker_addon_mintime',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'desc'     => __( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Max time', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'In %s format, e.g.: %s', 'custom-checkout-fields-for-woocommerce' ), 'HH:MM', '18:00' ),
				'id'       => 'type_datepicker_timepicker_addon_maxtime',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'desc'     => __( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Custom text', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Custom text, e.g. translations.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_datepicker_timepicker_addon_is_i18n',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
		) );
		foreach ( alg_wc_ccf_get_datepicker_timepicker_addon_i18n_options() as $i18n_key => $i18n_value ) {
			$options = array_merge( $options, array(
				array(
					'id'       => "type_datepicker_timepicker_addon_{$i18n_key}",
					'desc_tip' => str_replace( array( 'i18n_', '_' ), array( '', ' ' ), $i18n_key ),
					'default'  => $i18n_value,
					'type'     => 'text',
				),
			) );
		}
		$options = array_merge( $options, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'field_type_datepicker_options',
			),

			// Timepicker
			array(
				'title'    => __( 'Timepicker Type Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( $section_message, '<strong>' . __( 'timepicker', 'custom-checkout-fields-for-woocommerce' ) . '</strong>' ),
				'type'     => 'title',
				'id'       => 'field_type_timepicker_options',
			),
			array(
				'title'    => __( 'Time format', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'Visit <a href="%s" target="_blank">timepicker options page</a> for valid time formats', 'custom-checkout-fields-for-woocommerce' ),
					'http://timepicker.co/options/' ),
				'desc_tip' => sprintf( __( 'Please note that time formatting here differs from the formatting in "%s" option.', 'custom-checkout-fields-for-woocommerce' ),
					__( 'Datepicker/Weekpicker Type Options', 'custom-checkout-fields-for-woocommerce' ) . ' > ' .
						__( 'Timepicker addon', 'custom-checkout-fields-for-woocommerce' ) . ': ' . __( 'Time format', 'custom-checkout-fields-for-woocommerce' ) ),
				'id'       => 'type_timepicker_format',
				'type'     => 'text',
				'default'  => 'hh:mm p',
			),
			array(
				'title'    => __( 'Interval', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'minutes', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'type_timepicker_interval',
				'type'     => 'number',
				'default'  => 15,
			),
			array(
				'title'    => __( 'Min time', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'In %s format, e.g.: %s', 'custom-checkout-fields-for-woocommerce' ), '<code>HH:MM</code>', '<code>15:00</code>' ),
				'id'       => 'type_timepicker_mintime',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Max time', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'In %s format, e.g.: %s', 'custom-checkout-fields-for-woocommerce' ), '<code>HH:MM</code>', '<code>18:00</code>' ),
				'id'       => 'type_timepicker_maxtime',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_type_timepicker_options',
			),

			// Input
			array(
				'title'    => __( 'Input Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'input_options',
			),
			array(
				'title'    => __( 'Max length', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Maximum number of character for an input field. E.g. for <strong>Text</strong> type.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'maxlength',
				'default'  => 0,
				'type'     => 'number',
			),

			array(
				'title'    => __( 'Min value', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Minimum value for an input field. E.g. for <strong>Number/Range</strong> type.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Leave blank to disable.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'min',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Max value', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Maximum value for an input field. E.g. for <strong>Number/Range</strong> type.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Leave blank to disable.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'max',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Step', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Legal number intervals for an input field. E.g. for <strong>Number/Range</strong> type.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Leave blank to disable.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'step',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Autofocus', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'autofocus',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Autocomplete', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'autocomplete',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'input_options',
			),

			// Style
			array(
				'title'    => __( 'Style Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'field_style_options',
			),
			array(
				'title'    => __( 'Class', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'class',
				'default'  => 'form-row-wide',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'form-row-wide'  => __( 'Wide', 'custom-checkout-fields-for-woocommerce' ),
					'form-row-first' => __( 'First', 'custom-checkout-fields-for-woocommerce' ),
					'form-row-last'  => __( 'Last', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Label class', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'label_class',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Input class', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'input_class',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_style_options',
			),

			// Visibility
			array(
				'title'    => __( 'Visibility Options', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'field_visibility_options',
			),
			array(
				'title'    => __( 'Categories', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if there is a product of selected category in cart. Leave blank to show for all products.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'categories_in',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_cats,
			),
			array(
				'title'    => __( 'Tags', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if there is a product of selected tag in cart. Leave blank to show for all products.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'tags_in',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_tags,
			),
			array(
				'title'    => __( 'Products', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if there is a selected product in cart. Leave blank to show for all products.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'products_in',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => ( false === $products ? 'wc-product-search' : 'chosen_select' ),
				'options'  => ( false === $products ? array() : $products ),
				'custom_attributes' => ( false === $products ? array(
						'data-placeholder' => esc_attr__( 'Search for a product&hellip;', 'woocommerce' ),
						'data-action'      => 'woocommerce_json_search_products_and_variations',
					) : array() ),
			),
			array(
				'title'    => __( 'User roles', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if visitor has selected user role. Leave blank to show for all users.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'user_roles_in',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $user_roles,
			),
			array(
				'title'    => __( 'Min cart amount', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if cart total is at least this amount. Ignored if set to zero.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'min_cart_amount',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => $price_step ),
			),
			array(
				'title'    => __( 'Max cart amount', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if cart total is not more than this amount. Ignored if set to zero.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'max_cart_amount',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => $price_step ),
			),
			array(
				'desc'     => __( 'Add shipping cost to cart total', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Used for "Min cart amount" and "Max cart amount" options.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'cart_total_shipping',
				'type'     => 'checkbox',
				'default'  => 'yes',
			),
			array(
				'title'    => __( 'Product shipping classes', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Show this field only if there is a product with selected shipping classes in cart. Leave blank to show for all products.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'shipping_classes_in',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $shipping_classes,
			),
			array(
				'title'    => __( 'Virtual products', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'virtual_products',
				'default'  => '',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					''        => __( 'Always show the field', 'custom-checkout-fields-for-woocommerce' ),
					'require' => __( 'Show the field only if there are at least one virtual product in the cart', 'custom-checkout-fields-for-woocommerce' ),
					'exclude' => __( 'Hide the field if there are at least one virtual product in the cart', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Downloadable products', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'downloadable_products',
				'default'  => '',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					''        => __( 'Always show the field', 'custom-checkout-fields-for-woocommerce' ),
					'require' => __( 'Show the field only if there are at least one downloadable product in the cart', 'custom-checkout-fields-for-woocommerce' ),
					'exclude' => __( 'Hide the field if there are at least one downloadable product in the cart', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Countries', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => '<a href="#" class="button" id="alg-wc-ccf-select-all-countries">' . __( 'Select all countries', 'custom-checkout-fields-for-woocommerce' ) . '</a>' . ' ' .
					'<a href="#" class="button" id="alg-wc-ccf-deselect-all-countries">' . __( 'Deselect all countries', 'custom-checkout-fields-for-woocommerce' ) . '</a>',
				'id'       => 'countries',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select alg-wc-ccf-countries',
				'options'  => WC()->countries->get_countries(),
			),
			array(
				'desc'     => __( 'Countries action', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'countries_action',
				'default'  => 'hide',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'hide' => __( 'Hide in selected countries', 'custom-checkout-fields-for-woocommerce' ),
					'show' => __( 'Show in selected countries only', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'By another field', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => sprintf( __( 'E.g.: %s.', 'custom-checkout-fields-for-woocommerce' ),
					'<code>billing_' . ALG_WC_CCF_KEY . '_' . ( isset( $_GET['section'] ) && 'field_1' === wc_clean( $_GET['section'] ) ? '2' : '1' ) . '</code>' ),
				'desc_tip' => __( 'Visibility based on another field value. Enter field ID here.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'visibility_by_field',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'id'       => 'visibility_by_field_action',
				'default'  => 'not_empty',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'not_empty'   => __( 'Check if not empty', 'custom-checkout-fields-for-woocommerce' ),
					'exact_value' => __( 'Check for an exact value', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'desc_tip' => __( 'Exact value. Ignored unless the action is set to "Check for an exact value".', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'visibility_by_field_exact_value',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_visibility_options',
			),

			// Fees
			array(
				'title'    => __( 'Fee Options', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'In this optional section you can set fees that are added to the cart totals in case if custom field\'s value is not empty.', 'custom-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'field_fee_options',
			),
			array(
				'title'    => __( 'Fee value', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Can be positive or negative.', 'custom-checkout-fields-for-woocommerce' ) . ' ' .
					__( 'Ignored if set to zero.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_value',
				'type'     => 'number',
				'default'  => 0,
				'custom_attributes' => array( 'step' => '0.000001' ),
			),
			array(
				'title'    => __( 'Fee type', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_type',
				'type'     => 'select',
				'default'  => 'fixed',
				'options'  => array(
					'fixed'   => __( 'Fixed', 'custom-checkout-fields-for-woocommerce' ),
					'percent' => __( 'Percent', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Percent fee: Cart total', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Used only if "Percent" is selected in "Fee type".', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_percent_total',
				'type'     => 'select',
				'default'  => 'cart_contents_total',
				'options'  => array(
					'subtotal'            => __( 'Subtotal before discounts', 'custom-checkout-fields-for-woocommerce' ),
					'cart_contents_total' => __( 'Subtotal after discounts', 'custom-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'desc'     => __( 'Add shipping cost to cart total', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_percent_shipping',
				'type'     => 'checkbox',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Fee title', 'custom-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Can be empty.', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_title',
				'type'     => 'text',
				'default'  => '',
			),
			array(
				'title'    => __( 'Is fee taxable', 'custom-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Yes', 'custom-checkout-fields-for-woocommerce' ),
				'id'       => 'fee_taxable',
				'type'     => 'checkbox',
				'default'  => 'yes',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'field_fee_options',
			),

		) );
		return $options;
	}
}
