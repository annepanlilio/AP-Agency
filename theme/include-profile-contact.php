<?php
/*
Default Contact
*/

	echo "<div id=\"profile-contact\">\n";
	echo "	<div id=\"profile-picture\">\n";

		// images
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
		$resultsImg = mysql_query($queryImg);
		$countImg = mysql_num_rows($resultsImg);
		while ($dataImg = mysql_fetch_array($resultsImg)) {
			echo "		<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
		}

	echo "	</div> <!-- #profile-picture -->\n";
	echo "	<div id=\"profile-contact-form\">\n";
	echo "	  <h2>Contact ". $ProfileContactDisplay ." (ID-". $ProfileID .")</h2>\n";
	echo "	  <div class=\"form\">\n";
			echo "<script>\n";
			echo "	$(function() {\n";
			echo "		$( \"#datepicker\" ).datepicker();\n";
			echo "	});\n";
			echo "</script>\n";

	echo "	  	<form method=\"post\" action=\"". $rb_agency_WPURL ."/profile/". $profileURL ."/contact/\">\n";
	echo "	  	  <div><span>Your Name</span><input name=\"contact-your-name\" /></div>\n";
	echo "	  	  <div><span>Your Email</span><input name=\"contact-your-email\" /></div>\n";
	echo "	  	  <div><span>Date</span><input name=\"contact-your-date\" class=\"rbdatepicker\" /></div>\n";
	echo "	  	  <div><span>Message</span><textarea name=\"contact-your-message\" /></textarea></div>\n";
	echo "	  	  <div><input type=\"submit\" name=\"submit\" /></div>\n";
	echo "	  	  <input type=\"hidden\" name=\"contact-profileid\" value=\"". $ProfileID ."\" /></div>\n";
	echo "	  	  <input type=\"hidden\" name=\"contact-action\" value=\"contact\" /></div>\n";
	echo "	  	</form>\n";
	
	echo "	  </div>\n";  // Close Action
	
	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All
	echo "	</div>\n";  // Close info
	echo "</div>\n";  // Close Contact
?>