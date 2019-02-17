<?php
require (dirname(__FILE__) . "../../classes/extract.php");
require (dirname(__FILE__) . "../../classes/application.php");
require (dirname(__FILE__) . "../../classes/error_handler.php");
require (dirname(__FILE__) . "../../classes/measure_type.php");
require (dirname(__FILE__) . "../../classes/measurement_unit.php");
require (dirname(__FILE__) . "../../classes/measurement_unit_qualifier.php");
require (dirname(__FILE__) . "../../classes/measure.php");
require (dirname(__FILE__) . "../../classes/duty.php");
require (dirname(__FILE__) . "../../classes/siv_component.php");
require (dirname(__FILE__) . "../../classes/geographical_area.php");
require (dirname(__FILE__) . "../../classes/quota_definition.php");
require (dirname(__FILE__) . "../../classes/quota_order_number.php");
require (dirname(__FILE__) . "../../classes/quota_order_number_origin.php");
require (dirname(__FILE__) . "../../classes/quota_order_number_origin_exclusion.php");
require (dirname(__FILE__) . "../../classes/base_regulation.php");
require (dirname(__FILE__) . "../../classes/regulation_group.php");

if(isset($_COOKIE["showing"])) {
	$scope = $_COOKIE["showing"];
} else {
	$scope = "Now";
}
if ($scope == "Brexit") {
	$dbase = "tariff_staging";
} else {
	$dbase = "tariff_eu";
}
$dbase = "tariff_dev";
$msg = "All data displayed uses the <strong>" . $dbase . "</strong> database";

$pagesize	= 50;
$conn		= pg_connect("host=127.0.0.1 port=5432 dbname=" . $dbase . " user=postgres password=zanzibar");
$page       = intval(get_querystring("page"));
if ($page == 0) {$page = 1;}

function string_to_date($var) {
	if ($var != "") {
		$var2 = DateTime::createFromFormat('Y-m-d H:i:s', $var)->format('Y-m-d');
	} else {
		$var2 = "";
	}
	return ($var2);
}

function get_checked($key, $value) {
	if ($key == $value) {
		return (" checked");
	} else {
		return ("");
	}

}

function get_cookie($key) {
	if(isset($_COOKIE[$key])) {
		$s = $_COOKIE[$key];
		if ($s == "0") {
			return ("");
		} else {
			return ($_COOKIE[$key]);
		}
	} else {
		return ("");
	}
}

function get_formvar($key, $prefix = "", $store_cookie = False) {
	$s = "";
	if( isset($_REQUEST[$key])){
		$s = trim($_REQUEST[$key]);
	}
	if ($s == "") {
		$s = Null;
	}

	if ($store_cookie) {
		setcookie($prefix . $key, $s, time() + (86400 * 30), "/");
	}

	return ($s);
}

function get_querystring($key) {
	if( isset($_GET[$key])){
		return (trim($_GET[$key]));
	}
}

function geographical_code($id){
	switch ($id) {
		case "0":
			return ("Country");
			break;
		case "1":
			return ("Area group");
			break;
		case "2":
			return ("Region");
			break;
	}
}

function trade_movement_code($id){
	switch ($id) {
		case "0":
			return ("Used for import measures");
			break;
		case "1":
			return ("Used for export measures");
			break;
		case "2":
			return ("Used for import or export measures");
			break;
	}
}

function measure_component_applicable_code($id){
	switch ($id) {
		case "0":
			return ("Permitted");
			break;
		case "1":
			return ("Mandatory");
			break;
		case "2":
			return ("Not permitted");
			break;
	}

}

function order_number_capture_code($id){
	switch ($id) {
		case "1":
			return ("Mandatory");
			break;
		case "2":
			return ("Not permitted");
			break;
	}

}

function origin_dest_code($id){
	switch ($id) {
		case "0":
			return ("Origin");
			break;
		case "1":
			return ("Destination");
			break;
	}

}

function footnote_type_application_code($id){
	switch ($id) {
		case "1":
			return ("CN nomenclature");
			break;
		case "2":
			return ("TARIC nomenclature");
			break;
		case "3":
			return ("Export refund nomenclature");
			break;
		case "4":
			return ("Wine reference nomenclature");
			break;
		case "5":
			return ("Additional codes");
			break;
		case "6":
			return ("CN measures");
			break;
		case "7":
			return ("Other measures");
			break;
		case "8":
			return ("Meursing Heading");
			break;
		case "9":
			return ("Dynamic footnote");
			break;
	}

}

function rowclass($validity_start_date, $validity_end_date) {
	$rowclass = "";
	if (($validity_start_date == "2019-03-30") || ($validity_start_date == "2019-03-29")) {
		$rowclass = "uk";
	} elseif (($validity_start_date == "2019-01-01") && ($validity_end_date = NULL)) {
		$rowclass = "starts2019";
	} elseif ($validity_end_date != "") {
		$today	= date("Y-m-d");
		$d_today = strtotime($today);
		$d_ved	 = strtotime($validity_end_date);
		$diff	 = $d_ved - $d_today;
		if ($diff < 0) {
			$rowclass = "dead";
		}
	} else {
		$rowclass = "";
	}
	return ($rowclass);
}

function title_case($s) {
	$s = ucwords(strtolower($s));
	$s = str_replace(" And ", " and ", $s);
	$s = str_replace(" The ", " the ", $s);
	$s = str_replace(" In ", " in ", $s);
	$s = str_replace(" Of ", " of ", $s);
	$s = str_replace(" An ", " an ", $s);
	$s = str_replace(" On ", " on ", $s);
	$s = str_replace(" Or ", " or ", $s);
	$s = str_replace(" Not ", " not ", $s);
	return ($s);
}

function string_to_filtered_list($s) {
	$s = str_replace("\r\n", ",", $s);
	$s = str_replace("\n\r", ",", $s);
	$s = str_replace("\n", ",", $s);
	$s = str_replace("\t", ",", $s);
	$s = str_replace("\r", ",", $s);
	$s = str_replace(" ",  ",", $s);
	$s = str_replace(".",  ",", $s);
	
	$s_exploded = explode(",", $s);
	$s_exploded = array_filter($s_exploded);
	return ($s_exploded);
}

function set($data) {
	return array_map("unserialize", array_unique(array_map("serialize", $data)));
}

function get_measure($measure_sid) {
	global $conn;
	$measure = new measure;
	$measure->set_properties($measure_sid, "", "", "", "", "", "", "", "", "");

	$sql = "SELECT mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
    ded.description as duty_expression_description, mud.description as measurement_unit_description, muqd.description as measurement_unit_qualifier_description
    FROM duty_expression_descriptions ded, measurement_unit_qualifier_descriptions muqd RIGHT OUTER JOIN 
    measure_components mc ON mc.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
    LEFT OUTER JOIN measurement_unit_descriptions mud ON mc.measurement_unit_code = mud.measurement_unit_code
    WHERE measure_sid = " . $measure_sid . " AND ded.duty_expression_id = mc.duty_expression_id ORDER BY duty_expression_id";
	$result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $duty_expression_id                     = $row['duty_expression_id'];
            $duty_amount                            = $row['duty_amount'];
            $monetary_unit_code                     = $row['monetary_unit_code'];
            $measurement_unit_code                  = $row['measurement_unit_code'];
            $measurement_unit_qualifier_code        = $row['measurement_unit_qualifier_code'];
            $measurement_unit_description           = $row['measurement_unit_description'];
			$measurement_unit_qualifier_description = $row['measurement_unit_qualifier_description'];

			// These may need to be populated later
			$commodity_code = "";
			$additional_code_type_id = "";
			$additional_code_id = "";
			$measure_type_id = "";
			$quota_order_number_id = "";
			$geographical_area_id = "";
			$validity_start_date = "";
			$validity_end_date = "";
			
			$d = new duty;
			$d->set_properties($commodity_code, $additional_code_type_id, $additional_code_id, $measure_type_id,
			$duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code, $measure_sid,
			$quota_order_number_id, $geographical_area_id, $validity_start_date, $validity_end_date);

			array_push($measure->duty_list, $d);
        }
	}
	$measure->combine_duties();
	#echo ("Combined duty: " . $measure->combined_duty);
	return ($measure);

}
function update_type($i) {
	switch ($i) {
		case "1":
			return ("1 Update");
			break;
		case "2":
			return ("2 Delete");
			break;
		case "3":
			return ("3 Insert");
			break;
	}
}

function xml_item($item, $value) {
	print ('<tr class="govuk-table__row"><td class="govuk-table__cell">' . $item . '</td><td class="govuk-table__cell b">' . $value . '</td></tr>');
}

function xml_head($type, $i) {
    print ("<h3>$type record $i</h3>");
	print ('<table class="govuk-table" cellspacing="0">');
	print ('<tr class="govuk-table__row"><th class="govuk-table__header" style="width:15%">Property</th><th class="govuk-table__header" style="width:85%">Value</th></tr>');
}

function xml_foot() {
	print ("</table>");
	print ('<p class="back_to_top"><a href="#top">Back to top</a></p>');
}

function xml_count($id, $display, $xpath, $xml) {
	$nodes = $xml->xpath($xpath);
	$cnt = count($nodes);
	if ($cnt > 0) {
    	print ('<li><a href="#' . $id . '">' . $display . ' (' . $cnt . ' instances)</a></li>');
	}
}
function pre($data) {
	print '<pre>' . print_r($data, true) . '</pre>';
}

function prex($data) {
	$data = str_replace('&', '&amp;', $data);
	$data = str_replace('<', '&lt;', $data);
	$data = str_replace('>', '&gt;', $data);
	print '<pre>' . print_r($data, true) . '</pre>';
}

function to_date($day, $month, $year) {
	$day = str_pad($day, 2, '0', STR_PAD_LEFT);
	$month = str_pad($month, 2, '0', STR_PAD_LEFT);
	$date = $year . "-" . $month . "-" . $day;
	$d = DateTime::createFromFormat('Y-m-d', $date);
	#echo ($d);
	return ($d);
}

function to_date_string($day, $month, $year) {
	$day = str_pad($day, 2, '0', STR_PAD_LEFT);
	$month = str_pad($month, 2, '0', STR_PAD_LEFT);
	$date = $year . "-" . $month . "-" . $day;
	return ($date);
}

function p($s) {
	echo ("<p>" . $s . "</p>");
}

function h1($s) {
	echo ("<h1>" . $s . "</h1>");
}
?>