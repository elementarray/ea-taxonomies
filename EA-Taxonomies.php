<?php
/**
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
**/
namespace EA_Taxonomies;
// If this file is called directly, abort.
/**
define( 'ABSPATH', dirname(dirname(__FILE__)) . '/' );
define( 'WPINC', 'wp-includes' );
**/
if ( ! defined( 'WPINC' ) ) 	{ die; 	}
if ( ! defined( 'ABSPATH' ) ) 	{ exit; }

/** Define Constants **/
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );			// 'EA_Taxonomies\\'
define( NS . 'PLUGIN_NAME', 'ea-taxonomies' ); 				// 'ea-taxonomies'
define( NS . 'PLUGIN_VERSION', '0.0.1' );				// '0.0.1'
define( NS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );		// wrapper for trailingslashit( dirname( $file ) );
define( NS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );		// The URL of the directory that contains the plugin, including a trailing slash ("/")
define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );		// Path to a plugin file or directory, relative to the plugins directory (without the leading and trailing slashes).
define( NS . 'PLUGIN_TEXT_DOMAIN', 'ea' );				// 'ea'

/** Autoload Classes **/
require_once( PLUGIN_NAME_DIR . 'inc/class-autoloader.php' ); //? inc?

Inc\Autoloader::register();

Admin\Register_Post_Type_EA_Taxonomy::init();
Admin\Register_Custom_Taxonomies::init();


// Change the output of post/bulk post updated messages.
//add_filter( 'post_updated_messages', array( $this, 'updated_message' ), 10, 1 );
//add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_updated_messages' ), 10, 2 );

/** Plugin Singleton Container **/
class EA_Taxonomies {

	static $init;
	/**
	 * Loads the plugin
	 *
	 * @access    public
	 */
	public static function init() {

		if ( null == self::$init ) {
			self::$init = new Util\Init();
			self::$init->run();
		}

		return self::$init;
	}

}

/*
 * Begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Also returns copy of the app object so 3rd party developers
 * can interact with the plugin's hooks contained within.
 *
 */
function ea_taxonomies_init() {
	return EA_Taxonomies::init();
}
	
$min_php = '5.6.0';
	
// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
	ea_taxonomies_init();
}else{die ("version 5.6.0 or greater required for this plugin to run");}

class Load_Language {
	public function __construct(){
    		add_action('init', array($this, 'load_ea_translations'));
    	}

	public function load_ea_translations(){
        	load_plugin_textdomain(PLUGIN_TEXT_DOMAIN, FALSE, PLUGIN_NAME_DIR .'/languages/');
    	}
}

$ea_lang = new Load_Language;

?>

