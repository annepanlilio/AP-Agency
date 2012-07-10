<?php
// File and rotation
define("DIRECTORY_PATH",$_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/profile-media/");
$filename = DIRECTORY_PATH.@$_GET["name"]."/".@$_GET["imgsrc"];
$degrees = 90;


// Load
$source = imagecreatefromjpeg($filename);

// Rotate
$rotate = imagerotate($source, $degrees, 0);

// Output

$fh=fopen($filename,'w');
imagejpeg($rotate,$filename);
fclose($fh);
?>