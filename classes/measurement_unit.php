<?php
class measurement_unit
{
	// Class properties and methods go here
	public $measurement_unit_code   = "";
	public $description             = "";
	
	public function set_properties($measurement_unit_code, $description) {
		$this->measurement_unit_code    = $measurement_unit_code;
		$this->description				= $description;
	}
} 