<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$action = get_request("action");

//prend($_REQUEST);

switch ($action) {
    case "delete_certificate_description":
        $certificate = new certificate();
        $certificate->certificate_type_code = get_querystring("certificate_type_code");
        $certificate->certificate_code = get_querystring("certificate_code");
        $certificate->certificate_description_period_sid = get_querystring("period_sid");
        $confirm_delete = get_formvar("confirm_delete");
        if ($confirm_delete == "Yes") {
            $certificate->delete_description();
            $url = "/certificate_descriptions/delete_confirmation.html?mode=view&certificate_code=" . $certificate->certificate_code . "&certificate_type_code=" . $certificate->certificate_type_code;
            header("Location: " . $url);
            //$application->session->workbasket->close_workbasket();
            break;
        } else {
            $url = "/certificates/view.html?mode=view&certificate_code=" . $certificate->certificate_code . "&certificate_type_code=" . $certificate->certificate_type_code . "&notify=Deletion+cancelled#tab_certificate_descriptions";
            header("Location: " . $url);
        }
}
