<?php
class quota_order_number
{
    // Class properties and methods go here
    public $quota_order_number_id = "";
    public $quota_order_number_sid = 0;
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $description = "";
    public $origin_quota = null;
    public $quota_scope = null;
    public $quota_staging = null;
    public $quota_mechanism = null;
    public $critical_threshold = null;
    public $monetary_unit_code = null;
    public $measurement_unit_code = null;
    public $commodity_codes = null;
    public $measure_generating_regulation_id = null;
    public $measurement_unit_qualifier_code = null;
    public $introductory_period_option = null;
    public $period_type = null;
    public $maximum_precision = null;
    public $year_count = null;
    public $duties_same_for_all_commodities = null;
    public $quota_category = "";
    public $mechanism = "";
    public $origins = array();
    public $measure_types = array();

    #$quota_order_number_origin = new quota_order_number_origin;

    public function set_properties($quota_order_number_id, $validity_start_date, $validity_end_date)
    {
        $this->quota_order_number_id = $quota_order_number_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
    }

    function validate_form_step1()
    {
        global $application;
        $errors = array();

        //prend($_REQUEST);

        $this->measure_generating_regulation_id = strtoupper(get_formvar("measure_generating_regulation_id", "", True));
        $hyphen_pos = strpos($this->measure_generating_regulation_id, "-");
        if ($hyphen_pos !== -1) {
            $this->measure_generating_regulation_id = trim(substr($this->measure_generating_regulation_id, 0, $hyphen_pos - 1));
        }
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->quota_mechanism = get_formvar("quota_mechanism", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->description = get_formvar("description", "", True);
        $this->geographical_area_id_countries = get_formvar("geographical_area_id_countries", "", True);
        $this->description = get_formvar("description", "", True);

        /*
        $this->validity_start_date_day = get_formvar("validity_start_date_day", "", True);
        $this->validity_start_date_month = get_formvar("validity_start_date_month", "", True);
        $this->validity_start_date_year = get_formvar("validity_start_date_year", "", True);
        $this->validity_start_date_string = $this->validity_start_date_day . "|" . $this->validity_start_date_month . "|" . $this->validity_start_date_year;
        setcookie("validity_start_date_string", $this->validity_start_date_string, time() + (86400 * 30), "/");

        $this->validity_end_date_day = get_formvar("validity_end_date_day", "", True);
        $this->validity_end_date_month = get_formvar("validity_end_date_month", "", True);
        $this->validity_end_date_year = get_formvar("validity_end_date_year", "", True);
        $this->validity_end_date_string = $this->validity_end_date_day . "|" . $this->validity_end_date_month . "|" . $this->validity_end_date_year;
        setcookie("validity_end_date_string", $this->validity_end_date_string, time() + (86400 * 30), "/");

        //$this->set_dates();
        */

        # Check on the measure_generating_regulation_id
        if (strlen($this->measure_generating_regulation_id) != 8) {
            array_push($errors, "measure_generating_regulation_id");
        }

        # Check on the quota_category
        if ($this->quota_category == "Unspecified") {
            array_push($errors, "quota_category");
        }

        # Check on the quota_mechanism
        if (empty($this->quota_mechanism)) {
            array_push($errors, "quota_mechanism");
        }

        # Check on the quota_order_number_id
        if (strlen($this->quota_order_number_id) != 6) {
            array_push($errors, "quota_order_number_id");
        }

        # If we are creating, check that the quota order number ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "quota_order_number_exists");
            }
        }

        /*
        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if ($application->mode == "update") {
            if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
                $valid_end_date = 1;
            } else {
                $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
            }
            if ($valid_end_date != 1) {
                array_push($errors, "validity_end_date");
            }
        }
        */

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 400)) {
            array_push($errors, "description");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
 if ($create_edit == "create") {
 // Do create scripts
 $this->create();
 } else {
 // Do edit scripts
 $this->update();
 }*/
            $url = "./create_edit2.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step2()
    {
        global $application;
        $errors = array();

        $this->measure_generating_regulation_id = strtoupper(get_formvar("measure_generating_regulation_id", "", True));
        $hyphen_pos = strpos($this->measure_generating_regulation_id, "-");
        if ($hyphen_pos !== -1) {
            $this->measure_generating_regulation_id = trim(substr($this->measure_generating_regulation_id, 0, $hyphen_pos - 1));
        }
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->quota_mechanism = get_formvar("quota_mechanism", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->description = get_formvar("description", "", True);
        $this->geographical_area_id_countries = get_formvar("geographical_area_id_countries", "", True);
        $this->description = get_formvar("description", "", True);

        $this->quota_scope = get_formvar("quota_scope", "", True);
        $this->quota_staging = get_formvar("quota_staging", "", True);
        $this->origin_quota = get_formvar("origin_quota", "", True);

        # Check on the measure_generating_regulation_id
        if (strlen($this->origin_quota) == "") {
            array_push($errors, "origin_quota");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit2.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit3.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step3()
    {
        global $application;
        $errors = array();

        //pre($_REQUEST);

        $this->commodity_codes = trim(get_formvar("commodity_codes", "", True));
        $this->commodity_codes = str_replace("\n", " ", $this->commodity_codes);
        $this->commodity_codes = str_replace("\r", " ", $this->commodity_codes);
        $this->commodity_codes = trim(preg_replace('/\s\s+/', ' ', $this->commodity_codes));


        $parts = preg_split("@[\s+　]@u", trim($this->commodity_codes));
        h1(count($parts));
        $commodity_errors = false;

        foreach ($parts as $part) {
            if (!preg_match('/[0-9]{10}/', $part)) {
                $commodity_errors = true;
            }
        }

        # Check on the commodity_codes
        if ((strlen($this->commodity_codes) == "") || ($commodity_errors == true)) {
            array_push($errors, "commodity_codes");
        }


        # Check on the duties_same_for_all_commodities
        $this->duties_same_for_all_commodities = trim(get_formvar("duties_same_for_all_commodities", "", True));
        if (strlen($this->duties_same_for_all_commodities) == "") {
            array_push($errors, "duties_same_for_all_commodities");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit3.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit4.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    function validate_form_step4()
    {
        global $application;
        $errors = array();

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit4.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit5.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step5()
    {
        global $application;
        $errors = array();

        //prend ($_REQUEST);


        $this->measurement_unit_code = get_formvar("measurement_unit_code", "", True);
        $this->measurement_unit_qualifier_code = get_formvar("measurement_unit_qualifier_code", "", True);
        $this->maximum_precision = get_formvar("maximum_precision", "", True);
        $this->critical_threshold = intval(get_formvar("critical_threshold", "", True));

        if (($this->measurement_unit_code == "Unspecified") || ($this->measurement_unit_code == "")) {
            array_push($errors, "measurement_unit_code");
        }

        if (($this->maximum_precision == "Unspecified") || ($this->maximum_precision == "")) {
            array_push($errors, "maximum_precision");
        }

        if ($this->critical_threshold == "") {
            array_push($errors, "critical_threshold");
        }


        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit5.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit6.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    function validate_form_step6()
    {
        global $application;
        $errors = array();

        //prend ($_REQUEST);

        $this->period_type = get_formvar("period_type", "", True);
        if ($this->period_type == "") {
            array_push($errors, "period_type");
        }

        $this->year_count = get_formvar("year_count", "", True);
        if ($this->year_count == "") {
            array_push($errors, "year_count");
        }

        $this->introductory_period_option = get_formvar("introductory_period_option", "", True);
        if ($this->introductory_period_option == "") {
            array_push($errors, "introductory_period_option");
        }





        $this->validity_start_date_day = get_formvar("validity_start_date_day", "", True);
        $this->validity_start_date_month = get_formvar("validity_start_date_month", "", True);
        $this->validity_start_date_year = get_formvar("validity_start_date_year", "", True);
        $this->validity_start_date_string = $this->validity_start_date_day . "|" . $this->validity_start_date_month . "|" . $this->validity_start_date_year;
        setcookie("validity_start_date_string", $this->validity_start_date_string, time() + (86400 * 30), "/");

        $this->validity_end_date_day = get_formvar("validity_end_date_day", "", True);
        $this->validity_end_date_month = get_formvar("validity_end_date_month", "", True);
        $this->validity_end_date_year = get_formvar("validity_end_date_year", "", True);
        $this->validity_end_date_string = $this->validity_end_date_day . "|" . $this->validity_end_date_month . "|" . $this->validity_end_date_year;
        setcookie("validity_end_date_string", $this->validity_end_date_string, time() + (86400 * 30), "/");

        $this->set_dates();

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        /*
        if ($application->mode == "update") {
            if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
                $valid_end_date = 1;
            } else {
                $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
            }
            if ($valid_end_date != 1) {
                array_push($errors, "validity_end_date");
            }
        }
        */

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit6.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit7.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM quota_order_numbers WHERE quota_order_number_id = $1 AND validity_end_date is not null";
        pg_prepare($conn, "quota_order_number_exists", $sql);
        $result = pg_execute($conn, "quota_order_number_exists", array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }

    function set_dates()
    {
        if (($this->validity_start_date_day == "") || ($this->validity_start_date_month == "") || ($this->validity_start_date_year == "")) {
            $this->validity_start_date = Null;
        } else {
            $this->validity_start_date    = to_date_string($this->validity_start_date_day,    $this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date    = to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }


    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->quota_order_number_sid = trim(get_querystring("quota_order_number_sid"));
        $this->quota_order_number_id = trim(get_querystring("quota_order_number_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                if ($description == false) {
                    $ret = $this->populate_from_db();
                } else {
                    //$ret = $this->get_specific_description($this->validity_start_date);
                    $a = 1;
                }
                if (!$ret) {
                    h1("An error has occurred - no such quota order number");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
        // pre($this);
    }


    public function clear_cookies()
    {
        setcookie("footnote_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_type_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("footnote_description", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_year", "", time() + (86400 * 30), "/");
    }


    function populate_from_db()
    {
        global $conn;
        $ret = false;

        if (substr($this->quota_order_number_id, 0, 3) == "094") {
            $this->licensed = true;
        } else {
            $this->licensed = false;
        }

        // Get core data
        if (!$this->licensed) {
            $sql = "SELECT validity_start_date, validity_end_date, qon.description, origin_quota,
            quota_scope, qon.quota_category, quota_order_number_sid, qc.description as quota_category_description
            FROM quota_order_numbers qon left outer join quota_categories qc on qon.quota_category = qc.quota_category
            WHERE quota_order_number_sid = $1
            order by validity_start_date desc limit 1";
            pg_prepare($conn, "quota_populate_from_db_core", $sql);
            $result = pg_execute($conn, "quota_populate_from_db_core", array($this->quota_order_number_sid));
            if ($result) {
                if (pg_num_rows($result) > 0) {
                    $row = pg_fetch_row($result);
                    $this->mechanism = "First Come First Served";
                    $this->validity_start_date = $row[0];
                    $this->validity_end_date = $row[1];
                    $this->description = $row[2];
                    $this->origin_quota = $row[3];
                    $this->quota_scope = $row[4];
                    $this->quota_category = $row[5];
                    $this->quota_order_number_sid = $row[6];
                    $this->quota_category_description = $row[7];
                    $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
                    $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
                    $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
                    if ($this->validity_end_date != "") {
                        $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                        $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                        $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
                    } else {
                        $this->validity_end_date_day = "";
                        $this->validity_end_date_month = "";
                        $this->validity_end_date_year = "";
                    }
                    $ret = true;
                }
            }
        } else {
            //h1 ($this->quota_order_number_id);
            $sql = "SELECT validity_start_date, validity_end_date, lq.description, origin_quota,
            quota_scope, lq.quota_category, quota_order_number_sid, qc.description as quota_category_description
            FROM licensed_quotas_oplog lq left outer join quota_categories qc on lq.quota_category = qc.quota_category
            WHERE quota_order_number_id = $1
            order by validity_start_date desc limit 1";
            pg_prepare($conn, "quota_populate_from_db_core", $sql);
            $result = pg_execute($conn, "quota_populate_from_db_core", array($this->quota_order_number_id));
            if ($result) {
                if (pg_num_rows($result) > 0) {
                    $row = pg_fetch_row($result);
                    $this->mechanism = "Licensed";
                    $this->validity_start_date = $row[0];
                    $this->validity_end_date = $row[1];
                    $this->description = $row[2];
                    $this->origin_quota = $row[3];
                    $this->quota_scope = $row[4];
                    $this->quota_category = $row[5];
                    $this->quota_order_number_sid = $row[6];
                    $this->quota_category_description = $row[7];
                    $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
                    $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
                    $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
                    if ($this->validity_end_date != "") {
                        $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                        $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                        $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
                    } else {
                        $this->validity_end_date_day = "";
                        $this->validity_end_date_month = "";
                        $this->validity_end_date_year = "";
                    }
                    $ret = true;
                }
            }
        }


        // Get origins
        $this->origins = array();
        $sql = "select qono.quota_order_number_origin_sid, qono.geographical_area_id, qono.geographical_area_sid,
        qono.validity_start_date, qono.validity_end_date, ga.description, qono.status
        from quota_order_number_origins qono, ml.ml_geographical_areas ga 
        where ga.geographical_area_sid = qono.geographical_area_sid 
        and qono.quota_order_number_sid = $1
        order by qono.validity_start_date desc, ga.description;";
        pg_prepare($conn, "quota_populate_from_db_origins", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_origins", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $origin = new quota_order_number_origin();
                    $origin->quota_order_number_origin_sid = $row[0];
                    $origin->geographical_area_id = $row[1];
                    $origin->geographical_area_sid = $row[2];
                    $origin->validity_start_date = $row[3];
                    $origin->validity_end_date = $row[4];
                    $origin->description = $row[5];
                    $origin->status = $row[6];
                    $origin->exclusions = array();


                    $origin->actions = "";
                    $origin->edit_action = "<a class='govuk-link' href='origin_create_edit.html'><img src='/assets/images/edit.png' alt='Edit this origin' /></a>";
                    $origin->delete_action = "<a class='govuk-link' href='origin_create_edit.html'><img src='/assets/images/delete.png' alt='Delete this origin' /></a>";
                    $origin->actions .= $origin->edit_action;
                    $origin->actions .= $origin->delete_action;
                    array_push($this->origins, $origin);
                }
            }
        }

        // Get origin exclusions
        $origin_exclusions = array();
        $sql = "select qonoe.quota_order_number_origin_sid, qonoe.excluded_geographical_area_sid, ga.geographical_area_id, ga.description 
        from quota_order_number_origin_exclusions qonoe, quota_order_number_origins qono, ml.ml_geographical_areas ga 
        where qono.quota_order_number_origin_sid = qonoe.quota_order_number_origin_sid 
        and ga.geographical_area_sid = qonoe.excluded_geographical_area_sid 
        and qono.quota_order_number_sid = $1
        order by ga.description;";
        pg_prepare($conn, "quota_populate_from_db_origin_exclusions", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_origin_exclusions", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $origin_exclusion = new quota_order_number_origin_exclusion();
                    $origin_exclusion->quota_order_number_origin_sid = $row[0];
                    $origin_exclusion->excluded_geographical_area_sid = $row[1];
                    $origin_exclusion->geographical_area_id = $row[2];
                    $origin_exclusion->description = $row[3];
                    array_push($origin_exclusions, $origin_exclusion);
                }
            }
        }

        // Now assign the exclusions to the origins
        foreach ($origin_exclusions as $origin_exclusion) {
            foreach ($this->origins as $origin) {
                if ($origin_exclusion->quota_order_number_origin_sid == $origin->quota_order_number_origin_sid) {
                    array_push($origin->exclusions, $origin_exclusion);
                    break;
                }
            }
        }

        // And finally make the origin exclusion text field
        foreach ($this->origins as $origin) {
            $origin->exclusion_text = "";
            $count = count($origin->exclusions);
            $index = 0;
            foreach ($origin->exclusions as $origin_exclusion) {
                $index++;
                $origin->exclusion_text .= $origin_exclusion->geographical_area_id . ' - ' . $origin_exclusion->description;
                if ($index < $count) {
                    $origin->exclusion_text .= "<br />";
                }
            }
        }


        // Get the definitions
        $this->quota_definitions = array();
        $sql = "select qd.quota_definition_sid, qd.validity_start_date, qd.validity_end_date, qd.initial_volume,
        qd.measurement_unit_code, qd.maximum_precision, qd.critical_state, qd.critical_threshold,
        qd.monetary_unit_code, qd.measurement_unit_qualifier_code, qd.description,
        (qd.measurement_unit_code || ' - ' || mud.description) as measurement_unit_description,
        (qd.measurement_unit_qualifier_code || ' - ' || muqd.description) as measurement_unit_qualifier_description,
        qd.quota_order_number_sid, qd.quota_order_number_id, qd.status
        from measurement_unit_descriptions mud right outer join quota_definitions qd on mud.measurement_unit_code = qd.measurement_unit_code 
        left outer join measurement_unit_qualifier_descriptions muqd on qd.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code 
        where qd.quota_order_number_sid = $1 order by qd.validity_start_date desc;";
        pg_prepare($conn, "quota_populate_from_db_definitions", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_definitions", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $quota_definition = new quota_definition();
                    $quota_definition->quota_definition_sid = $row[0];
                    $quota_definition->validity_start_date = $row[1];
                    $quota_definition->validity_end_date = $row[2];
                    $quota_definition->initial_volume = $row[3];
                    $quota_definition->measurement_unit_code = $row[4];
                    $quota_definition->maximum_precision = $row[5];
                    $quota_definition->critical_state = $row[6];
                    $quota_definition->critical_threshold = $row[7];
                    $quota_definition->monetary_unit_code = $row[8];
                    $quota_definition->measurement_unit_qualifier_code = $row[9];
                    $quota_definition->description = $row[10];
                    $quota_definition->measurement_unit_description = $row[11];
                    $quota_definition->measurement_unit_qualifier_description = $row[12];
                    $quota_definition->quota_order_number_sid = $row[13];
                    $quota_definition->quota_order_number_id = $row[14];
                    $quota_definition->status = $row[15];

                    $quota_definition->initial_volume_string = number_format($quota_definition->initial_volume);
                    if ($quota_definition->monetary_unit_code != "") {
                        $quota_definition->unit = $quota_definition->monetary_unit_code;
                    } else {
                        $quota_definition->unit = $quota_definition->measurement_unit_description;
                        if ($quota_definition->measurement_unit_qualifier_code != "") {
                            $quota_definition->unit .= " " . $quota_definition->measurement_unit_qualifier_code_description;
                        }
                    }

                    $quota_definition->actions = "";
                    $quota_definition->duplicate_action = "<a class='govuk-link' href='/quota_definitions/create_edit.html?mode=duplicate&quota_definition_sid=" . $quota_definition->quota_definition_sid . "&quota_order_number_sid=" . $quota_definition->quota_order_number_sid . "&quota_order_number_id=" . $quota_definition->quota_order_number_id . "'><img src='/assets/images/copy.png' alt='Edit this quota definition' /></a>";
                    $quota_definition->edit_action = "<a class='govuk-link' href='/quota_definitions/create_edit.html?mode=update&quota_definition_sid=" . $quota_definition->quota_definition_sid . "&quota_order_number_sid=" . $quota_definition->quota_order_number_sid . "&quota_order_number_id=" . $quota_definition->quota_order_number_id . "'><img src='/assets/images/edit.png' alt='Edit this quota definition' /></a>";
                    $quota_definition->delete_action = "<a class='govuk-link' href='definition_create_edit.html'><img src='/assets/images/delete.png' alt='Delete this quota definition' /></a>";



                    $quota_definition->actions .= $quota_definition->duplicate_action;
                    $quota_definition->actions .= $quota_definition->edit_action;
                    $quota_definition->actions .= $quota_definition->delete_action;


                    array_push($this->quota_definitions, $quota_definition);
                }
            }
        }


        // Get the suspensions
        $this->quota_suspension_periods = array();
        $sql = "select qd.quota_order_number_sid, qd.quota_order_number_id, qsp.quota_suspension_period_sid, qsp.quota_definition_sid,
        qsp.suspension_start_date, qsp.suspension_end_date, qsp.description,
        qd.validity_start_date as definition_start_date, qd.validity_end_date as definition_end_date, quota_suspension_period_sid, qsp.status
        from quota_suspension_periods qsp, quota_definitions qd 
        where qsp.quota_definition_sid = qd.quota_definition_sid
        and qd.quota_order_number_sid = $1
        order by qsp.suspension_start_date desc;";
        pg_prepare($conn, "quota_populate_from_db_suspension_periods", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_suspension_periods", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $quota_suspension_period = new quota_suspension_period();
                    $quota_suspension_period->quota_order_number_sid = $row[0];
                    $quota_suspension_period->quota_order_number_id = $row[1];
                    $quota_suspension_period->quota_suspension_period_sid = $row[2];
                    $quota_suspension_period->quota_definition_sid = $row[3];
                    $quota_suspension_period->suspension_start_date = $row[4];
                    $quota_suspension_period->suspension_end_date = $row[5];
                    $quota_suspension_period->description = $row[6];
                    $quota_suspension_period->definition_start_date = $row[7];
                    $quota_suspension_period->definition_end_date = $row[8];
                    $quota_suspension_period->quota_suspension_period_sid = $row[9];
                    $quota_suspension_period->status = $row[10];

                    $quota_suspension_period->suspension_dates = short_date($quota_suspension_period->suspension_start_date) . " - " . short_date($quota_suspension_period->suspension_end_date);
                    $quota_suspension_period->definition_dates = short_date($quota_suspension_period->definition_start_date) . " - " . short_date($quota_suspension_period->definition_end_date);


                    $quota_suspension_period->actions = "";
                    $quota_suspension_period->edit_action = "<img src='/assets/images/blank.png' />";
                    $quota_suspension_period->delete_action = "<img src='/assets/images/blank.png' />";
                    if ($quota_suspension_period->suspension_end_date > date("Y-m-d")) {
                        $quota_suspension_period->edit_action = "<a class='govuk-link' href='/quota_suspension_periods/create_edit.html?mode=update&quota_order_number_sid=" . $quota_suspension_period->quota_order_number_sid . "&quota_order_number_id=" . $quota_suspension_period->quota_order_number_id . "&quota_suspension_period_sid=" . $quota_suspension_period->quota_suspension_period_sid . "'><img src='/assets/images/edit.png' alt='Edit this suspension period' /></a>";
                    }
                    if ($quota_suspension_period->suspension_start_date > date("Y-m-d")) {
                        $quota_suspension_period->delete_action = "<a class='govuk-link' href='actions.html?action=delete_suspension_period&quota_suspension_period_sid=" . $quota_suspension_period->quota_suspension_period_sid . "'><img src='/assets/images/delete.png' alt='Delete this suspension period' /></a>";
                    }
                    $quota_suspension_period->actions .= $quota_suspension_period->edit_action;
                    $quota_suspension_period->actions .= $quota_suspension_period->delete_action;

                    array_push($this->quota_suspension_periods, $quota_suspension_period);
                }
            }
        }

        // Get the blocking periods
        $this->quota_blocking_periods = array();
        $sql = "select qd.quota_order_number_sid, qd.quota_order_number_id, qbp.quota_blocking_period_sid, qbp.quota_definition_sid,
        qbp.blocking_start_date, qbp.blocking_end_date, qbp.description,
        qd.validity_start_date as definition_start_date, qd.validity_end_date as definition_end_date,
        qbp.blocking_period_type, bpt.description as blocking_period_type_description, qbp.status
        from quota_blocking_periods qbp, quota_definitions qd, blocking_period_types bpt 
        where qbp.quota_definition_sid = qd.quota_definition_sid
        and qbp.blocking_period_type = bpt.blocking_period_type 
        and qd.quota_order_number_sid = $1
        order by qbp.blocking_start_date desc;";
        pg_prepare($conn, "quota_populate_from_db_blocking_periods", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_blocking_periods", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $quota_blocking_period = new quota_blocking_period();
                    $quota_blocking_period->quota_order_number_sid = $row[0];
                    $quota_blocking_period->quota_order_number_id = $row[1];
                    $quota_blocking_period->quota_blocking_period_sid = $row[2];
                    $quota_blocking_period->quota_definition_sid = $row[3];
                    $quota_blocking_period->blocking_start_date = $row[4];
                    $quota_blocking_period->blocking_end_date = $row[5];
                    $quota_blocking_period->description = $row[6];
                    $quota_blocking_period->definition_start_date = $row[7];
                    $quota_blocking_period->definition_end_date = $row[8];
                    $quota_blocking_period->blocking_period_type = $row[9];
                    $quota_blocking_period->blocking_period_type_description = $row[10];
                    $quota_blocking_period->status = $row[11];

                    $quota_blocking_period->blocking_dates = short_date($quota_blocking_period->blocking_start_date) . " - " . short_date($quota_blocking_period->blocking_end_date);
                    $quota_blocking_period->definition_dates = short_date($quota_blocking_period->definition_start_date) . " - " . short_date($quota_blocking_period->definition_end_date);

                    $quota_blocking_period->actions = "";
                    $quota_blocking_period->edit_action = "<img src='/assets/images/blank.png' />";
                    $quota_blocking_period->delete_action = "<img src='/assets/images/blank.png' />";
                    if ($quota_blocking_period->blocking_end_date > date("Y-m-d")) {
                        $quota_blocking_period->edit_action = "<a class='govuk-link' href='/quota_blocking_periods/create_edit.html?mode=update&quota_order_number_sid=" . $quota_blocking_period->quota_order_number_sid . "&quota_order_number_id=" . $quota_blocking_period->quota_order_number_id . "&quota_blocking_period_sid=" . $quota_blocking_period->quota_blocking_period_sid . "'><img src='/assets/images/edit.png' alt='Edit this blocking period' /></a>";
                    }
                    if ($quota_blocking_period->blocking_start_date > date("Y-m-d")) {
                        $quota_blocking_period->delete_action = "<a class='govuk-link' href='actions.html?action=delete_blocking_period&quota_blocking_period_sid=" . $quota_blocking_period->quota_blocking_period_sid . "'><img src='/assets/images/delete.png' alt='Delete this blocking period' /></a>";
                    }
                    $quota_blocking_period->actions .= $quota_blocking_period->edit_action;
                    $quota_blocking_period->actions .= $quota_blocking_period->delete_action;

                    array_push($this->quota_blocking_periods, $quota_blocking_period);
                }
            }
        }

        // Get the associations
        $this->quota_associations = array();
        $sql = "select qa.main_quota_definition_sid, qa.sub_quota_definition_sid, qa.relation_type, qa.coefficient, 
        qdmain.initial_volume as main_initial_volume, qdmain.validity_start_date as main_validity_start_date, qdmain.validity_end_date as main_validity_end_date,
        qdmain.quota_order_number_id as main_quota_order_number_id, qdmain.quota_order_number_sid as main_quota_order_number_sid, 
        qdsub.initial_volume as sub_initial_volume, qdsub.validity_start_date as sub_validity_start_date, qdsub.validity_end_date as sub_validity_end_date,
        qdsub.quota_order_number_id as sub_quota_order_number_id, qdsub.quota_order_number_sid as sub_quota_order_number_sid,
        string_agg(distinct qonomain.geographical_area_id, ',' order by qonomain.geographical_area_id) as main_origin,
        string_agg(distinct qonosub.geographical_area_id, ',' order by qonosub.geographical_area_id) as sub_origin,
        qdmain.measurement_unit_code as main_mu, qdmain.measurement_unit_qualifier_code as main_muq, 
        qdsub.measurement_unit_code as sub_mu, qdsub.measurement_unit_qualifier_code as sub_muq, qa.status
        from quota_associations qa, quota_definitions qdmain, quota_definitions qdsub,
        quota_order_number_origins qonomain, quota_order_number_origins qonosub
        where qa.main_quota_definition_sid = qdmain.quota_definition_sid 
        and qa.sub_quota_definition_sid = qdsub.quota_definition_sid 
        and qonomain.quota_order_number_sid = qdmain.quota_order_number_sid 
        and qonosub.quota_order_number_sid = qdsub.quota_order_number_sid 
        and (qdmain.quota_order_number_sid = $1 or qdsub.quota_order_number_sid = $1)
        group by 
        qa.main_quota_definition_sid, qa.sub_quota_definition_sid, qa.relation_type, qa.coefficient, 
        qdmain.initial_volume, qdmain.validity_start_date, qdmain.validity_end_date,
        qdmain.quota_order_number_id, qdmain.quota_order_number_sid, 
        qdsub.initial_volume, qdsub.validity_start_date, qdsub.validity_end_date,
        qdsub.quota_order_number_id, qdsub.quota_order_number_sid,
        qdmain.measurement_unit_code, qdmain.measurement_unit_qualifier_code, 
        qdsub.measurement_unit_code, qdsub.measurement_unit_qualifier_code, qa.status
        order by qdmain.quota_order_number_id, qdmain.validity_start_date desc, qdsub.quota_order_number_id, qdsub.validity_start_date;";
        pg_prepare($conn, "quota_populate_from_db_associations", $sql);
        $result = pg_execute($conn, "quota_populate_from_db_associations", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $quota_association = new quota_association();
                    $quota_association->main_quota_definition_sid = $row[0];
                    $quota_association->sub_quota_definition_sid = $row[1];
                    $quota_association->relation_type = $row[2];
                    $quota_association->coefficient = $row[3];
                    $quota_association->main_initial_volume = $row[4];
                    $quota_association->main_validity_start_date = $row[5];
                    $quota_association->main_validity_end_date = $row[6];
                    $quota_association->main_quota_order_number_id = $row[7];
                    $quota_association->main_quota_order_number_sid = $row[8];
                    $quota_association->sub_initial_volume = $row[9];
                    $quota_association->sub_validity_start_date = $row[10];
                    $quota_association->sub_validity_end_date = $row[11];
                    $quota_association->sub_quota_order_number_id = $row[12];
                    $quota_association->sub_quota_order_number_sid = $row[13];
                    $quota_association->main_origin = $row[14];
                    $quota_association->sub_origin = $row[15];
                    $quota_association->main_mu = $row[16];
                    $quota_association->main_muq = $row[17];
                    $quota_association->sub_mu = $row[18];
                    $quota_association->sub_muq = $row[19];
                    $quota_association->status = $row[20];

                    $quota_association->main_origin_string = $this->origin_parse($quota_association->main_origin);
                    $quota_association->sub_origin_string = $this->origin_parse($quota_association->sub_origin);

                    $quota_association->main_initial_volume_string = number_format($quota_association->main_initial_volume, 0) . " " . $quota_association->main_mu . " " . $quota_association->main_muq;
                    $quota_association->sub_initial_volume_string = number_format($quota_association->sub_initial_volume, 0) . " " . $quota_association->sub_mu . " " . $quota_association->sub_muq;

                    $quota_association->ratio = ($quota_association->sub_initial_volume / $quota_association->main_initial_volume) * 100;

                    if ($quota_association->main_quota_order_number_id == $this->quota_order_number_id) {
                        $quota_association->main_quota_order_number_id_string = $quota_association->main_quota_order_number_id;
                    } else {
                        $quota_association->main_quota_order_number_id_string = '<a class="govuk-link" href="./view.html?mode=view&quota_order_number_sid=' . $quota_association->main_quota_order_number_sid . '&quota_order_number_id=' . $quota_association->main_quota_order_number_id . '#tab_quota_associations">' . $quota_association->main_quota_order_number_id . '</a>';
                    }
                    if ($quota_association->sub_quota_order_number_id == $this->quota_order_number_id) {
                        $quota_association->sub_quota_order_number_id_string = $quota_association->sub_quota_order_number_id;
                    } else {
                        $quota_association->sub_quota_order_number_id_string = '<a class="govuk-link" href="./view.html?mode=view&quota_order_number_sid=' . $quota_association->sub_quota_order_number_sid . '&quota_order_number_id=' . $quota_association->sub_quota_order_number_id . '#tab_quota_associations">' . $quota_association->sub_quota_order_number_id . '</a>';
                    }

                    $quota_association->actions = "";
                    $quota_association->edit_action = "<a class='govuk-link' href='definition_create_edit.html'><img src='/assets/images/edit.png' alt='Edit this association' /></a>";
                    $quota_association->delete_action = "<a class='govuk-link' href='definition_create_edit.html'><img src='/assets/images/delete.png' alt='Delete this association' /></a>";
                    $quota_association->actions .= $quota_association->edit_action;
                    $quota_association->actions .= $quota_association->delete_action;

                    array_push($this->quota_associations, $quota_association);
                }
            }
        }

        // Get the commodities
        $this->quota_commodities = array();
        if ($this->licensed == false) {
            $sql = "select distinct on (m.goods_nomenclature_item_id) m.goods_nomenclature_item_id, m.goods_nomenclature_sid,
            gnd.description, m.measure_type_id || ' - ' || mtd.description as measure_type
            from measures m, goods_nomenclatures gn, goods_nomenclature_descriptions gnd, measure_type_descriptions mtd
            where ordernumber = $1
            and m.goods_nomenclature_sid = gn.goods_nomenclature_sid 
            and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid 
            and gn.producline_suffix = '80'
            and gn.validity_end_date is null
            and m.measure_type_id = mtd.measure_type_id
            and m.validity_start_date >=
            (select qd.validity_start_date from quota_definitions qd where qd.quota_order_number_id = ordernumber order by qd.validity_start_date desc limit 1) 
            order by m.goods_nomenclature_item_id, gnd.oid desc;";
        } else {
            $sql = "select distinct on (m.goods_nomenclature_item_id) m.goods_nomenclature_item_id, m.goods_nomenclature_sid,
            gnd.description, m.measure_type_id || ' - ' || mtd.description as measure_type
            from measures m, goods_nomenclatures gn, goods_nomenclature_descriptions gnd, measure_type_descriptions mtd
            where ordernumber = $1
            and m.goods_nomenclature_sid = gn.goods_nomenclature_sid 
            and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid 
            and gn.producline_suffix = '80'
            and gn.validity_end_date is null
            and m.validity_end_date is null
            and m.measure_type_id = mtd.measure_type_id
            order by m.goods_nomenclature_item_id, gnd.oid desc;";
        }

        $stmt = "quota_get_commodities_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $gn = new goods_nomenclature();
                    $gn->goods_nomenclature_item_id = $row[0];
                    $gn->goods_nomenclature_sid = $row[1];
                    $gn->description = $row[2];
                    $gn->measure_type = $row[3];
                    $url = "/goods_nomenclatures/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $gn->goods_nomenclature_item_id . "&goods_nomenclature_sid=" . $gn->goods_nomenclature_sid;
                    $gn->goods_nomenclature_item_id_link = "<a class='nodecorate' href='" . $url . "'>" . format_goods_nomenclature_item_id($gn->goods_nomenclature_item_id) . "</a>";
                    array_push($this->quota_commodities, $gn);
                }
            }
        }

        // Get the measures
        $this->quota_measures = array();
        $sql = "select measure_sid, goods_nomenclature_item_id, goods_nomenclature_sid,
        geographical_area_id, geographical_area_sid, m.measure_type_id, validity_start_date, validity_end_date,
        measure_generating_regulation_id, reduction_indicator, m.status, mtd.description as measure_type_description
        from ml.measures_real_end_dates m, measure_type_descriptions mtd
        where ordernumber = $1
        and m.measure_type_id = mtd.measure_type_id
        order by validity_start_date desc, goods_nomenclature_item_id;";

        $stmt = "quota_get_measures_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $m = new measure();
                    $m->measure_sid = $row[0];
                    $m->goods_nomenclature_item_id = $row[1];
                    $m->goods_nomenclature_sid = $row[2];
                    $m->geographical_area_id = $row[3];
                    $m->geographical_area_sid = $row[4];
                    $m->measure_type_id = $row[5];
                    $m->validity_start_date = $row[6];
                    $m->validity_end_date = $row[7];
                    $m->measure_generating_regulation_id = $row[8];
                    $m->reduction_indicator = $row[9];
                    $m->status = $row[10];
                    $m->measure_type_description = $row[11];
                    $m->exclusions = "";
                    $m->duties = "duties";
                    $m->conditions = "conditions";
                    $m->footnotes = "footnotes";

                    // Get commodity link
                    $commodity_url = "/goods_nomenclatures/goods_nomenclature_item_view.html?mode=view&goods_nomenclature_item_id=" . $m->goods_nomenclature_item_id . "&goods_nomenclature_sid=" . $m->goods_nomenclature_sid;
                    $m->goods_nomenclature_item_id_link = "<a class='nodecorate' href='" . $commodity_url . "'>" . format_goods_nomenclature_item_id($m->goods_nomenclature_item_id) . "</a>";

                    // Get measure type link
                    $measure_type_url = "/measure_types/view.html?mode=view&measure_type_id=" . $m->measure_type_id;
                    $m->measure_type_link = "<a class='govuk-link' href='" . $measure_type_url . "'><abbr title='" . $m->measure_type_description . "'>" . $m->measure_type_id . "</abbr></a>";

                    // Get geographical area ID link
                    $geographical_area_url = "/geographical_areas/view.html?mode=view&geographical_area_id=" . $m->geographical_area_id . "&geographical_area_sid=" . $m->geographical_area_sid;
                    $m->geographical_area_link = "<a class='govuk-link' href='" . $geographical_area_url . "'>" . $m->geographical_area_id . "</a>";

                    // Get Measure SID link
                    $measure_url = "/measures/view.html?mode=view&measure_sid=" . $m->measure_sid;
                    $m->measure_link = "<a class='govuk-link' href='" . $measure_url . "'>" . $m->measure_sid . "</a>";

                    // Get regulation link
                    $regulation_url = "/regulations/view.html?mode=view&base_regulation_id=" . $m->measure_generating_regulation_id;
                    $m->regulation_link = "<a class='govuk-link' href='" . $regulation_url . "'>" . $m->measure_generating_regulation_id . "</a>";



                    http: //dev.tariffs.local/measures/view.html?mode=view&measure_sid=3702442



                    array_push($this->quota_measures, $m);
                }
            }
        }

        // Return the primary result
        return ($ret);
    }

    function origin_parse($s)
    {
        $array = explode(",", $s);
        $out = "";
        $count = count($array);
        $index = 0;
        foreach ($array as $s) {
            $index++;
            $out .= "<a class='govuk-link' href='/geographical_areas/view.html?mode=view&geographical_area_id=" . $s . "'>" . $s . "</a>";
            if ($index < $count) {
                $out .= ", ";
            }
        }
        return ($out);
    }

    function populate_from_cookies()
    {
        $this->validity_start_date_day = get_cookie("quota_order_number_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("quota_order_number_goods_nomenclature_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("quota_order_number_goods_nomenclature_validity_start_date_year");
        $this->validity_end_date_day = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_day");
        $this->validity_end_date_month = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_month");
        $this->validity_end_date_year = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_year");
        $this->description = get_cookie("quota_order_number_goods_nomenclature_description");
        $this->quota_scope = get_cookie("quota_order_number_quota_scope");
        $this->quota_staging = get_cookie("quota_order_number_quota_staging");
        $this->origin_quota = get_cookie("quota_order_number_origin_quota");
    }

    function clear()
    {
        $this->validity_start_date_day = "";
        $this->validity_start_date_month = "";
        $this->validity_start_date_year = "";
        $this->validity_end_date_day = "";
        $this->validity_end_date_month = "";
        $this->validity_end_date_year = "";
        $this->description = "";
        $this->quota_scope = "";
        $this->quota_staging = "";
        $this->origin_quota = "";
    }


    function insert()
    {
        global $conn;
        $application = new application;
        $this->quota_order_number_sid = $application->get_next_quota_order_number();
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $errors = $this->conflict_check();
        h1(count($errors));
        if (count($errors) > 0) {
            /*
 foreach ($errors as $error) {
 h1 ($error);
 }
 exit();
 */
            return ($errors);
        } else {
            $sql = "INSERT INTO quota_order_numbers_oplog
 (
 quota_order_number_sid,
 quota_order_number_id,
 validity_start_date,
 description,
 origin_quota,
 quota_scope,
 quota_staging,
 operation,
 operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
            pg_prepare($conn, "quota_order_number_insert", $sql);
            pg_execute($conn, "quota_order_number_insert", array(
                $this->quota_order_number_sid,
                $this->quota_order_number_id,
                $this->validity_start_date,
                $this->description,
                $this->origin_quota,
                $this->quota_scope,
                $this->quota_staging,
                $operation,
                $operation_date
            ));
            return (True);
        }
    }


    function update()
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $errors = array();
        $sql = "INSERT INTO quota_order_numbers_oplog
 (
 quota_order_number_sid,
 quota_order_number_id,
 validity_start_date,
 validity_end_date,
 description,
 origin_quota,
 quota_scope,
 quota_staging,
 operation,
 operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";

        pg_prepare($conn, "quota_order_number_insert", $sql);

        pg_execute($conn, "quota_order_number_insert", array(
            $this->quota_order_number_sid,
            $this->quota_order_number_id,
            $this->validity_start_date,
            $this->validity_end_date,
            $this->description,
            $this->origin_quota,
            $this->quota_scope,
            $this->quota_staging,
            $operation,
            $operation_date
        ));
    }

    function get_measure_types()
    {
        $out = "";
        foreach ($this->measure_types as $m) {
            $out .= $m . ", ";
        }
        $out = trim($out);
        $out = trim($out, ",");
        return ($out);
    }

    function conflict_check()
    {
        global $conn;
        $errors = array();
        # First, check for items that exist already and have not been end-dated
        $sql = "SELECT * FROM quota_order_numbers WHERE quota_order_number_id = $1 AND validity_end_date IS NOT NULL";
        pg_prepare($conn, "quota_order_number_conflict_check", $sql);
        $result = pg_execute($conn, "quota_order_number_conflict_check", array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                array_push($errors, "Error scenario 1");
            }
        }
        return ($errors);
    }


    public function get_quota_order_number_sid()
    {
        global $conn;
        $sql = "SELECT quota_order_number_sid FROM quota_order_numbers
 WHERE quota_order_number_id = '" . $this->quota_order_number_id . "' ORDER BY validity_start_date DESC LIMIT 1";
        $result = pg_query($conn, $sql);
        if ($result) {
            $row = pg_fetch_row($result);
            while ($row = pg_fetch_array($result)) {
                $this->quota_order_number_sid = $row['quota_order_number_sid'];
            }
        }
    }

    public function get_quota_definitions()
    {
        global $conn;
        $sql = "select quota_definition_sid, validity_start_date, validity_end_date
 from quota_definitions
 where quota_order_number_id = '" . $this->quota_order_number_id . "'
 and validity_end_date > current_date";
        //echo ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_definition = new quota_definition;
                $quota_definition->quota_definition_sid = $row['quota_definition_sid'];
                $quota_definition->validity_start_date = $row['validity_start_date'];
                $quota_definition->validity_end_date = $row['validity_end_date'];
                array_push($temp, $quota_definition);
            }
            $this->quota_definitions = $temp;
        }
    }



    public function get_origins()
    {
        global $conn;
        # Get all the quota order number exclusions
        $sql = "SELECT qonoe.quota_order_number_origin_sid, excluded_geographical_area_sid, ga.description
 FROM quota_order_number_origin_exclusions qonoe, ml.ml_geographical_areas ga, quota_order_number_origins qono, quota_order_numbers qon
 WHERE qonoe.excluded_geographical_area_sid = ga.geographical_area_sid
 AND qono.quota_order_number_origin_sid = qonoe.quota_order_number_origin_sid
 AND qon.quota_order_number_sid = qono.quota_order_number_sid
 AND qon.quota_order_number_id = '" . $this->quota_order_number_id . "'";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $quota_order_number_origin_exclusions = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_order_number_origin_sid = $row['quota_order_number_origin_sid'];
                $excluded_geographical_area_sid = $row['excluded_geographical_area_sid'];
                $description = $row['description'];
                $qonoe = new quota_order_number_origin_exclusion;
                $qonoe->set_properties($quota_order_number_origin_sid, $excluded_geographical_area_sid, $description);
                array_push($quota_order_number_origin_exclusions, $qonoe);
            }
        }

        # Get the complete list of quota order number origins
        $sql = "SELECT qono.quota_order_number_origin_sid, qono.quota_order_number_sid, qono.geographical_area_id,
 ga.description, qon.quota_order_number_id, qono.validity_start_date, qono.validity_end_date
 FROM quota_order_number_origins qono, ml.ml_geographical_areas ga, quota_order_numbers qon
 WHERE ga.geographical_area_id = qono.geographical_area_id
 AND qon.quota_order_number_sid = qono.quota_order_number_sid
 AND (qono.validity_end_date IS NULL OR qono.validity_end_date > CURRENT_DATE)
 AND quota_order_number_id = '" . $this->quota_order_number_id . "'
 ORDER BY qono.validity_start_date desc, qono.quota_order_number_sid, ga.description";
        #p ($sql);

        $result = pg_query($conn, $sql);
        $quota_order_number_origins = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_id = $row['geographical_area_id'];
                $quota_order_number_origin_sid = $row['quota_order_number_origin_sid'];
                $quota_order_number_sid = $row['quota_order_number_sid'];
                $quota_order_number_id = $row['quota_order_number_id'];
                $description = $row['description'];
                $validity_start_date = $row['validity_start_date'];
                $validity_end_date = $row['validity_end_date'];
                $qono = new quota_order_number_origin;
                $qono->set_properties($quota_order_number_origin_sid, $geographical_area_id, $quota_order_number_id, $quota_order_number_sid, $description);
                $qono->validity_start_date = $validity_start_date;
                $qono->validity_end_date = $validity_end_date;
                $qonoe_count = count($quota_order_number_origin_exclusions);
                for ($i = 0; $i < $qonoe_count; $i++) {
                    $t = $quota_order_number_origin_exclusions[$i];
                    if ($t->quota_order_number_origin_sid == $quota_order_number_origin_sid) {
                        array_push($qono->exclusions, $t);
                        #p ("Adding exclusion");
                    }
                }
                array_push($quota_order_number_origins, $qono);
            }
        }
        $this->origins = $quota_order_number_origins;
    }

    function validate_fcfs_order_number()
    {
        global $conn;

        $this->quota_order_number_id = trim($this->quota_order_number_id);
        if (strlen($this->quota_order_number_id) != 6) {
            $ret = false;
            return $ret;
        }
        if (substr($this->quota_order_number_id, 0, 3) == "094") {
            $ret = false;
            return $ret;
        }
        $sql = "select quota_order_number_sid from quota_order_numbers
        where validity_end_date is null and quota_order_number_id = $1
        order by validity_start_date desc;";
        pg_prepare($conn, "validate_fcfs_order_number", $sql);
        $result = pg_execute($conn, "validate_fcfs_order_number", array($this->quota_order_number_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $ret = true;
        } else {
            $ret = false;
        }
        return ($ret);
    }


    public function get_commodity_code_array()
    {
        $this->commodity_code_list = array();
        $ccs = $this->commodity_codes;

        $ccs = str_replace("\n", " ", $ccs);
        $ccs = str_replace("\r", " ", $ccs);


        $parts = preg_split('/\s+/', $ccs);

        foreach ($parts as $cc) {
            $gn = new goods_nomenclature;
            $gn->goods_nomenclature_item_id = $cc;
            array_push($this->commodity_code_list, $gn);
        }
    }
}
