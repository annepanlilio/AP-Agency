<?php
// *************************************************************************************************** //
// Get Category

session_start();
header("Cache-control: private"); //IE 6 Fix

// Get Profile
$ProfileType = get_query_var('target'); 

if($ProfileType=="print"){  // print by custom
	//$ProfileType=$_GET['cname'];
    $division=$_GET['gd'];
	$ageStart=$_GET['ast'];
	$ageStop=$_GET['asp'];
	$type=$_GET['t'];
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="pdf"){  // print by custom
    //$ProfileType=$_GET['cname'];
    $division=$_GET['gd'];
	$ageStart=$_GET['ast'];
	$ageStop=$_GET['asp'];
	$type=$_GET['t'];
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}

if($ProfileType=="women-print"){  //request to print women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}


if($ProfileType=="women-pdf"){  //request to PDF women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="men-print"){  //request to print men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="men-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-boys-print"){  //request to print men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="teen-boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-girls-print"){  //request to print men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="teen-girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="boys-print"){  //request to print men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	 include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	 include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="girls-print"){  //request to print men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include(rb_agency_BASEREL ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include(rb_agency_BASEREL ."theme/pdf-division.php"); 
	 die();
}



if (isset($ProfileType) && !empty($ProfileType)){
	$DataTypeID = 0;
	$DataTypeTitle = "";
	$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeTag = '". $ProfileType ."'";

	$results = mysql_query($query);
	while ($data = mysql_fetch_array($results)) {
		$DataTypeID = $data['DataTypeID'];
		$DataTypeTitle = $data['DataTypeTitle'];
		$filter .= " AND profile.ProfileType=". $DataTypeID ."";
	}
}


get_header(); 

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	
		echo "<div id=\"profile-category\">\n";

		echo "	<h1 class=\"profile-category-title entry-title\">\n";
		echo "	". __("Directory", rb_agency_TEXTDOMAIN) ." ";
				if ($DataTypeTitle) { echo " > ". $DataTypeTitle; }
		echo "	</h1>\n";

		echo "	<div class=\"cb\"></div>\n";

		/*
		 * Loopp to category list
		 */ 
		$queryList = "SELECT dt.DataTypeID, dt.DataTypeTitle, dt.DataTypeTag, 
				      COUNT(profile.ProfileID) AS CategoryCount 
					  FROM ".table_agency_data_type." dt,".table_agency_profile." profile 
					  WHERE  FIND_IN_SET(dt.DataTypeID, profile.ProfileType) and profile.ProfileIsActive = 1 
					  GROUP BY dt.DataTypeID ORDER BY dt.DataTypeTitle ASC";
		$resultsList = mysql_query($queryList);
		$countList = mysql_num_rows($resultsList);			
		while ($dataList = mysql_fetch_array($resultsList)) {
			echo "<div class=\"profile-category\">\n";
			var_dump($dataList["DataTypeID"]);
			if ($DataTypeID == $dataList["DataTypeID"]) {
				echo "  <div class=\"name\"><strong>". $dataList["DataTypeTitle"] ."</strong> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			} else {
				echo "  <div class=\"name\"><a href=\"".get_bloginfo('wpurl')."/profile-category/". $dataList["DataTypeTag"] ."/\">". $dataList["DataTypeTitle"] ."</a> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			}
			echo "</div>\n";
		}
		if ($countList < 1) {
			echo __("No Categories Found", rb_agency_TEXTDOMAIN);
		}

		/*
		 * Get Profile Results
		 */ 
		echo "	<div id=\"profile-category-results\">\n";
		$atts = array("profiletype" => $DataTypeID);
		$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($atts);
		echo $search_results = RBAgency_Profile::search_results($search_sql_query, 0);
		echo "			</div><!-- #profile-category-results -->\n";

		echo "</div><!-- #profile-category -->\n";
		echo "<div class=\"cb\"></div>\n";
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #primary -->\n";
       
get_sidebar();
get_footer(); 
?>