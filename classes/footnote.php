<?php
class footnote
{
	// Class properties and methods go here
	public $footnote_id             = "";
	public $footnote_type_id        = "";
	public $validity_start_date     = "";
	public $validity_end_date       = "";
	public $description             = "";
	public $validity_start_day      = "";
	public $validity_start_month    = "";
	public $validity_start_year     = "";
	public $validity_end_day        = "";
	public $validity_end_month      = "";
	public $validity_end_year       = "";
	
	public $footnotes = array ();

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

		$this->get_footnote_types();
	}

	public function get_footnote_types() {
		global $conn;
		$sql = "SELECT ft.footnote_type_id, description FROM footnote_types ft, footnote_type_descriptions ftd
        WHERE ft.footnote_type_id = ftd.footnote_type_id
        AND validity_end_date IS NULL ORDER BY 1";
		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$footnote_type       = new footnote_type;
				$footnote_type->footnote_type_id	= $row['footnote_type_id'];
				$footnote_type->description      	    = $row['description'];
				array_push($temp, $footnote_type);
			}
			$this->footnote_types = $temp;
		}
	}




	public function set_properties($footnote_id, $validity_start_date, $validity_end_date, $trade_movement_code,
	$priority_code, $measure_component_applicable_code, $origin_dest_code, $order_number_capture_code, $measure_explosion_level,
	$footnote_series_id, $description, $is_quota) {
		$this->footnote_id						= $footnote_id;
		$this->validity_start_date				    = $validity_start_date;
		$this->validity_end_date				    = $validity_end_date;
		$this->trade_movement_code				    = $trade_movement_code;
		$this->priority_code				        = $priority_code;
		$this->measure_component_applicable_code    = $measure_component_applicable_code;
		$this->origin_dest_code				        = $origin_dest_code;
		$this->order_number_capture_code			= $order_number_capture_code;
		$this->measure_explosion_level				= $measure_explosion_level;
		$this->footnote_series_id				= $footnote_series_id;
		$this->description				        	= $description;
		$this->description_truncated        	    = substr($description, 0, 75);
		$this->is_quota				        		= $is_quota;
	}

    function populate_from_cookies() {
        #$this->footnote_id						    = get_cookie("footnote_id");
        #$this->footnote_type_id						= get_cookie("footnote_type_id");
        $this->validity_start_day					= get_cookie("footnote_validity_start_day");
        $this->validity_start_month					= get_cookie("footnote_validity_start_month");
        $this->validity_start_year					= get_cookie("footnote_validity_start_year");
        $this->validity_end_day						= get_cookie("footnote_validity_end_day");
        $this->validity_end_month					= get_cookie("footnote_validity_end_month");
        $this->validity_end_year					= get_cookie("footnote_validity_end_year");
        $this->description							= get_cookie("footnote_description");
		$this->heading          					= "Create new footnote";
		$this->disable_footnote_id_field		= "";
	}

	function exists() {
		global $conn;
		$exists = false;
		$sql = "SELECT * FROM footnotes WHERE footnote_id = $1 AND footnote_type_id = $2";
		pg_prepare($conn, "footnote_exists", $sql);
		$result = pg_execute($conn, "footnote_exists", array($this->footnote_id, $this->footnote_type_id));
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
	}

	function create() {
		global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
		$this->footnote_description_period_sid  = $application->get_next_footnote_description_period();
		#h1 ($this->footnote_description_period_sid);
		#exit();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}
		
		# Create the footnote record
		$sql = "INSERT INTO footnotes_oplog (footnote_id, footnote_type_id, 
		validity_start_date, validity_end_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "create_footnote", $sql);
		$result = pg_execute($conn, "create_footnote", array($this->footnote_id, $this->footnote_type_id,
		$this->validity_start_date, $this->validity_end_date, $operation, $operation_date));

		# Create the footnote description period record
		$sql = "INSERT INTO footnote_description_periods_oplog (footnote_description_period_sid, footnote_id,
		footnote_type_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "create_footnote_description_period", $sql);
		$result = pg_execute($conn, "create_footnote_description_period", array($this->footnote_description_period_sid, $this->footnote_id,
		$this->footnote_type_id, $this->validity_start_date, $operation, $operation_date));

		# Create the footnote description record
		$sql = "INSERT INTO footnote_descriptions_oplog (footnote_description_period_sid, footnote_id,
		footnote_type_id, language_id, description, operation, operation_date)
		VALUES ($1, $2, $3, 'EN', $4, $5, $6)";
		pg_prepare($conn, "create_footnote_description", $sql);
		$result = pg_execute($conn, "create_footnote_description", array($this->footnote_description_period_sid, $this->footnote_id,
		$this->footnote_type_id, $this->description, $operation, $operation_date));
		#echo ($result);
		#exit();

	}

	function delete_description() {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();

        # Get the missing details
        $this->get_missing_details();

		# Insert the footnote
		$sql = "INSERT INTO footnotes_oplog
		(footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, 'U', $4)";
		pg_prepare($conn, "footnote_insert", $sql);
		pg_execute($conn, "footnote_insert", array($this->footnote_type_id,
		$this->footnote_id, $this->validity_start_date, $operation_date));

		# Insert the footnote description period
		$sql = "INSERT INTO footnote_description_periods_oplog
		(footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "footnote_description_period_insert", $sql);
		pg_execute($conn, "footnote_description_period_insert", array($this->footnote_description_period_sid, $this->footnote_type_id,
		$this->footnote_id, $this->period_validity_start_date, $operation, $operation_date));

		# Insert the footnote description
		$sql = "INSERT INTO footnote_descriptions_oplog
		(footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6, $7)";
		pg_prepare($conn, "footnote_description_insert", $sql);
		pg_execute($conn, "footnote_description_insert", array($this->footnote_description_period_sid, "EN",
		$this->footnote_type_id, $this->footnote_id, $this->description, $operation, $operation_date));
        return (True);
	}

	function get_missing_details() {
        global $conn;
        $sql = "SELECT description, cdp.validity_start_date as period_validity_start_date, c.validity_start_date as c_validity_start_date
        FROM footnote_descriptions cd, footnote_description_periods cdp, footnotes c
        WHERE cd.footnote_description_period_sid = cdp.footnote_description_period_sid
        AND c.footnote_id = cd.footnote_id
        AND c.footnote_type_id = cd.footnote_type_id
        AND c.footnote_id = cdp.footnote_id
        AND c.footnote_type_id = cdp.footnote_type_id
        AND cd.footnote_description_period_sid = $1";
		pg_prepare($conn, "get_missing_details", $sql);
		$result = pg_execute($conn, "get_missing_details", array($this->footnote_id, $this->footnote_type_id));

		if ($result) {
			$row = pg_fetch_row($result);
            $this->description = $row[0];
			$this->period_validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[1])->format('Y-m-d');
			$this->validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[2])->format('Y-m-d');
		} else {
            $this->description = "";
			$this->period_validity_start_date = "";
			$this->validity_start_date = "";
		}

    }



	public function old_delete_description() {
		global $conn;
		$application = new application;

		# Before I can delete anything, I need to retrieve the data, so that a "D" type instruction
		# with full data can be sent
		$sql = "SELECT fd.footnote_type_id, fd.footnote_id, fd.description, fdp.validity_start_date,
		fdp.validity_end_date FROM footnote_descriptions fd, footnote_description_periods fdp
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND fd.footnote_description_period_sid = $1";
		pg_prepare($conn, "get_description", $sql);
		$this->operation = "D";
		$this->operation_date = $application->get_operation_date();
        $result = pg_execute($conn, "get_description", array($this->footnote_description_period_sid));      
        if ($result) {
            $row = pg_fetch_row($result);
        	$this->footnote_type_id  	= $row[0];
        	$this->footnote_id  		= $row[1];
        	$this->description  		= $row[2];
        	$this->validity_start_date	= $row[3];
        	$this->validity_end_date  	= $row[4];
        } else {
			exit();
		}
		# The I can do the deletes, which are actually not deletes, but inserts with a type of "D"
		# I need an instruction for both the period and the description
		$sql = "INSERT INTO footnote_description_periods_oplog (footnote_description_period_sid, footnote_type_id, 
		validity_start_date, footnote_id, validity_end_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7)";
		pg_prepare($conn, "delete_description_period", $sql);
		pg_execute($conn, "delete_description_period", array($this->footnote_description_period_sid, $this->footnote_type_id,
		$this->validity_start_date, $this->footnote_id, $this->validity_end_date, $this->operation, $this->operation_date));      

		$sql = "INSERT INTO footnote_descriptions_oplog (footnote_description_period_sid, language_id, footnote_type_id, 
		footnote_id, operation, operation_date) VALUES ($1, 'EN', $2, $3, $4, $5)";
		pg_prepare($conn, "delete_description", $sql);
		pg_execute($conn, "delete_description", array($this->footnote_description_period_sid, $this->footnote_type_id,
		$this->footnote_id, $this->operation, $this->operation_date));      
	}


	function get_start_date() {
		global $conn;
		$sql = "SELECT validity_start_date FROM footnotes
		WHERE footnote_id = $1 AND footnote_type_id = $2 ORDER BY operation_date DESC LIMIT 1";
		pg_prepare($conn, "get_footnote_validity_start_date", $sql);
		$result = pg_execute($conn, "get_footnote_validity_start_date", array($this->footnote_id, $this->footnote_type_id));

		if ($result) {
			$row = pg_fetch_row($result);
			$d = $row[0];
			return (DateTime::createFromFormat('Y-m-d H:i:s', $d)->format('Y-m-d'));
		} else {
			return ("");
		}
	}

	function insert_description($footnote_id, $footnote_type_id, $validity_start_date, $description) {
        global $conn;
        $application = new application;
        $operation = "C";
        $footnote_description_period_sid  = $application->get_next_footnote_description_period();
        $operation_date = $application->get_operation_date();

        $this->footnote_id = $footnote_id;
        $this->footnote_type_id = $footnote_type_id;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->footnote_description_period_sid = $footnote_description_period_sid;

		$this->f_validity_start_date = $this->get_start_date();

		# Insert the footnote
		$sql = "INSERT INTO footnotes_oplog
		(footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, 'U', $4)";
		pg_prepare($conn, "footnote_insert", $sql);
		pg_execute($conn, "footnote_insert", array($this->footnote_type_id,
		$this->footnote_id, $this->f_validity_start_date, $operation_date));

		# Insert the footnote description period
		$sql = "INSERT INTO footnote_description_periods_oplog
		(footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "footnote_description_period_insert", $sql);
		pg_execute($conn, "footnote_description_period_insert", array($this->footnote_description_period_sid, $this->footnote_type_id,
		$this->footnote_id, $this->validity_start_date, $operation, $operation_date));

		# Insert the footnote description
		$sql = "INSERT INTO footnote_descriptions_oplog
		(footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6, $7)";
		pg_prepare($conn, "footnote_description_insert", $sql);
		pg_execute($conn, "footnote_description_insert", array($this->footnote_description_period_sid, "EN",
		$this->footnote_type_id, $this->footnote_id, $this->description, $operation, $operation_date));
		return (True);
	}

	function update_description($footnote_id, $footnote_type_id, $validity_start_date, $description, $footnote_description_period_sid) {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $this->footnote_id = $footnote_id;
        $this->footnote_type_id = $footnote_type_id;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->footnote_description_period_sid = $footnote_description_period_sid;

		$this->f_validity_start_date = $this->get_start_date();

		# Insert the footnote
		$sql = "INSERT INTO footnotes_oplog
		(footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, 'U', $4)";
		pg_prepare($conn, "footnote_insert", $sql);
		pg_execute($conn, "footnote_insert", array($this->footnote_type_id,
		$this->footnote_id, $this->f_validity_start_date, $operation_date));

		# Insert the footnote description period
		$sql = "INSERT INTO footnote_description_periods_oplog
		(footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "footnote_description_period_insert", $sql);
		pg_execute($conn, "footnote_description_period_insert", array($footnote_description_period_sid, $footnote_type_id,
		$footnote_id, $validity_start_date, $operation, $operation_date));

		# Insert the footnote description
		$sql = "INSERT INTO footnote_descriptions_oplog
		(footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6, $7)";
		pg_prepare($conn, "footnote_description_insert", $sql);
		pg_execute($conn, "footnote_description_insert", array($footnote_description_period_sid, "EN",
		$footnote_type_id, $footnote_id, $description, $operation, $operation_date));
		return (True);
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
		
		$sql = "INSERT INTO footnotes_oplog (footnote_id, validity_start_date,
		validity_end_date, trade_movement_code, priority_code,
		measure_component_applicable_code, origin_dest_code,
		order_number_capture_code, measure_explosion_level, footnote_series_id,
		operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";

		pg_prepare($conn, "create_footnote", $sql);

		$result = pg_execute($conn, "create_footnote", array($this->footnote_id, $this->validity_start_date,
		$this->validity_end_date, $this->trade_movement_code, $this->priority_code, 
		$this->measure_component_applicable_code, $this->origin_dest_code, 
		$this->order_number_capture_code, $this->measure_explosion_level, $this->footnote_series_id, 
		$operation, $operation_date));


		$sql = "INSERT INTO footnote_descriptions_oplog (footnote_id, language_id, description,
		operation, operation_date) VALUES ($1, 'EN', $2, $3, $4)";

		pg_prepare($conn, "create_footnote_description", $sql);

		$result = pg_execute($conn, "create_footnote_description", array($this->footnote_id, $this->description,
		$operation, $operation_date));
		#echo ($result);
		#exit();
	}

	function populate_from_db() {
		global $conn;
		$sql = "SELECT description, validity_start_date, validity_end_date, description
		FROM footnotes mt, footnote_descriptions mtd
		WHERE mt.footnote_id = mtd.footnote_id
		AND mt.footnote_id = $1 ";
		pg_prepare($conn, "get_footnote", $sql);
		$result = pg_execute($conn, "get_footnote", array($this->footnote_id));

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

			$this->footnote_heading					= "Edit measure type " . $this->footnote_id;
			$this->disable_footnote_id_field		= " disabled";

		}
	}

	function get_description_from_db() {
		global $conn;
		$sql = "SELECT fd.footnote_type_id, fd.footnote_id, fd.description, fdp.validity_start_date
		FROM footnote_description_periods fdp, footnote_descriptions fd
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND fd.footnote_description_period_sid = $1 ";

		pg_prepare($conn, "get_footnote_description", $sql);
		$result = pg_execute($conn, "get_footnote_description", array($this->footnote_description_period_sid));

		if ($result) {
            $row = pg_fetch_row($result);
        	$this->description  						= $row[2];
			$this->validity_start_date					= $row[3];
			$this->validity_start_day   				= date('d', strtotime($this->validity_start_date));
			$this->validity_start_month 				= date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  				= date('Y', strtotime($this->validity_start_date));
			$this->footnote_heading					= "Edit measure type " . $this->footnote_id;
			$this->disable_footnote_id_field		= " disabled";

		}
	}

    public function clear_cookies() {
        setcookie("footnote_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_type_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("footnote_description", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_year", "", time() + (86400 * 30), "/");
	}

    function get_latest_description() {
		global $conn;
		$sql = "SELECT fd.description
		FROM footnote_description_periods fdp, footnote_descriptions fd
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND fd.footnote_id = $1 AND fd.footnote_type_id = $2  
		ORDER BY fdp.validity_start_date DESC LIMIT 1";
		
		pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array($this->footnote_id, $this->footnote_type_id));      
        if ($result) {
            $row = pg_fetch_row($result);
        	$this->description = $row[0];
        }
	}


	public function business_rule_mt3() {
		// Business rule MT3
		// When a measure type is used in a measure then the validity period of the measure type must span the validity period of the measure. 
		global $conn;
		$succeeds = true;
		$sql = "SELECT measure_sid
		FROM measures m, base_regulations r
		WHERE m.measure_generating_regulation_id = r.base_regulation_id
		AND m.footnote_id = $1
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
		AND m.footnote_id = $1
		AND (	
			(r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
			OR
			(r.effective_end_date > $2 AND m.validity_end_date IS NULL)
			OR
			(m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
		)";
		pg_prepare($conn, "business_rule_mt3", $sql);
		$result = pg_execute($conn, "business_rule_mt3", array($this->footnote_id, $this->validity_end_date));
		if ($result) {
            if (pg_num_rows($result) > 0) {
				$succeeds = false;
			}
		}
		return ($succeeds);
	}
}

