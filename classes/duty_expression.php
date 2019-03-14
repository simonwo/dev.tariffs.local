<?php
class duty_expression
{
	// Class properties and methods go here
	public $duty_expression_id	= "";
	public $description			= "";

	public function set_properties($duty_expression_id, $description) {
		$this->duty_expression_id	= $duty_expression_id;
		$this->description			= $description;
	}
} 