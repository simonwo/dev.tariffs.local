<?php
require(dirname(__FILE__) . "../../includes/db.php");

$application = new application;
//$application->init("measures_core");
$error_handler = new error_handler();
$measure_activity = new measure_activity();

$action = get_formvar("action");

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
}
exit();
