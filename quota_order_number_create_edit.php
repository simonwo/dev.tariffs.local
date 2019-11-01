<?php

	$title = "Create / edit quota order number";
	require ("includes/db.php");
	//pre($_REQUEST);
	$application		= new application;
	$application->get_measure_types();
	$error_handler			= new error_handler;
	$phase          		= get_querystring("phase");
	$err          			= get_querystring("err");
	$quota_order_number_id	= get_querystring("quota_order_number_id");
	$quota_order_number_sid	= get_querystring("quota_order_number_sid");
	$quota_order_number		= new quota_order_number();

	if ($phase == "edit") {
		$phase = "quota_order_number_edit";
		$quota_order_number->quota_order_number_id	= $quota_order_number_id;
		$quota_order_number->quota_order_number_sid	= $quota_order_number_sid;
		$quota_order_number->populate_from_db();
		$title = "Edit quota order number";
		$button_text = "Update quota";
	} else {
		$phase = "quota_order_number_create";
		if ($err == 1) {
			$quota_order_number->populate_from_cookies();
		} else {
			$quota_order_number->clear();
		}
		$title = "Create a new quota";
		$button_text = "Create quota order number";
	}

	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota order numbers</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$title?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$title?></h1>
</div>

<form class="tariff" method="post" action="/actions/quota_order_number_actions.html">
	<input type="hidden" name="phase" value="<?=$phase?>" />
	<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number->quota_order_number_id?>" />
	<input type="hidden" name="quota_order_number_sid" value="<?=$quota_order_number->quota_order_number_sid?>" />

<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<div style="width:67%">

<!-- Begin workbasket field //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;">
			<label for="quota_order_number_id">What is the quota order number?</label>
		</h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please enter a 6-digit quota order number starting with 09.
		Any quota order number that begins 094 is a licensed quota.</span>
	<?=$error_handler->display_error_message("quota_order_number_id");?>
	<input value="<?=$quota_order_number->quota_order_number_id?>" class="govuk-input" style="width:20%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
	<span class="inline-message" id="quota_fulfilment_method"></span>
</div>
<!-- End workbasket field //-->


<!-- Begin quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;">
			<label for="quota_order_number_id">What is the quota order number?</label>
		</h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please enter a 6-digit quota order number starting with 09.
		Any quota order number that begins 094 is a licensed quota.</span>
	<?=$error_handler->display_error_message("quota_order_number_id");?>
	<input value="<?=$quota_order_number->quota_order_number_id?>" class="govuk-input" style="width:20%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
	<span class="inline-message" id="quota_fulfilment_method"></span>
</div>
<!-- End quota order number field //-->


<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">When should this quota start?</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">Please enter the date from which you would like this quota to start.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$quota_order_number->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$quota_order_number->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$quota_order_number->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->
<?php
	if ($phase == "quota_order_number_edit") {
?>
<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_end_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_end_date" class="govuk-fieldset__heading" style="max-width:100%;">When should this quota end (optional)?</h1>
		</legend>
		<span id="validity_end_hint" class="govuk-hint">Please enter the date on which you would like this quota to end.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$quota_order_number->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$quota_order_number->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$quota_order_number->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->
<?php		
	}
?>


<!-- Begin description field //-->
	<div class="govuk-form-group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 class="govuk-fieldset__heading" style="max-width:100%;">
				<label for="description">What is the description of this quota?</label>
			</h1>
		</legend>
		<span id="description-hint" class="govuk-hint">
			Use this field to enter a brief description of this quota. This information will be used to populate the quota
			definitions and to help you search for quotas in the Tariff Application.
		</span>
		<textarea class="govuk-textarea" id="description" name="description" rows="3" aria-describedby="description-hint"><?=$quota_order_number->description?></textarea>
	</div>
<!-- End description field //-->


<!-- Begin scoping text field //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="quota_scope">Is there any scoping text for this quota? (optional)</label></h1>
	</legend>
	<span id="quota_scope_hint" class="govuk-hint">If this quota needs any scoping text to identify it on reference documents, then please enter it here.
		A good example of scoping text is 'Mauritius only'.
	</span>
	<input value="<?=$quota_order_number->quota_scope?>" class="govuk-input" style="width:640px" id="quota_scope" name="quota_scope" type="text" maxlength="100" size="100">
</div>
<!-- End scoping text field //-->

<!-- Begin quota staging field //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="quota_staging">Is there an annual increase in quota volume? (optional)</label></h1>
	</legend>
	<span id="quota_staging_hint" class="govuk-hint">Some quotas that are a part of a Trade Agreement increase in volume on a periodic / annual
		basis. If this quota volumes increases each year, please enter the annual increase below.<br /><br />
		<i>Example:&nbsp;&nbsp;an annual increase of 1,000kg</i>
	</span>
	<input value="<?=$quota_order_number->quota_staging?>" class="govuk-input" style="width:640px" id="quota_staging" name="quota_staging" type="text" maxlength="100" size="100">
</div>
<!-- End quota staging field //-->



<!-- Start origin quota field //-->
<?php
	if ($quota_order_number->origin_quota == "t") {
		$yes_text	= " checked";
		$no_text	= "";
	} else {
		$yes_text	= "";
		$no_text	= " checked";
	}
?>
	<div class="govuk-form-group">
		<fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading">Is this an origin quota?</h1>
			</legend>
		<span id="changed-name-hint" class="govuk-hint">Lorem ipsum dolor sit amet.</span>
		<div class="govuk-radios govuk-radios--inline">
			<div class="govuk-radios__item">
				<input <?=$yes_text?> class="govuk-radios__input" id="origin_quota-yes" name="origin_quota" type="radio" value="yes">
				<label class="govuk-label govuk-radios__label" for="origin_quota-yes">Yes</label>
			</div>
			<div class="govuk-radios__item">
				<input <?=$no_text?> class="govuk-radios__input" id="origin_quota-no" name="origin_quota" type="radio" value="no">
				<label class="govuk-label govuk-radios__label" for="origin_quota-no">No</label>
			</div>
		</div>
		</fieldset>
	</div>
<!-- End origin quota field //-->



		<button type="submit" class="govuk-button"><?=$button_text?></button>
</div>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>