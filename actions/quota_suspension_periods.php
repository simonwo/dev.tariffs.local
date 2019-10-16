<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    if ($phase == "suspension_period_create") {
        get_formvars_suspension_period_create();
    }

    function get_formvars_suspension_period_create() {
        global $conn;
        $errors = array();
        pre($_REQUEST);
        //quit();
        $workbasket_name               = get_formvar("workbasket_name", "", true);
        $quota_order_number_id         = get_formvar("quota_order_number_id", "", true);

        // Check on workbasket name
        if ($workbasket_name == "") {
            array_push($errors, "workbasket_name");
        }

        // Check on parent (main) quota order number
        $sql = "select * from quota_order_numbers where quota_order_number_id = $1 and validity_end_date is null";
        pg_prepare($conn, "check_main_quota", $sql);
        $result = pg_execute($conn, "check_main_quota", array($quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) == 0){
                array_push($errors, "quota_order_number_id");
            }
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/quota_suspension_period_create.html?action=new&err=1&quota_order_number_id=" . $quota_order_number_id;
        } else {
            $url = "/quota_suspension_period_create2.html";
        }
        header("Location: " . $url);
    }

?>