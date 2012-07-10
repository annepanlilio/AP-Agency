<?php
						if ($rb_agency_option_profilenaming == 0) {
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileContactNameFirst\">". __("First Name", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        <input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION['ProfileContactNameFirst'] ."\" />\n";           
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileContactNameLast\">". __("Last Name", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION['ProfileContactNameLast'] ."\" />\n";           
								}
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("Both Male and Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Male\"". selected($_SESSION['ProfileGender'], "Male") .">". __("Male", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Female\"". selected($_SESSION['ProfileGender'], "Female") .">". __("Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "				        </select>\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Age", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" />\n";
		echo "				        <label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" />\n";
		echo "				    </div>\n";
		
								if ($rb_agency_option_unittype == 1) { // IMperial
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Height", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatHeight_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatHeight_min\" id=\"ProfileStatHeight_min\">\n";               
											if (empty($_SESSION['ProfileStatHeight_min'])) {
		echo "								 <option value=\"\" selected>". __("No Minimum", rb_agency_TEXTDOMAIN) . "</option>\n";
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
	
		echo "				        <label for=\"ProfileStatHeight_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatHeight_max\" id=\"ProfileStatHeight_max\">\n";               
											if (empty($_SESSION['ProfileStatHeight_max'])) {
		echo "								<option value=\"\" selected>". __("No Maximum", rb_agency_TEXTDOMAIN) . "</option>\n";
											}
											// Lets Convert It
											$i=36; $heightraw = 0; $heightfeet = 0; $heightinch = 0;
											while($i <= 90) {
												$heightraw = $i;
												$heightfeet = floor($heightraw/12);
												$heightinch = $heightraw - floor($heightfeet*12);
		echo "									<option value=\"". $i ."\" ". selected($_SESSION['ProfileStatHeight_max'], $i) .">". $heightfeet ." ft ". $heightinch ." in" . "</option>\n";
											  $i++;
											}
		echo "				        	</select>\n";
	
		echo "				    </div>\n";
								} else { // Metric
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Height", rb_agency_TEXTDOMAIN) . " (". __("cm", rb_agency_TEXTDOMAIN) . ")</div>\n";
		echo "				        <label for=\"ProfileStatHeight_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHeight_min\" name=\"ProfileStatHeight_min\" value=\"". $_SESSION['ProfileStatHeight_min'] ."\" />\n";
		echo "				        <label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHeight_max\" name=\"ProfileStatHeight_max\" value=\"". $_SESSION['ProfileStatHeight_max'] ."\" />\n";
		echo "				    </div>\n";
								}
		
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Weight", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatWeight_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_min\" name=\"ProfileStatWeight_min\" value=\"". $_SESSION['ProfileStatWeight_min'] ."\" />\n";
		echo "				        <label for=\"ProfileStatWeight_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_max\" name=\"ProfileStatWeight_max\" value=\"". $_SESSION['ProfileStatWeight_max'] ."\" />\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileStatEthnicity\">". __("Ethnicity", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatEthnicity\" id=\"ProfileStatEthnicity\">\n";               
											if (empty($ProfileStatEthnicity)) {
		echo "								<option value=\"\" selected>". __("Any Ethnicity", rb_agency_TEXTDOMAIN) . "</option>\n";
											}
											$query1 = "SELECT EthnicityTitle FROM ". table_agency_data_ethnicity ." ORDER BY EthnicityTitle";
											$results1 = mysql_query($query1);
											$count1 = mysql_num_rows($results1);
											while ($data1 = mysql_fetch_array($results1)) {
		echo "								<option value=\"". $data1['EthnicityTitle'] ."\""; if ($_SESSION['ProfileStatEthnicity'] == $data1['EthnicityTitle']) { echo " selected"; } echo ">". $data1['EthnicityTitle'] ."</option>\n";
											}
											mysql_free_result($results1);
		echo "				        	</select>\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileStatSkinColor\">". __("Skin Color", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatSkinColor\" id=\"ProfileStatSkinColor\">\n";               
											if (empty($ProfileStatSkinColor)) {
		echo "								<option value=\"\" selected>". __("Any Skin Color", rb_agency_TEXTDOMAIN) . "</option>\n";
											}
											$query = "SELECT * FROM ". table_agency_data_colorskin ." ORDER BY ColorSkinTitle";
											$results = mysql_query($query);
											while ($data = mysql_fetch_array($results)) {
		echo "								<option value=\"". $data['ColorSkinTitle'] ."\""; if ($_SESSION['ProfileStatSkinColor'] == $data['ColorSkinTitle']) { echo " selected"; } echo ">". $data['ColorSkinTitle'] ."</option>\n";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileStatEyeColor\">". __("Eye Color", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatEyeColor\" id=\"ProfileStatEyeColor\">\n";               
											if (empty($ProfileStatEyeColor)) {
		echo "								<option value=\"\" selected>". __("Any Eye Color", rb_agency_TEXTDOMAIN) . "</option>\n";
											}
											$query = "SELECT * FROM ". table_agency_data_coloreye ." ORDER BY ColorEyeTitle";
											$results = mysql_query($query);
											while ($data = mysql_fetch_array($results)) {
		echo "								<option value=\"". $data['ColorEyeTitle'] ."\""; if ($_SESSION['ProfileStatEyeColor'] == $data['ColorEyeTitle']) { echo "selected=\"selected\""; } echo ">". $data['ColorEyeTitle'] ."</option>\n";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileStatHairColor\">". __("Hair Color", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileStatHairColor\" id=\"ProfileStatHairColor\">\n";               
											if (empty($ProfileStatHairColor)) {
		echo "								<option value=\"\" selected>". __("Any Hair Color", rb_agency_TEXTDOMAIN) . "</option>\n";
											}
											$query = "SELECT * FROM ". table_agency_data_colorhair ." ORDER BY ColorHairTitle";
											$results = mysql_query($query);
											while ($data = mysql_fetch_array($results)) {
		echo "								<option value=\"". $data['ColorHairTitle'] ."\""; if ($_SESSION['ProfileStatHairColor'] == $data['ColorHairTitle']) { echo "selected=\"selected\""; } echo ">". $data['ColorHairTitle'] ."</option>\n";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Chest", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatBust_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatBust_min\" name=\"ProfileStatBust_min\" value=\"". $_SESSION['ProfileStatBust_min'] ."\" />\n";
		echo "				        <label for=\"ProfileStatWeight_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatBust_max\" name=\"ProfileStatBust_max\" value=\"". $_SESSION['ProfileStatBust_max'] ."\" />\n";
		echo "				    </div>\n";
		
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Waist", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatWaist_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWaist_min\" name=\"ProfileStatWaist_min\" value=\"". $_SESSION['ProfileStatWaist_min'] ."\" />\n";
		echo "				        <label for=\"ProfileStatWaist_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWaist_max\" name=\"ProfileStatWaist_max\" value=\"". $_SESSION['ProfileStatWaist_max'] ."\" />\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Hips", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatHip_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHip_min\" name=\"ProfileStatHip_min\" value=\"". $_SESSION['ProfileStatHip_min'] ."\" />\n";
		echo "				        <label for=\"ProfileStatHip_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHip_max\" name=\"ProfileStatHip_max\" value=\"". $_SESSION['ProfileStatHip_max'] ."\" />\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field double\">\n";
		echo "				        <div class=\"label\">". __("Shoe", rb_agency_TEXTDOMAIN) . "</div>\n";
		echo "				        <label for=\"ProfileStatShoe_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatShoe_min\" name=\"ProfileStatShoe_min\" value=\"". $_SESSION['ProfileStatShoe_min'] ."\" />\n";
		echo "				        <label for=\"ProfileStatShoe_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatShoe_max\" name=\"ProfileStatShoe_max\" value=\"". $_SESSION['ProfileStatShoe_max'] ."\" />\n";
		echo "				    </div>\n";
	
		echo "				    <div class=\"search-field single\">\n";
		echo "				        <label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileType']) {
													if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
		
		
		
		
		
		// CHECKBOX
		$ProfileCustomOptions_Array = explode( "|", $data1['ProfileCustomOptions']);
										foreach ($ProfileCustomOptions_Array as &$value) {
										//echo "	<input type=\"checkbox\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $value ."\" ". checked($ProfileCustomValue, $value) ." /> ". $value ."\n";
										} 
	