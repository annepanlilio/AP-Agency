






<div class="wrap">
 
    <?php screen_icon(); ?>
 
    <h2><?php echo __("Create e-Cards", RBAGENCY_TEXTDOMAIN);?>
        <a href="/wp-admin/admin.php?page=rb_agency_search" class="add-new-h2">Back</a>
    </h2>
	 
	 
	<style>
	input{outline-width: 0px; outline-style: none;}
	input{border-radius: 3px;border: 1px solid #dfdfdf;background-color: #ffffff;color: #333333;}
	input:focus{box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);border-color: #aaaaaa;}
	label.error{clear: both;display:block;color:#900000;background-color:#ffebe8;border:0px solid #cc0000;padding: 2px 4px;
	margin-right:5px;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;font-style:italic}
	input.error{color:#900000;background-color:#ffebe8;}
	</style>
	<div class="below-h2" style="display:none;" id="message"></div>
	
	

    <form name="my_form" method="post">
        <input type="hidden" name="action" value="some-action">
        <?php wp_nonce_field( 'some-action-nonce' );
 
        /* Used to save closed meta boxes and their order */
        wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
        wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
 
        <div id="poststuff">
 
            <div id="post-body" class="metabox-holder columns-1">
 
                <div id="post-body-content">
                    <!-- #post-body-content -->
                </div>
 
 
                <?
                
			 	$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
				$cartString = implode(",", array_unique($cartArray));
			
				$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") GROUP BY profile.ProfileID ORDER BY profile.ProfileID ASC";
				$results = $wpdb->get_results($query,ARRAY_A);
				$count = count($results);
				
					
				foreach($results as $model){
					?>
                
                <div id="postbox-container-<?php echo $model['ProfileID'];?>" class="postbox-container">
	                <div id="normal-sortables-1" class="meta-box-sortables ui-sortable">
						<div id="dashboard_media" class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php echo $model['ProfileContactDisplay'];?></span></h3>
							<div class="inside">
								<div class="main">
								
								<? //print_r($model);?>
								
								<a href="/profile/<?php echo $model['ProfileGallery'];?>" target="_blank">View Profile</a>
								
								
									<table class="form-table">
										<tbody>
											<tr class="form-field form-required">
												<th scope="row" style="width:150px;"><label for="team">Select Photos <span class="description">(Max 4)</span></label></th>
												<td>
												
												<?
												
												$rb_agency_options_arr = get_option('rb_agency_options');
												$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						
												$ProfileID = $model['ProfileID'];
												$ProfileGallery = $model['ProfileGallery'];
												
												$image_type =array('Image','Polaroid');//,'Headshot' exclude headshot
												
												echo '<select name="model-'. $ProfileID .'"  modelName="'.$model['ProfileContactDisplay'].'" ProfileID="'. $ProfileID .'" class="model-pics image-picker show-html" data-limit="4" multiple="multiple">';
												foreach($image_type as $display_imagetype){
													$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,$display_imagetype);
													$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
													$countImg =$wpdb->num_rows;
									
													foreach($resultsImg as $dataImg ){
														$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
														$bfi_params = array(
															'crop'=>true,
															'width'=>106,
															'height'=>130
														);
														$image_src = bfi_thumb( $image_path, $bfi_params );
		
														//echo '<div style="margin:4px; float:left;width:115px;height:150px;"><a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
														//echo "<img src=\"". $image_src."\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
														
														echo '<option data-img-src="'. $image_src .'" value="'. $dataImg["ProfileMediaID"] .'">'. $dataImg["ProfileMediaID"] .'</option>';
													}
												}
												echo '</select>';
												//$queryImg = rb_agency_option_galleryorder_query("ProfileMediaID" ,$ProfileID,"Polaroid");

												flush();
																	
											?>

										
												<script>
												    jQuery(document).ready(function() {
														jQuery("select").imagepicker({limit: 4,show_label: false});
		                                            });
												</script>	
												
										
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
                </div>
                
                <?php } ?>
                
                <div style="clear:both;"> </div>
                <div id="msg-handler-submit"></div>
                <p class="submit">
					<input id="submit" class="button-primary" type="submit" value="<?php echo __("Download e-Cards", RBAGENCY_TEXTDOMAIN);?>" name="submit">
					<img alt="" class="ajax-loading" src="<?php echo WP_HOME.'/wp-admin'?>/images/wpspin_light.gif">
					<input type="hidden" name="action" value="<?php echo $form_event_action;?>">
					<input type="hidden" name="ID" value="x" >
				</p>
				
				
            </div> <!-- #post-body -->
 
        </div> <!-- #poststuff -->
 
    </form>
    <div style="clear:both;"> </div>
</div><!-- .wrap -->



<script>
	
  jQuery(document).ready(function() {
		
	jQuery("#submit").on( "click", function() {
	
		var errorMsg = [];
		var postModel = [];
		jQuery('#msg-handler-submit').empty();
		jQuery('#msg-handler-submit').append('<p>');
		jQuery('#msg-handler-submit').addClass('updated');
		jQuery.each( jQuery("select"), function( i, val ) {
	
			var modelName = jQuery(this).attr('modelName');
			var ProfileID = jQuery(this).attr('ProfileID');
			
			var selectedPics = jQuery(this).data('picker').selected_values();
			
			var total = selectedPics.length;
		/* 	jQuery.each( selectedPics, function( i,v ) {
				jQuery('#msg-handler-submit').append(' , ' + v);
			});
			 */
			
			var temp = {};
            temp[ProfileID] = selectedPics;
			postModel.push(temp);
			
			//jQuery('#msg-handler-submit').append(' (total = '+total +')<br/><br/>');
			if(total < 1){
				errorMsg.push('<a href="#postbox-container-'+ProfileID+'" class="smoothscroll">'+modelName +'</a> doesn\'t have selected photo.');
			}
			//jQuery('#msg-handler-submit').append('<br/><br/>');
		});
		
		
		if(errorMsg.length > 0){
			jQuery('#msg-handler-submit').append('<b>Error:</b><br/>');
			jQuery.each( errorMsg, function( i,v ) {
				jQuery('#msg-handler-submit').append(v+'<br/>');
			});
	        jQuery('#msg-handler-submit').append('</p>');
			return false;
		}else{
			jQuery('#msg-handler-submit').append('<b>Success:</b> Please wait...<br/>');
			
			//console.log(postModel);
			
			jQuery.post("<?php echo site_url().'/wp-admin/admin-ajax.php'?>", {pics:postModel , action:'generatepdfEcard'})
			.done(function(data) {
				if( data== 'error'){
					jQuery('#msg-handler-submit').html('<p>Error generating ecard</p>');
				}else{
					//window.open(data, '_blank');
                    location.href=data;
				}
			});
			jQuery('#msg-handler-submit').append('</p>');
			return false;
		}
		
	   //console.log(jQuery("select").data('picker').selected_values());
	 // var imgPickerObj = jQuery("select").imagepicker();
	  //console.log(imgPickerObj.selected_values());
	 
	 //jQuery('#msg-handler-submit').append('not yet ready');
	 jQuery('#msg-handler-submit').append('</p>');
	 return false;
	 });
	 
	
	jQuery('.smoothscroll').live('click', function(){
		var targetID = jQuery(this).attr('href');
        jQuery('html, body').animate({scrollTop: jQuery(targetID).offset().top}, 'slow');
        return false;
    });

		
  });
	

</script>


