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
$checkupdate = false;
if(isset($_POST['exclude_nonce']) && !empty($_POST['exclude_nonce']) && wp_verify_nonce($_POST['exclude_nonce'], 'exclude_nonce')){
	if(isset($_POST['endpoints']) && !empty($_POST['endpoints'])){
		foreach($_POST['endpoints'] as $retrive_data){
			$store_data[] = sanitize_text_field(stripslashes($retrive_data));
		}
		$checkupdate = update_option('apiendpoints',$store_data);
	}else{
	$checkupdate = update_option('apiendpoints','');
	}

	?><div id="message" class=" notice updated is-dismissible"><p><?php _e( 'Exclude endpoints has been updated.', 'rest-api-cache' ); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div><?php

}
$endpoints = get_option('apiendpoints');

$homepage = wp_remote_get(site_url().'/wp-json/');
$result = json_decode($homepage['body']);

$i = 0;


foreach($result->routes as $key=>$data){
	$methods = $data->methods;
	if(isset($methods[0]) && $methods[0] == "GET"){
		if(!strpos($key,'?')){
			$endpointsurl[]=$data->_links->self;
			$endpointstitle[]=array("namespace"=>$data->namespace,"endpoints"=>$key);
			$i = 1;
		}
	}
}
//Sorting array by namespace
$sortArray = array();

foreach($endpointstitle as $person){
    foreach($person as $key=>$value){
        if(!isset($sortArray[$key])){
            $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
    }
}

$orderby = "namespace"; //change this to whatever key you want from the array

array_multisort($sortArray[$orderby],SORT_DESC,$endpointstitle);


?>

<div class="wrap">
	<h1><?php _e( 'Rest End-Points', 'rest-api-cache' ); ?></h1>
    <p><?php _e( 'Tick REST API end-points to exclude from API cache. Also you can clear API cache for particular end-point/group by clicking clear link.', 'rest-api-cache' ); ?></p>

	<form method="post" name="endpoints">
		<input type="button" value="Select All" class="button button-primary selectall"/>
		<input type="button"  value="Unselect All" class="button button-primary unselectall"/><br/> <br/>
			<?php
			$excludearray = array('oembed/1.0');
			if(isset($endpointstitle) && !empty($endpointstitle)){
				$namespace = '';
				$i = 1;
				foreach($endpointstitle as $retrive_data){

					if(!isset($retrive_data["namespace"]) || empty($retrive_data["namespace"]) || (in_array($retrive_data["namespace"],$excludearray)) ){
						continue;
					}
					if($namespace != $retrive_data["namespace"] ){
						if($namespace != ''){
							?>
                            </div>
						<?php
						}
						//echo '<div class="sildediv" data-slide="'.$i.'">'.$retrive_data["namespace"].'</div>';
						?>
						<h2 class="remove<?php echo $i;?> fa<?php echo $i;?> sildediv accordianheading <?php if($i == 1){?>upimage<?php } else {?>downimage<?php } ?>" data-slide="<?php echo $i;?>" >
							<?php echo $retrive_data["namespace"];?>
							<a href="javascript:void(0)" title="Clear cache" data-slug="/<?php echo $retrive_data["namespace"];?>" class="purge-cache-data"><?php _e( 'Clear', 'rest-api-cache' ); ?></a>
                            <span class="dashicons <?php if($i == 1){?>dashicons-arrow-up-alt2<?php } else {?>dashicons-arrow-down-alt2<?php } ?>"></span>
                            </h2>

						<div class="row endpoints_list" id="<?php echo $i;?>" style=" <?php if($i != 1){?>display:none;<?php } ?>">
						<?php
                        }
                        $namespace = $retrive_data["namespace"];
                        $checked = '';
                        if(isset($endpoints) && !empty($endpoints)){

                            if(in_array($retrive_data["endpoints"],$endpoints)){
                                $checked = 'checked';
                            }
                        }
                        ?>
						<div class="col-sm-3">
							<input type="checkbox" name="endpoints[]" value="<?php echo $retrive_data["endpoints"];?>" <?php echo $checked;?>/>
							<?php echo $retrive_data["endpoints"];?>

						</div>
						<?php
						$i++;
					}
				}

				?>
			</div>
			<div>
			<input type="hidden" name="exclude_nonce" value="<?php echo wp_create_nonce('exclude_nonce'); ?>"/>
			<br /><br /><input type="submit" name="submit" value="Save" class="button button-primary"/></div>
	</form>
</div>
<script>
jQuery(document).ready(function(){

	jQuery('.selectall').click(function(){
		jQuery('.endpoints_list input').attr('checked','true');
	});
	jQuery('.unselectall').click(function(){
		jQuery('.endpoints_list input').removeAttr('checked');
	});
	jQuery('.sildediv').click(function(){
		var slide=jQuery(this).data('slide');

		jQuery('#'+slide).slideToggle(400);
		if(jQuery(this).hasClass('upimage')){

			jQuery(this).removeClass('upimage');
			jQuery(this).addClass('downimage');
			jQuery(this).find('.dashicons').removeClass('dashicons-arrow-up-alt2');
			jQuery(this).find('.dashicons').addClass('dashicons-arrow-down-alt2');
		}
		else{

			jQuery(this).removeClass('downimage');
			jQuery(this).addClass('upimage');
			jQuery(this).find('.dashicons').removeClass('dashicons-arrow-down-alt2');
			jQuery(this).find('.dashicons').addClass('dashicons-arrow-up-alt2');
		}
	});

	jQuery('.purge-cache-data').click(function(){

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
					'url_parameter':slug,
					'type':'single',
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
