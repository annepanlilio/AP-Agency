<?php
ob_start();
error_reporting(0);
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
		array_push($custom_fields_name, str_replace(",","||",$value['ProfileCustomTitle']));
		array_push($custom_fields_id, $value['ProfileCustomID']);
		array_push($custom_fields_title, $value['ProfileCustomTitle']);
		array_push($custom_fields_type, $value['ProfileCustomType']);
	}

	if(isset($_POST)) {

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
            	if($value != "ProfileID"){
	            	$headings[$head_count] = $value;
	            	$head_count++;
	            }
            }

            $limit_template = isset($_POST["export-profile"]) && !empty($_POST["export-profile"])?" LIMIT ".str_replace("-",",",$_POST["export-profile"]):"";
            
            if(isset($_POST["export-profile"]) && $_POST["export-profile"] == "template"){
            	$limit_template = "LIMIT 1";
            }
            $objPHPExcel->getActiveSheet()->fromArray(array($headings),NULL,'A'.$rowNumber);
			/*Profile data*/
			$row_data = array();
			$row_data = $wpdb->get_results('SELECT ProfileID,ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive FROM '. table_agency_profile." ORDER BY ProfileContactDisplay ASC $limit_template", ARRAY_A);
			$profile_data_id = $wpdb->get_results("SELECT ProfileID FROM ". table_agency_profile, ARRAY_A);

			foreach ($row_data as $key => $data) 
			{
				$rowNumber++;

				//$subresult = $wpdb->get_results("SELECT * FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $profile_data_id[$key]['ProfileID'], ARRAY_A);
				$subresult = $wpdb->get_results("SELECT * FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $data['ProfileID'], ARRAY_A);
				$gender = $wpdb->get_row("SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID = '".$data['ProfileGender']."'", ARRAY_A);
                $ProfileGender = $data['ProfileGender'];

                $data['ProfileContactNameFirst'] = stripcslashes(stripcslashes($data['ProfileContactNameFirst']));
				$data['ProfileContactNameLast'] = stripcslashes(stripcslashes($data['ProfileContactNameLast']));
				$data['ProfileContactDisplay'] = stripcslashes(stripcslashes($data['ProfileContactDisplay']));

				$data['ProfileGender'] = $gender['GenderTitle'];
				$data['ProfileLocationCountry'] = rb_agency_getCountryTitle($data['ProfileLocationCountry'],true); // returns country code
				$data['ProfileLocationState'] = rb_agency_getStateTitle($data['ProfileLocationState'],true); // returns state code
				$data['ProfileType'] = str_replace(","," | ",$data['ProfileType']);
				
				$c_value_array = array();
				$temp_array = array();

				$ProfileID = $data['ProfileID'];
				
				$all_permit = false; // set to false
				if($ProfileID != 0){
					$query = $wpdb->get_results($wpdb->prepare("SELECT ProfileType FROM ".table_agency_profile." WHERE ProfileID = %d",$ProfileID),ARRAY_A);
					$fetchID = current($query);
					$ptype = $fetchID["ProfileType"];	
					if(strpos($ptype,",") > -1){
						$t = explode(",",$ptype);
						$ptype = ""; 
						foreach($t as $val){
							$ptyp[] = str_replace(" ","_",retrieve_title($val));
						}
						$ptype = implode(",",$ptyp);
					} else {
						$ptype = str_replace(" ","_",retrieve_title($ptype));
					}
					$ptype = str_replace(",","",$ptype);
				} else {
					$all_permit = true;
				}

				foreach ($subresult as $sub_value) {

								$permit_type = false;
								$PID = $sub_value['ProfileCustomID'];
								$get_types = "SELECT ProfileCustomTypes FROM ". table_agency_customfields_types ." WHERE ProfileCustomID = %d";
						
								$result = $wpdb->get_results($wpdb->prepare($get_types, $PID),ARRAY_A);
								$types = "";
								foreach ($result as $p){
										$types = $p['ProfileCustomTypes'];
								}
								if(!isset($ptype)){
									$ptype = "";
								}
								$ptype = str_replace(' ','_',$ptype);
								if($types != "" || $types != NULL){
									if(strpos($types,",") > -1){
										$types = explode(",",$types);
										foreach($types as $t){
											if(strpos($ptype,$t) > -1) {$permit_type=true; break;}  
										}			
									} else {
											if(strpos($ptype,$types) > -1) $permit_type=true;  
									}
								}
								  

			 //$data3["ProfileCustomShowGender"] == $ProfileGender 
					if(rb_agency_filterfieldGender($PID, $ProfileGender,false)  && $permit_type || $all_permit){
						$cfield = $wpdb->get_row("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = '".$sub_value["ProfileCustomID"]."'", ARRAY_A);
				    
						$ProfileCustomValue = "";

						if($cfield["ProfileCustomType"] == 10){
								$ProfileCustomValue = $sub_value['ProfileCustomDateValue'];
						}elseif($cfield["ProfileCustomType"] == 7){
								$ProfileCustomValue = rb_get_imperial_metrics($sub_value['ProfileCustomValue'],$cfield['ProfileCustomOptions']);
						}elseif($cfield["ProfileCustomType"] == 4 || $cfield["ProfileCustomType"] == 1){
								$ProfileCustomValue =  $sub_value['ProfileCustomValue'];
						}elseif(empty($sub_value['ProfileCustomDateValue'])){
							if(trim($sub_value['ProfileCustomValue']) != ""){
								$ProfileCustomValue = str_replace(',', '|', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sub_value['ProfileCustomValue']));
							} else {
								$ProfileCustomValue = "";
							}
						}

						$temp_array[$sub_value['ProfileCustomID']] = stripslashes($ProfileCustomValue); 
				    }
				}

				/*
				 * arrange array to right column headings
				 */
				foreach($custom_fields_id as $d){
					$c_value_array[] = $temp_array[$d];
				}
				
				//Height conversion from inches to feet n inches
				//foreach($custom_fields_title as $key => $title){
					/*if($title=="Height"){
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
                    }*/

                         $data = array_merge($data, $c_value_array);
                         unset($data['ProfileID']);
                      
                        $objPHPExcel->getActiveSheet()->fromArray(array($data),NULL,'A'.$rowNumber);	
	            }	
			  
			  $extension = "";
			  $type = "";
	         if($_POST["file_type"] == "csv"){
	         	 $type = "CSV";
	         	 $extension = "csv";
	         }elseif($_POST["file_type"] == "xls"){
	          	 $type = "Excel5";
	         	 $extension = "xls";
	        }
				
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,  $type);
			$objWriter->save(str_replace('.php', '.'.$extension, __FILE__));
			$profile_name = explode("-",$_POST["export-profile"]);
			$from = $profile_name[0];
			$to = $profile_name[1];
			$profile_paginate = isset($_POST["export-profile"]) && !empty($_POST["export-profile"])?"-profiles-".($from."-".(($to+$from)-1)):"";
            
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=".$_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time()).$profile_paginate .'.'.$extension); 
			header("Content-Transfer-Encoding: binary ");
			ob_clean();
			flush();
			readfile(str_replace('.php', '.'.$extension, __FILE__));
			unlink(str_replace('.php', '.'.$extension, __FILE__));
	}
	exit;
?>