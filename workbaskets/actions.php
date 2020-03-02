<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_request("action");

//prend($_REQUEST);

switch ($action) {
    case "close":
        $application->session->workbasket->close_workbasket();
        break;

    case "delete_workbasket":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $confirm_delete = get_querystring("confirm_delete");

        if ($confirm_delete == 'Yes') {
            $workbasket->delete_workbasket();
            $url = "workbasket_delete_confirmation.html";
        } else {
            $url = "/#workbaskets";
        }
        header("Location: " . $url);
        break;


    case "submit_for_approval":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket->submit_workbasket_for_approval();
        break;

    case "take_ownership":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket->take_ownership();
        $url = "view.html?notify=Ownership+of+this+workbasket+has+been+handed+over.&workbasket_id=" . $workbasket->workbasket_id;
        header("Location: " . $url);
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
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_sid = get_querystring("workbasket_item_sid");
        $confirm_delete = get_querystring("confirm_delete");
        if ($confirm_delete == "Yes") {
            $workbasket->delete_workbasket_item($workbasket_item_sid);
            $url = "view.html?notify=The+workbasket+item+has+been+deleted&workbasket_id=" . $workbasket_id . "#workbasket_activities";
        } else {
            $url = "view.html?workbasket_id=" . $workbasket_id . "#workbasket_activities";
        }
        header("Location: " . $url);
        break;

    case "approve_workbasket_item":
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_sid = get_querystring("workbasket_item_sid");
        $workbasket->approve_workbasket_item($workbasket_item_sid);
        $url = "view.html?workbasket_id=" . $workbasket->workbasket_id . "#workbasket_item_sid_" . $workbasket_item_sid;
        header("Location: " . $url);
        break;

    case "reject_workbasket_item":
        //prend ($_REQUEST);
        $workbasket = new workbasket();
        $workbasket->workbasket_id = get_querystring("workbasket_id");
        $workbasket_item_sid = get_querystring("workbasket_item_sid");
        $reason = get_querystring("reason");
        $workbasket->reject_workbasket_item($workbasket_item_sid, $reason);
        $url = "view.html?workbasket_id=" . $workbasket->workbasket_id . "#workbasket_item_sid_" . $workbasket_item_sid;
        header("Location: " . $url);
        break;
}
