<?php
class additional_code
{
	// Class properties and methods go here
	public $additional_code         = "";
	public $additional_code_type_id = "";
	public $validity_start_date     = "";
	public $validity_end_date       = "";
	public $description             = "";
	public $validity_start_day      = "";
	public $validity_start_month    = "";
	public $validity_start_year     = "";
	public $validity_end_day        = "";
	public $validity_end_month      = "";
	public $validity_end_year       = "";
	
	public $certificates = array ();

    public function __construct() {


		$this->get_additional_code_types();
	}

	public function get_additional_code_types() {
		global $conn;
		$sql = "SELECT ft.certificate_type_code, description FROM certificate_types ft, certificate_type_descriptions ftd
        WHERE ft.certificate_type_code = ftd.certificate_type_code
        AND validity_end_date IS NULL ORDER BY 1";
		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$certificate_type       = new certificate_type;
				$certificate_type->certificate_type_code	= $row['certificate_type_code'];
				$certificate_type->description      	    = $row['description'];
				array_push($temp, $certificate_type);
			}
			$this->certificate_types = $temp;
		}
	}




	public function set_properties($certificate_code, $validity_start_date, $validity_end_date, $trade_movement_code,
	$priority_code, $measure_component_applicable_code, $origin_dest_code, $order_number_capture_code, $measure_explosion_level,
	$certificate_series_id, $description, $is_quota) {
		$this->certificate_code						= $certificate_code;
		$this->validity_start_date				    = $validity_start_date;
		$this->validity_end_date				    = $validity_end_date;
		$this->trade_movement_code				    = $trade_movement_code;
		$this->priority_code				        = $priority_code;
		$this->measure_component_applicable_code    = $measure_component_applicable_code;
		$this->origin_dest_code				        = $origin_dest_code;
		$this->order_number_capture_code			= $order_number_capture_code;
		$this->measure_explosion_level				= $measure_explosion_level;
		$this->certificate_series_id				= $certificate_series_id;
		$this->description				        	= $description;
		$this->description_truncated        	    = substr($description, 0, 75);
		$this->is_quota				        		= $is_quota;
	}


	function populate_from_db() {
		global $conn;
		$sql = "SELECT description, validity_start_date, validity_end_date, description
		FROM certificates mt, certificate_descriptions mtd
		WHERE mt.certificate_code = mtd.certificate_code
		AND mt.certificate_code = $1 ";
		pg_prepare($conn, "get_certificate", $sql);
		$result = pg_execute($conn, "get_certificate", array($this->certificate_code));

		if ($result) {
            $row = pg_fetch_row($result);
        	$this->description  						= $row[0];
			$this->validity_start_date					= $row[1];
			$this->validity_start_day   				= date('d', strtotime($this->validity_start_date));
			$this->validity_start_month 				= date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  				= date('Y', strtotime($this->validity_start_date));
			$this->validity_end_date					= $row[2];
			if ($this->validity_end_date == "") {
				$this->validity_end_day   					= "";
				$this->validity_end_month 					= "";
				$this->validity_end_year  					= "";
			} else {
				$this->validity_end_day   					= date('d', strtotime($this->validity_end_date));
				$this->validity_end_month 					= date('m', strtotime($this->validity_end_date));
				$this->validity_end_year  					= date('Y', strtotime($this->validity_end_date));
			}

			$this->certificate_heading					= "Edit measure type " . $this->certificate_code;
			$this->disable_certificate_code_field		= " disabled";

		}
	}

	function get_description_from_db() {
		global $conn;
		$sql = "SELECT fd.certificate_type_code, fd.certificate_code, fd.description, fdp.validity_start_date
		FROM certificate_description_periods fdp, certificate_descriptions fd
		WHERE fd.certificate_description_period_sid = fdp.certificate_description_period_sid
		AND fd.certificate_description_period_sid = $1 ";

		pg_prepare($conn, "get_certificate_description", $sql);
		$result = pg_execute($conn, "get_certificate_description", array($this->certificate_description_period_sid));

		if ($result) {
            $row = pg_fetch_row($result);
        	$this->description  						= $row[2];
			$this->validity_start_date					= $row[3];
			$this->validity_start_day   				= date('d', strtotime($this->validity_start_date));
			$this->validity_start_month 				= date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  				= date('Y', strtotime($this->validity_start_date));
			$this->certificate_heading					= "Edit measure type " . $this->certificate_code;
			$this->disable_certificate_code_field		= " disabled";

		}
	}

    public function clear_cookies() {
        setcookie("certificate_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_type_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("certificate_description", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_year", "", time() + (86400 * 30), "/");
	}

}

