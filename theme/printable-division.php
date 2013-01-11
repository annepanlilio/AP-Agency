<?php 
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_agencylogo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php bloginfo('name'); ?> | Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	<link rel="stylesheet" type="text/css" media="screen, print" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" />
	<script language="Javascript1.2">
      <!--
      function printpage() {
       window.print();
      }
      //-->
    </script>
<?php //where we decide what print format will it be.
	  $widthAndHeight='style="width:450px; height:650px;"';
	  //$wrapperWidthHeight="width:887px;"; // $wrapperWidth="1774px";
	  $model_info_width="width:410px;";
	  $ul_css="width:70%;";
	  $toLandScape='@page{size: landscape;margin: 2cm;}';
?>
<style>
<?php echo $toLandScape;?>
body{color:#000;}
h1{color: #000; margin-bottom:15px; margin-top:15px;}
h3{font-size:12px;}
ul li span{ float:right; text-align:left; width:100px;}
ul {<?php echo $ul_css;?>}
ul li{ list-style:none; padding-bottom:5px; padding-top:5px;}
#print_logo{margin-bottom:25px; width:100%; float:left;}
#model_info{border:0px solid #000; float:left; <?php echo $model_info_width;?>}
#print_wrapper img.allimages_thumbs{margin-right:5px;}
#profile-results #profile-list .profile-list-layout0 { width: 150px; float: left; margin:  0 10px 10px 10px; position: relative; z-index: 8;  overflow: hidden; height:270px; }
	#profile-results #profile-list .profile-list-layout0 .style { position: absolute; }
	#profile-results #profile-list .profile-list-layout0 .image { width: 148px; height: 228px; overflow: hidden; background: #fff; border: 1px solid #ccc; }
	#profile-results #profile-list .profile-list-layout0 .image img { max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken { text-align: center; position: relative; background: #dadada !important; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a {  margin-top: 90px;  }
    #profile-results #profile-list .profile-list-layout0 .title { top: 230px; left: 0; width: 150px; position: absolute; text-align: center; }
    #profile-results #profile-list .profile-list-layout0 .title .name { width: 150px; height: 30px; line-height: 30px; text-transform:uppercase; }
    #profile-results #profile-list .profile-list-layout0 .title .name a { color:#000; text-transform:uppercase; font-weight:normal;  }
    #profile-results #profile-list .profile-list-layout0 .title .favorite { margin: 9px 0 0 0; font-size: 13px; background: #ccc; padding: 5px 0; }
    #profile-results #profile-list .profile-list-layout0 .details { font-size: 13px; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite a { color: #000; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box { padding: 7px; background: #fff; border: 1px solid #aaa; position: absolute; bottom: 3px; left: 32px; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box:hover { border: 1px solid #666; }
	#profile-results #profile-list .profile-list-layout0 .image img { max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken { margin: 10px 10px 0 10px; text-align: center; width: 180px; height: 220px; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a { margin-top: 90px;  float: left; margin-left: 55px; }	
	/* Profile Standard Layout */
	#profile-results #profile-list .profile-list-layout1 { border: 1px solid #bbacc7; width: 200px; float: left; margin: 0 20px 20px 0; position: relative; z-index: 8;  height: 235px; overflow: hidden; padding: 3px; background:#c8bdd2; }
	#profile-results #profile-list .profile-list-layout1 .image { width: 198px; height: 200px; overflow: hidden; margin: 0px auto; }
	#profile-results #profile-list .profile-list-layout1 .image img { max-width: 185px; }
	#profile-results #profile-list .profile-list-layout1 .image-broken {  }
	#profile-results #profile-list .profile-list-layout1 .image-broken a { display: none; }
	#profile-results #profile-list .profile-list-layout1 .title { }
	#profile-results #profile-list .profile-list-layout1 .title .name { font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .title .name a { color: #000; text-decoration: none; width: 190px; position: absolute; left: -3px; top: 213px; height: 30px; z-index: 10; text-align: center; font-size: 14px; }
	#profile-results #profile-list .profile-list-layout1 .style { background-color:#4D2375; width: 200px; position: absolute; top: 208px; height: 30px; z-index: 7;  }
	#profile-results #profile-list .profile-list-layout1 .favorite { background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .favorite:hover  { background: #bbb; }
	#profile-results #profile-list .profile-list-layout1 .details { overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout1 .details div { float: left; }
	#profile-results #profile-list .profile-list-layout1 .details .details-age { width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout1 .details .details-state { width: 50%; text-align: left;  }
	 /* Profile Standard Alternate */
	#profile-results #profile-list .profile-list-layout2 { width: 150px; float: left; margin: 0 20px 20px 0; }
	#profile-results #profile-list .profile-list-layout2 .image { width: 150px; height: 180px; overflow: hidden; }
	#profile-results #profile-list .profile-list-layout2 .image img { max-width: 150px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken { margin: 10px 10px 0 10px; text-align: center; width: 150px; height: 220px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken a { margin-top: 90px;  float: left; margin-left: 45px; }
	#profile-results #profile-list .profile-list-layout2 .title { color: #555; font-size: 13px; padding: 5px 0 0 0; background: #ddd; text-align: center; }
	#profile-results #profile-list .profile-list-layout2 .title .name { font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .title .name a { color: #555; }
	#profile-results #profile-list .profile-list-layout2 .favorite { background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .favorite:hover  { background: #bbb; }
	#profile-results #profile-list .profile-list-layout2 .details { overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout2 .details div { float: left; }
	#profile-results #profile-list .profile-list-layout2 .details .details-age { width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout2 .details .details-state { width: 50%; text-align: left;  }
    #profile-results-info-countrecord{display:none;}
</style>

</head>
<body onload="printpage()" style="background: #fff;">
<div id="print_wrapper" style=" <?php echo $wrapperWidthHeight;?>  border:0px solid #000;">

<div style="width:887px">
  <?php	
        $footerBlock="<div style='float:left; width:100%; height:90px;'><img style='width:320px; height:67px;' src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/theme/custom-layout6/images/address.jpg'></div>";
		$shortCode = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'" type="'.$type.'"]'); 
		//$shortCode = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'" type="'.$type.'"]'); 
  		$shortCode = str_replace('<!--{footer_block}-->',"$footerBlock",$shortCode);
		echo $shortCode;
  		echo "$footerBlock";
  
  //		"paging" => NULL, "pagingperpage" => NULL,
  ?>
</div>
</div>
</body>
</html>