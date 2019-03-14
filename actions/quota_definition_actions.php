<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    if ($phase == "quota_definition_create_edit") {
        get_formvars_quota_definition_create_edit();
    }

    function get_formvars_quota_definition_create_edit() {
        global $conn;
        $errors = array();
        pre($_REQUEST);
        $quota_order_number_id              = get_formvar("quota_order_number_id");
        $quota_order_number_sid             = get_formvar("quota_order_number_sid");
        $validity_start_day                 = get_formvar("validity_start_day",                 "quota_definition_", True);
        $validity_start_month               = get_formvar("validity_start_month",               "quota_definition_", True);
        $validity_start_year                = get_formvar("validity_start_year",                "quota_definition_", True);
        $validity_end_day                   = get_formvar("validity_end_day",                   "quota_definition_", True);
        $validity_end_month                 = get_formvar("validity_end_month",                 "quota_definition_", True);
        $validity_end_year                  = get_formvar("validity_end_year",                  "quota_definition_", True);
        $initial_volume                     = get_formvar("initial_volume",                     "quota_definition_", True);
        $measurement_unit_code              = get_formvar("measurement_unit_code",              "quota_definition_", True);
        $measurement_unit_qualifier_code    = get_formvar("measurement_unit_qualifier_code",    "quota_definition_", True);
        $maximum_precision                  = get_formvar("maximum_precision",                  "quota_definition_", True);
        $critical_state                     = get_formvar("critical_state",                     "quota_definition_", True);
        $critical_threshold                 = get_formvar("critical_threshold",                 "quota_definition_", True);
        $monetary_unit_code                 = get_formvar("monetary_unit_code",                 "quota_definition_", True);
        $description                        = get_formvar("description",                        "quota_definition_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
        $validity_end_date      = to_date_string($validity_end_day, $validity_end_month, $validity_end_year);

        # Check on the quota validity start and end dates
        $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
        $valid_end_date = checkdate($validity_end_month, $validity_end_day, $validity_end_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        # Check on initial volume
        if ($initial_volume == "") {
            array_push($errors, "initial_volume");
        }
        
        # Check on measurement_unit_code
        if ($measurement_unit_code == "") {
            array_push($errors, "measurement_unit_code");
        }
        
        # Check on maximum_precision
        if ($maximum_precision == "") {
            array_push($errors, "maximum_precision");
        }
        
        # Check on critical_state
        if ($critical_state == "") {
            array_push($errors, "critical_state");
        }
        
        # Check on critical_threshold
        if ($critical_threshold == "") {
            array_push($errors, "critical_threshold");
        }
        
        if ($valid_start_date && $valid_end_date) {
            $validity_start_date2    = to_date($validity_start_day, $validity_start_month, $validity_start_year);
            $validity_end_date2      = to_date($validity_end_day, $validity_end_month, $validity_end_year);
            $diff = date_diff($validity_start_date2, $validity_end_date2);
            #print ($diff->format('%R%a'));
            if ($diff->format('%R%a') <= 0) {
                array_push($errors, "validity_end_date_before_start_date");
            }

            if (count($errors) == 0) {
                $quota_definition = new quota_definition;
                $ret = $quota_definition->insert($quota_order_number_id, $validity_start_date, $validity_end_date,
                    $quota_order_number_sid, $initial_volume, $measurement_unit_code, $maximum_precision,
                    $critical_state, $critical_threshold, $monetary_unit_code, $measurement_unit_qualifier_code, $description);
                if (is_array($ret)) {
                    array_push($errors, "conflict_with_existing");
                }    
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
          $url = "/quota_definition_create_edit.html?action=new&err=1&quota_order_number_id=" . $quota_order_number_id;
        } else {
            $url = "/quota_order_number_view.html?quota_order_number_id=" . $quota_order_number_id . "#definitions";
        }
        header("Location: " . $url);
    }

?>