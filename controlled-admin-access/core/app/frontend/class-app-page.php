<?php

namespace WPRuby_CAA\Core\App\Frontend;

use WPRuby_CAA\Core\App\Backend\Endpoints\Endpoints_Factory;
use WPRuby_CAA\Core\Constants;

class App_Page {

	const PAGE_SLUG = 'controlled-admin-access';

	protected static $_instance = null;

	/**
	 * @return self
	 */
	public static function get_instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_scripts'] );

	}

	public function enqueue_admin_scripts($hook)
	{
		if (!isset($_GET['page']) || strtolower($_GET['page']) !== self::PAGE_SLUG) {
			return;
		}

		if (!current_user_can('manage_options')) {
			return;
		}

		$app_url = plugin_dir_url( __FILE__ ) . 'app/dist/';

		wp_enqueue_style('dashicons');
		wp_enqueue_style('wpruby-caa-css', $app_url . 'css/app.css', [], Constants::UTIL_CURRENT_VERSION);
		wp_enqueue_script('wpruby-caa-app', $app_url . 'app.js', ['wp-i18n'], Constants::UTIL_CURRENT_VERSION, true);

		$config = [
			'ajaxUrl'              => admin_url('admin-ajax.php'),
			'nonces'               => Endpoints_Factory::get_endpoints_nonce(),
			'currentUserId'        => get_current_user_id(),
			'adminUrl'             => admin_url(),
			'loginUrl'             => wp_login_url(),
			'internalPluginsCover' => plugin_dir_url( __FILE__ ) . 'views/internal-plugins-pages.png',
		];

		wp_add_inline_script('wpruby-caa-app', 'window.caaAdmin = ' . wp_json_encode($config) . ';', 'before');
		wp_set_script_translations('wpruby-caa-app', 'controlled-admin-access', dirname(__DIR__, 3) . '/languages');
	}

	public function admin_menu()
	{
		add_menu_page(
			__( 'Controlled Admin Access', 'controlled-admin-access' ),
			__( 'Controlled Access', 'controlled-admin-access' ),
			'manage_options',
			self::PAGE_SLUG,
			[$this, 'my_admin_page_contents'],
			'dashicons-lock',
			3
		);
	}

	public function my_admin_page_contents()
	{
		include_once "views/app.php";
	}

}
