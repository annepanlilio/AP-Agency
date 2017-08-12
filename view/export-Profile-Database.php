<?php
ob_start();
error_reporting(E_ALL);
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
	$query3 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType FROM ". table_agency_customfields ." ORDER BY ProfileCustomOrder";
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
            $field_names = $wpdb->get_results("SHOW COLUMNS FROM  ".$wpdb->prefix."agency_profile");
            
            $head_count = $field_names->num_rows;
            foreach($field_names as $col){
                if($col->Field!='CustomOrder'){
                    $headings[] = $col->Field;
                }
            }

            $headings = array_merge(array_values($headings),array_values($custom_fields_name));
           if(isset($_POST["export-profile"]) && $_POST["export-profile"] == "template"){
        		$limit_template = "LIMIT 1";
            }elseif(isset($_POST["export-profile"]) && $_POST["export-profile"] != "template" && !empty($_POST["export-profile"]) ){
            	if(strpos($_POST["export-profile"], '-' )===false){
            		$limit_offset = $_POST["export-profile"];
        			$limit_template = " LIMIT ".$limit_offset;
            	}else{
					$limit_values = explode("-",$_POST["export-profile"]);
            		$limit_offset = $limit_values[0];
        			$limit_template = " LIMIT ".$limit_offset.", 100";
            	}
            	// $limit_offset = ( intval($limit_values[0]) < 100) ? $limit_values[0] : $limit_values[0];
            }
            $objPHPExcel->getActiveSheet()->fromArray(array($headings),NULL,'A'.$rowNumber);
			/*Profile data*/
			$row_data = array();
			$row_data = $wpdb->get_results('SELECT '.table_agency_profile.'.*,'.table_agency_data_gender.'.GenderTitle FROM '. table_agency_profile." LEFT JOIN ".table_agency_data_gender." ON ".table_agency_profile.".ProfileGender=".table_agency_data_gender.".GenderID ORDER BY ProfileContactDisplay ASC $limit_template", ARRAY_A);
			//$profile_data_id = $wpdb->get_results("SELECT ProfileID FROM ". table_agency_profile, ARRAY_A);
			foreach ($row_data as $key => $data) 
			{
				$rowNumber++;
				
				$customfield_mux = $wpdb->get_results("SELECT mux.*,cfields.ProfileCustomTitle,cfields.ProfileCustomType FROM ". table_agency_customfield_mux ." AS mux LEFT JOIN ".table_agency_customfields." AS cfields ON mux.ProfileCustomID=cfields.ProfileCustomID WHERE mux.ProfileID = ". $data['ProfileID'], ARRAY_A);
				
                
                $data['ProfileContactNameFirst'] = stripcslashes(stripcslashes($data['ProfileContactNameFirst']));
				$data['ProfileContactNameLast'] = stripcslashes(stripcslashes($data['ProfileContactNameLast']));
				$data['ProfileContactDisplay'] = stripcslashes(stripcslashes($data['ProfileContactDisplay']));
				$data['ProfileGender'] = $data['GenderTitle'];
				$data['ProfileLocationCountry'] = rb_agency_getCountryTitle($data['ProfileLocationCountry'],true); // returns country code
				$data['ProfileLocationState'] = rb_agency_getStateTitle($data['ProfileLocationState'],true); // returns state code
				$data['ProfileType'] = str_replace(","," | ",$data['ProfileType']);
                $ProfileGender = $data['ProfileGender'];
				$c_value_array = array();
				$temp_array = array();
				$ProfileID = $data['ProfileID'];
				$all_permit = false; // set to false
				if($ProfileID != 0){
//					
					$ptype = $data["ProfileType"];
                    
					if(strpos($ptype,"|") > -1){
						$t = explode("|",$ptype);
						$ptype = ""; 
						foreach($t as $val){
							$ptyp[] = str_replace(" ","_",retrieve_title($val));
						}
						$ptype = implode(",",$ptyp);
					} else {
						$ptype = str_replace(" ","_",retrieve_title($ptype));
					}
					$ptype = str_replace("|"," ",$ptype);
				} else {
					$all_permit = true;
				}
                
				foreach ($customfield_mux as $sub_value) {
								$permit_type = false;
								$PID = $sub_value['ProfileCustomID'];
								
								$types = "";
								
                                $types = $sub_value['ProfileCustomType'];
								if(!isset($ptype)){
									$ptype = "";
								}
								$ptype = str_replace(' ','_',$ptype);
                                
								if($types != "" || $types != NULL){
								    if(strpos($ptype,$types) > -1) {$permit_type=true;}
								}
					if(rb_agency_filterfieldGender($PID, $ProfileGender,false)  && $permit_type || $all_permit){
				
                    
                        if($sub_value['ProfileCustomTitle']=="Height"){
    							$rawValue = $sub_value['ProfileCustomValue'];
    							$feet=intval($rawValue/12);
    							$inches=intval($rawValue%12);
                                $sub_value['ProfileCustomValue'] = $feet."ft ".$inches."in";							
    					}
                        if(array_key_exists($sub_value['ProfileCustomTitle'],$data)){
                            $data[$sub_value['ProfileCustomTitle']] += "|".$sub_value['ProfileCustomValue'];
                        }else{
                            $data[$sub_value['ProfileCustomTitle']] = $sub_value['ProfileCustomValue'];
                        }
                    
    	         }
                    
				}
                
                foreach($headings as $column_name){
                
                    $temp_array[$key][] = $data[$column_name];
                        
                }
                
                $objPHPExcel->getActiveSheet()->fromArray($temp_array,NULL,'A'.$rowNumber);
			  }
				
			   if($_POST["file_type"] == "csv"){
	                $type = "CSV";
    			   	$extension = ".csv";
                    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
			   } elseif($_POST["file_type"] == "xls"){
					$type = "Excel5";
			   	    $extension = ".xlsx";
                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			  }
			
			$objWriter->save(str_replace('.php', $extension, __FILE__));
			$profile_name = explode("-",$_POST["export-profile"]);
			$from = $profile_name[0]+1;
			$to = ($profile_name[1] == 100) ? 100 : ($profile_name[1] < 100 ? $profile_name[1] : $profile_name[1] - 100);
			$fname = is_numeric($profile_name[1]) ? ($from."-".$profile_name[1]) : $_POST["export-profile"];
			$profile_paginate = isset($_POST["export-profile"]) && !empty($_POST["export-profile"]) ? "-profiles-".$fname : "-profiles-".$fname;
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=".$_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time()).$profile_paginate . $extension); 
			header("Content-Transfer-Encoding: binary ");
			ob_clean();
			flush();
			readfile(str_replace('.php', $extension, __FILE__));
			unlink(str_replace('.php', $extension, __FILE__));
	}
	exit();
?>