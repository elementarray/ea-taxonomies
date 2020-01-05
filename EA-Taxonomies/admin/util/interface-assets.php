<?php
/**
If you have a class that implements an interface, 
the class must define functionality that the interface dictates.

So if the interface has two method signatures with a specific visibility and name, 
then the class implementing the interface must have two methods with the same visibility and name 
as well as an actual method implementation.

According to "SOLID" protocol, there is to be no constructor (althoguh PHP allows for this...)
**/

/**
 * Defines a common set of functions that any class responsible for loading
 * stylesheets, JavaScript, or other assets should implement.
 */

namespace EA_Taxonomies\Admin\Util;

interface Assets_Interface {
 
    //public function init();
    public function enqueue();
 
}

/**
Notice, the interface doesn't actually define functionality. 
Instead, it specifies what the classes that implement this interface should define. 
**/