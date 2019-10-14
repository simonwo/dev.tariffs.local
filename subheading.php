<?php
    $title = "Subheading";
    require ("includes/db.php");
    require ("includes/header.php");
    $section_id = get_querystring("section_id");
    $chapter_id = substr(get_querystring("chapter_id"), 0, 2);
    #echo ($chapter_id);
    #exit();
?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/sections.html">Nomenclature sections</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/chapter.html?section_id=<?=$section_id?>">Nomenclature chapters</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Nomenclature subheadings
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Nomenclature subheading</h1>
    </div>
    <table cellspacing="0" class="govuk-table tight">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:8%">Commodity</th>
            <th class="govuk-table__header c" style="width:7%">Suffix</th>
            <th class="govuk-table__header c" style="width:7%">Indents</th>
            <th class="govuk-table__header" style="width:78%">Description</th>
        </tr>

<?php
    $sql = "SELECT goods_nomenclature_item_id, producline_suffix, validity_start_date, validity_end_date, description, number_indents
    FROM ml.goods_nomenclature_export_new('" . $chapter_id . "%', '" . $critical_date . "') ORDER BY 1, 2";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $productline_suffix          = $row['producline_suffix'];
            $validity_start_date        = $row['validity_start_date'];
            $validity_end_date          = $row['validity_end_date'];
            $number_indents             = $row['number_indents'];
            $description                = $row['description'];
            $class = "indent" . $number_indents;
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">
                        <a class="nodecorate" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>&productline_suffix=<?=$productline_suffix?>"><?=format_commodity_code($goods_nomenclature_item_id)?></a>
                    </td>
                    <td class="govuk-table__cell c"><?=$productline_suffix?></td>
                    <td class="govuk-table__cell c"><?=$number_indents?></td>
                    <td class="govuk-table__cell <?=$class?>"><?=$description?></td>
                </tr>
<?php
        }
    }
?>
    </table>
</div>
<?php
    require ("includes/footer.php")
?>