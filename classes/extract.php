<?php
class extract
{
	// Class properties and methods go here
	public $last_exported_operation_date; 
    public $last_transaction_id;
    public $message_id;
    public $extract_string;

    public function __construct() {
        $this->message_id = 1;
        $this->envelope_id = "200000000000000";
        $this->extract_string = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $this->extract_string .= '<env:envelope xmlns="urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0" xmlns:env="urn:publicid:-:DGTAXUD:GENERAL:ENVELOPE:1.0" id="' . $this->envelope_id . '">' . "\r\n";
    }

    function extract_data() {
        $this->get_parameters();
        $this->extract_string .= $this->extract_geographical_area_descriptions();
        $this->extract_string .= "</env:envelope>";
        prex ($this->extract_string);
        $this->write_data();
    }

    function validate_data() {
        $validator = new XmlValidator;
        $validated = $validator->validateFeeds('../extracts/newfile.txt');
        if ($validated) {
          echo "Feed successfully validated";
        } else {
          print_r($validator->displayErrors());
        }        
    }

    function write_data() {
        $xml = new DOMDocument();
        error_reporting(E_ALL ^ E_WARNING);

        $xml->loadXML($this->extract_string, LIBXML_NOBLANKS);
        if (!$xml->schemaValidate("../xsd/envelope.xsd")) {
            // You have an error in the XML file
            H1 ("the file has not validated - Fix!");
            exit();
        }
        $myfile = fopen("../extracts/newfile.xml", "w") or die("Unable to open file!");
        $txt = "John Doe\n";
        fwrite($myfile, $this->extract_string);
        fclose($myfile);
        error_reporting(E_ALL);
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
        
        pg_prepare($conn, "last_exported_operation_date_insert", $sql);
        $result = pg_execute($conn, "last_exported_operation_date_insert", array($this->last_exported_operation_date));
        
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
                    #h1 ($gad_operation);
                    #h1 ($gadp_operation);
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

    function xml_date($s) {
        $array = explode(" ", $s);
        $s = $array[0];
        return ($s);
    }

    function get_operation($s) {
        switch ($s) {
        case "U":
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
                h1 ($this->last_exported_operation_date);
                h1 ($this->last_transaction_id);
            } else {
                $sql = "INSERT INTO ml.config (last_exported_operation_date, last_transaction_id) VALUES ($1, $2)";
                $operation_date = "2019-02-17";
                $last_transaction_id = 500000;
                pg_prepare($conn, "last_exported_operation_date_insert", $sql);
                pg_execute($conn, "last_exported_operation_date_insert", array($operation_date, $last_transaction_id));
                $this->last_exported_operation_date = $operation_date;
                $this->last_transaction_id          = $last_transaction_id;
            }
        }
        return ($this->last_exported_operation_date);
    }
}