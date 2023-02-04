<?php
/**
 * @author  HeyMehedi
 * @since   0.93
 * @version 0.93
 */

namespace Login_Me_Now;

class Add_Admin_Menu {
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'register_sub_menu' ) );
	}

	/**
	 * Adds a submenu page under a custom post type parent.
	 */
	public function register_sub_menu() {
		add_submenu_page(
			'options-general.php',
			__( 'Login Me Now', 'login-me-now' ),
			__( 'Login Me Now', 'login-me-now' ),
			'manage_options',
			'login-me-now',
			array( $this, 'submenu_page_callback' )
		);
	}

	/**
	 * Render submenu
	 * @return void
	 */
	public function submenu_page_callback() {
		Helper::get_template_part( 'menu-page/token-status' );
	}
}

new Add_Admin_Menu;