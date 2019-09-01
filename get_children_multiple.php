<?php
    $title = "Get children";
	require ("includes/db.php");
	require ("includes/header_empty.php");
	$link = get_querystring("link");
?>
<div id="wrapper" class="direction-ltr">
	<table class="govuk-table" cellspacing="0">
		<tr class="govuk-table__row">
			<th style="width:15%" class="govuk-table__header">Commodity</th>
			<th style="width:10%" class="govuk-table__header c">Suffix</th>
			<th style="width:10%" class="govuk-table__header c">Indents</th>
			<th style="width:55%" class="govuk-table__header">Description</th>
			<th style="width:5%" class="govuk-table__header c">Leaf</th>
			<th style="width:5%" class="govuk-table__header c">MFN</th>
		</tr>

<?php
	$csv = array_map('str_getcsv', file('csv/commodities.csv'));
	$lookup_list = [];
	foreach ($csv as $commodity) {
		$c = $commodity[0];
		$goods_nomenclature_item_id		= $commodity[0];
		$productline_suffix         	= "80";
		$obj_goods_nomenclature_item	= new goods_nomenclature;
		$obj_goods_nomenclature_item->set_properties($goods_nomenclature_item_id, $productline_suffix, "", "", "", "down");
?>
<?php
	$array      = $obj_goods_nomenclature_item->ar_hierarchies;
	$hier_count = sizeof($array);
	$parents    = array();
	$my_concat  = $goods_nomenclature_item_id . $productline_suffix;
	for($i = 0; $i < $hier_count; $i++) {
		$t      = $array[$i];
		$concat = $t->goods_nomenclature_item_id . $t->productline_suffix;
		$url    = "goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $t->goods_nomenclature_item_id . "&productline_suffix=" . $t->productline_suffix . "&measure_type_id=103,105#assigned";
		$class  = "indent" . $t->number_indents;
		if ($obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix != "80") {
			$suffix_class = "filler";
		} else {
			$suffix_class = "";
		}
		if (($t->goods_nomenclature_item_id == $goods_nomenclature_item_id) && ($t->productline_suffix == $productline_suffix)) {
			$suffix_class .= " selected";
		}
		if ($concat < $my_concat) {
			array_push ($parents, $t->goods_nomenclature_item_id);
		}
		if ($obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix == "80") {
			array_push ($lookup_list, $obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id);
		}
?>
		<tr class="govuk-table__row <?=$suffix_class?>">
<?php
	if ($link == 1) {
?>
			<td class="govuk-table__cell"><a target="_blank" href="<?=$url?>"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id?></a></td>
<?php
	} else {
?>
			<td class="govuk-table__cell"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id?></td>
<?php
	}

?>
			<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix?></td>
			<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->number_indents + 1?></td>
			<td class="govuk-table__cell <?=$class?>"><?=str_replace("|", " ", $obj_goods_nomenclature_item->ar_hierarchies[$i]->description)?></td>
			<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->leaf_string()?></td>
			<td class="govuk-table__cell c">
<?php
	if ($obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix == "80") {
?>
				<span id="commodity_<?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id?>"></span>
<?php
	}
?>
			</td>
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
<?php
	}
?>
	</table>
</div>

<?php
	$clause = "'" . implode("', '", $lookup_list) . "'";

	// Get the duties
	$duty_list = [];
	$sql = "SELECT m.measure_sid, m.goods_nomenclature_item_id, mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code,
	mc.measurement_unit_code, mc.measurement_unit_qualifier_code
	FROM measures m, measure_components mc
	WHERE m.measure_sid = mc.measure_sid
	AND validity_start_date = '2019-03-30' AND measure_type_id IN ('103', '105')
	AND goods_nomenclature_item_id IN (" . $clause . ")
	ORDER BY 1, 2";
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$measure_sid				= $row["measure_sid"];
			$goods_nomenclature_item_id				= $row["goods_nomenclature_item_id"];
			$duty_expression_id	= $row["duty_expression_id"];
			$duty_amount	= $row["duty_amount"];
			$monetary_unit_code	= $row["monetary_unit_code"];
			$measurement_unit_code	= $row["measurement_unit_code"];
			$measurement_unit_qualifier_code	= $row["measurement_unit_qualifier_code"];

			$duty = new duty;
			$duty->set_properties($goods_nomenclature_item_id, "", "", "", $duty_expression_id, $duty_amount,
			$monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code, $measure_sid, "", "", "", "");
			array_push($duty_list, $duty);
		}
	}

	// Get the measures
	$measure_list = [];
	$sql = "SELECT m.measure_sid, m.goods_nomenclature_item_id, mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code,
	mc.measurement_unit_code, mc.measurement_unit_qualifier_code
	FROM measures m, measure_components mc
	WHERE m.measure_sid = mc.measure_sid
	AND validity_start_date = '2019-03-30' AND measure_type_id IN ('103', '105')
	AND goods_nomenclature_item_id IN (" . $clause . ")
	ORDER BY 1, 2";
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$measure_sid				= $row["measure_sid"];
			$goods_nomenclature_item_id	= $row["goods_nomenclature_item_id"];

			$measure = new measure;
			$measure->set_properties($measure_sid, $goods_nomenclature_item_id, "", "", "", "", "", "", "", "", "");

			// Assign the relevant duties to the measures
			if (count($duty_list) > 0) {
				foreach ($duty_list as $d){
					if ($d->measure_sid == $measure_sid) {
						array_push($measure->duty_list, $d);
					}
				}
			}
			$measure->combine_duties();

			array_push($measure_list, $measure);
		}
	}
?>
<script type="text/javascript">
$( document ).ready(function() {

<?php
	foreach ($lookup_list as $item) {
		foreach ($measure_list as $m) {
			if ($m->commodity_code == $item) {
?>
	$("span#commodity_<?=$item?>").text("<?=$m->combined_duty?>");
<?php
				break;
			}
		}
	}
?>
});
</script>
<?php
	require ("includes/footer_empty.php")
?>