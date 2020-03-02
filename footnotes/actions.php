<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_request("action");

//prend($_REQUEST);

switch ($action) {
    case "delete_footnote_description":
        $footnote = new footnote();
        $footnote->footnote_type_id = get_querystring("footnote_type_id");
        $footnote->footnote_id = get_querystring("footnote_id");
        $footnote->footnote_description_period_sid = get_querystring("period_sid");
        $confirm_delete = get_formvar("confirm_delete");
        if ($confirm_delete == "Yes") {
            $footnote->delete_description();
            $url = "/footnote_descriptions/delete_confirmation.html?mode=view&footnote_id=" . $footnote->footnote_id . "&footnote_type_id=" . $footnote->footnote_type_id;
            header("Location: " . $url);
            //$application->session->workbasket->close_workbasket();
            break;
        } else {
            $url = "/footnotes/view.html?mode=view&footnote_id=" . $footnote->footnote_id . "&footnote_type_id=" . $footnote->footnote_type_id . "&notify=Deletion+cancelled#tab_footnote_descriptions";
            header("Location: " . $url);
        }
}
