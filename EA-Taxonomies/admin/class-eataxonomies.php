<?php

namespace EA_Taxonomies\Admin;

class eaTaxonomies { 
 
    /**
     * The basename of the plugin.
     *
     * @var string
     */
    private $basename;

 
    /**
     * Flag to track if the plugin is loaded.
     *
     * @var bool
     */
    private $loaded;
 
 
    /**
     * Constructor.
     *
     * @param string $file
     */
    public function __construct($file){

	/**
	The constructor function runs as soon as the class is created 
	which means that this is the place to put all our actions and filters:
	
	when you use hooks in classes you can’t simply specify the name of the function, 
	you need to use the array( $this, 'name_of_function' ) convention. 
	This is because the function we are referring to is within the class, not in the global space.
	**/

        $this->basename = plugin_basename($file);
        $this->loaded = false;

	add_action('plugins_loaded', array(&$this, 'load'));

    }
 
    /**
     * Loads the plugin into WordPress.
     */
    public function load(){
        if ($this->loaded) {
            return;
        }

}