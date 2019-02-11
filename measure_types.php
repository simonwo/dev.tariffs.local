<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $measure_type_series_id = get_querystring("measure_type_series_id");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Measure types
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <main id="content" lang="en">
    <div class="app-prose-scope">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">Measure types</h1></div>
                </div>
            </div>
<?php
    $clause = "";
    if ($measure_type_series_id != "") {
        $clause .= "AND mt.measure_type_series_id = '" . $measure_type_series_id . "'";
    }
	$sql = "SELECT mt.measure_type_id, mt.validity_start_date, mt.validity_end_date, mtd.description as measure_type_description,
    mt.measure_type_series_id, mtsd.description as measure_type_series_description
    FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
    WHERE mt.measure_type_series_id = mtsd.measure_type_series_id
    AND mt.measure_type_id = mtd.measure_type_id " . $clause . " ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header c" style="width:15%">Measure type ID</th>
        <th class="govuk-table__header c" style="width:15%">Series ID</th>
        <th class="govuk-table__header" style="width:38%">Description</th>
        <th class="govuk-table__header c" style="width:16%">Start date</th>
        <th class="govuk-table__header c" style="width:16%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $measure_type_id                    = $row['measure_type_id'];
            $measure_type_series_id             = $row['measure_type_series_id'];
            $measure_type_description           = $row['measure_type_description'];
            $validity_start_date                = string_to_date($row['validity_start_date']);
            $validity_end_date                  = string_to_date($row['validity_end_date']);
            $measure_type_series_description    = $row['measure_type_series_description'];
            $rowclass                           = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="govuk-table__row <?=$rowclass?>">
        <td class="govuk-table__cell c b"><a href="/measure_type_view.php?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
        <td class="govuk-table__cell c"><?=$measure_type_series_id?><br /><span class="explanatory"><?=$measure_type_series_description?></a></td>
        <td class="govuk-table__cell"><?=$measure_type_description?></td>
        <td class="govuk-table__cell c"><?=$validity_start_date?></td>
        <td class="govuk-table__cell c"><?=$validity_end_date?></td>
    </tr>
<?php            
		}
	}
?>
</table>
</div>
</div>
<?php
    require ("includes/footer.php")
?>