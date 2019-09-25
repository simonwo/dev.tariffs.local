<?php
    $title = "View additional code";
    require ("includes/db.php");
    $additional_code_type_id    = get_querystring("additional_code_type_id");
    $additional_code_id         = get_querystring("additional_code_id");
    $additional_code            = new additional_code;
    $additional_code->clear_cookies();
    require ("includes/header.php");
?>

<div id="wrapper" class="direction-ltr">
<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/additional_codes.html">Additional codes</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">Additional code</li>
    </ol>
    </div>
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View additional code <?=$additional_code_type_id?><?=$additional_code_id?></h1>
    </div>
            <!-- MENU //-->
            <h2 class="nomargin">Page content</h2>
            <ul class="tariff_menu">
                <li><a href="#details">Additional code details</a></li>
                <li><a href="#description_periods">Additional code description periods</a></li>
                <li><a href="#usage_measures">Additional code usage in measures</a></li>
            </ul>

            <h2 class="nomargin" id="details">Additional code details</h2>
            <table class="govuk-table" cellspacing="0">
                <tr class="govuk-table__row">
                    <th class="govuk-table__header" style="width:15%">Property</th>
                    <th class="govuk-table__header" style="width:50%">Value</th>
                </tr>
<?php
	$sql = "SELECT additional_code_type_id, additional_code, description, validity_start_date, validity_end_date
    FROM ml.ml_additional_codes WHERE additional_code_type_id = '" . $additional_code_type_id . "'
    AND additional_code = '" . $additional_code_id . "';";
    $result = pg_query($conn, $sql);
	if  ($result) {
        $row = pg_fetch_row($result);
        $additional_code_type_idx    = $row[0];
        $additional_codex            = $row[1];
        $description                = $row[2];
        $validity_start_date        = $row[3];
        $validity_end_date          = $row[4];
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Additional code type / code</td>
                    <td class="govuk-table__cell b"><?=$additional_code_type_idx?><?=$additional_codex?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Description</td>
                    <td class="govuk-table__cell tight"><?=$description?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">Start date</td>
                    <td class="govuk-table__cell"><?=short_date($validity_start_date)?></td>
                </tr>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell">End date</td>
                    <td class="govuk-table__cell"><?=short_date($validity_end_date)?></td>
                </tr>
<?php
    }
?>

            </table>
            <p class="back_to_top"><a href="#top">Back to top</a></p>






<h2 id="description_periods">Additional code description periods</h2>


<?php
    $sql = "select distinct on (validity_start_date)
    acdp.additional_code_description_period_sid, ac.additional_code_type_id,
    ac.additional_code, acdp.validity_start_date, acd.description
    from additional_codes ac, additional_code_description_periods acdp, additional_code_descriptions acd
    where ac.additional_code_sid = acdp.additional_code_sid
    and acdp.additional_code_sid = acd.additional_code_sid
    and ac.additional_code_sid = acd.additional_code_sid
    and ac.additional_code = '" . $additional_code_id . "' and ac.additional_code_type_id = '" . $additional_code_type_id . "' 
    order by validity_start_date desc";
    //echo ( $sql );
    $result = pg_query($conn, $sql);
	if  ($result) {
?>
        <p>The table below lists the historic and current descriptions for this additional code.</p>
        <table class="govuk-table" cellspacing="0">
            <tr class="govuk-table__row">
                <th class="govuk-table__header" style="width:10%">SID</th>
                <th class="govuk-table__header" style="width:65%">Name</th>
                <th class="govuk-table__header" style="width:25%">Validity start date</th>
            </tr>
<?php

        while ($row = pg_fetch_array($result)) {
            $additional_code_description_period_sid = $row["additional_code_description_period_sid"];
            $description                            = $row["description"];
            $validity_start_date                    = short_date($row["validity_start_date"]);
            $validity_start_date2                   = string_to_date($row["validity_start_date"]);
?>
                <tr class="govuk-table__row">
                    <td class="govuk-table__cell"><?=$additional_code_description_period_sid?></td>
                    <td class="govuk-table__cell"><?=$description?></td>
                    <td class="govuk-table__cell"><?=$validity_start_date2?></td>
                </tr>
<?php
        }
?>
        </table>
        <?php
    }
?>
    

            <p class="back_to_top"><a href="#top">Back to top</a></p>

</div>

<?php
    require ("includes/footer.php")
?>