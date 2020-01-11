<?php

namespace EA_Taxonomies\Admin;

// function as the menu callback and setup the 
// menus automatically. 
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
class EA_Admin_Page {
 
    	/**
     	* Main Instance
     	*
     	* @staticvar   array   $instance
     	* @return      The one true instance
     	*/

	//private static $instance = null;

/**
yes, it's impossible - a non-static method needs an object to read data from, while the point of a static one is that it has no such object attached. you can think of each non-static method of being passed an implicit argument, the object. you simply don't have a value to pass this value on to the method if you're calling from a static function.
**/

/**
    	public static function instance() {
		// ( ! isset( self::$instance ) )
         	if  (self::$instance == null) {
             		self::$instance = new self;
         		self::$instance->addMyAdminMenu();
    		}
 
        	return self::$instance;
    	}
**/

/**
// On this line:
add_action('admin_menu', array($this, 'addMyAdminMenu'));

//You are correctly passing the create_menu_item method of the current class to callback argument of the add_action() function. 
//You just need to apply the same principle for the callbacks in the add_menu_page() function:

add_menu_page( 
    $value->post_title, 			// 'My Page Title'
    $value->post_title, 			// 'My Page'
    'manage_options', 				// 'read'
    $value->post_name.'-admin-page.php', 	// 'my-menu-page-slug'
    array( $this, 'addMyAdminMenu' ), 		// <-- Here
    'svg code', 
    6 						// menu priority  
);
//This will apply to any WordPress function that has a 'callback' as a parameter. 
// You can read more about callbacks in PHP here:
// http://php.net/manual/en/language.types.callable.php
**/

	public function __construct() {
		add_action('admin_menu', array(&$this, 'addMyAdminMenu'));
	}
 
    	public function addMyAdminMenu() {
      
         	add_menu_page(
            		'My Page Title',
            		'My Page',
            		'read',
            		'my-menu-page-slug',
            		array(
                		&$this,
                		'myAdminPage'
            		),
//             		'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M 9.5249997,280.06665 H 24.341666 m -7.408333,-7.40833 -7.4083333,7.40833 7.4083333,7.40834 m 0,-14.81667 14.816666,14.81667 m -29.6333323,0 7.408333,-7.40834" /></svg>'),
            		'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMCIgdmlld0JveD0iMCAwIDMzLjg2NjY2NiAzMy44NjY2NjgiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPjxnIHN0eWxlPSJkaXNwbGF5OmlubGluZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCwtMjYzLjEzMzMyKSIgaWQ9ImxheWVyMSI+IDxwYXRoIGlkPSJwYXRoNDUyNC02LTciIGQ9Im0gMi4xMTY2NjY3LDI4Ny40NzQ5OSA3LjQwODMzMywtNy40MDgzNCIgc3R5bGU9ImZpbGw6bm9uZTtzdHJva2U6I2UwMDAwMDtzdHJva2Utd2lkdGg6My4xNzQ5OTk5NTtzdHJva2UtbGluZWNhcDpyb3VuZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6NDtzdHJva2UtZGFzaGFycmF5Om5vbmU7c3Ryb2tlLW9wYWNpdHk6MSIgLz48cGF0aCBpZD0icGF0aDQ1MjQiIGQ9Im0gMTYuOTMzMzMzLDI3Mi42NTgzMiAxNC44MTY2NjYsMTQuODE2NjciIHN0eWxlPSJmaWxsOm5vbmU7c3Ryb2tlOiNlNDAwYzA7c3Ryb2tlLXdpZHRoOjMuMTc0OTk5OTU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1vcGFjaXR5OjEiIC8+PHBhdGggaWQ9InBhdGgzNzE1IiBkPSJtIDE2LjkzMzMzMywyNzIuNjU4MzIgLTcuNDA4MzMzMyw3LjQwODMzIDcuNDA4MzMzMyw3LjQwODM0IiBzdHlsZT0iZmlsbDpub25lO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTojZmZmZjAwO3N0cm9rZS13aWR0aDozLjE3NDk5OTk1O3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIiAvPjxwYXRoIGlkPSJwYXRoNDUyMiIgZD0iTSA5LjUyNDk5OTcsMjgwLjA2NjY1IEggMjQuMzQxNjY2IiBzdHlsZT0iZmlsbDpub25lO3N0cm9rZTojZmY5YzAwO3N0cm9rZS13aWR0aDozLjE3NDk5OTk1O3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIiAvPjwvZz48L3N2Zz4=',
            		'2.1'
        	);

    	}
 
    	public function myAdminPage() {
        // here is where the form is built for custom taxonomy data 
		echo "<strong>here is where the form is built for custom taxonomy data </strong>";
    	}
 
}
