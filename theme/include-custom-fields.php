 <?php
$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomOrder, ProfileCustomView, ProfileCustomShowGender, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder ASC";
					$results1 = mysql_query($query1);
					$count1 = mysql_num_rows($results1);
					$pos = 0;

$query2 = "SELECT ProfileGender,ProfileUserLinked  FROM ".table_agency_profile." WHERE ProfileUserLinked = '".rb_agency_get_current_userid()."' ";
					$results2 = mysql_query($query2);
				      $dataList2 = mysql_fetch_assoc($results2); 
					$count2 = mysql_num_rows($results2);
					
while ($data1 = mysql_fetch_array($results1)) { 
       
	if($data1["ProfileCustomShowSearch"] == 1 || $data1["ProfileCustomShowProfile"] == 1  ){ // Show on Search Page or Profile Page

		if(is_user_logged_in()) // For logged in users 
		{
			if($isSearchPage == 1 && $data1["ProfileCustomShowSearch"] == 1 && $data1["ProfileCustomShowLogged"] == 1){ // In Search  page
				#DEBUG! 
				#echo "is Search Page";
				if($data1["ProfileCustomShowGender"] == $dataList2["ProfileGender"]){ // Depends on Current LoggedIn User's Gender
					// Show custom fields for admins only.
					#DEBUG
					#echo "ShowGender";
					if($data1["ProfileCustomShowAdmin"] == 1 && current_user_can("level_10") ){ 
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-1";
					}
					// Show custom fields for logged in users - below admin level.
					else {
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-2";
					}
				} else { // not in search page
					// Show custom fields for admins only.
					if($data1["ProfileCustomShowAdmin"] == 1 && is_user_logged_in()  && current_user_can("level_10") ){ 
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-3";
					}
					// Show custom fields for logged in users - below admin level.
					elseif(is_user_logged_in() ){
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-4";
					}						
				}
			} // if user is logged
			if($isSearchPage == 1 && $data1["ProfileCustomShowSearch"] == 1 && $data1["ProfileCustomShowLogged"] ==0){ // In Search  page
				#DEBUG! 
				#echo "is Search Page";
				if($data1["ProfileCustomShowGender"] == $dataList2["ProfileGender"]){ // Depends on Current LoggedIn User's Gender
				 	// Show custom fields for admins only.
					#DEBUG
					#echo "ShowGender";
					if($data1["ProfileCustomShowAdmin"] == 1 && current_user_can("level_10") ){ 
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-1";
					}
					// Show custom fields for logged in users - below admin level.
					else{
					 	include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-2";
					}
				} else { // not in search page
					// Show custom fields for admins only.
					if($data1["ProfileCustomShowAdmin"] == 1 && is_user_logged_in()  && current_user_can("level_10") ){ 
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-3";
					}
					// Show custom fields for logged in users - below admin level.
					elseif(is_user_logged_in() ){
						include("../view/view-custom-fields.php");
						#DEBUG!
						#echo "-4";
					}						
				}
			}

		}   // End - Show on Search Page or Profile Page
		else {  // For non-loggedin users
			if($isSearchPage == 1 && $data1["ProfileCustomShowSearch"] == 1 && $data1["ProfileCustomShowLogged"] == 0){ // In Search  page
			 	// Show custom fields to public
			 	if($data1["ProfileCustomShowLogged"] == 0 && !is_user_logged_in()){
				include("../view/view-custom-fields.php");
				#DEBUG!
				//echo "-7";
			}

			} elseif ($isSearchpage == 0 && $data1["ProfileCustomShowProfile"] == 1  && $data1["ProfileCustomShowLogged"] == 0){ //Profile Page
			  	// Show custom fields to public
			 	if(!is_user_logged_in()){
					include("../view/view-custom-fields.php");
					#DEBUG!
					//echo "-8";
				}
			}
		}
	}
	
		
}

mysql_free_result($results1);
mysql_free_result($results2);

?>