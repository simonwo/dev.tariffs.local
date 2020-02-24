<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("blocking_periods");
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
    case "blocking_period":
        h1("blocking period");
        $quota_blocking_period = new quota_blocking_period();
        switch ($mode) {
            case "insert":
                $quota_blocking_period->validate_form();
                break;
            case "update":
                $quota_blocking_period->validate_form();
                break;
            case "delete":
                break;
        }
        break;

    default:
        break;
}
exit();
