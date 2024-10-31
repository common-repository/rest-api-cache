<?php

/**
 *
 *
 * @link              https://www.vsourz.com
 * @since             1.0.0
 * @package           Rest_Api_Cache
 *
 * Plugin Name:       Rest API Cache
 * Plugin URI:        https://wordpress.org/plugins/rest-api-cache/
 * Description:       Boost your application speed by caching the WordPress REST API.
 * Version:           1.0.0
 * Author:            Vsourz Digital
 * Author URI:        https://www.vsourz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rest-api-cache
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'VSZ_REST_API_CACHE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rest-api-cache-activator.php
 */
function activate_rest_api_cache() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rest-api-cache-activator.php';
	Rest_Api_Cache_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rest-api-cache-deactivator.php
 */
function deactivate_rest_api_cache() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rest-api-cache-deactivator.php';
	Rest_Api_Cache_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rest_api_cache' );
register_deactivation_hook( __FILE__, 'deactivate_rest_api_cache' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rest-api-cache.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rest_api_cache() {

	$plugin = new Rest_Api_Cache();
	$plugin->run();

}
run_rest_api_cache();
