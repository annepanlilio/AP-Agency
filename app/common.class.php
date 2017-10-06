<?php

class RBAgency_Common {

	/**
	 * Show Header
	 *
	 * @return echo 
	 */

	static function rb_header() {

		// Call WordPress Header
		get_header();

		// Now Call Our Header
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_layout_options');
			// What is the unit of measurement?
			$rb_agency_value_styleheader = $rb_agency_options_arr['rb_agency_option_styleheader'];
			// Display the custom header html
			echo $rb_agency_value_styleheader;
	}

	/**
	 * Encode JSON
	 *
	 * @return echo 
	 */

	public static function json_encode($value){
		return json_encode($value);
	}

	/**
	 * Decode JSON
	 *
	 * @return echo 
	 */

	public static function json_decode($str, $is_assoc=true){
		return json_decode($str, $is_assoc);
	}

	/**
	 * Get Base URL
	 * Returns the url of the plugin's root folder
	 *
	 * @return echo 
	 */

	public static function get_base_url(){
		$plugin = plugin_basename(__FILE__);
		$plugin = substr($plugin, 0, strpos($plugin, "/"));
		return plugins_url($plugin);
	}

	/**
	 * Get Base Path
	 * Returns the physical path of the plugin's root folder
	 *
	 * @return echo 
	 */

	public static function get_base_path(){
		$folder = basename(dirname(__FILE__));
		return WP_PLUGIN_DIR . "/" . $folder;
	}


	/**
	 * Show Footer
	 *
	 * @return echo 
	 */

	static function rb_footer() {

		// Now Call Our Header
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_layout_options');
			// What is the unit of measurement?
			$rb_agency_value_stylefooter = $rb_agency_options_arr['rb_agency_option_stylefooter'];
			// Display the custom header html
			echo $rb_agency_value_stylefooter;

		// Call WordPress Header
		get_footer();
	}

	/**
	 * Clean String, remove extra quotes
	 *
	 * @param string $string
	 */

	static function clean_string($string) {

		// Remove trailing dingleberry
		if (substr($string, -1) == ",") { $string = substr($string, 0, strlen($string)-1); }
		if (substr($string, 0, 1) == ",") {$string = substr($string, 1, strlen($string)-1); }
		// Just Incase
		$string = str_replace(",,", ",", $string);

		return $string;
	}


	/**
	 * Generate random string
	 *
	 * @param int $length
	 * @param str $valid_chars
	 * @return string
	 */

	static function generate_random_string($length = 8, $valid_chars = "") {
		// start with an empty random string
		$random_string = "";

		// Create Character Set if Not Provided
		if (empty($valid_chars)){
			$num_valid_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		}

		// count the number of chars in the valid chars string so we know how many choices we have
		if(!empty($valid_chars))
			$num_valid_chars = strlen($valid_chars);
		else
			$num_valid_chars = strlen($num_valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++) {
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			if($valid_chars)
			$random_char = $valid_chars[$random_pick-1];
			else
			$random_char = $random_pick;


			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}



	/**
	 * Generate random number
	 *
	 * @param int $length
	 * @return string
	 */

	static function generate_random_numeric($length) {

		// Set Characters
		$chars = "0123456789";

		// Set Dictionary Size
		$size = strlen( $chars );

		// Loop
		for( $i = 0; $i < $length; $i++ ) {
			$string .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $string;

	}

	/**
	 * Get Session and avoid undefined index
	 *
	 * @param string $string
	 */
	static function session($string) {
		if(!is_array($string)){
			if(isset($_SESSION[$string]) && !empty($_SESSION[$string])){
				return $_SESSION[$string];
			} else {
				return "";
			}
		}
	}



	/**
	 * Collapse White Space
	 *
	 * @param string $string
	 */
	static function format_whitespace($string) {
		return preg_replace('/\s+/', ' ', $string);
	}


	/**
	 * Prepare string to be filename
	 *
	 * @param string $filename
	 */
	static function format_stripchars($filename,$tolower = true) {
		$filename = self::format_whitespace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		if($tolower){
			return strtolower($filename);
		} else {
			return $filename;
		}
	}


	/**
	 * Format a string in proper case.
	 *
	 * @param string $string
	 */
	static function format_propercase($string) {
		return ucwords(strtolower($string));
	}

	/**
	 * Get Gender Title
	 *
	 * @param int $ProfileGenderID
	 * @return str $GenderTitle
	 */

	static function profile_meta_gendertitle($ProfileGenderID){

		// Get DB
		global $wpdb;

		// Get Gender
		$gender = $wpdb->get_col( $wpdb->prepare( "SELECT GenderTitle FROM " . table_agency_data_gender . " WHERE GenderID = %s", $ProfileGenderID ) );

		// Check if Value Exists
		if($gender){
			return $gender[0];
		} else {
			return false;
		}

	}



	/**
	 * Check if RB Agency Plugin Exists
	 *
	 * @return bool true
	 */

	public static function rb_agency_exists(){

		if (is_plugin_active('rb-agency/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency-master/rb-agency-casting.php')) {
			return true;
		} else {
			return false;
		}

	}



	/**
	 * Check if Plugin Exists
	 *
	 * @return bool true
	 */

	public static function rb_agency_interact_exists(){

		if (is_plugin_active('rb-agency-interact/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency-Interact/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency-Interact-master/rb-agency-casting.php')) {
			return true;
		} else {
			return false;
		}

	}





	/**
	 * Check if RB Agency Casting Plugin Exists
	 *
	 * @return bool true
	 */

	public static function rb_agency_casting_exists(){

		if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency-Casting/rb-agency-casting.php')) {
			return true;
		} elseif (is_plugin_active('RB-Agency-Casting-master/rb-agency-casting.php')) {
			return true;
		} else {
			return false;
		}

	}



	/*
	 * Data: Country
	 */
	static function data_country(){

		return array(
			'AF'=>'Afghanistan',
			'AL'=>'Albania',
			'DZ'=>'Algeria',
			'AS'=>'American Samoa',
			'AD'=>'Andorra',
			'AO'=>'Angola',
			'AI'=>'Anguilla',
			'AQ'=>'Antarctica',
			'AG'=>'Antigua And Barbuda',
			'AR'=>'Argentina',
			'AM'=>'Armenia',
			'AW'=>'Aruba',
			'AU'=>'Australia',
			'AT'=>'Austria',
			'AZ'=>'Azerbaijan',
			'BS'=>'Bahamas',
			'BH'=>'Bahrain',
			'BD'=>'Bangladesh',
			'BB'=>'Barbados',
			'BY'=>'Belarus',
			'BE'=>'Belgium',
			'BZ'=>'Belize',
			'BJ'=>'Benin',
			'BM'=>'Bermuda',
			'BT'=>'Bhutan',
			'BO'=>'Bolivia',
			'BA'=>'Bosnia And Herzegovina',
			'BW'=>'Botswana',
			'BV'=>'Bouvet Island',
			'BR'=>'Brazil',
			'IO'=>'British Indian Ocean Territory',
			'BN'=>'Brunei',
			'BG'=>'Bulgaria',
			'BF'=>'Burkina Faso',
			'BI'=>'Burundi',
			'KH'=>'Cambodia',
			'CM'=>'Cameroon',
			'CA'=>'Canada',
			'CV'=>'Cape Verde',
			'KY'=>'Cayman Islands',
			'CF'=>'Central African Republic',
			'TD'=>'Chad',
			'CL'=>'Chile',
			'CN'=>'China',
			'CX'=>'Christmas Island',
			'CC'=>'Cocos (Keeling) Islands',
			'CO'=>'Columbia',
			'KM'=>'Comoros',
			'CG'=>'Congo',
			'CK'=>'Cook Islands',
			'CR'=>'Costa Rica',
			'CI'=>'Cote D\'Ivorie (Ivory Coast)',
			'HR'=>'Croatia (Hrvatska)',
			'CU'=>'Cuba',
			'CY'=>'Cyprus',
			'CZ'=>'Czech Republic',
			'CD'=>'Democratic Republic Of Congo (Zaire)',
			'DK'=>'Denmark',
			'DJ'=>'Djibouti',
			'DM'=>'Dominica',
			'DO'=>'Dominican Republic',
			'TP'=>'East Timor',
			'EC'=>'Ecuador',
			'EG'=>'Egypt',
			'SV'=>'El Salvador',
			'GQ'=>'Equatorial Guinea',
			'ER'=>'Eritrea',
			'EE'=>'Estonia',
			'ET'=>'Ethiopia',
			'FK'=>'Falkland Islands (Malvinas)',
			'FO'=>'Faroe Islands',
			'FJ'=>'Fiji',
			'FI'=>'Finland',
			'FR'=>'France',
			'FX'=>'France, Metropolitan',
			'GF'=>'French Guinea',
			'PF'=>'French Polynesia',
			'TF'=>'French Southern Territories',
			'GA'=>'Gabon',
			'GM'=>'Gambia',
			'GE'=>'Georgia',
			'DE'=>'Germany',
			'GH'=>'Ghana',
			'GI'=>'Gibraltar',
			'GR'=>'Greece',
			'GL'=>'Greenland',
			'GD'=>'Grenada',
			'GP'=>'Guadeloupe',
			'GU'=>'Guam',
			'GT'=>'Guatemala',
			'GN'=>'Guinea',
			'GW'=>'Guinea-Bissau',
			'GY'=>'Guyana',
			'HT'=>'Haiti',
			'HM'=>'Heard And McDonald Islands',
			'HN'=>'Honduras',
			'HK'=>'Hong Kong',
			'HU'=>'Hungary',
			'IS'=>'Iceland',
			'IN'=>'India',
			'ID'=>'Indonesia',
			'IR'=>'Iran',
			'IQ'=>'Iraq',
			'IE'=>'Ireland',
			'IL'=>'Israel',
			'IT'=>'Italy',
			'JM'=>'Jamaica',
			'JP'=>'Japan',
			'JO'=>'Jordan',
			'KZ'=>'Kazakhstan',
			'KE'=>'Kenya',
			'KI'=>'Kiribati',
			'KW'=>'Kuwait',
			'KG'=>'Kyrgyzstan',
			'LA'=>'Laos',
			'LV'=>'Latvia',
			'LB'=>'Lebanon',
			'LS'=>'Lesotho',
			'LR'=>'Liberia',
			'LY'=>'Libya',
			'LI'=>'Liechtenstein',
			'LT'=>'Lithuania',
			'LU'=>'Luxembourg',
			'MO'=>'Macau',
			'MK'=>'Macedonia',
			'MG'=>'Madagascar',
			'MW'=>'Malawi',
			'MY'=>'Malaysia',
			'MV'=>'Maldives',
			'ML'=>'Mali',
			'MT'=>'Malta',
			'MH'=>'Marshall Islands',
			'MQ'=>'Martinique',
			'MR'=>'Mauritania',
			'MU'=>'Mauritius',
			'YT'=>'Mayotte',
			'MX'=>'Mexico',
			'FM'=>'Micronesia',
			'MD'=>'Moldova',
			'MC'=>'Monaco',
			'MN'=>'Mongolia',
			'MS'=>'Montserrat',
			'MA'=>'Morocco',
			'MZ'=>'Mozambique',
			'MM'=>'Myanmar (Burma)',
			'NA'=>'Namibia',
			'NR'=>'Nauru',
			'NP'=>'Nepal',
			'NL'=>'Netherlands',
			'AN'=>'Netherlands Antilles',
			'NC'=>'New Caledonia',
			'NZ'=>'New Zealand',
			'NI'=>'Nicaragua',
			'NE'=>'Niger',
			'NG'=>'Nigeria',
			'NU'=>'Niue',
			'NF'=>'Norfolk Island',
			'KP'=>'North Korea',
			'MP'=>'Northern Mariana Islands',
			'NO'=>'Norway',
			'OM'=>'Oman',
			'PK'=>'Pakistan',
			'PW'=>'Palau',
			'PA'=>'Panama',
			'PG'=>'Papua New Guinea',
			'PY'=>'Paraguay',
			'PE'=>'Peru',
			'PH'=>'Philippines',
			'PN'=>'Pitcairn',
			'PL'=>'Poland',
			'PT'=>'Portugal',
			'PR'=>'Puerto Rico',
			'QA'=>'Qatar',
			'RE'=>'Reunion',
			'RO'=>'Romania',
			'RU'=>'Russia',
			'RW'=>'Rwanda',
			'SH'=>'Saint Helena',
			'KN'=>'Saint Kitts And Nevis',
			'LC'=>'Saint Lucia',
			'PM'=>'Saint Pierre And Miquelon',
			'VC'=>'Saint Vincent And The Grenadines',
			'SM'=>'San Marino',
			'ST'=>'Sao Tome And Principe',
			'SA'=>'Saudi Arabia',
			'SN'=>'Senegal',
			'SC'=>'Seychelles',
			'SL'=>'Sierra Leone',
			'SG'=>'Singapore',
			'SK'=>'Slovak Republic',
			'SI'=>'Slovenia',
			'SB'=>'Solomon Islands',
			'SO'=>'Somalia',
			'ZA'=>'South Africa',
			'GS'=>'South Georgia And South Sandwich Islands',
			'KR'=>'South Korea',
			'ES'=>'Spain',
			'LK'=>'Sri Lanka',
			'SD'=>'Sudan',
			'SR'=>'Suriname',
			'SJ'=>'Svalbard And Jan Mayen',
			'SZ'=>'Swaziland',
			'SE'=>'Sweden',
			'CH'=>'Switzerland',
			'SY'=>'Syria',
			'TW'=>'Taiwan',
			'TJ'=>'Tajikistan',
			'TZ'=>'Tanzania',
			'TH'=>'Thailand',
			'TG'=>'Togo',
			'TK'=>'Tokelau',
			'TO'=>'Tonga',
			'TT'=>'Trinidad And Tobago',
			'TN'=>'Tunisia',
			'TR'=>'Turkey',
			'TM'=>'Turkmenistan',
			'TC'=>'Turks And Caicos Islands',
			'TV'=>'Tuvalu',
			'UG'=>'Uganda',
			'UA'=>'Ukraine',
			'AE'=>'United Arab Emirates',
			'UK'=>'United Kingdom',
			'US'=>'United States',
			'UM'=>'United States Minor Outlying Islands',
			'UY'=>'Uruguay',
			'UZ'=>'Uzbekistan',
			'VU'=>'Vanuatu',
			'VA'=>'Vatican City (Holy See)',
			'VE'=>'Venezuela',
			'VN'=>'Vietnam',
			'VG'=>'Virgin Islands (British)',
			'VI'=>'Virgin Islands (US)',
			'WF'=>'Wallis And Futuna Islands',
			'EH'=>'Western Sahara',
			'WS'=>'Western Samoa',
			'YE'=>'Yemen',
			'YU'=>'Yugoslavia',
			'ZM'=>'Zambia',
			'ZW'=>'Zimbabwe'
		);
	}

	/*
	 *  Display: Print Profile
	 */

	static function print_profile($ProfileID = null,$ProfileGallery = null, $ProfileContactDisplay = null){

		global $wpdb;

		$rb_agency_options_arr = get_option('rb_agency_options');
		$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];

		$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
		$urlexploade = explode("/", $profileURLString);
		$subview= isset($urlexploade[1])?$urlexploade[1]:"";

		if(isset($_POST['pdf_all_images']) && $_POST['pdf_all_images']!=""){
			require_once(dirname(__FILE__).'/../theme/pdf-profile.php');
			exit;
		}



				//to load profile page sub pages or just load the main profile page
			if($subview=="images"){ //show all images page  //MODS 2012-11-28 ?>
				<div class="allimages_div">
					<script>  //JS to higlight selected images 
						function selectImg(mid){
						//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

							if(document.getElementById("p"+mid).value==1){
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=100)";
								img.style.MozOpacity   = "100";
								img.style.opacity      = "100";
								img.style.KhtmlOpacity = "100";
								document.getElementById("p"+mid).value=0;
							} else {
								document.getElementById("p"+mid).value=1;
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=25)";
								img.style.MozOpacity   = "0.25";
								img.style.opacity      = "0.25";
								img.style.KhtmlOpacity = "0.25";
							}

						}

						function validateAllImageForm()
						{
							if (!jQuery(".allImageCheck").is(":checked"))
							{
								alert("Please select atleast one photo!");
								return false;
							}

							return true;
						}
					</script>
					<span class="allimages_text"><?php echo __("Please select photos to print. Maximum is 100 photos only", RBAGENCY_TEXTDOMAIN)?><br /></span><br />
					<form action="../print-images/" method="post" id="allimageform" onsubmit="return validateAllImageForm()">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						# rb_agency_option_galleryorder
						$rb_agency_options_arr = get_option('rb_agency_options');
						$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
							$bfi_params = array(
								'crop'=>true,
								'width'=>106,
								'height'=>130
							);
							$image_src = bfi_thumb( $image_path, $bfi_params );

							echo '<div style="margin:4px; float:left;width:115px;height:150px;"><a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
							//echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/".RBAGENCY_TEXTDOMAIN."/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
							echo "<img src=\"". $image_src."\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
						}
						?> <br clear="all" />
						<input type="submit" value="Next, Select Print Format" />
					</form>
					</div><!-- allimages_div-->

					<?php  //load lightbox for images
					} elseif($subview=="lightbox"){ //show all images page  //MODS 2012-11-28 ?>
						<div class="allimages_div">
						<span class="allimages_text"> <br /></span><br />
						<form action="../print-images/" method="post" id="allimageform">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
							$bfi_params = array(
								'crop'=>true,
								'width'=>106,
								'height'=>130
							);
							$image_src = bfi_thumb( $image_path, $bfi_params );

							echo '<a class="allimages_print" href="'. RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery">';
							//echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/".RBAGENCY_TEXTDOMAIN."/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". $image_src."\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
					}
					?> <br clear="all" />

					</form>
				</div><!-- allimages_div-->

			<?php } elseif($subview=="polaroids"){ //show all polaroids page  //MODS 2012-11-28 ?>

				<div class="allimages_div">
					<script>  //JS to higlight selected images 
						function selectImg(mid){
							//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

							if(document.getElementById("p"+mid).value==1){
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=100)";
								img.style.MozOpacity   = "100";
								img.style.opacity      = "100";
								img.style.KhtmlOpacity = "100";
								document.getElementById("p"+mid).value=0;
							} else {
								document.getElementById("p"+mid).value=1;
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=25)";
								img.style.MozOpacity   = "0.25";
								img.style.opacity      = "0.25";
								img.style.KhtmlOpacity = "0.25";
							}

						}
					</script>
					<?php 
					$queryImg = rb_agency_option_galleryorder_query("ProfileMediaID" ,$ProfileID,"Polaroid");
					$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
					$countImg  = $wpdb->num_rows;

					if($countImg>0){

					?>
					<span class="allimages_text"><br /></span><br />
					<form action="../print-polaroids/" method="post" id="allimageform">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						foreach($resultsImg as $dataImg ){

							$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'];
							$bfi_params = array(
								'crop'=>true,
								'width'=>106,
								'height'=>130
							);
							$image_src = bfi_thumb( $image_path, $bfi_params );

							echo '<a href="'. RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery" class="allimages_print" href="javascript:void(0)">'; // onClick="selectImg('.$dataImg["ProfileMediaID"].')"
							//echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/".RBAGENCY_TEXTDOMAIN."/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". $image_src."\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
						}
						?> <br clear="all" />

						<!--	<input type="submit" value="Next, Select Print Format" />-->

					</form> <?php } else { ?>Sorry, there is no available polaroid images for this profile.<?php }?>
				</div><!-- allimages_div-->

			<?php } else if ($subview=="print-polaroids"){ //show print options

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
				$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
				$countImg  = $wpdb->num_rows;
				$withSelected = 0;
				$lasID = 0;
				foreach($resultsImg as $dataImg ){
					if($_POST[$dataImg['ProfileMediaID']]==1){
						$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
						$withSelected=1;
					}
						$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if($withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>

				<div class="print_options">
					<span class="allimages_text">Select Print Format</span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="11" name="print_option" checked="checked" /><h3>Four Polaroids Per Page</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo("url")?>/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>/view/layout/06/images/polariod-four-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="12" name="print_option" /><h3>One Polaroid Per Page</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo("url")?>/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>/view/layout/06/images/polariod-one-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

					</div><!-- polariod -->

					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="Print Polaroids" name="print_all_images" />
						<input type="submit" value="Download PDF Polaroids" name="pdf_all_images" />
					</center>
				</form>


			<?php } else if($subview=="print-images") { //show print options
				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
				$countImg  = $wpdb->num_rows;
				$selected = "";
				foreach($resultsImg as $dataImg ){
				if(isset($_POST[$dataImg['ProfileMediaID']]) && $_POST[$dataImg['ProfileMediaID']] ==1){
				$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
				$withSelected=1;
				}
				$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if(isset($withSelected) && $withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>

				<div class="print_options">
					<span class="allimages_text">Select Print Format</span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">
						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1" name="print_option" checked="checked" /><h3>Print Large Photos</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>/view/layout/06/images/polariod-large-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3" name="print_option" /><h3>Print Medium Size Photos</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>/view/layout/06/images/polariod-medium-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->


						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1-1" name="print_option" /><h3>Print Large Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>y/view/layout/06/images/polariod-large-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3-1" name="print_option" /><h3>Print Medium Size Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/<?php echo RBAGENCY_TEXTDOMAIN;?>y/view/layout/06/images/polariod-medium-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<?php
							if(isset($_POST['pdf_image_id'])) {
								$pdf_image_id=implode(',',$_POST['pdf_image_id']);
						?>
							<input type="hidden" name="pdf_image_id" value="<?php echo($pdf_image_id);?>" />
						<?php
							}
						?>


						</div><!-- polariod -->
					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="Print Pictures" name="print_all_images" />&nbsp;
						<input type="submit" value="Download PDF" name="pdf_all_images" />
					</center>
				</form>

			<?php } else { ?> 

				<div id="profile-slide" class="rbcol-8 rbcolumn">
					<div id="layout6-slider" class="flexslider">
						<ul class="slides">
							<?php
										$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
										$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
										$countImg  = $wpdb->num_rows;
										foreach($resultsImg as $dataImg ){

											$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
											$bfi_params = array(
												'crop'=>true,
												'height'=>400
											);
											$image_src = bfi_thumb( $image_path, $bfi_params );

											//echo "<li><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/".RBAGENCY_TEXTDOMAIN."/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&a=t&h=400\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
											echo "<li><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". $image_src."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
										}
							?>
						</ul>
					</div>
					<div id="layout6-carousel" class="flexslider rbcol-12 rbcolumn">
						<ul class="slides">
							<?php
										$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
										$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
										$countImg  = $wpdb->num_rows;
										foreach($resultsImg as $dataImg ){
											$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
											$bfi_params = array(
												'crop'=>true,
												'height'=>144,
												'width'=>144
											);
											$image_src = bfi_thumb( $image_path, $bfi_params );
											//echo "<li><img src=\"". get_bloginfo("url")."/wp-content/plugins/".RBAGENCY_TEXTDOMAIN."/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&a=t&w=144&h=144\" alt=\"". $ProfileContactDisplay ."\" /></li>\n";
											echo "<li><img src=\"". $image_src."\" alt=\"". $ProfileContactDisplay ."\" /></li>\n";
										}
							?>
						</ul>
					</div>
				</div><!-- #portfolio-slide -->

			<?php }
	}

     /**
     * Display: Embed Soundcloud
     *
     */

		public static function rb_agency_embed_soundcloud($url){
			
				$display = "";
				$display .= '<iframe width="100%" height="100" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.trim($url).'&amp;color=0066cc"></iframe>';
				return $display;

		}
	/**
	 * Converts array to http uri
	 */
		public static function generateFilename($filepath,$file){
		    $string = "";
            $pattern = '@\(.*?\)@';
            $name = preg_replace($pattern, '', $file['filename']);
		    $string = $file['filename'];
            $extension = !empty($file['extension']) ? "." . $file['extension'] : "";
            $string .= $extension;
            //$name = $file['filename'];
            $i = 1;
            while (file_exists($filepath.$string)) { 
                $string = $name . " ({$i})".$extension;
                $i++;
            }
		   return $string;
		}
        
        public static function http_build_query($query_data){
            if(is_array($query_data)){
                $query_data = http_build_query(array_filter($query_data));
				return $query_data;
            }
				
		}

}


?>