<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers");
$error_handler = new error_handler();
$quota_order_number = new quota_order_number();
$quota_order_number->get_parameters();
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

        $control_content["quota_commodities"] = $quota_order_number->quota_commodities;
        $control_content["quota_measures"] = $quota_order_number->quota_measures;
        if (!$quota_order_number->licensed) {
            $control_content["origins"] = $quota_order_number->origins;
            $control_content["quota_definitions"] = $quota_order_number->quota_definitions;
            $control_content["quota_associations"] = $quota_order_number->quota_associations;
            $control_content["quota_suspension_periods"] = $quota_order_number->quota_suspension_periods;
            $control_content["quota_blocking_periods"] = $quota_order_number->quota_blocking_periods;
        }

        new view_form($control_content, $quota_order_number);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>