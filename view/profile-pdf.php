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
    	#profile-list{width:95%;margin:auto;}
		#profile-list .rbprofile-list {
			width: 33.33333333333333%;
			padding: 10px 20px 20px 0px;
			font-size: 100%;
			max-width: 200px;
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
			height: 230px;
			overflow: hidden;
			text-align: center;
			position: relative;
			}
			.image a{
				display: block;
				width: 100%;
				height: 100%;
			}
			a{text-decoration:none;color:#000}
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
    $footerBlock ='<img style="margin-top:60px;width:320px; height:67px;" src="'.$rb_agency_option_agencylogo.'">';
	 
	// Catch Profile HTML elements
	$profiles = isset($_GET["profiles"])?stripslashes($_GET["profiles"]):"";
	
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
					(SELECT media.ProfileMediaURL 
						FROM ". table_agency_profile_media ." media  
						WHERE  profile.ProfileID = media.ProfileID  
						AND media.ProfileMediaType = \"Image\"  
						AND media.ProfileMediaPrimary = 1 LIMIT 1) AS ProfileMediaURL 
					FROM ". table_agency_profile ." profile 
						WHERE
					profile.ProfileID IN(".$profiles.")
					";

	$dataList = $wpdb->get_results($sql,ARRAY_A);
	
	$results = "<div id=\"profile-list\">";
	$i = 1;
	foreach ($dataList as $profile) {
		$results .= RBAgency_Profile::search_formatted($profile,array(),array(),'',true);
		if($i%3==0){
			$results .= "<div style=\"clear:both;\"></div>";
		}
		if($i%9==0 || $profile === end($dataList)){
			$results .= "<div style=\"clear:both;\"></div>";
			$results .= $footerBlock; 
		}
		$i++;
	}
	$results .= "</div>";

	$content  = $header.$pdf_style.$results.$footer;
	$fileName = "Search-Result_".date("Y-m-d")."-".rand(100,200);
	$htmlFile = $fileName.".html"; 
	$pdfFile  = $fileName.".pdf";

	$toRedirect = RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=10x16&output_filed=".$pdfFile."&input_file=".$htmlFile;
	$path = RBAGENCY_PLUGIN_DIR."ext/dompdf/htmls/";

	$fp = fopen($path.$htmlFile,"w");
	fwrite($fp,$content);
	fclose($fp);

	header("Location: $toRedirect");
?>