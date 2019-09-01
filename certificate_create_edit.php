<?php
    $title = "Create or edit certificate";
	require ("includes/db.php");
    $application    = new application;
    $certificate_code    = get_querystring("certificate_code");
    $phase          = get_querystring("phase");
    $certificate       = new certificate;

    if ($phase == "edit") {
        $certificate->certificate_code = $certificate_code;
        $certificate->populate_from_db();
        $phase = "certificate_edit";
    } else {
        $certificate->populate_from_cookies();
        $phase = "certificate_create";
    }

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/certificate_types.html">Certificate types</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$certificate->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$certificate->heading?></h1>
</div>

<form class="tariff" method="post" action="/actions/certificate_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "certificate_edit") {
        echo ('<input type="hidden" name="certificate_code" value="' . $certificate->certificate_code . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin certificate type field //-->
<div class="govuk-form-group <?=$error_handler->get_error("certificate_type_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="certificate_type_code">What is the certificate type?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the type of certificate.</span>
	<?=$error_handler->display_error_message("certificate_type_code");?>
	<select class="govuk-select" id="certificate_type_code" name="certificate_type_code">
		<option value="">- Select certificate type - </option>
<?php
	foreach ($certificate->certificate_types as $obj) {
        if ($obj->certificate_type_code == $certificate->certificate_type_code) {
            echo ("<option selected value='" . $obj->certificate_type_code . "'>" . $obj->certificate_type_code . " - " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->certificate_type_code . "'>" . $obj->certificate_type_code . " - " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End certificate type field //-->

<!-- Begin certificate ID field //-->
<div class="govuk-form-group <?=$error_handler->get_error("certificate_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="certificate_code">What is the certificate ID?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a 3-digit alphanumeric string.</span>
	<?=$error_handler->display_error_message("certificate_code");?>
	<input <?=$certificate->disable_certificate_code_field?> value="<?=$certificate->certificate_code?>" class="govuk-input" style="width:10%" id="certificate_code" name="certificate_code" max="999" type="text" maxlength="3" size="3">
</div>
<!-- End certificate ID field //-->

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
					<input value="<?=$certificate->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$certificate->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$certificate->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
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
        <span class="govuk-hint">Please leave blank unless you explicitly want to end date a certificate.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$certificate->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$certificate->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$certificate->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the certificate text?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" rows="5"><?=$certificate->description?></textarea>
</div>
<!-- End description field //-->




		<button type="submit" class="govuk-button">Save certificate</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>