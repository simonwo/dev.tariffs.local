<?php
require (dirname(__FILE__) . "/includes/db.php");
require (dirname(__FILE__) . "/classes/duty.php");
require (dirname(__FILE__) . "/classes/measure.php");

$d = new duty;
$d2 = new duty;
$m = new measure;

$commodity_code = "";
$additional_code_type_id = "";
$additional_code_id = "";
$measure_type_id = "";
$duty_expression_id = "01";
$duty_amount = "6.4";
$monetary_unit_code = "";
$measurement_unit_code = "";
$measurement_unit_qualifier_code = "";
$measure_sid = "";
$quota_order_number_id = "";
$geographical_area_id = "";
$validity_start_date = "";
$validity_end_date = "";

$d->set_properties($commodity_code, $additional_code_type_id, $additional_code_id, $measure_type_id,
$duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code, $measure_sid,
$quota_order_number_id, $geographical_area_id, $validity_start_date, $validity_end_date);

$d2->set_properties($commodity_code, $additional_code_type_id, $additional_code_id, $measure_type_id,
"04", "100", "EUR", "KGM", $measurement_unit_qualifier_code, $measure_sid,
$quota_order_number_id, $geographical_area_id, $validity_start_date, $validity_end_date);





$m = new measure;
$measure_sid = 3398754;
$commodity_code = "82374983279";
$quota_order_number_id = "093453";
$validity_start_date = "1029";
$validity_end_date = "joij";
$geographical_area_id = "1011";

$m->set_properties($measure_sid, $commodity_code, $quota_order_number_id, $validity_start_date, $validity_end_date, $geographical_area_id);
array_push($m->duty_list, $d);
array_push($m->duty_list, $d2);
$m->combine_duties();
print ($m->combined_duty);

exit();


?>