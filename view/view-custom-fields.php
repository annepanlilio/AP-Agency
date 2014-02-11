<?php
 		
$ProfileCustomID = $data1['ProfileCustomID'];
$ProfileCustomTitle = $data1['ProfileCustomTitle'];
$ProfileCustomType = $data1['ProfileCustomType'];
$ProfileCustomValue = $data1['ProfileCustomValue'];
			
if($ProfileCustomType!=4)	{
    
   /*
	* Opening of Field or Div
 	*/
  	if($ProfileCustomType == 7 OR $ProfileCustomType == 5 OR $ProfileCustomType == 6){
        echo "<fieldset class=\"search-field multi\">";
	} else {
 	    echo "<div class=\"search-field single\">";	
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
			 
			} elseif ($ProfileCustomType == 3) { // SELECT
				
				list($option1,$option2) = explode(":",$data1['ProfileCustomOptions']);	
					
				$data = explode("|",$option1);
				$data2 = explode("|",$option2);				
			 
				echo "<label>".$data[0]."</label>";
				
				echo "<div><select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">";
				echo "<option value=\"\">--</option>";
				  
					foreach($data as $val1){
						
						if($val1 != end($data) && $val1 != $data[0]){
						
							 $isSelected = "";
							if($_REQUEST["ProfileCustomID". $data1['ProfileCustomID']]==$val1){
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
			}
			elseif ($ProfileCustomType == 6) {
		
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
			}
			
			elseif ($ProfileCustomType == 7) {
		
				// RYAN EDITING				
				list($min_val,$max_val) =  @explode(",",$_SESSION["ProfileCustomID".$data1['ProfileCustomID']]);

					if($data1['ProfileCustomTitle']=="Height" && $data1['ProfileCustomOptions']==3){

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
				// END - RYAN EDITING
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
?>