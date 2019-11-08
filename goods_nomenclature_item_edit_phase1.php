<?php
    $title = "Create or edit regulation";
	require ("includes/db.php");
	$application = new application;
	$application->get_regulation_groups();
	$error_handler = new error_handler;

    # Initialise the quota order number object
	$action				= get_querystring("action");
	$base_regulation_id = get_querystring("base_regulation_id");
	$base_regulation	= new base_regulation;
	$base_regulation->set_properties($base_regulation_id);

	# Initialise the quota order number origin object

	$disabled = "";
	switch ($action) {
        case "new":
            $base_regulation->populate_from_cookies();
			$disabled = "";
            $phase = "regulation_create";
			break;
		case "edit":
			$base_regulation->base_regulation_id    = get_querystring("base_regulation_id");
			$base_regulation->populate_from_db();
            $disabled = " disabled";
            $phase = "regulation_edit";
			break;
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<!--<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/regulations.html">Regulations</a></li>//-->
		<li class="govuk-breadcrumbs__list-item">&lt; Back</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Work with selected commodity code</h1>
</div>

<form class="tariff" method="post" action="/actions/regulation_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "regulation_edit") {
?>
<input type="hidden" name="base_regulation_id" value="<?=$base_regulation->base_regulation_id?>" />
<?php        
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->

<!-- Begin regulation ID field //-->
<div class="govuk-form-group <?=$error_handler->get_error("base_regulation_id|regulation_already_exists");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_base_regulation_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="base_regulation_id">What is the regulation identifier?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">This must be exactly 8 characters long. Please use the following structure for regulations. Start with one of the following characters:<br /><br />
    	P&nbsp;&nbsp;Preferential Trade Agreement<br />
        U&nbsp;&nbsp;Unilateral preferences (GSP)<br />
        S&nbsp;&nbsp;Suspensions and reliefs<br />
        X&nbsp;&nbsp;Import and Export control<br />
        N&nbsp;&nbsp;Trade  <br />
        M&nbsp;&nbsp;MFN<br />
        Q&nbsp;&nbsp;Quotas<br />
        A&nbsp;&nbsp;Agri


    </span>
	<?=$error_handler->display_error_message("base_regulation_id");?>
	<input <?=$disabled?> value="<?=$base_regulation->base_regulation_id?>" class="govuk-input" style="width:20%" id="base_regulation_id" name="base_regulation_id" type="text" maxlength="8" size="8">
</div>
<!-- End regulation ID field //-->



<!-- Begin public-facing identifier field //-->
<div class="govuk-form-group <?=$error_handler->get_error("information_text_name");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_base_regulation_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="base_regulation_id">What is the public-facing regulation name?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">This is the name of the regulation as it would appear on (for example) legislation.gov.uk.</span>
	<?=$error_handler->display_error_message("information_text_name");?>
	<input value="<?=$base_regulation->information_text_name?>" class="govuk-input" style="width:40%" id="information_text_name" name="information_text_name" type="text" maxlength="50" size="50">
</div>
<!-- End public-facing identifier field //-->



<!-- Begin information text field //-->
<div class="govuk-form-group <?=$error_handler->get_error("information_text_primary");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_information_text_primary" class="govuk-fieldset__heading" style="max-width:100%;"><label for="information_text_primary">What is the description of this regulation?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">This is for information purposes only.</span>
	<?=$error_handler->display_error_message("base_regulation_id");?>
	<textarea class="govuk-textarea" id="information_text_primary" name="information_text_primary" rows="3"><?=$base_regulation->information_text_primary?></textarea>
</div>
<!-- End information text field //-->



<!-- Begin URL field //-->
<div class="govuk-form-group <?=$error_handler->get_error("information_text_url");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_information_text_primary" class="govuk-fieldset__heading" style="max-width:100%;"><label for="information_text_url">What is the URL of the regulation?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please enter the absolute URL of the regulation.</span>
	<?=$error_handler->display_error_message("base_regulation_id");?>
	<textarea class="govuk-textarea" id="information_text_url" name="information_text_url" rows="2"><?=$base_regulation->information_text_url?></textarea>
</div>
<!-- End URL field //-->



<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input value="<?=$base_regulation->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input value="<?=$base_regulation->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input value="<?=$base_regulation->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->


<!-- Begin regulation group field //-->
		<div class="govuk-form-group <?=$error_handler->get_error("regulation_group_id");?>">
			<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
				<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="regulation_group_id">What type of regulation do you want to create?</label></h1>
			</legend>
			<span id="validity_start_hint" class="govuk-hint">Please select the regulation group.</span>
			<?=$error_handler->display_error_message("regulation_group_id");?>
			<select class="govuk-select" id="regulation_group_id" name="regulation_group_id">
				<option value="">- Select regulation group - </option>
<?php
	foreach ($application->regulation_groups as $obj) {
		if ($obj->regulation_group_id == $base_regulation->regulation_group_id) {
			$selected = " selected";
		} else {
			$selected = "";
		}
        echo ("<option " . $selected . " value='" . $obj->regulation_group_id . "'>" . $obj->regulation_group_id . " (" . $obj->description . ")</option>");
	}
?>
			</select>
		</div>
<!-- End regulation group field //-->


		<button type="submit" class="govuk-button">Save regulation</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>