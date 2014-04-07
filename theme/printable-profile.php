<?php 
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_agencylogo'];
global $wpdb;

function is_chrome() {
	return strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php bloginfo('name'); ?> | Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	<link rel="stylesheet" type="text/css" media="screen, print" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" />
	<script language="Javascript1.2">
	<!--
	function printpage() {
	window.print();
	}
	//-->
	</script>

<?php //where we decide what print format will it be.
	$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;  //detect if CHROME
	$showFooter = "";
	$logoMarginTop = "";
	$toLandScape = "";
	$imageLeft = "";
	$isLeft = "";
	$wrapperWidthHeight = "";
		
	if($_POST['print_option']==1){
		if($chrome){
			$widthAndHeight='style="width:420px; height:550px;"';
			//$wrapperWidthHeight="width:887px;"; // $wrapperWidth="1774px";
			$isLeft="float:left;";  //put this for page on chrome preview
			$model_info_width="width:310px;";
			//die("chrome");
		}else{
			$widthAndHeight='style="width:450px; height:580px"';
			$model_info_width="width:410px;";
			$logoMarginTop=" margin-top:30px;";
			//die("not chrome");
		}
		$ul_css="width:70%;";
		$toLandScape='@page{size: landscape;margin: 2cm;}';
		$showFooter=2; //at what loop should the footer be displayed
	} elseif($_POST['print_option']==2){
		$widthAndHeight='style="width:230px;  height:330px;"';
		$model_info_width="width:230px; height:600px;";
		$ul_css="width:100%;";
	} elseif($_POST['print_option']==3){
		if($chrome){
			$widthAndHeight='style="width:202px; height:270px;"';
			$ul_css="width:100%;";
			$model_info_width="width:202px; height:270px;";
		}else{
			$widthAndHeight='style="width:202px; height:320px;"';
			$ul_css="width:100%;";
			$model_info_width="width:202px; height:320px;";
		}
		$toLandScape='@page{size: landscape;margin: 2cm;}';
		$showFooter=8; //at what loop should the footer be displayed
	} elseif($_POST['print_option']==4){
		$widthAndHeight='style="width:500px"';
		$ul_css="width:100%;";
		$model_info_width="width:202px; height:320px;";
	} elseif($_POST['print_option']==5){
		$widthAndHeight='style="width:100px"';
		$wrapperWidthHeight="width:887px; height:600px;";   
		$toLandScape='@page{size: landscape;margin: 2cm;}';
	} elseif($_POST['print_option']==11){
		$widthAndHeight='style="width:232px; height:340px;margin-bottom:5px;"';
		$ul_css="width:100%;";
		$model_info_width="width:170px; height:520px;";
	} elseif($_POST['print_option']==12){

		$ul_css="width:100%;";
		if(is_chrome()){
			$model_info_width="width:180px; height:520px;";
			$widthAndHeight='style="width:490px; height:620px;margin-bottom:5px;"';
		}else{
			$model_info_width="width:180px; height:520px;";
			$widthAndHeight='style="width:520px; height:620px;margin-bottom:5px;"';
		}
		$imageLeft=".lefty{margin-left:180px;}";
	} else {
		$widthAndHeight='style="width:400px"';
		$wrapperWidth="887px";
		$wrapperWidthHeight="887px";
		$toLandScape='@page{size: landscape;margin: 2cm;}';
	}
?>
<style>
<?php echo $toLandScape;?> 
<?php echo $imageLeft;?>
body{color:#000;}
h1{color: #000; margin-bottom:15px; margin-top:15px;}

ul li span{ float:right; text-align:left; width:100px;}
ul {<?php echo $ul_css;?>}
ul li{ list-style:none; padding-bottom:5px; padding-top:5px;}
#print_logo{margin-bottom:25px; width:100%; float:left;}
#model_info{border:0px solid #000; float:left; <?php echo $model_info_width;?>}
#print_wrapper img.allimages_thumbs{margin-right:5px; <?php echo $isLeft; ?> }
</style>

</head>
<body onload="printpage()" style="background: #fff;">
<div id="print_wrapper" style=" <?php echo $wrapperWidthHeight;?>  border:0px solid #000;">

<?php /*
<div id="print_logo" style="float: left; width: 50%;">
<?php if(!empty($rb_agency_option_agencylogo)){ ?>
  <img src="<?php echo $rb_agency_option_agencylogo; ?>" title="<?php echo $rb_agency_option_agencyname; ?>" />
<?php }else{ ?>
<?php echo $rb_agency_option_agencyname; ?>
<?php } ?>
</div>
 <br clear="all" />  */?>
<?php  if($_POST['print_option']!="3-1" AND $_POST['print_option']!="1-1"){
		$rowCount=1;
?>
		<div id="model_info">
			<h1><?php echo $ProfileContactDisplay; ?></h1>
		   <ul>
			<?php  rb_agency_getProfileCustomFieldsCustom($ProfileID, $ProfileGender);?>
			</ul>
		</div>

<?php }

	if($_POST['print_type']=="print-polaroids") {
		$printType="Polaroid";}
	else{
		$printType="Image";
	}
    $pdf_image_id = "";
	if(isset($_POST['pdf_image_id']) && count($_POST['pdf_image_id'])>0) {
		$pdf_image_id = $_POST['pdf_image_id'];
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"".$printType."\" AND ProfileMediaID IN ($pdf_image_id) ORDER BY $orderBy";
	} else {
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"".$printType."\" ORDER BY $orderBy";
	}
	$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
	$countImg = count($resultsImg);
	$imageCnt = 0;
	$rowCount = 0;
	foreach($resultsImg as $dataImg){$imageCnt++; $rowCount++;
		if($imageCnt>1){
			$left='class="lefty"';
		} else{
			$left='';
		}

		$timthumbHW=str_replace('style="width:',"&w=",$widthAndHeight);
		$timthumbHW=str_replace('px; height:',"&h=",$timthumbHW);
		$timthumbHW=str_replace('px;"',"",$timthumbHW);

		echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";
        if($rowCount == $showFooter){
			$rowCount=0; //reset to loop another row 
			echo '<br clear="all"><img style="width:347px; '.$logoMarginTop.'" src="'.get_bloginfo("url").'/wp-content/plugins/rb-agency/view/layout/06/images/address.jpg"><br clear="all">';
		}
	}

	if($rowCount!=$showFooter AND $rowCount!="0"){
		echo '<br clear="all"><img style="width:347px;" src="'.get_bloginfo("url").'/wp-content/plugins/rb-agency/view/layout/06/images/address.jpg"><br clear="all">';
	}
?>
</div>
</body>
</html>
