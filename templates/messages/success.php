<?php
/**
 * @author  HeyMehedi
 * @since   0.91
 * @version 0.91
 */

wp_head();
?>
<style>
	* {
		margin-bottom: 10px !important;
	}
	body {
		height: 80vh;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}
</style>
<body class="login-me-now">
	<h1><?php echo esc_html( $title ); ?></h1>
	<h4><?php echo esc_html( $message ); ?></h4>
	<p><?php _e( 'If you are not redirected to dashboard', 'login-me-now' )?></p>
	<a href="<?php echo admin_url(); ?>"><?php _e( 'Click here', 'login-me-now' )?></a>
	<script>location.replace("<?php echo admin_url(); ?>")</script>
</body>

<?php
wp_footer();