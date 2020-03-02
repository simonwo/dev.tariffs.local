<?php
// required headers

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$application->get_additional_code_types();
//prend ($application->additional_code_types);

$additional_code_type_id = trim(get_querystring("additional_code_type_id"));

if (sizeof($application->additional_code_types) > 0) {
    $parent_array = array();
    $parent_array["results"] = array();
    if ($additional_code_type_id != "") {
        foreach ($application->additional_code_types as $item) {
            if ($item->additional_code_type_id == $additional_code_type_id) {
                array_push($parent_array["results"], $item);
            }
        }
    } else {
        foreach ($application->additional_code_types as $item) {
            array_push($parent_array["results"], $item);
        }
    }
    http_response_code(200);
    echo json_encode($parent_array);
} else {
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
