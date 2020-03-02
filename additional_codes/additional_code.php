<?php
class additional_code
{
    // Class properties and methods go here
    public $additional_code = "";
    public $additional_code_sid = null;
    public $additional_code_type_id = "";
    public $code = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $description = "";
    public $validity_start_date_day = "";
    public $validity_start_date_month = "";
    public $validity_start_date_year = "";
    public $validity_end_date_day = "";
    public $validity_end_date_month = "";
    public $validity_end_date_year = "";
    public $descriptions = array();
    public $next_id = null;

    public function __construct()
    {
        $this->get_additional_code_types();
    }


    function validate_form()
    {
        global $application;
        $errors = array();
        $this->additional_code_type_id = strtoupper(get_formvar("additional_code_type_id", "", True));
        $this->additional_code = strtoupper(get_formvar("additional_code", "", True));
        $this->additional_code_sid = strtoupper(get_formvar("additional_code_sid", "", True));

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

        # Check on the additional_code_type_id
        if (strlen($this->additional_code_type_id) != 1) {
            array_push($errors, "additional_code_type_id");
        }

        # Check on the additional code
        if (strlen($this->additional_code) != 3) {
            array_push($errors, "additional_code");
        }

        # If we are creating, check that the additional code does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "additional_code_exists");
            }
        }

        # Check on the description
        if ($application->mode == "insert") {
            if (($this->description == "") || (strlen($this->description) > 5000)) {
                array_push($errors, "description");
            }
        }

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

        //pre ($_REQUEST);
        //prend ($errors);

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&additional_code_type_id=" . $this->additional_code_type_id;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->additional_code_sid = $this->create_update("C");
            } else {
                // Do edit scripts
                $this->create_update("U");
            }
            $url = "./confirmation.html?additional_code_sid=" . $this->additional_code_sid . "&additional_code_type_id=" . $this->additional_code_type_id . "&additional_code=" . $this->additional_code . "&mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    function validate_description_form()
    {
        //prend ($_REQUEST);
        global $application;
        $errors = array();
        $this->additional_code_sid = strtoupper(get_formvar("additional_code_sid", "", True));
        $this->additional_code_type_id = strtoupper(get_formvar("additional_code_type_id", "", True));
        $this->additional_code = strtoupper(get_formvar("additional_code", "", True));
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

        # Check on the additional_code_type_id
        if (strlen($this->additional_code_type_id) != 1) {
            array_push($errors, "additional_code_type_id");
        }

        # Check on the additional_code code
        if (strlen($this->additional_code) != 3) {
            array_push($errors, "additional_code");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 5000)) {
            array_push($errors, "description");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&additional_code_type_id=" . $this->additional_code_type_id;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create_update_description("C");
            } else {
                // Do edit scripts
                $this->create_update_description("U");
            }
            $url = "./confirmation.html?additional_code=" . $this->additional_code . "&additional_code_type_id=" . $this->additional_code_type_id . "&mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    public function get_specific_description($period_sid)
    {
        global $conn;
        if ($period_sid == null) {
            $sql = "select acd.description, null as validity_start_date
            from additional_code_description_periods acdp, additional_code_descriptions acd
            where acd.additional_code_type_id = $1 and acd.additional_code = $2
            and acd.additional_code_description_period_sid = acdp.additional_code_description_period_sid
            order by validity_start_date desc limit 1;";

            pg_prepare($conn, "get_specific_description", $sql);

            $result = pg_execute($conn, "get_specific_description", array($this->additional_code_type_id, $this->additional_code));
        } else {
            $sql = "select acd.description, acdp.validity_start_date
            from additional_code_description_periods acdp, additional_code_descriptions acd
            where acd.additional_code_type_id = $1 and acd.additional_code = $2
            and acd.additional_code_description_period_sid = acdp.additional_code_description_period_sid
            and acdp.additional_code_description_period_sid = $3
            order by validity_start_date desc;";

            pg_prepare($conn, "get_specific_description", $sql);

            $result = pg_execute($conn, "get_specific_description", array($this->additional_code_type_id, $this->additional_code, $period_sid));
        }
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_array($result);
            $this->description = $row['description'];
            $this->validity_start_date = $row['validity_start_date'];
            return (true);
        }
        return (false);
    }


    public function view_url()
    {
        return ("/additional_codes/view.html?mode=view&additional_code_sid=" . $this->additional_code_sid . "&additional_code_type_id=" . $this->additional_code_type_id . "&additional_code=" . $this->additional_code);
    }

    function create_update($operation)
    {
        //prend ($_REQUEST);
        global $conn, $application;
        $operation_date = $application->get_operation_date();
        $this->additional_code_description_period_sid = $application->get_next_additional_code_description_period();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }
        if ($operation == "C") {
            $this->additional_code_sid = $application->get_next_additional_code();
            $action = "NEW ADDITIONAL CODE";
        } else {
            $action = "UPDATE TO ADDITIONAL CODE";
        }

        $status = 'In progress';
        # Create the additional_code record
        $sql = "INSERT INTO additional_codes_oplog (
            additional_code_sid, additional_code, additional_code_type_id, validity_start_date, validity_end_date,
            operation, operation_date, workbasket_id, status)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
        pg_prepare($conn, "stmt_1", $sql);
        $result = pg_execute($conn, "stmt_1", array(
            $this->additional_code_sid, $this->additional_code, $this->additional_code_type_id, $this->validity_start_date, $this->validity_end_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $description = '[{';
        $description .= '"Action": "' . $action . '",';
        $description .= '"Additional code SID": "' . $this->additional_code_sid . '",';
        $description .= '"Additional code type ID": "' . $this->additional_code_type_id . '",';
        $description .= '"Additional code ID": "' . $this->additional_code . '",';
        if ($operation == "C") {
            $description .= '"Description": "' . $this->description . '",';
        }
        $description .= '"Validity start date": "' . $this->validity_start_date . '",';
        $description .= '"Validity end date": "' . $this->validity_end_date . '"';
        $description .= '}]';
        $workbasket_item_sid = $application->session->workbasket->insert_workbasket_item($oid, "additional code", $status, $operation, $operation_date, $description);

        // Then update the additional_code record with unique ID of the workbasket item record
        $sql = "UPDATE additional_codes_oplog set workbasket_item_sid = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_sid, $oid
        ));

        if ($operation == "C") {
            # Create the additional_code description period record
            $sql = "INSERT INTO additional_code_description_periods_oplog (
            additional_code_description_period_sid, additional_code_sid, additional_code, additional_code_type_id,
            validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_sid)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
            RETURNING oid;";
            pg_prepare($conn, "stmt_3", $sql);
            $result = pg_execute($conn, "stmt_3", array(
                $this->additional_code_description_period_sid, $this->additional_code_sid, $this->additional_code, $this->additional_code_type_id,
                $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_sid
            ));

            # Create the additional_code description record
            $sql = "INSERT INTO additional_code_descriptions_oplog (
            additional_code_description_period_sid, additional_code_sid, additional_code, additional_code_type_id,
            language_id, description, operation, operation_date, workbasket_id, status, workbasket_item_sid)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)
            RETURNING oid;";
            pg_prepare($conn, "stmt_4", $sql);
            $result = pg_execute($conn, "stmt_4", array(
                $this->additional_code_description_period_sid, $this->additional_code_sid, $this->additional_code, $this->additional_code_type_id, 'EN',
                $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_sid
            ));
        }
        return ($this->additional_code_sid);
    }



    function create_update_description($operation)
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();
        if ($operation == "C") {
            $this->additional_code_description_period_sid = $application->get_next_additional_code_description_period();
            $action = "NEW ADDITIONAL CODE DESCRIPTION";
        } else {
            $this->additional_code_description_period_sid = get_formvar("additional_code_description_period_sid");
            $action = "UPDATE TO ADDITIONAL CODE DESCRIPTION";
        }
        $status = 'In progress';

        # Create the additional_code description record
        $sql = "INSERT INTO additional_code_descriptions_oplog (
            additional_code_description_period_sid, additional_code_sid, additional_code,
            additional_code_type_id, language_id, description, operation, operation_date, workbasket_id, status)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
            RETURNING oid;";
        $stmt = "create_description_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->additional_code_description_period_sid, $this->additional_code_sid, $this->additional_code,
            $this->additional_code_type_id, 'EN', $this->description, $operation, $operation_date,
            $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $description = '[{';
        $description .= '"Action": "' . $action . '",';
        $description .= '"Additional code type ID": "' . $this->additional_code_type_id . '",';
        $description .= '"Additional code ID": "' . $this->additional_code . '",';
        $description .= '"Additional code SID": "' . $this->additional_code_sid . '",';
        $description .= '"Description": "' . $this->description . '",';
        $description .= '"Period start date": "' . $this->validity_start_date . '"';
        $description .= '}]';
        $workbasket_item_sid = $application->session->workbasket->insert_workbasket_item($oid, "additional code description", $status, $operation, $operation_date, $description);

        // Then update the additional_code description record with unique ID of the workbasket item record
        $sql = "UPDATE additional_code_descriptions_oplog set workbasket_item_sid = $1 where oid = $2";
        $stmt = "update_description_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $workbasket_item_sid, $oid
        ));

        # Create the additional_code description period record
        if ($operation == "C") {
            $sql = "INSERT INTO additional_code_description_periods_oplog (additional_code_description_period_sid, additional_code, additional_code_sid,
            additional_code_type_id, validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_sid)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
            RETURNING oid;";
            $stmt = "create_description_period" . uniqid();
            pg_prepare($conn, $stmt, $sql);
            $result = pg_execute($conn, $stmt, array(
                $this->additional_code_description_period_sid, $this->additional_code, $this->additional_code_sid,
                $this->additional_code_type_id, $this->validity_start_date, $operation, $operation_date,
                $application->session->workbasket->workbasket_id, $status, $workbasket_item_sid
            ));
        }
        //die();
    }



    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM additional_codes WHERE additional_code_type_id = $1 and additional_code = $2";
        pg_prepare($conn, "additional_code_exists", $sql);
        $result = pg_execute($conn, "additional_code_exists", array($this->additional_code_type_id, $this->additional_code));
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
            $this->validity_start_date = to_date_string($this->validity_start_date_day, $this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date = to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }

    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->additional_code_sid = trim(get_querystring("additional_code_sid"));
        $this->additional_code_type_id = trim(get_querystring("additional_code_type_id"));
        $this->additional_code = trim(get_querystring("additional_code"));
        $this->period_sid = trim(get_querystring("period_sid"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                if ($description == false) {
                    $ret = $this->populate_from_db();
                } else {
                    $ret = $this->get_specific_description($this->period_sid);
                }
                if (!$ret) {
                    h1("An error has occurred - no such footnote");
                    die();
                }
                $this->get_version_control();
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    public function get_version_control()
    {
        global $conn;
        $sql = "with cte as
        (
        select operation, operation_date,
        validity_start_date, validity_end_date, status, null as description, '0' as object_precedence
        from additional_codes_oplog
        where additional_code_type_id = $1 and additional_code = $2
        union
        select ac.operation, ac.operation_date,
        validity_start_date, null as validity_end_date, ac.status, description, '1' as object_precedence
        from additional_code_descriptions_oplog ac, additional_code_description_periods_oplog acp
        where ac.additional_code_description_period_sid = acp.additional_code_description_period_sid 
        and ac.additional_code_type_id = $1 and ac.additional_code = $2
        )
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->additional_code_type_id, $this->additional_code));
        if ($result) {
            $this->versions = $result;
            return;
            $row_count = pg_num_rows($result);
            if (($row_count > 0) && (pg_num_rows($result))) {
                while ($row = pg_fetch_array($result)) {
                    $version = new additional_code();
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_end_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    array_push($this->versions, $version);
                }
            }
        }
    }

    public function populate_from_cookies()
    {
        return (true);
    }

    public function get_additional_code_types()
    {
        global $conn;
        $sql = "SELECT act.additional_code_type_id, description FROM
        additional_code_types act, additional_code_type_descriptions actd
        WHERE act.additional_code_type_id = actd.additional_code_type_id
        AND validity_end_date IS NULL ORDER BY 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $additional_code_type = new additional_code_type;
                $additional_code_type->additional_code_type_id = $row['additional_code_type_id'];
                $additional_code_type->description = $row['description'];
                array_push($temp, $additional_code_type);
            }
            $this->additional_code_types = $temp;
        }
    }

    public function get_additional_code_sid()
    {
        global $conn;
        $sql = "select additional_code_sid
        from additional_codes where additional_code_type_id = $1 and additional_code = $2
        order by validity_start_date desc limit 1;";
        pg_prepare($conn, "get_additional_code_sid", $sql);
        $result = pg_execute($conn, "get_additional_code_sid", array($this->additional_code_type_id, $this->additional_code));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->additional_code_sid = $row[0];
        } else {
            $this->additional_code_sid = Null;
        }
        return ($this->additional_code_sid);
    }

    public function get_descriptions()
    {
        global $conn;
        $sql = "select validity_start_date, acd.description, acd.additional_code_description_period_sid
        from additional_code_description_periods acdp, additional_code_descriptions acd
        where acdp.additional_code_description_period_sid = acd.additional_code_description_period_sid
        and acd.additional_code_sid = $1
        order by validity_start_date desc;";
        pg_prepare($conn, "get_descriptions", $sql);
        $result = pg_execute($conn, "get_descriptions", array($this->additional_code_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $description = new description($row['validity_start_date'], $row['description'], $row['additional_code_description_period_sid']);
                array_push($this->descriptions, $description);
            }
        }
        return ($row_count);
    }

    function populate_from_db()
    {
        global $conn;
        if ($this->additional_code_sid != "") {
            $sql = "select ac.additional_code_type_id, additional_code, validity_start_date,
            validity_end_date, code, ac.description, actd.description as additional_code_type_description,
            ac.additional_code_sid
            from ml.ml_additional_codes ac, additional_code_type_descriptions actd
            where ac.additional_code_type_id = actd.additional_code_type_id
            and ac.additional_code_sid = $1;";
            pg_prepare($conn, "get_additional_code", $sql);
            $result = pg_execute($conn, "get_additional_code", array($this->additional_code_sid));
        } else {
            $sql = "select ac.additional_code_type_id, additional_code, validity_start_date,
            validity_end_date, code, ac.description, actd.description as additional_code_type_description,
            ac.additional_code_sid
            from ml.ml_additional_codes ac, additional_code_type_descriptions actd
            where ac.additional_code_type_id = actd.additional_code_type_id
            and ac.additional_code_type_id = $1 and ac.additional_code = $2;";
            pg_prepare($conn, "get_additional_code", $sql);
            $result = pg_execute($conn, "get_additional_code", array($this->additional_code_type_id, $this->additional_code));
        }

        if ($result) {
            $row = pg_fetch_row($result);
            $this->additional_code_type_id = $row[0];
            $this->additional_code = $row[1];
            $this->validity_start_date = $row[2];
            $this->validity_end_date = $row[3];
            $this->code = $row[4];
            $this->description = $row[5];
            $this->additional_code_type_description = $row[6];
            $this->additional_code_sid = $row[7];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            if ($this->validity_end_date == "") {
                $this->validity_end_date_day = "";
                $this->validity_end_date_month = "";
                $this->validity_end_date_year = "";
            } else {
                $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
            }
            $this->get_descriptions();
            return (true);
        } else {
            return (false);
        }
    }

    function get_description_from_db()
    {
        global $conn;
        $sql = "SELECT acd.additional_code_type_id, acd.additional_code, acd.description, acdp.validity_start_date
        FROM additional_code_description_periods acdp, additional_code_descriptions acd
        WHERE acd.additional_code_description_period_sid = acdp.additional_code_description_period_sid
        AND acd.additional_code_description_period_sid = $1 ";

        pg_prepare($conn, "get_additional_code_description", $sql);
        $result = pg_execute($conn, "get_additional_code_description", array($this->additional_code_description_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->disable_additional_code_field = " disabled";
        }
    }

    public function clear_cookies()
    {
        setcookie("additional_code", "", time() + (86400 * 30), "/");
        setcookie("additional_code_type_id", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("additional_code_description", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("additional_code_validity_end_date_year", "", time() + (86400 * 30), "/");
    }

    function validate()
    {
        global $conn;

        $this->base_regulation_id = trim($this->base_regulation_id);
        if (strlen($this->base_regulation_id) != 8) {
            $ret = false;
            return $ret;
        }

        $sql = "select base_regulation_id from base_regulations where base_regulation_id = $1
        and validity_end_date is null;";
        pg_prepare($conn, "validate_base_regulation", $sql);
        $result = pg_execute($conn, "validate_base_regulation", array($this->base_regulation_id));
        $row_count = pg_num_rows($result);

        if (($result) && ($row_count > 0)) {
            $ret = true;
        } else {
            $ret = false;
        }
        return ($ret);
    }

    public function spilt_code()
    {
        if (strlen($this->code) == 4) {
            $this->additional_code_type_id = substr($this->code, 0, 1);
            $this->additional_code = substr($this->code, 1, 3);
        }
    }

    public function get_details_from_id()
    {
        global $conn;
        $sql = "select additional_code_sid, validity_start_date, validity_end_date, description
        from ml.ml_additional_codes
        where additional_code_type_id = $1 and additional_code = $2 limit 1;";
        $stmt = "get_ac" . $this->additional_code_type_id . $this->additional_code;
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->additional_code_type_id,
            $this->additional_code
        ));

        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->additional_code_sid = $row[0];
            $this->validity_start_date = $row[1];
            $this->validity_end_date = $row[2];
            $this->description = $row[3];
        }
    }
}
