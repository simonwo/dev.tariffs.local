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
        $footnote = new footnote();
        $footnote->footnote_type_id = get_querystring("footnote_type_id");
        $footnote->footnote_id = get_querystring("footnote_id");
        $footnote->footnote_description_period_sid = get_querystring("period_sid");
        $footnote->get_specific_description($footnote->footnote_description_period_sid);
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/footnotes">Footnotes</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/footnotes/view.html?mode=view&footnote_id=<?=$footnote->footnote_id?>&footnote_type_id=<?=$footnote->footnote_type_id?>#tab_footnote_descriptions">Footnote <?=$footnote->footnote_type_id?><?=$footnote->footnote_id?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete footnote description</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete footnote description</h1>
                    <!-- End main title //-->


                    <form action="/footnotes/actions.php" method="get">

                        <?php
                        new warning_control(
                            $text = "<span class='highlighted_text'>You have opted to delete the existing footnote description for " . short_date($footnote->validity_start_date) . ":<br /><br /><strong>" . $footnote->description . "</strong><br /><br />By selecting 'Yes' below, you will delete the this description. This action cannot be undone.</span>",
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
                        new hidden_control("footnote_type_id", $footnote->footnote_type_id);
                        new hidden_control("footnote_id", $footnote->footnote_id);
                        new hidden_control("period_sid", $footnote->footnote_description_period_sid);
                        new hidden_control("action", "delete_footnote_description");
                        $btn = new button_control("Continue", "continue", "primary");
                        //$btn = new button_control("Cancel", "cancel", "text", "", "/");
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