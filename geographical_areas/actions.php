<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_request("action");

//prend($_REQUEST);

switch ($action) {
    case "delete_geographical_area_description":
        $geographical_area = new geographical_area();
        $geographical_area->geographical_area_sid = get_querystring("geographical_area_sid");
        $geographical_area->geographical_area_id = get_querystring("geographical_area_id");
        $geographical_area->geographical_area_description_period_sid = get_querystring("period_sid");
        $confirm_delete = get_formvar("confirm_delete");
        if ($confirm_delete == "Yes") {
            $geographical_area->delete_description();
            $url = "/geographical_area_descriptions/delete_confirmation.html?mode=view&geographical_area_id=" . $geographical_area->geographical_area_id . "&geographical_area_sid=" . $geographical_area->geographical_area_sid;
            header("Location: " . $url);
            break;
        } else {
            $url = "/geographical_areas/view.html?mode=view&geographical_area_id=" . $geographical_area->geographical_area_id . "&geographical_area_sid=" . $geographical_area->geographical_area_sid . "&notify=Deletion+cancelled#tab_geographical_area_descriptions";
            header("Location: " . $url);
        }
}
