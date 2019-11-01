<?php
    $title = "Create quota association";
	require ("includes/db.php");
    $application            = new application;
    $quota_order_number_id  = get_querystring("quota_order_number_id");
    $geographical_area_id   = get_querystring("geographical_area_id");
    
	require ("includes/header.php");
?>
<div id="wrapper" class="direction-ltr">
<!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
            <li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/quota_order_numbers.html">Quotas</a></li>
            <li class="govuk-breadcrumbs__list-item">Quota origin added</li>
        </ol>
    </div>
<!-- End breadcrumbs //-->


    <div class="panel panel--confirmation">
        <h1 class="heading-xlarge" style="width:1800px !important">Quota origin <?=$geographical_area_id?> added</h1>
        <p class="heading-medium">The origin <?=$geographical_area_id?> has been added to order number <strong><?=$quota_order_number_id?></strong>.</p>
    </div>

    <h3 class="heading-medium m-t-100">Next step</h3>
    <ul class="list next-steps">
        <li><a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>#origins">View and assign origins</a></li>
        <li><a href="quota_order_number_view.html?quota_order_number_id=<?=$quota_order_number_id?>#definitions">View and assign definition periods</a></li>
        <li><a href="#">Create measures</a></li>
        <li><a href="#">Create quota associations</a></li>
        <li><a href="/">Return to main menu</a></li>
    </ul>  

</div>

<?php
	require ("includes/footer.php")
?>