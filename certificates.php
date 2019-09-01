<?php
    $title = "Certificates";
    require ("includes/db.php");
    $certificate_type_code = get_querystring("certificate_type_code");
    $certificate = new certificate;
    $certificate->clear_cookies();
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
            <a class="govuk-breadcrumbs__link" href="/certificate_types.html">Certificate types</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">Certificates</li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
<?php
    $clause = "";
    if ($certificate_type_code != "") {
        $clause = " WHERE certificate_type_code = '" . $certificate_type_code . "' ";
        $heading = "certificates for type " . $certificate_type_code;
    } else {
        $heading = "certificates";
    }
?>    
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View <?=$heading?></h1>
    </div>

<?php
	$sql = "SELECT certificate_type_code, certificate_code, description, validity_start_date, validity_end_date
    FROM ml.ml_certificate_codes c " . $clause . " ORDER BY 1, 2";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:10%">Certificate ID</th>
        <th class="govuk-table__header" style="width:12%">Certificate type</th>
        <th class="govuk-table__header" style="width:44%">Description</th>
        <th class="govuk-table__header c" style="width:16%">Start date</th>
        <th class="govuk-table__header c" style="width:16%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $certificate_type_code    = $row['certificate_type_code'];
            $certificate_code         = $row['certificate_code'];
            $description         = $row['description'];
            $validity_start_date = short_date($row['validity_start_date']);
            $validity_end_date   = short_date($row['validity_end_date']);
            $rowclass            = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="<?=$rowclass?>">
        <td class="govuk-table__cell"><a href="certificate_view.html?certificate_type_code=<?=$certificate_type_code?>&certificate_code=<?=$certificate_code?>"><?=$certificate_type_code?><?=$certificate_code?></a></td>
        <td class="govuk-table__cell"><?=$certificate_type_code?></td>
        <td class="govuk-table__cell tight"><?=$description?></td>
        <td class="govuk-table__cell c"><?=$validity_start_date?></td>
        <td class="govuk-table__cell c"><?=$validity_end_date?></td>
    </tr>
<?php            
		}
	}
?>
<table>
</div>
<?php
    require ("includes/footer.php")
?>