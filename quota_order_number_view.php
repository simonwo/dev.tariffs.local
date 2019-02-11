<?php
	require ("includes/db.php");
	require ("includes/header.php");
	$quota_order_number_id        = get_querystring("quota_order_number_id");
	$quota_order_number = new quota_order_number;
	$quota_order_number->set_properties($quota_order_number_id, "", "");
	$quota_order_number->get_origins();
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Home</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/quota_order_numbers.php">Quota order numbers</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">Quota</li>
	</ol>
	</div>
	<div class="app-content__header">
    	<h1 class="govuk-heading-xl">View quota order number <?=$quota_order_number_id?></h1>
	</div>

		<!-- MENU //-->
		<h2>Page content</h2>
		<ul class="tariff_menu">
			<li><a href="#details">Quota details</a></li>
			<li><a href="#origins">Quota origins</a></li>
			<li><a href="#definitions">Quota definitions</a></li>
			<li><a href="#measures">Quota measures</a></li>
			<li><a target="_blank" href="http://ec.europa.eu/taxation_customs/dds2/taric/quota_tariff_details.jsp?Lang=en&StartDate=2019-01-14&Code=<?=$quota_order_number_id?>">View on EU Taric quota consultation site</a></li>
		</ul>

		<h2 id="details">Quota details</h2>
<?php
	$sql = "SELECT quota_order_number_sid, quota_order_number_id, validity_start_date, validity_end_date
	FROM quota_order_numbers WHERE quota_order_number_id = '" . $quota_order_number_id . "' ORDER BY validity_start_date DESC";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)){
?>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header">SID</th>
				<th class="govuk-table__header">Quota order number ID</th>
				<th class="govuk-table__header">Start date</th>
				<th class="govuk-table__header">End date</th>
			</tr>
<?php            
		while ($row = pg_fetch_array($result)) {
			$quota_order_number_sid = $row["quota_order_number_sid"];
			$quota_order_number_id  = $row["quota_order_number_id"];
			$validity_start_date    = string_to_date($row["validity_start_date"]);
			$validity_end_date      = string_to_date($row["validity_end_date"]);
?>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell"><?=$quota_order_number_sid?></td>
				<td class="govuk-table__cell"><?=$quota_order_number_id?></td>
				<td class="govuk-table__cell"><?=$validity_start_date?></td>
				<td class="govuk-table__cell"><?=$validity_end_date?></td>
			</tr>
<?php
		}
?>
		</table>
<?php        
	}
?>

		<p class="back_to_top"><a href="#top">Back to top</a></p>
			
		<h2 id="origins">Quota origins</h2>
<?php
	$origin_count = count($quota_order_number->origins);
	if ($origin_count > 0) {
?>
		<p>There are <?=$origin_count?> origins associated with this quota.</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header">Origin SID</th>
				<th class="govuk-table__header">Origin</th>
				<th class="govuk-table__header">Exclusion</th>
			</tr>
<?php
		for($i = 0; $i < $origin_count; $i++) {
			$origin					= $quota_order_number->origins[$i];
			$origin_sid				= $origin->quota_order_number_origin_sid;
			$geographical_area_id	= $origin->geographical_area_id;
			$description			= $origin->description;
			$origin->get_exclusion_text();
			$exclusion_text			= $origin->exclusion_text;
	?>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell"><?=$origin_sid?></td>
				<td class="govuk-table__cell"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a> (<?=$description?>)</td>
				<td class="govuk-table__cell"><?=$exclusion_text?></td>
			</tr>
<?php
		}
?>			
		</table>
<?php		
	}
?>
		<p class="back_to_top"><a href="#top">Back to top</a></p>
		
		<h2 id="definitions">Quota definitions</h2>
<?php
	$sql = "SELECT quota_definition_sid, quota_order_number_id, validity_start_date, validity_end_date, quota_order_number_sid,
	initial_volume, measurement_unit_code, maximum_precision, critical_state, critical_threshold, monetary_unit_code,
	measurement_unit_qualifier_code, description
	FROM quota_definitions WHERE quota_order_number_id = '" . $quota_order_number_id . "' ORDER BY validity_start_date DESC";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)){
?>
		<p>There are <strong><?=pg_num_rows($result)?></strong> definition periods associated with this quota.</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header">Definition SID</th>
				<th class="govuk-table__header">Start date</th>
				<th class="govuk-table__header">End date</th>
				<th class="govuk-table__header">Vol</th>
				<th class="govuk-table__header c">Unit</th>
				<th class="govuk-table__header c">Precision</th>
				<th class="govuk-table__header c">Critical state</th>
				<th class="govuk-table__header c">Critical threshold</th>
				<th class="govuk-table__header c">Monetary unit</th>
				<th class="govuk-table__header">Description</th>
			</tr>
<?php            
		while ($row = pg_fetch_array($result)) {
			$quota_definition_sid 				= $row["quota_definition_sid"];
			$quota_order_number_id  			= $row["quota_order_number_id"];
			$validity_start_date                = string_to_date($row["validity_start_date"]);
			$validity_end_date                  = string_to_date($row["validity_end_date"]);
			$initial_volume                     = number_format($row["initial_volume"], 2);
			$measurement_unit_code              = $row["measurement_unit_code"];
			$maximum_precision                  = $row["maximum_precision"];
			$critical_state                     = $row["critical_state"];
			$critical_threshold                 = $row["critical_threshold"];
			$monetary_unit_code                 = $row["monetary_unit_code"];
			$measurement_unit_qualifier_code    = $row["measurement_unit_qualifier_code"];
			$description                        = $row["description"];
?>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell"><?=$quota_definition_sid?></td>
				<td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
				<td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
				<td class="govuk-table__cell"><?=$initial_volume?></td>
				<td class="govuk-table__cell c"><?=$measurement_unit_code?>&nbsp;<?=$measurement_unit_qualifier_code?></td>
				<td class="govuk-table__cell c"><?=$maximum_precision?></td>
				<td class="govuk-table__cell c"><?=$critical_state?></td>
				<td class="govuk-table__cell c"><?=$critical_threshold?></td>
				<td class="govuk-table__cell c"><?=$monetary_unit_code?></td>
				<td class="govuk-table__cell vsmall"><?=$description?></td>
			</tr>
<?php
		}
?>
		</table>
<?php        
	}
?>

			<p class="back_to_top"><a href="#top">Back to top</a></p>















			<h2 id="measures">Measures associated with this quota</h2>
<?php
	$sql = "SELECT measure_sid, measure_type_id, geographical_area_id, goods_nomenclature_item_id, validity_start_date,
	validity_end_date, measure_generating_regulation_id
	FROM measures WHERE ordernumber = '" . $quota_order_number_id . "'
	ORDER BY validity_start_date DESC, goods_nomenclature_item_id";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)){
?>
			<p>There are <strong><?=pg_num_rows($result)?></strong> measures associated with this quota.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header">SID</th>
					<th class="govuk-table__header C">Measure type</th>
					<th class="govuk-table__header C">Geographical area</th>
					<th class="govuk-table__header">Commodity</th>
					<th class="govuk-table__header">Start date</th>
					<th class="govuk-table__header">End date</th>
					<th class="govuk-table__header C">Regulation</th>
				</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$measure_sid                = $row['measure_sid'];
			$measure_type_id            = $row['measure_type_id'];
			$geographical_area_id       = $row['geographical_area_id'];
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$regulation_id_full         = $row['measure_generating_regulation_id'];
			$validity_start_date        = string_to_date($row['validity_start_date']);
			$validity_end_date          = string_to_date($row['validity_end_date']);
            $rowclass                   = rowclass($validity_start_date, $validity_end_date);
			
			$commodity_url                  = "/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell"><a href="measure_view.php?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell C"><a href="measure_type_view.php?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
					<td class="govuk-table__cell C"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
					<td class="govuk-table__cell"><a href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=$goods_nomenclature_item_id?></a></td>
					<td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
					<td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
					<td class="govuk-table__cell C"><a href="regulation_view.php?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
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