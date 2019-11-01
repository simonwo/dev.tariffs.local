<?php
    $title = "Regulations";
    require ("includes/db.php");
    require ("includes/header.php");
	$application = new application;
	$application->get_regulation_groups();

    $regulation_group_id    = get_querystring("regulation_group_id");
    $regulation_text        = get_querystring("regulation_text");
    $regulation_scope       = get_querystring("regulation_scope");
    if ($regulation_scope == "") {
        $regulation_scope = "all";
    }
?>

<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
        </li>
        <li class="govuk-breadcrumbs__list-item">
            <a class="govuk-breadcrumbs__link" href="/regulations.html">Regulations</a>
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
	<h1 class="govuk-heading-xl">Regulations</h1>
</div>

<form action="/actions/regulation_actions.html" method="get" class="inline_form">
    <h3>Filter results</h3>
    <input type="hidden" name="phase" id="phase" value="filter_regulations" />
    <div class="column-one-third" style="width:320px">
        <div class="govuk-form-group">
            <fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
                <span id="base_regulation_hint" class="govuk-hint">Search regulations - enter free text</span>
                <div class="govuk-date-input">
                    <div class="govuk-date-input__item">
                        <div class="govuk-form-group" style="padding:0px;margin:0px">
                            <input value="<?=$regulation_text?>" class="govuk-input govuk-date-input__input govuk-input--width-16" id="regulation_text" maxlength="100" style="width:300px" name="regulation_text" type="text">
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>



    <details class="govuk-details" data-module="govuk-details" style="clear:both">
        <summary class="govuk-details__summary">
            <span class="govuk-details__summary-text medium">Advanced filters</span>
        </summary>
        <div class="govuk-details__text">

        <div class="govuk-form-group">
            <fieldset class="govuk-fieldset" aria-describedby="select_scope_hint" role="group">
                <span id="select_scope_hint" class="govuk-hint">Select scope</span>
                <div class="govuk-date-input">
                    <div class="govuk-date-input__item">
                        <div class="govuk-form-group" style="padding:0px;margin:0px">
                            <div class="govuk-radios govuk-radios--inline">
                                <div class="govuk-radios__item break" style="margin-bottom:1em">
                                    <input <?=get_checked($regulation_scope, "all")?> type="radio" class="govuk-radios__input" name="regulation_scope" id="regulation_scope_all" value="all" />
                                    <label class="govuk-label govuk-radios__label" for="regulation_scope_all">Show all regulations</label>
                                </div>
                            </div><br/>
                            <div class="govuk-radios govuk-radios--inline">
                                <div class="govuk-radios__item break">
                                    <input <?=get_checked($regulation_scope, "uk")?> type="radio" class="govuk-radios__input" name="regulation_scope" id="regulation_scope_uk" value="uk" />
                                    <label class="govuk-label govuk-radios__label" for="regulation_scope_uk">Show UK regulations only</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="govuk-form-group">
			<span id="validity_start_hint" class="govuk-hint">Please select the regulation group.</span>
			<select class="govuk-select" id="regulation_group_id" name="regulation_group_id">
				<option value="">- Select regulation group - </option>
<?php
	foreach ($application->regulation_groups as $obj) {
		if ($obj->regulation_group_id == $regulation_group_id) {
			$selected = " selected";
		} else {
			$selected = "";
		}
        echo ("<option " . $selected . " value='" . $obj->regulation_group_id . "'>" . $obj->regulation_group_id . " (" . $obj->description . ")</option>");
	}
?>
			</select>
		</div>
    </div>
    </details>    
    <div class="clearer"><!--&nbsp;//--></div>
    <div class="govuk-form-group" style="padding:0px;margin:0px">
        <button type="submit" class="govuk-button" style="margin-top:-12px">Search</button>
    </div>
</form>

<?php
    $clause1 = "";
    $clause2 = "";
    $offset         = intval($pagesize) * ($page - 1);
    $limit_clause   = "LIMIT " . $pagesize . " OFFSET " . $offset;

    if ($regulation_group_id != "") {
        $clause1 .= "AND br.regulation_group_id = '" . $regulation_group_id . "'";
        $clause2 .= "AND br.regulation_group_id = '" . $regulation_group_id . "'";
    }
    if ($regulation_scope == "uk") {
        $clause1 .= " AND ((br.officialjournal_number = '1' AND br.officialjournal_page = '1') OR (br.officialjournal_number IS NULL AND br.officialjournal_page IS NULL)) ";
        $clause2 .= " AND ((mr.officialjournal_number = '1' AND mr.officialjournal_page = '1') OR (mr.officialjournal_number IS NULL AND mr.officialjournal_page IS NULL)) ";
    }
    if ($regulation_text != "") {
        $t = strtoupper($regulation_text);
        $clause1 .= " AND (UPPER(br.base_regulation_id) LIKE '%" . $t . "%' OR UPPER(br.information_text) LIKE '%" . $t . "%') ";
        $clause2 .= " AND (UPPER(mr.modification_regulation_id) LIKE '%" . $t . "%' OR UPPER(mr.information_text) LIKE '%" . $t . "%') ";
    }

    $countsql = "SELECT base_regulation_id as regulation_id, information_text, regulation_group_id, validity_start_date, 'Base' as type, officialjournal_number
    FROM base_regulations br WHERE validity_start_date IS NULL " . $clause1 . " UNION
    SELECT modification_regulation_id as regulation_id, mr.information_text,
    br.regulation_group_id as regulation_group_id, mr.validity_start_date, 'Modification' as type, mr.officialjournal_number
    FROM modification_regulations mr, base_regulations br
    WHERE mr.base_regulation_id = br.base_regulation_id
    AND mr.validity_start_date IS NULL " . $clause2 . "
    ORDER BY regulation_id";

    $result = pg_query($conn, $countsql);
    if ($result) {
        $result_count = pg_num_rows($result);
        $page_count = ceil($result_count / $pagesize);
        #p ($result_count);
        #p ($result_count);
    }

    $sql = "SELECT base_regulation_id as regulation_id, information_text, regulation_group_id, validity_start_date,
    validity_end_date, 'Base' as type, officialjournal_number
    FROM base_regulations br WHERE 1 > 0 " . $clause1 . " UNION
    SELECT modification_regulation_id as regulation_id, mr.information_text,
    br.regulation_group_id as regulation_group_id, mr.validity_start_date, mr.validity_end_date,
    'Modification' as type, mr.officialjournal_number
    FROM modification_regulations mr, base_regulations br
    WHERE mr.base_regulation_id = br.base_regulation_id
    AND 1 > 0 " . $clause2 . "
    ORDER BY regulation_id " . $limit_clause;
    #p ($sql);
    $result = pg_query($conn, $sql);
	if (($result) && pg_num_rows($result) > 0) {
?>

<table class="govuk-table" cellspacing="0">
    <tr class="govuk-table__row">
        <th class="govuk-table__header" style="width:15%">Regulation ID</th>
        <th class="govuk-table__header" style="width:15%">Regulation group</th>
        <th class="govuk-table__header" style="width:10%">Type</th>
        <th class="govuk-table__header" style="width:35%">Information text</th>
        <th class="govuk-table__header" style="width:15%">Dates</th>
        <th class="govuk-table__header" style="width:10%">Actions</th>
    </tr>
<?php    
		while ($row = pg_fetch_array($result)) {
            $regulation_id          = $row['regulation_id'];
            $regulation_group_id2   = $row['regulation_group_id'];
            $type                   = $row['type'];
            $information_text       = $row['information_text'];
            $array = explode("|", $information_text);
            if (count($array) == 3) {
                $information_text  = "<ul class='understated'><li>" . $array[0] . "</li>";
                $information_text .= "<li><a href='" . $array[1]  . "' target='_blank'>" . $array[1] . "</a></li>";
                $information_text .= "<li>" . $array[2] . "</li></ul>";
            }
            $validity_start_date    = short_date($row['validity_start_date']);
            $validity_end_date      = short_date($row['validity_end_date']);
?>
    <tr class="govuk-table__row">
        <td class="govuk-table__cell"><a href="/regulation_view.html?base_regulation_id=<?=$regulation_id?>"><?=$regulation_id?></a></td>
        <td class="govuk-table__cell"><?=$regulation_group_id2?></td>
        <td class="govuk-table__cell"><?=$type?></td>
        <td class="govuk-table__cell"><?=$information_text?></td>
        <td class="govuk-table__cell"><?=$validity_start_date?> to <?=$validity_end_date?></td>
        <td class="govuk-table__cell">
            <form action="regulation_create_edit.html" method="get">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="base_regulation_id" value="<?=$regulation_id?>" />
                <button type="submit" class="govuk-button btn_nomargin">Edit</button>
            </form>
            <form action="measure_export.php" method="get">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="base_regulation_id" value="<?=$regulation_id?>" />
                <button type="submit" class="govuk-button btn_nomargin">Export measures</button>
            </form>
        </td>
    </tr>
<?php            
		}
	} else {
        echo ("<p>No matching regulations</p>");
    }
?>
</table>
<?php
    $url = "/regulations.html?";
    $clause_added = False;
    if ($regulation_group_id != "") {
        $url .= "regulation_group_id=" . $regulation_group_id;
        $clause_added = True;
    }
    if ($regulation_text != "") {
        if ($clause_added == True) {
            $url .= "&regulation_text=" . $regulation_text;
        } else {
            $url .= "regulation_text=" . $regulation_text;
        }
        $clause_added = True;
    }
    if ($regulation_scope != "") {
        if ($clause_added == True) {
            $url .= "&regulation_scope=" . $regulation_scope;
        } else {
            $url .= "regulation_scope=" . $regulation_scope;
        }
        $clause_added = True;
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