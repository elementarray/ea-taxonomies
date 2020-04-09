<?php
namespace EA_Taxonomies\Admin;
use EA_Taxonomies as NS;
/** Calls the class on the post edit screen **/
// @link       https://elementarray.com
// @author     elementarray

class EA_Metabox {
	private $plugin_name;
	private $version;
	private $plugin_text_domain;
	public $saved = false;

	public function __construct() {
		$this->plugin_name = NS\PLUGIN_NAME;
		$this->version = NS\PLUGIN_VERSION;
		$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		$this->init_metabox();
	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'add_meta_boxes', array( $this, 'add_side_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox($post_type) {
        // Limit meta box to certain post types.
        	$post_types = array( 'ea-taxonomies' );
 
        	if ( in_array( $post_type, $post_types ) ) {

			$test1 = add_meta_box(
				$this->plugin_name.'_1',
				__( 'EA Taxonomy', $this->plugin_text_domain ),
				array( $this, 'render_metabox' ),
				$post_type,
				'advanced',
				'default'
			);
		}

	}

	public function add_side_metabox($post_type) {
        	$post_types = array( 'ea-taxonomies' );
        	if ( in_array( $post_type, $post_types ) ) {
            		$test2 = add_meta_box(
                		$this->plugin_name.'_2',
                		__( 'EA Taxonomy Assign to Post Type', $this->plugin_text_domain  ),
                		array( $this, 'render_side_meta_box' ),
                		$post_type,
                		'side',
                		'low'
            		);

		}

	}

	public function render_side_meta_box( $post ){
		// Post types.
		$options    = array();
		$post_types = get_post_types( '', 'objects' );
		unset( $post_types['mb-taxonomy'], $post_types['revision'], $post_types['nav_menu_item'] );

		// Form fields.
		_e('<table class="form-table">');

		foreach ( $post_types as $post_type => $post_type_object ) {
			$options[ $post_type ] = $post_type_object->labels->singular_name;

			_e('<tr><th><label for="args_'.$options[ $post_type ].'" class="label-args_'.$options[ $post_type ].'">' . __( $options[ $post_type ], $this->plugin_text_domain ) . '</label></th>');
			_e('<td><input type="checkbox" id="args_'.$options[ $post_type ].'" name="args_'.$options[ $post_type ].'" class="field-args_'.$options[ $post_type ].'" value="' . $options[ $post_type ] . '" ' . checked( $options[ $post_type ], 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( $options[ $post_type ], $this->plugin_text_domain ) . '</span></td></tr>');
		}

		_e('</table>');

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'ea_taxonomies_nonce_action', 'ea_taxonomies_nonce' );

		/** 'required'
				'Plural name is required', 
				'Singular name is required',
				'Slug is required'
		**/
		//////////////////////////////////
		// $variable = $item ?: NULL;   //
		//////////////////////////////////

		// Basic fields
		// Default is _x( 'Post Tags', 'taxonomy general name' ) or _x( 'Categories', 'taxonomy general name' )
		$label_name = get_post_meta( $post->ID, 'label_name', true );
		// Default is _x( 'Post Tag', 'taxonomy singular name' ) or _x( 'Category', 'taxonomy singular name' )
		$label_singular_name = get_post_meta( $post->ID, 'label_singular_name', true );
		// Name should only contain lowercase letters and the underscore character, and not be more than 32 characters long (database structure restriction).
		// Default: None
		$args_taxonomy = get_post_meta( $post->ID, 'args_taxonomy', true );

		// Labels fields (text inputs)
		//  defaults to value of name label
		$label_menu_name = get_post_meta( $post->ID, 'label_menu_name', true );
		// Default is __( 'All Tags' ) or __( 'All Categories' )
		$label_all_items = get_post_meta( $post->ID, 'label_all_items', true );
		// Default is __( 'Edit Tag' ) or __( 'Edit Category' )
		$label_edit_item = get_post_meta( $post->ID, 'label_edit_item', true );
		// Default is __( 'View Tag' ) or __( 'View Category' )
		$label_view_item = get_post_meta( $post->ID, 'label_view_item', true );
		// Default is __( 'Update Tag' ) or __( 'Update Category' )
		$label_update_item = get_post_meta( $post->ID, 'label_update_item', true );
		// Default is __( 'Add New Tag' ) or __( 'Add New Category' )
		$label_add_new_item = get_post_meta( $post->ID, 'label_add_new_item', true );
		// Default is __( 'New Tag Name' ) or __( 'New Category Name' )
		$label_new_item_name = get_post_meta( $post->ID, 'label_new_item_name', true );
		// Default is null or __( 'Parent Category' )
		$label_parent_item = get_post_meta( $post->ID, 'label_parent_item', true );
		// Default is null or __( 'Parent Category:' )
		$label_parent_item_colon = get_post_meta( $post->ID, 'label_parent_item_colon', true );
		// Default is __( 'Search Tags' ) or __( 'Search Categories' )
		$label_search_items = get_post_meta( $post->ID, 'label_search_items', true );
		// Default is __( 'Popular Tags' ) or null
		$label_popular_items = get_post_meta( $post->ID, 'label_popular_items', true );
		// Default is __( 'Separate tags with commas' ), or null
		$label_separate_items_with_commas = get_post_meta( $post->ID, 'label_separate_items_with_commas', true );
		// Default is __( 'Add or remove tags' ) or null
		$label_add_or_remove_items = get_post_meta( $post->ID, 'label_add_or_remove_items', true );
		// Default is __( 'Choose from the most used tags' ) or null
		$label_choose_from_most_used = get_post_meta( $post->ID, 'label_choose_from_most_used', true );
		// Default is __( 'No tags found.' ) or __( 'No categories found.' )
		$label_not_found = get_post_meta( $post->ID, 'label_not_found', true );

		// Advanced fields (check inputs)
		// Default: true
		$args_public = get_post_meta( $post->ID, 'args_public', true );
		// default settings of `$publicly_queryable`, `$show_ui`, and `$show_in_nav_menus` are inherited from `$public`.
		$args_publicly_queryable = get_post_meta( $post->ID, 'args_public', true );
		// default settings of `$publicly_queryable`, `$show_ui`, and `$show_in_nav_menus` are inherited from `$public`.
		$args_show_ui = get_post_meta( $post->ID, 'args_show_ui', true );
		// default settings of `$publicly_queryable`, `$show_ui`, and `$show_in_nav_menus` are inherited from `$public`.
		$args_show_in_menu = get_post_meta( $post->ID, 'args_show_in_menu', true );
		// also inherited from `$public`.
		$args_show_in_nav_menus = get_post_meta( $post->ID, 'args_show_in_nav_menus', true );
		// Default: if not set, defaults to value of show_ui argument
		$args_show_tagcloud = get_post_meta( $post->ID, 'args_show_tagcloud', true );
		// Default: if not set, defaults to value of show_ui argument
		$args_show_in_quick_edit = get_post_meta( $post->ID, 'args_show_in_quick_edit', true );
		// text input
		// Default: null
		$args_meta_box_cb = get_post_meta( $post->ID, 'args_meta_box_cb', true );
		// Default: false
		$args_show_admin_column = get_post_meta( $post->ID, 'args_show_admin_column', true );
		// Default: ''
		$args_description = get_post_meta( $post->ID, 'args_description', true );
		// Default: false
		$args_show_in_rest = get_post_meta( $post->ID, 'args_show_in_rest', true );
		// text input
		// Default: $taxonomy
		$args_rest_base = get_post_meta( $post->ID, 'args_rest_base', true );
		// Default: false
		$args_hierarchical = get_post_meta( $post->ID, 'args_hierarchical', true );
		// Default: $taxonomy (set false to disable...) if (true) get string()
		$args_query_var = get_post_meta( $post->ID, 'args_query_var', true );
		// Default: None
		$args_sort = get_post_meta( $post->ID, 'args_sort', true );
		// heading (text?)
		// Default: true
		$args_rewrite = get_post_meta( $post->ID, 'args_rewrite', true );
		// if($args_rewrite==false) disable
		$args_rewrite_slug = get_post_meta( $post->ID, 'args_rewrite_slug', true );
		// if($args_rewrite==false) disable
		$args_rewrite_with_front = get_post_meta( $post->ID, 'args_rewrite_with_front', true );
		// if($args_rewrite==false) disable
		$args_rewrite_hierarchical = get_post_meta( $post->ID, 'args_rewrite_hierarchical', true );
		// if($args_rewrite==false) disable // option_select
		// Default: EP_NONE/0
		$args_rewrite_ep_mask = get_post_meta( $post->ID, 'args_rewrite_ep_mask', true );

/** initialization/assignment is key to how it renders on the form
To initialize is to make ready for use. And when we're talking about a variable, that means giving the variable a first, useful value. 
And one way to do that is by using an assignment.

'$this' is a special variable that can't be assigned.
**/


		// Set Basic fields default values
		if( $label_name === '' ) $label_name = $post->post_title;
		if( $label_singular_name === '' ) $label_singular_name = 'Post Tag';
		if( empty( $args_taxonomy ) ) $args_taxonomy = $label_name;
//DEFAULTS LABELS
/**
name		Post Tags
singular_name	Post Tag
menu_name	name label
all_items	All Tags
edit_item	Edit Tag
view_item	View Tag
update_item	Update Tag
add_new_item	Add New Tag
new_item_name	New Tag Name
parent_item	null
parent_item_colon	null
search_items	Search Tags
popular_items	Popular Tags
separate_items_with_commas	Separate tags with commas
add_or_remove_items	Add or remove tags
choose_from_most_used	Choose from the most used tags
not_found	No tags found.
**/
		// Set Labels fields (text inputs) default values
		if( $label_menu_name === '' ) $label_menu_name = $label_name;
		if( $label_all_items === '' ) $label_all_items = 'All '.$label_name;
		if( $label_edit_item === ''  ) $label_edit_item = 'Edit '.$label_name;
		if( $label_view_item === ''  ) $label_view_item = 'View '.$label_name;
		if( $label_update_item === ''  ) $label_update_item = 'Update '.$label_name;
		if( $label_add_new_item === ''  ) $label_add_new_item = 'Add New '.$label_name;
		if( $label_new_item_name === ''  ) $label_new_item_name = 'New '.$label_name. 'Name';
		if( $label_parent_item === ''  ) $label_parent_item = null; // non-hierarchal default
		if( $label_parent_item_colon === ''  ) $label_parent_item_colon = null; // non-hierarchal default
		if( $label_search_items === ''  ) $label_search_items = 'Search '.$label_name;
		if( $label_popular_items === ''  ) $label_popular_items = 'Popular '.$label_name; // or null
		if( $label_separate_items_with_commas === ''  ) $label_separate_items_with_commas = 'Separate '.$label_name.' with commas'; // or null
		if( $label_add_or_remove_items === ''  ) $label_add_or_remove_items = 'Add or remove '.$label_name; // or null
		if( $label_choose_from_most_used === ''  ) $label_choose_from_most_used = 'Choose from the most used  '.$label_name; // or null
		if( $label_not_found === ''  ) $label_not_found = 'No  '.$label_name.' found.';
/**
		$defaults = array(
			'labels'                => array(),
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => null,
			'hierarchical'          => false,
			'show_ui'               => null,
			'show_in_menu'          => null,
			'show_in_nav_menus'     => null,
			'show_tagcloud'         => null,
			'show_in_quick_edit'    => null,
			'show_admin_column'     => false,
			'meta_box_cb'           => null,
			'meta_box_sanitize_cb'  => null,
			'capabilities'          => array(),
			'rewrite'               => true,
			'query_var'             => $this->name,
			'update_count_callback' => '',
			'show_in_rest'          => false,
			'rest_base'             => false,
			'rest_controller_class' => false,
			'_builtin'              => false,
		);
**/
		// Set Advanced fields (check inputs) default values
		if( empty( $args_public ) ) $args_public = 'checked';
		if( empty( $args_publicly_queryable ) ) $args_publicly_queryable = null;
		if( empty( $args_show_ui ) ) $args_show_ui = null;
		if( empty( $args_show_in_menu ) ) $args_show_in_menu = null;
		if( empty( $args_show_in_nav_menus ) ) $args_show_in_nav_menus = null;
		if( empty( $args_show_tagcloud ) ) $args_show_tagcloud = null;
		if( empty( $args_show_in_quick_edit ) ) $args_show_in_quick_edit = null;
		// text input
		if( empty( $args_meta_box_cb === '' ) ) $args_meta_box_cb = null;
		if( empty( $args_show_admin_column ) ) $args_show_admin_column = false;
		if( empty( $args_show_in_rest ) ) $args_show_in_rest = false;
		// text input
		if( empty( $args_description  === '' ) ) $args_description = '';
		// text input
		if( empty( $args_rest_base === '' ) ) $args_rest_base = false;
		if( empty( $args_hierarchical ) ) $args_hierarchical =  false;
		// Default: $taxonomy (set false to disable...) if (true) get string()
		if( empty( $args_query_var ) ) $args_query_var =  $label_name;
		if( empty( $args_sort ) ) $args_sort =  false;
		// false disables next 4
		if( empty( $args_rewrite ) ) $args_rewrite =  true;
		// text
		if( empty( $args_rewrite_slug ) ) $args_rewrite_slug = $args_taxonomy;
		if( empty( $args_rewrite_with_front ) ) $args_rewrite_with_front =  false;
		if( empty( $args_rewrite_hierarchical ) ) $args_rewrite_hierarchical =  false;
		if( empty( $args_rewrite_ep_mask ) ) $args_rewrite_ep_mask = 'EP_NONE';
		
		// Form fields.
		_e('<table class="form-table">');

		_e('<tr><th><label for="label_name" class="label-label_name">' . __( 'Name', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" maxlength="32" id="label_name" name="label_name" class="field-label_name" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_name . '"></td></tr>');

		_e('<tr><th><label for="label_singular_name" class="label-label_singular_name">' . __( 'Singular Name', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" maxlength="32" id="label_singular_name" name="label_singular_name" class="field-label_singular_name" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_singular_name . '"></td></tr>');

		_e('<tr><th><label for="args_taxonomy" class="label-args_taxonomy">' . __( 'Taxonomy', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" maxlength="32" id="args_taxonomy" name="args_taxonomy" class="field-args_taxonomy" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $args_taxonomy . '"></td></tr>');

		_e('<tr><th><label for="label_menu_name" class="label-label_menu_name">' . __( 'Menu Name', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_menu_name" name="label_menu_name" class="field-label_menu_name" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_menu_name . '"></td></tr>');

		_e('<tr><th><label for="label_all_items" class="label-label_all_items">' . __( 'All Items', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_all_items" name="label_all_items" class="field-label_all_items" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_all_items . '"></td></tr>');

		_e('<tr><th><label for="label_edit_item" class="label-label_edit_item">' . __( 'Edit Item', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_edit_item" name="label_edit_item" class="field-label_edit_item" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_edit_item . '"></td></tr>');

		_e('<tr><th><label for="label_view_item" class="label-label_view_item">' . __( 'View Item', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_view_item" name="label_view_item" class="field-label_view_item" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_view_item . '"></td></tr>');

		_e('<tr><th><label for="label_update_item" class="label-label_update_item">' . __( 'Update Item', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_update_item" name="label_update_item" class="field-label_update_item" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_update_item . '"></td></tr>');

		_e('<tr><th><label for="label_add_new_item" class="label-label_add_new_item">' . __( 'Add New Item', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_add_new_item" name="label_add_new_item" class="field-label_add_new_item" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_add_new_item . '"></td></tr>');

		_e('<tr><th><label for="label_new_item_name" class="label-label_new_item_name">' . __( 'New Item Name', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_new_item_name" name="label_new_item_name" class="field-label_new_item_name" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_new_item_name . '"></td></tr>');


		_e('<tr><th><label for="label_parent_item" class="label-label_parent_item">' . __( 'Parent Item', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_parent_item" name="label_parent_item" class="field-label_parent_item" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_parent_item . '"></td></tr>');

		_e('<tr><th><label for="label_parent_item_colon" class="label-label_parent_item_colon">' . __( 'Parent Item Colon', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_parent_item_colon" name="label_parent_item_colon" class="field-label_parent_item_colon" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_parent_item_colon . '"></td></tr>');

		_e('<tr><th><label for="label_search_items" class="label-label_search_items">' . __( 'Search Items', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_search_items" name="label_search_items" class="field-label_search_items" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_search_items . '"></td></tr>');


		_e('<tr><th><label for="label_popular_items" class="label-label_popular_items">' . __( 'Popular Items', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_popular_items" name="label_popular_items" class="field-label_popular_items" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . esc_attr__( $label_popular_items ) . '"></td></tr>');

		_e('<tr><th><label for="label_separate_items_with_commas" class="label-label_separate_items_with_commas">' . __( 'Separate Items with Commas', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_separate_items_with_commas" name="label_separate_items_with_commas" class="field-label_separate_items_with_commas" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_separate_items_with_commas . '"></td></tr>');

		_e('<tr><th><label for="label_add_or_remove_items" class="label-label_add_or_remove_items">' . __( 'Add or Remove Items', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_add_or_remove_items" name="label_add_or_remove_items" class="field-label_add_or_remove_items" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_add_or_remove_items . '"></td></tr>');

		_e('<tr><th><label for="label_choose_from_most_used" class="label-label_choose_from_most_used">' . __( 'Choose From Most Used', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_choose_from_most_used" name="label_choose_from_most_used" class="field-label_choose_from_most_used" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $label_choose_from_most_used . '"></td></tr>');

		_e('<tr><th><label for="label_not_found" class="label-label_not_found">' . __( 'Not Found', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="label_not_found" name="label_not_found" class="field-label_not_found" placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . esc_attr__( $label_not_found ) . '"></td></tr>');

		_e('<tr><th><label for="args_public" class="label-args_public">' . __( 'Public?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_public" name="args_public" class="field-args_public" value="' . $args_public . '" ' . checked( $args_public, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ) . '<span class="description">' . __( 'Public?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_publicly_queryable" class="label-args_publicly_queryable">' . __( 'Publicly Queryable?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_publicly_queryable" name="args_publicly_queryable" class="field-args_publicly_queryable" value="' . $args_publicly_queryable . '" ' . checked( $args_publicly_queryable, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ) . '<span class="description">' . __( 'Publicly Queryable?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_show_ui" class="label-args_show_ui">' . __( 'Show UI?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_ui" name="args_show_ui" class="field-args_show_ui" value="' . $args_show_ui . '" ' . checked( $args_show_ui, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show UI?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_show_in_menu" class="label-args_show_in_menu">' . __( 'Show in Menu?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_in_menu" name="args_show_in_menu" class="field-args_show_in_menu" value="' . $args_show_in_menu . '" ' . checked( $args_show_in_menu, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show in Menu?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_show_in_nav_menus" class="label-args_show_in_nav_menus">' . __( 'Show in Nav Menus?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_in_nav_menus" name="args_show_in_nav_menus" class="field-args_show_in_nav_menus" value="' . $args_show_in_nav_menus . '" ' . checked( $args_show_in_nav_menus, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show in Nav Menus?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_show_tagcloud" class="label-args_show_tagcloud">' . __( 'Show Tag Cloud?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_tagcloud" name="args_show_tagcloud" class="field-args_show_tagcloud" value="' . $args_show_tagcloud . '" ' . checked( $args_show_tagcloud, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show Tag Cloud?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_show_in_quick_edit" class="label-args_show_in_quick_edit">' . __( 'Show In Quick Edit?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_in_quick_edit" name="args_show_in_quick_edit" class="field-args_show_in_quick_edit" value="' . $args_show_in_quick_edit . '" ' . checked( $args_show_in_quick_edit, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show In Quick Edit?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_meta_box_cb" class="label-args_meta_box_cb">' . __( 'Metabox Callback', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="args_meta_box_cb" name="args_meta_box_cb" class="field-args_meta_box_cb"     placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $args_meta_box_cb . '"></td></tr>');

		_e('<tr><th><label for="args_show_admin_column" class="label-args_show_admin_column">' . __( 'Show Admin Column?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_admin_column" name="args_show_admin_column" class="field-args_show_admin_column" value="' . $args_show_admin_column . '" ' . checked( $args_show_admin_column, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show Admin Column?', $this->plugin_text_domain ) . '</span></td></tr>');


		_e('<tr><th><label for="args_description" class="label-args_description">' . __( 'Description', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="args_description" name="args_description" class="field-args_description"     placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $args_description . '"></td></tr>');



		_e('<tr><th><label for="args_show_in_rest" class="label-args_show_in_rest">' . __( 'Show in REST?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_show_in_rest" name="args_show_in_rest" class="field-args_show_in_rest" value="' . $args_show_in_rest . '" ' . checked( $args_show_in_rest, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Show in REST?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rest_base" class="label-args_rest_base">' . __( 'REST Base', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="text" id="args_rest_base" name="args_rest_base" class="field-args_rest_base"     placeholder="' . esc_attr__( '', $this->plugin_text_domain ) . '" value="' . $args_rest_base . '"></td></tr>');

		_e('<tr><th><label for="args_hierarchical" class="label-args_hierarchical">' . __( 'Hierarchical?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_hierarchical" name="args_hierarchical" class="field-args_hierarchical" value="' . $args_hierarchical . '" ' . checked( $args_hierarchical, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Hierarchical?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_query_var" class="label-args_query_var">' . __( 'Query Var?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_query_var" name="args_query_var" class="field-args_query_var" value="' . $args_query_var . '" ' . checked( $args_query_var, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Query Var?', $this->plugin_text_domain ) . '</span></td></tr>');


		_e('<tr><th><label for="args_sort" class="label-args_sort">' . __( 'Sort?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_sort" name="args_sort" class="field-args_sort" value="' . $args_sort . '" ' . checked( $args_sort, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Sort?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rewrite" class="label-args_rewrite">' . __( 'Rewrite Array?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_rewrite" name="args_rewrite" class="field-args_rewrite" value="' . $args_rewrite . '" ' . checked( $args_rewrite, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Rewrite Array?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rewrite_slug" class="label-args_rewrite_slug">' . __( 'Rewrite Slug?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_rewrite_slug" name="args_rewrite_slug" class="field-args_rewrite_slug" value="' . $args_rewrite_slug . '" ' . checked( $args_rewrite_slug, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Rewrite Slug?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rewrite_with_front" class="label-args_rewrite_with_front">' . __( 'Rewrite with Front?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_rewrite_with_front" name="args_rewrite_with_front" class="field-args_rewrite_with_front" value="' . $args_rewrite_with_front . '" ' . checked( $args_rewrite_with_front, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Rewrite With Front?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rewrite_hierarchical" class="label-args_rewrite_hierarchical">' . __( 'Rewrite Hierarchical?', $this->plugin_text_domain ) . '</label></th>');
		_e('<td><input type="checkbox" id="args_rewrite_hierarchical" name="args_rewrite_hierarchical" class="field-args_rewrite_hierarchical" value="' . $args_rewrite_hierarchical . '" ' . checked( $args_rewrite_hierarchical, 'checked', false ) . '> ' . __( '', $this->plugin_text_domain ).'<span class="description">' . __( 'Rewrite Hierarchical?', $this->plugin_text_domain ) . '</span></td></tr>');

		_e('<tr><th><label for="args_rewrite_ep_mask" class="label-args_rewrite_ep_mask">' . __( 'Rewrite EP Mask', $this->plugin_text_domain ) . '</label></th>');
		_e('<td>
			<select id="args_rewrite_ep_mask" name="args_rewrite_ep_mask" class="field-args_rewrite_ep_mask" value="' . $args_rewrite_ep_mask . '">
			  <option class="options-args_rewrite_ep_mask" value="0" selected>EP_NONE</option>
			  <option class="options-args_rewrite_ep_mask" value="1">EP_PERMALINK </option>
			  <option class="options-args_rewrite_ep_mask" value="2">EP_ATTACHMENT</option>
			  <option class="options-args_rewrite_ep_mask" value="4">EP_DATE</option>
			  <option class="options-args_rewrite_ep_mask" value="8">EP_YEAR</option>
			  <option class="options-args_rewrite_ep_mask" value="16">EP_MONTH</option>
			  <option class="options-args_rewrite_ep_mask" value="32">EP_DAY</option>
			  <option class="options-args_rewrite_ep_mask" value="64">EP_ROOT</option>
			  <option class="options-args_rewrite_ep_mask" value="128">EP_COMMENTS</option>
			  <option class="options-args_rewrite_ep_mask" value="256">EP_SEARCH</option>
			  <option class="options-args_rewrite_ep_mask" value="512">EP_CATEGORIES</option>
			  <option class="options-args_rewrite_ep_mask" value="1024">EP_TAGS</option>
			  <option class="options-args_rewrite_ep_mask" value="2048">EP_AUTHORS</option>
			  <option class="options-args_rewrite_ep_mask" value="4096">EP_PAGES</option>
			  <option class="options-args_rewrite_ep_mask" value="8192">EP_ALL_ARCHIVES</option>
			  <option class="options-args_rewrite_ep_mask" value="16384">EP_ALL</option>
			</select>
			</td></tr></table>');

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		if ( ! isset( $_POST['ea_taxonomies_nonce'] ) ) 
			{ print 'Nonce Not in $_POST'; exit; }
		else{ $nonce_name   = $_POST['ea_taxonomies_nonce']; }
		
		if ( ! wp_verify_nonce( $_POST['ea_taxonomies_nonce'], 'ea_taxonomies_nonce_action' ) )
			{ print 'Nonce Action Not in $_POST';  exit;  }
		else{ $nonce_action = 'ea_taxonomies_nonce_action'; }

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

/** empty() vs isset() vs array_key_exists() vs in_array()
Before a variable is used, it has no existence. It is unset.
isset() - Determine if a variable is declared and is different than NULL
 isset() language construct can be used to detect if a variable has been already initialized
With the exception of null, the value a variable holds plays no part in determining whether a variable is set.
variables may be destroyed by using the unset() construct.
The first time that a variable is used in a scope, it's automatically created. After this isset is true. 
At the point at which it is created it also receives a type according to the context.
If it is used without having been given a value then it is uninitalized and it receives the default value for the type. 
The default values are the _empty_ values. E.g  Booleans default to FALSE, integers and floats default to zero, strings to the empty string '', arrays to the empty array.
A variable can be tested for emptiness using empty();
Unset variables are also empty.

**/
		// Sanitize user input.
		$new_label_name = isset( $_POST[ 'label_name' ] ) ? sanitize_text_field( $_POST[ 'label_name' ] ) : '';
		$new_label_singular_name = isset( $_POST[ 'label_singular_name' ] ) ? sanitize_text_field( $_POST[ 'label_singular_name' ] ) : '';
/////////////////////////////////////////
		$new_args_taxonomy = isset( $_POST[ 'args_taxonomy' ] ) ? sanitize_text_field( $_POST[ 'args_taxonomy' ] ) : '';
/////////////////////////////////////////
		$new_label_menu_name = isset( $_POST[ 'label_menu_name' ] ) ? sanitize_text_field( $_POST[ 'label_menu_name' ] ) : '';
		$new_label_all_items = isset( $_POST[ 'label_all_items' ] ) ? sanitize_text_field( $_POST[ 'label_all_items' ] ) : '';
		$new_label_edit_item = isset( $_POST[ 'label_edit_item' ] ) ? sanitize_text_field( $_POST[ 'label_edit_item' ] ) : '';
		$new_label_view_item = isset( $_POST[ 'label_view_item' ] ) ? sanitize_text_field( $_POST[ 'label_view_item' ] ) : '';
		$new_label_update_item = isset( $_POST[ 'label_update_item' ] ) ? sanitize_text_field( $_POST[ 'label_update_item' ] ) : '';
		$new_label_add_new_item = isset( $_POST[ 'label_add_new_item' ] ) ? sanitize_text_field( $_POST[ 'label_add_new_item' ] ) : '';
		$new_label_new_item_name = isset( $_POST[ 'label_new_item_name' ] ) ? sanitize_text_field( $_POST[ 'label_new_item_name' ] ) : '';
		$new_label_parent_item = isset( $_POST[ 'label_parent_item' ] ) ? sanitize_text_field( $_POST[ 'label_parent_item' ] ) : '';
		$new_label_parent_item_colon = isset( $_POST[ 'label_parent_item_colon' ] ) ? sanitize_text_field( $_POST[ 'label_parent_item_colon' ] ) : '';
		$new_label_search_items = isset( $_POST[ 'label_search_items' ] ) ? sanitize_text_field( $_POST[ 'label_search_items' ] ) : '';
		$new_label_popular_items = isset( $_POST[ 'label_popular_items' ] ) ? sanitize_text_field( $_POST[ 'label_popular_items' ] ) : '';
		$new_label_separate_items_with_commas = isset( $_POST[ 'label_separate_items_with_commas' ] ) ? sanitize_text_field( $_POST[ 'label_separate_items_with_commas' ] ) : '';
		$new_label_add_or_remove_items = isset( $_POST[ 'label_add_or_remove_items' ] ) ? sanitize_text_field( $_POST[ 'label_add_or_remove_items' ] ) : '';
		$new_label_choose_from_most_used = isset( $_POST[ 'label_choose_from_most_used' ] ) ? sanitize_text_field( $_POST[ 'label_choose_from_most_used' ] ) : '';
		$new_label_not_found = isset( $_POST[ 'label_not_found' ] ) ? sanitize_text_field( $_POST[ 'label_not_found' ] ) : '';
/////////////////////////////////////////
		$new_args_public = isset( $_POST[ 'args_public' ] ) ? 'checked' : '';
		$new_args_publicly_queryable = isset( $_POST[ 'args_publicly_queryable' ] ) ? 'checked' : '';
		$new_args_show_ui = isset( $_POST[ 'args_show_ui' ] ) ? 'checked' : '';
		$new_args_show_in_menu = isset( $_POST[ 'args_show_in_menu' ] ) ? 'checked' : '';
		$new_args_show_in_nav_menus = isset( $_POST[ 'args_show_in_nav_menus' ] ) ? 'checked' : '';
		$new_args_show_tagcloud = isset( $_POST[ 'args_show_tagcloud' ] ) ? 'checked' : '';
		$new_args_show_in_quick_edit = isset( $_POST[ 'args_show_in_quick_edit' ] ) ? 'checked' : '';
/** text **/	$new_args_meta_box_cb = isset( $_POST[ 'args_meta_box_cb' ] ) ? sanitize_text_field( $_POST[ 'args_meta_box_cb' ] ) : '';
		$new_args_show_admin_column = isset( $_POST[ 'args_show_admin_column' ] ) ? 'checked' : '';
/** text **/	$new_args_description = isset( $_POST[ 'args_description' ] ) ? sanitize_text_field( $_POST[ 'args_description' ] ) : '';
		$new_args_show_in_rest = isset( $_POST[ 'args_show_in_rest' ] ) ? 'checked' : '';
/** text **/	$new_args_rest_base = isset( $_POST[ 'args_rest_base' ] ) ? sanitize_text_field( $_POST[ 'args_rest_base' ] ) : '';
		$new_args_hierarchical = isset( $_POST[ 'args_hierarchical' ] ) ? 'checked' : '';
		$new_args_query_var = isset( $_POST[ 'args_query_var' ] ) ? 'checked' : '';
		$new_args_sort = isset( $_POST[ 'args_sort' ] ) ? 'checked' : '';
/** text **/	$new_args_rewrite = isset( $_POST[ 'args_rewrite' ] ) ? 'checked' : '';
		$new_args_rewrite_slug = isset( $_POST[ 'args_rewrite_slug' ] ) ? 'checked' : '';
		$new_args_rewrite_with_front = isset( $_POST[ 'args_rewrite_with_front' ] ) ? 'checked' : '';
		$new_args_rewrite_hierarchical = isset( $_POST[ 'args_rewrite_hierarchical' ] ) ? 'checked' : '';
/////////////////////////////////////////

		// Update the meta field in the database.
		update_post_meta( $post_id, 'label_name', $new_label_name );
		update_post_meta( $post_id, 'label_singular_name', $new_label_singular_name );
/////////////////////////////////////////
		update_post_meta( $post_id, 'args_taxonomy', $new_args_taxonomy );
/////////////////////////////////////////
		update_post_meta( $post_id, 'label_menu_name', $new_label_menu_name );
		update_post_meta( $post_id, 'label_all_items', $new_label_all_items );
		update_post_meta( $post_id, 'label_edit_item', $new_label_edit_item );
		update_post_meta( $post_id, 'label_view_item', $new_label_view_item );
		update_post_meta( $post_id, 'label_update_item', $new_label_update_item );
		update_post_meta( $post_id, 'label_add_new_item', $new_label_add_new_item );
		update_post_meta( $post_id, 'label_new_item_name', $new_label_new_item_name );
		update_post_meta( $post_id, 'label_parent_item', $new_label_parent_item );
		update_post_meta( $post_id, 'label_parent_item_colon', $new_label_parent_item_colon );
		update_post_meta( $post_id, 'label_search_items', $new_label_search_items );
		update_post_meta( $post_id, 'label_popular_items', $new_label_popular_items );
		update_post_meta( $post_id, 'label_separate_items_with_commas', $new_label_separate_items_with_commas );
		update_post_meta( $post_id, 'label_add_or_remove_items', $new_label_add_or_remove_items );
		update_post_meta( $post_id, 'label_choose_from_most_used', $new_label_choose_from_most_used );
		update_post_meta( $post_id, 'label_not_found', $new_label_not_found );
/////////////////////////////////////////
		update_post_meta( $post_id, 'args_public', $new_args_public );
		update_post_meta( $post_id, 'args_publicly_queryable', $new_args_publicly_queryable );
		update_post_meta( $post_id, 'args_show_ui', $new_args_show_ui );
		update_post_meta( $post_id, 'args_show_in_menu', $new_args_show_in_menu );
		update_post_meta( $post_id, 'args_show_in_nav_menus', $new_args_show_in_nav_menus );
		update_post_meta( $post_id, 'args_show_tagcloud', $new_args_show_tagcloud );
		update_post_meta( $post_id, 'args_show_in_quick_edit', $new_args_show_in_quick_edit );
		update_post_meta( $post_id, 'args_meta_box_cb', $new_args_meta_box_cb );
		update_post_meta( $post_id, 'args_show_admin_column', $new_args_show_admin_column );
		update_post_meta( $post_id, 'args_description', $new_args_description );
		update_post_meta( $post_id, 'args_show_in_rest', $new_args_show_in_rest );
		update_post_meta( $post_id, 'args_rest_base', $new_args_rest_base );
		update_post_meta( $post_id, 'args_hierarchical', $new_args_hierarchical );
		update_post_meta( $post_id, 'args_query_var', $new_args_query_var );
		update_post_meta( $post_id, 'args_sort', $new_args_sort );
		update_post_meta( $post_id, 'args_rewrite', $new_args_rewrite );
		update_post_meta( $post_id, 'args_rewrite_slug', $new_args_rewrite_slug );
		update_post_meta( $post_id, 'args_rewrite_with_front', $new_args_rewrite_with_front );
		update_post_meta( $post_id, 'args_rewrite_hierarchical', $new_args_rewrite_hierarchical );
	}

} // END EA_Metabox{}
