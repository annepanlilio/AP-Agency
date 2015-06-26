<?php
ob_start();
error_reporting(0);
// Tap into WordPress Database
@include_once('../../../../wp-config.php');
@include_once('../../../../wp-load.php');
@include_once('../../../../wp-includes/wp-db.php');
global $wpdb;

$query_agency_orders = "SELECT * FROM ". $wpdb->prefix."agency_casting_job";
$casting_jobs = $wpdb->get_results($query_agency_orders,ARRAY_A);
$rowNumber = 1;


function get_casting_contact_display($casting_user_link_id){
	global $wpdb;
	$query = "SELECT * FROM ". $wpdb->prefix."agency_casting WHERE CastingUserLinked = ".$casting_user_link_id;
	$casting_contacts = $wpdb->get_results($query,ARRAY_A);
	if($wpdb->num_rows == 0){
		return false;
	}else{
		$casting_name = null;
		foreach($casting_contacts as $casting_contact){
			$casting_name = $casting_contact["CastingContactNameFirst"]." ".$casting_contact["CastingContactNameLast"];
		}
		return $casting_name;
	}
	
}

function get_casting_job_type($casting_job_type_id){
	global $wpdb;
	$query = "SELECT * FROM ". $wpdb->prefix."agency_casting_job_type WHERE Job_Type_ID = ".$casting_job_type_id;
	$casting_contacts = $wpdb->get_results($query,ARRAY_A);
	foreach($casting_contacts as $casting_contact)
		return $casting_contact["Job_Type_Title"];
}

function get_custom_field_title($custom_field_id){
	global $wpdb;
	$query = "SELECT * FROM ". $wpdb->prefix."agency_customfields WHERE ProfileCustomID = ".$custom_field_id;
	$custom_titles = $wpdb->get_results($query,ARRAY_A);
	foreach($custom_titles as $custom_title)
		return $custom_title["ProfileCustomTitle"];
}

if(isset($_POST)) {
	require_once('../ext/PHPExcel.php');
	require_once('../ext/PHPExcel/IOFactory.php');
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	
	//setting up the headers
	$fixed_headers = array(
		'CastingJobAgencyProducer',
		'CastingJobJobTitle',
		'CastingJobDescription',
		'CastingJobOffer',
		'CastingJobJobDateStart',
		'CastingjobjobDateEnd',
		'CastingJobLocation',
		'CastingJobRegion',
		'CastingJobjobType',
		'CastingJobJobVisibility',
		'CastingJobAuditionDateStart',
		'CastingJobAuditionDateEnd',
		'CastingJobAuditionTime',
		'CastingJobAuditionVenue'
	);

	global $wpdb;
	$query = "SELECT * FROM ". $wpdb->prefix."agency_customfields WHERE ProfileCustomShowCastingJob = 1 ORDER BY ProfileCustomOrder ASC";
	$custom_headers = $wpdb->get_results($query,ARRAY_A);
	foreach($custom_headers as $custom_header){
		array_push($fixed_headers,$custom_header["ProfileCustomTitle"]);
	}	

	$objPHPExcel->getActiveSheet()->fromArray(array($fixed_headers),NULL,'A'.$rowNumber);
}

$data = array();
foreach($casting_jobs as $casting_job){
	if(get_casting_contact_display($casting_job['Job_UserLinked']) === false){

	}else{
		$rowNumber++;
		$data['CastingJobAgencyProducer'] = get_casting_contact_display($casting_job['Job_UserLinked']);
		$data['CastingJobJobTitle'] = $casting_job['Job_Title'];
		$data['CastingJobDescription'] = $casting_job['Job_Text'];
		$data['CastingJobOffer'] = $casting_job['Job_Offering'];
		$data['CastingJobJobDateStart'] = $casting_job['Job_Date_Start'];
		$data['CastingjobjobDateEnd'] = $casting_job['Job_Date_End'];
		$data['CastingJobLocation'] = $casting_job['Job_Location'];
		$data['CastingJobRegion'] = $casting_job['Job_Region'];
		$data['CastingJobjobType'] = get_casting_job_type($casting_job['Job_Type']);
		$data['CastingJobJobVisibility'] = $casting_job['Job_Visibility'];
		$data['CastingJobAuditionDateStart'] = $casting_job['Job_Audition_Date_Start'];
		$data['CastingJobAuditionDateEnd'] = $casting_job['Job_Audition_Date_End'];
		$data['CastingJobAuditionTime'] = $casting_job['Job_Audition_Time'];
		$data['CastingJobAuditionVenue']  = $casting_job['Job_Audition_Venue'];
		
		$query_custom_field_values = "SELECT DISTINCT(Customfield_ID),Customfield_value,Job_ID FROM ".$wpdb->prefix."agency_casting_job_customfields WHERE Job_ID = ".$casting_job["Job_ID"];
		$custom_field_results = $wpdb->get_results($query_custom_field_values,ARRAY_A);
		
		$data_custom = array();
		foreach($custom_field_results as $custom_field_result){
			$custom_value = $custom_field_result['Customfield_value'];
			array_push($data_custom,$custom_value);
		}

		$objPHPExcel->getActiveSheet()->fromArray(array(array_merge($data,$data_custom)),NULL,'A'.$rowNumber);
	}
		
		
}


$extension = ".xls";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,  "Excel5");
$objWriter->save(str_replace('.php', '.'.$extension, __FILE__));

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=".$_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time()).'-casting-job.'.$extension); 
header("Content-Transfer-Encoding: binary ");
ob_clean();
flush();
readfile(str_replace('.php', '.'.$extension, __FILE__));
unlink(str_replace('.php', '.'.$extension, __FILE__));


