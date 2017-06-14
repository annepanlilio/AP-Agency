<?php ob_start();?>
<?php 
#This page that will generate HTML to feed on domPDF
global $wpdb;

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];

if(strpos(get_site_url(),'localhost') !== false){
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo']) ? RBAGENCY_PLUGIN_URL."assets/img/".basename($rb_agency_options_arr['rb_agency_option_agencylogo']): RBAGENCY_PLUGIN_URL ."assets/img/logo_example.jpg";
} else {
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo']) ? $rb_agency_options_arr['rb_agency_option_agencylogo'] : "";
}


$printType = $_POST['print_type'];
$printOption = $_POST['print_option'];
$pdfImageID = $_POST['pdf_image_id'];
$profileImages = "";

//check if http include
/* if(strpos(rb_agency_option_agencylogo,'http') === false){
	$rb_agency_option_agencylogo =  get_site_url(). $rb_agency_option_agencylogo;
} */

$toLandScape = "";
$ul_css = "";
$additionalCss = "";
$wrapperWidthHeight = "";
$ProfileGender = "";
$modelInfo = "";
$orderBy = "";
$allImages = "";
$cnt = 0;
$cnt1  = 0;
$cnt2  = 0;
$footer = "";
$paperDef = "";
$has_info = 0;

$header='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Print</title>
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

//where we decide what print format will it be.

if($_POST['print_option']=="1"){ // Print Large Photos
	$photoSize = array(500,580);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 1, $pdfImageID, $ProfileGallery, 500, 2, 1, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']=="1-1"){ // Print Large Photos Without Model Info
	$widthAndHeight='style="width:183px;"';
	$model_info_width="width:182px;"; 
	$col=4;
	$perPage=8;
	$w = 183;
	$photoSize = array(500,580);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 0, $pdfImageID, $ProfileGallery, 500, 2, 1, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']==2){
	$widthAndHeight='style="width:183px;"';
	$model_info_width="width:182px;"; 
	$col=4;
	$perPage=8;
	$w = 183;
}

elseif($_POST['print_option']=="3"){ // Print Medium Size Photos
	$widthAndHeight='style="width:256px; height:300px"';
	$model_info_width="width:256px;"; 
	$col=4;
	$perPage=8;
	$fileFormat="_medium";
	$w = 256;
	$h = 300;
	$photoSize = array(244,285);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 1, $pdfImageID, $ProfileGallery, 244, 8, 6, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']=="3-1"){ // Print Medium Size Photos Without Model Info
	$widthAndHeight='style="width:183px; "';
	$model_info_width="width:182px;"; 
	$col=4;
	$perPage=8;
	$fileFormat="_medium";
	$w = 183;
	$h = 150;
	$photoSize = array(244,285);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 0, $pdfImageID, $ProfileGallery, 244, 8, 6, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']==4){
	$widthAndHeight='style="width:500px"';
	$ul_css="width:100%;";
	$model_info_width="width:202px; height:320px;";
	$w = 500;
}

elseif($_POST['print_option']==5){
	$widthAndHeight='style="width:100px"';
	$wrapperWidthHeight="width:887px; height:600px;"; 
	$toLandScape='@page{size: landscape;margin: 2cm;}';
	$w = 100;
}

elseif($_POST['print_option']=="11"){
	$widthAndHeight='style="width:232px; "';
	$model_info_width="width:158px; border:0px solid #000;"; 
	$col=2;
	$perPage=4;
	$fileFormat="_4perpage";
	$additionalCss="#4, #6, #8, #10, #12, #14, #16, #18 {margin-left:158px;}";
	$paperDef="10x16"; //pdf size
	$w = 232;
	$photoSize = array(325,285);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 1, $pdfImageID, $ProfileGallery, 325, 6, 4, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']=="12"){
	$widthAndHeight='style="width:360px; "';
	$model_info_width="width:348px; border:0px solid #000;"; 
	$col=1;
	$perPage=1;
	$fileFormat="_4perpage";
	$additionalCss="#1, #2, #3, #4, #5, #6, #7, #8, #9, #10, #11, #12 {margin-left:158px;}";
	$paperDef="10x16"; //pdf size
	$w = 360;
	$photoSize = array(500,580);
	$table = get_profile_images($ProfileID, $ProfileGender, $ProfileContactDisplay, $printType, $printOption, 1, $pdfImageID, $ProfileGallery, 500, 2, 1, $photoSize, $rb_agency_option_agencylogo);
}

elseif($_POST['print_option']==14){
	$widthAndHeight='style="width:91px; "';
	$model_info_width="width:154px;"; 
	$col=5;
	$perPage=10;
	$w = 91;
	$fileFormat="_division";

	$additionalCss='#profile-results #profile-list .profile-list-layout0 {width: 150px; float: left; margin:  0 10px 10px 10px; position: relative; z-index: 8;overflow: hidden; height:270px; }
	#profile-results #profile-list .profile-list-layout0 .style {position: absolute; }
	#profile-results #profile-list .profile-list-layout0 .image {width: 148px; height: 228px; overflow: hidden; background: #fff; border: 1px solid #ccc; }
	#profile-results #profile-list .profile-list-layout0 .image img {max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken {text-align: center; position: relative; background: #dadada !important; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a { margin-top: 90px;}
	#profile-results #profile-list .profile-list-layout0 .title {top: 230px; left: 0; width: 150px; position: absolute; text-align: center; }
	#profile-results #profile-list .profile-list-layout0 .title .name {width: 150px; height: 30px; line-height: 30px; text-transform:uppercase; }
	#profile-results #profile-list .profile-list-layout0 .title .name a {color:#000; text-transform:uppercase; font-weight:normal;}
	#profile-results #profile-list .profile-list-layout0 .title .favorite {margin: 9px 0 0 0; font-size: 13px; background: #ccc; padding: 5px 0; }
	#profile-results #profile-list .profile-list-layout0 .details {font-size: 13px; }
	#profile-results #profile-list .profile-list-layout0 .title .favorite a {color: #000; }
	#profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box {padding: 7px; background: #fff; border: 1px solid #aaa; position: absolute; bottom: 3px; left: 32px; }
	#profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box:hover {border: 1px solid #666; }
	#profile-results #profile-list .profile-list-layout0 .image img {max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken {margin: 10px 10px 0 10px; text-align: center; width: 180px; height: 220px; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a {margin-top: 90px;float: left; margin-left: 55px; }
	/* Profile Standard Layout */
	#profile-results #profile-list .profile-list-layout1 {border: 1px solid #bbacc7; width: 200px; float: left; margin: 0 20px 20px 0; position: relative; z-index: 8;height: 235px; overflow: hidden; padding: 3px; background:#c8bdd2; }
	#profile-results #profile-list .profile-list-layout1 .image {width: 198px; height: 200px; overflow: hidden; margin: 0px auto; }
	#profile-results #profile-list .profile-list-layout1 .image img {max-width: 185px; }
	#profile-results #profile-list .profile-list-layout1 .image-broken { }
	#profile-results #profile-list .profile-list-layout1 .image-broken a {display: none; }
	#profile-results #profile-list .profile-list-layout1 .title {}
	#profile-results #profile-list .profile-list-layout1 .title .name {font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .title .name a {color: #000; text-decoration: none; width: 190px; position: absolute; left: -3px; top: 213px; height: 30px; z-index: 10; text-align: center; font-size: 14px; }
	#profile-results #profile-list .profile-list-layout1 .style {background-color:#4D2375; width: 200px; position: absolute; top: 208px; height: 30px; z-index: 7;}
	#profile-results #profile-list .profile-list-layout1 .favorite {background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .favorite:hover  {background: #bbb; }
	#profile-results #profile-list .profile-list-layout1 .details {overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout1 .details div {float: left; }
	#profile-results #profile-list .profile-list-layout1 .details .details-age {width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout1 .details .details-state {width: 50%; text-align: left;}
	/* Profile Standard Alternate */
	#profile-results #profile-list .profile-list-layout2 {width: 150px; float: left; margin: 0 20px 20px 0; }
	#profile-results #profile-list .profile-list-layout2 .image {width: 150px; height: 180px; overflow: hidden; }
	#profile-results #profile-list .profile-list-layout2 .image img {max-width: 150px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken {margin: 10px 10px 0 10px; text-align: center; width: 150px; height: 220px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken a {margin-top: 90px;float: left; margin-left: 45px; }
	#profile-results #profile-list .profile-list-layout2 .title {color: #555; font-size: 13px; padding: 5px 0 0 0; background: #ddd; text-align: center; }
	#profile-results #profile-list .profile-list-layout2 .title .name {font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .title .name a {color: #555; }
	#profile-results #profile-list .profile-list-layout2 .favorite {background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .favorite:hover  {background: #bbb; }
	#profile-results #profile-list .profile-list-layout2 .details {overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout2 .details div {float: left; }
	#profile-results #profile-list .profile-list-layout2 .details .details-age {width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout2 .details .details-state {width: 50%; text-align: left;}
	.pagination{display: none;}';

} else {
	$widthAndHeight='style="width:400px"';
	$wrapperWidth="887px";
	$wrapperWidthHeight="887px";
	$toLandScape='@page{size: landscape;margin: 2cm;}';
	$w = 400;
}

$header.='
	<style>
	ul{list-style:none; }
	#Experience{display:none;}
	'.$toLandScape.'
	body{color:#000;}
	h1{color: #000; margin-bottom:15px; margin-top:15px;}

	tr td span{float:right; text-align:left; width:200px;}
	table {'.$ul_css.'}
	tr td{list-style:none; padding-bottom:1px; padding-top:1px;}
	#print_logo{margin-bottom:25px; width:100%; float:left;}
	#model_info{border:0px solid #000; float:left;'.$model_info_width.'}
	#print_wrapper img.allimages_thumbs{margin-right:5px;}
	ul{list-style:none; }
	'.$additionalCss.'
	</style>

	</head>
	<body style="background: #fff;">
	<div id="print_wrapper" style=" '.$wrapperWidthHeight.'border:0px solid #000;">';

if($_POST['print_option']==14){ // print for division

	$table1=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5"]');
	$table1=strip_tags($table1,'<img><script>');
	$table1=str_replace("&#187;","-->' src",$table1);
	$table1=str_replace('<script type="text/javascript">',"<!--' src",$table1);
	$table1=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table1);

	$table2.=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="2"]');
	$table2=strip_tags($table2,'<img><script>');
	$table2=str_replace("&#187;","-->' src",$table2);
	$table2=str_replace('<script type="text/javascript">',"<!--' src",$table2);
	$table2=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table2);

	$table3.=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="3"]');
	$table3=strip_tags($table3,'<img><script>');
	$table3=str_replace("&#187;","-->' src",$table3);
	$table3=str_replace('<script type="text/javascript">',"<!--' src",$table3);
	$table3=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table3);

	$table4=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="4"]');


} else { //this will loop images of simple profile

	
	
	
} //end else of if($_POST['print_option']


function get_profile_info($profileID, $profileGender, $profileName){
	$output = "";	
	$profileInfo = rb_agency_getProfileCustomFieldsArray($profileID, $profileGender);
	$output .= "<table style=\"width:100%;\">";
	$output .= "	<tr><td style=\"font-size: xx-large;padding-bottom:15px;\">".$profileName."</td></tr>";
	foreach ($profileInfo as $info) {
		$output .= "<tr><td>".$info['title'].": ".$info['value']."</td></tr>";
	}
	$output .= "</table>";
	return $output;
}

function get_profile_images($profile_id, $profile_gender, $profile_name, $print_type, $print_option, $has_info, $pdf_image_id, $profile_gallery, $column_width, $photos_per_page, $photos_beside_info, $photo_size, $agency_logo) {

	global $wpdb;	

	if($print_type == "print-polaroids"){
		$print_type = "Polaroid";
		$media_folder = "polaroid/";
	} else {
		$print_type = "Image";
		$media_folder = "";
	}

	if(isset($pdf_image_id) && count($pdf_image_id)>0) {		
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." as media WHERE ProfileID =  \"". $profile_id ."\" AND ProfileMediaType = \"".$print_type."\" AND ProfileMediaID IN ($pdf_image_id) ORDER BY  convert(`ProfileMediaOrder`, decimal)  ASC";
	} elseif(!empty($orderBy)) {
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." as media WHERE ProfileID =  \"". $profile_id ."\" AND ProfileMediaType = \"".$print_type."\" ORDER BY  convert(`ProfileMediaOrder`, decimal)  ASC";
	} else {
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." as media WHERE ProfileID =  \"". $profile_id ."\" AND ProfileMediaType = \"".$print_type."\" ORDER BY  convert(`ProfileMediaOrder`, decimal)  ASC";
	}

	$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
	$countImg = count($resultsImg);
	$totalCount = 0;
	$profileImages = "";

	$ctr_first_page = 1;
	$ctr_pages = 1;
	$ctr_loop = 1;

	foreach($resultsImg as $dataImg){

		$image_path = RBAGENCY_UPLOADDIR . $profile_gallery ."/".$media_folder."". $dataImg['ProfileMediaURL'];

		$bfi_params = array(
			'crop'=>true,			
			'width'=>$photo_size[0],
			'height'=>$photo_size[1]
		);
		$image_src = bfi_thumb( $image_path, $bfi_params );
		
		$_imgpath_upload = '';
		//be sure to add the full url
		if(strpos($image_src, get_bloginfo('url'))=== false){
			$_imgpath_upload = get_bloginfo('url');
		}
		$imgURL = $_imgpath_upload. $image_src;

		if(($ctr_first_page <= $photos_beside_info) && ($has_info === 1)) { // First Page
			if($ctr_first_page == 1) {
			$profileImages .= "	<table style=\"width: 100%;\">";
			$profileImages .= "		<tr>
										<td style=\"width:".$column_width."px;vertical-align:top;padding-right:10px;\">".get_profile_info($profile_id, $profile_gender, $profile_name)."</td>";
			$profileImages .= "			<td>";

			
				$profileImages .= "			<table style=\"width: 100%;\">";
				$profileImages .= "				<tr>";
				$profileImages .= "					<td style=\"vertical-align:top;\">";
			}
			// Photos beside info
			$profileImages .= "							<img id=\"".$dataImg["ProfileMediaID"]."\" src=\"".$imgURL."\" alt=\"a\" style=\"margin: 10px 5px 0 5px;\" />";

			if($ctr_first_page == $photos_beside_info) { // close table
					$profileImages .= "					</td>";
					$profileImages .= "				</tr>";
					$profileImages .= "			</table>";
				$profileImages .= "			</td>";
				$profileImages .= "		</tr>";
				$profileImages .= "		<tr><td colspan=\"2\" style=\"text-align:right;vertical-align:top;\"><img src=\"".get_site_url()."".$agency_logo."\" alt=\"\" style=\"height:64px; margin-right:10px; margin-top:10px;\" /></td></tr>";
				$profileImages .= "	</table>";
			}
			
			$ctr_first_page++;
		} else { // Succeeding Pages

			if(($ctr_pages % $photos_per_page == 1) || $photos_per_page == 1){ // open row
				if($has_info === 1) { // Break to New page if it has info
					$profileImages .= "<table style=\"width:100%;page-break-before:always;\"><tr><td style=\"vertical-align:top;\">";
				} else {
					$profileImages .= "<table style=\"width:100%;\"><tr><td style=\"vertical-align:top;\">";
				}	 			
		 	}
			$profileImages .= "<img id=\"".$dataImg["ProfileMediaID"]."\" src=\"".$imgURL."\" alt=\"a\" style=\"margin: 10px 5px 0 5px;\"/>\n";
			if($ctr_pages % $photos_per_page == 0 || $countImg == $ctr_loop) { // close row
				$profileImages .= "</td></tr>";
				// Add Logo
				$profileImages .= "<tr><td style=\"font-size: xx-large; width:50%;\">".$profile_name."</td><td style=\"width:50%;text-align:right;vertical-align:top;\"><img src=\"".get_site_url()."".$agency_logo."\" alt=\"\" style=\"margin-top: 15px;height:64px; margin-right:10px; margin-bottom: 10px;\" /></td></tr>";
				$profileImages .= "</table>";
			}

			$ctr_pages++;
		}
		$ctr_loop++;
	}
	
	return $profileImages;
}



$footer.='
</div>
</body>
</html>';


$border='
<div style="width:770px; height:760px; border:1px solid #000;">
</div>';

echo $profileImages;
$htmlFile=rand(1,10000).time().date("ymd").".html"; 
//$pdfFile=str_replace(".html",".pdf",$htmlFile);


//PDF FILE NAMING
if($_POST['print_option']=="1"){$format="-Photos-Large-with-info-";}
elseif($_POST['print_option']=="3"){$format="-Photos-Medium-with-info-";}
elseif($_POST['print_option']=="1-1"){$format="-Photos-Large-no-Info-";}
elseif($_POST['print_option']=="3-1"){$format="-Photos-Medium-no-Info-";}
elseif($_POST['print_option']=="11"){$format="-Polaroids-4perpage-with-Info-";}
elseif($_POST['print_option']=="12"){$format="-Polaroids-1perpage-with-Info-";}
else {$format="";}

//*$blog_title = strtolower(str_replace(" ","_",get_bloginfo('name')));
//*$pdfFile="$blog_title-".str_replace(" ","-",$ProfileContactDisplay).$format.date("ymd").".pdf";

$pdfFile=strtolower(str_replace(" ","-",$ProfileContactDisplay)).".pdf";

$p = str_replace("theme","ext",plugin_dir_path(__FILE__));

$path=$p."dompdf/htmls/";

//*include("/wp-content/plugins/rb-agency/dompdf/htmls/test.txt");

$fp=fopen($path.$htmlFile,"w");
/* 
$_server_path = $_SERVER['DOCUMENT_ROOT'];
preg_match_all('/src="([^"]*)"/i',$table, $result); 

foreach($result[1] as $_img){
	//preg_match_all('/(alt|title|src)=("[^"]*")/i',$img_tag, $img[$img_tag]);
	//preg_match_all('/(src)=("[^"]*")/i',$img_tag, $img[$img_tag]);
	echo realpath($_img).'<br/>';
} */

//img works if we use the server path - not the URL... but its depend on the server.. :)
$_server_path = dirname(dirname(dirname(dirname(__DIR__))));
$table = str_replace( get_site_url(),$_server_path,$table);

//fwrite($fp,$header);
//*fwrite($fp,$border);
if($_POST["print_option"] == 1 || $_POST['print_option'] == 3){
	//fwrite($fp,$modelInfo );
}
//fwrite($fp,$allImages);
fwrite($fp,$table);
fwrite($fp,$footer);
fclose($fp);
$toRedirect=RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&paper=A4&orientation=landscape&output_filed=".$pdfFile."&input_file=".$htmlFile;
//*die($toRedirect);

//echo $toRedirect;
wp_redirect($toRedirect); exit();
