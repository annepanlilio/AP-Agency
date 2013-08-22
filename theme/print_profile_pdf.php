<?php
 
/*
 * Instantiate object 
 */
  $profileID = (int)$_REQUEST['pid'];
  $img_row = (int)$_REQUEST['row'];
  $logo = $_REQUEST['logo'];
  $print = new rb_print_pdf($profileID,$img_row,$logo);
  $print->rb_out_pdf();
 

/*
 * Print PDF Object 
 */
  class rb_print_pdf{

       /*
        * pdf properties 
        */
        protected $profile_ID; 
        protected $rendered_action;
        protected $wp;
        protected $prf_tbl;
        protected $cus_fld;
        protected $cus_mux;
        protected $med_tbl;
        protected $img_row;
        protected $logo;
        protected $img_page = 16;
        protected $bse_path ='';
        
       /*
        * our constructor 
        */
        public function __construct($ID,$img_row,$logo) {
            
            $this->profile_ID = $ID;
            require_once( "../../../../wp-config.php" );
            require_once( "../../../../wp-includes/wp-db.php" );
            $this->wp = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
            $pfx = (!empty($this->wp->prefix)) ? $this->wp->prefix : "wp_"; 
            $this->prf_tbl = $pfx . "agency_profile";
            $this->cus_fld = $pfx . "agency_customfields";
            $this->cus_mux = $pfx . "agency_customfield_mux";
            $this->med_tbl = $pfx . "agency_profile_media";
            $this->img_row = $img_row;
            $this->logo = $logo;
            $this->bse_path = "htmls/";
            
       }
        
       /*
        * lets stream our file for output 
        */
        public function rb_out_pdf(){
 
            $file_out = $this->html_contruct();
            $this->rb_stream($file_out);
            
        }	 

       /*
        * file stream and response
        */
        private function rb_stream($file=NULL){

            $generate_file = $this->generate_file($file);
            $this->rb_render_redirect($generate_file);  
            
            $this->response($this->rendered_action);
                
        }

       /*
        * generate html file 
        */
        private function generate_file($htm){
 
            $htm_name = "print-pdf-".$this->profile_ID.".html";
            $path= "../../../../wp-content/plugins/rb-agency/tasks/dompdf/htmls/";
            $fp=fopen($path.$htm_name,"w");
            fwrite($fp,$htm);
            fclose($fp);
            
            if(file_exists($path.$htm_name)){
                return $htm_name;
            } else {
                return NULL;
            }

        }
        
       /*
        * redirect format
        */ 
        private function rb_render_redirect($file = NULL){
            
            if(is_null($file)) return false;
            
            $add = plugins_url("rb-agency/tasks/dompdf/");
            $this->rendered_action = $add. 'dompdf.php?base_path='.$this->bse_path.'&options[Attachment]=0&input_file='.$file.'&view=FitH&statusbar=0&messages=0&navpanes=0';
        }
        
       /*
        * html reconstruction
        */ 
        private function html_contruct(){

            $htm = "";
            
            $header = $this->body_part("upper");
            $footer = $this->body_part("lower");
            
            $data = $this->get_profile_details($this->profile_ID);
            $image = $this->get_profile_images($this->profile_ID);
            
            $data_htm = (!$data) ? "" : $this->render_data_htm($data);
            $image_htm = (!$image) ? "" : $this->render_image_htm($image);
            
            $htm = $header;
            $htm .= (!empty($image_htm)) ? $image_htm : "";
            $htm .= (!empty($data_htm)) ? $data_htm : "" ;
            $htm .= $footer;
            return $htm;

       }
       
      /*
       * get our table for data
       */ 
       private function render_data_htm($data=NULL){
           
            $htm = '<table>'.PHP_EOL;
            foreach($data as $d){
                if(strtolower($d->ProfileCustomTitle) == "height"){
                   $heightraw = $d->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
	           $val = $heightfeet."ft ".$heightinch." in"; 
                } else {
                   $val = $d->ProfileCustomValue; 
                }
                $name = $d->ProfileContactDisplay;
                $htm .="    <tr>";
                $htm .="        <td>";
                $htm .= $d->ProfileCustomTitle;
                $htm .="        </td>";
                $htm .="        <td>";
                $htm .= $val;
                $htm .="        </td>";
                $htm .="    </tr>".PHP_EOL;
            }
            $htm .= '</table>';
                $n = "   <h2>".$name."</h2><br/>".PHP_EOL;
            $logo = '   <img src="'.$this->logo.'" style="margin-top:20px; height:80px; width:auto">'.PHP_EOL;
            return $n . $htm . $logo;
      
       }

      /*
       * get our table for image
       */ 
       private function render_image_htm($image=NULL){
            $height = 200;
            $width = 150;
            $htm = '<table>' .PHP_EOL;
            $htm .="    <tr>".PHP_EOL; 
            $ctr = 0; $total = 0;
            foreach($image as $i){
                if($ctr == $this->img_row) {
                     $htm .= "  </tr>".PHP_EOL;
                     $htm .= "  <tr>".PHP_EOL;
                     $ctr = 0;
                } 
                if(($total % $this->img_page)==0 && $total > 0) {$htm .='</table>'.PHP_EOL.'<div style="page-break-before:always"></div>'.PHP_EOL.'<table border="0">'.PHP_EOL.'    <tr>';}
                $im = $this->get_image_url($i->ProfileMediaURL);
                $size = $this->get_size_p($im,$width, $height);
                $htm .="        <td style='float:left;'>";
                $htm .="            <img src='".$im."' style='width:".$size[0]."px; height:".$size[1]."px'/>";
                $htm .="        </td>".PHP_EOL;
                $ctr++;
                $total++;
            }
            $htm .= '</table>'.PHP_EOL;
            return $htm;
       }

      /*
       * get our image url
       */ 
       private function get_image_url($image=NULL){
           
            $query = "SELECT ProfileGallery FROM " . $this->prf_tbl . 
                     " WHERE ProfileID = " . $this->profile_ID;
            $result = $this->wp->get_results($query);
            if (count($result) > 0) {
               foreach($result as $r){
                   return get_bloginfo('wpurl') . "/wp-content/uploads/profile-media/" . $r->ProfileGallery ."/".$image;
               }
            } 
           
       }
       
       /*
        * get our profile values
        */ 
        private function get_profile_details($id=NULL){
            $query = "SELECT pr.ProfileContactDisplay, mx.ProfileCustomValue, title.ProfileCustomTitle FROM " . $this->cus_mux . " mx " .
                     "LEFT JOIN "  . $this->cus_fld  . " title " .
                     "ON title.ProfileCustomID = mx.ProfileCustomID " .
                     "LEFT JOIN "  . $this->prf_tbl  . " pr " .
                     "ON mx.ProfileID = pr.ProfileID " .
                     " WHERE mx.ProfileID = " . $id;
            $result = $this->wp->get_results($query);
            return (count($result) > 0) ?  $result : false; 
        }
       
       /*
        * get profile media images 
        */ 
        private function get_profile_images($id=NULL){
            $query = "SELECT * FROM " . $this->med_tbl . 
                     " WHERE ProfileID = " . $id . " AND ProfileMediaType = 'Image'";
             $result = $this->wp->get_results($query);
            return (count($result) > 0) ?  $result : false; 
        }                    
       
       /*
        * natural html parts
        */ 
        private function body_part($part = NULL){
            switch ($part){
                case "upper":
                    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
                            <head><title>Print</title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <meta name="Robots" content="noindex, nofollow" />
                            <style>
                                table { border-collapse: collapse; }
                            </style>
                            </head>
                            <body>';
                    break;
                case "lower":
                    return '</body>
                        </html>';
                    break;
            }
        }
        
        /*
         * Image scale proportion
         */
        private function get_size_p($image=NULL,$wd=NULL, $ht=NULL){

                $result = array('width'=> 0, 'height' => 0, 'fscaleToTargetWidth'=> true,'targetleft'=>0, 'targettop'=>0);
               	$size = getimagesize($image);
		$srcwidth = $size[0]; 
		$srcheight = $size[1]; 
		$targetwidth = $wd;
		$targetheight = $ht;
		$fLetterBox = true; //fit to window
                $scaleX1 = $targetwidth;
                $scaleY1 = ($srcheight * $targetwidth) / $srcwidth;

                $scaleX2 = ($srcwidth * $targetheight) / $srcheight;
                $scaleY2 = $targetheight;

                $fscaleOnWidth = ($scaleX2 > $targetwidth);
                if ($fscaleOnWidth) {
                    $fscaleOnWidth = $fLetterBox;
                }
                else {
                $fscaleOnWidth = !$fLetterBox;
                }

                if ($fscaleOnWidth) {
                    $result['width'] = floor($scaleX1);
                    $result['height'] = floor($scaleY1);
                    $result['fscaleToTargetWidth'] = true;
                }
                else {
                    $result['width'] = floor($scaleX2);
                    $result['height'] = floor($scaleY2);
                    $result['fscaleToTargetWidth'] = false;
                }
                $result['targetleft'] = floor(($targetwidth - $result['width']) / 2);
                $result['targettop'] = floor(($targetheight - $result['height']) / 2);

                return array($result['width'], $result['height']) ;

        }
        
        /*
         * Response; TODO, can setup error messages response return
         */
        private function response($response = NULL){
                        
            if($response){
                echo $response;
            } else {
                echo "0";
            }
            
        }

 }
?>

