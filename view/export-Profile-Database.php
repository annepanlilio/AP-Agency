<?php
ob_start();
// Tap into WordPress Database
@include_once('../../../../wp-config.php');
@include_once('../../../../wp-load.php');
@include_once('../../../../wp-includes/wp-db.php');
global $wpdb;

	//$query3 = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder";
	/*
	 * to include all types of views including
	 * private
	 */
	$query3 = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." ORDER BY ProfileCustomOrder";
	$custom_fields_name = array();
	$custom_fields_id = array();
	$custom_fields_title = array();
	$custom_fields_type = array();
	$custom_fields = $wpdb->get_results($query3,ARRAY_A);
	
	foreach ($custom_fields as $key => $value) {
		//array_push($custom_fields_name, 'Client'.str_replace(' ', '', $value['ProfileCustomTitle']));
		array_push($custom_fields_name, $value['ProfileCustomTitle']);
		array_push($custom_fields_id, $value['ProfileCustomID']);
		array_push($custom_fields_title, $value['ProfileCustomTitle']);
		array_push($custom_fields_type, $value['ProfileCustomType']);
	}

	if(isset($_POST)) {
		if($_POST['file_type'] == 'csv') { 
			$profile_data = $wpdb->get_results("SELECT ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive FROM ". table_agency_profile, ARRAY_A);
			$profile_data_id = $wpdb->get_results("SELECT ProfileID,ProfileContactDisplay,ProfileContactNameFirst  FROM ". table_agency_profile, ARRAY_A);
			$csv_output .= "ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive,";
			$csv_output .= implode(',', $custom_fields_name);
			$csv_output .= "\n"; 
			foreach ($profile_data as $key => $data_value) { 
				$gender = $wpdb->get_row("SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID = ".$data_value['ProfileGender'], ARRAY_A);
				$data_value['ProfileContactNameFirst'] = stripcslashes(stripcslashes($data_value['ProfileContactNameFirst']));
				$data_value['ProfileContactNameLast'] = stripcslashes(stripcslashes($data_value['ProfileContactNameLast']));
			    $data_value['ProfileContactDisplay'] = stripcslashes(stripcslashes($data_value['ProfileContactDisplay']));
				$data_value['ProfileGender'] = $gender['GenderTitle'];
				$data_value['ProfileLocationCountry'] = rb_agency_getCountryTitle($data_value['ProfileLocationCountry'],true); // returns country code
				$data_value['ProfileLocationState'] = rb_agency_getStateTitle($data_value['ProfileLocationState'],true); // returns state code
				$data_value['ProfileType'] = str_replace(","," | ",$data_value['ProfileType']);  
				
				$csv_output .= implode(',', $data_value);
				$subresult = $wpdb->get_results("SELECT ProfileCustomID, ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $profile_data_id[$key]['ProfileID']." ", ARRAY_A);
				$temp_array = array();
				$c_value_array = array(); 

				foreach ($subresult as $key => $sub_value) {
					$wpdb->get_results("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomType IN(".$data_value['ProfileType'].")", ARRAY_A);
				    $count = $wpdb->num_rows;
				    if($count > 0){
						$ProfileCustomValue = ""  ;
						if(trim($sub_value['ProfileCustomValue']) != ""){
							$ProfileCustomValue = str_replace(',', '|', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sub_value['ProfileCustomValue']));
						} else {
							$ProfileCustomValue = "";
						}
						$temp_array[$sub_value['ProfileCustomID']] = $ProfileCustomValue; 
					}
				}

				/*
				 * arrange array to right column headings
				 */
				foreach($custom_fields_id as $d){
					$c_value_array[] = $temp_array[$d];
				}
				
				//Height conversion from inches to feet n inches
				foreach($custom_fields_title as $key=>$title){
					if($title=="Height"){
						$rawValue=$temp_array[$custom_fields_id[$key]];
						$feet=intval($rawValue/12);
						$inches=intval($rawValue%12);
						if($feet==0 && $inches==0){
						 	$c_value_array[$key]='';
						}
						elseif(!is_int($rawValue)){
							 if(strpos($rawValue, "'") !== false && strpos($rawValue, "and") === false){
                         	     $c_value_array[$key] = str_replace('""',"\"",str_replace('\'"',"'",$rawValue.'"'));
	                        }else{
	                        	        $c_value_array[$key] = $rawValue;
	                        }
					    }else
							$c_value_array[$key]=$feet."ft ".$inches."in";
						}
					}
			
				
				$csv_output .= ','.implode(',', $c_value_array);
				$csv_output .="\n";

			}
			$filename = $_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time());

			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header( "Content-disposition: filename=".$filename.".csv");

			print $csv_output;
		} elseif($_POST['file_type'] == 'xls') {
			require_once('../ext/PHPExcel.php');
			require_once('../ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$rowNumber = 1;
			/*Getting headers*/
			$headings = array();
            $headings = array('ProfileContactDisplay','ProfileContactNameFirst','ProfileContactNameLast','ProfileGender','ProfileDateBirth','ProfileContactEmail','ProfileContactWebsite','ProfileContactPhoneHome','ProfileContactPhoneCell','ProfileContactPhoneWork','ProfileLocationStreet','ProfileLocationCity','ProfileLocationState','ProfileLocationZip','ProfileLocationCountry','ProfileType','ProfileIsActive');
            $head_count = count($headings);
            foreach ($custom_fields_name as $key => $value) {
            	$headings[$head_count] = $value;
            	$head_count++;
            }
            $objPHPExcel->getActiveSheet()->fromArray(array($headings),NULL,'A'.$rowNumber);
			/*Profile data*/
			$row_data = array();
			$row_data = $wpdb->get_results('SELECT ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive FROM '. table_agency_profile, ARRAY_A);
			$profile_data_id = $wpdb->get_results("SELECT ProfileID FROM ". table_agency_profile, ARRAY_A);

			foreach ($row_data as $key => $data) 
			{
				$rowNumber++;
				$subresult = $wpdb->get_results("SELECT * FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $profile_data_id[$key]['ProfileID'], ARRAY_A);

				$gender = $wpdb->get_row("SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID = ".$data['ProfileGender'], ARRAY_A);
                $data['ProfileContactNameFirst'] = stripcslashes(stripcslashes($data['ProfileContactNameFirst']));
				$data['ProfileContactNameLast'] = stripcslashes(stripcslashes($data['ProfileContactNameLast']));
				$data['ProfileContactDisplay'] = stripcslashes(stripcslashes($data['ProfileContactDisplay']));
				$data['ProfileGender'] =$gender['GenderTitle'];
				$data['ProfileLocationCountry'] = rb_agency_getCountryTitle($data['ProfileLocationCountry'],true); // returns country code
				$data['ProfileLocationState'] = rb_agency_getStateTitle($data['ProfileLocationState'],true); // returns state code
				$data['ProfileType'] = str_replace(","," | ",$data['ProfileType']);
				
				$c_value_array = array();
				$temp_array = array();

				foreach ($subresult as $sub_value) {
					$wpdb->get_results("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomType IN(".$data['ProfileType'].")", ARRAY_A);
				    $count = $wpdb->num_rows;
				    if($count > 0){
					
						if(trim($sub_value['ProfileCustomValue']) != ""){
							$ProfileCustomValue = str_replace(',', '|', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sub_value['ProfileCustomValue']));
						} else {
							$ProfileCustomValue = "";
						}

						$temp_array[$sub_value['ProfileCustomID']] = $ProfileCustomValue; 
					}
				}

				/*
				 * arrange array to right column headings
				 */
				foreach($custom_fields_id as $d){
					$c_value_array[] = $temp_array[$d];
				}
				
				//Height conversion from inches to feet n inches
				foreach($custom_fields_title as $key=>$title){
					if($title=="Height"){
						$rawValue=$temp_array[$custom_fields_id[$key]];
						$feet=intval($rawValue/12);
						$inches=intval($rawValue%12);
						if($feet==0 && $inches==0){
							$c_value_array[$key]='';
						}
						elseif(!is_int($rawValue)){
						  if(strpos($rawValue, "'") !== false && strpos($rawValue, "and") === false){
                          	     $c_value_array[$key] = str_replace('""',"\"",str_replace('\'"',"'",$rawValue.'"'));
	                     }else{
								 $c_value_array[$key] = $rawValue;
							}
					    }
						else
							$c_value_array[$key]=$feet."ft ".$inches."in";
							
						
						}
                    }
				

				$data = array_merge($data, $c_value_array);
				$objPHPExcel->getActiveSheet()->fromArray(array($data),NULL,'A'.$rowNumber);	
			}
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save(str_replace('.php', '.xls', __FILE__));
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=".$_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time()).".xls"); 
			header("Content-Transfer-Encoding: binary ");
			ob_clean();
			flush();
			readfile(str_replace('.php', '.xls', __FILE__));
			unlink(str_replace('.php', '.xls', __FILE__));
		}
	}
	exit;
?>