<?php
/**
 * Class for registering a new settings page under Settings.
 */

namespace EA_Taxonomies\Admin;

class EA_Taxonomy_Options_Page {
 
    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
    }
 
    /**
     * Registers a new settings page under Settings.
     */
    public function admin_menu() {
        add_options_page(
            __( 'EA Taxonomies Options Page', 'textdomain' ),
            __( 'EA Taxonomies Options', 'textdomain' ),
            'manage_options',
            'options_page_slug',
            array(
                &$this,
                'settings_page'
            )
        );
    }
 
    /**
     * Settings page display callback.
     */
    public function settings_page() {
        echo __( 'This is the page content', 'textdomain' );
    }
}

// new EA_Taxonomy_Options_Page;