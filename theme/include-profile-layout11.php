<?php
/*
Gallery View (without model details)
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"profile-layout-one\">\n";

	echo "	<div id=\"name\">\n";
	echo "	  <h2>". $ProfileContactDisplay ."</h2>\n";
	echo "	</div>\n";
       
	echo "	<div class=\"experience\">\n";
	echo		$ProfileExperience;
	echo "	</div>\n";  // Close Info
	
	echo "	<div id=\"photo\">\n";
	echo "	  <div class=\"inner\">\n";
		
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			}

	echo "	  </div>\n";
	echo "	</div>\n";
	
	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All

	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	
	
?>