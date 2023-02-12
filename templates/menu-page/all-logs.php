<?php
/**
 * @author  HeyMehedi
 * @since   1.0.0
 * @version 1.0.0
 */

use Login_Me_Now\Logs_Table;
?>

<h2><?php esc_html_e( 'All Logs', 'login-me-now' );?></h2>

<style>
	* {
	box-sizing: border-box;
	}

	#logsTable {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid #ddd;
	font-size: 18px;
	}

	#logsTable th, #logsTable td {
	text-align: left;
	padding: 12px;
	}

	#logsTable tr {
	border-bottom: 1px solid #ddd;
	}

	#logsTable tr.header, #logsTable tr:hover {
	background-color: #f1f1f1;
	}
</style>

<table id="logsTable">
  <tr class="header">
    <th style="width:15%;"><?php esc_html_e( 'Username', 'login-me-now' );?></th>
    <th style="width:10%;"><?php esc_html_e( 'IP Address', 'login-me-now' );?></th>
    <th style="width:15%;"><?php esc_html_e( 'Time', 'login-me-now' );?></th>
    <th style="width:60%;"><?php esc_html_e( 'Details', 'login-me-now' );?></th>
  </tr>

  <?php foreach ( Logs_Table::get_logs() as $log ) {
	echo '<tr>';
	$user_info = get_userdata( $log->user_id );
	printf( '<td>%s</td>', esc_html( $user_info->user_login ) );
	printf( '<td>%s</td>', esc_html( $log->ip ) );
	printf( '<td>%s</td>', esc_html( $log->created_at ) );
	printf( '<td>%s</td>', esc_html( $log->message ) );
	echo '</tr>';}?>

</table>