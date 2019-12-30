<?php
/**
 * Autoloads eaTaxonomies classes using WordPress convention.
 *
 * @author William Holt
 */
class eaTaxonomies_Autoloader
{
    /**
     * Registers eaTaxonomies_Autoloader as an SPL autoloader.
     *
     * @param boolean $prepend
     */
    public static function register($prepend = false)
    {
        if (version_compare(phpversion(), '5.3.0', '>=')) {
            spl_autoload_register(array(new self, 'autoload'), true, $prepend);
        } else {
            spl_autoload_register(array(new self, 'autoload'));
        }
    }
 
    /**
     * Handles autoloading of eaTaxonomies classes.
     *
     * @param string $class
     */
    public static function autoload($class)
    {
        if (0 !== strpos($class, 'eaTaxonomies')) {
            return;
        }
/**
echo __DIR__;
//'/var/www/html/example.loc/public_html/wordpress/wp-content/plugins/EA-Taxonomies/src'
        if (is_file( $file =  __DIR__ .'/'.  str_replace(array('_', "\0"), array('/', ''),$class) . '.php' ) ) {
            require_once $file;
        }
**/
echo __DIR__;
echo __DIR__.'/class-'.strtolower(str_replace(array('_', "\0"), array('-', ''), $class).'.php');
        if (is_file($file = dirname(__FILE__).'/class-'.strtolower(str_replace(array('_', "\0"), array('-', ''), $class).'.php'))) {
            require_once $file;
        }
    }
}