<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	//pre($_REQUEST);
	//die();

	$phase = get_formvar("phase");
    switch ($phase) {
	case "measure_edit":
		get_formvars_measure_edit();
		break;
	case "measure_condition_insert":
		get_formvars_measure_condition_insert();
		break;
	case "measure_excluded_geographical_area_delete":
		get_formvars_measure_excluded_geographical_area_delete();
		break;
	case "measure_excluded_geographical_area_insert":
		get_formvars_measure_excluded_geographical_area_insert();
		break;
	case "measure_create":
		get_formvars_measure_create();
		break;
	case "measure_search":
		get_formvars_measure_search();
		break;
	case "edit_measure":
		get_formvars_edit_measure();
		break;
	case "delete_measure":
		get_formvars_delete_measure();
		break;
	case "edit_component":
		get_formvars_edit_component();
		break;
	case "add_component":
		get_formvars_add_component();
		break;
	case "add_footnote":
		get_formvars_add_footnote();
		break;
	case "add_condition":
		get_formvars_add_condition();
		break;
	case "add_exclusion":
		get_formvars_add_exclusion();
		break;
	case "delete_component":
		get_formvars_delete_component();
		break;
	case "measure_component_insert":
		get_formvars_measure_component_insert();
		break;
	case "footnote_association_measure_insert":
		get_formvars_footnote_association_measure_insert();
		break;
	case "measure_component_update":
		get_formvars_measure_component_update();
		break;
	}

	function get_formvars_measure_edit() {
		$measure_sid						= get_querystring("measure_sid");
		$measure_generating_regulation_id	= get_querystring("measure_generating_regulation_id");
		$measure_start_day					= get_querystring("measure_start_day");
		$measure_start_month				= get_querystring("measure_start_month");
		$measure_start_year					= get_querystring("measure_start_year");
		$measure_end_day					= get_querystring("measure_end_day");
		$measure_end_month					= get_querystring("measure_end_month");
		$measure_end_year					= get_querystring("measure_end_year");
		$measure_type_id					= get_querystring("measure_type_id");
		$goods_nomenclature_item_id			= standardise_goods_nomenclature_item_id(get_querystring("goods_nomenclature_item_id"));
		$additional_code					= trim(get_querystring("additional_code"));
		$geographical_area_id				= get_querystring("geographical_area_id");
		$ordernumber						= get_querystring("ordernumber");


		if (($measure_start_day != "") and ($measure_start_month != "") and ($measure_start_year != "")) {
			$validity_start_date = to_date_string($measure_start_day, $measure_start_month, $measure_start_year);
		} else {
			$validity_start_date = Null;
		}

		if (($measure_end_day != "") and ($measure_end_month != "") and ($measure_end_year != "")) {
			$validity_end_date				= to_date_string($measure_end_day, $measure_end_month, $measure_end_year);
			$justification_regulation_id	= $measure_generating_regulation_id;
			$justification_regulation_role	= 1;
		} else {
			$validity_end_date				= Null;
			$justification_regulation_role	= Null;
			$justification_regulation_id	= Null;
		}
		if (strlen($additional_code) == 4) {
			$additional_code_type_id = substr($additional_code, 0, 1);
			$additional_code_id = substr($additional_code, 1, 3);
		} else {
			$additional_code_type_id = Null;
			$additional_code_id = Null;
		}
		$measure = new measure();

		// Get the SIDS - goods nomenclature
		$goods_nomenclature = new goods_nomenclature();
		$goods_nomenclature->goods_nomenclature_item_id = $goods_nomenclature_item_id;
		$measure->goods_nomenclature_sid				= $goods_nomenclature->get_goods_nomenclature_sid();


		// Get the SIDS - geo areas
		$geographical_area							= new geographical_area();
		$geographical_area->geographical_area_id	= $geographical_area_id;
		$measure->geographical_area_sid				= $geographical_area->get_geographical_area_sid();

		// Get the SIDS - additional code
		$add_code = new additional_code();
		if (($additional_code_type_id != Null) and ($additional_code_id != Null)) {
			$add_code->additional_code_type_id	= $additional_code_type_id;
			$add_code->additional_code			= $additional_code_id;
			$measure->additional_code_sid		= $add_code->get_additional_code_sid();
		} else {
			$measure->additional_code_sid		= Null;
		}

		$measure->measure_sid							= $measure_sid;
		$measure->measure_type_id						= $measure_type_id;
		$measure->geographical_area_id					= $geographical_area_id;
		$measure->goods_nomenclature_item_id			= $goods_nomenclature_item_id;
		$measure->additional_code_type_id				= $additional_code_type_id;
		$measure->additional_code_id					= $additional_code_id;
		$measure->ordernumber							= $ordernumber;
		$measure->reduction_indicator					= null;
		$measure->validity_start_date					= $validity_start_date;
		$measure->measure_generating_regulation_role	= 1;
		$measure->measure_generating_regulation_id		= $measure_generating_regulation_id;
		$measure->validity_end_date						= $validity_end_date;
		$measure->justification_regulation_role			= $justification_regulation_role;
		$measure->justification_regulation_id			= $justification_regulation_id;
		$measure->stopped_flag							= 0;
		$measure->export_refund_nomenclature_sid		= null;

		$measure->update_measure();
	}

	function get_formvars_edit_measure() {
		$measure_sid								= get_querystring("measure_sid");
		$url  = "/measure_create_edit.html?measure_sid=" . $measure_sid . "&phase=edit";
		header("Location: " . $url);
	}

	function get_formvars_measure_condition_insert() {
		$measure_sid								= get_querystring("measure_sid");
		$condition_code								= get_querystring("condition_code");
		$component_sequence_number					= get_querystring("component_sequence_number");
		$condition_duty_amount						= get_querystring("condition_duty_amount");
		$condition_monetary_unit_code				= get_querystring("condition_monetary_unit_code");
		$condition_measurement_unit_code			= get_querystring("condition_measurement_unit_code");
		$condition_measurement_unit_qualifier_code	= get_querystring("condition_measurement_unit_qualifier_code");
		$action_code								= get_querystring("action_code");
		$certificate_type_code						= get_querystring("certificate_type_code");
		$certificate_code							= get_querystring("certificate_code");

		if ($condition_duty_amount == "") {
			$condition_duty_amount = Null;
		}
		if ($condition_monetary_unit_code == "") {
			$condition_monetary_unit_code = Null;
		}
		if ($condition_measurement_unit_code == "") {
			$condition_measurement_unit_code = Null;
		}
		if ($condition_measurement_unit_qualifier_code == "") {
			$condition_measurement_unit_qualifier_code = Null;
		}
		if ($action_code == "") {
			$action_code = Null;
		}
		if ($certificate_type_code == "") {
			$certificate_type_code = Null;
		}
		if ($certificate_code == "") {
			$certificate_code = Null;
		}
		
		$measure_condition												= new measure_condition();
		$measure_condition->measure_sid									= $measure_sid;
		$measure_condition->condition_code								= $condition_code;
		$measure_condition->component_sequence_number					= $component_sequence_number;
		$measure_condition->condition_duty_amount						= $condition_duty_amount;
		$measure_condition->condition_monetary_unit_code				= $condition_monetary_unit_code;
		$measure_condition->condition_measurement_unit_code				= $condition_measurement_unit_code;
		$measure_condition->condition_measurement_unit_qualifier_code	= $condition_measurement_unit_qualifier_code;
		$measure_condition->action_code									= $action_code;
		$measure_condition->certificate_type_code						= $certificate_type_code;
		$measure_condition->certificate_code							= $certificate_code;
		$measure_condition->insert();
		$url  = "/measure_view.html?measure_sid=" . $measure_sid . "#measure_conditions";
		header("Location: " . $url);
	}

	function get_formvars_measure_excluded_geographical_area_delete() {
		$measure_sid				= get_querystring("measure_sid");
		$excluded_geographical_area	= get_querystring("excluded_geographical_area");
		$measure					= new measure();
		$measure->measure_sid		= $measure_sid;
		$measure->measure_excluded_geographical_area_delete($excluded_geographical_area);
		$url  = "/measure_view.html?measure_sid=" . $measure_sid . "#measure_excluded_geographical_areas";
		header("Location: " . $url);
	}

	function get_formvars_measure_excluded_geographical_area_insert() {
		$measure_sid				= get_querystring("measure_sid");
		$excluded_geographical_area	= get_querystring("excluded_geographical_area");
		$geo = new geographical_area();
		$geo->geographical_area_id = $excluded_geographical_area;
		$geo->get_geographical_area_sid();
		$measure = new measure();
		$measure->measure_sid = $measure_sid;
		$measure->measure_excluded_geographical_area_insert($excluded_geographical_area, $geo->geographical_area_sid);
		$url  = "/measure_view.html?measure_sid=" . $measure_sid . "#measure_excluded_geographical_areas";
		header("Location: " . $url);

	}

	function get_formvars_measure_create() {
		$error_list             = array();

		$measure_generating_regulation_id	= get_querystring("measure_generating_regulation_id");
		$measure_start_day					= get_querystring("measure_start_day");
		$measure_start_month				= get_querystring("measure_start_month");
		$measure_start_year					= get_querystring("measure_start_year");
		$measure_end_day					= get_querystring("measure_end_day");
		$measure_end_month					= get_querystring("measure_end_month");
		$measure_end_year					= get_querystring("measure_end_year");
		$measure_type_id					= strtoupper(get_querystring("measure_type_id"));
		$ordernumber						= get_querystring("ordernumber");

		// Get validity start date
		if (($measure_start_day != "") and ($measure_start_month != "") and ($measure_start_year != "")) {
			$valid_start_date = checkdate($measure_start_month, $measure_start_day, $measure_start_year);
			if ($valid_start_date) {
				$validity_start_date = to_date_string($measure_start_day, $measure_start_month, $measure_start_year);
			} else {
				array_push($error_list, "validity_start_date");
			}
		} else {
			array_push($error_list, "validity_start_date");
		}

		// Get validity end date
		$specified_count = 0;
		
		if ($measure_end_day != "")		{ $specified_count += 1;}
		if ($measure_end_month != "")	{ $specified_count += 1;}
		if ($measure_end_year != "")	{ $specified_count += 1;}

		if (($measure_end_day != "") and ($measure_end_month != "") and ($measure_end_year != "")) {
			$valid_end_date = checkdate($measure_end_month, $measure_end_day, $measure_end_year);
			if ($valid_end_date) {
				$validity_end_date = to_date_string($measure_end_day, $measure_end_month, $measure_end_year);
			} else {
				array_push($error_list, "validity_end_date");
			}
		} elseif (($specified_count > 0)) {
			array_push($error_list, "validity_end_date");
		}

		// Get validity end date
		if (($measure_end_day != "") and ($measure_end_month != "") and ($measure_end_year != "")) {
			$validity_end_date = to_date_string($measure_end_day, $measure_end_month, $measure_end_year);
			$justification_regulation_id	= $measure_generating_regulation_id;
			$justification_regulation_role	= 1;
		} else {
			$validity_end_date				= Null;
			$justification_regulation_id	= Null;
			$justification_regulation_role	= Null;
		}

		// Get goods nomenclature item id
		$goods_nomenclature_item_id						= get_querystring("goods_nomenclature_item_id");

		// Get the additional code type id and id
		$additional_code					= get_querystring("additional_code");
		if ($additional_code != "") {
			$additional_code_type_id						= strtoupper(substr($additional_code, 0, 1));
			$additional_code_id								= strtoupper(substr($additional_code, 1, 3));
			$obj_additional_code							= new additional_code();
			$obj_additional_code->additional_code_type_id	= $additional_code_type_id;
			$obj_additional_code->additional_code			= $additional_code_id;
			$additional_code_sid							= $obj_additional_code->get_additional_code_sid();
		} else {
			$additional_code_type_id	= Null;
			$additional_code_id			= Null;
			$additional_code_sid		= Null;
		}

		// Get the geo area
		$geographical_area_id						= get_querystring("geographical_area_id");



		// Perform validation on the measure generating regulation
		if ($measure_generating_regulation_id != "") {
			$base_regulation = new base_regulation();
			$base_regulation->base_regulation_id = $measure_generating_regulation_id;
			$ret = $base_regulation->validate();
			if (!$ret) {
				array_push($error_list, "base_regulation_id");
			}
		}

		// Perform validation on the measure type
		$measure_type = new measure_type();
		$measure_type->measure_type_id = $measure_type_id;
		$ret = $measure_type->validate();
		if (!$ret) {
			array_push($error_list, "measure_type_id");
		}

		// Perform validation on the quota order number
		if ($ordernumber != "") {
			$quota_order_number = new quota_order_number();
			$quota_order_number->quota_order_number_id = $ordernumber;
			$ret = $quota_order_number->validate_fcfs_order_number();
			if (!$ret) {
				array_push($error_list, "quota_order_number_id");
			}
		}

		// Perform validation on the goods nomenclature
		$goods_nomenclature_item_id						= standardise_goods_nomenclature_item_id($goods_nomenclature_item_id);
		$goods_nomenclature								= new goods_nomenclature();
		$goods_nomenclature->goods_nomenclature_item_id	= $goods_nomenclature_item_id;
		$goods_nomenclature_sid							= $goods_nomenclature->get_goods_nomenclature_sid();
		if (is_null($goods_nomenclature_sid)) {
			array_push($error_list, "goods_nomenclature_item_id");
		}

		// Perform validation on the geography
		$geographical_area							= new geographical_area();
		$geographical_area->geographical_area_id	= $geographical_area_id;
		$geographical_area_sid						= $geographical_area->get_geographical_area_sid();
		if (is_null($geographical_area_sid)) {
			array_push($error_list, "geographical_area_id");
		}

		// Perform validation on the order number
		if ($ordernumber != "") {
			$geographical_area = new geographical_area();
			$quota_order_number->quota_order_number_id = $ordernumber;
			$ret = $quota_order_number->validate_fcfs_order_number();
			if (!$ret) {
				array_push($error_list, "quota_order_number_id");
			}
		}

		
		// Perform validation on the additional code
		/*
		if ($ordernumber != "") {
			$quota_order_number = new quota_order_number();
			$quota_order_number->quota_order_number_id = $additional_code;
			$ret = $quota_order_number->validate_fcfs_order_number();
			if (!$ret) {
				array_push($error_list, "quota_order_number_id");
			}
		}
		*/
		

		if (count($error_list) > 0) {
			$error_string = serialize($error_list);
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
			echo ($error_string);
			//exit();
			header('Location: /measure_create_edit.html?err=1');
		} else {
			$measure = new measure();
			$measure->measure_generating_regulation_id		= $measure_generating_regulation_id;
			$measure->goods_nomenclature_item_id			= $goods_nomenclature_item_id;
			$measure->additional_code_type_id				= $additional_code_type_id;
			$measure->additional_code_id					= $additional_code_id;
			$measure->geographical_area_id					= $geographical_area_id;
			$measure->measure_generating_regulation_id		= $measure_generating_regulation_id;
			$measure->measure_generating_regulation_role	= 1;
			$measure->measure_type_id						= $measure_type_id;
			$measure->validity_start_date					= $validity_start_date;
			$measure->validity_end_date						= $validity_end_date;
			$measure->justification_regulation_id			= $justification_regulation_id;
			$measure->justification_regulation_role			= $justification_regulation_role;
			$measure->stopped_flag							= 0;
			$measure->geographical_area_sid					= $geographical_area_sid;
			$measure->goods_nomenclature_sid				= $goods_nomenclature_sid;
			$measure->ordernumber							= $ordernumber;
			$measure->additional_code_sid					= $additional_code_sid;
			$measure->reduction_indicator					= Null;
			$measure->export_refund_nomenclature_sid		= Null;
			$measure->insert_measure();
		}
	}

	function get_formvars_delete_measure() {
		$measure_sid = get_querystring("measure_sid");
		/* In deleting a measure, we also need to delete the following items
		   -- measure components
		   -- meeasure condition
		   -- measure condition component
		   -- measure excluded geogrraphical area
		   -- footnote association measure
		   -- measure partial temporary stop
		*/
		$measure = new measure();
		$measure->measure_sid = get_querystring("measure_sid");
		$measure->get_goods_nomenclature_item_id();

		// First, get all the subsidiary objects
		$measure->get_measure_condition_components();
		$measure->get_measure_conditions();
		$measure->get_measure_components();
		$measure->get_footnote_association_measures();
		$measure->get_measure_excluded_geographical_areas();
		$measure->get_measure_partial_temporary_stops();

		// Second, delete the subsidiary objects
		$measure->delete_measure_condition_components();
		$measure->delete_measure_conditions();
		$measure->delete_measure_components();
		$measure->delete_footnote_association_measures();
		$measure->delete_measure_excluded_geographical_areas();
		$measure->delete_measure_partial_temporary_stops();
		$measure->delete_measure();

		$url = "/measure_delete_confirm.html?measure_sid=" . $measure->measure_sid . "&goods_nomenclature_item_id=" . $measure->goods_nomenclature_item_id . "&geographical_area_id=" . $measure->geographical_area_id;
		header("Location: " . $url);
	}

	function get_formvars_measure_component_insert() {
		$measure_sid						= get_querystring("measure_sid");
		$duty_expression_id					= get_querystring("duty_expression_id");
		$duty_amount						= get_querystring("duty_amount");
		$monetary_unit_code					= get_querystring("monetary_unit_code");
		$measurement_unit_code				= get_querystring("measurement_unit_code");
		$measurement_unit_qualifier_code	= get_querystring("measurement_unit_qualifier_code");

		$measure = new measure();
		$ret = $measure->insert_component($measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code);

		header('Location: /measure_view.html?measure_sid=' . $measure_sid . '#measure_components');
	
	}

	function get_formvars_footnote_association_measure_insert() {
		$measure_sid						= get_querystring("measure_sid");
		$footnote_type_id					= get_querystring("footnote_type_id");
		$footnote_id						= get_querystring("footnote_id");
		$measure = new measure();
		$ret = $measure->insert_footnote_association_measure($measure_sid, $footnote_type_id, $footnote_id);

		header('Location: /measure_view.html?measure_sid=' . $measure_sid . '#measure_footnotes');
	
	}

	function get_formvars_measure_component_update() {
		$measure_sid						= get_querystring("measure_sid");
		$duty_expression_id					= get_querystring("duty_expression_id");
		$duty_amount						= get_querystring("duty_amount");
		$monetary_unit_code					= get_querystring("monetary_unit_code");
		$measurement_unit_code				= get_querystring("measurement_unit_code");
		$measurement_unit_qualifier_code	= get_querystring("measurement_unit_qualifier_code");

		$measure = new measure();
		$ret = $measure->update_component($measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code);

		header('Location: /measure_view.html?measure_sid=' . $measure_sid . '#measure_components');
	
	}

	function get_formvars_edit_component() {
		$measure_sid = get_querystring("measure_sid");
		$duty_expression_id = get_querystring("duty_expression_id");
		$phase = get_querystring("phase");
		header('Location: /measure_component_insert_update.html?phase=' . $phase . '&measure_sid=' . $measure_sid . '&duty_expression_id=' . $duty_expression_id);
	}

	function get_formvars_delete_component() {
		$measure_sid		= get_querystring("measure_sid");
		$duty_expression_id	= get_querystring("duty_expression_id");
		$measure = new measure();
		$ret = $measure->delete_component($measure_sid, $duty_expression_id);
		header('Location: /measure_view.html?measure_sid=' . $measure_sid . '#measure_components');
	}

	function get_formvars_add_component() {
		$measure_sid = get_querystring("measure_sid");
		$phase = "add_component_form";
		header('Location: /measure_component_insert_update.html?phase=' . $phase . '&measure_sid=' . $measure_sid);
	}

	function get_formvars_add_footnote() {
		$measure_sid = get_querystring("measure_sid");
		$phase = "add_footnote_form";
		header('Location: /footnote_association_measure_insert_update.html?phase=' . $phase . '&measure_sid=' . $measure_sid);
	}

	function get_formvars_add_condition() {
		$measure_sid = get_querystring("measure_sid");
		$phase = "add_condition_form";
		header('Location: /measure_condition_insert_update.html?phase=' . $phase . '&measure_sid=' . $measure_sid);
	
	}

	function get_formvars_add_exclusion() {
		$measure_sid = get_querystring("measure_sid");
		$phase = "add_component_form";
		header('Location: /measure_excluded_geographical_area_insert_update.html?phase=' . $phase . '&measure_sid=' . $measure_sid);
	}

	function get_formvars_phase1() {
		$error_list             = array();
		$base_regulation        = strtoupper(get_formvar("base_regulation"));
		$measure_start_day      = get_formvar("measure_start_day");
		$measure_start_month    = get_formvar("measure_start_month");
		$measure_start_year     = get_formvar("measure_start_year");
		$measure_end_day        = get_formvar("measure_end_day");
		$measure_end_month      = get_formvar("measure_end_month");
		$measure_end_year       = get_formvar("measure_end_year");
		$measure_type           = get_formvar("measure_type");
		$workbasket             = get_formvar("workbasket");
		$goods_nomenclatures    = get_formvar("goods_nomenclatures");
		$additional_codes       = get_formvar("additional_codes");
		$geographical_area_id   = get_formvar("geographical_area_id");

		setcookie("base_regulation", $base_regulation, time() + (86400 * 30), "/");



		# Check that base regulation is 8 characters long exactly
		if (strlen($base_regulation) != 8) {
			array_push($error_list, "base_regulation");
		}
		# Check that the workbasket is not blank
		if (strlen($workbasket) == "") {
			array_push($error_list, "workbasket");
		}

		# Check that the measure type is selected
		if ($measure_type == "0") {
			array_push($error_list, "measure_type");
		}

		# Nomenclature
		$goods_nomenclatures_exploded = string_to_filtered_list($goods_nomenclatures);
		foreach ($goods_nomenclatures_exploded as $g) {
			if (strlen($g) != 10) {
				array_push($error_list, "goods_nomenclatures");
				break;
			}
		}

		# Additional codes
		$additional_codes_exploded = string_to_filtered_list($additional_codes);
		foreach ($additional_codes_exploded as $ac) {
			if (strlen($ac) != 4) {
				array_push($error_list, "additional_codes");
				break;
			}
		}

		if (count($error_list) > 0) {
			$error_string = serialize($error_list);
			setcookie("errors", $error_string, time() + (86400 * 30), "/");
			#echo ($error_string);
			#exit();
			header('Location: /measure_create.html?err=1');
		}

	}


	function get_formvars_measure_search() {
		$measure_sid				= get_querystring("measure_sid");
		$goods_nomenclature_item_id	= get_querystring("goods_nomenclature_item_id");
		$measure_type_id			= get_querystring("measure_type_id");
		$geographical_area_id		= get_querystring("geographical_area_id");
		$base_regulation_id			= get_querystring("base_regulation_id");

		if ($goods_nomenclature_item_id != "") {
			$goods_nomenclature_item_id = standardise_goods_nomenclature_item_id($goods_nomenclature_item_id);
		}
		
		if ($measure_sid != "") {
			$url  = "/measure_view.html";
			$url .= "?measure_sid=" . $measure_sid;
			header("Location: " . $url);
		} elseif ($goods_nomenclature_item_id != "") {
			$url  = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
			if ($measure_type_id != "") {
				$url .= "&measure_type_id=" . $measure_type_id;
			}
			if ($geographical_area_id != "") {
				$url .= "&geographical_area_id=" . $geographical_area_id;
			}
			$url .= "#assigned";
			header("Location: " . $url);
		} elseif ($measure_type_id != "") {
			$url  = "/measure_type_view.html?measure_type_id=" . $measure_type_id;
			$url .= "#measures";
			header("Location: " . $url);
		} elseif ($geographical_area_id != "") {
			$url  = "/geographical_area_view.html?geographical_area_id=" . $geographical_area_id;
			$url .= "#measures";
			header("Location: " . $url);
		}
	}

?>