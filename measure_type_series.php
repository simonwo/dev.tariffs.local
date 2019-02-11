<?php
    require ("includes/db.php");
    require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Measure type series
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <main id="content" lang="en">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">Measure type series</h1></div>
                </div>
            </div>

<?php
	$sql = "SELECT mts.measure_type_series_id, validity_start_date, validity_end_date, measure_type_combination, description
    FROM measure_type_series mts, measure_type_series_descriptions mtsd
    WHERE mts.measure_type_series_id = mtsd.measure_type_series_id ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header c" style="width:15%">Measure type series ID</th>
        <th class="govuk-table__header" style="width:40%">Description</th>
        <th class="govuk-table__header c" style="width:15%">Measure type combination</th>
        <th class="govuk-table__header" style="width:15%">Start date</th>
        <th class="govuk-table__header" style="width:15%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $measure_type_series_id            = $row['measure_type_series_id'];
            $measure_type_combination   = $row['measure_type_combination'];
            $description                = $row['description'];
            $validity_start_date        = string_to_date($row['validity_start_date']);
            $validity_end_date          = string_to_date($row['validity_end_date']);
            $rowclass                   = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="<?=$rowclass?>">
        <td class="govuk-table__cell c"><a href="/measure_types.php?measure_type_series_id=<?=$measure_type_series_id?>">Type <?=$measure_type_series_id?></a></td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell c"><?=$measure_type_combination?></td>
        <td class="govuk-table__cell"><?=$validity_start_date?></td>
        <td class="govuk-table__cell"><?=$validity_end_date?></td>
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