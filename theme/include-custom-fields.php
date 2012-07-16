<?php
			$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder,ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 ORDER BY ProfileCustomOrder ASC";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								$pos = 0;
		while ($data1 = mysql_fetch_array($results1)) { 
		        
				   if($data1["ProfileCustomShowSearch"] == 1 || $data1["ProfileCustomShowProfile"] == 1  ){ // Show on Search Page
					
					 if(($data1["ProfileCustomShowLogged"] ==1 && is_user_logged_in()))
					 {
						 // Show custom fields for admins only.
						if($data1["ProfileCustomShowAdmin"] == 1 && current_user_can("level_10") && is_user_logged_in()){ 
							include("view-custom-felds.php");
						//	echo "1";
						}
						// Show custom fields for logged in users.
						if($data1["ProfileCustomShowAdmin"] == 0 && !current_user_can("level_10")){
							 include("view-custom-felds.php");
						//  echo "2";
						}
						
					 }
					 
					 // Show custom fields to all user level.
					 if(($data1["ProfileCustomShowLogged"] == 0 && !is_user_logged_in())){
						
						 include("view-custom-felds.php");
						// echo "3";
					}
					if((!current_user_can("level_10") && $data1["ProfileCustomShowAdmin"] ==0  && $data1["ProfileCustomShowLogged"] == 0)){
							 include("view-custom-felds.php");
						// echo "4";
						
					}
					
				 }
			
				
      }
?>