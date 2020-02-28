<?php
class quota_suspension_period
{
    // Class properties and methods go here
    public $workbasket_name = "";
    public $quota_order_number_id = "";
    public $quota_order_number_sid = "";
    public $quota_suspension_period_sid = "";
    public $quota_definition_sid = null;
    public $description = "";
    public $suspension_start_date = null;
    public $suspension_end_date = null;

    function populate_from_cookies()
    {
        $this->quota_order_number_id = get_cookie("quota_order_number_id");
        $this->quota_order_number_sid = get_cookie("quota_order_number_sid");
        $this->quota_definition_sid = get_cookie("quota_definition_sid");
        $this->description = get_cookie("description");
        $this->suspension_start_date = get_cookie("suspension_start_date");
        $this->suspension_end_date = get_cookie("suspension_end_date");
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

        $this->suspension_start_date_day = get_formvar("suspension_start_date_day", "", True);
        $this->suspension_start_date_month = get_formvar("suspension_start_date_month", "", True);
        $this->suspension_start_date_year = get_formvar("suspension_start_date_year", "", True);
        $this->suspension_start_date_string = $this->suspension_start_date_day . "|" . $this->suspension_start_date_month . "|" . $this->suspension_start_date_year;
        setcookie("suspension_start_date_string", $this->suspension_start_date_string, time() + (86400 * 30), "/");

        $this->suspension_end_date_day = get_formvar("suspension_end_date_day", "", True);
        $this->suspension_end_date_month = get_formvar("suspension_end_date_month", "", True);
        $this->suspension_end_date_year = get_formvar("suspension_end_date_year", "", True);
        $this->suspension_end_date_string = $this->suspension_end_date_day . "|" . $this->suspension_end_date_month . "|" . $this->suspension_end_date_year;
        setcookie("suspension_end_date_string", $this->suspension_end_date_string, time() + (86400 * 30), "/");

        $this->set_dates();

        # Check on the quota definition
        if (($this->quota_definition_sid == "Unspecified") || ($this->quota_definition_sid == "")) {
            array_push($errors, "quota_definition_sid");
        }

        # Check on the suspension start date
        $valid_start_date = checkdate($this->suspension_start_date_month, $this->suspension_start_date_day, $this->suspension_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "suspension_start_date");
        }

        # Check on the suspension end date
        $valid_end_date = checkdate($this->suspension_end_date_month, $this->suspension_end_date_day, $this->suspension_end_date_year);
        if ($valid_end_date != 1) {
            array_push($errors, "suspension_end_date");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&quota_order_number_id=" . $this->quota_order_number_id . "&quota_order_number_sid=" . $this->quota_order_number_sid . "&quota_suspension_period_sid=" . $this->quota_suspension_period_sid;
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
        if (($this->suspension_start_date_day == "") || ($this->suspension_start_date_month == "") || ($this->suspension_start_date_year == "")) {
            $this->suspension_start_date = Null;
        } else {
            $this->suspension_start_date = to_date_string($this->suspension_start_date_day, $this->suspension_start_date_month, $this->suspension_start_date_year);
        }

        if (($this->suspension_end_date_day == "") || ($this->suspension_end_date_month == "") || ($this->suspension_end_date_year == "")) {
            $this->suspension_end_date = Null;
        } else {
            $this->suspension_end_date = to_date_string($this->suspension_end_date_day, $this->suspension_end_date_month, $this->suspension_end_date_year);
        }
    }

    function get_next_sid()
    {
        global $conn, $application;
        $minimum = $application->minimum_sids["quota.suspension.periods"];
        $sql = "select max(quota_suspension_period_sid) as maximum from quota_suspension_periods qsp;";
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
        if ($this->suspension_end_date == "") {
            $this->suspension_end_date = Null;
        }

        if ($operation == "C") {
            $this->quota_suspension_period_sid = $this->get_next_sid();
        }

        $status = 'In progress';
        $sql = "INSERT INTO quota_suspension_periods_oplog (
        quota_suspension_period_sid,
        quota_definition_sid,
        suspension_start_date,
        suspension_end_date,
        description,
        operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
        RETURNING oid;";

        $stmt = "stmt_1" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->quota_suspension_period_sid,
            $this->quota_definition_sid,
            $this->suspension_start_date,
            $this->suspension_end_date,
            $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "quota_suspension_period", $status, $operation, $operation_date);
    }

    public function get_active_definitions()
    {
        global $conn;
        $sql = "select quota_definition_sid, validity_start_date, validity_end_date 
		from quota_definitions qd where quota_order_number_sid = $1
		order by validity_start_date desc;";
        $stmt = "get_definitions";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->quota_order_number_sid));
        $array = array();
        if ($result) {
            $row_count = pg_num_rows($result);
            if (($row_count > 0) && (pg_num_rows($result))) {
                while ($row = pg_fetch_array($result)) {
                    $obj = new reusable();
                    $obj->id = $row['quota_definition_sid'];
                    $obj->string = short_date($row['validity_start_date']) . " - " . short_date($row['validity_end_date']);
                    $obj->optgroup = "";
                    array_push($array, $obj);
                }
            }
        }
        return ($array);
    }

    public function get_parameters()
    {
        global $application;
        global $error_handler;

        $this->quota_order_number_sid = trim(get_querystring("quota_order_number_sid"));
        $this->quota_order_number_id = trim(get_querystring("quota_order_number_id"));
        //h1 ($this->quota_order_number_sid);

        if (isset($_GET["err"])) {
            $this->populate_from_cookies();
        }
        elseif ($application->mode == "insert") {
            $this->clear_cookies();
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
        $sql = "select quota_definition_sid, suspension_start_date, suspension_end_date, description 
		from quota_suspension_periods qsp where quota_suspension_period_sid = $1";
        pg_prepare($conn, "stmt1", $sql);
        $result = pg_execute($conn, "stmt1", array($this->quota_suspension_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->quota_definition_sid = $row[0];
            $this->suspension_start_date = $row[1];
            $this->suspension_end_date = $row[2];
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
        setcookie("suspension_start_date", "", time() + (86400 * 30), "/");
        setcookie("suspension_end_date", "", time() + (86400 * 30), "/");
    }
}
