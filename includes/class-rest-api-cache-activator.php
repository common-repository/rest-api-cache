<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.vsourz.com
 * @since      1.0.0
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/includes
 * @author     Vsourz Digital <mehul@vsourz.com>
 */
class Rest_Api_Cache_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		vsz_users_include_first_load_value();
	}

}
function vsz_users_include_first_load_value(){
	if(empty(get_option('rest_cache_time'))){
		update_option('rest_cache_time',1);
	}
	if(empty(get_option('rest_cache_datetime'))){
		update_option('rest_cache_datetime',2592000);
	}	
}
