<?php
    $title = "Create quota association";
	require ("includes/db.php");
    $application        = new application;
    $phase              = get_querystring("phase");
    $quota_association  = new quota_association;
    $err                = get_querystring("err");

    if ($phase == "edit") {
        $footnote->footnote_id = $footnote_id;
        $footnote->populate_from_db();
        $phase = "association_edit";
    } else {
        if ($err != "") {
            $quota_association->populate_from_cookies();
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
		<li class="govuk-breadcrumbs__list-item">Create quota association</li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 style="margin-bottom:0px" class="govuk-heading-xl">Create quota association</h1>
</div>
<p style="margin-bottom:2em">
    Use this functionality to associate parent and child quotas together.<br /><br />
    Alternatively, please 
    click here to view <a href="quota_associations.html">existing quota associations</a>.
</p>

<form class="tariff" method="post" action="/actions/quota_associations.html">
<input type="hidden" name="phase" value="<?=$phase?>" />
<?php
    if ($phase == "footnote_edit") {
        echo ('<input type="hidden" name="footnote_id" value="' . $footnote->footnote_id . '" />');
    }
?>
<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin main quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("main_quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_main_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="main_quota_order_number_id">Please enter the parent (main) quota order number ID</label></h1>
	</legend>
    <span class="govuk-hint">Please ensure that you select an existing quota order number ID with 6 numeric digits beginning &quot;09&quot;.</span>
	<?=$error_handler->display_error_message("main_quota_order_number_id");?>
	<input value="<?=$quota_association->main_quota_order_number_id?>" class="govuk-input" style="width:10%" id="main_quota_order_number_id" name="main_quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End main quota order number field //-->

<!-- Begin sub quota order number field //-->
<div class="govuk-form-group <?=$error_handler->get_error("sub_quota_order_number_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_sub_quota_order_number_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="sub_quota_order_number_id">Please enter the child (sub) quota order number ID</label></h1>
	</legend>
    <span class="govuk-hint">Please ensure that you select an existing quota order number ID with 6 numeric digits beginning &quot;09&quot;.</span>
	<?=$error_handler->display_error_message("sub_quota_order_number_id");?>
	<input value="<?=$quota_association->sub_quota_order_number_id?>" class="govuk-input" style="width:10%" id="sub_quota_order_number_id" name="sub_quota_order_number_id" type="text" maxlength="6" size="6">
</div>
<!-- End sub quota order number field //-->





		<button type="submit" class="govuk-button">Continue</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>