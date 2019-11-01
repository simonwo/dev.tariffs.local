<?php
    $title = "Create or edit measure component";
	require ("includes/db.php");
    $application        = new application;
    $measure_sid        = get_querystring("measure_sid");
    $duty_expression_id = get_querystring("duty_expression_id");
    $phase              = get_querystring("phase");

    $measure_component  = new measure_component;
    $measure_component->measure_sid = $measure_sid;
    $application->get_duty_expressions();
    $application->get_monetary_units();
    $application->get_measurement_units();
    $application->get_measurement_unit_qualifiers();

    if ($phase == "edit_component") {
        $measure_component->measure_sid         = $measure_sid;
        $measure_component->duty_expression_id  = $duty_expression_id;
        $measure_component->populate_from_db();
        $phase = "measure_component_update";
        $duty_expression_disabled   = " disabled";
        $duty_expression_field      = "<input type='hidden' name='duty_expression_id' value='" . $duty_expression_id . "'";
    } else {
        $measure_component->populate_from_cookies();
        $phase = "measure_component_insert";
        $duty_expression_disabled   = "";
        $duty_expression_field      = "";
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
		<li class="govuk-breadcrumbs__list-item"><?=$measure_component->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$measure_component->heading?></h1>
</div>

<form class="tariff" method="get" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<input type="hidden" name="measure_sid" value="<?=$measure_component->measure_sid?>" />
<?php
    echo ($duty_expression_field);
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin duty expression field //-->
<div class="govuk-form-group <?=$error_handler->get_error("duty_expression_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_duty_expression_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_expression_id">What is the duty expression ID?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the duty expression</span>
	<?=$error_handler->display_error_message("duty_expression_id");?>
	<select class="govuk-select" id="duty_expression_id" name="duty_expression_id" <?=$duty_expression_disabled?>>
		<option value="">- Select duty expression - </option>
<?php
	foreach ($application->duty_expressions as $obj) {
        if ($obj->duty_expression_id == $measure_component->duty_expression_id) {
            echo ("<option selected value='" . $obj->duty_expression_id . "'>" . $obj->duty_expression_id . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->duty_expression_id . "'>" . $obj->duty_expression_id . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End duty expression field //-->

<!-- Begin duty amount field //-->
<div class="govuk-form-group <?=$error_handler->get_error("duty_amount");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_amount">What is the duty amount?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a numeric value</span>
	<?=$error_handler->display_error_message("duty_amount");?>
	<input value="<?=$measure_component->duty_amount?>" class="govuk-input" style="width:10%" id="duty_amount" name="duty_amount" max="999" type="text" maxlength="5" size="5">
</div>
<!-- End duty amount field //-->


<!-- Begin monetary unit field //-->
<div class="govuk-form-group <?=$error_handler->get_error("monetary_unit_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_monetary_unit_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="monetary_unit_code">What is the monetary unit?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the monetary unit</span>
	<?=$error_handler->display_error_message("monetary_unit_code");?>
	<select class="govuk-select" id="monetary_unit_code" name="monetary_unit_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->monetary_units as $obj) {
        if ($obj == $measure_component->monetary_unit_code) {
            echo ("<option selected value='" . $obj . "'>" . $obj . "</option>");
        } else {
            echo ("<option value='" . $obj . "'>" . $obj . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End monetary unit field //-->

<!-- Begin measurement unit field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measurement_unit_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_measurement_unit_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measurement_unit_code">What is the measurement unit?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the measurement unit</span>
	<?=$error_handler->display_error_message("measurement_unit_code");?>
	<select class="govuk-select" id="measurement_unit_code" name="measurement_unit_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->measurement_units as $obj) {
        if ($obj->measurement_unit_code == $measure_component->measurement_unit_code) {
            echo ("<option selected value='" . $obj->measurement_unit_code . "'>" . $obj->measurement_unit_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->measurement_unit_code . "'>" . $obj->measurement_unit_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End measurement unit field //-->

<!-- Begin measurement unit qualifier field //-->
<div class="govuk-form-group <?=$error_handler->get_error("measurement_unit_qualifier_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_measurement_unit_qualifier_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="measurement_unit_qualifier_code">What is the measurement unit qualifier?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the measurement unit qualifier</span>
	<?=$error_handler->display_error_message("measurement_unit_qualifier_code");?>
	<select class="govuk-select" id="measurement_unit_qualifier_code" name="measurement_unit_qualifier_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->measurement_unit_qualifiers as $obj) {
        if ($obj->measurement_unit_qualifier_code == $measure_component->measurement_unit_qualifier_code) {
            echo ("<option selected value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End measurement unit qualifier field //-->



		<button type="submit" class="govuk-button">Save</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>