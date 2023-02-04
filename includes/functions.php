<?php
/**
 * @author  HeyMehedi
 * @since   1.0.0
 * @version 1.0.0
 */

/**
 * Return options.
 *
 * @param  string $option       Option key.
 * @param  mixed  $default      Option default value.
 * @return mixed                Return option value.
 */
function lmn_get_option( $option, $default = '' ) {

	/**
	 * Filter the options array for Settings.
	 *
	 * @since  1.0.0
	 * @var Array
	 */
	$options = apply_filters( 'lmn_get_option_array', $option, $default );

	$value = ( isset( $options[$option] ) && '' !== $options[$option] ) ? $options[$option] : $default;

	/**
	 * Dynamic filter lmn_get_option_$option.
	 * $option is the name of the Setting.
	 *
	 * @since  1.0.0
	 * @var Mixed.
	 */

	return apply_filters( "lmn_get_option_{$option}", $value, $option, $default );
}

function lmn_get_pro_url() {
	return '#';
}
