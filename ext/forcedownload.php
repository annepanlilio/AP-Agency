<?php
if(isset($_GET['file'])){
    //Please give the Path like this
    $dir = str_replace("ext","",dirname(__FILE__));
    $dir = str_replace("rb-agency","",$dir);
    $dir = str_replace("plugins","uploads\profile-media\\",$dir);
    $dir = str_replace("//","",$dir);
    $dir = str_replace("\\","/",$dir);
    $file = $dir.$_GET['file'];

   if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
   }
   
 
}
?>