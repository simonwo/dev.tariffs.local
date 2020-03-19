<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_duties");
$error_handler = new error_handler();
$measure_activity = new measure_activity();
$measure_activity->get_sid();
$measure_activity->populate_duties_form();

// Hide the Trade remedy-related items if the user has not added in any trade remedy-related additional codes
$show_trade_remedies_fields = false;
foreach ($measure_activity->additional_code_list as $additional_code) {
    $code = trim($additional_code->code);
    $additional_code_type_id = strtoupper(substr($code, 0, 1));
    if (in_array($additional_code_type_id, array('8', 'A', 'B', 'C', 'E', 'F'))) {
        $show_trade_remedies_fields = true;
        break;
    }
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
        new data_entry_form($control_content, $measure_activity, $left_nav = "", "measure_activity_actions.php");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>