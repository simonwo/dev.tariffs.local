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

    echo "Order number,Definition SID,Measure SID,Type,Commodity code,Origin,Definition start,Definition end,Measure start,Measure end,Initial volume,Unit code,Unit qualifier code,Monetary unit,Critical state,Critical threshold";
    echo ($delimiter);


    if (strlen($base_regulation_id) == 7) {
        $reg_clause = " and left(measure_generating_regulation_id, 7) = '" . $base_regulation_id . "'";
    } else {
        $reg_clause = " and measure_generating_regulation_id = '" . $base_regulation_id . "'";
    }

    /* Get the measures */
    $sql = "select m.measure_sid, qd.quota_definition_sid,m.measure_type_id, m.goods_nomenclature_item_id, qd.quota_order_number_id, m.geographical_area_id, 
    qd.validity_start_date as definition_start_date, qd.validity_end_date as definition_end_date,
    m.validity_start_date as measure_start_date, m.validity_end_date as measure_end_date,
    qd.initial_volume, qd.measurement_unit_code, qd.measurement_unit_qualifier_code, qd.monetary_unit_code,
    qd.maximum_precision, qd.critical_state, qd.critical_threshold
    from measures m, quota_definitions qd
    where m.ordernumber = qd.quota_order_number_id ";
    
    $sql .= $reg_clause;
    $sql .= " order by m.ordernumber, m.goods_nomenclature_item_id";

    $result = pg_query($conn, $sql);
	if  ($result) {
        $measures = array();
		while ($row = pg_fetch_array($result)) {
            $measure = new measure;
            $measure->measure_sid                       = $row['measure_sid'];
            $measure->quota_definition_sid              = $row['quota_definition_sid'];
            $measure->measure_type_id                   = $row['measure_type_id'];
            $measure->goods_nomenclature_item_id        = $row['goods_nomenclature_item_id'];
            $measure->quota_order_number_id             = $row['quota_order_number_id'];
            $measure->geographical_area_id              = $row['geographical_area_id'];
            $measure->definition_start_date             = $row['definition_start_date'];
            $measure->definition_end_date               = $row['definition_end_date'];
            $measure->measure_start_date                = $row['measure_start_date'];
            $measure->measure_end_date                  = $row['measure_end_date'];
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
        echo ($measure->measure_sid . ',');
        echo ('"' . $measure->measure_type_id . '",');
        echo ('"' . $measure->goods_nomenclature_item_id . '",');
        echo ('"' . $measure->geographical_area_id . '",');
        echo ('"' . $measure->definition_start_date . '",');
        echo ('"' . $measure->definition_end_date . '",');
        echo ('"' . $measure->measure_start_date . '",');
        echo ('"' . $measure->measure_end_date . '",');
        echo ('"' . $measure->initial_volume . '",');
        echo ('"' . $measure->measurement_unit_code . '",');
        echo ('"' . $measure->measurement_unit_qualifier_code . '",');
        echo ('"' . $measure->monetary_unit_code . '",');
        echo ('"' . $measure->maximum_precision . '",');
        echo ('"' . $measure->critical_state . '",');
        echo ('"' . $measure->critical_threshold . '",');

        echo ($delimiter);
    }
    die;

?>