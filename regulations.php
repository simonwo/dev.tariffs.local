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
            <a class="govuk-breadcrumbs__link" href="/regulations.php">Regulations</a>
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
    <main id="content" lang="en">
        <div class="grid-row">
            <div class="column-two-thirds">
                <div class="gem-c-title gem-c-title--margin-bottom-5">
                    <h1 class="gem-c-title__text">Regulations</h1></div>
                </div>
            </div>

<?php
    $clause = "";
    $offset         = intval($pagesize) * ($page - 1);
    $limit_clause   = "LIMIT " . $pagesize . " OFFSET " . $offset;

    if ($regulation_group_id != "") {
        $clause .= "WHERE b.regulation_group_id = '" . $regulation_group_id . "'";
    }

    $countsql = "SELECT COUNT(DISTINCT regulation_id) FROM ml.v5_2019";
    $result = pg_query($conn, $countsql);
    if ($result) {
        $row = pg_fetch_row($result);
        $result_count = $row[0];
        $page_count = ceil($result_count / $pagesize);
    }

    $sql = "SELECT DISTINCT m.regulation_id, m.regulation_id_full, m.reformat_regulation_id, information_text, COUNT(m.*) as measure_count
    FROM ml.v5_2019 m LEFT OUTER JOIN base_regulations b ON m.regulation_id_full = b.base_regulation_id " . $clause . 
    "GROUP BY m.regulation_id, information_text, reformat_regulation_id, regulation_id_full ORDER BY 1 " . $limit_clause;
    $result = pg_query($conn, $sql);
	if (($result) && pg_num_rows($result) > 0) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Regulation ID</th>
        <th class="govuk-table__header" style="width:15%">EU format</th>
        <th class="govuk-table__header" style="width:70%">Information text</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $regulation_id          = $row['regulation_id'];
            $regulation_id_full     = $row['regulation_id_full'];
            $reformat_regulation_id = $row['reformat_regulation_id'];
            $information_text       = $row['information_text'];
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell"><a href="/regulation_view.php?regulation_id=<?=$regulation_id?>"><?=$regulation_id_full?></a></td>
        <td class="govuk-table__cell"><?=$reformat_regulation_id?></td>
        <td class="govuk-table__cell"><?=$row['information_text']?></td>
    </tr>
<?php            
		}
	} else {
        echo ("<p>No matching regulations</p>");
    }
?>
</table>
<?php
    $url = "/regulations.php?";
    if ($regulation_group_id != "") {
        $url .= "regulation_group_id=" . $regulation_group_id;
    }
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