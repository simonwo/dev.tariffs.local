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
        <li class="govuk-breadcrumbs__list-item">Measurement units</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Measurement units</h1>
    </div>
    <p>The applicable qualifiers are defined in the measurements table.</p>

<?php
    $sql = "SELECT m.measurement_unit_code, m.measurement_unit_qualifier_code, muqd.description
    FROM measurements m, measurement_unit_qualifier_descriptions muqd
    WHERE m.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
    ORDER BY 1, 2";
    $result = pg_query($conn, $sql);
    $measurement_list = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $measurement = new measurement;
            $measurement->measurement_unit_code              = $row['measurement_unit_code'];
            $measurement->measurement_unit_qualifier_code    = $row['measurement_unit_qualifier_code'];
            $measurement->description                        = $row['description'];
            array_push ($measurement_list, $measurement);
        }
    }
?>


<?php
	$sql = "SELECT mu.measurement_unit_code, description
    FROM measurement_units mu, measurement_unit_descriptions mud
    WHERE mu.measurement_unit_code = mud.measurement_unit_code
    AND mu.validity_end_date IS NULL
    ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Measurement unit</th>
        <th class="govuk-table__header" style="width:45%">Description</th>
        <th class="govuk-table__header" style="width:40%">Applicable qualifiers</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $measurement_unit_code  = $row['measurement_unit_code'];
            $description            = $row['description'];
            $applicable_q           = "";
            foreach ($measurement_list as $m) {
                #p ($m->measurement_unit_code . " | " . $measurement_unit_code);
                if ($m->measurement_unit_code == $measurement_unit_code) {
                    $applicable_q .= $m->measurement_unit_qualifier_code . " - " . $m->description . "<br />";
                }
            }
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell"><?=$measurement_unit_code?></td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell"><?=$applicable_q?></td>
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