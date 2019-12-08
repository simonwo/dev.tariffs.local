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
                <th class="govuk-table__header" style="width:65%">Description</th>
                <th class="govuk-table__header r" style="width:20%">Extract</th>
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
                    <td class="govuk-table__cell"><a class="nodecorate" href="subheading.html?section_id=<?=$section_id?>&chapter_id=<?=$goods_nomenclature_item_id?>"><?=format_goods_nomenclature_item_id($goods_nomenclature_item_id)?></a></td>
                    <td class="govuk-table__cell"><?=$description?></td>
                    <td class="govuk-table__cell r">
                        <a target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?=substr($goods_nomenclature_item_id, 0, 2)?>&depth=4">HS4</a>&nbsp;&nbsp;&nbsp;
                        <a target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?=substr($goods_nomenclature_item_id, 0, 2)?>&depth=6">HS6</a>&nbsp;&nbsp;&nbsp;
                        <a target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?=substr($goods_nomenclature_item_id, 0, 2)?>&depth=8">CN8</a>&nbsp;&nbsp;
                        <a target="_blank" href="goods_nomenclature_extract.html?chapter_id=<?=substr($goods_nomenclature_item_id, 0, 2)?>">CN10</a>
                    </td>
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