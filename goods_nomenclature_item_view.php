<?php
    $title = "View goods nomenclature";
	require ("includes/db.php");

	$goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
	$geographical_area_id       = strtoupper(get_querystring("geographical_area_id"));
	$measure_type_id            = get_querystring("measure_type_id");
	$productline_suffix         = get_querystring("productline_suffix");
	$sort_order					= get_querystring("so");
	$sort_direction				= get_querystring("sd");
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
				<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
			</li>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="/sections.html">Search or browse goods classification</a>
			</li>
		</ol>
	</div>

	<div class="app-content__header">
		<h1 class="govuk-heading-xl">View commodity <?=$goods_nomenclature_item_id?></h1>
	</div>

	<?php
	if ($obj_goods_nomenclature_item->exists == true) {
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

		$result = pg_query($conn, $sql);
		if  ($result) {
			while ($row = pg_fetch_array($result)) {
				$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
				$goods_nomenclature_sid		= $row['goods_nomenclature_sid'];
				$productline_suffix         = $row['productline_suffix'];
				$description                = $row['description'];
				$friendly_description       = $row['friendly_description'];
				$validity_start_date        = short_date($row['validity_start_date']);
				$validity_end_date          = $row['validity_end_date'];
				$validity_end_date2         = short_date($validity_end_date);
			}
		}

		if ($validity_end_date != ""){
			echo ("<div class='warning'><p><strong>Warning</strong><br />This commodity code has an end-date. Please be careful when assigning duties to this commodity.</p></div>");
		}
?>	
			<!-- MENU //-->
			<h2>Page content</h2>
			<ul class="tariff_menu">
				<li><a href="#details">Commodity code details</a></li>
				<li><a href="#hierarchy">Position in hierarchy</a></li>
				<li><a href="#description_periods">Historical descriptions</a></li>
				<li><a href="#origins">Origins</a></li>
				<li><a href="#assigned">Assigned measures</a></li>
				<li><a href="#inherited">Inherited measures</a></li>
				<li><a title="Opens in new window" href="https://www.trade-tariff.service.gov.uk/trade-tariff/commodities/<?=$goods_nomenclature_item_id?>#import" target="_blank" href="#usage_measures">View in Trade Tariff Service</a></li>
				<li><a title="Opens in new window" href="https://ec.europa.eu/taxation_customs/dds2/taric/measures.jsp?Lang=en&SimDate=20190827&Area=&MeasType=&StartPub=&EndPub=&MeasText=&GoodsText=&op=&Taric=<?=$goods_nomenclature_item_id?>&search_text=goods&textSearch=&LangDescr=en&OrderNum=&Regulation=&measStartDat=&measEndDat=" target="_blank" href="#usage_measures">View in EU Taric consultation</a></li>
			</ul>

		<h2 id="details">Commodity code details</h2>
		<p>The table below shows the core details of this commodity code</p>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header nopad" style="width:30%">Item</th>
				<th class="govuk-table__header" style="width:70%">Value</th>
			</tr>

				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Commodity code</td>
					<td class="govuk-table__cell b"><?=$goods_nomenclature_item_id?> ( <?=format_commodity_code($goods_nomenclature_item_id)?> )</td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">SID</td>
					<td class="govuk-table__cell b"><?=$goods_nomenclature_sid?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Product line suffix</td>
					<td class="govuk-table__cell b"><?=$productline_suffix?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Description</td>
					<td class="govuk-table__cell"><?=$description?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Friendly description</td>
					<td class="govuk-table__cell"><?=$friendly_description?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Validity start date</td>
					<td class="govuk-table__cell"><?=$validity_start_date?></td>
				</tr>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell nopad">Validity end date</td>
					<td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
				</tr>
			</table>
			<form action="/actions/goods_nomenclature_actions.html" method="get">
				<input type="hidden" name="phase" value="goods_nomenclature_item_edit" />
				<input type="hidden" name="goods_nomenclature_sid" value="<?=$goods_nomenclature_sid?>" />
				<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature_item_id?>" />
				<input type="hidden" name="productline_suffix" value="<?=$productline_suffix?>" />
				<button type="submit" class="govuk-button small")>Edit this commodity code</button>
			</form>
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="hierarchy">Position in hierarchy</h2>
			<p>The table below shows the position of this commodity code in the hierarchy. Lines that are greyed out are those
			that use a product line suffix that is not "80". These are structural elements only and cannot be declared against.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th style="width:10%" class="govuk-table__header nopad">Commodity</th>
					<th style="width:6%" class="govuk-table__header c">Suffix</th>
					<th style="width:6%" class="govuk-table__header c">Indents</th>
					<th style="width:73%" class="govuk-table__header">Description</th>
					<th style="width:5%" class="govuk-table__header c">Leaf</th>
				</tr>
<?php
	$array = $obj_goods_nomenclature_item->ar_hierarchies;

	$hier_count = sizeof($array);

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
			if ($t->productline_suffix == "80") {
				array_push ($parents, $t->goods_nomenclature_item_id);
			}
		}
?>
				<tr class="govuk-table__row <?=$suffix_class?>">
					<td class="govuk-table__cell nopad"><a class="nodecorate" href="<?=$url?>"><?=format_commodity_code($obj_goods_nomenclature_item->ar_hierarchies[$i]->goods_nomenclature_item_id)?></a></td>
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
<?php
	//}
?>			
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
// Get historical commodity code descriptions
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

			<h2 id="origins">Origins</h2>
			<p>When a new goods nomenclature code is created, this record provides details of the goods
nomenclature code from which the new code originated.</p>
<?php
// Get origins
	$sql = "select gno.derived_goods_nomenclature_item_id, gno.derived_productline_suffix, description
	from goods_nomenclature_origins gno, goods_nomenclature_descriptions gnd
	where gno.productline_suffix = gnd.productline_suffix
	and gno.goods_nomenclature_item_id = gnd.goods_nomenclature_item_id
	and gno.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'
	and gno.productline_suffix = '" . $productline_suffix . "'
	order by 1, 2";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)) {
?>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header" style="width:15%">Commodity code</th>
				<th class="govuk-table__header c" style="width:5%">Suffix</th>
				<th class="govuk-table__header" style="width:80%">Description</th>
			</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$derived_goods_nomenclature_item_id	= $row['derived_goods_nomenclature_item_id'];
			$derived_productline_suffix 			= $row['derived_productline_suffix'];
			$description       						= $row['description'];
?>
			<tr class="<?=$rowclass?>">
				<td class="govuk-table__cell" class="nodecorate"><?=format_commodity_code($derived_goods_nomenclature_item_id)?></td>
				<td class="govuk-table__cell c"><?=$derived_productline_suffix?></td>
				<td class="govuk-table__cell"><?=$description?></td>
			</tr>
<?php
		}
?>
		</table>
<?php
	} else {
?>
		<p>There are no origins for this commodity code.</p>
<?php				
	}
?>			
			<p class="back_to_top"><a href="#top">Back to top</a></p>

			<h2 id="successors">Successors</h2>
			<p>When a goods nomenclature code is closed, this record provides details of the goods
nomenclature code which supersedes it.</p>
<?php
// Get successors
	$sql = "select distinct gns.absorbed_goods_nomenclature_item_id, gns.absorbed_productline_suffix, gnd.description
	from goods_nomenclature_successors gns, goods_nomenclature_descriptions gnd
	where gns.productline_suffix = gnd.productline_suffix
	and gns.goods_nomenclature_item_id = gnd.goods_nomenclature_item_id
	and gns.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "'
	and gns.productline_suffix = '" . $productline_suffix . "'
	order by 1, 2";
	$result = pg_query($conn, $sql);
	if  (($result) && (pg_num_rows($result) > 0)) {
?>
		<table class="govuk-table" cellspacing="0">
			<tr class="govuk-table__row">
				<th class="govuk-table__header" style="width:15%">Commodity code</th>
				<th class="govuk-table__header c" style="width:5%">Suffix</th>
				<th class="govuk-table__header" style="width:80%">Description</th>
			</tr>
<?php
		while ($row = pg_fetch_array($result)) {
			$absorbed_goods_nomenclature_item_id	= $row['absorbed_goods_nomenclature_item_id'];
			$absorbed_productline_suffix 			= $row['absorbed_productline_suffix'];
			$description       						= $row['description'];
?>
			<tr class="<?=$rowclass?>">
				<td class="govuk-table__cell"><?=$absorbed_goods_nomenclature_item_id?></td>
				<td class="govuk-table__cell c"><?=$absorbed_productline_suffix?></td>
				<td class="govuk-table__cell"><?=$description?></td>
			</tr>
<?php
		}
?>
		</table>
<?php
	} else {
?>
		<p>There are no successors for this commodity code.</p>
<?php				
	}
?>			
			<p class="back_to_top"><a href="#top">Back to top</a></p>
			<h2 id="assigned">Assigned measures</h2>
			<p>The measures below have been directly assigned to this commodity code.</p>

<?php
	require ("includes/measure_table.php");
?>

			<h2 id="inherited">Inherited measures</h2>
<?php
	if (($productline_suffix == "80")  && ($parent_string != "")) {
		$sql = "SELECT m.*, g.description as geo_description, mtd.description as measure_type_description
		FROM ml.measures_real_end_dates m, ml.ml_geographical_areas g, measure_type_descriptions mtd
		WHERE m.geographical_area_id = g.geographical_area_id
		AND m.measure_type_id = mtd.measure_type_id
		AND goods_nomenclature_item_id IN (" . $parent_string  . ") ";
		if ($geographical_area_id != "") {
			$sql .= " AND m.geographical_area_id in (" . $geographical_area_string . ")";
		}
		if ($measure_type_id != "") {
			$type_array = explode(",", $measure_type_id);
			$type_string = "";
			foreach ($type_array as $type) {
				$type_string .= "'" . $type . "', ";
			}
			$type_string = trim($type_string);
			$type_string = trim($type_string, ",");

			$sql .= " AND m.measure_type_id in (" . $type_string . ") ";
		}
		// Get sort order
		switch ($sort_order) {
			case "measure_sid":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.measure_sid";
				} else {
					$sql .= " ORDER BY m.measure_sid DESC";
				}
				break;
			case "goods_nomenclature_item_id":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.goods_nomenclature_item_id, m.validity_start_date DESC";
				} else {
					$sql .= " ORDER BY m.goods_nomenclature_item_id DESC, m.validity_start_date DESC";
				}
				break;
			case "measure_type_id":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.measure_type_id, m.validity_start_date DESC";
				} else {
					$sql .= " ORDER BY m.measure_type_id DESC, m.validity_start_date DESC";
				}
				break;
			case "geographical_area_id":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.geographical_area_id, m.validity_start_date DESC";
				} else {
					$sql .= " ORDER BY m.geographical_area_id DESC, m.validity_start_date DESC";
				}
				break;
			case "regulation":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.measure_generating_regulation_id, m.validity_start_date DESC";
				} else {
					$sql .= " ORDER BY m.measure_generating_regulation_id DESC, m.validity_start_date DESC";
				}
				break;
			case "ordernumber":
				if ($sort_direction == "asc") {
					$sql .= " ORDER BY m.ordernumber, m.validity_start_date DESC";
				} else {
					$sql .= " ORDER BY m.ordernumber DESC, m.validity_start_date DESC";
				}
				break;
			default:
				$sql .= " ORDER BY m.validity_start_date DESC, goods_nomenclature_item_id";
				break;
			}

		//print ($sql);

		$result = pg_query($conn, $sql);
		if  (($result) && (pg_num_rows($result) > 0)) {
			# Get the base URL for the sorting
			$base_url = str_replace("?" . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']);

			$qs = "";
			if ($goods_nomenclature_item_id != "") {
				$qs .= "goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
			}
			if ($measure_type_id != "") {
				if ($qs != "") { $qs .= "&"; }
				$qs .= "measure_type_id=" . $measure_type_id;
			}
			if ($geographical_area_id != "") {
				if ($qs != "") { $qs .= "&"; }
				$qs .= "geographical_area_id=" . $geographical_area_id;
			}
			if ($qs != "") {
				$qs = "?" . $qs;
			}
			$url = $base_url . $qs;

?>
			<p>The matching measures below have been inherited down to this commodity code.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th nowrap class="govuk-table__header nopad vsmall">Measure&nbsp;SID&nbsp;<a href="<?=$url . "&so=measure_sid&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=measure_sid&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall" style="width:10%">Commodity&nbsp;<a href="<?=$url . "&so=goods_nomenclature_item_id&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=goods_nomenclature_item_id&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall" style="width:15%">Measure type ID&nbsp;<a href="<?=$url . "&so=measure_type_id&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=measure_type_id&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall" style="width:15%">Geographical area ID&nbsp;<a href="<?=$url . "&so=geographical_area_id&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=geographical_area_id&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall c">Add. code</th>
					<th nowrap class="govuk-table__header vsmall">Regulation&nbsp;<a href="<?=$url . "&so=regulation&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=regulation&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall c">Start date</th>
					<th nowrap class="govuk-table__header vsmall c">End date</th>
					<th nowrap class="govuk-table__header vsmall c">Order number&nbsp;<a href="<?=$url . "&so=ordernumber&sd=asc#inherited"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=ordernumber&sd=desc#inherited"?>" class="table_arrow">&darr;</a></th>
					<th nowrap class="govuk-table__header vsmall c">Duty</th>
				</tr>
<?php
			while ($row = pg_fetch_array($result)) {
				$measure_sid                = $row['measure_sid'];
				$goods_nomenclature_item_ix = $row['goods_nomenclature_item_id'];
				$measure_type_id            = $row['measure_type_id'];
				$measure_type_description	= $row['measure_type_description'];
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
					<td class="govuk-table__cell nopad vsmall"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell vsmall"><a class="nodecorate" href="<?=$url?>"><?=format_commodity_code($goods_nomenclature_item_ix)?></a></td>
					<td class="govuk-table__cell vsmall"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?> <?=$measure_type_description?></a></td>
					<td class="govuk-table__cell vsmall"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_ix?>"><?=$geographical_area_ix?>&nbsp;<?=$geo_description?></a></td>
					<td class="govuk-table__cell vsmall c"><?=$additional_code_type_id?><?=$additional_code_id?></td>
					<td class="govuk-table__cell vsmall"><a href="regulation_view.html?regulation_id=<?=$regulation_id_full?>"><?=$regulation_id_full?></a></td>
					<td class="govuk-table__cell vsmall c"><?=$validity_start_date?></td>
					<td class="govuk-table__cell vsmall c"><?=$validity_end_date?></td>
					<td class="govuk-table__cell vsmall c"><?=$quota_order_number_id?></td>
					<td class="govuk-table__cell vsmall c">&nbsp;</td>
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
} else {
	echo ("<div class='warning'><p><strong>Warning</strong><br />This commodity code does not exist on this database.</p></div>");

}
?>    

</div>

<?php
	require ("includes/footer.php")
?>