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

// Setup class autoloader
require_once  trailingslashit( dirname( __FILE__ ) ) . '/src/eaTaxonomies/Autoloader.php';
eaTaxonomies_Autoloader::register();
 
$eaTaxonomies = new eaTaxonomies_Plugin(__FILE__);
add_action('wp_loaded', array($eaTaxonomies, 'load'));

// https://stackoverflow.com/questions/10542012/php-namespaces-and-use
// https://code.tutsplus.com/tutorials/using-namespaces-and-autoloading-in-wordpress-plugins-4--cms-27342
