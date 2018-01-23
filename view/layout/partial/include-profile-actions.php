<?php
/*
 * Social Links
 */
global $user_ID;

$ProfileType = get_user_meta($user_ID,"rb_agency_interact_profiletype",true);
//profile_dashboard_link($user_ID,$ProfileType);

// Social Link
if(function_exists('rb_agency_getSocialLinks')) {
	echo "<div id=\"profile-social\">";
		rb_agency_getSocialLinks($ProfileID);
	echo "</div>";
}


echo "<div id=\"profile-casting-link\">";
	if(is_user_logged_in()){
		if(rb_get_casting_profileid() > 0 && !current_user_can("manage_options")){
			$disp .= "<a href=\"".  get_bloginfo("wpurl") ."/casting-dashboard/\" rel=\"nofollow\" title=\"View Favorites\" class=\"btn btn-primary\">".__("Go Back to My Account",RBAGENCY_TEXTDOMAIN)."</a>";
		}
	}
echo "</div>";
echo "<div id=\"profile-actions\">";
	if (is_plugin_active('rb-agency-casting/rb-agency-casting.php') && is_user_logged_in()) {
		echo rb_agency_get_new_miscellaneousLinks($ProfileID);
	}
echo "</div>";


echo "<div id=\"profile-links\">\n";

// View Photos and Print Photos
	if(isset($rb_agency_options_arr["rb_agency_option_layoutprofile"]) && $rb_agency_options_arr["rb_agency_option_layoutprofile"] != 2 && $rb_agency_options_arr["rb_agency_option_layoutprofile"] != 3){

		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countImg  = $wpdb->num_rows;

		if($countImg  > 0){
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/\" class=\"profile-link\">". __("View Photos", RBAGENCY_TEXTDOMAIN)."</a>\n"; //MODS 2014-05-21
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/images/\" class=\"profile-link\">". __("Print Photos", RBAGENCY_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-28
		}
	}

// Polaroid
	if(isset($rb_agency_options_arr["rb_agency_option_layoutprofile"]) && $rb_agency_options_arr["rb_agency_option_layoutprofile"] != 2 && $rb_agency_options_arr["rb_agency_option_layoutprofile"] != 3){
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countImg  = $wpdb->num_rows;

		if($countImg  > 0){
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/polaroids/\" class=\"lightbox[polaroid] profile-link polaroid\">". __("View Polaroids", RBAGENCY_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-30
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/print-polaroids/\" class=\"profile-link polaroid\">". __("Print Polaroids", RBAGENCY_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-28
		}
	}

// Resume
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/resume/". $dataMedia['ProfileMediaURL'],true) ." class=\"profile-link\">".__("View Resume",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}
	}

// Comp Card
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CompCard");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/compcard/". $dataMedia['ProfileMediaURL']) ."  class=\"lightbox[compcard] profile-link\">".__("View Comp Card",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}
	}

// Card Photos
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_profilemedia_links = isset($rb_agency_options_arr["rb_agency_option_profilemedia_links"])?$rb_agency_options_arr["rb_agency_option_profilemedia_links"]:2;

	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CardPhotos");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		//foreach($resultsImg as $dataMedia ){
		if($rb_agency_option_profilemedia_links == 2){
			echo "<a ".rb_get_profilemedia_link_opentype(get_bloginfo('url')."/profile/".$ProfileGallery."/cardphotos/",true,true) ."  class=\"cardphotos-link\">".__("View Model Card",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}else{
			echo "<a ".rb_get_profilemedia_link_opentype(get_bloginfo('url')."/profile/".$ProfileGallery."/cardphotos/",true,true) ."  class=\"cardphotos-link\">".__("Download Model Card",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}

		//}
	}

// Headshots
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot",0,true);
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows; 
	if ($countMedia > 0) {javascript:;
		foreach($resultsImg as $dataMedia ){
		    
			echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/headshot/". $dataMedia['ProfileMediaURL']) ."  class=\"lightbox[headshot]  profile-link\">".__("View Headshot",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}
	}

//Voice Demo
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			$optionProfileMedia = get_option("voicedemo_".$dataMedia['ProfileMediaID']);
			$voicedemo = empty($optionProfileMedia) ? "Voice Demo" : get_option("voicedemo_".$dataMedia['ProfileMediaID']);
			echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/voicedemo/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\">".$voicedemo."</a>\n";
		}
	}

//Video Slate
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
			echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link slate\">".__("Watch Video Slate",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}
	}

//Video Monologue
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link monologue\">".__("Watch Video Monologue",RBAGENCY_TEXTDOMAIN)."</a>\n";
		}
	}

//Demo Reel
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			if ( substr($dataMedia['ProfileMediaURL'], 0, 4) == "http" ) {
				echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link\">".__("Watch Demo Reel",RBAGENCY_TEXTDOMAIN)."</a>\n";
			} else {
				if ( $dataMedia['ProfileVideoType'] == "youtube") {
					echo "<a href=\"https://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link\">".__("Watch Demo Reel",RBAGENCY_TEXTDOMAIN)."</a>\n";
				} elseif ( $dataMedia['ProfileVideoType'] == "vimeo") {
					echo "<a href=\"https://vimeo.com/". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link\">".__("Watch Demo Reel",RBAGENCY_TEXTDOMAIN)."</a>\n";
				}
			}
		}
	}

// Custom URLs
	$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Link");
	$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
	$countMedia = $wpdb->num_rows;
	if ($countMedia > 0) {
		foreach($resultsImg as $dataMedia ){
			echo "<a href=\"". rb_get_profilemedia_link_opentype($ProfileGallery ."/custom/". $dataMedia['ProfileMediaURL'])  ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link\">". $dataMedia['ProfileMediaTitle'] ."</a>\n";
		}
	}

	// Other Media Type not the default ones
		/*$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType NOT IN ('Image','Resume','Polaroid','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')";
		$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg, $ProfileID),ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				if (!empty($dataMedia['ProfileMediaType']) && isset($dataMedia['ProfileMediaType'])) {
					echo "<li class=\"item video custom\"><a target=\"_blank\" href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">". $dataMedia['ProfileMediaType'] . "</a></li>\n";
				}
			}
		}*/
		rb_agency_showMediaCategories($ProfileID, $ProfileGallery);

echo "	</div>\n";

/*
 * Contact

	//Contact Profile
	if($rb_agency_option_showcontactpage==1){
		echo "<div class=\"rel\"><strong>". __("Contact: ", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
	}
 */

?>
