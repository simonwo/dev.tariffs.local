<?php
// required headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$measure_activity = new measure_activity();
$measure_activity->measure_activity_sid = trim(get_querystring("measure_activity_sid"));
$measure_activity->get_full_summary();
$measure_activity->duty_list = array();

// Check what conditions have variable rates
// If there are any conditions with variable rates, then it will just be conditions
// If there are any conditions at all where the duty is present, then it will be just conditions, and there are no duties to enter here
// Otherwise, it will be just standard non-conditional duties

$contains_conditional_rates = false;
$contains_conditional_variable_rates = false;

$condition_count = count($measure_activity->measure_conditions);
if ($condition_count > 0) {
    foreach ($measure_activity->measure_conditions as $mc) {
        if ($mc->requires_duty == "t") {
            $contains_conditional_rates = true;
            if ($mc->applicable_duty == "variable") {
                $contains_conditional_variable_rates = true;
            }
        }
    }
}

$duty_array = array();
if (!$contains_conditional_rates) {
    // Standard rates only
    //h1 ("here");
    array_push($duty_array, "standard_duty");
}

//prend($measure_activity);

if (count($measure_activity->additional_code_list) > 0) {
    // If there are additional codes
    foreach ($measure_activity->additional_code_list as $ac) {
        foreach ($measure_activity->commodity_code_list as $cc) {
            $item = new reusable();
            $item->commodity_code = $cc->goods_nomenclature_item_id;
            $item->additional_code = $ac->code;
            foreach ($duty_array as $duty) {
                $item->{$duty} = "";
            }
            /*
            $item->duty001 = "";
            $item->duty002 = "";
            $item->duty003 = "";
            */
            array_push($measure_activity->duty_list, $item);
        }
    }
} else {
    // If there are no additional codes
    foreach ($measure_activity->commodity_code_list as $cc) {
        $item = new reusable();
        $item->commodity_code = $cc->goods_nomenclature_item_id;
        $item->duty001 = "";
        $item->duty002 = "";
        $item->duty003 = "";
        array_push($measure_activity->duty_list, $item);
    }
}

http_response_code(200);

$response = "{\"data\":" . json_encode($measure_activity->duty_list) . "}";

if (isset($_GET['callback'])) {
    echo $_GET['callback'] . '(' . $response . ')';
} else {
    echo ($response);
}
