<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler;
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
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/goods_nomenclatures/view.html?<?=$gn->query_string() ?>">Commodity <?= $gn->goods_nomenclature_item_id ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete commodity code</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete commodity code</h1>
                    <!-- End main title //-->


                    <form action="" method="get">

                        <?php
                        new warning_control(
                            $text = "You have opted to delete commodity code " . $gn->goods_nomenclature_item_id .
                            " with product line suffix " . $gn->productline_suffix . ". This commodity code has no measures, no footnotes and no child codes
                            associaed with it, so can be safely deleted. Please ensure that you do not leave a single end-line commodity code in place."
                        );


                        new radio_control(
                            $label = "Are you sure you want to delete this commodity code?",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "",
                            $control_name = "confirm_delete",
                            $dataset = $application->get_yes_no(),
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true,
                            $disabled_on_edit = false
                        );


                        $gn->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
                        $gn->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
                        $gn->productline_suffix = get_querystring("productline_suffix");
                        ?>
                        <?php
                        new hidden_control("goods_nomenclature_item_id", $gn->goods_nomenclature_item_id);
                        new hidden_control("goods_nomenclature_sid", $gn->goods_nomenclature_sid);
                        new hidden_control("productline_suffix", $gn->productline_suffix);
                        new hidden_control("action", "delete_goods_nomenclature_item_id");
                        $btn = new button_control("Continue", "continue", "primary");



                        new break_control(
                            $text = "☝️ Above is what should be displayed if the commodity code is safe to delete.<br /><br />
                            👇Below is what should be displayed if the commodity code may
                            not be deleted, due to the fact that it has either footnotes, measures or child codes associated with it. BTW these do not necessarily have to be
                            current dependent objects - any time will prevent the code from being deleted."
                        );




                        new warning_control(
                            $text = "Commodity code " . $gn->goods_nomenclature_item_id .
                            " with product line suffix " . $gn->productline_suffix . " cannot be deleted because measures have been assigned to this code. Instead of deleting the code, please return to the 'view commodity' screen and terminate the code."
                        );

                        new paragraph_control(
                            $text = "<i>or</i>"
                        );

                        new warning_control(
                            $text = "Commodity code " . $gn->goods_nomenclature_item_id .
                            " with product line suffix " . $gn->productline_suffix . " cannot be deleted because the commodity has child codes. Please delete any child codes before attempting to delete this code."
                        );

                        new paragraph_control(
                            $text = "<i>or</i>"
                        );

                        new warning_control(
                            $text = "Commodity code " . $gn->goods_nomenclature_item_id .
                            " with product line suffix " . $gn->productline_suffix . " cannot be deleted because footnotes have been assigned to this code. Instead of deleting the code, please return to the 'view commodity' screen and terminate the code."
                        );


                        $btn = new button_control("Cancel", "cancel", "text");


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