<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    pre($_REQUEST);
    #exit();
    $phase = get_formvar("phase");

    if ($phase == "certificate_create") {
        get_formvars_certificate_create_edit("create");
    }
    elseif ($phase == "certificate_update_description") {
        get_formvars_certificate_update_description();
    }
    elseif ($phase == "certificate_description_delete") {
        get_formvars_certificate_description_delete();
    }
    elseif ($phase == "certificate_edit") {
        get_formvars_certificate_create_edit("edit");
    }
    elseif ($phase == "show_edit_certificate_form") {
        get_formvars_show_edit_certificate_form();
    }
    elseif ($phase == "show_create_certificate_form") {
        get_formvars_show_create_certificate_form();
    }
    elseif ($phase == "filter_certificates") {
        get_formvars_filter_certificates();
    }
    else {
        exit();
    }

    function get_formvars_certificate_description_delete() {
        $certificate = new certificate;
        $certificate->certificate_description_period_sid = get_querystring("certificate_description_period_sid");
        $certificate->certificate_code = get_querystring("certificate_code");
        $certificate->certificate_type_code = get_querystring("certificate_type_code");
        $certificate->delete_description();
        $url = "/certificate_view.html?certificate_code=" . $certificate->certificate_code . "&certificate_type_code=" . $certificate->certificate_type_code;
        header("Location: " . $url);            
    }

    function get_formvars_certificate_create_edit($create_edit) {
        $errors = array();
        $certificate = new certificate;
        $certificate->certificate_code                      = get_formvar("certificate_code", "", True);
        $certificate->certificate_type_code                 = get_formvar("certificate_type_code", "", True);
        $certificate->description                      = get_formvar("description",                        "certificate_", True);
        $certificate->validity_start_day               = get_formvar("validity_start_day",                 "certificate_", True);
        $certificate->validity_start_month             = get_formvar("validity_start_month",               "certificate_", True);
        $certificate->validity_start_year              = get_formvar("validity_start_year",                "certificate_", True);
        $certificate->validity_end_day                 = get_formvar("validity_end_day",                   "certificate_", True);
        $certificate->validity_end_month               = get_formvar("validity_end_month",                 "certificate_", True);
        $certificate->validity_end_year                = get_formvar("validity_end_year",                  "certificate_", True);

        $certificate->set_dates();

        # Check on the certificate id
        if (strlen($certificate->certificate_code) != 3 && strlen($certificate->certificate_code) != 5) {
            array_push($errors, "certificate_code");
        }

        # If we are creating, check that the certificate / certificate type does not already exist
        if ($create_edit == "create") {
            if ($certificate->exists()) {
                array_push($errors, "certificate_exists");
            }
        }

        # Check on the description
        if ($certificate->description == "") {
            array_push($errors, "description");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($certificate->validity_start_month, $certificate->validity_start_day, $certificate->validity_start_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($certificate->validity_end_day == "") && ($certificate->validity_end_month == "") && ($certificate->validity_end_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($certificate->validity_end_month, $certificate->validity_end_day, $certificate->validity_end_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }
        
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/certificate_create_edit.html?phase=" . $create_edit . "&action=new&err=1&certificate_code=" . $certificate->certificate_code;
        } else {
            if ($create_edit == "create") {
                // Do create scripts
                $certificate->create();
            } else {
                // Do edit scripts
                $certificate->update();
            }
            $url = "/certificates.html";
        }
        header("Location: " . $url);

    }

    function get_formvars_show_edit_certificate_form() {
        $certificate_code = get_querystring("certificate_code");
        $url = "/certificate_create_edit.html?phase=edit&certificate_code=" . $certificate_code;
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_show_create_certificate_form() {
        $url = "/certificate_create_edit.html?phase=create";
        header("Location: " . $url);            
        exit();
    }

    function get_formvars_filter_certificates() {
        $certificate_scope          = get_querystring("certificate_scope");
        $url  = "/certificates.html";
        $url .= "?certificate_scope=" . $certificate_scope;
        header("Location: " . $url);
    }

    function get_formvars_certificate_update_description() {
        $errors = [];
        $certificate_code                       = get_formvar("certificate_code");
        $certificate_type_code                  = get_formvar("certificate_type_code");
        $certificate_description_period_sid   = get_formvar("certificate_description_period_sid");
        $validity_start_day                = get_formvar("validity_start_day",     "certificate_", True);
        $validity_start_month              = get_formvar("validity_start_month",   "certificate_", True);
        $validity_start_year               = get_formvar("validity_start_year",    "certificate_", True);
        $description                       = get_formvar("description",            "certificate_", True);

        $validity_start_date    = to_date_string($validity_start_day, $validity_start_month, $validity_start_year);

        if ($certificate_description_period_sid == -1) {
            # Check on the validity start and end dates
            $valid_start_date = checkdate($validity_start_month, $validity_start_day, $validity_start_year);
            if ($valid_start_date != 1) {
                array_push($errors, "validity_start_date");
            }
        }

        # Check on description
        if ($description == "") {
            array_push($errors, "description");
        }

        if (count($errors) == 0) {
            $certificate = new certificate;
            if ($certificate_description_period_sid != -1) {
                #$ret = $certificate->update_description($certificate_description_period_sid, $description);
                $ret = $certificate->update_description($certificate_code, $certificate_type_code, $validity_start_date, $description, $certificate_description_period_sid);
            } else {
                $ret = $certificate->insert_description($certificate_code, $certificate_type_code, $validity_start_date, $description);
                if (is_array($ret)) {
                    array_push($errors, "conflict_with_existing");
                }    
            }
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/certificate_add_description.html?action=new&err=1&certificate_code=" . $certificate_code . "&certificate_type_code=" . $certificate_type_code;
        } else {
            $url = "/certificate_view.html?certificate_code=" . $certificate_code . "&certificate_type_code=" . $certificate_type_code . "#description_periods";
        }
        header("Location: " . $url);

    }
?>