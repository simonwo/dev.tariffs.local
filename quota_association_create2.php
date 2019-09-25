<?php
    $title = "Create quota association";
	require ("includes/db.php");
    $application    = new application;
    $phase          = get_querystring("phase");
    $quota_association  = new quota_association;

    if ($phase == "edit") {
        $footnote->footnote_id = $footnote_id;
        $footnote->populate_from_db();
        $phase = "footnote_edit";
    } else {
        $quota_association->populate_from_cookies();
        $phase = "footnote_create2";
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
		<li class="govuk-breadcrumbs__list-item">Create quota association</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 style="margin-bottom:0px" class="govuk-heading-xl">Create quota association</h1>
</div>
<p style="margin-bottom:2em">
    You are associating parent quota
    <strong><?=$quota_association->main_quota_order_number_id?></strong>
    with child quota <strong><?=$quota_association->sub_quota_order_number_id?></strong>.<br /><br />
    Describe the type of relationship that you would like to create between these quotas and the periods
    that you would like to link. Please ensure that you select <strong>concurrent</strong> periods with the same start
    and end dates to ensure that the quota association functionality works correctly. If concurrent periods exist, then
    the two quota definition periods will be synchronised automatically.
</p>

<form class="tariff" method="post" action="/quota_association_confirm.html">

<input type="hidden" name="phase" value="<?=$phase?>" />
<input type="hidden" name="main_quota_order_number_id" value="<?=$quota_association->main_quota_order_number_id?>" />
<input type="hidden" name="sub_quota_order_number_id" value="<?=$quota_association->sub_quota_order_number_id?>" />
<?php
    if ($phase == "footnote_edit") {
        echo ('<input type="hidden" name="footnote_id" value="' . $footnote->footnote_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin parent period field //-->
<div class="govuk-form-group <?=$error_handler->get_error("definition_period");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="main_definition_period">Select the parent quota definition period</label></h1>
	</legend>
    <span class="govuk-hint">To use this functionality, the quota definition period must already exist on the system.</span>
	<?=$error_handler->display_error_message("definition_period");?>
	<select class="govuk-select" id="main_definition_period" name="main_definition_period">
        <option value="0">- Select parent quota definition period - </option>
        <option value="123">01-01-20 to 31-12-20</option>
        <option value="456">01-01-19 to 31-12-19</option>
        <option value="789">01-01-18 to 31-12-18</option>
<?php
/*
	foreach ($footnote->footnote_types as $obj) {
        if ($obj->footnote_type_id == $footnote->footnote_type_id) {
            echo ("<option selected value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        }
    }
*/
?>
	</select>
</div>
<!-- End parent period field //-->

<!-- Begin child period field //-->
<div class="govuk-form-group <?=$error_handler->get_error("definition_period");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="sub_definition_period">Select the child quota definition period</label></h1>
	</legend>
    <span class="govuk-hint">To use this functionality, the quota definition period must already exist on the system.</span>
	<?=$error_handler->display_error_message("definition_period");?>
	<select class="govuk-select" id="sub_definition_period" name="sub_definition_period">
		<option value="0">- Select child quota definition period - </option>
        <option value="012">01-01-20 to 31-12-20</option>
        <option value="345">01-01-19 to 31-12-19</option>
        <option value="678">01-01-18 to 31-12-18</option>
<?php
/*
	foreach ($footnote->footnote_types as $obj) {
        if ($obj->footnote_type_id == $footnote->footnote_type_id) {
            echo ("<option selected value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        } else {
            echo ("<option value='" . $obj->footnote_type_id . "'>" . $obj->footnote_type_id . " - " . $obj->description . "</option>");
        }
    }
*/
?>
	</select>
</div>
<!-- End child period field //-->

<!-- Begin relation type //-->
<fieldset class="govuk-fieldset">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_geographical_area_id" class="govuk-fieldset__heading" style="max-width:100%">Select the relation type</h1>
    </legend>
    <span id="changed-name-hint" class="govuk-hint">Some text goes here.</span>
    <div class="clearer"><!--&nbsp;//--></div>
    <div class="govuk-radios govuk-radios--inline" style="margin-bottom:1em">
        <div class="govuk-radios__item break">
            <input type="radio" class="govuk-radios__input" name="relation_type" id="relation_type_eq" value="EQ" />
            <label class="govuk-label govuk-radios__label" for="relation_type_eq">EQ - Equivalent to main quota</label>
        </div>
    </div>

    <div class="govuk-radios govuk-radios--inline" style="margin-bottom:1em">
        <div class="govuk-radios__item break">
            <input type="radio" class="govuk-radios__input" name="relation_type" id="relation_type_nm" value="NM" />
            <label class="govuk-label govuk-radios__label" for="relation_type_nm">NM - Normal (restrictive to main quota)</label>
        </div>
    </div>
</fieldset>

<!-- Begin main quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="coefficient">Enter the co-efficient</label></h1>
	</legend>
    <span class="govuk-hint">The coefficient determines the ... If you select a relation type of &quot;NM&quot; in the relation
        type field (above), then the coefficient field will automatically be set to the value &quot;1.00000&quot;<br /><br />
        Please enter up to 5 decimal places.
    </span>
	<?=$error_handler->display_error_message("quota_order_number_id");?>
	<input value="" class="govuk-input" style="width:10%" id="coefficient" name="coefficient" type="text" maxlength="8" size="8">
</div>
<!-- End main quota order number field //-->






		<button type="submit" class="govuk-button">Send quota association for approval</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>