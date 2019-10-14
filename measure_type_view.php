<?php
    $title = "View measure type";
	require ("includes/db.php");
	$measure_type_id = get_querystring("measure_type_id");
	$measure_type = new measure_type;
	$measure_type->clear_cookies();
	require ("includes/header.php");
?>
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/measure_types.html">Measure types</a>
			</li>
		</ol>
	</div>
	<div class="app-content__header">
		<h1 class="govuk-heading-xl">View measure type <?=$measure_type_id?></h1>
	</div>

		<h2 class="nomargin">Measure type details</h2>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header nopad" style="width:30%">Item</th>
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
			$validity_start_date                = short_date($row['validity_start_date']);
			$validity_end_date                  = short_date($row['validity_end_date']);
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
				<td class="govuk-table__cell nopad">Description</td>
				<td class="govuk-table__cell b"><?=$description?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Measure type ID</td>
				<td class="govuk-table__cell"><?=$measure_type_id?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Validity start date</td>
				<td class="govuk-table__cell"><?=$validity_start_date?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Validity end date</td>
				<td class="govuk-table__cell"><?=$validity_end_date?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Trade movement code</td>
				<td class="govuk-table__cell"><?=$trade_movement_code?> - <?=trade_movement_code($trade_movement_code)?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Priority code</td>
				<td class="govuk-table__cell"><?=$priority_code?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Measure component applicable code</td>
				<td class="govuk-table__cell"><?=$measure_component_applicable_code?> - <?=measure_component_applicable_code($measure_component_applicable_code)?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Origin dest code</td>
				<td class="govuk-table__cell"><?=$origin_dest_code?> - <?=origin_dest_code($origin_dest_code)?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Order number capture code</td>
				<td class="govuk-table__cell"><?=$order_number_capture_code?> - <?=order_number_capture_code($order_number_capture_code)?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Explosion level</td>
				<td class="govuk-table__cell"><?=$measure_explosion_level?></td>
			</tr>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad">Measure type series ID</td>
				<td class="govuk-table__cell"><?=$measure_type_series_id?> - <?=$measure_type_series_description?></td>
			</tr>
<?php
		}
	}
?>

		</table>
		<form action="/actions/measure_type_actions.html" method="get" class="inline_form">
	<input type="hidden" name="phase" value="show_edit_measure_type_form" />
	<input type="hidden" name="measure_type_id" value="<?=$measure_type_id?>" />
	<h3>Edit this measure type</h3>
	<p>Click on this button to edit this measure type.</p>
	<div class="column-one-third" style="width:320px">
		<div class="govuk-form-group" style="padding:0px;margin:0px">
		<button type="submit" class="govuk-button">Edit measure type</button>
		</div>
	</div>
	<div class="clearer"><!--&nbsp;//--></div>
</form>

<form action="/actions/measure_type_actions.html" method="get" class="inline_form">
	<input type="hidden" name="phase" value="terminate_measures" />
	<input type="hidden" name="measure_type_id" value="<?=$measure_type_id?>" />
	<h3>Terminate measures</h3>
	<p>Click on this button to terminate all current and future measures of this type. Warning - this cannot be undone.</p>
	<div class="column-one-third" style="width:320px">
		<div class="govuk-form-group" style="padding:0px;margin:0px">
		<button type="submit" class="govuk-button">Terminate measures of this type</button>
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
	AND m.measure_type_id = '" . $measure_type_id . "' ORDER BY m.measure_sid, mc.duty_expression_id";

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
	AND mcc.duty_expression_id = '01'
	AND m.measure_type_id = '" . $measure_type_id . "'";
	$sql .= "ORDER BY m.measure_sid, component_sequence_number";
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

	$sql = "SELECT measure_sid, goods_nomenclature_item_id, measure_generating_regulation_id,
	additional_code_type_id, additional_code_id, measure_type_id,
	geographical_area_id, validity_start_date, validity_end_date, ordernumber
	FROM ml.measures_real_end_dates WHERE measure_type_id = '" . $measure_type_id . "' ORDER BY validity_start_date DESC, goods_nomenclature_item_id";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<h2 class="nomargin">Measures of type <?=$description?></h2>
<p>There are <strong><?=pg_num_rows($result)?></strong> measures of type <?=$description?>.  Please click here to <a target="_blank" href="measure_export.html?measure_type_id=<?=$measure_type_id?>">export these measures to CSV</a>.</p>
<?php
	if (pg_num_rows($result) == 0){
?>
<p>There are no measures of this type.</p>
<?php
	} else {
?>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header nopad" style="width:10%">Measure SID</th>
				<th class="govuk-table__header" style="width:8%">Commodity</th>
				<th class="govuk-table__header c" style="width:8%">Additional code</th>
				<th class="govuk-table__header c" style="width:10%">Geographical area</th>
				<th class="govuk-table__header" style="width:8%">Start date</th>
				<th class="govuk-table__header" style="width:8%">End date</th>
				<th class="govuk-table__header" style="width:10%">Regulation</th>
				<th class="govuk-table__header" style="width:10%">Order number</th>
				<th class="govuk-table__header r" style="width:28%">Duty</th>
			</tr>
<?php
		$measure_list = [];
		while ($row = pg_fetch_array($result)) {
			$measure_sid                		= $row['measure_sid'];
			$goods_nomenclature_item_id 		= $row['goods_nomenclature_item_id'];
			$measure_generating_regulation_id	= $row['measure_generating_regulation_id'];
			$additional_code_type_id    		= $row['additional_code_type_id'];
			$additional_code_id         		= $row['additional_code_id'];
			$measure_type_id            		= $row['measure_type_id'];
			$geographical_area_id       		= $row['geographical_area_id'];
			$ordernumber                		= $row['ordernumber'];
			$validity_start_date        		= short_date($row['validity_start_date']);
			$validity_end_date          		= short_date($row['validity_end_date']);
			if ($additional_code_type_id != "") {
				$additional_code_show = $additional_code_type_id . " / " . $additional_code_id;
			} else {
				$additional_code_show = "";
			}
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
			$rowclass = rowclass($validity_start_date, $validity_end_date);
?>
			<tr class="govuk-table__row <?=$rowclass?>">
				<td class="govuk-table__cell nopad"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
				<td class="govuk-table__cell"><a href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>"><?=$goods_nomenclature_item_id?></a></td>
				<td class="govuk-table__cell c"><?=$additional_code_show?></td>
				<td class="govuk-table__cell c"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
				<td class="govuk-table__cell"><?=$validity_start_date?></td>
				<td class="govuk-table__cell"><?=$validity_end_date?></td>
				<td class="govuk-table__cell"><a href="regulation_view.html?base_regulation_id=<?=$measure_generating_regulation_id?>"><?=$measure_generating_regulation_id?></a></td>
				<td class="govuk-table__cell"><?=$ordernumber?></td>
				<td class="govuk-table__cell r"><span id="measure_<?=$measure_sid?>"></span></td>
			</tr>
<?php
		}
?>
		</table>
<?php
		}
	}
?>
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
	require ("includes/footer.php")
?>