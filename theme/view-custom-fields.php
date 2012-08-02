<?php
 		 echo "	    <div class=\"search-field single\">\n";
		
	        
									$ProfileCustomID = $data1['ProfileCustomID'];
									$ProfileCustomTitle = $data1['ProfileCustomTitle'];
									$ProfileCustomType = $data1['ProfileCustomType'];
									$ProfileCustomValue = $data1['ProfileCustomValue'];
			//Hardcoded Fields -> Height
			if(strtolower($ProfileCustomTitle) == "height"){
				
				 // Metric or Imperial?
				  if ($rb_agency_option_unittype == 1) {
						
					      echo "	 <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("In Inches", rb_agency_TEXTDOMAIN) .")</em></label>\n";
						echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
								if (empty($ProfileStatHeight)) {
						echo " 				<option value=\"\" selected>--</option>\n";
										}
										
										$i=36;
										$heightraw = 0;
										$heightfeet = 0;
										$heightinch = 0;
										while($i<=90)  { 
										  $heightraw = $i;
										  $heightfeet = floor($heightraw/12);
										  $heightinch = $heightraw - floor($heightfeet*12);
						echo " 				<option value=\"". $i ."\" ". selected($_SESSION["ProfileCustomID".$ProfileCustomID], $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
										  $i++;
										}
						echo " 			</select>\n";

				} else {
						echo "	 <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("cm", rb_agency_TEXTDOMAIN) .")</em></label>\n";
						echo "	 <input type=\"text\" id=\"ProfileStatHeight\" name=\"ProfileStatHeight\" value=\"". $_SESSION["ProfileCustomID".$ProfileCustomID] ."\" />\n";
				}
				
			}
			//Hardcoded Fields -> Weight
			elseif(strtolower($ProfileCustomTitle) == "weight"){
						  echo "	 <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Weight", rb_agency_TEXTDOMAIN); 
							 if ($rb_agency_option_unittype == 1) { echo "<em>(". __("In Pounds", rb_agency_TEXTDOMAIN) .")</em>"; } else { echo "<em>(". __("In Kilo", rb_agency_TEXTDOMAIN) .")</em></th>\n"; }	
						  echo "</label>\n";
				
									echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID".$ProfileCustomID]."\" /><br />\n";
						 
			}
			// Customfields
			else{ 
			 
			 echo "				        <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label>\n";
									if ($ProfileCustomType == 1) { //TEXT
											
											
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
										   
										
										
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
											
											
								         
											echo "<label>".$data[0]."</label>";
											
											echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
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
											  	echo "</select>\n";
												
												
											if(!empty($data2) && !empty($option2)){
												    echo "<br/>";
													echo "<br/>";
													echo "<label>".$data2[0]."</label>";
											
											 		$pos2 = 0;
													echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\">\n";
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
													echo "</select>\n";
											
											}
									   
										
										
									} elseif ($ProfileCustomType == 4) {
										echo "<textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] ."</textarea>";
									}
									 elseif ($ProfileCustomType == 5) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										          echo "<div style=\"width:300px;float:left;\">";
												  foreach($array_customOptions_values as $val){
													if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']])){ 
													   
													  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
													  if(in_array($val,$dataArr,true)){
														 echo "<label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";
													  }else{
														 echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";	
													}
												  	}else{
														 echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";	
													}
												  }
												  echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/>";
												  echo "</div>";
									       
									}
									elseif ($ProfileCustomType == 6) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										   
												  foreach($array_customOptions_values as $val){
													if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] !=""){ 
													   
													  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
														
													  if(in_array($val,$dataArr) && $val !=""){
														 echo "<label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";
													  }else{
														 echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";	
													}
												  	}else{
														 echo "<label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label>";	
													}
												  }
												    echo "<input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/>";
									       
									}
									
									else {
										
										
												echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /><br />\n";
									
									}
			}
		echo "				    </div>\n";
		?>