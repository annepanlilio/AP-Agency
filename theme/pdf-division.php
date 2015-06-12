<?php
#This page that will generate HTML to feed on domPDF

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";

$header='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title> Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />

	<script language="Javascript1.2">
      <!--
      function printpage() {
       window.print();
      }
      //-->
    </script>

<style>
	.image {width: 150px; height: 180px; margin-left:5px; margin-right:5px;}
</style>


</head>
<body  style="background: #fff;">';
if(!empty($type)){$type=' type="'.$type.'"';}

		$divisions = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'"'.$type.' paging="0"]'); 
  
	$scode = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'"'.$type.' paging="0"]');
 
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


		// $footerBlock="<img style='margin-top:60px;width:320px; height:67px;' src='".$rb_agency_option_agencylogo."'>";
		$footerBlock.='<img style="margin-top:60px;width:320px; height:67px;" src="'.$rb_agency_option_agencylogo.'">';

		$perRow=5;
		$perPage=10;
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
			$targetleft = floor((targetwidth - result.width) / 2);
			$targettop = floor((targetheight - result.height) / 2);

			if(!empty($value)){
				$loopcntR++;
				$loopcntP++;
					$value="<div style='width:150px; height:220px; overflow:hidden;'><img class='image' style='width:".$width."px; height:".$height."px;' src='" . $value . "'/></div><br>" . $key;
				$result .= "<td align='center' width='150'>";
				$result .= "".$value."";
				$result .= "</td>";
				if($loopcntR==$perRow){
						$result .= "</tr>";
					if($loopcntP==$perPage){$result .= "</table>$footerBlock<div style='page-break-before:always' /></div><table border='0'>"; $loopcntP=0;}
						$result .= "<tr>";$loopcntR=0;
				}

			}
		}
		$result .= "</tr></table><br clear='all'>$footerBlock";

$footer='</body>
</html>';

//die($result);

//echo $ageStart."-".$ageStop."-".$division."<br>";
//$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'"'.$type.' paging="0 "
if($ageStart==1 AND $ageStop==12 AND $division==1){$divisionName="Boys";}
elseif($ageStart==1 AND $ageStop==12 AND $division==2){$divisionName="Girls";}
elseif($ageStart==13 AND $ageStop==18 AND $division==1){$divisionName="Teen-Boys";}
elseif($ageStart==13 AND $ageStop==18 AND $division==2){$divisionName="Teen-Girls";}
elseif($ageStart==18 AND $ageStop==99 AND $division==1){$divisionName="Men";}
elseif($ageStart==18 AND $ageStop==99 AND $division==2){$divisionName="Women";}
else {$divisionName="Models";}


//$paperDef="10x16"; // PDF ppaer size
//$divisionName=str_replace("-","_",$ProfileType);
$htmlFile="DirectBooking-".$divisionName."-".date("Ymd")."-".rand(100,200).".html"; 
//$pdfFile=str_replace(".html",".pdf",$htmlFile);
$pdfFile=str_replace(" ","_",$htmlFile).$fileFormat.".pdf";
$pdfFile=str_replace(".html","",$pdfFile);

$blog_title = strtolower(str_replace(" ","_",get_bloginfo('name')));
$pdfFile="$blog_title-".$pdfFile;

//die($pdfFile);
$toRedirect=RBAGENCY_PLUGIN_URL."ext/dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
$path="wp-content/plugins/rb-agency/ext/dompdf/htmls/";

$fp=fopen($path.$htmlFile,"w");
fwrite($fp,$header);
//fwrite($fp,$border);
//fwrite($fp,$modelInfo);
//fwrite($fp,$allImages);
fwrite($fp,$result);
fwrite($fp,$footer);
fclose($fp);

//die($toRedirect);
header("location:$toRedirect");

?>