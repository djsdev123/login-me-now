<?php
/**
 * @author  HeyMehedi
 * @since   1.0.0
 * @version 1.0.0
 */

namespace Login_Me_Now;

use WP_Error;
use WP_User;

/**
 * The Magic Number Handling Class
 */
class Magic_Number {

	/**
	 * Get the shareable magic link
	 *
	 * @param Integer $user_id
	 *
	 * @return string|WP_Error|null
	 */
	public function get_shareable_link( Int $user_id ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return;
		}

		$number = $this->get_new( $user );
		if ( ! $number ) {
			return;
		}

		$link = sprintf( '%s%s', site_url( '/?lmn=' ), $number );

		return array( 'link' => $link, 'number' => $number );
	}

	/**
	 * Generate a JWT
	 *
	 * @param WP_User $user
	 * @param Integer $expiration
	 *
	 * @return mixed|WP_Error|null
	 */
	private function get_new( WP_User $user, Int $hour = 8 ) {

		/** Valid credentials, the user exists create the according Token */
		$issuedAt = time();
		$expire   = apply_filters( 'login_me_now_magic_number_expire', $issuedAt + ( HOUR_IN_SECONDS * $hour ), $issuedAt );

		$number = $this->rand_number();

		/** Store the generated token in transient*/
		$saved = set_transient( $number, $user->data->ID, $expire );
		if ( ! $saved ) {
			return new WP_Error(
				"Something wen't wrong, please try again.",
			);
		}

		/** Store a log */
		// Tokens_Table::insert( $user->data->ID, $issuedAt, $expire, 'active' );

		return $number;
	}

	/**
	 * Verify the number
	 *
	 * @param bool|string $number
	 *
	 * @return WP_Error | False | Integer
	 */
	public function verify( Int $number ) {
		$len = strlen( $number );

		/**
		 * if the number is not valid return an error.
		 */
		if ( ! $number || 16 !== $len ) {
			return new WP_Error(
				'Invalid Number'
			);
		}

		/** Get the user_id from transient */
		$user_id = (int) get_transient( $number );

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		return $user->data->ID;
	}

	/**
	 * Random number generate
	 *
	 * @return Integer
	 */
	private function rand_number() {
		$number = mt_rand( 1000000000000000, 9999999999999999 );

		return $number;
	}
}