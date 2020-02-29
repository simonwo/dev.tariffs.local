<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_request("action");

//prend($_REQUEST);

switch ($action) {
    case "close":
        $application->session->workbasket->close_workbasket();
        break;

    case "submit_for_approval":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket->submit_workbasket_for_approval();
        break;

    case "filter":
        $application->session->filter_workbasket();
        break;

    case "update_workbasket":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket->update_workbasket();
        break;

    case "withdraw":
        $withdraw_workbasket = get_querystring("withdraw_workbasket");
        if ($withdraw_workbasket == 'Yes') {
            $workbasket = new workbasket();
            $workbasket->workbasket_id = get_querystring("workbasket_id");
            $workbasket->withdraw_workbasket();
        } else {
            $url = "/#workbaskets";
            header("Location: " . $url);
        }
        break;

    case "open":
        $workbasket_id = get_querystring("workbasket_id");
        $application->session->open_workbasket($workbasket_id);
        break;

    case "delete_workbasket_item":
        //prend ($_REQUEST);
        $withdraw_workbasket_item = get_querystring("withdraw_workbasket_item");
        $workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_id = get_querystring("workbasket_item_id");
        if ($withdraw_workbasket_item == "Yes") {
            $application->session->workbasket->delete_workbasket_item($workbasket_item_id);
            $url = "view.html";
        } else {
            $url = "view.html#workbasket_activities";
        }
        header("Location: " . $url);
        break;

    case "approve_workbasket_item":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_id = get_querystring("workbasket_item_id");
        $workbasket->approve_workbasket_item($workbasket_item_id);
        $url = "view.html?workbasket_id=" . $workbasket->workbasket_id . "#workbasket_item_id_" . $workbasket_item_id;
        header("Location: " . $url);
        break;

    case "reject_workbasket_item":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_id = get_querystring("workbasket_item_id");
        $workbasket->reject_workbasket_item($workbasket_item_id);
        $url = "view.html?workbasket_id=" . $workbasket->workbasket_id . "#workbasket_item_id_" . $workbasket_item_id;
        header("Location: " . $url);
        break;
}
