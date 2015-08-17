<?php


add_action('wp_ajax_sendcastmail','sendcastmail');
add_action('wp_ajax_nopriv_sendcastmail','sendcastmail');
function sendcastmail(){
	
	echo 'xxxxxxxxxxx';
				
				$to = "jenner.alagao@gmail.com";
$subject = "HTML email";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <webmaster@example.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

wp_mail($to,$subject,$message,$headers);
wp_mail($to,$subject,$message);

echo 'success email.';
echo $to,$subject,$message,$headers;

	exit;
}
	