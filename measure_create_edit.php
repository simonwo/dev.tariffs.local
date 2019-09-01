<?php
    $title = "Create measure";
	require ("includes/db.php");
	$application = new application;
	$application->get_duty_expressions();
	$application->get_measurement_units();
	$application->get_measurement_unit_qualifiers();
	$application->get_measure_types();
	$application->get_geographical_areas();
	$application->get_geographical_members("1011");
	$application->get_countries_and_regions();

	$measure_sid	= get_querystring("measure_sid");
	$phase			= get_querystring("phase");
	$measure = new measure;
	if ($phase == "edit") {
		$measure->measure_sid = $measure_sid;
		$measure->populate_from_db();
		h1 ("Getting from DB " . $measure_type->measure_type_id);
		$phase = "measure_edit";
	} else {
		$measure->populate_from_cookies();
		h1 ("Getting from cookies");
		$phase = "measure_create";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measures.html">Measures</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$measure_type->measure_type_heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->
<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create measures</h1>
</div>

<form class="tariff" method="post" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
	if ($phase == "measure_type_edit") {
		echo ('<input type="hidden" name="measure_sid" value="' . $measure->measure_sid . '" />');
	}
?>

<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin base regulation fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("base_regulation");?>">
	<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Which regulation gives legal force to these measures?</h1>
		</legend>
		<span id="base_regulation_hint" class="govuk-hint">Start typing in the field to see available regulations. If the regulation is not in the list, you can <a href="regulation_create.html">add a regulation</a> from here. If you're not sure of the regulation name or ID, you can <a href="">search here</a>.</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid regulation identifier.
		</span>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$error_handler->get_value_on_error("base_regulation")?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="base_regulation" maxlength="8" name="base_regulation" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End base regulation fields //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="measure_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">When will these measures come into force?</h1>
		</legend>
		<span id="measure_start_hint" class="govuk-hint">This is the start of the measures' validity period. This will be delayed for any measures that are not approved in time, or if the generating regulation has not come into force by the date specified here.</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid start date
		</span>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_day">Day</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_start_day" maxlength="2" name="measure_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_month">Month</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_start_month" maxlength="2" name="measure_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_year">Year</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-4" id="measure_start_year" maxlength="4" name="measure_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->

<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_end_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="measure_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">When will these measures cease to be valid?</h1>
		</legend>
		<span id="measure_end_hint" class="govuk-hint">This is the end of the measures' validity period. By default, this will inherit from the generating regulation and may be open-ended.</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid end date
		</span>
		<div class="govuk-date-input" id="measure_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_end_day">Day</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_end_day" maxlength="2" name="measure_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_end_month">Month</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_end_month" maxlength="2" name="measure_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_end_year">Year</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-4" id="measure_end_year" maxlength="4" name="measure_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin measure type field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("measure_type");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type">What type of measures do you want to create?</label></h1>
			</legend>
			<span id="measure_type-error" class="govuk-error-message">
		  		Please enter a valid measure type
			</span>
			<select class="govuk-select" id="measure_type" name="measure_type">
				<option value="0">- Select measure type - </option>
<?php
	foreach ($application->measure_types as $mt) {
		echo ("<option value='" . $mt->measure_type_id . "'>" . $mt->measure_type_id . " (" . $mt->description_truncated . ")</option>");
	}
?>
			</select>
		</div>
<!-- End measure type field //-->

<!-- Begin workbasket field //-->
<!--
		<div class="govuk-form-group <?=$error_handler->get_error("workbasket");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="workbasket">What is the name of this workbasket?</label></h1>
			</legend>
			<span id="base_regulation-error" class="govuk-error-message">
	  			Please enter a valid workbasket name
			</span>
			<input class="govuk-input" id="workbasket" name="workbasket" type="text">
		</div>
//-->
<!-- End workbasket field //-->

<!-- Begin commodity field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("goods_nomenclatures");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="goods_nomenclatures">What goods will the measures apply to?</label></h1>
			</legend>
			<span id="more-detail-hint" class="govuk-hint">Enter one or more commodity codes here. Separate individual codes with line break.
If you don't know which code you need, you can find it via the  Trade Tariff tool.
You may optionally leave this field blank if you will be providing a Meursing code in the additional code field below.</span>
			<span id="base_regulation-error" class="govuk-error-message">
	  			Please enter valid 10-digit commodity codes only
			</span>
			<textarea class="govuk-textarea" id="goods_nomenclatures" name="goods_nomenclatures" rows="5"></textarea>
		</div>			
<!-- End commodity field //-->

<!-- Begin additional code field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("additional_codes");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="additional_codes">What additional code(s) will the measures apply to?</label></h1>
			</legend>
			<span id="more-detail-hint" class="govuk-hint">You can optionally specify one or more additional codes. If you do not provide a commodity code above, then you must provide at least one Meursing code here.
			Separate individual codes with comma. Separate measures will be created for every combination of commodity code and additional code.</span>
			<span id="additional-code-error" class="govuk-error-message">
	  			Please enter valid 4-digit additional codes only
			</span>
			<textarea class="govuk-textarea" id="additional_codes" name="additional_codes" rows="5"></textarea>
		</div>
<!-- End additional code field //-->

<!-- Begin geographical area field new //-->
<div class="govuk-form-group <?=$error_handler->get_error("geographical_area_id");?>">
	<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">What geography does the measure apply to?</h1>
		</legend>
		<span class="govuk-hint">Just type in the geographical area ID</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid regulation identifier.
		</span>
		<div class="govuk-date-input">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$error_handler->get_value_on_error("geographical_area_id")?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="geographical_area_id" maxlength="8" name="geographical_area_id" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End geographical area field //-->

<!-- Begin geographical area exclusion field new //-->
<div class="govuk-form-group <?=$error_handler->get_error("geographical_area_id");?>">
	<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">What geographies should be excluded?</h1>
		</legend>
		<span class="govuk-hint">Just type in the geographical area IDs, delimited by commas if there are multiple</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid geographical area IDs.
		</span>
		<div class="govuk-date-input">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$error_handler->get_value_on_error("excluded_geographical_area_id")?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="excluded_geographical_area_id" maxlength="8" name="excluded_geographical_area_id" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End geographical area field //-->

<!-- Begin measure component fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("measure_component1");?>">
	<fieldset class="govuk-fieldset" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter measure components</h1>
		</legend>
		<div class="float_left">
			<span class="govuk-hint">Component 1 - duty expression</span>
			<?=$error_handler->display_error_message("measure_component1");?>
			<select class="govuk-select" id="duty_expression1" name="duty_expression1">
				<option value="0">- Select duty expression - </option>
<?php
	foreach ($application->duty_expressions as $obj) {
		echo ("<option value='" . $obj->duty_expression_id . "'>" . $obj->duty_expression_id . " - " . $obj->description . "</option>");
	}
?>
			</select>
		</div>

		<div class="float_left">
			<span class="govuk-hint">Duty amount</span>
			<span id="base_regulation-error" class="govuk-error-message">
				Please enter a valid geographical area IDs.
			</span>
			<div class="govuk-date-input">
				<div class="govuk-date-input__item">
					<div class="govuk-form-group">
						<input value="<?=$error_handler->get_value_on_error("duty_amount")?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="duty_amount_1" maxlength="8" name="duty_amount_1" type="text">
					</div>
				</div>
			</div>
		</div>

		<div class="float_left">
			<span class="govuk-hint">Measurement unit</span>
			<span id="base_regulation-error" class="govuk-error-message">
				Please enter a valid unit.
			</span>
			<select class="govuk-select" id="measurement_unit1" name="measurement_unit1">
				<option value="0">- Select unit - </option>
<?php
	foreach ($application->measurement_units as $obj) {
		echo ("<option value='" . $obj->measurement_unit_code . "'>" . $obj->measurement_unit_code . " - " . substr($obj->description, 0, 20) . "</option>");
	}
?>
			</select>
		</div>

		<div class="float_left">
			<span class="govuk-hint">Qualifier</span>
			<span id="base_regulation-error" class="govuk-error-message">
				Please enter a valid qualifier.
			</span>
			<select class="govuk-select" id="measurement_unit_qualifier1" name="measurement_unit_qualifier1">
				<option value="0">- Select qualifier - </option>
				<?php
	foreach ($application->measurement_unit_qualifiers as $obj) {
		echo ("<option value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " - " . substr($obj->description, 0, 20) . "</option>");
	}
?>
			</select>
		</div>


	</fieldset>
</div>
<!-- End measure component fields //-->


		<button type="submit" class="govuk-button">Save measure(s)</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>