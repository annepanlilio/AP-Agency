<?php
/*
Plugin Name: WP Force DL
Text Domain: wpfdl
Description: Force download a file
Author: RB Agency
Version: 0.0.1
*/
if(!defined('ADAccess')) {
   die('Direct access not permitted');
}

$preset_token = wpfdl_generate_token();
$access_token = get_option('wpfdl_token');

if(empty($access_token)){
	add_option('wpfdl_token',$preset_token);
}

if(isset($_GET['action']) && $_GET['action'] == 'dl'){
	if(empty($_GET['token']) || empty($_GET['dl'])){
		die("You don't have permission to download this file!");
	}
	if(isset($_GET['token']) && !empty($_GET['token'])){
		if(isset($_GET['dl']) && !empty($_GET['dl'])){

			$dlaccess = get_option("wpfdl_dl_".$_GET['dl']."_".$_GET['token']);
			if($dlaccess !== $_GET['dl']){
				die("You don't have permission to download this file!");
			}else{

				update_option('wpfdl_token',wpfdl_generate_token());

				$dir = str_replace("plugins","uploads\profile-media\\",dirname(__FILE__));
				$dir = str_replace("//","",$dir);
				$dir = str_replace("\\","/",$dir);
				$file = $dir.$_GET['dl'];

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
					 
		}
	}
}


function wpfdl_generate_token(){
	return md5(time() . rand());
}

function wpfdl_dl($url,$token){
	$option = get_option("wpfdl_dl_".$url."_".$token);
	if(empty($option)){
		add_option("wpfdl_dl_".$url."_".$token,$url);
	}else{
		update_option("wpfdl_dl_".$url."_".$token,$url);
	}
	
	$dl = " href=\"wp-content\plugins\wpfdl\wpfdl.php?dl=".$url."&token=".$token."&action=dl\" ";
	return $dl;
}


