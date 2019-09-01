<?php
    $title = "Create / edit monetary exchange rate";
	require ("includes/db.php");
	$application = new application;
	$monetary_exchange_period_sid   = get_querystring("monetary_exchange_period_sid");
	$phase              			= get_querystring("phase");
	$monetary_exchange_rate = new monetary_exchange_rate;
	if ($phase == "edit") {
		$monetary_exchange_rate->monetary_exchange_period_sid = $monetary_exchange_period_sid;
		$monetary_exchange_rate->populate_from_db();
		#h1 ("Getting from DB " . $monetary_exchange_rate->monetary_exchange_period_sid);
		$phase = "monetary_exchange_rate_edit";
	} else {
		$monetary_exchange_rate->populate_from_cookies();
		#h1 ("Getting from cookies");
		$phase = "monetary_exchange_rate_create";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/monetary_exchange_rates.html">Monetary exchange rates</a></li>
		<li class="govuk-breadcrumbs__list-item">Monetary exchange rate</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create / edit monetary exchange rate</h1>
</div>

<form class="tariff" method="post" action="/actions/monetary_exchange_rate_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
	if ($phase == "monetary_exchange_rate_edit") {
		echo ('<input type="hidden" name="monetary_exchange_period_sid" value="' . $monetary_exchange_rate->monetary_exchange_period_sid . '" />');
	}
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">When will the exchange rate come into force?</h1>
		</legend>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$monetary_exchange_rate->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$monetary_exchange_rate->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$monetary_exchange_rate->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
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
			<h1 id="heading_validity_end_date" class="govuk-fieldset__heading" style="max-width:100%;">When will the exchange rate expire?</h1>
		</legend>
		<span class="govuk-hint">Please leave blank unless you explicitly want to end date this exchange rate.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$monetary_exchange_rate->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$monetary_exchange_rate->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$monetary_exchange_rate->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin exchange_rate field //-->
<div class="govuk-form-group <?=$error_handler->get_error("exchange_rate");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="exchange_rate">What is the exchange rate?</label></h1>
	</legend>
	<span class="govuk-hint">Enter the proposed exchange rate here. Please ensure that you enter at least 5 decimal places.</span>
	<?=$error_handler->display_error_message("exchange_rate");?>
	<input value="<?=$monetary_exchange_rate->exchange_rate?>" class="govuk-input" style="width:15%" id="exchange_rate" name="exchange_rate" type="text" maxlength="12" size="12">
</div><!-- End exchange_rate field //-->





		<button type="submit" class="govuk-button">Save exchange rate</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>