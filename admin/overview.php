<?php
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype = $rb_agency_options_arr['rb_agency_option_unittype'];
	get_currentuserinfo(); global $user_level;

echo "<div class=\"wrap\">\n";
echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
echo "  <h2>". __("Dashboard", rb_agency_TEXTDOMAIN) ."</h2>\n";
echo "  <p>". __("You are using version", rb_agency_TEXTDOMAIN) ." <b>". rb_agency_VERSION ."</b></p>\n";
  
echo "  <div class=\"boxblock-holder\">\n";
  
echo "    <div class=\"boxblock-container\" style=\"width: 46%;\">\n";
    
echo "     <div class=\"boxblock\">\n";
echo "        <h3>". __("Quick Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
echo "        <div class=\"inner\">\n";

	   if ($user_level >= 7) {
		   
		echo "        	<form method=\"GET\" action=\"". admin_url("admin.php?page=rb_agency_menu_search") ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"rb_agency_menu_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
		echo "				<table cellspacing=\"0\">\n";
		echo "				  <thead>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Classification", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "							<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
										$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
										$results2 = mysql_query($query);
										while ($dataType = mysql_fetch_array($results2)) {
											if ($_SESSION['ProfileType']) {
												if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
											} else { $selectedvalue = ""; }
											echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
										}
		echo "				        	</select></td>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Gender", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("Both Male and Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Male\"". selected($_SESSION['ProfileGender'], "Male") .">". __("Male", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Female\"". selected($_SESSION['ProfileGender'], "Female") .">". __("Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Age", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		
		  $queryHeight = mysql_query("SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomTitle = 'Height' or ProfileCustomTitle = 'height'  ");
		   $dataHeight = mysql_fetch_assoc($queryHeight) or die(mysql_error());
		  $countHeight = mysql_num_rows($queryHeight);
		  if($countHeight > 0){
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) . " :</th>\n";
		echo "				        <td>\n";               
									if ($rb_agency_option_unittype == 1) {
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<select name=\"ProfileCustomID".$dataHeight["ProfileCustomID"]."[]\" id=\"ProfileStatHeight_min\">\n";               
										  if (empty($_SESSION['ProfileStatHeight_min'])) {
		echo "								<option value=\"\" selected>". __("No Minimum", rb_agency_TEXTDOMAIN) . "</option>\n";
										  }
										  // Lets Convert It
										  $i=36; $heightraw = 0; $heightfeet = 0; $heightinch = 0;
										  while ($i <= 90) { 
											 $heightraw = $i;
											 $heightfeet = floor($heightraw / 12);
											 $heightinch = $heightraw - floor($heightfeet * 12);
		echo "								 <option value=\"". $i ."\" ". selected($_SESSION['ProfileStatHeight_min'], $i) .">". $heightfeet ." ft ". $heightinch ." in" . "</option>\n";
											 $i++;
										  }
		echo "				        	</select>\n";

		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<select name=\"ProfileCustomID".$dataHeight["ProfileCustomID"]."[]\" id=\"ProfileStatHeight_max\">\n";               
										  if (empty($_SESSION['ProfileStatHeight_max'])) {
		echo "								<option value=\"\" selected>". __("No Maximum", rb_agency_TEXTDOMAIN) . "</option>\n";
										  }
										  // Lets Convert It
										  $i=36; $heightraw = 0; $heightfeet = 0; $heightinch = 0;
										  while ($i <= 90) { 
											 $heightraw = $i;
											 $heightfeet = floor($heightraw / 12);
											 $heightinch = $heightraw - floor($heightfeet * 12);
		echo "								 <option value=\"". $i ."\" ". selected($_SESSION['ProfileStatHeight_min'], $i) .">". $heightfeet ." ft ". $heightinch ." in" . "</option>\n";
											 $i++;
										  }
		echo "				        	</select>\n";
									} else {
									// Metric
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . " (". __("cm", rb_agency_TEXTDOMAIN) . "):\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . " (". __("cm", rb_agency_TEXTDOMAIN) . "):\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" />\n";
									}

		echo "				        </td>\n";
		echo "				    </tr>\n";
		  } // end if height
		$queryWeight = mysql_query("SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomTitle = 'Weight' or ProfileCustomTitle = 'weight'  ");
		 $dataWeight = mysql_fetch_assoc($queryWeight);
		$countWeight = mysql_num_rows($queryWeight);
		if($countWeight > 0){
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Weight", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_min\" name=\"".$dataHeight["ProfileCustomID"]."[]\" value=\"". $_SESSION['ProfileStatWeight_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_max\" name=\"".$dataHeight["ProfileCustomID"]."[]\" value=\"". $_SESSION['ProfileStatWeight_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				  </thead>\n";
		echo "				</table>\n";
		echo "				<div>\n";
		echo "				<input type=\"submit\" value=\"". __("Quick Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "				<a href=\"?page=rb_agency_menu_search\" class=\"button-secondary\">". __("Advanced Search", rb_agency_TEXTDOMAIN) . "</a></p>\n";
		echo "				</div>\n";
		echo "        	<form>\n";
		  }
	   } // Editor  

echo "        </div>\n"; 
echo "     </div>\n"; 

echo "    </div><!-- .container -->\n"; 

echo "    <div class=\"boxblock-container\" style=\"width: 46%;\">\n"; 
 
echo "     <div class=\"boxblock\">\n"; 
echo "        <h3>". __("Actions", rb_agency_TEXTDOMAIN) . "</h3>\n"; 
echo "        <div class=\"inner\">\n"; 

			   if ($user_level >= 7) {
            echo "<p><a href='?page=rb_agency_menu_profiles' class=\"button-secondary\">". __("Manage Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Browse and edit existing profiles", rb_agency_TEXTDOMAIN) . ".</p>";
            echo "<p><a href='?page=rb_agency_menu_profiles&action=add' class=\"button-secondary\">". __("Create New Profile", rb_agency_TEXTDOMAIN) . "</a> - ". __("Create new profile", rb_agency_TEXTDOMAIN) . ".</p>";
            echo "<p><a href='?page=rb_agency_menu_search' class=\"button-secondary\">". __("Search Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Find and send profiles by filtering by chriteria", rb_agency_TEXTDOMAIN) . ".</p>";
			   }

echo "        </div>\n"; 
echo "     </div>\n"; 

echo "     <div class=\"boxblock\">\n"; 
echo "        <h3>". __("Recent Activity", rb_agency_TEXTDOMAIN) . "</h3>\n"; 
echo "        <div class=\"inner\">\n"; 

			   if ($user_level >= 7) {
				// Recently Updated
				echo "<p class=\"sub\">". __("Recently Created/Modified Profiles", rb_agency_TEXTDOMAIN) . "</p>";
				echo "<div style=\"border-top: 2px solid #c0c0c0; \" class=\"profile\">";
				$query = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileDateUpdated DESC LIMIT 0,10";
				$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
				$count = mysql_num_rows($results);
				while ($data = mysql_fetch_array($results)) {
					$ProfileDateUpdated = $data['ProfileDateUpdated'];
					echo "<div style=\"border-bottom: 1px solid #e1e1e1; line-height: 22px; \" class=\"profile\">";
					echo " <div style=\"font-size: 8px; float: left; width: 100px; line-height: 22px; \"><em>" . $ProfileDateUpdated . "</em></div>";
					echo " <div style=\"float: left; width: 200px; \"><a href=\"?page=rb_agency_menu_profiles&action=editRecord&ProfileID=". $data['ProfileID'] ."\">". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</a></div>"; 
					echo " <div style=\"clear: both; \"></div>";
					echo "</div>";
				}
				mysql_free_result($results);
				if ($count < 1) {
					echo "". __("There are currently no profiles added", rb_agency_TEXTDOMAIN) . ".";
				}
				echo "</div>";
				
				// Recently Viewed
				echo "<p style=\"margin-top: 15px;\" class=\"sub\">". __("Recently Viewed Profiles", rb_agency_TEXTDOMAIN) . "</p>";
				echo "<div style=\"border-top: 2px solid #c0c0c0; \" class=\"profile\">";
				$query = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileDateViewLast DESC LIMIT 0,10";
				$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
				$count = mysql_num_rows($results);
				while ($data = mysql_fetch_array($results)) {
					echo "<div style=\"border-bottom: 1px solid #e1e1e1; line-height: 22px; \" class=\"profile\">";
					echo " <div style=\"font-size: 8px; float: left; width: 100px; line-height: 22px; \"><em>" . $data['ProfileDateViewLast'] . "</em></div>";
					echo " <div style=\"float: left; width: 250px; \"><a href=\"?page=rb_agency_menu_profiles&action=editRecord&ProfileID=". $data['ProfileID'] ."\">". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</a></div>"; 
					echo " <div style=\"font-size: 8px; float: left; width: 50px; \">". $data['ProfileStatHits'] ." Views</div>";
					echo " <div style=\"clear: both; \"></div>";
					echo "</div>";
				}
				mysql_free_result($results);
				if ($count < 1) {
					echo "". __("There are currently no profiles added", rb_agency_TEXTDOMAIN) . ".";
				}
				echo "</div>";
			
		   }
echo "        </div>\n"; 
echo "     </div>\n"; 

    
echo "    </div><!-- .container -->\n"; 

echo "    <div class=\"clear\"></div>\n"; 

echo "    <div class=\"boxblock-container\" style=\"width: 93%;\">\n"; 

echo "     <div class=\"boxblock\">\n"; 
echo "        <div class=\"inner\">\n"; 
echo "            <p>". __("WordPress Plugins by ", rb_agency_TEXTDOMAIN) . " <a href=\"http://rbplugin.com\" target=\"_blank\">Rob Bertholf</a>.</p>\n"; 
echo "        </div>\n"; 
echo "     </div>\n"; 
     
echo "    </div><!-- .container -->\n"; 

echo " </div>\n"; 
echo "</div>\n"; 
?>