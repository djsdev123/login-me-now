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

</script>

<style>
	.lmnExt {
		display: none;
		position: fixed;
		height: 50px;
		width: 100%;
		left: 0;
		bottom: 0;
		background: #000;
		color: #fff;
		text-align: center;
		z-index: 999999999;
		justify-content: center;
		align-items: center;
	}
	#lmnExt button {
		padding: 5px 15px;
		margin: 0 5px;
		cursor: pointer;
		border: 0;
		box-shadow: 2px 2px 2px 2px #ffffff50;
	}
	#lmn-save {
		background: #00ba00;
		color: #fff;
	}
</style>