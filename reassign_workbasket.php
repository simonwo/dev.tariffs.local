<?php
    $title = "Create measure";
	require ("includes/db.php");
	$application = new application;
	$application->get_duty_expressions();
	$application->get_measurement_units();
	$application->get_measurement_unit_qualifiers();
	$application->get_measure_types();
	$application->get_geographical_areas();
	$application->get_geographical_members("1011");
	$application->get_countries_and_regions();

	$measure_sid	= get_querystring("measure_sid");
	$phase			= get_querystring("phase");
	$measure = new measure;
	if ($phase == "edit") {
		$measure->measure_sid = $measure_sid;
		$measure->populate_from_db();
		$phase = "measure_edit";
	} else {
		$measure->populate_from_cookies();
		$phase = "measure_create";
	}

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measures.html">Workbaskets</a></li>
		<li class="govuk-breadcrumbs__list-item">Reassign workbasket</li>
	</ol>
</div>
<!-- End breadcrumbs //-->
<div class="app-content__header">
    <h1 class="govuk-heading-xl" style="margin-bottom:0px">Reassign workbasket</h1>
</div>
<p>Please use this screen to reassign the workbasket to a different Tariff Manager.</p>
<h2 class="large">Workbasket details</h2>
<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad" style="width:25%">Workbasket ID</th>
        <td class="govuk-table__cell">1822</td>
    </tr>
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad">Workbasket name</th>
        <td class="govuk-table__cell">New requirement for Singapore Trade Agreement</td>
    </tr>
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad">Created by</th>
        <td class="govuk-table__cell">Angela Houseman</td>
    </tr>
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad">Type</th>
        <td class="govuk-table__cell">Create Measures</td>
    </tr>
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad">Status</th>
        <td class="govuk-table__cell">New - in progress</td>
    </tr>
    <tr class="govuk-table__row">
        <th class="govuk-table__header nopad">Last event</th>
        <td class="govuk-table__cell">26 Nov 2019</td>
    </tr>
</table>

<form action="reassign_confirm.html" method="get">
<div class="govuk-form-group" style="max-width:100%;margin-top:4em;">
  <fieldset class="govuk-fieldset" style="max-width:100%">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl" style="max-width:100%">
      <h1 class="govuk-fieldset__heading" style="max-width:100%">
        Which Tariff Manager would you like to progress this workbasket?
      </h1>
    </legend>
	<span id="validity_start_hint" class="govuk-hint">Please select the tariff manager to whom to assign the workbasket.</span>
    <div class="govuk-radios">
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live" name="where-do-you-live" type="radio" value="england">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live">
          Marjorie Antrobus
        </label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-2" name="where-do-you-live" type="radio" value="scotland">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-2">
          Erin Schmidt
        </label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-3" name="where-do-you-live" type="radio" value="wales">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-3">
        Talhah Whittle
        </label>
      </div>
      <div class="govuk-radios__item">
        <input class="govuk-radios__input" id="where-do-you-live-4" name="where-do-you-live" type="radio" value="northern-ireland">
        <label class="govuk-label govuk-radios__label" for="where-do-you-live-4">
        Taiba Roman
        </label>
      </div>
    </div>
  </fieldset>
</div>



		<button type="submit" class="govuk-button">Reassign workbasket</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>