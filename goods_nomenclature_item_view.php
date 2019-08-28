<?php
	require ("includes/db.php");

	$goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
	$geographical_area_id       = get_querystring("geographical_area_id");
	$measure_type_id            = get_querystring("measure_type_id");
	$productline_suffix         = get_querystring("productline_suffix");
	if ($productline_suffix == "") {
		$productline_suffix = "80";
	}

	$obj_goods_nomenclature_item = new goods_nomenclature;
	$obj_goods_nomenclature_item->clear_cookies();
	$obj_goods_nomenclature_item->set_properties($goods_nomenclature_item_id, $productline_suffix, "", "", "");
	require ("includes/header.php");

?>
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
		<ol class="govuk-breadcrumbs__list">
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/">Home</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/sections.html">Goods nomenclature section</a>
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
				<li><a href="#description_periods">Historical descriptions</a></li>
				<li><a href="#assigned">Assigned measures</a></li>
				<li><a href="#inherited">Inherited measures</a></li>
				<li><a title="Opens in new window" href="https://www.trade-tariff.service.gov.uk/trade-tariff/commodities/<?=$goods_nomenclature_item_id?>#import" target="_blank" href="#usage_measures">View in Trade Tariff Service</a></li>
				<li><a title="Opens in new window" href="https://ec.europa.eu/taxation_customs/dds2/taric/measures.jsp?Lang=en&SimDate=20190827&Area=&MeasType=&StartPub=&EndPub=&MeasText=&GoodsText=&op=&Taric=<?=$goods_nomenclature_item_id?>&search_text=goods&textSearch=&LangDescr=en&OrderNum=&Regulation=&measStartDat=&measEndDat=" target="_blank" href="#usage_measures">View in EU Taric consultation</a></li>
			</ul>

		<h2 id="details">Commodity code details</h2>
		<p>The table below shows the core details of this commodity code</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header" style="width:30%">Item</th>
				<th class="govuk-table__header" style="width:70%">Value</th>
			</tr>
<?php
	$sql = "SELECT gn.goods_nomenclature_item_id, gn.producline_suffix as productline_suffix,
	gn.goods_nomenclature_sid, gn.validity_start_date, gn.validity_end_date, gnd1.description, f.description as friendly_description
	FROM goods_nomenclature_descriptions gnd1, goods_nomenclatures gn 
	left outer join ml.commodity_friendly_names f on left(gn.goods_nomenclature_item_id, 8) = f.goods_nomenclature_item_id
	WHERE gn.goods_nomenclature_item_id = gnd1.goods_nomenclature_item_id AND gn.producline_suffix = gnd1.productline_suffix
	AND gn.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "' AND gn.producline_suffix = '" . $productline_suffix . "'
	AND  (gnd1.goods_nomenclature_description_period_sid IN ( SELECT max(gnd2.goods_nomenclature_description_period_sid) AS max
	FROM goods_nomenclature_descriptions gnd2
	WHERE gnd1.goods_nomenclature_item_id = gnd2.goods_nomenclature_item_id AND gnd1.productline_suffix = gnd2.productline_suffix))
	ORDER BY validity_start_date DESC LIMIT 1";
	//print ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
			$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$goods_nomenclature_sid		= $row['goods_nomenclature_sid'];
			$productline_suffix         = $row['productline_suffix'];
			$description                = $row['description'];
			$friendly_description       = $row['friendly_description'];
			$validity_start_date        = short_date($row['validity_start_date']);
			$validity_end_date          = short_date($row['validity_end_date']);
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">Commodity code</td>
					<td class="govuk-table__cell b"><?=$goods_nomenclature_item_id?> ( <?=format_commodity_code($goods_nomenclature_item_id)?> )</td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell">SID</td>
					<td class="govuk-table__cell b"><?=$goods_nomenclature_sid?></td>
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
					<td class="govuk-table__cell">Friendly description</td>
					<td class="govuk-table__cell"><?=$friendly_description?></td>
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
			<p>The table below shows the position of this commodity code in the hierarchy. Lines that are greyed out are those
			that use a product line suffix that is not "80". These are structural elements only and cannot be declared against.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th style="width:10%" class="govuk-table__header">Commodity</th>
					<th style="width:6%" class="govuk-table__header c">Suffix</th>
					<th style="width:6%" class="govuk-table__header c">Indents</th>
					<th style="width:73%" class="govuk-table__header">Description</th>
					<th style="width:5%" class="govuk-table__header c">Leaf</th>
				</tr>
<?php
	$array      = $obj_goods_nomenclature_item->ar_hierarchies;
	$hier_count = sizeof($array);
	print ("Hierarchy count " . $hier_count);
	$parents    = array();
	$my_concat  = $goods_nomenclature_item_id . $productline_suffix;
	for($i = 0; $i < $hier_count; $i++) {
		$t      = $array[$i];
		$concat = $t->goods_nomenclature_item_id . $t->productline_suffix;
		$url    = "goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $t->goods_nomenclature_item_id . "&productline_suffix=" . $t->productline_suffix . "#hierarchy";
		$class  = "indent" . $t->number_indents;
		if ($obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix != "80") {
			$suffix_class = "filler";
		} else {
			$suffix_class = "";
		}
		if (($t->goods_nomenclature_item_id == $goods_nomenclature_item_id) && ($t->productline_suffix == $productline_suffix)) {
			#$class .= " selected";
			$suffix_class .= " selected";
		}
		if ($concat < $my_concat) {
			array_push ($parents, $t->goods_nomenclature_item_id);
		}
?>
				<tr class="govuk-table__row <?=$suffix_class?>">
					<td class="govuk-table__cell"><a class="nodecorate" href="<?=$url?>"><?=format_commodity_code($obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id)?></a></td>
					<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->productline_suffix?></td>
					<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->number_indents + 1?></td>
					<td class="govuk-table__cell <?=$class?>"><?=str_replace("|", " ", $obj_goods_nomenclature_item->ar_hierarchies[$i]->description)?></td>
					<td class="govuk-table__cell c"><?=$obj_goods_nomenclature_item->ar_hierarchies[$i]->leaf_string()?></td>
				</tr>

<?php        
	}
	$parent_count = count($parents);
	$parent_string = "";
	for($i = 0; $i < $parent_count; $i++) {
		if ($parents[$i] != $goods_nomenclature_item_id) {
			$parent_string .= "'" . $parents[$i] . "',";
		}
	}
	$parent_string = trim($parent_string);
	$parent_string = trim($parent_string, ",");
?>
			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

	<h2 id="description_periods">Historical descriptions</h2>
	<form action="/goods_nomenclature_add_description.html" method="get" class="inline_form">
		<input type="hidden" name="phase" value="goods_nomenclature_add_description" />
		<input type="hidden" name="action" value="new" />
		<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
		<input type="hidden" name="goods_nomenclature_sid" value="<?=$goods_nomenclature_sid?>" />
		<input type="hidden" name="productline_suffix" value="<?=$productline_suffix?>" />
		<h3>Create new goods nomenclature description</h3>
		<div class="column-one-third" style="width:320px">
			<div class="govuk-form-group" style="padding:0px;margin:0px">
				<button type="submit" class="govuk-button">New description</button>
			</div>
		</div>
		<div class="clearer"><!--&nbsp;//--></div>
	</form>

			<p>The table below shows the historical descriptions for this commodity code (most recent first).</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:15%">Date</th>
					<th class="govuk-table__header" style="width:75%">Description</th>
					<th class="govuk-table__header" style="width:10%">Actions</th>
				</tr>
<?php
	$sql = "SELECT gndp.validity_start_date, gnd.description, gndp.goods_nomenclature_description_period_sid
	FROM goods_nomenclature_description_periods gndp, goods_nomenclature_descriptions gnd
	WHERE gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
	AND gnd.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'
	AND gnd.productline_suffix = '" . $productline_suffix . "'
	AND gnd.goods_nomenclature_sid = " . $goods_nomenclature_sid . "
	ORDER BY 1 DESC";
	$result = pg_query($conn, $sql);
	if  ($result) {
        $row_count = pg_num_rows($result);
        $i = 0;
		while ($row = pg_fetch_array($result)) {
            $i += 1;
			$description                				= str_replace("|", " ", $row['description']);
			$validity_start_date        				= short_date($row['validity_start_date']);
            $validity_start_date2       				= string_to_date($row["validity_start_date"]);
			$goods_nomenclature_description_period_sid	= $row['goods_nomenclature_description_period_sid'];
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><?=$validity_start_date?></td>
					<td class="govuk-table__cell"><?=$description?></td>
					<td class="govuk-table__cell">
<?php
    if (is_in_future($validity_start_date2)) {
?>
                        <form action="goods_nomenclature_add_description.html" method="get">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
                            <input type="hidden" name="productline_suffix" value="<?=$productline_suffix?>" />
                            <input type="hidden" name="goods_nomenclature_description_period_sid" value="<?=$goods_nomenclature_description_period_sid?>" />
                            <button type="submit" class="govuk-button btn_nomargin")>Edit</button>
                        </form>
<?php
        if ($i < $row_count) {
?>
                        <form action="actions/goods_nomenclature_actions.html" method="get">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="phase" value="goods_nomenclature_description_delete" />
                            <input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
                            <input type="hidden" name="productline_suffix" value="<?=$productline_suffix?>" />
                            <input type="hidden" name="goods_nomenclature_description_period_sid" value="<?=$goods_nomenclature_description_period_sid?>" />
                            <button onclick="return (are_you_sure());" type="submit" class="govuk-button btn_nomargin")>Delete</button>
                        </form>
<?php
        }
    }
?>                        
					</td>
				</tr>
<?php
		}
	}
?>

			</table>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="assigned">Assigned measures</h2>
			<p>The measures below have been directly assigned to this commodity code.</p>

<?php
	require ("includes/measure_table.php");
?>

			<h2 id="inherited">Inherited measures</h2>
<?php
	if (($productline_suffix == "80")  && ($parent_string != "")) {
		$sql = "SELECT m.*, g.description as geo_description
		FROM ml.measures_real_end_dates m, ml.ml_geographical_areas g
		WHERE m.geographical_area_id = g.geographical_area_id
		AND goods_nomenclature_item_id IN (" . $parent_string  . ") ";
		if ($geographical_area_id != "") {
			$sql .= " AND m.geographical_area_id = '" . $geographical_area_id . "'";
		}
		if ($measure_type_id != "") {
			$sql .= " AND measure_type_id = '" . $measure_type_id . "'";
		}
		$sql .= " ORDER BY goods_nomenclature_item_id, m.validity_start_date DESC";

		$result = pg_query($conn, $sql);
		if  (($result) && (pg_num_rows($result) > 0)) {
?>
			<p>The measures below have been inherited down to this commodity code.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header">Measure SID</th>
					<th class="govuk-table__header">Commodity</th>
					<th class="govuk-table__header c">Measure type ID</th>
					<th class="govuk-table__header">Geographical area ID</th>
					<th class="govuk-table__header c">Additional code</th>
					<th class="govuk-table__header c">Regulation</th>
					<th class="govuk-table__header c">Start date</th>
					<th class="govuk-table__header c">End date</th>
					<th class="govuk-table__header c">Order number</th>
					<th class="govuk-table__header c">Duty</th>
				</tr>
<?php
			while ($row = pg_fetch_array($result)) {
				$measure_sid                = $row['measure_sid'];
				$goods_nomenclature_item_ix = $row['goods_nomenclature_item_id'];
				$measure_type_id            = $row['measure_type_id'];
				$geographical_area_ix       = $row['geographical_area_id'];
				$geo_description	        = $row['geo_description'];
				$additional_code_type_id    = $row['additional_code_type_id'];
				$additional_code_id         = $row['additional_code_id'];
				$regulation_id_full         = $row['measure_generating_regulation_id'];
				$quota_order_number_id      = $row['ordernumber'];
				$validity_start_date        = short_date($row['validity_start_date']);
				$validity_end_date          = short_date($row['validity_end_date']);
				$rowclass                   = rowclass($validity_start_date, $validity_end_date);
				$url                        = "goods_nomenclature_item_view.html?goods_nomenclature_item_id=" . $goods_nomenclature_item_ix . "&productline_suffix=80";
				if ($goods_nomenclature_item_ix != $goods_nomenclature_item_id) {
?>
				<tr class="<?=$rowclass?>">
					<td class="govuk-table__cell"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell"><a class="nodecorate" href="<?=$url?>"><?=format_commodity_code($goods_nomenclature_item_ix)?></a></td>
					<td class="govuk-table__cell c"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
					<td class="govuk-table__cell"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_ix?>"><?=$geographical_area_ix?>&nbsp;<?=$geo_description?></a></td>
					<td class="govuk-table__cell c"><?=$additional_code_type_id?><?=$additional_code_id?></td>
					<td class="govuk-table__cell c"><a href="regulation_view.html?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
					<td class="govuk-table__cell c"><?=$validity_start_date?></td>
					<td class="govuk-table__cell c"><?=$validity_end_date?></td>
					<td class="govuk-table__cell c"><?=$quota_order_number_id?></td>
					<td class="govuk-table__cell c">&nbsp;</td>
				</tr>
<?php
				}
			}
?>
			</table>
<?php
		} else {
?>
			<p>There are no measures inherited down to this commodity code.</p>
<?php				
		}
?>
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