<?php 
session_start();
header("Cache-control: private"); //IE 6 Fix

// Get User Information
	global $wpdb;
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$CurrentUser = $current_user->ID;

	// Set Values
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_privacy = $rb_agency_options_arr['rb_agency_option_privacy'];
	$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];
	$rb_agency_option_agencyemail = $rb_agency_options_arr['rb_agency_option_agencyemail'];
	$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
	
	$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_cardphoto_logo_url'];
	$rb_agency_option_agencycardphoto_layout = (int)$rb_agency_options_arr['rb_agency_option_cardphoto_layout'];
	
	
	// Get Profile
	$profileURL = get_query_var('target'); //$_REQUEST["profile"];
	

	$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='$profileURL'";
	$results = $wpdb->get_results($query,ARRAY_A) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	$count = count($results);
	foreach ($results as $data) {
		$ProfileID					=$data['ProfileID'];
		$ProfileUserLinked			=$data['ProfileUserLinked'];
		$ProfileGallery				=stripslashes($data['ProfileGallery']);
		$ProfileContactDisplay		=stripslashes($data['ProfileContactDisplay']);
		$ProfileContactNameFirst	=stripslashes($data['ProfileContactNameFirst']);
		$ProfileContactNameLast		=stripslashes($data['ProfileContactNameLast']);
			if ($rb_agency_option_profilenaming == 0) {
				$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
			} elseif ($rb_agency_option_profilenaming == 1) {
				$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
			} elseif ($rb_agency_option_profilenaming == 3) {
				$ProfileContactDisplay = "ID ". $ProfileID;
			} elseif ($rb_agency_option_profilenaming == 4) {
				$ProfileContactDisplay = $ProfileContactNameFirst;
			} elseif ($rb_agency_option_profilenaming == 5) {
				$ProfileContactDisplay = $ProfileContactNameLast;
			}
		$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
		$ProfileType				=$data['ProfileType'];
		$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
		$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
		$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
		$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
		$ProfileGender    			=stripslashes($data['ProfileGender']);
		$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
		$ProfileAge 				= rb_agency_get_age($ProfileDateBirth);
		$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
		$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
		$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
		$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
		$ProfileStatEthnicity		=stripslashes($data['ProfileStatEthnicity']);
		$ProfileStatSkinColor		=stripslashes($data['ProfileStatSkinColor']);
		$ProfileStatEyeColor		=stripslashes($data['ProfileStatEyeColor']);
		$ProfileStatHairColor		=stripslashes($data['ProfileStatHairColor']);
		$ProfileStatHeight			=stripslashes($data['ProfileStatHeight']);
		$ProfileStatWeight			=stripslashes($data['ProfileStatWeight']);
		$ProfileStatBust	        =stripslashes($data['ProfileStatBust']);
		$ProfileStatWaist	    	=stripslashes($data['ProfileStatWaist']);
		$ProfileStatHip	        	=stripslashes($data['ProfileStatHip']);
		$ProfileStatShoe		    =stripslashes($data['ProfileStatShoe']);
		$ProfileStatDress			=stripslashes($data['ProfileStatDress']);
		$ProfileUnion				=stripslashes($data['ProfileUnion']);
		$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
		$ProfileIsActive			=stripslashes($data['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
		$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
		$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
	}

	
	//return array(title,value)
	$_allFields = rb_agency_getProfileCustomFieldsArray($ProfileID, $ProfileGender,"strong", "span", true);
	$_display_array = array(
		'height-inch',
		'dress-size',
		'bust-inch',
		'waist-inch',
		'hips-inch',
		'shoe-size-uk',
		'hair-colour',
		'eye-colour',
	);
	$_infoText = array();
	foreach($_display_array as $key){
		if(array_key_exists($key,$_allFields)){
			$_infoText[]= $_allFields[$key]['title'] .': '. $_allFields[$key]['value'].'';
		}
	}
	$_modelInfo = implode(' | ',$_infoText);
	//Height 5' 10" | Dress 8 | Bust 34A | Waist 24 | Hips 35 | Shoes 6 | Hair Blond | Eyes Blue/Green
	
	
	$_media =array();
	//Card Photos
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CardPhotos");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			$_media[] = $ProfileGallery ."/". $dataMedia['ProfileMediaURL'];
		}
	}
	
	//control the layout of image
	
	if($rb_agency_option_agencycardphoto_layout == 0){
		if(count($_media) <=4){
			$_photoSize = array('width'=>200, 'height'=>330);
		}elseif(count($_media) == 5){
			$_photoSize_primary = array('width'=>285, 'height'=>365);
			$_photoSize = array('width'=>141, 'height'=>175);
		}elseif(count($_media) > 5){
			$_photoSize = array('width'=>200, 'height'=>330);
		}
	}else{
		if(count($_media) <=4){
			$_photoSize = array('width'=>225, 'height'=> 283);
		}elseif(count($_media) == 5){
			$_photoSize_primary = array('width'=>471, 'height'=>587);
			$_photoSize = array('width'=>225, 'height'=> 283);
		}elseif(count($_media) > 5){
			$_photoSize = array('width'=>225, 'height'=> 283);
		}
	}
	
	
	//ready the photo html
	$_cardPhotos ='';$x=0;
	foreach($_media as $key){
		if(!defined('RBAGENCY_UPLOADDIR')){
			$image_path =  rb_agency_UPLOADDIR . $key;
		}else{
			$image_path =  RBAGENCY_UPLOADDIR . $key;
		}
		
		$x++;
		if(is_array($_photoSize_primary) and $x==1){
			$_photoHeight = $_photoSize_primary['height'];
			$_photoWidth = $_photoSize_primary['width'];
		}else{
			$_photoHeight = $_photoSize['height'];
			$_photoWidth = $_photoSize['width'];
		}
		$bfi_params = array(
			'crop'  => true,
			'width' => $_photoWidth,
			'height'=> $_photoHeight
		);
		
		
		if(function_exists('bfi_thumb')){
			$image_src = bfi_thumb( $image_path, $bfi_params );
		}else{
			if(!defined('RBAGENCY_PLUGIN_URL')){
				$image_src = rb_agency_BASEDIR."/ext/timthumb.php?src={$image_path}&w={$_photoWidth}&h={$_photoHeight}&zc=1";
			}else{
				$image_src = RBAGENCY_PLUGIN_URL."/ext/timthumb.php?src={$image_path}&w={$_photoWidth}&h={$_photoHeight}&zc=1";
			}
		}
		
		//validate the right path of image
		$size = getimagesize($image_src);
		if (!$size){
			$image_src = site_url() . $image_src;
		}else{
			$image_src = $image_src;
		}
		
		$_addClass = '';
		if(is_array($_photoSize_primary) and $x==1){
			
			$_cardPhotos.= "<table><tr><td class='primary-pic'><img src=\"". $image_src."\" class='model-pics' /></td>";
			$_cardPhotos.= "<td class='thumb-photo'>";
		}elseif($x== count($_media) and is_array($_photoSize_primary)){
			$_cardPhotos.= "<img src=\"". $image_src."\" class='model-pics ".$_addClass."' />";
			$_cardPhotos.= "</td></tr></table>";
		}elseif((($x -1) % 2) == 0 and is_array($_photoSize_primary)){
			$_cardPhotos.= "<img src=\"". $image_src."\" class='model-pics ".$_addClass."' /><br/>";
		}else{
			$_cardPhotos.= "<img src=\"". $image_src."\" class='model-pics ".$_addClass."' />";
		}

	}
		
	$pdf_image_id = "";
	$orderBy = "";
	$allImages = "";
	$cnt = 0;
	$cnt1  = 0;
	$cnt2  = 0;
	$footer = "";
	$paperDef = "";
	$pdf_image_id = "";
	$col=5;
	$perPage=10;
	$w = 91;
	$fileFormat="_division";
	$landspace_css = "
		@page{size: landscape;margin: 0px!important;}
		.primary-pic{text-align:left!important;}
		img.model-pics{padding: 0!important;border-width:0px !important;margin:0px !important;}
		.thumb-photo{padding: 0px!important}
		.thumb-photo img{padding-left: 10px!important;padding-bottom: 15px!important;margin:0px !important;}
		.thumb-photo img:nth-child(even){padding-left: 30px!important}
		html,body{margin:10px!important;margin-left: 15px!important;}
		.model-title{font-weight:800!important;font-size:26px!important;line-height:1.5em;
		padding:0!important; text-align:center;clear:both;display:block;margin: 10px auto;}
		#headerlogo{height: 100px!important;width:230px!important;}
		table tr td.logo{width:230px!important;overflow:hidden}
		
		";
	$toLandScape = $rb_agency_option_agencycardphoto_layout == 1 ? $landspace_css : '';

if(!empty($rb_agency_option_agencylogo)){
	$pdf_logo = $rb_agency_option_agencylogo;
	$logo_height = array(200,100);
}

$header='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Card Photos - '.$ProfileContactDisplay.'</title>
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
    
	<style>
	
	@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,800);
	
	*,html{font-family: "Open Sans", sans-serif;line-height:1.3em;}
	ul{list-style:none; }
	#Experience{display:none;}
	
	@page { margin: 50px 50px;}
	'. $toLandScape.'
	#headerlogo { display:block;border:0px solid #d00; text-align: center; }
	/* #headerlogo { border:0px solid #d00; position: fixed; left: 0px; top: -'. ((int)$logo_height[1] + 40) .'px; right: 0px; height: '. (int)$logo_height[1] .'px; text-align: center; } */

	body{color:#000;font-size:11px;text-align:center;margin:0;padding:0}
	
	h3{font-size:14px;font-weight:bold;border:0;margin:0;padding:0;}
	h4{}
	h3 a{}
	div{display:inline-block;}
	ul li,ul{list-style:none;}
	
	.page-title{font-size:11px; text-align:center;clear:both;display:none;}
	.model-title{font-size:16px;font-weight:bold;padding:10px; text-align:center;clear:both;display:block;margin: 10px auto;height:5px;}
	
	.model-info{display:block;padding: 10px 0;clear:both;text-align:center;font-size:12px;color:#aaa;}
	.model-info b{color:#888;}
	img.model-pics{padding: 3px;border:1px solid #ddd; margin: 3px;}
	.primary-pic{text-align:right;
		width:'.($_photoSize_primary['width']+20).'px;
		height:'.$_photoSize_primary['height'].'px;
		}
	.primary-pic img{padding: 3px;border:1px solid #ddd; margin: 3px; float: none;display:inline-block;}
	.thumb-photo{padding: 0;padding-left: 10px;border:0px solid #f00; margin: 0;}
	.thumb-photo img{float: left;display:inline-block;}
	
		table{width:100%;border:0;}
		table tr{border:0;border-collapse:collapse;padding:0;}
		table.center tr td{border:0;border-collapse:collapse;padding:0;text-align:center;vertical-align:top;}
		table.layout2{margin-top: 10px;}
		table.layout2 tr td{text-align:right;vertical-align:top;}
		table.layout2 tr td.modeltable-info{text-align:left!important;width:700px;}
		table.layout2 tr td.modeltable-info .model-info{}
		table.layout2 tr td.modeltable-info .model-info b{color:#888;}
	.clear{clear:both;display:block;height:10px;padding:10px;}
	</style>

</head>

<body style="background: #fff;">
	
	';

	
	ob_start();
		
	if($rb_agency_option_agencycardphoto_layout == 0){
		$_dataContent = $header
			.'<div class="page-title">Card Photos - '.$ProfileContactDisplay.'</div>'
			.'<div id="headerlogo"><img src="'.$pdf_logo.'"></div>'
			.'<div class="model-title">'.$ProfileContactDisplay.'</div>'
			.$_cardPhotos
			.'<table class="center"><tr><td>'
			.'<div class="model-info">'.$_modelInfo.'</div></td></tr></table>'
			.'</div></body></html>';
	}else{
		$_dataContent = $header
			.$_cardPhotos
			.'<table class="layout2"><tr>'
			.'<td class="modeltable-info"><div class="model-title">'.$ProfileContactDisplay.'</div>'
			.'<div class="model-info">'.$_modelInfo.'</div></td>'
			.'<td class="logo"><div id="headerlogo"><img src="'.$pdf_logo.'"></div></td>'
			.'</tr></table></div></body></html>';
	}	
		
	$htmlFile=rand(1,10000).time().date("ymd").".html"; 
	$_tempFilename = $ProfileContactNameFirst. '-'. substr($ProfileContactNameLast,0,1) .'-Model-Card-'.$rb_agency_option_agencyname;

	$pdfFile=str_replace(" ","-",$_tempFilename).".pdf";

	if(!defined('RBAGENCY_PLUGIN_DIR')){
		$path=rb_agency_BASEPATH."ext/dompdf/htmls/";
	}else{
		$path=RBAGENCY_PLUGIN_DIR."ext/dompdf/htmls/";
	}
		
	
	//*include("/wp-content/plugins/rb-agency/dompdf/htmls/test.txt");
	$fp=fopen($path.$htmlFile,"w");
		fwrite($fp,$_dataContent);
		fclose($fp);
		
	if (!file_exists($path.$htmlFile)) {
		echo 'the cache folder is not writable. pls change the chmod. <br><b>'.$path;
		exit;
	}
	//echo $path.$htmlFile;exit;
	if(!defined('RBAGENCY_PLUGIN_URL')){
		$toRedirect_base=rb_agency_BASEDIR;
	}else{
		$toRedirect_base=RBAGENCY_PLUGIN_URL;
	}
	
	$toRedirect = $toRedirect_base . "ext/dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
	if($rb_agency_option_agencycardphoto_layout == 1){
		$toRedirect .= '&orientation=landscape';//landscape;-o orientation	either 'portrait' or 'landscape'
	}
	//echo $toRedirect;
	wp_redirect($toRedirect); 

	exit;

	
	