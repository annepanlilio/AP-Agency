<?php 
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']: RBAGENCY_PLUGIN_URL ."assets/img/logo_example.jpg";

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
    </script>
<?php //where we decide what print format will it be.
	  $widthAndHeight='style="width:450px; height:650px;"';
	  //$wrapperWidthHeight="width:887px;"; // $wrapperWidth="1774px";
	  $model_info_width="width:410px;";
	  $ul_css="width:70%;";
	  $toLandScape='@page{size: landscape;margin: 2cm;}';

	  $wrapperWidthHeight = "";
	  $targetwidth = "";
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
		$divisions = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'" type="'.$type.'"]'); 
		//$shortCode = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'" type="'.$type.'"]'); 
			 $divisions=trim(strip_tags($divisions,'<a>'));
			 $divisions = preg_replace( '/\s+/', ' ', $divisions );
			 $divisions = explode("<a" , $divisions);
			 $ctr = 0;
			 $array_info = array();
			 foreach($divisions as $d){
					 $d = trim ($d);
					 if(strpos($d,'style="background-image:') != false){
							// get the image url
							$x = explode('style="background-image:' , $d);
							$i = trim($x[1]);
							$img = str_replace('url(','',$i);
							$img = str_replace(')">','',$img);
							$img = str_replace('</a>','',$img);
							//get name
							$name = $divisions[$ctr+1];
							$n = explode('"scroll">',$name);
							$n = explode('</a>',$n[1]);
							$name = trim($n[0]);
							// load to array
							$array_info[$name] = $img;
					 }
					 $ctr++;
			 }
			
			   
			   $footerBlock="<img style='margin-top:60px;width:320px; height:67px;' src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/style/address.jpg'>";
			   
				$perRow=5;
				$perPage=10;
				$loopcntR = 0;
				$loopcntP = 0;
			   $result = "<table border='0'><tr>";
				foreach ($array_info as $key => $value) {
					$value=trim($value);
					
					//get ratio size;
					$size = getimagesize($value);
					$srcwidth = $size[0]; 
					$srcheight = $size[1]; 
					$targetwidth = 150;
					$targetheight = 220;
					$fLetterBox = true; //fit to window
					
					// scale to the target width
					$scaleX1 = $targetwidth;
					$scaleY1 = ($srcheight * $targetwidth) / $srcwidth;
				
					// scale to the target height
					$scaleX2 = ($srcwidth * $targetheight) / $srcheight;
					$scaleY2 = $targetheight;
				
					// now figure out which one we should use
					$fScaleOnWidth = ($scaleX2 > $targetwidth);
					if ($fScaleOnWidth) {
						$fScaleOnWidth = $fLetterBox;
					}
					else {
					   $fScaleOnWidth = !$fLetterBox;
					}
					if ($fScaleOnWidth) {
						$width = floor($scaleX1);
						$height = floor($scaleY1);
						$fScaleToTargetWidth = true;
					}
					else {
						$width = floor($scaleX2);
						$height = floor($scaleY2);
						$fScaleToTargetWidth = false;
					}
					$targetleft = floor(($targetwidth - $width) / 2);
					$targettop = floor(($targetheight - $height) / 2);			
					
					if(!empty($value)){
						 $loopcntR++;
						 $loopcntP++;
						 $value="<div style='width:150px; height:220px; overflow:hidden;'><img style='width:".$width."px; height:".$height."px;' src='" . $value . "'/></div><br>" . $key;
						 $result .= "<td align='center' width='150' style='margin:5px; float:left;'>";
						 $result .= "".$value."";
						 $result .= "</td>";
						 if($loopcntR==$perRow){
							  $result .= "</tr>";
							if($loopcntP==$perPage){$result .= "</table>$footerBlock<div style='page-break-before:always' /></div><table border='0'>"; $loopcntP=0;}
							  $result .= "<tr>";$loopcntR=0;
						 }
						
					}
				}	
				$result .= "</tr></table><br clear='all'>";
		echo $result;
  		echo $footerBlock;
  ?>
</div>
</div>
</body>
</html>