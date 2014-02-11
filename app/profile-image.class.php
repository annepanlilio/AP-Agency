<?php

class RBAgency_ProfileImage{

	
	// File and rotation
	// TODO: Need to test and implement not found any referance for testing
	function imageRotate($name,$imgsrc){
	
		$directoryPath = $_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/profile-media/" ; 
		$filename = $directoryPath.$name."/"$imgsrc;
		$degrees = 90;
		// Load
		$source = imagecreatefromjpeg($filename);
		// Rotate
		$rotate = imagerotate($source, $degrees, 0);
		// Output
		$fh=fopen($filename,'w');
		imagejpeg($rotate,$filename);
		fclose($fh);
	}


}


?>