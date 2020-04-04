<?php
namespace EA_Taxonomies\Inc;
use EA_Taxonomies as NS;
/**
Writing an autoloader will require the following:

1 - understanding a PHP function called spl_autoload_register
2 - writing a function that will automatically load our namespaced files
3 - including our custom autoloading function

When writing an autoloader, the key is how our files are organized; 
how to map our namespaces to our directories.

When PHP attempts to load a class, our autoloader is going to need to do the following:

1 - Split the namespace up based on the slashes.
2 - Split the package and the subpackages up based on underscores and replace them with hyphens (if necessary).
3 - Know how to map class names, interfaces, and so on to file names.
4 - Create a string representation of the filename based on the above information.
5 - Include the file.
**/
 
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: TutsPlus_Namespace_Demo\Namespace. The
 * function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
/////////////////////////////////////////////////////////////////////////////////////
// set up a loop that's going to iterate backwards through the parts of the filename 
// that are passed into the autoloading function
///////////////////////////////////////////////////////////////////////////////////////

class Autoloader { 
	/** Registers eaTaxonomies_Autoloader as an SPL autoloader **/
	// @param boolean $prepend
    	public static function register($prepend = false) {
        	if (version_compare(phpversion(), '5.3.0', '>=')) {
            		spl_autoload_register(array(new self, 'autoload'), true, $prepend);
        	} else {
            		spl_autoload_register(array(new self, 'autoload'));
        	}	
    	}
 
    	public static function autoload($class_name){
    		// If the specified $class_name does not include our namespace, duck out.
		if ( false === strpos( $class_name, 'EA_Taxonomies' ) ) { return; }
		// Split the class name into an array to read the namespace and class.
    		$file_parts = explode( '\\', $class_name );
 
    		// Do a reverse loop through $file_parts to build the path to the file.
    		$namespace = '';
    		for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {
		        // look at the $file_parts and replace all occurrences of the underscore with a hyphen 
			// because all of our class names and interface use underscores 
			// whereas our filenames use hyphens
	
			// Read the current component of the file part.
			$current = strtolower( $file_parts[ $i ] );
			$current = str_ireplace( '_', '-', $current );
			/**
			we're going to need a conditional that does a few things.
			1 - It needs to check to see which entry of the path of the filename that we're reading.
			2 - If we're on the first entry, then we're at the file name; otherwise, we are at its namespace.
			3 - Next, if we're reading the first entry, we need to determine if we're trying to autoload an interface or we're loading a class.
			4 - If it's the former, then we need to adjust the name of the interface so we load it properly based on its filename; otherwise, we'll load the class based on the value in the $current variable.
			**/
			// If we're at the first entry, then we're at the filename.
			if ( count( $file_parts ) - 1 === $i ) {
		 	     	/**
				* If 'interface' is contained in the parts of the file name, then
			     	* define the $file_name differently so that it's properly loaded.
			     	* Otherwise, just set the $file_name equal to that of the class
			     	* filename structure.
		     		**/
				if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {
			        	// Grab the name of the interface from its qualified name.
			        	$interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
                			$interface_name = strtolower($interface_name[0]);
			        	$file_name = "interface-$interface_name.php";
				} else { 
					$file_name = "class-$current.php"; 
				}
			} else { 
				$namespace = '/' . $current . $namespace; 
			}
		}
		// build a fully-qualified path to the file, this is little more than basic string concatenation
		// Now build a path to the file using mapping to the file location.
		//$filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
		// was __DIR__ changed to __FILE__
		$filepath  = trailingslashit( untrailingslashit( plugin_dir_path( dirname(  __FILE__  ) ) ) . $namespace );
		$filepath .= $file_name;
		// make sure the file exists
		// If the file exists in the specified path, then include it.
		if ( file_exists( $filepath ) ) {
	    		include_once( $filepath );
		} else {
	    		wp_die( esc_html( "**[class-autloadre.php]** The file attempting to be loaded at $filepath does not exist." ) );
		}
   	}

}  //END Autoloader{}