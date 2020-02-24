<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$application->get_current_geographical_areas();

$count = count($application->current_geographical_areas);
if ($count > 0) {
    echo ("{");
    $index = 0;
    foreach ($application->current_geographical_areas as $item) {
        $index ++;
        echo ('"' . $item->id . '" : "' . $item->string . '"');
        if ($index < $count) {
            echo (",");
        }
        echo ("\n");
    }
    echo ("}");

    http_response_code(200);
    //echo json_encode($parent_array);
} else {
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
