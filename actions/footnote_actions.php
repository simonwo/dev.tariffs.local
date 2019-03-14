<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    pre($_REQUEST);
    #exit();
    $phase = get_formvar("phase");

    if ($phase == "footnote_create") {
        get_formvars_footnote_create_edit("create");
    }
    elseif ($phase == "footnote_update_description") {
        get_formvars_footnote_update_description();
    }
    elseif ($phase == "footnote_description_delete") {
        get_formvars_footnote_description_delete();
    }
    elseif ($phase == "footnote_edit") {
        get_formvars_footnote_create_edit("edit");
    }
    elseif ($phase == "show_edit_footnote_form") {
        get_formvars_show_edit_footnote_form();
    }
    elseif ($phase == "show_create_footnote_form") {
        get_formvars_show_create_footnote_form();
    }
    elseif ($phase == "filter_footnotes") {
        get_formvars_filter_footnotes();
    }
    else {
        exit();
    }

    function get_formvars_footnote_description_delete() {
        $footnote = new footnote;
        $footnote->footnote_description_period_sid = get_querystring("footnote_description_period_sid");
        $footnote->footnote_id = get_querystring("footnote_id");
        $footnote->footnote_type_id = get_querystring("footnote_type_id");
        $footnote->delete_description();
        $url = "/footnote_view.html?footnote_id=" . $footnote->footnote_id . "&footnote_type_id=" . $footnote->footnote_type_id;
        header("Location: " . $url);            
    }

    function get_formvars_footnote_create_edit($create_edit) {
        $errors = array();
        $footnote = new footnote;
        $footnote->footnote_id                      = get_formvar("footnote_id", "", True);
        $footnote->footnote_type_id                 = get_formvar("footnote_type_id", "", True);
        $footnote->description                      = get_formvar("description",                        "footnote_", True);
        $footnote->validity_start_day               = get_formvar("validity_start_day",                 "footnote_", True);
        $footnote->validity_start_month             = get_formvar("validity_start_month",               "footnote_", True);
        $footnote->validity_start_year              = get_formvar("validity_start_year",                "footnote_", True);
        $footnote->validity_end_day                 = get_formvar("validity_end_day",                   "footnote_", True);
        $footnote->validity_end_month               = get_formvar("validity_end_month",                 "footnote_", True);
        $footnote->validity_end_year                = get_formvar("validity_end_year",                  "footnote_", True);

        $footnote->set_dates();

        # Check on the footnote id
        if (strlen($footnote->footnote_id) != 3 && strlen($footnote->footnote_id) != 5) {
            array_push($errors, "footnote_id");
        }

        # If we are creating, check that the footnote / footnote type does not already exist
        if ($create_edit == "create") {
            if ($footnote->exists()) {
                array_push($errors, "footnote_exists");
            }
        }

        # Check on the description
        if ($footnote->description == "") {
            array_push($errors, "description");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($footnote->validity_start_month, $footnote->validity_start_day, $footnote->validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($footnote->validity_end_day == "") && ($footnote->validity_end_month == "") && ($footnote->validity_end_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($footnote->validity_end_month, $footnote->validity_end_day, $footnote->validity_end_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }
        
        h1 (count($errors));
        #exit();
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/footnote_create_edit.html?phase=" . $create_edit . "&action=new&err=1&footnote_id=" . $footnote->footnote_id;
        } else {
            if ($create_edit == "create") {
                #h1 ("here");
                #exit();
                // Do create scripts
                $footnote->create();
            } else {
                // Do edit scripts
                $footnote->update();
            }
            $url = "/footnotes.html";
        }
        header("Location: " . $url);

    }

    function get_formvars_show_edit_footnote_form() {
        $footnote_id = get_querystring("footnote_id");
        $url = "/footnote_create_edit.html?phase=edit&footnote_id=" . $footnote_id;
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_show_create_footnote_form() {
        $url = "/footnote_create_edit.html?phase=create";
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_filter_footnotes() {
        $footnote_scope          = get_querystring("footnote_scope");
        $url  = "/footnotes.html";
        $url .= "?footnote_scope=" . $footnote_scope;
        #h1 ($url);
        #exit();
        header("Location: " . $url);
    }

    function get_formvars_footnote_update_description() {
        $errors = [];
        $footnote_id                       = get_formvar("footnote_id");
        $footnote_type_id                  = get_formvar("footnote_type_id");
        $footnote_description_period_sid   = get_formvar("footnote_description_period_sid");
        $validity_start_day                = get_formvar("validity_start_day",     "footnote_", True);
        $validity_start_month              = get_formvar("validity_start_month",   "footnote_", True);
        $validity_start_year               = get_formvar("validity_start_year",    "footnote_", True);
        $description                       = get_formvar("description",            "footnote_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);
        #h1 ($validity_start_date);
        #exit();

        if ($footnote_description_period_sid == -1) {
            # Check on the validity start and end dates
            $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
            #h1 ("valid start date" . $valid_start_date);
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
            $footnote = new footnote;
            if ($footnote_description_period_sid != -1) {
                #$ret = $footnote->update_description($footnote_description_period_sid, $description);
                $ret = $footnote->update_description($footnote_id, $footnote_type_id, $validity_start_date, $description, $footnote_description_period_sid);
            } else {
                $ret = $footnote->insert_description($footnote_id, $footnote_type_id, $validity_start_date, $description);
                if (is_array($ret)) {
                    array_push($errors, "conflict_with_existing");
                }    
            }
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/footnote_add_description.html?action=new&err=1&footnote_id=" . $footnote_id . "&footnote_type_id=" . $footnote_type_id;
        } else {
            $url = "/footnote_view.html?footnote_id=" . $footnote_id . "&footnote_type_id=" . $footnote_type_id . "#description_periods";
        }
        header("Location: " . $url);

    }
?>