<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
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
        $additional_code = new additional_code();
        $additional_code->additional_code_type_id = get_querystring("additional_code_type_id");
        $additional_code->additional_code = get_querystring("additional_code");
        $additional_code->additional_code_description_period_sid = get_querystring("period_sid");
        $additional_code->get_specific_description($additional_code->additional_code_description_period_sid);
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/additional_codes">Additional codes</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $additional_code->view_url() ?>#tab_additional_code_descriptions">Additional code <?=$additional_code->additional_code_type_id?><?=$additional_code->additional_code?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete additional code description</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete additional code description</h1>
                    <!-- End main title //-->


                    <form action="/additional_codes/actions.php" method="get">

                        <?php
                        new warning_control(
                            $text = "<span class='highlighted_text'>You have opted to delete the existing additional_code description for " . short_date($additional_code->validity_start_date) . ":<br /><br /><strong>" . $additional_code->description . "</strong><br /><br />By selecting 'Yes' below, you will delete the this description. This action cannot be undone.</span>",
                        );

                        new radio_control(
                            $label = "Are you sure you want to delete this description?",
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
                        new hidden_control("additional_code_type_id", $additional_code->additional_code_type_id);
                        new hidden_control("additional_code", $additional_code->additional_code);
                        new hidden_control("period_sid", $additional_code->additional_code_description_period_sid);
                        new hidden_control("action", "delete_additional_code_description");
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