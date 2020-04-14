<?php
// class-register-custom-taxonomies.php
namespace EA_Taxonomies\Admin;
use EA_Taxonomies as NS;

class Register_Custom_Taxonomies {
	private static $plugin_name;
	private static $plugin_version;
	private static $plugin_text_domain;

  	static function init() {
		self::$plugin_name = NS\PLUGIN_NAME;
		self::$plugin_version = NS\PLUGIN_VERSION;
		self::$plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		self::register();
	}

	public static function register( ) {

		$taxonomies = self::get_taxonomies();  // calling get_taxonomies()
		
		foreach ( $taxonomies as $taxonomy => $args ) {
			// THIS IS THE MONEY
			// Register_taxonomy adds $taxonomy to the global variable $wp_taxonomies  // taxonomy.php line 461

			if(isset( $args['post_types'] )){
				$post_types_array = $args['post_types'];
				unset($args['post_types']);
				unset($args['label']);
				unset($args['rewrite_array']);
	
				$post_types_unserialized_array = unserialize(urldecode($post_types_array));
	    			// var_dump($arr);
	
				register_taxonomy( $taxonomy, $post_types_unserialized_array , $args );
			}
		}

	} // END static function register()

	public static function get_taxonomies() { 							// called 1st
		// This array stores all cpt ea-taxonomies instances
		$taxonomies = array();
		// Get all post where where post_type = ea-taxonomies.
		$ea_taxonomies = get_posts(
			array(
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'post_type'      => 'ea-taxonomies',
			)
		);

		foreach ( $ea_taxonomies as $taxonomy ) {

			list( $labels, $args ) = self::get_taxonomy_data( $taxonomy->ID ); 		// called 2nd

			$taxonomies[ $args['taxonomy'] ] = self::set_up_taxonomy( $labels, $args ); 	// called 3rd
		}
		// print_r($taxonomies); // at this point everything is in...
		return $taxonomies;
	}

	public static function get_taxonomy_data( $ea_cpt_id ) {
		// Get all post meta from current post.
		$post_meta = get_post_meta( $ea_cpt_id );
		// Create array that contains Labels of this current custom taxonomy.
		$labels = array();
		// Create array that contains arguments of this current custom taxonomy.
		$args = array();

		foreach ( $post_meta as $key => $value ) {
			if ( false !== strpos( $key, 'label' ) ) {
				// If post meta has prefix 'label' then add it to $labels.
				// @codingStandardsIgnoreLine
				// make sure single value not array
				//$data = 1 == count( $value ) ? $value[0] : $value;

				$labels[ str_replace( 'label_', '', $key ) ] = $value[0];//$data;
				// echo "labels=".$labels[str_replace( 'label_', '', $key )];
				// echo "<br/>";

			} elseif ( false !== strpos( $key, 'args' ) ) {
				// If post meta has prefix 'args' then add it to $args.
				// @codingStandardsIgnoreLine
				// make sure single value not array
				//$data = 1 == count( $value ) ? $value[0] : $value;
				// check for checkbox (t||f)
				//$data = is_numeric( $data ) ? ( 1 === intval( $data ) ? true : false ) : $data;
				
				$args[ str_replace( 'args_', '', $key ) ] = $value[0];//$data;
				// echo "args=".$args[str_replace( 'args_', '', $key )];
				// echo "<br/>";			
			}
		}
		return array( $labels, $args );
}

	/** Setup labels, arguments for a custom taxonomy **/
	public static function set_up_taxonomy( $labels = array(), $args = array() ) {
		$labels = wp_parse_args(
			$labels,
			array(
				'menu_name'                  => $labels['name'],
				// translators: %s: Name of the taxonomy in plural form.
				'all_items'                  => sprintf( __( 'All %s', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'edit_item'                  => sprintf( __( 'Edit %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'view_item'                  => sprintf( __( 'View %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'update_item'                => sprintf( __( 'Update %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'add_new_item'               => sprintf( __( 'Add new %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'new_item_name'              => sprintf( __( 'New %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'parent_item'                => sprintf( __( 'Parent %s', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in singular form.
				'parent_item_colon'          => sprintf( __( 'Parent %s:', self::$plugin_text_domain ), $labels['singular_name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'search_items'               => sprintf( __( 'Search %s', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'popular_items'              => sprintf( __( 'Popular %s', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'add_or_remove_items'        => sprintf( __( 'Add or remove %s', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'choose_from_most_used'      => sprintf( __( 'Choose most used %s', self::$plugin_text_domain ), $labels['name'] ),
				// translators: %s: Name of the taxonomy in plural form.
				'not_found'                  => sprintf( __( 'No %s found', self::$plugin_text_domain ), $labels['name'] ),
			)
		);
		$args   = wp_parse_args(
			$args,
			array(
				'label'  => $labels['name'],
				'labels' => $labels,
				'public' => true, // ADDS PUBLIC TO ARGS
			)
		);

		if ( empty( $args['rewrite_slug'] ) && empty( $args['rewrite_with_front'] ) ) {
			$args['rewrite'] = true;
		} else {
			$rewrite = array();
			if ( ! empty( $args['rewrite_slug'] ) ) { $rewrite['slug'] = $args['rewrite_slug']; }
			if ( ! empty( $args['rewrite_with_front'] ) ) { $rewrite['with_front'] = false; }
			if ( ! empty( $args['rewrite_hierarchical'] ) ) { $rewrite['hierarchical'] = true; }
			$args['rewrite'] = $rewrite;
			unset( $args['rewrite_slug'] );
			unset( $args['rewrite_no_front'] );
			unset( $args['rewrite_hierarchical'] );
		}

		unset( $args['taxonomy'] ); // REMOVES TAXONOMY ARG
		return $args;
	}

} // END CLASS