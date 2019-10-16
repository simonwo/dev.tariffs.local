<?php
    $title = "Create quota suspension period";
	require ("includes/db.php");
    $application        = new application;
    $phase              = get_querystring("phase");
    $edit_mode			= get_querystring("edit_mode");
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
    if ($edit_mode == 1) {
        // Edit mode
        $crumb  = "Edit quota suspension period";
        $title  = "Quota suspension period edit submitted";
        $msg    = "1 quota suspension period edit has been submitted for approval.";
    } else {
        // Create mode
        $crumb  = "Create quota suspension period";
        $title  = "New quota suspension period submitted";
        $msg    = "1 new quota suspension period has been submitted for approval.";
    }
?>
<div id="wrapper" class="direction-ltr">
<!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quotas</a></li>
            <li class="govuk-breadcrumbs__list-item"><?=$crumb?></li>
        </ol>
    </div>
<!-- End breadcrumbs //-->


    <div class="panel panel--confirmation">
        <h1 class="heading-xlarge" style="width:1800px !important"><?=$title?></h1>
        <p class="heading-medium"><?=$msg?></p>
    </div>

    <!--
    <h3 class="heading-medium m-t-100">Next step</h3>
    <ul class="list next-steps">
        <li><a href="#">Withdraw submission/edit quota suspension period</a></li>
        <li><a href="#">Create more quota suspension periods</a></li>
        <li><a href="#">View these quota suspension periods</a></li>
        <li><a href="#">Return to main menu</a></li>
    </ul>
    //-->

</div>

<?php
	require ("includes/footer.php")
?>