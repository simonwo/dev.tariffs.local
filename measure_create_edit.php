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
		$phase = "measure_edit";
	} else {
		$measure->populate_from_cookies();
		$phase = "measure_create";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measures.html">Measures</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$measure->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->
<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$measure->heading?></h1>
</div>

<form class="tariff" method="get" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
	if ($phase == "measure_edit") {
		echo ('<input type="hidden" name="measure_sid" value="' . $measure->measure_sid . '" />');
	}
?>

<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin base regulation fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("measure_generating_regulation_id");?>">
	<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Which regulation gives legal force to these measures?</h1>
		</legend>
		<span id="base_regulation_hint" class="govuk-hint">Start typing in the field to see available regulations. If the regulation is not in the list, you can <a target="_blank" href="/regulation_create_edit.html?action=new&phase=regulation_create">add a regulation</a> from here. If you're not sure of the regulation name or ID, you can <a href="/regulations.html">search here</a>.</span>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid regulation identifier.
		</span>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$measure->measure_generating_regulation_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="measure_generating_regulation_id" maxlength="8" name="measure_generating_regulation_id" type="text">
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
					<input value="<?=$measure->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_start_day" maxlength="2" name="measure_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_month">Month</label>
					<input value="<?=$measure->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_start_month" maxlength="2" name="measure_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_year">Year</label>
					<input value="<?=$measure->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="measure_start_year" maxlength="4" name="measure_start_year" type="text" pattern="[0-9]*">
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
					<input value="<?=$measure->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_end_day" maxlength="2" name="measure_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_end_month">Month</label>
					<input value="<?=$measure->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_end_month" maxlength="2" name="measure_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_end_year">Year</label>
					<input value="<?=$measure->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="measure_end_year" maxlength="4" name="measure_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin measure type field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("measure_type_id");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type_id">What type of measure do you want to create?</label></h1>
			</legend>
			<span id="measure_type-error" class="govuk-error-message">
		  		Please enter a valid measure type
			</span>
			<select class="govuk-select" id="measure_type_id" name="measure_type_id">
				<option value="0">- Select measure type - </option>
<?php
	foreach ($application->measure_types as $mt) {
		if ($mt->measure_type_id == $measure->measure_type_id) {
			echo ("<option selected value='" . $mt->measure_type_id . "'>" . $mt->measure_type_id . " " . $mt->description_truncated . "</option>");
		} else {
			echo ("<option value='" . $mt->measure_type_id . "'>" . $mt->measure_type_id . " " . $mt->description_truncated . "</option>");
		}
	}
?>
			</select>
		</div>
<!-- End measure type field //-->

<!-- Begin commodity field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("goods_nomenclature_item_id");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="goods_nomenclatures">What goods will the measures apply to?</label></h1>
			</legend>
			<!--
			<span id="more-detail-hint" class="govuk-hint">Enter one or more commodity codes here. Separate individual codes with line break.
If you don't know which code you need, you can find it via the  Trade Tariff tool.
You may optionally leave this field blank if you will be providing a Meursing code in the additional code field below.</span>
//-->
			<span id="more-detail-hint" class="govuk-hint">Enter one commodity code only.</span>
			<span id="base_regulation-error" class="govuk-error-message">
	  			Please enter valid 10-digit commodity codes only
			</span>
			<div class="govuk-date-input">
				<div class="govuk-date-input__item">
					<div class="govuk-form-group">
						<input value="<?=$measure->goods_nomenclature_item_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="goods_nomenclature_item_id" maxlength="14" name="goods_nomenclature_item_id" type="text">
					</div>
				</div>
			</div>
		</div>			
<!-- End commodity field //-->

<!-- Begin additional code field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("additional_codes");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="additional_codes">What additional code will the measures apply to?</label></h1>
			</legend>
			<span id="more-detail-hint" class="govuk-hint">Enter up to one additional code.</span>
			<span id="additional-code-error" class="govuk-error-message">
	  			Please enter valid 4-digit additional codes only
			</span>
			<div class="govuk-date-input">
				<div class="govuk-date-input__item">
					<div class="govuk-form-group">
						<input value="<?=$measure->additional_code_type_id?><?=$measure->additional_code_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="additional_code" maxlength="4" name="additional_code" type="text">
					</div>
				</div>
			</div>
		</div>
<!-- End additional code field //-->

<!-- Begin geographical area field new //-->
<div class="govuk-form-group <?=$error_handler->get_error("geographical_area_id");?>">
	<fieldset class="govuk-fieldset" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">What geography does the measure apply to?</h1>
		</legend>
		<span id="base_regulation-error" class="govuk-error-message">
	  		Please enter a valid geographical area ID
		</span>
		<div class="govuk-date-input">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$measure->geographical_area_id?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="geographical_area_id" maxlength="8" name="geographical_area_id" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End geographical area field //-->

<!-- Begin order number field new //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<fieldset class="govuk-fieldset" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter the quota order number</h1>
		</legend>
		<span id="ordernumber-error" class="govuk-error-message">
	  		Please enter a valid quota order number
		</span>
		<div class="govuk-date-input">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<input value="<?=$measure->ordernumber?>" class="govuk-input govuk-date-input__input govuk-input--width-8" id="ordernumber" maxlength="6" name="ordernumber" type="text">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End order number field //-->




		<button type="submit" class="govuk-button">Save measure(s)</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>