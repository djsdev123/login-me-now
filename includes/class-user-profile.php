<?php
/**
 * @author  HeyMehedi
 * @since   0.92
 * @version 0.93
 */

namespace Login_Me_Now;

class User_Profile {

	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'edit_user_profile' ) );
	}

	public function edit_user_profile() {?>
		<hr>
		<h2><?php esc_html_e( 'Generate Login Me Now Link', 'login-me-now' );?></h2>
		<div class="shareable-link">
			<p><?php echo esc_html__( 'Set expire day', 'login-me-now' ); ?></p>
			<input id="expiration" type="number" id="valid-for" placeholder="<?php esc_html_e( 'ex: 1', 'login-me-now' );?>">
			<button id="generate-token" class="button-primary"><?php esc_html_e( 'Generate', 'login-me-now' );?></button>
		</div>
		<hr>
		<script>
			document.querySelector('#generate-token').addEventListener('click', function(event){
				event.preventDefault();

				let formData = new FormData();
				let url = `<?php echo admin_url( 'admin-ajax.php' ); ?>`;
				let exp = document.querySelector('#expiration').value;
				formData.append( 'action', 'generate_shareable_link' );
				formData.append( 'expiration', exp);
				fetch(url, {
						method: 'POST',
						body: formData,
					} )
					.then( res => res.json() )
					.then( res => {
						document.querySelector('.shareable-link').innerHTML = `<p style="color:green;max-width:80vh;word-break: break-all;padding:15px;box-shadow: 0 0 5px #ddd;"> ${res} </p><p style="color:red;font-size:120%"><?php esc_attr_e( "Please keep this link confidential and do not share it with anyone. Save the link for future reference, as it cannot be retrieved again in the future." )?></p>`;
					} )
					.catch( err => console.log( err ) );
			});
		</script>
	<?php }
}

new User_Profile;