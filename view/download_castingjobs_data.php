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
	$objPHPExcel->getActiveSheet()->fromArray(array($fixed_headers),NULL,'A'.$rowNumber);
}
$data = array();
foreach($casting_jobs as $casting_job){
		$rowNumber++;
		$data['CastingJobAgencyProducer'] = $casting_job['Job_UserLinked'];
		$data['CastingJobJobTitle'] = $casting_job['Job_Title'];
		$data['CastingJobDescription'] = $casting_job['Job_Text'];
		$data['CastingJobOffer'] = $casting_job['Job_Offering'];
		$data['CastingJobJobDateStart'] = $casting_job['Job_Date_Start'];
		$data['CastingjobjobDateEnd'] = $casting_job['Job_Date_End'];
		$data['CastingJobLocation'] = $casting_job['Job_Location'];
		$data['CastingJobRegion'] = $casting_job['Job_Region'];
		$data['CastingJobjobType'] = $casting_job['Job_Type'];
		$data['CastingJobJobVisibility'] = $casting_job['Job_Visibility'];
		$data['CastingJobAuditionDateStart'] = $casting_job['Job_Audition_Date_Start'];
		$data['CastingJobAuditionDateEnd'] = $casting_job['Job_Audition_Date_End'];
		$data['CastingJobAuditionTime'] = $casting_job['Job_Audition_Time'];
		$data['CastingJobAuditionVenue']  = $casting_job['Job_Audition_Venue'];

		$objPHPExcel->getActiveSheet()->fromArray(array($data),NULL,'A'.$rowNumber);
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