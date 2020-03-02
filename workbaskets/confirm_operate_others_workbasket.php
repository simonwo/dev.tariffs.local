<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler();
$workbasket = new workbasket();
$workbasket->workbasket_id = get_session_variable("workbasket_id");
$workbasket->populate();

$submitted = get_formvar("submitted");
if ($submitted) {
    $continue = get_formvar("continue");
    $request_uri = get_formvar("request_uri");
    //h1 ($continue);
    //die();
    if ($continue == 'No') {
        unset($_SESSION["workbasket_id"]);
        $_SESSION["confirm_operate_others_workbasket"] = "";
        //$url = "/#workbaskets";
        header("Location: " . $request_uri);
    } else {
        $_SESSION["confirm_operate_others_workbasket"] = "Yes";
        $url = "/#workbaskets";
        header("Location: " . $url);
    }
} else {
    $request_uri = get_querystring("request_uri");
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
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/#workbaskets">Workbaskets</a>
                </li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Please confirm you want to continue</h1>
                    <!-- End main title //-->


                    <form id="confirm_operate_others_workbasket" action="" novalidate method="post">
                        <?php
                        new hidden_control(
                            $control_name = "request_uri",
                            $value = $request_uri
                        );
            
                        new warning_control(
                            $text = "<span class='highlighted_text'>The currently active workbasket '<strong>" . $workbasket->title . "'</strong> belongs to another user (<strong>" . $workbasket->user_name . "</strong>). Please confirm that you would like to use this workbasket.</span>",
                        );

                        new radio_control(
                            $label = "Please confirm if you want to use this workbasket.",
                            $label_style = "govuk-fieldset__legend--s",
                            $hint_text = "",
                            $control_name = "continue",
                            $dataset = $application->get_yes_no_continue(),
                            $selected = null,
                            $radio_control_style = "stacked_detail",
                            $required = true,
                            $disabled_on_edit = false
                        );
                        
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
                            $value = $_SESSION["user_id"]
                        );

                        ?>


                        <!-- Start button //-->
                        <input type="hidden" name="submitted" value="1" />
                        <button id="btn_confirm_operate_others_workbasket" class="govuk-button" data-module="govuk-button">Continue</button>
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