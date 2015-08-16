<?php

function rb_agency_profilesphoto_script() {
	//wp_enqueue_script('media-upload');
	//wp_enqueue_script('thickbox');
	//wp_register_script('upload', '/wp-content/plugins/js/upload.js', array('jquery','media-upload','thickbox'));
	//wp_enqueue_script('upload');
	
	
	if( $_GET['page'] == 'rb_agency_profiles' and $_GET['action']=='editRecord'){
		wp_admin_css('thickbox');
		add_thickbox();
		
		
		wp_deregister_script('jquery');
		wp_register_script( 'jquery-new', RBAGENCY_PLUGIN_URL . 'ext/uploadify/jquery.min.js','', '1.7.2');
		wp_register_script( 'jquery-uploadify', RBAGENCY_PLUGIN_URL.'ext/uploadify/jquery.uploadify.min.js'); 
		wp_register_style( 'uploadify', RBAGENCY_PLUGIN_URL.'ext/uploadify/uploadify.css');
	
		
		wp_print_styles('uploadify');
		
		wp_print_scripts('jquery-new');
		wp_print_scripts('jquery-uploadify');
	}
	
}


add_action('admin_print_scripts', 'rb_agency_profilesphoto_script');
/* add_action('admin_footer', 'rb_agency_profilesphoto_admin_foot');

function rb_agency_profilesphoto_admin_foot(){

	if( $_GET['page'] == 'rb_agency_profiles' and $_GET['action']=='editRecord'){
	
		echo '
		<script>
			
		</script>';
	}
} */


add_action('wp_ajax_profilesphoto_save','profilesphoto_save');
add_action('wp_ajax_nopriv_profilesphoto_save','profilesphoto_save');
function profilesphoto_save(){
	
	global $wpdb,$upload_dir;
	/* ;
	
	$sport = '_temp';
	$root_path = $upload_dir['basedir'];
	
	$image = wp_get_image_editor($root_path .'/'.$img);
	if ( ! is_wp_error($image)){
		//$return_filaneme = teamphoto::savephoto($root_path .'/'.$img,$sport,'','_thumb-');
		echo $return_filaneme;
	}else{
		echo 'error'.$root_path;
	}
	 */
	$img = trim($_POST['imgpath']);
	$ProfileID = trim($_POST['ProfileID']);
	$uploadMediaType = trim($_POST['ProfileMediaType']);
	
	
	$results = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image'";
	$results = $wpdb->get_results($wpdb->prepare($results, $ProfileID),ARRAY_A);
	if ($wpdb->num_rows > 0) {
		$pi = $wpdb->num_rows +1;
	} else {
		$pi = 1;
	}
						
	$params = array(
			'width' => 100,
			'height' => 150,
		);
	$profile_image_src = bfi_thumb( RBAGENCY_UPLOADREL . $img, $params );
	
	$safeProfileMediaFilename = basename($img);
	
	$results = $wpdb->query("INSERT INTO " . 
		table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileMediaOrder) 
		VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "','" . $pi . "')");
	
    $ProfileMediaID = $wpdb->insert_id;
   
	$dataImg =array();
	$toggleClass = "";
	$isChecked = "";
	$isCheckedText = " Set Primary";
	$toDelete = "<a href=\"javascript:confirmDelete('" . $ProfileMediaID . "','" . $uploadMediaType . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
	$massDelete = '<input type="checkbox" name="massgaldel" value="' . $ProfileMediaID . '"> Select';

	echo "<div class=\"item gallery-item".$toggleClass."\">\n";

	echo $toDelete;
	
	echo "  <div class=\"photo\"><img src=\"" . $profile_image_src ."\"/></div>\n";
	echo "		<div class=\"item-order\" style='display:none;'>Order: <input type=\"hidden\" name=\"ProfileMediaOrder_" . $ProfileMediaID . "\" style=\"width: 25px\" value=\"" . $pi . "\" /></div>";
	echo "  	<div class=\"make-primary\"><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"" . $ProfileMediaID . "\" " . $isChecked . " /> " . $isCheckedText . "</div>";
	echo "		<div>".$massDelete."</div>";
	echo "  </div>\n";
									
	exit;
}





