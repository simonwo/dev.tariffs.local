<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->mode = "insert";
$error_handler = new error_handler();

$gn = new goods_nomenclature;
$gn->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
$gn->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
$gn->productline_suffix = get_querystring("productline_suffix");
if ($gn->productline_suffix == "") {
    $gn->productline_suffix = "80";
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
                    <a class="govuk-breadcrumbs__link" href="/goods_nomenclatures">Commodity codes</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">
                    <a class="govuk-breadcrumbs__link" href="/goods_nomenclatures/view.html?<?= $gn->query_string() ?>#footnotes">Commodity <?= $gn->goods_nomenclature_item_id ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Assign footnote</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Assign footnote to commodity code <?= $gn->goods_nomenclature_item_id ?> (<?= $gn->productline_suffix ?>)</h1>
                    <!-- End main title //-->
                    <?php

                    new input_control(
                        $label = "What footnote do you want to associate?",
                        $label_style = "govuk-label--m",
                        $hint_text = "Start typing the ID of the footnote or terms contained in the footnote's description.",
                        $control_name = "footnote_id",
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

                    new date_picker_control(
                        $label = "Please select the date on which to associate the footnote",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "date",
                        $control_scope = "insert",
                        $default = "",
                        $required = true,
                        $custom_errors = ""
                    );

                    new button_control("Assign footnote", "footnote_assign", "primary");
                    new button_control("Cancel", "cancel", "text");
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