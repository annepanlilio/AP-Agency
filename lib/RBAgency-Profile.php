<?php

/*
 * RBAgency_Profile Class
 *
 * These are profile specific functions
 */

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
		protected static $paging = 1;

	/**
	 * Search Form
	 * Process Search
	 *
	 * @param array $atts		Search form attributes
	 * @param array $args		Search form arguments
	 * @param array $type		Which layout type? (0: Simple, 1: Advanced)
	 * @param array $location	Where is it located? (0: Public, 1: Admin, 2)
	 */
		public static function search_form($atts ='', $args = '', $type = 0, $location = 0){

			/*
			 * Setup Requirements
			 */
				global $wpdb;
				$rb_agency_options_arr = get_option('rb_agency_options');
					$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:1;
					$rb_agency_option_formshow_location = isset($rb_agency_options_arr['rb_agency_option_formshow_location'])?$rb_agency_options_arr['rb_agency_option_formshow_location']:0;
					$rb_agency_option_formshow_name = isset($rb_agency_options_arr['rb_agency_option_formshow_name'])?$rb_agency_options_arr['rb_agency_option_formshow_name']:0;
					$rb_agency_option_formshow_type = isset($rb_agency_options_arr['rb_agency_option_formshow_type'])?$rb_agency_options_arr['rb_agency_option_formshow_type']:0;
					$rb_agency_option_formshow_gender = isset($rb_agency_options_arr['rb_agency_option_formshow_gender'])?$rb_agency_options_arr['rb_agency_option_formshow_gender']:0;
					$rb_agency_option_formshow_age = isset($rb_agency_options_arr['rb_agency_option_formshow_age'])?$rb_agency_options_arr['rb_agency_option_formshow_age']:0;
					$rb_agency_option_formshow_birthdate = isset($rb_agency_options_arr['rb_agency_option_formshow_birthdate'])?$rb_agency_options_arr['rb_agency_option_formshow_birthdate']:0;
					$rb_agency_option_formshow_displayname = isset($rb_agency_options_arr['rb_agency_option_formshow_displayname'])?$rb_agency_options_arr['rb_agency_option_formshow_displayname']:0;
					$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;
					$rb_agency_option_form_clearvalues = isset($rb_agency_options_arr['rb_agency_option_form_clearvalues'])?$rb_agency_options_arr['rb_agency_option_form_clearvalues']:0;

				// Which Target Location?
				if ($location == 0) {

					// Public Facing
					$rb_agency_searchurl = get_bloginfo("wpurl") ."/search-results/";

					if ($type == 0) {
						$search_layout = "simple";
					} else {
						$search_layout = "full";
					}

				} else {

					// Admin Back-end 
					$rb_agency_searchurl = admin_url("admin.php?page=rb_agency_search");

					if ($type == 0) {
						$search_layout = "simple";
					} else {
						$search_layout = "admin";
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
							var inputs = jQuery(".rbfield").find("input");
								for (var i = 0; i < inputs.length; i++) {
									switch (inputs[i].type) {
										case 'text':
											inputs[i].value = '';
											break;
										case 'radio':
											inputs[i].checked = false;
										case 'checkbox':
											inputs[i].checked = false;
									}
								}
							jQuery(".rbfield").find("select").prop('selectedIndex',0);
						});
					}

					jQuery("#rst_btn").rset();

					<?php if(!is_admin()){ ?>
						// TODO: What's this?
						/*jQuery.fn.css_ = function(){
							var el = this.get(0); var st; var returns = {};
							if(typeof(window.getComputedStyle) !== "undefined" && typeof(el) !== "undefined"){
								var camel = function(a,b){return b.toUpperCase();}
								st = window.getComputedStyle(el, null);
								try{
									for(var s=0; s < st.length; s++){
										var css_style = st[s];
										var cml = null;

										if(typeof(css_style.replace) !== "undefined"){
											cml = css_style.replace(/\-([a-z])/, camel);
										}
										var vl = st.getPropertyValue(css_style);
										returns[cml] = vl;
									}
								}catch(e){

								}
								return returns;
							}
							if(typeof(el) !== "undefined" && el.currentStyle){
								st = el.currentStyle;
								for(var prop in style){returns[prop] = st[prop];}
								return returns;
							}
							return this.css();
						}
						jQuery("#rst_btn").css(jQuery("#sr_pr").css_());*/
					<?php }?>
				});
				</script>
				<?php 


			/*
			 * Search Form
			 */

				echo "	<div id=\"profile-search-form-condensed\" class=\"rbform form-". (isset($search_layout)?$search_layout:"") ."\">\n";
				echo "		<form method=\"post\" enctype=\"multipart/form-data\" action=\"". (isset($rb_agency_searchurl)?$rb_agency_searchurl:"") ."\">\n";
				echo "			<input type=\"hidden\" name=\"form_action\" value=\"search_profiles\" />\n";
				echo "			<input type=\"hidden\" name=\"form_mode\" value=\"". (isset($type)?$type:0) ."\" />\n";

				// Show Profile Name
				if ( ($rb_agency_option_formshow_name > 0) || $search_layout == "admin" || ($search_layout == "full" && $rb_agency_option_formshow_name > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_firstname\" id=\"rb_firstname\">\n";
						echo "					<label for=\"namefirst\">". __("First Name", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"namefirst\" name=\"namefirst\" value=\"".(isset($_REQUEST["namefirst"])?$_REQUEST["namefirst"]:"")."\" /></div>\n";
						echo "				</div>\n";
						echo "				<div class=\"rbfield rbtext rbsingle rb_lastname\" id=\"rb_lastname\">\n";
						echo "					<label for=\"namelast\">". __("Last Name", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"namelast\" name=\"namelast\" value=\"".(isset($_REQUEST["namelast"])?$_REQUEST["namelast"]:"")."\" /></div>\n";
						echo "				</div>\n";
				}

				if ( ($rb_agency_option_formshow_displayname > 0) || (isset($search_layout) && $search_layout == "admin") || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_displayname > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_displayname\" id=\"rb_displayname\">\n";
						echo "					<label for=\"displayname\">". __("Display Name", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"displayname\" name=\"displayname\" value=\"".(isset($_REQUEST["displayname"])?$_REQUEST["displayname"]:"")."\" /></div>\n";
						echo "				</div>\n";
				}

				// Show Classification
				if ( ($rb_agency_option_formshow_type > 0) || isset($search_layout) && $search_layout == "admin" || ( isset($search_layout) &&  $search_layout == "full" && $rb_agency_option_formshow_type > 1) ) {
						echo "				<div class=\"rbfield rbselect rbsingle rb_profiletype\" id=\"rb_profiletype\">\n";
						echo "					<label for=\"type\">". __("Type", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "					<div>";
						echo "						<select name=\"profiletype\" id=\"type\">\n";
						echo "							<option value=\"\">". __("Any", RBAGENCY_TEXTDOMAIN) . "</option>\n";
														$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
														$results2 = $wpdb->get_results($query,ARRAY_A);
														foreach ($results2 as $key) {
															if (isset($_REQUEST['profiletype']) && $_REQUEST['profiletype']) {
																	if ($key["DataTypeID"] == @$_REQUEST["profiletype"]) {$selectedvalue = " selected"; } else {$selectedvalue = ""; }
															} else {$selectedvalue = ""; }
															echo "<option value=\"". $key["DataTypeID"] ."\"".$selectedvalue.">". $key["DataTypeTitle"] ."</option>\n";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";
				}

				// Show Profile Gender
				if ( ($rb_agency_option_formshow_gender > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_gender > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_gender\" id=\"rb_gender\">\n";
						echo "					<label for=\"gender\">". __("Gender", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "					<div>";
						$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
						$results2 = $wpdb->get_results($query2,ARRAY_A);
						echo "						<select name=\"gender\" id=\"gender\">\n";
						echo "							<option value=\"\">". __("Any Gender", RBAGENCY_TEXTDOMAIN) . "</option>\n";
														// Pul Genders from Database
														foreach ($results2 as $key) {
																if(isset($key["GenderID"]))
																echo "<option value=\"". $key["GenderID"] ."\"".selected(isset($_REQUEST['gender'])?$_REQUEST['gender']:"", $key["GenderID"],false).">". $key["GenderTitle"] ."</option>\n";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";
				}

				// Show Profile Age
				if ( ($rb_agency_option_formshow_age > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_age > 1) ) {
						echo "				<div class=\"rbfield rbtext rbmulti rb_datebirth\" id=\"rb_datebirth\">\n";
						echo "					<label for=\"datebirth_min datebirth_max\">". __("Age", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "					<div>\n";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_min\">". __("Min", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_min\" name=\"datebirth_min\" value=\"".(isset($_REQUEST['datebirth_min'])?$_REQUEST['datebirth_min']:"") ."\" />\n";
						echo "						</div>";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_max\">". __("Max", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_max\" name=\"datebirth_max\" value=\"".(isset($_REQUEST['datebirth_max'])?$_REQUEST['datebirth_max']:"") ."\" />\n";
						echo "						</div>\n";
						echo "					</div>\n";
						echo "				</div>\n";
				}

				// Show Profile birthdate
				if ( ($rb_agency_option_formshow_birthdate > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_birthdate > 1) ) {
						echo "				<div class=\"rbfield rbtext rbmulti rb_datebirth\" id=\"rb_datebirth2\">\n";
						echo "					<label for=\"datebirth_min2 datebirth_max\">". __("Birthdate", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "					<div>\n";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_min2\">". __("From", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "							<input  id=\"rb_datepicker_from_bd\" name=\"rb_datepicker_from_bd\" class=\"rb-datepicker stubby\" type=\"text\"  />";
						//echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_min2\" name=\"datebirth_min2\" value=\"".(isset($_REQUEST['datebirth_min2'])?$_REQUEST['datebirth_min2']:"") ."\" />\n";
						echo "						</div>";
						echo "						<div>\n";
						echo "							<label for=\"datebirth_max2\">". __("To", RBAGENCY_TEXTDOMAIN) . "</label>\n";
						echo "							<input  id=\"rb_datepicker_to_bd\" name=\"rb_datepicker_to_bd\" class=\"rb-datepicker stubby\" type=\"text\"  />";
						//echo "							<input type=\"text\" class=\"stubby\" id=\"datebirth_max2\" name=\"datebirth_max2\" value=\"".(isset($_REQUEST['datebirth_max2'])?$_REQUEST['datebirth_max2']:"") ."\" />\n";
						echo "						</div>\n";
						echo "					</div>\n";
						echo "				</div>\n";
						
						echo '<script type="text/javascript">
						jQuery(document).ready(function($){
							jQuery( "input[id=rb_datepicker_from_bd]").datepicker({
								dateFormat: "yy-mm-dd"
							});
							jQuery( "input[id=rb_datepicker_to_bd]").datepicker({
								dateFormat: "yy-mm-dd"
							});
						});
						</script>';

						//JS here..

				}

				// Show Location Search
				if ( ($rb_agency_option_formshow_location > 0) || isset($search_layout) && $search_layout == "admin" || (isset($search_layout) && $search_layout == "full" && $rb_agency_option_formshow_location > 1) ) {
						echo "				<div class=\"rbfield rbtext rbsingle rb_city\" id=\"rb_city\">\n";
						echo "					<label for=\"city\">". __("City", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"city\" name=\"city\" value=\"".(isset($_REQUEST["city"])?$_REQUEST["city"]:"")."\" /></div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbselect rbsingle rb_country\" id=\"rb_country\">\n";
												$location= site_url();
						echo "					<input type=\"hidden\" id=\"url\" value=\"". $location ."\">\n";
						echo "					<label for=\"country\">". __("Country", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div>\n";
						echo "						<select name=\"country\" id=\"country\" onchange='javascript:populateStates(\"country\",\"state\");'>\n";
						echo "							<option value=\"\">". __("Any Country", RBAGENCY_TEXTDOMAIN) ."</option>\n";
														$query_get ="SELECT * FROM `".table_agency_data_country."` ORDER BY CountryTitle ASC" ;
														$result_query_get = $wpdb->get_results($query_get);
														foreach($result_query_get as $r){
																$selected = isset($_REQUEST["country"]) && $_REQUEST["country"] ==$r->CountryID?"selected=selected":"";
						echo "							<option ". $selected ." value=\"".$r->CountryID."\">". $r->CountryTitle ."</option>\n";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbselect rbsingle rb_state\" id=\"rb_state\">\n";
						echo "					<label for=\"state\">". __("State", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div>";
						echo '						<select name="state" id="state">';
						echo '							<option value="">'. __("Any State", RBAGENCY_TEXTDOMAIN) .'</option>';
														$query_get ="SELECT * FROM `".table_agency_data_state."` WHERE CountryID = %d ORDER BY StateTitle ASC" ;
														$result_query_get = $wpdb->get_results($wpdb->prepare($query_get,(isset($_REQUEST["country"])?$_REQUEST["country"]:0)));
														foreach($result_query_get as $r){
															$selected = isset($_REQUEST["state"]) && $_REQUEST["state"] == $r->StateID?"selected=selected":"";
						echo "								<option ". $selected ." value=\"".$r->StateID."\">". $r->StateTitle ."</option>\n";
														}
						echo "						</select>\n";
						echo "					</div>\n";
						echo "				</div>\n";

						echo "				<div class=\"rbfield rbtext rbsingle rb_zip\" id=\"rb_zip\">\n";
						echo "					<label for=\"zip\">". __("Zip", RBAGENCY_TEXTDOMAIN) ."</label>\n";
						echo "					<div><input type=\"text\" id=\"zip\" name=\"zip\" value=\"".(isset($_REQUEST["zip"])?$_REQUEST["zip"]:"") ."\" /></div>\n";
						echo "				</div>\n";
				}// Show Location Search


			/*
			 * Custom Fields
			 */

			// Query Fields
				if(is_admin()){
					$field_sql = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomShowSearch, ProfileCustomShowSearchSimple
									FROM ". table_agency_customfields ." WHERE ProfileCustomView <= 2 ORDER BY ProfileCustomOrder ASC";
				} else {
					$field_sql = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomShowSearch, ProfileCustomShowSearchSimple
									FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 ORDER BY ProfileCustomOrder ASC";
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
								echo "<div class=\"rbfield rbtext rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
								echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>\n";
								echo "<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".(isset($_POST["ProfileCustomID".$ProfileCustomID])?$_POST["ProfileCustomID".$ProfileCustomID]:"")."\" /></div>";
								echo "</div>\n";

						/*
						 * Min Max
						 */
						} elseif($ProfileCustomType == 2) {

								echo "<div class=\"rbfield rbtext rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
									echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>\n";
									$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($ProfileCustomOptions,"}"),"{"));
									list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);

									if(is_array($_POST["ProfileCustomID".$ProfileCustomID])){
										$_POST["ProfileCustomID".$ProfileCustomID] = @implode(",",@$_POST["ProfileCustomID".$ProfileCustomID]);
										list($min_val2,$max_val2) =  @explode(",",@$_POST["ProfileCustomID".$ProfileCustomID]);
									} else {
										list($min_val2,$max_val2) =  @explode(",",@$_POST["ProfileCustomID".$ProfileCustomID]);
									}
									if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
										echo "<div>\n";
										echo "		<label for=\"ProfileCustomLabel_min first\" style=\"text-align:right;\">". __("Min ", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>\n";
										echo "</div>\n";
										echo "<div>\n";
										echo "		<label for=\"ProfileCustomLabel_max first\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>\n";
										echo "</div>\n";
									} else {
										echo "<div>\n";
										echo "		<label for=\"ProfileCustomLabel_min second\" style=\"text-align:right;\">". __("Min", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$min_val2."\" /></div>\n";
										echo "</div>\n";
										echo "<div>\n";
										echo "		<label for=\"ProfileCustomLabel_max second\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
										echo "		<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$max_val2."\" /></div>\n";
										echo "</div>\n";
									}
								echo "</div>\n";

						/*
						 * Dropdown
						 */
						} elseif($ProfileCustomType == 3 || $ProfileCustomType == 9 ) {
								echo "<div class=\"rbfield rbselect rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
								echo "	<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>\n";
								echo "	<div>\n";
								echo "		<select name=\"ProfileCustomID". $ProfileCustomID ."[]\" ".($ProfileCustomType == 9 ?"multiple":"").">\n";
								echo "			<option value=\"\">--</option>\n";
												$values = explode("|",$ProfileCustomOptions);
												foreach($values as $value){
													// Validate Value
													if(!empty($value)) {
														// Identify Existing Value
														$isSelected = "";
														if(strpos(strtolower($ProfileCustomTitle),"height") === false){
															if(isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && $_REQUEST["ProfileCustomID". $ProfileCustomID]==stripslashes($value)  || isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && in_array(stripslashes($value), $_REQUEST["ProfileCustomID".$ProfileCustomID])){
																$isSelected = "selected=\"selected\"";
																echo "		<option value=\"".stripslashes($value)."\" ".$isSelected .">".stripslashes($value)."</option>\n";
															} else {
																echo "		<option value=\"".stripslashes($value)."\" >".stripslashes($value)."</option>\n";
															}
														} else {
															$label = $value;
															$value = str_replace('"',"",$value);
															if(isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && $_REQUEST["ProfileCustomID". $ProfileCustomID]==stripslashes($value)  || isset($_REQUEST["ProfileCustomID". $ProfileCustomID]) && in_array(stripslashes($value), $_REQUEST["ProfileCustomID".$ProfileCustomID])){
																$isSelected = "selected=\"selected\"";
																echo "		<option value=\"".stripslashes($value)."\" ".$isSelected .">".stripslashes($label)."</option>\n";
															} else {
																echo "		<option value=\"".stripslashes($value)."\" >".stripslashes($label)."</option>\n";
															}
														}
													}
												}
								echo "		</select>\n";
								echo "	</div>\n";
								echo "</div>\n";


						/*
						 * Textbox
						 */
						} elseif($ProfileCustomType == 4) {
							/*
							TODO: Should we search text inside of text area?
											echo "<div class=\"rbfield rbsingle\">\n";
											echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>\n";
											echo "<textarea name=\"ProfileCustomID". $ProfileCustomID ."\">". $_REQUEST["ProfileCustomID". $ProfileCustomID] ."</textarea>\n";
											echo "</div>\n";
							 */
								echo "<div class=\"rbfield rbtext rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
								echo "<label for=\"ProfileCustomID". $ProfileCustomID ."\">". $ProfileCustomTitle ."</label>\n";
								echo "<div><input type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."\" value=\"".(isset($_POST["ProfileCustomID".$ProfileCustomID])?$_POST["ProfileCustomID".$ProfileCustomID]:"")."\" /></div>";
								echo "</div>\n";

						/*
						 * Checkbox
						 */
						} elseif($ProfileCustomType == 5) {
								echo "<div class=\"rbfield rbcheckbox rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
								echo "<label>". $ProfileCustomTitle ."</label>\n";
								echo "<div>\n";
								$array_customOptions_values = explode("|", $ProfileCustomOptions);
								foreach($array_customOptions_values as $val){

									if(isset($_POST["ProfileCustomID". $ProfileCustomID])){

										//$dataArr = @explode(",",@implode(",",@explode("','",stripslashes(@$_POST["ProfileCustomID". $ProfileCustomID]))));
										$dataArr = @$_POST["ProfileCustomID". $ProfileCustomID];
										if(in_array($val,$dataArr,true)){
											echo "<div ><label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
											echo "<span> ". $val."</span></label></div>\n";
										} else {
											if($val !=""){
												echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
												echo "<span> ". $val."</span></label></div>\n";
											}
										}
									} else {
										if($val !=""){
											echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
											echo "<span> ". $val."</span></label></div>\n";
										}
									}
								}

								echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>\n";
								echo "</div>\n";
								echo "</div>\n";

						/*
						 * Radio Button
						 */
						} elseif($ProfileCustomType == 6) {
								echo "<div class=\"rbfield rbradio rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
								echo "<label>". $ProfileCustomTitle ."</label>\n";
								echo "<div>\n";
								$array_customOptions_values = explode("|", $ProfileCustomOptions);

								foreach($array_customOptions_values as $val){

									if(isset($_POST["ProfileCustomID". $ProfileCustomID]) && $_POST["ProfileCustomID". $ProfileCustomID] !=""){

										//$dataArr = explode(",",implode(",",explode("','",@$_POST["ProfileCustomID". $ProfileCustomID])));
										$dataArr = @$_POST["ProfileCustomID". $ProfileCustomID];

										if(in_array($val,$dataArr,true) && $val !=""){
											echo "<div><label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
											echo "<span> ". $val."</span></label></div>\n";
										} else {
											if($val !=""){
												echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
												echo "<span> ". $val."</span></label></div>\n";
											}
										}
									} else {
										if($val !=""){
											echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $ProfileCustomID ."[]\" />\n";
											echo "<span> ". $val."</span></label></div>\n";
										}
									}
								}
								echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $ProfileCustomID ."[]\"/>\n";
								echo "</div>\n";
								echo "</div>\n";

						/*
						 * Metric
						 */
						} elseif($ProfileCustomType == 7) {
								echo "<div class=\"rbfield rbselect rbmulti profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";

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

									echo "<label>". $ProfileCustomTitle . $measurements_label ."</label>\n";

								/*
								 * Handle Array
								 */

								// Is Array?
								if(isset($_POST["ProfileCustomID".$ProfileCustomID]) && is_array($_POST["ProfileCustomID".$ProfileCustomID])){
									$_POST["ProfileCustomID".$ProfileCustomID] = @implode(",",@$_POST["ProfileCustomID".$ProfileCustomID]);
								}

								// List
								$list_value = isset($_POST["ProfileCustomID".$ProfileCustomID])?$_POST["ProfileCustomID".$ProfileCustomID]:",";
								@list($min_val,$max_val) =  @explode(",",$list_value);

								// Is Height and is Imperial
								if($ProfileCustomTitle=="Height" && $rb_agency_option_unittype == 1 && $data['ProfileCustomOptions']==3){

									echo "<div>\n";
										echo "<div>\n";
										echo "<label>Min</label>\n";
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
												echo " <option value=\"". $i ."\" ". selected(isset($ProfileCustomValue) && !empty($ProfileCustomValue)?$ProfileCustomValue:$min_val, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
												$i++;
											}
										echo " </select>\n";
										echo "</div>\n";

										echo "<div>\n";
										echo "<label>Max</label>\n";
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
												echo " <option value=\"". $i ."\" ". selected(isset($ProfileCustomValue) && !empty($ProfileCustomValue)?$ProfileCustomValue:$max_val, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
												$i++;
											}
										echo " </select>\n";
										echo "</div>\n";
									echo "</div>\n";
								} else {
									echo "<div>\n";
										// for other search
										echo "<div>\n";
										echo "<label for=\"ProfileCustomID".$ProfileCustomID."_min first\">Min</label><input value=\""
										.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
										."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
										.$ProfileCustomID."[]\" />\n";
										echo "</div>\n";

										echo "<div><label for=\"ProfileCustomID".(isset($data1['ProfileCustomID'])?$data1['ProfileCustomID']:"")
										."_max second\">Max</label><input value=\"".(isset($max_val)?$max_val:"") ."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$ProfileCustomID."[]\" />\n";
										echo "</div>\n";
									echo "</div>\n";
								}
							echo "</div>\n";

						}

						/*
						 * Date Between
						 */
						elseif($ProfileCustomType == 10) {
								$from = "";
								$to = "";
								@list($from,$to) = isset($_POST["ProfileCustomID".$ProfileCustomID])?$_POST["ProfileCustomID".$ProfileCustomID]:array("",""); // @explode(",",@$_POST["ProfileCustomID".$ProfileCustomID]);

								echo "<div class=\"rbfield rbselect rbmulti rbdate profilecustomid_". $ProfileCustomID ."\" data-id=\"". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
									echo "<label>". $ProfileCustomTitle ."</label>\n";
									echo "<div>\n";
											echo "<div>\n";
											echo "		<label for=\"ProfileCustomLabel_min\" >". __("From", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
											echo "		<input  id=\"rb_datepicker_from\" class=\"rb-datepicker stubby profilecustomid_". $ProfileCustomID ."_from\" type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$from."\" />\n";
											echo "</div>\n";
											echo "<div>\n";
											echo "		<label for=\"ProfileCustomLabel_max\" >". __("to", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
											echo "		<input  id=\"rb_datepicker_to\" class=\"rb-datepicker stubby profilecustomid_". $ProfileCustomID ."_to\" type=\"text\" name=\"ProfileCustomID". $ProfileCustomID ."[]\" value=\"".$to."\" />\n";
											echo "</div>\n";
									echo "</div>\n";
								echo "</div>\n";
						}// End Type

					}

				}

				/* status The “Status” field should not show up on front-end search. */ 
				if(isset($_REQUEST['page']) && $_REQUEST['page']=='rb_agency_search'){
					echo "			<div class=\"rbfield rbselect rbsingle profilecustomid_". $ProfileCustomID ."\" id=\"profilecustomid_". $ProfileCustomID ."\">\n";
					echo "				<label for=\"state\">". __("Status", RBAGENCY_TEXTDOMAIN) ."</label>\n";
					echo "					<div>\n";
					echo "						<select name=\"isactive\" id=\"ProfileIsActive\">\n";
													if (isset($_REQUEST['isactive'])) {
														$isactive = $_REQUEST['isactive'];
													} else {
														$isactive = 5;
													}
					echo "							<option value=\"5\">". __("Any Status", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "							<option value=\"1\"". selected($isactive, 1) .">". __("Active", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "							<option value=\"4\"". selected($isactive, 4) .">". __("Active - Not Visible on Front End", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "							<option value=\"0\"". selected($isactive, 0) .">". __("Inactive", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "							<option value=\"2\"". selected($isactive, 2) .">". __("Archived", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "							<option value=\"3\"". selected($isactive, 3) .">". __("Pending Approval", RBAGENCY_TEXTDOMAIN) . "</option>\n";
					echo "						</select>\n";
					echo "					</div>\n";
					echo "			</div>\n";
				}

				echo "				<div class=\"rbfield rbsubmit rbsingle rbsearch-".$rb_agency_option_formhide_advancedsearch_button."\">\n";
					echo "				<input type=\"submit\" name=\"search_profiles\" value=\"". __("Search Profiles", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\"  />\n"; // onclick=\"this.form.action='". $rb_agency_searchurl ."'\"
					echo "				<input type=\"button\" id=\"rst_btn\" value=\"". __("Empty Form", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"clearForm();\" />\n";
					$is_casting_page = get_query_var("rbgroup"); 
					if ($type == 1) {
						if(is_admin() === false){
							echo "				<input type=\"button\" onclick=\"window.location.href='". get_bloginfo("wpurl") ."/search-basic/'\" value=\"". __("Go to Basic Search", RBAGENCY_TEXTDOMAIN) . "\"/>\n";
						}
					} elseif($rb_agency_option_formhide_advancedsearch_button != 1 && $is_casting_page != "casting") {
						if(is_admin() === true){
							echo "				<input type=\"button\" class=\"button-primary\" onclick=\"window.location.href='".admin_url("admin.php?page=rb_agency_search")."'\" value=\"". __("Go to Advanced Search", RBAGENCY_TEXTDOMAIN) . "\"/>\n";
						} else {
							echo "				<input type=\"button\" onclick=\"window.location.href='". get_bloginfo("wpurl") ."/search-advanced/'\" value=\"". __("Go to Advanced Search", RBAGENCY_TEXTDOMAIN) . "\"/>\n";
						}
					}
				echo "				</div>\n";
				global $user_ID, $wpdb;

				// Check if user is registered as Model/Talent
				$is_model_or_talent = 0; 
				if(class_exists('RBAgencyCasting')){
					$profile_is_active = $wpdb->get_row($wpdb->prepare("SELECT CastingID FROM ".table_agency_casting." WHERE CastingUserLinked = %d  ",$user_ID));
					$is_model_or_talent = $wpdb->num_rows;
				}
				if($is_model_or_talent > 0){
					echo "<div class=\"rbclear\"></div>\n";
					echo "<div class=\"rb-goback-link\"><a href=\"".get_bloginfo("url")."/casting-dashboard/\">Go Back to My Dashboard</a></div>\n";
				}
				echo "			</form>\n";
				echo "	<div class=\"rbclear\" style=\"clear:both;\"></div>\n";

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
					echo "								data: {\n";
					echo "									'action': 'rb_agency_clear_casting_array'\n";
					echo "								},\n";
					echo "								success: function(d){\n";
					echo "									console.log(d); \n";
					echo "								},\n";
					echo "								error: function(e){\n";
					echo "									console.log(e);\n";
					echo "								}\n";
					echo "					});\n";
					echo "									jQuery('html, body').animate({scrollTop: 0 }, 'slow'); \n";
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
					if (isset($value)) {
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
						$filterArray['sort'] = "profile.ProfileContactNameFirst,profile.ProfileContactNameLast";
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

				/*
				 * Search by birthdate
				 */

					if(isset($_REQUEST['rb_datepicker_from_bd'])){
						$filterArray['rb_datepicker_from_bd'] = $_REQUEST['rb_datepicker_from_bd'];

					}
					if(isset($_REQUEST['rb_datepicker_to_bd'])){
						$filterArray['rb_datepicker_to_bd'] = $_REQUEST['rb_datepicker_to_bd'];

					}

			// Debug
			if(self::$error_debug){
				self::$error_checking[] = array('search_process',$filterArray);
				echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
			}
				return $filterArray;
			}// If no post, ignore.

		}


	/*
	 * Search: Prepare WHERE SQL String
	 * Process values into SQL string holding WHERE clause
	 */
		public static function search_generate_sqlwhere($atts = null, $exclude = null){
			global $wpdb;

			// Fetch Options
			$rb_agency_options_arr = get_option('rb_agency_options');
				// Get Time Zone
				$rb_agency_option_locationtimezone = isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? $rb_agency_options_arr['rb_agency_option_locationtimezone']:"";

			// Convert Input
			if(is_array($atts)) {

				// Convert Requests to Sessions
				foreach ($atts as $key => $value) {
					// Clear old values
					unset($_SESSION[$key]);
					// Set the new value
					if (isset($value) && !empty($value)) {
						$_SESSION[$key] = $value; //$$key = $value;
					}
				}


				/*
				 * Get Search Chriteria
				 */

					// Support Legacy Naming Convention
					if ( isset($atts["datebirth_min"]) && !empty($atts["datebirth_min"])) {
						$atts["age_min"] = $atts["datebirth_min"];
					}
					if ( isset($atts["datebirth_max"]) && !empty($atts["datebirth_max"])) {
						$atts["age_max"] = $atts["datebirth_max"];
					}
					if ( isset($atts["age_start"]) && !empty($atts["age_start"])) {
						$atts["age_min"] = $atts["age_start"];
					}
					if ( isset($atts["age_stop"]) && !empty($atts["age_stop"])) {
						$atts["age_max"] = $atts["age_stop"];
					}
					if ( isset($atts["type"]) && !empty($atts["type"])) {
						$atts["profiletype"] = $atts["type"];
					}

					// Exctract from Shortcode
					extract(shortcode_atts(array(

						// Specific
						"id" => NULL,
						"namefirst" => NULL,
						"namelast" => NULL,
						"displayname" => NULL,
						// General
						"profiletype" => NULL,
						"profileumltitype" => NULL,
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
						$filter .=  $wpdb->prepare(" AND profile.ProfileContactNameFirst LIKE %s ",'%'.str_replace('"','\"',(str_replace("'","\'",($namefirst)))) ."%");
					}

					// Last Name
					if (isset($namelast) && !empty($namelast)){
						$filter .= $wpdb->prepare(" AND profile.ProfileContactNameLast LIKE %s ", '%'.str_replace('"','\"',(str_replace("'","\'",($namelast)))) ."%");
					}

					// Display Name
					if (isset($displayname) && !empty($displayname)){
						$filter .= $wpdb->prepare(" AND profile.ProfileContactDisplay LIKE %s ",'%'.str_replace('"','\"',(str_replace("'","\'",($displayname)))) ."%");
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

					// Age by Date
					/*if (isset($datebirth_min) && !empty($datebirth_min)){
						$minyear = date('Y-m-d', strtotime($datebirth_min));
						$filter .= " AND profile.ProfileDateBirth <= '$minyear'";
					}
					if (isset($datebirth_max) && !empty($datebirth_max)){
						$maxyear = date('Y-m-d', strtotime($datebirth_max));
						$filter .= " AND profile.ProfileDateBirth >= '$maxyear'";
					}*/
					// By birthdate
					if(isset($atts['rb_datepicker_from_bd'])){
						$birthdate_from = $atts['rb_datepicker_from_bd'];
						$filter .= " AND profile.ProfileDateBirth >= '$birthdate_from'";
					}
					if(isset($atts['rb_datepicker_to_bd'])){
						$birthdate_to = $atts['rb_datepicker_to_bd'];
						$filter .= " AND profile.ProfileDateBirth <= '$birthdate_to'";
					}
					// Age by Number
					if (isset($age_min) && !empty($age_min)){
						$minyear = date('Y-m-d', strtotime('-'. $age_min - 1 .' year'. $date));
						$filter .= " AND profile.ProfileDateBirth <= '$minyear'";
					}
					if (isset($age_max) && !empty($age_max)){
						$maxyear = date('Y-m-d', strtotime('-'. $age_max - 2 .' year'. $date));
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
					
					if (isset($profileumltitype)){
						//$filter .= " AND profile.ProfileType REGEXP '($profileumltitype)'";
						
						$_t_Filter = array();
						$arrayProfMul = explode(',',$profileumltitype);
						foreach($arrayProfMul as $kayv){
							$_t_Filter[]= " (FIND_IN_SET('". $kayv ."', profile.ProfileType) > 0) ";
						}
						$filter .= " AND (". implode('OR',$_t_Filter) .") ";
						

					}
					

					// Set CustomFields search
					if(isset($atts) && !empty($atts)){

					/*
					 *  Custom Fields
					 */
						$filterDropdown = array();
						$filter2 = "";

						// Loop through all attributes looking for custom
						foreach ($_REQUEST as $key => $val) { //!empty($_POST)?$_POST:$_GET)
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
										9 - Multi-select
									 *********************/

									$open_st = ' AND EXISTS (SELECT DISTINCT(ProfileCustomMuxID) FROM '. table_agency_customfield_mux . ' WHERE ' ;
									$close_st = ' AND ProfileCustomID = '.substr($key,15).' AND ProfileID = profile.ProfileID)  ';

									if ($ProfileCustomType["ProfileCustomType"] == 1) {
										// Text
										$filter2 .= "$open_st ProfileCustomValue = '".$val."' $close_st";
										$_SESSION[$key] = $val;

									} elseif ($ProfileCustomType["ProfileCustomType"] == 3 || $ProfileCustomType["ProfileCustomType"] == 9) {
										// Dropdown
										if($ProfileCustomType["ProfileCustomType"] == 3 ){

											$filter2 .="$open_st ProfileCustomValue = '".addslashes($val)."' $close_st";
										// Dropdown Multi-Select
										} elseif($ProfileCustomType["ProfileCustomType"] == 9 ){

												$val = stripslashes($val);
												if(!empty($val)){

													if(strpos($val,",") === false){
														$filter2 .= $open_st;
														$val2 = $val;
														$filter2 .= $wpdb->prepare(" FIND_IN_SET(ProfileCustomValue,%s) > 0 AND ProfileCustomValue LIKE %s  AND ProfileCustomValue = %s   ",$val2,"%".$val2."%",$val2);
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
																$sr_data .= $wpdb->prepare("(FIND_IN_SET(%s,ProfileCustomValue) = 0 AND ProfileCustomValue LIKE %s)".(($i <= $likecounter)?" AND ":""),$like,"%".$val2."%");
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
										/*
										TODO:??? WTH?
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
										}*/
										$open_st = "AND profile.ProfileID IN (SELECT DISTINCT(ProfileID) FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = ".substr($key,15)." AND ";
										$close_st = ")";

										$val = stripslashes($val);
										if(!empty($val)){
											// Is there a single value?
											if(strpos($val,",") === false){
												$filter2 .= $open_st . $wpdb->prepare(" ProfileCustomValue LIKE %s","%".$val."%") . $close_st;

											// Are there multiple values?
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
										}

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
									} elseif ($ProfileCustomType["ProfileCustomType"] == 10) {
										// Date
										list($from, $to) = explode(",", $val);

										$filter2 .= "$open_st ProfileCustomDateValue BETWEEN '".$from."' AND '".$to."' $close_st";
										$_SESSION[$key] = $val;

									}

								}// if not empty
							}// end if
						}// end for each

						if(count($filterDropdown) > 0){
							$filter2 .="$open_st ProfileCustomValue IN ('".implode("','",$filterDropdown)."') $close_st";
						}


						// Clean
						$filter2 = str_replace(array("\n","\t","\r")," ", $filter2);
						$filter2 = str_replace(")(", ") OR (", $filter2);

						// Clean
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

					/*
					 * Check if Private profile
					 */
					global $current_user;
					if(!is_user_logged_in()){
						$filter .= " AND profile.isPrivate=0 ";
					}
					//echo '-----------------' .$filter;
					
					
					
					// Debug
					if(self::$error_debug){
						self::$error_checking[] = array('search_generate_sqlwhere',$filter);
						echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
					}

					self::search_generate_sqlorder($atts,$filter2);

					// Store SQL and Custom Fields SQL 
					$filter_array = array(
						"standard" => $filter,
						"custom" => $filter2,
					);
					return $filter_array;

			} else {
				// Empty Search
				return false;
			}

		}


	/*
	 * Search: Prepare ORDER SQL String
	 * Process values into SQL string holding ORDER clause
	 */

		public static function search_generate_sqlorder($atts, $filter2 = ""){

			$filter = "";

			$rb_agency_options_arr = get_option("rb_agency_options");
			// Sort by date 
			$rb_agency_option_profilelist_sortbydate = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortbydate']) ? $rb_agency_options_arr['rb_agency_option_profilelist_sortbydate']: 0;
			$rb_agency_option_persearch = (int)$rb_agency_options_arr['rb_agency_option_persearch'];

			if($rb_agency_option_profilelist_sortbydate && !empty($filter2)){
				$atts["sort"] = "cmux.ProfileCustomDateValue";
			} elseif(!isset($atts["sort"])){
				$atts["sort"] = "profile.ProfileContactNameFirst,profile.ProfileContactNameLast";
			}

			if(!isset($_GET["limit"]) && isset($_GET["page"]) && $_GET["page"] == "rb_agency_search"){
				$atts["limit"] = $rb_agency_option_persearch;
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
					$filter .= " ORDER BY $sort $dir ";
				}

			/*
			 * LIMIT
			 */

				if ( (isset($limit) && !empty($limit)) && strpos($filter, 'LIMIT') == 0  ){
					//$filter .= " LIMIT 0, $limit ";
				}
				// Show top 100
				if(is_admin() && !isset($_GET["limit"])){
					$filter .= " LIMIT 101";
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

		public static function search_results($sql_where_array, $query_type = 0, $castingcart = false, $arr_query = array(),$shortcode = false){

			global $wpdb;

			if (self::$error_debug_query){
				echo $sql_where_array['standard'];
				echo "<hr />";
				echo $sql_where_array['custom'];
				echo "<hr />";
			}

			// Merge them
			$sql_where = $sql_where_array['standard'] ." ". $sql_where_array['custom'];
	
			$sqlCasting_userID = "";

			switch ($query_type) {
			/* 
			 * Standard Query (Public Front-End)
			 */
				case 0:
					$sql = "SELECT 
					profile.ProfileID,
					profile.ProfileGallery,
					profile.ProfileType,
					profile.ProfileContactEmail,
					profile.ProfileContactPhoneCell,
					profile.ProfileContactPhoneWork,
					profile.ProfileContactPhoneHome,
					profile.ProfileContactWebsite,
					profile.ProfileContactDisplay,
					profile.ProfileContactNameFirst,
					profile.ProfileContactNameLast,
					profile.ProfileDateBirth,
					profile.ProfileGender,
					profile.ProfileDateCreated,
					profile.ProfileLocationState,
					profile.ProfileLocationCountry,
					profile.ProfileIsActive,
					(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media  WHERE  profile.ProfileID = media.ProfileID  AND media.ProfileMediaType = \"Image\"  AND media.ProfileMediaPrimary = 1 LIMIT 1) AS ProfileMediaURL ";

					// Do we need the custom fields table?
					if ( isset($sql_where_array['custom']) && !empty($sql_where_array['custom']) ) {
						$sql .= ", cmux.ProfileCustomDateValue 
							FROM ". table_agency_profile ." profile 
							LEFT JOIN  ". table_agency_customfield_mux." cmux ON profile.ProfileID = cmux.ProfileID
							WHERE ". $sql_where_array['standard'] ."
								AND EXISTS (
								SELECT count(cmux.ProfileCustomMuxID)  FROM
								".table_agency_customfield_mux." cmux
								WHERE profile.ProfileID = cmux.ProfileID
								". $sql_where_array['custom'] ."
								GROUP BY cmux.ProfileCustomMuxID
								LIMIT 1)
							";
					} else {
						$sql .= "FROM ". table_agency_profile ." profile 
							WHERE ". $sql_where_array['standard'] ." ";
					}
					$sql .= self::$order_by;

					break;

			/* 
			 * Admin Query (Back-End)
			 */
				case 1:
					$sql = "SELECT 
					profile.ProfileID,
					profile.ProfileGallery,
					profile.ProfileContactDisplay,
					profile.ProfileContactNameFirst,
					profile.ProfileContactNameLast,
					profile.ProfileDateBirth, 
					profile.ProfileDateCreated,
					profile.ProfileLocationStreet, 
					profile.ProfileLocationCity,
					profile.ProfileLocationState,
					profile.ProfileLocationZip,
					profile.ProfileLocationCountry,
					profile.ProfileGender,
					profile.ProfileIsActive,
					profile.ProfileContactEmail,
					profile.ProfileContactPhoneCell,
					profile.ProfileContactPhoneWork,
					profile.ProfileContactPhoneHome,
					profile.ProfileContactWebsite,
					profile.ProfileType,
					(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media  WHERE  profile.ProfileID = media.ProfileID  AND media.ProfileMediaType = \"Image\"  AND media.ProfileMediaPrimary = 1 LIMIT 1) AS ProfileMediaURL ";

					// Do we need the custom fields table?
					if ( isset($sql_where_array['custom']) && !empty($sql_where_array['custom']) ) {
						$sql .= " FROM ". table_agency_profile ." profile 
							WHERE ". $sql_where_array['standard'] ."
								". $sql_where_array['custom'] ."
							";

						/*  TODO: ProfileCustomDateValue  <-- do we need this?
						$sql .= ", cmux.ProfileCustomDateValue 
							FROM ". table_agency_profile ." profile 
							LEFT JOIN  ". table_agency_customfield_mux." cmux ON profile.ProfileID = cmux.ProfileID
							WHERE ". $sql_where_array['standard'] ."
								AND EXISTS (
								SELECT count(cmux.ProfileCustomMuxID)  FROM
								".table_agency_customfield_mux." cmux
								WHERE profile.ProfileID = cmux.ProfileID
								". $sql_where_array['custom'] ."
								GROUP BY cmux.ProfileCustomMuxID
								LIMIT 1)
							"; */




					} else {
						$sql .= "FROM ". table_agency_profile ." profile 
							WHERE ". $sql_where_array['standard'] ." ";
					}

					$sql .= self::$order_by;

					break;

			/* 
			 * Casting Cart
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
							} else {
								//$sqlCasting_userID .= " AND cart.CastingCartTalentID <> '' AND cart.CastingCartTalentID IS NOT NULL ";
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
							}
						} else {
							$sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID";
							if(isset($_GET["Job_ID"]) && !empty($_GET["Job_ID"])){
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
								
								$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingJobID = %s",$_GET["Job_ID"]);
							} else {
								$uid = rb_agency_get_current_userid();
								if($uid > 0){
									//$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d  AND cart.CastingJobID <= 0 ",rb_agency_get_current_userid());
									$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d AND (cart.CastingJobID IS NULL OR cart.CastingJobID <= 0 )",rb_agency_get_current_userid());
									//echo 'xxxxxxx';
									//$query_castingcartx = $wpdb->get_results($wpdb->prepare("DELETE FROM ". table_agency_castingcart));
									
									//print_r($query_castingcartx);
									
									//$sqlCasting_userID .= $wpdb->prepare(" AND cart.CastingCartProfileID = %d",rb_agency_get_current_userid());
								}
								
								
								//echo $sqlCasting_userID;
								//AND (cart.CastingJobID IS NULL OR cart.CastingJobID <= 0 )
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
								profile.ProfileGender,
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
											media.ProfileMediaPrimary = 1
											LIMIT 1
											)
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
								GROUP BY profile.ProfileID";
					break;

			/*
			 * Casting Query
			 */
				case 3:
					//(SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst)
					$sql = "SELECT profile.*, (SELECT media.ProfileMediaURL FROM ".
											table_agency_profile_media ." media
											WHERE
											profile.ProfileID = media.ProfileID
											AND
											media.ProfileMediaType = \"Image\"
											AND
											media.ProfileMediaPrimary = 1
											LIMIT 1
											)
											AS
								ProfileMediaURL  FROM ".table_agency_profile." AS profile WHERE ".$sql_where_array['standard']."  GROUP BY(profile.ProfileID) ";
					break;

			/* 
			 * Query Casting Agent Favorites
			 */
				case 4:
					$sqlFavorite_userID  = " fav.SavedFavoriteTalentID = profile.ProfileID  AND fav.SavedFavoriteProfileID = '".rb_agency_get_current_userid()."' ";
					$sql = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileGender,profile.ProfileContactNameFirst, profile.ProfileContactNameLast, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileIsActive, profile.ProfileLocationState, profile.ProfileID as pID, fav.SavedFavoriteTalentID, fav.SavedFavoriteProfileID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE " . $sql_where_array['standard'] . " AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite." fav WHERE $sqlFavorite_userID AND profile.ProfileIsActive = 1 GROUP BY fav.SavedFavoriteTalentID";
					break;
			}

			/*
			 * Execute Query
			 */

				// Show Errors?
				if(self::$error_debug || self::$error_debug_query){
					self::$error_checking[] = array('-MAIN_QUERY-',$sql);
					echo "<pre>"; print_r(self::$error_checking); echo "</pre>";

					$wpdb->show_errors();
					$wpdb->print_error();

					echo "<hr><div style='color: red;'>". $sql ."</div>";
				}
			
			/*
			 * Check if search is Admin or Public
			 */
				if(is_admin()){
					return self::search_result_admin( $sql, $arr_query );
				} else {
					return self::search_result_public( $sql, $castingcart, $shortcode, $arr_query );
				}

		}

	/* 
	 * Results for Public (Front-End)
	 */
		public static function search_result_public($sql, $castingcart = '',$shortcode = false,$arr_query = array()){
			global $wpdb;

			/*
			 * format profile list per profile
			 */
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_profilelist_sortby = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
				$rb_agency_option_persearch = isset($rb_agency_options_arr["rb_agency_option_persearch"])?$rb_agency_options_arr["rb_agency_option_persearch"]:10;
				$rb_agency_option_profilelist_perpage = isset($rb_agency_options_arr["rb_agency_option_profilelist_perpage"])?$rb_agency_options_arr["rb_agency_option_profilelist_perpage"]:15;
				$rb_agency_option_profilelist_printpdf = isset($rb_agency_options_arr["rb_agency_option_profilelist_printpdf"])?$rb_agency_options_arr["rb_agency_option_profilelist_printpdf"]:0;
				$rb_agency_option_profilelist_count = isset($rb_agency_options_arr["rb_agency_option_profilelist_count"])?$rb_agency_options_arr["rb_agency_option_profilelist_count"]:0;
				$rb_agency_option_layoutprofilelistlayout = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']:0;
				$rb_agency_option_layoutprofilelist_perrow = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist_perrow']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelist_perrow']:5;
				$rb_agency_option_profilelist_showprofiletypeslinks = isset($rb_agency_options_arr['rb_agency_option_profilelist_showprofiletypeslinks']) ? $rb_agency_options_arr['rb_agency_option_profilelist_showprofiletypeslinks']:0;
				$rb_agency_option_profilelist_thumbsslide	= isset($rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']:0;
				$rb_agency_option_layoutprofileviewmode = isset($rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']) ? $rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']:0;
				$profiles_perrow = array('one','two','three','four','five','six','seven','eight','nine','ten');

				$results = $wpdb->get_results($sql,ARRAY_A);//echo 'error'.$sql;print_r($wpdb->last_error);
				$profile_list = "";
				$all_html = "";
				// $all_html.='<div id="rbfilter-sort">';
				$paginate = new RBAgency_Pagination;
				$items = $wpdb->num_rows;
				$count = $items;

				$all_html.='<div id="results-options">';
				$all_html.='	<div class="rbsort">';

				// RB Agency default paging variables
				$page = get_query_var("paging");
				$paging = get_query_var("paging");
				$offset = $page < 1?0:($page - 1)*(int)$rb_agency_option_persearch;
				$limit = (int)$rb_agency_option_persearch;

				if($shortcode){ // Wordpress default paging variables
					$page = get_query_var("page");
					$paging = get_query_var("page");
					$offset = $page < 1?0:($page - 1)*(int)$rb_agency_option_profilelist_perpage;
					$limit = (int)$rb_agency_option_profilelist_perpage;
				}

				// Avoid double limits
				$sql .= " LIMIT {$offset},{$limit}";

				$results = $wpdb->get_results($sql,ARRAY_A);
				$count = $wpdb->num_rows;

				unset($_REQUEST["search_profiles"]); //unset unwanted variable
				$query = RBAgency_Common::http_build_query($_REQUEST);
				$target = $query;
				$paginate->items($items);
				$paginate->limit($limit);
				$paginate->target($_SERVER["REQUEST_URI"],$target);
				$paginate->currentPage(!empty($paging)?$paging:1);


			/*
			 *  sorting options is activated if set on in admin/settings
			 */

				if($rb_agency_option_profilelist_sortby && empty($castingcart) && strpos($_SERVER['REQUEST_URI'],'profile-favorite') <= -1){

					// Enqueue our js script
					wp_enqueue_script( 'list_reorder', RBAGENCY_PLUGIN_URL .'assets/js/list_reorder.js', array('jquery'));

					// Dropdown
					///get custom field
					$query = " SELECT * FROM " .  table_agency_customfields . " WHERE ProfileCustomShowFilter = 1 ORDER BY ProfileCustomOrder ASC";
					$customFilters = $wpdb->get_results($query,ARRAY_A);

					$query_gender = "SELECT * FROM ".table_agency_data_gender;
					$results_genders = $wpdb->get_results($query_gender,ARRAY_A);

					$all_html.='
							<select id="sort_by">
								<option value="0">Sort List</option>
								<option value="1">Age</option>
								<option value="2">Name</option>
								<option value="3">Date Joined</option>
								<option value="2">Display Name</option>
								<option value="50">Gender</option>';
								$customFilters = array_merge($customFilters,$results_genders);
								foreach($customFilters as $customFilter){
									
									$customID = $customFilter['ProfileCustomID'] + 100; 
									if($customFilter['ProfileCustomType'] == 1 || $customFilter['ProfileCustomType'] == 4){
										$all_html.= "<option value='1'>".$customFilter['ProfileCustomTitle']."</option>";
									} elseif($customFilter['ProfileCustomType'] == 10){
										$all_html.= "<option value='3'>".$customFilter['ProfileCustomTitle']."</option>";
									} elseif($customFilter['ProfileCustomType'] == 3){
										$all_html.= "<option value='sortdropdown_".$customID."_|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other|'>".$customFilter['ProfileCustomTitle']."</option>";
									} elseif($customFilter['ProfileCustomType'] == 7){
										$all_html.= "<option value='2'>".$customFilter['ProfileCustomTitle']."</option>";
									}
								}

								

					$all_html.='</select>
							<select id="sort_option">
								<option value="">Sort Options</option>
							</select>';
				}
				$all_html.="	</div>";
				$all_html .= $paginate->show();
				$all_html.="	</div><!-- #results-options -->"; // #results-options
				
				$type = get_query_var('type');


				// Profile Types right side
				
				global $_list_my_profiles;
				//echo $_list_my_profiles.'xxxxxxxxxxx';
				if($rb_agency_option_profilelist_showprofiletypeslinks == 1 && $rb_agency_option_layoutprofilelistlayout != 1){

					$all_html.="<ul class=\"profile-types\">";
					$queryPType = "SELECT DataTypeID, DataTypeTitle, DataTypeTag FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
					$resultsPType = $wpdb->get_results($queryPType,ARRAY_A);
					$result_count = count($resultsPType);
					$ctr = 0;

					foreach ($resultsPType as $PTypekey) {
						if($ctr < 3) {
							$all_html.= "<li id=\"". $PTypekey["DataTypeID"] ."\"><a href=\"/". $PTypekey["DataTypeTag"] ."/\" title=\"". $PTypekey["DataTypeTitle"] ."\">". $PTypekey["DataTypeTitle"] ."</a></li>\n";
						}
						if($ctr == 2) {
							$all_html.= "<li class=\"more-types\">";
							$all_html.= "	<i class=\"fa fa-angle-double-down\"></i>";
							$all_html.= "	<ul>";
						}
						if($ctr > 2) {							
							$all_html.= "		<li id=\"". $PTypekey["DataTypeID"] ."\"><a href=\"/". $PTypekey["DataTypeTag"] ."/\" title=\"". $PTypekey["DataTypeTitle"] ."\">". $PTypekey["DataTypeTitle"] ."</a></li>\n";
						}
						if($ctr+1 == $result_count){
							$all_html.= "	</ul>";
							$all_html.= "</li>";
						}
					$ctr++;					
					}
					$all_html.="</ul>";
				}
				

				$all_html.='<div id="results-info">';

				if ($rb_agency_option_profilelist_count && (!in_array(get_query_var("type"), array("favorite","casting"))) ){
					$all_html.='	<div class="rbtotal-results">Total Results : '.$items.' </div>';
				}

			if(class_exists("RBAgencyCasting") && is_user_logged_in() && strpos($type,"casting") <= -1 && strpos($type,"favorite") <= -1){


				$all_html.="<div class=\"rb-cart-links\">";

				if($rb_agency_options_arr['rb_agency_option_profilelist_castingcart'] == 1){
					$all_html.="<a href=\"".get_bloginfo("url")."/profile-casting/\" class=\"link-casting-cart\">View Casting Cart</a> <span class=\"link-separate\">|</span> ";
				}

				if($rb_agency_options_arr['rb_agency_option_profilelist_favorite'] == 1){
					$all_html.="<a href=\"".get_bloginfo("url")."/profile-favorites/\" class=\"link-favorite\">View Favorites</a>";
				}

				$all_html.="</div>";
			}

			if ($count > 0){

				// Return Casting Casting Cart Results
				$castingcart_results = array();
				if(function_exists("rb_agency_get_miscellaneousLinks")){
					//$castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."'");
					//$castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."' GROUP BY CastingCartProfileID ");
					$castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."' AND (CastingJobID<= 0 OR CastingJobID IS NULL) ");
				}

				$arr_castingcart = array();
				foreach ($castingcart_results as $key) {
					array_push($arr_castingcart, $key->CastingCartTalentID);
				}

				// Return Favorites Results
				$favorites_results = $wpdb->get_results("SELECT SavedFavoriteTalentID FROM ".table_agency_savedfavorite." WHERE SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'");

				$arr_favorites = array();
				foreach ($favorites_results  as $key) {
					array_push($arr_favorites, $key->SavedFavoriteTalentID);
				}

				$profilesPerRow = 1;

				// Loop through Profiles
				foreach($results as $profile) {
					$availability = '';

					if(!empty($castingcart)){
						if(isset($_GET["Job_ID"]) && !empty($_GET["Job_ID"])){
							$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
							$prepared = $wpdb->prepare($query,$profile["ProfileID"],$_GET["Job_ID"]);
						}

						$data = $wpdb->get_row($prepared);
						$count2 = $wpdb->num_rows;
						if($count2 >= 1){
							$availability = $data->status;
						}
					}

					$profile_list .= self::search_formatted($profile, $arr_favorites, $arr_castingcart, $availability, false, $arr_query );

					if($rb_agency_option_layoutprofileviewmode == 2 && $rb_agency_option_layoutprofilelistlayout !=1){
						if($profilesPerRow % $rb_agency_option_layoutprofilelist_perrow == 0) {
							$profile_list .="	<div class=\"info-panel\"> \n";
							$profile_list .="		<div class=\"profile-pic\"> \n";
							$profile_list .="		<img src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/female_model-01.jpg\" alt=\"Profile Pic\"> \n";
							$profile_list .="		</div> <!-- .profile-pic --> \n";
							$profile_list .="		<div class=\"info\"> \n";
							$profile_list .="			<h2>Profile Name</h2> \n";
							$profile_list .="			<ul class=\"stats\"> \n";
							$profile_list .="			<li><strong>Gender<span>:</span></strong> Female</li> \n";
							$profile_list .="			<li><strong>Ethnicity:</strong>  White</li> \n";
							$profile_list .="			<li><strong>Skin Colour:</strong> White</li> \n";
							$profile_list .="			<li><strong>Height (cm):</strong> 172</li> \n";
							$profile_list .="			<li><strong>Hair Colour:</strong> Blonde</li> \n";
							$profile_list .="			</ul> <!-- .stats --> \n";
							$profile_list .="			<div class=\"owl-carousel\"> \n";
							$profile_list .="			</div> <!-- .info --> \n";
							$profile_list .="		</div> <!-- .info --> \n";
							$profile_list .="	</div> <!-- .info-panel --> \n";
						}
						$profilesPerRow++;
					}

				}// End Loop

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
					$rb_agency_option_layoutprofilelistlayout = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']:0;
					$rb_agency_option_layoutprofilelist_favcartfx = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist_favcartfx'])?$rb_agency_options_arr['rb_agency_option_layoutprofilelist_favcartfx']:0;
					$rb_agency_option_layoutprofilelist_perrow = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist_perrow']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelist_perrow']:5;
					$rb_agency_value_profilethumbwidth			= isset($rb_agency_options_arr['rb_agency_option_agencyprofilethumbwidth'])?$rb_agency_options_arr['rb_agency_option_agencyprofilethumbwidth']:180;
				$rb_agency_value_profilethumbheight			= isset($rb_agency_options_arr['rb_agency_option_agencyprofilethumbheight'])?$rb_agency_options_arr['rb_agency_option_agencyprofilethumbheight']:230;
					$profiles_perrow = array('one','two','three','four','five','six','seven','eight','nine','ten');

				/* 
				 * this is the upper header html of the profile list
				 */

					// $all_html .=  "<script type='text/javascript' src='". RBAGENCY_PLUGIN_URL ."assets/js/resize.js'></script>";
					if ($rb_agency_option_profilelist_count) {
						$all_html .= "<div id=\"profile-results-info-countrecord\">\n";
						$all_html .=  __("Displaying", RBAGENCY_TEXTDOMAIN) ." <strong><span class='count-display'>". (isset($count)?$count:0) ."</span></strong> ". __("of", RBAGENCY_TEXTDOMAIN) ." <span class='items-display'>". (isset($items)?$items:0) ."</span> ". __(" records", RBAGENCY_TEXTDOMAIN) ."\n";
						$all_html .= "</div>\n";
					}

					if($rb_agency_option_profilelist_printpdf == 1){
						$all_html.="<div class=\"results-links\">";
						$all_html.="<a href=\"javascript:;\" class=\"link-profile-print\">Print</a> <span class=\"link-separate\">|</span> ";
						$all_html.="<a href=\"javascript:;\" class=\"link-profile-pdf\">Download PDF</a>";
						$all_html.="</div>";
					}

					$profile_listlayout_class = array();
					global $_list_my_profiles;
					// Profile List Layout "Voiceover (no image)"
					if($rb_agency_option_layoutprofilelistlayout == 1 or $_list_my_profiles=='voiceover'){
						$profile_listlayout_class[] = 'voiceover';
						
						
						$resultsP = $wpdb->get_results("SELECT med.*,dat.* FROM ".table_agency_data_media ." as med
						INNER JOIN ".table_agency_data_type." as dat ON med.MediaCategoryTitle = dat.DataTypeTitle",ARRAY_A);
						
						$_titleattr = '';
						foreach($resultsP as $key => $val){
							$_te = 'custom_mp3_'. $val['MediaCategoryID'];
							$_titleattr .='jQuery("li.'.$_te.' a.play-button").attr("title","'. $val['MediaCategoryTitle'] .'");'."\n";
						/* 	$_typeClass = sanitize_title_with_dashes($dataMedia['ProfileMediaType']);
								$_typeClass = str_replace('/','', $_typeClass);
								 */
						}
						
						//rbcustommedia_audio-book_button_mp3_7
						$all_html.='
						<script>
							// change the title of play button 
							jQuery(document).ready(function($){
								jQuery("li.voicedemo a.play-button").attr("title","Voice Demo");
								'. $_titleattr .'
							});
						</script>
						';
						
						//$queryPType = "SELECT DataTypeID, DataTypeTitle, DataTypeTag FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";

											
				 
						
					// Profile List Layout "Default"
					} else {
						$profile_listlayout_class[] = 'default';
					}

					// Profile per row div class
					$profile_listlayout_class[] = $profiles_perrow[$rb_agency_option_layoutprofilelist_perrow-1]."-profiles";

					// Prepare div classes
					$profile_listlayout_classes = array_unique($profile_listlayout_class);
					$profile_listlayout_classes = join(' ',$profile_listlayout_classes);

					$all_html .= '<div id="profile-results-info">';
					/*
					if ($rb_agency_option_profilelist_favorite){
						$all_html .= "<div class=\"profile-results-info-countpage\">\n";
						$all_html .= $count;// Echo out the list of paging. 
						$all_html .= "</div>\n";
					}
					 */

					/*
					if (self::$castingcart){  //allow email to admin casting
						$all_html .= "<div>\n";
						$all_html .= '	<a id="sendemail" href="javascript:;">Email to Admin</a>';
						$all_html .= "</div>\n";
					}*/
					$all_html .= '</div>';

				/* 
				 * wrap profile listing
				 */

					$all_html .= '</div>'; // #results-info
					$all_html .= '<div class="rbclear"></div>';
					// $all_html .= '<hr />';
					
					
					global $_list_my_profiles;
				
					// Profile Types Voiceover
					if($rb_agency_option_profilelist_showprofiletypeslinks == 1 && $rb_agency_option_layoutprofilelistlayout == 1){

						$all_html.="<div id=\"profile-categories\">";
						$queryPType = "SELECT DataTypeID, DataTypeTitle, DataTypeTag FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
						$resultsPType = $wpdb->get_results($queryPType,ARRAY_A);
						foreach ($resultsPType as $PTypekey) {					
							$all_html.= "<a href=\"/". $PTypekey["DataTypeTag"] ."/\" title=\"". $PTypekey["DataTypeTitle"] ."\">". $PTypekey["DataTypeTitle"] ."</a>\n";
						}
						$all_html.="</div>";
					}

					$all_html .= "<div id='profile-list' class='".$profile_listlayout_classes."'>".$profile_list."</div>";
					$all_html .= $paginate->show();

					if($rb_agency_option_layoutprofileviewmode == 1 && $rb_agency_option_layoutprofilelistlayout !=2){
						$all_html .= "<div id=\"profile-id\" class=\"profile-popup white-popup mfp-hide\">";
						$all_html .= "	<div class=\"profile-photo\">";
						$all_html .= "		<img src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/female_model-01.jpg\" alt=\"Profile Pic\"> \n";
						$all_html .= "	</div>";
						$all_html .= "	<div class=\"info\">";
						$all_html .= "		<h3>Lorem Ipsum</h3>";
						$all_html .= "		<p>Info info info info info info</p>";
						$all_html .= "		<a href=\"\" title=\"\">Download Resume</a>";
						$all_html .= "		<a href=\"\" title=\"\">More</a>";
						$all_html .= "	</div>";
						$all_html .= "</div>";
					}					
					$all_html .= "<div class='clear'></div>";

					// casting and favorite effect
					if($rb_agency_option_layoutprofilelist_favcartfx > 0) {
						if($rb_agency_option_layoutprofilelist_favcartfx == 2) {
							$mouseoverfx = 'jQuery(this).find(".rb_profile_tool").stop().slideDown("slow");';
							$mouseoutfx  = 'jQuery(this).find(".rb_profile_tool").stop().slideUp("slow");';
						} else {
							$mouseoverfx = 'jQuery(this).find(".rb_profile_tool").stop().fadeIn("slow");';
							$mouseoutfx  = 'jQuery(this).find(".rb_profile_tool").stop().fadeOut("slow");';
						}
						$all_html .= '<script type="text/javascript">
							// Casting and Favorite slide down
							jQuery(".rb_profile_tool").hide();
							jQuery("#profile-list .rbprofile-list").mouseover(function(){
								'.$mouseoverfx.'
							});
							jQuery("#profile-list .rbprofile-list").mouseout(function(){
								'.$mouseoutfx.'
							});
						</script>';
					}

					$type = get_query_var("type");
					// if(!in_array($type,array("favorite","castingjobs","casting","profilecastingcart"))){
					if(in_array($type,array("search-basic","search-advanced","search-basic","search-result"))){
						$all_html .= "<div class=\"rb-search-result-links\"><a href=\"".get_bloginfo("url")."/search-basic/\">Go Back to Basic Search</a><span class=\"rb-search-link-sep\">|</span><a href=\"".get_bloginfo("url")."/search-advanced/\">Go Back to Advanced Search</a></div>";
					}
					if(self::$error_debug){
						self::$error_checking[] = array('search_result_public',$all_html);
						echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
					}

					if($rb_agency_option_profilelist_thumbsslide == 1){
						$rb_agency_value_profilethumbheight = $rb_agency_value_profilethumbheight -20;
						$all_html .='<style type="text/css">
										#profile-list .rbprofile-list .image { height: '.$rb_agency_value_profilethumbheight.'px; }
									</style>';
						$all_html .='<script type="text/javascript">
						
										var interval = "";

										jQuery(".rbprofile-list .image a img:first").addClass("active");

										function cycleImages(elem){											
											var active = jQuery(elem).find(".active");
											var next = (active.next().length > 0) ? active.next() : jQuery(elem).find("img:first");
											next.css("z-index",2); //move the next image up the pile
											active.fadeOut(500,function(){ //fade out the top image
											active.css("z-index",1).show().removeClass("active");//reset the z-index and unhide the image
												next.css("z-index",3).addClass("active"); //make the next image the top one
											});
										}

										jQuery(".rbprofile-list .image").mouseout(function(){
											clearInterval(interval);
											jQuery(this).find("img").removeClass("active");
											jQuery(this).find("img").css("z-index",1);
											jQuery(this).find("img.primary").css("z-index",4).addClass("active");
										});
						
									</script>';
					}

				// Return
				return $all_html;


			} else {

				/* 
				 * No results Found.
				 */
					$no_rec_html = '<div class=\"rbclear\"></div>' . __("No Profiles Found", RBAGENCY_TEXTDOMAIN);
					$no_rec_html .= '<div class=\"rbclear\"></div>';
					if(!in_array($type,array("favorite","castingjobs","casting","profilecastingcart"))){
					$no_rec_html .= "<div class=\"rb-search-result-links\"><a href=\"".get_bloginfo("url")."/search-basic/\">Go Back to Basic Search</a><span class=\"rb-search-link-sep\">|</span><a href=\"".get_bloginfo("url")."/search-advanced/\">Go Back to Advanced Search</a></div>";
					}
					if(self::$error_debug){
						self::$error_checking[] = array('search_result_public',$no_rec_html);
						echo "<pre>"; print_r(self::$error_checking); echo "</pre>";
					}

				// Return
				return $no_rec_html;

			}

		}


	/* 
	 * Results for Admin (Back-End)
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
				if ($rb_agency_option_persearch < 0) {$rb_agency_option_persearch = 100; }

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
				$displayHtml .=  "       <form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
				$displayHtml .=  "        <input type=\"hidden\" name=\"forceCart\" value=\"".(!is_array(RBAgency_Common::session('cartArray'))?RBAgency_Common::session('cartArray'):"") ."\" />\n";
				$displayHtml .=  "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
				$displayHtml .=  "        <thead>\n";
				$displayHtml .=  "            <tr class=\"thead\">\n";
				$displayHtml .=  "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=rb_agency_profiles&sort=ProfileID&dir=". (isset($sortDirection)?$sortDirection:"") ."\">". __("ID", RBAGENCY_TEXTDOMAIN) ."</a></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileImage\" id=\"ProfileImage\" scope=\"col\" style=\"width:150px;\">". __("Headshot", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "            </tr>\n";
				$displayHtml .=  "        </thead>\n";
				$displayHtml .=  "        <tfoot>\n";
				$displayHtml .=  "            <tr class=\"thead\">\n";
				$displayHtml .=  "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\">". __("ID", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "                <th class=\"column-image\" id=\"col-image\" scope=\"col\">". __("Headshot", RBAGENCY_TEXTDOMAIN) ."</th>\n";
				$displayHtml .=  "            </tr>\n";
				$displayHtml .=  "        </tfoot>\n";
				$displayHtml .=  "        <tbody>\n";

				foreach($results as $data){
					$ProfileID = $data['ProfileID'];
					$isInactive = '';
					$isInactiveDisable = '';
					$statusClass = '';
					$profileStatus = $data['ProfileIsActive'];
					if($profileStatus == 0){
						$statusClass = "inactive";
					}
					if($profileStatus == 1){
						$statusClass = "active";
					}
					if($profileStatus == 2){
						$statusClass = "archived";
					}
					if($profileStatus == 3){
						$statusClass = "pending";
					}
					if($profileStatus == 4){
						$statusClass = "active-notvisible";
					}
					if ($data['ProfileIsActive'] == 0 || empty($data['ProfileIsActive'])){
						$isInactive = 'style="background: #FFEBE8"';
						$isInactiveDisable = "disabled=\"disabled\"";
					}
					$p_image = str_replace(" ", "%20", rb_get_primary_image($data["ProfileID"]));

					$checkboxDisable ="";
					if(empty($p_image)){
						$checkboxDisable = "data-disabled=\"true\"";
					} else {
						$checkboxDisable ="";
					}
					$displayHtml .=  "        <tr class=\"".$statusClass."\">\n";
					$displayHtml .=  "            <th class=\"check-column\" scope=\"row\" >\n";
					$displayHtml .=  "                <input ".$checkboxDisable." type=\"checkbox\" ". $isInactiveDisable." value=\"". $ProfileID ."\" class=\"administrator\" id=\"ProfileID". $ProfileID ."\" name=\"ProfileID[]\" />\n";
					$displayHtml .=  "            </th>\n";
					$displayHtml .=  "            <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
					$displayHtml .=  "            <td class=\"ProfileContact column-ProfileContact\">\n";
					$displayHtml .=  "                <div class=\"detail\">\n";
					$displayHtml .=  "                </div>\n";
					$displayHtml .=  "                <div class=\"title\">\n";
					$displayHtml .=  "            		<h2>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) ."</h2>\n";
					$displayHtml .=  "                </div>\n";
					$displayHtml .=  "                <div class=\"row-actions\">\n";
					$displayHtml .=  "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
					if(isset($data['ProfileGallery'])){
					$displayHtml .=  "                    <span class=\"review\"><a href=\"". RBAGENCY_PROFILEDIR . (isset($RBAGENCY_UPLOADDIR)?$RBAGENCY_UPLOADDIR:"") . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
					}
					$displayHtml .=  "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=deleteRecord&amp;ProfileID=". $ProfileID ."\" onclick=\"if ( confirm('You are about to delete the model ". ucfirst($data['ProfileContactNameFirst']) ." ". ucfirst($data['ProfileContactNameLast']) ."') ) {return true;}return false;\">Delete</a></span>\n";
					$displayHtml .=  "                </div>\n";
					if(!empty($isInactiveDisable)){
							$displayHtml .=  "<div><strong>Profile Status:</strong> <span style=\"color:red;\">Inactive</span></div>\n";
					}
					$displayHtml .=  "            </td>\n";

					// private into 
					$displayHtml .=  "            <td class=\"ProfileStats column-ProfileStats\">\n";
					$displayHtml .=  "<ul style='margin: 0px;list-type:none;'>" ;

					if (!empty($data['ProfileContactEmail'])) {
							$displayHtml .=  "<li><strong>Email:</strong> <a href=\"mailto:".$data['ProfileContactEmail']."\">". $data['ProfileContactEmail'] ."</a></li>\n";
					}
					if (!empty($data['ProfileLocationStreet'])) {
							$displayHtml .=  "<li><strong>Address:</strong> ". $data['ProfileLocationStreet'] ."</li>\n";
					}
					if (!empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState'])) {
							$displayHtml .=  "<li><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". get_stateabv_by_id($data['ProfileLocationState']) ." ". $data['ProfileLocationZip'] ."</li>\n";
					}
					if (!empty($data['ProfileLocationCountry'])) {
							$country = (rb_agency_getCountryTitle($data['ProfileLocationCountry']) != false) ? rb_agency_getCountryTitle($data['ProfileLocationCountry']):"";
							$displayHtml .=  "<li><strong>". __("Country", RBAGENCY_TEXTDOMAIN) .":</strong> ". $country ."</li>\n";
					}
					if (!empty($data['ProfileLocationState'])) {
							$State = (rb_agency_getStateTitle($data['ProfileLocationState'],true) != false) ? rb_agency_getStateTitle($data['ProfileLocationState'],true):"";
							$displayHtml .=  "<li><strong>". __("State", RBAGENCY_TEXTDOMAIN) .":</strong> ". $State ."</li>\n";
					}
					if (!empty($data['ProfileDateBirth'])) {
							$displayHtml .=  "<li><strong>". __("Age", RBAGENCY_TEXTDOMAIN) .":</strong> ". rb_agency_get_age($data['ProfileDateBirth'],$arr_query) ."</li>\n";
					}
					if (!empty($data['ProfileDateBirth']) && $data['ProfileDateBirth'] !== "0000-00-00") {
							$displayHtml .=  "<li><strong>". __("Birthdate", RBAGENCY_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</li>\n";
					}
					if (!empty($data['ProfileContactWebsite'])) {
							$displayHtml .=  "<li><strong>". __("Website", RBAGENCY_TEXTDOMAIN) .":</strong> <a href=\"".$data['ProfileContactWebsite']."\" target=\"_blank\" > Click Here</a></li>\n";
					}
					if (!empty($data['ProfileContactPhoneHome'])) {
							$displayHtml .=  "<li><strong>". __("Phone Home", RBAGENCY_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneHome'] ."</li>\n";
					}
					if (!empty($data['ProfileContactPhoneCell'])) {
							$displayHtml .=  "<li><strong>". __("Phone Cell", RBAGENCY_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneCell'] ."</li>\n";
					}

					if (!empty($data['ProfileContactPhoneWork'])) {
							$displayHtml .=  "<li><strong>". __("Phone Work", RBAGENCY_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</li>\n";
					}
					$displayHtml .= rb_agency_getProfileCustomFields_admin($ProfileID ,$data['ProfileGender'],'1,2,3');
					$displayHtml .= "</ul>";
					$displayHtml .=  "            </td>\n";
					// public info
					$displayHtml .=  "            <td class=\"ProfileDetails column-ProfileDetails\">\n";
					$displayHtml .=  "<ul style='margin: 0px;list-type:none;'>" ;

					if (!empty($data['ProfileGender'])) {
						$displayHtml .=  "<li><strong>". __("Gender", RBAGENCY_TEXTDOMAIN) .":</strong> ".rb_agency_getGenderTitle($data['ProfileGender'])."</li>\n";
					} else {
						$displayHtml .=  "<li><strong>". __("Gender", RBAGENCY_TEXTDOMAIN) .":</strong> --</li>\n";
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
						$displayHtml .= "<li><strong>". __("Classification", RBAGENCY_TEXTDOMAIN) .":</strong> ".$ptype."</li>\n";
					}

					$displayHtml .= rb_agency_getProfileCustomFields_admin($ProfileID ,$data['ProfileGender']);

					$displayHtml .=  "</ul>" ;

					$displayHtml .=  "            </td>\n";
					$displayHtml .=  "            <td class=\"ProfileImage column-ProfileImage\">\n";

					if ($p_image) {
						$displayHtml .=  "				<div class=\"image\" style=\"height:240px\"><img style=\"width: 150px; \" src=\"". RBAGENCY_UPLOADDIR ."". $data['ProfileGallery'] ."/". $p_image ."\" /></div>\n";
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
				$displayHtml .=  "                <p>". __("No profiles found with this criteria!", RBAGENCY_TEXTDOMAIN) .".</p>\n";
				$displayHtml .=  "            </td>\n";
				$displayHtml .=  "        </tr>\n";
					} else {
				$displayHtml .=  "        <tr>\n";
				$displayHtml .=  "            <th class=\"check-column\" scope=\"row\"></th>\n";
				$displayHtml .=  "            <td class=\"name column-name\" colspan=\"5\">\n";
				$displayHtml .=  "                <p>". __("There aren't any Profiles loaded yet!", RBAGENCY_TEXTDOMAIN) ."</p>\n";
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
				?>
				<script type="text/javascript">
							function checkCastingCart(){
									var no_image = jQuery("input[data-disabled]:checked").length;
									var selected = jQuery("input:checked").length;

									if(selected <= 0){
										alert("Please select profiles that you want to add to casting cart")
										return false;
									} else {
										if(no_image > 0){
											alert("Profiles without photo cannot be added to casting cart. Please unselect to continue or try another search.");
											return false;
										}
										return true;
									}
									return false;
							}

				</script>

				<?php
				$displayHtml .=  "  		<input type=\"submit\" onClick=\"return checkCastingCart();\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_search') ."\" class=\"button-primary\" />\n";
				$displayHtml .=  "          <a href=\"#\" onClick=\"testPrint(1);\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ."</a>\n";
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
				$displayHtml .=  "          <a href=\"#\" onClick=\"testPrint(0); \" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ." - ". __("Without Details", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				$displayHtml .=  "          <a href=\"#\" onClick=\"testPrint(2); \" title=\"Quick Print - One Profile per Page\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ." - ". __("One Profile per Page", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				$displayHtml .=  "     </p>\n";
				$displayHtml .=  "  </form>\n";
				$displayHtml .=  "</div>";

				return $displayHtml;

		}


		/*
		 * Format Profile
		 * Create list from IDs
		 */
		public static function search_formatted($dataList,$arr_favorites = array(),$arr_castingcart = array(), $casting_availability = '',$plain = false,$arr_query = array()){

			global $wpdb;
			
				
				wp_register_style( 'fancybox-style', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.css' );
				wp_enqueue_style( 'fancybox-style' );
	
				wp_deregister_script( 'jquery-latest' ); 
				wp_register_script( 'jquery-latest', "//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
				wp_enqueue_script( 'jquery-latest' );
			
				wp_enqueue_script( 'fancybox-jquery', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.pack.js', array( 'jquery-latest' ));
				wp_enqueue_script( 'fancybox-jquery' );
				
				wp_enqueue_script( 'fancybox-init', RBAGENCY_PLUGIN_URL .'ext/fancybox/fancybox.init.js', array( 'jquery-latest', 'fancybox-jquery' ));
				wp_enqueue_script( 'fancybox-init' );
				
				wp_enqueue_script( 'voiceover-init', RBAGENCY_PLUGIN_URL .'assets/js/voiceover.init.js', array( 'jquery-latest'));
				wp_enqueue_script( 'voiceover-init' );
				
				wp_enqueue_script( 'audiojs', RBAGENCY_PLUGIN_URL .'ext/audiojs/audio.min.js', array( 'jquery-latest'));
				wp_enqueue_script( 'audiojs' );
				
				
	
			
			/* 
			 * RB Agency Options
			 */
				$rb_agency_options_arr = get_option('rb_agency_options');

				$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
				$rb_agency_option_profilelist_castingcart	= isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart']:0;
				$rb_agency_option_profilelist_favorite		= isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;
				$rb_agency_option_privacy					= isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
				$rb_agency_option_profilelist_count			= isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
				$rb_agency_option_profilelist_perpage		= isset($rb_agency_options_arr['rb_agency_option_profilelist_perpage']) ?$rb_agency_options_arr['rb_agency_option_profilelist_perpage']:0;
				$rb_agency_option_profilelist_sortby		= isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
				$rb_agency_option_layoutprofilelistlayout	= isset($rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']:0;
				$rb_agency_option_profilelist_expanddetails	= isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']) ? $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']:0;
				$rb_agency_option_locationtimezone			= isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? (int)$rb_agency_options_arr['rb_agency_option_locationtimezone']:0;
				$rb_agency_option_profilenaming				= isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
				$rb_agency_option_profilelist_printpdf		= isset($rb_agency_options_arr['rb_agency_option_profilelist_printpdf']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_printpdf']:0;
				$rb_agency_option_profilelist_thumbsslide	= isset($rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']:0;
				$rb_agency_option_detail_state 				= isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state']:0;
				$rb_agency_option_show_email_search_result	= isset($rb_agency_options_arr['rb_agency_option_formshow_email_search_result'])?$rb_agency_options_arr['rb_agency_option_formshow_email_search_result']:0;
				$rb_agency_option_show_contact_search_result	= isset($rb_agency_options_arr['rb_agency_option_formshow_contact_search_result'])?$rb_agency_options_arr['rb_agency_option_formshow_contact_search_result']:0;
				$rb_agency_option_show_email_listing		= isset($rb_agency_options_arr['rb_agency_option_formshow_email_listing'])?$rb_agency_options_arr['rb_agency_option_formshow_email_listing']:0;
				$rb_agency_option_show_contact_listing		= isset($rb_agency_options_arr['rb_agency_option_formshow_contact_listing'])?$rb_agency_options_arr['rb_agency_option_formshow_contact_listing']:0;
				$rb_agency_value_profilethumbwidth			= isset($rb_agency_options_arr['rb_agency_option_agencyprofilethumbwidth'])?$rb_agency_options_arr['rb_agency_option_agencyprofilethumbwidth']:180;
				$rb_agency_value_profilethumbheight			= isset($rb_agency_options_arr['rb_agency_option_agencyprofilethumbheight'])?$rb_agency_options_arr['rb_agency_option_agencyprofilethumbheight']:230;
				$rb_agency_option_layoutprofilelist_favcartdisp	= isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist_favcartdisp'])?$rb_agency_options_arr['rb_agency_option_layoutprofilelist_favcartdisp']:0;
				$rb_agency_option_layoutprofileviewmode = isset($rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']) ? $rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']:0;
				$rb_agency_option_layoutprofilenamepos = isset($rb_agency_options_arr['rb_agency_option_layoutprofilenamepos']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilenamepos']:0;

				// TODO: Check Logic
				$ProfileContactNameFirst = isset($dataList["ProfileContactNameFirst"]) ? $dataList["ProfileContactNameFirst"]: "-";
				$ProfileContactNameLast = isset($dataList["ProfileContactNameLast"]) ? $dataList["ProfileContactNameLast"]: "-";
				$ProfileID = $dataList["ProfileID"];

				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
					$ProfileContactDisplay = $dataList["ProfileContactDisplay"];
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID-". $ProfileID;
				} elseif ($rb_agency_option_profilenaming == 4) {
					$ProfileContactDisplay = $ProfileContactNameFirst;
				} elseif ($rb_agency_option_profilenaming == 5) {
					$ProfileContactDisplay = $ProfileContactNameLast;
				}

			/* 
			 * Initialize html
			 */
			$displayHTML ="";

			global $_list_my_profiles;
			// make shortcode as priority
			if($_list_my_profiles == 'voiceover'){
				$rb_agency_option_layoutprofilelistlayout = 1;
			}elseif($_list_my_profiles == 'lightbox'){
				$rb_agency_option_layoutprofilelistlayout = 0;
				$rb_agency_option_layoutprofileviewmode = 1; // bypass admin setting and change to popup / lightbox
			}else{
				$rb_agency_option_layoutprofilelistlayout = 0;
			}
			
			if($rb_agency_option_layoutprofilelistlayout == 0) {



			/*
			 * Default Profile Layout
			 */

				$profile_list_class = "rbprofile-list -list-layout-".$rb_agency_option_layoutprofilelistlayout;

				if($rb_agency_option_profilelist_thumbsslide == 1){
					$profile_list_class .= " name-pos-".$rb_agency_option_layoutprofilenamepos;
				}
				if($rb_agency_option_profilelist_thumbsslide == 1){
					$profile_list_class .= " thumbslide";
				}

				if($rb_agency_option_layoutprofileviewmode == 0) {
					$profile_link_class = "default";
				} elseif($rb_agency_option_layoutprofileviewmode == 1) {
					$profile_link_class = "profile-popup-fancybox";
				} else {
					$profile_link_class = "slide-panel";
				}
				
				$_proftypeClass = array();
							
				$profiType = explode(',',$dataList["ProfileType"]);
				if(is_array($profiType)){
					foreach($profiType as $val){
						$_proftypeClass[] = 'profile_type_'. $val;
					}
				}else{
					$_proftypeClass[] = 'profile_type_'. $profiType;
				}
				$profile_list_class .= ' '. implode(' ', array_unique($_proftypeClass));
				
				
				
				

				$PGENDER = $dataList["ProfileGender"] == 1 ? "Male" : "Female";
				$displayHTML .= "<div data-profileid=\"".$dataList["ProfileID"]."\" id=\"rbprofile-".$dataList["ProfileID"]."\" class=\"".$profile_list_class." ".$PGENDER."\" >\n";
				$displayHTML .= "	<div class=\"profile-box\" >\n";

				if(!$plain){
					$displayHTML .= '<input id="br'.$dataList["ProfileID"].'" type="hidden" class="p_birth" value="'.$dataList["ProfileDateBirth"].'">';
					$displayHTML .= '<input id="nm'.$dataList["ProfileID"].'" type="hidden" class="p_name" value="'.$dataList["ProfileContactDisplay"].'">';
					$displayHTML .= '<input id="cr'.$dataList["ProfileID"].'" type="hidden" class="p_created" value="'.(isset($dataList["ProfileDateCreated"])?$dataList["ProfileDateCreated"]:"").'">';
					$displayHTML .= '<input id="du'.$dataList["ProfileID"].'" type="hidden" class="p_duedate" value="'.(isset($dataList["ProfileDueDate"])?$dataList["ProfileDueDate"]:"").'">';
					

					
					
					//foreach($profile_genders as $profile_gender)

						/**if($dataList["ProfileGender"] == '1'){
							$displayHTML .= '<input id="male'.$dataList["ProfileID"].'" type="hidden" class="p_gender_male" value="Male">';
						}elseif($dataList["ProfileGender"] == '2'){
							$displayHTML .= '<input id="female'.$dataList["ProfileID"].'" type="hidden" class="p_gender_female" value="Female">';
						}**/
					$PGENDER = $dataList["ProfileGender"] == 1 ? "Male" : "Female";
					$displayHTML .= '<input id="gender'.$dataList["ProfileID"].'" type="hidden" class="p_gender" value="'.$PGENDER.'">';
					
					


					$query = " SELECT mu.ProfileCustomMuxID,mu.ProfileCustomDateValue,mu.ProfileCustomID,mu.ProfileCustomValue,c.ProfileCustomType FROM " .  table_agency_customfield_mux . " mu INNER JOIN ".table_agency_customfields." c ON mu.ProfileCustomID = c.ProfileCustomID WHERE mu.ProfileID = ".$dataList['ProfileID']." AND c.ProfileCustomShowFilter = 1";
					$customFilters = $wpdb->get_results($query,ARRAY_A);

					foreach($customFilters as $customFilter){
						$customIDPlus = $customFilter["ProfileCustomID"] + 100;
						if($customFilter['ProfileCustomType'] == 1 || $customFilter['ProfileCustomType'] == 4 || $customFilter['ProfileCustomType'] == 7){
							$displayHTML .= '<input id="type_text'. $customIDPlus . '" type="hidden" class="type_text'.$customIDPlus.'" name="type_text"  value="'.(isset($customFilter["ProfileCustomValue"])?$customFilter["ProfileCustomValue"]:"").'">';
						} elseif($customFilter['ProfileCustomType'] == 10){
							$displayHTML .= '<input id="type_text'.$customIDPlus.'" type="hidden" class="type_text'.$customIDPlus.'" name="type_text" value="'.(isset($customFilter["ProfileCustomDateValue"])?$customFilter["ProfileCustomDateValue"]:"").'">';
						} elseif($customFilter['ProfileCustomType'] == 3){
							$displayHTML .= '<input id="type_text'.$customIDPlus.'" type="hidden" class="type_text'.$customIDPlus.'" name="type_text" value="'.(isset($customFilter["ProfileCustomValue"])?$customFilter["ProfileCustomValue"]:"").'">';
						}

					}
				}

				$displayActions = "";
				$type = get_query_var('type');

				
				
				$profile_name = "<strong class=\"name\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\" class=\"".$profile_link_class."\">". stripslashes($ProfileContactDisplay) ."</a></strong>\n";
				
				if($rb_agency_option_layoutprofileviewmode == 1) {
					$profile_name = "<strong class=\"name\"><a href=\"#lightbox-fancy-".$dataList["ProfileID"] ."\" class=\"".$profile_link_class."\">". stripslashes($ProfileContactDisplay) ."</a></strong>\n";
				} 
				
				
				//admin icon settings 
				if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
					$cartIcon = "<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">";
				}else{
					$cartIcon = "<i class=\"fa fa-star\"></i>";
				} 
				
				
				if(!empty($rb_agency_options_arr['rb_agency_option_faviconurl'])){
					$favIcon = "<img src=\"{$rb_agency_options_arr['rb_agency_option_faviconurl']}\" style=\"border:0;\">";
				}else{
					$favIcon = "<i class=\"fa fa-heart\"></i>";
				}
	
				if(!$plain && class_exists("RBAgencyCasting") && is_user_logged_in() && strpos($type,"profilecastingcart") <= -1){
					$displayActions = "<div class=\"rb_profile_tool\">";
					// if($rb_agency_option_profilelist_favorite && $type != "casting"){
					if($rb_agency_option_profilelist_favorite){
						$displayActions .= "<div id=\"profile-favorite\" class=\"favorite\"><a href=\"javascript:;\" title=\""
							.(in_array($dataList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")
							."\" attr-id=\"".$dataList["ProfileID"]."\" class=\""
							.(in_array($dataList["ProfileID"], $arr_favorites)?"active":"inactive")
							." favorite\">$favIcon <span>"
							.(in_array($dataList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")
							."</span></a></div>";
					}
					$p_image = str_replace(" ", "%20", rb_get_primary_image($dataList["ProfileID"]));

					// if($rb_agency_option_profilelist_castingcart && !empty($p_image)  && $type != "favorite"){
					if($rb_agency_option_profilelist_castingcart && !empty($p_image)){
						$displayActions .= "<div  id=\"profile-casting\"  class=\"casting\"><a href=\"javascript:;\" title=\""
							.(in_array($dataList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
							."\"  attr-id=\"".$dataList["ProfileID"]."\"  class=\""
							.(in_array($dataList["ProfileID"], $arr_castingcart)?"active":"inactive")
							." castingcart\">$cartIcon <span>"
							.(in_array($dataList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
							."</span></a></div>";
					}
					$displayActions .= "</div>";

					if(!empty($casting_availability)){
						if($casting_availability == 'available'){
							$displayActions .= "<span style=\"text-align:center;color:#2BC50C;font-weight:bold;width:80%;padding:10px;display:block;\">Available</span>\n";
						} else {
							$displayActions .= "<span style=\"text-align:center;color:#EE0F2A;font-weight:bold;width:80%;padding:10px;display:block;\">Not Available</span>\n";
						}
					}
				}

				// Determine primary image
				$images = "";
				$p_image = str_replace(" ", "%20", rb_get_primary_image($dataList["ProfileID"]));
				if ($p_image){

					if($rb_agency_option_layoutprofileviewmode == 1) {
						$profile_link = "#profile-id";
						$profile_link = '#lightbox-fancy-'.$dataList["ProfileID"];
					} else {
						$profile_link = RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"];
					}
					
					$displayHTML .="<div class=\"image\">";					

					$displayHTML .= "<a href=\"". $profile_link ."\" title=\"". stripslashes($ProfileContactDisplay) ."\" class=\"".$profile_link_class."\">";

					if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){
						if($rb_agency_option_profilelist_thumbsslide == 1){ //show profile sub thumbs for thumb slide on hover
							// $images = rb_agency_profileimages($dataList["ProfileID"]); not working
							//$images = str_replace("{PHOTO_PATH}",RBAGENCY_UPLOADDIR ."". $dataList["ProfileGallery"]."/", $images);
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
							$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
							$countImg = $wpdb->num_rows;

							foreach($resultsImg as $dataImg ){
								if ($countImg > 1) {									
									$images .= " <img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $dataList["ProfileGallery"] ."/". $dataImg['ProfileMediaURL'] ."&w=".$rb_agency_value_profilethumbwidth."&h=".$rb_agency_value_profilethumbheight."&a=t\" alt=\"". stripslashes($ProfileContactDisplay) ."\" class=\"roll\" />";
								}
							}

							$displayHTML .= '<script type="text/javascript">

												jQuery("#rbprofile-'.$dataList["ProfileID"].' .image").mouseover(function(){												
													var elem = jQuery(this);
													interval = setInterval(function(){cycleImages(jQuery("#rbprofile-'.$dataList["ProfileID"].' .image"))}, 1000);												  
												});
											</script>
											';
						}						

						// Favorite and Casting Display - Image Overlay
						if($rb_agency_option_layoutprofilelist_favcartdisp == 1) {
							$displayHTML .= $displayActions;
						}

						if($rb_agency_option_layoutprofileviewmode == 1) {
							$profile_link = "#profile-id";
						} else {
							$profile_link = RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"];
						}

						$displayHTML .="<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $dataList["ProfileGallery"] ."/". $p_image ."&w=".$rb_agency_value_profilethumbwidth."&h=".$rb_agency_value_profilethumbheight."&a=t&zc=1\" alt=\"". stripslashes($ProfileContactDisplay) ."\" class=\"primary active\" />".$images."";
					} else {
						$displayHTML .="<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $dataList["ProfileGallery"] ."/". $p_image ."&w=".$rb_agency_value_profilethumbwidth."&h=".$rb_agency_value_profilethumbheight."&a=t&zc=1\" alt=\"". stripslashes($ProfileContactDisplay) ."\"  class=\"primary active\" >".$images."\n";
					}

					$displayHTML .="</a>";

					if($rb_agency_option_layoutprofilenamepos == 1) {
						$displayHTML .= $profile_name;
					}

					$displayHTML .="</div>";

				} else {
					$displayHTML .= "<div class=\"image image-broken\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/Placeholder.jpg&w=".$rb_agency_value_profilethumbwidth."&h=".$rb_agency_value_profilethumbheight."&a=t&zc=1\" alt=\"". stripslashes($ProfileContactDisplay) ."\"></div>\n";
				}				

				// Determine profile details

				$displayHTML .= "  <div class=\"profile-info\">\n";
				$uid = rb_agency_get_current_userid();

				if($rb_agency_option_layoutprofilenamepos == 0 || $rb_agency_option_layoutprofilenamepos == 2) {
					$displayHTML .= $profile_name;
				}				

				if(get_query_var('type') == "casting" && $uid > 0){
					$displayHTML .= "<input type=\"checkbox\" name=\"profileid\" value=\"".$dataList["ProfileID"]."\"/>";
				}

				if ($rb_agency_option_profilelist_expanddetails) {
					$displayHTML .= "<div class=\"details\">";
					$displayHTML .= "<span class=\"details-merged\">";

					$HideAgeYear = get_user_meta($dataList['ProfileID'],"rb_agency_hide_age_year",true);
					if($HideAgeYear == 1){
						$arr_query['year_style'] = 'style="display:none!important;"';
					} else {
						$arr_query['year_style'] = "";
					}
					$HideAgeMonth = get_user_meta($dataList['ProfileID'],"rb_agency_hide_age_month",true);
					if($HideAgeMonth == 1){
						$arr_query['month_style'] = 'style="display:none!important;"';
					} else {
						$arr_query['month_style'] = "";
					}
					$HideAgeDay = get_user_meta($dataList['ProfileID'],"rb_agency_hide_age_day",true);
					if($HideAgeDay == 1){
						$arr_query['day_style'] = 'style="display:none!important;"';
					} else {
						$arr_query['day_style'] = "";
					}
					$ProfileDateBirth = rb_agency_get_age($dataList["ProfileDateBirth"],$arr_query);

					if(!empty($ProfileDateBirth)){
					$displayHTML .= "<span class=\"details-age\">". $ProfileDateBirth ."</span>";
					}

					if($dataList["ProfileLocationState"]!=""){

						if(!empty($arr_query['show_state'])){
							$detailState = $arr_query['show_state'] == "true" ? 1 : 0;
						} else {
							$detailState = $rb_agency_option_detail_state;
						}

						$HideState = get_user_meta($dataList['ProfileID'],"rb_agency_hide_state",true);
						if($HideState == 1){
							$state_style = 'style="display:none!important;"';
						} else {
							$state_style = "";
						}

						$stateTitle =  rb_agency_getStateTitle($dataList["ProfileLocationState"],false,$arr_query);

						//$displayHTML .= "<span class=\"divider\">".(rb_agency_get_age($dataList["ProfileDateBirth"],$arr_query)>0 && !empty($stateTitle)?", ":" ")."</span>";
						if($detailState == 1){
							$displayHTML .= "<span class=\"details-state\" ".$state_style.">". $stateTitle ."</span>";
						}

					}

					$type = get_query_var("type");
					$displayHTML .= "</span>";
					if(is_user_logged_in()){
						if(in_array($type,array("search-basic","search-advanced","search-basic","search-result",'rb-pdf')) || $plain){

							$displayEmailInSearchResult = isset($arr_query['show_email_search_result']) && $arr_query['show_email_search_result'] == true ? 1 :$rb_agency_option_show_email_search_result;
							if($displayEmailInSearchResult == 1){
								if(!empty($dataList["ProfileContactEmail"]))
								$displayHTML .= "<span class=\"details-email contact\"><label>Email:</label> <a href=\"mailto:". $dataList["ProfileContactEmail"] ."\">". $dataList["ProfileContactEmail"] ."</a></span>";
							}
							$displayContactSearchResult = isset($arr_query['show_contact_search_result']) && $arr_query['show_contact_search_result'] == true ? 1 :$rb_agency_option_show_contact_search_result;
							if($displayContactSearchResult == 1){
								if(!empty($dataList["ProfileContactPhoneWork"]))
								$displayHTML .= "<span class=\"details-contact-phonework contact\"><label>Phone Work:</label> ". $dataList["ProfileContactPhoneWork"] ."</span>";
								if(!empty($dataList["ProfileContactPhoneHome"]))
								$displayHTML .= "<span class=\"details-contact-phonehome contact\"><label>Phone Home:</label> ". $dataList["ProfileContactPhoneHome"] ."</span>";
								if(!empty($dataList["ProfileContactPhoneCell"]))
								$displayHTML .= "<span class=\"details-contact-phonecell contact\"><label>CellPhone:</label> ". $dataList["ProfileContactPhoneCell"] ."</span>";
								if(!empty($dataList["ProfileContactWebsite"]))
								$displayHTML .= "<span class=\"details-contact-website contact\"><a href=\"".$dataList["ProfileContactWebsite"]."\" target=\"_blank\" rel=\"nofollow\">".($type == "rb-pdf"?$dataList["ProfileContactWebsite"]:"Visit Website")."</a></span>";
							}
						} else {
							$displayEmailListing = isset($arr_query['show_email_listing']) && $arr_query['show_email_listing'] == true ? 1 :$rb_agency_option_show_email_listing;
							if($displayEmailListing == 1){
								if(!empty($dataList["ProfileContactEmail"]))
								$displayHTML .= "<span class=\"details-email contact\"><label>Email:</label> <a href=\"mailto:". $dataList["ProfileContactEmail"] ."\">". $dataList["ProfileContactEmail"] ."</a></span>";
							}
							$displayContactListing = isset($arr_query['show_contact_listing']) && $arr_query['show_contact_listing'] == true ? 1 :$rb_agency_option_show_contact_listing;
							if($displayContactListing == 1){
								if(!empty($dataList["ProfileContactPhoneWork"]))
								$displayHTML .= "<span class=\"details-contact-phonework contact\"><label>Phone Work:</label> ". $dataList["ProfileContactPhoneWork"] ."</span>";
								if(!empty($dataList["ProfileContactPhoneHome"]))
								$displayHTML .= "<span class=\"details-contact-phonehome contact\"><label>Phone Home:</label> ". $dataList["ProfileContactPhoneHome"] ."</span>";
								if(!empty($dataList["ProfileContactPhoneCell"]))
								$displayHTML .= "<span class=\"details-contact-phonecell contact\"><label>CellPhone:</label> ". $dataList["ProfileContactPhoneCell"] ."</span>";
								if(!empty($dataList["ProfileContactWebsite"]))
								$displayHTML .= "<span class=\"details-contact-website contact\"><a href=\"".$dataList["ProfileContactWebsite"]."\" target=\"_blank\" rel=\"nofollow\">".($type == "rb-pdf"?$dataList["ProfileContactWebsite"]:"Visit Website")."</a></span>";
							}
						}
					}
					$displayHTML .= "</div>\n";
				}

					// Favorite and Casting Display - Default
					if($rb_agency_option_layoutprofilelist_favcartdisp == 0) {
						$displayHTML .= $displayActions;
					}

				$displayHTML .=" </div> <!-- .profile-info - profile-class --> \n";
				$displayHTML .=" </div> <!-- .profile-box --> \n";
				$displayHTML .=" </div> <!-- .rbprofile-list --> \n";
				
				
				$_likeexp = $wpdb->esc_like('Experience');
				$_likeexp = '%' . $_likeexp . '%';
			$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT 
				c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomView,
				cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx 
				LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID 
				WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $dataList["ProfileID"] ."
				AND c.ProfileCustomTitle LIKE %s
				GROUP BY cx.ProfileCustomID",$_likeexp ));
		
		 //
		//Experience
		$ProfileExperience='';
		foreach ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom->ProfileCustomValue )){
				$ProfileExperience .= $resultCustom->ProfileCustomValue;
			}
		}
				
				
				$outLinkResume = '';
				$queryMedia = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = \"%s\" AND ProfileMediaType = \"Resume\"";
				$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryMedia,$dataList["ProfileID"]),ARRAY_A);
				
				foreach($resultsMedia as $dataMedia ){
					$outLinkResume = RBAGENCY_PLUGIN_URL."ext/forcedownload.php?file=". $dataList["ProfileGallery"] ."/". $dataMedia['ProfileMediaURL'];
				}
				
				$displayHTML .='
				
				<div style="display:none">
				<div id="lightbox-fancy-'.$dataList["ProfileID"].'" class="profile-fancy white-popup">
					<div class="profile-photo">
						<img src="'. RBAGENCY_UPLOADDIR . $dataList["ProfileGallery"] .'/'. $p_image .'" alt="'. stripslashes($ProfileContactDisplay) .'"  class="primary active" />
						
					</div>
					
					<div class="info">
						<h3>'. stripslashes($ProfileContactDisplay).'</h3>
						<p>'. stripslashes($ProfileExperience).'</p>						
						<ul>
							<li><a href="'. get_bloginfo("url").$outLinkResume.'" title="Download Resume" target="_blank">Download Resume</a></li>
							<li><a href="'. RBAGENCY_PROFILEDIR . $dataList["ProfileGallery"] .'" title="'.stripslashes($ProfileContactDisplay).'">More</a></li>
						</ul>
						
					</div>
					
				</div>
				</div>
				';
				
				

			}
			elseif($rb_agency_option_layoutprofilelistlayout == 1){

			/*
			 * Voiceover Mode
			 */
			 
			 
			 
				$querys = "SELECT * FROM ". table_agency_data_media;
				$results = $wpdb->get_results($querys, ARRAY_A);
				$count = $wpdb->num_rows;
				
				$_allMedLinkSQL = array();
				
				
				foreach ($results as $data) {
					$_allMedLinkSQL[] = "'$data[MediaCategoryTitle]'";
				}
				
				$_allMediaSQL = implode(', ',$_allMedLinkSQL);
		        $_allMediaSQL  = ' , '. $_allMediaSQL;
			 
			 //$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType = \"VoiceDemo\" ";
			  
			 //
			 
			 
			 
			 $queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType 
			        NOT IN ('Link','Image','Demo Reel','Video Monologue','Video Slate','SoundCloud','Resume','Headshot','Polaroid','CompCard')";
			 
							$resultsMedia =  $wpdb->get_results($wpdb->prepare($queryMedia, $ProfileID),ARRAY_A);
							$countMedia = $wpdb->num_rows;
							$outVideoMedia = "";
							$outLinkVoiceDemo = "";
							$outLinkResume = "";
							$outLinkHeadShot = "";
							$outLinkPolaroid = "";
							$outLinkComCard = "";
							$outCustomMediaLink = "";
							$outSoundCloud = "";
							
							
							
							
							$voicedemo_links = '<ul class="links">';
							if(!empty($dataList["ProfileContactWebsite"])){
								$voicedemo_links .= '<li class="site_link"><a href="'.$dataList["ProfileContactWebsite"].'" title="Site Link" class="mp3-link icon-website rb-icon" target="_blank">
									<img src="'.RBAGENCY_PLUGIN_URL .'assets/img/icon-website.png" alt="" /></a>
									</li>';
							}
							//<li>'.sanitize_title($dataMedia['ProfileMediaType']).' <a href="'.$mp3link.'" title="" class="mp3-link icon-website rb-icon">
							
							$voicedemo_links .= (count($resultsMedia) > 1) ? '<li class="site_link hover-audio"><i class="fa fa-bars"></i><ul>' : '';	// add dropdown for multiple audio
							$_mp3typeClass = array();
							$audios = 0;
							foreach ($resultsMedia  as $dataMedia) {
								$audios++;
								$_typeClass = sanitize_title_with_dashes($dataMedia['ProfileMediaType']);
								$_typeClass = str_replace('/','', $_typeClass);
							
								//custom database mp3 type.
								if (strpos($dataMedia['ProfileMediaType'] ,"rbcustommedia") !== false){
									//we need to get only the ID.
									//rbcustommedia_audio-book_button_mp3_71
									$_revType = strrev($dataMedia['ProfileMediaType']); // 17_3pm_nottub..
									$explTyep = explode('_',$_revType); //17 - 3pm - nottub
									$_typeID = strrev($explTyep[0]); //71
									$_typeClass = 'custom_mp3_' . $_typeID;
									$_mediaTypeArr = explode("_", $dataMedia['ProfileMediaType']);
									$_typeLabel = $_mediaTypeArr[1];									
								}
								
								$_mp3typeClass[] = $_typeClass;
								$mp3link = RBAGENCY_UPLOADDIR . $dataList["ProfileGallery"]. "/" . $dataMedia['ProfileMediaURL'];
								
								$voicedemo_links .= "\n";								
								$voicedemo_links .= '<li class="'.$_typeClass.'"><a href="#" title="" alt="" class="play-button" voicelink="'.$mp3link.'"><i class="fa fa-play"></i></a></li>';								
							}

							$voicedemo_links .= (count($resultsMedia) > 1) ? '</ul></li>' : ''; // close for multiple audio dropdown

							$voicedemo_links .= '</ul><!-- .links -->';
							
							$_proftypeClass = array();
							
							$profiType = explode(',',$dataList["ProfileType"]);
							if(is_array($profiType)){
								foreach($profiType as $val){
									$_proftypeClass[] = 'profile_type_'. $val;
								}
							}else{
								$_proftypeClass[] = 'profile_type_'. $profiType;
							}
							$_profiletypeClassUniq = implode(' ', array_unique($_proftypeClass));
			                $_mp3typeClassUniq = implode(' ', array_unique($_mp3typeClass));
			                //$displayHTML .= 'profile_type_'. $dataList["ProfileType"];
			                

				$displayHTML .= '
				<div data-profileid="'.$dataList["ProfileID"].'" id="rbprofile-'.$dataList["ProfileID"].'" class="rbprofile-list '.$_profiletypeClassUniq.' '.$_mp3typeClassUniq.'" mp3_type="'.$_mp3typeClassUniq.'">
					<div class="profile-voiceover">
					   	<strong class="name"><a href="'. RBAGENCY_PROFILEDIR . $dataList["ProfileGallery"] .'">
						'. stripslashes($ProfileContactDisplay) .'</a></strong>'.$voicedemo_links .'
					</div><!-- .profile-voiceover -->
				</div> <!-- .? -->';
				
				
				
				

/* TODO: ADD VOICEOVER HTML HERE */


			}
			
			
			$displayHTML .='
			<style>
				.profile-fancy .profile-photo {
				  width: 60%;
				  margin-right: 5%;
				  float: left;
				}
				.profile-fancy .profile-photo img {
				  max-width: 100%;
				}
				.profile-fancy .info {
				  width: 35%;
				  float: left;
				}
				.profile-fancy .info h3 {
					margin-bottom: 15px;
				}
				.profile-fancy .info p,
				.profile-fancy .info a {
					font-size: medium;
				}				
				</style>
				';

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

			if($ProfileCustomType!=4) {

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
						echo "	<label for=\"ProfileCustomLabel_min first\" style=\"text-align:right;\">". __("Min", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>";
						echo "</div>";
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min first\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>";
						echo "</div>";
					} else {
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min second\" style=\"text-align:right;\">". __("Min", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
						echo "	<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>";
						echo "</div>";
						echo "<div>";
						echo "	<label for=\"ProfileCustomLabel_min second\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>";
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
				} elseif ($ProfileCustomType == 6) {
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


					list($min_val,$max_val) =  isset($_POST["ProfileCustomID".$data1['ProfileCustomID']])?$_POST["ProfileCustomID".$data1['ProfileCustomID']]:@explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);

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
							."_min third\">Min</label><input value=\""
							.(!is_array($min_val) && $min_val != "Array" ? $min_val : "")
							."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID"
							.$data1['ProfileCustomID']."[]\" /></div>";

							echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']
							."_max third\">Max</label><input value=\"".$max_val
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

	/*********************************************************************************/
	/* Shortcodes */
	/*********************************************************************************/


	public static function view_featured($atts, $content = NULL) {
		/*
		if (function_exists('rb_agency_profilefeatured')) {
			$atts = array('count' => 8, 'type' => 0);
			rb_agency_profilefeatured($atts); }
		 */
		// Set It Up
		global $wp_rewrite;
		global $wpdb;

		extract(shortcode_atts(array(
				"type" => 0,
				"count" => 1
		), $atts));

		if ($type == 1) { // Featured
			$sqlWhere = " AND profile.ProfileIsPromoted=1";
		}
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
		if ( //Must be logged to view model list and profile information
		($rb_agency_option_privacy == 2 && is_user_logged_in()) || 
		// Model list public. Must be logged to view profile information
		($rb_agency_option_privacy == 1 && is_user_logged_in()) ||
		// All Public
		($rb_agency_option_privacy == 0) ||
		//admin users
		(is_user_logged_in() && current_user_can( 'edit_posts' )) ||
		//  Must be logged in as Casting Agent to View Profiles
		($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()) ) {
				echo "<div id=\"profile-featured\">\n";
				/*
				 * Execute Query
				 */
					$queryList = "SELECT profile.*,
					(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
					WHERE profile.ProfileID = media.ProfileID 
					AND media.ProfileMediaType = \"Image\" 
					AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
					FROM ". table_agency_profile ." profile 
					WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
					AND profile.ProfileIsFeatured = 1  
					ORDER BY RAND() LIMIT 0,$count";
				$resultsList =$wpdb->get_results($queryList,ARRAY_A);
				$countList = count($resultsList);
				foreach($resultsList as $dataList) {
					echo "<div class=\"rbprofile-list\">\n";
					if (isset($dataList["ProfileMediaURL"]) ) {
					echo "  <div class=\"image\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"". RBAGENCY_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"] ."\" /></a></div>\n";
					} else {
					echo "  <div class=\"image image-broken\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
					}
					echo "<div class=\"profile-info\">";
							$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
							if ($rb_agency_option_profilenaming == 0) {
								$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". $dataList["ProfileContactNameLast"];
							} elseif ($rb_agency_option_profilenaming == 1) {
								$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". substr($dataList["ProfileContactNameLast"], 0, 1);
							} elseif ($rb_agency_option_profilenaming == 2) {
								$ProfileContactDisplay = $dataList["ProfileContactNameFirst"];
							} elseif ($rb_agency_option_profilenaming == 3) {
								$ProfileContactDisplay = "ID ". $ProfileID;
							} elseif ($rb_agency_option_profilenaming == 4) {
								$ProfileContactDisplay = $ProfileContactNameFirst;
							} elseif ($rb_agency_option_profilenaming == 5) {
								$ProfileContactDisplay = $ProfileContactNameLast;
							}
					echo "     <h3 class=\"name\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". $ProfileContactDisplay ."</a></h3>\n";
					if (isset($rb_agency_option_profilelist_expanddetails)) {
						echo "<div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"],$arr_query) ."</span>";
						if($dataList["ProfileLocationState"]!=""){
							echo "<span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span>";
						}
						echo "</div>\n";
					}
					if(is_user_logged_in() && function_exists("rb_agency_get_miscellaneousLinks")){
						// Add Favorite and Casting Cart links
						rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
					}
					echo "  </div><!-- .profile-info -->\n";
					echo "</div><!-- .rbprofile-list -->\n";
				}
				if ($countList < 1) {
					echo __("No Featured Profiles", RBAGENCY_TEXTDOMAIN);
				}
				echo "  <div style=\"clear: both; \"></div>\n";
				echo "</div><!-- #profile-featured -->\n";
			}
	}



	/**
	 * List Categories
	 *
	 * @param array $atts 
	 */
	public static function view_categories($atts, $content = NULL) {
		/*
		EXAMPLE USAGE: 
		if (function_exists( array($RBAgency_Profile, 'view_categories') ) ) {
			$atts = array('profilesearch_layout' => 'advanced');
			view_categories($atts); }
		 */

		// Set It Up
		global $wpdb, $wp_rewrite;
		extract(shortcode_atts(array(
			"profilesearch_layout" => "advanced"
		), $atts));

		// Query
		$queryList = "SELECT dt.DataTypeID, dt.DataTypeTitle, dt.DataTypeTag, 
						(SELECT COUNT(profile.ProfileID) FROM ".table_agency_profile." profile WHERE dt.DataTypeID= profile.ProfileType and profile.ProfileIsActive = 1) AS CategoryCount  
						FROM ".table_agency_data_type." dt 
						ORDER BY dt.DataTypeTitle ASC";
		$resultsList = $wpdb->get_results($queryList,ARRAY_A);
		$countList = count($resultsList);

		// Loop through Results
		foreach ($resultsList as $dataList) {
			echo "<div class=\"profile-category\">\n";
			if ($DataTypeID == $dataList["DataTypeID"]) {
				echo "  <div class=\"name\"><strong>". $dataList["DataTypeTitle"] ."</strong> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			} else {
				echo "  <div class=\"name\"><a href=\"/profile-category/". $dataList["DataTypeTag"] ."/\">". $dataList["DataTypeTitle"] ."</a> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			}
			echo "</div>\n";
		}
		if ($countList < 1) {
			echo __("No Categories Found", RBAGENCY_TEXTDOMAIN);
		}
	}


}
?>
