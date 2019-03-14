<?php
	require ("includes/db.php");
	$application = new application;
	$application->get_measure_types();
	$application->get_geographical_areas();
	$application->get_geographical_members("1011");
	$application->get_countries_and_regions();
	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create measures</h1>
</div>

<form class="tariff" method="post" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="1" />
<!-- Start error handler //-->
<div class="govuk-error-summary" aria-labelledby="error-summary-title" role="alert" tabindex="-1" data-module="error-summary">
  <h2 class="govuk-error-summary__title" id="error-summary-title">
	There is a problem
  </h2>
  <div class="govuk-error-summary__body">
	<ul class="govuk-list govuk-error-summary__list">
	  <li>
		<a href="#passport-issued-error">The date your passport was issued must be in the past</a>
	  </li>
	  <li>
		<a href="#postcode-error">Enter a postcode, like AA1 1AA</a>
	  </li>
	</ul>
  </div>
</div>
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
			<span id="more-detail-hint" class="govuk-hint">You can optionally specify one or more additional codes. If you do not provide a commodity code above, then you must provide at least one Meursing code here. Separate individual codes with comma.Separate measures will be created for every combination of commodity code and additional code.</span>
			<span id="additional-code-error" class="govuk-error-message">
	  			Please enter valid 4-digit additional codes only
			</span>
			<textarea class="govuk-textarea" id="additional_codes" name="additional_codes" rows="5"></textarea>
		</div>
<!-- End additional code field //-->

<!-- Begin origins field //-->
		<div class="govuk-form-group" <?=$error_handler->get_error("origins");?>>
			<fieldset class="govuk-fieldset">
				<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
					<h1 class="govuk-fieldset__heading" style="max-width:100%">Which origins will the measure(s) apply to?</h1>
				</legend>
				<span id="changed-name-hint" class="govuk-hint">You can specify a single country or territory, or a pre-defined group of countries, or select
'Erga Omnes' to apply the quota to all origins. If the group you need is not in the list, you can add it from here.</span>
				<div class="clearer"><!--&nbsp;//--></div>
<!-- Begin Erga Omnes block //-->
				<div class="govuk-radios govuk-radios--inline">
					<div class="govuk-radios__item break">
						<input type="radio" class="govuk-radios__input" name="geographical_area_id" id="geographical_area_id_all" value="1011" />
						<label class="govuk-label govuk-radios__label" for="geographical_area_id_all">Erga Omnes</label>
					</div>
					<div class="hidden govuk-inset-text indented" style="clear:both" id="geographical_area_id_erga_omnes_content">
						<div class="govuk-form-group">
							<label for="measure_type">Select an exclusion</label><br />
							<select class="govuk-select" id="measure_type" name="sort">
								<option value="0">- Select a group of countries - </option>
<?php
	foreach ($application->members as $obj) {
		echo ("<option value='" . $obj->geographical_area_id . "'>" . $obj->geographical_area_id . " (" . $obj->description . ")</option>\n");
	}
?>
							</select>
						</div>
					</div>
<!-- End Erga Omnes block //-->

					<div class="govuk-radios__item break">
						<input type="radio" class="govuk-radios__input" name="geographical_area_id" id="geographical_area_id_group" value="0" />
						<label class="govuk-label govuk-radios__label" for="geographical_area_id_group">Select a group of countries</label>
					</div>
					<div class="hidden govuk-inset-text indented" style="clear:both" id="geographical_area_id_group_content">
						<div class="govuk-form-group">
							<label for="measure_type">Select a group of countries</label><br />
							<select class="govuk-select" id="measure_type" name="sort">
								<option value="0">- Select a group of countries - </option>
<?php
	foreach ($application->geographical_areas as $obj) {
		echo ("<option value='" . $obj->geographical_area_id . "'>" . $obj->geographical_area_id . " (" . $obj->description . ")</option>\n");
	}
?>
							</select>
						</div>

						<div class="govuk-form-group">
							<label for="measure_type">If you want to exclude countries, enter them here:</label><br />
							<select class="govuk-select" id="measure_type" name="sort">
								<option value="0">- Select an exclusion - </option>
							</select>
						</div>
					</div>
					<div class="govuk-radios__item break">
						<input type="radio" class="govuk-radios__input" name="geographical_area_id" id="geographical_area_id_country" value="0" />
						<label class="govuk-label govuk-radios__label" for="geographical_area_id_country">Select a country or territory</label>
					</div>
					<div class="hidden govuk-inset-text indented" style="clear:both" id="geographical_area_id_country_content">
					<div class="govuk-form-group">
							<label for="measure_type">Select a country or territory</label><br />
							<select class="govuk-select" id="measure_type" name="sort">
								<option value="0">- Select a country or territory - </option>
<?php
	foreach ($application->countries_and_regions as $obj) {
		echo ("<option value='" . $obj->geographical_area_id . "'>" . $obj->geographical_area_id . " (" . $obj->description . ")</option>");
	}
?>
							</select>
						</div>					
					</div>
				</div>
			</fieldset>
		</div>
<!-- End origins field //-->
		<button type="submit" class="govuk-button">Continue</button>
		<button type="submit" class="govuk-button">Save progress</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>