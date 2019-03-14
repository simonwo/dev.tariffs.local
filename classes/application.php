<?php
class application
{
	// Class properties and methods go here
	public $measure_types           = array ();
	public $regulation_groups       = array ();
	public $countries_and_regions   = array ();
	public $geographical_areas      = array ();
	public $members                 = array ();

	public $min_additional_code_description_periods = 20000;
	public $min_additional_codes = 20000;
	public $min_certificate_description_periods = 10000;
	public $min_footnote_description_periods = 200000;
	public $min_geographical_area_description_periods = 10000;
	public $min_geographical_areas = 10000;
	public $min_goods_nomenclature = 200000;
	public $min_goods_nomenclature_description_periods = 200000;
	public $min_goods_nomenclature_indents = 200000;
	public $min_measure_conditions = 2000000;
	public $min_measures = 5000000;
	public $min_quota_blocking_periods = 1000;
	public $min_quota_definitions = 20000;
	public $min_quota_order_number_origins = 10000;
	public $min_quota_order_numbers = 10000;
	public $min_quota_suspension_periods = 1000;
	public $min_monetary_exchange_periods = 10000;


	public function get_regulation_groups() {
		global $conn;
		$sql = "SELECT rg.regulation_group_id, description FROM regulation_groups rg, regulation_group_descriptions rgd
		WHERE rg.regulation_group_id = rgd.regulation_group_id
		AND validity_end_date IS NULL ORDER BY rgd.regulation_group_id;";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$regulation_group_id  = $row['regulation_group_id'];
				$description            = $row['description'];
				
				$regulation_group = new regulation_group;
				$regulation_group->set_properties($regulation_group_id, $description);
				array_push($temp, $regulation_group);
			}
			$this->regulation_groups = $temp;
		}
	}

	public function get_geographical_areas() {
		global $conn;
		$sql = "SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
		validity_end_date FROM ml.ml_geographical_areas WHERE geographical_code = '1' AND
		(validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
		ORDER BY geographical_area_id;";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$geographical_area_sid  = $row['geographical_area_sid'];
				$geographical_area_id   = $row['geographical_area_id'];
				$description            = $row['description'];
				$geographical_code      = $row['geographical_code'];
				$validity_start_date    = short_date($row['validity_start_date']);
				$validity_end_date      = short_date($row['validity_end_date']);
				
				$geographical_area      = new geographical_area;
				$geographical_area->set_properties($geographical_area_sid, $geographical_area_id, $description,
				$geographical_code, $validity_start_date, $validity_end_date);
				array_push($temp, $geographical_area);
			}
			$this->geographical_areas = $temp;
		}
	}

	public function get_geographical_members($parent_id) {
		global $conn;
		$sql = "SELECT child_id as geographical_area_id, child_description as description FROM ml.ml_geo_memberships WHERE parent_id = '" . $parent_id . "'
		AND (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
		ORDER BY child_id";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$geographical_area_sid  = 0;
				$geographical_area_id   = $row['geographical_area_id'];
				$description            = $row['description'];
				$geographical_code      = 0;
				$validity_start_date    = "";
				$validity_end_date      = "";
				
				$member      = new geographical_area;
				$member->set_properties($geographical_area_sid, $geographical_area_id, $description,
				$geographical_code, $validity_start_date, $validity_end_date);
				array_push($temp, $member);
			}
			$this->countries_and_regions = $temp;
		}
	}
	public function get_countries_and_regions() {
		global $conn;
		$sql = "SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
		validity_end_date FROM ml.ml_geographical_areas WHERE geographical_code != '1' AND
		(validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
		ORDER BY geographical_area_id;";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$geographical_area_sid  = $row['geographical_area_sid'];
				$geographical_area_id   = $row['geographical_area_id'];
				$description            = $row['description'];
				$geographical_code      = $row['geographical_code'];
				$validity_start_date    = short_date($row['validity_start_date']);
				$validity_end_date      = short_date($row['validity_end_date']);
				
				$geographical_area      = new geographical_area;
				$geographical_area->set_properties($geographical_area_sid, $geographical_area_id, $description,
				$geographical_code, $validity_start_date, $validity_end_date);
				array_push($temp, $geographical_area);
			}
			$this->countries_and_regions = $temp;
		}
	}

	public function get_measure_types() {
		global $conn;
		$sql = "SELECT mt.measure_type_id, validity_start_date, validity_end_date, trade_movement_code, priority_code,
		measure_component_applicable_code, origin_dest_code, order_number_capture_code, measure_explosion_level,
		measure_type_series_id, mtd.description
		FROM measure_types mt, measure_type_descriptions mtd
		WHERE mt.measure_type_id = mtd.measure_type_id
		AND (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
		ORDER BY mt.measure_type_id";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$measure_type_id      				= $row['measure_type_id'];
				$validity_start_date     			= short_date($row['validity_start_date']);
				$validity_end_date     				= short_date($row['validity_end_date']);
				$trade_movement_code      			= $row['trade_movement_code'];
				$priority_code      				= $row['priority_code'];
				$measure_component_applicable_code  = $row['measure_component_applicable_code'];
				$origin_dest_code                   = $row['origin_dest_code'];
				$order_number_capture_code      	= $row['order_number_capture_code'];
				$measure_explosion_level      		= $row['measure_explosion_level'];
				$measure_type_series_id      		= $row['measure_type_series_id'];
				$description      					= $row['description'];
				$measure_type = new measure_type;

				$quota_list = array(122, 123, 143, 145);
				if (in_array($measure_type_id, $quota_list)) {
					$is_quota = True;
				} else {
					$is_quota = False;
				}

				$measure_type->set_properties($measure_type_id, $validity_start_date, $validity_end_date, $trade_movement_code,
				$priority_code, $measure_component_applicable_code, $origin_dest_code, $order_number_capture_code, $measure_explosion_level,
				$measure_type_series_id, $description, $is_quota);
				array_push($temp, $measure_type);
			}
			$this->measure_types = $temp;
		}
	}

	public function get_measurement_units() {
		global $conn;
		$sql = "SELECT mu.measurement_unit_code, description FROM measurement_units mu, measurement_unit_descriptions mud
		WHERE mu.measurement_unit_code = mud.measurement_unit_code ORDER BY 1";
		#p ($sql);
		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$measurement_unit_code  = $row['measurement_unit_code'];
				$description      		= $row['description'];
				$measurement_unit       = new measurement_unit;

				$measurement_unit->set_properties($measurement_unit_code, $description);
				array_push($temp, $measurement_unit);
			}
			$this->measurement_units = $temp;
		}
	}

	public function get_measurement_unit_qualifiers() {
		global $conn;
		$sql = "SELECT muq.measurement_unit_qualifier_code, description FROM measurement_unit_qualifiers muq, measurement_unit_qualifier_descriptions muqd
		WHERE muq.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code ORDER BY 1";

		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$measurement_unit_qualifier_code    = $row['measurement_unit_qualifier_code'];
				$description      		            = $row['description'];
				$measurement_unit_qualifier = new measurement_unit_qualifier;

				$measurement_unit_qualifier->set_properties($measurement_unit_qualifier_code, $description);
				array_push($temp, $measurement_unit_qualifier);
			}
			$this->measurement_unit_qualifiers = $temp;
		}
	}

	public function get_maximum_precisions() {
		$array = array(1, 2, 3, 4, 5);
		$this->maximum_precisions = $array;
	}

	public function get_critical_states() {
		$array = array("Y", "N");
		$this->critical_states = $array;
	}

	public function get_monetary_units() {
		$array = array("EUR");
		$this->monetary_units = $array;
	}

	function pre($data) {
		print '<pre>' . print_r($data, true) . '</pre>';
	}

	function get_single_value($sql) {
		global $conn;
		$result = pg_query($conn, $sql);
		if ($result) {
			$val = pg_fetch_result($result, 0, 0);
		}
		return ($val);
	}

	function get_next_quota_definition() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(quota_definition_sid) FROM quota_definitions");
		if ($s < $this->min_quota_definitions) {
			$s = $this->min_quota_definitions;
		}
		$s += 1;
		return ($s);
	}

	function get_next_quota_order_number() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(quota_order_number_sid) FROM quota_order_numbers");
		if ($s < $this->min_quota_order_numbers) {
			$s = $this->min_quota_order_numbers;
		}
		$s += 1;
		return ($s);
	}

	function get_next_geographical_area_description_period() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(geographical_area_description_period_sid) FROM geographical_area_description_periods");
		if ($s < $this->min_geographical_area_description_periods) {
			$s = $this->min_geographical_area_description_periods;
		}
		$s += 1;
		return ($s);
	}

	function get_next_footnote_description_period() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(footnote_description_period_sid) FROM footnote_description_periods");
		if ($s < $this->min_footnote_description_periods) {
			$s = $this->min_footnote_description_periods;
		}
		$s += 1;
		return ($s);
	}

	function get_next_certificate_description_period() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(certificate_description_period_sid) FROM certificate_description_periods");
		if ($s < $this->min_certificate_description_periods) {
			$s = $this->min_certificate_description_periods;
		}
		$s += 1;
		return ($s);
	}

	function get_next_goods_nomenclature_description_period() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(goods_nomenclature_description_period_sid) FROM goods_nomenclature_description_periods");
		if ($s < $this->min_goods_nomenclature_description_periods) {
			$s = $this->min_goods_nomenclature_description_periods;
		}
		$s += 1;
		return ($s);
	}

	function get_next_monetary_exchange_period() {
		global $conn;
		$s = $this->get_single_value("SELECT MAX(monetary_exchange_period_sid) FROM monetary_exchange_periods");
		if ($s < $this->min_monetary_exchange_periods) {
			$s = $this->min_monetary_exchange_periods;
		}
		$s += 1;
		return ($s);
	}

	function get_operation_date() {
		$date = date('Y-m-d H:i:s');
		return ($date);
	}
}
