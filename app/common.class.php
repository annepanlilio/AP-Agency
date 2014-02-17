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
		$rb_agency_options_arr = get_option('rb_agency_options');
			// What is the unit of measurement?
			$rb_agency_value_styleheader = $rb_agency_options_arr['rb_agency_option_styleheader'];
			// Display the custom header html
			echo $rb_agency_value_styleheader;
	}

	/**
     * Show Footer
     *
     * @return echo 
     */

	static function rb_footer() {

		// Now Call Our Header
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');
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
		if (substr($string, -1) == ",") {  $string = substr($string, 0, strlen($string)-1); }
		if (substr($string, 0, 1) == ",") { $string = substr($string, 1, strlen($string)-1); }
		// Just Incase
		$string = str_replace(",,", ",", $string);

		return $string;
	}


	/**
     * Generate random string
     *
     * @param int $length
     * @return string
     */

	static function generate_random_string($length) {

		// Set Characters
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		// Set Dictionary Size
		$size = strlen( $chars );

		// Loop
		for( $i = 0; $i < $length; $i++ ) {
			$string .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $string;

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
	static function format_stripchars($filename) {
		$filename = self::format_whitespace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		return strtolower($filename);
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


}


?>