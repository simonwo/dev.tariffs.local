<?php
class measure
{
	// Class properties and methods go here
	public function __construct() {
		$this->geographical_area_description    = "";
		$this->geographical_area_id    			= "";
		$this->assigned                         = False;
		$this->combined_duty          	        = "";
		$this->duty_list              			= array();
		$this->siv_component_list       		= array();
		$this->footnote_list              		= array();
		$this->condition_list              		= array();
		$this->mega_list	              		= array();
		$this->suppress							= False;
		$this->marked							= False;
		$this->significant_children   			= False;
		$this->measure_count          			= 0;
		$this->measure_type_count     			= 0;
		$this->additional_code_id				= "";
		$this->additional_code_type_id			= "";
	}

	public function set_properties($measure_sid, $commodity_code, $quota_order_number_id, $validity_start_date,
	$validity_end_date, $geographical_area_id, $measure_type_id, $additional_code_type_id,
	$additional_code_id, $regulation_id_full, $measure_type_description = "") {
		$this->measure_sid				= $measure_sid;
		$this->commodity_code			= $commodity_code;
		$this->quota_order_number_id    = $quota_order_number_id;
		$this->validity_start_date		= $validity_start_date;
		$this->validity_end_date		= $validity_end_date;
		$this->geographical_area_id		= $geographical_area_id;
		$this->measure_type_id  		= $measure_type_id;
		$this->additional_code_type_id  = $additional_code_type_id;
		$this->additional_code_id		= $additional_code_id;
		$this->regulation_id_full		= $regulation_id_full;
		$this->measure_type_description = $measure_type_description;
	}

	public function get_footnote_string() {
		$s = "";
		$footnote_count = count($this->footnote_list);
		for ($j = 0; $j < $footnote_count; $j++ ) {
			$f = $this->footnote_list[$j];
			$s .= $f->footnote_type_id . $f->footnote_id . ", ";
		}
		$s = trim($s);
		$s = trim($s, ",");
		$this->footnote_string = $s;
	}

	public function get_condition_string() {
		$s = "";
		$condition_count = count($this->condition_list);
		for ($j = 0; $j < $condition_count; $j++ ) {
			$mc = $this->condition_list[$j];
			$s .= $mc->condition_string . " " . $mc->action_string . ", ";
		}
		$s = trim($s);
		$s = trim($s, ",");
		$this->condition_string = $s;
	}

	public function get_mega_string() {
		$s = "";
		$mega_count = count($this->mega_list);
		for ($j = 0; $j < $mega_count; $j++ ) {
			$mega = $this->mega_list[$j];
			$s .= $mega->excluded_geographical_area . ", ";
		}
		$s = trim($s);
		$s = trim($s, ",");
		$this->mega_string = $s;
	}

	
	function populate_from_cookies() {
		$this->measure_heading						= "Create new measure";
		$this->measure_sid							= get_cookie("measure_sid");
		/*
		$this->validity_start_day					= get_cookie("measure_type_validity_start_day");
		$this->validity_start_month					= get_cookie("measure_type_validity_start_month");
		$this->validity_start_year					= get_cookie("measure_type_validity_start_year");
		$this->validity_end_day						= get_cookie("measure_type_validity_end_day");
		$this->validity_end_month					= get_cookie("measure_type_validity_end_month");
		$this->validity_end_year					= get_cookie("measure_type_validity_end_year");
		$this->description							= get_cookie("measure_type_description");
		$this->trade_movement_code					= get_cookie("measure_type_trade_movement_code");
		$this->priority_code						= get_cookie("measure_type_priority_code");
		$this->origin_dest_code						= get_cookie("measure_type_origin_dest_code");
		$this->measure_component_applicable_code	= get_cookie("measure_type_measure_component_applicable_code");
		$this->order_number_capture_code			= get_cookie("measure_type_order_number_capture_code");
		$this->measure_type_series_id				= get_cookie("measure_type_measure_type_series_id");
		$this->disable_measure_type_id_field		= "";
		*/
	}

	public function get_siv_specific(){
		$s = 0;
		if (count($this->siv_component_list) > 0) {
			$s = floatval($this->siv_component_list[0]->duty_amount);
		}
		$this->combined_duty = "<span class='entry_price'>Entry Price</span> " . number_format($s, 3) . "%";
	}

	public function combine_duties(){
		$this->combined_duty      = "";
		$this->measure_list         = array();
		$this->measure_type_list    = array();
		$this->additional_code_list = array();

		foreach ($this->duty_list as $d) {
			$d->geographical_area_id = $this->geographical_area_id;
			array_push($this->measure_type_list, $d->measure_type_id);
			array_push($this->measure_list, $d->measure_sid);
			array_push($this->additional_code_list, $d->additional_code_id);
		}

		$measure_type_list_unique    = set($this->measure_type_list);
		$measure_list_unique         = set($this->measure_list);
		$additional_code_list_unique = set($this->additional_code_list);

		$this->measure_count            = count($measure_list_unique);
		$this->measure_type_count       = count($measure_type_list_unique);
		$this->additional_code_count    = count($additional_code_list_unique);
		
		if (($this->measure_count == 1) && ($this->measure_type_count == 1) && ($this->additional_code_count == 1)) {
			foreach ($this->duty_list as $d) {
				$this->combined_duty .= $d->duty_string . " ";
			}
		} else {
			if ($this->measure_type_count > 1) {
				if (in_array("105", $measure_type_list_unique)) {
					foreach ($this->duty_list as $d) {
						if ($d->measure_type_id == "105") {
							$this->combined_duty .= $d->duty_string . " ";
						}
					}
				}
			} elseif ($this->additional_code_count > 1) {
				if (in_array("500", $additional_code_list_unique)) {
					foreach ($this->duty_list as $d) {
						if ($d->additional_code_id == "500") {
							$this->combined_duty .= $d->duty_string . " ";
						}
					}
				}
				if (in_array("500", $additional_code_list_unique)) {
					foreach ($this->duty_list as $d) {
						if ($d->additional_code_id == "500") {
							$this->combined_duty .= $d->duty_string . " ";
						}
					}
				}
			}
		}
	
		$this->combined_duty = str_replace("  ", " ", $this->combined_duty);
		$this->combined_duty = trim($this->combined_duty);

		# Now add in the Meursing components
		$ad = strpos($this->combined_duty, "AC");
		$sd = strpos($this->combined_duty, "SD");
		$fd = strpos($this->combined_duty, "FD");

		if (($ad) || ($sd) || ($fd)) {
			$this->combined_duty = "CAD - " . $this->combined_duty . ") 100%";
			$this->combined_duty = preg_replace("/ \+ /", " + (", $this->combined_duty, 1);
		}
	}
}