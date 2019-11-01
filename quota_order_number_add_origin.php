<?php
    $title = "Add quota order number origin";
	require ("includes/db.php");
	$application = new application;
	$application->get_measure_types();
	$application->get_geographical_areas();
	$application->get_geographical_members("1011");
	$application->get_countries_and_regions();

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the quota order number object
	$action					= get_querystring("action");
	$quota_order_number_id	= get_querystring("quota_order_number_id");
	$quota_order_number_sid	= get_querystring("quota_order_number_sid");
    $quota_order_number		= new quota_order_number;
    $quota_order_number->quota_order_number_id = $quota_order_number_id;
    $quota_order_number->quota_order_number_sid = $quota_order_number_sid;
	$quota_order_number->set_properties($quota_order_number_id, "", "");

	# Initialise the quota order number origin object
	$quota_order_number_origin = new quota_order_number_origin;
	$quota_order_number_origin->quota_order_number_id = $quota_order_number_id;
	$quota_order_number_origin->quota_order_number_sid = $quota_order_number_sid;
	switch ($action) {
		case "new":
			$quota_order_number_origin->quota_order_number_origin_sid = -1;
            $quota_order_number_origin->populate_from_cookies();
			break;
		case "edit":
			$quota_definition_sid = get_querystring("quota_definition_sid");
			$quota_definition->populate_from_db($quota_definition_sid);
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item">
			<a class="govuk-breadcrumbs__link" href="/">Main menu</a>
		</li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota order numbers</a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Add quota origin to quota <?=$quota_order_number->quota_order_number_id?></h1>
</div>

<form class="tariff" method="post" action="/actions/quota_order_number_actions.html">
<input type="hidden" name="phase" value="quota_order_number_add_origin" />
<input type="hidden" name="quota_order_number_sid" value="<?=$quota_order_number->quota_order_number_sid?>" />
<input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number->quota_order_number_id?>" />

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
	<input disabled value="<?=$quota_order_number->quota_order_number_id?>" class="govuk-input" style="width:10%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End quota order number field //-->



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Origin start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the start of the origin's validity period.</span>
		<span class="note_to_self"><b>Note to self</b> - force origin starting at the start of the quota period</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$quota_order_number_origin->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$quota_order_number_origin->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$quota_order_number_origin->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->



<!-- Begin origins field //-->
<div class="govuk-form-group" <?=$error_handler->get_error("origins");?>>
			<fieldset class="govuk-fieldset">
				<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
					<h1 id="heading_geographical_area_id" class="govuk-fieldset__heading" style="max-width:100%">Please select the required origin</h1>
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
							<select class="govuk-select" id="geographical_area_id_country" name="geographical_area_id_country">
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
							<select class="govuk-select" name="geographical_area_id_group" id="geographical_area_id_group" name="sort">
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



		<button type="submit" class="govuk-button">Add origin</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>