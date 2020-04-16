<?php

new paragraph_control("<a class='govuk-link' id='populate_commodity_migration_form' href='#'>Populate form with default values</a>");

?>

<table class="govuk-table govuk-table--s sticky" id="migrate_table" cellspacing="0">
    <tr class="govuk-table__row">
        <th style="width:10%" scope="col" class="govuk-table__header nopad">Commodity</th>
        <th style="width:6%" scope="col" class="govuk-table__header c">Suffix</th>
        <th style="width:6%" scope="col" class="govuk-table__header c">Indents</th>
        <th style="width:56%" scope="col" class="govuk-table__header">Description</th>
        <th style="width:10%" scope="col" class="govuk-table__header nw">New commodity</th>
        <th style="width:6%" scope="col" class="govuk-table__header c nw">New suffix</th>
        <th style="width:6%" scope="col" class="govuk-table__header c nw">New indent</th>
    </tr>
    <?php
    global $gn;
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
            <td class="govuk-table__cell nopad xgrey2 commodity"><a class="nodecorate" href="<?= $url ?>"><?= format_goods_nomenclature_item_id($gn->ar_hierarchies[$i]->goods_nomenclature_item_id) ?></a></td>
            <td class="govuk-table__cell c xgrey2 productline_suffix"><?= $gn->ar_hierarchies[$i]->productline_suffix ?></td>
            <td class="govuk-table__cell c xgrey2 indent"><?= $gn->ar_hierarchies[$i]->number_indents + 1 ?></td>
            <td class="govuk-table__cell xgrey2 <?= $class ?>"><?= str_replace("|", " ", $gn->ar_hierarchies[$i]->description) ?></td>
            <td class="govuk-table__cell p5 pl0">
                <input type="text" size="10" maxlength="10" id="commodity_new_<?= $t->goods_nomenclature_sid?>" name="commodity_new_<?= $t->goods_nomenclature_sid?>" class="govuk-input mono govuk-input--s govuk-input--width-10 commodity_new" />
            </td>
            <td class="govuk-table__cell p5 c">
                <input type="text" size="2" maxlength="2" id="productline_suffix_new_<?= $t->goods_nomenclature_sid?>" name="productline_suffix_new_<?= $t->goods_nomenclature_sid?>" class="govuk-input govuk-input--s govuk-input--width-2 productline_suffix_new" />
            </td>
            <td class="govuk-table__cell p5 c">
                <input type="text" size="2" maxlength="2" id="indent_new_<?= $t->goods_nomenclature_sid?>" name="indent_new_<?= $t->goods_nomenclature_sid?>" class="govuk-input govuk-input--s govuk-input--width-2 indent_new" />
            </td>
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