<?php
/*
 * Plugin Name: 	EA Taxonomies
 * Plugin URI:        https://elementarray.com/ea-taxonomies/
 * Description:       create, read, update, delete taxonomies
 * Version:           0.1
 * Author:            Element Array
 * Author URI:        https://elementarray.com/author/eaadmin/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       ea
 * Domain Path:       /languages
 */

/**
The use keyword must be declared in the outermost scope of a file (the global scope) or inside namespace declarations. 
This is because the importing is done at compile time and not runtime, so it cannot be block scoped.
**/
///////////////////////////////////////
use EA_Taxonomies\Admin;
use EA_Taxonomies\Admin\Util;
/////////////////////////////////////////

// If this file is accessed directory, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Setup class autoloader
require_once  trailingslashit( dirname( __FILE__ ) ) . 'inc/class-autoloader.php'; //? inc?
Autoloader::register(); /** eaTaxonomies_Autoloader **/

/**
The constructor function runs as soon as the class is created 
which means that this is the place to put all our actions and filters:

when you use hooks in classes you can’t simply specify the name of the function, 
you need to use the array( $this, 'name_of_function' ) convention. 
This is because the function we are referring to is within the class, not in the global space.
**/

$eaTaxonomies = new Admin\eaTaxonomies(__FILE__);
$css_loader = new Util\CSS_Loader();

