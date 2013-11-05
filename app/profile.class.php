<?php
class RBAgency_Profile {

	/*
	 * debug options
	 */
        protected static $error_debug = false;
	protected static $error_debug_query = false;
	protected static $error_checking = array();
	
	/*
	 * order by storage field
	 */
        protected static $order_by ='';


               /*
                * Search Form
                * Process Search
                */
		public static function search_form($atts = "", $args = "", $type = 0){

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

				// Which Type?
				if ($type == 1) {

					// Admin Back-end 
					$rb_agency_searchurl = admin_url("admin.php?page=". $_GET['page']);
					$search_layout = "admin";

				} else {

					// Front Back-end
					$rb_agency_searchurl = get_bloginfo("wpurl") ."/search-results/";
					if ( (get_query_var("type") == "search-basic") ){
							$search_layout = "simple";
					} elseif ( (get_query_var("type") == "search-advanced") ){
							$search_layout = "full";
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
								for (var i = 0; i < inputs.length; i++) {
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

					<?php if(!is_admin()){ ?>
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
					<?php } ?>
				});
				</script>
				<?php 


			/*
			* Search Form
			*/

				echo "		<div id=\"profile-search-form-condensed\" class=\"rbsearch-form form-". $search_layout ."\">\n";
				echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". $rb_agency_searchurl ."\">\n";
				echo "				<input type=\"hidden\" name=\"form_action\" value=\"search_profiles\" />\n";
				echo "				<input type=\"hidden\" name=\"form_mode\" value=\"". $search_layout ."\" />\n";

				// Show Profile Name
				if ( ($rb_agency_option_formshow_name > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_name > 1) ) {
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"namefirst\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<input type=\"text\" id=\"namefirst\" name=\"namefirst\" value=\"". $_SESSION["namefirst"] ."\" />\n";
						echo "				</div>\n";
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"namelast\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<input type=\"text\" id=\"namelast\" name=\"namelast\" value=\"". $_SESSION["namelast"] ."\" />\n";
						echo "				</div>\n";
				}

				// Show Profile Type
				if ( ($rb_agency_option_formshow_type > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_type > 1) ) {
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"type\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "					<select name=\"profiletype\" id=\"type\">\n";               
						echo "						<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
														$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
														$results2 = mysql_query($query);
														while ($dataType = mysql_fetch_array($results2)) {
																if ($_SESSION['type']) {
																		if ($dataType["DataTypeID"] ==  $_SESSION['type']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
																} else { $selectedvalue = ""; }
																echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
														}
						echo "					</select>\n";
						echo "				</div>\n";
				}

				// Show Profile Gender
				if ( ($rb_agency_option_formshow_gender > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_gender > 1) ) {
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"gender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "					<select name=\"gender\" id=\"gender\">\n";
						echo "						<option value=\"\">". __("All Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
														// Pul Genders from Database
														$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
														$results2 = mysql_query($query2);
														while ($dataGender = mysql_fetch_array($results2)) {
																echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['gender'],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
														}
						echo "					</select>\n";
						echo "				</div>\n";
				}

				// Show Profile Age
				if ( ($rb_agency_option_formshow_age > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_age > 1) ) {
						echo "				<div class=\"search-field single\">\n";
						echo "				  <fieldset class=\"search-field multi\">";
						echo "					<legend>". __("Age", rb_agency_TEXTDOMAIN) . "</legend>";
						echo "					<div>\n";
						echo "						<label for=\"datebirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>";
						echo "						<input type=\"text\" class=\"stubby\" id=\"datebirth_min\" name=\"datebirth_min\" value=\"". $_SESSION['datebirth_min'] ."\" />\n";
						echo "					</div>";
						echo "					<div>\n";
						echo "						<label for=\"datebirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "						<input type=\"text\" class=\"stubby\" id=\"datebirth_max\" name=\"datebirth_max\" value=\"". $_SESSION['datebirth_max'] ."\" />\n";
						echo "				  </fieldset>";
						echo "				</div>\n";
				}

				// Show Location Search
				if ( ($rb_agency_option_formshow_location > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_location > 1) ) {
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"city\">". __("City", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<input type=\"text\" id=\"city\" name=\"city\" value=\"". $_SESSION["city"] ."\" />\n";
						echo "				</div>\n";

						echo "				<div class=\"search-field single\">\n";
																		$location= site_url();
						echo '					<input type="hidden" id="url" value="'.$location.'">';
						echo "					<label for=\"country\">". __("Country", rb_agency_TEXTDOMAIN) ."</label>\n";
																		$query_get ="SELECT * FROM `".table_agency_data_country."`" ;
																		$result_query_get = $wpdb->get_results($query_get);
						echo "					<select name=\"country\" id=\"country\" onchange='javascript:populateStates(\"country\",\"state\");'>";
						echo '					<option value="">'. __("Select country", rb_agency_TEXTDOMAIN) .'</option>';
																		foreach($result_query_get as $r){
																				$selected =$_SESSION["country"]==$r->CountryID?"selected=selected":"";
						echo '						<option '.$selected.' value='.$r->CountryID.' >'.$r->CountryTitle.'</option>';
																		}
						echo '					</select>';
						echo "				</div>\n";

						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"state\">". __("State", rb_agency_TEXTDOMAIN) ."</label>\n";
																		//echo "					<input type=\"text\" id=\"state\" name=\"state\" value=\"". $_SESSION["state"] ."\" />\n";
																		$query_get ="SELECT * FROM `".table_agency_data_state."`" ;
																		$result_query_get = $wpdb->get_results($query_get);
						echo '						<select name="state" id="state">';
						echo '					<option value="">'. __("Select state", rb_agency_TEXTDOMAIN) .'</option>';
																		foreach($result_query_get as $r){
																				$selected =$_SESSION["state"]==$r->StateID?"selected=selected":"";
						echo '					<option '.$selected.' value='.$r->StateID.' >'.$r->StateTitle.'</option>';
																		}
						echo '					</select>';
						echo "				</div>\n";

						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"zip\">". __("Zip", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<input type=\"text\" id=\"zip\" name=\"zip\" value=\"". $_SESSION["zip"] ."\" />\n";
						echo "				</div>\n";
				} // Show Location Search
						
						//status
						echo "				<div class=\"search-field single\">\n";
						echo "					<label for=\"state\">". __("Status", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "				        <select name=\"isactive\" id=\"ProfileIsActive\">\n";               
						echo "							<option value=\"\">". __("Any Status", rb_agency_TEXTDOMAIN) . "</option>\n";
						echo "							<option value=\"1\"". selected($_SESSION['ProfileIsActive'], 1) .">". __("Active", rb_agency_TEXTDOMAIN) . "</option>\n";
						echo "							<option value=\"4\"". selected($_SESSION['ProfileIsActive'], 4) .">". __("Not Visible", rb_agency_TEXTDOMAIN) . "</option>\n";
						echo "							<option value=\"0\"". selected($_SESSION['ProfileIsActive'], 0) .">". __("Inactive", rb_agency_TEXTDOMAIN) . "</option>\n";
						echo "							<option value=\"2\"". selected($_SESSION['ProfileIsActive'], 2) .">". __("Archived", rb_agency_TEXTDOMAIN) . "</option>\n";
						echo "				        	</select>\n";
						echo "				    </div>\n";

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
					if( $search_layout == "admin" || 
						($ProfileCustomShowSearch == 1 && $search_layout == "full" || 
						(isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) )){

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
								//Commentd to fix language value populate
								//echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" />";
								echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".
								$_SESSION["ProfileCustomID".$ProfileCustomID]."\" />";
								echo "</div>";

						/*
						 * Min Max
						 */
						} elseif($ProfileCustomType == 2) {

								echo "<div class=\"search-field single\">";
								echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
								$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($ProfileCustomOptions,"}"),"{"));
								list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
								//print_r($_SESSION["ProfileCustomID".$ProfileCustomID]);
							if(is_array($_SESSION["ProfileCustomID".$ProfileCustomID])){
								$_SESSION["ProfileCustomID".$ProfileCustomID]=@implode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);
								list($min_val2,$max_val2) =  @explode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);
							} else {
								list($min_val2,$max_val2) =  @explode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);
							}

							if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
								echo "<div>";
								echo "		<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
								echo "		<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Min_value ."\" />";
								echo "</div>";
								echo "<div>";
								echo "		<label for=\"ProfileCustomLabel_max\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
								echo "		<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Max_value ."\" />";
								echo "</div>";
							} else {
								echo "<div>";
								echo "		<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
								echo "		<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$min_val2."\" />";
								echo "</div>";
								echo "<div>";
								echo "		<label for=\"ProfileCustomLabel_max\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
								echo "		<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$max_val2."\" />";
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
												echo "		<option value=\"".$value."\" ".$isSelected .">".$value."</option>";
											}else{
												echo "		<option value=\"".$value."\" >".$value."</option>"; 
											}
										}
									}
								echo "	</select>";
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

									if(isset($_SESSION["ProfileCustomID". $ProfileCustomID])){ 

										$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $ProfileCustomID])));
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

									if(isset($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $ProfileCustomID] !=""){ 

										$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $ProfileCustomID])));

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
								if(is_array($_SESSION["ProfileCustomID".$ProfileCustomID])){
									$_SESSION["ProfileCustomID".$ProfileCustomID]=@implode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);
									}
								list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);

									if($ProfileCustomTitle=="Height" && $data['ProfileCustomOptions']==3){

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
										echo " </select>\n";
										echo "</div>\n";

									} else {

										// for other search
										echo "<div><label for=\"ProfileCustomID".$ProfileCustomID."_min\">Min</label><input value=\""
										.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
										."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
										.$ProfileCustomID."[]\" /></div>";

										echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
										."_max\">Max</label><input value=\"".$max_val ."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$ProfileCustomID."[]\" /></div>";

									}
							echo "</fieldset>";

						} // End Type

					}

				}


				echo "				<div class=\"search-field submit\">";
				echo "					<input type=\"submit\" name=\"search_profiles\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='". $rb_agency_searchurl ."\" />";
				echo "					<input type=\"button\" id=\"rst_btn\" value=\"". __("Empty Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"clearForm();\" />";
				if ( (get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ){
				echo "					<input type=\"button\" name=\"advanced_search\" value=\"". __("Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
				} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) ){
				echo "					<input type=\"button\" name=\"advanced_search\" value=\"". __("Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
				}
				echo "				</div>\n";
				echo "			</form>\n";

				echo "			<script>\n";
				echo "			function clearForm(){\n";
				echo "				$('namefirst').val('');\n";
				echo "				$('namelast').val('');\n";
				echo "				$('type').val('');\n";
				echo "				$('gender').val('');\n";
				echo "				$('datebirth_min').val('');\n";
				echo "				$('datebirth_max').val('');\n";
				echo "			}\n";
				echo "			</script>\n";
				echo "		</div>\n";
		}


                /*
                * Search: Process Form Submission
                * Process Search converting requests to an array of search terms
                */
		public static function search_process(){


			/*
			 * Set Session
			 */

				// Convert Requests to Sessions
				foreach ($_REQUEST as $key => $value) {
					// Clear old values
					unset($_SESSION[$key]);
					
					// Set the new value
					if (isset($value) && !empty($value)) {
						$_SESSION[$key] = $value; //$$key = $value;
					}
				}

			/*
			 * Create Array
			 */

			// Check if a new search is posted
			if ($_REQUEST["form_action"] == "search_profiles") {

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

					// Override Privacy or Show in Admin Mode
					if ( (isset($_REQUEST['override_privacy']) && !empty($_REQUEST['override_privacy'])) || $_REQUEST['mode'] == "admin") {
						$filterArray['override_privacy'] = 1;
					}


				/*
				 * Specific
				 */

					// Specific User ID
					if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
						$filterArray['id'] = $_REQUEST['id'];
					}

					// Name
					if ((isset($_REQUEST['namefirst']) && !empty($_REQUEST['namefirst'])) || isset($_REQUEST['namelast']) && !empty($_REQUEST['namelast'])){
						if (isset($_REQUEST['namefirst']) && !empty($_REQUEST['namefirst'])){
							$filterArray['namefirst'] = $_REQUEST['namefirst'];
						}
						if (isset($_REQUEST['namelast']) && !empty($_REQUEST['namelast'])){
							$filterArray['namelast'] = $_REQUEST['namelast'];
						}
					}


				/*
				 * General
				 */

					// Type
					if (isset($_REQUEST['profiletype']) && !empty($_REQUEST['profiletype'])){
						$filterArray['profiletype'] = $_REQUEST['profiletype'];
					}

					// Gender
					if (isset($_REQUEST['gender']) && !empty($_REQUEST['gender'])){
						$filterArray['gender'] = $_REQUEST['gender'];
					}

					// Age by Date
					if (isset($_REQUEST['datebirth_min']) && !empty($_REQUEST['datebirth_min'])){
						$filterArray['datebirth_min'] = $_REQUEST['datebirth_min'];
					}
					if (isset($_REQUEST['datebirth_max']) && !empty($_REQUEST['datebirth_max'])){
						$filterArray['datebirth_max'] = $_REQUEST['datebirth_max'];
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

					// City
					if (isset($_REQUEST['city']) && !empty($_REQUEST['city'])){
						$filterArray['city'] = $_REQUEST['city'];
					}		

					// State
					if (isset($_REQUEST['state']) && !empty($_REQUEST['state'])){
						$filterArray['state'] = $_REQUEST['state'];
					}

					// ZIP
					if (isset($_REQUEST['zip']) && !empty($_REQUEST['zip'])){
						$filterArray['zip'] = $_REQUEST['zip'];
					}

					// Country
					if (isset($_REQUEST['country']) && !empty($_REQUEST['country'])){
						$filterArray['country'] = $_REQUEST['country'];
					}

					// Active
					if (isset($_REQUEST['isactive'])){
						$filterArray['isactive'] = $_REQUEST['isactive'];
					}

			// Debug
			if(self::$error_debug){
				self::$error_checking[] = array('search_process',$filterArray);
				var_dump(self::$error_checking);
			}
				return $filterArray;
			} // If no post, ignore.

		}


                /*
                * Search: Prepare WHERE SQL String
                * Process values into SQL string holding WHERE clause
                */
		public static function search_generate_sqlwhere($atts, $exclude){

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
					"profiletype" => NULL,
					"gender" => NULL,
					"datebirth_min" => NULL,
					"datebirth_max" => NULL,
					"age_min" => NULL,
					"age_max" => NULL,
					// Location
					"city" => NULL,
					"state" => NULL,
					"zip" => NULL,
					"country" => NULL,
					// Casting Cart
					"include" => NULL,
					// Distinction
					"promoted" => NULL,
					"featured" => NULL,
					"isactive" => NULL,
					
					// ?
					"stars" => NULL,
					"favorite" => NULL,
					"override_privacy" => NULL
				), $atts));

			/*
			 * WHERE
			 */

				// Option to show all profiles
				if (isset($override_privacy) && !empty($override_privacy)) {
					// If sent link, show both hidden and visible
					$filter = "profile.ProfileIsActive IN (1, 4)";
				} else {
					if (isset($isactive) && $isactive != ''){
						$filter = "profile.ProfileIsActive = " . $isactive;
					} else {
						$filter = "profile.ProfileIsActive = 1 ";
					}
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
				if (isset($profiletype) && !empty($profiletype)){
					$filter .= " AND FIND_IN_SET(". $type .", profile.ProfileType) ";
				}

				// Gender
				if (isset($gender) && !empty($gender)){
					$filter .= " AND profile.ProfileGender='".$gender."'";
				}

				// Age
				$date = gmdate('Y-m-d', time() + $rb_agency_option_locationtimezone *60 *60);
				/* 
				$timezone_offset = -10; // Hawaii Time
				$dateInMonth = gmdate('d', time() + $timezone_offset *60 *60);
				$format = 'Y-m-d';
				$date = gmdate($format, time() + $timezone_offset *60 *60);
				*/
				if (isset($datebirth_min) && !empty($datebirth_min)){
					$minyear = date('Y-m-d', strtotime('-'. $datebirth_min .' year'. $date));
					$filter .= " AND profile.ProfileDateBirth <= '$minyear'";
				}
				if (isset($datebirth_max) && !empty($datebirth_max)){
					$maxyear = date('Y-m-d', strtotime('-'. $datebirth_max - 1 .' year'. $date));
					$filter .= " AND profile.ProfileDateBirth >= '$maxyear'";
				}

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

				if (isset($featured)){
					$filter .= " AND profile.ProfileIsFeatured = '1' ";
				}
				if (isset($promoted)){
					$filter .= " AND profile.ProfileIsPromoted = '1' ";
				}

				// Set CustomFields search
				if(isset($atts) && !empty($atts)){

				/*
				 *  Custom Fields
				 */
					$filterDropdown = array();
					$filter2 = "";

					// Loop through all attributes looking for custom
					foreach ($_POST as $key => $val) {
						if (substr($key,0,15) == "ProfileCustomID") {

						/*
						 *  Check if this is array or not because sometimes $val is an array so
						 *  array_filter is not applicable
						 */
							if ((!empty($val) AND !is_array($val)) OR (is_array($val) AND count(array_filter($val)) > 0)) {

								/*
								 * Id like to chop this one out and extract
								 * the array values from here and make it a string with "," or
								 * pass the single value back $val
								 */
								if(is_array($val)){
									if(count(array_filter($val)) > 1) {
										$ct =1;
										foreach($val as $v){
											if($ct == 1){
												$val = $v;
												$ct++;
											} else {
												$val = $val .",".$v;
											}
										}
									} else {
										$val = array_shift(array_values($val));
									} 
								}

								$q = mysql_query("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = '".substr($key,15)."' ");
								$ProfileCustomType = mysql_fetch_assoc($q);

								/*
								 * Have created a holder $filter2 and
								 * create its own filter here and change
								 * AND should be OR
								 */

								/******************
								  1 - Text
								  2 - Min-Max > Removed
								  3 - Dropdown
								  4 - Textbox
								  5 - Checkbox
								  6 - Radiobutton
								  7 - Metrics/Imperials
								 *********************/

								$open_st = ' AND EXISTS (SELECT * FROM '. table_agency_customfield_mux . ' WHERE ' ;
								$close_st = ' AND ProfileCustomID = '.substr($key,15).' AND ProfileID = profile.ProfileID ) ';

								if ($ProfileCustomType["ProfileCustomType"] == 1) {
									// Text
									$filter2 .= "$open_st ProfileCustomValue = '".$val."' $close_st";
									$_SESSION[$key] = $val;

								} elseif ($ProfileCustomType["ProfileCustomType"] == 3) {
									// Dropdown
									$filter2 .="$open_st ProfileCustomValue = '".$val."' $close_st";

								} elseif ($ProfileCustomType["ProfileCustomType"] == 4) {
									// Textarea
									$filter2 .= "$open_st ProfileCustomValue LIKE ('%".$val."%') $close_st";
									$_SESSION[$key] = $val;

								} elseif ($ProfileCustomType["ProfileCustomType"] == 5) {
									//Checkbox

									if(!empty($val)){

										if(strpos($val,",") === false){
											$filter2 .= "$open_st ProfileCustomValue LIKE '%".$val."%' $close_st";

										} else {
											
											$likequery = explode(",", $val);
											echo $likedata4= "(ProfileCustomValue LIKE  '%".$val."%')";
											$likecounter = count($likequery);
											$i=1; 

											$likedata = "" ;

											// for profiles with multiple values
											$likedata2 = "" ;
											$likedata3 = "" ;

											foreach($likequery as $like){

												if($i != $likecounter){
													if($like!="") {

													$likedata.= " ProfileCustomValue ='".$like."' OR "  ;
														$likedata2.= " (ProfileCustomValue LIKE ',".$like."%' OR ProfileCustomValue LIKE '%".$like.",%') OR "  ;
														$likedata3.= " (ProfileCustomValue LIKE '%,".$like."%,' OR ProfileCustomValue NOT LIKE '%".$like."-%' OR ProfileCustomValue NOT LIKE '%".$like." Month%') OR "  ;
													}
												} else {
													if($like!=""){
														$likedata.= " ProfileCustomValue ='".$like."' "  ;
														$likedata2.= " (ProfileCustomValue LIKE ',".$like."%' OR ProfileCustomValue LIKE '%".$like.",%') ";
														$likedata3.= " (ProfileCustomValue LIKE '%,".$like.",%' OR ProfileCustomValue NOT LIKE '%".$like."-%' OR ProfileCustomValue NOT LIKE '%".$like." Month%') "  ;
													}
												}
												$i++;

											}
											//Commented to fix checkbox issue
											//$val = substr($val, 0, -1);
											$sr_data = $likedata . " OR " . $likedata2 . " OR " . $likedata3 ;
											$filter2 .= "$open_st (".$sr_data.") AND ".$likedata4." $close_st";

										}

										$_SESSION[$key] = $val;
									} else {
										$_SESSION[$key] = "";
									}

								} elseif ($ProfileCustomType["ProfileCustomType"] == 6) {
									//Radiobutton 
									$val = implode("','",explode(",",$val));
									$filter2 .= "$open_st ProfileCustomValue LIKE ('%".$val."%') $close_st";
									$_SESSION[$key] = $val;

								} elseif ($ProfileCustomType["ProfileCustomType"] == 7) {
									//Measurements 
									list($Min_val,$Max_val) = explode(",",$val);
									if( (isset($Min_val) && !empty($Min_val)) && (isset($Max_val) && !empty($Max_val)) ) {
										if(!is_numeric($Min_val)){
											$filter2 .= "$open_st ProfileCustomValue >= '".$Min_val."' AND";
										} else {
											$filter2 .= "$open_st ProfileCustomValue >= ".$Min_val." AND";
										}

										if(!is_numeric($Max_val)){
											$filter2 .= "  ProfileCustomValue <= '".$Max_val."' $close_st";
										} else {
											$filter2 .= "  ProfileCustomValue <= ".$Max_val." $close_st";
										}

										$_SESSION[$key] = $val;
									}
								}

								mysql_free_result($q);
							} // if not empty
						} // end if
					} // end for each

					if(count($filterDropdown) > 0){
						$filter2 .="$open_st ProfileCustomValue IN ('".implode("','",$filterDropdown)."') $close_st";
					}

					$filter .= $filter2;
				}

                                /**
                                * Only Show from Casting Cart
                                */

				// Profile Search Saved 
				if(isset($include) && !empty($include)){
					$filter .= " AND profile.ProfileID IN (".$include.") ";
				}

                                /**
                                * Filter Models Already in Cart
                                */

				// Pull Profiles in Cart
				if (isset($exclude) && !empty($exclude)) {
					//$cartString = implode(",", $exclude);
					$filter .= " AND profile.ProfileID NOT IN (". $exclude .")";
				}

				// Debug
				if(self::$error_debug){
					self::$error_checking[] = array('search_generate_sqlwhere',$filter);
					var_dump(self::$error_checking);
				}
				
				self::search_generate_sqlorder($atts);
				
				return $filter;

			} else {
				// Empty Search
				return false;
			}

		}


                /*
                * Search: Prepare ORDER SQL String
                * Process values into SQL string holding ORDER clause
                */
		public static function search_generate_sqlorder($atts){

			// Convert Input
			if(is_array($atts)) {

			/*
			 * Get Search Chriteria
			 */

				// Exctract from Shortcode
				extract(shortcode_atts(array(

					// Handling
					"sort" => NULL,
					"dir" => NULL,
					"limit" => NULL,
					"perpage" => NULL,
					"page" => NULL
				), $atts));

			/*
			 * ORDER BY
			 */

				if (isset($sort) && !empty($sort)){
					$filter .= " ORDER BY $sort ";
				}

			/*
			 * LIMIT
			 */

				if (isset($limit) && !empty($limit)){
					$filter .= " LIMIT 0, $limit ";
				}

				// Debug
				if(self::$error_debug){
					self::$error_checking[] = array('search_generate_sqlorder',$filter);
					var_dump(self::$error_checking);
				}
				self::$order_by = $filter;

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

				/* 
				 * standard query
				 */
				case 0:
					$sql = "SELECT * FROM ". table_agency_profile ." profile WHERE ". $sql_where . self::$order_by;
					break;

				/* 
				 * query for favorites
				 */
				 case 1:
					$sqlFavorite_userID  = " fav.SavedFavoriteTalentID = profile.ProfileID  AND fav.SavedFavoriteProfileID = '".rb_agency_get_current_userid()."' ";
					$sql = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID, fav.SavedFavoriteTalentID, fav.SavedFavoriteProfileID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE " . $sql_where . " AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite." fav WHERE $sqlFavorite_userID AND profile.ProfileIsActive = 1 GROUP BY fav.SavedFavoriteTalentID"  . self::$order_by;
					break;
			}

			if(self::$error_debug || self::$error_debug_query){		
				self::$error_checking[] = array('-MAIN_QUERY-',$sql);
				var_dump(self::$error_checking);
			}
			
			/*
			 * check if search is admin or public
			 */
			 if(is_admin()){
				return self::search_result_admin($sql);
			 } else {
				return self::search_result_public($sql);
			 }

		}

               	/* 
		 * search for public 
		 */
		public static function search_result_public($sql){

			global $wpdb;
			/* 
			 * format profile list per profile
			 */
			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			$profile_list = "";
			$all_html = "";
			if ($count > 0){
				while ($profile = mysql_fetch_array($results)) {
					$profile_list .= self::search_formatted($profile);
				}

				if(self::$error_debug){
					self::$error_checking[] = array('search_formatted','success');
					var_dump(self::$error_checking);
				}

				/* 
				 * rb agency options
				 */
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_profilelist_count  = isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
				$rb_agency_option_profilelist_favorite  = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;

				/* 
				 * this is the upper header html of the profile list
				 */
				$all_html =  "<script type='text/javascript' src='".rb_agency_BASEDIR."js/resize.js'></script>";
				$all_html .= '<div id="profile-results-info">';
				if ($rb_agency_option_profilelist_favorite){ 
					$all_html .= "<div class=\"profile-results-info-countpage\">\n";
					$all_html .= $count;  // Echo out the list of paging. 
					$all_html .= "</div>\n";
				}
				if ($rb_agency_option_profilelist_count) {
					$all_html .= "<div id=\"profile-results-info-countrecord\">\n";
					$all_html .=  __("Displaying", rb_agency_TEXTDOMAIN) ." <strong>". $countList ."</strong> ". __("of", rb_agency_TEXTDOMAIN) ." ". $items ." ". __(" records", rb_agency_TEXTDOMAIN) ."\n";
					$all_html .= "</div>\n";
				}
				$all_html .= '</div>';

				/* 
				 * wrap profile listing
				 */				
				$all_html .="<div id='profile-list'>".$profile_list."</div></div>";
				if(self::$error_debug){
					self::$error_checking[] = array('search_result_public',$all_html);
					var_dump(self::$error_checking);
				}
				return $all_html;

			} else {

				/* 
				 * No results Found.
				 */
				$no_rec_html = '<div class=\"rbclear\"></div>' . __("No Profiles Found", rb_agency_TEXTDOMAIN);
				if(self::$error_debug){
					self::$error_checking[] = array('search_result_public',$no_rec_html);
					var_dump(self::$error_checking);
				}
				return $no_rec_html;

			}
		
		}


		/* 
		 * search for admin 
		 */
		public static function search_result_admin($sql){

				global $wpdb;
				/* 
				 * rb agency search for admin options 
				 */
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_unittype =  $rb_agency_options_arr['rb_agency_option_unittype'];
				$rb_agency_option_persearch = (int)$rb_agency_options_arr['rb_agency_option_persearch'];
				$rb_agency_option_agencyemail = (int)$rb_agency_options_arr['rb_agency_option_agencyemail'];
				if ($rb_agency_option_persearch < 0) { $rb_agency_option_persearch = 100; }

				/* 
				 * process query 
				 */
				 
				$results = mysql_query($sql);
				$count = mysql_num_rows($results);
				
				/* 
				 * initialize html 
				 */
				$displayHtml = "";
				$displayHtml .=  "  <div class=\"boxblock-holder\">\n";
				$displayHtml .=  "<h2 class=\"title\">Search Results: " . $count . "</h2>\n";

				if (($count > ($rb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
					$displayHtml .=  "<div id=\"message\" class=\"error\"><p>Search exceeds ". $rb_agency_option_persearch ." records first ". $rb_agency_option_persearch ." displayed below.  <a href=". admin_url("admin.php?page=". $_GET['page']) ."&". $sessionString ."&limit=none><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
				}
				$displayHtml .=  "       <form method=\"get\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"forceCart\" value=\"". $_SESSION['cartArray'] ."\" />\n";
				$displayHtml .=  "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
				$displayHtml .=  "        <thead>\n";
				$displayHtml .=  "            <tr class=\"thead\">\n";
				$displayHtml .=  "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=rb_agency_profiles&sort=ProfileID&dir=". $sortDirection ."\">". __("ID", rb_agency_TEXTDOMAIN) ."</a></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileImage\" id=\"ProfileImage\" scope=\"col\" style=\"width:150px;\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "            </tr>\n";
				$displayHtml .=  "        </thead>\n";
				$displayHtml .=  "        <tfoot>\n";
				$displayHtml .=  "            <tr class=\"thead\">\n";
				$displayHtml .=  "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\">". __("ID", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-image\" id=\"col-image\" scope=\"col\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "            </tr>\n";
				$displayHtml .=  "        </tfoot>\n";
				$displayHtml .=  "        <tbody>\n";

				while ($data = mysql_fetch_array($results)) {
						$ProfileID = $data['ProfileID'];
						$isInactive = '';
						$isInactiveDisable = '';
						if ($data['ProfileIsActive'] == 0 || empty($data['ProfileIsActive'])){
							$isInactive = 'style="background: #FFEBE8"';
							$isInactiveDisable = "disabled=\"disabled\"";
						}
						$displayHtml .=  "        <tr ". $isInactive.">\n";
						$displayHtml .=  "            <th class=\"check-column\" scope=\"row\" >\n";
						$displayHtml .=  "                <input type=\"checkbox\" ". $isInactiveDisable." value=\"". $ProfileID ."\" class=\"administrator\" id=\"ProfileID". $ProfileID ."\" name=\"ProfileID[]\" />\n";
						$displayHtml .=  "            </th>\n";
						$displayHtml .=  "            <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
						$displayHtml .=  "            <td class=\"ProfileContact column-ProfileContact\">\n";
						$displayHtml .=  "                <div class=\"detail\">\n";
						$displayHtml .=  "                </div>\n";
						$displayHtml .=  "                <div class=\"title\">\n";
						$displayHtml .=  "                	<h2>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h2>\n";
						$displayHtml .=  "                </div>\n";
						$displayHtml .=  "                <div class=\"row-actions\">\n";
						$displayHtml .=  "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
						$displayHtml .=  "                    <span class=\"review\"><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
						$displayHtml .=  "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;deleteRecord&amp;ProfileID=". $ProfileID ."' onclick=\"if ( confirm('You are about to delete the model \'". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;\">Delete</a></span>\n";
						$displayHtml .=  "                </div>\n";
						if(!empty($isInactiveDisable)){
							   $displayHtml .=  "<div><strong>Profile Status:</strong> <span style=\"color:red;\">Inactive</span></div>\n";
						}
						$displayHtml .=  "            </td>\n";

						// private into 
						$displayHtml .=  "            <td class=\"ProfileStats column-ProfileStats\">\n";

						if (!empty($data['ProfileContactEmail'])) {
								$displayHtml .=  "<div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
						}
						if (!empty($data['ProfileLocationStreet'])) {
								$displayHtml .=  "<div><strong>Address:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
						}
						if (!empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState'])) {
								$displayHtml .=  "<div><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
						}
						if (!empty($data['ProfileLocationCountry'])) {
								$country = (rb_agency_getCountryTitle($data['ProfileLocationCountry']) != false) ? rb_agency_getCountryTitle($data['ProfileLocationCountry']):"";
								$displayHtml .=  "<div><strong>". __("Country", rb_agency_TEXTDOMAIN) .":</strong> ". $country ."</div>\n";
						}
						if (!empty($data['ProfileDateBirth'])) {
								$displayHtml .=  "<div><strong>". __("Age", rb_agency_TEXTDOMAIN) .":</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."</div>\n";
						}
						if (!empty($data['ProfileDateBirth'])) {
								$displayHtml .=  "<div><strong>". __("Birthdate", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</div>\n";
						}
						if (!empty($data['ProfileContactWebsite'])) {
								$displayHtml .=  "<div><strong>". __("Website", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
						}
						if (!empty($data['ProfileContactPhoneHome'])) {
								$displayHtml .=  "<div><strong>". __("Phone Home", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneHome'] ."</div>\n";
						}
						if (!empty($data['ProfileContactPhoneCell'])) {
								$displayHtml .=  "<div><strong>". __("Phone Cell", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneCell'] ."</div>\n";
						}
						if (!empty($data['ProfileContactPhoneWork'])) {
								$displayHtml .=  "<div><strong>". __("Phone Work", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
						}
						
						$resultsCustomPrivate =  $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC"));
						if (count($resultsCustomPrivate) > 0){
							foreach ($resultsCustomPrivate as $resultCustomPrivate) {
								$displayHtml .=  "<div><strong>". $resultCustomPrivate->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustomPrivate->ProfileCustomValue ."</div>\n";
							}
						}

						$displayHtml .=  "            </td>\n";
						// public info
						$displayHtml .=  "            <td class=\"ProfileDetails column-ProfileDetails\">\n";
						$displayHtml .=  "<ul style='margin: 0px;'>" ;
						
						if (!empty($data['ProfileGender'])) {
							$displayHtml .=  "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> ".rb_agency_getGenderTitle($data['ProfileGender'])."</li>\n";
						}else{
							$displayHtml .=  "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> --</li>\n";	
						}

						$displayHtml .= rb_agency_getProfileCustomFields_admin($ProfileID ,$data['ProfileGender']);

						$displayHtml .=  "</ul>" ;
						
						$displayHtml .=  "            </td>\n";
						$displayHtml .=  "            <td class=\"ProfileImage column-ProfileImage\">\n";
						
						$p_image = rb_get_primary_image($data["ProfileID"]); 
						
						if ($p_image) {
							$displayHtml .=  "				<div class=\"image\"><img style=\"width: 150px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $p_image ."\" /></div>\n";
						} else {
							$displayHtml .=  "				<div class=\"image no-image\">NO IMAGE</div>\n";
						}

						$displayHtml .=  "            </td>\n";
						$displayHtml .=  "        </tr>\n";

				}

				mysql_free_result($results);
				if ($count < 1) {
					if (isset($filter)) { 
				$displayHtml .=  "        <tr>\n";
				$displayHtml .=  "            <th class=\"check-column\" scope=\"row\"></th>\n";
				$displayHtml .=  "            <td class=\"name column-name\" colspan=\"5\">\n";
				$displayHtml .=  "                <p>". __("No profiles found with this criteria!", rb_agency_TEXTDOMAIN) .".</p>\n";
				$displayHtml .=  "            </td>\n";
				$displayHtml .=  "        </tr>\n";
					} else {
				$displayHtml .=  "        <tr>\n";
				$displayHtml .=  "            <th class=\"check-column\" scope=\"row\"></th>\n";
				$displayHtml .=  "            <td class=\"name column-name\" colspan=\"5\">\n";
				$displayHtml .=  "                <p>". __("There aren't any Profiles loaded yet!", rb_agency_TEXTDOMAIN) ."</p>\n";
				$displayHtml .=  "            </td>\n";
				$displayHtml .=  "        </tr>\n";
					}
				}
				$displayHtml .=  "        </tbody>\n";
				$displayHtml .=  "    </table>\n";
				$displayHtml .=  "     <p>\n";
				$displayHtml .=  "      	<input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_search') ."\" class=\"button-primary\" />\n";
				$displayHtml .=  "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
				$displayHtml .=  "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
				$displayHtml .=  "     </p>\n";
				$displayHtml .=  "  </form>\n";
				$displayHtml .=  "</div>";
				
				return $displayHtml;

		}


		/*
		 * Format Profile
		 * Create list from IDs
		 */
		public static function search_formatted($dataList){

			/* 
			 * rb agency options
			 */
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_privacy					 = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
			$rb_agency_option_profilelist_count			 = isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
			$rb_agency_option_profilelist_perpage		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_perpage']) ?$rb_agency_options_arr['rb_agency_option_profilelist_perpage']:0;
			$rb_agency_option_profilelist_sortby		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
			$rb_agency_option_layoutprofilelist		 	 = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelist']:0;
			$rb_agency_option_profilelist_expanddetails	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']) ? $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']:0;
			$rb_agency_option_locationtimezone 			 = isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? (int)$rb_agency_options_arr['rb_agency_option_locationtimezone']:0;
			$rb_agency_option_profilelist_favorite		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;
			$rb_agency_option_profilenaming				 = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
			$rb_agency_option_profilelist_castingcart 	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart']:0;
			$rb_agency_option_profilelist_printpdf 	     = isset($rb_agency_options_arr['rb_agency_option_profilelist_printpdf']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_printpdf']:0;

			/* 
			 * initialize html
			 */
			$displayHTML ="";
			$displayHTML .= "<div id=\"rbprofile-".$dataList["ProfileID"]."\" class=\"rbprofile-list profile-list-layout0\" >\n";
			$displayHTML .= '<input id="br'.$dataList["ProfileID"].'" type="hidden" class="p_birth" value="'.$dataList["ProfileDateBirth"].'">';
			$displayHTML .= '<input id="nm'.$dataList["ProfileID"].'" type="hidden" class="p_name" value="'.$dataList["ProfileContactDisplay"].'">';
			$displayHTML .= '<input id="cr'.$dataList["ProfileID"].'" type="hidden" class="p_created" value="'.$dataList["ProfileDateCreated"].'">';

			/* 
			 * determine primary image
			 */
			$p_image = rb_get_primary_image($dataList["ProfileID"]); 
			if ($p_image){ 
				if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){
					if($rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']==1){  //show profile sub thumbs for thumb slide on hover
						$images=getAllImages($dataList["ProfileID"]);
						$images=str_replace("{PHOTO_PATH}",rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"]."/",$images);
					}
					$displayHTML .="<div  class=\"image\">"."<a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\" style=\"background-image: url(".rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $p_image.")\"></a>".$images."</div>\n";
				} else {
					$displayHTML .="<div  class=\"image\">"."<a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\" style=\"background-image: url(".rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $p_image.")\"></a>".$images."</div>\n";
				}
			} else {
				$displayHTML .= "  <div class=\"image image-broken\" style='background:lightgray; color:white; font-size:20px; text-align:center; line-height:120px; vertical-align:bottom'>No Image</div>\n";
			}

			/* 
			 * determine profile details
			 */
			$displayHTML .= "  <div class=\"profile-info\">\n";
			$displayHTML .= "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\" class=\"scroll\">". stripslashes($dataList["ProfileContactDisplay"]) ."</a></h3>\n";
			if ($rb_agency_option_profilelist_expanddetails) {
				$displayHTML .= "     <div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span>";
				if($dataList["ProfileLocationState"]!=""){
					$displayHTML .= "<span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span>";
				} 
				$displayHTML .= "</div>\n";
			}
			
			if(is_user_logged_in()){
				$displayHTML .= rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
			}

			$displayHTML .=" </div> <!-- .profile-info --> \n";
			if(self::$error_debug){		
				self::$error_checking[] = array('search_formatted',$displayHTML);
				var_dump(self::$error_checking);
			}
			return $displayHTML;

		}


}

?>