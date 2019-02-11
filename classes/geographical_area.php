<?php
class geographical_area
{
	// Class properties and methods go here
	public $geographical_area_sid   = 0;
	public $geographical_area_id    = "";
	public $description             = "";
	public $geographical_code       = "";
	public $validity_start_date     = "";
	public $validity_end_date       = "";
	
	#public $members = array ();

	public function set_properties($geographical_area_sid, $geographical_area_id, $description, $geographical_code, $validity_start_date, $validity_end_date) {
		$this->geographical_area_sid    = $geographical_area_sid;
		$this->geographical_area_id		= $geographical_area_id;
		$this->description				= $description;
		$this->geographical_code		= $geographical_code;
		$this->validity_start_date		= $validity_start_date;
		$this->validity_end_date		= $validity_end_date;
	}
}