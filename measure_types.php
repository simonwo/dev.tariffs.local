<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $measure_type_series_id = get_querystring("measure_type_series_id");
    $measure_type_scope = get_querystring("measure_type_scope");
    if ($measure_type_scope == "") {
        $measure_type_scope = "all";
    }
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            Measure types
        </li>
    </ol>
</div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Measure types</h1>
    </div>
    <form action="/actions/measure_type_actions.php" method="get" class="inline_form">
        <h3>Filter results</h3>
        <input type="hidden" name="phase" id="phase" value="filter_measure_types" />
        <div class="govuk-radios govuk-radios--inline">
            <div class="govuk-radios__item break">
                <input <?=get_checked($measure_type_scope, "all")?> type="radio" class="govuk-radios__input" name="measure_type_scope" id="measure_type_scope_all" value="all" />
                <label class="govuk-label govuk-radios__label" for="measure_type_scope_all">Show all measure types</label>
            </div>
        </div><br/>
        <div class="govuk-radios govuk-radios--inline">
            <div class="govuk-radios__item break">
                <input <?=get_checked($measure_type_scope, "current")?> type="radio" class="govuk-radios__input" name="measure_type_scope" id="measure_type_scope_current" value="current" />
                <label class="govuk-label govuk-radios__label" for="measure_type_scope_current">Only show current measure types</label>
            </div>
        </div>
        <div class="clearer"><!--&nbsp;//--></div>
        <div class="column-one-third">
            <div class="govuk-form-group" style="padding:0px;margin:0px">
                <button type="submit" class="govuk-button" style="margin-top:36px">Search</button>
            </div>
        </div>
        <div class="clearer"><!--&nbsp;//--></div>
    </form>

<?php
    $clause = "";
    if ($measure_type_series_id != "") {
        $clause .= "AND mt.measure_type_series_id = '" . $measure_type_series_id . "'";
    }
	$sql = "SELECT mt.measure_type_id, mt.validity_start_date, mt.validity_end_date, mtd.description as measure_type_description,
    mt.measure_type_series_id, mtsd.description as measure_type_series_description
    FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
    WHERE mt.measure_type_series_id = mtsd.measure_type_series_id
    AND mt.measure_type_id = mtd.measure_type_id " . $clause;
    
    if ($measure_type_scope == "current") {
        $sql .= " AND mt.validity_end_date Is Null";
    }

    $sql .= " ORDER BY 1";
	$result = pg_query($conn, $sql);
	if  ($result) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Measure type ID</th>
        <th class="govuk-table__header c" style="width:15%">Series ID</th>
        <th class="govuk-table__header" style="width:36%">Description</th>
        <th class="govuk-table__header c" style="width:12%">Start date</th>
        <th class="govuk-table__header c" style="width:12%">End date</th>
        <th class="govuk-table__header c" style="width:10%">Action</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $measure_type_id                    = $row['measure_type_id'];
            $measure_type_series_id             = $row['measure_type_series_id'];
            $measure_type_description           = $row['measure_type_description'];
            $validity_start_date                = string_to_date($row['validity_start_date']);
            $validity_end_date                  = string_to_date($row['validity_end_date']);
            $measure_type_series_description    = $row['measure_type_series_description'];
            $rowclass                           = rowclass($validity_start_date, $validity_end_date);
?>
    <tr class="govuk-table__row <?=$rowclass?>">
        <td class="govuk-table__cell b"><a href="/measure_type_view.php?measure_type_id=<?=$measure_type_id?>"><?=$measure_type_id?></a></td>
        <td class="govuk-table__cell c"><?=$measure_type_series_id?><br /><span class="explanatory"><?=$measure_type_series_description?></a></td>
        <td class="govuk-table__cell"><?=$measure_type_description?></td>
        <td class="govuk-table__cell c"><?=$validity_start_date?></td>
        <td class="govuk-table__cell c"><?=$validity_end_date?></td>
        <td class="govuk-table__cell c">&nbsp;</td>
    </tr>
<?php            
		}
	}
?>
</table>
</div>
</div>
<?php
    require ("includes/footer.php")
?>