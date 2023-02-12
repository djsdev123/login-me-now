<?php
/**
 * @author  HeyMehedi
 * @since   1.0.0
 * @version 1.0.0
 */

namespace Login_Me_Now;

/**
 * Logs related methods and actions
 *
 * @since 1.0.0
 */
class Logs_Table {
	/**
	 * Create logs table if not exist
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		$table_schema = array(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}login_me_now_logs (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`ip` varchar(260) DEFAULT NULL,
			`message` varchar(260) DEFAULT NULL,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`)
			) ENGINE=InnoDB $collate;",
		);

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $table_schema as $table ) {
			dbDelta( $table );
		}
	}

	/**
	 * Insert log data
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function insert( Int $user_id, String $message ) {
		global $wpdb;

		$ip = get_ip_address();

		$checkin_sql = sprintf( "INSERT INTO {$wpdb->prefix}login_me_now_logs
		(user_id, ip, message)
		VALUES
		('%s', '%s', '%s')",
			intval( $user_id ), $ip, $message );

		$wpdb->query( $checkin_sql );
	}

	/**
	 * Get all logs
	 *
	 * @since 1.0.0
	 *
	 * @return Array|Object|NULL
	 */
	public static function get_logs() {
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}login_me_now_logs
			ORDER BY id
			DESC";

		$result = $wpdb->get_results( $sql );

		return $result;
	}
}

new Logs_Table();