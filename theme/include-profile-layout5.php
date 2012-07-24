<?php
/*
Custom Layout: Shake it like a polaroid picture
*/
	echo "<link rel=\"stylesheet\" href=\"/wp-content/plugins/rb-agency/theme/custom-layout5/css/style.css\" type=\"text/css\" media=\"screen\"/>\n";
	echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n";
	echo "<script src=\"/wp-content/plugins/rb-agency/theme/custom-layout5/js/cufon-yui.js\" type=\"text/javascript\"></script>\n";
	echo "<script src=\"/wp-content/plugins/rb-agency/theme/custom-layout5/js/jquery.transform-0.8.0.min.js\"></script>\n";
	echo "<script src=\"/wp-content/plugins/rb-agency/theme/custom-layout5/js/image.js\"></script>\n";
	echo "<script type=\"text/javascript\">\n";
	echo "Cufon.replace('span');
			Cufon.replace('h1', {
				textShadow: '0px 1px #ddd'
			});

			$(document).ready(function(){
			         setTimeout(
						function(){
							$('#pp_thumbContainer div.album').click();
						}
					,800);
					
					$(\"#pp_back\").click(function(){
							  setTimeout(
								function(){
									$('#pp_thumbContainer div.album').click();
								}
							,800);
					});

			});

		</script>
        <style type=\"text/css\">
		#profile
		{
			width:960px;
			min-height:600px;
			margin:0 auto;
			background:#eef1f1;
		}
		#profile-layout-four
		{
			width:960px;
			min-height:400px;
			float:left;
		
		}
		.rel
		{
			text-transform:lowercase;
		}
		.stats
		{
			float:left; width:200px;
			margin-top:12px;
		}
		
		.stats div
		{
			margin-top:12px;
			margin-left:30px;
			font-size:15px;
			border-bottom:1px dotted #333;
		}

		</style>\n";

	echo "<div id=\"profile\">\n";
	echo " <div id=\"profile-layout-four\">\n";
				
		//=============================================================================================
		//    Statistics with Name and Info
		//=============================================================================================

	echo "		<div style=\"float:left; width:960px; min-height:501px; margin-left:20px;margin-top:40px;\">\n";
	echo "				<h1 style=\"font-size:60px;font-family:Arial; color:#555;\">". $ProfileContactDisplay ."</h1>\n";
	echo "				<div style=\"float:left; width:900px;min-height:200px; font-family:Arial Narrow, Helvetica, sans-serif; font-size:18px; color:#877; border:1px solid #999; margin-top:12px;\">\n";
	echo "					<div class=\"stats\" >\n";
		if (!empty($ProfileGender)) {
			echo "<div class=\"rel\"><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileGender ."</div>\n";
		}
		if (!empty($ProfileStatEthnicity)) {
			echo "<div class=\"rel\"><strong>". __("Ethnicity", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEthnicity ."</div>\n";
		}
		if (!empty($ProfileStatSkinColor)) {
			echo "<div class=\"rel\"><strong>". __("Skin Tone", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatSkinColor ."</div>\n";
		}
		if (!empty($ProfileStatHairColor)) {
			echo "<div class=\"rel\"><strong>". __("Hair Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHairColor ."</div>\n";
		}
		if (!empty($ProfileStatEyeColor)) {
			echo "<div class=\"rel\"><strong>". __("Eye Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEyeColor ."</div>\n";
		}
		if (!empty($ProfileStatHeight)) {
			if ($rb_agency_option_unittype == 0) { // Metric
				echo "<div class=\"rel\"><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</div>\n";
			} else { // Imperial
				$heightraw = $ProfileStatHeight;
				$heightfeet = floor($heightraw/12);
				$heightinch = $heightraw - floor($heightfeet*12);
				echo "<div class=\"rel\"><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</div>\n";
			}
		}
		if (!empty($ProfileStatWeight)) {
			if ($rb_agency_option_unittype == 0) { // Metric
				echo "<div class=\"rel\"><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</div>\n";
			} else { // Imperial
				echo "<div class=\"rel\"><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</div>\n";
			}
		}
		if (!empty($ProfileStatBust)) {
			if($ProfileGender == "Male"){ $ProfileStatBustTitle = __("Chest", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatBustTitle = __("Bust", rb_agency_TEXTDOMAIN); } else { $ProfileStatBustTitle = __("Chest/Bust", rb_agency_TEXTDOMAIN); }
			echo "<div class=\"rel\"><strong>". $ProfileStatBustTitle ."</strong> ". $ProfileStatBust ."</div>\n";
		}
		if (!empty($ProfileStatWaist)) {
			echo "<div class=\"rel\"><strong>". __("Waist", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWaist ."</div>\n";
		}
		if (!empty($ProfileStatHip)) {
			if($ProfileGender == "Male"){ $ProfileStatHipTitle = __("Inseam", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatHipTitle = __("Hips", rb_agency_TEXTDOMAIN); } else { $ProfileStatHipTitle = __("Hips/Inseam", rb_agency_TEXTDOMAIN); }
			echo "<div class=\"rel\"><strong>". $ProfileStatHipTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatHip ."</div>\n";
		}
		if (!empty($ProfileStatDress) || ($ProfileStatDress == 0)) {
			if($ProfileGender == "Male"){ $ProfileStatDressTitle = __("Suit Size", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatDressTitle = __("Dress Size", rb_agency_TEXTDOMAIN); } else { $ProfileStatDressTitle = __("Suit/Dress Size", rb_agency_TEXTDOMAIN); }
			echo "<div class=\"rel\"><strong>". $ProfileStatDressTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatDress ."</div>\n";
		}
		if (!empty($ProfileStatShoe)) {
			echo "<div class=\"rel\"><strong>". __("Shoe Size", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatShoe ."</div>\n";
		}
	
		    echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"/profile/".$_GET["target"]."/contact/\">Click Here</a></div>\n";
	
	echo "					</div>\n";
	echo "				</div>\n";	
	echo "		</div>	\n";	



		//=============================================================================================
		//    Here is the Photo gallery
		//    Must follow the Id and Class names
		//=============================================================================================

	echo "		<div id=\"pp_gallery\" class=\"pp_gallery\">\n";
	echo "		  <div id=\"pp_loading\" class=\"pp_loading\"></div>\n";
	echo "		  <div id=\"pp_next\" class=\"pp_next\"></div>\n";
	echo "		  <div id=\"pp_prev\" class=\"pp_prev\"></div>\n";
	echo "		  <div id=\"pp_thumbContainer\">\n";

	echo "		  <div class=\"album\">\n";

			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
	echo "		  	<div class=\"content\">\n";
	echo "		  		<img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" />\n";
	echo "		  		<span>". $ProfileContactDisplay ."</span>\n";
	echo "		  	</div>\n";
			}


	
	
	echo "  		<div class=\"descr\">". $ProfileContactDisplay ." Photos</div>\n";
	echo "		  </div>\n";

	echo "		  <div id=\"pp_back\" class=\"pp_back\">Reset</div>\n";
	echo "		</div>\n";
	echo "		</div>\n";
	echo "  </div>\n";
	echo "  </div> \n";





	echo "  <div style=\"clear: both;\"></div>\n"; // Clear All

	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	
	
?>