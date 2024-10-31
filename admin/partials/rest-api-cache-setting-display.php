<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.vsourz.com
 * @since      1.0.0
 *
 * @package    Advanced_Rest_Api_Cache
 * @subpackage Advanced_Rest_Api_Cache/admin/partials
 */
?>
<?php
	$rest_cache_time = '';
	$rest_cache_datetime = '';
	$checkupdate = 0;
	if(isset($_POST['setting_nonce']) && !empty($_POST['setting_nonce']) && wp_verify_nonce($_POST['setting_nonce'], 'setting_nonce')){
		if(isset($_POST['rest_cache_time']) && !empty($_POST['rest_cache_time'])){
			$checktimeupdate = update_option('rest_cache_time',sanitize_text_field($_POST['rest_cache_time']));
			if($checktimeupdate){
				$checkupdate = 1;
			}
		}
		if(isset($_POST['rest_cache_datetime']) && !empty($_POST['rest_cache_datetime'])){
			$checkdatetime = update_option('rest_cache_datetime',sanitize_text_field($_POST['rest_cache_datetime']));
			if($checkdatetime){
				$checkupdate = 1;
			}
		}
		?><div id="message" class=" notice updated is-dismissible"><p><?php _e( 'The cache time has been updated.', 'rest-api-cache' ); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div><?php

	}
	$rest_cache_time = get_option('rest_cache_time');
	$rest_cache_datetime = get_option('rest_cache_datetime');

	
?>

<div class="wrap">
    <h1><?php _e( 'REST API Cache', 'rest-api-cache' ); ?></h1>
    <div class="wrap-div">
	    <div class="purge-cache">
	    	<h2><?php _e( 'Empty all cache', 'rest-api-cache' ); ?></h2>
	        <p class="p-min"><?php _e( 'By clicking this button you will purge all REST API cache', 'rest-api-cache' ); ?></p>
	        <input type="submit" class="button button-primary purge-cache-data" value="Empty Cache" name="emptycache" >
	    </div>
	    <div class="cache-time-setting">
		    <h2><?php _e( 'Cache Time Setting', 'rest-api-cache' ); ?></h2>
	        <p><?php _e( 'Set REST API purge cache time from here', 'rest-api-cache' ); ?></p>
		    <form method="POST" name="post_types">
	                    <input type="number" id="fld-cache-time" min="1" style="width:70px; height:34px; vertical-align:top;" name="rest_cache_time" value="<?php echo $rest_cache_time; ?>">
	                    <select name="rest_cache_datetime">
	                        <option value="60" <?php if($rest_cache_datetime == '60'){?>selected="selected"<?php }?>><?php _e( 'Minute', 'rest-api-cache' ); ?></option>
	                        <option value="3600" <?php if($rest_cache_datetime == '3600'){?>selected="selected"<?php }?>><?php _e( 'Hour', 'rest-api-cache' ); ?></option>
							<option value="86400" <?php if($rest_cache_datetime == '86400'){?>selected="selected"<?php }?>><?php _e( 'Day', 'rest-api-cache' ); ?></option>
	                        <option value="604800" <?php if($rest_cache_datetime == '604800'){?>selected="selected"<?php }?>><?php _e( 'Week', 'rest-api-cache' ); ?></option>
	                        <option value="2592000" <?php if($rest_cache_datetime == '2592000'){?>selected="selected"<?php }?>><?php _e( 'Month', 'rest-api-cache' ); ?></option>
	                        <option value="31536000" <?php if($rest_cache_datetime == '31536000'){?>selected="selected"<?php }?>><?php _e( 'Year', 'rest-api-cache' ); ?></option>
	                    </select>
	                 <br /><br />
		                <input type="submit" class="button button-primary" value="Save Changes" name="submit">
						<input type="hidden" name="setting_nonce" value="<?php echo wp_create_nonce('setting_nonce'); ?>"/>
	                <input type="hidden" id="noncevalue" name="nonce" value="<?php echo wp_create_nonce('ajaxnonce_rest_api');?>" />
	    </form>
	    </div>
	</div>
</div>
<script>
jQuery(document).ready(function(){
	jQuery('.purge-cache-data').click(function(){
		var nonce = jQuery('#noncevalue').val();
		var slug = this.dataset.slug;
		if(slug != ''){
			var r = confirm("Are you sure, you need to clear the cache?");
	     	if (r == false) {
	     		return false;
	     	}
			jQuery.ajax({
				url: adv_rest_api_cache_object.ajax_url,
				data: { 						/////// send data
					'action':'adv_clear_cache',
					'nonce': adv_rest_api_cache_object.nonce
				},
				type: 'POST',
				success: function(data) {
					console.log(data);
					alert(data);
				}
			});
		}
		return false;
	});

});
</script>