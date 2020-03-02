<?php
class quota_order_number_origin
{
    // Class properties and methods go here
    public $workbasket_name = "";
    public $quota_order_number_id = "";
    public $quota_order_number_sid = "";
    public $quota_definition_sid = null;
    public $validity_start_date = null;
    public $validity_end_date = null;
    public $geographical_area_id = null;
    public $geographical_area_sid = null;
    public $create_measures = null;
    public $geographical_area_exclusions = null;
    
    
    function populate_from_cookies()
    {
        $this->quota_order_number_id = get_cookie("quota_order_number_id");
        $this->quota_order_number_sid = get_cookie("quota_order_number_sid");
        $this->quota_definition_sid = get_cookie("quota_definition_sid");
        $this->description = get_cookie("description");
        $this->validity_start_date = get_cookie("validity_start_date");
        $this->validity_end_date = get_cookie("validity_end_date");
    }

    // Validate form
    function validate_form()
    {
        global $application;

        $errors = array();
        $this->quota_order_number_sid = get_formvar("quota_order_number_sid", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_definition_sid = get_formvar("quota_definition_sid", "", True);
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

        $this->set_dates();

        # Check on the quota definition
        if (($this->quota_definition_sid == "Unspecified") || ($this->quota_definition_sid == "")) {
            array_push($errors, "quota_definition_sid");
        }

        # Check on the suspension start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the suspension end date
        $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&quota_order_number_id=" . $this->quota_order_number_id . "&quota_order_number_sid=" . $this->quota_order_number_sid . "&quota_definition_sid=" . $this->quota_definition_sid;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create_update("C");
            } else {
                // Do edit scripts
                $this->create_update("U");
            }
            $url = "./confirmation.html?mode=" . $application->mode . "&quota_order_number_sid=" . $this->quota_order_number_sid . "&quota_order_number_id=" . $this->quota_order_number_id;
        }
        header("Location: " . $url);
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

    function get_next_sid()
    {
        global $conn, $application;
        $minimum = $application->minimum_sids["quota.suspension.periods"];
        $sql = "select max(quota_definition_sid) as maximum from quota_definitions qsp;";
        $stmt = "get_next_sid_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_array($result);
            $max_existing = $row['maximum'];
            return (max($minimum, $max_existing) + 1);
        } else {
            return ($minimum + 1);
        }
    }

    function create_update($operation)
    {
        global $conn;
        $application = new application;
        $operation_date = $application->get_operation_date();
        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        if ($operation == "C") {
            $this->quota_definition_sid = $this->get_next_sid();
        }

        $status = 'In progress';
        $sql = "INSERT INTO quota_definitions_oplog (
        quota_definition_sid,
        quota_definition_sid,
        validity_start_date,
        validity_end_date,
        description,
        operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
        RETURNING oid;";

        $stmt = "stmt_1" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->quota_definition_sid,
            $this->quota_definition_sid,
            $this->validity_start_date,
            $this->validity_end_date,
            $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $description = $this->quota_definition_sid . " - " . $this->description;
        $workbasket_item_sid = $application->session->workbasket->insert_workbasket_item($oid, "quota definition", $status, $operation, $operation_date, $description);
    }

    public function get_parameters()
    {
        global $application;
        global $error_handler;

        $this->quota_order_number_sid = trim(get_querystring("quota_order_number_sid"));
        $this->quota_order_number_id = trim(get_querystring("quota_order_number_id"));
        $this->quota_definition_sid = trim(get_querystring("quota_definition_sid"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such suspension period.");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "select quota_definition_sid, validity_start_date, validity_end_date, description 
		from quota_definitions qsp where quota_definition_sid = $1";
        pg_prepare($conn, "stmt1", $sql);
        $result = pg_execute($conn, "stmt1", array($this->quota_definition_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->quota_definition_sid = $row[0];
            $this->validity_start_date = $row[1];
            $this->validity_end_date = $row[2];
            $this->description = $row[3];

            return (true);
        } else {
            return (false);
        }
    }

    public function clear_cookies()
    {
        setcookie("quota_order_number_id", "", time() + (86400 * 30), "/");
        setcookie("quota_order_number_sid", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_sid", "", time() + (86400 * 30), "/");
        setcookie("description", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date", "", time() + (86400 * 30), "/");
    }
}
