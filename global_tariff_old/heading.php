<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
$goods_nomenclature = new goods_nomenclature();
$goods_nomenclature->section_id = get_querystring("section_id");
$goods_nomenclature->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
$goods_nomenclature->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
$goods_nomenclature->producline_suffix = get_querystring("producline_suffix");
$goods_nomenclature->populate();
$goods_nomenclature->get_children($goods_nomenclature->goods_nomenclature_item_id, 1);
$heading_text = new heading_text();

$section = new section(get_querystring("section_id"));
$section->get_section();

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
        <!--
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Commodity codes</li>
            </ol>
        </div>
        //-->
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <?php
                    new title_control("", "", "", "The UK Global Tariff");
                    if ($heading_text->text != "") {
                        new inset_control($heading_text->text);
                    }
                    ?>

                    <?php
                    //require("commodity_search.php");
                    require("commodity_nav.php");
                    ?>
                    <table cellspacing="0" class="govuk-table sticky">
                        <tr class="govuk-table__row">
                            <th scope="col" class="govuk-table__header" scope="col" style="width:10%">Code</th>
                            <th scope="col" class="govuk-table__header" scope="col" style="width:75%">Description</th>
                            <!--
                            <th class="govuk-table__header c tip" scope="col" style="width:5%" aria-describedby="tip_suffix">
                                Suffix
                                <span id="tip_suffix" class="tooltip govuk-visually-hidden" role="tooltip" aria-hidden="true"><span class="notch"></span>
                                    The product line suffix field identifies if the commodity code is potentially declarable or just an intermediate 'header' line.<br /><br />
                                    All commodities in the table below where the product line suffix is noted with a hyphen are potentially declarable. The hyphen has been used to represent '80', which identifies potentially declarable lines in Taric-related databases.<br /><br />
                                    Any other value indicates that the commodity is a header line.
                                </span>
                            </th>
                            <th class="govuk-table__header c tip" scope="col" style="width:5%" aria-describedby="tip_indent">
                                Indent
                                <span id="tip_indent" class="tooltip govuk-visually-hidden" role="tooltip" aria-hidden="true"><span class="notch"></span>
                                    The indent field is used to show parental relationships between one commodity code and its neighbours.<br /><br />For instance a commodity code with an indent of 2 will be the 'child' of a commodity code with an indent of 1 that appears immediately above it.
                                </span>
                            </th>
                            <th class="govuk-table__header c" scope="col" style="width:5%">Manage</th>
                            //-->
                        </tr>

                        <?php
                        foreach ($goods_nomenclature->headings as $heading) {
                            if ($goods_nomenclature->goods_nomenclature_sid != $heading->goods_nomenclature_sid) {
                        ?>
                                <tr class="govuk-table__row">
                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id($heading->goods_nomenclature_item_id) ?></td>
                                    <?php
                                    if ($heading->productline_suffix != "80") {
                                    ?>
                                        <td class="govuk-table__cell"><?= $heading->description ?></td>
                                    <?php

                                    } else {
                                    ?>
                                        <!--<td class="govuk-table__cell"><a class="govuk-link" href="commodity.html?section_id=<?= $goods_nomenclature->section_id ?>&goods_nomenclature_item_id=<?= $heading->goods_nomenclature_item_id ?>&producline_suffix=<?= $heading->producline_suffix ?>&goods_nomenclature_sid=<?= $heading->goods_nomenclature_sid ?>&"><?= $heading->description ?></a></td>//-->
                                        <td class="govuk-table__cell">
                                            <a class="govuk-link" href="./commodity.html?section_id=<?= $section->section_id ?>&goods_nomenclature_item_id=<?= $heading->goods_nomenclature_item_id ?>&wts=1&day_start=1&month_start=1&year_start=2020&day_end=31&month_end=12&year_end=2020&range=<?= substr($heading->goods_nomenclature_item_id, 0, 4) ?>&scope=1011&fmt=screen#table_intro"><?= $heading->description ?></a>
                                        </td>
                                    <?php

                                    }
                                    ?>
                                    <!--
                                    <td class="govuk-table__cell c"><?= $heading->productline_suffix_display() ?></td>
                                    <td class="govuk-table__cell c"><?= $heading->number_indents ?></td>
                                    <td class="govuk-table__cell c" nowrap><a class="govuk-link" href=""><img src='/assets/images/view.png' alt='View this commodity' /></a></td>
                                    //-->
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                    <a href="javascript:history.back();" class="govuk-back-link">Back</a>

                </div>
            </div>
        </main>
    </div>
    <?php
    //require("../includes/footer.php");
    ?>
</body>

</html>