<?php
/**
*This page that will generate HTML to feed on domPDF
*/
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";
	
	// PDF layout style
    $pdf_style = "<link rel=\"stylesheet\" href=\"".RBAGENCY_PLUGIN_URL .'assets/css/style.css'."\"/>
    <style type=\"text/css\">
		body *{visibility: hidden;}
		body{background: #fff;}
		#profile-list,#profile-list *{ visibility: visible;}
		#profile-list{position: absolute;top:20px;}
		#profile-list .rb_profile_tool,#profile-list .rb_profile_tool * {visibility: hidden;display:none;}
		#profile-list .rbprofile-list{float:left;display: block !important;margin-bottom:-30px;}
		#profile-list .rbprofile-list .details{height: 56px;}
		#profile-list .rbprofile-list .image{height: 205px;}
		.rb-print-header, .rb-print-footer,.rb-print-header *, .rb-print-footer *,.rb-print, .rb-print *{display: block !important;visibility: visible !important;}
		.rb-print-header{height:30px;margin-top:0px;clear:both;}
		.rb-print-header img{height:30px;float:left;}
		.rb-print-footer{height:30px;font-size:14px;margin-bottom: 30px;clear:both;}
		.print-clear{clear:both;}
	</style>";
	// Call Header
	$header='
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title> Print</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Robots" content="noindex, nofollow" />
	</head>
	<body  style="background: #fff;">';
	// Call Footer
	$footer='</body>
	</html>';

	// Catch Profile HTML elements
	$result = isset($_POST["profiles"])?stripslashes($_POST["profiles"]):"";
	
	$htmlFile = "DirectBooking-Search-Result-".date("Ymd")."-".rand(100,200).".html"; 
	$pdfFile = str_replace(" ","_",$htmlFile).$fileFormat.".pdf";
	$pdfFile = str_replace(".html","",$pdfFile);

	$blog_title = strtolower(str_replace(" ","_",get_bloginfo('name')));
	$pdfFile = "$blog_title-".$pdfFile;

	$toRedirect = RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
	$path = "wp-content/plugins/rb-agency/ext/dompdf/htmls/";

	$fp = fopen($path.$htmlFile,"w");
	fwrite($fp,$header);
	fwrite($fp,$result);
	fwrite($fp,$footer);
	fwrite($fp,$pdf_style);
	fclose($fp);

	header("Location: $toRedirect");

?>