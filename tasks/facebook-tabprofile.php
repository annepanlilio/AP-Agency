<?php
	
/* FACEBOOK INTEGRATION */
require 'facebook.php';

$app_id = "244619088897387";
$api_key = "c6bd1b5cc14e43e8094bf4c9a9e59f43";
$app_secret = "6b422fe445ba3747838bd8777048decb";
$facebook = new Facebook(array(
        'appId' => $app_id,
        'secret' => $app_secret,
        'cookie' => true
));

$signed_request = $facebook->getSignedRequest();

$page_id = $signed_request["page"]["id"];
$page_admin = $signed_request["page"]["admin"];
$like_status = $signed_request["page"]["liked"];
$country = $signed_request["user"]["country"];
$locale = $signed_request["user"]["locale"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<style>
  body { margin:0px; }
  img { border:none; }
  #wrapper { background:#fff url(http://hostedfacebookapps.com/profile/egnite/images/bomb.png) no-repeat 0 0; width:520px; height:800px; position: relative; }
  #header { position:relative; height:0px; }
  #welcomepage { position:relative; height:0px; }
  #marketing { position:absolute; top:490px; }
  #footerbackground { position:absolute; top:542px; }
  #contactformbar { position:absolute; top:550px; }
  #contactus { position: absolute; top:561px; right: 125px; }
  #contactusbar { position: absolute; top: 550px; left:25px; }
  #logo { position:absolute; top:550px; right:0px; }
  #bomb { position:absolute; top:572px; right:0px; }
  #search { position:absolute; top:605px; left:15px; }
  #contactformtitle { position: absolute; top: 593px; left: 40px; font-family: Arial; font-size: 15px; color: #fff; font-weight: bold; }
  #contactformline { position:absolute; top:635px; left:16px; }
  #formfield { color: #fff; position:absolute; top:645px; left: 16px; }
  #formfield input[type="text"], #formfield textarea { color: #666; background: #D7D7D7; width: 250px; height: 20px; font-family: Arial; margin-bottom:10px; }
  #formfield .form-submit { position: relative; left: 95px; }
  .your-name label { font-family:Arial; font-size: 12px; color:#fff; padding-right:29px; font-weight:bold; }
  .your-email label { font-family:Arial; font-size: 12px; color:#fff; padding-right:31px; font-weight:bold; }
  .your-number label { font-family:Arial; font-size: 12px; color:#fff; padding-right:0px; font-weight:bold; }
</style>
</head>
<body>
<div id="wrapper">
    <div id="welcomepage">
	  <div class="inner">
        <?php
		if ($like_status) {
 		  ?><a href="http://www.facebook.com/egnitemarketing" title="Tell Your Friends"><img src="http://hostedfacebookapps.com/profile/egnite/images/tybtn.jpg" alt="Thank you for your support, tell your friends!" /></a><?php
        } else {
 		  ?><img src="http://hostedfacebookapps.com/profile/egnite/images/likebtn.jpg" alt="Become a Fan of Egnite.com" /><?php
        }
        ?>
      </div>
    </div>
</div>
</body>
</html>