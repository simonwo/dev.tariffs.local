<?php
	require ("includes/db.php");
    $application = new application;
    $measure_type_id    = get_querystring("measure_type_id");
    $phase              = get_querystring("phase");
    $measure_type = new measure_type;
    if ($phase == "edit") {
        $measure_type->measure_type_id = $measure_type_id;
        $measure_type->populate_from_db();
        #h1 ("Getting from DB " . $measure_type->measure_type_id);
        $phase = "measure_type_edit";
    } else {
        $measure_type->populate_from_cookies();
        #h1 ("Getting from cookies");
        $phase = "measure_type_create";
    }

    $application->get_measure_types();
	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measure_types.html">Measure types</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$measure_type->measure_type_heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$measure_type->measure_type_heading?></h1>
</div>

<form class="tariff" method="post" action="/actions/measure_type_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "measure_type_edit") {
        echo ('<input type="hidden" name="measure_type_id" value="' . $measure_type->measure_type_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin measure type ID field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measure_type_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_measure_type_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type_id">What is the measure type ID?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a 3-digit numeric string.</span>
	<?=$error_handler->display_error_message("measure_type_id");?>
	<input <?=$measure_type->disable_measure_type_id_field?> value="<?=$measure_type->measure_type_id?>" class="govuk-input" style="width:10%" id="measure_type_id" name="measure_type_id" max="999" type="text" maxlength="3" size="3">
</div><!-- End measure type ID field //-->

<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the description of the measure type?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a brief description of the measure type - this will appear on the UK Trade Tariff service
    and on the Trade Helpdesk.</span>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" name="goods_nomenclatures" rows="2"><?=$measure_type->description?></textarea>
</div>
<!-- End description field //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Validity start date</h1>
		</legend>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$measure_type->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$measure_type->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$measure_type->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->

<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_end_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_end_date" class="govuk-fieldset__heading" style="max-width:100%;">Validity end date</h1>
		</legend>
        <span class="govuk-hint">Please leave blank unless you explicitly want to end date a measure type.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$measure_type->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$measure_type->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$measure_type->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin trade movement code field //-->
<div class="govuk-form-group <?=$error_handler->get_error("trade_movement_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="trade_movement_code">What is the trade movement code?</label></h1>
	</legend>
    <span class="govuk-hint">The trade movement code identifies if a measure type is used to describe an import or export measure or a measure that could plausibly be either.</span>
	<?=$error_handler->display_error_message("trade_movement_code");?>
	<select class="govuk-select" id="trade_movement_code" name="trade_movement_code">
		<option value="">- Select trade movement code - </option>
<?php
	foreach ($measure_type->trade_movement_codes as $obj) {
        if ($obj[0] == $measure_type->trade_movement_code) {
            echo ("<option selected value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>");
        } else {
            echo ("<option value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End trade movement code field //-->

<!-- Begin priority code field //-->
<div class="govuk-form-group <?=$error_handler->get_error("priority_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
    <h1 id="heading_priority_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="priority_code">What is the priority code?</label></h1>
	</legend>
    <span class="govuk-hint">The priority code indicates the order in which measures must be applied</span>
	<?=$error_handler->display_error_message("priority_code");?>
	<select class="govuk-select" id="priority_code" name="priority_code">
		<option value="">- Select priority code - </option>
<?php
	foreach ($measure_type->priority_codes as $obj) {
        if ($obj[0] == $measure_type->priority_code) {
            echo ("<option selected value='" . $obj[0] . "'>" . $obj[0] . "</option>");
        } else {
            echo ("<option value='" . $obj[0] . "'>" . $obj[0] . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End priority code field //-->

<!-- Begin Measure component applicable code field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measure_component_applicable_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
    <h1 id="heading_initial_volume" class="govuk-fieldset__heading" style="max-width:100%;"><label for="initial_volume">Can measure components (duties) be applied to this code?</label></h1>
	</legend>
    <span class="govuk-hint">Code which indicates whether or not a duty expression must be defined for a measure type. The most
common value is "2".</span>
	<?=$error_handler->display_error_message("measure_component_applicable_code");?>
	<select class="govuk-select" id="measure_component_applicable_code" name="measure_component_applicable_code">
		<option value="">- Select component applicable code - </option>
<?php
	foreach ($measure_type->measure_component_applicable_codes as $obj) {
        if ($obj[0] == $measure_type->measure_component_applicable_code) {
            echo ("<option selected value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>");
        } else {
            echo ("<option value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End Measure component applicable code field //-->

<!-- Begin origin dest code field //-->
<div class="govuk-form-group <?=$error_handler->get_error("origin_dest_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
    <h1 id="heading_initial_volume" class="govuk-fieldset__heading" style="max-width:100%;"><label for="origin_dest_code">What is the origin / destination code?</label></h1>
	</legend>
    <span class="govuk-hint">The code which indicates if the geographical area is an origin or a destination.</span>
	<?=$error_handler->display_error_message("origin_dest_code");?>
	<select class="govuk-select" id="origin_dest_code" name="origin_dest_code">
		<option value="">- Select origin /destination code - </option>
<?php
	foreach ($measure_type->origin_dest_codes as $obj) {
        if (strval($obj[0]) == strval($measure_type->origin_dest_code)) {
            echo ("<option selected value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>\n");
        } else {
            echo ("<option value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>\n");
        }
	}
?>
	</select>
</div>
<!-- End origin dest code field //-->

<!-- Order number capture code field //-->
<div class="govuk-form-group <?=$error_handler->get_error("order_number_capture_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
    <h1 id="heading_initial_volume" class="govuk-fieldset__heading" style="max-width:100%;"><label for="order_number_capture_code">Can quota order numbers be captured against measures of this type?</label></h1>
	</legend>
    <span class="govuk-hint">For quota measure types, entering a quota order number is mandatory.</span>
	<?=$error_handler->display_error_message("order_number_capture_code");?>
	<select class="govuk-select" id="order_number_capture_code" name="order_number_capture_code">
		<option value="">- Select order number capture code - </option>
<?php
	foreach ($measure_type->order_number_capture_codes as $obj) {
        if ($obj[0] == $measure_type->order_number_capture_code) {
            echo ("<option selected value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>\n");
        } else {
            echo ("<option value='" . $obj[0] . "'>" . $obj[0] . " - " . $obj[1] . "</option>\n");
        }
	}
?>
	</select>
</div>
<!-- End Order number capture code field //-->

<!-- Begin explosion level field //-->
<input type="hidden" name="measure_explosion_level" id="measure_explosion_level" value="10" />
<!-- End explosion level field //-->


<!-- Begin measure type series field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measure_type_series_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
    <h1 id="heading_initial_volume" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type_series_id">To which measure type series does this measure type belong?</label></h1>
	</legend>
    <span class="govuk-hint">Measure type series group measure together according to function.</span>
	<?=$error_handler->display_error_message("measure_type_series_id");?>
	<select class="govuk-select" id="measure_type_series_id" name="measure_type_series_id">
		<option value="">- Select series identifier - </option>
<?php
	foreach ($measure_type->measure_type_series as $obj) {
        if ($obj->measure_type_series_id == $measure_type->measure_type_series_id) {
            echo ("<option selected value='" . $obj->measure_type_series_id . "'>" . $obj->measure_type_series_id . " - " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->measure_type_series_id . "'>" . $obj->measure_type_series_id . " - " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End measure type series field //-->



		<button type="submit" class="govuk-button">Save measure type</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>