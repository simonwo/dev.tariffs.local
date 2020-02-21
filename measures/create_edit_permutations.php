<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_permutations");
$application->get_duties_same_for_all_commodities();

$error_handler = new error_handler();

$measure_activity = new measure_activity();
$measure_activity->get_sid();
$measure_activity->populate_permutations_form();
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
if ($measure_activity->suppress_additional_codes_field == true) {
?>
    <script>
        $(document).ready(function() {
            $(".additional_codes_group").hide();
        });
    </script>
<?php
}
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        if (intval($measure_activity->measure_component_applicable_code) == 2) {
        ?>
            <script>
                $(document).ready(function() {
                    $(".suited_for_duties").hide();
                });
            </script>
        <?php
        }

        require("../includes/phase_banner.php");
        $control_content = array();
        $control_content["duties_same_for_all_commodities"] = $application->duties_same_for_all_commodities;
        new data_entry_form($control_content, $measure_activity, $left_nav = "", "measure_activity_actions.php");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>