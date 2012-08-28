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
										
										
										
									  list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
											
											$data = explode("|",$option1);
											$data2 = explode("|",$option2);
											
											
								            echo "<br/>";
											echo "<br/>";
											echo "<label>".$data[0]."</label>";
											echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
											    $pos = 0;
												foreach($data as $val1){
													
													if($val1 != end($data) && $val1 != $data[0]){
													 $pos++;
													     $isSelected = "";
														if($pos==1){
															 if(end($data)=="yes"){
																	$isSelected = "selected=\"selected\"";
																	echo "<option value=\"\" ".$isSelected .">".$val1."</option>";
															 }else{
																	echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
															 }
														 }else{
															echo "<option value=\"".$val1."\">".$val1."</option>";
														 }
												
													}
												}
											  	echo "</select>\n";
												
												
											if(!empty($data2) && !empty($option2)){
												    echo "<br/>";
													echo "<br/>";
													echo "<label>".$data2[0]."</label>";
											
											 		$pos2 = 0;
													echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
													foreach($data2 as $val2){
														  
															if($val2 != end($data2) && $val2 !=  $data2[0]){
																 $pos2++;
																 if($pos2==1){
																	 if(end($data2)=="yes"){
																		$isSelected = "selected=\"selected\"";
																		echo "<option value=\"\" ".$isSelected .">".$val2."</option>";
																	 }else{
																		echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";
															 		}
																
																 }else{
																	echo "<option value=\"".$val2."\">".$val2."</option>";
																 }
															}
														}
													echo "</select>\n";
											
											}
									   
										
										
									} elseif ($ProfileCustomType == 4) {
										echo "<textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $ProfileCustomValue ."</textarea>";
									}
									 elseif ($ProfileCustomType == 5) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										          echo "<div style=\"width:300px;float:left;\">";
												  foreach($array_customOptions_values as $val){
														 echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";
												  }
												  echo "</div>";
									       
									}
									elseif ($ProfileCustomType == 6) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										   
												  foreach($array_customOptions_values as $val){
														 echo "<input type=\"radio\" value=\"". $val."\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "<span style=\"color:white;\">". $val."</span><br/>";
												  }
									       
									}
									
									else {
										
										
										
									
										if(!empty($ProfileCustomValue)){
											
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
									
											
										}else{
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										}
									}
									
		echo "				    </div>\n";
		?>