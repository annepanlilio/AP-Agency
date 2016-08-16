<?php


/* Throughout $screen_id is assumed to hold the screen ID */
/*  
 add_action( 'current_screen', 'thisScreen');

function thisScreen(){
    $currentScreen = get_current_screen();
   // if( $currentScreen->id === "widgets" ) {
        // Run some code, only on the admin widgets page
	print_r($currentScreen);
    //}
    
}
  */

  $screen_id = 'agency_page_rb_agency_searchsaved';
/* Add callbacks for this screen only. */
add_action('load-'.$screen_id, 'wptuts_add_screen_meta_boxes');
add_action('admin_footer-'.$screen_id,'wptuts_print_script_in_footer');

function wptuts_add_screen_meta_boxes() {
 
    /* Trigger the add_meta_boxes hooks to allow meta boxes to be added */
    do_action('add_meta_boxes_'.$screen_id, null);
 
    /* Enqueue WordPress' script for handling the meta boxes */
    wp_enqueue_script('postbox');
    
   wp_enqueue_style( 'image-picker-css', RBAGENCY_PLUGIN_URL .'ext/image-picker/image-picker.css');
   wp_enqueue_script( 'image-picker-js', RBAGENCY_PLUGIN_URL .'ext/image-picker/image-picker.min.js');
    
    
    
    //
 
    /* Add screen option: user can choose between 1 or 2 columns (default 2) */
    //add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
}
 
/* Prints script in footer. This 'initialises' the meta boxes */
function wptuts_print_script_in_footer() {
    ?>
    <script>jQuery(document).ready(function(){ postboxes.add_postbox_toggles(pagenow); });</script>
    <?php
}

add_action('wp_ajax_generatepdfEcard','generatepdfEcard');
add_action('wp_ajax_nopriv_generatepdfEcard','generatepdfEcard');

function generatepdfEcard(){
	
	
	
	
	ob_start();
	$modelData = $_POST['pics'];
#This page that will generate HTML to feed on domPDF
global $wpdb;

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];

if(strpos(get_site_url(),'localhost') !== false){
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo']) ? RBAGENCY_PLUGIN_URL."assets/img/".basename($rb_agency_options_arr['rb_agency_option_agencylogo']): RBAGENCY_PLUGIN_URL ."assets/img/logo_example.jpg";
} else {
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo']) ? get_site_url().$rb_agency_options_arr['rb_agency_option_agencylogo'] : "";
}



$toLandScape = "";
$wrapperWidthHeight = "";
$ProfileGender = "";
$modelInfo = "";
$pdf_image_id = "";
$orderBy = "";
$allImages = "";
$cnt = 0;
$cnt1  = 0;
$cnt2  = 0;
$footer = "";
$paperDef = "";
$pdf_image_id = "";

$header='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>eCard</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	<link rel="stylesheet" type="text/css" media="screen, print"  />
	<script language="Javascript1.2">
      <!--
      function printpage() {
       //window.print();
      }
      //-->
    </script>
	'; 

$widthAndHeight='style="width:91px; "';
$model_info_width="width:154px;"; 
$col=5;
$perPage=10;
$w = 91;
$fileFormat="_division";



if(!empty($rb_agency_options_arr['rb_agency_option_agencylogo'])){
	$size = getimagesize(site_url() . $rb_agency_options_arr['rb_agency_option_agencylogo']);
	if (!$size){
		$pdf_logo = $rb_agency_options_arr['rb_agency_option_agencylogo'];
		$logo_height= getimagesize( $rb_agency_options_arr['rb_agency_option_agencylogo']);
	}else{
		$pdf_logo = site_url() . $rb_agency_options_arr['rb_agency_option_agencylogo'];
		$logo_height = $size;
	}
}





$header.='
	<style>
	*,html{font-family: Arial, Tahoma;}
	ul{list-style:none; }
	#Experience{display:none;}
	
	@page { margin: '. ((int)$logo_height[1] + 60) .'px 50px 50px 50px;}
	#headerlogo { border:0px solid #d00; position: fixed; left: 0px; top: -'. ((int)$logo_height[1] + 40) .'px; right: 0px; height: '. (int)$logo_height[1] .'px; text-align: center; }

	body{color:#000;font-size:11px;}
	
	h3{font-size:14px;font-weight:bold;border:0;margin:0;padding:0;}
	h4{font-size:16px;font-weight:bold;border:0;margin:0;padding:0;text-transform:uppercase;text-align:center;}
	h3 a{}
	div{display:inline-block;}
	ul li,ul{list-style:none;}
	
	div.infos{background-color: #ddd;width: 200px;min-height:250px;padding: 5px;}
	.model-pics{width:106px;height:130px; padding: 3px;border:1px solid #ddd; margin: 10px 5px;}
	.model-container{display:block; clear:both;}
	</style>

	</head>
	<body style="background: #fff;">
	
	<div id="headerlogo"><img src="'.$pdf_logo.'"></div>
	';

	
$modelProfiles='';
$modelProfiles='';

foreach($modelData as $value){
  $modelProfiles[]=key($value);
  $modelProfilesPhoto[key($value)]=$value;
}

$cartString = implode(",",$modelProfiles);


$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];





	foreach($modelProfilesPhoto as $models){
		foreach($models as $_models => $_model){
			
			$ProfileID = $_models;
			$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID = $ProfileID GROUP BY profile.ProfileID ORDER BY profile.ProfileID ASC";
			$result_model = $wpdb->get_results($query,ARRAY_A);
			$count = count($results);
			
			
			$ProfileGallery  = $result_model[0]['ProfileGallery'];
			$ProfileGender   = $result_model[0]['ProfileGender'];
			$_excludeField = array('Dress Size','Skin Tone','Ethnicity','Language','Best Way to Contact You',
				'Expertise','Experience');
				$_excludeField = array();
			$ret =rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender,$_excludeField ,false);
			
			$table.= "<div class='model-container'>
				<h3><b> {$result_model[0]['ProfileContactDisplay']}</b> : 
					<a href='". site_url() ."/profile/{$result_model[0]['ProfileGallery']}' target='_blank'>"
				. site_url() ."/profile/{$result_model[0]['ProfileGallery']}</a>
				</h3>
				<br/><br/>
				
				
				<div class='infos'>
					<h4>{$result_model[0]['ProfileContactDisplay']}</h4>
					<ul>
					{$ret}";
					
					if(!empty($result_model[0]['ProfileLocationCity'])){
					$table.= "<li><strong>Location:</strong> {$result_model[0]['ProfileLocationCity']}</li>";
					}
					$table.="
					</ul>
				
				</div>
				";
				
			foreach($_model as $_pics => $_pic){
				
				$ProfileMediaID = $_pic;
				$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE 
					ProfileMediaID = ".$ProfileMediaID." GROUP BY(ProfileMediaURL) ORDER BY ProfileMediaID DESC,ProfileMediaPrimary DESC "
				);
								
				$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
				$countImg =$wpdb->num_rows;
				
				foreach($resultsImg as $dataImg ){
					$image_path =  RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
					$bfi_params = array(
						'crop'=>true,
						'width'=>106,
						'height'=>130
					);
					$image_src = bfi_thumb( $image_path, $bfi_params );

					$size = getimagesize(site_url() . $image_src);
					if (!$size){
						$table.="<img src=\"". $image_src."\" class='model-pics' />";
					}else{
						$table.="<img src=\"". site_url() . $image_src."\" class='model-pics' />";
					}
				}
						
						
			}
			
			$table.= "
			
			<div style='display:block;clear:both;'><br/></div>
			
			</div>";
		}
	}

	
	
					
					$ProfileID = $model['ProfileID'];
					$ProfileGallery = $model['ProfileGallery'];
					
					$image_type =array('Image','Polaroid','Headshot');
					$photoString = implode(",",$modelProfilesPhoto);
					
					
					foreach($modelProfilesPhoto[$ProfileID] as $ProfileMedia => $val){
					
					
						//$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,$display_imagetype);
						
						
						$ProfileMediaID = $ProfileMedia['ProfileMediaID'];
						/* $queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE 
							ProfileID =  \"%s\" AND ProfileMediaType = \"%s\" ". $sql_exclude_primary_image ." GROUP BY(ProfileMediaURL) ORDER BY ProfileMediaID DESC,ProfileMediaPrimary DESC ". $sql_count
							, $ProfileID, $display_imagetype);
							 */
							$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE 
							ProfileMediaID = ".$ProfileMediaID." GROUP BY(ProfileMediaURL) ORDER BY ProfileMediaID DESC,ProfileMediaPrimary DESC "
							);
								
							
							
		
						$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
						$countImg =$wpdb->num_rows;
						//echo '/s/s/s/';
						
						
						//print_r($val);
						
						//echo '/s/s/s/';
						
						//print_r($resultsImg );
		
		
						
					}
					//$queryImg = rb_agency_option_galleryorder_query("ProfileMediaID" ,$ProfileID,"Polaroid");

											
				
				
				/* 
				$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") GROUP BY profile.ProfileID ORDER BY profile.ProfileID ASC";
				$results = $wpdb->get_results($query,ARRAY_A);
				$count = count($results);
				
					
				foreach($results as $model){

					//$table
				
				}




 */

$footer.='
</div>
</body>
</html>';


$border='
<div style="width:770px; height:760px; border:1px solid #000;">
</div>';




$htmlFile=rand(1,10000).time().date("ymd").".html"; 
$ProfileContactDisplay = $rb_agency_options_arr['rb_agency_option_agencyname'] . '_eCard_'.date("Ymd").'_'.time('U');


$format="-Polaroids-1perpage-with-Info-";

$pdfFile=str_replace(" ","-",$ProfileContactDisplay).".pdf";

$path=RBAGENCY_PLUGIN_DIR."ext/dompdf/htmls/";
//*include("/wp-content/plugins/rb-agency/dompdf/htmls/test.txt");
$fp=fopen($path.$htmlFile,"w");
fwrite($fp,$header);
//*fwrite($fp,$border);

//fwrite($fp,$allImages);
fwrite($fp,$table);
fwrite($fp,$footer);
fclose($fp);
$toRedirect=RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
//*die($toRedirect);




echo $toRedirect;

//wp_redirect($toRedirect); 

	exit;
}




add_action('wp_ajax_sendcastmail','sendcastmail');
add_action('wp_ajax_nopriv_sendcastmail','sendcastmail');
function sendcastmail(){
	//$to = "jenner.alagao@gmail.com";
	$subject = "HTML email";
	$message = "<html><head><title>HTML email</title></head><body><p>This email contains HTML Tags!</p></body></html>";
	
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <webmaster@example.com>' . "\r\n";
	//$headers .= 'Cc: myboss@example.com' . "\r\n";
	/* 
	wp_mail($to,$subject,$message,$headers);
	wp_mail($to,$subject,$message);
	 */
	echo 'success email.';
	//echo $to,$subject,$message,$headers;
	exit;
}



add_action('wp_ajax_generate_admin_cusfieldscheckbox','generate_admin_cusfieldscheckbox');
add_action('wp_ajax_nopriv_generate_admin_cusfieldscheckbox','generate_admin_cusfieldscheckbox');
function generate_admin_cusfieldscheckbox(){
	
	global $wpdb, $_POST;
	
	echo $_POST['DataID'];
	$profileID =  $_POST['profileID'];
	$ProfileGender =  $_POST['gender'];
	$profile_types =  $_POST['profile_types'];
	
	//gender
	//profile_types
	
	//get current profile types // safe for cancelling edit profile
	$userProfilesDB = $wpdb->get_var("SELECT ProfileType FROM ".table_agency_profile ." WHERE ProfileID=".$profileID);
	$userGenderDB = $wpdb->get_var("SELECT ProfileGender FROM ".table_agency_profile ." WHERE ProfileID=".$profileID);
	
	//temporary change the profile type of user
	$wpdb->update(table_agency_profile, array('ProfileType' => $profile_types , 'ProfileGender' => $ProfileGender),array( 'ProfileID' => $profileID));
	echo $wpdb->last_error;
	//give the new set ung custom fields
	rb_custom_fields(0, $profileID, $ProfileGender, true);
	
	//retrieve the tempo change on profiles (profile type and gender)
	$wpdb->update(table_agency_profile, array('ProfileType' => $userProfilesDB , 'ProfileGender' => $userGenderDB),array( 'ProfileID' => $profileID));
	exit;
}




add_action('wp_ajax_save_data_custom_field','save_data_custom_field');
add_action('wp_ajax_nopriv_save_data_custom_field','save_data_custom_field');
function save_data_custom_field(){
	
	global $wpdb, $_POST;
	
	/* echo $_POST['DataID'];
	DataID: dataTypeID, 
	customFieldID: customFieldID,
	inChange: inChangeInfo,
	 */
	 
	$CustomTypeTitle = $wpdb->get_var("SELECT DataTypeTitle FROM ".table_agency_data_type ." WHERE DataTypeID = {$_POST['DataID']}");
	
	$ProfileCustomTypes = $wpdb->get_var("SELECT ProfileCustomTypes FROM ".table_agency_customfields_types ." WHERE ProfileCustomID = {$_POST['customFieldID']}");
	
	$ProfileCustomTypes_exp = explode(',',$ProfileCustomTypes);
	
	
	print_r($ProfileCustomTypes_exp);
	//delete to custom field
	if($_POST['inChange'] == 0){
		$ProfileCustomTypes_exp = array_diff($ProfileCustomTypes_exp, array($CustomTypeTitle));
	}else{
		$ProfileCustomTypes_exp[] = $CustomTypeTitle;
	}
	echo $ProfileCustomTypes;
	
	echo $CustomTypeTitle;
	print_r($ProfileCustomTypes_exp);
	
	$ProfileCustomTypes_imp = implode(',',array_filter($ProfileCustomTypes_exp));
	
	$wpdb->update(table_agency_customfields_types, array('ProfileCustomTypes' => $ProfileCustomTypes_imp),array( 'ProfileCustomID' => $_POST['customFieldID']));
	echo $wpdb->last_error;
	
	
	
	echo 'success changes on data custom fields.';
	//echo $to,$subject,$message,$headers;
	exit;
}

add_action('wp_ajax_save_data_type_gender','save_data_type_gender');
add_action('wp_ajax_nopriv_save_data_type_gender','save_data_type_gender');
function save_data_type_gender(){
	
	global $wpdb, $_POST;
	
	echo $_POST['DataID'];
	$wpdb->update(table_agency_data_type, array('DataTypeGenderID' => $_POST['GenderID']),array( 'DataTypeID' => $_POST['DataID']));
	echo $wpdb->last_error;
	echo 'success changes on data type gender.';
	//echo $to,$subject,$message,$headers;
	exit;
}
	
add_action('wp_ajax_request_datatype_bygender_memberregister','request_datatype_bygender_memberregister');
add_action('wp_ajax_nopriv_request_datatype_bygender_memberregister','request_datatype_bygender_memberregister');
function request_datatype_bygender_memberregister(){
	
	global $wpdb, $_POST;
	
	$genderID = $_POST['GenderID'];
	$query3 = "SELECT * FROM " . table_agency_data_type . " WHERE DataTypeParentID = 0 AND (DataTypeGenderID = {$genderID} OR DataTypeGenderID = 0) ORDER BY DataTypeTitle";
	
		
				$results3 = $wpdb->get_results($query3,ARRAY_A);
				$count3 =  $wpdb->num_rows;
	echo "		<div>";
				  $ptype_arr = isset($_POST["ProfileType"]) && !empty($_POST["ProfileType"])?$_POST["ProfileType"]: array();
				foreach($results3 as $data3) {

					$rb_agency_uri_profiletype = ucfirst($rb_agency_uri_profiletype);
					$profiletypeid = $wpdb->get_var($wpdb->prepare("SELECT DataTypeID FROM " . table_agency_data_type . " WHERE DataTypeTitle = %s",$rb_agency_uri_profiletype));

					if(!empty($rb_agency_uri_profiletype) && isset($profiletypeid)){
						if($profiletypeid ==  $data3["DataTypeID"]){
								echo "<div><label><input type=\"checkbox\" checked='checked' name=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\" id=".$data3['DataTypeID']." myparent=".$data3['DataTypeParentID']." profile-type-title=\"".$data3['DataTypeTitle']."\" class=\"DataTypeIDClassCheckbox\" /><span> " . $data3['DataTypeTitle'] . "</span></label></div>";
						}
					} else {
						echo "<div><label><input type=\"checkbox\" ".(in_array($data3["DataTypeID"],$ptype_arr)?"checked='checked'":"")." name=\"ProfileType[]\" id=".$data3['DataTypeID']." myparent=".$data3['DataTypeParentID']." profile-type-title=\"".$data3['DataTypeTitle']."\" class=\"DataTypeIDClassCheckbox\" value=\"" . $data3['DataTypeID'] . "\"  /><span> " . $data3['DataTypeTitle'] . "</span></label></div>";
					}

					//do_action('rb_get_profile_type_childs_checkbox_ajax_register_display',$data3["DataTypeID"]);
				}
	exit;
}
	


	