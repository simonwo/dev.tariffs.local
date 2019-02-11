<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $certificate_type_code = get_querystring("certificate_type_code");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
    <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/certificate_types.php">Certificate types</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Certificates
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
<?php
    $clause = "";
    if ($certificate_type_code != "") {
        $heading = "Certificates of type " . $certificate_type_code;
        $clause = " WHERE certificate_type_code = '" . $certificate_type_code . "'";
    } else {
        $heading = "Certificates";
    }
?>   

    <div class="app-content__header">
        <h1 class="govuk-heading-xl"><?=$heading?></h1>
    </div>



<?php
    $clause = "";
    if ($certificate_type_code != "") {
        $clause = " WHERE certificate_type_code = '" . $certificate_type_code . "'";
    }
	$sql = "SELECT certificate_type_code, certificate_code, description, validity_start_date, validity_end_date
    FROM ml.ml_certificate_codes " . $clause . " ORDER BY 1, 2";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th style="width:15%" class="c">Certificate type</th>
        <th style="width:15%" class="c">Certificate code</th>
        <th style="width:46%">Description</th>
        <th style="width:12%">Start date</th>
        <th style="width:12%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $certificate_type_code  = $row['certificate_type_code'];
            $certificate_code       = $row['certificate_code'];
            $description            = $row['description'];
            $validity_start_date    = string_to_date($row['validity_start_date']);
            $validity_end_date      = string_to_date($row['validity_end_date']);
            $rowclass               = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="<?=$rowclass?>">
        <td class="c"><?=$certificate_type_code?></td>
        <td class="c"><a href="certificate_view.php?certificate_type_code=<?=$certificate_type_code?>&certificate_code=<?=$certificate_code?>"><?=$certificate_code?></a></td>
        <td><?=$description?></td>
        <td><?=$validity_start_date?></td>
        <td><?=$validity_end_date?></td>
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