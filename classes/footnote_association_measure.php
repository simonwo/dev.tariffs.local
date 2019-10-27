<?php
class footnote_association_measure
{
	// Class properties and methods go here
	public $measure_sid         = Null;
	public $footnote_type_id	= Null;
	public $footnote_id			= Null;

	function populate_from_cookies() {
		$this->heading          		= "Add footnote";
	}

}