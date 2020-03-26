<?php
class reference_document
{
    // Class properties and methods go here
    public $unique_id  = "";
    public $area_name             = "";
    public $country_codes = "";
    public $agreement_title = "";
    public $agreement_date = "";
    public $agreement_date_string = "";
    public $agreement_version = "";
    public $date_created = "";
    public $last_updated = "";

    public function get_parameters()
    {
        global $error_handler, $application;
        $this->unique_id = trim(get_querystring("unique_id"));
        $application->mode = trim(get_querystring("mode"));
        if ($application->mode == "") {
            $application->mode = "insert";
        }

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such reference document");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    public function clear_cookies()
    {
        setcookie("additional_code_type_id", "", time() + (86400 * 30), "/");
        setcookie("description", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_string", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_string", "", time() + (86400 * 30), "/");
        setcookie("application_code", "", time() + (86400 * 30), "/");
    }

    function populate_from_cookies()
    {
        $this->additional_code_type_id = get_cookie("additional_code_type_id");
        $this->validity_start_date_day = get_cookie("validity_start_date_day");
        $this->validity_start_date_month = get_cookie("validity_start_date_month");
        $this->validity_start_date_year = get_cookie("validity_start_date_year");
        $this->validity_start_date_string = get_cookie("validity_start_date_string");

        $this->validity_end_date_day = get_cookie("validity_end_date_day");
        $this->validity_end_date_month = get_cookie("validity_end_date_month");
        $this->validity_end_date_year = get_cookie("validity_end_date_year");
        $this->validity_end_date_string = get_cookie("validity_end_date_string");

        $this->description = get_cookie("description");
        $this->application_code = get_cookie("application_code");
        $this->id_disabled = false;
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "select unique_id, area_name, country_codes, agreement_title, agreement_date, agreement_version, date_created 
        from reference_documents where unique_id = $1";
        pg_prepare($conn, "get_reference_document", $sql);
        $result = pg_execute($conn, "get_reference_document", array($this->unique_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->unique_id = $row[0];
            $this->area_name = $row[1];
            $this->country_codes = $row[2];
            $this->agreement_title = $row[3];
            $this->agreement_date = $row[4];
            $this->agreement_version = $row[5];
            $this->date_created = $row[6];

            return (true);
        } else {
            return (false);
        }
    }

    // Get measure types
    function get_measure_types()
    {
        global $conn;
        $sql = "select actmt.measure_type_id, description, validity_start_date, validity_end_date
        from additional_code_type_measure_types actmt, measure_type_descriptions mtd
        where actmt.measure_type_id = mtd.measure_type_id
        and additional_code_type_id = $1 order by 1;";
        pg_prepare($conn, "get_measure_types", $sql);
        $result = pg_execute($conn, "get_measure_types", array($this->additional_code_type_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $temp = new measure_type;
                $temp->measure_type_id = $row[0];
                $temp->description = $row[1];
                $temp->validity_start_date = $row[2];
                $temp->validity_end_date = $row[3];

                array_push($this->measure_types, $temp);
            }
        }
        //pre($this->measure_types);
    }

    // Validate form
    function validate_form()
    {
        global $application;
        $errors = array();
        $this->additional_code_type_id = get_formvar("additional_code_type_id", "", True);
        $this->description = get_formvar("description", "", True);

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

        $this->application_code = get_formvar("application_code", "", True);
        $application->mode = get_formvar("mode");
        $this->set_dates();

        # Check on the measure type series id
        if (strlen($this->additional_code_type_id) != 1) {
            array_push($errors, "additional_code_type_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "additional_code_type_exists");
            }
        }

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 500)) {
            array_push($errors, "description");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        # Check on the application code
        if ($this->application_code == "") {
            array_push($errors, "application_code");
        }


        /*
 # Check business rules
 # If we are setting an end date on a measure type, there must be no measures of this type that extend beyond
 # the newly-set end date
 $this->set_dates();
 if ($this->validity_end_date != Null) {
 if ($this->business_rule_mt3() == false) {
 array_push($errors, "validity_end_date_mt3");
 }
 }

 */
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&measure_type_id=" . $this->measure_type_id;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create_update("C");
            } else {
                // Do edit scripts
                $this->create_update("U");
            }
            $url = "./confirmation.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function create_update($operation)
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }
        if ($operation == "C") {
            $action = "NEW FOOTNOTE TYPE";
        } else {
            $action = "UPDATE TO FOOTNOTE TYPE";
        }

        $status = 'In progress';
        # Create the additional_code_type record
        $sql = "INSERT INTO additional_code_types_oplog (
            additional_code_type_id, application_code,
            validity_start_date, validity_end_date, operation, operation_date, workbasket_id, status
            )
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING oid;";

        pg_prepare($conn, "stmt_1", $sql);
        $result = pg_execute($conn, "stmt_1", array(
            $this->additional_code_type_id, $this->application_code,
            $this->validity_start_date, $this->validity_end_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $description = '[{';
        $description .= '"Action": "' . $action . '",';
        $description .= '"Additional code type ID": "' . $this->additional_code_type_id . '",';
        $description .= '"Description": "' . $this->description . '",';
        $description .= '"Application code": "' . $this->application_code . '",';
        $description .= '"Validity start date": "' . $this->validity_start_date . '",';
        $description .= '"Validity end date": "' . $this->validity_end_date . '"';
        $description .= '}]';
        $workbasket_item_sid = $application->session->workbasket->insert_workbasket_item($oid, "additional code type", $status, $operation, $operation_date, $description);

        // Then update the additional code type record with unique ID of the workbasket item record
        $sql = "UPDATE additional_code_types_oplog set workbasket_item_sid = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_sid, $oid
        ));

        # Create the additional_code_type description record
        $sql = "INSERT INTO additional_code_type_descriptions_oplog (
            additional_code_type_id, language_id, description,
            operation, operation_date, workbasket_id, status, workbasket_item_sid
            )
            VALUES ($1, 'EN', $2, $3, $4, $5, $6, $7)
            RETURNING oid;";

        pg_prepare($conn, "stmt_3", $sql);
        $result = pg_execute($conn, "stmt_3", array(
            $this->additional_code_type_id, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_sid
        ));
    }

    function set_dates()
    {
        if (($this->validity_start_date_day == "") || ($this->validity_start_date_month == "") || ($this->validity_start_date_year == "")) {
            $this->validity_start_date = Null;
        } else {
            $this->validity_start_date = to_date_string($this->validity_start_date_day, $this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date = to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT additional_code_type_id FROM additional_code_types WHERE additional_code_type_id = $1";
        pg_prepare($conn, "measure_type_series_exists", $sql);
        $result = pg_execute($conn, "measure_type_series_exists", array($this->additional_code_type_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }

    public function get_descriptive_fields()
    {
        // Application code
        foreach ($this->application_codes as $item) {
            if ($item->id == $this->application_code) {
                $this->application_code_description = $item->string;
            }
        }
    }
}
