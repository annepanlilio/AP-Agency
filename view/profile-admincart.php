<?php
echo $rb_header = RBAgency_Common::rb_header();
global $wpdb;

	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];

	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo " <div id=\"profile-private\">\n";

		// Get Profile
		$SearchMuxHash = get_query_var('target');
		if (isset($SearchMuxHash)) {

			// Get Identifier
			$_SESSION['SearchMuxHash'] = $SearchMuxHash;
			//$wpdb->query("ALTER TABLE  ". table_agency_searchsaved." ADD INDEX (`SearchProfileID`)");
			//$wpdb->query("ALTER TABLE  ". table_agency_searchsaved_mux." ADD INDEX (`SearchID`)");
			// Get Casting Cart by Identifier
			$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash, searchsent.SearchMuxCustomThumbnail FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
			$data = $wpdb->get_row($query,ARRAY_A) or die ( __("Error, query failed", RBAGENCY_TEXTDOMAIN ));
			$count =  $wpdb->num_rows;
			/*$wpdb->show_errors();
			$wpdb->print_error();
			 */
			// Get Casting Cart ID

			$search_profile_arr = explode(",",$data['SearchProfileID']);
			$searchProfileArr = array();
			$searchCastingArr = array();
			foreach($search_profile_arr as $k=>$v){
				if(strpos($v, '@')>-1){
					$searchCastingArr[] = "'".$v."'";
				}else{
					$searchProfileArr[] =$v;
				}
			}
			$profile_list = implode(",",array_unique($searchProfileArr));
			$castingcart_id = $profile_list;

			$arr = (array)unserialize($data["SearchMuxCustomThumbnail"]);
			$_SESSION["profilephotos_view"] = is_array($arr[0])?array_filter(array_unique($arr[0])):"";

			$search_array = array("perpage" => 9999, "include" => $castingcart_id);
			//$search_sql_query = RBAgency_Profile::search_generate_sqlwhere(array_filter(array_unique($search_array)));
			$search_sql_query['standard'] = " profile.ProfileID IN(".(!empty($castingcart_id)?$castingcart_id:0).") ";

			$implodedCastingEmail = "(".implode(",",$searchCastingArr).")";

			$sql = "SELECT t.*,c.CastingUserLinked,c.CastingContactNameFirst,c.CastingContactNameLast,c.CastingContactDisplay,c.CastingContactCompany FROM ".$wpdb->prefix."agency_casting as c INNER JOIN ".$wpdb->prefix."agency_casting_types as t ON t.CastingTypeID = c.CastingType WHERE c.CastingContactEmail IN $implodedCastingEmail";
			$resultsCasting = $wpdb->get_results($sql, ARRAY_A);

			// Process Form Submission
			$search_results = RBAgency_Profile::search_results($search_sql_query, 3);

			if(!empty($resultsCasting) && $search_results == 'No Profiles Found'){

			}elseif(empty($resultsCasting) && $search_results != 'No Profiles Found'){
				echo $search_results;
			}elseif(!empty($resultsCasting) && $search_results != 'No Profiles Found'){
				echo $search_results;
			}



			$castingInfo = "";
			foreach($resultsCasting as $result){
				$displayName = "";
				$resultCastingID = $result["CastingUserLinked"];
				$resultCastingContactNameFirst = $result["CastingContactNameFirst"];
				$resultCastingContactNameLast = $result["CastingContactNameLast"];
				$resultCastingContactDisplay = $result["CastingContactDisplay"];
				$resultCastingContactCompany = $result["CastingContactCompany"];

				if ($rb_agency_option_profilenaming == 0) {
					$displayName = $resultCastingContactNameFirst . " ". $resultCastingContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$displayName = $resultCastingContactNameFirst . " ". substr($resultCastingContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
					$displayName = $resultCastingContactDisplay;
				} elseif ($rb_agency_option_profilenaming == 3) {
					$displayName = "ID-". $resultCastingID;
				} elseif ($rb_agency_option_profilenaming == 4) {
					$displayName = $resultCastingContactNameFirst;
				} elseif ($rb_agency_option_profilenaming == 5) {
					$displayName = $resultCastingContactNameLast;
				}

				$castingInfo .= "  <div class=\"casting_agent_".$resultCastingID."\" style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
				$castingInfo .= "    <div style=\"text-align: center; \"><h3 style=\"text-align:left;\">". $displayName  . "</h3></div>";
				$castingInfo .= "<img src=\"".site_url()."/wp-content/plugins/rb-agency/assets/demo-data/Placeholder.jpg\" style=\"width:100%\"/>";
				$castingInfo .= "<p>Company:&nbsp;".$resultCastingContactCompany."</p><p>Casting Type:&nbsp;".$result["CastingTypeTitle"]."</p>";
				$castingInfo .= "</div>";
			}
			echo $castingInfo;
			// echo  $formatted = RBAgency_Profile::search_formatted($search_array);

		}
		if (empty($SearchMuxHash) || ($count == 0)) {
			echo "<strong>". __("No search results found.  Please check link again.", RBAGENCY_TEXTDOMAIN) ."</strong>";
		}

		echo "  <div style=\"clear: both;\"></div>";
		echo " </div>\n";
		echo "  </div>\n";
		echo "</div>\n";

//get_sidebar(); 
echo $rb_footer = RBAgency_Common::rb_footer(); 
?>