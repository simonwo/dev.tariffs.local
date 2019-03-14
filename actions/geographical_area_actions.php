<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    pre($_REQUEST);
    #exit();

    $phase = get_formvar("phase");
    if ($phase == "measure_filter_geographical_area_view") {
        get_formvars_measure_filter_geographical_area_view();
    }
    elseif ($phase == "measure_filter_geographical_area_members") {
        get_formvars_measure_filter_geographical_area_members();
    }
    elseif ($phase == "geographical_area_update_description") {
        get_formvars_geographical_area_update_description();
    }
    elseif ($phase == "geographical_area_description_delete") {
        get_formvars_geographical_area_description_delete();
    }
    elseif ($phase == "geographical_area_add_member_form") {
        get_formvars_geographical_area_add_member_form();
    }
    elseif ($phase == "add_member") {
        get_formvars_add_member();
    }
    elseif ($phase == "terminate_membership") {
        get_formvars_terminate_membership();
    }
    elseif ($phase == "delete_membership") {
        get_formvars_delete_membership();
    }
    elseif ($phase == "terminate_membership_form") {
        get_formvars_terminate_membership_form();
    }
    elseif ($phase == "filter_geography") {
        get_formvars_filter_geography();
        }
    else {
        exit();
    }

    function get_formvars_delete_membership() {
        $errors = [];
        $geographical_area_group_sid    = get_formvar("geographical_area_group_sid");
        $geographical_area_group_id     = get_formvar("geographical_area_group_id");
        $geographical_area_id           = get_formvar("geographical_area_id");
        $geographical_area_sid          = get_formvar("geographical_area_sid");
        $geographical_area = new geographical_area;

        $geographical_area->geographical_area_group_sid = $geographical_area_group_sid;
        $geographical_area->geographical_area_group_id  = $geographical_area_group_id;
        $geographical_area->geographical_area_id        = $geographical_area_id;
        $geographical_area->geographical_area_sid       = $geographical_area_sid;
        
        $geographical_area->delete_member();

        $url = "/geographical_area_view.html?geographical_area_id=" . $geographical_area_group_id . "#members1";
        header("Location: " . $url);
    }

    function get_formvars_terminate_membership_form() {
        #exit();
        h1 ("here");
        $errors = [];
        $geographical_area_group_sid    = get_formvar("geographical_area_group_sid");
        $geographical_area_group_id     = get_formvar("geographical_area_group_id");
        $geographical_area_id           = get_formvar("geographical_area_id");
        $geographical_area_sid          = get_formvar("geographical_area_sid");
        $validity_end_day               = get_formvar("validity_end_day",     "geographical_area_", True);
        $validity_end_month             = get_formvar("validity_end_month",   "geographical_area_", True);
        $validity_end_year              = get_formvar("validity_end_year",    "geographical_area_", True);

        $validity_end_date    = to_date_string($validity_end_day, $validity_end_month, $validity_end_year);

        # Check on the validity end dates
        $valid_end_date = checkdate($validity_end_month, $validity_end_day, $validity_end_year);
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        if (count($errors) == 0) {
            $geographical_area = new geographical_area;

            $geographical_area->geographical_area_group_sid = $geographical_area_group_sid;
            $geographical_area->geographical_area_group_id  = $geographical_area_group_id;
            $geographical_area->geographical_area_id        = $geographical_area_id;
            $geographical_area->geographical_area_sid       = $geographical_area_sid;
            $geographical_area->validity_end_date           = $validity_end_date;
            
            $geographical_area->terminate_member();
        }

        #exit();
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            #h1 ($error_string);
            #exit();
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/geographical_area_add_member.html?action=new&err=1&geographical_area_id=" . $geographical_area_id . "&geographical_area_sid=" . $geographical_area_sid;
        } else {
            $url = "/geographical_area_view.html?geographical_area_id=" . $geographical_area_group_id . "#members1";
        }
        header("Location: " . $url);
    }

    function get_formvars_terminate_membership() {
        #exit();
        $geographical_area_group_id = get_querystring("geographical_area_group_id");
        $geographical_area_group_sid = get_querystring("geographical_area_group_sid");
        $geographical_area_id = get_querystring("geographical_area_id");
        $geographical_area_sid = get_querystring("geographical_area_sid");

        $url = "/geographical_area_terminate_member.html?geographical_area_id=" . $geographical_area_id . 
        "&geographical_area_sid=" . $geographical_area_sid . 
        "&geographical_area_group_id=" . $geographical_area_group_id . 
        "&geographical_area_group_sid=" . $geographical_area_group_sid;
        h1 ($url);
        #exit();
        header("Location: " . $url);            

    }


    function get_formvars_geographical_area_add_member_form() {
        $errors = [];
        $geographical_area_group_sid    = get_formvar("geographical_area_group_sid");
        $geographical_area_id           = get_formvar("geographical_area_id");
        $geographical_area_sid          = get_formvar("geographical_area_sid");
        $validity_start_day             = get_formvar("validity_start_day",     "geographical_area_", True);
        $validity_start_month           = get_formvar("validity_start_month",   "geographical_area_", True);
        $validity_start_year            = get_formvar("validity_start_year",    "geographical_area_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);

        # Check on the quota validity start and end dates
        $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on description
        if ($geographical_area_id == "") {
            array_push($errors, "geographical_area_id");
        }

        if (count($errors) == 0) {
            $geographical_area = new geographical_area;
            $ret = $geographical_area->add_member($geographical_area_group_sid, $geographical_area_id, $geographical_area_sid, $validity_start_date);
        }

        #exit();
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            #h1 ($error_string);
            #exit();
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/geographical_area_add_member.html?action=new&err=1&geographical_area_id=" . $geographical_area_id . "&geographical_area_sid=" . $geographical_area_sid;
        } else {
            $url = "/geographical_area_view.html?geographical_area_id=" . $geographical_area_id . "#members1";
        }
        header("Location: " . $url);
    }

    function get_formvars_add_member() {
        $geographical_area_id = get_querystring("geographical_area_id");
        $geographical_area_sid = get_querystring("geographical_area_sid");
        $url = "/geographical_area_add_member.html?geographical_area_id=" . $geographical_area_id . "&geographical_area_sid=" . $geographical_area_sid;
        header("Location: " . $url);            

    }

    function get_formvars_geographical_area_description_delete() {
        $geographical_area = new geographical_area;
        $geographical_area->geographical_area_description_period_sid = get_querystring("geographical_area_description_period_sid");
        $geographical_area->geographical_area_id = get_querystring("geographical_area_id");
        #h1 ($geographical_area->geographical_area_id);
        #exit();
        $geographical_area->delete_description();
        $url = "/geographical_area_view.html?geographical_area_id=" . $geographical_area->geographical_area_id;
        header("Location: " . $url);            
    }

    function get_formvars_filter_geography() {
        h1 ("ushfiu");
        $geographical_area_text   = get_querystring("geographical_area_text");
        $geography_scope          = get_querystring("geography_scope");
        $url  = "/geographical_areas.html";
        $url .= "?geographical_area_text=" . $geographical_area_text;
        $url .= "&geography_scope=" . $geography_scope;
        header("Location: " . $url);
    }

    function get_formvars_measure_filter_geographical_area_members() {
        $geographical_area_id   = get_querystring("geographical_area_id");
        #$geographical_area_sid  = get_querystring("geographical_area_sid");
        $member_currency        = get_querystring("member_currency");

        $url  = "/geographical_area_view.html";
        $url .= "?geographical_area_id=" . $geographical_area_id;
        $url .= "&member_currency=" . $member_currency;
        $url .= "#members1";

        header("Location: " . $url);
    }

    function get_formvars_measure_filter_geographical_area_view() {
        $geographical_area_id   = get_querystring("geographical_area_id");
        $measure_scope          = get_querystring("measure_scope");
        $sort                   = get_querystring("sort");
        $currency               = get_querystring("currency");

        $url  = "/geographical_area_view.html";
        $url .= "?geographical_area_id=" . $geographical_area_id;
        $url .= "&measure_scope=" . $measure_scope;
        $url .= "&sort=" . $sort;
        $url .= "&currency=" . $currency;

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
            $url = "/geographical_area_add_description.html?action=new&err=1&geographical_area_id=" . $geographical_area_id . "&geographical_area_sid=" . $geographical_area_sid;
        } else {
            $url = "/geographical_area_view.html?geographical_area_id=" . $geographical_area_id . "#definitions";
        }
        header("Location: " . $url);

    }
?>