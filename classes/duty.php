<?php
class duty
{
	// Class properties and methods go here

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
        case "NAR":
            return ("item");
        case "CCT":
            return ("ct/l");
        case "CEN":
            return ("100 p/st");
        case "CTM":
            return ("c/k");
        case "DTN":
            return ("100 kg");
        case "GFI":
            return ("gi F/S");
        case "GRM":
            return ("g");
        case "HLT":
            return ("hl");
        case "HMT":
            return ("100 m");
        case "KGM":
            return ("kg");
        case "KLT":
            return ("1,000 l");
        case "KMA":
            return ("kg met.am.");
        case "KNI":
            return ("kg N");
        case "KNS":
            return ("kg H2O2");
        case "KPH":
            return ("kg KOH");
        case "KPO":
            return ("kg K2O");
        case "KPP":
            return ("kg P2O5");
        case "KSD":
            return ("kg 90 % sdt");
        case "KSH":
            return ("kg NaOH");
        case "KUR":
            return ("kg U");
        case "LPA":
            return ("l alc. 100%");
        case "LTR":
            return ("l");
        case "MIL":
            return ("1,000 items");
        case "MTK":
            return ("m2");
        case "MTQ":
            return ("m3");
        case "MTR":
            return ("m");
        case "MWH":
            return ("1,000 kWh");
        case "NCL":
            return ("ce/el");
        case "NPR":
            return ("pa");
        case "TJO":
            return ("TJ");
        case "TNE":
            return ("tonne");
        default:
            return ($this->measurement_unit_code);
        }
    }

	function getQualifier() {
		$qual_desc = "";
        switch ($this->measurement_unit_qualifier_code) {
        case "A":
			$qual_desc = "tot alc";
		case "C":
			$qual_desc = "1 000";
		case "E":
			$qual_desc = "net drained wt";
		case "G":
			$qual_desc = "gross";
		case "M":
			$qual_desc = "net dry";
		case "P":
			$qual_desc = "lactic matter";
		case "R":
			$qual_desc = "std qual";
		case "S":
			$qual_desc = " raw sugar";
		case "T":
			$qual_desc = "dry lactic matter";
		case "X":
			$qual_desc = " hl";
		case "Z":
            $qual_desc = "% sacchar.";
        }
		return ($qual_desc);
    }
}