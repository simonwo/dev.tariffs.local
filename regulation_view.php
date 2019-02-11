<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $regulation_id = get_querystring("regulation_id");
?>
<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/regulations.php">Regulations</a>
        </li>
    </ol>
    </div>
    <main id="content" lang="en">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">View regulation <?=$regulation_id?></h1></div>
                </div>
            </div>

            <h2 class="nomargin">Regulation details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th style="width:15%">Regulation ID</th>
                    <th style="width:50%">Information text</th>
                    <th style="width:35%">Regulation group</th>
                </tr>
<?php
	$sql = "SELECT b.base_regulation_id, b.information_text, b.regulation_group_id, rgd.description as regulation_group_description
    FROM base_regulations b, regulation_group_descriptions rgd
    WHERE b.regulation_group_id = rgd.regulation_group_id
    AND base_regulation_id LIKE '" . $regulation_id . "%' ORDER BY 1";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $regulation_id                  = $row['base_regulation_id'];
            $information_text               = $row['information_text'];
            $regulation_group_id            = $row['regulation_group_id'];
            $regulation_group_description   = $row['regulation_group_description'];
?>
                <tr class="govuk-table__row">
                    <td><?=$regulation_id?></td>
                    <td><?=$information_text?></td>
                    <td><?=$regulation_group_id?> - <?=$regulation_group_description?></td>
                </tr>
<?php
        }
    }
?>

            </table>
            
            <h2>Measure details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:10%">SID</th>
                    <th class="govuk-table__header" style="width:10%">Commodity code</th>
                    <th class="govuk-table__header" style="width:13%">Start date</th>
                    <th class="govuk-table__header" style="width:13%">End date</th>
                    <th class="govuk-table__header" style="width:24%">Geographical area</th>
                    <th class="govuk-table__header" style="width:20%">Type</th>
                    <th class="govuk-table__header" style="width:10%">Regulation&nbsp;ID</th>
                </tr>
<?php
    $regulation_id = $_GET["regulation_id"];
	$sql = "SELECT m.measure_sid, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date, m.geographical_area_id,
    m.measure_type_id, m.regulation_id_full, g.description as geographical_area_description, mtd.description as measure_type_description
    FROM ml.v5_2019 m, ml.ml_geographical_areas g, measure_type_descriptions mtd
    WHERE m.geographical_area_id = g.geographical_area_id
    AND mtd.measure_type_id = m.measure_type_id
    AND regulation_id_full LIKE '" . $regulation_id . "%'";
    #echo ($sql);
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $measure_sid                = $row['measure_sid'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $validity_start_date        = trim($row['validity_start_date'] . "");
            $validity_end_date          = trim($row['validity_end_date'] . "");

            $validity_start_date        = DateTime::createFromFormat('Y-m-d H:i:s', $validity_start_date)->format('Y-m-d');
            if ($validity_end_date != "") {
                $validity_end_date      = DateTime::createFromFormat('Y-m-d H:i:s', $validity_end_date)->format('Y-m-d');
            } else {
                $validity_end_date = "";
            }

            $measure_type_id                = $row['measure_type_id'];
            $geographical_area_id           = $row['geographical_area_id'];
            $regulation_id_full             = $row['regulation_id_full'];
            $geographical_area_description  = $row['geographical_area_description'];
            $measure_type_description       = $row['measure_type_description'];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><?=$measure_sid?></td>
                    <td class="govuk-table__cell"><a href="goods_nomenclature_item_view.php?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>"><?=$goods_nomenclature_item_id?></a></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell"><?=$validity_end_date?></td>
                    <td class="govuk-table__cell"><a href="geographical_area?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?> (<?=$geographical_area_description?>)</a></td>
                    <td class="govuk-table__cell"><?=$measure_type_id?> - <?=$measure_type_description?></td>
                    <td class="govuk-table__cell"><?=$regulation_id_full?></td>
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