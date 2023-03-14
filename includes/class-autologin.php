<?php
/**
 * @author  HeyMehedi
 * @since   0.90
 * @version 0.96
 */

namespace Login_Me_now_Now;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Login_Me_Now\Helper;
use Login_Me_Now\JWT_Auth;
use Login_Me_Now\Logs_DB;
use Login_Me_Now\Tokens_Table;

class AutoLogin {

	public function __construct() {
		add_action( 'template_include', array( $this, 'using_onetime_number' ) );
		add_action( 'template_include', array( $this, 'using_reusable_number' ) );
	}

	public function using_onetime_number( $template ) {
		if ( ! isset( $_GET['lmn'] ) ) {
			return $template;
		}

		if ( empty( $_GET['lmn'] ) ) {
			$title   = __( 'Number Not Provided', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		/** First thing, check the secret number if not exist return an error*/
		$number  = sanitize_text_field( $_GET['lmn'] );
		$t_value = get_transient( $number );
		if ( ! $t_value ) {
			$title   = __( 'Invalid number', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$user_id = ! empty( $t_value ) ? $t_value : false;

		if ( ! $user_id ) {
			$title   = __( 'User not found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		delete_transient( $number );

		( new Logs_DB )->insert( $user_id, "Logged in using onetime link #{$number}" );
		$this->now( $user_id );
	}

	public function using_reusable_number( $template ) {
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
		} catch ( \Throwable$th ) {
			$title   = __( 'Token not valid', 'login-me-now' );
			$message = $th->getMessage();
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$user_id = ! empty( $payload->data->user->id ) ? $payload->data->user->id : false;

		if ( ! $user_id ) {
			$title   = __( 'User not found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		$token_id     = ! empty( $payload->data->tid ) ? $payload->data->tid : false;
		$token_status = Tokens_Table::get_token_status( $token_id );

		if ( ! $token_status || 'active' != $token_status ) {
			$title   = __( 'Token not active', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( 'messages/error', array( 'title' => $title, 'message' => $message ) );

			return;
		}

		( new Logs_DB )->insert( $user_id, "Logged in using reusable link #{$token_id}" );

		$this->now( $user_id );
	}

	public function now( $user_id ) {
		include ABSPATH . "wp-includes/pluggable.php";
		wp_clear_auth_cookie();
		wp_set_auth_cookie( $user_id, true );

		$title   = __( 'Authentication Success 🎉', 'login-me-now' );
		$message = __( 'You are being redirected to the dashboard', 'login-me-now' );
		Helper::get_template_part( 'messages/success', array( 'title' => $title, 'message' => $message ) );
	}
}

new AutoLogin;