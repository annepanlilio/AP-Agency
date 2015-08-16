<?php

$server_ip = $_SERVER['SERVER_ADDR'];





$rootFolder = $_POST['uploadPath'];
$modelID = $_POST['modelID'];
$modelType = $_POST['modelType'];

					
$image_path = $modelID; // Relative to the root
$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);	
	$targetPath = $rootFolder . $image_path;
	
	
	if(!is_dir($targetPath))mkdir($targetPath);

	 
	$randstring = time();
	$_t_filename =  $modelID .'-'. $modelType .'-'. $randstring . '.' . $fileParts['extension'];
	$targetFile  = rtrim($targetPath,'/') . '/' . $_t_filename;
	
	//if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo $image_path .'/'. $_t_filename;
	/* } else {
		echo 'Invalid file type.';
	} */
}
