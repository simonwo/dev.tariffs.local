<?php
    $title = "Footnotes";
    require ("includes/db.php");
    $footnote_type_id = get_querystring("footnote_type_id");
    $footnote = new footnote;
    $footnote->clear_cookies();
    require ("includes/header.php");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
    <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/footnote_types.html">Footnote types</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Footnotes
        </li>
    </ol>
    </div>
    <!-- End breadcrumbs //-->
<?php
    $clause = "";
    if ($footnote_type_id != "") {
        $clause = " WHERE footnote_type_id = '" . $footnote_type_id . "' ";
        $heading = "footnotes for type " . $footnote_type_id;
    } else {
        $heading = "footnotes";
    }
?>    
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">View <?=$heading?></h1>
    </div>

<?php
	$sql = "SELECT footnote_type_id, footnote_id, description, validity_start_date, validity_end_date
    FROM ml.ml_footnotes f " . $clause . " ORDER BY 1, 2";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Footnote ID</th>
        <th class="govuk-table__header" style="width:15%">Footnote type</th>
        <th class="govuk-table__header" style="width:36%">Description</th>
        <th class="govuk-table__header c" style="width:16%">Start date</th>
        <th class="govuk-table__header c" style="width:16%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $footnote_type_id    = $row['footnote_type_id'];
            $footnote_id         = $row['footnote_id'];
            $description         = $row['description'];
            $validity_start_date = short_date($row['validity_start_date']);
            $validity_end_date   = short_date($row['validity_end_date']);
            $rowclass            = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="<?=$rowclass?>">
        <td class="govuk-table__cell"><a href="footnote_view.html?footnote_type_id=<?=$footnote_type_id?>&footnote_id=<?=$footnote_id?>"><?=$footnote_type_id?><?=$footnote_id?></a></td>
        <td class="govuk-table__cell"><?=$footnote_type_id?></td>
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