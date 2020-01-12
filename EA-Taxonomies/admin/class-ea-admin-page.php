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
//             		'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 33.866666 33.866668" preserveAspectRatio="xMidYMid meet"><g style="display:inline" transform="translate(0,-263.13332)" id="layer1"><path d="m 2.1166667,287.47499 7.408333,-7.40834" stroke="#e00000" fill="none" stroke-width="3.17499995" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-dasharray="none" stroke-opacity="1"/><path d="m 16.933333,272.65832 14.816666,14.81667" stroke="#e400c0" fill="none" stroke-width="3.17499995" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-dasharray="none" stroke-opacity="1"/><path d="m 16.933333,272.65832 -7.4083333,7.40833 7.4083333,7.40834" stroke="#ffff00" fill="none" stroke-width="3.17499995" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-dasharray="none" stroke-opacity="1"/><path d="M 9.5249997,280.06665 H 24.341666" stroke="#ff9c00" fill="none" stroke-width="3.17499995" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-dasharray="none" stroke-opacity="1"/></g></svg>'),
            		'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMCIgdmlld0JveD0iMCAwIDMzLjg2NjY2NiAzMy44NjY2NjgiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPjxnIHN0eWxlPSJkaXNwbGF5OmlubGluZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCwtMjYzLjEzMzMyKSIgaWQ9ImxheWVyMSI+PHBhdGggZD0ibSAyLjExNjY2NjcsMjg3LjQ3NDk5IDcuNDA4MzMzLC03LjQwODM0IiBzdHJva2U9IiNlMDAwMDAiIGZpbGw9Im5vbmUiIHN0cm9rZS13aWR0aD0iMy4xNzQ5OTk5NSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2UtbWl0ZXJsaW1pdD0iNCIgc3Ryb2tlLWRhc2hhcnJheT0ibm9uZSIgc3Ryb2tlLW9wYWNpdHk9IjEiLz48cGF0aCBkPSJtIDE2LjkzMzMzMywyNzIuNjU4MzIgMTQuODE2NjY2LDE0LjgxNjY3IiBzdHJva2U9IiNlNDAwYzAiIGZpbGw9Im5vbmUiIHN0cm9rZS13aWR0aD0iMy4xNzQ5OTk5NSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2UtbWl0ZXJsaW1pdD0iNCIgc3Ryb2tlLWRhc2hhcnJheT0ibm9uZSIgc3Ryb2tlLW9wYWNpdHk9IjEiLz48cGF0aCBkPSJtIDE2LjkzMzMzMywyNzIuNjU4MzIgLTcuNDA4MzMzMyw3LjQwODMzIDcuNDA4MzMzMyw3LjQwODM0IiBzdHJva2U9IiNmZmZmMDAiIGZpbGw9Im5vbmUiIHN0cm9rZS13aWR0aD0iMy4xNzQ5OTk5NSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2UtbWl0ZXJsaW1pdD0iNCIgc3Ryb2tlLWRhc2hhcnJheT0ibm9uZSIgc3Ryb2tlLW9wYWNpdHk9IjEiLz48cGF0aCBkPSJNIDkuNTI0OTk5NywyODAuMDY2NjUgSCAyNC4zNDE2NjYiIHN0cm9rZT0iI2ZmOWMwMCIgZmlsbD0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIzLjE3NDk5OTk1IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS1taXRlcmxpbWl0PSI0IiBzdHJva2UtZGFzaGFycmF5PSJub25lIiBzdHJva2Utb3BhY2l0eT0iMSIvPjwvZz48L3N2Zz4=',
            		'2.1'
        	);

    	}
 
    	public function myAdminPage() {
        // here is where the form is built for custom taxonomy data 
		echo "<strong>here is where the form is built for custom taxonomy data </strong>";
    	}
 
}
