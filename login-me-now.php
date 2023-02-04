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

define( 'LOGIN_ME_NOW_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGIN_ME_NOW_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load dependencies managed by composer
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'vendor/autoload.php';

/**
 * Load necessary classes
 */
require_once LOGIN_ME_NOW_BASE_DIR . 'includes/init.php';
require_once LOGIN_ME_NOW_BASE_DIR . 'routes/init.php';