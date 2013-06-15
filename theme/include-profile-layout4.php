<?php
/*

Escort
	Use Age not birthdate
	echo "	<div id=\"profile-sidebar\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
	echo "	</div>\n";

*/

	echo "	<div id=\"profile\">\n";
	echo " 		<div id=\"rblayout-four\" class=\"rblayout\">\n";

	echo "			<div id=\"name\">\n";
	echo "	  			<h1>". $ProfileContactDisplay ." <a href=\"contact/\">Book Now!</a></h1>\n";

				      	// Social Link
					 	rb_agency_getSocialLinks();

	echo "			</div>\n";

	echo "			<div id=\"info\" class=\"col_4 column\">\n";

	echo "	  			<div class=\"experience\">\n";
	echo					$ProfileExperience;
	echo "	  			</div>\n"; // Close Experience
	
	echo "	  			<h3>Statistics</h3>\n";

	echo "	  			<div class=\"stats\">\n";

	echo "	  				<ul>\n";

	if (!empty($ProfileGender)) {
		$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
		$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
	echo "						<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
	}
	
	// Insert Custom Fields
	rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);

	if($rb_agency_option_showcontactpage==1){
		echo "					<li class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
	}

	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC");

	foreach  ($resultsCustom as $resultCustom) {
	   	if(!empty($resultCustom->ProfileCustomValue)){
			echo "				<li><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
	   	}
	}
	echo "	  				</ul>\n"; // Close ul
	echo "	    			<div id=\"book-now\"><a href=\"contact/\">Book Now!</a></div>\n"; // Close Stats
	echo "	  			</div>\n"; // Close Stats	
	echo "			</div><!-- #info -->\n";

	echo "			<div id=\"photo\" class=\"col_8 column\">\n";	
	echo "	  			<h3>". __("Call", rb_agency_TEXTDOMAIN). ": <span>". $ProfileContactPhoneWork ."</span></h3>\n";
	echo "	  			<div class=\"inner\">\n";
		
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  	if ($countImg > 1) { 
					echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  	} else {
					echo "<div class=\"single\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  	}
			}

	echo "	  			</div><!-- .inner -->\n";
	echo "			</div><!-- #photo -->\n";	
	
	echo "  		<div style=\"clear: both;\"></div>\n"; // Clear All

	echo " 		</div>\n";  // Close Profile Layout
	echo "	</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All	
?>