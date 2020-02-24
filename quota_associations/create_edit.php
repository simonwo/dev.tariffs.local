<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_associations");
$application->get_relation_types();
$error_handler = new error_handler();
$quota_association = new quota_association();
$quota_association->get_parameters();
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
        $control_content["relation_type"] = $application->relation_types;
        $control_content["quota_definition_sid"] = $quota_association->get_active_definitions();
        new data_entry_form($control_content, $quota_association, $left_nav = "", $action = "actions.php");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>