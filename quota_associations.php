<?php
    $title = "Quota associations";
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
        <li class="govuk-breadcrumbs__list-item">Quota associations</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
  
    <div class="app-content__header">
	    <h1 style="margin-bottom:0px" class="govuk-heading-xl">View quota associations</h1>
    </div>
        <p style="margin-bottom:2em">
            This screen lists all quota associations that have been created for quotas in
            the last 2 years.<br /><br />
            Please click here to <a href="quota_association_create.html">create a new quota association</a>. If you would like to edit
            the values associated with an existing quota association, please
            delete the association and then create a new association afterwards. Please be aware that a quota association can only
            be deleted / edited for quota definition periods that are yet to start.
        </p>
<?php
    $key_date = strtotime("2019-01-01");
    $key_date = time();

    $sql = "select qdm.quota_order_number_id as mqoni, qds.quota_order_number_id as sqoni,
    qdm.quota_definition_sid as mqds, qds.quota_definition_sid as sqds, qa.relation_type, qa.coefficient,
    qdm.validity_start_date, qdm.validity_end_date
    from quota_associations qa, quota_definitions qdm, quota_definitions qds
    where qa.main_quota_definition_sid = qdm.quota_definition_sid
    and qa.sub_quota_definition_sid = qds.quota_definition_sid
    and qdm.validity_start_date >= '2018-01-01'
    order by qdm.quota_order_number_id, qdm.validity_start_date desc";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header">Parent quota</th>
        <th class="govuk-table__header">Child quota</th>
        <!--
        <th class="govuk-table__header">Parent definition</th>
        <th class="govuk-table__header">Child definition</th>
        //-->
        <th class="govuk-table__header">Relation type</th>
        <th class="govuk-table__header">Coefficient</th>
        <th class="govuk-table__header c">Start date</th>
        <th class="govuk-table__header c">End date</th>
        <th class="govuk-table__header c">Actions</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $mqoni                  = $row['mqoni'];
            $sqoni                  = $row['sqoni'];
            $mqds                   = $row['mqds'];
            $sqds                   = $row['sqds'];
            $relation_type          = $row['relation_type'];
            $coefficient            = $row['coefficient'];
            $validity_start_date    = short_date($row['validity_start_date']);
            $validity_end_date      = short_date($row['validity_end_date']);
?>
    <tr>
        <!--
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$mqoni?>"><?=$mqoni?></a></td>
        <td class="govuk-table__cell"><a href="quota_order_number_view.html?quota_order_number_id=<?=$sqoni?>"><?=$sqoni?></a></td>
        //-->
        <td class="govuk-table__cell"><?=$mqoni?></td>
        <td class="govuk-table__cell"><?=$sqoni?></td>
        <td class="govuk-table__cell"><?=$relation_type?></td>
        <td class="govuk-table__cell tight"><?=$coefficient?></td>
        <td class="govuk-table__cell c"><?=$validity_start_date?></td>
        <td class="govuk-table__cell c"><?=$validity_end_date?></td>
        <td class="govuk-table__cell c">
<?php
    if (strtotime($row['validity_start_date']) >= $key_date) {
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
    require ("includes/footer.php")
?>