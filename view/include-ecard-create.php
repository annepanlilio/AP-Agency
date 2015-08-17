






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
                
                <div id="postbox-container-1" class="postbox-container">
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
												
												$image_type =array('Image','Polaroid','Headshot');
												
												echo '<select name="model-'. $ProfileID .'" ProfileID="'. $ProfileID .'" class="model-pics image-picker show-html" data-limit="4" multiple="multiple">';
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
					<img alt="" class="ajax-loading" src="<?=WP_HOME.'/wp-admin'?>/images/wpspin_light.gif">
					<input type="hidden" name="action" value="<?=$form_event_action;?>">
					<input type="hidden" name="ID" value="x" >
				</p>
				
				
            </div> <!-- #post-body -->
 
        </div> <!-- #poststuff -->
 
    </form>
    <div style="clear:both;"> </div>
</div><!-- .wrap -->





	
	
	
	
	
	
		<?
		/* <script>
		<?php $timestamp = time();?>
		$(function() {
			var img_path;
			
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'sport'		: '_temp',
					'id'		: '<?=$teamPhoto->ID;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'onUploadSuccess' : function(file, data, response){
					img_path = data;
					saveimage_temp_ajax();
				},
				'multi'    : false,
				'buttonClass' : 'button-primary',
				'removeTimeout' : 0,
				'buttonText' : 'Browse image...',
				'swf'      : '<?=TEAMPHOTO_URL_PLUG;?>/uploadify/uploadify.swf',
				'uploader' : '<?=TEAMPHOTO_URL_PLUG;?>/uploadify/uploadify.php'
			});
			
			function saveimage_temp_ajax() {
	$.post("<?=site_url().'/wp-admin/admin-ajax.php'?>", {imgpath:img_path,action:'teamphoto_saveimg_temp',sport:'<?=$_clean_sport;?>'})
		.done(function(data) {
			if( data== 'error'){
				$('#message').addClass('error');
				$('#message').html('<p>Error uploading image</p>');
			}else{
				$('#message').addClass('updated');
				$('#message').html('<p>Thumbnail successfully uploaded - but not yet saved.</p>');
				
				$parentDIV = $('#image-place');
				
				$parentDIV.fadeTo('fast', 0.1,function(){
					d = new Date();
					jQuery(this).css('background-image','url(<?=TEAMPHOTO_UPLOAD_URL;?>'+data+'?'+d.getTime()+')');
					jQuery(this).fadeIn(1000);
				});
				$parentDIV.animate({opacity: 1}).css('opacity','1');
				
				$('#photo').val(data);
			}
			$('#message').fadeIn(function(){
				$parentDIV.css('opacity','1');
			});
	});
	
  return true;
			}
			
			
			
		});
		</script>	
		 */?>
		
		
<script>
	
  jQuery(document).ready(function() {
		
	jQuery("#submit").on( "click", function() {
		jQuery('#msg-handler-submit').empty();
		jQuery('#msg-handler-submit').append('<p>');
		jQuery('#msg-handler-submit').addClass('error');
		jQuery.each( jQuery("select"), function( i, val ) {
	
	
			jQuery('#msg-handler-submit').append('ProfileID=');
			jQuery('#msg-handler-submit').append(jQuery(this).attr('ProfileID'));
			jQuery('#msg-handler-submit').append('  -- ');
			
			//jQuery('#msg-handler-submit').append(jQuery(this).data('picker').selected_values());
			var selectedPics = jQuery(this).data('picker').selected_values();
			jQuery.each( selectedPics, function( i,v ) {
			jQuery('#msg-handler-submit').append(' , ' + v);
			});
			
			jQuery('#msg-handler-submit').append('<br/><br/>');
		});
	   //console.log(jQuery("select").data('picker').selected_values());
	 // var imgPickerObj = jQuery("select").imagepicker();
	  //console.log(imgPickerObj.selected_values());
	 
	 jQuery('#msg-handler-submit').append('not yet ready');
	 jQuery('#msg-handler-submit').append('</p>');
	 return false;
	 });
	 
	
  });
	
	
	 
	 /* 
	
	function userBeforeSubmit(){
		jQuery("#wpcontent .ajax-loading").css('visibility','visible');
		jQuery('#message').fadeOut();
		jQuery('#message').removeClass('updated');
		jQuery('#message').removeClass('error');
	}
	function userSuccess(){ 
		jQuery("#wpcontent .ajax-loading").css('visibility','hidden'); 
		jQuery('#message').fadeIn();
	}
	
	// Form submission - ajax
	jQuery("#users-form").submit(function(){
		if(jQuery(this).valid() == false){
			jQuery('#message').fadeOut();
			return false;
		}
		
		userBeforeSubmit();
		jQuery.ajax({
			type: "post",
			url: "<?=admin_url("admin-ajax.php")?>",
			data: jQuery('#users-form').serialize(),
			success: function(result){
				response = jQuery.parseJSON(result);
				jQuery('#message').addClass(response['class']);
				jQuery("#message").html('<p>'+response['text']+'</p>');
				
				
				var the_new_photo = response['photo'];
				jQuery("#photo").val(the_new_photo);
				
				if(the_new_photo != ''){
					$parentDIV = $('#image-place-cur');
					$parentDIV.fadeTo('fast', 0.1,function(){
						d = new Date();
						jQuery(this).css('background-image','url(<?=TEAMPHOTO_UPLOAD_URL;?>'+the_new_photo+'?'+d.getTime()+')');
						jQuery(this).fadeIn(1000);
						$('#image-place').fadeOut();
					});
					$parentDIV.animate({opacity: 1}).css('opacity','1');
				}
				
				

				userSuccess();
				return false;
			}
		});
		return false;
	});
 
 });
 */

</script>


