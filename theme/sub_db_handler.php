<?php
/* Don't remove this line. */


define( 'BLOCK_LOAD', true );

//get absolute path - adjustment for $_SERVER['DOCUMENT_ROOT'] error as it cannot get subdomain path

//Commented by Gaurav
//$currentDIR= str_replace("/wp-content/plugins/rb-agency/theme","",getcwd());
$currentDIR= str_ireplace(DIRECTORY_SEPARATOR."wp-content".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."rb-agency".DIRECTORY_SEPARATOR."theme","",getcwd());
require_once( $currentDIR . '/wp-config.php' );
require_once( $currentDIR . '/wp-includes/wp-db.php' );
$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);


if($_POST['usage']=="addtocart"){ //for add to cart
    session_start();
	
	//check if its already in cart for this current user
	$currentUserID=rb_agency_get_current_userid();
	$query="SELECT * FROM  ".table_agency_castingcart." WHERE CastingCartProfileID='".$currentUserID."' AND CastingCartTalentID='".$_POST['pid']."' ";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	if(mysql_num_rows($results)==0){
		mysql_query("INSERT INTO ".table_agency_castingcart." (CastingCartProfileID,CastingCartTalentID) VALUES('".$currentUserID."','".$_POST['pid']."'); ") or die(mysql_error());
			echo "Profile has been added to cart casting.";
	}else{
			echo "Profile already exists in cart casting.";
	}
	
	
}else{
	if(isset($_POST['sub'])){ //for profile filtering sub select dropdwon
			$query = "SELECT *,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 AS age FROM " . table_agency_profile . " WHERE 1 AND ProfileType='1' AND ProfileIsActive='1'  ";
			if($_POST['sub']=="men"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '18' AND '99' AND ProfileGender ='1' ";}

			if($_POST['sub']=="women"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '18' AND '99' AND ProfileGender ='2' ";}
			
			if($_POST['sub']=="teen_boys"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '13' AND '17' AND ProfileGender ='1' ";}
			
			if($_POST['sub']=="teen_girls"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '13' AND '17' AND ProfileGender ='2' ";}

			if($_POST['sub']=="boys"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '1' AND '12' AND ProfileGender ='1' ";}
			
			if($_POST['sub']=="girls"){$query.=" AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth )), '%Y')+0 
			BETWEEN '1' AND '12' AND ProfileGender ='2' ";}

			$query.=" ORDER BY ProfileContactDisplay ASC ";
		$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		for($cnt=1;$data=mysql_fetch_array($results);$cnt++){
			$option.='<option value="'.'/profile/'.$data["ProfileGallery"].'/">'.$data["ProfileContactDisplay"].'</option>';
		}
		echo '<select onchange="location.href=this.value;"><option value="">Select Model</option>'.$option.'</select>';
	}else{
		echo "<select><option value=''>Select Division First</option>$option</select>";
	}
}
?>