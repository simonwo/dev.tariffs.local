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
	public $validity_start_day = "";
	public $validity_start_month = "";
	public $validity_start_year = "";
	public $validity_end_day = "";
	public $validity_end_month = "";
	public $validity_end_year = "";
	
	public $measure_types = array ();

    public function __construct() {
		$this->trade_movement_codes = array();
		array_push($this->trade_movement_codes, array("0", "Import measure type"));
		array_push($this->trade_movement_codes, array("1", "Export measure type"));
		array_push($this->trade_movement_codes, array("2", "Import / export measure type"));

		$this->priority_codes = array();
		array_push($this->priority_codes, array("1"));
		array_push($this->priority_codes, array("5"));

		$this->measure_component_applicable_codes = array();
		array_push($this->measure_component_applicable_codes, array("0", "Measure components MAY be applied"));
		array_push($this->measure_component_applicable_codes, array("1", "Measure components MUST be applied"));
		array_push($this->measure_component_applicable_codes, array("2", "Measure components MUST NOT be applied"));

		$this->origin_dest_codes = array();
		array_push($this->origin_dest_codes, array("0", "Origin - the measure concerns imports"));
		array_push($this->origin_dest_codes, array("1", "Destination - the measure concerns exports"));

		$this->order_number_capture_codes = array();
		array_push($this->order_number_capture_codes, array("1", "Mandatory - an order number MUST be supplied"));
		array_push($this->order_number_capture_codes, array("2", "Not permitted - an order number MUST NOT be supplied"));

		$this->get_measure_type_series();
	}

	public function get_measure_type_series() {
		global $conn;
		$sql = "SELECT mts.measure_type_series_id, mtsd.description FROM measure_type_series mts, measure_type_series_descriptions mtsd
		WHERE mts.measure_type_series_id = mtsd.measure_type_series_id
		AND mts.validity_end_date IS NULL
		ORDER BY 1";
		#p ($sql);
		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$measure_type_series       = new measure_type_series;
				$measure_type_series->measure_type_series_id	= $row['measure_type_series_id'];
				$measure_type_series->description      			= $row['description'];
				array_push($temp, $measure_type_series);
			}
			$this->measure_type_series = $temp;
		}
	}




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

    function populate_from_cookies() {
        $this->measure_type_id						= get_cookie("measure_type_id");
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
		$this->measure_type_heading					= "Create new measure type";
		$this->disable_measure_type_id_field		= "";
	}

	function exists() {
		global $conn;
		$exists = false;
		$sql = "SELECT * FROM measure_types WHERE measure_type_id = $1";
		pg_prepare($conn, "measure_type_exists", $sql);
		$result = pg_execute($conn, "measure_type_exists", array($this->measure_type_id));
		if ($result) {
            if (pg_num_rows($result) > 0) {
				$exists = true;
			}
		}
		return ($exists);
	}

	function set_dates(){
		if (($this->validity_start_day == "") || ($this->validity_start_month == "") || ($this->validity_start_year == "")) {
			$this->validity_start_date = Null;
		} else {
			$this->validity_start_date	= to_date_string($this->validity_start_day,	$this->validity_start_month, $this->validity_start_year);
		}
		
		if (($this->validity_end_day == "") || ($this->validity_end_month == "") || ($this->validity_end_year == "")) {
			$this->validity_end_date = Null;
		} else {
			$this->validity_end_date	= to_date_string($this->validity_end_day, $this->validity_end_month, $this->validity_end_year);
		}
		#h1 ("start " . $this->validity_start_date);
		#h1 ("end " . $this->validity_end_date);
		#exit();
	}

	function create() {
		global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}
		
		$sql = "INSERT INTO measure_types_oplog (measure_type_id, validity_start_date,
		validity_end_date, trade_movement_code, priority_code,
		measure_component_applicable_code, origin_dest_code,
		order_number_capture_code, measure_explosion_level, measure_type_series_id,
		operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";

		pg_prepare($conn, "create_measure_type", $sql);

		$result = pg_execute($conn, "create_measure_type", array($this->measure_type_id, $this->validity_start_date,
		$this->validity_end_date, $this->trade_movement_code, $this->priority_code, 
		$this->measure_component_applicable_code, $this->origin_dest_code, 
		$this->order_number_capture_code, $this->measure_explosion_level, $this->measure_type_series_id, 
		$operation, $operation_date));


		$sql = "INSERT INTO measure_type_descriptions_oplog (measure_type_id, language_id, description,
		operation, operation_date) VALUES ($1, 'EN', $2, $3, $4)";

		pg_prepare($conn, "create_measure_type_description", $sql);

		$result = pg_execute($conn, "create_measure_type_description", array($this->measure_type_id, $this->description,
		$operation, $operation_date));
		#echo ($result);
		#exit();
	}

	function update() {
		global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}
		
		$sql = "INSERT INTO measure_types_oplog (measure_type_id, validity_start_date,
		validity_end_date, trade_movement_code, priority_code,
		measure_component_applicable_code, origin_dest_code,
		order_number_capture_code, measure_explosion_level, measure_type_series_id,
		operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";

		pg_prepare($conn, "create_measure_type", $sql);

		$result = pg_execute($conn, "create_measure_type", array($this->measure_type_id, $this->validity_start_date,
		$this->validity_end_date, $this->trade_movement_code, $this->priority_code, 
		$this->measure_component_applicable_code, $this->origin_dest_code, 
		$this->order_number_capture_code, $this->measure_explosion_level, $this->measure_type_series_id, 
		$operation, $operation_date));


		$sql = "INSERT INTO measure_type_descriptions_oplog (measure_type_id, language_id, description,
		operation, operation_date) VALUES ($1, 'EN', $2, $3, $4)";

		pg_prepare($conn, "create_measure_type_description", $sql);

		$result = pg_execute($conn, "create_measure_type_description", array($this->measure_type_id, $this->description,
		$operation, $operation_date));
		#echo ($result);
		#exit();
	}

	function populate_from_db() {
		global $conn;
		$sql = "SELECT description, validity_start_date, validity_end_date, trade_movement_code,
		priority_code, measure_component_applicable_code, origin_dest_code,
		order_number_capture_code, measure_explosion_level, measure_type_series_id
		FROM measure_types mt, measure_type_descriptions mtd
		WHERE mt.measure_type_id = mtd.measure_type_id
		AND mt.measure_type_id = $1";
		pg_prepare($conn, "get_measure_type", $sql);
		$result = pg_execute($conn, "get_measure_type", array($this->measure_type_id));

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
			$this->trade_movement_code					= $row[3];
			$this->priority_code						= $row[4];
			$this->measure_component_applicable_code	= $row[5];
			$this->origin_dest_code						= $row[6];
			$this->order_number_capture_code			= $row[7];
			$this->measure_explosion_level				= $row[8];
			$this->measure_type_series_id				= $row[9];

			$this->measure_type_heading					= "Edit measure type " . $this->measure_type_id;
			$this->disable_measure_type_id_field		= " disabled";

		}
	}

    public function clear_cookies() {
        setcookie("measure_type_id", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("measure_type_description", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("measure_type_validity_end_year", "", time() + (86400 * 30), "/");
        setcookie("measure_type_trade_movement_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_priority_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_origin_dest_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_measure_component_applicable_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_order_number_capture_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_measure_type_series_id", "", time() + (86400 * 30), "/");
	}

	public function business_rule_mt3() {
		h1 ($this->measure_type_id);
		h1 ($this->validity_end_date);
		// Business rule MT3
		// When a measure type is used in a measure then the validity period of the measure type must span the validity period of the measure. 
		global $conn;
		$succeeds = true;
		$sql = "SELECT measure_sid
		FROM measures m, base_regulations r
		WHERE m.measure_generating_regulation_id = r.base_regulation_id
		AND m.measure_type_id = $1
		AND (	
			(r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
			OR
			(r.effective_end_date > $2 AND m.validity_end_date IS NULL)
			OR
			(m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
		)
		UNION
		SELECT measure_sid
		FROM measures m, modification_regulations r
		WHERE m.measure_generating_regulation_id = r.modification_regulation_id
		AND m.measure_type_id = $1
		AND (	
			(r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
			OR
			(r.effective_end_date > $2 AND m.validity_end_date IS NULL)
			OR
			(m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
		)";
		pg_prepare($conn, "business_rule_mt3", $sql);
		$result = pg_execute($conn, "business_rule_mt3", array($this->measure_type_id, $this->validity_end_date));
		if ($result) {
            if (pg_num_rows($result) > 0) {
				$succeeds = false;
			}
		}
		#h1 ($succeeds);
		#exit();
		return ($succeeds);
	}
}

