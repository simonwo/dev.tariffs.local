<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_associations");
$error_handler = new error_handler();

$action = get_formvar("action");
if ($action == "") {
    $action = get_querystring("action");
}

$mode = get_formvar("mode");
if ($mode == "") {
    $mode = get_querystring("mode");
}
//prend($_REQUEST);

switch ($action) {
    case "quota_association":
        $quota_association = new quota_association();
        switch ($mode) {
            case "insert":
                $quota_association->validate_form();
                break;
            case "update":
                $quota_association->validate_form();
                break;
            case "delete":
                break;
        }
        break;

    default:
        break;
}
exit();
