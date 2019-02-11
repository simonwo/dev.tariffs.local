<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $footnote_type_id   = get_querystring("footnote_type_id");
    $footnote_id        = get_querystring("footnote_id");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/footnotes.php">Footnotes</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Footnote
        </li>
    </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View footnote <?=$footnote_type_id?><?=$footnote_id?></h1>
    </div>
            <!-- MENU //-->
            <p class="b">Page content</p>
            <ul class="tariff_menu">
                <li><a href="#details">Footnote details</a></li>
                <li><a href="#usage_measures">Footnote usage in measures</a></li>
                <li><a href="#usage_nomenclature">Footnote usage in nomenclature</a></li>
            </ul>

            <h2 class="nomargin" id="details">Area details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:15%">Property</th>
                    <th class="govuk-table__header" style="width:50%">Value</th>
                </tr>
<?php
	$sql = "SELECT footnote_type_id, footnote_id, description, validity_start_date, validity_end_date
    FROM ml.ml_footnotes WHERE footnote_type_id = '" . $footnote_type_id . "' AND footnote_id = '" . $footnote_id . "';";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $footnote_type_id       = $row[0];
        $footnote_id            = $row[1];
        $description            = $row[2];
        $validity_start_date    = $row[3];
        $validity_end_date      = $row[4];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Footnote type / code</td>
                    <td class="govuk-table__cell b"><?=$footnote_type_id?><?=$footnote_id?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Description</td>
                    <td class="govuk-table__cell"><?=$description?></td>
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
            
            <h2 id="usage_measures">Footnote usage in measures</h2>
<?php
	$sql = "SELECT m.measure_sid, fam.footnote_type_id, fam.footnote_id, m.measure_type_id, m.goods_nomenclature_item_id,
    m.geographical_area_id, m.validity_start_date, m.validity_end_date, m.measure_generating_regulation_id
    FROM footnote_association_measures fam, measures m
    WHERE m.measure_sid = fam.measure_sid
    AND footnote_type_id = '" . $footnote_type_id . "' AND footnote_id = '" . $footnote_id . "'";
    $result = pg_query($conn, $sql);
    if  (($result) && (pg_num_rows($result) > 0)){
?>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:12%">SID</th>
                    <th class="govuk-table__header c" style="width:13%">Footnote ID</th>
                    <th class="govuk-table__header" style="width:12%">Commodity code</th>
                    <th class="govuk-table__header" style="width:13%">Start date</th>
                    <th class="govuk-table__header" style="width:12%">End date</th>
                    <th class="govuk-table__header c" style="width:13%">Geographical area</th>
                    <th class="govuk-table__header c" style="width:12%">Measure type</th>
                    <th class="govuk-table__header" style="width:13%">Regulation&nbsp;ID</th>
                </tr>
<?php
        while ($row = pg_fetch_array($result)) {
            $measure_sid                = $row['measure_sid'];
            $footnote_type_id           = $row['footnote_type_id'];
            $footnote_id                = $row['footnote_id'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
            $measure_type_id            = $row['measure_type_id'];
            $geographical_area_id       = $row['geographical_area_id'];
            $regulation_id_full         = $row['measure_generating_regulation_id'];
            $validity_start_date        = trim($row['validity_start_date'] . "");
            $validity_end_date          = trim($row['validity_end_date'] . "");
            

            $validity_start_date        = DateTime::createFromFormat('Y-m-d H:i:s', $validity_start_date)->format('Y-m-d');
            if ($validity_end_date != "") {
                $validity_end_date      = DateTime::createFromFormat('Y-m-d H:i:s', $validity_end_date)->format('Y-m-d');
            } else {
                $validity_end_date = "";
            }

            $commodity_url                  = "/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><a href="measure_view.php?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
                    <td class="govuk-table__cell c"><?=$footnote_type_id?><?=$footnote_id?></td>
                    <td class="govuk-table__cell"><a href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=$goods_nomenclature_item_id?></a></td>
                    <td class="govuk-table__cell"><?=$validity_start_date?></td>
                    <td class="govuk-table__cell"><?=$validity_end_date?></td>
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
            <h2 id="usage_measures">Footnote usage in nomenclature</h2>
<?php
	$sql = "SELECT fagn.footnote_type as footnote_type_id, fagn.footnote_id, g.goods_nomenclature_item_id
    FROM footnote_association_goods_nomenclatures fagn, goods_nomenclatures g
    WHERE g.goods_nomenclature_item_id = fagn.goods_nomenclature_item_id
    AND g.producline_suffix = '80' AND footnote_type = '" . $footnote_type_id . "' AND footnote_id = '" . $footnote_id . "'";
    $result = pg_query($conn, $sql);
    if  (($result) && (pg_num_rows($result) > 0)){
?>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:20%">Footnote ID</th>
                    <th class="govuk-table__header" style="width:80%">Commodity code</th>
                </tr>
<?php
        while ($row = pg_fetch_array($result)) {
            $footnote_type_id           = $row['footnote_type_id'];
            $footnote_id                = $row['footnote_id'];
            $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];

            $commodity_url                  = "/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><?=$footnote_type_id?><?=$footnote_id?></td>
                    <td class="govuk-table__cell"><a href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=$goods_nomenclature_item_id?></a></td>
                </tr>

<?php
        }
    
?>
            </table>
<?php
    } else {
        echo ("<p>There are no associations of this foonote with goods nomenclatures.");
        
    }
?>
            <p class="back_to_top"><a href="#top">Back to top</a></p>
</div>

<?php
    require ("includes/footer.php")
?>