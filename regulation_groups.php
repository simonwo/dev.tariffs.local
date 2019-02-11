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
            Regulation groups
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <main id="content" lang="en">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">Regulation groups</h1></div>
                </div>
            </div>

<?php
	$sql = "SELECT rg.regulation_group_id, validity_start_date, validity_end_date, description FROM regulation_groups rg, regulation_group_descriptions rgd
    WHERE rg.regulation_group_id = rgd.regulation_group_id
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:18%">Regulation group ID</th>
        <th class="govuk-table__header" style="width:41%">Description</th>
        <th class="govuk-table__header" style="width:13%">Start date</th>
        <th class="govuk-table__header" style="width:13%">End date</th>
        <th class="govuk-table__header" style="width:15%">Action</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $regulation_group_id    = $row['regulation_group_id'];
            $description            = $row['description'];
            $validity_start_date    = string_to_date($row['validity_start_date']);
            $validity_end_date      = string_to_date($row['validity_end_date']);
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell"><?=$regulation_group_id?></td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell"><?=$validity_start_date?></td>
        <td class="govuk-table__cell"><?=$validity_end_date?></td>
        <td class="govuk-table__cell">
            <a href="regulations.php?regulation_group_id=<?=$regulation_group_id?>">View regulations</a><br />
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