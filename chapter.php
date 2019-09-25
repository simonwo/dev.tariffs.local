<?php
    $title = "View chapter";
    require ("includes/db.php");
    require ("includes/header.php");
    $section_id = get_querystring("section_id");
?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/sections.html">Nomenclature sections</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Nomenclature chapters
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Nomenclature chapters</h1>
    </div>


    <table cellspacing="0" class="govuk-table">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:15%">Section</th>
                <th class="govuk-table__header" style="width:85%">Description</th>
            </tr>

<?php
    $sql = "SELECT gn.goods_nomenclature_item_id, gn.goods_nomenclature_sid, description FROM chapters_sections cs, goods_nomenclatures gn, goods_nomenclature_descriptions gnd
    WHERE cs.goods_nomenclature_sid = gn.goods_nomenclature_sid
    AND gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
    AND section_id = " . $section_id . " ORDER BY gn.goods_nomenclature_item_id";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $description                = title_case($row['description']);
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a class="nodecorate" href="subheading.html?section_id=<?=$section_id?>&chapter_id=<?=$goods_nomenclature_item_id?>"><?=format_commodity_code($goods_nomenclature_item_id)?></a></td>
                    <td class="govuk-table__cell"><?=$description?></td>
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