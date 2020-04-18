<?php
namespace EA_Taxonomies\Util;
use EA_Taxonomies as NS;

class Internationalization_i18n {

	private $text_domain;

	/** Initialize the class and set its properties **/
	public function __construct( $plugin_text_domain ) { $this->text_domain = $plugin_text_domain; }

	/** Load the plugin text domain for translation **/
	public function load_plugin_textdomain() {
 		load_plugin_textdomain( $this->text_domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
}
