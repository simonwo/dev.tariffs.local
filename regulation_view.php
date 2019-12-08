<?php
    $title = "View regulation";
	require ("includes/db.php");
	require ("includes/header.php");
	$regulation_id = get_querystring("base_regulation_id");
	$measure_scope = get_querystring("measure_scope");
	$sort_order					= get_querystring("so");
	$sort_direction				= get_querystring("sd");
	if ($measure_scope == "") {
		$measure_scope = "all";
	}
?>
<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
		</li>
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/regulations.html">Regulations</a>
		</li>
	</ol>
	</div>
	<div class="app-content__header">
		<h1 class="govuk-heading-xl">View regulation <?=$regulation_id?></h1>
	</div>


	<ul class="tariff_menu">
		<li><a href="#regulation_details">Regulation details</a></li>
		<li><a href="#measure_details">Measure details</a></li>
	</ul>


<?php
	$sql = "SELECT 'Base' as regulation_type, b.base_regulation_id as regulation_id, b.base_regulation_role as regulation_role,
	b.information_text, b.regulation_group_id,
	rgd.description as regulation_group_description, b.validity_start_date, b.validity_end_date, b.effective_end_date
	FROM base_regulations b, regulation_group_descriptions rgd WHERE b.regulation_group_id = rgd.regulation_group_id
	AND base_regulation_id LIKE '" . $regulation_id . "%' 
	UNION
	SELECT 'Modification' as regulation_type, m.modification_regulation_id as regulation_id, m.modification_regulation_role as regulation_role,
	m.information_text, b.regulation_group_id,
	rgd.description, m.validity_start_date, m.validity_end_date, m.effective_end_date
	FROM modification_regulations m, base_regulations b, regulation_group_descriptions rgd
	WHERE m.base_regulation_id = b.base_regulation_id
	AND b.regulation_group_id = rgd.regulation_group_id
	AND m.modification_regulation_id LIKE '" . $regulation_id . "%'
	ORDER BY 1";
	#echo ($sql);
	$result = pg_query($conn, $sql);
	if  ($result) {
		if (pg_num_rows($result) != 0){
			$found = True;
?>
			<h2 id="regulation_details" class="nomargin">Regulation details</h2>
			<p style="max-width:100%">The European Union often splits a regulation into multiple parts for ease of management. There may
			be multiple regulation records here to represent just a single actual regulation.</p>
			<table class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header" style="width:12%">Regulation&nbsp;ID</th>
					<th class="govuk-table__header" style="width:8%">Type</th>
					<th class="govuk-table__header" style="width:8%">Role</th>
					<th class="govuk-table__header" style="width:26%">Information text</th>
					<th class="govuk-table__header" style="width:20%">Regulation group</th>
					<th class="govuk-table__header" style="width:8%">Start</th>
					<th class="govuk-table__header" style="width:8%">End</th>
					<th class="govuk-table__header" style="width:8%">Effective end</th>
				</tr>
<?php
			while ($row = pg_fetch_array($result)) {
				$regulation_type				= $row['regulation_type'];
				$regulation_idx					= $row['regulation_id'];
				$regulation_role				= $row['regulation_role'];
				$information_text				= $row['information_text'];
				$array = explode("|", $information_text);
				if (count($array) == 3) {
					$information_text  = "<ul class='understated'><li>" . $array[0] . "</li>";
					$information_text .= "<li><a href='" . $array[1]  . "' target='_blank'>" . $array[1] . "</a></li>";
					$information_text .= "<li>" . $array[2] . "</li></ul>";
				}
				$regulation_group_id			= $row['regulation_group_id'];
				$regulation_group_description   = $row['regulation_group_description'];
				$validity_start_date			= $row['validity_start_date'];
				$validity_end_date				= $row['validity_end_date'];
				$effective_end_date				= $row['effective_end_date'];
?>
				<tr class="govuk-table__row">
					<td class="govuk-table__cell"><?=$regulation_idx?></td>
					<td class="govuk-table__cell"><?=$regulation_type?></td>
					<td class="govuk-table__cell"><?=$regulation_role?></td>
					<td class="govuk-table__cell"><?=$information_text?></td>
					<td class="govuk-table__cell"><?=$regulation_group_id?> - <?=$regulation_group_description?></td>
					<td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
					<td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
					<td class="govuk-table__cell"><?=short_date($effective_end_date)?></td>
				</tr>
<?php
			}
?>
			</table>
<?php			
		} else {
			$found = False;
			echo ("<div class='warning'><p><strong>Warning</strong><br />This regulation cannot be found.</p></div>");

		}
	}
?>

<?php
	if ($found == True) {
?>				
			<h2 id="measure_details">Measure details</h2>
			<p style="max-width:100%">The list below identifies any measures that have come into force as a result of this regulation.
			Any measures that are shaded in grey are now in the past and closed. Any highlighted in blue
			are due to start on or around Brexit and have been created by DIT.</p>
			<form action="regulation_view.html#measure_details" method="get" class="inline_form">
				<input type="hidden" name="base_regulation_id" value="<?=$regulation_id?>" />
				<h3>Filter measures</h3>
				<div class="xgovuk-form-group">
					<fieldset class="govuk-fieldset" aria-describedby="hint">
						<span id="hint" class="govuk-hint">Select the measures to display</span>
						<div class="govuk-radios govuk-radios--inline">
							<div class="govuk-radios__item">
								<input <?=get_checked($measure_scope, "current")?> class="govuk-radios__input" id="measure_scope_current" name="measure_scope" type="radio" value="current">
								<label class="govuk-label govuk-radios__label" for="changed-name-2">Current measures only</label>
							</div>
							<div class="govuk-radios__item">
								<input <?=get_checked($measure_scope, "all")?> class="govuk-radios__input" id="measure_scope_all" name="measure_scope" type="radio" value="all">
								<label class="govuk-label govuk-radios__label" for="changed-name">All measures</label>
							</div>
						</div>
					</fieldset>
					<div class="govuk-form-group" style="padding:0px;margin:0.5em 0px 0px 0px !important">
						<button type="submit" class="govuk-button">Filter</button>
					</div>
				</div>
			</form>
<?php
	$sql = "select m.measure_sid, mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code,
	mc.measurement_unit_code, mc.measurement_unit_qualifier_code
	from measure_components mc, measures m
	where mc.measure_sid = m.measure_sid ";
	if (strlen($regulation_id) == 7) {
		$sql .= " and m.measure_generating_regulation_id LIKE '" . $regulation_id . "%'";
	} else {
		$sql .= " and m.measure_generating_regulation_id = '" . $regulation_id . "'";
	}
	$sql .= " order by m.measure_sid, mc.duty_expression_id";
	$result = pg_query($conn, $sql);
	if  ($result) {
		$duties = array();
		while ($row = pg_fetch_array($result)) {
			$duty = new duty();
			$duty->measure_type_id = "";
			$duty->duty_expression_id = $row['duty_expression_id'];
			$duty->duty_amount = $row['duty_amount'];
			$duty->monetary_unit_code = $row['monetary_unit_code'];
			$duty->measurement_unit_code = $row['measurement_unit_code'];
			$duty->measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
			$duty->measure_sid = $row['measure_sid'];
			$duty->get_duty_string();
			array_push($duties, $duty);
		}
	}

	$today = date("Y-m-d");
	$sql = "select measure_sid, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date, m.geographical_area_id,
	m.measure_type_id, measure_generating_regulation_id, mtd.description as measure_type_description,
	g.description as geographical_area_description, m.additional_code_type_id, m.additional_code_id, m.goods_nomenclature_sid,
	m.ordernumber
	from ml.measures_real_end_dates m, measure_type_descriptions mtd, ml.ml_geographical_areas g
	where m.measure_type_id = mtd.measure_type_id
	and m.geographical_area_id = g.geographical_area_id";
	if (strlen($regulation_id) == 7) {
		$sql .= " and measure_generating_regulation_id LIKE '" . $regulation_id . "%'";
	} else {
		$sql .= " and measure_generating_regulation_id = '" . $regulation_id . "'";
	}
	if ($measure_scope == "current") {
		$sql .= " and (m.validity_end_date is null or m.validity_end_date > '" . $today . "')";
	}
	//$sql .= " ORDER BY m.validity_start_date DESC, goods_nomenclature_item_id, additional_code_type_id, additional_code_id";

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
		case "validity_start_date":
			if ($sort_direction == "asc") {
				$sql .= " ORDER BY m.validity_start_date ASC, m.goods_nomenclature_item_id, m.additional_code_type_id, m.additional_code_id";
			} else {
				$sql .= " ORDER BY m.validity_start_date DESC, m.goods_nomenclature_item_id, m.additional_code_type_id, m.additional_code_id";
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

	$result = pg_query($conn, $sql);
	echo ("<p>There are <strong>" . pg_num_rows($result) . "</strong> matching measures.");
	if  ($result) {
		$measures = array();
		while ($row = pg_fetch_array($result)) {
			$measure = new measure();
			$measure->measure_sid = $row['measure_sid'];
			$measure->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
			$measure->additional_code_id = $row['additional_code_id'];
			$measure->additional_code_type_id = $row['additional_code_type_id'];
			$measure->validity_start_date = $row['validity_start_date'];
			$measure->validity_end_date = $row['validity_end_date'];
			$measure->measure_type_id = $row['measure_type_id'];
			$measure->geographical_area_id = $row['geographical_area_id'];
			$measure->measure_generating_regulation_id = $row['measure_generating_regulation_id'];
			$measure->geographical_area_description = $row['geographical_area_description'];
			$measure->measure_type_description = $row['measure_type_description'];
			$measure->goods_nomenclature_sid = $row['goods_nomenclature_sid'];
			$measure->order_number = $row['ordernumber'];

			array_push($measures, $measure);
		}
		// Now assign the duties to the measures
		foreach ($duties as $duty) {
			foreach ($measures as $measure) {
				if ($duty->measure_sid == $measure->measure_sid) {
					array_push($measure->duty_list, $duty);
					break;
				}
			}
		}
		foreach ($measures as $measure) {
			$measure->combine_duties();
		}

		if (count($measures) > 0) {
			# Get the base URL for the sorting
			$base_url = str_replace("?" . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']);

			$qs = "";
			if ($regulation_id != "") {
				$qs .= "?base_regulation_id=" . $regulation_id;
			}
			$url = $base_url . $qs;
?>
			<div>
				<ul class="tariff_menu">
					<li><a target="_blank" href="measure_export.php?base_regulation_id=<?=$regulation_id?>">Export these measures to CSV</a></li>
					<li><a target="_blank" href="definition_export.php?base_regulation_id=<?=$regulation_id?>">Export quota definitions to CSV</a></li>
					<li><a target="_blank" href="definition_measure_export.php?base_regulation_id=<?=$regulation_id?>">Export quota definitions &amp; measures to CSV</a></li>
				</ul>
			</div>
		
			<table id="measure_table" class="govuk-table" cellspacing="0">
				<tr class="govuk-table__row">
					<th class="govuk-table__header nopad" style="width:6%">SID&nbsp;<a href="<?=$url . "&so=measure_sid&sd=asc#measure_table"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=measure_sid&sd=desc#measure_table"?>" class="table_arrow">&darr;</a></th>
					<th class="govuk-table__header" style="width:16%">Commodity&nbsp;(ID&nbsp;/&nbsp;SID)&nbsp;<a href="<?=$url . "&so=goods_nomenclature_item_id&sd=asc#measure_table"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=goods_nomenclature_item_id&sd=desc#measure_table"?>" class="table_arrow">&darr;</a></th>
					<th class="govuk-table__header" style="width:7%">Add&nbsp;code</th>
					<th class="govuk-table__header c" style="width:8%">Start&nbsp;date&nbsp;<a href="<?=$url . "&so=goods_nomenclature_item_id&sd=asc#measure_table"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=goods_nomenclature_item_id&sd=desc#measure_table"?>" class="table_arrow">&darr;</a></th>
					<th class="govuk-table__header c" style="width:7%">End&nbsp;date</th>
					<th class="govuk-table__header" style="width:14%">Geographical&nbsp;area&nbsp;<a href="<?=$url . "&so=geographical_area_id&sd=asc#measure_table"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=geographical_area_id&sd=desc#measure_table"?>" class="table_arrow">&darr;</a></th>
					<th class="govuk-table__header" style="width:15%">Type</th>
					<th class="govuk-table__header" style="width:10%">Regulation&nbsp;ID</th>
					<th class="govuk-table__header c" style="width:7%">Order&nbsp;number&nbsp;<a href="<?=$url . "&so=ordernumber&sd=asc#measure_table"?>" class="table_arrow">&uarr;</a><a href="<?=$url . "&so=ordernumber&sd=desc#measure_table"?>" class="table_arrow">&darr;</a></th>
					<th class="govuk-table__header r" style="width:10%">Duty</th>
				</tr>

<?php
		foreach ($measures as $measure) {
		//while ($row = pg_fetch_array($result)) {
			$measure_sid                = $measure->measure_sid;
			$additional_code            = $measure->additional_code_id;
			$additional_code_type       = $measure->additional_code_type_id;
			$goods_nomenclature_item_id = $measure->goods_nomenclature_item_id;
			$validity_start_date        = short_date($measure->validity_start_date . "");
			$validity_end_date          = short_date($measure->validity_end_date . "");

			$my_add_code = $additional_code_type . $additional_code;
			if ($my_add_code == "") {
				$my_add_code = "-";
			} else {
				$my_add_code = '<a href="additional_code_view.html?additional_code_type_id=' . $additional_code_type . '&additional_code_id=' . $additional_code . '">' . $my_add_code . '</a>';
			}

			$measure_type_id                	= $measure->measure_type_id;
			$geographical_area_id           	= $measure->geographical_area_id;
			$measure_generating_regulation_id	= $measure->measure_generating_regulation_id;
			$geographical_area_description  	= $measure->geographical_area_description;
			$measure_type_description       	= $measure->measure_type_description;

			$rowclass = rowclass($validity_start_date, $validity_end_date);
?>
				<tr class="govuk-table__row <?=$rowclass?>">
					<td class="govuk-table__cell vsmall nopad"><a href="measure_view.html?measure_sid=<?=$measure_sid?>"><?=$measure_sid?></a></td>
					<td class="govuk-table__cell vsmall"><a class="nodecorate" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>"><?=format_goods_nomenclature_item_id($goods_nomenclature_item_id)?></a>&nbsp;&nbsp;&nbsp;&nbsp;[<?=$measure->goods_nomenclature_sid?>]</td>
					<td class="govuk-table__cell vsmall"><?=$my_add_code?></td>
					<td class="govuk-table__cell vsmall c"><?=$validity_start_date?></td>
					<td class="govuk-table__cell vsmall c"><?=$validity_end_date?></td>
					<td class="govuk-table__cell vsmall"><a href="geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?> - <?=$geographical_area_description?></a></td>
					<td class="govuk-table__cell vsmall"><a href="measure_type_view.html?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?> - <?=$measure_type_description?></a></td>
					<td class="govuk-table__cell vsmall"><?=$measure_generating_regulation_id?></td>
					<td class="govuk-table__cell vsmall c"><a href="quota_order_number_view.html?quota_order_number_id=<?=$measure->order_number?>"><?=$measure->order_number?></a></td>
					<td class="govuk-table__cell r vsmall"><?=$measure->combined_duty?></td>
				</tr>
<?php
		}
	}
?>
	</table>
<?php			
}
}
?>
</div>
<?php
	require ("includes/footer.php")
?>