<?php exit;
#This page that will generate HTML to feed on domPDF

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_agencylogo'];

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
	.image { width: 150px; height: 180px; margin-left:5px; margin-right:5px;}
</style>


</head>
<body  style="background: #fff;">';
if(!empty($type)){$type=' type="'.$type.'"';}

	  $divisions = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'"'.$type.' paging="0"]'); 
  		
	 $scode = do_shortcode('[profile_list gender="'.$division.'" age_start="'.$ageStart.'" age_stop="'.$ageStop.'"'.$type.' paging="0"]');
 	
	   $divisions=strip_tags($divisions,'<img>');
	   $divisions=str_replace("<img",",<img",$divisions);
	   $divisions=explode(",",$divisions);
	   $footerBlock="<img style='margin-top:60px;width:320px; height:67px;' src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/style/address.jpg'>";
	   
		$perRow=5;
		$perPage=10;
	   $result = "<table border='0'><tr>";
		foreach ($divisions as &$value) {
			$value=trim($value);
		    if(!empty($value) AND strlen($value)>20){
				 $loopcntR++;
				 $loopcntP++;
			     $value=str_replace("<img","<img class='image' style='width:150px; height:220px;' ",$value);
				 $value=str_replace("/>","/><br>",$value);
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
else{$divisionName="";}


//$paperDef="10x16"; // PDF ppaer size
//$divisionName=str_replace("-","_",$ProfileType);
$htmlFile="DirectBooking-".$divisionName."-".date("ymd").".html"; 
//$pdfFile=str_replace(".html",".pdf",$htmlFile);
$pdfFile=str_replace(" ","_",$htmlFile).$fileFormat.".pdf";
$pdfFile=str_replace(".html","",$pdfFile);

$blog_title = strtolower(str_replace(" ","_",get_bloginfo('name')));
$pdfFile="$blog_title-".$pdfFile;

//die($pdfFile);
$toRedirect=rb_agency_BASEDIR."dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
$path="wp-content/plugins/rb-agency/dompdf/htmls/";

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