<?php
    $title = "Additional codes";
    require ("includes/db.php");
    require ("includes/header.php");
	$additional_code_type_id   = get_querystring("additional_code_type_id");
	$additional_code_id   = get_querystring("additional_code_id");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Additional codes
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->

    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Additional codes</h1>
    </div>
    
<?php
	$sql = "SELECT additional_code_type_id, additional_code, validity_start_date, validity_end_date, description
    FROM ml.ml_additional_codes
    where additional_code_type_id = '" . $additional_code_type_id . "'";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header c" style="width:12%">Additional code type</th>
        <th class="govuk-table__header c" style="width:12%">Additional code</th>
        <th class="govuk-table__header" style="width:34%">Description</th>
        <th class="govuk-table__header" style="width:12%">Start date</th>
        <th class="govuk-table__header" style="width:12%">End date</th>
        <th class="govuk-table__header" style="width:18%">Action</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $additional_code_type_id    = $row['additional_code_type_id'];
            $additional_code            = $row['additional_code'];
            $description                = $row['description'];
            $validity_start_date        = short_date($row['validity_start_date']);
            $validity_end_date          = short_date($row['validity_end_date']);
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell c"><?=$additional_code_type_id?></td>
        <td class="govuk-table__cell c">
            <a href="additional_code_view.html?additional_code_type_id=<?=$additional_code_type_id?>&additional_code_id=<?=$additional_code?>"><?=$additional_code?></a>
        </td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell"><?=$validity_start_date?></td>
        <td class="govuk-table__cell"><?=$validity_end_date?></td>
        <td class="govuk-table__cell">
            <a href="#">View relevant measures</a><br />
        </td>
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