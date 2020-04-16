<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Move commodity (Stage 1 of x)</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Move commodity code</h1>
                    <!-- End main title //-->


                    <!--<div class="govuk-inset-text">//-->
                    <p class="govuk-body">
                        Use this screen to move commodity code <span class="mono b"><?= $gn->goods_nomenclature_item_id ?></span>
                        with product line suffix <span class="mono b"><?= $gn->productline_suffix ?></span> to a new position in the hierarchy.
                        This function will perform the following actions if you decide to proceed:
                    </p>
                    <ul class="govuk-list govuk-list--bullet">
                        <li>Terminate all <b>measures</b> associated with the selected commodity code and all its descendants</li>
                        <li>Terminate all <b>footnotes</b> associated with the selected commodity code and all its descendants</li>
                        <li>Terminate this <b>commodity code</b> and all its descendants</li>
                        <li>Recreate this <b>commodity code</b> and all its descendants in their new location</li>
                        <li>Recreate each of the <b>measures</b> for this commodity code and all its descendants in their new location</li>
                        <li>Recreate each of the <b>footnotes</b> for this commodity code and all its descendants in their new location</li>

                    </ul>

                    <?php
                    new warning_control("This function will not move any measures which are inherited down
                    to this commodity code: this activity will need to be undertaken manually.");
                    ?>
                    <!--</div>//-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!--
                    <p class="govuk-body">You are moving commodity code <?= format_goods_nomenclature_item_id("0102030405") ?> from its position in the goods classification as described below:</p>
                    //-->
                    <h2 class="govuk-heading-m">Position of commodity code in current hierarchy</h2>
                    <p class="govuk-body">
                        The table below illustrates the commodity code hierarchy in which your selected code resides.
                        The final column identifies the codes which will be required to move as a result of this activity.
                    </p>

                    <table class="govuk-table govuk-table--m sticky" cellspacing="0">
                        <tr class="govuk-table__row">
                            <th style="width:10%" scope="col" class="govuk-table__header nopad">Commodity</th>
                            <th style="width:6%" scope="col" class="govuk-table__header c">Suffix</th>
                            <th style="width:6%" scope="col" class="govuk-table__header c">Indents</th>
                            <th style="width:63%" scope="col" class="govuk-table__header">Description</th>
                            <th style="width:5%" scope="col" class="govuk-table__header c nw">End line</th>
                            <th style="width:10%" scope="col" class="govuk-table__header c nw">Subject to move</th>
                        </tr>
                        <?php
                        $subject_to_move = false;
                        $move_count = 0;
                        $array = $gn->ar_hierarchies;

                        $hier_count = sizeof($array);

                        $parents = array();
                        $commodities_to_migrate = array();
                        $my_concat = $gn->goods_nomenclature_item_id . $gn->productline_suffix;
                        for ($i = 0; $i < $hier_count; $i++) {
                            $t = $array[$i];
                            $concat = $t->goods_nomenclature_item_id . $t->productline_suffix;
                            $url = "view.html?goods_nomenclature_sid=" . $t->goods_nomenclature_sid . "&goods_nomenclature_item_id=" . $t->goods_nomenclature_item_id . "&productline_suffix=" . $t->productline_suffix . "#hierarchy";
                            $class = "indent" . $t->number_indents;
                            if ($gn->ar_hierarchies[$i]->productline_suffix != "80") {
                                $suffix_class = "filler";
                            } else {
                                $suffix_class = "";
                            }
                            if (($t->goods_nomenclature_item_id == $gn->goods_nomenclature_item_id) && ($t->productline_suffix == $gn->productline_suffix)) {
                                $suffix_class .= " selected";
                                $subject_to_move = true;
                            }
                            if ($concat < $my_concat) {
                                if ($t->productline_suffix == "80") {
                                    array_push($parents, $t->goods_nomenclature_item_id);
                                }
                            }
                            if ($subject_to_move == true) {
                                array_push($commodities_to_migrate, $t->goods_nomenclature_sid);
                                $move_count += 1;
                                $subject_to_move_string = "Yes";
                            } else {
                                $subject_to_move_string = "-";
                            }
                        ?>
                            <tr class="govuk-table__row <?= $suffix_class ?>">
                                <td class="govuk-table__cell nopad"><a class="nodecorate" href="<?= $url ?>"><?= format_goods_nomenclature_item_id($gn->ar_hierarchies[$i]->goods_nomenclature_item_id) ?></a></td>
                                <td class="govuk-table__cell c"><?= $gn->ar_hierarchies[$i]->productline_suffix ?></td>
                                <td class="govuk-table__cell c"><?= $gn->ar_hierarchies[$i]->number_indents + 1 ?></td>
                                <td class="govuk-table__cell <?= $class ?>"><?= str_replace("|", " ", $gn->ar_hierarchies[$i]->description) ?></td>
                                <td class="govuk-table__cell c"><?= $gn->ar_hierarchies[$i]->leaf_string() ?></td>
                                <td class="govuk-table__cell c"><?= $subject_to_move_string ?></td>
                            </tr>

                        <?php
                        }
                        $parent_count = count($parents);
                        $parent_string = "";
                        for ($i = 0; $i < $parent_count; $i++) {
                            if ($parents[$i] != $gn->goods_nomenclature_item_id) {
                                $parent_string .= "'" . $parents[$i] . "',";
                            }
                        }
                        $parent_string = trim($parent_string);
                        $parent_string = trim($parent_string, ",");
                        $commodities_to_migrate_string = serialize($commodities_to_migrate);
                        
                        //$commodities_to_migrate = unserialize($commodities_to_migrate_string);
                        //pre ($commodities_to_migrate);
                        ?>
                    </table>

                    <p class="govuk-body">
                        If you decide to proceed, <?= $move_count ?> commodity codes and their dependent objects will be moved.
                    </p>
                    <p class="govuk-body">
                        Click on the 'Continue' button to proceed to the next screen, where you will be asked to select the new commodity codes
                        to which to move the existing commodities.
                    </p>
                    <form action="./commodity_migrate_actions.html" method="post">
                        <?php
                        new hidden_control("commodities_to_migrate", $commodities_to_migrate_string, false);
                        new hidden_control("goods_nomenclature_item_id", $gn->goods_nomenclature_item_id);
                        new hidden_control("goods_nomenclature_sid", $gn->goods_nomenclature_sid);
                        new hidden_control("productline_suffix", $gn->productline_suffix);
                        new hidden_control("action", "start");
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