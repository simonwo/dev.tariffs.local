<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler();
$submitted = get_formvar("submitted");
$workbasket = new workbasket();
$workbasket->workbasket_id = get_querystring("workbasket_id");
$workbasket->populate();
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
                    <h1 class="govuk-heading-xl">Edit workbasket details</h1>
                    <!-- End main title //-->

                    <form action="actions.php" method="post">
                        <?php

                        new inset_control(
                            $text = "Use this screen to create a update the title and the purpose of this workbasket",
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
                            $disabled = false,
                            $custom_errors = "workbasket_exists"
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
                            $control_scope = ""
                        );

                        new hidden_control(
                            $control_name = "user_id",
                            $value = $_SESSION["uid"]
                        );
                        $btn = new button_cluster_control();
                        $btn->submit_button_text = "Create workbasket";
                        ?>


                        <!-- Start button //-->
                        <input type="hidden" name="submitted" value="1" />
                        <button class="govuk-button" data-module="govuk-button">Update workbasket details</button>
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