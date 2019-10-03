<?php
    $title = "View geographical area";
	require ("includes/db.php");
	$geographical_area_id   = get_querystring("geographical_area_id");
	$measure_scope          = get_querystring("measure_scope");
	if ($measure_scope == "") {
		$measure_scope = "all";
	}
	$sort = get_querystring("sort");
	if ($sort == "") {
		$sort = "date";
	}
	$currency = get_querystring("currency");
	if ($currency == "") {
		$currency = "all";
	}
	$member_currency = get_querystring("member_currency");
	if ($member_currency == "") {
		$member_currency = "current";
	}

	$geographical_area = new geographical_area;
	$geographical_area->clear_cookies();
	require ("includes/header.php");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/geographical_areas.html">Geographical areas</a>
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
				<li><a href="#history">Geographical area description history</a></li>
				<li><a href="#quotas">Quotas</a></li>
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
		$latest_description                 = $row[3];
		$geographical_code                  = $row[4];
		$validity_start_date                = $row[5];
		$validity_end_date                  = $row[6];
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Description</td>
					<td class="govuk-table__cell b"><?=$latest_description?></td>
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
					<td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">End date</td>
					<td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
				</tr>
<?php
	}
?>

			</table>


			<button type="submit" class="govuk-button" style="margin:0px">Edit geographical area</button>
			<p>Click on the button above to modify the start and end dates of this geographical area.</p>

			<p class="back_to_top"><a href="#top">Back to top</a></p>

<h2 id="history">Geographical area description history</h2>
<form action="/geographical_area_add_description.html" method="get" class="inline_form">
	<input type="hidden" name="phase" value="geographical_area_add_description" />
	<input type="hidden" name="action" value="new" />
	<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />
	<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
	<h3>Create new geographical area description</h3>
	<div class="column-one-third" style="width:320px">
	<div class="govuk-form-group" style="padding:0px;margin:0px">
			<button type="submit" class="govuk-button">New description</button>
		</div>
	</div>
	<div class="clearer"><!--&nbsp;//--></div>
</form>
<?php
	$sql = "SELECT gad.geographical_area_description_period_sid, gad.description,
	gadp.validity_start_date, gadp.validity_end_date
	FROM geographical_area_description_periods gadp, geographical_area_descriptions gad
	WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
	AND gad.geographical_area_id = '" . $geographical_area_id ."'
	ORDER BY gadp.validity_start_date DESC";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
		<p>The table below lists the historic and current descriptions for this geographical area. You can only edit or delete descriptions
		that have not yet begun.</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header" style="width:10%">SID</th>
				<th class="govuk-table__header" style="width:69%">Name</th>
				<th class="govuk-table__header" style="width:15%">Validity start date</th>
				<th class="govuk-table__header c" style="width:6%">Actions</th>
			</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$geographical_area_description_period_sid   = $row["geographical_area_description_period_sid"];
			$description                                = $row["description"];
			$validity_start_date                        = short_date($row["validity_start_date"]);
			$validity_end_date                          = short_date($row["validity_end_date"]);
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><?=$geographical_area_description_period_sid?></td>
					<td class="govuk-table__cell"><?=$description?></td>
					<td class="govuk-table__cell"><?=$validity_start_date?></td>
					<td class="govuk-table__cell c">
<?php
	$today = date("Y-m-d");
	if ($validity_start_date > $today) {
?>
						<form action="geographical_area_add_description.html" method="get">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />
							<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
							<input type="hidden" name="geographical_area_description_period_sid" value="<?=$geographical_area_description_period_sid?>" />
							<button type="submit" class="govuk-button btn_nomargin")>Edit</button>
						</form>
						<form action="actions/geographical_area_actions.html" method="get">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="phase" value="geographical_area_description_delete" />
							<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />
							<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
							<input type="hidden" name="geographical_area_description_period_sid" value="<?=$geographical_area_description_period_sid?>" />
							<button type="submit" class="govuk-button btn_nomargin")>Delete</button>
						</form>
<?php
	}
?>
					</td>
				</tr>
<?php
		}
?>
		</table>
		<?php
	}
?>

<h2 id="quotas">Quotas related to <?=$latest_description?></h2>
<?php
	$sql = "SELECT DISTINCT m.ordernumber, m.measure_type_id, mtd.description as measure_type_description,
	COUNT(m.measure_sid)
	FROM ml.v5_2019 m, ml.ml_geographical_areas g, measure_type_descriptions mtd
	WHERE m.geographical_area_id = g.geographical_area_id
	AND mtd.measure_type_id = m.measure_type_id
	AND ordernumber IS NOT NULL
	AND m.geographical_area_id = '" . $geographical_area_id . "'
	GROUP BY m.ordernumber, m.measure_type_id, mtd.description
	ORDER BY 1, 2";
	// echo ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
			<p>There are <strong><?=pg_num_rows($result)?></strong> matching quotas. Please note - this list is derived
		from the measures table, not the quotas table, so that licensed quotas are included.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:10%">Order number</th>
					<th class="govuk-table__header" style="width:75%">Type</th>
					<th class="govuk-table__header c" style="width:15%">Measure count</th>
				</tr>

<?php
		while ($row = pg_fetch_array($result)) {

			$ordernumber                = $row['ordernumber'];
			$measure_type_id            = $row['measure_type_id'];
			$measure_type_description   = $row['measure_type_description'];
			$count                      = $row['count'];
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$ordernumber?>"><?=$ordernumber?></a></td>
					<td class="govuk-table__cell"><?=$measure_type_id?> - <?=$measure_type_description?></td>
					<td class="govuk-table__cell c"><?=$count?></td>
				</tr>

<?php
		}
	}
?>
			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>



<h2 id="measures">Measure details</h2>

			<form action="/actions/geographical_area_actions.html#measures" method="get" class="inline_form">
			<h3>Filter results</h3>
			<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />
			<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
			<input type="hidden" name="phase" value="measure_filter_geographical_area_view" />

			<!-- Scope //-->
			<div class="column-one-third" style="width:300px;padding-bottom:1.5em;">
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($measure_scope, "all") ?> type="radio" class="govuk-radios__input" name="measure_scope" id="measure_scope_all" value="all" />
						<label class="govuk-label govuk-radios__label" for="measure_scope_all">Show all measures</label>
					</div>
				</div><br/>
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($measure_scope, "duty") ?> type="radio" class="govuk-radios__input" name="measure_scope" id="measure_scope_duty" value="duty" />
						<label class="govuk-label govuk-radios__label" for="measure_scope_duty">Only show duty measures</label>
					</div>
				</div>
			</div>

			<!-- Currency //-->
			<div class="column-one-third" style="width:230px">
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($currency, "all") ?> type="radio" class="govuk-radios__input" name="currency" id="currency_all" value="all" />
						<label class="govuk-label govuk-radios__label" for="currency_all">Show all</label>
					</div>
				</div><br/>
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($currency, "current") ?> type="radio" class="govuk-radios__input" name="currency" id="currency_current" value="current" />
						<label class="govuk-label govuk-radios__label" for="currency_current">Show only current</label>
					</div>
				</div>
			</div>

			<!-- Sort order //-->
			<div class="column-one-third" style="width:230px">
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($sort, "date") ?> type="radio" class="govuk-radios__input" name="sort" id="sort_date" value="date" />
						<label class="govuk-label govuk-radios__label" for="sort_date">Sort by date</label>
					</div>
				</div><br/>
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($sort, "commodity") ?> type="radio" class="govuk-radios__input" name="sort" id="sort_commodity" value="commodity" />
						<label class="govuk-label govuk-radios__label" for="sort_commodity">Sort by commodity</label>
					</div>
				</div>
			</div>

			<div class="column-one-third" style="width:130px">
				<div class="govuk-form-group" style="padding:0px;margin:0px">
					<button type="submit" class="govuk-button" style="xmargin-top:54px">Search</button>
				</div>
			</div>
			<div class="clearer"><!--&nbsp;//--></div>
			</form>



<?php
	// Get the duties
	$duty_list = [];
	$sql = "SELECT mc.duty_expression_id, mc.duty_amount, monetary_unit_code, measurement_unit_code,
	measurement_unit_qualifier_code, m.measure_sid, m.goods_nomenclature_item_id
	FROM ml.v5_2019 m, measure_components mc WHERE m.measure_sid = mc.measure_sid
	AND m.geographical_area_id = '" . $geographical_area_id . "' ORDER BY m.measure_sid, mc.duty_expression_id";

	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$measure_sid						= $row["measure_sid"];
			$goods_nomenclature_item_id			= $row["goods_nomenclature_item_id"];
			$duty_expression_id					= $row["duty_expression_id"];
			$duty_amount						= $row["duty_amount"];
			$monetary_unit_code					= $row["monetary_unit_code"];
			$measurement_unit_code				= $row["measurement_unit_code"];
			$measurement_unit_qualifier_code	= $row["measurement_unit_qualifier_code"];

			$duty = new duty;
			$duty->set_properties($goods_nomenclature_item_id, "", "", "", $duty_expression_id, $duty_amount,
			$monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code, $measure_sid, "", "", "", "");
			array_push($duty_list, $duty);
		}
	}

	// Secondly, get the measure components explicitly related to SIVs
	$sql = "SELECT mc.measure_sid, mcc.duty_amount FROM measure_conditions mc, measure_condition_components mcc, measures m
	WHERE mcc.measure_condition_sid = mc.measure_condition_sid
	AND m.measure_sid = mc.measure_sid
	AND mcc.duty_expression_id = '01' AND m.geographical_area_id = '" . $geographical_area_id . "' 
	ORDER BY m.measure_sid, component_sequence_number";
	$result = pg_query($conn, $sql);
	$siv_component_list = array();
	if  (($result) && (pg_num_rows($result) > 0)) {
		while ($row = pg_fetch_array($result)) {
			$measure_sid    = $row['measure_sid'];
			$duty_amount	= $row['duty_amount'];
			$siv_component = new siv_component;
			$siv_component->set_properties($measure_sid, $duty_amount);
			array_push($siv_component_list, $siv_component);
		}
	}

	// Get the measures
	if ($measure_scope == "all") {
		$measure_scope_clause = "";
	} else {
		$measure_scope_clause = " AND m.measure_type_id IN ('142', '143', '145', '146') ";
	}
	if ($sort == "commodity") {
		$sort_clause = "ORDER BY goods_nomenclature_item_id, validity_start_date DESC, validity_end_date DESC";
	} else {
		$sort_clause = "ORDER BY validity_start_date DESC, validity_end_date DESC, goods_nomenclature_item_id";
	}
	if ($currency == "current") {
		$currency_clause = " AND (m.validity_end_date IS NULL OR m.validity_end_date > CURRENT_DATE) ";
	} else {
		$currency_clause = "";
	}
	$sql = "SELECT m.measure_sid, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date, m.geographical_area_id,
	m.measure_type_id, m.measure_generating_regulation_id, g.description as geographical_area_description,
	mtd.description as measure_type_description, m.ordernumber, measure_component_applicable_code
	FROM ml.measures_real_end_dates m, ml.ml_geographical_areas g, measure_type_descriptions mtd, measure_types mt
	WHERE m.geographical_area_id = g.geographical_area_id
	AND m.measure_type_id = mt.measure_type_id
	AND mtd.measure_type_id = m.measure_type_id " . $currency_clause . "
	AND m.geographical_area_id = '" . $geographical_area_id . "' " . $measure_scope_clause . $sort_clause;
	//echo ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
			<p>There are <strong><?=pg_num_rows($result)?></strong> matching measures.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:10%">SID</th>
					<th class="govuk-table__header" style="width:14%">Commodity</th>
					<th class="govuk-table__header" style="width:9%">Start date</th>
					<th class="govuk-table__header" style="width:9%">End date</th>
					<th class="govuk-table__header" style="width:14%">Geographical area</th>
					<th class="govuk-table__header" style="width:18%">Type</th>
					<th class="govuk-table__header" style="width:8%">Regulation&nbsp;ID</th>
					<th class="govuk-table__header" style="width:8%">Order number</th>
					<th class="govuk-table__header r" style="width:12%">Duty</th>
				</tr>

<?php
		$measure_list = [];
		while ($row = pg_fetch_array($result)) {
			$measure_sid                = $row['measure_sid'];
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$validity_start_date        = short_date($row['validity_start_date']);
			$validity_end_date          = short_date($row['validity_end_date']);
			$rowclass                   = rowclass($validity_start_date, $validity_end_date);

			$ordernumber                    	= $row['ordernumber'];
			$measure_type_id                	= $row['measure_type_id'];
			$geographical_area_id           	= $row['geographical_area_id'];
			$regulation_id_full             	= $row['measure_generating_regulation_id'];
			$geographical_area_description  	= $row['geographical_area_description'];
			$measure_type_description       	= $row['measure_type_description'];
			$measure_component_applicable_code	= $row['measure_component_applicable_code'];
			$commodity_url                  = "/goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
			$measure = new measure;
			$measure->set_properties($measure_sid, $goods_nomenclature_item_id, "", "", "", "", "", "", "", "", "");
			
			if ($measure_component_applicable_code != 2) {
				if (count($duty_list) > 0) {
					foreach ($duty_list as $d){
						if ($d->measure_sid == $measure_sid) {
							array_push($measure->duty_list, $d);
						}
					}
				}
				$measure->combine_duties();	

				if ($measure->combined_duty == "") {
					// Assign the relevant SIV components to the measures
					if (count($siv_component_list) > 0) {
						foreach ($siv_component_list as $s){
							if ($s->measure_sid == $measure_sid) {
								array_push($measure->siv_component_list, $s);
							}
						}
					}
					$measure->get_siv_specific();
				}

			}
			array_push($measure_list, $measure);
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell"><a class="nodecorate" href="<?=$commodity_url?>"><?=format_commodity_code($goods_nomenclature_item_id)?></a></td>
					<td class="govuk-table__cell" nowrap><?=$validity_start_date?></td>
					<td class="govuk-table__cell" nowrap><?=$validity_end_date?></td>
					<td class="govuk-table__cell"><?=$geographical_area_id?> (<?=$geographical_area_description?>)</td>
					<td class="govuk-table__cell"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?> - <?=$measure_type_description?></a></td>
					<td class="govuk-table__cell"><a href="regulation_view.html?base_regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
					<td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$ordernumber?>"><?=$ordernumber?></a></td>
					<td class="govuk-table__cell r"><span id="measure_<?=$measure_sid?>"></span></td>
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



			<form action="/actions/geographical_area_actions.html#measures" method="get" class="inline_form">
			<h3>Filter results</h3>
			<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />
			<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
			<input type="hidden" name="phase" value="measure_filter_geographical_area_members" />


			<!-- Currency //-->
			<div class="column-one-third" style="width:230px;margin-bottom:1em;">
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($member_currency, "all") ?> type="radio" class="govuk-radios__input" name="member_currency" id="member_currency_all" value="all" />
						<label class="govuk-label govuk-radios__label" for="member_currency_all">Show all</label>
					</div>
				</div><br/>
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input <?=checked($member_currency, "current") ?> type="radio" class="govuk-radios__input" name="member_currency" id="member_currency_current" value="current" />
						<label class="govuk-label govuk-radios__label" for="member_currency_current">Show only current</label>
					</div>
				</div>
			</div>


			<div class="column-one-third" style="width:130px">
				<div class="govuk-form-group" style="padding:0px;margin:0px">
					<button type="submit" class="govuk-button" style="xmargin-top:54px">Update</button>
				</div>
			</div>
			<div class="clearer"><!--&nbsp;//--></div>
			</form>


			<p>The table below lists the countries that are / have been members of this area group.
			You are able to terminate existing memberships if that membership has already started
			or you can delete memberships entirely if they are yet to begin.</p>

<?php
	$sql = "SELECT child_sid, child_id, child_description, validity_start_date, validity_end_date
	FROM ml.ml_geo_memberships WHERE parent_id = '" . $geographical_area_id . "'";
	if ($member_currency == "current") {
		$sql .= " AND (validity_end_date::date > CURRENT_DATE OR validity_end_date IS NULL) ";
	}
	$sql .= " ORDER BY 3";
	//print ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {

?>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:10%">Child ID</th>
					<th class="govuk-table__header" style="width:10%">Child SID</th>
					<th class="govuk-table__header" style="width:50%">Description</th>
					<th class="govuk-table__header" style="width:10%">Validity start date</th>
					<th class="govuk-table__header" style="width:10%">Validity end date</th>
					<th class="govuk-table__header" style="width:10%">Actions</th>
				</tr>
<p>There are <strong><?=pg_num_rows($result)?></strong> members of this geographical area group.</p>
<?php
			while ($row = pg_fetch_array($result)) {
			$child_id               = $row['child_id'];
			$child_sid              = $row['child_sid'];
			$child_description      = $row['child_description'];
			$validity_start_date    = short_date($row['validity_start_date']);
			$validity_end_date      = short_date($row['validity_end_date']);
			$validity_start_date2   = string_to_date($row['validity_start_date']);

?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$child_id?>"><?=$child_id?></a></td>
					<td class="govuk-table__cell"><?=$child_sid?></td>
					<td class="govuk-table__cell"><?=$child_description?></td>
					<td class="govuk-table__cell"><?=$validity_start_date?></td>
					<td class="govuk-table__cell"><?=$validity_end_date?></td>
					<td class="govuk-table__cell">
<?php
	if ($validity_end_date == "-") {
?>
						<form action="/actions/geographicaL_area_actions.html" method="get">
							<input type="hidden" name="geographical_area_group_sid" value="<?=$geographical_area_sid?>" />
							<input type="hidden" name="geographical_area_group_id" value="<?=$geographical_area_id?>" />
							<input type="hidden" name="geographical_area_sid" value="<?=$child_sid?>" />
							<input type="hidden" name="geographical_area_id" value="<?=$child_id?>" />
<?php
		if (is_in_future($validity_start_date2) == true) {
?>
							<input type="hidden" name="phase" value="delete_membership" />
							<button type="submit" class="govuk-button btn_nomargin")>Delete</button>
<?php
		} else {
?>
							<input type="hidden" name="phase" value="terminate_membership" />
							<button type="submit" class="govuk-button btn_nomargin")>Terminate</button>
<?php
		}
?>
						</form>
<?php
	}
?>
					</td>
				</tr>
<?php
		}
	}
?>
			</table>
			<form action="/actions/geographical_area_actions.html" method="get">
				<input type="hidden" name="geographical_area_id" id="geographical_area_id" value="<?=$geographical_area_id?>" />
				<input type="hidden" name="geographical_area_sid" id="geographical_area_sid" value="<?=$geographical_area_sid?>" />
				<input type="hidden" name="phase" id="phase" value="add_member" />
				<button type="submit" class="govuk-button" style="margin:0px">Add member to this geographical area</button>
				<!--<p>sfsdf</p>//-->
			</form>
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
			$validity_start_date    = short_date($row['validity_start_date']);
			$validity_end_date      = short_date($row['validity_end_date']);

?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$parent_id?>"><?=$parent_id?></a></td>
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
<script type="text/javascript">
$( document ).ready(function() {

<?php
	foreach ($measure_list as $m) {
?>
	$("span#measure_<?=$m->measure_sid?>").html("<?=$m->combined_duty?>");
<?php
	}
?>
});
</script>

<?php
	require ("includes/footer.php");

	function checked($a, $b) {
		if ($a == $b) {
			return (" checked");
		} else {
			return ("");
		}
	}
?>