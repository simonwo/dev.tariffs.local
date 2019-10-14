<?php
class extract
{
	// Class properties and methods go here
	public $last_exported_operation_date; 
	public $last_transaction_id;
	public $message_id;
	public $extract_string;
	public $filename;

	public function __construct() {
		$this->message_id = 1;
		$this->envelope_id = "200000000000000";
		$this->extract_string = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
		$this->extract_string .= '<env:envelope xmlns="urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0" xmlns:env="urn:publicid:-:DGTAXUD:GENERAL:ENVELOPE:1.0" id="' . $this->envelope_id . '">' . "\r\n";
	}

	function extract_data() {
		$this->get_parameters();
		$this->extract_string .= $this->extract_measure_types();                    # 23500 & 23505
		$this->extract_string .= $this->extract_geographical_area_descriptions();
		$this->extract_string .= $this->extract_certificates();
		$this->extract_string .= $this->extract_footnotes();
		$this->extract_string .= $this->extract_geographical_area_memberships();    # 25015
		$this->extract_string .= $this->extract_base_regulations();
		$this->extract_string .= "</env:envelope>";
		$this->write_data();
	}

	function extract_geographical_area_memberships() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/geographical.area.membership.xml', true);
		$sql = "SELECT geographical_area_sid, geographical_area_group_sid, validity_start_date, validity_end_date, operation
		FROM geographical_area_memberships_oplog
		WHERE operation_date > $1 AND operation_date IS NOT NULL
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_geographical_area_memberships", $sql);
		$result = pg_execute($conn, "extract_geographical_area_memberships", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                       = $template;
					$geographical_area_sid          = $row["geographical_area_sid"];
					$geographical_area_group_sid    = $row["geographical_area_group_sid"];
					$operation                      = $row["operation"];
					$validity_start_date            = $this->xml_date($row["validity_start_date"]);
					$validity_end_date              = $this->xml_date($row["validity_end_date"]);

					$instance = str_replace("[TRANSACTION_ID]", $this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $this->message_id + 1, $instance);
					$instance = str_replace("[OPERATION]", $this->get_operation($operation), $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_SID]", $geographical_area_sid, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_GROUP_SID]", $geographical_area_group_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\r\n", "", $instance);

					$ret .= $instance;
					$this->message_id += 1;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

	function extract_measure_types() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/measure.type.xml', true);
		$sql = "SELECT mt.measure_type_id, mt.validity_start_date, mt.validity_end_date, mt.trade_movement_code,
		mt.priority_code, mt.measure_component_applicable_code, mt.origin_dest_code,
		mt.order_number_capture_code, mt.measure_explosion_level, mt.measure_type_series_id, mtd.description,
		mt.operation as mt_operation, mtd.operation as mtd_operation
		FROM measure_types_oplog mt, measure_type_descriptions_oplog mtd
		WHERE mt.measure_type_id = mtd.measure_type_id
		AND mt.operation_date > $1 AND mt.operation_date IS NOT NULL
		ORDER BY mt.operation_date";
		
		pg_prepare($conn, "extract_measure_types", $sql);
		$result = pg_execute($conn, "extract_measure_types", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$measure_type_id                    = $row["measure_type_id"];
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$validity_end_date                  = $this->xml_date($row["validity_end_date"]);
					$trade_movement_code                = $row["trade_movement_code"];
					$priority_code                      = $row["priority_code"];
					$measure_component_applicable_code  = $row["measure_component_applicable_code"];
					$origin_dest_code                   = $row["origin_dest_code"];
					$order_number_capture_code          = $row["order_number_capture_code"];
					$measure_explosion_level            = $row["measure_explosion_level"];
					$measure_type_series_id             = $row["measure_type_series_id"];
					$description                        = $row["description"];
					$mt_operation                       = $this->get_operation($row["mt_operation"]);
					$mtd_operation                      = $this->get_operation($row["mtd_operation"]);

					$instance = str_replace("[TRANSACTION_ID]", $this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $this->message_id + 1, $instance);
					$instance = str_replace("[MT_OPERATION]", $mt_operation, $instance);
					$instance = str_replace("[MTD_OPERATION]", $mtd_operation, $instance);
					$instance = str_replace("[MEASURE_TYPE_ID]", $measure_type_id, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);
					$instance = str_replace("[TRADE_MOVEMENT_CODE]", $trade_movement_code, $instance);
					$instance = str_replace("[PRIORITY_CODE]", $priority_code, $instance);
					$instance = str_replace("[MEASURE_COMPONENT_APPLICABLE_CODE]", $measure_component_applicable_code, $instance);
					$instance = str_replace("[ORIGIN_DEST_CODE]", $origin_dest_code, $instance);
					$instance = str_replace("[ORDER_NUMBER_CAPTURE_CODE]", $order_number_capture_code, $instance);
					$instance = str_replace("[MEASURE_EXPLOSION_LEVEL]", $measure_explosion_level, $instance);
					$instance = str_replace("[MEASURE_TYPE_SERIES_ID]", $measure_type_series_id, $instance);
					$instance = str_replace("[DESCRIPTION]", $description, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\r\n", "", $instance);

					$ret .= $instance;
					$this->message_id += 2;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

	function extract_geographical_area_descriptions() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/geographical.area.descriptions.xml', true);
		$sql = "SELECT gad.geographical_area_description_period_sid, gad.geographical_area_sid, gad.geographical_area_id,
		gad.description, gadp.operation as gadp_operation, gad.operation as gad_operation, gadp.validity_start_date
		FROM geographical_area_descriptions_oplog gad, geographical_area_description_periods_oplog gadp
		WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
		AND gad.operation_date > $1";
		
		pg_prepare($conn, "extract_geographical_area_descriptions", $sql);
		$result = pg_execute($conn, "extract_geographical_area_descriptions", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                                   = $template;
					$geographical_area_description_period_sid   = $row["geographical_area_description_period_sid"];
					$geographical_area_sid                      = $row["geographical_area_sid"];
					$geographical_area_id                       = $row["geographical_area_id"];
					$description                                = $row["description"];
					$validity_start_date                        = $this->xml_date($row["validity_start_date"]);
					$gad_operation                              = $this->get_operation($row["gad_operation"]);
					$gadp_operation                             = $this->get_operation($row["gadp_operation"]);
					$instance = str_replace("[TRANSACTION_ID]", $this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $this->message_id + 1, $instance);
					$instance = str_replace("[GADP_OPERATION]", $gadp_operation, $instance);
					$instance = str_replace("[GAD_OPERATION]", $gad_operation, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_DESCRIPTION_PERIOD_SID]", $geographical_area_description_period_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_SID]", $geographical_area_sid, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_ID]", $geographical_area_id, $instance);
					$instance = str_replace("[DESCRIPTION]", $description, $instance);
					$ret .= $instance;
					$this->message_id += 2;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

	function extract_footnotes() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/footnotes.xml', true);
		$sql = "SELECT footnote_type_id, footnote_id, operation, validity_start_date
		FROM footnotes_oplog WHERE operation_date > $1 ORDER BY operation_date";

		$sql = "SELECT f.validity_start_date as f_validity_start_date, fd.footnote_description_period_sid, fd.footnote_type_id, fd.footnote_id,
		fd.description, f.operation as f_operation, fd.operation as fd_operation, fdp.operation as fdp_operation, fdp.validity_start_date,
		fd.operation_date, fdp.operation_date
		FROM footnote_descriptions_oplog fd, footnote_description_periods_oplog fdp, footnotes_oplog as f
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND f.footnote_id = fd.footnote_id
		AND f.footnote_type_id = fd.footnote_type_id
		AND fd.operation_date = fdp.operation_date
		AND f.operation_date = fdp.operation_date		
		AND f.operation_date > $1
		AND fd.operation_date > $1
		AND fdp.operation_date > $1
		ORDER BY fd.operation_date";

		pg_prepare($conn, "extract_footnotes", $sql);
		$result = pg_execute($conn, "extract_footnotes", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$footnote_type_id                   = $row["footnote_type_id"];
					$footnote_id                        = $row["footnote_id"];
					$description                        = $row["description"];
					$footnote_description_period_sid	= $row["footnote_description_period_sid"];
					$f_validity_start_date              = $this->xml_date($row["f_validity_start_date"]);
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$f_operation						= $this->get_operation($row["f_operation"]);
					$fd_operation						= $this->get_operation($row["fd_operation"]);
					$fdp_operation						= $this->get_operation($row["fdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$this->message_id + 1, $instance);
					$instance = str_replace("[MESSAGE_ID3]",						$this->message_id + 2, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER3]",			$this->message_id + 2, $instance);
					$instance = str_replace("[F_OPERATION]",						$f_operation, $instance);
					$instance = str_replace("[FD_OPERATION]",						$fd_operation, $instance);
					$instance = str_replace("[FDP_OPERATION]",						$fdp_operation, $instance);
					$instance = str_replace("[F_VALIDITY_START_DATE]",				$f_validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[FOOTNOTE_TYPE_ID]",					$footnote_type_id, $instance);
					$instance = str_replace("[FOOTNOTE_ID]",						$footnote_id, $instance);
					$instance = str_replace("[FOOTNOTE_DESCRIPTION_PERIOD_SID]",	$footnote_description_period_sid, $instance);
					$instance = str_replace("[DESCRIPTION]",						$description, $instance);
					$ret .= $instance;
					$this->message_id += 3;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}


	function extract_certificates() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/certificates.xml', true);

		$sql = "SELECT c.validity_start_date as c_validity_start_date, cd.certificate_description_period_sid, cd.certificate_type_code, cd.certificate_code,
		cd.description, c.operation as c_operation, cd.operation as cd_operation, cdp.operation as cdp_operation, cdp.validity_start_date,
		cd.operation_date, cdp.operation_date
		FROM certificate_descriptions_oplog cd, certificate_description_periods_oplog cdp, certificates_oplog as c
		WHERE cd.certificate_description_period_sid = cdp.certificate_description_period_sid
		AND c.certificate_code = cd.certificate_code
		AND c.certificate_type_code = cd.certificate_type_code
		AND cd.operation_date = cdp.operation_date
		AND c.operation_date = cdp.operation_date		
		AND c.operation_date > $1
		AND cd.operation_date > $1
		AND cdp.operation_date > $1
		ORDER BY cd.operation_date";

		pg_prepare($conn, "extract_certificates", $sql);
		$result = pg_execute($conn, "extract_certificates", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$certificate_type_code              = $row["certificate_type_code"];
					$certificate_code                   = $row["certificate_code"];
					$description                        = $row["description"];
					$certificate_description_period_sid	= $row["certificate_description_period_sid"];
					$c_validity_start_date              = $this->xml_date($row["c_validity_start_date"]);
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$c_operation						= $this->get_operation($row["c_operation"]);
					$cd_operation						= $this->get_operation($row["cd_operation"]);
					$cdp_operation						= $this->get_operation($row["cdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$this->message_id + 1, $instance);
					$instance = str_replace("[MESSAGE_ID3]",						$this->message_id + 2, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER3]",			$this->message_id + 2, $instance);
					$instance = str_replace("[C_OPERATION]",						$c_operation, $instance);
					$instance = str_replace("[CD_OPERATION]",						$cd_operation, $instance);
					$instance = str_replace("[CDP_OPERATION]",						$cdp_operation, $instance);
					$instance = str_replace("[C_VALIDITY_START_DATE]",				$c_validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[CERTIFICATE_TYPE_CODE]",				$certificate_type_code, $instance);
					$instance = str_replace("[CERTIFICATE_CODE]",					$certificate_code, $instance);
					$instance = str_replace("[CERTIFICATE_DESCRIPTION_PERIOD_SID]",	$certificate_description_period_sid, $instance);
					$instance = str_replace("[DESCRIPTION]",						$description, $instance);
					
					$ret .= $instance;
					$this->message_id += 3;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

	function extract_footnote_descriptions() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/footnote.description.update.xml', true);
		$sql = "SELECT fd.footnote_description_period_sid, fd.footnote_type_id, fd.footnote_id,
		fd.description, fdp.operation as fdp_operation, fd.operation as fd_operation, fdp.validity_start_date
		FROM footnote_descriptions_oplog fd, footnote_description_periods_oplog fdp
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND fd.operation_date = fdp.operation_date
		AND fd.operation_date > $1 ORDER BY fd.operation_date";
		
		pg_prepare($conn, "extract_footnote_descriptions", $sql);
		$result = pg_execute($conn, "extract_footnote_descriptions", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$footnote_description_period_sid    = $row["footnote_description_period_sid"];
					$footnote_type_id                   = $row["footnote_type_id"];
					$footnote_id                        = $row["footnote_id"];
					$description                        = $row["description"];
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$fd_operation                       = $this->get_operation($row["fd_operation"]);
					$fdp_operation						= $this->get_operation($row["fdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$this->message_id + 1, $instance);
					$instance = str_replace("[FDP_OPERATION]",						$fdp_operation, $instance);
					$instance = str_replace("[FD_OPERATION]",						$fd_operation, $instance);
					$instance = str_replace("[FOOTNOTE_DESCRIPTION_PERIOD_SID]",	$footnote_description_period_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[FOOTNOTE_TYPE_ID]",					$footnote_type_id, $instance);
					$instance = str_replace("[FOOTNOTE_ID]",						$footnote_id, $instance);
					$instance = str_replace("[DESCRIPTION]",						$description, $instance);
					$ret .= $instance;
					$this->message_id += 2;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}


	function extract_base_regulations() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/base.regulation.update.xml', true);
		
		$sql = "SELECT base_regulation_role, base_regulation_id, validity_start_date, validity_end_date,
		community_code, regulation_group_id, replacement_indicator, stopped_flag, information_text,
		approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
		antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role,
		complete_abrogation_regulation_id, explicit_abrogation_regulation_role, explicit_abrogation_regulation_id,
		operation, operation_date FROM base_regulations WHERE base_regulations.operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_base_regulations", $sql);
		$result = pg_execute($conn, "extract_base_regulations", array($this->last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                               = $template;
					$base_regulation_role                   = $row["base_regulation_role"];
					$base_regulation_id                     = $row["base_regulation_id"];
					$validity_start_date                    = string_to_date($row["validity_start_date"]);
					$validity_end_date                      = string_to_date($row["validity_end_date"]);
					$community_code                         = $row["community_code"];
					$regulation_group_id                    = $row["regulation_group_id"];
					$replacement_indicator                  = $row["replacement_indicator"];
					$stopped_flag                           = bool_to_int($row["stopped_flag"]);
					$information_text                       = $row["information_text"];
					$approved_flag                          = bool_to_int($row["approved_flag"]);
					$published_date                         = $row["published_date"];
					$officialjournal_number                 = $row["officialjournal_number"];
					$officialjournal_page                   = $row["officialjournal_page"];
					$effective_end_date                     = string_to_date($row["effective_end_date"]);

					$operation = $this->get_operation($row["operation"]);
					$instance = str_replace("[TRANSACTION_ID]", $this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID]", $this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER]", $this->message_id, $instance);
					$instance = str_replace("[OPERATION]", $operation, $instance);
					$instance = str_replace("[BASE_REGULATION_ROLE]", $base_regulation_role, $instance);
					$instance = str_replace("[BASE_REGULATION_ID]", $base_regulation_id, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);
					$instance = str_replace("[COMMUNITY_CODE]", $community_code, $instance);
					$instance = str_replace("[REGULATION_GROUP_ID]", $regulation_group_id, $instance);
					$instance = str_replace("[REPLACEMENT_INDICATOR]", $replacement_indicator, $instance);
					$instance = str_replace("[STOPPED_FLAG]", $stopped_flag, $instance);
					$instance = str_replace("[INFORMATION_TEXT]", $information_text, $instance);
					$instance = str_replace("[APPROVED_FLAG]", $approved_flag, $instance);
					$instance = str_replace("[PUBLISHED_DATE]", $published_date, $instance);
					$instance = str_replace("[OFFICIALJOURNAL_NUMBER]", $officialjournal_number, $instance);
					$instance = str_replace("[OFFICIALJOURNAL_PAGE]", $officialjournal_page, $instance);
					$instance = str_replace("[EFFECTIVE_END_DATE]", $effective_end_date, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\r\n", "", $instance);
					$instance = str_replace("\t\t\t\t\t\t<oub:effective.end.date></oub:effective.end.date>\r\n", "", $instance);

					$ret .= $instance;
					$this->message_id += 1;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

	function xml_date($s) {
		$array = explode(" ", $s);
		$s = $array[0];
		return ($s);
	}

	function get_operation($s) {
		switch ($s) {
		case "U":
			#h1 ("here");
			$s2 = "1";
			break;
		case "D":
			$s2 = "2";
			break;
		case "C":
			$s2 = "3";
			break;
		}
		return ($s2);
	}

	function get_parameters() {
		global $conn;
		$sql = "SELECT last_exported_operation_date, last_transaction_id FROM ml.config";
		$result = pg_query($conn, $sql);
		if ($result) {
			if (pg_num_rows($result) > 0){
				$row = pg_fetch_row($result);
				$this->last_exported_operation_date  = $row[0];
				$this->last_transaction_id  = $row[1];
			} else {
				$sql = "INSERT INTO ml.config (last_exported_operation_date, last_transaction_id) VALUES ($1, $2)";
				$operation_date = "2019-03-01";
				$last_transaction_id = 500000;
				pg_prepare($conn, "last_exported_operation_date_insert", $sql);
				pg_execute($conn, "last_exported_operation_date_insert", array($operation_date, $last_transaction_id));
				$this->last_exported_operation_date = $operation_date;
				$this->last_transaction_id          = $last_transaction_id;
			}
		}
		return ($this->last_exported_operation_date);
	}

	function validate_data() {
		# Do not believe this is used
		$validator = new XmlValidator;
		$validated = $validator->validateFeeds('../extracts/newfile.xml');
		if ($validated) {
		  echo "Feed successfully validated";
		} else {
		  print_r($validator->displayErrors());
		}        
	}

	function write_data() {
		global $http_host;
		#$http_host = strtolower($_SERVER["HTTP_HOST"]);
		#h1 ()
		$xml = new DOMDocument();
		$opdate = str_replace(":", "-", $this->last_exported_operation_date);
		$filename = "../extracts/" . $http_host . "_since_" . $opdate . ".xml";
		#$filename = "../extracts/newfile2.xml";
 
		$xml->loadXML($this->extract_string, LIBXML_NOBLANKS);
		$myfile = fopen($filename, "w") or die("Unable to open file!");
		fwrite($myfile, $this->extract_string);
		fclose($myfile);
		error_reporting(E_ALL ^ E_WARNING);
	   if (!$xml->schemaValidate("../xsd/envelope.xsd")) {
			// You have an error in the XML file
			H1 ("The file has not validated - please fix!");
			prex ($this->extract_string);
			exit();
		} else {
			$this->last_exported_operation_date = date("Y-m-d h:i:s");
			$this->update_last_exported_operation_date();
			#h1 (date("Y-m-d h:i:s"));

		}
		error_reporting(E_ALL);
	}

	function update_last_exported_operation_date(){
		global $conn;
		$sql = "UPDATE ml.config SET last_exported_operation_date = $1";
		pg_prepare($conn, "update_last_exported_operation_date", $sql);
		$result = pg_execute($conn, "update_last_exported_operation_date", array($this->last_exported_operation_date));
		#h1 (date("Y-m-d h:i:s"));
	}
}