<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_querystring("action");
switch ($action) {
    case "close":
        $application->session->close_workbasket();
        break;

    case "withdraw":
        $workbasket_id = get_querystring("workbasket_id");
        $application->session->withdraw_workbasket($workbasket_id);
        break;

    case "open":
        $workbasket_id = get_querystring("workbasket_id");
        $application->session->open_workbasket($workbasket_id);
        break;

    case "delete_workbasket_item":
        //prend ($_REQUEST);
        //pre($application->session->workbasket);
        $workbasket_item_id = get_querystring("workbasket_item_id");
        $application->session->workbasket->delete_workbasket_item($workbasket_item_id);
        //die();
        $url = "view.html";
        header("Location: " . $url);
        break;
}
