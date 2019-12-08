<?php
    $title = "Measure snapshot";
    require ("includes/db.php");
    // Get format
    $fmt = get_querystring("fmt");
    if ($fmt == "csv") {
        $write_to_csv = true;
    } else {
        $write_to_csv = false;
    }

    // Work out whether to write to screen or not
    if ($write_to_csv == true) {
        $write_to_screen = get_querystring("wts");
        if ($write_to_screen == false) {
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=" . $scope . ".csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $delimiter = "\n";
            $start_string = "";
            $end_string = "";
        } else {
            $delimiter = "<br />";
            $start_string = "<pre>";
            $end_string = "</pre>";
        }
    
        echo ($start_string);
    }

    $error_handler = new error_handler;
    if ($write_to_csv == false) {
        require ("includes/header.php");
        // Get countries
        $countries = array_map('str_getcsv', file('csv/countries.csv'));
    }


    if (!empty($_REQUEST)) {
        // Get snapshot day start
        $day_start = get_querystring("day_start") . "";
        $month_start = get_querystring("month_start") . "";
        $year_start = get_querystring("year_start") . "";
        $snapshot_date_start = to_date_string($day_start, $month_start, $year_start);
        $valid_date_start = checkdate($month_start, $day_start, $year_start);
        if ($valid_date_start != 1) {
            array_push($error_handler->error_list, "snapshot_date_start");
        }

        // Get snapshot day end
        $day_end = get_querystring("day_end") . "";
        $month_end = get_querystring("month_end") . "";
        $year_end = get_querystring("year_end") . "";
        if (($day_end != "") && ($month_end != "") && ($year_end != "")) {
            $snapshot_date_end = to_date_string($day_end, $month_end, $year_end);
            $valid_date_end = checkdate($month_end, $day_end, $year_end);
            if ($valid_date_end != 1) {
                array_push($error_handler->error_list, "snapshot_date_end");
            }
        } else {
            $snapshot_date_end = "";
        }
        
        // Get commodity range
        $range = get_querystring("range") . "";
        if ($range == "" ) {
            if ($write_to_csv == false) {
                array_push($error_handler->error_list, "commodity_range");
            } else {
                $range = "";
            }
        }

        // Get scope
        $scope = strtoupper(get_querystring("scope") . "");
        if ($scope == "" ) {
            if ($write_to_csv == false) {
                array_push($error_handler->error_list, "scope");
            }
        }
        $form_submitted = true;
    } else {
        $day_start = "";
        $month_start = "";
        $year_start = "";
        $day_end = "";
        $month_end = "";
        $year_end = "";
        $scope = "";
        $range = "";
        $form_submitted = false;
    }
    if ($write_to_csv == false) {
    ?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">Measure snapshot</li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Measure snapshot</h1>
    </div>

<form action="coverage.html#table_intro">
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("snapshot_date_start");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter date range for snapshot</h1>
        </legend>
		<span id="snapshot_hint" class="govuk-hint">Please enter the date for which you would like to take a database snapshot.</span>
        <?=$error_handler->display_error_message("snapshot_date_start");?>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="day_start">Day</label>
					<input value="<?=$day_start?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_start" maxlength="2" name="day_start" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="month_start">Month</label>
					<input value="<?=$month_start?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_start" maxlength="2" name="month_start" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="year_start">Year</label>
					<input value="<?=$year_start?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_start" maxlength="4" name="year_start" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->

<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("snapshot_date_end");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
<!--		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter end date for snapshot</h1>
        </legend>
//-->        
		<span id="snapshot_hint" class="govuk-hint">Optionally, if you would like to select data that spans a range of dates,
            please enter the end date of this range.</span>
        <?=$error_handler->display_error_message("snapshot_date_end");?>
		<div class="govuk-date-input" id="measure_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="day_end">Day</label>
					<input value="<?=$day_end?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_end" maxlength="2" name="day_end" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="month_end">Month</label>
					<input value="<?=$month_end?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_end" maxlength="2" name="month_end" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="year_end">Year</label>
					<input value="<?=$year_end?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_end" maxlength="4" name="year_end" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin commodity range field //-->
<div class="govuk-form-group <?=$error_handler->get_error("commodity_range");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="range">Which commodity range do you want to check?</label></h1>
	</legend>
    <span class="govuk-hint" style="max-width:70%">Enter the leading digits of the commodity range you would like to review. Please do
    not leave this field blank.</span>
	<?=$error_handler->display_error_message("commodity_range");?>
    <input value="<?=$range?>" class="govuk-input govuk-date-input__input govuk-input--width-10" id="range" maxlength="10" pattern="[0-9]{0,10}" name="range" type="text">
</div>
<!-- End commodity range field //-->

<!-- Begin scope fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("scope");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter the geographical scope</h1>
        </legend>
		<span id="snapshot_hint" class="govuk-hint">If you don't know the geographical area ID, you can <a target="_blank" href="#">find geographical area IDs</a> here.</span>
        <?=$error_handler->display_error_message("scope");?>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<!--<label class="govuk-label govuk-date-input__label" for="day" style="display:hidden !important">Enter geographical area ID</label>//-->
					<input value="<?=$scope?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="scope" maxlength="4" name="scope" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity scope fields //-->


    
    <div class="govuk-form-group" style="padding:0px;margin:0px">
        <button type="submit" class="govuk-button">Generate snapshot</button>
    </div>


</form>
<?php
    }
    if (($form_submitted == true) && (count($error_handler->error_list) == 0)) {

        // Get the duties / measure components
$range_len = strlen($range);
if ($range_len != 0) {
    $range_clause = " and left(m.goods_nomenclature_item_id, " . $range_len . ") = '" . $range . "' ";
} else {
    $range_clause = "";
}

if (($scope == "mfn") || ($scope == "1011")) {
    $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, mc.duty_expression_id,
    mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
    m.validity_start_date, m.validity_end_date, mtd.description as measure_type_description
    from measure_type_descriptions mtd, ml.measures_real_end_dates m
    left outer join measure_components mc
    on m.measure_sid = mc.measure_sid
    where m.measure_type_id = mtd.measure_type_id
    and m.validity_start_date <= '" . $snapshot_date_end . "'
    and (m.validity_end_date is null or m.validity_end_date >= '" . $snapshot_date_start . "')
    and m.measure_type_id in ('103', '105') " . $range_clause . 
    " order by m.goods_nomenclature_item_id, m.validity_start_date, mc.duty_expression_id;";
} else {
    $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, mc.duty_expression_id,
    mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
    m.validity_start_date, m.validity_end_date, mtd.description as measure_type_description
    from measure_type_descriptions mtd, ml.measures_real_end_dates m
    left outer join measure_components mc
    on m.measure_sid = mc.measure_sid
    where m.measure_type_id = mtd.measure_type_id
    and m.validity_start_date >= '" . $snapshot_date_start . "' and (m.validity_end_date is null or m.validity_end_date <= '" . $snapshot_date_end . "')
    and m.measure_type_id in ('142', '145') " . $range_clause . 
    "and geographical_area_id = '" . $scope . "'
    order by m.goods_nomenclature_item_id, m.validity_start_date, mc.duty_expression_id
    ";
}

//prend ($sql);

$result = pg_query($conn, $sql);
$duties = array();
if ($result) {
    while ($row = pg_fetch_array($result)) {
        $duty = new duty();
        $duty->measure_sid                      = $row['measure_sid'];
        $duty->goods_nomenclature_item_id       = $row['goods_nomenclature_item_id'];
        $duty->measure_type_id                  = $row['measure_type_id'];
        $duty->duty_expression_id               = $row['duty_expression_id'];
        $duty->duty_amount                      = $row['duty_amount'];
        $duty->monetary_unit_code               = $row['monetary_unit_code'];
        $duty->measurement_unit_code            = $row['measurement_unit_code'];
        $duty->measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
        $duty->measure_type_description         = $row['measure_type_description'];
        $duty->validity_start_date              = $row['validity_start_date'];
        $duty->validity_end_date                = $row['validity_end_date'];
        
        if ($duty->duty_expression_id == Null) {
            $duty->entry_price_applies              = true;
            $duty->duty_string = "Entry price";
        } else {
            $duty->get_duty_string();
        }
        array_push($duties, $duty);
    }
}
// Form the duties into measure objects
$measures = array();
foreach ($duties as $duty) {
    $measure_sid = $duty->measure_sid;
    $matched = false;
    foreach ($measures as $measure) {
        $measure_sid2 = $measure->measure_sid;
        if ($measure_sid == $measure_sid2) {
            array_push($measure->duty_list, $duty);
            $matched = true;
            break;
        }
    }
    if ($matched == false) {
        $measure = new measure();
        $measure->measure_sid = $duty->measure_sid;
        $measure->goods_nomenclature_item_id = $duty->goods_nomenclature_item_id;
        $measure->measure_type_id = $duty->measure_type_id;
        $measure->measure_type_description = $duty->measure_type_description;
        $measure->validity_start_date = $duty->validity_start_date;
        $measure->validity_end_date = $duty->validity_end_date;
        array_push($measure->duty_list, $duty);
        array_push($measures, $measure);
    }
}

foreach ($measures as $measure) {
    $measure->combine_duties();
}

// Next - SQL to get the commodity codes
$sql = "select goods_nomenclature_item_id, producline_suffix, number_indents,
description, leaf, significant_digits, validity_start_date, validity_end_date
from ml.goods_nomenclature_export_new ('" . $range . "%', '" . $snapshot_date_start . "')
order by goods_nomenclature_item_id, producline_suffix";

//pre($sql);

$result = pg_query($conn, $sql);
$commodities = array();
if ($result) {
    while ($row = pg_fetch_array($result)) {
        $commodity	= new goods_nomenclature;
        $commodity->goods_nomenclature_item_id  = $row['goods_nomenclature_item_id'];
        $commodity->productline_suffix          = $row['producline_suffix'];
        $commodity->number_indents              = $row['number_indents'];
        $commodity->description                 = $row['description'];
        $commodity->leaf                        = yn($row['leaf']);
        $commodity->significant_digits          = $row['significant_digits'];
        $commodity->validity_start_date         = $row['validity_start_date'];
        $commodity->validity_end_date           = $row['validity_end_date'];
        if ($commodity->significant_digits != 2) {
            $commodity->number_indents += 1;
        }
        array_push($commodities, $commodity);
    }
}

// Finally, assign the measures to the commodities
foreach ($measures as $measure) {
    foreach ($commodities as $commodity) {
        if ($measure->goods_nomenclature_item_id == $commodity->goods_nomenclature_item_id) {
            if ($commodity->productline_suffix == "80") {
                array_push($commodity->measure_list, $measure);
                $commodity->measure_sid = $measure->measure_sid;
                $commodity->assigned = true;
                break;
            }
        }
    }
}

// Now inherit down where appropriate
$commodity_count = count($commodities);
for ($i = 0; $i < $commodity_count; $i++) {
    $commodity = $commodities[$i];
    if ($commodity->assigned == true) {
        for ($j = ($i + 1); $j < $commodity_count; $j++) {
            $commodity2 = $commodities[$j];
            if ($commodity2->number_indents > $commodity->number_indents) {
                if ($commodity2->assigned == false) {
                    if ($commodity2->productline_suffix == "80") {
                        $commodity2->measure_list = $commodity->measure_list;
                        foreach ($commodity2->measure_list as $measure) {
                            h1 ("before" . $measure->goods_nomenclature_item_id);
                            $measure->goods_nomenclature_item_id = $commodity2->goods_nomenclature_item_id;
                            h1 ("after" . $measure->goods_nomenclature_item_id);
                        }
                    }
                } /* else {
                    if ($commodity2->measure_type_id != '105') {
                        break;
                    }
                } */
            } else {
                break;
            }
        }
    }
}
$last_goods_nomenclature_item_id = "";
if (count($commodities) > 0) {
    if ($write_to_csv == false) {
?>
<h3 id="table_intro">Notes on the table below - snapshot for <?=$snapshot_date_start?>
<?php
    if ($snapshot_date_end != "") {
        echo (" to " . $snapshot_date_end);
    }
?>
</h3>
<p style="text-align:justify">The table below shows the entire commodity code tree and show the prevalent duties applicable
    for the jurisdiction or duty type selected. Rows where the description text is grey are those rows where
    the commodity code is not declarable (either not an end-line or has a product line suffix that is not 80).
</p>

<p><b>Next steps</b></p>
<ul class="tariff_menu" style="max-width:100%">
    <li><a target="_blank" href="coverage.html?fmt=csv&wts=1&range=<?=$range?>&day=<?=$day?>&month=<?=$month?>&year=<?=$year?>&scope=<?=$scope?>">Extract this data set to a single CSV</a>.</li>
    <li><a target="_blank" href="coverage.html?fmt=csv&wts=1&day=<?=$day?>&month=<?=$month?>&year=<?=$year?>&scope=<?=$scope?>">Extract the entire data set to a single CSV</a> (Warning - this will take up to 10 minutes to generate).</li>
</ul>
<table cellspacing="0" class="govuk-table" id="table">
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad small" style="width:10%">Commodity</th>
        <th class="govuk-table__header c small" style="width:4%">Suffix</th>
        <th class="govuk-table__header c small" style="width:4%">Indent</th>
        <th class="govuk-table__header c small" style="width:4%">End-line?</th>
        <th class="govuk-table__header c small" style="width:6%">Assigned</th>
        <th class="govuk-table__header small" style="width:30%">Description</th>
        <th class="govuk-table__header l small" style="width:14%">Measure type</th>
        <th class="govuk-table__header r small" style="width:16%">Duty</th>
        <th class="govuk-table__header r small" style="width:6%">Start</th>
        <th class="govuk-table__header r small" style="width:6%">End</th>
    </tr>
<?php
    } else {
        ob_start();
        echo "Commodity code,Suffix,Indent,End-line?,Description,Assigned,Measure type,Origin,Origin exclusions,Duties";
        echo ($delimiter);
    }
    foreach ($commodities as $commodity) {
        $padding_string = "padding:" . (8 + ($commodity->number_indents * 16)) . "px;";
        if (($commodity->leaf == 0) or ($commodity->productline_suffix != "80")) {
            $padding_string .= "color:#999;";
        }
        $number_indents_real = $commodity->number_indents - 1;
        if ($number_indents_real == -1) {
            $number_indents_real = 0;
        }
        if ($commodity->measure_list == 0) {

        } else {
            foreach ($commodity->measure_list as $measure) {
                $match_class = "";
                if ($commodity->assigned == true) {
                    $match_class = " assigned";
                }
                if ($write_to_csv == false) {
?>
    <tr class="govuk-table__row<?=$match_class?>" valign="top">
<?php
                    if ($measure->goods_nomenclature_item_id != $last_goods_nomenclature_item_id) {
?>
        <td class="govuk-table__cell vsmall nopad">
            <a class="nodecorate vsmall" target="_blank" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$commodity->goods_nomenclature_item_id?>&productline_suffix=<?=$commodity->productline_suffix?>"><?=format_goods_nomenclature_item_id($commodity->goods_nomenclature_item_id)?></a>
        </td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->productline_suffix?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$number_indents_real?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->leaf?></td>
        <td class="govuk-table__cell vsmall c"><?=yn($commodity->assigned)?></td>
        <td class="govuk-table__cell vsmall" style="<?=$padding_string?>"><?=$commodity->format_description()?></td>
<?php                    
                    } else {
                        echo ('<td colspan="6" class="govuk-table__cell vsmall c">&nbsp;xx</td>');
                    }
                    $last_goods_nomenclature_item_id = $commodity->goods_nomenclature_item_id;
?>

        <td class="govuk-table__cell vsmall l"><?=$measure->measure_type_id?>&nbsp;<?=$measure->measure_type_description?></td>
        <td class="govuk-table__cell vsmall r">
<?php
                    if ($commodity->assigned == true) {
                        echo ("<a href='measure_view.html?measure_sid=" . $measure->measure_sid . "'>" . $measure->combined_duty . "</a>");
                    } else {
                        echo ($measure->combined_duty);
                    }
?>
        </td>
        <td class="govuk-table__cell vsmall r"><?=short_date($measure->validity_start_date)?></td>
        <td class="govuk-table__cell vsmall r"><?=short_date($measure->validity_end_date)?></td>
<?php
                }
        }
?>
    </tr>
<?php
            } else {
            echo ("'" . $commodity->goods_nomenclature_item_id . "',");
            echo ("'" . $commodity->productline_suffix . "',");
            echo ($number_indents_real . ",");
            echo ("'" . yn($commodity->leaf) . "',");
            echo ("'" . $commodity->format_description2() . "',");
            echo ("'" . yn($commodity->assigned) . "',");
            echo ("'" . $measure->measure_type_id . $measure->measure_type_description . "',");
            echo ("'" . $commodity->geographical_area_id . "',");
            echo ("'" . $commodity->mega_string . "',");
            echo ("'" . $commodity->combined_duty . "'");
            echo ($delimiter);
            ob_flush();
            flush();
        }
        $last_goods_nomenclature_item_id = $commodity->goods_nomenclature_item_id;
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
    if ($write_to_csv == false) {
        require ("includes/footer.php");
    } else {
        echo ($end_string);
    }
?>