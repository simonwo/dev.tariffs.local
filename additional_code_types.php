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
            Additional code types
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Additional code types</h1>
    </div>

<?php
	$sql = "SELECT ac.additional_code_type_id, ac.validity_start_date, ac.validity_end_date, acd.description
    FROM additional_code_types ac, additional_code_type_descriptions acd
    WHERE ac.additional_code_type_id = acd.additional_code_type_id
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header c" style="width:15%">Additional code type</th>
        <th class="govuk-table__header" style="width:61%">Description</th>
        <th class="govuk-table__header" style="width:12%">Start date</th>
        <th class="govuk-table__header" style="width:12%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $additional_code_type_id    = $row['additional_code_type_id'];
            $description                = $row['description'];
            $validity_start_date        = short_date($row['validity_start_date']);
            $validity_end_date          = short_date($row['validity_end_date']);
            $rowclass                   = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="govuk-table__row <?=$rowclass?>">
        <td class="govuk-table__cell c"><a href="additional_codes?additional_code_type_id=<?=$additional_code_type_id?>">Type <?=$additional_code_type_id?></a></td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell"><?=$validity_start_date?></td>
        <td class="govuk-table__cell"><?=$validity_end_date?></td>
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