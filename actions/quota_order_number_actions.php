<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	//pre($_REQUEST);
	//die();
	$phase = get_formvar("phase");
	if ($phase == "quota_order_number_create") {
		get_formvars_quota_order_number_create();
	}
	elseif ($phase == "quota_order_number_edit") {
		get_formvars_quota_order_number_edit();
	}
	elseif ($phase == "quota_order_number_add_origin") {
		get_formvars_quota_order_number_add_origin();
	}

	function get_formvars_quota_order_number_create() {
		global $conn;
		$errors = array();
		$quota_order_number_id	= get_formvar("quota_order_number_id",	"quota_order_number_", True);
		$validity_start_day		= get_formvar("validity_start_day",     "quota_order_number_", True);
		$validity_start_month	= get_formvar("validity_start_month",   "quota_order_number_", True);
		$validity_start_year	= get_formvar("validity_start_year",    "quota_order_number_", True);
		$origin_quota			= get_formvar("origin_quota",           "quota_order_number_", True);
		$description			= trim(get_formvar("description",       "quota_order_number_", True));
		$quota_scope			= get_formvar("quota_scope",            "quota_order_number_", True);
		$quota_staging			= get_formvar("quota_staging",          "quota_order_number_", True);

		# Check on quota order number ID
		if ((strlen($quota_order_number_id) != 6) || (substr($quota_order_number_id, 0, 2) != "09")) {
			array_push($errors, "quota_order_number_id");
		}

		# Start date
		if (($validity_start_day != "") and ($validity_start_month != "") and ($validity_start_year != "")) {
			$valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
			if ($valid_start_date) {
				$validity_start_date = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
			} else {
				array_push($errors, "validity_start_date");
			}
		} else {
			array_push($errors, "validity_start_date");
		}
		$validity_end_date = Null;

		# Check description
		if ($description == "") {
			array_push($errors, "description");
		}

		if (count($errors) == 0) {
			$quota_order_number = new quota_order_number;
			$quota_order_number->quota_order_number_id	= $quota_order_number_id;
			$quota_order_number->validity_start_date	= $validity_start_date;
			$quota_order_number->validity_end_date		= $validity_end_date;
			$quota_order_number->quota_scope			= $quota_scope;
			$quota_order_number->quota_staging			= $quota_staging;
			$quota_order_number->origin_quota			= $origin_quota;
			$quota_order_number->description			= $description;

			$ret = $quota_order_number->insert();
			if (is_array($ret)) {
				array_push($errors, "conflict_with_existing");
			}    
		}

		if (count($errors) > 0) {
			$error_string = serialize($errors);
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
		  $url = "/quota_order_number_create_edit.html?action=new&err=1&quota_order_number_id=" . $quota_order_number_id;
		} else {
			//$url = "/quota_order_number_view.html?quota_order_number_id=" . $quota_order_number_id . "#definitions";
			$url = "/quota_order_number_confirm.html?quota_order_number_id=" . $quota_order_number_id . "#definitions";
		}
		header("Location: " . $url);
	}

	function get_formvars_quota_order_number_edit() {
		global $conn;
		$errors = array();
		$quota_order_number_sid	= get_formvar("quota_order_number_sid",	"quota_order_number_", True);
		$quota_order_number_id	= get_formvar("quota_order_number_id",	"quota_order_number_", True);
		$validity_start_day		= get_formvar("validity_start_day",     "quota_order_number_", True);
		$validity_start_month	= get_formvar("validity_start_month",   "quota_order_number_", True);
		$validity_start_year	= get_formvar("validity_start_year",    "quota_order_number_", True);
		$validity_end_day		= get_formvar("validity_end_day",     	"quota_order_number_", True);
		$validity_end_month		= get_formvar("validity_end_month",   	"quota_order_number_", True);
		$validity_end_year		= get_formvar("validity_end_year",    	"quota_order_number_", True);
		$origin_quota			= get_formvar("origin_quota",           "quota_order_number_", True);
		$description			= trim(get_formvar("description",       "quota_order_number_", True));
		$quota_scope			= get_formvar("quota_scope",            "quota_order_number_", True);
		$quota_staging			= get_formvar("quota_staging",          "quota_order_number_", True);


		# Check on quota order number ID
		if ((strlen($quota_order_number_id) != 6) || (substr($quota_order_number_id, 0, 2) != "09")) {
			array_push($errors, "quota_order_number_id");
		}


		# Start date
		if (($validity_start_day != "") and ($validity_start_month != "") and ($validity_start_year != "")) {
			$valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
			if ($valid_start_date) {
				$validity_start_date = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
			} else {
				array_push($errors, "validity_start_date");
			}
		} else {
			array_push($errors, "validity_start_date");
		}


		# End date
		if (($validity_end_day != "") or ($validity_end_month != "") or ($validity_end_year != "")) {
			$valid_end_date = checkdate($validity_end_month, $validity_end_day, $validity_end_year);
			if ($valid_end_date) {
				$validity_end_date = to_date_string($validity_end_day, $validity_end_month, $validity_end_year);
			} else {
				array_push($errors, "validity_end_date");
			}
		}


		if ($origin_quota == Null) {
			array_push($errors, "origin_quota");
		} elseif ($origin_quota == Null) {
			array_push($errors, "origin_quota");
		}

		
		# Check description
		if ($description == "") {
			array_push($errors, "description");
		}

		if (count($errors) == 0) {
			$quota_order_number = new quota_order_number;
			$quota_order_number->quota_order_number_sid	= $quota_order_number_sid;
			$quota_order_number->quota_order_number_id	= $quota_order_number_id;
			$quota_order_number->validity_start_date	= $validity_start_date;
			$quota_order_number->validity_end_date		= $validity_end_date;
			$quota_order_number->quota_scope			= $quota_scope;
			$quota_order_number->quota_staging			= $quota_staging;
			$quota_order_number->origin_quota			= $origin_quota;
			$quota_order_number->description			= $description;

			$ret = $quota_order_number->update();
			if (is_array($ret)) {
				array_push($errors, "conflict_with_existing");
			}    
		}

		if (count($errors) > 0) {
			$error_string = serialize($errors);
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
		  $url = "/quota_order_number_create_edit.html?action=new&err=1&quota_order_number_id=" . $quota_order_number_id;
		} else {
			$url = "/quota_order_number_view.html?quota_order_number_id=" . $quota_order_number_id . "#details";
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

		// Actual code needs to go here
		$url = "/quota_order_number_origin_confirm.html?quota_order_number_id=" . $quota_order_number_id . "&geographical_area_id=" . $geographical_area_id;
		header("Location: " . $url);

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
		  $url = "/quota_order_number_add_origin.html?action=new&err=1&quota_order_number_id=" . $quota_order_number_id . "&quota_order_number_sid=" . $quota_order_number_sid;
		} else {
			$url = "/quota_order_number_vie.htmlp?quota_order_number_id=" . $quota_order_number_id . "#definitions";
		}
		header("Location: " . $url);
	}
?>