<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler();
//pre ($_REQUEST);
$workbasket_id = get_querystring("workbasket_id");
$workbasket_item_sid = get_querystring("workbasket_item_sid");
?>
<html>

<head>
    <link href="/css/govuk-frontend-3.4.0.min.css" rel="stylesheet" />
    <link href="/css/application.css" rel="stylesheet" />
</head>

<body>
    <h1 class="govuk-heading-l">Reject activity</h1>
    <form action="./actions.php" method="get">
    <?php


    new hidden_control(
        $control_name = "workbasket_id",
        $value = $workbasket_id
    );

    new hidden_control(
        $control_name = "workbasket_item_sid",
        $value = $workbasket_item_sid
    );

    new hidden_control(
        $control_name = "action",
        $value = "reject_workbasket_item"
    );

    new character_count_control(
        $label = "Please describe the reason for rejecting this workbasket activity.",
        $label_style = "govuk-label",
        $hint_text = "",
        $control_name = "reason",
        $rows = 5,
        $maxlength = 500,
        $required = "",
        $default = "",
        $pattern = "",
        $control_scope = "",
        $custom_errors = "",
        $group_class = ""
    );
    new button_control("Confirm", "button_reject", "primary", true, "");
    ?>
</form>
</body>

</html>