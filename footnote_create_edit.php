<?php
	require ("includes/db.php");
    $application    = new application;
    $footnote_id    = get_querystring("footnote_id");
    $phase          = get_querystring("phase");
    $footnote       = new footnote;

    if ($phase == "edit") {
        $footnote->footnote_id = $footnote_id;
        $footnote->populate_from_db();
        $phase = "footnote_edit";
    } else {
        $footnote->populate_from_cookies();
        $phase = "footnote_create";
    }

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Home</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/footnote_types.html">Footnote types</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$footnote->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$footnote->heading?></h1>
</div>

<form class="tariff" method="post" action="/actions/footnote_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "footnote_edit") {
        echo ('<input type="hidden" name="footnote_id" value="' . $footnote->footnote_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin footnote type field //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_type_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="footnote_type_id">What is the footnote type?</label></h1>
	</legend>
    <span class="govuk-hint">Specify the type of footnote.</span>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="footnote_type_id" name="footnote_type_id">
		<option value="">- Select footnote type - </option>
<?php
	foreach ($footnote->footnote_types as $obj) {
        if ($obj->footnote_type_id == $footnote->footnote_type_id) {
            echo ("<option selected value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End footnote type field //-->

<!-- Begin footnote ID field //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="footnote_id">What is the footnote ID?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a 3- or 5-digit numeric string.</span>
	<?=$error_handler->display_error_message("footnote_id");?>
	<input <?=$footnote->disable_footnote_id_field?> value="<?=$footnote->footnote_id?>" class="govuk-input" style="width:10%" id="footnote_id" name="footnote_id" max="999" type="text" maxlength="5" size="5">
</div>
<!-- End footnote ID field //-->

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
					<input value="<?=$footnote->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$footnote->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$footnote->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
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
        <span class="govuk-hint">Please leave blank unless you explicitly want to end date a footnote.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_day">Day</label>
					<input value="<?=$footnote->validity_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_day" maxlength="2" name="validity_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_month">Month</label>
					<input value="<?=$footnote->validity_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_month" maxlength="2" name="validity_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_year">Year</label>
					<input value="<?=$footnote->validity_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_year" maxlength="4" name="validity_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->

<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the footnote text?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" rows="5"><?=$footnote->description?></textarea>
</div>
<!-- End description field //-->




		<button type="submit" class="govuk-button">Save footnote</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>