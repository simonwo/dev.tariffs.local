<?php
    $title = "Add / edit certificate description";
	require ("includes/db.php");
	$application = new application;

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the certificate object
	$action					    = get_querystring("action");
	$certificate_code	            = get_querystring("certificate_code");
	$certificate_type_code	        = get_querystring("certificate_type_code");
    $certificate		            = new certificate;
    $certificate->certificate_code      = $certificate_code;
    $certificate->certificate_type_code = $certificate_type_code;

	# Initialise the quota order number origin object
    switch ($action) {
        case "new":
			$certificate->certificate_description_period_sid = -1;
            $certificate->populate_from_cookies();
            if ($certificate->description == "") {
                $certificate->get_latest_description();
            }
			$disabled = "";
			break;
		case "edit":
			$certificate->certificate_code						= get_querystring("certificate_code");
			$certificate->certificate_type_code					= get_querystring("certificate_type_code");
			$certificate->certificate_description_period_sid	= get_querystring("certificate_description_period_sid");
			$certificate->get_description_from_db();
			$disabled = " disabled";
			break;
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/certificates.html">Certificates</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/certificate_view.html?certificate_type_code=<?=$certificate_type_code?>&certificate_code=<?=$certificate_code?>">Certificate <?=$certificate_type_code?><?=$certificate_code?></a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Update description for certificate <?=$certificate_type_code?><?=$certificate_code?></h1>
</div>

<form class="tariff" method="post" action="/actions/certificate_actions.html">
<input type="hidden" name="phase" value="certificate_update_description" />
<input type="hidden" name="certificate_type_code" value="<?=$certificate->certificate_type_code?>" />
<input type="hidden" name="certificate_code" value="<?=$certificate->certificate_code?>" />
<input type="hidden" name="certificate_description_period_sid" value="<?=$certificate->certificate_description_period_sid?>" />


<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->




<!-- Begin certificate type code //-->
<div class="govuk-form-group <?=$error_handler->get_error("certificate_type_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="certificate_type_code">What is the certificate type code?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("certificate_type_code");?>
	<input disabled value="<?=$certificate->certificate_type_code?>" class="govuk-input" style="width:10%" id="certificate_type_code" name="certificate_type_code" type="text" maxlength="6" size="6">
</div>
<!-- End certificate type code field //-->

<!-- Begin certificate code //-->
<div class="govuk-form-group <?=$error_handler->get_error("certificate_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="certificate_code">What is the certificate code?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("certificate_code");?>
	<input disabled value="<?=$certificate->certificate_code?>" class="govuk-input" style="width:10%" id="certificate_code" name="certificate_code" type="text" maxlength="6" size="6">
</div>
<!-- End certificate code field //-->


<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the description of the certificate?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please describe the certificate.</span>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" name="goods_nomenclatures" rows="5"><?=$certificate->description?></textarea>
</div>
<!-- End description field //-->


<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the date at which the description change will take place.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input <?=$disabled?> value="<?=$certificate->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input <?=$disabled?> value="<?=$certificate->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input <?=$disabled?> value="<?=$certificate->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->
<?php
	if ($action == "edit") {
?>		
	<input type="hidden" name="validity_start_day" value="<?=$certificate->validity_start_day?>" />
	<input type="hidden" name="validity_start_month" value="<?=$certificate->validity_start_month?>" />
	<input type="hidden" name="validity_start_year" value="<?=$certificate->validity_start_year?>" />
<?php
	}
?>



		<button type="submit" class="govuk-button">Update description</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>