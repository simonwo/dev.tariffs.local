<?php
    $title = "Audit trail";
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
		<li class="govuk-breadcrumbs__list-item">Audit</li>
	</ol>
</div>
<!-- End breadcrumbs //-->
<div class="app-content__header">
    <h1 class="govuk-heading-xl" style="xmargin-bottom:0px">Audit trail</h1>
</div>



<form>
<!-- Begin object selection //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label>Select objects to audit</label></h1>
	</legend>
    <span class="govuk-hint">Lorem ipsum</span>
    <?=$error_handler->display_error_message("suppress_cn10");?>
    
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Regulations</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Measures</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Quotas</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Goods nomenclatures</label>
      </div>
    </div>    
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Footnotes</label>
      </div>
    </div>    
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Certificates</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Geographical areas</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Additional codes</label>
      </div>
    </div>
</div>
</form>
<!-- End object selection //-->

<!-- Begin activity selection //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label>Select activities to audit</label></h1>
	</legend>
    <span class="govuk-hint">Lorem ipsum</span>
    <?=$error_handler->display_error_message("suppress_cn10");?>
    
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Create workbasket</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Cross check workbasket</label>
      </div>
    </div>
    <div class="govuk-checkboxes" style="margin-bottom:0.5em;">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="objects_measures" name="objects" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label">Approve workbasket</label>
      </div>
    </div>
</div>
</form>
<!-- End suppress CN10 field //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="measure_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">Select start date for audit period</h1>
        </legend>
		<span id="measure_start_hint" class="govuk-hint">Please note: this represents the date the activity took place
            on the Tariff database, not the date at which the data will become valid.</span>
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
        <!--
        <span id="measure_end_hint" class="govuk-hint">This is the end of the measures' validity period. By default, this will inherit from the generating regulation and may be open-ended.</span>
        //-->
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


		<button type="submit" class="govuk-button">Create audit report</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>