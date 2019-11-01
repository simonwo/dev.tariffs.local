<?php
class quota_order_number
{
	// Class properties and methods go here
	public $quota_order_number_id           = "";
	public $quota_order_number_sid          = 0;
	public $validity_start_date             = "";
	public $validity_end_date               = "";
	public $origins							= array();
	public $measure_types					= array();
	
	#$quota_order_number_origin    = new quota_order_number_origin;

	public function set_properties($quota_order_number_id, $validity_start_date, $validity_end_date) {
		$this->quota_order_number_id    = $quota_order_number_id;
		$this->validity_start_date		= $validity_start_date;
		$this->validity_end_date		= $validity_end_date;
	}


	function populate_from_db() {
        global $conn;
        $sql = "SELECT validity_start_date, validity_end_date, description, origin_quota, quota_scope, quota_staging, quota_order_number_sid
		FROM quota_order_numbers WHERE quota_order_number_sid = $1
		order by validity_start_date desc limit 1";
        pg_prepare($conn, "quota_populate_from_db", $sql);
        $result = pg_execute($conn, "quota_populate_from_db", array($this->quota_order_number_sid));      
        if ($result) {
            if (pg_num_rows($result) > 0){
				$row = pg_fetch_row($result);
				$this->validity_start_date		= $row[0];
				$this->validity_end_date		= $row[1];
				$this->description				= $row[2];
				$this->origin_quota				= $row[3];
				$this->quota_scope				= $row[4];
				$this->quota_staging			= $row[5];
				$this->quota_order_number_sid	= $row[6];
				$this->validity_start_day   	= date('d', strtotime($this->validity_start_date));
				$this->validity_start_month 	= date('m', strtotime($this->validity_start_date));
				$this->validity_start_year  	= date('Y', strtotime($this->validity_start_date));
				if ($this->validity_end_date != "") {
					$this->validity_end_day   = date('d', strtotime($this->validity_end_date));
					$this->validity_end_month = date('m', strtotime($this->validity_end_date));
					$this->validity_end_year  = date('Y', strtotime($this->validity_end_date));
				} else {
					$this->validity_end_day   = "";
					$this->validity_end_month = "";
					$this->validity_end_year  = "";
				}
			}
        }
	}

	function populate_from_cookies() {
        $this->validity_start_day					= get_cookie("quota_order_number_validity_start_day");
        $this->validity_start_month					= get_cookie("quota_order_number_goods_nomenclature_validity_start_month");
        $this->validity_start_year					= get_cookie("quota_order_number_goods_nomenclature_validity_start_year");
        $this->validity_end_day						= get_cookie("quota_order_number_goods_nomenclature_validity_end_day");
        $this->validity_end_month					= get_cookie("quota_order_number_goods_nomenclature_validity_end_month");
        $this->validity_end_year					= get_cookie("quota_order_number_goods_nomenclature_validity_end_year");
        $this->description							= get_cookie("quota_order_number_goods_nomenclature_description");
        $this->quota_scope							= get_cookie("quota_order_number_quota_scope");
        $this->quota_staging						= get_cookie("quota_order_number_quota_staging");
        $this->origin_quota							= get_cookie("quota_order_number_origin_quota");
	}

function clear() {
        $this->validity_start_day					= "";
        $this->validity_start_month					= "";
        $this->validity_start_year					= "";
        $this->validity_end_day						= "";
        $this->validity_end_month					= "";
        $this->validity_end_year					= "";
        $this->description							= "";
        $this->quota_scope							= "";
        $this->quota_staging						= "";
        $this->origin_quota							= "";
	}


    function insert() {
        global $conn;
        $application = new application;
        $this->quota_order_number_sid  = $application->get_next_quota_order_number();
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $errors = $this->conflict_check();
        h1 (count($errors));
        if (count($errors) > 0) {
			/*
			foreach ($errors as $error) {
                h1 ($error);
            }
			exit();
			*/
            return ($errors);
        } else {
            $sql = "INSERT INTO quota_order_numbers_oplog
            (
				quota_order_number_sid,
				quota_order_number_id,
				validity_start_date,
				description,
				origin_quota,
				quota_scope,
				quota_staging,
				operation,
				operation_date)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
            pg_prepare($conn, "quota_order_number_insert", $sql);
            pg_execute($conn, "quota_order_number_insert", array(
				$this->quota_order_number_sid,
				$this->quota_order_number_id,
				$this->validity_start_date,
				$this->description,
				$this->origin_quota,
				$this->quota_scope,
				$this->quota_staging,
				$operation,
				$operation_date));
			return (True);
        }
	}
	

    function update() {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

		$errors = array();
		$sql = "INSERT INTO quota_order_numbers_oplog
		(
			quota_order_number_sid,
			quota_order_number_id,
			validity_start_date,
			validity_end_date,
			description,
			origin_quota,
			quota_scope,
			quota_staging,
			operation,
			operation_date)
		VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";

		pg_prepare($conn, "quota_order_number_insert", $sql);

		pg_execute($conn, "quota_order_number_insert", array(
			$this->quota_order_number_sid,
			$this->quota_order_number_id,
			$this->validity_start_date,
			$this->validity_end_date,
			$this->description,
			$this->origin_quota,
			$this->quota_scope,
			$this->quota_staging,
			$operation,
			$operation_date));
	}
	
	function get_measure_types() {
		$out = "";
		foreach ($this->measure_types as $m) {
			$out .= $m . ", ";
		}
		$out  = trim($out);
		$out  = trim($out, ",");
		return ($out);
	}

    function conflict_check() {
        global $conn;
        $errors = array();
        # First, check for items that exist already and have not been end-dated
        $sql = "SELECT * FROM quota_order_numbers WHERE quota_order_number_id = $1 AND validity_end_date IS NOT NULL";
        pg_prepare($conn, "quota_order_number_conflict_check", $sql);
        $result = pg_execute($conn, "quota_order_number_conflict_check", array($this->quota_order_number_id));      
        if ($result) {
            if (pg_num_rows($result) > 0){
                array_push($errors, "Error scenario 1");
            }
        }
        return ($errors);
    }


	public function get_quota_order_number_sid() {
		global $conn;
		$sql = "SELECT quota_order_number_sid FROM quota_order_numbers
		WHERE quota_order_number_id = '" . $this->quota_order_number_id . "' ORDER BY validity_start_date DESC LIMIT 1";
		$result = pg_query($conn, $sql);
		if ($result) {
        $row = pg_fetch_row($result);
			while ($row = pg_fetch_array($result)) {
				$this->quota_order_number_sid = $row['quota_order_number_sid'];
			}
		}
	}

	public function get_quota_definitions() {
		global $conn;
		$sql = "select quota_definition_sid, validity_start_date, validity_end_date
		from quota_definitions
		where quota_order_number_id = '" . $this->quota_order_number_id . "'
		and validity_end_date > current_date";
		//echo ($sql);
		$result = pg_query($conn, $sql);
		$temp = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$quota_definition       = new quota_definition;
				$quota_definition->quota_definition_sid	= $row['quota_definition_sid'];
				$quota_definition->validity_start_date	= $row['validity_start_date'];
				$quota_definition->validity_end_date	= $row['validity_end_date'];
				array_push($temp, $quota_definition);
			}
			$this->quota_definitions = $temp;
		}
	}



	public function get_origins() {
		global $conn;
		# Get all the quota order number exclusions
		$sql = "SELECT qonoe.quota_order_number_origin_sid, excluded_geographical_area_sid, ga.description
		FROM quota_order_number_origin_exclusions qonoe, ml.ml_geographical_areas ga, quota_order_number_origins qono, quota_order_numbers qon
		WHERE qonoe.excluded_geographical_area_sid = ga.geographical_area_sid
		AND qono.quota_order_number_origin_sid = qonoe.quota_order_number_origin_sid
		AND qon.quota_order_number_sid = qono.quota_order_number_sid
		AND qon.quota_order_number_id = '" . $this->quota_order_number_id . "'";
		#p ($sql);
		$result = pg_query($conn, $sql);
		$quota_order_number_origin_exclusions = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$quota_order_number_origin_sid      = $row['quota_order_number_origin_sid'];
				$excluded_geographical_area_sid     = $row['excluded_geographical_area_sid'];
				$description                        = $row['description'];
				$qonoe = new quota_order_number_origin_exclusion;
				$qonoe->set_properties($quota_order_number_origin_sid, $excluded_geographical_area_sid, $description);
				array_push($quota_order_number_origin_exclusions, $qonoe);
			}
		}

		# Get the complete list of quota order number origins
		$sql = "SELECT qono.quota_order_number_origin_sid, qono.quota_order_number_sid, qono.geographical_area_id,
		ga.description, qon.quota_order_number_id, qono.validity_start_date, qono.validity_end_date
		FROM quota_order_number_origins qono, ml.ml_geographical_areas ga, quota_order_numbers qon
		WHERE ga.geographical_area_id = qono.geographical_area_id
		AND qon.quota_order_number_sid = qono.quota_order_number_sid
		AND (qono.validity_end_date IS NULL OR qono.validity_end_date > CURRENT_DATE)
		AND quota_order_number_id = '" . $this->quota_order_number_id . "'
		ORDER BY qono.validity_start_date desc, qono.quota_order_number_sid, ga.description";
		#p ($sql);

		$result = pg_query($conn, $sql);
		$quota_order_number_origins = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$geographical_area_id           = $row['geographical_area_id'];
				$quota_order_number_origin_sid  = $row['quota_order_number_origin_sid'];
				$quota_order_number_sid         = $row['quota_order_number_sid'];
				$quota_order_number_id          = $row['quota_order_number_id'];
				$description                    = $row['description'];
				$validity_start_date            = $row['validity_start_date'];
				$validity_end_date            	= $row['validity_end_date'];
				$qono = new quota_order_number_origin;
				$qono->set_properties($quota_order_number_origin_sid, $geographical_area_id, $quota_order_number_id, $quota_order_number_sid, $description);
				$qono->validity_start_date	= $validity_start_date;
				$qono->validity_end_date	= $validity_end_date;
				$qonoe_count = count($quota_order_number_origin_exclusions);
				for($i = 0; $i < $qonoe_count; $i++) {
					$t = $quota_order_number_origin_exclusions[$i];
					if ($t->quota_order_number_origin_sid == $quota_order_number_origin_sid) {
						array_push($qono->exclusions, $t);
						#p ("Adding exclusion");
					}
				}
				array_push($quota_order_number_origins, $qono);
			}
		}
		$this->origins = $quota_order_number_origins;
	}

	function validate_fcfs_order_number() {
		global $conn;

		$this->quota_order_number_id = trim($this->quota_order_number_id);
		if (strlen($this->quota_order_number_id) != 6 ) {
			$ret = false;
			return $ret;
		}
		if (substr($this->quota_order_number_id, 0, 3) == "094") {
			$ret = false;
			return $ret;
		}
		$sql = "select quota_order_number_sid from quota_order_numbers
		where validity_end_date is null and quota_order_number_id = $1
		order by validity_start_date desc;";
		pg_prepare($conn, "validate_fcfs_order_number", $sql);
		$result = pg_execute($conn, "validate_fcfs_order_number", array($this->quota_order_number_id));
		$row_count = pg_num_rows($result);
		if (($result) && ($row_count > 0)) {
			$ret = true;
		} else {
			$ret = false;
		}
		return ($ret);
	}
}    