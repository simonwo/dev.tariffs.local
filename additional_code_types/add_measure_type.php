<?php
require(dirname(__FILE__) . "../../includes/db.php");
$additional_code_type = new additional_code_type();
$additional_code_type->additional_code_type_id = get_querystring("additional_code_type_id");

// http://dev.tariffs.local/additional_code_types/add_measure_type.html?additional_code_type_id=2
$application = new application;
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
                    <h1 class="govuk-heading-xl">Add measure type to additional code type <?= $additional_code_type->additional_code_type_id ?></h1>
                    <!-- End main title //-->
                    <?php
                    //new inset_control("Use this form to associate a measure type with addtional code type " . $additional_code_type->additional_code_type_id);
                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <?php
                    new input_control(
                        $label = "What measure type do you want to associate?",
                        $label_style = "govuk-label--m",
                        $hint_text = "Start typing the ID of the measure type",
                        $control_name = "measure_type_id",
                        $control_style = "govuk-input govuk-input--width-30 condition_mechanic_reference_duty duty",
                        $size = 100,
                        $maxlength = 100,
                        $pattern = "",
                        $required = "required",
                        $default = "",
                        $default_on_insert = "",
                        $disabled = false,
                        $custom_errors = "",
                        $group_class = ""
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