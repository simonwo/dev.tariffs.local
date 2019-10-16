<?php
    $title = "Create quota suspension period";
    require ("includes/db.php");
    $application                = new application;
    $phase                      = get_querystring("phase");
    $edit_mode                  = get_querystring("edit_mode");
    $quota_suspension_period    = new quota_suspension_period;
    $err                        = get_querystring("err");

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
    
    if ($edit_mode == 1) {
        // Edit mode
        $quota_suspension_period->quota_order_number_id = "090006";
        $disabled   = " disabled";
        $title      = "Edit quota suspension period";
        $msg        = '';
        $on_msg     = '';
    } else {
        // Create mode
        $disabled   = "";
        $title      = "Create quota suspension period";
        $msg        = 'Use this functionality to create a suspension period for a given quota.<br /><br />
        Alternatively, please click here to view <a href="quota_suspension_periods.html">existing quota suspension periods</a>.';
        $on_msg     = 'Please ensure that you select an existing quota order number ID with 6 numeric digits beginning &quot;09&quot;. Only select
        <abbr title="First Come First Served">FCFS</abbr> quotas that do not start with the characters &quot;094&quot;.';
    }

?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quotas</a></li>
		<li class="govuk-breadcrumbs__list-item"><?=$title?></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 style="margin-bottom:0px" class="govuk-heading-xl"><?=$title?></h1>
</div>
<p style="margin-bottom:2em"><?=$msg?></p>

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
<div class="govuk-form-group <?=$error_handler->get_error("workbasket_name");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_workbasket_name" class="govuk-fieldset__heading" style="max-width:100%;"><label for="workbasket_name">Please enter the name of the workbasket</label></h1>
	</legend>
    <!--<span class="govuk-hint">Lorem ipsum dolor sit amet ...</span>//-->
	<?=$error_handler->display_error_message("workbasket_name");?>
	<input value="<?=$quota_suspension_period->workbasket_name?>" class="govuk-input" style="width:25%" id="workbasket_name" name="workbasket_name" type="text" maxlength="50" size="50">
</div>
<!-- End workbasket field //-->


<!-- Begin main quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="quota_order_number_id">Please enter the quota order number ID</label></h1>
	</legend>
    <span class="govuk-hint"><?=$on_msg?></span>
	<?=$error_handler->display_error_message("quota_order_number_id");?>
	<input <?=$disabled?> value="<?=$quota_suspension_period->quota_order_number_id?>" class="govuk-input" style="width:10%" id="quota_order_number_id" name="quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End main quota order number field //-->


		<button type="submit" class="govuk-button">Continue</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>