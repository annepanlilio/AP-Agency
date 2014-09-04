<?php
class RBAgency_Profile {

	/*
	 * Debug Options
	 */
		protected static $error_debug = false;
		protected static $error_debug_query = false;
		protected static $error_checking = array();
	/*
	 * Class Properties
	 */
		protected static $order_by ='';
		protected static $castingcart = false;

	/*
	 * Search Form
	 * Process Search
	 */
		public static function search_form($atts ='', $args = '', $type = 0, $profilesearch_layout = '', $profilesearch_advanced_button = false){

			/*
			* Setup Requirements
			*/

				global $wpdb;
				$rb_agency_options_arr = get_option('rb_agency_options');
					// What is the unit of measurement?
					$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:1;

					/* Search Form - Simple */
					$rb_agency_option_formshow_location = isset($rb_agency_options_arr['rb_agency_option_formshow_location'])?$rb_agency_options_arr['rb_agency_option_formshow_location']:0;
					$rb_agency_option_formshow_name = isset($rb_agency_options_arr['rb_agency_option_formshow_name'])?$rb_agency_options_arr['rb_agency_option_formshow_name']:0;
					$rb_agency_option_formshow_type = isset($rb_agency_options_arr['rb_agency_option_formshow_type'])?$rb_agency_options_arr['rb_agency_option_formshow_type']:0;
					$rb_agency_option_formshow_gender = isset($rb_agency_options_arr['rb_agency_option_formshow_gender'])?$rb_agency_options_arr['rb_agency_option_formshow_gender']:0;
					$rb_agency_option_formshow_age = isset($rb_agency_options_arr['rb_agency_option_formshow_age'])?$rb_agency_options_arr['rb_agency_option_formshow_age']:0;
					$rb_agency_option_formshow_displayname = isset($rb_agency_options_arr['rb_agency_option_formshow_displayname'])?$rb_agency_options_arr['rb_agency_option_formshow_displayname']:0;

				// Which Type?
				if ($type == 1) {

					// Admin Back-end 
					$rb_agency_searchurl = admin_url("admin.php?page=rb_agency_search");

					if ($profilesearch_layout == 'condensed'){
						$search_layout = "simple";
					}else{
						$search_layout = "admin";
					}

				} else {

					// Front Back-end
					$rb_agency_searchurl = get_bloginfo("wpurl") ."/search-results/";
					if ( (get_query_var("type") == "search-basic") || ($profilesearch_layout == 'condensed') ){
							$search_layout = "simple";
					} elseif ( (get_query_var("type") == "search-advanced") || ($profilesearch_layout == 'advanced') ){
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
							var inputs = jQuery(".rbfield").find("input[type=text]");
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
							jQuery(".rbfield").find("select").prop('selectedIndex',0);
						});
					}

					jQuery("#rst_btn").rset();

					<?php if(!is_admin()){ ?>
						jQuery.fn.css_ = function(){
							var el = this.get(0); var st; var returns = {};
							if(window.getComputedStyle){
								var camel = function(a,b){return b.toUpperCase();}
								st = window.getComputedStyle(el, null);
								try{
						
									for(var s=0; s < st.length; s++){
										var css_style = st[s];
										var cml = css_style.replace(/\-([a-z])/, camel);
										var vl = st.getPropertyValue(css_style);
										returns[cml] = vl;
									}
									
								}catch(e){

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

				echo "	<div id=\"profile-search-form-condensed\" class=\"rbform form-". (isset($search_layout)?$search_layout:"") ."\">\n";
				echo "		<form method=\"post\" enctype=\"multipart/form-data\" action=\"". (isset($rb_agency_searchurl)?$rb_agency_searchurl:"") ."\">\n";
				echo "			<input type=\"hidden\" name=\"form_action\" value=\"search_profiles\" />\n";
				echo "			<input type=\"hidden\" name=\"form_mode\" value=\"". (isset($search_layout)?$search_layout:"") ."\" />\n";

				// Show Profile Name
				if ( ($rb_agency_option_formshow_name > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_name > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_firstname\" id=\"rb_firstname\">\n";
						echo "					<label for=\"namefirst\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"namefirst\" name=\"namefirst\" value=\"".RBAgency_Common::session("namefirst") ."\" /></div>\n";
						echo "				</div>\n";
						echo "				<div class=\"rbfield rbtext rbsingle rb_lastname\" id=\"rb_lastname\">\n";
						echo "					<label for=\"namelast\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"namelast\" name=\"namelast\" value=\"".RBAgency_Common::session("namelast")."\" /></div>\n";
						echo "				</div>\n";
				}

				if ( ($rb_agency_option_formshow_displayname > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_displayname > 1) ) {
				
						echo "				<div class=\"rbfield rbtext rbsingle rb_displayname\" id=\"rb_displayname\">\n";
						echo "					<label for=\"displayname\">". __("Display Name", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"displayname\" name=\"displayname\" value=\"".RBAgency_Common::session("displayname")."\" /></div>\n";
						echo "				</div>\n";
				}

				// Show Profile Type
				if ( ($rb_agency_option_formshow_type > 0) || isset($search_layout) && $search_layout == "admin" || ( isset($search_layout) &&  $search_layout == "full" && $rb_agency_option_formshow_type > 1) ) {
						echo "				<div class=\"rbfield rbselect rbsingle rb_profiletype\" id=\"rb_profiletype\">\n";
						echo "					<label for=\"type\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "					<div>";
						echo "						<select name=\"profiletype\" id=\"type\">\n";               
						echo "							<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
														$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
														$results2 = $wpdb->get_results($query,ARRAY_A);
														foreach ($results2 as $key) {
															 
																if (isset($_SESSION['profiletype']) && $_SESSION['profiletype']) {
																		if ($key["DataTypeID"] == RBAgency_Common::session('profiletype')) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
																} else { $selectedvalue = ""; }
																echo "<option value=\"". $key["DataTypeID"] ."\"".$selectedvalue.">". $key["DataTypeTitle"] ."</option>";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";
				}

				// Show Profile Gender
				if ( ($rb_agency_option_formshow_gender > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_gender > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_gender\" id=\"rb_gender\">\n";
						echo "					<label for=\"gender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "					<div>";
						$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
						$results2 = $wpdb->get_results($query2,ARRAY_A);
						echo "						<select name=\"gender\" id=\"gender\">\n";
						echo "							<option value=\"\">". __("Any Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
														// Pul Genders from Database
														foreach ($results2 as $key) {
															  if(isset($key["GenderID"]))
																echo "<option value=\"". $key["GenderID"] ."\"".selected(isset($_SESSION['gender'])?$_SESSION['gender']:"", $key["GenderID"],false).">". $key["GenderTitle"] ."</option>";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";
				}

				// Show Profile Age
				if ( ($rb_agency_option_formshow_age > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_age > 1) ) {
						echo "				  <div class=\"rbfield rbtext rbmulti rb_datebirth\" id=\"rb_datebirth\">";
						echo "					<label for=\"datebirth_min datebirth_max\">". __("Age", rb_agency_TEXTDOMAIN) . "</label>";
						echo "					<div>\n";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>";
						echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_min\" name=\"datebirth_min\" value=\"".RBAgency_Common::session('datebirth_min') ."\" />\n";
						echo "						</div>";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
						echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_max\" name=\"datebirth_max\" value=\"".RBAgency_Common::session('datebirth_max') ."\" />\n";
						echo "						</div>";
						echo "					</div>";
						echo "				  </div>";
				}

				// Show Location Search
				if ( ($rb_agency_option_formshow_location > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_location > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_city\" id=\"rb_city\">\n";
						echo "					<label for=\"city\">". __("City", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"city\" name=\"city\" value=\"".RBAgency_Common::session("city") ."\" /></div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbselect rbsingle rb_country\" id=\"rb_country\">\n";
												$location= site_url();
						echo '					<input type="hidden" id="url" value="'.$location.'">';
						echo "					<label for=\"country\">". __("Country", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div>";
						echo "						<select name=\"country\" id=\"country\" onchange='javascript:populateStates(\"country\",\"state\");'>";
						echo '							<option value="">'. __("Any Country", rb_agency_TEXTDOMAIN) .'</option>';
														$query_get ="SELECT * FROM `".table_agency_data_country."` ORDER BY CountryTitle ASC" ;
														$result_query_get = $wpdb->get_results($query_get);
														foreach($result_query_get as $r){
																$selected = isset($_SESSION["country"]) && $_SESSION["country"] ==$r->CountryID?"selected=selected":"";
						echo '							<option '.$selected.' value='.$r->CountryID.' >'.$r->CountryTitle.'</option>';
														}
						echo '						</select>';
						echo "					</div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbselect rbsingle rb_state\" id=\"rb_state\">\n";
						echo "					<label for=\"state\">". __("State", rb_agency_TEXTDOMAIN) ."</label>\n";
												//echo "	<input type=\"text\" id=\"state\" name=\"state\" value=\"".RBAgency_Common::session("state") ."\" />\n";
						echo "					<div>";
						echo '						<select name="state" id="state">';
						echo '							<option value="">'. __("Any State", rb_agency_TEXTDOMAIN) .'</option>';
														/*$query_get ="SELECT * FROM `".table_agency_data_state."` ORDER BY StateTitle ASC" ;
														$result_query_get = $wpdb->get_results($query_get);
														foreach($result_query_get as $r){
															$selected =RBAgency_Common::session("state")==$r->StateID?"selected=selected":"";
						echo '								<option '.$selected.' value='.$r->StateID.' >'.$r->StateTitle.'</option>';
														}*/
						echo '						</select>';
						echo "					</div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbtext rbsingle rb_zip\" id=\"rb_zip\">\n";
						echo "					<label for=\"zip\">". __("Zip", rb_agency_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"zip\" name=\"zip\" value=\"".RBAgency_Common::session("zip") ."\" /></div>\n";
						echo "				</div>\n";
				} // Show Location Search

                 
			/*
			 * Custom Fields
			 */
			// Query Fields
				if(is_admin()){
					$field_sql = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView <= 1 ORDER BY ProfileCustomOrder ASC";
				}else{
					$field_sql = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 ORDER BY ProfileCustomOrder ASC";
				}
				$field_results = $wpdb->get_results($field_sql,ARRAY_A);
				foreach($field_results  as $data){

					// Set Variables
					$ProfileCustomID = $data['ProfileCustomID'];
					$ProfileCustomTitle = $data['ProfileCustomTitle'];
					$ProfileCustomType = $data['ProfileCustomType'];
					$ProfileCustomOptions = $data['ProfileCustomOptions'];
					$ProfileCustomShowSearch = $data['ProfileCustomShowSearch'];
					$ProfileCustomShowSearchSimple = $data['ProfileCustomShowSearchSimple']; 

					// Show this Custom Field on Search
					if( isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $ProfileCustomShowSearch == 1 && $search_layout == "full" || (isset($search_layout) && $ProfileCustomShowSearchSimple == 1 && $search_layout=='simple') || 
						(isset($_POST['form_mode']) && $_POST['form_mode'] == "full" )  )){

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
								echo "<div class=\"rbfield rbtext rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
								echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
								//Commentd to fix language value populate
								//echo "<input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" />";
								echo "<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".
								RBAgency_Common::session("ProfileCustomID".$ProfileCustomID)."\" /></div>";
								echo "</div>";

						/*
						 * Min Max
						 */
						} elseif($ProfileCustomType == 2) {

								echo "<div class=\"rbfield rbtext rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
									echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
									$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($ProfileCustomOptions,"}"),"{"));
									list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
										//print_r($_SESSION["ProfileCustomID".$ProfileCustomID]);
									if(is_array($_SESSION["ProfileCustomID".$ProfileCustomID])){
										$_SESSION["ProfileCustomID".$ProfileCustomID]=@implode(",",RBAgency_Common::session("ProfileCustomID".$ProfileCustomID));
										list($min_val2,$max_val2) =  @explode(",",RBAgency_Common::session("ProfileCustomID".$ProfileCustomID));
									} else {
										list($min_val2,$max_val2) =  @explode(",",$_SESSION["ProfileCustomID".$ProfileCustomID]);
									}

									if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>";
										echo "</div>";
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_max\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>";
										echo "</div>";
									} else {
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$min_val2."\" /></div>";
										echo "</div>";
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_max\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$max_val2."\" /></div>";
										echo "</div>";
									}
								echo "</div>";

						/*
						 * Dropdown
						 */
						} elseif($ProfileCustomType == 3 || $ProfileCustomType == 9 ) {
								echo "<div class=\"rbfield rbselect rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
								echo "	<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
								echo "	<div>";
								echo "		<select name=\"ProfileCustomID". $ProfileCustomID ."[]\" ".($ProfileCustomType == 9 ?"multiple":"").">";
								echo "			<option value=\"\">--</option>";
												$values = explode("|",$ProfileCustomOptions);
												foreach($values as $value){
													// Validate Value
													if(!empty($value)) {
														// Identify Existing Value
														$isSelected = "";
														if(isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && $_REQUEST["ProfileCustomID". $ProfileCustomID]==stripslashes($value)  || isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && in_array(stripslashes($value), $_REQUEST["ProfileCustomID".$ProfileCustomID])){
															$isSelected = "selected=\"selected\"";
															echo "		<option value=\"".stripslashes($value)."\" ".$isSelected .">".stripslashes($value)."</option>";
														}else{
															echo "		<option value=\"".stripslashes($value)."\" >".stripslashes($value)."</option>"; 
														}
													}
												}
								echo "		</select>";
								echo "	</div>";
								echo "</div>";


						/*
						 * Textbox
						 */
						} elseif($ProfileCustomType == 4) {
							/*
							TODO: Should we search text inside of text area?
											echo "<div class=\"rbfield rbsingle\">";
											echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											echo "<textarea name=\"ProfileCustomID". $ProfileCustomID ."\">". $_REQUEST["ProfileCustomID". $ProfileCustomID] ."</textarea>";
											echo "</div>";
							*/

						/*
						 * Checkbox
						 */
						} elseif($ProfileCustomType == 5) {
								echo "<div class=\"rbfield rbcheckbox rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
								echo "<label>". $ProfileCustomTitle ."</label>";
								echo "<div>";
								$array_customOptions_values = explode("|", $ProfileCustomOptions);
								foreach($array_customOptions_values as $val){

									if(isset($_SESSION["ProfileCustomID". $ProfileCustomID])){ 

										$dataArr = @explode(",",@implode(",",@explode("','",stripslashes(RBAgency_Common::session("ProfileCustomID". $ProfileCustomID)))));
										if(in_array($val,$dataArr,true)){
											echo "<div ><label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
											echo "<span> ". $val."</span></label></div>";
										} else {
											if($val !=""){
												echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
												echo "<span> ". $val."</span></label></div>";
											}
										}
									} else {
										if($val !=""){
											echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
											echo "<span> ". $val."</span></label></div>";
										}
									}
								}

								echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>";
								echo "</div>";
								echo "</div>";

						/*
						 * Radio Button
						 */
						} elseif($ProfileCustomType == 6) {
								echo "<div class=\"rbfield rbradio rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
								echo "<label>". $ProfileCustomTitle ."</label>";
								echo "<div>";
								$array_customOptions_values = explode("|", $ProfileCustomOptions);

								foreach($array_customOptions_values as $val){

									if(isset($_REQUEST["ProfileCustomID". (isset($data1['ProfileCustomID'])?$data1['ProfileCustomID']:0)]) &&RBAgency_Common::session("ProfileCustomID". $ProfileCustomID) !=""){ 

										$dataArr = explode(",",implode(",",explode("','",RBAgency_Common::session("ProfileCustomID". $ProfileCustomID))));

										if(in_array($val,$dataArr) && $val !=""){
											echo "<div><label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
											echo "<span> ". $val."</span></label></div>";
										}else{
											if($val !=""){
												echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
												echo "<span> ". $val."</span></label></div>";
											}
										}
									} else {
										if($val !=""){
											echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />";
											echo "<span> ". $val."</span></label></div>";
										}
									}
								}
								echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>";	       
								echo "</div>";
								echo "</div>";

						/*
						 * Metric
						 */
						} elseif($ProfileCustomType == 7) {
								echo "<div class=\"rbfield rbselect rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";

								/*
								 * Measurement Label
								 */

									$measurements_label = "";

									// 0 = Metrics(cm/kg)
									if($rb_agency_option_unittype ==0){
										if($ProfileCustomOptions == 1){
											$measurements_label  ="<em> (cm)</em>";
										} elseif($ProfileCustomOptions == 2){
											$measurements_label  ="<em> (kg)</em>";
										} elseif($ProfileCustomOptions == 3){
											$measurements_label  ="<em> (cm)</em>";
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

									echo "<label>". $ProfileCustomTitle . $measurements_label ."</label>";

								/*
								 * Handle Array
								 */

								// Is Array?
								if(isset($_SESSION["ProfileCustomID".$ProfileCustomID]) && is_array($_SESSION["ProfileCustomID".$ProfileCustomID])){
									$_SESSION["ProfileCustomID".$ProfileCustomID] = @implode(",",RBAgency_Common::session("ProfileCustomID".$ProfileCustomID));
								}

								// List
								$list_value = RBAgency_Common::session("ProfileCustomID".$ProfileCustomID);
								@list($min_val,$max_val) =  @explode(",",$list_value);

								// Is Height and is Imperial
								if($ProfileCustomTitle=="Height" && $rb_agency_option_unittype == 1 && $data['ProfileCustomOptions']==3){

									echo "<div>";
										echo "<div>";
										echo "<label>Min</label>";
										echo "<select name=\"ProfileCustomID". $ProfileCustomID ."[]\">\n";
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
												echo " <option value=\"". $i ."\" ". selected(isset($ProfileCustomValue)?$ProfileCustomValue:"", $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
												$i++;
											}
										echo " </select>";
										echo "</div>\n";

										echo "<div>";
										echo "<label>Max</label>";
										echo "<select name=\"ProfileCustomID". $ProfileCustomID ."[]\">\n";
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
												echo " <option value=\"". $i ."\" ". selected(isset($ProfileCustomValue)?$ProfileCustomValue:"", $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
												$i++;
											}
										echo " </select>\n";
										echo "</div>\n";
									echo "</div>";
								} else {
									echo "<div>";
										// for other search
										echo "<div>";
										echo "<label for=\"ProfileCustomID".$ProfileCustomID."_min\">Min</label><input value=\""
										.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
										."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
										.$ProfileCustomID."[]\" />";
										echo "</div>";

										echo "<div><label for=\"ProfileCustomID".(isset($data1['ProfileCustomID'])?$data1['ProfileCustomID']:"")
										."_max\">Max</label><input value=\"".(isset($max_val)?$max_val:"") ."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$ProfileCustomID."[]\" />";
										echo "</div>";
									echo "</div>";
								}
							echo "</div>";

						} 

						/*
						 * Date Between
						 */
						elseif($ProfileCustomType == 10) {
							     $from = "";
							       $to = "";
								@list($from,$to) =  @explode(",",RBAgency_Common::session("ProfileCustomID".$ProfileCustomID));
									
								echo "<div class=\"rbfield rbselect rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">";
									echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>";
											echo "<div>";
									
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("From", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input  id=\"rb_datepicker_from\" class=\"rb-datepicker\" type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$from."\" /></div>";
										echo "</div>";
										echo "<div>";
										echo "		<label for=\"ProfileCustomLabel_max\" style=\"text-align:right;\">". __("to", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
										echo "		<div><input  id=\"rb_datepicker_to\" class=\"rb-datepicker\" type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$to."\" /></div>";
										echo "</div>";
											echo "</div>";
								
								echo "</div>";
						} // End Type


					}

				}



				/* status The “Status” field should not show up on front-end search.*/ 
				if(isset($_REQUEST['page']) && $_REQUEST['page']=='rb_agency_search'){
					echo "				<div class=\"rbfield rbselect rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
					echo "					<label for=\"state\">". __("Status", rb_agency_TEXTDOMAIN) ."</label>\n";
					echo "						<div>";
					echo "							<select name=\"isactive\" id=\"ProfileIsActive\">\n";               
					echo "								<option value=\"5\">". __("Any Status", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "								<option value=\"1\"". selected(RBAgency_Common::session("isactive"), 1) .">". __("Active", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "								<option value=\"4\"". selected(RBAgency_Common::session("isactive"), 4) .">". __("Active - Not Visible on Front End", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "								<option value=\"0\"". selected(RBAgency_Common::session("isactive"), 0) .">". __("Inactive", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "								<option value=\"2\"". selected(RBAgency_Common::session("isactive"), 2) .">". __("Archived", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "								<option value=\"3\"". selected(RBAgency_Common::session("isactive"), 3) .">". __("Pending Approval", rb_agency_TEXTDOMAIN) . "</option>\n";
					echo "							</select>\n";
					echo "						</div>\n";
					echo "				</div>\n";
				}

				echo "				<div class=\"rbfield rbsubmit rbsingle\">";
					echo "					<input type=\"submit\" name=\"search_profiles\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\"  />"; // onclick=\"this.form.action='". $rb_agency_searchurl ."'\"
					echo "					<input type=\"button\" id=\"rst_btn\" value=\"". __("Empty Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"clearForm();\" />";
					if($profilesearch_advanced_button == true){
					echo "					<input type=\"button\" name=\"advanced_search\" value=\"". __("Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
					}
					if ( (get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ||  $profilesearch_layout == 'condensed' ){
					echo "					<input type=\"button\" name=\"advanced_search\" value=\"". __("Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
					} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" )  || $profilesearch_layout == 'advanced' ){
					echo "					<input type=\"button\" name=\"advanced_search\" value=\"". __("Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
					
					}
				echo "				</div>\n";
				echo "			</form>\n";

				echo "			<script>\n";
					echo "			function clearForm(){\n";
					echo "				jQuery('#namefirst').val('');\n";
					echo "				jQuery('#namelast').val('');\n";
					echo "				jQuery(\"select[name*='ProfileCustomID']\" ).val('');\n";
					echo "				jQuery('#type').val('');\n";
					echo "				jQuery('#gender').val('');\n";
					echo "				jQuery('#datebirth_min').val('');\n";
					echo "				jQuery('#datebirth_max').val('');\n";
					echo "				jQuery('.rbcheckbox input[type=checkbox]').each(function(){";
					echo "				jQuery(this).removeAttr('checked');";
					echo "				});";
					echo "					jQuery.ajax({\n";
					echo "								type: 'POST',\n";
					echo "								dataType: 'json',\n";
					echo "								url: '".admin_url('admin-ajax.php')."',\n";
					echo "								data: { \n";
					echo "									'action': 'rb_agency_clear_casting_array'\n";
					echo "								},\n";
					echo "								success: function(d){\n";
					echo "									console.log(d); \n";
					echo "								},\n";
					echo "								error: function(e){\n";
					echo "									console.log(e);\n";
					echo "								}\n";
					echo "					});\n";
					echo "									jQuery('html, body').animate({ scrollTop: 0 }, 'slow'); \n";
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
						$filterArray['limit'] = 1000;
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
					if ( (isset($_REQUEST['override_privacy']) && !empty($_REQUEST['override_privacy'])) || isset($_REQUEST['mode']) && $_REQUEST['mode'] == "admin") {
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

					if (isset($_REQUEST['displayname']) && !empty($_REQUEST['displayname'])){
							$filterArray['displayname'] = $_REQUEST['displayname'];
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
				echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
			}
				return $filterArray;
			} // If no post, ignore.

		}


	/*
	 * Search: Prepare WHERE SQL String
	 * Process values into SQL string holding WHERE clause
	 */
		public static function search_generate_sqlwhere($atts = null, $exclude = null){

			$rb_agency_options_arr = get_option('rb_agency_options');

			// Time Zone
			$rb_agency_option_locationtimezone = isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? $rb_agency_options_arr['rb_agency_option_locationtimezone']:"";
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
					"displayname" => NULL,
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
					
					"profilecasting" => NULL,
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
					if ($isactive == 5) {
						// Just ignore.  Need a value to show all
						$filter = "profile.ProfileID > 0 ";
					} elseif (isset($isactive) && $isactive != ''){
						// Show the specified value
						$filter = "profile.ProfileIsActive = " . $isactive;
					} else {
						// Default to active profiles
						$filter = "profile.ProfileIsActive = 1 ";
					}
				}

				// First Name
				if (isset($namefirst) && !empty($namefirst)){
					$filter .= " AND profile.ProfileContactNameFirst LIKE '". mysql_real_escape_string(str_replace('"','\"',(str_replace("'","\'",($namefirst))))) ."%'";
				}

				
				// Last Name
				if (isset($namelast) && !empty($namelast)){
					$filter .= " AND profile.ProfileContactNameLast LIKE '".  mysql_real_escape_string(str_replace('"','\"',(str_replace("'","\'",($namelast))))) ."%'";
				}

				// Display Name
				if (isset($displayname) && !empty($displayname)){
					$filter .= " AND profile.ProfileContactDisplay LIKE '". mysql_real_escape_string(str_replace('"','\"',(str_replace("'","\'",($displayname))))) ."%'";
				}


				// Type
				if (isset($profiletype) && !empty($profiletype)){
					$filter .= " AND FIND_IN_SET('". $profiletype ."', profile.ProfileType) ";
				}

				// Gender
				if (isset($gender) && !empty($gender)){
					$filter .= " AND profile.ProfileGender='".$gender."'";
				}

				// casting
				if (isset($profilecasting) && $profilecasting){
					self::$castingcart = true;
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
					foreach ((!empty($_POST)?$_POST:$_GET) as $key => $val) {
						if (substr(strtolower($key),0,15) == "profilecustomid") {

						/*
						 *  Check if this is array or not because sometimes $val is an array so
						 *  array_filter is not applicable
						 */
							if ((!empty($val) AND !is_array($val)) OR (is_array($val) AND count(array_filter($val)) > 0)) {

								/*
								 * Id like to chop this one out and extract
								 * the array values from here and make it a string with "," or
								 * pass the rbsingle value back $val
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
										$val = array_values($val);
										$val = array_shift($val);
									} 
								}
								global $wpdb;
								$q = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = '%d' ",substr($key,15)),ARRAY_A);
								$ProfileCustomType = current($q);

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

								$open_st = ' AND EXISTS(SELECT * FROM '. table_agency_customfield_mux . '  WHERE ' ;
								$close_st = ' AND ProfileCustomID = '.substr($key,15).' AND ProfileID = profile.ProfileID)  ';

								if ($ProfileCustomType["ProfileCustomType"] == 1) {
									// Text
									$filter2 .= "$open_st ProfileCustomValue = '".$val."' $close_st";
									$_SESSION[$key] = $val;

								} elseif ($ProfileCustomType["ProfileCustomType"] == 3 || $ProfileCustomType["ProfileCustomType"] == 9) {
									// Dropdown
									if($ProfileCustomType["ProfileCustomType"] == 3 ){
										$filter2 .="$open_st ProfileCustomValue = '".$val."' $close_st";

									// Dropdown Multi-Select	
									}elseif($ProfileCustomType["ProfileCustomType"] == 9 ){
										
											$val = stripslashes($val);
											if(!empty($val)){
											
												if(strpos($val,",") === false){
													$filter2 .= $open_st;
													$val2 = $val;
													$filter2 .= $wpdb->prepare(" FIND_IN_SET(%s,ProfileCustomValue) > 0 AND ProfileCustomValue LIKE %s",$val2,"%".$val2."%");
													/*$val2 = addslashes(addslashes($val2));
													$filter2 .= $wpdb->prepare(" ProfileCustomValue NOT LIKE %s AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND ProfileCustomValue LIKE %s AND ProfileCustomValue NOT LIKE %s AND ProfileCustomValue NOT LIKE %s  OR  FIND_IN_SET(%s,ProfileCustomValue) > 0)   ",$val2.",%",$val."-",$val." Months",$val." Months","-".$val." Months","%".$val."%","%".$val."-%","%".$val2." Months%",$val2);
													*/
													$filter2 .= $close_st;

												} else {
													
													$likequery = array_filter(explode(",", $val));
													$likecounter = count($likequery);
													$i=1; 
								
													foreach($likequery as $like){
														$i++;
													
																	if($like!="") {
																		$val2 = addslashes(addslashes($like));
																	 	$sr_data .= $wpdb->prepare("(FIND_IN_SET(%s,ProfileCustomValue) > 0)".(($i <= $likecounter)?" AND ":""),$like);
																		//$sr_data .= $wpdb->prepare(" (FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND ProfileCustomValue LIKE %s AND ProfileCustomValue NOT LIKE %s AND ProfileCustomValue NOT LIKE %s OR  FIND_IN_SET(%s,ProfileCustomValue) > 0)     ".(($i <= $likecounter)?" OR ":""),$like."-",$like." Months",$like." Months","-".$like." Months","%".$val2."%","%".$val2."-%","%".$val2." Months%",$like);
															
																	}
																						//Commented to fix checkbox issue
								

													}

																		$filter2 .= "$open_st  ".$sr_data."  $close_st";

												}

												$_SESSION[$key] = $val;
											} else {
												$_SESSION[$key] = "";
											}
									}

								} elseif ($ProfileCustomType["ProfileCustomType"] == 4) {
									// Textarea
									$filter2 .= "$open_st ProfileCustomValue LIKE ('%".$val."%') $close_st";
									$_SESSION[$key] = $val;

								} elseif ($ProfileCustomType["ProfileCustomType"] == 5) {
									//Checkbox

										$val = stripslashes($val);
									if(!empty($val)){
									
										if(strpos($val,",") === false){
											$filter2 .= $open_st;
											$val2 = $val;
											$filter2 .= $wpdb->prepare(" (FIND_IN_SET(%s,ProfileCustomValue) > 0 AND ",$val2);
											$val2 = addslashes(addslashes($val2));
											$filter2 .= $wpdb->prepare(" ProfileCustomValue NOT LIKE %s AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND ProfileCustomValue LIKE %s AND ProfileCustomValue NOT LIKE %s AND ProfileCustomValue NOT LIKE %s  OR  FIND_IN_SET(%s,ProfileCustomValue) > 0)   ",$val2.",%",$val."-",$val." Months",$val." Months","-".$val." Months","%".$val."%","%".$val."-%","%".$val2." Months%",$val2);
											$filter2 .= $close_st;

										} else {
											
											$likequery = array_filter(explode(",", $val));
											$likecounter = count($likequery);
											$i=1; 
						
											foreach($likequery as $like){
												$i++;
											
															if($like!="") {
																$val2 = addslashes(addslashes($like));
																$sr_data .= $wpdb->prepare(" (FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND FIND_IN_SET(%s,ProfileCustomValue) = 0 AND ProfileCustomValue LIKE %s AND ProfileCustomValue NOT LIKE %s AND ProfileCustomValue NOT LIKE %s OR  FIND_IN_SET(%s,ProfileCustomValue) > 0)     ".(($i <= $likecounter)?" OR ":""),$like."-",$like." Months",$like." Months","-".$like." Months","%".$val2."%","%".$val2."-%","%".$val2." Months%",$like);
															}
														

											}
											//Commented to fix checkbox issue
											$filter2 .= "$open_st (".$sr_data.") $close_st";

										}

										$_SESSION[$key] = $val;
									} else {
										$_SESSION[$key] = "";
									}
									/*$val = stripslashes($val);
									if(!empty($val)){
									
										if(strpos($val,",") === false){
											$filter2 .= $open_st;
											$val2 = $val;
											$filter2 .= $wpdb->prepare(" FIND_IN_SET(%s,ProfileCustomValue) > 0 OR",$val2);
											$val2 = addslashes(addslashes($val2));
											$filter2 .= $wpdb->prepare(" ProfileCustomValue LIKE %s","%".$val2."%");
											$filter2 .= $close_st;
										} else {
											
											$likequery = array_filter(explode(",", $val));
											$likecounter = count($likequery);
											$i=1; 

											foreach($likequery as $like){
												$i++;
											
															if($like!="") {
																$val2 = addslashes(addslashes($like));
																$sr_data .= $wpdb->prepare(" ProfileCustomValue LIKE %s OR  FIND_IN_SET(%s,ProfileCustomValue) > 0".(($i <= $likecounter)?" OR ":""),"%".$val2."%",$like);
																
															}
														

											}
											//Commented to fix checkbox issue
											$filter2 .= "$open_st (".$sr_data.") $close_st";

											
											
										}

										$_SESSION[$key] = $val;
									} else {
										$_SESSION[$key] = "";
									}*/
									
								} elseif ($ProfileCustomType["ProfileCustomType"] == 6) {
									//Radiobutton 
									$val = implode("','",explode(",",$val));
									$filter2 .= "$open_st ProfileCustomValue LIKE ('%".$val."%')  $close_st";
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
								}elseif ($ProfileCustomType["ProfileCustomType"] == 10) {
									// Date
									list($from, $to) = explode(",", $val);

									$filter2 .= "$open_st ProfileCustomDateValue BETWEEN '".$from."' AND '".$to."' $close_st";
									$_SESSION[$key] = $val;

								} 

							} // if not empty
						} // end if
					} // end for each

					if(count($filterDropdown) > 0){
						$filter2 .="$open_st ProfileCustomValue IN ('".implode("','",$filterDropdown)."') $close_st";
					}

					
					$filter .= $filter2;
					$filter = str_replace(array("\n","\t","\r")," ", $filter);
					$filter = str_replace(")(", ") OR (", $filter);

				
				}

				
			/**
			 * Only Show from Casting Cart
			 * Profile Search Saved 
			 */
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
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
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

			$filter = "";

			$rb_agency_options_arr = get_option("rb_agency_options");
			// Sort by date 
			$rb_agency_option_profilelist_sortbydate = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortbydate']) ? $rb_agency_options_arr['rb_agency_option_profilelist_sortbydate']: 0;
			
			if($rb_agency_option_profilelist_sortbydate){
				   $atts["sort"] = "cmux.ProfileCustomDateValue";
			}

			 $filter .= " GROUP BY profile.ProfileID";
		


			
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
					$filter .= " ORDER BY $sort DESC ";
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
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
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

		public static function search_results($sql_where, $query_type = 0, $castingcart = false, $arr_query = array()){

			global $wpdb;

			$sqlCasting_userID = "";

			switch ($query_type) {

				/* 
				 * standard query
				 */
				case 0:
					$sql = "SELECT profile.*, cmux.* FROM ". table_agency_profile ." as profile LEFT JOIN ".table_agency_customfield_mux." as cmux ON cmux.ProfileID = profile.ProfileID WHERE ". $sql_where . self::$order_by;
					break;

				/* 
				 * query for favorites
				 */
				 case 1:
					$sqlFavorite_userID  = " fav.SavedFavoriteTalentID = profile.ProfileID  AND fav.SavedFavoriteProfileID = '".rb_agency_get_current_userid()."' ";
					$sql = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileContactNameFirst, profile.ProfileContactNameLast, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileIsActive, profile.ProfileLocationState, profile.ProfileID as pID, fav.SavedFavoriteTalentID, fav.SavedFavoriteProfileID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE " . $sql_where . " AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite." fav WHERE $sqlFavorite_userID AND profile.ProfileIsActive = 1 GROUP BY fav.SavedFavoriteTalentID"  . self::$order_by;
					break;

				/* 
				 * casting cart
				 */
				case 2:
					// Get User ID
					$user = get_userdata(rb_agency_get_current_userid());  

					// check if user is admin, if yes this allow the admin to view other users cart 
					// if(isset($user->user_level) && $user->user_level==10 AND get_query_var('target')!="casting") {
					// 	$sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID AND cart.CastingCartProfileID = '".get_query_var('target')."' ";
					// } else {
						if(current_user_can("edit_posts")){
							$sqlCasting_userID .= " cart.CastingCartTalentID = profile.ProfileID ";
							if(isset($_GET["Job_ID"]) && !empty($_GET["Job_ID"])){
								$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingJobID = %s",$_GET["Job_ID"]);
							}else{
								//$sqlCasting_userID .= " AND cart.CastingCartTalentID <> '' AND cart.CastingCartTalentID IS NOT NULL ";
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
							}
						}else{
							$sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID";
							if(isset($_GET["Job_ID"]) && !empty($_GET["Job_ID"])){
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
								$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingJobID = %s",$_GET["Job_ID"]);
							}else{
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
							}
						}	
						
					//}

					// Execute the query showing casting cart
					$sql = "SELECT profile.ProfileID, 
								   profile.ProfileGallery, 
								   profile.ProfileContactDisplay,
								   profile.ProfileContactNameFirst, 
								   profile.ProfileContactNameLast, 
								   profile.ProfileDateBirth, 
								   profile.ProfileIsActive, 
								   profile.ProfileLocationState, 
								   profile.ProfileID as pID, 
								   cart.CastingCartTalentID, 
								   cart.CastingCartTalentID, 
								   (SELECT media.ProfileMediaURL FROM ". 
								   			table_agency_profile_media ." media 
								   			WHERE 
								   			profile.ProfileID = media.ProfileID 
								   			AND 
								   			media.ProfileMediaType = \"Image\" 
								   			AND 
								   			media.ProfileMediaPrimary = 1) 
											AS 
								   ProfileMediaURL,
								   (SELECT cmux.ProfileCustomDateValue FROM ".
								   			table_agency_customfield_mux." as cmux
								   			WHERE 
								   			cmux.ProfileID = profile.ProfileID
								   			LIMIT 1
								   			) 
											AS
									ProfileDueDate
								   FROM ". table_agency_profile ." profile 
								   INNER JOIN  ".table_agency_castingcart." cart  
								   WHERE $sqlCasting_userID 
								   AND ProfileIsActive = 1 
								   GROUP BY profile.ProfileID";  
			}

			if(self::$error_debug || self::$error_debug_query){
				self::$error_checking[] = array('-MAIN_QUERY-',$sql);
				echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
			}
			/*
			 * check if search is admin or public
			 */
			if(is_admin()){
				
				return self::search_result_admin($sql,$arr_query );
			} else {
				
				return self::search_result_public($sql, $castingcart);

			}

		}

	/* 
	 * search for public 
	 */
		public static function search_result_public($sql, $castingcart = ''){

			global $wpdb;
			/* 
			 * format profile list per profile
			 */
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_profilelist_sortby		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
			$results = $wpdb->get_results($sql,ARRAY_A);
			$count = count($results);
			$profile_list = "";
			$all_html = "";
			$all_html.='<div id="rbfilter-sort">';
			$all_html.='<div class="rbtotal-results">Total Results : '.$count.' </div>';
			$all_html.='<div class="rbsort">';

			/*
			 *  sorting options is activated if set on in admin/settings
			 */
			if($rb_agency_option_profilelist_sortby && !self::$castingcart && !strpos($_SERVER['REQUEST_URI'],'profile-favorite') > -1){

				// Enqueue our js script
				wp_enqueue_script( 'list_reorder', plugins_url('rb-agency/js/list_reorder.js'),array('jquery'));

				// Dropdown
				
				$all_html.='<label>Sort By: </label>
						<select id="sort_by">
							<option value="">Sort List</option>
							<option value="1">Age</option>
							<option value="2">Name</option>
							<option value="3">Date Joined</option>
							<option value="2">Display Name</option>
						</select>
						<select id="sort_option">
							<option value="">Sort Options</option>
						</select>';
			}
			$all_html.='</div></div>';
			$all_html .= "	<hr />";
			if ($count > 0){
				
				$castingcart_results = array();
				if(defined("table_agency_castingcart")){
					$castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."'");
				}
				$favorites_results = $wpdb->get_results("SELECT SavedFavoriteTalentID FROM ".table_agency_savedfavorite." WHERE SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'");
				
				$arr_castingcart = array();
				foreach ($castingcart_results as $key) {
					array_push($arr_castingcart, $key->CastingCartTalentID);
				}

				$arr_favorites = array();
				foreach ($favorites_results  as $key) {
					array_push($arr_favorites, $key->SavedFavoriteTalentID);
				}
				foreach($results as $profile) {
					$availability = '';
				
					if(!empty($castingcart) && isset($_GET["Job_ID"]) && !empty($_GET["Job_ID"])){
						 $query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
						$prepared = $wpdb->prepare($query,$profile["ProfileID"],$_GET["Job_ID"]);
						$data = $wpdb->get_row($prepared);
						$count2 = $wpdb->num_rows;
						if($count2 >= 1){
							$availability = $data->status;
						}
					}
						
					$profile_list .= self::search_formatted($profile,$arr_favorites,$arr_castingcart, $availability );
				}

				if(self::$error_debug){
					self::$error_checking[] = array('search_formatted','success');
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
				}

				/* 
				 * rb agency options
				 */
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_persearch  = isset($rb_agency_options_arr['rb_agency_option_persearch']) ? (int)$rb_agency_options_arr['rb_agency_option_persearch']:0;
				$rb_agency_option_profilelist_count  = isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
				$rb_agency_option_profilelist_favorite  = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;
				
				/* 
				 * this is the upper header html of the profile list
				 */

				$all_html .=  "<script type='text/javascript' src='".rb_agency_BASEDIR."js/resize.js'></script>";
				$all_html .= '<div id="profile-results-info">';
				/*
				if ($rb_agency_option_profilelist_favorite){ 
					$all_html .= "<div class=\"profile-results-info-countpage\">\n";
					$all_html .= $count;  // Echo out the list of paging. 
					$all_html .= "</div>\n";
				}
				*/
				if ($rb_agency_option_profilelist_count) {
					$items = $count;
					$countList = $items;
					$all_html .= "<div id=\"profile-results-info-countrecord\">\n";
					$all_html .=  __("Displaying", rb_agency_TEXTDOMAIN) ." <strong>". (isset($countList)?$countList:0) ."</strong> ". __("of", rb_agency_TEXTDOMAIN) ." ". (isset($items)?$items:0) ." ". __(" records", rb_agency_TEXTDOMAIN) ."\n";
					$all_html .= "</div>\n";
				}
				/*		
				if (self::$castingcart){   //allow email to admin casting
					$all_html .= "<div>\n";
					$all_html .= '	<a id="sendemail" href="javascript:;">Email to Admin</a>';
					$all_html .= "</div>\n";
				}*/
				$all_html .= '</div>';
				/* 
				 * wrap profile listing
				 */
				$all_html .="<div id='profile-list'>".$profile_list."</div>";

				if(self::$error_debug){
					self::$error_checking[] = array('search_result_public',$all_html);
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
				}



				return $all_html;

			} else {

				/* 
				 * No results Found.
				 */
				$no_rec_html = '<div class=\"rbclear\"></div>' . __("No Profiles Found", rb_agency_TEXTDOMAIN);
				if(self::$error_debug){
					self::$error_checking[] = array('search_result_public',$no_rec_html);
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
				}
				return $no_rec_html;

			}

		}


		/* 
		 * search for admin 
		 */
		public static function search_result_admin($sql, $arr_query  = array()){

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
				$results = $wpdb->get_results($sql,ARRAY_A);
				$count = count($results);

				/* 
				 * initialize html 
				 */
				$displayHtml = "";
				$displayHtml .=  "  <div class=\"boxblock-holder\">\n";
				$displayHtml .=  "<h2 class=\"title\">Search Results: " . $count . "</h2>\n";
				unset($arr_query["limit"]);
				unset($arr_query["perpage"]);
				
				$query_built =  http_build_query($arr_query);
               if (($count > ($rb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
					$displayHtml .=  "<div id=\"message\" class=\"error\"><p>Search exceeds ". $rb_agency_option_persearch ." records first ". $rb_agency_option_persearch ." displayed below.  <a href='". admin_url("admin.php?page=". $_GET['page']) ."&". (isset($sessionString)?$sessionString:"") ."&limit=none&".$query_built."'><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
				}
				$displayHtml .=  "       <form method=\"get\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"forceCart\" value=\"".(!is_array(RBAgency_Common::session('cartArray'))?RBAgency_Common::session('cartArray'):"") ."\" />\n";
				$displayHtml .=  "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
				$displayHtml .=  "        <thead>\n";
				$displayHtml .=  "            <tr class=\"thead\">\n";
				$displayHtml .=  "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=rb_agency_profiles&sort=ProfileID&dir=". (isset($sortDirection)?$sortDirection:"") ."\">". __("ID", rb_agency_TEXTDOMAIN) ."</a></th>\n";
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

				foreach($results as $data){
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
						$displayHtml .=  "                	<h2>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) ."</h2>\n";
						$displayHtml .=  "                </div>\n";
						$displayHtml .=  "                <div class=\"row-actions\">\n";
						$displayHtml .=  "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
						if(isset($data['ProfileGallery'])){
						$displayHtml .=  "                    <span class=\"review\"><a href=\"". rb_agency_PROFILEDIR . (isset($rb_agency_UPLOADDIR)?$rb_agency_UPLOADDIR:"") . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
						}
						$displayHtml .=  "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=deleteRecord&amp;ProfileID=". $ProfileID ."\" onclick=\"if ( confirm('You are about to delete the model ". ucfirst($data['ProfileContactNameFirst']) ." ". ucfirst($data['ProfileContactNameLast']) ."') ) { return true;}return false;\">Delete</a></span>\n";
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
								$displayHtml .=  "<div><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". get_stateabv_by_id($data['ProfileLocationState']) ." ". $data['ProfileLocationZip'] ."</div>\n";
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
						if (!empty($data['ProfileType'])) {
								if(strpos($data['ProfileType'],",") > -1){
										$t = explode(",",$data['ProfileType']);
										$ptype = "";
										$ptyp = array();
										foreach($t as $val){
												$ptyp[] = retrieve_title($val);
										}
										$ptype = implode(",",$ptyp);
								} else {
										$ptype = retrieve_title($data['ProfileType']);
								}
								$displayHtml .= "<div><strong>". __("Profile Type", rb_agency_TEXTDOMAIN) .":</strong> ".$ptype."</div>\n";
						}
						if (!empty($data['ProfileContactPhoneWork'])) {
								$displayHtml .=  "<div><strong>". __("Phone Work", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
						}

						$resultsCustomPrivate =  $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC");
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
							$displayHtml .=  "				<div class=\"image\" style=\"height:240px\"><img style=\"width: 150px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $p_image ."\" /></div>\n";
						} else {
							$displayHtml .=  "				<div class=\"image no-image\">NO IMAGE</div>\n";
						}

						$displayHtml .=  "            </td>\n";
						$displayHtml .=  "        </tr>\n";

				}

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
				if(isset($_SESSION['cartArray'] )){

					$cartProfiles = $_SESSION['cartArray'];
					foreach ($cartProfiles as $key) {
						$displayHtml .= "<input type=\"hidden\" name=\"ProfileID[]\" value=\"".$key."\"/> ";
					}
				}
				
				$displayHtml .=  "      	<input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_search') ."\" class=\"button-primary\" />\n";
				$displayHtml .=  "          <a href=\"#\" onClick=\"testPrint(1);\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "<script> function testPrint(type){

					var checkboxes = document.getElementsByName('ProfileID[]');
						var vals = '';
						for (var i=0, n=checkboxes.length;i<n;i++) {
						  if (checkboxes[i].checked) 
						  {
						  vals += ','+checkboxes[i].value;
						  }
						}
						if (vals) vals = vals.substring(1);
						window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD='+type+'&id='+vals,'mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes');
				}
					</script>" ; 
				$displayHtml .=  "          <a href=\"#\" onClick=\"testPrint(0); \" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
				$displayHtml .=  "     </p>\n";
				$displayHtml .=  "  </form>\n";
				$displayHtml .=  "</div>";
				
				return $displayHtml;

		}


		/*
		 * Format Profile
		 * Create list from IDs
		 */
		public static function search_formatted($dataList,$arr_favorites = array(),$arr_castingcart = array(), $casting_availability = ''){

			/* 
			 * rb agency options
			 */
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_profilelist_castingcart 	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart']:0;
			$rb_agency_option_profilelist_favorite		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;
			$rb_agency_option_privacy					 = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
			$rb_agency_option_profilelist_count			 = isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
			$rb_agency_option_profilelist_perpage		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_perpage']) ?$rb_agency_options_arr['rb_agency_option_profilelist_perpage']:0;
			$rb_agency_option_profilelist_sortby		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
			$rb_agency_option_layoutprofilelist		 	 = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelist']:0;
			$rb_agency_option_profilelist_expanddetails	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']) ? $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']:0;
			$rb_agency_option_locationtimezone 			 = isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? (int)$rb_agency_options_arr['rb_agency_option_locationtimezone']:0;
			$rb_agency_option_profilenaming				 = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
			$rb_agency_option_profilelist_printpdf 	     = isset($rb_agency_options_arr['rb_agency_option_profilelist_printpdf']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_printpdf']:0;
			$rb_agency_option_profilelist_thumbsslide	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']:0;
			
			$ProfileContactNameFirst = $dataList["ProfileContactNameFirst"];
			$ProfileContactNameLast = $dataList["ProfileContactNameLast"];
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " " . $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					// If John-D already exists, make John-D-1
					for ($i = 'a', $j = 1; $j <= 26; $i++, $j++) {
						if (isset($ar) && in_array($i, $ar)){
							$ProfileContactDisplay = $ProfileContactNameFirst . " " . $i .'-'. $j;
						} else {
							$ProfileContactDisplay = $ProfileContactNameFirst . " " . substr($ProfileContactNameLast, 0, 1);
						}
					}

				}elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID " . $ProfileID;
				} elseif ($rb_agency_option_profilenaming == 4) {
					$ProfileContactDisplay = $ProfileContactNameFirst;
				} elseif ($rb_agency_option_profilenaming == 5) {
					$ProfileContactDisplay = $ProfileContactNameLast;
				}else{
					$ProfileContactDisplay = $dataList["ProfileContactDisplay"];
				}
			

			/* 
			 * initialize html
			 */
			$displayHTML ="";
			$displayHTML .= "<div id=\"rbprofile-".$dataList["ProfileID"]."\" class=\"rbprofile-list profile-list-layout0\" >\n";
			$displayHTML .= '<input id="br'.$dataList["ProfileID"].'" type="hidden" class="p_birth" value="'.$dataList["ProfileDateBirth"].'">';
			$displayHTML .= '<input id="nm'.$dataList["ProfileID"].'" type="hidden" class="p_name" value="'.$dataList["ProfileContactDisplay"].'">';
			$displayHTML .= '<input id="cr'.$dataList["ProfileID"].'" type="hidden" class="p_created" value="'.(isset($dataList["ProfileDateCreated"])?$dataList["ProfileDateCreated"]:"").'">';
            $displayHTML .= '<input id="du'.$dataList["ProfileID"].'" type="hidden" class="p_duedate" value="'.(isset($dataList["ProfileDueDate"])?$dataList["ProfileDueDate"]:"").'">';
           $displayActions = "";  
			if(is_user_logged_in()){
	            $displayActions = "<div class=\"rb_profile_tool\">";
	            if($rb_agency_option_profilelist_favorite){
		        //    $displayActions .= "<div class=\"favorite\"><a href=\"javascript:;\" title=\"".(in_array($dataList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")."\" attr-id=\"".$dataList["ProfileID"]."\" class=\"".(in_array($dataList["ProfileID"], $arr_favorites)?"active":"inactive")." favorite\"><strong>&#9829;</strong></a>&nbsp;<span><a href=\"".get_bloginfo("url")."/profile-favorite/\">Favorite</a></span></div>";
		        	    $displayActions .= "<div class=\"favorite\"><a href=\"javascript:;\" title=\"".(in_array($dataList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")."\" attr-id=\"".$dataList["ProfileID"]."\" class=\"".(in_array($dataList["ProfileID"], $arr_favorites)?"active":"inactive")." favorite\">".(in_array($dataList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")."</a></div>";
		        
		        }
		        $p_image = rb_get_primary_image($dataList["ProfileID"]); 

		        if($rb_agency_option_profilelist_castingcart && !empty($p_image) ){
	           	//	$displayActions .= "<div class=\"casting\"><a href=\"javascript:;\" title=\"".(in_array($dataList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")."\"  attr-id=\"".$dataList["ProfileID"]."\"  class=\"".(in_array($dataList["ProfileID"], $arr_castingcart)?"active":"inactive")." castingcart\"><strong>&#9733;</strong></a>&nbsp;<span><a href=\"".get_bloginfo("url")."/profile-casting/\">Casting Cart</a></span></div>";
	            		$displayActions .= "<div class=\"casting\"><a href=\"javascript:;\" title=\"".(in_array($dataList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")."\"  attr-id=\"".$dataList["ProfileID"]."\"  class=\"".(in_array($dataList["ProfileID"], $arr_castingcart)?"active":"inactive")." castingcart\">".(in_array($dataList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")."</a></div>";
	            }
	            $displayActions .= "</div>";
				
				if(!empty($casting_availability)){
						 if($casting_availability == 'available'){
							 $displayActions .= "<span style=\"text-align:center;color:#2BC50C;font-weight:bold;width:80%;padding:10px;display:block;\">Available</span>\n";
						 }else{
							 $displayActions .= "<span style=\"text-align:center;color:#EE0F2A;font-weight:bold;width:80%;padding:10px;display:block;\">Not Available</span>\n";
						 }
				}
				
	        }
			/* 
			 * determine primary image
			 */
			$images = "";
			$p_image = rb_get_primary_image($dataList["ProfileID"]); 
			if ($p_image){ 
				if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){
					if($rb_agency_option_profilelist_thumbsslide==1){  //show profile sub thumbs for thumb slide on hover
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
			$uid = rb_agency_get_current_userid();
								
			if(get_query_var('type') == "casting" && $uid > 0){
				$displayHTML .= "<input type=\"checkbox\" name=\"profileid\" value=\"".$dataList["ProfileID"]."\"/>";
			}
			$displayHTML .= "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\" class=\"scroll\">". stripslashes($ProfileContactDisplay) ."</a></h3>\n";
			if ($rb_agency_option_profilelist_expanddetails) {
				$displayHTML .= "     <div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span>";
				if($dataList["ProfileLocationState"]!=""){
					$displayHTML .= "<span class=\"divider\">, </span><span class=\"details-state\">". rb_agency_getStateTitle($dataList["ProfileLocationState"],true) ."</span>";
				}				
				$displayHTML .= "</div>\n";
				$displayHTML .=  $displayActions;
			}
			

			if(is_user_logged_in() && function_exists("rb_agency_get_miscellaneousLinks")){
				$displayHTML .= rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
			}			
			$displayHTML .=" </div> <!-- .profile-info --> \n";
			$displayHTML .=" </div> <!-- .rbprofile-list --> \n";
			if(self::$error_debug){		
				self::$error_checking[] = array('search_formatted',$displayHTML);
				echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
			}

			return $displayHTML;

		}
	/* 
	* Move view_custom_fields.php code in class file
	* genrate and display custome field
	*/
	public static function view_custom_fields($data1){

			$ProfileCustomID = $data1['ProfileCustomID'];
			$ProfileCustomTitle = $data1['ProfileCustomTitle'];
			$ProfileCustomType = $data1['ProfileCustomType'];
			$ProfileCustomValue = $data1['ProfileCustomValue'];
			$ProfileCustomDateValue = $data1['ProfileCustomDateValue'];

			if($ProfileCustomType!=4)	{

			/*
			 * Opening of Field or Div
			 */
				if($ProfileCustomType == 7 OR $ProfileCustomType == 5 OR $ProfileCustomType == 6){
					echo "<fieldset class=\"search-field multi profilecustomfieldid_".$ProfileCustomID."\" id=\"profilecustomfieldid_".$ProfileCustomID."\">";
				} else {
					echo "<div class=\"search-field single profilecustomfieldid_".$ProfileCustomID."\" id=\"profilecustomfieldid_".$ProfileCustomID."\">";	
				}

			/*
			 * Custom Field Contents Goes here
			 */

				// SET Label for Measurements
				// Imperial(in/lb), Metrics(ft/kg)
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_unittype  = $rb_agency_options_arr['rb_agency_option_unittype'];
				$measurements_label = "";
				/*
				0- metric
				1 - cm
				2- kg
				3 - inches/feet
				1-imperials	
				1- inches
				2- pounds
				3-inches/feet
				*/
				if ($ProfileCustomType == 7) { //measurements field type
					if($rb_agency_option_unittype ==0){ // 0 = Metrics(ft/kg)
						if($data1['ProfileCustomOptions'] == 1) {
							$measurements_label  ="<em> (cm)</em>";
						} elseif($data1['ProfileCustomOptions'] == 2) {
							$measurements_label  ="<em> (kg)</em>";
						} elseif($data1['ProfileCustomOptions'] == 3) {
							$measurements_label  ="<em> (ft/in)</em>";
						}
					} elseif($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
						if($data1['ProfileCustomOptions'] == 1){
							$measurements_label  ="<em> (in)</em>";
						} elseif($data1['ProfileCustomOptions'] == 2) {
							$measurements_label  ="<em> (lb)</em>";
						} elseif($data1['ProfileCustomOptions'] == 3) {
							$measurements_label  ="<em> (ft/in)</em>";
						}
					}
				}

				if($ProfileCustomType == 7 OR $ProfileCustomType == 5 OR $ProfileCustomType == 6){
					echo "<legend>".$data1['ProfileCustomTitle'].$measurements_label."</legend>";
				} else {
					echo "<label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label>";
				}

				if ($ProfileCustomType == 1) { //TEXT		
					echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>";
				} elseif ($ProfileCustomType == 2) { // Min Max

					$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data1['ProfileCustomOptions'],"}"),"{"));
					list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);

					if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>";
						echo "</div>";
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>";
						echo "</div>";
					} else {
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>";
						echo "</div>";
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>";
						echo "</div>";
					}

				} elseif ($ProfileCustomType == 3 || $ProfileCustomType == 9) { // SELECT

					list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);

					$data = explode("|",$option1);
					$data2 = explode("|",$option2);

					echo "<label>".$data[0]."</label>";
					echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" ".($ProfileCustomType == 9?"multiple":"").">";
					echo "<option value=\"\">--</option>";

						foreach($data as $val1){

							if($val1 != end($data) && $val1 != $data[0]){

								 $isSelected = "";
								if($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]==$val1 || in_array(stripslashes($val1), explode(",",$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]))){
									$isSelected = "selected=\"selected\"";
									echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
								} else {
									echo "<option value=\"".$val1."\" >".$val1."</option>"; 
								}

							}
						}
					echo "</select></<div>div>";

				} elseif ($ProfileCustomType == 4) {
					echo "<div><textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $_REQUEST["ProfileCustomID". $data1['ProfileCustomID']] ."</textarea></div>";
				} elseif ($ProfileCustomType == 5) {
                      echo "<div>";
					$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);

						foreach($array_customOptions_values as $val){
							if(isset($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']])){ 

								$dataArr = explode(",",implode(",",explode("','",$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']])));
								if(in_array($val,$dataArr,true)){
									echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
									echo "<span>&nbsp;&nbsp;". $val."</span></label>";
								} else {
									if($val !=""){
										echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
										echo "<span>&nbsp;&nbsp;". $val."</span></label>";
									}
								}
							} else {
								if($val !=""){
									echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
									echo "<span>&nbsp;&nbsp;". $val."</span></label>";
								}
							}
						}

					echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/>";
					 echo "</div>";				   
				}elseif ($ProfileCustomType == 6) {
					 echo "<div>";
					$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);

					foreach($array_customOptions_values as $val) {

						if(isset($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]) && $_REQUEST["ProfileCustomID". $data1['ProfileCustomID']] !=""){ 

							$dataArr = explode(",",implode(",",explode("','",$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']])));

							if(in_array($val,$dataArr) && $val !="") {
								echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
								echo "<span>&nbsp;&nbsp;". $val."</span></label>";
							} else {
								if($val !="") {
									echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
									echo "<span>&nbsp;&nbsp;". $val."</span></label>";
								}
							}
						} else {
							if($val !="") {
								echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
								echo "<span>&nbsp;&nbsp;". $val."</span></label>";	
							}
						}
					}
					echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/>";
					echo "</div>";
				} elseif ($ProfileCustomType == 7) {


					list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);

						if($data1['ProfileCustomTitle']=="Height" && $rb_agency_option_unittype == 1 && $data1['ProfileCustomOptions']==3){

							echo "<div><label>Min</label><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
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

							echo "<div><label>Max</label><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
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
							echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
							."_min\">Min</label><input value=\""
							.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
							."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
							.$data1['ProfileCustomID']."[]\" /></div>";

							echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
							."_max\">Max</label><input value=\"".$max_val
							."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
						}

				} elseif ($ProfileCustomType == 10) { //Date
				
						echo "<input type=\"text\" id=\"rb_datepicker". $data1['ProfileCustomID']."\" class=\"rb-datepicker\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."_date\" value=\"". $ProfileCustomDateValue ."\" /><br />\n";						
					    echo "<script type=\"text/javascript\">\n\n";
					    echo "jQuery(function(){\n\n";
					    echo "jQuery(\"input[name=ProfileCustomID". $data1['ProfileCustomID'] ."_date]\").val('". $ProfileCustomDateValue ."');\n\n";
					   	echo "});\n\n";
					    echo "</script>\n\n";

				}		

				/*
				 * Close Div or FieldSet
				 */
				if($ProfileCustomType==7 || $ProfileCustomType==5 || $ProfileCustomType == 6){
					echo "</fieldset>";
				} else {
					echo "</div>";
					
				}
			}

	}

}

?>