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
		echo "				        <th scope=\"row\">". __("First Name", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION['ProfileContactNameFirst'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Last Name", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION['ProfileContactNameLast'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		
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
					$query1 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ."";
					$results1 = mysql_query($query1);
					$count1 = mysql_num_rows($results1);
					if ($count1 > 0) {
						echo " <option value=\"\">All Gender</option>";
						while ($data1 = mysql_fetch_array($results1)) {
							
							echo " <option value=\"". $data1["GenderID"] ."\" ". selected( $_SESSION['ProfileGender'], $data1["GenderID"]) .">". $data1["GenderTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						echo "". __("No items to select", rb_restaurant_TEXTDOMAIN) .".";
					}
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Age", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";
		echo "				        <fieldset>\n";
		echo "				        	<div><label>". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /><br /></div>\n";
		echo "				        	<div><label>". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>\n";
		echo "				        </fieldset>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
          
			//rb_custom_fields(0, $ProfileID, $ProfileGender,false);
			$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 AND ProfileCustomTitle IN('Ethnicity','Height','Hair Color','Eye Color','Shirt Size','Bust','Dress Size','Inseam','Union Status')  ORDER BY ProfileCustomOrder ASC";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								$pos = 0;
		while ($data1 = mysql_fetch_array($results1)) { 
			
									$ProfileCustomID = $data1['ProfileCustomID'];
									$ProfileCustomTitle = $data1['ProfileCustomTitle'];
									$ProfileCustomType = $data1['ProfileCustomType'];
									$ProfileCustomValue = $data1['ProfileCustomValue'];
			
  echo "				    <tr>\n";
  echo " 				    \n";
  
  if($ProfileCustomType!=4)	{		
			

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
						if($data1['ProfileCustomOptions'] == 1){
						     $measurements_label  ="<em>(In Inches)</em>";
						}elseif($data1['ProfileCustomOptions'] == 2){
						  $measurements_label  ="<em>(In Pounds)</em>";
						}elseif($data1['ProfileCustomOptions'] == 3){
						  $measurements_label  ="<em>(In Inches/Feet)</em>";
						}
					}elseif($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
						if($data1['ProfileCustomOptions'] == 1){
						     $measurements_label  ="<em>(cm)</em>";
						}elseif($data1['ProfileCustomOptions'] == 2){
						     $measurements_label  ="<em>(kg)</em>";
						}elseif($data1['ProfileCustomOptions'] == 3){
						  $measurements_label  ="<em>(In Inches/Feet)</em>";
						}
					}
					
			 }
 	 echo " 				    <th scope=\"row\>\n";
                   
			                                     if($ProfileCustomType==7){
			 echo "				       <div class=\"label\">". $data1['ProfileCustomTitle'].$measurements_label."</div> \n";
									 }else{
			 echo "				       <div><label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label></div> \n";							 
	 
	 								 }
    
    			echo  "			</th>		";
			echo  "			<td>";
									if ($ProfileCustomType == 1) { //TEXT
										echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";		
									} elseif ($ProfileCustomType == 2) { // Min Max
										echo "<fieldset>";
									   
											$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data1['ProfileCustomOptions'],"}"),"{"));
											list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
										   
											if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
												      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label></div>\n";
													echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>\n";
													echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label></div>\n";
													echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>\n";
											} else {
												      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
													echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
												      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
													echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
											}
										echo "</fieldset>";
									 
									} elseif ($ProfileCustomType == 3) {
										
									  		list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
											
											$data = explode("|",$option1);
											$data2 = explode("|",$option2);										
											
								         
											echo "<div><label>".$data[0]."</label></div>";
											
											echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">\n";
											echo "<option value=\"\">--</option>";
											  
												foreach($data as $val1){
													
													if($val1 != end($data) && $val1 != $data[0]){
													
													     $isSelected = "";
														if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]==$val1){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
														}else{
																		echo "<option value=\"".$val1."\" >".$val1."</option>"; 
														}												
													}
												}
											  	echo "</select></div>\n";
												
											/*	
											if(!empty($data2) && !empty($option2)){
												       echo "<div><label>".$data2[0]."</label></div>";
											
											 		$pos2 = 0;
													echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
													echo "<option value=\"\">--</option>";
													foreach($data2 as $val2){
														  
															if($val2 != end($data2) && $val2 !=  $data2[0]){
																if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]==$val2){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";
																}else{
																		echo "<option value=\"".$val2."\" >".$val2."</option>"; 
																}
															}
														}
													echo "</select></div>\n";
											
											}
									   */
										
									} elseif ($ProfileCustomType == 4) {
										echo "<fieldset>";
										echo "<div><textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] ."</textarea></div>";
										echo "</fieldset>";
									}
									elseif ($ProfileCustomType == 5) {
										$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										echo "<fieldset>";
											foreach($array_customOptions_values as $val){
												if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']])){ 
													   
												  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
													if(in_array($val,$dataArr,true)){
														echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														echo " ". $val."</label><br />";
											  		} else {
														echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														echo " ". $val."</label><br />";
													}
											  	} else {
												 	echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
												 	echo " ". $val."</label><br />";
												}
										  	}
												  echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";
									  	echo "</fieldset>";
									       
									}
									elseif ($ProfileCustomType == 6) {
									   	$array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
									   	echo "<fieldset>";
											foreach($array_customOptions_values as $val){
												if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] !=""){ 
												   
												  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
													
												  	if(in_array($val,$dataArr) && $val !=""){
													 	echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														echo " ". $val."</label><br />";
												  	} else {
														echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														echo " ". $val."</label><br />";
													}
											  	} else {
													echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
													echo " ". $val."</label><br />";	
												}
										  	}
										    echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";
									    echo "</fieldset>";
									}
									
									elseif ($ProfileCustomType == 7){
										echo "<fieldset>";
										    list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);
											
										    echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']."_min\">Min:</label><input value=\"".(!is_array($min_val) && $min_val != "Array" ? $min_val : "")."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
										    echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']."_max\">Max:</label><input value=\"".$max_val."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
									    echo "</fieldset>";
											
									}
			
	
			
			}
			echo  "			</td>";
			echo  "			</tr>";
		}
   
		
		echo "				  </thead>\n";
		echo "				</table>\n";
		echo "				<p class=\"submit\">\n";
		echo "				<input type=\"submit\" value=\"". __("Quick Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "				<a href=\"?page=rb_agency_menu_search\" class=\"button-secondary\">". __("Advanced Search", rb_agency_TEXTDOMAIN) . "</a></p>\n";
		echo "				</p>\n";
		echo "        	<form>\n";
		
	   } // Editor  

echo "        </div>\n"; 
echo "     </div>\n"; 

echo "    </div><!-- .container -->\n"; 

echo "    <div class=\"boxblock-container\" style=\"width: 46%;\">\n"; 

echo "     <div class=\"boxblock\">\n"; 
echo "        <h3>". __("Actions", rb_agency_TEXTDOMAIN) . "</h3>\n"; 
echo "        <div class=\"inner\">\n"; 

			   if ($user_level >= 7) {
           echo "<a href='?page=rb_agency_menu_profiles' class=\"button-secondary\">". __("Manage Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Browse and edit existing profiles", rb_agency_TEXTDOMAIN) . ".";
           echo "<br/>";
		  $queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." ");
		  $queryGenderCount = mysql_num_rows($queryGenderResult);
		  echo "<div style=\"margin-top:20px;margin-bottom:20px;\">";
		  while($fetchGender = mysql_fetch_assoc($queryGenderResult)){
			 echo "	<a style=\"margin-bottom:10px !important;float:left;\" class=\"button-primary\" href=\"". admin_url("admin.php?page=rb_agency_menu_profiles&action=add&ProfileGender=".$fetchGender["GenderID"])."\">". __("Create New ".ucfirst($fetchGender["GenderTitle"])."", rb_agency_TEXTDOMAIN) ."</a>\n";
		  }
		  echo "</div>";
		  if($queryGenderCount < 1){
			echo "<br/><p>". __("No Gender Found. <a href=\"". admin_url("admin.php?page=rb_agency_menu_settings&ampConfigID=5")."\">Create New Gender</a>", rb_agency_TEXTDOMAIN) ."</p>\n";
		  
		  } 
		echo "<div style=\"clear:both;\">";
		echo "<a href='?page=rb_agency_menu_search' class=\"button-secondary\">". __("Search Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Find and send profiles by filtering by chriteria", rb_agency_TEXTDOMAIN) . ".";
		echo "</div>";
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