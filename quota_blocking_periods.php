<?php
    $title = "Quota blocking periods";
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
        <li class="govuk-breadcrumbs__list-item">Quota blocking periods</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
  
    <div class="app-content__header">
	    <h1 style="margin-bottom:0px" class="govuk-heading-xl">View quota blocking periods</h1>
    </div>
        <p style="margin-bottom:2em">
            This screen lists all quota blocking periods that have been created for quotas that are currently active.<br /><br />
            Please click here to <a href="quota_blocking_period_create.html">create a new quota blocking period</a>.
            Please be aware that a quota blocking period can only be edited for blocking periods that are yet to finish.
            You may only delete quota suspension periods that are yet to start.
        </p>
<?php
    $key_date = "2019-04-10";
    $key_date2 = strtotime($key_date);

    $sql = "select quota_blocking_period_sid, quota_order_number_id, qd.validity_start_date, qd.validity_end_date,
    qbp.blocking_start_date, qbp.blocking_end_date, blocking_period_type, qbp.description
    from quota_blocking_periods qbp, quota_definitions qd
    where qbp.quota_definition_sid = qd.quota_definition_sid
    and qd.validity_end_date > current_date
    order by qd.quota_order_number_id, qbp.blocking_start_date
    ";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header">Quota order number</th>
        <th class="govuk-table__header">Definition dates</th>
        <th class="govuk-table__header">Blocking period dates</th>
        <th class="govuk-table__header">Blocking type</th>
        <th class="govuk-table__header">Description</th>
        <th class="govuk-table__header c">Actions</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $quota_blocking_period_sid  = $row['quota_blocking_period_sid'];
            $quota_order_number_id  = $row['quota_order_number_id'];
            $validity_start_date    = $row['validity_start_date'];
            $validity_end_date      = $row['validity_end_date'];
            $blocking_start_date    = $row['blocking_start_date'];
            $blocking_end_date      = $row['blocking_end_date'];
            $blocking_period_type   = get_blocking_type($row['blocking_period_type']);
            $description            = $row['description'];
?>  
    <tr>
        <!--
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$mqoni?>"><?=$mqoni?></a></td>
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$sqoni?>"><?=$sqoni?></a></td>
        //-->
        <td class="govuk-table__cell"><?=$quota_order_number_id?></td>
        <td class="govuk-table__cell"><?=short_date($validity_start_date)?> to <?=short_date($validity_end_date)?></td>
        <td class="govuk-table__cell tight"><?=short_date($blocking_start_date)?> to <?=short_date($blocking_end_date)?></td>
        <td class="govuk-table__cell vsmall"><?=$blocking_period_type?></td>
        <td class="govuk-table__cell vsmall"><?=$description?></td>
        <td class="govuk-table__cell c">
<?php
            # Only show if the blocking start date > today
            if ($blocking_end_date >= $key_date) {
?>            
            <form action="#" method="get">
                <button type="submit" class="govuk-button btn_nomargin")>Edit</button>
            </form>    
<?php
            }
            if ($blocking_start_date >= $key_date) {
?>
            <form action="quota_blocking_period_delete.html" method="get">
                <input type="hidden" name="quota_order_number_id" value="<?=$quota_order_number_id?>" />
                <input type="hidden" name="quota_blocking_period_sid" value="<?=$quota_blocking_period_sid?>" />
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

    function get_blocking_type($var) {
        switch ($var) {
        case 1:
            return ("Block the allocations for a quota after its reopening due to a volume increase");
            break;
        case 2:
            return ("Block the allocations for a quota after its reopening due to a volume increase");
            break;
        case 3:
            return ("Block the allocations for a quota after its reopening due to the reception of quota return requests");
            break;
        case 4:
            return ("Block the allocations for a quota due to the modification of the validity period after receiving quota return requests");
            break;
        case 5:
            return ("Block the allocations for a quota on request of a MSA");
            break;
        case 6:
            return ("Block the allocations for a quota due to an end-user decision");
            break;
        case 7:
            return ("Block the allocations for a quota due to an exceptional condition");
            break;
        case 8:
            return ("Block the allocations for a quota after its reopening due to a balance transfer");
            break;
        }
    }
?>
