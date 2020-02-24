<?php
class quota_association
{
    // Class properties and methods go here
    public $workbasket_name = "";
    public $quota_order_number_id = null;
    public $quota_order_number_sid = null;
    public $blocking_period_type = null;
    public $quota_definition_sid = null;
    public $description = "";
    public $relation_type = null;
    public $coefficient = null;
    public $sub_quota_order_number_id = null;
    public $sub_quota_definition_sid = null;
    
    function populate_from_cookies()
    {
        if ($this->quota_order_number_id == null) {
            $this->quota_order_number_id = get_cookie("quota_order_number_id");

        }
        if ($this->quota_order_number_sid == null) {
            $this->quota_order_number_sid = get_cookie("quota_order_number_sid");
        }
        $this->quota_definition_sid = get_cookie("quota_definition_sid");
        $this->coefficient = get_cookie("coefficient");
        $this->relation_type = get_cookie("relation_type");
        $this->sub_quota_order_number_id = get_cookie("sub_quota_order_number_id");
    }

    // Validate form
    function validate_form()
    {
        global $application;
        pre ($_REQUEST);

        $errors = array();
        $this->quota_order_number_sid = get_formvar("quota_order_number_sid", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_definition_sid = get_formvar("quota_definition_sid", "", True);
        $this->coefficient = get_formvar("coefficient", "", True);
        $this->relation_type = get_formvar("relation_type", "", True);
        $this->sub_quota_order_number_id = get_formvar("sub_quota_order_number_id", "", True);
        
        # Check on the main quota definition
        if (($this->quota_definition_sid == "Unspecified") || ($this->quota_definition_sid == "")) {
            array_push($errors, "quota_definition_sid");
        }

        # Check on the sub quota ID
        if ($this->sub_quota_order_number_id == "") {
            array_push($errors, "sub_quota_order_number_id");
        }

        # Check on the relation type
        if ($this->relation_type == "") {
            array_push($errors, "relation_type");
        }

        # Check on the coefficient
        if ($this->coefficient == "") {
            array_push($errors, "coefficient");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&quota_order_number_id=" . $this->quota_order_number_id . "&quota_order_number_sid=" . $this->quota_order_number_sid . "&quota_blocking_period_sid=" . $this->quota_blocking_period_sid;
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
        if (($this->blocking_start_date_day == "") || ($this->blocking_start_date_month == "") || ($this->blocking_start_date_year == "")) {
            $this->blocking_start_date = Null;
        } else {
            $this->blocking_start_date = to_date_string($this->blocking_start_date_day, $this->blocking_start_date_month, $this->blocking_start_date_year);
        }

        if (($this->blocking_end_date_day == "") || ($this->blocking_end_date_month == "") || ($this->blocking_end_date_year == "")) {
            $this->blocking_end_date = Null;
        } else {
            $this->blocking_end_date = to_date_string($this->blocking_end_date_day, $this->blocking_end_date_month, $this->blocking_end_date_year);
        }
    }


    function create_update($operation)
    {
        die();
        global $conn;
        $application = new application;
        $operation_date = $application->get_operation_date();
        if ($this->blocking_end_date == "") {
            $this->blocking_end_date = Null;
        }

        if ($operation == "C") {
            $this->quota_blocking_period_sid = $this->get_next_sid();
        }

        $status = 'awaiting approval';
        $sql = "INSERT INTO quota_blocking_periods_oplog (
        quota_blocking_period_sid,
        quota_definition_sid,
        blocking_start_date,
        blocking_end_date,
        description,
        operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
        RETURNING oid;";

        $stmt = "stmt_1" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->quota_blocking_period_sid,
            $this->quota_definition_sid,
            $this->blocking_start_date,
            $this->blocking_end_date,
            $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "quota_blocking_period", $status, $operation, $operation_date);
    }

    public function get_active_definitions()
    {
        //h1 ($this->quota_order_number_sid);
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
                    h1("An error has occurred - no such quota association.");
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
        $sql = "select quota_definition_sid, blocking_start_date, blocking_end_date, description 
		from quota_blocking_periods qsp where quota_blocking_period_sid = $1";
        pg_prepare($conn, "stmt1", $sql);
        $result = pg_execute($conn, "stmt1", array($this->quota_blocking_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->quota_definition_sid = $row[0];
            $this->blocking_start_date = $row[1];
            $this->blocking_end_date = $row[2];
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
        setcookie("coefficient", "", time() + (86400 * 30), "/");
        setcookie("relation_type", "", time() + (86400 * 30), "/");
        setcookie("sub_quota_order_number_id", "", time() + (86400 * 30), "/");
    }
}
