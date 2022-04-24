<?php
/**
 * Custom Checkout Fields for WooCommerce - Shortcodes Class
 *
 * @version 1.6.1
 * @since   1.4.8
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CCF_Shortcodes' ) ) :

class Alg_WC_CCF_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 1.6.1
	 * @since   1.4.8
	 */
	function __construct() {
		add_shortcode( 'alg_wc_ccf_translate', array( $this, 'language_shortcode' ) );
		add_shortcode( 'alg_wc_ccf_if',        array( $this, 'if_shortcode' ) );
		add_shortcode( 'alg_wc_ccf_datetime',  array( $this, 'datetime_shortcode' ) );
	}

	/**
	 * datetime_shortcode.
	 *
	 * @version 1.6.1
	 * @since   1.6.1
	 */
	function datetime_shortcode( $atts, $content = '' ) {
		$format = ( isset( $atts['format'] ) ? $atts['format'] : get_option( 'date_format', 'F j, Y' ) );
		return date_i18n( $format, current_time( 'timestamp' ) );
	}

	/**
	 * if_shortcode.
	 *
	 * @version 1.6.1
	 * @since   1.6.1
	 */
	function if_shortcode( $atts, $content = '' ) {
		// E.g.: Min date: `[alg_wc_ccf_if value1="{alg_wc_ccf_datetime format='Gi'}" operator="greater" value2="1300" then="1" else="0"]`
		if ( ! isset( $atts['value1'], $atts['operator'], $atts['value2'] ) || ( '' === $content && ! isset( $atts['then'] ) ) ) {
			return '';
		}
		$value1  = do_shortcode( str_replace( array( '{', '}' ), array( '[', ']' ), $atts['value1'] ) );
		$value2  = do_shortcode( str_replace( array( '{', '}' ), array( '[', ']' ), $atts['value2'] ) );
		$then    = do_shortcode( ( '' !== $content ? $content : str_replace( array( '{', '}' ), array( '[', ']' ), $atts['then'] ) ) );
		$else    = ( isset( $atts['else'] ) ? do_shortcode( str_replace( array( '{', '}' ), array( '[', ']' ), $atts['else'] ) ) : '' );
		switch ( $atts['operator'] ) {
			case 'equal':
				return ( $value1 == $value2 ? $then : $else );
			case 'not_equal':
				return ( $value1 != $value2 ? $then : $else );
			case 'less':
				return ( $value1 <  $value2 ? $then : $else );
			case 'less_or_equal':
				return ( $value1 <= $value2 ? $then : $else );
			case 'greater':
				return ( $value1 >  $value2 ? $then : $else );
			case 'greater_or_equal':
				return ( $value1 >= $value2 ? $then : $else );
		}
		return '';
	}

	/**
	 * language_shortcode.
	 *
	 * @version 1.4.8
	 * @since   1.4.8
	 */
	function language_shortcode( $atts, $content = '' ) {
		// E.g.: `[alg_wc_ccf_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_ccf_translate lang="EN,DE"]Text for EN & DE[/alg_wc_ccf_translate][alg_wc_ccf_translate not_lang="EN,DE"]Text for other languages[/alg_wc_ccf_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

}

endif;

return new Alg_WC_CCF_Shortcodes();
