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
        $footnote_type = new footnote_type();
        $footnote_type->footnote_type_id = get_querystring("footnote_type_id");
        $footnote_type->get_description();
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/footnote_types">Footnote types</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $footnote_type->view_url() ?>#tab_footnote_descriptions">Footnote type <?=$footnote_type->footnote_type_id?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete footnote type</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete footnote type <?=$footnote_type->footnote_type_id?></h1>
                    <!-- End main title //-->


                    <form action="/footnotes/actions.php" method="get">

                        <?php
                        new warning_control(
                            $text = "You have opted to delete footnote type " . $footnote_type->footnote_type_id . " - " . $footnote_type->description . ". By selecting 'Yes' below, you will delete the this footnote type. This action cannot be undone.</span>",
                        );

                        new radio_control(
                            $label = "Are you sure you want to delete this footnote type?",
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
                        new hidden_control("footnote_type_id", $footnote_type->footnote_type_id);
                        new hidden_control("action", "delete_footnote");
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