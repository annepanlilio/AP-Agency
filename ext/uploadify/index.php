<?
	
	if ( ! isset( $_GET['inline'] ) )
	define( 'IFRAME_REQUEST' , true );
	
	$wp_dir = '../../../../wp-admin/admin.php';
	/* 
	if ( isset($_GET['wp-dir'])){
		$wp_dir = $_GET['wp-dir'].'/wp-admin/admin.php';
	}else{
		echo "<h2>Wordpress Wp-Admin directory not found.</h2>";
		exit;
	} */
	if( !file_exists($wp_dir)){
		echo "<h2>Error: Wordpress Wp-Admin directory not found.</h2>";
		exit;
	}
	
	global $parent_file;
	 $parent_file = 'add-event.php';
	 
	/** Load WordPress Administration Bootstrap */
	require_once($wp_dir);
	
	$body_id = 'media-upload';
	//do_action("admin_head");
	
	 
?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Upload Team Logo</title>
<? teamphoto_iframe_script(); ?>
</head>


<body class=" wp-core-ui" style="padding:0;margin:0;">
	
	<div class="wrap wp-content" style="padding: 20px;">
	<div id="icon-upload" class="icon32"><br></div>
	<h2>Upload Team Photo
		<a href="#" onclick="parent.eval('tb_remove()')" class="add-new-h2">Cancel Upload</a>

	</h2>
	
	<?
	
	$_id = trim($_GET['id']);
	$_sport = trim($_GET['sport']);
	$_team = trim($_GET['team']);
	
	$_clean_sport = preg_replace("/[^A-Za-z0-9]/", '', $_sport);
	$_clean_sport = strtolower($_clean_sport);
	$_clean_team = trim($_team);
	$_clean_id = (int)$_id;
	?>
	<br/>
	<br/>
	<?
		$_photo = '';
		$photoURL = teamphoto_info($_clean_id,'photo');
		if(!empty($photoURL)){
			$_photo = TEAMPHOTO_UPLOAD_URL .'/'. stripcslashes($photoURL);
		}else{
			$_photo=TEAMPHOTO_URL_PLUG.'/no-photo.png';
		}
	?>
	<div class="image-place" style="border: 0px solid #bbb; float: left;height: 150px;width:150px; background:#fff url(<?=$_photo;?>) no-repeat center;" class="alignleft">
		
	</div>
	<div class="alignright" style=" width: 430px;float:right;">
		<h3><?=$_clean_team;?></h3>
		ID: <b><?=$_GET['id'];?></b><br/>
		Sport: <b><?php echo $_sport;?></b><br/>
		Team: <b><?php echo $_clean_team;?></b><br/>
		
		<br/>
		<form name="upload-form">
		
			<input id="file_upload" name="file_upload" type="file" multiple="true">
			
			<div id="queue"></div>	
		</form>
		
		
	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			var img_path;
			
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'sport'		: '<?php echo $_clean_sport;?>',
					'id'		: '<?php echo $_clean_id;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'onUploadSuccess' : function(file, data, response){
					img_path = data;
					saveimage_ajax();
				},
				'multi'    : false,
				'buttonClass' : 'button-primary',
				'buttonText' : 'Browse image...',
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php'
			});
		
			function saveimage_ajax() {
	$.post("<?=site_url().'/wp-admin/admin-ajax.php'?>", {imgpath:img_path,ID:'<?php echo $_clean_id;?>',action:'teamphoto_saveimg',sport:'<?=$_clean_sport;?>'})
		.done(function(data) {
			$message_res = jQuery('#message', window.parent.document);
			$message_res.fadeOut();
			$message_res.removeClass('updated');
			$message_res.removeClass('error');
			if( data== 'error'){
				$message_res.addClass('error');
				$message_res.html('<p>Error Saving thumbnail</p>');
			}else{
				$message_res.addClass('updated');
				$message_res.html('<p>Thumbnail successfully updated.</p>');
				
				d = new Date();
				$parentDIV = $('#photo-div-<?=$_clean_id;?>', window.parent.document);
				$parentDIV.fadeTo('fast', 0.1,function(){
					//jQuery(this).css('background-image','url(<?=$upload_dir['baseurl'];?>/teams/icehockey/19.jpg)');
					jQuery(this).css('background-image','url(<?=$upload_dir['baseurl'];?>'+data+'?'+d.getTime()+')');
				});
				$parentDIV.animate({opacity: 1}).css('opacity','1');
				
				//var the_td = jQuery('#row_<?=$_clean_id?>', window.parent.document);		
				$the_tr = $('#row-<?=$_clean_id?>', window.parent.document);		
				//var the_tr = jQuery(the_td).parent(); 
				$the_tr.each(function(){
					jQuery(this).find('td').css('background-color','#ffffba');				
					jQuery(this).find('th').css('background-color','#ffffba');
				});	
				
				
			}
			$message_res.fadeIn(function(){
				$parentDIV.css('opacity','1');
				parent.eval('tb_remove()');
			});
	});
	
  return true;
			}
		});
	</script>
	
	</div>
</body>
</html>