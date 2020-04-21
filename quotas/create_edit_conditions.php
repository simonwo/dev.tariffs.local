<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers_conditions");
$application->get_conditional_duty_application_options();

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

if ($submitted == 1) {
    $_SESSION["commodity_codes"] = get_formvar("commodity_codes");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_conditions();
} else {
    $quota_order_number = new quota_order_number();
    $quota_order_number->get_parameters();
    $quota_order_number->measure_activity = new measure_activity();
    $quota_order_number->measure_activity->populate_conditions_form();
    }
?>

<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        $control_content = array();
        new data_entry_form($control_content, $quota_order_number, $left_nav = "");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

