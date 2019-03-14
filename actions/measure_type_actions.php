<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    pre($_REQUEST);
    #exit();
    $phase = get_formvar("phase");

    if ($phase == "measure_type_create") {
        get_formvars_measure_type_create_edit("create");
    }
    if ($phase == "measure_type_edit") {
        get_formvars_measure_type_create_edit("edit");
    }
    elseif ($phase == "show_edit_measure_type_form") {
        get_formvars_show_edit_measure_type_form();
    }
    elseif ($phase == "show_create_measure_type_form") {
        get_formvars_show_create_measure_type_form();
    }
    elseif ($phase == "filter_measure_types") {
        get_formvars_filter_measure_types();
    }
    else {
        exit();
    }

    function get_formvars_measure_type_create_edit($create_edit) {
        $errors = array();
        $measure_type = new measure_type;
        $measure_type->measure_type_id                      = get_formvar("measure_type_id", "", True);
        $measure_type->description                          = get_formvar("description",                        "measure_type_", True);
        $measure_type->validity_start_day                   = get_formvar("validity_start_day",                 "measure_type_", True);
        $measure_type->validity_start_month                 = get_formvar("validity_start_month",               "measure_type_", True);
        $measure_type->validity_start_year                  = get_formvar("validity_start_year",                "measure_type_", True);
        $measure_type->validity_end_day                     = get_formvar("validity_end_day",                   "measure_type_", True);
        $measure_type->validity_end_month                   = get_formvar("validity_end_month",                 "measure_type_", True);
        $measure_type->validity_end_year                    = get_formvar("validity_end_year",                  "measure_type_", True);
        $measure_type->trade_movement_code                  = get_formvar("trade_movement_code",                "measure_type_", True);
        $measure_type->priority_code                        = get_formvar("priority_code",                      "measure_type_", True);
        $measure_type->measure_component_applicable_code    = get_formvar("measure_component_applicable_code",  "measure_type_", True);
        $measure_type->origin_dest_code                     = get_formvar("origin_dest_code",                   "measure_type_", True);
        $measure_type->order_number_capture_code            = get_formvar("order_number_capture_code",          "measure_type_", True);
        $measure_type->measure_type_series_id               = get_formvar("measure_type_series_id",             "measure_type_", True);
        $measure_type->measure_explosion_level              = get_formvar("measure_explosion_level",            "measure_type_", True);

        $measure_type->set_dates();

        # Check on the measure type id
        if (strlen($measure_type->measure_type_id) != 3) {
            array_push($errors, "measure_type_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($create_edit == "create") {
            if ($measure_type->exists()) {
                array_push($errors, "measure_type_exists");
            }
        }

        # Check on the description
        if ($measure_type->description == "") {
            array_push($errors, "description");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($measure_type->validity_start_month, $measure_type->validity_start_day, $measure_type->validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($measure_type->validity_end_day == "") && ($measure_type->validity_end_month == "") && ($measure_type->validity_end_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($measure_type->validity_end_month, $measure_type->validity_end_day, $measure_type->validity_end_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        # Check business rules
        # If we are setting an end date on a measure type, there must be no measures of this type that extend beyond
        # the newly-set end date
        $measure_type->set_dates();
        if ($measure_type->validity_end_date != Null) {
            if ($measure_type->business_rule_mt3() == false) {
                array_push($errors, "validity_end_date_mt3");
            }
        }

        # Check on the trade movement code
        if ($measure_type->trade_movement_code == "") {
            array_push($errors, "trade_movement_code");
        }
        
        # Check on the priority code
        if ($measure_type->priority_code == "") {
            array_push($errors, "priority_code");
        }
        
        # Check on the measure_component_applicable_code
        if ($measure_type->measure_component_applicable_code == "") {
            array_push($errors, "measure_component_applicable_code");
        }
        
        # Check on the origin_dest_code
        if ($measure_type->origin_dest_code == "") {
            array_push($errors, "origin_dest_code");
        }

        # Check on the order_number_capture_code
        if ($measure_type->order_number_capture_code == "") {
            array_push($errors, "order_number_capture_code");
        }
        
        # Check on the measure type series ID
        if ($measure_type->measure_type_series_id == "") {
            array_push($errors, "measure_type_series_id");
        }
        
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/measure_type_create_edit.html?phase=" . $create_edit . "&action=new&err=1&measure_type_id=" . $measure_type->measure_type_id;
        } else {
            if ($create_edit == "create") {
                // Do create scripts
                $measure_type->create();
            } else {
                // Do edit scripts
                $measure_type->update();
            }
            $url = "/measure_types.html";
        }
        header("Location: " . $url);

    }

    function get_formvars_show_edit_measure_type_form() {
        $measure_type_id = get_querystring("measure_type_id");
        $url = "/measure_type_create_edit.html?phase=edit&measure_type_id=" . $measure_type_id;
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_show_create_measure_type_form() {
        $url = "/measure_type_create_edit.html?phase=create";
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_filter_measure_types() {
        $measure_type_scope          = get_querystring("measure_type_scope");
        $url  = "/measure_types.html";
        $url .= "?measure_type_scope=" . $measure_type_scope;
        #h1 ($url);
        #exit();
        header("Location: " . $url);
    }

	function set_dates(){
		if (($this->validity_start_day == "") || ($this->validity_start_month == "") || ($this->validity_start_year == "")) {
			$this->validity_start_date = Null;
		} else {
			$this->validity_start_date	= to_date_string($this->validity_start_day,	$this->validity_start_month, $this->validity_start_year);
		}
		
		if (($this->validity_end_day == "") || ($this->validity_end_month == "") || ($this->validity_end_year == "")) {
			$this->validity_end_date = Null;
		} else {
			$this->validity_end_date	= to_date_string($this->validity_end_day, $this->validity_end_month, $this->validity_end_year);
		}
	}

?>