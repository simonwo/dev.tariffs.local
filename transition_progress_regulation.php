<?php
    $title = "Transition progress";
	require ("includes/db.php");
	require ("includes/header.php");
	$section_id = get_querystring("section_id");
?>
<div id="wrapper" class="direction-ltr">
	<!-- Start breadcrumbs //-->
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				Transition progress
			</li>
		</ol>
	</div>
	<!-- End breadcrumbs //-->
	<div class="app-content__header">
		<h1 class="govuk-heading-xl">Transition progress - by regulation</h1>
	</div>


	<p>This page lists progress on migrating measures. Listed below are all measures, by regulation, measure type and geographical area ID
		that do not stop on or before the expected date of EU Exit and therefore still need to be either terminated or transitioned.</p>
	<p><a href="transition_progress.html">View transition progress by measure type</a></p>
	<p><strong>Please note</strong> - this page is dealing with a complex query and will take up to 1 minute to load fully.</p>
<?php
ob_flush();
flush();
?>

	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
            <th class="govuk-table__header nopad" style="width:10%">Regulation</th>
            <th class="govuk-table__header" style="width:45%">Measure type</th>
			<th class="govuk-table__header" style="width:35%">Geographical area ID</th>
			<th class="govuk-table__header r" style="width:10%">Count</th>
		</tr>

<?php
    $sql = "select m.measure_generating_regulation_id, m.measure_type_id, m.geographical_area_id,
    mtd.description as measure_type_description, ga.description as geo_description, count (*) as count
    from ml.measures_real_end_dates m, ml.ml_geographical_areas ga, measure_type_descriptions mtd, goods_nomenclatures g
    where mtd.measure_type_id = m.measure_type_id 
    and m.geographical_area_id = ga.geographical_area_id
    and g.goods_nomenclature_item_id = m.goods_nomenclature_item_id
    and g.producline_suffix = '80'
    and g.validity_end_date is null
    and m.validity_start_date < '2019-11-01'
    and (m.validity_end_date is null
    or m.validity_end_date > '2019-10-31')
    group by m.measure_generating_regulation_id, m.measure_type_id, m.geographical_area_id, mtd.description, ga.description
    order by m.measure_generating_regulation_id, m.measure_type_id, m.geographical_area_id";
	//echo ($sql);
	$tally = 0;
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$measure_generating_regulation_id   = $row['measure_generating_regulation_id'];
			$geographical_area_id               = $row['geographical_area_id'];
			$geo_description                    = $row['geo_description'];
			$count                              = $row['count'];
			$measure_type_id                    = $row['measure_type_id'];
			$measure_type_description           = $row['measure_type_description'];
			$tally += $count;
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad"><a href="regulation_view.html?base_regulation_id=<?=$measure_generating_regulation_id?>" target="_blank"><?=$measure_generating_regulation_id?></a></td>
			<td class="govuk-table__cell"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>" target="_blank"><?=$measure_type_id?> - <?=$measure_type_description?></a></td>
			<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>" target="_blank"><?=$geographical_area_id?> - <?=$geo_description?></a></td>
			<td class="govuk-table__cell r"><?=number_format($count)?></td>
		</tr>
<?php
		}
	}
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell b">TOTAL</td>
			<td class="govuk-table__cell">&nbsp;</td>
			<td class="govuk-table__cell">&nbsp;</td>
			<td class="govuk-table__cell b r"><?=number_format($tally)?></td>
		</tr>
    </table>
</div>

<?php
	require ("includes/footer.php")
?>