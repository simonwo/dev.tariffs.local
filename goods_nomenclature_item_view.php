<?php
	require ("includes/db.php");
	require ("includes/header.php");
	require ("classes/goods_nomenclature.php");

	$goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
	$geographical_area_id       = get_querystring("geographical_area_id");
	$measure_type_id            = get_querystring("measure_type_id");
	$productline_suffix         = get_querystring("productline_suffix");
	if ($productline_suffix == "") {
		$productline_suffix = "80";
	}

	$obj_goods_nomenclature_item = new goods_nomenclature;
	$obj_goods_nomenclature_item->set_properties($goods_nomenclature_item_id, $productline_suffix, "", "");

?>
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Home</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/sections.php">Goods nomenclature section</a>
			</li>
		</ol>
	</div>

	<div class="app-content__header">
		<h1 class="govuk-heading-xl">View commodity <?=$goods_nomenclature_item_id?></h1>
	</div>
			<!-- MENU //-->
			<h2>Page content</h2>
			<ul class="tariff_menu">
				<li><a href="#details">Commodity code details</a></li>
				<li><a href="#hierarchy">Position in hierarchy</a></li>
				<li><a href="#historical">Historical descriptions</a></li>
				<li><a href="#assigned">Assigned measures</a></li>
				<li><a href="#inherited">Inherited measures</a></li>
				<li><a title="Opens in new window" href="https://www.trade-tariff.service.gov.uk/trade-tariff/commodities/<?=$goods_nomenclature_item_id?>#import" target="_blank" href="#usage_measures">View in Trade Tariff Service</a></li>
			</ul>

		<h2 id="details">Commodity code details</h2>
		<p>The table below shows the core details of this commodity code</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header" style="width:30%">Item</th>
				<th class="govuk-table__header" style="width:70%">Value</th>
			</tr>
<?php
	$sql = "SELECT gn.goods_nomenclature_item_id, gn.producline_suffix as productline_suffix, gn.validity_start_date, gn.validity_end_date, gnd1.description
	FROM goods_nomenclatures gn, goods_nomenclature_descriptions gnd1
	WHERE gn.goods_nomenclature_item_id = gnd1.goods_nomenclature_item_id AND gn.producline_suffix = gnd1.productline_suffix
	AND gn.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "' AND gn.producline_suffix = '" . $productline_suffix . "'
	AND  (gnd1.goods_nomenclature_description_period_sid IN ( SELECT max(gnd2.goods_nomenclature_description_period_sid) AS max
	FROM goods_nomenclature_descriptions gnd2
	WHERE gnd1.goods_nomenclature_item_id = gnd2.goods_nomenclature_item_id AND gnd1.productline_suffix = gnd2.productline_suffix))";
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$productline_suffix         = $row['productline_suffix'];
			$description                = $row['description'];
			$validity_start_date        = string_to_date($row['validity_start_date']);
			$validity_end_date          = string_to_date($row['validity_end_date']);
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Commodity code</td>
					<td class="govuk-table__cell b"><?=$goods_nomenclature_item_id?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Product line suffix</td>
					<td class="govuk-table__cell b"><?=$productline_suffix?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Description</td>
					<td class="govuk-table__cell"><?=$description?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Validity start date</td>
					<td class="govuk-table__cell"><?=$validity_start_date?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Validity end date</td>
					<td class="govuk-table__cell"><?=$validity_end_date?></td>
				</tr>
<?php
		}
	}
?>

			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="hierarchy">Position in hierarchy</h2>
			<p>The table below shows the position of this commodity code in the hierarchy.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th style="width:10%" class="govuk-table__header">Commodity</th>
					<th style="width:10%" class="govuk-table__header c">Suffix</th>
					<th style="width:10%" class="govuk-table__header c">Indents</th>
					<th style="width:70%" class="govuk-table__header">Description</th>
				</tr>
<?php
	$array      = $obj_goods_nomenclature_item->ar_hierarchies;
	$hier_count = sizeof($array);
	$parents    = array();
	$my_concat  = $goods_nomenclature_item_id . $productline_suffix;
	for($i = 0; $i < $hier_count; $i++) {
		$t      = $array[$i];
		$concat = $t->goods_nomenclature_item_id . $t->productline_suffix;
		$url    = "goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $t->goods_nomenclature_item_id . "&productline_suffix=" . $t->productline_suffix . "#hierarchy";
		$class  = "indent" . $t->number_indents;
		if (($t->goods_nomenclature_item_id == $goods_nomenclature_item_id) && ($t->productline_suffix == $productline_suffix)) {
			$class .= " selected";
		}
		if ($concat < $my_concat) {
			array_push ($parents, $t->goods_nomenclature_item_id);
		}

?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><a href="<?=$url?>"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id?></a></td>
					<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix?></td>
					<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->number_indents + 1?></td>
					<td class="govuk-table__cell <?=$class?>"><?=str_replace("|", " ", $obj_goods_nomenclature_item->ar_hierarchies[$i]->description)?></td>
				</tr>

<?php        
	}
	$parent_count = count($parents);
	$parent_string = "";
	for($i = 0; $i < $parent_count; $i++) {
		$parent_string .= "'" . $parents[$i] . "'";
		if ($i != $parent_count - 1) {
			$parent_string .= ",";
		}
	}
?>
			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="historical">Historical descriptions</h2>
			<p>The table below shows the historical descriptions for this commodity code.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:15%">Date</th>
					<th class="govuk-table__header" style="width:85%">Description</th>
				</tr>
<?php
	$sql = "SELECT gndp.validity_start_date, gnd.description FROM goods_nomenclature_description_periods gndp, goods_nomenclature_descriptions gnd
	WHERE gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
	AND gnd.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "' AND gnd.productline_suffix = '" . $productline_suffix . "' ORDER BY 1 DESC";
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$description                = str_replace("|", " ", $row['description']);
			$validity_start_date        = string_to_date($row['validity_start_date']);
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell b"><?=$validity_start_date?></td>
					<td class="govuk-table__cell"><?=$description?></td>
				</tr>
<?php
		}
	}
?>

			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="assigned">Assigned measures</h2>
			<p>The measures below have been directly assigned to this commodity code.</p>

			<form action="/actions/goods_nomenclature_actions.php" method="get" class="inline_form">
			<h3>Filter results</h3>
			<!--<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />//-->
			<input type="hidden" name="phase" value="goods_nomenclature_item_view_filter" />
			<input type="hidden" name="productline_suffix" value="<?=$productline_suffix?>" />
			<div class="column-one-third" style="width:200px">
				<div class="govuk-form-group">
					<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
						<span id="base_regulation_hint" class="govuk-hint">Filter by commodity code</span>
						<div class="govuk-date-input" id="measure_start">
							<div class="govuk-date-input__item">
								<div class="govuk-form-group" style="padding:0px;margin:0px">
									<input value="<?=$goods_nomenclature_item_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="goods_nomenclature_item_id" maxlength="14" name="goods_nomenclature_item_id" type="text">
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="column-one-third" style="width:200px">
				<div class="govuk-form-group">
					<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
						<span id="base_regulation_hint" class="govuk-hint">Filter by measure type ID</span>
						<div class="govuk-date-input" id="measure_start">
							<div class="govuk-date-input__item">
								<div class="govuk-form-group" style="padding:0px;margin:0px">
									<input value="<?=$measure_type_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="measure_type_id" maxlength="3" name="measure_type_id" type="text">
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="column-one-third" style="width:200px">
				<div class="govuk-form-group">
					<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
						<span id="base_regulation_hint" class="govuk-hint">Filter by geo. area ID</span>
						<div class="govuk-date-input" id="measure_start">
							<div class="govuk-date-input__item">
								<div class="govuk-form-group" style="padding:0px;margin:0px">
									<input value="<?=$geographical_area_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="geographical_area_id" maxlength="4" name="geographical_area_id" type="text">
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="column-one-third" style="width:200px">
				<div class="govuk-form-group" style="padding:0px;margin:0px">
					<!--<input type="submit" class="govuk-button" value="Search" />//-->
					<button type="submit" class="govuk-button" style="margin-top:36px">Search</button>
				</div>
			</div>
			<div class="clearer"><!--&nbsp;//--></div>
			</form>

<?php
	if ($productline_suffix == "80") {
?>        
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header">Measure SID</th>
					<th class="govuk-table__header">Commodity</th>
					<th class="govuk-table__header c">Measure type ID</th>
					<th class="govuk-table__header c">Geographical area ID</th>
					<th class="govuk-table__header c">Additional code</th>
					<th class="govuk-table__header c">Regulation</th>
					<th class="govuk-table__header r">Start date</th>
					<th class="govuk-table__header r">End date</th>
					<th class="govuk-table__header r">Order number</th>
					<th class="govuk-table__header r">Duty</th>
				</tr>
<?php
	// Firstly, get all the duties to put in the duty column
		$sql = "SELECT m.additional_code_type_id, m.additional_code_id, m.measure_type_id,
		mc.measure_sid, duty_expression_id, duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
		FROM measures m, measure_components mc WHERE m.measure_sid = mc.measure_sid
		AND m.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'";
		if ($geographical_area_id != "") {
			$sql .= " AND m.geographical_area_id = '" . $geographical_area_id . "' ";
		}
		$sql .= "ORDER BY m.measure_sid, mc.duty_expression_id";
		$result = pg_query($conn, $sql);
		$duty_list = array();
		if  (($result) && (pg_num_rows($result) > 0)) {
			while ($row = pg_fetch_array($result)) {
				$measure_sid                        = $row['measure_sid'];
				$additional_code_type_id            = $row['additional_code_type_id'];
				$additional_code_id                 = $row['additional_code_id'];
				$measure_type_ix                    = $row['measure_type_id'];
				$duty_expression_id                 = $row['duty_expression_id'];
				$duty_amount                        = $row['duty_amount'];
				$monetary_unit_code                 = $row['monetary_unit_code'];
				$measurement_unit_code              = $row['measurement_unit_code'];
				$measurement_unit_qualifier_code    = $row['measurement_unit_qualifier_code'];
				
				$duty = new duty;
				$duty->set_properties($goods_nomenclature_item_id, $additional_code_type_id, $additional_code_id, $measure_type_ix,
				$duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code,
				$measurement_unit_qualifier_code, $measure_sid, "", "", "", "");
				array_push($duty_list, $duty);
			}
		}

	// Secondly, get the measure components explicitly related to SIVs
	$sql = "SELECT mc.measure_sid, mcc.duty_amount FROM measure_conditions mc, measure_condition_components mcc, measures m
	WHERE mcc.measure_condition_sid = mc.measure_condition_sid
	AND m.measure_sid = mc.measure_sid
	AND mcc.duty_expression_id = '01'
	AND m.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'";
	if ($geographical_area_id != "") {
		$sql .= " AND m.geographical_area_id = '" . $geographical_area_id . "' ";
	}
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


	// Thirdly, get the measures
		$sql = "SELECT * FROM measures WHERE goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'";
		if ($geographical_area_id != "") {
			$sql .= " AND geographical_area_id = '" . $geographical_area_id . "'";
		}
		if ($measure_type_id != "") {
			$sql .= " AND measure_type_id = '" . $measure_type_id . "'";
		}
		$sql .= " ORDER BY validity_start_date DESC";
		#echo ($sql);
		#exit();

		$result = pg_query($conn, $sql);
		if  (($result) && (pg_num_rows($result) > 0)){
			$measure_list = array();
			while ($row = pg_fetch_array($result)) {
				$measure_sid                = $row['measure_sid'];
				$goods_nomenclature_item_ix = $row['goods_nomenclature_item_id'];
				$measure_type_id            = $row['measure_type_id'];
				$geographical_area_id       = $row['geographical_area_id'];
				$additional_code_type_id    = $row['additional_code_type_id'];
				$additional_code_id         = $row['additional_code_id'];
				$quota_order_number_id      = $row['ordernumber'];
				$regulation_id_full         = $row['measure_generating_regulation_id'];
				$validity_start_date        = string_to_date($row['validity_start_date']);
				$validity_end_date          = string_to_date($row['validity_end_date']);

				$measure = new measure;
				$measure->set_properties($measure_sid, $goods_nomenclature_item_id, $quota_order_number_id, $validity_start_date,
				$validity_end_date, $geographical_area_id, $measure_type_id, $additional_code_type_id,
				$additional_code_id, $regulation_id_full);

				// Assign the relevant duties to the measures
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
				array_push($measure_list, $measure);
			}
			foreach ($measure_list as $m){
				$rowclass                   = rowclass($m->validity_start_date, $m->validity_end_date);
?>
				<tr class="<?=$rowclass?>">
					<td class="govuk-table__cell"><a href="measure_view.php?measure_sid=<?=$m->measure_sid?>"><?=$m->measure_sid?></a></td>
					<td class="govuk-table__cell"><?=$goods_nomenclature_item_id?></td>
					<td class="govuk-table__cell c"><a href="measure_type_view.php?measure_type_id=<?=$m->measure_type_id?>"><?=$m->measure_type_id?></a></td>
					<td class="govuk-table__cell c"><a href="geographical_area_view.php?geographical_area_id=<?=$m->geographical_area_id?>"><?=$m->geographical_area_id?></a></td>
					<td class="govuk-table__cell c"><?=$m->additional_code_type_id?><?=$m->additional_code_id?></td>
					<td class="govuk-table__cell c"><a href="regulation_view.php?regulation_id=<?=$m->regulation_id_full?>"><?=$m->regulation_id_full?></a></td>
					<td nowrap class="govuk-table__cell r"><?=$m->validity_start_date?></td>
					<td nowrap class="govuk-table__cell r"><?=$m->validity_end_date?></td>
					<td nowrap class="govuk-table__cell r"><?=$m->quota_order_number_id?></td>
					<td class="govuk-table__cell r"><?=$m->combined_duty?></td>
				</tr>
<?php
			}
		}
	?>
			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
	} else {
		echo ("<p>No measures, as this does not have a product line suffix of '80'.</p>");
	}
?>

			<h2 id="inherited">Inherited measures</h2>
			<p>The measures below have been inherited down to this commodity code.</p>
<?php
	if (($productline_suffix == "80")  && ($parent_string != "")) {
?>        
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header c">Measure SID</th>
					<th class="govuk-table__header c">Commodity</th>
					<th class="govuk-table__header c">Measure type ID</th>
					<th class="govuk-table__header c">Geographical area ID</th>
					<th class="govuk-table__header c">Additional code</th>
					<th class="govuk-table__header c">Regulation</th>
					<th class="govuk-table__header c">Start date</th>
					<th class="govuk-table__header c">End date</th>
					<th class="govuk-table__header c">Order number</th>
					<th class="govuk-table__header c">Duty</th>
				</tr>
<?php
		$sql = "SELECT * FROM measures WHERE goods_nomenclature_item_id IN (" . $parent_string  . ") ";
		if ($geographical_area_id != "") {
			$sql .= " AND geographical_area_id = '" . $geographical_area_id . "'";
		}
		if ($measure_type_id != "") {
			$sql .= " AND measure_type_id = '" . $measure_type_id . "'";
		}
		$sql .= " ORDER BY goods_nomenclature_item_id, validity_start_date DESC";
		$result = pg_query($conn, $sql);
		if  (($result) && (pg_num_rows($result) > 0)){
			while ($row = pg_fetch_array($result)) {
				$measure_sid                = $row['measure_sid'];
				$goods_nomenclature_item_ix = $row['goods_nomenclature_item_id'];
				$measure_type_id            = $row['measure_type_id'];
				$geographical_area_id       = $row['geographical_area_id'];
				$additional_code_type_id    = $row['additional_code_type_id'];
				$additional_code_id         = $row['additional_code_id'];
				$regulation_id_full         = $row['measure_generating_regulation_id'];
				$quota_order_number_id      = $row['ordernumber'];
				$validity_start_date        = string_to_date($row['validity_start_date']);
				$validity_end_date          = string_to_date($row['validity_end_date']);
				$rowclass                   = rowclass($validity_start_date, $validity_end_date);
				$url                        = "goods_nomenclature_item_view.php?goods_nomenclature_item_id=" . $goods_nomenclature_item_ix . "&productline_suffix=80";
				if ($goods_nomenclature_item_ix != $goods_nomenclature_item_id) {
?>
				<tr class="<?=$rowclass?>">
					<td class="govuk-table__cell c"><a href="measure_view.php?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell c"><a href="<?=$url?>"><?=$goods_nomenclature_item_ix?></a></td>
					<td class="govuk-table__cell c"><a href="measure_type_view.php?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
					<td class="govuk-table__cell c"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
					<td class="govuk-table__cell c"><?=$additional_code_type_id?><?=$additional_code_id?></td>
					<td class="govuk-table__cell c"><a href="regulation_view.php?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
					<td class="govuk-table__cell c"><?=$validity_start_date?></td>
					<td class="govuk-table__cell c"><?=$validity_end_date?></td>
					<td class="govuk-table__cell c"><?=$quota_order_number_id?></td>
					<td class="govuk-table__cell c">Duty</td>
				</tr>
<?php
			}
		}
	}
?>
			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>
<?php
	} else {
		echo ("<p>No measures, as this does not have a product line suffix of '80'.</p>");
	}
?>    

</div>

<?php
	require ("includes/footer.php")
?>