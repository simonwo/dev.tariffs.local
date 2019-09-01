<?php
    $title = "View quota order number";
	require ("includes/db.php");
	$quota_order_number_id        = get_querystring("quota_order_number_id");
	$quota_order_number = new quota_order_number;
	$quota_order_number->set_properties($quota_order_number_id, "", "");
	$quota_order_number->get_origins();

	$quota_definition = new quota_definition;
	$quota_definition->clear_cookies();
	require ("includes/header.php");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Home</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota order numbers</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">Quota <?=$quota_order_number_id?></li>
	</ol>
	</div>
	<div class="app-content__header">
    	<h1 class="govuk-heading-xl">View quota order number <?=$quota_order_number_id?></h1>
	</div>

		<!-- MENU //-->
		<h2>Page content</h2>
		<ul class="tariff_menu">
			<li><a href="#details">Quota details</a></li>
			<li><a href="#definitions">Quota definitions</a></li>
			<li><a href="#associations">Quota associations</a></li>
			<li><a href="#origins">Quota origins</a></li>
			<li><a href="#commodities">Commodities</a></li>
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
			$validity_start_date    = short_date($row["validity_start_date"]);
			$validity_end_date      = short_date($row["validity_end_date"]);
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
			
		<h2 id="definitions">Quota definitions</h2>
		<form action="/quota_definition_create_edit.html" method="get" class="inline_form">
		<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
		<input type="hidden" name="action" value="new" />
		<h3>New definition</h3>
		<div class="column-one-third" style="width:320px">
		<div class="govuk-form-group" style="padding:0px;margin:0px">
				<button type="submit" class="govuk-button">Create new quota definition</button>
			</div>
		</div>
		<div class="clearer"><!--&nbsp;//--></div>
		</form>

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
				<th class="govuk-table__header tiny">Definition SID</th>
				<th class="govuk-table__header tiny">Start date</th>
				<th class="govuk-table__header tiny">End date</th>
				<th class="govuk-table__header tiny">Vol</th>
				<th class="govuk-table__header c tiny">Unit</th>
				<th class="govuk-table__header c tiny">Precision</th>
				<th class="govuk-table__header c tiny">Critical state</th>
				<th class="govuk-table__header c tiny">Critical threshold</th>
				<th class="govuk-table__header c tiny">Monetary unit</th>
				<th class="govuk-table__header tiny" style="width:25%">Description</th>
				<th class="govuk-table__header tiny">Actions</th>
			</tr>
<?php            
		while ($row = pg_fetch_array($result)) {
			$quota_definition_sid 				= $row["quota_definition_sid"];
			$quota_order_number_id  			= $row["quota_order_number_id"];
			$validity_start_date                = short_date($row["validity_start_date"]);
			$validity_end_date                  = short_date($row["validity_end_date"]);
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
				<td class="govuk-table__cell tiny"><?=$quota_definition_sid?></td>
				<td class="govuk-table__cell tiny" nowrap><?=$validity_start_date?></td>
				<td class="govuk-table__cell tiny" nowrap><?=$validity_end_date?></td>
				<td class="govuk-table__cell tiny"><?=$initial_volume?></td>
				<td class="govuk-table__cell c tiny"><?=$measurement_unit_code?>&nbsp;<?=$measurement_unit_qualifier_code?></td>
				<td class="govuk-table__cell c tiny"><?=$maximum_precision?></td>
				<td class="govuk-table__cell c tiny"><?=$critical_state?></td>
				<td class="govuk-table__cell c tiny"><?=$critical_threshold?></td>
				<td class="govuk-table__cell c tiny"><?=$monetary_unit_code?></td>
				<td class="govuk-table__cell tiny"><?=$description?></td>
				<td class="govuk-table__cell">
					<form action="quota_definition_create_edit.html" method="get">
						<input type="hidden" name="action" value="edit" />
						<input type="hidden" name="quota_definition_sid" value="<?=$quota_definition_sid?>" />
						<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
						<button type="submit" class="govuk-button btn_nomargin")>Edit</button>
					</form>
					<form action="quota_definition_create_edit.html" method="get">
						<input type="hidden" name="action" value="duplicate" />
						<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
						<input type="hidden" name="quota_definition_sid" value="<?=$quota_definition_sid?>" />
						<button type="submit" class="govuk-button btn_nomargin")>Duplicate</button>
					</form>
					<form action="actions/quota_definition_actions.html" method="get">
						<input type="hidden" name="action" value="delete" />
						<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
						<input type="hidden" name="quota_definition_sid" value="<?=$quota_definition_sid?>" />
						<button type="submit" class="govuk-button btn_nomargin")>Delete</button>
					</form>
				</td>
			</tr>
<?php
		}
?>
		</table>
<?php        
	}
?>

			<p class="back_to_top"><a href="#top">Back to top</a></p>



		<h2 id="associations">Quota associations</h2>


<?php
	$sql = "select qdm.quota_order_number_id as main_quota_order_number_id, qdm.quota_definition_sid as main_quota_definition_sid,
	qds.quota_order_number_id as sub_quota_order_number_id, qds.quota_definition_sid as sub_quota_definition_sid, 
	qdm.validity_start_date as main_start_date, qds.validity_start_date as sub_start_date, 
	qdm.validity_end_date as main_end_date, qds.validity_end_date as sub_end_date, qa.relation_type, qa.coefficient,
	qdm.initial_volume as main_volume, qdm.measurement_unit_code as main_unit, qdm.measurement_unit_qualifier_code as main_qualifier, 
	qds.initial_volume as sub_volume, qds.measurement_unit_code as sub_unit, qdm.measurement_unit_qualifier_code as sub_qualifier
	from quota_associations qa, quota_definitions qdm, quota_definitions qds
	where qa.main_quota_definition_sid = qdm.quota_definition_sid
	and qa.sub_quota_definition_sid = qds.quota_definition_sid
	and (qdm.quota_order_number_id = '" . $quota_order_number_id . "' or qds.quota_order_number_id = '" . $quota_order_number_id . "')
	order by main_quota_order_number_id, sub_quota_order_number_id, main_start_date desc";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)){
?>
		<p>There are <strong><?=pg_num_rows($result)?></strong> associations on this quota.</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header cell_grey tiny">main quota order number id</th>
				<th class="govuk-table__header cell_grey tiny">main quota definition sid</th>
				<th class="govuk-table__header cell_grey tiny">main dates</th>
				<th class="govuk-table__header cell_grey tiny">main balance</th>
				<th class="govuk-table__header tiny">sub quota order number id</th>
				<th class="govuk-table__header tiny">sub quota definition sid</th>
				<th class="govuk-table__header tiny">sub dates</th>
				<th class="govuk-table__header tiny">sub balance</th>
				<th class="govuk-table__header c cell_grey tiny">relation type</th>
				<th class="govuk-table__header r cell_grey tiny">coefficient</th>
			</tr>
<?php            
		while ($row = pg_fetch_array($result)) {
			$main_quota_order_number_id	= $row["main_quota_order_number_id"];
			$main_quota_definition_sid  = $row["main_quota_definition_sid"];
			$sub_quota_order_number_id  = $row["sub_quota_order_number_id"];
			$sub_quota_definition_sid  	= $row["sub_quota_definition_sid"];
			$main_start_date            = short_date($row["main_start_date"]);
			$sub_start_date             = short_date($row["sub_start_date"]);
			$main_end_date            	= short_date($row["main_end_date"]);
			$sub_end_date             	= short_date($row["sub_end_date"]);
			$relation_type              = $row["relation_type"];
			$coefficient                = number_format($row["coefficient"], 5);

			$main_volume				= $row["main_volume"];
			$main_unit					= $row["main_unit"];
			$main_qualifier				= $row["main_qualifier"];
			$sub_volume					= $row["sub_volume"];
			$sub_unit					= $row["sub_unit"];
			$sub_qualifier				= $row["sub_qualifier"];
		?>
			<tr class="govuk-table__row">
<?php
	if ($main_quota_order_number_id == $quota_order_number_id) {
?>
				<td class="govuk-table__cell cell_grey tiny"><?=$main_quota_order_number_id?></td>
<?php
	} else {
?>
				<td class="govuk-table__cell cell_grey tiny"><a href="/quota_order_number_view.html?quota_order_number_id=<?=$main_quota_order_number_id?>#associations"><?=$main_quota_order_number_id?></a></td>
<?php		
	}
?>
				<td class="govuk-table__cell cell_grey tiny"><?=$main_quota_definition_sid?></td>
				<td class="govuk-table__cell cell_grey tiny" nowrap><?=$main_start_date?> to <?=$main_end_date?></td>
				<td class="govuk-table__cell cell_grey tiny"><?=$main_volume?> <?=$main_unit?> <?=$main_qualifier?></td>
				<?php
	if ($sub_quota_order_number_id == $quota_order_number_id) {
?>
				<td class="govuk-table__cell tiny"><?=$sub_quota_order_number_id?></td>
<?php
	} else {
?>
				<td class="govuk-table__cell tiny"><a href="/quota_order_number_view.html?quota_order_number_id=<?=$sub_quota_order_number_id?>#associations"><?=$sub_quota_order_number_id?></a></td>
<?php		
	}
?>

				<td class="govuk-table__cell tiny"><?=$sub_quota_definition_sid?></td>
				<td class="govuk-table__cell tiny"><?=$sub_start_date?> to <?=$sub_end_date?></td>
				<td class="govuk-table__cell tiny"><?=$sub_volume?> <?=$sub_unit?> <?=$sub_qualifier?></td>
				<td class="govuk-table__cell c cell_grey tiny"><?=$relation_type?></td>
				<td class="govuk-table__cell r cell_grey tiny"><?=$coefficient?></td>
			</tr>
<?php
		}
?>
		</table>
<?php        
	} else {
?>
<P>There are no quota associations on this quota.</p>
<?php		
	}
?>

			<p class="back_to_top"><a href="#top">Back to top</a></p>







			<h2 id="origins">Quota origins</h2>
			<form action="/quota_order_number_add_origin.html" method="get" class="inline_form">
			<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
			<input type="hidden" name="quota_order_number_sid" value="<?=$quota_order_number_sid?>" />
		<input type="hidden" name="action" value="new" />
		<h3>New origin</h3>
		<div class="column-one-third" style="width:320px">
		<div class="govuk-form-group" style="padding:0px;margin:0px">
				<button type="submit" class="govuk-button">Add origin</button>
			</div>
		</div>
		<div class="clearer"><!--&nbsp;//--></div>
		</form>
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
				<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a> (<?=$description?>)</td>
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
		


		<h2 id="commodities">Commodities associated with this quota</h2>
<?php
	$sql = "SELECT DISTINCT m.goods_nomenclature_item_id, gd.description, fn.description as friendly
	FROM measures m, goods_nomenclature_descriptions gd
	left outer join ml.commodity_friendly_names fn on left(gd.goods_nomenclature_item_id, 8) = fn.goods_nomenclature_item_id
	WHERE m.goods_nomenclature_item_id = gd.goods_nomenclature_item_id
	and gd.productline_suffix = '80'
	and m.ordernumber = '" . $quota_order_number_id . "'
	AND m.validity_start_date >= '2017-01-01'
	ORDER BY 1 ";

	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)){
?>
			<p>There are <strong><?=pg_num_rows($result)?></strong> commodities associated with this quota.</p>
			<p>Please note: the 'friendly' descriptions are derived from the website 'tariffnumber.com'.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:10%">Commodity</th>
					<th class="govuk-table__header" style="width:45%">Description</th>
					<th class="govuk-table__header" style="width:45%">Friendly description</th>
				</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$description				= $row['description'];
			$friendly					= $row['friendly'];
			
			$commodity_url                  = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell"><a class="nodecorate" href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=format_commodity_code($goods_nomenclature_item_id)?></a></td>
					<td class="govuk-table__cell"><?=$description?></td>
					<td class="govuk-table__cell"><em><?=$friendly?></em></td>
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
					<th class="govuk-table__header c">Measure type</th>
					<th class="govuk-table__header c">Geographical area</th>
					<th class="govuk-table__header">Commodity</th>
					<th class="govuk-table__header">Start date</th>
					<th class="govuk-table__header">End date</th>
					<th class="govuk-table__header c">Regulation</th>
				</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$measure_sid                = $row['measure_sid'];
			$measure_type_id            = $row['measure_type_id'];
			$geographical_area_id       = $row['geographical_area_id'];
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$regulation_id_full         = $row['measure_generating_regulation_id'];
			$validity_start_date        = short_date($row['validity_start_date']);
			$validity_end_date          = short_date($row['validity_end_date']);
            $rowclass                   = rowclass($validity_start_date, $validity_end_date);
			
			$commodity_url                  = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell c"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
					<td class="govuk-table__cell c"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
					<td class="govuk-table__cell"><a class="nodecorate" href="<?=$commodity_url?>" data-lity data-lity-target="<?=$commodity_url?>?>"><?=format_commodity_code($goods_nomenclature_item_id)?></a></td>
					<td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
					<td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
					<td class="govuk-table__cell c"><a href="regulation_view.html?base_regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
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