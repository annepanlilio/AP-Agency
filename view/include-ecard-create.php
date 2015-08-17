








	<div class="wrap">	
		<h2>Add New Team Logo
			<a href="/wp-admin/admin.php?page=rb_agency_search" class="add-new-h2">Back to List</a>
			</h2>			
	<br/> 	
	<br/>
	


<style>
input{outline-width: 0px; outline-style: none;}
input{border-radius: 3px;border: 1px solid #dfdfdf;background-color: #ffffff;color: #333333;}
input:focus{box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);border-color: #aaaaaa;}

</style>
<div class="below-h2" style="display:none;" id="message"></div>

<form method="POST" id="users-form" action="<?=admin_url("admin-ajax.php")?>">


<div id="dashboard-widgets" class="metabox-holder columns-1">

			<!-- Row 2: Column Left Start -->

			<div id="postbox-container-3" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_media" class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Media &amp; Links</span></h3>
					<div class="inside">
							<div class="main">
	<table class="form-table" style="width:550px">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row" style="width:200px;"><label for="team">Team<span class="description">(required)</span></label></th>
				<td><input name="team" type="text" id="team" size="30" value="" placeholder="Miami Heat" class="required" /></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="team_abbr">Team Abbr <span class="description"></span></label></th>
			</tr>
			
			
			<?
			/* <tr class="form-field form-required">
				<th scope="row"><label for="photo">Photo</label></th>
				<td>
				
					<?
					$_photo = '';
					$photoURL = teamphoto_info($teamPhoto->ID,'photo');
					if(!empty($photoURL)){
						$_photo = TEAMPHOTO_UPLOAD_URL .'/'. stripcslashes($photoURL);
					}else{
						$_photo=TEAMPHOTO_URL_PLUG.'/no-photo.png';
					}
					?>
					Current:<br/>
					<div class="image-place-cur" id="image-place-cur" style="overflow: hidden;float: left; margin: 0 10px 0 0;height: 150px;width:150px; background:#fff url(<?=$_photo;?>) no-repeat center;">
					</div>
					
					<div class="image-place" id="image-place" style="overflow: hidden;float:left; margin: 0;height: 150px;width:150px; background:#fff none no-repeat center;">
					</div>
					<div class="clear:both;">
					<input name="photo" type="text" id="photo" value="<?=$teamPhoto->photo;?>" style="visibility:hidden" />
					<input id="file_upload" name="file_upload" type="file" multiple="true">
			
					<div id="queue"></div>
				</td>
			</tr> */?>
			
		</tbody>
	</table>
	
	
	
	
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
	<p class="submit">
		<input id="submit" class="button-primary" type="submit" value="Save Team Info" name="submit">
		<img alt="" class="ajax-loading" src="<?=WP_HOME.'/wp-admin'?>/images/wpspin_light.gif">
		<input type="hidden" name="action" value="<?=$form_event_action;?>">
		<input type="hidden" name="ID" value="x" >
	</p>
</form>
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
		
		
<style>
	label.error{clear: both;display:block;color:#900000;background-color:#ffebe8;border:0px solid #cc0000;padding: 2px 4px;
	margin-right:5px;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;font-style:italic}
	input.error{color:#900000;background-color:#ffebe8;}
</style>
<script>
	/* 
  jQuery(document).ready(function() {
	
	jQuery.validator.addMethod("checkteamphoto_Availability",function(value,element){
		var team = jQuery("#team").val();
		var league = jQuery("#league").val();
		ret = jQuery.ajax({
			url: "<?=admin_url("admin-ajax.php")?>",
			type: "POST",
			async: false,
			data: "action=teamphoto_check&field=team&ID=<?=$teamPhoto->ID;?>&val="+team+"&field2=league&val2="+league,
			success: function(output) {
				if(output=='true'){
					return true;}else{return false;}
			}
		});
		if(ret.responseText=='true'){
			return true;}else{ return false;
		}
	},"Sorry, Team already exist in selected League.");
	// handles Form submission
	jQuery("#users-form").validate({
		rules:{
			team:{required:true, checkteamphoto_Availability:true}
		},
		onkeyup: false,
		onclick: false
	});
	
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


</div>
