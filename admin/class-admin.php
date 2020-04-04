<?php
namespace EA_Taxonomies\Admin;
use EA_Taxonomies as NS;
/** Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript **/
// @link       https://elementarray.com
// @author     elementarray

class Admin { 

	private $plugin_name;
	private $version;
	private $plugin_text_domain;

	/** Initialize the class and set its properties **/
	public function __construct( ) {

		$this->plugin_name = NS\PLUGIN_NAME;
		$this->version = NS\PLUGIN_VERSION;
		$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
	}

	/** Register the stylesheets for the admin area **/
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ea-taxonomies-admin.css', array(), $this->version, 'all' );
	}

	/** Register the JavaScript for the admin area **/
	public function enqueue_scripts() {
		$params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'ea_ajax_handle', plugin_dir_url( __FILE__ ) . 'js/ea-taxonomies-ajax-handler.js', array( 'jquery' ), $this->version, false );				
		wp_localize_script( 'ea_ajax_handle', 'params', $params );		
	}
	
	/** Callback for the admin menu **/
	public function add_ea_taxonomy_menu() { // Init{}
		// Add a submenu page and save the returned hook suffix.
		$admin_page_hook = add_menu_page(
		        __( 'EA Admin Page Title', $this->plugin_text_domain ),  		//page title
		        __( 'EA  Admin Menu Title', $this->plugin_text_domain ), 		//menu title
		        'edit_others_posts', 							//capability 
		        'ea-taxonomies',							//menu slug
		        array( $this, 'admin_page_content' ) 					//callback for page content
		);
		//add_action( 'post_edit_form_tag', array( $this, 'ea_remove_form' ) );

		// Runs when an administration menu page load-($page).php is loaded, in this case, load-post.php
		add_action( 'load-post.php', array( $this, 'add_ea_meta_boxes') );
		add_action( 'load-post.php', array( $this, 'ea_remove_form' ) );
		add_action( 'load-post-new.php', array( $this, 'ea_remove_form_not_title' ) );	
	}

	public function ea_remove_form() {
		$screen = get_current_screen();
		// hides the dom in edit-post ea-taxonomies
		if('ea-taxonomies' === $screen->post_type){
			wp_enqueue_style( $this->plugin_name.'_hide_div_wrap', plugin_dir_url( __FILE__ ) . 'css/hide_div_wrap.css', array(), $this->version, 'all' );
		}
	}

	public function ea_remove_form_not_title() {
		$screen = get_current_screen();
		// hides the dom in edit-post ea-taxonomies
		if('ea-taxonomies' === $screen->post_type){
			wp_enqueue_style( $this->plugin_name.'_hide_div_post-body-content', plugin_dir_url( __FILE__ ) . 'css/hide_div_post-body-content.css', array(), $this->version, 'all' );
		}
	}

	public function add_ea_meta_boxes(){//$post_id
		new EA_Metabox();//$post_id
	}


	/** Callback for the $admin_page_hook action hook **/
	public function admin_page_content() {

	}

} // END class-Admin