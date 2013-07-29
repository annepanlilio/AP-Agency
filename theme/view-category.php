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
	  include("printable-division.php"); 
	 die();
}

if($ProfileType=="pdf"){  // print by custom
    //$ProfileType=$_GET['cname'];
    $division=$_GET['gd'];
	$ageStart=$_GET['ast'];
	$ageStop=$_GET['asp'];
	$type=$_GET['t'];
	include("pdf-division.php"); 
	 die();
}

if($ProfileType=="women-print"){  //request to print women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	include("printable-division.php"); 
	 die();
}


if($ProfileType=="women-pdf"){  //request to PDF women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	include("pdf-division.php"); 
	 die();
}


if($ProfileType=="men-print"){  //request to print men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	include("printable-division.php"); 
	 die();
}

if($ProfileType=="men-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	include("pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-boys-print"){  //request to print men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	include("printable-division.php"); 
	 die();
}

if($ProfileType=="teen-boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	include("pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-girls-print"){  //request to print men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	include("printable-division.php"); 
	 die();
}

if($ProfileType=="teen-girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	include("pdf-division.php"); 
	 die();
}


if($ProfileType=="boys-print"){  //request to print men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include("printable-division.php"); 
	 die();
}

if($ProfileType=="boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include("pdf-division.php"); 
	 die();
}


if($ProfileType=="girls-print"){  //request to print men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include("printable-division.php"); 
	 die();
}

if($ProfileType=="girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include("pdf-division.php"); 
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

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	
		echo "<div id=\"profile-category\">\n";

		echo "	<h1 class=\"profile-category-title entry-title\">\n";
		echo "	". __("Directory", rb_agency_TEXTDOMAIN) ." ";
				if ($DataTypeTitle) { echo " > ". $DataTypeTitle; }
		echo "	</h1>\n";

		echo "	<div class=\"cb\"></div>\n";

			if (function_exists('rb_agency_categorylist')) { 
				$atts = array('currentcategory' => $DataTypeID);
				rb_agency_categorylist($atts); }

		echo "			<div id=\"profile-category-results\">\n";
	
						if (function_exists('rb_agency_profilelist')) { 
						  $atts = array("type" => $DataTypeID);
						  rb_agency_profilelist($atts); 
						}
									
		echo "			</div><!-- #profile-category-results -->\n";
		echo "</div><!-- #profile-category -->\n";
		echo "<div class=\"cb\"></div>\n";
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #primary -->\n";
       
get_sidebar();
get_footer(); 
?>