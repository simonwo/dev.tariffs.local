<?php
    $title = "Measure snapshot";
    require ("includes/db.php");
    $snapshot = new snapshot();
    $snapshot->get_parameters();
    require ("includes/header.php");
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
<?=$snapshot->error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$snapshot->error_handler->get_error("snapshot->snapshot_date_start");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter date range for snapshot</h1>
        </legend>
		<span id="snapshot_hint" class="govuk-hint">Please enter the date for which you would like to take a database snapshot.</span>
        <?=$snapshot->error_handler->display_error_message("snapshot->snapshot_date_start");?>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="day_start">Day</label>
					<input value="<?=$snapshot->day_start?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_start" maxlength="2" name="day_start" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="month_start">Month</label>
					<input value="<?=$snapshot->month_start?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_start" maxlength="2" name="month_start" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="year_start">Year</label>
					<input value="<?=$snapshot->year_start?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_start" maxlength="4" name="year_start" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->

<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$snapshot->error_handler->get_error("snapshot->snapshot_date_end");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
		<span id="snapshot_hint" class="govuk-hint">Optionally, if you would like to select data that spans a range of dates,
            please enter the end date of this range.</span>
        <?=$snapshot->error_handler->display_error_message("snapshot->snapshot_date_end");?>
		<div class="govuk-date-input" id="measure_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="day_end">Day</label>
					<input value="<?=$snapshot->day_end?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_end" maxlength="2" name="day_end" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="month_end">Month</label>
					<input value="<?=$snapshot->month_end?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_end" maxlength="2" name="month_end" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="year_end">Year</label>
					<input value="<?=$snapshot->year_end?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_end" maxlength="4" name="year_end" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin commodity range field //-->
<div class="govuk-form-group <?=$snapshot->error_handler->get_error("commodity_range");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="range">Which commodity range do you want to check?</label></h1>
	</legend>
    <span class="govuk-hint" style="max-width:70%">Enter the leading digits of the commodity range you would like to review. Please do
    not leave this field blank.</span>
	<?=$snapshot->error_handler->display_error_message("commodity_range");?>
    <input value="<?=$snapshot->range?>" class="govuk-input govuk-date-input__input govuk-input--width-10" id="range" maxlength="10" pattern="[0-9]{0,10}" name="range" type="text">
</div>
<!-- End commodity range field //-->

<!-- Begin scope fields //-->
<div class="govuk-form-group <?=$snapshot->error_handler->get_error("scope");?>">
	<fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter the geographical scope</h1>
        </legend>
		<span id="snapshot_hint" class="govuk-hint">If you don't know the geographical area ID, you can <a target="_blank" href="#">find geographical area IDs</a> here.</span>
        <?=$snapshot->error_handler->display_error_message("scope");?>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<!--<label class="govuk-label govuk-date-input__label" for="day" style="display:hidden !important">Enter geographical area ID</label>//-->
					<input value="<?=$snapshot->scope?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="scope" maxlength="4" name="scope" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity scope fields //-->



<!-- Start Submit button //-->
<div class="govuk-form-group" style="padding:0px;margin:0px">
    <button type="submit" class="govuk-button">Generate snapshot</button>
</div>
<!-- End Submit button //-->


</form>
<?php
    if (($snapshot->form_submitted == true) && (count($snapshot->error_handler->error_list) == 0)) {
        $last_goods_nomenclature_item_id = "";
        if (count($snapshot->commodities) > 0) {
?>
<h3 id="table_intro">Notes on the table below - snapshot for <?=$snapshot->snapshot_date_start?>
<?php
            if ($snapshot->snapshot_date_end != "") {
                echo (" to " . $snapshot->snapshot_date_end);
            }
?>
 for <?=$snapshot->geographical_area_description?></h3>
<p style="text-align:justify">The table below shows the entire commodity code tree and show the prevalent duties applicable
    for the jurisdiction or duty type selected. Rows where the description text is grey are those rows where
    the commodity code is not declarable (either not an end-line or has a product line suffix that is not 80).
</p>
<p><b>Next steps</b></p>
<ul class="tariff_menu" style="max-width:100%">
    <li><a target="_blank" href="coverage.html?fmt=csv&wts=1&range=<?=$snapshot->range?>&day_start=<?=$snapshot->day_start?>&month_start=<?=$snapshot->month_start?>&year_start=<?=$snapshot->year_start?>&day_end=<?=$snapshot->day_end?>&month_end=<?=$snapshot->month_end?>&year_end=<?=$snapshot->year_end?>&scope=<?=$snapshot->scope?>">Extract this data set to a CSV</a>.</li>
    <li><a target="_blank" href="coverage.html?fmt=csv&wts=1&day_start=<?=$snapshot->day_start?>&month_start=<?=$snapshot->month_start?>&year_start=<?=$snapshot->year_start?>&day_end=<?=$snapshot->day_end?>&month_end=<?=$snapshot->month_end?>&year_end=<?=$snapshot->year_end?>&scope=<?=$snapshot->scope?>">Extract the entire commodity code tree to a single CSV</a> (Warning - this will take up to 10 minutes to generate).</li>
    <li><a target="_blank" href="coverage.html?fmt=json&wts=1&range=<?=$snapshot->range?>&day_start=<?=$snapshot->day_start?>&month_start=<?=$snapshot->month_start?>&year_start=<?=$snapshot->year_start?>&day_end=<?=$snapshot->day_end?>&month_end=<?=$snapshot->month_end?>&year_end=<?=$snapshot->year_end?>&scope=<?=$snapshot->scope?>">Extract this data set to a JSON file</a>.</li>
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
        <th class="govuk-table__header r small" style="width:12%">Duty</th>
        <th class="govuk-table__header c small" style="width:4%"><abbr title="Entry Price applies">EP</abbr></th>
        <th class="govuk-table__header r small" style="width:5%">Start</th>
        <th class="govuk-table__header r small" style="width:5%">End</th>
    </tr>
<?php
            foreach ($snapshot->commodities as $commodity) {
                $padding_string = "padding:" . (8 + ($commodity->number_indents * 16)) . "px;";
                if (($commodity->leaf == 0) or ($commodity->productline_suffix != "80")) {
                    $padding_string .= "color:#999;";
                }
                $number_indents_real = $commodity->number_indents - 1;
                if ($number_indents_real == -1) {
                    $number_indents_real = 0;
                }
                $match_class = "";
                if ($commodity->assigned == true) {
                    $match_class = " assigned";
                }
                elseif ($scope == "mfn") {
                    if (($commodity->leaf == 1) && ($commodity->combined_duty == "")) {
                        $match_class = " match_error";
                    }
                }
            if (count($commodity->measure_list) == 0) {
                    // Just show the commodity
?>
    <tr class="govuk-table__row<?=$match_class?>" valign="top">
        <td class="govuk-table__cell vsmall nopad">
            <a class="nodecorate vsmall" target="_blank" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$commodity->goods_nomenclature_item_id?>&productline_suffix=<?=$commodity->productline_suffix?>"><?=format_goods_nomenclature_item_id($commodity->goods_nomenclature_item_id)?></a>
        </td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->productline_suffix?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$number_indents_real?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->leaf?></td>
        <td class="govuk-table__cell vsmall c"><?=yn($commodity->assigned)?></td>
        <td class="govuk-table__cell vsmall" style="<?=$padding_string?>"><?=$commodity->format_description()?></td>
        <td class="govuk-table__cell vsmall l">&nbsp;</td>
        <td class="govuk-table__cell vsmall r">&nbsp;</td>
        <td class="govuk-table__cell vsmall r">&nbsp;</td>
        <td class="govuk-table__cell vsmall r">&nbsp;</td>
        <td class="govuk-table__cell vsmall r">&nbsp;</td>
    </tr>
<?php                    
                } else {
                    foreach ($commodity->measure_list as $measure) {
?>
    <tr class="govuk-table__row<?=$match_class?>" valign="top">
        <td class="govuk-table__cell vsmall nopad">
            <a class="nodecorate vsmall" target="_blank" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$commodity->goods_nomenclature_item_id?>&productline_suffix=<?=$commodity->productline_suffix?>"><?=format_goods_nomenclature_item_id($commodity->goods_nomenclature_item_id)?></a>
        </td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->productline_suffix?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$number_indents_real?></a></td>
        <td class="govuk-table__cell vsmall c"><?=$commodity->leaf?></td>
        <td class="govuk-table__cell vsmall c"><?=yn($commodity->assigned)?></td>
        <td class="govuk-table__cell vsmall" style="<?=$padding_string?>"><?=$commodity->format_description()?></td>
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
        <td class="govuk-table__cell vsmall c"><?=$measure->entry_price_string?></td>
        <td class="govuk-table__cell vsmall r"><?=short_date($measure->validity_start_date)?></td>
        <td class="govuk-table__cell vsmall r"><?=short_date($measure->validity_end_date)?></td>
    </tr>
<?php
                    }
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
    require ("includes/footer.php");
?>