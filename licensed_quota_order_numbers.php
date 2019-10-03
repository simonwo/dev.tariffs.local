<?php
    $title = "Quota order numbers";
	require ("includes/db.php");
	require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item">Licensed quota order numbers</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl nomargin">Licensed quota order numbers</h1>
</div>


<?php
	$sql = "select distinct ordernumber, m.measure_type_id, geographical_area_id, mtd.description as measure_type_description
	from measures m, measure_type_descriptions mtd
	where ordernumber like '094%'
	and m.measure_type_id = mtd.measure_type_id
	and (m.validity_end_date >= '2008-01-01' or validity_end_date is null)
	order by ordernumber, m.measure_type_id, geographical_area_id
	";
	$result = pg_query($conn, $sql);
	$quota_order_numbers = array();
	if ($result) {
?>
<table class="govuk-table" cellspacing="0">
	<thead class="govuk-table__head">
	<tr class="govuk-table__row">
		<th class="govuk-table__header" scope="col">Order number</th>
		<th class="govuk-table__header" scope="col">Measure type</th>
		<th class="govuk-table__header" scope="col">Geographical area</th>
	</tr>
	</thead>
	<tbody>
<?php
		while ($row = pg_fetch_array($result)) {
			$ordernumber				= $row['ordernumber'];
			$measure_type_id			= $row['measure_type_id'];
			$measure_type_description	= $row['measure_type_description'];
			$geographical_area_id		= $row['geographical_area_id'];
?>
	<tr class="govuk-table__row">
		<td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$ordernumber?>"><?=$ordernumber?></a></td>
		<td class="govuk-table__cell"><?=$measure_type_id?>&nbsp;<?=$measure_type_description?></td>
		<td class="govuk-table__cell"><?=$geographical_area_id?></td>
	</tr>
<?php
		}
	}
?>
	</tbody>
</table>
<?php		
	
?>
<p class="back_to_top"><a href="#top">Back to top</a></p>

</div>
<?php
	require ("includes/footer.php")
?>