<?php
/**
 * Extension Popup.
 *
 * @package Login Me Now
 * @since 0.97
 */

$current_user = wp_get_current_user();
$html         = '<div class="lmnExt" id="lmnExt">';
$html .= sprintf( '<div id="lmnEmail" data-email="%s"></div>', $current_user->user_email );
$html .= sprintf( '<div id="lmnSiteUrl" data-siteurl="%s"></div>', get_site_url() );
$html .= sprintf( '<div id="lmnSecurity" data-security="%s"></div>', wp_create_nonce( 'login_me_now_generate_onetime_link_nonce' ) );
$html .= sprintf( '<div id="lmnAjaxUrl" data-ajaxurl="%s"></div>', admin_url( 'admin-ajax.php' ) );
$html .= __( '<strong>Login Me Now</strong> - save your website for quick & secure access.', 'login-me-now' );
$html .= sprintf( '<button id="lmn-save">%s</button>', __( 'Save Now', 'login-me-now' ) );
$html .= sprintf( '<button id="lmn-later">%s</button>', __( 'Later', 'login-me-now' ) );
$html .= '</div>';
echo $html;
?>
<script>
	let lmnLater = document.getElementById('lmn-later');
	const keyUrlEmail = '<?php echo get_site_url() . '_' . $current_user->user_email; ?>';
	let lmnExt = document.querySelector("#lmnExt");

	if( typeof lmnLater !== 'undefined' && lmnLater	) {
		lmnLater.addEventListener('click', function(e){
			const today = new Date();
			const nextWeek = new Date(today);
			nextWeek.setDate(today.getDate() + 3);
			localStorage.setItem(keyUrlEmail, nextWeek);
			lmnExt.style.display = 'none';
		})
	}

	let hasLater = localStorage.getItem(keyUrlEmail);
	if( typeof hasLater !== 'undefined' && hasLater	) {
		const today = new Date();
		const expectedTime = new Date(hasLater);
		if (today.getDate() !== expectedTime.getTime()) {
			lmnExt.innerHTML = '';
		} else {
			localStorage.removeItem(keyUrlEmail);
		}
	}
</script>

<style>
	.lmnExt {
		display: none;
		position: fixed;
		height: 50px;
		width: 100%;
		left: 0;
		bottom: 0;
		background: #263E94;
		color: #fff;
		text-align: center;
		z-index: 999999999;
		justify-content: center;
		align-items: center;
		transition: .3s;
	}
	#lmnExt button {
		padding: 5px 15px;
		margin: 0 5px;
		cursor: pointer;
		border: 0;
		box-shadow: 2px 2px 2px 2px #ffffff50;
	}
	#lmn-save {
		background: #fff;
		color: #000;
	}
</style>