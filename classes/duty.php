<?php
class duty
{
	// Class properties and methods go here

	public function __construct() {
		$this->measure_sid		        = 0;
		$this->geographical_area_id	    = "";
		$this->additional_code_id	    = "";
		$this->additional_code_type_id  = "";
	}

    public function set_properties($commodity_code, $additional_code_type_id, $additional_code_id, $measure_type_id,
    $duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code, $measure_sid,
	$quota_order_number_id, $geographical_area_id, $validity_start_date, $validity_end_date) {

		$this->commodity_code					= $commodity_code;
		$this->additional_code_type_id			= $additional_code_type_id;
		$this->additional_code_id				= $additional_code_id;
		$this->measure_type_id				    = $measure_type_id;
		$this->duty_expression_id				= $duty_expression_id;
		$this->duty_amount                      = $duty_amount;
		$this->monetary_unit_code			    = $monetary_unit_code;
		$this->measurement_unit_code			= $measurement_unit_code;
		$this->measurement_unit_qualifier_code  = $measurement_unit_qualifier_code;
		$this->measure_sid				        = $measure_sid;
		$this->quota_order_number_id			= $quota_order_number_id;
		$this->geographical_area_id				= $geographical_area_id;
		$this->validity_start_date				= $validity_start_date;
        $this->validity_end_date				= $validity_end_date;
        
        $this->getDutyString();
        #echo ("setting duty properties");
        #echo ("Duty string " . $this->duty_string);
	}

	public function getDutyString() {
		# This bit of code removes the Meursing data from the duty expressions
		# This will be artifically re-inserted from an external data source,
		# i.e. not from data that is stored in the database (check meursing_products.csv in /source folder)
		# Confirmed 24/01/19 by Daren Timson-Hunt and David Owen that this will not be needed on the Tariff
		# but should be used in the reference documents in order to back up the legislation
		
		#if $this->duty_expression_id in ('12', '13', '14', '21', '23', '25', '27', '29'):
		#	$this->duty_string = ""
		#	return

        $this->duty_string = "";
        $duty_amount = number_format($this->duty_amount, 2);
        
        switch ($this->duty_expression_id) {
            case "01":
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= $duty_amount . "%";
                } else {
                    $this->duty_string .= $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "04": // All three of these together
            case "19":
            case "20":
                // Do stuff
			    if ($this->monetary_unit_code == "") {
                    $this->duty_string .= " + " . $duty_amount . "%";
                } else {
                    $this->duty_string .= " + " . $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "12":
    			$this->duty_string .= " + AC";
                break;
            case "15":
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= "MIN " . $duty_amount . "%";
                } else {
                    $this->duty_string .= "MIN " . $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;

            case "17":
            case "35":
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= "MAX " . $duty_amount . "%";
                } else {
                    $this->duty_string .= "MAX " . $duty_amount  . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "21":
    			$this->duty_string .= " + SD";
                break;
            case "27":
    			$this->duty_string .= " + FD";
                break;
            case "25":
    			$this->duty_string .= " + SD (reduced)";
                break;
            case "29":
    			$this->duty_string .= " + FD (reduced)";
                break;
            case "14":
    			$this->duty_string .= " + AC (reduced)";
                break;
        }
    }

	function getMeasurementUnit() {
        switch ($this->measurement_unit_code) {
        case "ASV":
            return ("% vol");
            break;
        case "NAR":
            return ("item");
            break;
        case "CCT":
            return ("ct/l");
            break;
        case "CEN":
            return ("100 p/st");
            break;
        case "CTM":
            return ("c/k");
            break;
        case "DTN":
            return ("100 kg");
            break;
        case "GFI":
            return ("gi F/S");
            break;
        case "GRM":
            return ("g");
            break;
        case "HLT":
            return ("hl");
            break;
        case "HMT":
            return ("100 m");
            break;
        case "KGM":
            return ("kg");
            break;
        case "KLT":
            return ("1,000 l");
            break;
        case "KMA":
            return ("kg met.am.");
            break;
        case "KNI":
            return ("kg N");
            break;
        case "KNS":
            return ("kg H2O2");
            break;
        case "KPH":
            return ("kg KOH");
            break;
        case "KPO":
            return ("kg K2O");
            break;
        case "KPP":
            return ("kg P2O5");
            break;
        case "KSD":
            return ("kg 90 % sdt");
            break;
        case "KSH":
            return ("kg NaOH");
            break;
        case "KUR":
            return ("kg U");
            break;
        case "LPA":
            return ("l alc. 100%");
            break;
        case "LTR":
            return ("l");
            break;
        case "MIL":
            return ("1,000 items");
            break;
        case "MTK":
            return ("m2");
            break;
        case "MTQ":
            return ("m3");
            break;
        case "MTR":
            return ("m");
            break;
        case "MWH":
            return ("1,000 kWh");
            break;
        case "NCL":
            return ("ce/el");
            break;
        case "NPR":
            return ("pa");
            break;
        case "TJO":
            return ("TJ");
            break;
        case "TNE":
            return ("tonne");
            break;
        default:
            return ($this->measurement_unit_code);
            break;
        }
    }

	function getQualifier() {
		$qual_desc = "";
        switch ($this->measurement_unit_qualifier_code) {
        case "A":
            $qual_desc = "tot alc";
            break;
		case "C":
			$qual_desc = "1 000";
            break;
		case "E":
			$qual_desc = "net drained wt";
            break;
		case "G":
			$qual_desc = "gross";
            break;
		case "M":
			$qual_desc = "net dry";
            break;
		case "P":
			$qual_desc = "lactic matter";
            break;
		case "R":
			$qual_desc = "std qual";
            break;
		case "S":
			$qual_desc = " raw sugar";
            break;
		case "T":
			$qual_desc = "dry lactic matter";
            break;
		case "X":
			$qual_desc = " hl";
            break;
		case "Z":
            $qual_desc = "% sacchar.";
            break;
        }
		return ($qual_desc);
    }
}