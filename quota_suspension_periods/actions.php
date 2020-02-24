<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("suspension_periods");
$error_handler = new error_handler();

$action = get_formvar("action");
if ($action == "") {
    $action = get_querystring("action");
}

$mode = get_formvar("mode");
if ($mode == "") {
    $mode = get_querystring("mode");
}
pre($_REQUEST);

switch ($action) {
    case "suspension_period":
        h1("Suspension period");
        $quota_suspension_period = new quota_suspension_period();
        switch ($mode) {
            case "insert":
                $quota_suspension_period->validate_form();
                break;
            case "update":
                $quota_suspension_period->validate_form();
                break;
            case "delete":
                break;
        }
        break;

    default:
        break;
}
exit();
