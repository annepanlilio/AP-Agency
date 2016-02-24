<?php 
session_start();
echo $rb_header = RBAgency_Common::rb_header();


/**
$paypal_code = get_option('rbagency_paypal_button_code');

$change = array("http://79.170.40.242/petlondonmodels.com/?s2member_paypal_return=1");

$pcode = str_replace($change,site_url().'/registration-success',$paypal_code);
echo $pcode;
**/



echo "<div id=\"main-content\" class=\"main-content col-sm-8\">";
echo "<div id=\"primary\" class=\"content-area\">";
echo "<div id=\"content\" class=\"site-content\" role=\"main\">";




echo "<h2 style=\" margin-top: 0px; margin-bottom: 10px; \">Choose Membership Plan</h2>";

			add_option("subscription_title_$idx",false);
			add_option("subscription_description_$idx",false);
			add_option("subscription_paypal_btn_$idx",false);

echo "<ul style=\" -webkit-padding-start: 0px; \">";
for($idx=1;$idx<4;$idx++){
	echo "<li style='float:left;margin-right:40px;list-style-type:none;'>";
		$paypal_code = get_option("subscription_paypal_btn_$idx");
		$change = array(
			site_url()."/?s2member_paypal_return=1"
		);

		$change2 = array(
			'<?php echo S2MEMBER_CURRENT_USER_VALUE_FOR_PP_ON0; ?>', 
			'<?php echo S2MEMBER_CURRENT_USER_VALUE_FOR_PP_OS0; ?>',
			'<?php echo S2MEMBER_CURRENT_USER_VALUE_FOR_PP_ON1; ?>',
			'<?php echo S2MEMBER_CURRENT_USER_VALUE_FOR_PP_OS1; ?>');

		//$return_url = site_url()."/?s2member_paypal_return=1&s2member_paypal_return_success=".site_url()."/registration-success";
		$return_url = site_url()."/?s2member_paypal_return=1&s2member_paypal_return_success=".site_url()."/registration-success";
		$pcode = str_replace($change2, '', $paypal_code);
		$pcode_final = str_replace($change,$return_url,$pcode);

		echo "<table>";
		echo "<tr>";
		echo "<td>".nl2br(get_option("subscription_title_$idx"))."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td style=\" margin-bottom: 15px; \">".nl2br(get_option("subscription_description_$idx"))."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".$pcode_final."</td>";
		echo "</tr>";
		echo "</table>";

	echo "</li>";
}
echo "</ul>";


echo "</div></div></div>";


echo $rb_footer = RBAgency_Common::rb_footer();

?>

