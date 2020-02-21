<?php
require(dirname(__FILE__) . "../../includes/db.php");
//prend ($_REQUEST);
$application = new application;
$application->init("measures_conditions");
$application->get_conditional_duty_application_options();
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
$measure_activity = new measure_activity();
$measure_activity->populate_conditions_form();
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