<?php
namespace EA_Taxonomies\Util;
use EA_Taxonomies as NS;
use EA_Taxonomies\Admin as Admin;
use EA_Taxonomies\Frontend as Frontend;

/** Defines internationalization, admin-specific hooks **/
// @link       https://elementarray.com
// @author     elementarray

class Init {
 
	/** protected members **/
	protected $loader; 		// @var Loader
	protected $plugin_basename; 	// @var string;
	protected $version; 		// @var string;
	protected $plugin_text_domain; 	// @var string;

	/** Constructor **/
    	public function __construct(){

		$this->plugin_name = NS\PLUGIN_NAME;
		$this->version = NS\PLUGIN_VERSION;
		$this->plugin_basename = NS\PLUGIN_BASENAME;
		$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		$this->load_dependencies();
		//$this->set_locale();
		$this->define_admin_hooks();
    	}

	/**
	* load dependencies for plugin
	* Loader - Orchestrates the hooks of the plugin.
	* Internationalization_i18n - Defines internationalization functionality.
	* Admin - Defines all hooks for the admin area.
	* @access    private
	**/

	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access    private
	 */

	/**
	private function set_locale() {
		// MISSING $this ?????????????????????????????????????????
		$plugin_i18n = new Internationalization_i18n( $this->plugin_text_domain );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	**/

	/**
	 * Register admin hooks
	 * Callbacks documented in admin/class-admin.php
	 * 
	 * @access private
	**/
	
	private function define_admin_hooks() {
		/** preliminaries **/
		$plugin_admin = new Admin\Admin(); // ( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' ); // defined in Admin{}
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' ); // defined in Admin{}
		/** Add a top-level admin menu for our plugin **/

		// It runs after the basic admin panel menu structure is in place.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_ea_taxonomy_menu' ); // defined in Admin{}	
		/** register admin notices **/
		//$this->loader->add_action( 'admin_notices', $plugin_admin, 'print_plugin_admin_notices');
	}

	/** invoke loader to execute all hooks with WordPress **/
	public function run() { $this->loader->run(); }

	public function get_plugin_name() { return $this->plugin_name; }
	public function get_version() { return $this->version; }
	public function get_plugin_text_domain() { return $this->plugin_text_domain; }
	
	/** reference class Loader ( loads hooks ) @return    Loader    Orchestrates the hooks of the plugin **/
	public function get_loader() { return $this->loader; }	

}