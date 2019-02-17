<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	$phase = get_formvar("phase");
	if ($phase == "quota_order_number_create_edit") {
		get_formvars_quota_order_number_create_edit();
	}
	elseif ($phase == "quota_order_number_add_origin") {
		get_formvars_quota_order_number_add_origin();
	}

	function get_formvars_quota_order_number_create_edit() {
		global $conn;
		$errors = array();
		pre($_REQUEST);
		$quota_order_number_id              = get_formvar("quota_order_number_id");
		$quota_order_number_sid             = get_formvar("quota_order_number_sid");
		$validity_start_day                 = get_formvar("validity_start_day",                 "quota_definition_", True);
		$validity_start_month               = get_formvar("validity_start_month",               "quota_definition_", True);
		$validity_start_year                = get_formvar("validity_start_year",                "quota_definition_", True);

		$validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);

		# Check on quota order number ID
		if ((strlen($quota_order_number_id) != 6) || (substr($quota_order_number_id, 0, 2) != "09")) {
			array_push($errors, "quota_order_number_id");
		}

		# Check on the quota validity start and end dates
		$valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
		if ($valid_start_date != 1) {
			array_push($errors, "validity_start_date");
		}

		if (count($errors) == 0) {
			h1 ("No errors");
			#exit();
			$quota_order_number = new quota_order_number;
			$ret = $quota_order_number->insert($quota_order_number_id, $validity_start_date);
			if (is_array($ret)) {
				array_push($errors, "conflict_with_existing");
			}    
		}

		foreach ($errors as $error) {
			h1 ($error);
		}
		#exit();
		
		if (count($errors) > 0) {
			$error_string = serialize($errors);
			#h1 ($error_string);
			#exit();
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
		  $url = "/quota_order_number_create_edit.php?action=new&err=1&quota_order_number_id=" . $quota_order_number_id;
		} else {
			$url = "/quota_order_number_view.php?quota_order_number_id=" . $quota_order_number_id . "#definitions";
		}
		header("Location: " . $url);
	}


	function get_formvars_quota_order_number_add_origin() {
		global $conn;
		$errors = array();
		pre($_REQUEST);
		#exit();
		$quota_order_number_id              = get_formvar("quota_order_number_id");
		$quota_order_number_sid             = get_formvar("quota_order_number_sid");
		$geographical_area_id             	= get_formvar("geographical_area_id", "", True);
		if ($geographical_area_id != "1011") {
			$geographical_area_id     	= get_formvar("geographical_area_id_group");
			if (strlen($geographical_area_id) != "4") {
				$geographical_area_id     	= get_formvar("geographical_area_id_country");
			}
		}
		setcookie("geographical_area_id", $geographical_area_id, time() + (86400 * 30), "/");

		$validity_start_day                 = get_formvar("validity_start_day",     "quota_order_number_origin_", True);
		$validity_start_month               = get_formvar("validity_start_month",   "quota_order_number_origin_", True);
		$validity_start_year                = get_formvar("validity_start_year",    "quota_order_number_origin_", True);

		$validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);

		# Check on the quota validity start and end dates
		$valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
		if ($valid_start_date != 1) {
			array_push($errors, "validity_start_date");
		}

		# Check on geographical_area_id
		h1 ($geographical_area_id);
		if ($geographical_area_id == "") {
			array_push($errors, "geographical_area_id");
		}
		exit();

		/*
		if (count($errors) == 0) {
			h1 ("No errors");
			#exit();
			$quota_order_number = new quota_order_number;
			$ret = $quota_order_number->insert($quota_order_number_id, $validity_start_date);
			if (is_array($ret)) {
				array_push($errors, "conflict_with_existing");
			}    
		}*/

		foreach ($errors as $error) {
			h1 ($error);
		}
		#exit();
		
		if (count($errors) > 0) {
			$error_string = serialize($errors);
			#h1 ($error_string);
			#exit();
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
		  $url = "/quota_order_number_add_origin.php?action=new&err=1&quota_order_number_id=" . $quota_order_number_id . "&quota_order_number_sid=" . $quota_order_number_sid;
		} else {
			$url = "/quota_order_number_view.php?quota_order_number_id=" . $quota_order_number_id . "#definitions";
		}
		header("Location: " . $url);
	}
?>