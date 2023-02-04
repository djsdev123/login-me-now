<?php
/*
Plugin Name: Login Me Now
Plugin URI: https://heymehedi.com
Description: Simple and Timer Saver One Click Login WordPress Plugin for Chrome Extension
Author: HeyMehedi
Author URI: https://heymehedi.com/
Text Domain: login-me-now
Domain Path: /languages
Version: 0.93
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( defined( 'LOGIN_ME_NOW_BASE_DIR' ) ) {
	return;
}

function astra_get_pro_url() {
	return '#';
}


/**
 * Return Theme options.
 *
 * @param  string $option       Option key.
 * @param  mixed  $default      Option default value.
 * @param  string $deprecated   Option default value.
 * @return mixed               Return option value.
 */
function astra_get_option( $option, $default = '', $deprecated = '' ) {

	if ( '' != $deprecated ) {
		$default = $deprecated;
	}

	$theme_options = Astra_Theme_Options::get_options();

	/**
	 * Filter the options array for Astra Settings.
	 *
	 * @since  1.0.20
	 * @var Array
	 */
	$theme_options = apply_filters( 'astra_get_option_array', $theme_options, $option, $default );

	$value = ( isset( $theme_options[$option] ) && '' !== $theme_options[$option] ) ? $theme_options[$option] : $default;

	/**
	 * Dynamic filter astra_get_option_$option.
	 * $option is the name of the Astra Setting, Refer Astra_Theme_Options::defaults() for option names from the theme.
	 *
	 * @since  1.0.20
	 * @var Mixed.
	 */

	return apply_filters( "astra_get_option_{$option}", $value, $option, $default );
}

function astra_is_white_labelled() {
}

define( 'LOGIN_ME_NOW_VERSION', '0.93' );
define( 'LOGIN_ME_NOW_PRO_UPGRADE_URL', 'https://halalbrains.com/login-me-now/' );

define( 'LOGIN_ME_NOW_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGIN_ME_NOW_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load dependencies managed by composer
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'vendor/autoload.php';

/**
 * Load necessary classes
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'admin/class-login-me-now-admin-loader.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'includes/init.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'routes/init.php';