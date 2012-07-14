<?php
 echo "				    <div class=\"search-field single\">\n";
		 echo "				        <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label>\n";
	        
									$ProfileCustomType = $data1['ProfileCustomType'];
									$ProfileCustomValue = $data1['ProfileCustomValue'];
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
									 
									} elseif ($ProfileCustomType == 3) {
										
										$ProfileCustomOptions_Array = explode( ":",$data1['ProfileCustomOptions']);
									
										echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">\n";
										if(end($ProfileCustomOptions_Array)=="no"){
											echo "<option value=\"\"> </option>";
										}else{
											echo "<option value=\"\">".$ProfileCustomOptions_Array[2]."</option>";	
										}
										foreach ($ProfileCustomOptions_Array as &$value) {
											    if($value != end($ProfileCustomOptions_Array) && $ProfileCustomOptions_Array[2] != $value && $ProfileCustomOptions_Array[1] != $value && $ProfileCustomOptions_Array[0] != $value && !empty($value)){
														if($_SESSION["ProfileCustomID". $data1['ProfileCustomID']] ==  "ProfileCustomID". $data1['ProfileCustomID']){
															echo "	<option value=\"". $value ."\" ". selected($ProfileCustomValue, $value) ." selected='selected'> ". $value ." </option>\n";
														}else{
																echo "	<option value=\"". $value ."\" ". selected($ProfileCustomValue, $value) ."> ". $value ." </option>\n";
														
														}
												}
											
										
										} 
										echo "</select>\n";
									} else {
										
									
										if(!empty($ProfileCustomValue)){
											
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
									
											
										}else{
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										}
									}
									
		echo "				    </div>\n";
		?>