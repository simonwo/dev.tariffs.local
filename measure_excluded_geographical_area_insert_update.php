<?php
	$title = "Create or edit excluded geographical area";
	require ("includes/db.php");
	$application        = new application;
	$measure_sid        = get_querystring("measure_sid");
	$phase              = get_querystring("phase");

	$measure_excluded_geographical_area  = new measure_excluded_geographical_area;
	$measure_excluded_geographical_area->measure_sid = $measure_sid;

	$application->get_duty_expressions();
	$application->get_monetary_units();
	$application->get_measurement_units();
	$application->get_measurement_unit_qualifiers();

	if ($phase == "edit_exclusion") {
		$measure_excluded_geographical_area->measure_sid         = $measure_sid;
		$measure_excluded_geographical_area->duty_expression_id  = $duty_expression_id;
		$measure_excluded_geographical_area->populate_from_db();
		$phase = "measure_excluded_geographical_area_update";
	} else {
		$measure_excluded_geographical_area->populate_from_cookies();
		$phase = "measure_excluded_geographical_area_insert";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measure_view.html?measure_sid=<?=$measure_sid?>#measure_components">Measure <?=$measure_sid?></a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$measure_excluded_geographical_area->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$measure_excluded_geographical_area->heading?></h1>
</div>

<form class="tariff" method="get" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<input type="hidden" name="measure_sid" value="<?=$measure_excluded_geographical_area->measure_sid?>" />
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin geo area field //-->
<div class="govuk-form-group <?=$error_handler->get_error("geographical_area_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="h1_excluded_geographical_area" class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_expression_id">What is the geographical area ID of the country that you want to exclude?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("excluded_geographical_area");?>
	<input value="<?=$measure_excluded_geographical_area->excluded_geographical_area?>" class="govuk-input" style="width:10%" id="excluded_geographical_area" name="excluded_geographical_area" type="text" maxlength="4" size="4">
</div>
<!-- End geo area field //-->




		<button type="submit" class="govuk-button">Save</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>