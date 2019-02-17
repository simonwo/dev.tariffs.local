<?php
class measure_type
{
	// Class properties and methods go here
	public $measure_type_id                     = "";
	public $validity_start_date                 = "";
	public $validity_end_date                   = "";
	public $trade_movement_code                 = "";
	public $priority_code                       = 0;
	public $measure_component_applicable_code   = "";
	public $origin_dest_code                    = "";
	public $order_number_capture_code           = "";
	public $measure_explosion_level             = "";
	public $measure_type_series_id              = "";
	public $description                         = "";
	public $is_quota							= False;
	
	public $measure_types = array ();

	public function set_properties($measure_type_id, $validity_start_date, $validity_end_date, $trade_movement_code,
	$priority_code, $measure_component_applicable_code, $origin_dest_code, $order_number_capture_code, $measure_explosion_level,
	$measure_type_series_id, $description, $is_quota) {
		$this->measure_type_id						= $measure_type_id;
		$this->validity_start_date				    = $validity_start_date;
		$this->validity_end_date				    = $validity_end_date;
		$this->trade_movement_code				    = $trade_movement_code;
		$this->priority_code				        = $priority_code;
		$this->measure_component_applicable_code    = $measure_component_applicable_code;
		$this->origin_dest_code				        = $origin_dest_code;
		$this->order_number_capture_code			= $order_number_capture_code;
		$this->measure_explosion_level				= $measure_explosion_level;
		$this->measure_type_series_id				= $measure_type_series_id;
		$this->description				        	= $description;
		$this->description_truncated        	    = substr($description, 0, 75);
		$this->is_quota				        		= $is_quota;
	}
} 