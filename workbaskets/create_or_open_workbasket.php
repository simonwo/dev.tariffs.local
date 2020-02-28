<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler();
$submitted = get_formvar("submitted");
$workbasket = new workbasket();
if ($submitted) {
    $application->session->create_or_open_workbasket();
    $application->session->workbasket = $workbasket;
} else {
    $request_uri = get_querystring("request_uri");
}
//h1 ($request_uri);
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
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create new or select existing workbasket</h1>
                    <!-- End main title //-->


                    <form id="create_or_add_to_existing" action="" novalidate method="post">


                        <?php

                        //pre ($_SERVER);

                        new hidden_control(
                            $control_name = "request_uri",
                            $value = $request_uri
                        );
            
                        new inset_control(
                            $text = "You currently do not have an open workbasket. Please specify if you would like to open an existing 'In progress' workbasket or create a new one.",
                        );
                        new radio_control(
                            $label = "Select an option",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "",
                            $control_name = "workbasket_id",
                            $dataset = $application->get_my_workbaskets_or_new(),
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true,
                            $disabled_on_edit = false
                        );
                        
                        new input_control(
                            $label = "What is the workbasket name?",
                            $label_style = "govuk-label",
                            $hint_text = "",
                            $control_name = "title",
                            $control_style = "govuk-input govuk-!-width-one-half",
                            $size = 100,
                            $maxlength = 100,
                            $pattern = "",
                            $required = "required",
                            $default = $workbasket->title,
                            $default_on_insert = "",
                            $disabled_on_edit = "",
                            $custom_errors = "workbasket_exists",
                            $group_class = "new_workbasket"
                        );

                        // Description
                        new character_count_control(
                            $label = "What is the reason for creating this workbasket?",
                            $label_style = "govuk-label",
                            $hint_text = "",
                            $control_name = "reason",
                            $rows = 5,
                            $maxlength = 500,
                            $required = "required",
                            $default = $workbasket->reason,
                            $pattern = "",
                            $control_scope = "",
                            $custom_errors = "",
                            $group_class = "new_workbasket"
                        );

                        new hidden_control(
                            $control_name = "user_id",
                            $value = $_SESSION["uid"]
                        );

                        //$btn = new button_cluster_control();
                        //$btn->submit_button_text = "Create or open workbasket";
                        ?>


                        <!-- Start button //-->
                        <input type="hidden" name="submitted" value="1" />
                        <button id="btn_create_or_open_workbasket" class="govuk-button" data-module="govuk-button">Create or open workbasket</button>
                        <a href="/" class="textual_button govuk-link">Cancel</a>
                        <!-- End button //-->
                    </form>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>