<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $measure_type_id = get_querystring("measure_type_id");
?>
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/measure_types.php">Measure types</a>
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View measure type <?=$measure_type_id?></h1>
    </div>

        <h2 class="nomargin">Measure type details</h2>
        <table class="govuk-table" cellspacing="0">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:30%">Item</th>
                <th class="govuk-table__header" style="width:70%">Value</th>
            </tr>
<?php
	$sql = "SELECT mt.measure_type_id, validity_start_date, validity_end_date,
    trade_movement_code, priority_code, measure_component_applicable_code,
    origin_dest_code, order_number_capture_code, measure_explosion_level, mt.measure_type_series_id, mtd.description,
    mtsd.description as measure_type_series_description
    FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
    WHERE mt.measure_type_id = mtd.measure_type_id
    AND mt.measure_type_series_id = mtsd.measure_type_series_id
    AND mt.measure_type_id = '" . $measure_type_id . "' ORDER BY 1";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $measure_type_id                    = $row['measure_type_id'];
            $validity_start_date                = string_to_date($row['validity_start_date']);
            $validity_end_date                  = string_to_date($row['validity_end_date']);
            $trade_movement_code                = $row['trade_movement_code'];
            $priority_code                      = $row['priority_code'];
            $measure_component_applicable_code  = $row['measure_component_applicable_code'];
            $origin_dest_code                   = $row['origin_dest_code'];
            $order_number_capture_code          = $row['order_number_capture_code'];
            $measure_explosion_level            = $row['measure_explosion_level'];
            $measure_type_series_id             = $row['measure_type_series_id'];
            $description                        = $row['description'];
            $measure_type_series_description    = $row['measure_type_series_description'];
?>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Description</td>
                <td class="govuk-table__cell b"><?=$description?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Measure type ID</td>
                <td class="govuk-table__cell"><?=$measure_type_id?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Validity start date</td>
                <td class="govuk-table__cell"><?=$validity_start_date?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Validity end date</td>
                <td class="govuk-table__cell"><?=$validity_end_date?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Trade movement code</td>
                <td class="govuk-table__cell"><?=$trade_movement_code?> - <?=trade_movement_code($trade_movement_code)?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Priority code</td>
                <td class="govuk-table__cell"><?=$priority_code?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Measure component applicable code</td>
                <td class="govuk-table__cell"><?=$measure_component_applicable_code?> - <?=measure_component_applicable_code($measure_component_applicable_code)?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Origin dest code</td>
                <td class="govuk-table__cell"><?=$origin_dest_code?> - <?=origin_dest_code($origin_dest_code)?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Order number capture code</td>
                <td class="govuk-table__cell"><?=$order_number_capture_code?> - <?=order_number_capture_code($order_number_capture_code)?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Explosion level</td>
                <td class="govuk-table__cell"><?=$measure_explosion_level?></td>
            </tr>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell">Measure type series ID</td>
                <td class="govuk-table__cell"><?=$measure_type_series_id?> - <?=$measure_type_series_description?></td>
            </tr>
<?php
        }
    }
?>

        </table>
<?php
    $sql = "SELECT measure_sid, goods_nomenclature_item_id, regulation_id_full, additional_code_type_id, additional_code_id, measure_type_id,
    geographical_area_id, validity_start_date, validity_end_date, ordernumber
    FROM ml.v5_brexit_day WHERE measure_type_id = '" . $measure_type_id . "'";
    $result = pg_query($conn, $sql);
	if  ($result) {
?>
        <h2 class="nomargin">Measures of type <?=$description?></h2>
        <table class="govuk-table" cellspacing="0">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:10%">Measure SID</th>
                <th class="govuk-table__header" style="width:10%">Commodity</th>
                <th class="govuk-table__header c" style="width:10%">Additional code</th>
                <th class="govuk-table__header c" style="width:10%">Geographical area</th>
                <th class="govuk-table__header" style="width:10%">Start date</th>
                <th class="govuk-table__header" style="width:10%">End date</th>
                <th class="govuk-table__header" style="width:10%">Regulation</th>
                <th class="govuk-table__header" style="width:10%">Order number</th>
            </tr>
<?php        
        while ($row = pg_fetch_array($result)) {
            $measure_sid                = $row['measure_sid'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $regulation_id_full         = $row['regulation_id_full'];
            $additional_code_type_id    = $row['additional_code_type_id'];
            $additional_code_id         = $row['additional_code_id'];
            $measure_type_id            = $row['measure_type_id'];
            $geographical_area_id       = $row['geographical_area_id'];
            $ordernumber                = $row['ordernumber'];
            $validity_start_date        = string_to_date($row['validity_start_date']);
            $validity_end_date          = string_to_date($row['validity_end_date']);
            if ($additional_code_type_id != "") {
                $additional_code_show = $additional_code_type_id . " / " . $additional_code_id;
            } else {
                $additional_code_show = "";
            }
?>            
            <tr class="govuk-table__row">
                <td class="govuk-table__cell"><a href="measure_view.php?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
                <td class="govuk-table__cell"><a href="goods_nomenclature_item_view.php?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>"><?=$goods_nomenclature_item_id?></a></td>
                <td class="govuk-table__cell c"><?=$additional_code_show?></td>
                <td class="govuk-table__cell c"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
                <td class="govuk-table__cell"><?=$validity_start_date?></td>
                <td class="govuk-table__cell"><?=$validity_end_date?></td>
                <td class="govuk-table__cell"><a href="regulation_view.php?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
                <td class="govuk-table__cell"><?=$ordernumber?></td>
            </tr>
<?php
        }
?>
        </table>
<?php
    } else {
        echo ("<p>No measures of this type");
    }
?>    
</div>
<?php
    require ("includes/footer.php")
?>