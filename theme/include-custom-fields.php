<?php
			$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder ASC";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								$pos = 0;
			
			$query2 = "SELECT ProfileGender,ProfileUserLinked  FROM ".table_agency_profile." WHERE ProfileUserLinked = '".rb_agency_get_current_userid()."' ";
								$results2 = mysql_query($query2);
							      $dataList2 = mysql_fetch_assoc($results2); 
								$count2 = mysql_num_rows($results2);
								
			$query3 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderTitle='".$dataList2["ProfileGender"]."' ORDER BY GenderID";
								$results3 = mysql_query($query3);
							      $dataList3 = mysql_fetch_assoc($results3); 
							
		while ($data1 = mysql_fetch_array($results1)) { 
		       
				   if($data1["ProfileCustomShowSearch"] == 1 || $data1["ProfileCustomShowProfile"] == 1  ){ // Show on Search Page or Profile Page
					
					 if(is_user_logged_in()) // For logged in users 
					 {
						 if($isSearchPage == 1 && $data1["ProfileCustomShowSearch"] == 1 && $data1["ProfileCustomShowLogged"] == 1){ // In Search  page
							#DEBUG! 
							#echo "is Search Page";
							if($data1["ProfileCustomShowGender"] == $dataList3["GenderID"]){ // Depends on Current LoggedIn User's Gender
								 // Show custom fields for admins only.
								 #DEBUG
								 #echo "ShowGender";
								if($data1["ProfileCustomShowAdmin"] == 1 && current_user_can("level_10") ){ 
									include("view-custom-fields.php");
									#DEBUG!
									#echo "-1";
								}
								// Show custom fields for logged in users - below admin level.
								else{
									 include("view-custom-fields.php");
									#DEBUG!
									#echo "-2";
								}
							}else{
								 // Show custom fields for admins only.
								if($data1["ProfileCustomShowAdmin"] == 1 && current_user_can("level_10") ){ 
									include("view-custom-fields.php");
									#DEBUG!
									#echo "-3";
								}
								// Show custom fields for logged in users - below admin level.
								elseif(is_user_logged_in() ){
									 include("view-custom-fields.php");
									 #DEBUG!
									 #echo "-4";
								}	
								
							}
						}
						
					 }
					else{  // For non-loggedin users
						 if($isSearchPage == 1 && $data1["ProfileCustomShowSearch"] == 1 && $data1["ProfileCustomShowLogged"] == 0){ // In Search  page
								 // Show custom fields to public
								 if($data1["ProfileCustomShowLogged"] == 0 && !is_user_logged_in()){
									include("view-custom-fields.php");
									#DEBUG!
									#echo "-7";
								}
								
						}elseif($isSearchpage == 0 && $data1["ProfileCustomShowProfile"] == 1){ //Profile Page
								  // Show custom fields to public
								 if(!is_user_logged_in()){
									include("view-custom-fields.php");
									#DEBUG!
									#echo "-8";
								}
								
						 
						}
					}
				 }
			
				
      }
?>