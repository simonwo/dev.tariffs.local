<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_core");
$error_handler = new error_handler();
$measure_activity = new measure_activity();

if (isset($_SESSION["measure_activity_sid"])) {
    $measure_activity->measure_activity_sid = $_SESSION["measure_activity_sid"];
    $measure_activity->populate_core_from_db();
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