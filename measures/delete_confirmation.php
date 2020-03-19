<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
//$application->init("measures_conditions");
$application->get_conditional_duty_application_options();
$error_handler = new error_handler();
$measure_activity = new measure_activity();
$measure_activity->get_sid();
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
                    <a class="govuk-breadcrumbs__link" href="/measures">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Cancel new measures</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Cancel measure creation</h1>
                    <!-- End main title //-->
                    <form action="./measure_activity_actions.html" method="get">

                        <?php
                        /*
                        new warning_control(
                            $text = "<span class='highlighted_text'>You have opted to delete the existing certificate description for xxx:<br /><br /><strong></strong><br /><br />By selecting 'Yes' below, you will delete the this description. This action cannot be undone.</span>",
                        );
                        */

                        new radio_control(
                            $label = "Are you sure you want to cancel the creation of these measures?",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "",
                            $control_name = "confirm_delete",
                            $dataset = $application->get_yes_no(),
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true,
                            $disabled_on_edit = false
                        );

                        ?>
                        <?php
                        new hidden_control("measure_activity_sid", $measure_activity->measure_activity_sid);
                        new hidden_control("action", "cancel_confirm");
                        $btn = new button_control("Continue", "continue", "primary");
                        ?>
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