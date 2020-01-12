<?php

namespace EA_Taxonomies\Admin;

// function as the menu callback and setup the 
// menus automatically. 
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
class EA_Static_Admin_Page {
 
    	/**
     	* Main Instance
     	*
     	* @staticvar   array   $instance
     	* @return      The one true instance
     	*/

	private static $instance = null;

    	public static function instance() {
		// ( ! isset( self::$instance ) )
         	if  (self::$instance == null) {
             		self::$instance = new self;
         		self::$instance->addMyAdminMenu();
    		}
 
        	return self::$instance;
    	}
 	
	// callback
    	public function addMyAdminMenu() {
      
         	add_menu_page(
            		'My Page Title',
            		'My Page',
            		'read',
            		'my-menu-page-slug',
            		array(
                		$this,
                		'myAdminPage'
            		),
			'none',
            		'2.1'
        	);

    	}
 
    	public function myAdminPage() {
        // here is where the form is built for custom taxonomy data 
		echo "<strong>here is where the form is built for custom taxonomy data </strong>";
    	}
 
}

/** 
Admin\EA_Static_Admin_Page::instance();
--
running the static method instance() which calls addMyAdminMenu(), 
which inturn calls wordpress method add_menu_page() 
--
--produces:
Fatal error: Uncaught Error: Call to undefined function EA_Taxonomies\Admin\add_menu_page() 
**/
