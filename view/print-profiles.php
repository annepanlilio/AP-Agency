<?php
global $wpdb;
/**
*This page that will generate HTML to feed on domPDF
*/
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";
	$rb_agency_option_detail_city = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_city'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_city']:0;

	// PDF layout style
    $pdf_style = "
    <style type=\"text/css\">
		#profile-list{background:#fff;}
		#profile-list .rbprofile-list {
			width: 20%;
			padding: 10px 20px 20px 0px;
			font-size: 14px;
			max-width: 150px;
			min-width: 100px;
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
			overflow: hidden;
			text-align: center;
			position: relative;
			}
			#profile-list .rbprofile-list .image img {
			width: 100%;
			}
			.image a{
				display: block;
				width: 100%;
				height: 100%;
				background-repeat:no-repeat;
			}
			a{text-decoration:none;color:#000}
			.details-email,.details,.details-merged{font-size:10px;word-wrap:break-word;}
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
	<script language="Javascript1.2">
		<!--
		function printpage() {
			window.print();
		}
		//-->
	</script>
	<body onload="printpage()" style="background: #fff;">';
	// Call Footer
	$footer='</body></html>';
    $footerLogo ='<img style="width: auto; max-height: 60px" src="'.$rb_agency_option_agencylogo.'">';

	// Catch Profile HTML elements
	$profiles = isset($_GET["print_profiles"])?stripslashes($_GET["print_profiles"]):"";
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
	$resultsCount = count($dataList);
	$results = "<div id=\"profile-list\">";
	$photosPerRow = 4;
	$photosPerPage = 12;
	$i = 1;	
	foreach ($dataList as $profile) {

		$image_path = RBAGENCY_UPLOADDIR . $profile["ProfileGallery"]. "/". $profile['ProfileMediaURL'];
		$bfi_params = array(
			'crop'=>true,			
			'width'=> 200,
			'height'=> 230
		);
		$image_src = bfi_thumb( $image_path, $bfi_params );
		
		if($i == 1) {  // First Table
			$results .= "<table style=\"margin-left: -5px; margin-right:-5px;\">";
			$results .= "<tr>";
		} else {
			if(($i %$photosPerPage == 1) || $photosPerPage == 1 ){
				$results .= "<table style=\"margin-left: -5px; margin-right:-5px; page-break-before:always;\">";				
			}
			if(($i %$photosPerRow == 1) || $photosPerRow == 1 ){				
				$results .= "<tr>";
			}
		}

		$stateTitle =  rb_agency_getStateTitle($profile["ProfileLocationState"],false,$arr_query = array());
		$cityTitle = rb_agency_getCityTitle($profile['ProfileID']);
		
		$results .= "<td style=\"max-width:20%; vertical-align: top; padding-left:5px; padding-right:5px;\">";
		$results .= "	<img src=\"".$image_src."\" style=\"width:100%;\">";
		$results .= "	<strong style=\"margin-top:9px;display:block\">".$profile["ProfileContactDisplay"]."</strong>";
		$results .= "	<p style=\"margin-top:0px;margin-bottom:0\">".rb_agency_get_age($profile['ProfileDateBirth'])."</p>";
		$results .= "	<p style=\"margin-top:0px;margin-bottom:0\">".$stateTitle."</p>";
		$results .= "	<p style=\"margin-top:0px\">".$cityTitle."</p>";
		$results .= "</td>";

		if(($i % $photosPerRow == 0) || $i == $resultsCount ){
			$results .= "</tr>";
		}
		if(($i % $photosPerPage == 0) || $i == $resultsCount ){
			$results .= "</tr>";
			$results .= "<tr><td colspan=\"4\">".$footerLogo."</td></tr>";
			$results .= "</table>";
		}
		// $results .= RBAgency_Profile::search_formatted($profile,array(),array(),'',true);
		// if($i%5==0){
		// 	$results .= "<div style=\"clear:both;\"></div>";
		// }
		// if($i%15==0 || $profile === end($dataList)){
		// 	$results .= "<div style=\"clear:both;\"></div>";
		// 	$results .= $footerBlock; 
		// 	$results .= "<div style=\"clear:both;\"></div>";
		// }
		$i++;
	}
	$results .= "</div>";

	$content  = $header.$pdf_style.$results.$footer;
	// echo $content;
	$fileName = "Search-Result_".date("Y-m-d")."-".rand(100,200);
	$htmlFile = $fileName.".html"; 
	// $pdfFile  = $fileName.".pdf";

	// $toRedirect = RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=10x20&output_filed=".$pdfFile."&input_file=".$htmlFile;
	$toRedirect = RBAGENCY_PLUGIN_URL."ext/dompdf/htmls/".$htmlFile;
	$path = RBAGENCY_PLUGIN_DIR."ext/dompdf/htmls/";

	$fp = fopen($path.$htmlFile,"w");
	fwrite($fp,$content);
	fclose($fp);

	header("Location: $toRedirect");
?>