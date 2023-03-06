<?php
/**
 * Login Me Now Admin Loader
 *
 * @package Login Me Now
 * @since 0.94
 */

namespace Login_Me_Now;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Login_Me_Now_Admin_Loader
 *
 * @since 0.94
 */
class Login_Me_Now_Admin_Loader {

	/**
	 * Instance
	 *
	 * @access private
	 * @var null $instance
	 * @since 0.94
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 0.94
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			self::$instance = new self();
			/** @psalm-suppress InvalidPropertyAssignmentValue */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 0.94
	 */
	public function __construct() {
		define( 'LOGIN_ME_NOW_ADMIN_DIR', LOGIN_ME_NOW_BASE_DIR . 'admin/' );
		define( 'LOGIN_ME_NOW_ADMIN_URL', LOGIN_ME_NOW_BASE_URL . 'admin/' );

		$this->includes();
	}

	/**
	 * Include required classes.
	 *
	 * @since 0.94
	 */
	public function includes() {
		/* Tokens Table */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-tokens-table.php';
		/* Logs Table */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-logs-table.php';
		/* After Plugin Activation */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-after-activation.php';
		/* Setup API */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-api-init.php';
		/* Ajax init */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-admin-ajax.php';
		/* Setup Menu */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-menu.php';
		/* CRON Jobs */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-cron-jobs.php';
	}
}

Login_Me_Now_Admin_Loader::get_instance();