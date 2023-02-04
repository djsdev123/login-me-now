<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.93
 */

namespace Login_Me_Now;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AutoLogin {

	public function __construct() {
		add_action( 'template_include', array( $this, 'login_me' ) );
	}

	public function login_me( $template ) {
		if ( ! isset( $_GET['login-me-now'] ) ) {
			return $template;
		}

		if ( empty( $_GET['token'] ) ) {
			$title   = __( 'Token Not Provided', 'login-me-now' );
			$message = __( 'Please provide a valid token', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		/** First thing, check the secret key if not exist return an error*/
		$secret_key = JWT_Auth::get_secret_key();
		if ( ! $secret_key ) {
			$title   = __( 'Not Configured Correctly', 'login-me-now' );
			$message = __( 'Login Me Now is not configured properly, please contact the admin', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		try {
			$token     = sanitize_text_field( $_GET['token'] );
			$algorithm = ( new JWT_Auth )->get_algorithm();
			$payload   = JWT::decode( $token, new Key( $secret_key, $algorithm ) );
		} catch ( \Throwable $th ) {
			$title   = __( 'Token not valid', 'login-me-now' );
			$message = $th->getMessage();
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$user_id = ! empty( $payload->data->user->id ) ? $payload->data->user->id : false;

		if ( ! $user_id ) {
			$title   = __( 'User not found', 'login-me-now' );
			$message = __( 'Please contact the admin', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$token_id     = ! empty( $payload->iat ) ? $payload->iat : false;
		$token_status = Tokens_Table::get_token_status( $token_id );

		if ( ! $token_status || 'active' != $token_status ) {
			$title   = __( 'Token not active', 'login-me-now' );
			$message = __( 'Your token is blocked or expired', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$this->now( $user_id );
	}

	public function now( $user_id ) {
		include ABSPATH . "wp-includes/pluggable.php";
		wp_clear_auth_cookie();
		wp_set_auth_cookie( $user_id, true );

		$title   = __( 'Success', 'login-me-now' );
		$message = __( 'Authenticated and your are redirecting...', 'login-me-now' );
		Helper::get_template_part( 'messages/success', array( 'title' => $title, 'message' => $message ) );
	}
}

new AutoLogin;