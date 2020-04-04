<?php
// class-register-post-type-ea-taxonomy.php
namespace EA_Taxonomies\Admin;
use EA_Taxonomies as NS;
// https://wordpress.stackexchange.com/questions/110562/is-it-possible-to-add-custom-post-type-menu-as-another-custom-post-type-sub-menu

class Register_Post_Type_EA_Taxonomy {
	private static $plugin_name;
	private static $plugin_version;
	private static $plugin_text_domain;

  	static function init() {
		self::$plugin_name = NS\PLUGIN_NAME;
		self::$plugin_version = NS\PLUGIN_VERSION;
		self::$plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		self::register();
		// add_action('init', 'Register_Custom_Taxonomies::init');
	}

	public static function register( ) {
		// Register post type for the plugin
		$labels = array(
			'name'               	=> _x( self::$plugin_name.'s', self::$plugin_text_domain ),
			'singular_name'      	=> _x( self::$plugin_name, self::$plugin_text_domain ),
			'menu_name'          	=> __( self::$plugin_name.'s', self::$plugin_text_domain ),
			'name_admin_bar'     	=> __( self::$plugin_name, self::$plugin_text_domain ),
			'parent_item'  		=> __( 'Parent '.self::$plugin_name, self::$plugin_text_domain ),
			'parent_item_colon'  	=> __( 'Parent '.self::$plugin_name.':', self::$plugin_text_domain ),
			'all_items'          	=> __( self::$plugin_name, self::$plugin_text_domain ), // (under main menu heading)
			'add_new_item'       	=> __( 'Add New '.self::$plugin_name, self::$plugin_text_domain ),
			'add_new'            	=> __( 'Add New', self::$plugin_text_domain ),
			'new_item'           	=> __( 'New '.self::$plugin_name, self::$plugin_text_domain ),
			'edit_item'          	=> __( 'Edit '.self::$plugin_name, self::$plugin_text_domain ),
			'update_item'        	=> __( 'Update '.self::$plugin_name, self::$plugin_text_domain ),
			'view_item'          	=> __( 'View '.self::$plugin_name, self::$plugin_text_domain ),
			'search_items'       	=> __( 'Search '.self::$plugin_name, self::$plugin_text_domain ),
			'not_found'          	=> __( 'Not found', self::$plugin_text_domain ),
			'not_found_in_trash' 	=> __( 'Not found in Trash', self::$plugin_text_domain ),
			/** Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'      	=> __( self::$plugin_name.'s', self::$plugin_text_domain ),
		);
		$args   = array(
			'label'        => __( self::$plugin_name, self::$plugin_text_domain ),
			'labels'       => $labels,
			'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom_fields' ),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => 'ea-taxonomies',
			'menu_icon'    => 'dashicons-exerpt-view',
			'can_export'   => true,
			'rewrite'      => false,
			'query_var'    => false,
		);
		register_post_type( self::$plugin_name, $args );
	} // END static function register()

/**
	public function updated_message( $messages ) {
		$post     = get_post();
		$revision = filter_input( INPUT_GET, 'revision', FILTER_SANITIZE_NUMBER_INT );

		$messages['ea taxonomy'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Taxonomy updated.', self::$plugin_text_domain ),
			2  => __( 'Custom field updated.', self::$plugin_text_domain ),
			3  => __( 'Custom field deleted.', self::$plugin_text_domain ),
			4  => __( 'Taxonomy updated.', self::$plugin_text_domain ),
			// translators: %s: Date and time of the revision.
			5  => $revision ? sprintf( __( 'Taxonomy restored to revision from %s.', self::$plugin_text_domain ), wp_post_revision_title( $revision, false ) ) : false,
			6  => __( 'Taxonomy published.', self::$plugin_text_domain ),
			7  => __( 'Taxonomy saved.', self::$plugin_text_domain ),
			8  => __( 'Taxonomy submitted.', self::$plugin_text_domain ),
			// translators: %s: Date and time of the revision.
			9  => sprintf( __( 'Taxonomy scheduled for: <strong>%s</strong>.', self::$plugin_text_domain ), date_i18n( __( 'M j, Y @ G:i', $this->plugin_text_domain ), strtotime( $post->post_date ) ) ),
			10 => __( 'Taxonomy draft updated.', self::$plugin_text_domain ),
		);

		return $messages;
	}
**/

/**
	public function bulk_updated_messages( $bulk_messages, $bulk_counts ) {
		$bulk_messages['ea taxonomy'] = array(
			// translators: %s: Name of the taxonomy in singular and plural form.
			'updated'   => sprintf( _n( '%s taxonomy updated.', '%s taxonomies updated.', $bulk_counts['updated'], $this->plugin_text_domain ), $bulk_counts['updated'] ),
			// translators: %s: Name of the taxonomy in singular and plural form.
			'locked'    => sprintf( _n( '%s taxonomy not updated, somebody is editing.', '%s taxonomies not updated, somebody is editing.', $bulk_counts['locked'], $this->plugin_text_domain ), $bulk_counts['locked'] ),
			// translators: %s: Name of the taxonomy in singular and plural form.
			'deleted'   => sprintf( _n( '%s taxonomy permanently deleted.', '%s taxonomies permanently deleted.', $bulk_counts['deleted'], $this->plugin_text_domain ), $bulk_counts['deleted'] ),
			// translators: %s: Name of the taxonomy in singular and plural form.
			'trashed'   => sprintf( _n( '%s taxonomy moved to the Trash.', '%s taxonomies moved to the Trash.', $bulk_counts['trashed'], $this->plugin_text_domain ), $bulk_counts['trashed'] ),
			// translators: %s: Name of the taxonomy in singular and plural form.
			'untrashed' => sprintf( _n( '%s taxonomy restored from the Trash.', '%s taxonomies restored from the Trash.', $bulk_counts['untrashed'], $this->plugin_text_domain ), $bulk_counts['untrashed'] ),
		);

		return $bulk_messages;
	}
**/

/**
	public function my_contextual_help( $contextual_help, $screen_id, $screen ) { 
		if ( 'product' == $screen->id ) {
		
		    	$contextual_help = 
				'<h2>Products</h2>
		    		<p>Products show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p> 
		    		<p>You can view/edit the details of each product by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
		
		} elseif ( 'edit-product' == $screen->id ) {
		
		    	$contextual_help = 
				'<h2>Editing products</h2>
		    		<p>This page allows you to view/modify product details. Please make sure to fill out the available boxes with the appropriate details (product image, price, brand) and <strong>not</strong> add these details to the product description.</p>';
		
		}
  		return $contextual_help;
	}
add_action( 'contextual_help', 'my_contextual_help', 10, 3 );
**/

}