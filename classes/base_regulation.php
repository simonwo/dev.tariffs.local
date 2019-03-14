<?php
class base_regulation
{
	// Class properties and methods go here
	public $base_regulation_id       	= "";
	public $validity_start_date       	= "";
	public $regulation_group_id       	= "";
	public $information_text_name       = "";
	public $information_text_primary	= "";
	public $information_text_url       	= "";

	
	public function set_properties($base_regulation_id) {
		$this->base_regulation_id		= $base_regulation_id;
	}

	function insert($base_regulation_id, $information_text, $validity_start_date, $regulation_group_id) {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $this->base_regulation_id = $base_regulation_id;
        $this->information_text = $information_text;
        $this->validity_start_date = $validity_start_date;
		$this->regulation_group_id = $regulation_group_id;
		$this->published_date = '2019-03-30';

		$errors = $this->conflict_check();
        if (count($errors) > 0) {
            /*foreach ($errors as $error) {
                h1 ($error);
            }
			exit();*/
			#h1 ("fail");
            return ($errors);
        } else {
			h1 ($this->base_regulation_id);
			# Insert the geographical area description period
            $sql = "INSERT INTO base_regulations_oplog
            (base_regulation_role, base_regulation_id, validity_start_date, validity_end_date, community_code, regulation_group_id, replacement_indicator,
			stopped_flag, information_text, approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
			antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role, complete_abrogation_regulation_id,
			explicit_abrogation_regulation_role, explicit_abrogation_regulation_id, operation, operation_date)
            VALUES (
			1, $1, $3, Null, 1, $4, 0,
			False, $2, True, $7, '1', '1', Null,
			Null, Null, Null, Null,
			Null, Null, $5, $6)";

            pg_prepare($conn, "base_regulation_insert", $sql);
			pg_execute($conn, "base_regulation_insert", array($this->base_regulation_id, $this->information_text,
			$this->validity_start_date, $this->regulation_group_id, $operation, $operation_date, $this->published_date));

            return (True);
        }

	}

	function update() {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

		$this->published_date = '2019-03-30';

		#h1 ("Update" . $this->base_regulation_id);
		#exit();
		$sql = "INSERT INTO base_regulations_oplog
		(base_regulation_role, base_regulation_id, validity_start_date, validity_end_date, community_code, regulation_group_id, replacement_indicator,
		stopped_flag, information_text, approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
		antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role, complete_abrogation_regulation_id,
		explicit_abrogation_regulation_role, explicit_abrogation_regulation_id, operation, operation_date)
		VALUES (
		1, $1, $3, Null, 1, $4, 0,
		False, $2, True, $7, '1', '1', Null,
		Null, Null, Null, Null,
		Null, Null, $5, $6)";

		pg_prepare($conn, "base_regulation_insert", $sql);
		pg_execute($conn, "base_regulation_insert", array($this->base_regulation_id, $this->information_text,
		$this->validity_start_date, $this->regulation_group_id, $operation, $operation_date, $this->published_date));

		return (True);

	}

	function conflict_check() {
        global $conn;
		$errors = array();
        # Check that the regulation does not already exist
        $sql = "SELECT * FROM base_regulations WHERE base_regulation_id = $1";
        pg_prepare($conn, "base_regulation_conflict_check", $sql);
        $result = pg_execute($conn, "base_regulation_conflict_check", array($this->base_regulation_id));      
        if ($result) {
            if (pg_num_rows($result) > 0){
                array_push($errors, "base_regulation_id conflict");
				#h1 ("err" . $this->base_regulation_id);
				#exit();
			}
        }

        
        return ($errors);
	}
	
	function get_latest_description() {
		global $conn;
		$sql = "SELECT gad.description
		FROM geographical_area_description_periods gadp, geographical_area_descriptions gad
		WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
		AND gad.geographical_area_id = $1
		ORDER BY gadp.validity_start_date DESC LIMIT 1";
		
		pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array($this->geographical_area_id));      
        if ($result) {
            $row = pg_fetch_row($result);
        	$this->description  = $row[0];
        }
	}


    function populate_from_cookies() {
        $this->validity_start_day		= get_cookie("base_regulation_validity_start_day");
        $this->validity_start_month		= get_cookie("base_regulation_validity_start_month");
        $this->validity_start_year		= get_cookie("base_regulation_validity_start_year");
        $this->base_regulation_id		= strtoupper(get_cookie("base_regulation_base_regulation_id"));
        $this->information_text_name	= get_cookie("base_regulation_information_text_name");
        $this->information_text_url		= get_cookie("base_regulation_information_text_url");
        $this->information_text_primary	= get_cookie("base_regulation_information_text_primary");
        $this->regulation_group_id		= get_cookie("base_regulation_regulation_group_id");
	}
	
	function populate_from_db() {
		global $conn;
		$sql = "SELECT validity_start_date, regulation_group_id, information_text FROM base_regulations WHERE base_regulation_id = $1";
		pg_prepare($conn, "get_specific_description", $sql);
		$result = pg_execute($conn, "get_specific_description", array($this->base_regulation_id));
        if ($result) {
			$row = pg_fetch_row($result);
			$this->validity_start_date	= $row[0];
        	$this->regulation_group_id	= $row[1];
			$this->information_text  	= $row[2];
			$this->validity_start_day   = date('d', strtotime($this->validity_start_date));
			$this->validity_start_month = date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  = date('Y', strtotime($this->validity_start_date));
			$this->split_information_text();
        }
	}

	function split_information_text() {
		$split = explode("|", $this->information_text);
		if (count($split) == 3) {
			$this->information_text_name	= $split[0];
			$this->information_text_url		= $split[1];
			$this->information_text_primary = $split[2];
		}
	}


    function clear_cookies() {
        setcookie("geographical_area_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_description", "", time() + (86400 * 30), "/");
    }

}