<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.90
 */

namespace Login_Me_Now\Routes;

defined( 'ABSPATH' ) || exit;

use Login_Me_Now\JWT_Auth;
use Login_Me_Now\Routes\Rest_Base;

/**
 * Generate API class.
 */
class API_Generate extends Rest_Base {

	/**
	 * Registers the route to generate the token.
	 *
	 * @since 0.9
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/generate',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array(  ( new JWT_Auth ), 'generate_token' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}
}

new API_Generate;