
	
	
	<div id="photo-message-div" class="message-alert"></div>
	Upload images for Photo
<input id="file_upload" name="file_upload" type="file" multiple="true">



<script>
		<?php $timestamp = time();?>
		
		<?
		if(!is_dir (RBAGENCY_UPLOADPATH .$ProfileGallery)){
			$ProfileGallery = rb_agency_createdir($ProfileGallery);
		}
		
		
		?>
		jQuery(function() {
			var img_path;
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'uploadPath': '<?php echo RBAGENCY_UPLOADPATH ?>',
					'modelID'	: '<?php echo $ProfileGallery;?>',
					'modelType'	: 'photo',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'onUploadSuccess' : function(file, data, response){
					img_path = data;
					saveimage_temp_ajax();
					$('#gallery-no-image').fadeOut();
				},
				'multi'    : true,
				'fileTypeExts' : '*.gif; *.jpg; *.png',
				'buttonClass' : 'button-primary',
				'removeTimeout' : 0,
				'buttonText' : 'Browse image...',
				'swf'      : '<?=RBAGENCY_PLUGIN_URL;?>ext/uploadify/uploadify.swf',
				'uploader' : '<?=RBAGENCY_PLUGIN_URL;?>ext/uploadify/uploadify.php'
			});
			
			function saveimage_temp_ajax() {
				jQuery.post("<?=admin_url("admin-ajax.php")?>", 
					{
					imgpath:img_path,
					ProfileID: <?php echo $ProfileID;?>,
					ProfileMediaType: 'Image', 
					action:'profilesphoto_save'
					})
					
				.done(function(data) {
					if( data== 'error'){
						$('#photo-message-div').addClass('error');
						$('#photo-message-div').html('<p>Error uploading image</p>');
					}else{
						jQuery("#wrapper-sortable #gallery-sortable").append(data);
						//$('#photo-message-div').addClass('updated');
						//$('#photo-message-div').html('<p>Thumbnail successfully uploaded - but not yet saved.</p>');
						
						/* 
						$parentDIV = $('#image-place');
						$parentDIV.fadeTo('fast', 0.1,function(){
							d = new Date();
							jQuery(this).css('background-image','url(<?=TEAMPHOTO_UPLOAD_URL;?>'+data+'?'+d.getTime()+')');
							jQuery(this).fadeIn(1000);
						});
						$parentDIV.animate({opacity: 1}).css('opacity','1');
						
						$('#photo').val(data); */
					}
					/* $('#message').fadeIn(function(){
						$parentDIV.css('opacity','1');
					}); */
			});
			//console.log('were good na');
		  return true;
			}
			
			
			
		});
			</script>	
			

<?php



/* 


called by rb-agency/view/admin-profile.php

include-photouploadmulti.php


RBAGENCY_UPLOADPATH


include_once(RBAGENCY_interact_BASEDIR .'include-photouploadmulti.php');
								
								
								
	define("RBAGENCY_interact_UPLOADDIR", $rb_agency_interact_WPUPLOADARRAY['baseurl'] ."/profile-media/" );// http://domain.com/wordpress/wp-content/uploads/profile-media/
	define("RBAGENCY_interact_UPLOADPATH", $rb_agency_interact_WPUPLOADARRAY['basedir'] ."/profile-media/" ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
	 */
	 
	 