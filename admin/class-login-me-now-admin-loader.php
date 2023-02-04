<?php
/**
 * Login Me Now Admin Loader
 *
 * @package Login Me Now
 * @since 1.0.0
 */

namespace Login_Me_Now;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Login_Me_Now_Admin_Loader
 *
 * @since 1.0.0
 */
class Login_Me_Now_Admin_Loader {

	/**
	 * Instance
	 *
	 * @access private
	 * @var null $instance
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public function __construct() {
		define( 'LOGIN_ME_NOW_ADMIN_DIR', LOGIN_ME_NOW_BASE_DIR . 'admin/' );
		define( 'LOGIN_ME_NOW_ADMIN_URL', LOGIN_ME_NOW_BASE_URL . 'admin/' );

		$this->includes();
	}

	/**
	 * Include required classes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		/* Setup API */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-api-init.php';

		/* Ajax init */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-admin-ajax.php';

		/* Setup Menu */
		require_once LOGIN_ME_NOW_ADMIN_DIR . 'includes/class-menu.php';
	}
}

Login_Me_Now_Admin_Loader::get_instance();
