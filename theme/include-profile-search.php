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
	
	if (isset($_REQUEST['ProfileType']) && !empty($_REQUEST['ProfileType'])) { $_SESSION['ProfileType'] = $_REQUEST['ProfileType']; }
	if (isset($DataTypeID) && !empty($DataTypeID)) { $_SESSION['ProfileType'] = $DataTypeID; }
	if (isset($_REQUEST['ProfileGender']) && !empty($_REQUEST['ProfileGender'])) {  $_SESSION['ProfileGender'] = $_REQUEST['ProfileGender']; }
	
	
	/* ----------------------------------------------------------------------
	 *	test layout here
	 * ----------------------------------------------------------------------*/
	
	# we are inside the content only
	if(in_the_loop() && is_main_query()){
		
		if ( (isset($_POST['basic_search']) || !isset($_POST['page'])) && !isset($_GET[srch])) {
			$lay_out = "BASIC-LAYOUT";
		} else {
			$lay_out = "ADVANCE-LAYOUT";
		}
			
	# we are inside the widgets only
	} else {
		if ( $profilesearch_layout != "advanced") {
			$lay_out = "BASIC-LAYOUT";
		} else {
			$lay_out = "ADVANCE-LAYOUT";
		}
	} 
?>
     <!-- RESET BACKUP -->
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery.fn.rset = function(){
			jQuery(this).on("click",function(){
			    var inputs = jQuery(".search-field").find("input");
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
	switch ($lay_out) {
		
	/* ----------------------------------------------------------------------
	 *	BASIC SEARCH ONLY IN CONTENT
	 * ---------------------------------------------------------------------- */		
	case "BASIC-LAYOUT":
	
		echo "		<div id=\"profile-search-form-condensed\" class=\"rbsearch-form\">\n";
		echo "        	<form method=\"post\" id=\"search-form-condensed\" action=\"". get_bloginfo("wpurl") ."/profile-search/\">\n";
		echo "        		<div><input type=\"hidden\" name=\"form_action\" value=\"search_profiles\" /></div>\n";
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileFirstName\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label>\n";
		echo "		 				<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" />\n";
		echo "	 				</div>\n";
		echo "	 				<div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileLastName\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label>\n";
		echo "						 <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" />\n";
		echo "					 </div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = $wpdb->get_results($query,ARRAY_A);
											foreach($results2 as $dataType) {
												if ($_SESSION['ProfileType']) {
													if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>\n";
		echo "				    </div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("All Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = $wpdb->get_results($query2,ARRAY_A);
											foreach($results2 as $dataGender) {
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"],false).">". $dataGender["GenderTitle"] ."</option>";
											}
		echo "				        </select>\n";
		echo "				    </div>\n";
		echo "				    <fieldset class=\"search-field multi\">";
		echo "				        <legend>". __("Age", rb_agency_TEXTDOMAIN) . "</legend>";
		echo "				    <div>";
		echo "				        <label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>";
		echo "				    </fieldset>";
                
		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div class=\"search-field submit\">";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/'\" />";
		echo "				<input type=\"button\" name=\"search\" id=\"rst_btn\" value=\"". __("Empty Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"clearForm();\" />";
		echo "				<input type=\"submit\" name=\"advanced_search\" value=\"". __("Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search/?srch=1'\" />";
		echo "				</div>\n";
		echo "        	</form>\n";
		echo "        	<script>function clearForm(){";
		echo "			$('ProfileContactNameFirst').val('');";
		echo "			$('ProfileType').val('');";
		echo "			$('ProfileGender').val('');";
		echo "			$('ProfileContactNameLast').val('');";
		echo "			$('ProfileDateBirth_min').val('');";
		echo "			$('ProfileDateBirth_max').val('');";
		echo "			$('.rbcheckbox input[type=checkbox]').each(function(){";
		echo "			$(this).removeAttr('checked');";
		echo "			});";
		echo "        	}</script>";
		echo "		</div>\n";
		break;
 
       /* ----------------------------------------------------------------------
 	*	ADVANCE SEARCH ONLY IN CONTENT
 	* ---------------------------------------------------------------------- */	
  	case "ADVANCE-LAYOUT":
	
		echo "  <form method=\"post\" id=\"search-form-advanced\" action=\"". get_bloginfo("wpurl") ."/profile-search/\" class=\"rbsearch-form\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"rb_agency_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"form_action\" value=\"search_profiles\" />\n";
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileFirstName\">". __("First Name", rb_agency_TEXTDOMAIN) ."</label>\n";
	        echo "		 				<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION["ProfileContactNameFirst"] ."\" />\n";
	        echo "	 				</div>\n";
		echo "	 				<div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileLastName\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</label>\n";
	        echo "						 <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION["ProfileContactNameLast"] ."\" />\n";
		echo "					 </div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileType\">". __("Type", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "						<select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "								<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
											$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
											$results2 = $wpdb->get_results($query,ARRAY_A);
											foreach($results2 as $dataType) {
												echo "<option value=\"". $dataType["DataTypeID"] ."\"".selected($_SESSION['ProfileType'],$dataType["DataTypeID"] ,false).">". $dataType["DataTypeTitle"] ."</option>";
											}
		echo "				        	</select>";
		echo "				    </div>\n";
		echo "				    <div class=\"search-field single\">\n";
		echo "				       <label for=\"ProfileGender\">". __("Gender", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				       <select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("All Gender", rb_agency_TEXTDOMAIN) . "</option>\n";
											$query2 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." ORDER BY GenderID";
											$results2 = $wpdb->get_results($query2,ARRAY_A);
											foreach($results2 as $dataGender) {
												if ($_SESSION['ProfileGender']) {
													if ($dataGender["GenderTitle"] ==  $_SESSION['ProfileGender']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
												} else { $selectedvalue = ""; }
												echo "<option value=\"". $dataGender["GenderID"] ."\"".selected($_SESSION['ProfileGender'],$dataGender["GenderID"] ,false).">". $dataGender["GenderTitle"] ."</option>";
											}
	        echo "				        </select>\n";
		echo "				    </div>\n";
		
		echo "				    <fieldset class=\"search-field multi\">";
		echo "				        <legend>". __("Age", rb_agency_TEXTDOMAIN) . "</legend>";
		
		echo "				    <div>";
		echo "				        <label for=\"ProfileDateBirth_min\">". __("Min", rb_agency_TEXTDOMAIN) . "</label>";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /></div>";
		echo "				        <div><label for=\"ProfileDateBirth_max\">". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>";
		echo "				    </fieldset>";
		include("include-custom-fields.php");
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileCity\">". __("City", rb_agency_TEXTDOMAIN) ."</label>\n";
	        echo "		 				<input type=\"text\" id=\"ProfileCity\" name=\"ProfileCity\" value=\"". $_SESSION["ProfileCity"] ."\" />\n";
	        echo "	 				</div>\n";
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileState\">". __("State", rb_agency_TEXTDOMAIN) ."</label>\n";
	        echo "		 				<input type=\"text\" id=\"ProfileState\" name=\"ProfileState\" value=\"". $_SESSION["ProfileState"] ."\" />\n";
	        echo "	 				</div>\n";
		echo "	 			      <div class=\"search-field single\">\n";
		echo "		 				<label for=\"ProfileZip\">". __("Zip", rb_agency_TEXTDOMAIN) ."</label>\n";
	        echo "		 				<input type=\"text\" id=\"ProfileZip\" name=\"ProfileZip\" value=\"". $_SESSION["ProfileZip"] ."\" />\n";
	        echo "	 				</div>\n";
		echo "				<div><input type=\"hidden\" name=\"ProfileIsActive\" value=\"1\" /></div>\n";
		echo "				<div class=\"search-field submit\">";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/profile-search/'\" />";
		echo '				<input type="button" id="rst_btn" class=\"button-primary\" value="Empty Form">';
		echo "				<input type=\"submit\" name=\"basic_search\" value=\"". __("Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search'\" />";
		echo "				</div>";
 	        echo'				<div></div>';
		echo "        	</form>\n";
	    break;
	}
   
?>