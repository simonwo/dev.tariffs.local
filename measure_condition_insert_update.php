<?php
    $title = "Create or edit measure condition";
	require ("includes/db.php");
    $application            = new application;
    $measure_sid            = get_querystring("measure_sid");
    $measure_condition_sid  = get_querystring("measure_condition_sid");
    $phase                  = get_querystring("phase");

    $measure_condition  = new measure_condition;
    $measure_condition->measure_sid = $measure_sid;
    $application->get_measure_condition_codes();
    $application->get_action_codes();
    $application->get_certificate_types();
    $application->get_monetary_units();
    $application->get_measurement_units();
    $application->get_measurement_unit_qualifiers();
    

    if ($phase == "edit_condition") {
        $measure_condition->measure_sid             = $measure_sid;
        $measure_condition->measure_condition_sid   = $measure_condition_sid;
        $measure_condition->populate_from_db();
        $phase = "measure_condition_update";
    } else {
        $measure_condition->populate_from_cookies();
        $phase = "measure_condition_insert";
    }

	$error_handler = new error_handler;
    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/measure_view.html?measure_sid=<?=$measure_sid?>#measure_conditions">Measure <?=$measure_sid?></a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$measure_condition->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$measure_condition->heading?></h1>
</div>

<form class="tariff" method="get" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<input type="hidden" name="measure_sid" value="<?=$measure_condition->measure_sid?>" />
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin condition_code field //-->
<?php
?>

<div class="govuk-form-group <?=$error_handler->get_error("condition_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="condition_code">What is the condition code?</label></h1>
	</legend>
    <span class="govuk-hint">Select from the dropdown (mandatory)</span>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="condition_code" name="condition_code">
		<option value="">- Select condition code - </option>
<?php
	foreach ($application->measure_condition_codes as $obj) {
        if ($obj->condition_code == $measure_condition->condition_code) {
            echo ("<option selected value='" . $obj->condition_code . "'>" . $obj->condition_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->condition_code . "'>" . $obj->condition_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End condition_code field //-->

<!-- Begin component sequence number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("component_sequence_number");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="component_sequence_number">What is the sequence number?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a numeric value - lowest comes first (mandatory)</span>
	<?=$error_handler->display_error_message("component_sequence_number");?>
	<input value="<?=$measure_condition->component_sequence_number?>" class="govuk-input" style="width:10%" id="component_sequence_number" name="component_sequence_number" max="999" type="text" maxlength="2" size="2">
</div>
<!-- End component sequence number field //-->


<!-- Begin duty amount field //-->
<div class="govuk-form-group <?=$error_handler->get_error("duty_amount");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_amount">What is the duty amount?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a numeric value (optional)</span>
	<?=$error_handler->display_error_message("duty_amount");?>
	<input value="<?=$measure_condition->condition_duty_amount?>" class="govuk-input" style="width:10%" id="condition_duty_amount" name="condition_duty_amount" max="999" type="text" maxlength="5" size="5">
</div>
<!-- End duty amount field //-->


<!-- Begin monetary unit field //-->
<div class="govuk-form-group <?=$error_handler->get_error("monetary_unit_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_monetary_unit_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="condition_monetary_unit_code">What is the monetary unit?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the monetary unit (optional)</span>
	<?=$error_handler->display_error_message("monetary_unit_code");?>
	<select class="govuk-select" id="condition_monetary_unit_code" name="condition_monetary_unit_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->monetary_units as $obj) {
        if ($obj == $measure_condition->condition_monetary_unit_code) {
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
        <h1 id="h1_measurement_unit_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="condition_measurement_unit_code">What is the measurement unit?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the measurement unit (optional)</span>
	<?=$error_handler->display_error_message("measurement_unit_code");?>
	<select class="govuk-select" id="condition_measurement_unit_code" name="condition_measurement_unit_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->measurement_units as $obj) {
        if ($obj->measurement_unit_code == $measure_condition->condition_measurement_unit_code) {
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
        <h1 id="h1_measurement_unit_qualifier_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="condition_measurement_unit_qualifier_code">What is the measurement unit qualifier?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the measurement unit qualifier (optional)</span>
	<?=$error_handler->display_error_message("measurement_unit_qualifier_code");?>
	<select class="govuk-select" id="condition_measurement_unit_qualifier_code" name="condition_measurement_unit_qualifier_code">
		<option value="">- Unspecified - </option>
<?php
	foreach ($application->measurement_unit_qualifiers as $obj) {
        if ($obj->measurement_unit_qualifier_code == $measure_condition->condition_measurement_unit_qualifier_code) {
            echo ("<option selected value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->measurement_unit_qualifier_code . "'>" . $obj->measurement_unit_qualifier_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End measurement unit qualifier field //-->


<!-- Begin action_code field //-->
<?php
?>

<div class="govuk-form-group <?=$error_handler->get_error("action_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="action_code">What is the action code?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the action code (offically optional)</span>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="action_code" name="action_code">
		<option value="">- Select action code - </option>
<?php
	foreach ($application->action_codes as $obj) {
        if ($obj->action_code == $measure_condition->action_code) {
            echo ("<option selected value='" . $obj->action_code . "'>" . $obj->action_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->action_code . "'>" . $obj->action_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End action_code field //-->


<!-- Begin certificate type code field //-->
<?php
?>

<div class="govuk-form-group <?=$error_handler->get_error("certificate_type_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="certificate_type_code">What is the certificate type code?</label></h1>
	</legend>
    <span class="govuk-hint">Please seelct from the dropdown (optional)</span>
	<?=$error_handler->display_error_message("certificate_type_code");?>
	<select class="govuk-select" id="certificate_type_code" name="certificate_type_code">
		<option value="">- Select certificate type code - </option>
<?php
	foreach ($application->certificate_types as $obj) {
        if ($obj->certificate_type_code == $measure_condition->certificate_type_code) {
            echo ("<option selected value='" . $obj->certificate_type_code . "'>" . $obj->certificate_type_code . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->certificate_type_code . "'>" . $obj->certificate_type_code . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End certificate type code field //-->


<!-- Begin certificate field //-->
<div class="govuk-form-group <?=$error_handler->get_error("certificate_code");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_amount">What is the certificate code?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a three-digit code (optional)</span>
	<?=$error_handler->display_error_message("certificate_code");?>
	<input value="<?=$measure_condition->certificate_code?>" class="govuk-input" style="width:10%" id="certificate_code" name="certificate_code" max="999" type="text" maxlength="5" size="5">
</div>
<!-- End certificate field //-->



		<button type="submit" class="govuk-button">Save</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>