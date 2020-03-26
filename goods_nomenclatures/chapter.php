<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
$section = new section(get_querystring("section_id"));
$section->get_section();
$section->get_chapters();
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
                    ?>
                    <nav class="nomenclature_nav">
                        <ul>
                            <li><a href="./">All sections</a></li>
                            <li>Section <?= $section->numeral ?> - <?= $section->title ?></li>
                        </ul>
                    </nav>
                    <table cellspacing="0" class="govuk-table xgovuk-table--m sticky" id="table">
                        <tr class="govuk-table__row">
                            <th class="govuk-table__header" style="width:15%" scope="col">Commodity code</th>
                            <th class="govuk-table__header" style="width:55%" scope="col">Description</th>
                            <th class="govuk-table__header" style="width:20%" scope="col">Extract</th>
                            <th class="govuk-table__header" style="width:10%" scope="col">Manage</th>
                        </tr>

                        <?php
                        foreach ($section->chapters as $chapter) {
                            $url = "view.html?" . $chapter->query_string();
                        ?>
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell"><a class="nodecorate" href='<?= $url ?>'><?= format_goods_nomenclature_item_id($chapter->goods_nomenclature_item_id) ?></a></td>
                                <td class="govuk-table__cell"><a class="govuk-link" href="heading.html?section_id=<?= $section->section_id ?>&<?= $chapter->query_string() ?>#table"><?= $chapter->description ?></a></td>
                                <td class="govuk-table__cell" nowrap>
                                    <a class="govuk-link" target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?= substr($chapter->goods_nomenclature_item_id, 0, 2) ?>&depth=4">HS4</a>&nbsp;&nbsp;&nbsp;
                                    <a class="govuk-link" target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?= substr($chapter->goods_nomenclature_item_id, 0, 2) ?>&depth=6">HS6</a>&nbsp;&nbsp;&nbsp;
                                    <a class="govuk-link" target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?= substr($chapter->goods_nomenclature_item_id, 0, 2) ?>&depth=8">CN8</a>&nbsp;&nbsp;
                                    <a class="govuk-link" target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?= substr($chapter->goods_nomenclature_item_id, 0, 2) ?>">CN10</a>
                                </td>
                                <td class="govuk-table__cell nw">
                                    <a class="icon govuk-link" href='<?= $url ?>'><img src="/assets/images/view.png" /><span>View commodity</span></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <p class="govuk-body">Extract commodity tree:&nbsp;&nbsp;
                        <a class="govuk-link" href="goods_nomenclature_extract.html?depth=4">HS4</a>&nbsp;
                        <a class="govuk-link" href="goods_nomenclature_extract.html?depth=6">HS6</a>&nbsp;
                        <a class="govuk-link" href="goods_nomenclature_extract.html?depth=8">CN8</a>&nbsp;
                        <a class="govuk-link" href="goods_nomenclature_extract.html?depth=10">CN10</a>
                    </p>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>