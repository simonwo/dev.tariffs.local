<?php
require(dirname(__FILE__) . "../../includes/db.php");
prend ($_REQUEST);

$application = new application;
$error_handler = new error_handler();
$measure_activity = new measure_activity();

$action = get_formvar("action");
if ($action == "") {
    $action = get_querystring("action");
}

switch ($action) {
    case "activity_name":
        $measure_activity->validate_form_activity_name();
        break;

    case "core":
        $measure_activity->validate_form_core();
        break;

    case "permutations":
        $measure_activity->validate_form_permutations();
        break;

    case "conditions":
        $measure_activity->validate_form_conditions();
        break;

    case "add_condition":
        $measure_activity->add_condition();
        break;

    case "delete_condition":
        $measure_activity->delete_condition();
        break;

    case "promote_condition":
        $measure_activity->promote_condition();
        break;

    case "demote_condition":
        $measure_activity->demote_condition();
        break;

    case "add_footnote":
        $measure_activity->add_footnote();
        break;

    case "delete_footnote":
        $measure_activity->delete_footnote();
        break;

    case "duties":
        $measure_activity->validate_form_duties();
        break;

    case "footnotes":
        $measure_activity->validate_form_footnotes();
        break;

    default:
        prend($_REQUEST);
        break;
}
exit();
