<?php
/**
 * Login Me Now CRON Jobs Base.
 *
 * @package Login Me Now
 * @since 1.0.0
 */

namespace Login_Me_Now;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class CRON_Jobs
 *
 * @since 1.0.0
 */
class CRON_Jobs {

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
		// Create cron job.
		add_action( 'admin_init', array( $this, 'create_purge_cron' ) );
		// Handler for cron job.
		add_action( 'login-me-now-purge-old-records', array( $this, 'purge_old_records' ) );
	}

	public function create_purge_cron() {
		if ( ! wp_next_scheduled( 'login-me-now-purge-old-records' ) ) {
			wp_schedule_event( time() + 60, 'hourly', 'login-me-now-purge-old-records' );
		}
	}

	/**
	 * Purges old Log records.
	 * 
	 * @since 1.0.0
	 * 
	 * @param  int $days_old How many days back to go.
	 * @param  boolean $dry_run  Is this a dry run?
	 *
	 * @return void
	 */
	public function purge_old_records( $days_old ) {

		if ( empty( $days_old ) ) {
			$days_old = lmn_get_option( 'logs_expiration', 7 );
		}

		$days_old = absint( $days_old );
		if ( empty( $days_old ) ) {
			return;
		}

		Logs_Table::delete_logs( $days_old );
	}

}

CRON_Jobs::get_instance();