<?php
require(dirname(__FILE__) . "../../includes/db.php");
$additional_code_type = new additional_code_type();
$additional_code_type->additional_code_type_id = get_querystring("additional_code_type_id");
$measure_type_id = get_querystring("measure_type_id");
$application = new application;
$application->mode = "update";
# Initialise the error handler
$error_handler = new error_handler;
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
        ?>
<!-- Start breadcrumbs //-->
<div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="./">Additional code types</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Additional code type <?= $additional_code_type->additional_code_type_id ?></li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Terminate relationship of measure type <?= $measure_type_id ?> to additional code type <?= $additional_code_type->additional_code_type_id ?></h1>
                    <!-- End main title //-->
                    <?php
                    //new inset_control("Use this form to associate a measure type with addtional code type " . $additional_code_type->additional_code_type_id);
                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <?php
                    
                    new date_picker_control(
                        $label = "Please select the date of which to terminate the relationship",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "date",
                        $control_scope = "update",
                        $default = "",
                        $required = true,
                        $custom_errors = ""
                    );
                    $btn = new button_control("Continue", "continue", "primary");
                    $btn2 = new button_control("Cancel", "cancel", "text");
                ?>

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>