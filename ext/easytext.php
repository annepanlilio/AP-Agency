<?php 
class RBAgency_CastingSMS{

	 function sendText($mobile, $link){
			    $mobile = $mobile;
				$mobile = str_replace(' ', '', $mobile);
				$mobile = preg_replace("/[^0-9,.]/", "", $mobile);
				$mobile = ltrim($mobile,'0');
				$mobile = "64".$mobile;
				
				$xml_data ='<request>
								<content>Scouts Honour has put you forward for a Job. See the following link: '.get_tiny_url($link).'</content>
								<recipients>
									<recipient>'.$mobile.'</recipient>
								</recipients>
							</request>';

				$url = "http://YWxhbi5tb250ZWZpb3JlQGdtYWlsLmNvbQ==:d3Z2a3VhYmtt@scoutshonour.easytxt.co.nz/api2/xml/sms";

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);
	}
}

?>