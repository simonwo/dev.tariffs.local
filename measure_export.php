<?php
	$write_to_screen = true; // false;
	$write_to_screen = false;
	require ("includes/db.php");
	$base_regulation_id = get_querystring("base_regulation_id");
	$measure_type_id    = get_querystring("measure_type_id");
	
	if ($base_regulation_id != "") {
		$filename = $base_regulation_id . ".csv";
	} elseif ($measure_type_id != "") {
		$filename = "measure_type_" . $measure_type_id . ".csv";
	}

	
	if ($write_to_screen == false) {
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=" . $filename);
		header("Pragma: no-cache");
		header("Expires: 0");
		$delimiter = "\n";
	} else {
		$delimiter = "<br />";
	}


	echo "ID,Regulation,Type,Start date,End date,Commodity code,Additional code,Order number,Origin,Origin exclusions,Duties,Conditions,Footnotes";
	echo ($delimiter);

	if ($base_regulation_id != "") {
		if (strlen($base_regulation_id) == 7) {
			$reg_clause = " and left(measure_generating_regulation_id, 7) = '" . $base_regulation_id . "'";
		} else {
			$reg_clause = " and measure_generating_regulation_id = '" . $base_regulation_id . "'";
		}
	}

	$conditional_clause = "";
	if ($base_regulation_id != "") {
		$conditional_clause = $reg_clause;
	} elseif ($measure_type_id != "") {
		$conditional_clause = " and m.measure_type_id = '" . $measure_type_id . "'";
	}

	/* Get the measures */
	$sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, m.geographical_area_id, m.measure_generating_regulation_id, 
	m.validity_start_date, m.validity_end_date, m.additional_code_type_id, m.additional_code_id,
	mtd.description as measure_type_description, g.description as geographical_area_description, m.ordernumber
	from ml.measures_real_end_dates m, measure_type_descriptions mtd, ml.ml_geographical_areas g
	where m.measure_type_id = mtd.measure_type_id
	and m.geographical_area_id = g.geographical_area_id ";
	$sql .= $conditional_clause;
	$sql .= " order by m.goods_nomenclature_item_id";

	$result = pg_query($conn, $sql);
	if  ($result) {
		$measures = array();
		while ($row = pg_fetch_array($result)) {
			$measure = new measure;
			$measure->measure_sid                       = $row['measure_sid'];
			$measure->measure_type_id                   = $row['measure_type_id'];
			$measure->goods_nomenclature_item_id        = $row['goods_nomenclature_item_id'];
			$measure->geographical_area_id              = $row['geographical_area_id'];
			$measure->measure_generating_regulation_id  = $row['measure_generating_regulation_id'];
			$measure->validity_start_date               = $row['validity_start_date'];
			$measure->validity_end_date                 = $row['validity_end_date'];
			$measure->additional_code_type_id           = $row['additional_code_type_id'];
			$measure->additional_code_id                = $row['additional_code_id'];
			$measure->measure_type_description          = $row['measure_type_description'];
			$measure->geographical_area_description     = $row['geographical_area_description'];
			$measure->quota_order_number_id             = $row['ordernumber'];
			array_push($measures, $measure);
		}
	}

	/* get the measure components */
	$sql = "select m.measure_sid, mc.duty_amount, mc.duty_expression_id,
	mc.measurement_unit_code, mc.measurement_unit_qualifier_code, mc.monetary_unit_code
	from measures m, measure_components mc
	where m.measure_sid = mc.measure_sid ";
	$sql .= $conditional_clause;
	$sql .= " order by m.measure_sid, mc.duty_expression_id";

	$result = pg_query($conn, $sql);
	$measure_components = array();
	if ($result) {
		while ($row = pg_fetch_array($result)) {
			$mc = new duty;
			$mc->measure_sid                        = $row['measure_sid'];
			$mc->duty_amount                        = $row['duty_amount'];
			$mc->duty_expression_id                 = $row['duty_expression_id'];
			$mc->measurement_unit_code              = $row['measurement_unit_code'];
			$mc->measurement_unit_qualifier_code    = $row['measurement_unit_qualifier_code'];
			$mc->monetary_unit_code                 = $row['monetary_unit_code'];
			array_push($measure_components, $mc);
		}
	}
	/* Assign measure compoents to measures */
	$measure_count  = count($measures);
	$mc_count = count($measure_components);
	for ($i = 0; $i < $mc_count; $i++ ) {
		$mc = $measure_components[$i];
		for ($j = 0; $j < $measure_count; $j++ ) {
			$measure = $measures[$j];
			if ($measure->measure_sid == $mc->measure_sid) {
				$mc->measure_type_id = $measure->measure_type_id;
				$mc->get_duty_string();
				array_push($measure->duty_list, $mc);
				break;
			}
		}
	}

	/* Get the footnotes */
	$sql = "select m.measure_sid, fam.footnote_type_id, fam.footnote_id
	from measures m, footnote_association_measures fam
	where m.measure_sid = fam.measure_sid ";
	$sql .= $conditional_clause;
	$sql .= " order by m.measure_sid, fam.footnote_type_id, fam.footnote_id";
	$result = pg_query($conn, $sql);
	$footnotes = array();
	if ($result) {
		while ($row = pg_fetch_array($result)) {
			$f = new footnote;
			$f->footnote_id         = $row['footnote_id'];
			$f->footnote_type_id    = $row['footnote_type_id'];
			$f->measure_sid         = $row['measure_sid'];
			array_push($footnotes, $f);
		}
	}
	/* Assign footnotes to measures */
	$footnote_count = count($footnotes);
	for ($i = 0; $i < $footnote_count; $i++ ) {
		$f = $footnotes[$i];
		for ($j = 0; $j < $measure_count; $j++ ) {
			$measure = $measures[$j];
			//$measure->footnote_list = array();
			if ($measure->measure_sid == $f->measure_sid) {
				array_push($measure->footnote_list, $f);
				break;
			}
		}
	}

	/* Get the excluded geographical areas */
	$sql = "select mega.measure_sid, mega.excluded_geographical_area, mega.geographical_area_sid
	from measures m, measure_excluded_geographical_areas mega
	where m.measure_sid = mega.measure_sid ";
	$sql .= $conditional_clause;
	$sql .= " order by m.goods_nomenclature_item_id, mega.excluded_geographical_area";
	//q ($sql);

	$result = pg_query($conn, $sql);
	$measure_excluded_geographical_areas = array();
	if ($result) {
		while ($row = pg_fetch_array($result)) {
			$mega = new measure_excluded_geographical_area;
			$mega->measure_sid                  = $row['measure_sid'];
			$mega->excluded_geographical_area   = $row['excluded_geographical_area'];
			$mega->geographical_area_sid        = $row['geographical_area_sid'];
			array_push($measure_excluded_geographical_areas, $mega);
		}
	}
	/* Assign megas to measures */
	$mega_count = count($measure_excluded_geographical_areas);
	for ($i = 0; $i < $mega_count; $i++ ) {
		$mega = $measure_excluded_geographical_areas[$i];
		for ($j = 0; $j < $measure_count; $j++ ) {
			$measure = $measures[$j];
			if ($measure->measure_sid == $mega->measure_sid) {
				array_push($measure->mega_list, $mega);
				break;
			}
		}
	}
	/* Now get the conditions */
	$sql = "select m.measure_sid, (mc.condition_code || mc.component_sequence_number) as condition_string,
	(COALESCE(mc.certificate_type_code, ' - ') || COALESCE(mc.certificate_code, '') || ' ' || COALESCE(mc.action_code, '')) as action_string
	from measures m, measure_conditions mc
	where m.measure_sid = mc.measure_sid ";
	$sql .= $conditional_clause;
	$sql .= " order by m.measure_sid, mc.condition_code, mc.component_sequence_number";
	$result = pg_query($conn, $sql);
	$conditions = array();
	if ($result) {
		while ($row = pg_fetch_array($result)) {
			$mc = new measure_condition;
			$mc->measure_sid         = $row['measure_sid'];
			$mc->condition_string    = $row['condition_string'];
			$mc->action_string      = $row['action_string'];
			array_push($conditions, $mc);
		}
	}
	/* Assign conditions to measures */
	$condition_count = count($conditions);
	for ($i = 0; $i < $condition_count; $i++ ) {
		$mc = $conditions[$i];
		for ($j = 0; $j < $measure_count; $j++ ) {
			$measure = $measures[$j];
			if ($measure->measure_sid == $mc->measure_sid) {
				array_push($measure->condition_list, $mc);
				break;
			}
		}
	}    
	/* Print them to screen or to CSV */
	for ($i = 0; $i < $measure_count; $i++ ) {
		$measure    = $measures[$i];
		$measure->footnote_string = "";
		$measure->combine_duties();
		$measure->get_footnote_string();
		$measure->get_condition_string();
		$measure->get_mega_string();

		echo ($measure->measure_sid . ',');
		echo ('"' . $measure->measure_generating_regulation_id . '",');
		echo ('"' . $measure->measure_type_id. ' (' . $measure->measure_type_description . ')",');
		echo ('"' . short_date($measure->validity_start_date) . '",');
		echo ('"' . short_date($measure->validity_end_date) . '",');
		echo ('"' . $measure->goods_nomenclature_item_id . '",');
		echo ('"' . $measure->additional_code_type_id . $measure->additional_code_id . '",');
		echo ('"' . $measure->quota_order_number_id . '",');
		echo ('"' . $measure->geographical_area_id . ' (' . $measure->geographical_area_description . ')",');
		echo ('"' . $measure->mega_string . '",');
		echo ('"' . $measure->combined_duty . '",');
		echo ('"' . $measure->condition_string . '",');
		echo ('"' . $measure->footnote_string . '"');
		echo ($delimiter);
	}
	die;

?>