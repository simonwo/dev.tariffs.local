<?php
// required headers
$debug = false;
if (!$debug) {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
}

include '../../../includes/db.php';
$application = new application;
$measure_activity = new measure_activity();
$measure_activity->measure_activity_sid = trim(get_querystring("measure_activity_sid"));
$measure_activity->get_full_summary();
$measure_activity->duty_list = array();

//prend($measure_activity->measure_conditions);

if ($debug) {
    //prend($measure_activity);
}

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
            if ($mc->applicable_duty_permutation == 1) {
                $contains_conditional_variable_rates = true;
            }
        }
    }
}
/*
h1 ("Contains conditional rates: " . $contains_conditional_rates);
h1 ("Contains conditional variable rates: " . $contains_conditional_variable_rates);
*/

$duty_array = array();

if (!$contains_conditional_rates) {
    // Standard rates only
    $duty = new reusable();
    $duty->label = "Standard (non-conditional) duty";
    $duty->value = "";
    array_push($duty_array, $duty);
} else {
    $index = 0;
    foreach ($measure_activity->measure_conditions as $mc) {
        if ($mc->requires_duty == "t") {
            $index++;
            $duty = new reusable();
            if ($mc->condition_code_type == 0) {
                // The conditions are based on provision (or not) of a certificate or other document
                if ($mc->certificate_type_code != "") {
                    $duty->label = $mc->condition_code . $mc->component_sequence_number . ": Duty payable on provision of certificate " . $mc->certificate_type_code . $mc->certificate_code;
                } else {
                    $duty->label = $mc->condition_code . $mc->component_sequence_number . ": Duty payable with no certificate";
                }
            } else {
                // The conditions are based on exceeding a reference price
                $duty->label = $mc->condition_code . $mc->component_sequence_number . ": Reference price is greater than " . $mc->reference_price;
            }

            if ($mc->applicable_duty_permutation == 1) {
                $duty->label .= " (variable)";
            } else {
                $duty->label .= " (fixed)";
            }


            if ($mc->applicable_duty == "variable") {
                $duty->value = "";
            } else {
                $duty->value = $mc->applicable_duty;
            }
            array_push($duty_array, $duty);
        }
    }
}

if (count($measure_activity->additional_code_list) > 0) {
    // If there are additional codes
    foreach ($measure_activity->additional_code_list as $ac) {
        foreach ($measure_activity->commodity_code_list as $cc) {
            $item = new reusable();
            $item->commodity_code = $cc->goods_nomenclature_item_id;
            $item->additional_code = $ac->code;
            foreach ($duty_array as $duty) {
                $item->{$duty->label} = $duty->value;
            }
            array_push($measure_activity->duty_list, $item);
        }
    }
} else {
    // If there are no additional codes
    foreach ($measure_activity->commodity_code_list as $cc) {
        $item = new reusable();
        $item->commodity_code = $cc->goods_nomenclature_item_id;
        //$item->additional_code = "";
        foreach ($duty_array as $duty) {
            $item->{$duty->label} = $duty->value;
        }
        array_push($measure_activity->duty_list, $item);
    }
}

if ($debug) {
    prend($measure_activity->duty_list);
}
http_response_code(200);

$response = "{\"data\":" . json_encode($measure_activity->duty_list) . "}";

if (isset($_GET['callback'])) {
    echo $_GET['callback'] . '(' . $response . ')';
} else {
    echo ($response);
}
