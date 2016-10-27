<?php
/*
Title:  Alba Profile View
Author: RB Plugin
Text:   Fullwidth Scroller
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/19/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

/*
 * Insert Script
 */

	wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/19/js/jquery.mCustomScrollbar.concat.min.js' );
	wp_enqueue_script( 'photo-scroller' );

	wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/19/js/init-scroller.js' );
	wp_enqueue_script( 'init-scroller' );



/*
 * Layout 
 */


$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
$urlexploade = explode("/", $profileURLString);

if (isset($urlexploade[1])) {
	$subview = $urlexploade[1];
} else {
	$subview = "";
}

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

$profile_cf = rb_agency_getProfileCustomFieldsArray($ProfileID, $ProfileGender);

// print_r($profile_cf);

$stats = array( 'height', 'kids-clothing', 'bust', 'waist', 'suit', 'inseam', 'hips', 'dress', 'shoe', 'skin', 'hair', 'eyes' );
$stats_filtered = get_array_values($profile_cf, $stats);


$_profileType = get_profile_type($ProfileType);

// $queryProfileType = $wpdb->get_results($wpdb->prepare("SELECT ProfileType FROM ".table_agency_profile." WHERE ProfileID = %d",$ProfileID),ARRAY_A);
// $resultProfiletype = current($queryProfileType);
// $profileType = $resultProfiletype["ProfileType"];
// echo "<h1>".$profileType."</h1>";

// print_r($stats_filtered);

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-nineteen\" class=\"rblayout\">\n";

echo "			<div class=\"container\">";
echo "				<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";
echo "				<div id=\"profile-nav\">";
echo "					<ul id=\"links\" class=\"profile-tab-menu nav nav-tabs\">";
if(in_array("Actor", $_profileType)){
	echo "						<li class=\"nav-item link\"><a href=\"#photos\" title=\"\" id=\"nav-photos\" class=\"nav-link active\" data-toggle=\"tab\" role=\"tab\">Photos</a></li>\n";
} else {
	echo "						<li class=\"nav-item link\"><a href=\"#photos\" title=\"\" id=\"nav-photos\" class=\"nav-link active\" data-toggle=\"tab\" role=\"tab\">Portfolio</a></li>\n";
}


if(has_profile_media("Headshot", $ProfileID) || has_profile_media("Polaroid", $ProfileID) || has_profile_media("CompCard", $ProfileID) || has_profile_media("Resume", $ProfileID) || has_profile_media("VoiceDemo", $ProfileID)){
	echo "						<li class=\"nav-item link\"><a href=\"#digitals\" title=\"\" id=\"nav-digitals\" class=\"nav-link\" data-toggle=\"tab\" role=\"tab\">Digitals</a></li>\n";	
}

if(in_array("Actor", $_profileType)){
	if(has_profile_media("Demo Reel", $ProfileID)) {
echo "							<li class=\"nav-item link\"><a href=\"#reel\" title=\"\" id=\"nav-reel\" class=\"nav-link\" data-toggle=\"tab\" role=\"tab\">Reel</a></li>\n";
	}	
}
if(has_profile_media("Video Slate", $ProfileID) || has_profile_media("Video Monologue", $ProfileID)) {
echo "							<li class=\"nav-item link\"><a href=\"#videos\" title=\"\" id=\"nav-videos\" class=\"nav-link\" data-toggle=\"tab\" role=\"tab\">Videos</a></li>\n";
}
if(in_array("Actor", $_profileType)){
	if(has_profile_media("Resume", $ProfileID)){
	echo "						<li class=\"nav-item link\"><a href=\"#resume\" title=\"\" id=\"nav-resume\" class=\"nav-link\" data-toggle=\"tab\" role=\"tab\">Resume</a></li>\n";
	}
	if(has_profile_media("IMDB", $ProfileID)){
	echo "						<li class=\"nav-item link\"><a href=\"#imdb\" title=\"\" id=\"nav-imdb\" class=\"nav-link\" data-toggle=\"tab\" role=\"tab\">IMDB</a></li>\n";
	}
}

echo "					</ul>\n";
echo "					<ul class=\"profile-action\">";
echo "						<li><a href=\"#\" title=\"\" id=\"toggle-photos\" class=\"scroller-view\"><i class=\"fa fa-th\"></i></a><li>";
echo "					</ul>\n";
echo "				</div>\n";
echo "			</div>\n";


echo "  		<div>\n";

echo "				<div class=\"tab-content\">";
// Photos
echo "					<div id=\"photos\" class=\"tab-pane fade in active\" role=\"tabpanel\">";
// Grid View
echo "						<div id=\"grid-view\" class=\"grid-view container\">"; //								
								$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID, "Image");
								$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
								$countImg  = $wpdb->num_rows;
								foreach($resultsImg as $dataImg ){								
	echo "							<div class=\"photo\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=350&h=400&a=t\"  /></a></div>\n";
								}
echo "						</div><!-- #grid-view -->"; //
// Scroller View
echo "						<div id=\"photo-scroller\" class=\"scroller scroller-view\">";								
								$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
								$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
								$countImg  = $wpdb->num_rows;
								foreach($resultsImg as $dataImg ){
									if ($countImg > 1) {
										echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=480\"  /></a>\n";
									} else {
										echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=480\" /></a>\n";
									}
								}
echo "						</div><!-- #photo-scroller -->"; //
echo "					</div><!-- #photos -->"; //


// Digitals
echo "					<div id=\"digitals\" class=\"tab-pane fade\" role=\"tabpanel\">";
echo "						<div class=\"container-fluid\">";
// echo 							"<h3>Digitals</h3>";

								// $queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType <> \"Link\" AND ProfileMediaType <> \"Image\" AND ProfileMediaType <> \"Video Slate\"";
								// $resultsMedia =  $wpdb->get_results($wpdb->prepare($queryMedia, $ProfileID),ARRAY_A);
								// $countMedia = $wpdb->num_rows;

								// print_r($resultsMedia);

echo "							<div class=\"digitals\">";

								get_profile_media_by_type("Headshot", $ProfileID, $ProfileGallery);
// echo "							<div class=\"cb\"></div>";								

								get_profile_media_by_type("Polaroid", $ProfileID, $ProfileGallery);
// echo "							<div class=\"cb\"></div>";

								get_profile_media_by_type("CompCard", $ProfileID, $ProfileGallery);
echo "							<div class=\"cb\"></div>";

								if(!in_array("Actor", $_profileType)){
									get_profile_media_by_type("Resume", $ProfileID, $ProfileGallery, true);
// echo "								<div class=\"cb\"></div>";
								}

								get_profile_media_by_type("VoiceDemo", $ProfileID, $ProfileGallery, true);
echo "							<div class=\"cb\"></div>";
// 								foreach ($resultsMedia as $digital) {

// 									$digitalType = $digital['ProfileMediaType'];
// 									$digitalURL = $digital["ProfileMediaURL"];
// 									$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $digitalURL;

// 									$itemClass = array('digital');
// 									$itemClass[] = strtolower($digitalType);
// 									$itemClassString = implode(" ", $itemClass);

// echo "								<div class=\"".$itemClassString."\">";
// echo "								<div class=\"box\">";
// 									$image_params = array(
// 										// 'crop'=>true,
// 										'width'=>350,
// 										'height'=>400,
// 										// 'crop_width'=>'500',
// 										// 'crop_height'=>'500',
// 										// 'crop_only'=>true,
// 										// 'crop_y'=>'0'
// 									);
// 									$image_src = bfi_thumb( $image_path, $image_params );
// 									if ( $digitalType == "Headshot" || $digitalType == "CompCard" || $digitalType == "Polaroid" ) {
// echo "									<a href=\"".$image_path."\" title=\"\" target=\"_blank\"><img src=\"".$image_src."\" alt=\"\"/></a>";
// 									} else {
// 										if($digitalType == "Resume"){
// 											$iconClass = "file-audio-o";
// 										} elseif($digitalType == "VoiceDemo"){
// 											$iconClass = "file-text-o";
// 										} elseif($digitalType == "Video Slate"){
// 											$iconClass = "file-video-o";
// 										} else {
// 											$iconClass = "file-o";
// 										}
// echo "									<a href=\"".RBAGENCY_UPLOADDIR . $ProfileGallery ."/".$digitalURL."\" title=\"\" target=\"_blank\">";
// echo "										<img src=\"".plugins_url()."/rb-agency/view/layout/19/images/dummy.jpg\" /><div class=\"icon\"><i class=\"fa fa-".$iconClass."\"></i><br><span class=\"title\">$digitalType</span></div>";
// echo "									</a>";
// 									}
// echo "								</div>";
// echo "								</div>";
// 								}
echo "							</div>";
echo "						</div><!-- .container -->"; //
echo "					</div><!-- #digitals -->"; //
// Reel
echo "					<div id=\"reel\" class=\"tab-pane fade\" role=\"tabpanel\">";
echo "						<div class=\"container\">";
								$queryVideos = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
											AND (ProfileMediaType = 'Demo Reel')";
											$resultsVideo =  $wpdb->get_results($wpdb->prepare($queryVideos),ARRAY_A);
											$countMedia  = $wpdb->num_rows;

											// print_r($resultsVideo);

								if ($countMedia > 0) {
echo "								<div class=\"videos\">\n";
									//Videos
									foreach($resultsVideo as $dataMedia ){
										$media_url = $dataMedia['ProfileMediaURL'];
										$embed_string = substr($media_url, strpos($media_url, "=") + 1);
										echo "<div class=\"video slate\">";
										echo "<div class=\"videoWrapper\">";
										if($dataMedia['ProfileVideoType'] == "youtube" || $dataMedia['ProfileVideoType'] == ""){
											
											echo "<iframe style='width:720px; height:480px;'  src='//www.youtube.com/embed/".trim($embed_string)."?&autoplay=0&controls=1&rel=0&modestbranding=1&showinfo=0&autohide=1' frameborder='0' allowfullscreen></iframe>";											
											/* echo "<div class=\"item video slate ".$dataMedia['ProfileVideoType']." youtube-vid vid_co\" ytid='".trim($embed_string) ."' style=\"height:200px;width: 250px;background-color:000; background-image: url(http://img.youtube.com/vi/".trim($embed_string) ."/0.jpg); background-size:cover;background-repeat:no-repeat;\">";
											
											echo '<div class="btn-play" ><i class="fa fa-youtube-play"></i></div>'; */
										} elseif($dataMedia['ProfileVideoType'] == "vimeo") {
											
											$embed_string = (int) substr(parse_url($media_url, PHP_URL_PATH), 1);
											echo '<iframe style="width:720px; height:480px;" src="//player.vimeo.com/video/'.$embed_string.'?portrait=0&amp;badge=0&amp;showinfo=0&amp;controls=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';											
										}
										echo "</div>\n";
										echo "<p>".$dataMedia['ProfileMediaTitle']."</p>";
										echo "</div>\n";
									}
echo "								</div>\n";// #videos
								}								
echo "					</div><!-- .container -->";
echo "				</div><!-- .tab-content -->";
// Videos
echo "					<div id=\"videos\" class=\"tab-pane fade\" role=\"tabpanel\">";
echo "						<div class=\"container\">";
// echo "							<h3>Videos</h3>";

								if(in_array("Actor", $_profileType)){
									$queryVideos = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
											AND (ProfileMediaType = 'Video Slate' OR 
											ProfileMediaType = 'Video Monologue')";
								} else {
									$queryVideos = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
											AND (ProfileMediaType = 'Video Slate' OR 
											ProfileMediaType = 'Video Monologue' OR
											ProfileMediaType = 'Demo Reel')";								
								}

								$resultsVideo =  $wpdb->get_results($wpdb->prepare($queryVideos),ARRAY_A);
								$countMedia  = $wpdb->num_rows;	
								
											// print_r($resultsVideo);

								if ($countMedia > 0) {
echo "								<div class=\"videos\">\n";
									//Videos
									foreach($resultsVideo as $dataMedia ){
										$media_url = $dataMedia['ProfileMediaURL'];
										$embed_string = substr($media_url, strpos($media_url, "=") + 1);
										echo "<div class=\"video slate\">";
										echo "<div class=\"videoWrapper\">";
										if($dataMedia['ProfileVideoType'] == "youtube" || $dataMedia['ProfileVideoType'] == ""){
											
											echo "<iframe style='width:720px; height:480px;'  src='//www.youtube.com/embed/".trim($embed_string)."?&autoplay=0&controls=1&rel=0&modestbranding=1&showinfo=0&autohide=1' frameborder='0' allowfullscreen></iframe>";											
											/* echo "<div class=\"item video slate ".$dataMedia['ProfileVideoType']." youtube-vid vid_co\" ytid='".trim($embed_string) ."' style=\"height:200px;width: 250px;background-color:000; background-image: url(http://img.youtube.com/vi/".trim($embed_string) ."/0.jpg); background-size:cover;background-repeat:no-repeat;\">";
											
											echo '<div class="btn-play" ><i class="fa fa-youtube-play"></i></div>'; */
										} elseif($dataMedia['ProfileVideoType'] == "vimeo") {
											
											$embed_string = (int) substr(parse_url($media_url, PHP_URL_PATH), 1);
											echo '<iframe style="width:720px; height:480px;" src="//player.vimeo.com/video/'.$embed_string.'?portrait=0&amp;badge=0&amp;showinfo=0&amp;controls=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';											
										}
										echo "</div>\n";
										echo "<p>".$dataMedia['ProfileMediaTitle']."</p>";
										echo "</div>\n";
									}
echo "								</div>\n";// #videos
								}
echo "					</div><!-- .container -->";
echo "				</div><!-- .tab-content -->";
// Resume
echo "				<div id=\"resume\" class=\"tab-pane fade\" role=\"tabpanel\">";
echo "					<div class=\"container\">";
echo "						<div class=\"digitals\">";
								get_profile_media_by_type("Resume", $ProfileID, $ProfileGallery, true);
echo "							<div class=\"cb\"></div>";
echo "						</div><!-- .digitals -->";
echo "					</div><!-- .container -->";
echo "				</div><!-- .tab-content -->";
// IMDB
echo "					<div id=\"imdb\" class=\"tab-pane fade\" role=\"tabpanel\">";
echo "						<div class=\"container\">";
								$queryLinks = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType = \"Link\" AND isPrivate = 0";
								$resultsLinks =  $wpdb->get_results($wpdb->prepare($queryLinks, $ProfileID),ARRAY_A);
								$countLinks = $wpdb->num_rows;
								if ($countLinks > 0) {							
									echo "<ul>\n";
									foreach ($resultsLinks  as $dataLinks) {
									echo "	<li>\n";
									echo "		<a href='". $dataLinks['ProfileMediaURL'] ."' target='_blank'><i class=\"fa fa-link\"></i><br />". $dataLinks['ProfileMediaTitle'] ."</a>\n";
									echo "	</li>\n";
									}
									echo "</ul>\n";
								}
echo "					</div><!-- .container -->";
echo "				</div><!-- .tab-content -->";

// Profile Stats
echo "			<div class=\"container\">";
					if(!empty($profile_cf)){					
echo "				<div id=\"profile-stats\" class=\"rbcol-12 rbcolumn\">";
echo "					<ul>";
						foreach ($profile_cf as $stat) {
							$stitle = $stat['title'];
							$svalue = $stat['value'];
							$svalue = str_replace(" in", "\"", $svalue);
							$svalue = str_replace(" ft ", "'", $svalue);
							if($stitle){
echo "							<li><span class=\"title\">".$stitle."</span>: <span class=\"value\">".$svalue."</span></li>";
							}
						}
echo "					</ul>";
echo "				</div>\n";
					}
echo "			</div>\n";

echo " <div class=\"container go-back-btn\" style=\"width:10%;\">";
echo " <button onclick=\"goBack()\">Go Back</button>";
echo " <script type=\"text/javascript\" >";
echo " function goBack() { ";
echo "    window.history.back();";
echo " }";
echo " </script>";
echo " </div>";

// echo "  		<div class=\"rbcol-12 rbcolumn\">\n";
// echo "				<div id=\"scroller\">\n";
// echo "					<div id=\"photo-scroller\" class=\"scroller\">";
// 							// Image Slider

// 							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
// 							$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
// 							$countImg  = $wpdb->num_rows;
// 							foreach($resultsImg as $dataImg ){
// 								if ($countImg > 1) {
// 									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=350\"  /></a>\n";
// 								} else {
// 									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=350\" /></a>\n";
// 								}
// 							}
// echo "					</div><!-- #photo-scroller -->"; //
// echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo " 		</div>\n";// Close Profile Layout
echo "	</div>\n";// Close Profile
echo "	<div class=\"cb;\"></div>\n"; // Clear All

?>



<?php

function get_array_values($mapping, $keys) {
    foreach($keys as $key) {
        $output_arr[] = $mapping[$key];
    }
    return $output_arr;
}

function has_profile_media($media_type, $profile_id){
	global $wpdb;
	$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID = '%d' AND ProfileMediaType = '%s'";
	$resultsMedia = $wpdb->get_results($wpdb->prepare($queryMedia, $profile_id, $media_type),ARRAY_A);
	$countMedia  = $wpdb->num_rows;
	if($countMedia > 0) {
		return true;
	} else {
		return false;
	}
}

function get_profile_media_by_type($media_type, $profile_id, $ProfileGallery, $hasIcon = false, $label = false){
	global $wpdb;
	$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID = '%d' AND ProfileMediaType = '%s'";
	$resultsMedia = $wpdb->get_results($wpdb->prepare($queryMedia, $profile_id, $media_type),ARRAY_A);
	$countMedia  = $wpdb->num_rows;
	if($countMedia > 0) {
		if($hasLabel){
			echo "<br /><h3>".$media_type."</h3>";
		}		
		foreach ($resultsMedia as $digital) {
			$digitalType = $digital['ProfileMediaType'];
			$digitalURL = $digital["ProfileMediaURL"];
			$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $digitalURL;

			$itemClass = array('digital');
			$itemClass[] = strtolower($digitalType);
			$itemClassString = implode(" ", $itemClass);

			$image_params = array(
				// 'crop'=>true,
				// 'width'=>350,
				'height'=>780,
				// 'crop_width'=>'500',
				// 'crop_height'=>'500',
				// 'crop_only'=>true,
				// 'crop_y'=>'0'
			);
			$image_src = bfi_thumb( $image_path, $image_params );

			echo "<div class=\"".$itemClassString."\">";
			echo "	<div class=\"box\">";

			if ( $digitalType == "Headshot" || $digitalType == "CompCard" || $digitalType == "Polaroid" ) {
				echo "<a href=\"".$image_path."\" title=\"\" rel=\"lightbox[rbagency]\" target=\"_blank\"><img src=\"".$image_src."\" alt=\"\"/></a>";
			} else {
				if($digitalType == "Resume"){
					$iconClass = "file-audio-o";
				} elseif($digitalType == "VoiceDemo"){
					$iconClass = "file-text-o";
				} elseif($digitalType == "Video Slate"){
					$iconClass = "file-video-o";
				} else {
					$iconClass = "file-o";
				}		
			
				echo "	<a href=\"".RBAGENCY_UPLOADDIR . $ProfileGallery ."/".$digitalURL."\" target=\"_blank\">";
				echo "		<img src=\"".plugins_url()."/rb-agency/view/layout/19/images/dummy.jpg\" />";
				if($hasIcon){
					echo "		<div class=\"icon\"><i class=\"fa fa-".$iconClass."\"></i><br><span class=\"title\">$digitalType</span></div>";
				}		
				echo "	</a>";
			}

			echo "	</div>";
			echo "	</div>";
		}
		// echo "<div class=\"cb\"></div>";
	}
}


function get_profile_type($ProfileType){
	global $wpdb;
	$results = array();
	$queryProfileType = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID IN ($ProfileType) ORDER BY DataTypeTitle";
	$resultsProfileType = $wpdb->get_results($queryProfileType,ARRAY_A);
	foreach($resultsProfileType as $profileType){
		$results[] = $profileType['DataTypeTitle'];
	}
	return $results;
}

// function rb_get_profile_custom_value($profileID,$customView = ""){
//  global $wpdb;

//  $sql = "SELECT mux.ProfileCustomValue,mux.ProfileCustomDateValue,cus.ProfileCustomTitle FROM ".
//    $wpdb->prefix."agency_customfield_mux as mux INNER JOIN ".
//    $wpdb->prefix."agency_customfields as cus ON cus.ProfileCustomID = mux.ProfileCustomID INNER JOIN ".
//    $wpdb->prefix."agency_profile as profile ON profile.ProfileID = mux.ProfileID WHERE cus.ProfileCustomView = ".$customView." AND profile.ProfileID = ".$profileID;
//  $results = $wpdb->get_results($sql,ARRAY_A);
 
//  $result_value_handler = [];
//  foreach($results as $result){
//   $title = $result["ProfileCustomTitle"];
//   $title = strtolower($title);
//   $title = str_replace(" ", "_", $title);
//   //check if date
//   if($result['ProfileCustomDateValue'] !== '0000-00-00' && !empty($result['ProfileCustomDateValue'])){
//    $result_value_handler[$title] = $result['ProfileCustomDateValue'];
//   }else{
//    $result_value_handler[$title] = $result['ProfileCustomValue'];
//   }
  
//  }
//  return $result_value_handler;
// }

// $cf_public = rb_get_profile_custom_value($ProfileID,0);
//         print_r($cf_public);

?>

<script type="text/javascript">
(function($) {
		$(document).ready(function(){
	$('#links a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	var headerHeight = $(".site-header").outerHeight();
	var navHeight = $(".site-nav").outerHeight();
	var profilenavHeight = $("#rblayout-nineteen > .container").outerHeight();

	function setPortfolioScrollHeights() {
	    var scrollHeight = navsHeight() + $(".footer").height() + 45;
	    $(".portfolio-scroll").css("height", "calc(100vh - " + scrollHeight + "px)");
	    $(".portfolio-scroll ul").css("height", "calc(100vh - " + (scrollHeight + 20) + "px)");
	}
});
	})(jQuery);
</script>