<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.vsourz.com
 * @since      1.0.0
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/includes
 * @author     Vsourz Digital <mehul@vsourz.com>
 */
class Rest_Api_Cache_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rest-api-cache',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
