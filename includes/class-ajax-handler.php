<?php
/**
 * @author  HeyMehedi
 * @since   0.92
 * @version 0.93
 */

namespace Login_Me_Now;

class AJAX_Handler {
	public function __construct() {
		add_action( 'wp_ajax_generate_shareable_link', array( $this, 'generate_shareable_link' ) );
		add_action( 'wp_ajax_update_status_of_token', array( $this, 'update_status_of_token' ) );
	}

	public function generate_shareable_link() {
		$expiration = ! empty( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : 7;
		$user_id    = get_current_user_id();
		$link       = ( new JWT_Auth )->get_shareable_link( $user_id, $expiration );
		wp_send_json( $link );
		wp_die();
	}

	public function update_status_of_token() {
		$status = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : false;
		$id     = ! empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : 0;

		if ( ! $status && ! $id ) {
			wp_send_json( __( "Something wen't wrong!" ) );
			wp_die();
		}

		Tokens_Table::update( $id, $status );
		wp_die();
	}
}

new AJAX_Handler;