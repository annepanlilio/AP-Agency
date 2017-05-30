<?php

class RBAgency_Customfields{

	public function getCustomFieldsProfileManager($ProfileGender,$param=[]){
		global $wpdb;

		$customFields = $wpdb->get_results("SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowProfile = 1 AND ProfileCustomView = 0 ORDER BY ProfileCustomOrder ASC",ARRAY_A);

		if($param["operation"] == "addProfile"){ 

			if(!isset($param["profileTypes"])){
				foreach($customFields as $customField){
					rb_custom_fields_template_noprofile(1, $customField);
				}
			}else{
				// if profile type is being selected
				foreach($customFields as $customField){
					# check if available in specific gender
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);

					# check if available in specific data type
					$customfield_types = $wpdb->get_row("SELECT ProfileCustomTypes FROM ".$wpdb->prefix."agency_customfields_types WHERE ProfileCustomID = ".$customField['ProfileCustomID'],ARRAY_A);
					$checkedTypes = [];
					$customfield_types_arr = explode(",",str_replace(" ","_", $customfield_types["ProfileCustomTypes"]));
					foreach($param["profileTypes"] as $k=>$v){
						if(in_array(str_replace(" ", "_", $v), $customfield_types_arr)){
							$checkedTypes[] = $v;
						}
					}					
					if( (strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender') && !empty($checkedTypes) ){
						rb_custom_fields_template_noprofile(1, $customField);
					}					
				}
			}
			
		}elseif($param["operation"] == "editProfile"){

			if(!isset($param["profileTypes"])){
				foreach($customFields as $customField){
					rb_custom_fields_template(1, $param["ProfileID"],$customField);
				}
			}else{
				// if profile type is being selected
				foreach($customFields as $customField){
					# check if available in specific gender
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);

					# check if available in specific data type
					$customfield_types = $wpdb->get_row("SELECT ProfileCustomTypes FROM ".$wpdb->prefix."agency_customfields_types WHERE ProfileCustomID = ".$customField['ProfileCustomID'],ARRAY_A);
					$checkedTypes = [];
					$customfield_types_arr = explode(",",str_replace(" ","_", $customfield_types["ProfileCustomTypes"]));
					foreach($param["profileTypes"] as $k=>$v){
						if(in_array(str_replace(" ", "_", $v), $customfield_types_arr)){
							$checkedTypes[] = $v;
						}
					}					
					if( (strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender') && !empty($checkedTypes) ){
						rb_custom_fields_template(1, $param["ProfileID"],$customField);
					}					
				}
			}
		}else{
			
			foreach($customFields as $customField){
				$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
				$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);
				//echo $genderTitle."=".$customFieldGenders."<br/>";
				if( strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender'){
						rb_custom_fields_template_noprofile(1, $customField);
				}
			}
		}		

	}

	public function getCustomFieldsProfileManagerPrivate($ProfileGender,$param=[]){
		global $wpdb;

		$customFields = $wpdb->get_results("SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowProfile = 1 AND ProfileCustomView = 1 OR ProfileCustomView = 2 ORDER BY ProfileCustomOrder ASC",ARRAY_A);

		if($param["operation"] == "addProfile"){ 

			if(!isset($param["profileTypes"])){
				foreach($customFields as $customField){
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);
					if( strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender'){
						rb_custom_fields_template_noprofile(1, $customField);
					}
					
				}
			}else{
				// if profile type is being selected
				foreach($customFields as $customField){
					# check if available in specific gender
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);

					# check if available in specific data type
					$customfield_types = $wpdb->get_row("SELECT ProfileCustomTypes FROM ".$wpdb->prefix."agency_customfields_types WHERE ProfileCustomID = ".$customField['ProfileCustomID'],ARRAY_A);
					$checkedTypes = [];
					$customfield_types_arr = explode(",",str_replace(" ","_", $customfield_types["ProfileCustomTypes"]));
					foreach($param["profileTypes"] as $k=>$v){
						if(in_array(str_replace(" ", "_", $v), $customfield_types_arr)){
							$checkedTypes[] = $v;
						}
					}					
					if( (strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender') && !empty($checkedTypes) ){
						rb_custom_fields_template_noprofile(1, $customField);
					}					
				}
			}
			
		}elseif($param["operation"] == "editProfile"){

			if(!isset($param["profileTypes"])){
				foreach($customFields as $customField){
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);
					if( strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender'){
						rb_custom_fields_template(1, $param["ProfileID"],$customField);
					}
				}
			}else{
				// if profile type is being selected
				foreach($customFields as $customField){
					# check if available in specific gender
					$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
					$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);

					# check if available in specific data type
					$customfield_types = $wpdb->get_row("SELECT ProfileCustomTypes FROM ".$wpdb->prefix."agency_customfields_types WHERE ProfileCustomID = ".$customField['ProfileCustomID'],ARRAY_A);
					$checkedTypes = [];
					$customfield_types_arr = explode(",",str_replace(" ","_", $customfield_types["ProfileCustomTypes"]));
					foreach($param["profileTypes"] as $k=>$v){
						if(in_array(str_replace(" ", "_", $v), $customfield_types_arr)){
							$checkedTypes[] = $v;
						}
					}					
					if( (strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender') && !empty($checkedTypes) ){
						rb_custom_fields_template(1, $param["ProfileID"],$customField);
					}					
				}
			}
		}else{
			
			foreach($customFields as $customField){
				$genderTitle = rb_agency_getGenderTitle($ProfileGender);						
				$customFieldGenders = get_option("ProfileCustomShowGenderArr_".$customField['ProfileCustomID']);

				
				if( strpos($customFieldGenders,$genderTitle)>-1 || $customFieldGenders == 'All Gender'){
						rb_custom_fields_template_noprofile(1, $customField);
				}
				
			}
		}
	}

}

function rb_get_customfields_load(){
	global $wpdb;
	$profileGenderID = $_POST['gender'];
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManager($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_load','rb_get_customfields_load');
add_action('wp_ajax_nopriv_rb_get_customfields_load','rb_get_customfields_load');


function rb_get_customfields_add_profile(){
	global $wpdb;
	$profileGenderID = $_POST['gender'];
	$param["operation"] = "addProfile";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManager($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_add_profile','rb_get_customfields_add_profile');
add_action('wp_ajax_nopriv_rb_get_customfields_add_profile','rb_get_customfields_add_profile');


function rb_get_customfields_add_profile_onchanged_profiletype(){
	global $wpdb;
	
	$profileGenderID = $_POST["gender"];
	$param["operation"] = "addProfile";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";	
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManager($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_add_profile_onchanged_profiletype','rb_get_customfields_add_profile_onchanged_profiletype');
add_action('wp_ajax_nopriv_rb_get_customfields_add_profile_onchanged_profiletype','rb_get_customfields_add_profile_onchanged_profiletype');


function rb_get_customfields_edit_profile(){
	global $wpdb;
	$profileGenderID = $_POST['gender'];
	$param["operation"] = "editProfile";
	$param["ProfileID"] = !empty($_POST['profileID']) ? $_POST['profileID'] : "";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManager($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_edit_profile','rb_get_customfields_edit_profile');
add_action('wp_ajax_nopriv_rb_get_customfields_edit_profile','rb_get_customfields_edit_profile');


function rb_get_customfields_edit_profile_onchanged_profiletype(){
	global $wpdb;
	$profileGenderID = $_POST["gender"];
	$param["operation"] = "editProfile";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";	
	$param["ProfileID"] = !empty($_POST['profileID']) ? $_POST['profileID'] : "";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManager($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_edit_profile_onchanged_profiletype','rb_get_customfields_edit_profile_onchanged_profiletype');
add_action('wp_ajax_nopriv_rb_get_customfields_edit_profile_onchanged_profiletype','rb_get_customfields_edit_profile_onchanged_profiletype');

//PRIVATE
function rb_get_customfields_load_private(){
	global $wpdb;
	$profileGenderID = $_POST['gender'];
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_load_private','rb_get_customfields_load_private');
add_action('wp_ajax_nopriv_rb_get_customfields_load_private','rb_get_customfields_load_private');


function rb_get_customfields_add_profile_private(){
	global $wpdb;
	$profileGenderID = $_POST['gender'];
	$param["operation"] = "addProfile";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_add_profile_private','rb_get_customfields_add_profile_private');
add_action('wp_ajax_nopriv_rb_get_customfields_add_profile_private','rb_get_customfields_add_profile_private');


function rb_get_customfields_add_profile_onchanged_profiletype_private(){
	global $wpdb;
	
	$profileGenderID = $_POST["gender"];
	$param["operation"] = "addProfile";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";	
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_add_profile_onchanged_profiletype_private','rb_get_customfields_add_profile_onchanged_profiletype_private');
add_action('wp_ajax_nopriv_rb_get_customfields_add_profile_onchanged_profiletype_private','rb_get_customfields_add_profile_onchanged_profiletype_private');


function rb_get_customfields_edit_profile_private(){
	global $wpdb; 
	$profileGenderID = $_POST['gender'];
	$param["operation"] = "editProfile";
	$param["ProfileID"] = !empty($_POST['profileID']) ? $_POST['profileID'] : "";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_edit_profile_private','rb_get_customfields_edit_profile_private');
add_action('wp_ajax_nopriv_rb_get_customfields_edit_profile_private','rb_get_customfields_edit_profile_private');


function rb_get_customfields_edit_profile_onchanged_profiletype_private(){
	global $wpdb;
	
	$profileGenderID = $_POST["gender"];
	$param["operation"] = "editProfile";
	$param["profileTypes"] = !empty($_POST["profile_types"]) ? $_POST["profile_types"] : "";	
	$param["ProfileID"] = !empty($_POST['profileID']) ? $_POST['profileID'] : "";
	$rbagencyCustomfieldsClass = new RBAgency_Customfields();
	echo $rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($profileGenderID,$param);
	die();
}
add_action('wp_ajax_rb_get_customfields_edit_profile_onchanged_profiletype_private','rb_get_customfields_edit_profile_onchanged_profiletype_private');
add_action('wp_ajax_nopriv_rb_get_customfields_edit_profile_onchanged_profiletype_private','rb_get_customfields_edit_profile_onchanged_profiletype_private');