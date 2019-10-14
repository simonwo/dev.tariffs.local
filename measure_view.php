<?php
    $title = "View measure";
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
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
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
	$valid = true;
	if ($result) {
		if (pg_num_rows($result) != 0){
			$row = pg_fetch_row($result);
			$goods_nomenclature_item_id = $row[0];
			$geographical_area_id       = $row[1];
			$url = "https://www.trade-tariff.service.gov.uk/trade-tariff/commodities/" . $goods_nomenclature_item_id;
			if ($geographical_area_id != "1011") {
				$url .= "?country=" . $geographical_area_id;
			}
			$url .= "#import";
		} else {
			$valid = false;
			echo ("<div class='warning'><p><strong>Warning</strong><br />This measure cannot be found.</p></div>");
		}
	}

	if ($valid == true) {
?>

	<h2>Page content</h2>
	<ul class="tariff_menu">
		<li><a href="#measure_details">Measure details</a></li>
		<li><a href="#oplog">Oplog entries</a></li>
		<li><a href="#measure_components">Measure components</a></li>
		<li><a href="#measure_conditions">Measure conditions</a></li>
		<li><a href="#measure_footnotes">Measure footnotes</a></li>
		<li><a href="#measure_excluded_geographical_areas">Excluded geographical areas</a></li>
		<li><a title="Opens in new window" href="<?=$url?>" target="_blank" href="#usage_measures">View commodity in Trade Tariff Service</a></li>
	</ul>

	<h2 id="measure_details">Measure details</h2>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad" style="width:25%">Item</th>
			<th class="govuk-table__header" style="width:75%">Value</th>
		</tr>

<?php
	$sql = "select distinct on (m.measure_type_id)
	m.measure_type_id, m.geographical_area_id, m.goods_nomenclature_item_id, m.validity_start_date,
	m.validity_end_date, measure_generating_regulation_role, measure_generating_regulation_id,
	justification_regulation_role, justification_regulation_id, stopped_flag, ordernumber,
	additional_code_type_id, additional_code_id, reduction_indicator, mtd.description as measure_type_description,
	ga.description as geographical_area_description, rrtd.description as regulation_role_type_description,
	rrtd2.description as justification_role_type_description, g.validity_start_date as commodity_start_date,
	g.validity_end_date as commodity_end_date, gnd.description as goods_nomenclature_description
	FROM goods_nomenclature_descriptions gnd, ml.ml_geographical_areas ga, measure_type_descriptions mtd,
	regulation_role_type_descriptions as rrtd, goods_nomenclatures g, ml.measures_real_end_dates m
	LEFT JOIN regulation_role_type_descriptions as rrtd2
	ON CAST(rrtd2.regulation_role_type_id as INTEGER) = CAST(m.justification_regulation_role as INTEGER)
	WHERE measure_sid = " . $measure_sid . "
	and g.goods_nomenclature_item_id = gnd.goods_nomenclature_item_id
	and g.producline_suffix = gnd.productline_suffix
	AND m.measure_type_id = mtd.measure_type_id AND m.geographical_area_id = ga.geographical_area_id
	AND CAST(rrtd.regulation_role_type_id as INTEGER) = CAST(m.measure_generating_regulation_role as INTEGER)
	and g.goods_nomenclature_item_id = m.goods_nomenclature_item_id and g.producline_suffix = '80'
	order by m.measure_type_id, gnd.goods_nomenclature_description_period_sid desc
	";

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
			$commodity_start_date                   = $row['commodity_start_date'];
			$commodity_end_date                     = $row['commodity_end_date'];
			$goods_nomenclature_description			= $row['goods_nomenclature_description'];

			if ($justification_regulation_id == "") {
				$justification_regulation_show = "";
			} else{
				$justification_regulation_show = '<a href="regulation_view.html?base_regulation_id=' . $justification_regulation_id .'">' .
				$justification_regulation_id . '</a> (Role type ' . $justification_regulation_role . ' - ' . $justification_role_type_description .')';
			}
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Goods nomenclature item ID</td>
			<td class="govuk-table__cell">
				<a href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>">
					<?=$goods_nomenclature_item_id?>
				</a>
				(dated <?=short_date($commodity_start_date)?> to <?=short_date($commodity_end_date)?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>#hierarchy">
					Show commodity code in hierarchy
				</a>
			</td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Goods nomenclature description</td>
			<td class="govuk-table__cell"><?=$goods_nomenclature_description?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Measure type ID</td>
			<td class="govuk-table__cell"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?> - <?=$measure_type_description?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Duty</td>
			<td class="govuk-table__cell"><?=$measure->combined_duty?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Geographical area ID</td>
			<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?> - <?=$geographical_area_description?></a></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Validity start date</td>
			<td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Validity end date</td>
			<td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Measure generating regulation</td>
			<td class="govuk-table__cell">
					<a href="regulation_view.html?base_regulation_id=<?=$measure_generating_regulation_id?>">
						<?=$measure_generating_regulation_id?></a>
					(Role type <?=$measure_generating_regulation_role?> - <?=$regulation_role_type_description?>)
					
				</td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Justification regulation</td>
			<td class="govuk-table__cell"><?=$justification_regulation_show?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Quota order number ID</td>
			<td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>"><?=$ordernumber?></a></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Additional code</td>
			<td class="govuk-table__cell"><?=$additional_code_type_id?><?=$additional_code_id?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Stopped flag</td>
			<td class="govuk-table__cell"><?=$stopped_flag?></td>
		</tr>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad">Reduction indicator</td>
			<td class="govuk-table__cell"><?=$reduction_indicator?></td>
		</tr>
<?php
		}
	}
?>
		</table>
		<p class="back_to_top"><a href="#top">Back to top</a></p>

		<h2 id="oplog">Oplog entries</h2>
		<?php
	$sql = "select measure_type_id, geographical_area_id, goods_nomenclature_item_id,
	validity_start_date, validity_end_date, measure_generating_regulation_id,
	justification_regulation_id, ordernumber, additional_code_type_id, additional_code_id,
	operation, operation_date
	from measures_oplog
	where measure_sid = " . $measure_sid . "
	order by operation_date desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad small">Measure type</th>
			<th class="govuk-table__header small">Geography</th>
			<th class="govuk-table__header small">Commodity</th>
			<th class="govuk-table__header small">Start date</th>
			<th class="govuk-table__header small">End date</th>
			<th class="govuk-table__header small">Meas. reg</th>
			<th class="govuk-table__header small">Just. reg</th>
			<th class="govuk-table__header small">Order no.</th>
			<th class="govuk-table__header small">Add. code</th>
			<th class="govuk-table__header small">Operation</th>
			<th class="govuk-table__header small">Op. date</th>
		</tr>

<?php        
		while ($row = pg_fetch_array($result)) {
			$measure_type_id					= $row['measure_type_id'];
			$geographical_area_id				= $row['geographical_area_id'];
			$goods_nomenclature_item_id			= $row['goods_nomenclature_item_id'];
			$validity_start_date				= $row['validity_start_date'];
			$validity_end_date					= $row['validity_end_date'];
			$measure_generating_regulation_id	= $row['measure_generating_regulation_id'];
			$justification_regulation_id		= $row['justification_regulation_id'];
			$ordernumber						= $row['ordernumber'];
			$additional_code_type_id			= $row['additional_code_type_id'];
			$additional_code_id					= $row['additional_code_id'];
			$operation							= $row['operation'];
			$operation_date						= $row['operation_date'];
?>
			<tr class="govuk-table__row">
				<td class="govuk-table__cell nopad small"><?=$measure_type_id?></td>
				<td class="govuk-table__cell small"><?=$geographical_area_id?></td>
				<td class="govuk-table__cell small"><?=$goods_nomenclature_item_id?></td>
				<td class="govuk-table__cell small"><?=short_date($validity_start_date)?></td>
				<td class="govuk-table__cell small"><?=short_date($validity_end_date)?></td>
				<td class="govuk-table__cell small"><?=$measure_generating_regulation_id?></td>
				<td class="govuk-table__cell small"><?=$justification_regulation_id?></td>
				<td class="govuk-table__cell small"><?=$ordernumber?></td>
				<td class="govuk-table__cell small"><?=$additional_code_type_id?><?=$additional_code_id?></td>
				<td class="govuk-table__cell small"><?=$operation?></td>
				<td class="govuk-table__cell small"><?=short_date($operation_date)?></td>
			</tr>
	
	<?php
			}
	?>
		</table>
<?php
	}
?>
		<p class="back_to_top"><a href="#top">Back to top</a></p>

		<h2 id="measure_components">Measure components</h2>
		<p>The following components are assigned to this measure.</p>
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
			<th class="govuk-table__header nopad" style="width:15%">Duty expression</th>
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
			<td class="govuk-table__cell nopad"><?=$duty_expression_id?></td>
			<td class="govuk-table__cell"><?=number_format($duty_amount, 3)?></td>
			<td class="govuk-table__cell"><?=$monetary_unit_code?></td>
			<td class="govuk-table__cell"><?=$measurement_unit_show?><?php if ($measurement_unit_show != "") { echo ("&nbsp;/&nbsp;"); } ?><?=$measurement_unit_qualifier_show?></td>
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

<!-- Measure conditions //-->
		<h2 id="measure_conditions">Measure conditions</h2>
		<p>The following conditions apply to this measure.</p>
<?php
	$sql = "SELECT mc.measure_condition_sid, mc.condition_code, mc.component_sequence_number, mc.condition_duty_amount,
	mc.condition_monetary_unit_code, mc.condition_measurement_unit_code, mc.condition_measurement_unit_qualifier_code,
	mc.action_code, mc.certificate_type_code, mc.certificate_code, mccd.description as condition_code_description, mad.description as action_code_description
	FROM measure_condition_code_descriptions mccd, measure_conditions mc
	LEFT OUTER JOIN measure_action_descriptions mad
	ON mc.action_code = mad.action_code WHERE measure_sid = " . $measure_sid . "
	AND mc.condition_code = mccd.condition_code ORDER BY condition_code, component_sequence_number";
	$result = pg_query($conn, $sql);
	$row_count = pg_num_rows($result);
	if (($result) && ($row_count > 0)) {
?>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row" valign="bottom">
			<th class="govuk-table__header nopad" style="width:8%">SID</th>
			<th class="govuk-table__header" style="width:12%">Condition code</th>
			<th class="govuk-table__header c" style="width:10%">Duty amount</th>
			<th class="govuk-table__header c" style="width:10%">Monetary unit code</th>
			<th class="govuk-table__header c" style="width:20%">Measurement unit code / qualifier code</th>
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
			<td class="govuk-table__cell nopad"><?=$measure_condition_sid?></td>
			<td class="govuk-table__cell"><?=$condition_code_show?></td>
			<td class="govuk-table__cell c"><?=duty_format($duty_amount)?></td>
			<td class="govuk-table__cell c"><?=$monetary_unit_code?></td>
			<td class="govuk-table__cell c"><?=$measurement_unit_code?> <?=$measurement_unit_qualifier_code?></td>
			<td class="govuk-table__cell"><?=$action_code_show?></td>
<?php
	if ($certificate_type_code != "") {
?>		
			<td class="govuk-table__cell"><a href="certificate_view.html?certificate_type_code=<?=$certificate_type_code?>&certificate_code=<?=$certificate_code?>"><?=$certificate_type_code?><?=$certificate_code?></a></td>
<?php
	} else {
?>		
			<td class="govuk-table__cell">&nbsp;</td>
<?php
	}
?>		
		</tr>

<?php
		}
?>
	</table>
<?php
	} else {
?>
	<p>There are no conditions associated with this measure.</p>
<?php		
	}
?>
	<p class="back_to_top"><a href="#top">Back to top</a></p>

<!-- Footnotes //-->
<h2 id="measure_footnotes">Footnotes</h2>


<?php
	$sql = "select f.footnote_type_id, f.footnote_id, description
	from footnote_association_measures fam, ml.ml_footnotes f 
	where fam.footnote_id = f.footnote_id and fam.footnote_type_id = f.footnote_type_id
	and fam.measure_sid = " . $measure_sid . "
	order by 1, 2";
	$result = pg_query($conn, $sql);
	if  (($result) and (pg_num_rows($result) > 0)) {
?>
	<p>The following footnotes are associated with this measure.</p>
	<table cellspacing="0" class="govuk-table">
		<tr class="govuk-table__row">
			<th class="govuk-table__header nopad" style="width:10%">Footnote</th>
			<th class="govuk-table__header" style="width:90%">Description</th>
		</tr>

<?php        
		while ($row = pg_fetch_array($result)) {
			$footnote_type_id	= $row['footnote_type_id'];
			$footnote_id		= $row['footnote_id'];
			$description        = $row['description'];
?>
		<tr class="govuk-table__row">
			<td class="govuk-table__cell nopad"><a href="footnote_view.html?footnote_type_id=<?=$footnote_type_id?>&footnote_id=<?=$footnote_id?>"><?=$footnote_type_id?><?=$footnote_id?></a></td>
			<td class="govuk-table__cell"><?=$description?></td>
		</tr>

<?php
		}
?>
	</table>
<?php
	} else {
?>
<p>There are no foonotes associated with this measure.</p>
<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php		
	}
?>

<!-- Excluded geographical areas //-->

		<h2 id="measure_excluded_geographical_areas">Excluded geographical areas</h2>
<?php
	$sql = "SELECT x.excluded_geographical_area, description
	FROM measure_excluded_geographical_areas x, ml.ml_geographical_areas ga
	WHERE x.excluded_geographical_area = ga.geographical_area_id
	AND measure_sid = " . $measure_sid . "
	ORDER BY excluded_geographical_area";
	$result = pg_query($conn, $sql);
	$row_count = pg_num_rows($result);
	if (($result) && ($row_count > 0)) {
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
	} else {
?>
<p>There are no excluded geographical areas associated to this measure.</p>
<?php		
	}
?>	
	<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
}
?>
</div>

<?php
	require ("includes/footer.php")
?>