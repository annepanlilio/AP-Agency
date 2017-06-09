<?php 
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";
global $wpdb;

function is_chrome() {
	return strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
}
show_admin_bar( false ); // hide wp admin bar
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- /theme/printable-profile.php -->
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
	$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;//detect if CHROME
	$showFooter = "";
	$logoMarginTop = "";
	$toLandScape = "";
	$imageLeft = "";
	$isLeft = "";
	$wrapperWidthHeight = "";


	$print_format = $_POST['print_option']; // get print format

	// classes for print format styling
	if($print_format == "1"){ // Print Large Photos
		$print_format_class = "lg-photos-info";
		$first_photos = 1;
		$images_per_row = 2;
	}
	if($print_format == "3") { // Print Medium Size Photos
		$print_format_class = "md-photos-info";
		$first_photos = 7;
		$images_per_row = 8;
	}
	if ($print_format == "1-1" ) { // Print Large Photos Without Model Info
		$print_format_class = "lg-photos";
		$images_per_row = 2;
	}
	if ($print_format == "3-1" ) { // Print Medium Size Photos Without Model Info
		$print_format_class = "md-photos";
		$images_per_row = 8;
	}
	if ($print_format == "11" ) { // Print Medium Size Photos Without Model Info
		$print_format_class = "polariod-1-4";
		$widthAndHeight='style="width:300px; height:280px;"';
		$images_per_row = 4;
		$model_info_width="width:32%;";
	}
	if ($print_format == "12" ) { // Print Medium Size Photos Without Model Info
		$print_format_class = "polariod-1-2";
		$widthAndHeight='style="width:300px; height:300px;"';
		$images_per_row = 2;
		$toLandScape='@page{size: landscape; margin: 1cm}';
	}


	if($_POST['print_option']==1){ // Print Large Photos
		if($chrome){
			$widthAndHeight='style="width:455px; height:570px;"';
			//$wrapperWidthHeight="width:887px;"; // $wrapperWidth="1774px";
			$isLeft="float:left;";//put this for page on chrome preview
			$model_info_width="width:310px;";
			//die("chrome");
		} else {
			$widthAndHeight='style="width:455px; height:580px"';
			$model_info_width="width:415px;";
			$logoMarginTop=" margin-top:30px;";
			//die("not chrome");
		}

		$ul_css="width:70%;";
		$toLandScape='@page{size: landscape;margin: 2cm;}';
		$showFooter=2; //at what loop should the footer be displayed
	} elseif($_POST['print_option']==2){
		$widthAndHeight='style="width:230px;height:330px;"';
		$model_info_width="width:230px; height:600px;";
		$ul_css="width:100%;";
	} elseif($_POST['print_option']==3){
		if($chrome){
			$widthAndHeight='style="width:215px; height:270px;"';
			$ul_css="width:100%;";
			// $model_info_width="width:202px; height:270px;";
		} else {
			$widthAndHeight='style="width:220px; height:320px;"';
			$ul_css="width:100%;";
			// $model_info_width="width:202px; height:320px;";
		}
		$toLandScape = '@page{size: landscape;margin: 2cm 1cm;}';
		$showFooter = 6; //at what loop should the footer be displayed
	} elseif($_POST['print_option']==4){
		$widthAndHeight='style="width:500px"';
		$ul_css="width:100%;";
		$model_info_width="width:202px; height:320px;";
	} elseif($_POST['print_option']==5){
		$widthAndHeight='style="width:100px"';
		$wrapperWidthHeight="width:887px; height:600px;"; 
		$toLandScape='@page{size: landscape;margin: 2cm;}';
	} elseif($_POST['print_option']==11){
		$ul_css="width:100%;";
		$toLandScape='@page{size: landscape;margin: 1.5cm;}';
	} elseif($_POST['print_option']==12){

		$ul_css="width:100%;";
		if(is_chrome()){
			$widthAndHeight='style="width:450px; height:600px;margin-bottom:5px;"';
		} else {
			$widthAndHeight='style="width:550px; height:600px;margin-bottom:5px;"';
		}
		$imageLeft=".lefty{margin-left:180px;}";
		$model_info_width="width:50%;";

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
body:before{display: none!important; }
h1{color: #000; margin-bottom:15px; margin-top:15px;}

ul {margin: 0; padding: 0; <?php echo $ul_css;?>}
ul li{list-style:none;}
#print_logo{margin-bottom:25px; width:100%; float:left;}
#model_info{border:0px solid #000; float:left; <?php echo $model_info_width;?>}
.allimages_thumbs{margin-left:10px;margin-bottom:10px; float: left; <?php echo $isLeft; ?> }
.agency-logo {max-height: 60px; float: right; }
.group {float: left; width: 920px; }
.group.first {width: 620px; }
.row {float: left; width: 100%; clear: both; }
.name {float: left; text-transform: uppercase; font-size: 26px; }

.lg-photos .group .allimages_thumbs:first-child {margin-left: 0; }
.lg-photos-info #model_info {height: auto; width: 450px; }
.lg-photos-info .group.first {width: 450px; }
.lg-photos-info .allimages_thumbs {width: 450px; }

.md-photos-info #model_info {height: auto; width: 215px; }
.md-photos-info #model_info h1 {margin-top: 0; }
.md-photos-info .group.first {width: 690px; }
.md-photos-info > img:nth-of-type(4) {margin-left: 0; }

.md-photos .group .allimages_thumbs:first-child,
.md-photos .group .allimages_thumbs:nth-child(5n+0),
.md-photos-info .group .allimages_thumbs:first-child,
.md-photos-info .group .allimages_thumbs:nth-child(5n+0) {margin-left: 0; }
</style>

</head>
<body onload="printpage()" style="background: #fff;">
<div id="print_wrapper" class="<?php echo $print_format_class; ?>" style=" <?php echo $wrapperWidthHeight;?>  border:0px solid #000;">

<?php /*
<div id="print_logo" style="float: left; width: 50%;">
<?php if(!empty($rb_agency_option_agencylogo)){ ?>
  <img src="<?php echo $rb_agency_option_agencylogo; ?>" title="<?php echo $rb_agency_option_agencyname; ?>" />
<?php } else { ?>
<?php echo $rb_agency_option_agencyname; ?>
<?php }?>
</div>
 <br clear="all" />  */?>
<?php  if($_POST['print_option']!="3-1" AND $_POST['print_option']!="1-1"){
		$rowCount=1;
?>
		<div id="model_info">
			<h1><?php echo $ProfileContactDisplay; ?></h1>
				<ul id="info">
				<?php rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=false);?>
				<script type="text/javascript">
 
				function count(){
					var ul = document.getElementById('info');
					var i=0, c =0;
					while(ul.getElementsByTagName('li')[i++]) c++;
					return(c);
				}

				</script>
			</ul>
		</div>

<?php }

	if($_POST['print_type']=="print-polaroids") {
		$printType="Polaroid";}
	else {
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
	$imageCnt = 1;
	$rowCount = 0;

	foreach($resultsImg as $dataImg){

		// if($imageCnt>1){
		// 	$left='class="lefty"';
		// } else {
		// 	$left='';
		// }

		$timthumbHW=str_replace('style="width:',"&w=",$widthAndHeight);
		$timthumbHW=str_replace('px; height:',"&h=",$timthumbHW);
		$timthumbHW=str_replace('px;"',"",$timthumbHW);

		if($print_format == "1") { // Print Large Photos with info Layout

    		if($rowCount % $images_per_row == $first_photos || $rowCount == 0) { // add row clear, add agency logo, close .group
				echo '<div class="group'.($rowCount == 0 ? ' first' : '').'">';
			}

			echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";

    		if($rowCount % $images_per_row == 0 || $rowCount+$first_photos == $countImg) { // add row clear, add agency logo, close .group
				echo '<div class="row"></div><img class="agency-logo" '.$logoMarginTop.'" src="'.$rb_agency_option_agencylogo.'"></div>'; // add row clear, add agency logo, close .group
			}

			$rowCount++;

        } elseif ($print_format == "3"){ // Print Medium Photos with info Layout

			if($rowCount < $first_photos) { // Photos beside info

			// 	if($rowCount == 0){
			// 		echo '<div class="group first">';
			// 	}

				echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";

				if($rowCount == $first_photos-1){
					echo '<div class="row"></div><img class="agency-logo" '.$logoMarginTop.'" src="'.$rb_agency_option_agencylogo.'">'; // add row clear, add agency logo, close .group
					$imageCnt = 1;
				}

				$rowCount++;

			} else { // Succeding Photos

				$rowCount++;

				if(($imageCnt % $images_per_row == 1) || $images_per_row == 1) { // group photos in a div
					echo '<div class="group">';
				}

				echo "	<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";

				if($imageCnt % $images_per_row == 0 || $imageCnt+$first_photos == $countImg) { // add row clear, add agency logo, close .group
					echo '<div class="row"></div><img class="agency-logo" '.$logoMarginTop.'" src="'.$rb_agency_option_agencylogo.'"></div>'; // add row clear, add agency logo, close .group
				}

				$imageCnt++;
			}

		} elseif ($print_format == "1-1"){ // Print Large Photos with without info

			$rowCount++;

			if(($rowCount % $images_per_row == 1) || $images_per_row == 1) {
				echo '<div class="group">';
			}

			echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";

    		if($rowCount % $images_per_row == 0 || $rowCount == $countImg) { // add row clear, add agency logo, close .group
				echo '<div class="row"></div><h1 class="name">'.$ProfileContactDisplay.'</h1><img class="agency-logo" '.$logoMarginTop.'" src="'.$rb_agency_option_agencylogo.'"></div>'; // add row clear, add agency logo, close .group
			}

		} elseif ($print_format == "3-1") { // Print Medium Photos with without info

			$rowCount++;
			if(($rowCount % $images_per_row == 1) || $images_per_row == 1) {
				echo '<div class="group">';
			}

			echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";

    		if($rowCount % $images_per_row == 0 || $rowCount == $countImg) { // add row clear, add agency logo, close .group
				echo '<div class="row"></div><h1 class="name">'.$ProfileContactDisplay.'</h1><img class="agency-logo" '.$logoMarginTop.'" src="'.$rb_agency_option_agencylogo.'"></div>'; // add row clear, add agency logo, close .group
			}

		} elseif ($print_format == "11") { //  Four Polaroids Per Page

			if($rowCount < 4) { // First 4 photos beside info

				if($rowCount == 0){ // Open first page table
echo "				<table style=\"width:60%; float: left;\">";
				}
					if(($rowCount % 2 == 0) || 2 == 1) { // Add tr for every 2 photos
echo "					<tr>";
					}
echo "						<td style=\"width:50%;\">";
echo "							<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";
echo "						</td>";
					if($rowCount % 2 == 1 || 2 == 1) { // Close tr
echo "					</tr>";
					}
				if($rowCount == 3){ // Close first page table and add logo
echo "					<tr>";
echo "						<td colspan=\"2\" style=\"text-align:right;\"><img src=\"".$rb_agency_option_agencylogo."\"></td>";
echo "					</tr>";
echo '				</table>';
				}
				$rowCount++;

			} else { // Succeding Photos

				if(($imageCnt % $images_per_row == 1) || $images_per_row == 1) { // group photos in a div
					echo '<table style="width:100%; page-break-before:always;">';
				}

				if(($rowCount % 4 == 0) || 4 == 1) { // Add tr for every 2 photos
echo "				<tr>";
				}
echo "					<td style=\"width:50%;\"><img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' /></td>";
				if($rowCount % 4 == 1 || 4 == 1) { // Close tr
echo "				</tr>";
				}

				if($imageCnt % $images_per_row == 0 || $imageCnt+4 == $countImg) { // add row clear, add agency logo, close .group
					echo '<tr><td style="width:50%;"><h1 class="name">'.$ProfileContactDisplay.'</h1></td><td style="width:50%;text-align:right;"><img src="'.$rb_agency_option_agencylogo.'"></td></tr>';
					echo '<table>';
				}

				$imageCnt++;
				$rowCount++;
			}

		} else { // One Polaroid Per Page

			if($rowCount < 1) { // First 4 photos beside info

				if($rowCount == 0){ // Open first page table
echo "				<table style=\"width:50%; float: left;\">";
				}
					if(($rowCount % 1 == 0) || 1 == 1) { // Add tr for every 2 photos
echo "					<tr>";
					}
echo "						<td style=\"width:50%;\">";
echo "							<img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' />";
echo "						</td>";
					if($rowCount % 1 == 1 || 1 == 1) { // Close tr
echo "					</tr>";
					}
				if($rowCount == 1){ // Close first page table and add logo
echo "					<tr>";
echo "						<td colspan=\"2\" style=\"text-align:right;\"><img src=\"".$rb_agency_option_agencylogo."\"></td>";
echo "					</tr>";
echo '				</table>';
				}
				$rowCount++;

			} else { // Succeding Photos

				if(($imageCnt % $images_per_row == 1) || $images_per_row == 1) { // group photos in a div
					echo '<table style="width:100%; page-break-before:always;">';
				}

				if(($rowCount % 2 == 1) || 2 == 1) { // Add tr for every 2 photos
echo "				<tr>";
				}
echo "					<td style=\"width:50%;\" class=\"rowcount-".$rowCount."\"><img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .$timthumbHW."\" alt='' class='allimages_thumbs' /></td>";
				if($rowCount % 2 == 0 || 2 == 1) { // Close tr
echo "				</tr>";
				}

				if($imageCnt % $images_per_row == 0 || $imageCnt == $countImg) { // add row clear, add agency logo, close .group
					echo '<tr><td style="width:50%;"><h1 class="name">'.$ProfileContactDisplay.'</h1></td><td style="width:50%;text-align:right;"><img src="'.$rb_agency_option_agencylogo.'"></td></tr>';
					echo '<table>';
				}

				$imageCnt++;
				$rowCount++;
			}
		}

	}

	// if($rowCount!=$showFooter AND $rowCount!= "0"){
	// 	echo '<br clear="all"><img style="width:347px;" src="'.$rb_agency_option_agencylogo."\"><br clear=\"all\">";
	// }
?>
</div>
</body>
</html>
