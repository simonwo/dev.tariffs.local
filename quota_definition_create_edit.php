<?php
	require ("includes/db.php");
	$application = new application;

	# Get the data to populate the dropdowns
	$application->get_measurement_units();
	$application->get_measurement_unit_qualifiers();
	$application->get_maximum_precisions();
	$application->get_critical_states();
	$application->get_monetary_units();

	# Initialise the error handler
	$error_handler = new error_handler;

	# Initialise the quota order number object
	$action					= get_querystring("action");
	$quota_order_number_id	= get_querystring("quota_order_number_id");
	$quota_order_number		= new quota_order_number;
	$quota_order_number->set_properties($quota_order_number_id, "", "");
	$quota_order_number->get_quota_order_number_sid();

	# Initialise the quota definition object
	$quota_definition = new quota_definition;
	$quota_definition->quota_order_number_id = $quota_order_number_id;
	switch ($action) {
		case "new":
			$quota_definition->quota_definition_sid = -1;
			$quota_definition->populate_from_cookies();
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
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quota order numbers</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_number_view.html?quota_order_number_id=<?=$quota_definition->quota_order_number_id?>">Quota <?=$quota_definition->quota_order_number_id?></a></li>
		<li class="govuk-breadcrumbs__list-item">Quota definition</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create quota definition</h1>
</div>

<form class="tariff" method="post" action="/actions/quota_definition_actions.html">
<input type="hidden" name="phase" value="quota_definition_create_edit" />
<input type="hidden" name="quota_order_number_sid" value="<?=$quota_order_number->quota_order_number_sid?>" />
<input type="hidden" name="quota_order_number_id" value="<?=$quota_definition->quota_order_number_id?>" />
<input type="hidden" name="quota_definition_sid" value="<?=$quota_definition->quota_definition_sid?>" />

<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<!-- Begin quota order number field //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="quota_order_number_id">What is the quota order number?</label></h1>
	</legend>
	<input class="govuk-input" value="<?=$quota_definition->quota_order_number_id?>" disabled style="width:10%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End quota order number field //-->


<!-- Begin definition validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date|conflict_with_existing");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Definition start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">Please enter the start of the definition's validity period. This field is mandatory.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<?=$error_handler->display_error_message("conflict_with_existing");?>
				<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$quota_definition->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$quota_definition->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$quota_definition->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->

<!-- Begin validity end date fields //-->

<div class="govuk-form-group <?=$error_handler->get_error("validity_end_date|validity_end_date_before_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_end_date" class="govuk-fieldset__heading" style="max-width:100%;">Definition end date</h1>
		</legend>
		<span id="validity_end_hint" class="govuk-hint">Please enter the end of the definition's validity period. This field is mandatory.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<?=$error_handler->display_error_message("validity_end_date_before_start_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$quota_definition->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$quota_definition->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$quota_definition->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>

<!-- End validity end date fields //-->


<!-- Begin volume field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("initial_volume");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 id="heading_initial_volume" class="govuk-fieldset__heading" style="max-width:100%;"><label for="initial_volume">What is the quota volume?</label></h1>
			</legend>
			<span id="validity_end_hint" class="govuk-hint">Please enter the opening balance for this time period. This field is mandatory.</span>
			<?=$error_handler->display_error_message("initial_volume");?>
			<input value="<?=$quota_definition->initial_volume?>" class="govuk-input" id="initial_volume" name="initial_volume" style="width:200px" maxlength="15" type="text">
		</div>
<!-- End volume field //-->



<!-- Begin measurement unit field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measurement_unit_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_measurement_unit_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measure_type">What measurement unit will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Please enter the measurement. This field is mandatory.</span>
	<?=$error_handler->display_error_message("measurement_unit_code");?>
	<select class="govuk-select" id="measurement_unit_code" name="measurement_unit_code">
		<option value="">- Select measurement unit - </option>
<?php
	foreach ($application->measurement_units as $obj) {
		if ($obj->measurement_unit_code == $quota_definition->measurement_unit_code) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo ("<option " . $selected . " value='" . $obj->measurement_unit_code . "'>" . $obj->measurement_unit_code . " (" . $obj->description . ")</option>");
	}
?>
	</select>
</div>
<!-- End measurement unit field //-->



<!-- Begin measurement unit qualifier field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measurement_unit_qualifier_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_measurement_unit__qualifier_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measurement_unit_qualifier_code">What measurement unit qualifier will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Please enter the measurement. This field is optional.</span>
	<span id="measure_type-error" class="govuk-error-message">Please enter a valid measurement unit qualifier</span>
	<select class="govuk-select" id="measurement_unit_qualifier_code" name="measurement_unit_qualifier_code">
		<option value="">- Select measurement unit qualifier - </option>
<?php
	foreach ($application->measurement_unit_qualifiers as $obj) {
		if ($obj->measurement_unit_qualifier_code == $quota_definition->measurement_unit_qualifier_code) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo ("<option " . $selected . " value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " (" . $obj->description . ")</option>");
	}
?>
	</select>
</div>
<!-- End measurement unit qualifier field //-->



<!-- Begin maximum precision field //-->
<div class="govuk-form-group <?=$error_handler->get_error("maximum_precision");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_maximum_precision" class="govuk-fieldset__heading" style="max-width:100%;"><label for="maximum_precision">What maximum precision will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Maximum precision identifies the number of decimal places to which the quota will be measured.
		Typically the maximum precision is set to 3.</span>
		<?=$error_handler->display_error_message("maximum_precision");?>
	<select class="govuk-select" id="maximum_precision" name="maximum_precision">
		<option value="">- Select maximum precision - </option>
<?php
	foreach ($application->maximum_precisions as $obj) {
		if ($obj == $quota_definition->maximum_precision) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo ("<option " . $selected . "  value='" . $obj . "'>" . $obj . "</option>");
	}
?>
	</select>
</div>
<!-- End maximum precision field //-->

<!-- Begin critical state field //-->
<div class="govuk-form-group <?=$error_handler->get_error("critical_state");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_critical_state" class="govuk-fieldset__heading" style="max-width:100%;"><label for="critical_state">What critical state will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Set the critical state to 'Y' if securities are to be collected for ths quota.</span>
	<?=$error_handler->display_error_message("critical_state");?>
	<select class="govuk-select" id="critical_state" name="critical_state">
		<option value="">- Select critical state - </option>
<?php
	foreach ($application->critical_states as $obj) {
		if ($obj == $quota_definition->critical_state) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo ("<option " . $selected . " value='" . $obj . "'>" . $obj . "</option>");
	}
?>
	</select>
</div>
<!-- End critical state field //-->

<!-- Begin critical threshold field //-->
<div class="govuk-form-group <?=$error_handler->get_error("critical_threshold");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_critical_threshold" class="govuk-fieldset__heading" style="max-width:100%;"><label for="critical_threshold">What critical threshold will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Set the usage threshold. Once more than the specified percentage of the quota has been used, the quota
		will revert to critical automatically.
	</span>
	<?=$error_handler->display_error_message("critical_threshold");?>
	<input value="<?=$quota_definition->critical_threshold?>" class="govuk-input" id="initial_volume" name="critical_threshold" style="width:200px" maxlength="15" type="text">
</div>
<!-- End critical threshold field //-->

<!-- Begin monetary unit field //-->
<div class="govuk-form-group <?=$error_handler->get_error("monetary_unit_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="critical_threshold">What monetary unit will you use?</label></h1>
	</legend>
	<span id="validity_end_hint" class="govuk-hint">Please only enter a monetary unit if this quota is measured in Euros.</span>
	<span id="measure_type-error" class="govuk-error-message">Please enter a valid monetary unit</span>
	<select class="govuk-select" id="monetary_unit_code" name="monetary_unit_code">
		<option value="">- Select monetary unit - </option>
<?php
	foreach ($application->monetary_units as $obj) {
		if ($obj == $quota_definition->monetary_unit_code) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo ("<option " . $selected . " value='" . $obj . "'>" . $obj . "</option>");
	}
?>
	</select>
</div>
<!-- End monetary unit field //-->

<!-- Begin description field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("goods_nomenclatures");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">Describe this quota definition</label></h1>
			</legend>
			<span id="more-detail-hint" class="govuk-hint">Please enter some words to help to identify this quota definition.</span>
			<textarea class="govuk-textarea" id="description" name="description" rows="5"><?=$quota_definition->description?></textarea>
		</div>			
<!-- End description field //-->


		<button type="submit" class="govuk-button">Save</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>