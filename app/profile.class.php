<?php
class RBAgency_Profile {

	/*
	 * Search Form
	 * Process Search
	 */

		public static function search_form($atts, $args = ""){

			/*
			 * Setup Requirements
			 */

				global $wpdb;
				$rb_agency_options_arr = get_option('rb_agency_options');
					// What is the unit of measurement?
					$rb_agency_option_unittype = $rb_agency_options_arr['rb_agency_option_unittype'];

					/* Search Form - Simple */
					$rb_agency_option_formshow_location = $rb_agency_options_arr['rb_agency_option_formshow_location'];
					$rb_agency_option_formshow_name = $rb_agency_options_arr['rb_agency_option_formshow_name'];
					$rb_agency_option_formshow_type = $rb_agency_options_arr['rb_agency_option_formshow_type'];
					$rb_agency_option_formshow_gender = $rb_agency_options_arr['rb_agency_option_formshow_gender'];
					$rb_agency_option_formshow_age = $rb_agency_options_arr['rb_agency_option_formshow_age'];

			/*
			 * Where is Form Located?
			 */

				// Are we inside the content only
				if(in_the_loop() && is_main_query()){

					if ( (isset($_POST['basic_search']) || !isset($_POST['page'])) && !isset($_GET[srch])) {
						$search_layout = "simple";
					} else {
						$search_layout = "full";
					}

				// Or we are inside the widgets
				} else {

					if ( $profilesearch_layout == "advanced") {
						$search_layout = "full";
					} else {
						$search_layout = "simple";
					}

				}


			/*
			 * Display Form
			 */

				?>
				<!-- RESET BACKUP -->
				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery.fn.rset = function(){
						jQuery(this).on("click",function(){
							var inputs = jQuery(".search-field").find("input[type=text]");
								for (var i = 0; i<inputs.length; i++) {
									switch (inputs[i].type) {
										case 'text':
											inputs[i].value = '';
											break;
										case 'radio':
										case 'checkbox':
											inputs[i].checked = false;
									}
								}
							jQuery(".search-field").find("select").prop('selectedIndex',0);
						});
					}

					jQuery("#rst_btn").rset();

					jQuery.fn.css_ = function(){
						var el = this.get(0); var st; var returns = {};
						if(window.getComputedStyle){
							var camel = function(a,b){return b.toUpperCase();}
							st = window.getComputedStyle(el, null);
							for(var s=0; s < st.length; s++){
								var css_style = st[s];
								var cml = css_style.replace(/\-([a-z])/, camel);
								var vl = st.getPropertyValue(css_style);
								returns[cml] = vl;
							}
							return returns;
						}
						if(el.currentStyle){
							st = el.currentStyle;
							for(var prop in style){ returns[prop] = st[prop];}
							return returns;
						}
						return this.css();
					}
					jQuery("#rst_btn").css(jQuery("#sr_pr").css_());
				});
				</script>
				<?php


			/*
			 * Search Form
			 */

				echo "		<div id=\"profile-search-form-condensed\" class=\"rbsearch-form form-". $search_layout ."\">\n";
				echo "			<form method=\"post\" id=\"search-form-condensed\" action=\"". get_bloginfo("wpurl") ."/profile-search/\">\n";
				echo "				<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
				echo "				<input type=\"hidden\" name=\"mode\" value=\"". $search_layout ."\" />\n";
				echo "				<input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" />\n";

									// Show Profile Name
									if ( ($rb_agency_option_formshow_name > 0) || ($search_layout == "full" && $rb_agency_option_formshow_name > 1) ) {
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileFirstName\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label>\n";
				echo "					<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" />\n";
				echo "				</div>\n";
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileLastName\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label>\n";
				echo "					<input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" />\n";
				echo "				</div>\n";
									}

									// Show Profile Type
									if ( ($rb_agency_option_formshow_type > 0) || ($search_layout == "full" && $rb_agency_option_formshow_type > 1) ) {
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
				echo "					<select name=\"ProfileType\" id=\"ProfileType\">\n";               
				echo "						<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = mysql_query($query);
											while ($dataType = mysql_fetch_array($results2)) {
												if ($_SESSION['ProfileType']) {
													if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
											}
				echo "					</select>\n";
				echo "				</div>\n";
									}

									// Show Profile Gender
									if ( ($rb_agency_option_formshow_gender > 0) || ($search_layout == "full" && $rb_agency_option_formshow_gender > 1) ) {
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
				echo "					<select name=\"ProfileGender\" id=\"ProfileGender\">\n";
				echo "						<option value=\"\">". __("All Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
													$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
													$results2 = mysql_query($query2);
													while ($dataGender = mysql_fetch_array($results2)) {
														echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
													}
				echo "					</select>\n";
				echo "				</div>\n";
									}

									// Show Profile Age
									if ( ($rb_agency_option_formshow_age > 0) || ($search_layout == "full" && $rb_agency_option_formshow_age > 1) ) {
				echo "				<div class=\"search-field single\">\n";
				echo "				  <fieldset class=\"search-field multi\">";
				echo "					<legend>". __("Age", rb_agency_TEXTDOMAIN) . "</legend>";
				echo "					<div>\n";
				echo "						<label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>";
				echo "						<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" />\n";
				echo "					</div>";
				echo "					<div>\n";
				echo "						<label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
				echo "						<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" />\n";
				echo "				  </fieldset>";
				echo "				</div>\n";
									}

									// Show Location Search
									if ( ($rb_agency_option_formshow_location > 0) || ($search_layout == "full" && $rb_agency_option_formshow_location > 1) ) {
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileCity\">". __("City", rb_agency_TEXTDOMAIN) ."</label>\n";
				echo "					<input type=\"text\" id=\"ProfileCity\" name=\"ProfileCity\" value=\"". $_SESSION["ProfileCity"] ."\" />\n";
				echo "				</div>\n";
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileState\">". __("State", rb_agency_TEXTDOMAIN) ."</label>\n";
				echo "					<input type=\"text\" id=\"ProfileState\" name=\"ProfileState\" value=\"". $_SESSION["ProfileState"] ."\" />\n";
				echo "				</div>\n";
				echo "				<div class=\"search-field single\">\n";
				echo "					<label for=\"ProfileZip\">". __("Zip", rb_agency_TEXTDOMAIN) ."</label>\n";
				echo "					<input type=\"text\" id=\"ProfileZip\" name=\"ProfileZip\" value=\"". $_SESSION["ProfileZip"] ."\" />\n";
				echo "				</div>\n";
									}

				/*
				 * Custom Fields
				 */

				// Query Fields
				$field_sql = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomShowSearch FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 ORDER BY ProfileCustomOrder ASC";
				$field_results = mysql_query($field_sql);
				while ($data = mysql_fetch_array($field_results)) { 

					// Set Variables
					$ProfileCustomID = $data['ProfileCustomID'];
					$ProfileCustomTitle = $data['ProfileCustomTitle'];
					$ProfileCustomType = $data['ProfileCustomType'];
					$ProfileCustomOptions = $data['ProfileCustomOptions'];
					$ProfileCustomShowSearch = $data['ProfileCustomShowSearch'];

					// Show this Custom Field on Search
					if($ProfileCustomShowSearch == 1 && ( ($search_layout == "simple" && $ProfileCustomShowSearch) || ($search_layout == "full" && $ProfileCustomShowSearch) ) ){

						/* Field Type 
						 * 1 = Single Line Text
						 * 2 = Min / Max (Depreciated)
						 * 3 = Dropdown
						 * 4 = Textbox
						 * 5 = Checkbox
						 * 6 = Radiobutton
						 * 7 = Metric
						 *     1 = Inches
						 *     2 = Pounds
						 *     3 = Feet/Inches
						 */


						/*
						 * Single Text Line
						 */
						if($ProfileCustomType == 1) {
											echo "<div class=\"search-field single\">";
											echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]."\" />";
											echo "</div>";


						/*
						 * Min Max
						 */
						} elseif($ProfileCustomType == 2) {
											echo "<div class=\"search-field single\">";
											echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($ProfileCustomOptions,"}"),"{"));
											list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);

											if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
												echo "<div>";
												echo "<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
												echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />";
												echo "</div>";
												echo "<div>";
												echo "<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
												echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"". $ProfileCustomOptions_Max_value ."\" />";
												echo "</div>";
											} else {
												echo "<div>";
												echo "<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
												echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".$_REQUEST["ProfileCustomID". $ProfileCustomID]."\" />";
												echo "</div>";
												echo "<div>";
												echo "<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
												echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".$_REQUEST["ProfileCustomID". $ProfileCustomID]."\" />";
												echo "</div>";
											}
											echo "</div>";

						/*
						 * Dropdown
						 */
						} elseif($ProfileCustomType == 3) {
											echo "<div class=\"search-field single\">";
											echo "	<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											echo "	<select name=\"ProfileCustomID". $ProfileCustomID ."\">";
											echo "		<option value=\"\">--</option>";

												$values = explode("|",$ProfileCustomOptions);
												foreach($values as $value){
													// Validate Value
													if(!empty($value)) {
														// Identify Existing Value
														$isSelected = "";
														if($_REQUEST["ProfileCustomID". $ProfileCustomID]==$value){
															$isSelected = "selected=\"selected\"";
															echo "<option value=\"".$value."\" ".$isSelected .">".$value."</option>";
														}else{
															echo "<option value=\"".$value."\" >".$value."</option>"; 
														}
													}
												}
											echo "</select>";
											echo "</div>";


						/*
						 * Textbox
						 */
						} elseif($ProfileCustomType == 4) {
							/*
							TODO: Should we search text inside of text area?
											echo "<div class=\"search-field single\">";
											echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											echo "<textarea name=\"ProfileCustomID". $ProfileCustomID ."\">". $_REQUEST["ProfileCustomID". $ProfileCustomID] ."</textarea>";
											echo "</div>";
							*/

						/*
						 * Checkbox
						 */
						} elseif($ProfileCustomType == 5) {
											echo "<fieldset class=\"search-field multi\">";
											echo "<legend>". $ProfileCustomTitle ."</legend>";

											$array_customOptions_values = explode("|", $ProfileCustomOptions);

											foreach($array_customOptions_values as $val){
												if(isset($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']])){ 

													$dataArr = explode(",",implode(",",explode("','",$_REQUEST["ProfileCustomID". $ProfileCustomID])));
													if(in_array($val,$dataArr,true)){
														echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
														echo "<span>&nbsp;&nbsp;". $val."</span></label>";
													} else {
														if($val !=""){	
															echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
															echo "<span>&nbsp;&nbsp;". $val."</span></label>";	
														}
													}
												} else {
													if($val !=""){	
														echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
														echo "<span>&nbsp;&nbsp;". $val."</span></label>";	
													}
												}
											}

											echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>";
											echo "</fieldset>";

						/*
						 * Radio Button
						 */
						} elseif($ProfileCustomType == 6) {
											echo "<fieldset class=\"search-field multi\">";
											echo "<legend>". $ProfileCustomTitle ."</legend>";
											$array_customOptions_values = explode("|", $ProfileCustomOptions);

											foreach($array_customOptions_values as $val){

												if(isset($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]) && $_REQUEST["ProfileCustomID". $ProfileCustomID] !=""){ 

													$dataArr = explode(",",implode(",",explode("','",$_REQUEST["ProfileCustomID". $ProfileCustomID])));

													if(in_array($val,$dataArr) && $val !=""){
														echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
														echo "<span>&nbsp;&nbsp;". $val."</span></label>";
													}else{
														if($val !=""){
															echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
															echo "<span>&nbsp;&nbsp;". $val."</span></label>";	
														}
													}
												} else {
													if($val !=""){
														echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
														echo "<span>&nbsp;&nbsp;". $val."</span></label>";	
													}
												}
											}
											echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>";	       
											echo "</fieldset>";

						/*
						 * Metric
						 */
						} elseif($ProfileCustomType == 7) {
											echo "<fieldset class=\"search-field multi\">";

											$measurements_label = "";

											// 0 = Metrics(ft/kg)
											if($rb_agency_option_unittype ==0){
												if($ProfileCustomOptions == 1){
													$measurements_label  ="<em> (cm)</em>";
												} elseif($ProfileCustomOptions == 2){
													$measurements_label  ="<em> (kg)</em>";
												} elseif($ProfileCustomOptions == 3){
													$measurements_label  ="<em> (ft/in)</em>";
												}

											//1 = Imperial(in/lb)
											} elseif($rb_agency_option_unittype ==1){
												if($ProfileCustomOptions == 1){
													$measurements_label  ="<em> (in)</em>";
												} elseif($ProfileCustomOptions == 2){
													$measurements_label  ="<em> (lb)</em>";
												} elseif($ProfileCustomOptions == 3){
													$measurements_label  ="<em> (ft/in)</em>";
												}
											}
											echo "<legend>". $ProfileCustomTitle . $measurements_label ."</legend>";

											list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);

												if($ProfileCustomTitle=="Height" && $ProfileCustomID==3){

												echo "<div><label>Min</label><select name=\"ProfileCustomID". $ProfileCustomID ."[]\">\n";
													  if (empty($ProfileCustomValue)) {
														echo "  <option value=\"\">--</option>\n";
													  }
													  // 
													  $i=12;
													  $heightraw = 0;
													  $heightfeet = 0;
													  $heightinch = 0;
													  while($i<=96)  { 
														  $heightraw = $i;
														  $heightfeet = floor($heightraw/12);
														  $heightinch = $heightraw - floor($heightfeet*12);
													  echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
															  $i++;
															}
													  echo " </select></div>\n";
													  
													   echo "<div><label>Max</label><select name=\"ProfileCustomID". $ProfileCustomID ."[]\">\n";
													  if (empty($ProfileCustomValue)) {
														echo "  <option value=\"\">--</option>\n";
													  }
													  // 
													  $i=12;
													  $heightraw = 0;
													  $heightfeet = 0;
													  $heightinch = 0;
													  while($i<=96)  { 
														  $heightraw = $i;
														  $heightfeet = floor($heightraw/12);
														  $heightinch = $heightraw - floor($heightfeet*12);
														echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
															  $i++;
															}
														echo " </select></div>\n";

												} else {
													
													// for other search
													echo "<div><label for=\"ProfileCustomID".$ProfileCustomID
													."_min\">Min</label><input value=\""
													.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
													."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
													.$ProfileCustomID."[]\" /></div>";

													echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
													."_max\">Max</label><input value=\"".$max_val
													."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$ProfileCustomID."[]\" /></div>";
													
												}
										echo "</fieldset>";

						} // End Type

					}

				}


				echo "				<div class=\"search-field submit\">";
				echo "					<input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/'\" />";
				echo "					<input type=\"button\" name=\"search\" id=\"rst_btn\" value=\"". __("Empty Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"clearForm();\" />";
				echo "					<input type=\"submit\" name=\"advanced_search\" value=\"". __("Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/advanced/'\" />";
				echo "				</div>\n";
				echo "			</form>\n";

				echo "			<script>\n";
				echo "			function clearForm(){\n";
				echo "				$('ProfileContactNameFirst').val('');\n";
				echo "				$('ProfileType').val('');\n";
				echo "				$('ProfileGender').val('');\n";
				echo "				$('ProfileContactNameLast').val('');\n";
				echo "				$('ProfileDateBirth_min').val('');\n";
				echo "				$('ProfileDateBirth_max').val('');\n";
				echo "			}\n";
				echo "			</script>\n";
				echo "		</div>\n";
		}


	/*
	 * Search: Process Form Submission
	 * Process Search conerting requests to an array of search terms
	 */

		public static function search_process(){

				// Initialize
				$filterArray = array();

				/*
				 * Data Handling
				 */

					// Sort By
					if (isset($_REQUEST['sort']) && !empty($_REQUEST['sort'])){
						$filterArray['sort'] = $_REQUEST['sort'];
					} else {
						$filterArray['sort'] = "profile.ProfileContactNameFirst";
					}

					// Sort Order
					if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])){
						$filterArray['dir'] = $_REQUEST['dir'];
					} else {
						$filterArray['dir'] = "asc";
					}

					// Limit total records returned
					if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])){
						$filterArray['limit'] = $_REQUEST['limit'];
					} else {
						$filterArray['limit'] = 100;
					}

					// Records Per Page
					if (isset($_REQUEST['perpage']) && !empty($_REQUEST['perpage'])){
						$filterArray['perpage'] = $_REQUEST['perpage'];
					} else {
						$filterArray['perpage'] = 20;
					}

					// Page
					if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])){
						$filterArray['page'] = $_REQUEST['page'];
					} else {
						$filterArray['page'] = 1;
					}

					// Override Privacy
					if (isset($_REQUEST['override_privacy']) && !empty($_REQUEST['override_privacy'])){
						$filterArray['override_privacy'] = $_REQUEST['override_privacy'];
					}


				/*
				 * Specific
				 */

					// Specific User ID
					if (isset($_REQUEST['ProfileID']) && !empty($_REQUEST['ProfileID'])){
						$filterArray['id'] = $_REQUEST['ProfileID'];
					}

					// Name
					if ((isset($_REQUEST['ProfileContactNameFirst']) && !empty($_REQUEST['ProfileContactNameFirst'])) || isset($_REQUEST['ProfileContactNameLast']) && !empty($_REQUEST['ProfileContactNameLast'])){
						if (isset($_REQUEST['ProfileContactNameFirst']) && !empty($_REQUEST['ProfileContactNameFirst'])){
							$filterArray['namefirst'] = $_REQUEST['ProfileContactNameFirst'];
						}
						if (isset($_REQUEST['ProfileContactNameLast']) && !empty($_REQUEST['ProfileContactNameLast'])){
							$filterArray['namelast'] = $_REQUEST['ProfileContactNameLast'];
						}
					}


				/*
				 * General
				 */

					// Type
					if (isset($_REQUEST['ProfileType']) && !empty($_REQUEST['ProfileType'])){
						$filterArray['type'] = $_REQUEST['ProfileType'];
					}

					// Gender
					if (isset($_REQUEST['ProfileGender']) && !empty($_REQUEST['ProfileGender'])){
						$filterArray['gender'] = $_REQUEST['ProfileGender'];
					}

					// Age by Date
					if (isset($_REQUEST['ProfileDateBirth_min']) && !empty($_REQUEST['ProfileDateBirth_min'])){
						$filterArray['datebirth_min'] = $_REQUEST['ProfileDateBirth_min'];
					}
					if (isset($_REQUEST['ProfileDateBirth_max']) && !empty($_REQUEST['ProfileDateBirth_max'])){
						$filterArray['datebirth_max'] = $_REQUEST['ProfileDateBirth_max'];
					}

					// Age by Number
					if (isset($_REQUEST['age_min']) && !empty($_REQUEST['age_min'])){
						$filterArray['age_min'] = $_REQUEST['age_min'];
					}
					if (isset($_REQUEST['age_max']) && !empty($_REQUEST['age_max'])){
						$filterArray['age_max'] = $_REQUEST['age_max'];
					}

				/*
				 * Location
				 */

					// Location
					if (isset($_REQUEST['ProfileLocationCity']) && !empty($_REQUEST['ProfileLocationCity'])){
						$filterArray['city'] = $_REQUEST['ProfileLocationCity'];
					}
					// City
					if (isset($_REQUEST['ProfileCity']) && !empty($_REQUEST['ProfileCity'])){
						$filterArray['city'] = $_REQUEST['ProfileCity'];
					}		

					// State
					if (isset($_REQUEST['ProfileState']) && !empty($_REQUEST['ProfileState'])){
						$filterArray['state'] = $_REQUEST['ProfileState'];
					}

					// ZIP
					if (isset($_REQUEST['ProfileZip']) && !empty($_REQUEST['ProfileZip'])){
						$filterArray['zip'] = $_REQUEST['ProfileZip'];
					}

					// Country
					if (isset($_REQUEST['ProfileCountry']) && !empty($_REQUEST['ProfileCountry'])){
						$filterArray['country'] = $_REQUEST['ProfileCountry'];
					}

				/*
				 * Custom Fields
				 */

					// Custom Fields
					foreach($_POST as $key =>$val){
						// Loop through all posts to find custom fields
						if(substr($key,0,15)=="ProfileCustomID"){
							// Determine if the value is an array
							if(is_array($val)){
								if(count($val)>1){
									// Convert to String
									$imploded = implode(",",$val);
									// Remove trailing comma
									$imploded = rtrim($imploded, ",");
									// Is there any value left?
									if(isset($imploded) && !empty($imploded)){
										$filterArray[$key] = $imploded;
									}
								} else {
									// Remove empty keys
									if(isset($val) && !empty($val)){
										$filterArray[$key] = $val;
									}
								}
							} else {
								if(isset($val) && !empty($val)){
									$filterArray[$key] = $val;
								}
							}
						}
					}

				// Active
				if (isset($_REQUEST['ProfileIsActive'])){
					$filterArray['isactive'] = $_REQUEST['ProfileIsActive'];
				}

			return $filterArray;

		}


	/*
	 * Search: Prepare SQL String
	 * Process Search converting array to HTML
	 */

		public static function search_generate_sqlwhere($atts){

			$rb_agency_options_arr = get_option('rb_agency_options');
				// Time Zone
				$rb_agency_option_locationtimezone = $rb_agency_options_arr['rb_agency_option_locationtimezone'];

			// Convert Input
			if(is_array($atts)) {

			/*
			 * Get Search Chriteria
			 */

				// Exctract from Shortcode
				extract(shortcode_atts(array(

					// Specific
					"id" => NULL,
					"namefirst" => NULL,
					"namelast" => NULL,
					// General
					"type" => NULL,
					"gender" => NULL,
					"datebirth_min" => NULL,
					"datebirth_max" => NULL,
					"age_start" => NULL,
					"age_stop" => NULL,
					// Location
					"city" => NULL,
					"state" => NULL,
					"zip" => NULL,
					"country" => NULL,
					// REmove
					"castingcart" => NULL,
					"getprofile_saved" => NULL,
					// Distinction
					"promoted" => NULL,
					"featured" => NULL,
					// ?
					"stars" => NULL,
					"favorite" => NULL,
					// Handling
					"sort" => NULL,
					"dir" => NULL,
					"limit" => NULL,
					"perpage" => NULL,
					"page" => NULL,
					"override_privacy" => NULL
				), $atts));

			/*
			 * WHERE
			 */

				// Option to show all profiles
				if (isset($override_privacy)) {
					// If sent link, show both hidden and visible
					$filter = "profile.ProfileIsActive IN (1, 4)";
				} else {
					$filter = "profile.ProfileIsActive = 1";
				}

				// ID
				if (isset($id) && !empty($id)){
					$filter .= " AND profile.ProfileID = '". $id ."%'";
				}

				// First Name
				if (isset($namefirst) && !empty($namefirst)){
					$filter .= " AND profile.ProfileContactNameFirst LIKE '". $namefirst ."%'";
				}

				// Last Name
				if (isset($namelast) && !empty($namelast)){
					$filter .= " AND profile.ProfileContactNameLast LIKE '". $namelast ."%'";
				}

				// Type
				if (isset($type) && !empty($type)){
					$filter .= " AND FIND_IN_SET(". $type .", profile.ProfileType) ";
				}

				// Gender
				if (isset($gender) && !empty($gender)){
					$filter .= " AND profile.ProfileGender='".$gender."'";
				}

				// Age
				$date = gmdate('Y-m-d', time() + $rb_agency_option_locationtimezone *60 *60);
				if (isset($datebirth_min) && !empty($datebirth_min)){
					$minyear = date('Y-m-d', strtotime('-'. $datebirth_min .' year'. $date));
					$filter .= " AND profile.ProfileDateBirth <= '$minyear'";
				}
				if (isset($datebirth_max) && !empty($datebirth_max)){
					$maxyear = date('Y-m-d', strtotime('-'. $datebirth_max - 1 .' year'. $date));
					$filter .= " AND profile.ProfileDateBirth >= '$maxyear'";
				}

				// TODO ADD Age number

				// City
				if (isset($city) && !empty($city)){
					$filter .= " AND profile.ProfileLocationCity = '". ucfirst($city) ."'";
				}

				// State
				if (isset($state) && !empty($state)){
					$filter .= " AND profile.ProfileLocationState = '". ucfirst($state) ."'";
				}

				// Zip
				if (isset($zip) && !empty($zip)){
					$filter .= " AND profile.ProfileLocationZip = '". ucfirst($zip) ."'";
				}

				// Country
				if (isset($country) && !empty($country)){
					$filter .= " AND profile.ProfileLocationCountry = '". ucfirst($country) ."'";
				}


				// Profile Search Saved 
				if(isset($GetProfileSaved) && !empty($GetProfileSaved)){
					$filter .= " AND profile.ProfileID IN(".$GetProfileSaved.") ";
				}


				if (isset($featured)){
					$filter .= " AND profile.ProfileIsFeatured = '1' ";
				}
				if (isset($promoted)){
					$filter .= " AND profile.ProfileIsPromoted = '1' ";
				}

				// Set CustomFields search
				if(isset($atts) && !empty($atts)){
					$filter .= recreate_custom_search($atts);
				}

			/*
			 * ORDER BY
			 */

				if (isset($sort)){
					$filter .= " ORDER BY $sort $desc ";
				}

			/*
			 * LIMIT
			 */

				if (isset($sort)){
					$filter .= " LIMIT 0, $limit ";
				}

				return $filter;

			} else {
				// Empty Search
				return false;
			}

		}

	/*
	 * Search: Results as Profile IDs
	 * Process Search and return Profile IDs
	 */

		public static function search_results($sql_where, $query_type = 0){

			switch ($query_type) {

				// Standard
				case 0:
					$sql = "SELECT profile.ProfileID FROM ". table_agency_profile ." profile WHERE ". $sql_where;
					//break;

				// Execute query showing favorites
			}

			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			if ($count > 0){

				while ($profile = mysql_fetch_array($results)) {
					$string .= $profile["ProfileID"] .",";
				}
			} else {
				$string = 0;

			}

			return rtrim($string, ",");

		}



	/*
	 * Format Profile
	 * Create list from IDs
	 */

		public static function search_formatted($ids){

			/*
			 * Execute the Query
			 */
				// Execute Query   removed profile.*,
				$queryList = "
				SELECT 
					profile.ProfileID,
					profile.ProfileGallery,
					profile.ProfileContactDisplay, 
					profile.ProfileDateBirth, 
					profile.ProfileDateCreated,
					profile.ProfileLocationState
				FROM ". table_agency_profile ." profile 
				WHERE profile.ProfileID IN ($ids)
				GROUP BY profile.ProfileID";
				$results = mysql_query($queryList);
				$count = mysql_num_rows($results);
				if ($count > 0){
					while ($profile = mysql_fetch_array($results)) {
						$string .= "Name: ". $profile["ProfileContactDisplay"];
					}
				} else {
					$string = "Nobody profiles returned";
				}

				echo $string;

		}


}

?>