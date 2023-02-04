<?php
/**
 * @author  HeyMehedi
 * @since   0.93
 * @version 0.93
 */

use Login_Me_Now\Helper;
use Login_Me_Now\Tokens_Table;
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	* {
	box-sizing: border-box;
	}

	#myInput {
	background-image: url('/css/searchicon.png');
	background-position: 10px 10px;
	background-repeat: no-repeat;
	width: 100%;
	font-size: 16px;
	padding: 12px 20px 12px 40px;
	border: 1px solid #ddd;
	margin-bottom: 12px;
	}

	#tokensTable {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid #ddd;
	font-size: 18px;
	}

	#tokensTable th, #tokensTable td {
	text-align: left;
	padding: 12px;
	}

	#tokensTable tr {
	border-bottom: 1px solid #ddd;
	}

	#tokensTable tr.header, #tokensTable tr:hover {
	background-color: #f1f1f1;
	}
</style>

<h2><?php esc_html_e( 'All Tokens', 'login-me-now' );?></h2>

<!-- <input type="text" id="myInput" onkeyup="searchTokens()" placeholder="Search for usernames.." title="Type in a name"> -->

<table id="tokensTable">
  <tr class="header">
    <th style="width:10%;"><?php esc_html_e( 'Token ID', 'login-me-now' );?></th>
    <th style="width:25%;"><?php esc_html_e( 'Username', 'login-me-now' );?></th>
    <th style="width:15%;"><?php esc_html_e( 'Issued At', 'login-me-now' );?></th>
    <th style="width:15%;"><?php esc_html_e( 'Expire', 'login-me-now' );?></th>
    <th style="width:15%;"><?php esc_html_e( 'Status', 'login-me-now' );?></th>
  </tr>

  <?php foreach ( Tokens_Table::get_tokens() as $token ) {
	echo '<tr>';
	$user_info = get_userdata( $token->user_id );
	printf( '<td>%s</td>', $token->token_id );
	printf( '<td>%s</td>', $user_info->user_login );
	printf( '<td>%s</td>', date( 'M d, Y, h:i A', $token->token_id ) );
	printf( '<td>%s</td>', date( 'M d, Y, h:i A', $token->expire ) );
	printf( '<td>%s</td>', Helper::generate_status_options( $token->status, $token->id ) );
	echo '</tr>';}?>

</table>

<script>
	// Show the alert when the page loads
	const Toast = Swal.mixin({
		toast: true,
		position: 'bottom-end',
		showConfirmButton: false,
		timer: 1500,
	});

	function updateStatus(event) {
		let formData = new FormData();
		formData.append( 'action', 'update_status_of_token' );
		formData.append( 'id', event.target.getAttribute('data-id'));
		formData.append( 'status', event.target.value);

		let url = `<?php echo admin_url( 'admin-ajax.php' ); ?>`;

		fetch(url, {
				method: 'POST',
				body: formData,
			} )
			.then( res => res.json() )
			.then( res => {
				console.log(res);
				Toast.fire({
					icon: 'success',
					title: 'Status updated',
				})
			} )
			.catch( err => {
				console.log(err)
				Toast.fire({
					icon: 'error',
					title: "Something wen't wrong",
				})
			} );

		console.log(event.target.value);
	}
</script>