<?php
    $title = "Add footnote description";
	require ("includes/db.php");
	$application = new application;

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the footnote object
	$action					    = get_querystring("action");
	$footnote_id	            = get_querystring("footnote_id");
	$footnote_type_id	        = get_querystring("footnote_type_id");
    $footnote		            = new footnote;
    $footnote->footnote_id      = $footnote_id;
    $footnote->footnote_type_id = $footnote_type_id;

	# Initialise the quota order number origin object
    switch ($action) {
        case "new":
			$footnote->footnote_description_period_sid = -1;
            $footnote->populate_from_cookies();
            if ($footnote->description == "") {
                $footnote->get_latest_description();
            }
			$disabled = "";
			break;
		case "edit":
			$footnote->footnote_id						= get_querystring("footnote_id");
			$footnote->footnote_type_id					= get_querystring("footnote_type_id");
			$footnote->footnote_description_period_sid	= get_querystring("footnote_description_period_sid");
			$footnote->get_description_from_db();
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
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/footnotes.html">Footnotes</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/footnote_view.html?footnote_type_id=<?=$footnote_type_id?>&footnote_id=<?=$footnote_id?>">Footnote <?=$footnote_type_id?><?=$footnote_id?></a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Update description for footnote <?=$footnote_type_id?><?=$footnote_id?></h1>
</div>

<form class="tariff" method="post" action="/actions/footnote_actions.html">
<input type="hidden" name="phase" value="footnote_update_description" />
<input type="hidden" name="footnote_type_id" value="<?=$footnote->footnote_type_id?>" />
<input type="hidden" name="footnote_id" value="<?=$footnote->footnote_id?>" />
<input type="hidden" name="footnote_description_period_sid" value="<?=$footnote->footnote_description_period_sid?>" />


<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->




<!-- Begin footnote type ID //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_type_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="footnote_type_id">What is the footnote type ID?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<input disabled value="<?=$footnote->footnote_type_id?>" class="govuk-input" style="width:10%" id="footnote_type_id" name="footnote_type_id" type="text" maxlength="6" size="6">
</div>
<!-- End footnote type ID field //-->

<!-- Begin footnote ID //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="footnote_id">What is the footnote ID?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("footnote_id");?>
	<input disabled value="<?=$footnote->footnote_id?>" class="govuk-input" style="width:10%" id="footnote_id" name="footnote_id" type="text" maxlength="6" size="6">
</div>
<!-- End footnote ID field //-->


<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the description of the footnote?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please describe the footnote.</span>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" name="goods_nomenclatures" rows="5"><?=$footnote->description?></textarea>
</div>
<!-- End description field //-->


<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the date at which the description change will take place.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input <?=$disabled?> value="<?=$footnote->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input <?=$disabled?> value="<?=$footnote->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input <?=$disabled?> value="<?=$footnote->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->
<?php
	if ($action == "edit") {
?>		
	<input type="hidden" name="validity_start_day" value="<?=$footnote->validity_start_day?>" />
	<input type="hidden" name="validity_start_month" value="<?=$footnote->validity_start_month?>" />
	<input type="hidden" name="validity_start_year" value="<?=$footnote->validity_start_year?>" />
<?php
	}
?>



		<button type="submit" class="govuk-button">Update description</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>