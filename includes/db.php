<?php
require (dirname(__FILE__) . "../../classes/application.php");
require (dirname(__FILE__) . "../../classes/error_handler.php");
require (dirname(__FILE__) . "../../classes/measure_type.php");
require (dirname(__FILE__) . "../../classes/geographical_area.php");
require (dirname(__FILE__) . "../../classes/quota_order_number.php");
require (dirname(__FILE__) . "../../classes/quota_order_number_origin.php");
require (dirname(__FILE__) . "../../classes/quota_order_number_origin_exclusion.php");

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
$msg = "All data displayed uses the <strong>" . $dbase . "</strong> database";

$pagesize	= 50;
$conn		= pg_connect("host=127.0.0.1 port=5432 dbname=" . $dbase . " user=postgres password=zanzibar");
$page       = intval(get_querystring("page"));
if ($page == 0) {$page = 1;}

function string_to_date($var) {
	if ($var != "") {
		$var2 = DateTime::createFromFormat('Y-m-d H:i:s', $var)->format('Y-m-d');
		#$ar = explode(" ", $var2);
		#$var2 = $ar[0];
	} else {
		$var2 = "";
	}
	#echo ("<p>" . $var2 . "</p>");
	return ($var2);
}

function get_cookie($key) {
	if(isset($_COOKIE[$key])) {
		return ($_COOKIE[$key]);
	} else {
		return ("");
	}
}

function get_formvar($key) {
	if( isset($_REQUEST[$key])){
		return (trim($_REQUEST[$key]));
	}
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
function p($s) {
	echo ("<p>" . $s . "</p>");
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
?>