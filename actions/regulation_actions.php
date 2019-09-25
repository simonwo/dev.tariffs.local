<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	$phase = get_formvar("phase");
	if ($phase == "regulation_create") {
		get_formvars_regulation_create();
	}
	elseif ($phase == "regulation_edit") {
		get_formvars_regulation_edit();
	}
	elseif ($phase == "filter_regulations") {
		get_formvars_filter_regulations();
	}
	
	function get_formvars_filter_regulations() {
		$regulation_group_id	= get_querystring("regulation_group_id");
		$regulation_scope		= get_querystring("regulation_scope");
		$regulation_text		= get_querystring("regulation_text");

		$url = "/regulations.html?";
		if ($regulation_group_id != "") {
			$url .= "&regulation_group_id=" . $regulation_group_id;
		}
		if ($regulation_scope != "") {
			$url .= "&regulation_scope=" . $regulation_scope;
		}
		if ($regulation_text != "") {
			$url .= "&regulation_text=" . $regulation_text;
		}
		header("Location: " . $url);
	}
	
	function get_formvars_regulation_create() {
		global $conn;
		$errors = array();
		pre($_REQUEST);

		$base_regulation_id         = get_formvar("base_regulation_id",      	"base_regulation_", True);
		$information_text_name      = get_formvar("information_text_name",      "base_regulation_", True);
		$information_text_primary   = get_formvar("information_text_primary",   "base_regulation_", True);
		$information_text_url       = get_formvar("information_text_url",       "base_regulation_", True);
		$regulation_group_id        = get_formvar("regulation_group_id",        "base_regulation_", True);
		$validity_start_day         = get_formvar("validity_start_day",         "base_regulation_", True);
		$validity_start_month       = get_formvar("validity_start_month",       "base_regulation_", True);
		$validity_start_year        = get_formvar("validity_start_year",        "base_regulation_", True);

		$validity_start_date        = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
		#exit();
		# Check on base_regulation_id
		$list = array("P", "U", "S", "X", "N", "M", "S", "A");
		$first_char = substr($base_regulation_id, 0, 1);
		if ((strlen($base_regulation_id) != 8) || (!(in_array($first_char, $list)))) {
			array_push($errors, "base_regulation_id");
		}
		
		# Check on the quota validity start and end dates
		$valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
		if ($valid_start_date != 1) {
			array_push($errors, "validity_start_date");
		}

		# Check on regulation_group_id
		if ($regulation_group_id == "") {
			array_push($errors, "regulation_group_id");
		}
		
		
		$information_text = $information_text_name . "|" . $information_text_url . "|" . $information_text_primary;

		if (count($errors) == 0) {
			$base_regulation = new base_regulation;
			$ret = $base_regulation->insert($base_regulation_id, $information_text, $validity_start_date, $regulation_group_id);
			if (is_array($ret)) {
				array_push($errors, "regulation_already_exists");
			}    
		}
		/*foreach ($errors as $error) {
			h1 ($error);
		}
		exit();*/
		
		if (count($errors) > 0) {
			$error_string = serialize($errors);
			#h1 ($error_string);
			#exit();
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
			$url = "/regulation_create_edit.html?action=new&err=1";
			#h1 ("fail again");
		} else {
			#h1 ("succeed");
			$url = "/regulations.html";
		}
		header("Location: " . $url);
	}

	function get_formvars_regulation_edit() {
		global $conn;
		$errors = array();
		pre($_REQUEST);
		#exit();

		$base_regulation = new base_regulation;
		$base_regulation->base_regulation_id         = get_formvar("base_regulation_id",      	 "base_regulation_", True);
		$base_regulation->information_text_name      = get_formvar("information_text_name",      "base_regulation_", True);
		$base_regulation->information_text_primary   = get_formvar("information_text_primary",   "base_regulation_", True);
		$base_regulation->information_text_url       = get_formvar("information_text_url",       "base_regulation_", True);
		$base_regulation->regulation_group_id        = get_formvar("regulation_group_id",        "base_regulation_", True);
		$base_regulation->validity_start_day         = get_formvar("validity_start_day",         "base_regulation_", True);
		$base_regulation->validity_start_month       = get_formvar("validity_start_month",       "base_regulation_", True);
		$base_regulation->validity_start_year        = get_formvar("validity_start_year",        "base_regulation_", True);

		$base_regulation->validity_start_date        = to_date_string($base_regulation->validity_start_day, $base_regulation->validity_start_month, $base_regulation->validity_start_year);
		#exit();
		# Check on base_regulation_id
		$list = array("P", "U", "S", "X", "N", "M", "S", "A");
		$first_char = substr($base_regulation->base_regulation_id, 0, 1);
		if ((strlen($base_regulation->base_regulation_id) != 8) || (!(in_array($first_char, $list)))) {
			array_push($errors, "base_regulation_id");
		}
		
		# Check on the quota validity start and end dates
		$valid_start_date = checkdate($base_regulation->validity_start_month, $base_regulation->validity_start_day, $base_regulation->validity_start_year);
		if ($valid_start_date != 1) {
			array_push($errors, "validity_start_date");
		}

		# Check on regulation_group_id
		if ($base_regulation->regulation_group_id == "") {
			array_push($errors, "regulation_group_id");
		}

		$base_regulation->information_text = $base_regulation->information_text_name . "|" . $base_regulation->information_text_url . "|" . $base_regulation->information_text_primary;

		if (count($errors) == 0) {
			$base_regulation->update();
		}
		/*foreach ($errors as $error) {
			h1 ($error);
		}
		exit();*/
		
		if (count($errors) > 0) {
			$error_string = serialize($errors);
			#h1 ($error_string);
			#exit();
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
			$url = "/regulation_create_edit.html?action=edit&base_regulation_id=" . $base_regulation_id . "&err=1";
			#h1 ("fail again");
		} else {
			#h1 ("succeed");
			$url = "/regulations.html";
		}
		header("Location: " . $url);
	}

?>