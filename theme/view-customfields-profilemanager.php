<?php
				
									$q2 = mysql_query("SELECT ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = ". $data1['ProfileCustomID'] ." AND ProfileID = ". $ProfileID." ");
									$data2 = mysql_fetch_assoc($q2);
									
									$ProfileCustomValue = $data2['ProfileCustomValue'];
									if ($ProfileCustomType == 1) { //TEXT
										
										if(!empty($ProfileCustomValue)){
										
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
									
											
										}else{
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										}
										
									} elseif ($ProfileCustomType == 2) { // Min Max
									
									   
										$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data1['ProfileCustomOptions'],"}"),"{"));
										list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
									   
									 
										if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
											    echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
												echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
									
											
										}else{
											    echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" />\n";
											    echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										}
									 
									} elseif ($ProfileCustomType == 3) { // Dropdown
										
										
										
									  list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
											
											$data1 = explode("|",$option1);
											$data2 = explode("|",$option2);
											
											
								
											echo "<label>".$data1[0]."</label>";
											echo "<select name=\"ProfileCustomID". $ProfileCustomID  ."[]\">\n";
											   
												foreach($data1 as $val1){
													
													
															if($val1 != end($data1) && $val1 != $data1[0]){
															
																  if(in_array($val1,explode(",",$ProfileCustomValue))){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";  
																   }else{
																		echo "<option value=\"".$val1."\">".$val1."</option>";
																   }
															}
												}
											  	echo "</select>\n";
												
												
											if(!empty($data2) && !empty($option2)){
													echo "<label>".$data2[0]."</label>";
											
											 		
													echo "<select name=\"ProfileCustomID". $ProfileCustomID  ."[]\">\n";
													foreach($data2 as $val2){
														  
															if($val2 != end($data2) && $val2 != $data2[0]){
															    echo "lol$val2 == $ProfileCustomValue";
																  if(in_array($val2,explode(",",$ProfileCustomValue))){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";  
																   }else{
																		echo "<option value=\"".$val2."\">".$val2."</option>";
																   }
															}
															
														}
													echo "</select>\n";
											
											}
									   
										
										
									}
									
									 elseif ($ProfileCustomType == 4) {
										echo "<textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $ProfileCustomValue ."</textarea>";
									}
									 elseif ($ProfileCustomType == 5) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										          echo "<div style=\"width:300px;float:left;\">";
												  foreach($array_customOptions_values as $val){
													
													     if(in_array($val,explode(",",$ProfileCustomValue))){
															  $isChecked = "checked=\"checked\"";
															   echo "<label><input type=\"checkbox\" value=\"".$val."\" ".$isChecked." name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";
														 }else{
														 echo "<label><input type=\"checkbox\" value=\"".$val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";
														 }
												  }
												  echo "</div>";
									       
									}
									elseif ($ProfileCustomType == 6) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										      
												  foreach($array_customOptions_values as $val){
													   
														    if($val == $ProfileCustomValue){
															  $isChecked = "checked=\"checked\" ";
															    echo "<input type=\"radio\" value=\"". $val."\"  $isChecked  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" />";
														 	    echo "<span style=\"color:white;\">". $val."</span><br/>";   
														   }else{
														 		echo "<input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" />";
														 		echo "<span style=\"color:white;\">". $val."</span><br/>";
														   }
												  }
									       
									}
									
									else {
										
										
										
									
										if(!empty($ProfileCustomValue)){
											
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
									
											
										}else{
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										}
									}
			?>