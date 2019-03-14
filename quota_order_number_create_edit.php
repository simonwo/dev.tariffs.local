<?php
	require ("includes/db.php");
	$application = new application;
	$application->get_measure_types();
	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Home</a>
		</li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota order numbers</a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create quota</h1>
</div>

<form class="tariff" method="post" action="/actions/quota_order_number_actions.html">
<input type="hidden" name="phase" value="quota_order_number_create_edit" />

<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<!-- Begin quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="quota_order_number_id">What is the quota order number?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please enter a 6-digit quota order number starting with 09. Please note: licensed quota order numbers (beginning with 094) should not be entered here.</span>
	<?=$error_handler->display_error_message("quota_order_number_id");?>
	<input class="govuk-input" style="width:10%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End quota order number field //-->



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Quota start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the start of the measures' validity period. This will be delayed for any measures that are not approved in time, or if the generating regulation has not come into force by the date specified here.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


<!-- Begin measure type field //-->
<!--
		<div class="govuk-form-group <?=$error_handler->get_error("measure_type");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type">What type of quota do you want to create?</label></h1>
			</legend>
			<span id="validity_start_hint" class="govuk-hint">Please select the type of quota. Preferential quotas belong to trade agreements, while non-preferential
				quotas are WTO quotas or autonomous quotas.
			</span>
			<span id="measure_type-error" class="govuk-error-message">Please enter a valid measure type</span>
			<select class="govuk-select" id="measure_type" name="measure_type">
				<option value="0">- Select measure type - </option>
<?php
	foreach ($application->measure_types as $mt) {
        if ($mt->is_quota == True) {
            echo ("<option value='" . $mt->measure_type_id . "'>" . $mt->measure_type_id . " (" . $mt->description_truncated . ")</option>");
        }
	}
?>
			</select>
		</div>
//-->
<!-- End measure type field //-->


		<button type="submit" class="govuk-button">Save quota</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>