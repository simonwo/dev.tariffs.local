<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("blocking_periods");
$application->get_blocking_period_types();
$error_handler = new error_handler();
$quota_blocking_period = new quota_blocking_period();
$quota_blocking_period->get_parameters();
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
        $control_content["quota_definition_sid"] = $quota_blocking_period->get_active_definitions();
        $control_content["blocking_period_type"] = $application->blocking_period_types;
        new data_entry_form($control_content, $quota_blocking_period, $left_nav = "", $action = "actions.php");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>