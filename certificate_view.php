<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $certificate_type_code  = get_querystring("certificate_type_code");
    $certificate_code       = get_querystring("certificate_code");
?>

<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/certificates.php">Certificates</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Certificate
            </li>
        </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Certificates</h1>
    </div>

    <!-- MENU //-->
    <p class="b">Page content</p>
    <ul class="tariff_menu">
        <li><a href="#details">Certificate details</a></li>
        <li><a href="#usage_measures">Certificate usage in measures</a></li>
    </ul>

    <h2 class="nomargin" id="details">Certificate details</h2>
    <table class="govuk-table" cellspacing="0">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:15%">Property</th>
            <th class="govuk-table__header" style="width:50%">Value</th>
        </tr>
<?php
	$sql = "SELECT certificate_type_code, certificate_code, description, validity_start_date, validity_end_date
    FROM ml.ml_certificate_codes WHERE certificate_type_code = '" . $certificate_type_code . "'
    AND certificate_code = '" . $certificate_code . "'";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $certificate_type_code  = $row[0];
        $certificate_code       = $row[1];
        $description            = $row[2];
        $validity_start_date    = $row[3];
        $validity_end_date      = $row[4];
?>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Certificate type / code</td>
            <td class="govuk-table__cell"><?=$certificate_type_code?><?=$certificate_code?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Description</td>
            <td class="govuk-table__cell b"><?=$description?></td>
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
    
    <h2 id="usage_measures">Certificate usage in measures</h2>
<?php
	$sql = "SELECT m.measure_sid, mc.condition_code, mc.action_code, m.measure_type_id, m.goods_nomenclature_item_id,
    m.validity_start_date, m.validity_end_date, m.geographical_area_id, m.measure_generating_regulation_id as regulation_id_full
    FROM measure_conditions mc, measures m
    WHERE mc.measure_sid = m.measure_sid
    AND certificate_type_code = '" . $certificate_type_code . "' AND certificate_code = '" . $certificate_code . "'
    ORDER BY m.validity_start_date, m.goods_nomenclature_item_id";
    $result = pg_query($conn, $sql);
	if  ($result) {
?>
    <table class="govuk-table" cellspacing="0">
        <tr class="govuk-table__row">
            <th style="width:10%">SID</th>
            <th style="width:10%" class="c">Certificate code</th>
            <th style="width:10%" class="c">Action code</th>
            <th style="width:10%" class="c">Condition code</th>
            <th style="width:10%">Commodity code</th>
            <th style="width:10%">Start date</th>
            <th style="width:10%">End date</th>
            <th style="width:10%" class="c">Geographical area</th>
            <th style="width:10%" class="c">Measure type</th>
            <th style="width:10%">Regulation&nbsp;ID</th>
        </tr>
<?php
        while ($row = pg_fetch_array($result)) {
            $measure_sid                = $row['measure_sid'];
            $condition_code             = $row['condition_code'];
            $action_code                = $row['action_code'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $measure_type_id            = $row['measure_type_id'];
            $geographical_area_id       = $row['geographical_area_id'];
            $regulation_id_full         = $row['regulation_id_full'];
            $validity_start_date        = string_to_date($row['validity_start_date']);
            $validity_end_date          = string_to_date($row['validity_end_date']);

            $commodity_url              = "/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell"><a href="measure_view.php?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
            <td class="govuk-table__cell c"><?=$certificate_type_code?><?=$certificate_code?></td>
            <td class="govuk-table__cell c"><?=$action_code?></td>
            <td class="govuk-table__cell c"><?=$condition_code?></td>
            <td class="govuk-table__cell"><a href="<?=$commodity_url?>"><?=$goods_nomenclature_item_id?></a></td>
            <td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
            <td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
            <td class="govuk-table__cell c"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
            <td class="govuk-table__cell c"><a href="measure_type_view.php?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
            <td class="govuk-table__cell"><a href="regulation_view.php?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
        </tr>
<?php
        }
?>
    </table>
<?php
    } else {
        echo ("<p>There are no associations of this foonote with measures.");
    }
?>
    <p class="back_to_top"><a href="#top">Back to top</a></p>
</div>
<?php
    require ("includes/footer.php")
?>