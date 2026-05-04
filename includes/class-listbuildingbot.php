<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wickedcoolplugins.com
 * @since      1.0.0
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class Listbuildingbot {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Listbuildingbot_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LISTBUILDINGBOT_VERSION' ) ) {
			$this->version = LISTBUILDINGBOT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'listbuildingbot';

		$this->load_dependencies();
		$this->set_locale();
		/*if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {*/
		$this->define_admin_hooks();
		/*if (is_admin() && isset($_GET['page']) && (strpos($_GET['page'], "listbuildingbot" || strpos($_GET['page'], "lbb_create") !== false)) {
		}else{
			$this->define_all_pages_hooks();

		}*/
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Listbuildingbot_Loader. Orchestrates the hooks of the plugin.
	 * - Listbuildingbot_i18n. Defines internationalization functionality.
	 * - Listbuildingbot_Admin. Defines all hooks for the admin area.
	 * - Listbuildingbot_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-post-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-questions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-conversations.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-contacts.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-import.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-aiassistant.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-aiassistant-mapping.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-faq.php';
		

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-listbuildingbot-admin.php';

		if(is_admin()){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ai-functions.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin-functions.php';
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-messages.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-customfields.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-tags.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-listbuildingbot-outcomes.php';

		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-listbuildingbot-public.php';

		$this->loader = new Listbuildingbot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Listbuildingbot_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Listbuildingbot_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Listbuildingbot_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}
	private function define_all_pages_hooks() {

		$plugin_admin = new Listbuildingbot_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'all_pages_enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Listbuildingbot_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_lbb_check_current_user', $plugin_public, "lbb_check_current_user" );
		$this->loader->add_action( 'wp_ajax_nopriv_lbb_check_current_user', $plugin_public, "lbb_check_current_user" );
		$this->loader->add_action( 'wp_ajax_lbb_auth_current_user', $plugin_public, "lbb_auth_current_user" );
		$this->loader->add_action( 'wp_ajax_nopriv_lbb_auth_current_user', $plugin_public, "lbb_auth_current_user" );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Listbuildingbot_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
