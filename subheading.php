<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $section_id = get_querystring("section_id");
    $chapter_id = substr(get_querystring("chapter_id"), 0, 2);
    #echo ($chapter_id);
    #exit();
?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/sections.php">Nomenclature sections</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/chapter.php?section_id=<?=$section_id?>">Nomenclature chapters</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Nomenclature subheadings
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Nomenclature subheading</h1>
    </div>
    <table cellspacing="0" class="noborder tbl tight">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:20%">Commodity / suffix</th>
            <th class="govuk-table__header" style="width:80%">Description</th>
        </tr>

<?php
    $sql = "SELECT goods_nomenclature_item_id, producline_suffix, validity_start_date, validity_end_date, description, number_indents
    FROM ml.goods_nomenclature_export_2019('" . $chapter_id . "%') ORDER BY 1, 2";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $productline_suffix          = $row['producline_suffix'];
            $validity_start_date        = $row['validity_start_date'];
            $validity_end_date          = $row['validity_end_date'];
            $number_indents             = $row['number_indents'];
            $description                = title_case($row['description']);
            $class = "indent" . $number_indents;
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="goods_nomenclature_item_view.php?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>&productline_suffix=<?=$productline_suffix?>"><?=$goods_nomenclature_item_id?> (<?=$productline_suffix?>)</a></td>
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