<?php
    $title = "Delete quota suspension period";
	require ("includes/db.php");
    $application            = new application;
    $phase                  = get_querystring("phase");
    $edit_mode              = get_querystring("edit_mode");
    $quota_order_number_id  = get_querystring("quota_order_number_id");
    $quota_suspension_period  = new quota_suspension_period;
    $err                    = get_querystring("err");

    if ($phase == "edit") {
        $footnote->footnote_id = $footnote_id;
        $footnote->populate_from_db();
        $phase = "suspension_period_edit";
    } else {
        if ($err != "") {
            $quota_suspension_period->populate_from_cookies();
        }
        $phase = "suspension_period_create";
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
		<li class="govuk-breadcrumbs__list-item">Delete quota suspension period</li>
	</ol>
</div>
<!-- End breadcrumbs //-->



<div class="app-content__header">
	<h1 style="margin-bottom:0px" class="govuk-heading-xl">Delete quota suspension period</h1>
</div>
<p style="xmargin-bottom:2em">You will delete the following quota suspension period:</p>
<table class="govuk-table" style="width:66%" cellspacing="0">
    <thead>
    <tr>
        <th class="govuk-table__header nopad">Quota order number</th>
        <td class="govuk-table__cell">090006</td>
    </tr>
    <tr>
        <th class="govuk-table__header nopad">Quota definition period</th>
        <td class="govuk-table__cell">01 June 2019 to 30 May 2020</td>
    </tr>
    <tr>
        <th class="govuk-table__header nopad">Quota suspension period</th>
        <td class="govuk-table__cell">01 Sep 2019 to 15 Sep 2109</td>
    </tr>
    <tr>
        <th class="govuk-table__header nopad" style="vertical-align:top !important">Description</th>
        <td class="govuk-table__cell">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent blandit sem mauris,
            et consectetur magna rutrum id. Donec facilisis dictum dolor id dignissim</td>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>

    </tbody>
</table>

<form class="tariff" method="post" action="/actions/quota_suspension_periods.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "footnote_edit") {
        echo ('<input type="hidden" name="footnote_id" value="' . $footnote->footnote_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin workbasket field //-->
<div class="govuk-form-group <?=$error_handler->get_error("workbasket_name");?>" style="width:66%">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_workbasket_name" class="govuk-fieldset__heading" style="max-width:100%;"><label for="workbasket_name">What is the name of this workbasket?</label></h1>
	</legend>
    <span class="govuk-hint">This will allow you to identify the workbasket if you save progress, it will also help cross-checkers and approvers identify it. Be descriptive!</span>
	<?=$error_handler->display_error_message("workbasket_name");?>
	<input value="<?=$quota_suspension_period->workbasket_name?>" class="govuk-input" style="width:25%" id="workbasket_name" name="workbasket_name" type="text" maxlength="50" size="50">
</div>
<!-- End workbasket field //-->

<!-- Begin reason field //-->
<div class="govuk-form-group <?=$error_handler->get_error("workbasket_name");?>" style="width:66%">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_workbasket_name" class="govuk-fieldset__heading" style="max-width:100%;"><label for="workbasket_name">What is the reason for these changes?</label></h1>
	</legend>
    <span class="govuk-hint">Be as descriptive as you can, and keep in mind what you type here may become publicly visible. Please list the changes you intend to make.</span>
	<?=$error_handler->display_error_message("workbasket_name");?>
	<input value="<?=$quota_suspension_period->workbasket_name?>" class="govuk-input" style="width:25%" id="workbasket_name" name="workbasket_name" type="text" maxlength="50" size="50">
</div>
<!-- End reason field //-->



		<button type="submit" class="govuk-button">Continue</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>