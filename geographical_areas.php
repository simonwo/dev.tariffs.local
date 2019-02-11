<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $regulation_group_id    = get_querystring("regulation_group_id");
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/regulations.php">Geographical areas</a>
        </li>
<?php
    if ($regulation_group_id != "") {
?>
        <li class="govuk-breadcrumbs__list-item">
            <?=$regulation_group_id?>
        </li>
<?php
    }
?>        
    </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Geographical areas</h1>
    </div>
<?php
    $clause = "";
    $offset         = intval($pagesize) * ($page - 1);
    $limit_clause   = "LIMIT " . $pagesize . " OFFSET " . $offset;

    if ($regulation_group_id != "") {
        $clause .= "WHERE b.regulation_group_id = '" . $regulation_group_id . "'";
    }

    $countsql = "SELECT COUNT(DISTINCT geographical_area_id) FROM ml.ml_geographical_areas";
    $result = pg_query($conn, $countsql);
    if ($result) {
        $row = pg_fetch_row($result);
        $result_count = $row[0];
        $page_count = ceil($result_count / $pagesize);
    }

    $sql = "SELECT geographical_area_sid, geographical_area_id, description, geographical_code,
    validity_start_date, validity_end_date FROM ml.ml_geographical_areas ORDER BY 2 " . $limit_clause;
    $result = pg_query($conn, $sql);
	if (($result) && pg_num_rows($result) > 0) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:10%">Area ID</th>
        <th class="govuk-table__header c" style="width:10%">Area SID</th>
        <th class="govuk-table__header" style="width:41%">Description</th>
        <th class="govuk-table__header c" style="width:10%">Geographical code</th>
        <th class="govuk-table__header" style="width:12%">Start date</th>
        <th class="govuk-table__header" style="width:12%">End date</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $geographical_area_id       = $row['geographical_area_id'];
            $geographical_area_sid      = $row['geographical_area_sid'];
            $description                = $row['description'];
            $geographical_code          = $row['geographical_code'];
            $validity_start_date        = string_to_date($row['validity_start_date']);
            $validity_end_date          = string_to_date($row['validity_end_date']);
            $rowclass                    = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="<?=$rowclass?>">
        <td class="govuk-table__cell"><a href="/geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?></a></td>
        <td class="govuk-table__cell c"><?=$geographical_area_sid?></td>
        <td class="govuk-table__cell"><?=$description?></td>
        <td class="govuk-table__cell c"><?=$geographical_code?><br /><span class="explanatory"><?=geographical_code($geographical_code)?></span></td>
        <td class="govuk-table__cell"><?=$validity_start_date?></td>
        <td class="govuk-table__cell"><?=$validity_end_date?></td>
    </tr>
<?php            
		}
	} else {
        echo ("<p>No matching geographical areas</p>");
    }
?>
</table>
<?php
    $url = "/geographical_areas.php?";
    for ($i = 1; $i <= $page_count; $i++) {
        if (intval($i) == intval($page)) {
            $class = " selected";
        } else {
            $class = "";
        }
        $url2 = $url . "&page=" . $i;
        echo ("<span class='paging " . $class ."'><a href='" . $url2 . "'>" . $i . "</a></span>");
    }
?>
<div class="clearer"><!--&nbsp;//--></div>
</div>
<?php
    require ("includes/footer.php")
?>