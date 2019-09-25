<?php
    $title = "Monetary exchange rate";
    require ("includes/db.php");
    $section_id = get_querystring("section_id");
    $monetary_exchange_rate = new monetary_exchange_rate;
    $monetary_exchange_rate->clear_cookies();
    require ("includes/header.php");
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Monetary exchange rates
            </li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Monetary exchange rates</h1>
    </div>


    <form action="/monetary_exchange_rate_create_edit.html" method="get" class="inline_form">
        <h3>Actions monetary exchange rate</h3>
        <button type="submit" class="govuk-button">Create new monetary exchange rate</button>
        <div class="clearer"><!--&nbsp;//--></div>
    </form>

    <p>The table below lists the EUR / GBP monetary exchange rates since the start of 2016. Many more exist but
    are suppressed to save space.</p>

    <table cellspacing="0" class="govuk-table">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:15%">Start date</th>
            <th class="govuk-table__header" style="width:15%">End date</th>
            <th class="govuk-table__header" style="width:25%">Exchange rate</th>
            <th class="govuk-table__header" style="width:55%">Actions</th>
        </tr>

<?php
    $sql = "SELECT mep.monetary_exchange_period_sid, mep.validity_start_date, mep.validity_end_date, mer.exchange_rate
    FROM monetary_exchange_rates mer, monetary_exchange_periods mep
    WHERE mer.monetary_exchange_period_sid = mep.monetary_exchange_period_sid 
    AND child_monetary_unit_code = 'GBP'
    AND mep.validity_start_date >= '2016-01-01'
    ORDER BY mep.validity_start_date DESC";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $validity_start_date    = short_date($row['validity_start_date']);
            $validity_end_date      = short_date($row['validity_end_date']);
            $exchange_rate          = $row['exchange_rate'];
            $monetary_exchange_period_sid = $row["monetary_exchange_period_sid"];
?>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell"><?=$validity_start_date?></td>
            <td class="govuk-table__cell"><?=$validity_end_date?></td>
            <td class="govuk-table__cell"><?=$exchange_rate?></td>
            <td class="govuk-table__cell"><a href="/monetary_exchange_rate_create_edit.html?phase=edit&monetary_exchange_period_sid=<?=$monetary_exchange_period_sid?>">Edit</a></td>
        </tr>
<?php
        }
    }
?>
        </table>
</div>

<?php
    require ("includes/footer.php")
?>