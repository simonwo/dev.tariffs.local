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
$goods_nomenclature->get_children($goods_nomenclature->goods_nomenclature_item_id, 3);

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
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Commodity codes</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">

                    <?php
                    new title_control("", "", "", "Find and edit commodity codes");
                    ?>

                    <?php
                    require("commodity_search.php");
                    require("commodity_nav.php");
                    ?>
                    <table cellspacing="0" class="govuk-table xgovuk-table--m sticky" id="table">
                        <tr class="govuk-table__row">
                            <th class="govuk-table__header" scope="col" style="width:10%">Commodity code</th>
                            <th class="govuk-table__header" scope="col" style="width:70%">Description</th>
                            <th class="govuk-table__header c tip" scope="col" style="width:5%" aria-describedby="tip_suffix">
                                Suffix
                                <span id="tip_suffix" class="tooltip govuk-visually-hidden" role="tooltip" aria-hidden="true"><span class="notch"></span>
                                    The product line suffix field identifies if the commodity code is potentially declarable or just an intermediate 'header' line.<br /><br />
                                    All commodities in the table below where the product line suffix is noted with a hyphen are potentially declarable. The hyphen has been used to represent '80', which identifies potentially declarable lines in Taric-related databases.<br /><br />
                                    Any other value indicates that the commodity is a header line.
                                </span>
                            </th>
                            <th class="govuk-table__header c tip" scope="col" style="width:10%" aria-describedby="tip_indent">
                                Indent
                                <span id="tip_indent" class="tooltip govuk-visually-hidden" role="tooltip" aria-hidden="true"><span class="notch"></span>
                                    The indent field is used to show parental relationships between one commodity code and its neighbours.<br /><br />For instance a commodity code with an indent of 2 will be the 'child' of a commodity code with an indent of 1 that appears immediately above it.
                                </span>
                            </th>
                            <th class="govuk-table__header" scope="col" style="width:5%">Actions</th>
                        </tr>



                        <?php
                        foreach ($goods_nomenclature->headings as $heading) {
                            if ($goods_nomenclature->goods_nomenclature_sid != $heading->goods_nomenclature_sid) {
                                $indent_class = "indent_" . $heading->number_indents;
                                if ($heading->number_indents > 1) {
                                    $leaf_class = " leaf";
                                } else {
                                    $leaf_class = " leaf";
                                }
                                $url = "view.html?" . $heading->query_string();
                        ?>
                                <tr class="govuk-table__row">
                                    <td class="govuk-table__cell"><a class="nodecorate" href="<?=$url?>"><?= format_goods_nomenclature_item_id($heading->goods_nomenclature_item_id) ?></a></td>
                                    <td class="govuk-table__cell <?= $indent_class ?> <?= $leaf_class ?>">
                                        <?= $heading->description_display() ?>
                                    </td>
                                    <td class="govuk-table__cell c"><?= $heading->productline_suffix_display() ?></td>
                                    <td class="govuk-table__cell c"><?= $heading->number_indents ?></td>
                                    <td class="govuk-table__cell" nowrap>
                                        <a class="govuk-link icon" href="<?=$url?>"><img src='/assets/images/view.png' alt='View this commodity' /><span>View commodity</span></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>

                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>