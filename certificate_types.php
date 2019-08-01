<?php
    require ("includes/db.php");
    require ("includes/header.php");
?>

<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Certificate types
            </li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Certificate types</h1>
    </div>

    
<?php
	$sql = "SELECT ct.certificate_type_code, ct.validity_start_date, ct.validity_end_date, ctd.description
    FROM certificate_types ct, certificate_type_descriptions ctd
    WHERE ct.certificate_type_code = ctd.certificate_type_code
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

    <table class="govuk-table" cellspacing="0">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:15%">Certificate type</th>
            <th class="govuk-table__header" style="width:55%">Description</th>
            <th class="govuk-table__header" style="width:15%">Start date</th>
            <th class="govuk-table__header" style="width:15%">End date</th>
        </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $certificate_type_code    = $row['certificate_type_code'];
            $description            = $row['description'];
            $validity_start_date    = short_date($row['validity_start_date']);
            $validity_end_date      = short_date($row['validity_end_date']);
            $rowclass               = rowclass($validity_start_date, $validity_end_date);
?>
        <tr class="govuk-table__row <?=$rowclass?>">
            <td class="govuk-table__cell"><a href="certificates.html?certificate_type_code=<?=$certificate_type_code?>">Type <?=$certificate_type_code?></a></td>
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