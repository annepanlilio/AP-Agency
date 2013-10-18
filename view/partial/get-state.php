<?php
$states=array();
$country=get_query_var('country');
$query_get ="SELECT * FROM ".table_agency_data_state." WHERE CountryID='".$country."'";
$result_query_get = $wpdb->get_results($query_get);
echo json_encode($result_query_get);
?>