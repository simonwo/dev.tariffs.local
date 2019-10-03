<?php
    //$write_to_screen = true; // false;
    $write_to_screen = false;
    require ("includes/db.php");
	$base_regulation_id = get_querystring("base_regulation_id");
    if ($write_to_screen == false) {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $base_regulation_id . "_definition.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $delimiter = "\n";
    } else {
        $delimiter = "<br />";
    }

    echo "Order number,Definition SID,Definition start,Definition end,Initial volume,Unit code,Unit qualifier code,Monetary unit,Maximum precision,Critical state,Critical threshold";
    echo ($delimiter);


    if (strlen($base_regulation_id) == 7) {
        $reg_clause = " and left(measure_generating_regulation_id, 7) = '" . $base_regulation_id . "'";
    } else {
        $reg_clause = " and measure_generating_regulation_id = '" . $base_regulation_id . "'";
    }

    /* Get the measures */
    $sql = "select distinct qd.quota_definition_sid, qd.quota_order_number_id, qd.validity_start_date as definition_start_date,
    qd.validity_end_date as definition_end_date, qd.initial_volume, qd.measurement_unit_code,
    qd.measurement_unit_qualifier_code, qd.monetary_unit_code, qd.maximum_precision,
    qd.critical_state, qd.critical_threshold
    from measures m, quota_definitions qd, base_regulations br
    where m.ordernumber = qd.quota_order_number_id
    and m.measure_generating_regulation_id = br.base_regulation_id
    and qd.validity_start_date >= br.validity_start_date ";
    
    $sql .= $reg_clause;
    $sql .= " order by qd.quota_order_number_id, qd.validity_start_date";

    //echo ($sql);

    $result = pg_query($conn, $sql);
	if  ($result) {
        $measures = array();
		while ($row = pg_fetch_array($result)) {
            $measure = new measure;
            $measure->quota_definition_sid              = $row['quota_definition_sid'];
            $measure->quota_order_number_id             = $row['quota_order_number_id'];
            $measure->definition_start_date             = $row['definition_start_date'];
            $measure->definition_end_date               = $row['definition_end_date'];
            $measure->initial_volume                    = $row['initial_volume'];
            $measure->measurement_unit_code             = $row['measurement_unit_code'];
            $measure->measurement_unit_qualifier_code   = $row['measurement_unit_qualifier_code'];
            $measure->monetary_unit_code                = $row['monetary_unit_code'];
            $measure->maximum_precision                 = $row['maximum_precision'];
            $measure->critical_state                    = $row['critical_state'];
            $measure->critical_threshold                = $row['critical_threshold'];
            array_push($measures, $measure);
        }
    }

    /* Print them to screen or to CSV */
    $measure_count  = count($measures);
    for ($i = 0; $i < $measure_count; $i++ ) {
        $measure    = $measures[$i];

        echo ('"' . $measure->quota_order_number_id . '",');
        echo ($measure->quota_definition_sid . ',');
        echo ('"' . $measure->definition_start_date . '",');
        echo ('"' . $measure->definition_end_date . '",');
        echo ($measure->initial_volume . ',');
        echo ('"' . $measure->measurement_unit_code . '",');
        echo ('"' . $measure->measurement_unit_qualifier_code . '",');
        echo ('"' . $measure->monetary_unit_code . '",');
        echo ($measure->maximum_precision . ',');
        echo ('"' . $measure->critical_state . '",');
        echo ($measure->critical_threshold);

        echo ($delimiter);
    }
    die;

?>