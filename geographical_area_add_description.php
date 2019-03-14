<?php
	require ("includes/db.php");
	$application = new application;

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the quota order number object
	$action					= get_querystring("action");
	$geographical_area_id	= get_querystring("geographical_area_id");
	$geographical_area_sid	= get_querystring("geographical_area_sid");
    $geographical_area		= new geographical_area;
    $geographical_area->geographical_area_id = $geographical_area_id;
    $geographical_area->geographical_area_sid = $geographical_area_sid;

	# Initialise the quota order number origin object
	switch ($action) {
		case "new":
			$geographical_area->geographical_area_description_period_sid = -1;
            $geographical_area->populate_from_cookies();
            if ($geographical_area->description == "") {
                $geographical_area->get_latest_description();
            }
			$disabled = "";
			break;
		case "edit":
			$geographical_area->geographical_area_id						= get_querystring("geographical_area_id");
			$geographical_area->geographical_area_sid						= get_querystring("geographical_area_sid");
			$geographical_area->geographical_area_description_period_sid	= get_querystring("geographical_area_description_period_sid");
			$geographical_area->populate_from_db();
			#h1 ("Here" . $geographical_area->description);
			$disabled = " disabled";
			break;
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/geographical_areas.html">Geographical areas</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>">Geographical area <?=$geographical_area_id?></a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Update description for geographical area <?=$geographical_area_id?></h1>
</div>

<form class="tariff" method="post" action="/actions/geographical_area_actions.html">
<input type="hidden" name="phase" value="geographical_area_update_description" />
<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area->geographical_area_sid?>" />
<input type="hidden" name="geographical_area_id" value="<?=$geographical_area->geographical_area_id?>" />
<input type="hidden" name="geographical_area_description_period_sid" value="<?=$geographical_area->geographical_area_description_period_sid?>" />


<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->



<!-- Begin geographical area ID //-->
<div class="govuk-form-group <?=$error_handler->get_error("geographical_area_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_geographical_area_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="geographical_area_id">What is the geographical area ID?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please enter the ID of the geographical area.</span>
	<?=$error_handler->display_error_message("geographical_area_id");?>
	<input disabled value="<?=$geographical_area->geographical_area_id?>" class="govuk-input" style="width:10%" id="geographical_area_id" name="geographical_area_id" type="text" maxlength="6" size="6">
</div>
<!-- End geographical area ID field //-->




<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the description of the geographical area?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please describe the geographical area.</span>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" name="goods_nomenclatures" rows="5"><?=$geographical_area->description?></textarea>
</div>
<!-- End description field //-->



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the date at which the name change will take place.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input <?=$disabled?> value="<?=$geographical_area->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input <?=$disabled?> value="<?=$geographical_area->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input <?=$disabled?> value="<?=$geographical_area->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->




		<button type="submit" class="govuk-button">Update description</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>