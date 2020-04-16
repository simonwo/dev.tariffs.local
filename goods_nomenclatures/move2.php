<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler();
$gn = new goods_nomenclature;
$gn->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
$gn->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
$gn->productline_suffix = get_querystring("productline_suffix");
$gn->get_hierarchy();
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
                    <a class="govuk-breadcrumbs__link" href="/goods_nomenclatures/view.html?<?= $gn->query_string() ?>">Commodity <?= $gn->goods_nomenclature_item_id ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Move commodity (Stage 2 of x)</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Move commodity code</h1>
                    <!-- End main title //-->


                    <?php
                    new inset_control("You are moving commodity code <span class='mono'>" . $gn->goods_nomenclature_item_id . "</span> with 
                    product line suffix <span class='mono'>" . $gn->productline_suffix . "</span> and all its dependent objects.[WORKBASKET]");
                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <?php
                new input_control(
                        $label = "Please enter a name for this activity",
                        $label_style = "govuk-label--m",
                        $hint_text = "On completion of this activity, the changes made will be listed in your workbasket under this activity name.",
                        $control_name = "activity_name",
                        $control_style = "govuk-input--width-30",
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
                    ?>

                    <form action="./move2.html" method="get">
                        <?php
                        new hidden_control("goods_nomenclature_item_id", $gn->goods_nomenclature_item_id);
                        new hidden_control("goods_nomenclature_sid", $gn->goods_nomenclature_sid);
                        new hidden_control("productline_suffix", $gn->productline_suffix);
                        $btn = new button_control("Continue", "continue", "primary");
                        $btn2 = new button_control("Cancel", "cancel", "text");

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