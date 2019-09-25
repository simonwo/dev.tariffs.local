<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    if ($phase == "association_create") {
        get_formvars_association_create();
    }

    function get_formvars_association_create() {
        global $conn;
        $errors = array();
        //pre($_REQUEST);
        $main_quota_order_number_id         = get_formvar("main_quota_order_number_id", "", true);
        $sub_quota_order_number_id          = get_formvar("sub_quota_order_number_id", "", true);

        // Check on parent (main) quota order number
        $sql = "select * from quota_order_numbers where quota_order_number_id = $1 and validity_end_date is null";
        pg_prepare($conn, "check_main_quota", $sql);
        $result = pg_execute($conn, "check_main_quota", array($main_quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) == 0){
                array_push($errors, "main_quota_order_number_id");
            }
        }

        // Check on child (sub) quota order number
        $sql = "select * from quota_order_numbers where quota_order_number_id = $1 and validity_end_date is null";
        pg_prepare($conn, "check_sub_quota", $sql);
        $result = pg_execute($conn, "check_sub_quota", array($sub_quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) == 0){
                array_push($errors, "sub_quota_order_number_id");
            }
        }


        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "/quota_association_create.html?action=new&err=1&main_quota_order_number_id=" . $main_quota_order_number_id;
        } else {
            $url = "/quota_association_create2.html";
        }
        header("Location: " . $url);
    }

?>