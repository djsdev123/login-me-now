<?php
/**
 * Class Menu.
 *
 * @package Login Me Now
 * @since 1.0.0
 */

namespace Login_Me_Now;

use Login_Me_Now\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Menu {

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
	 * Page title
	 *
	 * @since 1.0.0
	 * @var string $page_title
	 */
	public static $page_title = 'Login Me Now';

	/**
	 * Plugin slug
	 *
	 * @since 1.0.0
	 * @var string $plugin_slug
	 */
	public static $plugin_slug = 'login-me-now';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->initialize_hooks();
	}

	/**
	 * Init Hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function initialize_hooks() {

		self::$page_title  = apply_filters( 'login_me_now_page_title', __( 'Login Me Now', 'login-me-now' ) );
		self::$plugin_slug = self::$plugin_slug;

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_admin_scripts' ) );

		add_action( 'after_setup_theme', array( $this, 'init_admin_settings' ), 99 );
	}

	/**
	 * Admin settings init.
	 *
	 * @since 1.0.0
	 */
	public function init_admin_settings() {
		if ( ! is_customize_preview() ) {
			add_action( 'admin_head', array( $this, 'admin_submenu_css' ) );
		}
	}

	/**
	 * Add custom CSS for admin area sub menu icons.
	 *
	 * @since 1.0.0
	 */
	public function admin_submenu_css() {
		echo '<style class="astra-menu-appearance-style">
				#toplevel_page_' . esc_attr( self::$plugin_slug ) . ' .wp-menu-image.svg {
					background-size: 18px auto !important;
				}
			</style>';
	}

	/**
	 *  Initialize after Astra gets loaded.
	 *
	 * @since 1.0.0
	 */
	public function settings_admin_scripts() {
		// Enqueue admin scripts.
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( ! empty( $_GET['page'] ) && ( self::$plugin_slug === $_GET['page'] || false !== strpos( $_GET['page'], self::$plugin_slug . '_' ) ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			add_action( 'admin_enqueue_scripts', array( $this, 'styles_scripts' ) );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_link' ), 99 );
		}
	}

	/**
	 * Add submenu to admin menu.
	 *
	 * @since 1.0.0
	 */
	public function setup_menu() {
		global $submenu;
		$capability = 'manage_options';

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		$astra_icon = apply_filters( 'menu_icon', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iI2E3YWFhZCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI4IDIwIDIwIDE1LjUyMjggMjAgMTBDMjAgNC40NzcxNSAxNS41MjI4IDAgMTAgMEM0LjQ3NzE1IDAgMCA0LjQ3NzE1IDAgMTBDMCAxNS41MjI4IDQuNDc3MTUgMjAgMTAgMjBaTTUuODczMDQgMTEuMTY0MUM3LjIwMjM0IDguNDQyNzggOC41MzE4MSA1LjcyMTEyIDkuODYxMjcgMy4wMDAzOEwxMS4yNTUyIDUuNzA3NTlDMTAuMjA2NCA3Ljc2MjQ0IDkuMTU3NSA5LjgxNjg1IDguMTA4NzggMTEuODcwOEw2LjUxMTkgMTQuOTk4NUg0TDUuODczMDQgMTEuMTY0MVpNMTAuMDQ2NCAxMi44MzM5TDEyLjQ2NTUgNy45NjE2NUMxMi45OTMzIDkuMDEyOTIgMTMuNTIxMyAxMC4wNjQyIDE0LjA0OTQgMTEuMTE1NkMxNC42OTk2IDEyLjQxMDEgMTUuMzQ5OSAxMy43MDQ4IDE2IDE1SDEzLjMwMjVMMTIuODM5MyAxMy45NjY2TDEyLjM3MjIgMTIuOTI0NUgxMC4wNDY0SDkuOTk5NzZMMTAuMDQ2NCAxMi44MzM5WiIgZmlsbD0iI2E3YWFhZCIvPgo8L3N2Zz4K' );
		$priority   = apply_filters( 'menu_priority', 59 );

		add_menu_page(
			self::$page_title,
			self::$page_title,
			$capability,
			self::$plugin_slug,
			array( $this, 'render_admin_dashboard' ),
			$astra_icon,
			$priority
		);

		add_submenu_page(
			self::$plugin_slug,
			__( 'Tokens', 'login-me-now' ),
			__( 'Tokens', 'login-me-now' ),
			$capability,
			'login-me-now-tokens',
			array( $this, 'tokens_callback' )
		);

		add_submenu_page(
			self::$plugin_slug,
			__( 'Logs', 'login-me-now' ),
			__( 'Logs', 'login-me-now' ),
			$capability,
			'login-me-now-logs',
			array( $this, 'logs_callback' )
		);

		// Rename to Home menu.
		$submenu[self::$plugin_slug][0][0] = __( 'Dashboard', 'login-me-now' );
	}

	/**
	 * Render Tokens
	 * @return void
	 */
	public function tokens_callback() {
		Helper::get_template_part( 'menu-page/token-status' );
	}

	/**
	 * Render Tokens
	 * @return void
	 */
	public function logs_callback() {
		Helper::get_template_part( 'menu-page/token-status' );
	}

	/**
	 * Renders the admin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_admin_dashboard() {
		$page_action = '';

		if ( isset( $_GET['action'] ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = str_replace( '_', '-', $page_action );
		}

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include_once LOGIN_ME_NOW_ADMIN_DIR . 'views/admin-base.php';
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 */
	public function styles_scripts() {

		if ( is_customize_preview() ) {
			return;
		}

		wp_enqueue_style( 'astra-admin-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap', array(), LOGIN_ME_NOW_VERSION ); // Styles.

		wp_enqueue_style( 'wp-components' );

		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$show_self_branding = true;
		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$localize = array(
			'current_user'           => ! empty( wp_get_current_user()->user_firstname ) ? ucfirst( wp_get_current_user()->user_firstname ) : ucfirst( wp_get_current_user()->display_name ),
			'admin_base_url'         => admin_url(),
			'plugin_dir'             => LOGIN_ME_NOW_BASE_URL,
			'plugin_ver'             => defined( 'LOGIN_ME_NOW_EXT_VER' ) ? LOGIN_ME_NOW_EXT_VER : '',
			'version'                => LOGIN_ME_NOW_VERSION,
			'pro_available'          => defined( 'LOGIN_ME_NOW_EXT_VER' ) ? true : false,
			'pro_installed_status'   => 'installed' === self::get_plugin_status( 'astra-addon/astra-addon.php' ) ? true : false,
			'product_name'           => __( 'Login Me Now', 'login-me-now' ),
			'plugin_name'            => __( 'Login Me Now PRO', 'login-me-now' ),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'show_self_branding'     => $show_self_branding,
			'admin_url'              => admin_url( 'admin.php' ),
			'home_slug'              => self::$plugin_slug,
			'upgrade_url'            => LOGIN_ME_NOW_PRO_UPGRADE_URL,
			'extension_url'          => 'https://chrome.google.com/webstore/detail/login-me-now/kkkofomlfhbepmpiplggmfpomdnkljoh/?sourch=wp-dashboard',
			'login_me_now_base_url'  => admin_url( 'admin.php?page=' . self::$plugin_slug ),
			'logo_url'               => apply_filters( 'login_me_now_admin_menu_icon', LOGIN_ME_NOW_BASE_URL . 'admin/assets/images/icon.svg' ),
			'update_nonce'           => wp_create_nonce( 'login_me_now_update_admin_setting' ),
			'extensions'             => self::get_pro_extensions(),
			'plugin_manager_nonce'   => wp_create_nonce( 'login_me_now_plugin_manager_nonce' ),
			'plugin_installer_nonce' => wp_create_nonce( 'updates' ),
			'free_vs_pro_link'       => admin_url( 'admin.php?page=' . self::$plugin_slug . '&path=free-vs-pro' ),
			'plugin_installed_text'  => __( 'Installed', 'login-me-now' ),
			'plugin_activating_text' => __( 'Activating', 'login-me-now' ),
			'plugin_activated_text'  => __( 'Activated', 'login-me-now' ),
			'plugin_activate_text'   => __( 'Activate', 'login-me-now' ),
			'upgrade_notice'         => true,
		);

		$this->settings_app_scripts( apply_filters( 'login_me_now_react_admin_localize', $localize ) );
	}

	/**
	 * Get plugin status
	 *
	 * @since 1.0.0
	 *
	 * @param  string $plugin_init_file Plguin init file.
	 * @return mixed
	 */
	public static function get_plugin_status( $plugin_init_file ) {

		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[$plugin_init_file] ) ) {
			return 'install';
		} elseif ( is_plugin_active( $plugin_init_file ) ) {
			return 'activated';
		} else {
			return 'installed';
		}
	}

	/**
	 * Get Login Me Now's pro feature list.
	 *
	 * @since 1.0.0
	 * @return array
	 * @access public
	 */
	public static function get_pro_extensions() {
		return apply_filters(
			'login_me_now_feature_list',
			array(
				'email-magic-link' => array(
					'title'     => __( 'Email Magic Link', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => '#',
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => '#',
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'typography'       => array(
					'title'     => __( 'Email Magic Number', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'opt_login'        => array(
					'title'     => __( 'OTP Login', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'spacing'          => array(
					'title'     => __( 'Google Login', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/spacing-addon-overview/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/spacing-addon-overview/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'blog-pro'         => array(
					'title'     => __( 'Facebook Login', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/blog-pro-overview/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/blog-pro-overview/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'header-sections'  => array(
					'title'     => __( 'Github Login', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/header-sections-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/header-sections-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'sticky-header'    => array(
					'title'     => __( 'User Switching', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/sticky-header-pro/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/sticky-header-pro/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
				'site-layouts'     => array(
					'title'     => __( 'Login and Registration Popup', 'login-me-now' ),
					'class'     => 'ast-addon',
					'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/site-layout-overview/', 'wp', 'dashboard' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/site-layout-overview/', 'wp', 'dashboard' ),
							'link_text'    => __( 'Upcoming', 'login-me-now' ),
							'target_blank' => true,
						),
					),
				),
			)
		);
	}

	/**
	 * Settings app scripts
	 *
	 * @since 1.0.0
	 * @param array $localize Variable names.
	 */
	public function settings_app_scripts( $localize ) {
		$handle            = 'login-me-now-admin-dashboard-app';
		$build_path        = LOGIN_ME_NOW_ADMIN_DIR . 'assets/build/';
		$build_url         = LOGIN_ME_NOW_ADMIN_URL . 'assets/build/';
		$script_asset_path = $build_path . 'dashboard-app.asset.php';

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$script_info = file_exists( $script_asset_path ) ? include $script_asset_path : array(
			'dependencies' => array(),
			'version'      => LOGIN_ME_NOW_VERSION,
		);
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$script_dep = array_merge( $script_info['dependencies'], array( 'updates', 'wp-hooks' ) );

		wp_register_script(
			$handle,
			$build_url . 'dashboard-app.js',
			$script_dep,
			$script_info['version'],
			true
		);

		wp_register_style(
			$handle,
			$build_url . 'dashboard-app.css',
			array(),
			LOGIN_ME_NOW_VERSION
		);

		wp_register_style(
			'login-me-now-admin-google-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap',
			array(),
			LOGIN_ME_NOW_VERSION
		);

		wp_enqueue_script( $handle );

		wp_set_script_translations( $handle, 'login-me-now' );

		wp_enqueue_style( 'login-me-now-admin-google-fonts' );
		wp_enqueue_style( $handle );

		wp_style_add_data( $handle, 'rtl', 'replace' );

		wp_localize_script( $handle, 'lmn_admin', $localize );
	}

	/**
	 *  Add footer link.
	 *
	 * @since 1.0.0
	 */
	public function admin_footer_link() {
		echo '<span id="footer-thankyou"> Thank you for using <span class="focus:text-astra-hover active:text-astra-hover hover:text-astra-hover"> ' . esc_attr( __( 'Login Me Now', 'login-me-now' ) ) . '.</span></span>';
	}
}

Menu::get_instance();
