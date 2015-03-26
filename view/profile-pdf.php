<?php
global $wpdb;
/**
*This page that will generate HTML to feed on domPDF
*/
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";
	
	// PDF layout style
    $pdf_style = "
    <style type=\"text/css\">
    	#profile-list{background:#fff;}
    	#profile-list .rbprofile-list {
			width: 35%;
			padding: 10px 20px 20px 0px;
			font-size: 14px;
			max-width: 150px;
			min-width: 150px;
			margin-bottom: 15px;
			position: relative;
			vertical-align: top;
			display:  block;
			float:left;
			box-sizing: border-box;
			line-height: normal;
			}
			#profile-list .rbprofile-list .image {
			width: 100%;
			height: 180px;
			overflow: hidden;
			text-align: center;
			position: relative;
			}
			.image a{
				display: block;
				width: 100%;
				height: 100%;
				background-repeat:no-repeat;
			}
			a{text-decoration:none;color:#000}
			.details-email,.details,.details-merged{font-size:10px;  word-wrap:break-word;}
			.contact{display:block;font-size:10px;word-wrap:break-word;}
	</style>";
	// Call Header
	$header='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	</head>
	<body  style="background: #fff;">';
	// Call Footer
	$footer='</body></html>';
    $footerBlock ='<img style="height:30px page-break-before:always" src="'.$rb_agency_option_agencylogo.'">';
	 
	// Catch Profile HTML elements
	$profiles = isset($_GET["target"])?stripslashes($_GET["target"]):"";
	$profiles = implode(",",array_filter(explode(",",$profiles)));
	
	$sql = "SELECT 
					profile.ProfileID,
					profile.ProfileGallery,
					profile.ProfileContactDisplay,
					profile.ProfileContactNameFirst,
					profile.ProfileContactNameLast,
					profile.ProfileDateBirth,
					profile.ProfileDateCreated,
					profile.ProfileLocationState,
					profile.ProfileLocationCountry,
					profile.ProfileIsActive,
					profile.ProfileContactEmail,
					profile.ProfileContactPhoneCell,
					profile.ProfileContactPhoneWork,
					profile.ProfileContactPhoneHome,
					profile.ProfileContactWebsite,
					(SELECT media.ProfileMediaURL 
						FROM ". table_agency_profile_media ." media  
						WHERE  profile.ProfileID = media.ProfileID  
						AND media.ProfileMediaType = \"Image\"  
						AND media.ProfileMediaPrimary = 1 LIMIT 1) AS ProfileMediaURL 
					FROM ". table_agency_profile ." profile 
						WHERE
					profile.ProfileID IN(".$profiles.")
					ORDER BY FIELD(profile.ProfileID, ".$profiles.")
					";

	$dataList = $wpdb->get_results($sql,ARRAY_A);
	$results = "<div id=\"profile-list\">";
	$i = 1;
	foreach ($dataList as $profile) {
		$results .= RBAgency_Profile::search_formatted($profile,array(),array(),'',true);
		if($i%4==0){
			$results .= "<div style=\"clear:both;\"></div>";
		}
		if($i%12==0 || $profile === end($dataList)){
			$results .= "<div style=\"clear:both;\"></div>";
			$results .= $footerBlock; 
			$results .= "<div style=\"clear:both;\"></div>";
			
		}
		$i++;
	}
	$results .= "</div>";

	$content  = $header.$pdf_style.$results.$footer;
	$fileName = "Search-Result_".date("Y-m-d")."-".rand(100,200);
	$htmlFile = $fileName.".html"; 
	$pdfFile  = $fileName.".pdf";

	$toRedirect = RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=10x20&output_filed=".$pdfFile."&input_file=".$htmlFile;
	$path = RBAGENCY_PLUGIN_DIR."ext/dompdf/htmls/";

	$fp = fopen($path.$htmlFile,"w");
	fwrite($fp,$content);
	fclose($fp);

	header("Location: $toRedirect");
?>