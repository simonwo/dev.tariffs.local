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
        $measure_type = new measure_type();
        $measure_type->measure_type_id = get_querystring("measure_type_id");
        $measure_type->get_description();
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/measure_types">Measure types</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $measure_type->view_url() ?>#tab_measure_descriptions">Measure type <?=$measure_type->measure_type_id?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete measure type</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete measure type <?=$measure_type->measure_type_id?></h1>
                    <!-- End main title //-->


                    <form action="/measures/actions.php" method="get">

                        <?php
                        new warning_control(
                            $text = "You have opted to delete measure type " . $measure_type->measure_type_id . " - " . $measure_type->description . ". By selecting 'Yes' below, you will delete the this measure type. This action cannot be undone.</span>",
                        );

                        new radio_control(
                            $label = "Are you sure you want to delete this measure type?",
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
                        new hidden_control("measure_type_id", $measure_type->measure_type_id);
                        new hidden_control("action", "delete_measure");
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