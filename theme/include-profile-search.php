<?php
global $wpdb;
$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];
	$rb_agency_option_unittype = $rb_agency_options_arr['rb_agency_option_unittype'];
	//Custom fields display options
	$rb_agency_option_customfields_profilepage = $rb_agency_options_arr['rb_agency_option_customfield_profilepage'];
	$rb_agency_option_customfields_searchpage = $rb_agency_options_arr['rb_agency_option_customfield_searchpage'];
	$rb_agency_option_customfields_loggedin_all = $rb_agency_options_arr['rb_agency_option_customfield_loggedin_all'];
	$rb_agency_option_customfields_loggedin_admin = $rb_agency_options_arr['rb_agency_option_customfield_loggedin_admin'];
	

	
$_SESSION['ProfileType'] = $_REQUEST['ProfileType'];
	if (isset($DataTypeID) && !empty($DataTypeID)) { $_SESSION['ProfileType'] = $DataTypeID; }
$_SESSION['ProfileGender'] = $_REQUEST['ProfileGender'];
	

   if ($profilesearch_layout == "condensed" || $profilesearch_layout == "simple") {
	
		echo "		<div id=\"profile-search-form-condensed\" class=\"search-form\">\n";
		echo "        	<form method=\"post\" id=\"search-form-condensed\" action=\"". get_bloginfo("wpurl") ."/profile-search/\">\n";
		echo "        		<div><input type=\"hidden\" name=\"action\" value=\"search\" /></div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <div> <label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div><select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileType']) {
													if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select></div>\n";
		echo "				    </div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <div> <label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        <div><select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("--", rb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = mysql_query($query2);
											while ($dataGender = mysql_fetch_array($results2)) {
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
											}
	      echo "				        </select></div>\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Age", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <div><label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div><input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>\n";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div><input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>\n";
		echo "				    </div>\n";

		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div><input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" /></div>\n";
		echo "				<br /><a href=\"". $rb_agency_WPURL ."/profile-search/\">". __("Advanced Search", rb_agency_TEXTDOMAIN) . "</a>\n";
		echo "        	<form>\n";
		echo "		</div>\n";

   } else {
	   // Advanced

		echo " <div id=\"profile-search-form-advanced\" class=\"search-form\">\n";
		echo "  <form method=\"post\" id=\"search-form-advanced\" action=\"". get_bloginfo("wpurl") ."/profile-search/\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"rb_agency_menu_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
		
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<div><label for=\"ProfileFirstName\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label></div>\n";
	      echo "		 				<div><input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" /></div>\n";
	      echo "	 				</div>\n";
		
		echo "	 				<div class=\"search-field single\">\n";
		echo "		 				<div><label for=\"ProfileLastName\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label></div>\n";
	      echo "						 <div><input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" /></div>\n";
		echo "					 </div>\n";
	
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <div> <label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div>";
		echo "						<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".selected($_SESSION['ProfileType'],$dataType["DataTypeID"] ,false).">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>";
		echo "						</div>\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <div> <label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				       <div> <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("All Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = mysql_query($query2);
											while ($dataGender = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileGender']) {
													if ($dataGender["GenderTitle"] ==  $_SESSION['ProfileGender']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"] ,false).">". $dataGender["GenderTitle"] ."</option>";
											}
	      echo "				        </select></div>\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Age", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <div><label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div><input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>\n";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label></div>\n";
		echo "				        	<div><input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>\n";
		echo "				    </div>\n";
		
							
						        
	     if($rb_agency_option_customfields_searchpage == 1 || $rb_agency_option_customfield_profilepage == 1 ){ // Show on Search Page or Profile Page
		    
			 if(is_user_logged_in())  // All with loggedin permissions
			 {
				
				 // Show custom fields for admins only.
				if($rb_agency_option_customfields_loggedin_admin == 1 && current_user_can("level_10")){ 
			       	 include("include-custom-fields.php");
					//echo "1-";
				}
				// Show custom fields for loggedin members only
				else{
					 include("include-custom-fields.php");
					// echo "3-";
				}
				
			 }else{ // All with non-logged here
			
			
					 // Show custom fields to public
					 include("include-custom-fields.php");
				
				
			}
		
			
		 }
			
					 
		
		
		
	 
		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div><input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" /></div>\n";
		//echo "				<input type=\"submit\" value=\"". __("Reset Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
		//echo "				<input type=\"button\" onclick=\"document.getElementById('search-form-advanced').reset();\" value=\"". __("Clear Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
		echo "        	<form>\n";
		echo "			</div>\n";
		
		
   }
?>
