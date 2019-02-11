    <?php
    require ("includes/db.php");
    require ("includes/header.php");
    $geographical_area_id = get_querystring("geographical_area_id");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/geographical_areas.php">Geographical areas</a>
        </li>
    </ol>
</div>
<div class="app-content__header">
    <h1 class="govuk-heading-xl">View geographical area <?=$geographical_area_id?></h1>
</div>
            <!-- MENU //-->
            <h2>Page content</h2>
            <ul class="tariff_menu">
                <li><a href="#details">Area details</a></li>
                <li><a href="#measures">Measures</a></li>
                <li><a href="#members1">Members of this country group</a></li>
                <li><a href="#members2">Groups to which this country belongs</a></li>
            </ul>

            <h2 class="nomargin" id="details">Area details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:15%">Property</th>
                    <th class="govuk-table__header" style="width:50%">Value</th>
                </tr>
<?php
	$sql = "SELECT geographical_area_id, geographical_area_sid, parent_geographical_area_group_sid, 
    description, geographical_code, validity_start_date, validity_end_date
    FROM ml.ml_geographical_areas WHERE geographical_area_id = '" . $geographical_area_id ."'";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $geographical_area_id               = $row[0];
        $geographical_area_sid              = $row[1];
        $parent_geographical_area_group_sid = $row[2];
        $description                        = $row[3];
        $geographical_code                  = $row[4];
        $validity_start_date                = $row[5];
        $validity_end_date                  = $row[6];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Description</td>
                    <td class="govuk-table__cell b"><?=$description?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Geographical area ID</td>
                    <td class="govuk-table__cell"><?=$geographical_area_id?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Geographical area SID</td>
                    <td class="govuk-table__cell"><?=$geographical_area_sid?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Parent area SID</td>
                    <td class="govuk-table__cell"><?=$parent_geographical_area_group_sid?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Geographical area code</td>
                    <td class="govuk-table__cell"><?=$geographical_code?> (<span class="explanatory-inline"><?=geographical_code($geographical_code)?></span>)</td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Start date</td>
                    <td class="govuk-table__cell"><?=string_to_date($validity_start_date)?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">End date</td>
                    <td class="govuk-table__cell"><?=string_to_date($validity_end_date)?></td>
                </tr>
<?php
    }
?>

            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>
            
            <h2 id="measures">Measure details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:10%">SID</th>
                    <th class="govuk-table__header" style="width:10%">Commodity</th>
                    <th class="govuk-table__header" style="width:11%">Start date</th>
                    <th class="govuk-table__header" style="width:11%">End date</th>
                    <th class="govuk-table__header" style="width:20%">Geographical area</th>
                    <th class="govuk-table__header" style="width:22%">Type</th>
                    <th class="govuk-table__header" style="width:10%">Regulation&nbsp;ID</th>
                    <th class="govuk-table__header" style="width:8%">Order number</th>
                </tr>
<?php
	$sql = "SELECT m.measure_sid, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date, m.geographical_area_id,
    m.measure_type_id, m.regulation_id_full, g.description as geographical_area_description, mtd.description as measure_type_description,
    m.ordernumber FROM ml.v5_2019 m, ml.ml_geographical_areas g, measure_type_descriptions mtd
    WHERE m.geographical_area_id = g.geographical_area_id
    AND mtd.measure_type_id = m.measure_type_id
    AND m.geographical_area_id = '" . $geographical_area_id . "' ORDER BY validity_start_date DESC";
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

            $ordernumber                    = $row['ordernumber'];
            $measure_type_id                = $row['measure_type_id'];
            $geographical_area_id           = $row['geographical_area_id'];
            $regulation_id_full             = $row['regulation_id_full'];
            $geographical_area_description  = $row['geographical_area_description'];
            $measure_type_description       = $row['measure_type_description'];
            $commodity_url                  = "/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><?=$measure_sid?></td>
                    <td class="govuk-table__cell"><a href="<?=$commodity_url?>"><?=$goods_nomenclature_item_id?></a></td>
                    <td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
                    <td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
                    <td class="govuk-table__cell"><?=$geographical_area_id?> (<?=$geographical_area_description?>)</td>
                    <td class="govuk-table__cell"><?=$measure_type_id?> - <?=$measure_type_description?></td>
                    <td class="govuk-table__cell"><a href="regulation_view.php?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
                    <td class="govuk-table__cell"><a href="quota_order_number_view.php?quota_order_number_id=<?=$ordernumber?>"><?=$ordernumber?></a></td>
                </tr>

<?php
        }
    }
?>
            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
    if ($geographical_code == "1") {
?>            
            <h2 id="members1">Members of this country group</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:10%">Child ID</th>
                    <th class="govuk-table__header" style="width:10%">Child SID</th>
                    <th class="govuk-table__header" style="width:60%">Description</th>
                    <th class="govuk-table__header" style="width:10%">Validity start date</th>
                    <th class="govuk-table__header" style="width:10%">Validity end date</th>
                </tr>
                <?php
	$sql = "SELECT child_sid, child_id, child_description, validity_start_date, validity_end_date
    FROM ml.ml_geo_memberships WHERE parent_id = '" . $geographical_area_id . "' ORDER BY 3";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $child_id               = $row['child_id'];
            $child_sid              = $row['child_sid'];
            $child_description      = $row['child_description'];
            $validity_start_date    = string_to_date($row['validity_start_date']);
            $validity_end_date      = string_to_date($row['validity_end_date']);

?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="geographical_area_view.php?geographical_area_id=<?=$child_id?>"><?=$child_id?></a></td>
                    <td class="govuk-table__cell"><?=$child_sid?></td>
                    <td class="govuk-table__cell"><?=$child_description?></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell"><?=$validity_end_date?></td>
                </tr>

<?php
        }
    }
?>
            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>

<?php
}
?>
            <h2 id="members2">Groups to which this country belongs</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:10%">Parent ID</th>
                    <th class="govuk-table__header" style="width:60%">Description</th>
                    <th class="govuk-table__header" style="width:10%">Parent SID</th>
                    <th class="govuk-table__header" style="width:10%">Start date</th>
                    <th class="govuk-table__header" style="width:10%">End date</th>
                </tr>
                <?php
	$sql = "SELECT parent_sid, parent_id, parent_description, validity_start_date, validity_end_date
    FROM ml.ml_geo_memberships WHERE child_id = '" . $geographical_area_id . "' ORDER BY 3";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $parent_id               = $row['parent_id'];
            $parent_sid              = $row['parent_sid'];
            $parent_description      = $row['parent_description'];
            $validity_start_date    = string_to_date($row['validity_start_date']);
            $validity_end_date      = string_to_date($row['validity_end_date']);

?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="geographical_area_view.php?geographical_area_id=<?=$parent_id?>"><?=$parent_id?></a></td>
                    <td class="govuk-table__cell"><?=$parent_description?></td>
                    <td class="govuk-table__cell"><?=$parent_sid?></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell"><?=$validity_end_date?></td>
                </tr>
<?php
        }
    }
?>
            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>

        </div>
</div>

<?php
    require ("includes/footer.php")
?>