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
        $certificate_type = new certificate_type();
        $certificate_type->certificate_type_code = get_querystring("certificate_type_code");
        $certificate_type->get_description();
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/certificate_types">Certificate types</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $certificate_type->view_url() ?>#tab_certificate_descriptions">Certificate type <?=$certificate_type->certificate_type_code?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete certificate type</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete certificate type <?=$certificate_type->certificate_type_code?></h1>
                    <!-- End main title //-->


                    <form action="/certificates/actions.php" method="get">

                        <?php
                        new warning_control(
                            $text = "You have opted to delete certificate type " . $certificate_type->certificate_type_code . " - " . $certificate_type->description . ". By selecting 'Yes' below, you will delete the this certificate type. This action cannot be undone.</span>",
                        );

                        new radio_control(
                            $label = "Are you sure you want to delete this certificate type?",
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
                        new hidden_control("certificate_type_code", $certificate_type->certificate_type_code);
                        new hidden_control("action", "delete_certificate");
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