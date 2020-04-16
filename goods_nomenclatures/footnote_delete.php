<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->mode = "insert";
$error_handler = new error_handler();
$gn = new goods_nomenclature;
$footnote = new footnote;

$gn->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
$gn->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
$gn->productline_suffix = get_querystring("productline_suffix");
if ($gn->productline_suffix == "") {
    $gn->productline_suffix = "80";
}

$footnote->footnote_type_id = get_querystring("footnote_type_id");
$footnote->footnote_id = get_querystring("footnote_id");

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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete footnote assignment</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete assignment of footnote <?= $footnote->footnote_type_id ?><?= $footnote->footnote_id ?>
                        to commodity code <?= $gn->goods_nomenclature_item_id ?> (<?= $gn->productline_suffix ?>)</h1>
                    <!-- End main title //-->
                    <?php

                    new radio_control(
                        $label = "Are you sure you want to delete this footnote association?",
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
                    new hidden_control("action", "delete_footnote_association");
                    $btn = new button_control("Delete assignment", "delete_assignment", "primary");
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