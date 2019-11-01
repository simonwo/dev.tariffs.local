<?php
    $title = "Create or edit measure footnote association";
	require ("includes/db.php");
    $application        = new application;
    $measure_sid        = get_querystring("measure_sid");
    $duty_expression_id = get_querystring("duty_expression_id");
    $phase              = get_querystring("phase");

    $footnote_association_measure  = new footnote_association_measure;
    $footnote_association_measure->measure_sid = $measure_sid;
    $application->get_footnote_types();

    if ($phase == "edit_component") {
        $footnote_association_measure->measure_sid         = $measure_sid;
        $footnote_association_measure->duty_expression_id  = $duty_expression_id;
        $footnote_association_measure->populate_from_db();
        $phase = "footnote_association_measure_update";
        $duty_expression_disabled   = " disabled";
        $duty_expression_field      = "<input type='hidden' name='duty_expression_id' value='" . $duty_expression_id . "'";
    } else {
        $footnote_association_measure->populate_from_cookies();
        $phase = "footnote_association_measure_insert";
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
		<li class="govuk-breadcrumbs__list-item"><?=$footnote_association_measure->heading?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl"><?=$footnote_association_measure->heading?></h1>
</div>

<form class="tariff" method="get" action="/actions/measure_actions.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<input type="hidden" name="measure_sid" value="<?=$footnote_association_measure->measure_sid?>" />
<?php
    echo ($duty_expression_field);
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin footnote_type_id field //-->
<div class="govuk-form-group <?=$error_handler->get_error("duty_expression_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="h1_duty_expression_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="duty_expression_id">What is the footnote type?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("footnote_type_id");?>
	<select class="govuk-select" id="footnote_type_id" name="footnote_type_id">
		<option value="">- Select footnote type - </option>
<?php
	foreach ($application->footnote_types as $obj) {
        if ($obj->footnote_type_id == $footnote_association_measure->footnote_type_id) {
            echo ("<option selected value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " " . $obj->description . "</option>");
        }
	}
?>
	</select>
</div>
<!-- End footnote_type_id field //-->

<!-- Begin duty amount field //-->
<div class="govuk-form-group <?=$error_handler->get_error("footnote_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="footnote_id">What is the footnote ID?</label></h1>
	</legend>
    <span class="govuk-hint">Please enter a 3 or 5 digit value</span>
	<?=$error_handler->display_error_message("footnote_id");?>
	<input value="<?=$footnote_association_measure->footnote_id?>" class="govuk-input" style="width:10%" id="footnote_id" name="footnote_id" max="999" type="text" maxlength="5" size="5">
</div>
<!-- End duty amount field //-->



		<button type="submit" class="govuk-button">Save</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>