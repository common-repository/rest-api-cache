<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.vsourz.com
 * @since      1.0.0
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/public
 * @author     Vsourz Digital <mehul@vsourz.com>
 */
class Rest_Api_Cache_Public {
	private static $refresh = null;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rest_Api_Cache_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rest_Api_Cache_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rest-api-cache-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rest_Api_Cache_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rest_Api_Cache_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rest-api-cache-public.js', array( 'jquery' ), $this->version, false );

	}
	public static function vsz_rest_api_cache_pre_dispatch($result, $server, $request) {

		$request_uri = esc_url( $_SERVER['REQUEST_URI'] );

		if ( method_exists( $server, 'send_headers' ) ) {

			$headers = apply_filters( 'vsz_rest_cache_headers', array(), $request_uri, $server, $request );
			if ( ! empty( $headers ) ) {
				$server->send_headers( $headers );
			}
		}

		if ( true == self::$refresh ) {
				return $result;
		}

		$skip = apply_filters( 'vsz_rest_cache_skip', false, $request_uri, $server, $request );
		if($_SERVER['REQUEST_METHOD'] != 'POST'){

				if ( ! $skip ) {

				$key = 'vsz_rest_cache_'.apply_filters( 'vsz_rest_cache_key', $request_uri, $server, $request );

				if ( false === ( $result = get_transient( $key ) ) ) {

					if ( is_null( self::$refresh ) ) {
							self::$refresh = true;
						}

					$result  = $server->dispatch($request );
					$length = get_option('rest_cache_time');
					$period = get_option('rest_cache_datetime');
					$timeout = apply_filters( 'vsz_rest_cache_timeout', $length * $period, $length, $period );

					set_transient( $key, $result, $timeout );

				}
			}
		}
		return $result;
	}
	public function vsz_rest_api_cache_exclude_endpoints($skip){
		$request_uri = esc_url( $_SERVER['REQUEST_URI'] );
		$excluded_api_endpoints = get_option('apiendpoints');
		$needle = $excluded_api_endpoints;
		if(empty(!$needle)){
			$haystack =  $request_uri;
			if ( ! $skip  ) {
				if(!is_array($needle)) $needle = array($needle);

				foreach($needle as $what) {
					if(($pos = strpos($haystack, $what))!==false) return true;
				}
			}
		}	
		return false;
	}


}

