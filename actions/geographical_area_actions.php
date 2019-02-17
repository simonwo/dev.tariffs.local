<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    pre($_REQUEST);
    #exit();
    $phase = get_formvar("phase");
    if ($phase == "measure_filter_geographical_area_view") {
        get_formvars_measure_filter_geographical_area_view();
    }
    elseif ($phase == "geographical_area_update_description") {
        get_formvars_geographical_area_update_description();
    }
    elseif ($phase == "geographical_area_description_delete") {
        get_formvars_geographical_area_description_delete();
    }
    elseif ($phase == "filter_geography") {
        get_formvars_filter_geography();
    }
    else {
        exit();
    }

    function get_formvars_geographical_area_description_delete() {
        $geographical_area = new geographical_area;
        $geographical_area->geographical_area_description_period_sid = get_querystring("geographical_area_description_period_sid");
        $geographical_area->geographical_area_id = get_querystring("geographical_area_id");
        #h1 ($geographical_area->geographical_area_id);
        #exit();
        $geographical_area->delete_description();
        $url = "/geographical_area_view.php?geographical_area_id=" . $geographical_area->geographical_area_id;
        header("Location: " . $url);            
    }

    function get_formvars_filter_geography() {
        h1 ("ushfiu");
        $geographical_area_text   = get_querystring("geographical_area_text");
        $geography_scope          = get_querystring("geography_scope");
        $url  = "/geographical_areas.php";
        $url .= "?geographical_area_text=" . $geographical_area_text;
        $url .= "&geography_scope=" . $geography_scope;
        header("Location: " . $url);
    }

    function get_formvars_measure_filter_geographical_area_view() {
        $geographical_area_id   = get_querystring("geographical_area_id");
        $measure_scope          = get_querystring("measure_scope");

        $url  = "/geographical_area_view.php";
        $url .= "?geographical_area_id=" . $geographical_area_id;
        $url .= "&measure_scope=" . $measure_scope;

        header("Location: " . $url);
    }

    function get_formvars_geographical_area_update_description() {
        $errors = [];
        $geographical_area_id                       = get_formvar("geographical_area_id");
        $geographical_area_sid                      = get_formvar("geographical_area_sid");
        $geographical_area_description_period_sid   = get_formvar("geographical_area_description_period_sid");
        $validity_start_day                         = get_formvar("validity_start_day",     "geographical_area_", True);
        $validity_start_month                       = get_formvar("validity_start_month",   "geographical_area_", True);
        $validity_start_year                        = get_formvar("validity_start_year",    "geographical_area_", True);
        $description                                = get_formvar("description",            "geographical_area_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
        #h1 ($geographical_area_description_period_sid);
        #exit();

        if ($geographical_area_description_period_sid == -1) {
            # Check on the quota validity start and end dates
            $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
            #h1 ($valid_start_date);
            #exit();
            if ($valid_start_date != 1) {
                array_push($errors, "validity_start_date");
            }
        }

        # Check on description
        if ($description == "") {
            array_push($errors, "description");
        }

        if (count($errors) == 0) {
            $geographical_area = new geographical_area;
            if ($geographical_area_description_period_sid != -1) {
                $ret = $geographical_area->update_description($geographical_area_description_period_sid, $description);
            } else {
                $ret = $geographical_area->insert_description($geographical_area_id, $geographical_area_sid, $validity_start_date, $description);
                if (is_array($ret)) {
                    array_push($errors, "conflict_with_existing");
                }    
            }
        }

        #exit();
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            #h1 ($error_string);
            #exit();
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/geographical_area_add_description.php?action=new&err=1&geographical_area_id=" . $geographical_area_id . "&geographical_area_sid=" . $geographical_area_sid;
        } else {
            $url = "/geographical_area_view.php?geographical_area_id=" . $geographical_area_id . "#definitions";
        }
        header("Location: " . $url);

    }
?>