<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");

    if ($phase == "extract_data") {
        $extract = new extract;
        $extract->set_parameters();
        $extract->create_subclasses();
        $extract->extract_data();
    } elseif ($phase == "extract_date_set") {
        $extract_day    = get_querystring("extract_day");
        $extract_month  = get_querystring("extract_month");
        $extract_year   = get_querystring("extract_year");
        $extract_hour   = get_querystring("extract_hour");
        $extract_minute = get_querystring("extract_minute");

        $extract_day    = str_pad($extract_day, 2, '0', STR_PAD_LEFT);
        $extract_month  = str_pad($extract_month, 2, '0', STR_PAD_LEFT);
        $extract_hour   = str_pad($extract_hour, 2, '0', STR_PAD_LEFT);
        $extract_minute = str_pad($extract_minute, 2, '0', STR_PAD_LEFT);

        $datetime = $extract_year . "-" . $extract_month . "-" . $extract_day . " " . $extract_hour . ":" . $extract_minute;
        $d = DateTime::createFromFormat('Y-m-d h:i', $datetime);
    
		global $conn, $last_exported_operation_date;
		$sql = "UPDATE ml.config SET last_exported_operation_date = $1";
		pg_prepare($conn, "update_last_exported_operation_date", $sql);
		$result = pg_execute($conn, "update_last_exported_operation_date", array($datetime));
        $url = "/extract_date_set.html";
        header("Location: " . $url);
    }
?>