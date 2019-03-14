<?php
    require ("includes/db.php");
    require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Footnote types
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Footnote types</h1>
    </div>

    <?php
	$sql = "SELECT ft.footnote_type_id, application_code, validity_start_date, validity_end_date, description
    FROM footnote_types ft, footnote_type_descriptions ftd
    WHERE ft.footnote_type_id = ftd.footnote_type_id
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header c" style="width:15%">Footnote type</th>
        <th class="govuk-table__header c" style="width:15%">Application code</th>
        <th class="govuk-table__header" style="width:36%">Description</th>
        <th class="govuk-table__header c" style="width:16%">Start date</th>
        <th class="govuk-table__header c" style="width:16%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $footnote_type_id    = $row['footnote_type_id'];
            $application_code    = $row['application_code'];
            $description         = $row['description'];
            $validity_start_date = short_date($row['validity_start_date']);
            $validity_end_date   = short_date($row['validity_end_date']);
            $rowclass            = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="govuk-table__row <?=$rowclass?>">
        <td class="govuk-table__cell c b"><a href="footnotes.html?footnote_type_id=<?=$footnote_type_id?>"><?=$footnote_type_id?></a></td>
        <td class="govuk-table__cell c"><?=footnote_type_application_code($application_code)?> (<?=$application_code?>)</td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell c"><?=$validity_start_date?></td>
        <td class="govuk-table__cell c"><?=$validity_end_date?></td>
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