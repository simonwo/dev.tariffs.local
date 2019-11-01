<?php
	require (dirname(__FILE__) . "../../includes/db.html");
	pre($_REQUEST);
	#exit();
	$phase = get_formvar("phase");
	if ($phase == "measure_filter_geographical_area_view") {
		get_formvars_measure_filter_geographical_area_view();
	} elseif ($phase == "monetary_exchange_rate_create") {
		monetary_exchange_rate_create();
	} elseif ($phase == "monetary_exchange_rate_edit") {
		monetary_exchange_rate_edit();
	} else {
		get_formvars_phase1();
	}

	function monetary_exchange_rate_create() {
		$errors = array();
		$monetary_exchange_rate = new monetary_exchange_rate;
		$monetary_exchange_rate->validity_start_day     = get_formvar("validity_start_day",                 "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_start_month	= get_formvar("validity_start_month",               "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_start_year    = get_formvar("validity_start_year",                "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_day       = get_formvar("validity_end_day",                   "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_month     = get_formvar("validity_end_month",                 "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_year      = get_formvar("validity_end_year",                  "monetary_exchange_rate_", True);
		$monetary_exchange_rate->exchange_rate  	    = get_formvar("exchange_rate",                  "monetary_exchange_rate_", True);

		$monetary_exchange_rate->set_dates();

        # Check on the validity start date
        $valid_start_date = checkdate($monetary_exchange_rate->validity_start_month, $monetary_exchange_rate->validity_start_day, $monetary_exchange_rate->validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($monetary_exchange_rate->validity_end_day == "") && ($monetary_exchange_rate->validity_end_month == "") && ($monetary_exchange_rate->validity_end_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($monetary_exchange_rate->validity_end_month, $monetary_exchange_rate->validity_end_day, $monetary_exchange_rate->validity_end_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

		# Check on the measure type id
		if (strlen($monetary_exchange_rate->exchange_rate) == 0) {
			array_push($errors, "exchange_rate");
		}
		
		#h1 (count($errors));
		#exit();

		if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/monetary_exchange_rate_create_edit.html?phase=create&action=new&err=1";
        } else {
			$monetary_exchange_rate->create();
            $url = "/monetary_exchange_rates.html";
        }
        header("Location: " . $url);

	}

	function monetary_exchange_rate_edit() {
		$errors = array();
		$monetary_exchange_rate = new monetary_exchange_rate;
		$monetary_exchange_rate->monetary_exchange_period_sid	= get_formvar("monetary_exchange_period_sid", "", True);
		$monetary_exchange_rate->validity_start_day     		= get_formvar("validity_start_day",           "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_start_month			= get_formvar("validity_start_month",         "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_start_year    		= get_formvar("validity_start_year",          "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_day       		= get_formvar("validity_end_day",             "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_month     		= get_formvar("validity_end_month",           "monetary_exchange_rate_", True);
		$monetary_exchange_rate->validity_end_year      		= get_formvar("validity_end_year",            "monetary_exchange_rate_", True);
		$monetary_exchange_rate->exchange_rate  	    		= get_formvar("exchange_rate",                "", True);

		$monetary_exchange_rate->set_dates();

        # Check on the validity start date
        $valid_start_date = checkdate($monetary_exchange_rate->validity_start_month, $monetary_exchange_rate->validity_start_day, $monetary_exchange_rate->validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($monetary_exchange_rate->validity_end_day == "") && ($monetary_exchange_rate->validity_end_month == "") && ($monetary_exchange_rate->validity_end_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($monetary_exchange_rate->validity_end_month, $monetary_exchange_rate->validity_end_day, $monetary_exchange_rate->validity_end_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

		# Check on the measure type id
		if (strlen($monetary_exchange_rate->exchange_rate) == 0) {
			array_push($errors, "exchange_rate");
		}
		
		if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/monetary_exchange_rate_create_edit.html?phase=edit&err=1&monetary_exchange_period_sid=" . $monetary_exchange_rate->monetary_exchange_period_sid;
        } else {
			$monetary_exchange_rate->update();
			#exit();
			$url = "/monetary_exchange_rates.html";
        }
        header("Location: " . $url);

	}

	function get_formvars_phase1() {
		$geographical_area_text = get_querystring("geographical_area_text");

		$url  = "/geographical_areas.html";
		$url .= "?geographical_area_text=" . $geographical_area_text;

		header("Location: " . $url);

	}
	function get_formvars_measure_filter_geographical_area_view() {
		$geographical_area_id   = get_querystring("geographical_area_id");
		$measure_scope          = get_querystring("measure_scope");

		$url  = "/geographical_area_view.html";
		$url .= "?geographical_area_id=" . $geographical_area_id;
		$url .= "&measure_scope=" . $measure_scope;

		header("Location: " . $url);

	}


?>