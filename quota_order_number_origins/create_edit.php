<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_number_origins");
$error_handler = new error_handler();
$quota_order_number_origin = new quota_order_number_origin();
$quota_order_number_origin->get_parameters();
$application->get_measurement_units($use_common = true);
$application->get_measurement_unit_qualifiers();
$application->get_quota_precisions();
$application->get_create_measures_yes_no();
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
        $control_content["measurement_unit_code"] = $application->measurement_units;
        $control_content["measurement_unit_qualifier_code"] = $application->measurement_unit_qualifiers;
        $control_content["maximum_precision"] = $application->quota_precisions;
        $control_content["create_measures"] = $application->create_measures_yes_no;
        $control_content["geographical_area_exclusions"] = null;
        
        new data_entry_form($control_content, $quota_order_number_origin, $left_nav = "", $action = "actions.php");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>