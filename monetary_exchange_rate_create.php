<?php
	require ("includes/db.php");
	$application = new application;
	$application->get_measure_types();
	$application->get_geographical_areas();
	$application->get_geographical_members("1011");
	$application->get_countries_and_regions();
	$error_handler = new error_handler;
	$error_handler->get_errors("create_measure_phase1");
	require ("includes/header.php");
?>
<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create monetary exchange rate</h1>
</div>

<form class="tariff" method="post" action="/actions/monetary_exchange_rate_actions.php">
<input type="hidden" name="phase" value="1" />
<!-- Start error handler //-->
<div class="govuk-error-summary hidden" role="alert" tabindex="-1">
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

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="measure_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">When will the exchange rate come into force?</h1>
		</legend>
		<span id="measure_start_hint" class="govuk-hint">Exchange rates always start on the first day of the month.</span>
		<span id="base_regulation-error" class="govuk-error-message">
      		Please enter a valid start date
    	</span>
		<div class="govuk-date-input" id="measure_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="measure_start_day">Day</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="measure_start_day" maxlength="2" name="measure_start_day" type="text" pattern="[0-9]*" value="1">
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
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">When will the exchange rate expire?</h1>
		</legend>
		<span id="measure_end_hint" class="govuk-hint">Please leave these fields blank, and they will be populated the next time a new record is created</span>
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
<!-- Begin base regulation fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("base_regulation");?>">
	<fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">What is the exchange rate?</h1>
		</legend>
		<span id="base_regulation_hint" class="govuk-hint">Enter the proposed exchange rate here. Please ensure that you enter at least 5 decimal places.</span>
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

		<button type="submit" class="govuk-button">Continue</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>