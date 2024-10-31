<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.vsourz.com
 * @since      1.0.0
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rest_Api_Cache
 * @subpackage Rest_Api_Cache/admin
 * @author     Vsourz Digital <mehul@vsourz.com>
 */
class Rest_Api_Cache_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rest-api-cache-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'bootstrapCss', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_register_style( 'jquery.dropdown.min', plugin_dir_url( __FILE__ ) . 'css/fSelect.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rest-api-cache-admin.js', array( 'jquery' ), $this->version, false );

		wp_register_script( 'bootstrapJs', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), true );
		wp_register_script( 'jquery.dropdown.min', plugin_dir_url( __FILE__ ) . 'js/fSelect.js', array( 'jquery' ), true );
		wp_localize_script( $this->plugin_name, 'adv_rest_api_cache_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) , 'nonce' => wp_create_nonce('ajaxnonce_rest_api')) );

	}
	public function admin_menu() {

		/**
		 *
		 * Set the admin menu and be visible in admin side
		 *
		 */
		add_menu_page( "General Settings","Rest API Cache", "manage_options", "rest_api_cache_setting",array($this,"vsz_rest_api_setting_callback"), 'dashicons-controls-repeat' , 8);

		add_submenu_page("rest_api_cache_setting", "Exclude API Cache", "Exclude API Cache", "manage_options", "rest_api_exclude",array($this,"vsz_rest_api_cache_callback"));



	}
	function vsz_rest_api_setting_callback(){
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rest-api-cache-setting-display.php');
	}
	function vsz_rest_api_cache_callback(){
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rest-api-cache-admin-display.php');
	}


	public static function vsz_rest_api_cache_clear_cache_callback()
	{
		global $wpdb;
		$nonce = $_POST['nonce'];
		// Verify nonce field passed from javascript code
	    if ( ! wp_verify_nonce( $nonce, 'ajaxnonce_rest_api' ) )
	        die ( 'Busted!');


		if(isset($_POST['type']) && $_POST['type'] == 'single' && isset($_POST['url_parameter']) && !empty($_POST['url_parameter'])){
			$url = parse_url( site_url() );
			if(!empty($url) && is_array($url)){
				$url_path = esc_url($url['path'].'/wp-json'.$_POST['url_parameter']);
			}else{
				$url_path = esc_url($url['path']);
			}


			$results = $wpdb->query( $wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_vsz_rest_cache_%'.$url_path.'%',
				'_transient_timeout_vsz_rest_cache_%'.$url_path.'%'
			) );
			if(isset($results) && !empty($results)){
				echo "Cached purged successfully.";
			}else{
				echo "Cache already purged.";
			}
			exit;
		}else{

			$results = $wpdb->query( $wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_vsz_rest_cache_%',
				'_transient_timeout_vsz_rest_cache_%'
			) );
			if(isset($results) && !empty($results)){
				echo "Cached purged successfully.";
			}else{
				echo "Cache already purged.";
			}
			exit;
		}
	}

	/*
     * Adding the menu item to the top level for quick access to purge all cache
	 */
	public function vsz_rest_api_cache_admin_bar(){

		global $wp_admin_bar;

		//Add a link called 'My Link'...
		$wp_admin_bar->add_menu( array(
			'id'    => 'adv-rest-api-cache',
			'title' => 'Rest Api Cache',
			'href'  => '#'
		));

		//THEN add a sub-link called 'Sublink 1'...
		$wp_admin_bar->add_menu( array(
			'id'    => 'adv-rest-api-cache-purge',
			'title' => 'Purge Rest API Cache',
			'href'  => '#',
			'parent'=>'adv-rest-api-cache'
		));
	}


	function vsz_rest_api_cache_purge_action_js()
	{
		?>
		  <script type="text/javascript" >
		     jQuery("li#wp-admin-bar-adv-rest-api-cache-purge .ab-item").on( "click", function() {
		     	var r = confirm("Are you sure, you need to clear the cache?");
		     	if (r == false) {
		     		return false;
		     	}
		        var data = {
		                      'action': 'adv_clear_cache',
		                    };

		        /* since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php */
		        jQuery.ajax({
				url: adv_rest_api_cache_object.ajax_url,
				data: { 						/////// send data
					'action':'adv_clear_cache',
					//'url_parameter':slug,
					'type':'single',
					'nonce': adv_rest_api_cache_object.nonce
				},
				type: 'POST',
				success: function(data) {
					alert(data);

				}
			});

		      });
		  </script> <?php
	}



}
