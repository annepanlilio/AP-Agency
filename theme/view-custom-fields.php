<?php
 		
									$ProfileCustomID = $data1['ProfileCustomID'];
									$ProfileCustomTitle = $data1['ProfileCustomTitle'];
									$ProfileCustomType = $data1['ProfileCustomType'];
									$ProfileCustomValue = $data1['ProfileCustomValue'];
			
  if($ProfileCustomType!=4)	{		
			
		      if($ProfileCustomType==7){
	             echo "	    <div class=\"search-field double\">\n";
			}else{
			 echo "	    <div class=\"search-field single\">\n";	
			}
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
			
/*			//Hardcoded Fields -> Height
			if(strtolower($ProfileCustomTitle) == "height"){
				
				 // Metric or Imperial?
				  if ($rb_agency_option_unittype == 1) {
						
					      echo "<div> <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("In Inches", rb_agency_TEXTDOMAIN) .")</em></label></div>\n";
						echo "<div> <select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">\n";
								if (empty($ProfileStatHeight)) {
						echo " 				<option value=\"\">--</option>\n";
										}
										
										$i=36;
										$heightraw = 0;
										$heightfeet = 0;
										$heightinch = 0;
										while($i<=90)  { 
										  $heightraw = $i;
										  $heightfeet = floor($heightraw/12);
										  $heightinch = $heightraw - floor($heightfeet*12);
						echo " 				<option value=\"". $i ."\" ". selected($_SESSION["ProfileCustomID".$data1['ProfileCustomID']], $i,false) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
										  $i++;
										}
						echo " 			</select></div>\n";

				} else {
						echo "	<div> <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("cm", rb_agency_TEXTDOMAIN) .")</em></label></div>\n";
						echo "	<div> <input type=\"text\" id=\"ProfileStatHeight\" name=\"ProfileStatHeight\" value=\"". $_SESSION["ProfileCustomID".$ProfileCustomID] ."\" /></div>\n";
				}
				
			}
			//Hardcoded Fields -> Weight
			elseif(strtolower($ProfileCustomTitle) == "weight"){
						  echo "	<div> <label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". __("Weight", rb_agency_TEXTDOMAIN); 
							 if ($rb_agency_option_unittype == 1) { echo "<em>(". __("In Pounds", rb_agency_TEXTDOMAIN) .")</em>"; } else { echo "<em>(". __("In Kilo", rb_agency_TEXTDOMAIN) .")</em>\n"; }	
						  echo "</label></div>\n";
				
									echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID".$ProfileCustomID]."\" /></div>\n";
						 
			}
			// Customfields
			else{ */
			                                     if($ProfileCustomType==7){
			 echo "				       <div class=\"label\">". $data1['ProfileCustomTitle'].$measurements_label."</div> \n";
									 }else{
			 echo "				       <div><label for=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $data1['ProfileCustomTitle']."</label></div> \n";							 
									 }
									if ($ProfileCustomType == 1) { //TEXT
											
											
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
									
										   
										
										
									} elseif ($ProfileCustomType == 2) { // Min Max
									
									   
										$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data1['ProfileCustomOptions'],"}"),"{"));
										list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
									   
									 
										if(!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)){
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label></div>\n";
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" /></div>\n";
												echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label></div>\n";
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /></div>\n";
									
											
										}else{
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
											      echo "<div><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
												echo "<div><input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data1['ProfileCustomID']]."\" /></div>\n";
									
										   
										}
									 
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
										echo "<div><textarea name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">". $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] ."</textarea></div>";
									}
									 elseif ($ProfileCustomType == 5) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										          echo "<div style=\"width:300px;float:left;\">";
												  foreach($array_customOptions_values as $val){
													if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']])){ 
													   
													  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
													  if(in_array($val,$dataArr,true)){
														 echo "<div><label><input type=\"checkbox\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";
													  }else{
														 echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";	
													}
												  	}else{
														 echo "<div><label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";	
													}
												  }
												  echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";
												  echo "</div>";
									       
									}
									elseif ($ProfileCustomType == 6) {
										   $array_customOptions_values = explode("|",$data1['ProfileCustomOptions']);
										   
												  foreach($array_customOptions_values as $val){
													if(isset($_SESSION["ProfileCustomID". $data1['ProfileCustomID']]) && $_SESSION["ProfileCustomID". $data1['ProfileCustomID']] !=""){ 
													   
													  	$dataArr = explode(",",implode(",",explode("','",$_SESSION["ProfileCustomID". $data1['ProfileCustomID']])));
														
													  if(in_array($val,$dataArr) && $val !=""){
														 echo "<div><label><input type=\"radio\" checked=\"checked\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";
													  }else{
														 echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";	
													}
												  	}else{
														 echo "<div><label><input type=\"radio\" value=\"". $val."\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\" />";
														 echo "". $val."</label></div>";	
													}
												  }
												    echo "<div><input type=\"hidden\" value=\"\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."[]\"/></div>";
									       
									}
									
									elseif ($ProfileCustomType == 7){
										   
										 
										      list($min_val,$max_val) =  explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);
											
										     echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']."_min\">Min:</label></div><div> <input value=\"".(!is_array($min_val) && $min_val != "Array" ? $min_val : "")."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
										     echo "<div><label for=\"ProfileCustomID".$data1['ProfileCustomID']."_max\">Max:</label></div><div> <input value=\"".$max_val."\" class=\"stubby\" type=\"text\" name=\"ProfileCustomID".$data1['ProfileCustomID']."[]\" /></div>";
											
									}
			
		echo "				    </div>\n";
			}
		?>