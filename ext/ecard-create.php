<?php


/* Throughout $screen_id is assumed to hold the screen ID */
/*  
 add_action( 'current_screen', 'thisScreen');

function thisScreen(){
    $currentScreen = get_current_screen();
   // if( $currentScreen->id === "widgets" ) {
        // Run some code, only on the admin widgets page
	print_r($currentScreen);
    //}
    
}
  */

  $screen_id = 'agency_page_rb_agency_searchsaved';
/* Add callbacks for this screen only. */
add_action('load-'.$screen_id, 'wptuts_add_screen_meta_boxes');
add_action('admin_footer-'.$screen_id,'wptuts_print_script_in_footer');

function wptuts_add_screen_meta_boxes() {
 
    /* Trigger the add_meta_boxes hooks to allow meta boxes to be added */
    do_action('add_meta_boxes_'.$screen_id, null);
 
    /* Enqueue WordPress' script for handling the meta boxes */
    wp_enqueue_script('postbox');
    
   wp_enqueue_style( 'image-picker-css', RBAGENCY_PLUGIN_DIR.'ext/image-picker/image-picker.css');
   wp_enqueue_script( 'image-picker-js', RBAGENCY_PLUGIN_DIR.'ext/image-picker/image-picker.min.js');
    
    
    
    //
 
    /* Add screen option: user can choose between 1 or 2 columns (default 2) */
    //add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
}
 
/* Prints script in footer. This 'initialises' the meta boxes */
function wptuts_print_script_in_footer() {
    ?>
    <script>jQuery(document).ready(function(){ postboxes.add_postbox_toggles(pagenow); });</script>
    <?php
}

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
	