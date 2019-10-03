<?php
    $title = "Create quota blocking period";
	require ("includes/db.php");
    $application        = new application;
    $phase              = get_querystring("phase");
    $quota_blocking_period  = new quota_blocking_period;

    $quota_order_number = new quota_order_number;
    $quota_order_number->quota_order_number_id = $_COOKIE["quota_order_number_id"];
    $quota_order_number->get_quota_definitions();


    $err                = get_querystring("err");

    if ($phase == "edit") {
        $footnote->footnote_id = $footnote_id;
        $footnote->populate_from_db();
        $phase = "association_edit";
    } else {
        if ($err != "") {
            $quota_blocking_period->populate_from_cookies();
        }
        $phase = "association_create";
    }

	$error_handler = new error_handler;
	require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quotas</a></li>
		<li class="govuk-breadcrumbs__list-item">Create quota blocking period</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Create quota blocking period</h1>
</div>

<form class="tariff" method="post" action="/quota_blocking_period_confirm.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "footnote_edit") {
        echo ('<input type="hidden" name="footnote_id" value="' . $footnote->footnote_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin quota definition period field //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_type_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;">
            <label for="footnote_type_id">Select the quota definition period to block</label>
        </h1>
	</legend>
    <span class="govuk-hint">This is the quota definition to which the quota blocking period will be assigned.</span>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="footnote_type_id" name="footnote_type_id">
		<option value="">- Select quota definition period - </option>
<?php
	foreach ($quota_order_number->quota_definitions as $obj) {
        if ($obj->footnote_type_id == $footnote->footnote_type_id) {
            echo ("<option selected value='" . $obj->quota_definition_sid . "'>" . short_date($obj->validity_start_date) . " to " . short_date($obj->validity_end_date) . "</option>");
        } else {
            echo ("<option value='" . $obj->quota_definition_sid . "'>" . short_date($obj->validity_start_date) . " to " . short_date($obj->validity_end_date) . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End quota definition period field //-->

<!-- Begin blocking type field //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_type_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;">
            <label for="footnote_type_id">Enter the type of blocking period</label>
        </h1>
	</legend>
    <span class="govuk-hint">This is the quota definition to which the quota blocking period will be assigned.</span>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="footnote_type_id" name="footnote_type_id">
        <option value="">- Select blocking type - </option>
        <option value="1">1 - Block the allocations for a quota due to a late publication</option>
        <option value="2">2 - Block the allocations for a quota after its reopening due to a volume increase</option>
        <option value="3">3 - Block the allocations for a quota after its reopening due to the reception of quota return requests</option>
        <option value="4">4 - Block the allocations for a quota due to the modification of the validity period after receiving quota return requests</option>
        <option value="5">5 - Block the allocations for a quota on request of a MSA</option>
        <option value="6">6 - Block the allocations for a quota due to an end-user decision</option>
        <option value="7">7 - Block the allocations for a quota due to an exceptional condition</option>
        <option value="8">8 - Block the allocations for a quota after its reopening due to a balance transfer</option>
	</select>
</div>
<!-- End blocking type field //-->

<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("blocking_period_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="blocking_period_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_blocking_period_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Blocking period start date</h1>
		</legend>
		<?=$error_handler->display_error_message("blocking_period_start_date");?>
		<div class="govuk-date-input" id="blocking_period_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_start_day">Day</label>
					<input value="<?=$quota_blocking_period->blocking_period_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="blocking_period_start_day" maxlength="2" name="blocking_period_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_start_month">Month</label>
					<input value="<?=$quota_blocking_period->blocking_period_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="blocking_period_start_month" maxlength="2" name="blocking_period_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_start_year">Year</label>
					<input value="<?=$quota_blocking_period->blocking_period_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="blocking_period_start_year" maxlength="4" name="blocking_period_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


<!-- Begin validity end date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("blocking_period_end_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="blocking_period_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_blocking_period_end_date" class="govuk-fieldset__heading" style="max-width:100%;">Blocking period end date</h1>
		</legend>
		<?=$error_handler->display_error_message("blocking_period_end_date");?>
		<div class="govuk-date-input" id="blocking_period_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_end_day">Day</label>
					<input value="<?=$quota_blocking_period->blocking_period_end_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="blocking_period_end_day" maxlength="2" name="blocking_period_end_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_end_month">Month</label>
					<input value="<?=$quota_blocking_period->blocking_period_end_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="blocking_period_end_month" maxlength="2" name="blocking_period_end_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="blocking_period_end_year">Year</label>
					<input value="<?=$quota_blocking_period->blocking_period_end_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="blocking_period_end_year" maxlength="4" name="blocking_period_end_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity end date fields //-->



<!-- Begin description field //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_workbasket_name" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">Please enter a description of this blocking pereiod</label></h1>
	</legend>
    <span class="govuk-hint">This field is for informational purposes only. It is not mandatory.</span>

    <textarea class="govuk-textarea" name="description" id="description" rows="5"><?=$quota_blocking_period->description?></textarea>
</div>
<!-- End description field //-->



		<button type="submit" class="govuk-button">Continue</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>