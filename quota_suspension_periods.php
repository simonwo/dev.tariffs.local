<?php
    $title = "Quota suspension periods";
    require ("includes/db.php");
    $certificate_type_code = get_querystring("certificate_type_code");
    $certificate = new certificate;
    $certificate->clear_cookies();
    require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
    <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">Quota suspension periods</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
  
    <div class="app-content__header">
	    <h1 style="margin-bottom:0px" class="govuk-heading-xl">View quota suspension periods</h1>
    </div>
        <p style="margin-bottom:2em">
            This screen lists all quota blocking periods that have been created for quotas that are currently active.<br /><br />
            Please click here to <a href="quota_suspension_period_create.html">create a new quota suspension period</a>.
            Please be aware that a quota suspension period can only be edited for suspension periods that are yet to finish.
            You may only delete quota suspension periods that are yet to start.
        </p>
<?php
    $key_date = "2019-03-01";
    $key_date2 = strtotime($key_date);


    $sql = "select quota_order_number_id, qd.validity_start_date, qd.validity_end_date,
    qbp.suspension_start_date, qbp.suspension_end_date, qbp.description
    from quota_suspension_periods qbp, quota_definitions qd
    where qbp.quota_definition_sid = qd.quota_definition_sid
    and qd.validity_end_date > current_date
    order by qd.quota_order_number_id
    ";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header">Quota order number</th>
        <th class="govuk-table__header">Definition dates</th>
        <th class="govuk-table__header">Suspension period dates</th>
        <th class="govuk-table__header">Description</th>
        <th class="govuk-table__header r">Actions</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $quota_order_number_id  = $row['quota_order_number_id'];
            $validity_start_date    = ($row['validity_start_date']);
            $validity_end_date      = ($row['validity_end_date']);
            $suspension_start_date    = ($row['suspension_start_date']);
            $suspension_end_date      = ($row['suspension_end_date']);
            $description            = $row['description'];
?>  
    <tr>
        <!--
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$mqoni?>"><?=$mqoni?></a></td>
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$sqoni?>"><?=$sqoni?></a></td>
        //-->
        <td class="govuk-table__cell"><?=$quota_order_number_id?></td>
        <td class="govuk-table__cell"><?=short_date($validity_start_date)?> to <?=short_date($validity_end_date)?></td>
        <td class="govuk-table__cell tight"><?=short_date($suspension_start_date)?> to <?=short_date($suspension_end_date)?></td>
        <td class="govuk-table__cell vsmall"><?=$description?></td>
        <td class="govuk-table__cell r">
        <?php
            # Only show if the blocking start date > today
            if ($suspension_end_date >= $key_date) {
?>            
            <form action="#" method="get">
                <input type="hidden" name="action" value="delete" />
                <button type="submit" class="govuk-button btn_nomargin")>Edit</button>
            </form>    
<?php
            }
            if ($suspension_start_date >= $key_date) {
?>
            <form action="#" method="get">
                <input type="hidden" name="action" value="delete" />
                <button type="submit" class="govuk-button btn_nomargin")>Delete</button>
            </form>    
<?php
            }
?>
        </td>
    </tr>
<?php            
		}
	}
?>
<table>
</div>
</div>
<?php
    require ("includes/footer.php");
?>
