<?php
	require ("includes/db.php");
	require ("includes/header.php");
	$measure_sid    = get_querystring("measure_sid");
	$measure        = get_measure($measure_sid);
?>
<div id="wrapper" class="direction-ltr">
	<!-- Start breadcrumbs //-->
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Home</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">Measures</li>
		</ol>
	</div>
	<!-- End breadcrumbs //-->
	<div class="app-content__header">
		<h1 class="govuk-heading-xl">Measure <?=$measure_sid?></h1>
	</div>

<!-- MENU //-->
<?php
	$sql = "SELECT goods_nomenclature_item_id, geographical_area_id FROM measures m WHERE measure_sid = " . $measure_sid;
	$result = pg_query($conn, $sql);
	if ($result) {
		$row = pg_fetch_row($result);
		$goods_nomenclature_item_id = $row[0];
		$geographical_area_id       = $row[1];
		$url = "https://www.trade-tariff.service.gov.uk/trade-tariff/commodities/" . $goods_nomenclature_item_id;
		if ($geographical_area_id != "1011") {
			$url .= "?country=" . $geographical_area_id;
		}
		$url .= "#import";
	}
?>

	<h2>Page content</h2>
	<ul class="tariff_menu">
		<li><a href="#measure_details">Measure details</a></li>
		<li><a href="#measure_components">Measure components</a></li>
		<li><a href="#measure_conditions">Measure conditions</a></li>
		<li><a href="#measure_excluded_geographical_areas">Excluded geographical areas</a></li>
		<li><a title="Opens in new window" href="<?=$url?>" target="_blank" href="#usage_measures">View commodity in Trade Tariff Service</a></li>
	</ul>

	<h2 id="measure_details">Measure details</h2>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header" style="width:25%">Item</th>
			<th class="govuk-table__header" style="width:75%">Value</th>
		</tr>

<?php
	$sql = "SELECT m.measure_type_id, m.geographical_area_id, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date,
	measure_generating_regulation_role, measure_generating_regulation_id, justification_regulation_role,
	justification_regulation_id, stopped_flag, ordernumber, additional_code_type_id, additional_code_id,
	reduction_indicator, mtd.description as measure_type_description, ga.description as geographical_area_description,
	rrtd.description as regulation_role_type_description, rrtd2.description as justification_role_type_description
	FROM ml.ml_geographical_areas ga, measure_type_descriptions mtd, regulation_role_type_descriptions as rrtd, measures m
	LEFT JOIN regulation_role_type_descriptions as rrtd2 ON CAST(rrtd2.regulation_role_type_id as INTEGER) = CAST(m.justification_regulation_role as INTEGER)
	WHERE measure_sid = " . $measure_sid . "
	AND m.measure_type_id = mtd.measure_type_id
	AND m.geographical_area_id = ga.geographical_area_id
	AND CAST(rrtd.regulation_role_type_id as INTEGER) = CAST(m.measure_generating_regulation_role as INTEGER)";
	#echo ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$measure_type_id                        = $row['measure_type_id'];
			$geographical_area_id                   = $row['geographical_area_id'];
			$goods_nomenclature_item_id             = $row['goods_nomenclature_item_id'];
			$validity_start_date                    = $row['validity_start_date'];
			$validity_end_date                      = $row['validity_end_date'];
			$measure_generating_regulation_role     = $row['measure_generating_regulation_role'];
			$measure_generating_regulation_id       = $row['measure_generating_regulation_id'];
			$justification_regulation_role          = $row['justification_regulation_role'];
			$justification_regulation_id            = $row['justification_regulation_id'];
			$stopped_flag                           = $row['stopped_flag'];
			$ordernumber                            = $row['ordernumber'];
			$additional_code_type_id                = $row['additional_code_type_id'];
			$additional_code_id                     = $row['additional_code_id'];
			$reduction_indicator                    = $row['reduction_indicator'];
			$measure_type_description               = $row['measure_type_description'];
			$geographical_area_description          = $row['geographical_area_description'];
			$regulation_role_type_description       = $row['regulation_role_type_description'];
			$justification_role_type_description    = $row['justification_role_type_description'];

			if ($justification_regulation_id == "") {
				$justification_regulation_show = "";
			} else{
				$justification_regulation_show = '<a href="regulation_view.html?regulation_id=' . $justification_regulation_id .'">' .
				$justification_regulation_id . '</a> - Role type (' . $justification_regulation_role . ' - ' . $justification_role_type_description .')';
			}

?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Goods nomenclature item ID</td>
			<td class="govuk-table__cell"><a href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>"><?=$goods_nomenclature_item_id?></a></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Measure type ID</td>
			<td class="govuk-table__cell"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?> - <?=$measure_type_description?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Duty</td>
			<td class="govuk-table__cell"><?=$measure->combined_duty?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Geographical area ID</td>
			<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?> - <?=$geographical_area_description?></a></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Validity start date</td>
			<td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Validity end date</td>
			<td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Measure generating regulation</td>
			<td class="govuk-table__cell"><a href="regulation_view.html?regulation_id=<?=$measure_generating_regulation_id?>"><?=$measure_generating_regulation_id?></a> - Role type (<?=$measure_generating_regulation_role?> - <?=$regulation_role_type_description?>)</td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Justification regulation</td>
			<td class="govuk-table__cell"><?=$justification_regulation_show?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Quota order number ID</td>
			<td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>"><?=$ordernumber?></a></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Additional code</td>
			<td class="govuk-table__cell"><?=$additional_code_type_id?><?=$additional_code_id?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Stopped flag</td>
			<td class="govuk-table__cell"><?=$stopped_flag?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell">Reduction indicator</td>
			<td class="govuk-table__cell"><?=$reduction_indicator?></td>
		</tr>
<?php
		}
	}
?>
		</table>
		<p class="back_to_top"><a href="#top">Back to top</a></p>

		<h2 id="measure_components">Measure components</h2>
<?php
	$sql = "SELECT mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
	ded.description as duty_expression_description, mud.description as measurement_unit_description, muqd.description as measurement_unit_qualifier_description
	FROM duty_expression_descriptions ded, measurement_unit_qualifier_descriptions muqd RIGHT OUTER JOIN 
	measure_components mc ON mc.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
	LEFT OUTER JOIN measurement_unit_descriptions mud ON mc.measurement_unit_code = mud.measurement_unit_code
	WHERE measure_sid = " . $measure_sid . " AND ded.duty_expression_id = mc.duty_expression_id ORDER BY duty_expression_id";
	#print ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header" style="width:15%">Duty expression</th>
			<th class="govuk-table__header" style="width:15%">Duty amount</th>
			<th class="govuk-table__header" style="width:15%">Monetary unit code</th>
			<th class="govuk-table__header" style="width:38 %">Measurement unit code / qualifier code</th>
			<th class="govuk-table__header" style="width:17%">Actions</th>
		</tr>

<?php        
		while ($row = pg_fetch_array($result)) {
			$duty_expression_id                     = $row['duty_expression_id'];
			$duty_amount                            = $row['duty_amount'];
			$monetary_unit_code                     = $row['monetary_unit_code'];
			$measurement_unit_code                  = $row['measurement_unit_code'];
			$measurement_unit_qualifier_code        = $row['measurement_unit_qualifier_code'];
			$measurement_unit_description           = $row['measurement_unit_description'];
			$measurement_unit_qualifier_description = $row['measurement_unit_qualifier_description'];
			$measurement_unit_show = $measurement_unit_code;
			if ($measurement_unit_description != ""){
				$measurement_unit_show .= " - " . $measurement_unit_description;
			}
			$measurement_unit_qualifier_show = $measurement_unit_qualifier_code;
			if ($measurement_unit_qualifier_description != ""){
				$measurement_unit_qualifier_show .= " - " . $measurement_unit_qualifier_description;
			}
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell"><?=$duty_expression_id?></td>
			<td class="govuk-table__cell"><?=$duty_amount?></td>
			<td class="govuk-table__cell"><?=$monetary_unit_code?></td>
			<td class="govuk-table__cell"><?=$measurement_unit_show?>  /  <?=$measurement_unit_qualifier_show?></td>
			<td class="govuk-table__cell">
				<form action="actions/measure_actions.html" method="get" style="display:inline">
					<input type="hidden" name="action" value="edit_component" />
					<input type="hidden" name="measure_sid" value="<?=$measure_sid?>" />
					<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
					<button type="submit" class="govuk-button btn_nomargin")>Edit</button>
				</form>
				<form action="actions/measure_actions.html" method="get" style="display:inline">
					<input type="hidden" name="action" value="delete_component" />
					<input type="hidden" name="measure_sid" value="<?=$measure_sid?>" />
					<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
					<button type="submit" class="govuk-button btn_nomargin")>Delete</button>
				</form>
			</td>
		</tr>

<?php
		}
?>
	</table>
	<form action="actions/measure_actions.html" method="get">
		<input type="hidden" name="action" value="add_component" />
		<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
		<input type="hidden" name="measure_sid" value="<?=$measure_sid?>" />
		<button type="submit" class="govuk-button btn_nomargin" style="width:120px !important">Add component</button>
	</form>
	<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
	}
?>
		<h2 id="measure_conditions">Measure conditions</h2>
<?php
	$sql = "SELECT mc.measure_condition_sid, mc.condition_code, mc.component_sequence_number, mc.condition_duty_amount,
	mc.condition_monetary_unit_code, mc.condition_measurement_unit_code, mc.condition_measurement_unit_qualifier_code,
	mc.action_code, mc.certificate_type_code, mc.certificate_code, mccd.description as condition_code_description, mad.description as action_code_description
	FROM measure_condition_code_descriptions mccd, measure_conditions mc
	LEFT OUTER JOIN measure_action_descriptions mad
	ON mc.action_code = mad.action_code WHERE measure_sid = " . $measure_sid . "
	AND mc.condition_code = mccd.condition_code ORDER BY component_sequence_number";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header" style="width:8%">SID</th>
			<th class="govuk-table__header" style="width:12%">Condition code</th>
			<th class="govuk-table__header" style="width:10%">Duty amount</th>
			<th class="govuk-table__header" style="width:10%">Monetary unit code</th>
			<th class="govuk-table__header" style="width:20%">Measurement unit code / qualifier code</th>
			<th class="govuk-table__header" style="width:20%">Action code</th>
			<th class="govuk-table__header" style="width:20%">Certificate code</th>
		</tr>

<?php        
		while ($row = pg_fetch_array($result)) {
			$measure_condition_sid              = $row['measure_condition_sid'];
			$condition_code                     = $row['condition_code'];
			$component_sequence_number          = $row['component_sequence_number'];
			$duty_amount                        = $row['condition_duty_amount'];
			$monetary_unit_code                 = $row['condition_monetary_unit_code'];
			$measurement_unit_code              = $row['condition_measurement_unit_code'];
			$measurement_unit_qualifier_code    = $row['condition_measurement_unit_qualifier_code'];
			$action_code                        = $row['action_code'];
			$certificate_type_code              = $row['certificate_type_code'];
			$certificate_code                   = $row['certificate_code'];
			$condition_code_description         = $row['condition_code_description'];
			$action_code_description            = $row['action_code_description'];

			$action_code_show = $action_code;
			if ($action_code_description != ""){
				$action_code_show .= " - " . $action_code_description;
			}
			$condition_code_show = $condition_code;
			if ($condition_code_description != ""){
				$condition_code_show .= " - " . $condition_code_description;
			}
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell"><?=$measure_condition_sid?></td>
			<td class="govuk-table__cell"><?=$condition_code_show?></td>
			<td class="govuk-table__cell"><?=$duty_amount?></td>
			<td class="govuk-table__cell"><?=$monetary_unit_code?></td>
			<td class="govuk-table__cell"><?=$measurement_unit_code?> <?=$measurement_unit_qualifier_code?></td>
			<td class="govuk-table__cell"><?=$action_code_show?></td>
			<td class="govuk-table__cell"><?=$certificate_type_code?><?=$certificate_code?></td>
		</tr>

<?php
		}
?>
	</table>
	<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
	}
?>
		<h2 id="measure_excluded_geographical_areas">Excluded geographical areas</h2>
<?php
	$sql = "SELECT x.excluded_geographical_area, description
	FROM measure_excluded_geographical_areas x, ml.ml_geographical_areas ga
	WHERE x.excluded_geographical_area = ga.geographical_area_id
	AND measure_sid = " . $measure_sid . "
	ORDER BY excluded_geographical_area";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header" style="width:20%">Geographical area ID</th>
			<th class="govuk-table__header" style="width:80%">Geographical area</th>
		</tr>

<?php        
		while ($row = pg_fetch_array($result)) {
			$excluded_geographical_area	= $row['excluded_geographical_area'];
			$description                = $row['description'];
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell"><?=$excluded_geographical_area?></td>
			<td class="govuk-table__cell"><?=$description?></td>
		</tr>

<?php
		}
?>
	</table>
<?php
	}
?>	
	<p class="back_to_top"><a href="#top">Back to top</a></p>

</div>

<?php
	require ("includes/footer.php")
?>