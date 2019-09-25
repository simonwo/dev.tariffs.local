<?php
    $title = "Measurement unit qualifiers";
    require ("includes/db.php");
    require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">Measurement unit qualifiers</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Measurement unit qualifiers</h1>
    </div>

    <?php
	$sql = "SELECT muq.measurement_unit_qualifier_code, description
    FROM measurement_unit_qualifiers muq, measurement_unit_qualifier_descriptions muqd
    WHERE muq.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
    AND muq.validity_end_date IS NULL
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Measurement unit qualifier</th>
        <th class="govuk-table__header" style="width:85%">Description</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
            $description            = $row['description'];
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell"><?=$measurement_unit_qualifier_code?></td>
        <td class="govuk-table__cell"><?=$description?></td>
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