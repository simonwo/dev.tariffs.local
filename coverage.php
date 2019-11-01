<?php
    $title = "Coverage";
    require ("includes/db.php");
	$error_handler = new error_handler;
    require ("includes/header.php");
    $commodity_range = get_querystring("commodity_range") . "";
    if ($commodity_range == "" ) {
        $commodity_range = "0";
    }
    $scope = get_querystring("scope") . "";
    if ($scope == "" ) {
        $scope = "mfn";
    }
    if (get_querystring("suppress_cn10") == "") {
        $suppress_cn10 = false;
        //h1 ("Don't suppress");
    } else {
        $suppress_cn10 = true;
        //h1 ("Suppress");
    }
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Coverage
            </li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Coverage</h1>
    </div>

<form action="coverage.html#table_intro">
<!-- Begin commodity range field //-->
<div class="govuk-form-group <?=$error_handler->get_error("commodity_range");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="commodity_range">Which commodity range do you want to check?</label></h1>
	</legend>
    <span class="govuk-hint">Select the commodity range to review</span>
	<?=$error_handler->display_error_message("commodity_range");?>
	<select class="govuk-select" id="commodity_range" name="commodity_range">
		<option value="">- Select commodity range - </option>
<?php
    for ($i = 0; $i < 10; $i++ ) {
        $item1 = str_pad($i, 4, '0', STR_PAD_RIGHT);
        $item2 = (intval($item1) + 999) . "";
        $item2 = str_pad($item2, 4, '0', STR_PAD_LEFT);

        if ($i == $commodity_range) {
            echo ("<option selected value='" . $i . "'>" . $item1 . " - " . $item2 . "</option>");
        } else {
            echo ("<option value='" . $i . "'>" . $item1 . " - " . $item2 . "</option>");
        }
    }
?>
    </select>
</div>
<!-- End commodity range field //-->

<!-- Begin measure type field //-->
<div class="govuk-form-group <?=$error_handler->get_error("scope");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="scope">What type of measure do you want to check?</label></h1>
	</legend>
    <span class="govuk-hint">Select the measure / geography type</span>
	<?=$error_handler->display_error_message("scope");?>
	<select class="govuk-select" id="scope" name="scope">
		<option value="">- Select scope / measure type - </option>
<?php
    if ($scope == "mfn") {
        echo ("<option selected value='mfn'>Most Favoured Nation duties (MFN)</option>");
    } else {
        echo ("<option value='mfn'>Most Favoured Nation duties (MFN)</option>");
    }

    $countries      = "1032,2005,2020,2027,CH,FO,LI,IS,NO,IL,PL,EC,CO,PE,1033,2200,CL,1034";
    $countries2     = "Transitional Protection Measure (1032),GSP - LDC Framework (2005),GSP - General Framework (2020),GSP - Enhanced Framework (2027),
    Switzerland,Faroe Islands,Liechtenstein,Iceland,Norway,Israel,Palestine,Ecuador,
    Colombia,Peru,Cariforum,Central America,Chile,East and Southern African States (ESA)";
    $arcountries    = explode(',', $countries);
    $arcountries2   = explode(',', $countries2);

    $country_count  = count($arcountries);

    
    for ($i = 0; $i < $country_count; $i++ ) {
        $country_code   = $arcountries[$i];
        $description    = $arcountries2[$i];
        if ($country_code == $scope) {
            echo ("<option selected value='" . $country_code . "'>" . $description . "</option>");
        } else {
            echo ("<option value='" . $country_code . "'>" . $description . "</option>");
        }
    }
?>
    </select>
</div>
<!-- End measure type field //-->   


<!-- Begin suppress CN10 field //-->
<div class="govuk-form-group <?=$error_handler->get_error("suppress_cn10");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="suppress_cn10">Do you want to suppress CN10s that have no assigned duties?</label></h1>
	</legend>
    <span class="govuk-hint">This is useful for checking against the MFN reference document.</span>
    <?=$error_handler->display_error_message("suppress_cn10");?>
    
    <div class="govuk-checkboxes">
      <div class="govuk-checkboxes__item">
        <input class="govuk-checkboxes__input" id="suppress_cn10" name="suppress_cn10" type="checkbox" value="yes">
        <label class="govuk-label govuk-checkboxes__label" for="suppress_cn10">
          Suppress CN 10 codes where there is no duty assigned
        </label>
      </div>
    </div>
</div>
<!-- End suppress CN10 field //-->
    
    <div class="govuk-form-group" style="padding:0px;margin:0px">
        <button type="submit" class="govuk-button">View coverage</button>
    </div>


</form>
<?php
// Get the duties
    $range_string = str_pad($commodity_range, 2, '0', STR_PAD_LEFT);
    if ($scope == "mfn") {
        $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id,
        mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code,
        mc.measurement_unit_qualifier_code
        from measures m, measure_components mc
        where m.measure_sid = mc.measure_sid
        and m.validity_start_date = '2019-11-01'
        and measure_type_id in ('103', '105')
        and left(m.goods_nomenclature_item_id, 1) = '" . $commodity_range . "'
        order by m.goods_nomenclature_item_id, mc.duty_expression_id";
    }
    elseif ($scope == "gsp") {

    } else {
        $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id,
        mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code,
        mc.measurement_unit_qualifier_code
        from measures m, measure_components mc
        where m.measure_sid = mc.measure_sid
        and m.validity_start_date = '2019-11-01'
        and measure_type_id in ('142', '145')
        and left(m.goods_nomenclature_item_id, 1) = '" . $commodity_range . "'
        and geographical_area_id = '" . $scope . "'
        order by m.goods_nomenclature_item_id, mc.duty_expression_id
        ";
    }
    //print ($sql);

    $result = pg_query($conn, $sql);
    $duties = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $duty = new duty();
            $duty->measure_sid                      = $row['measure_sid'];
            $duty->commodity_code                   = $row['goods_nomenclature_item_id'];
            $duty->measure_type_id                  = $row['measure_type_id'];
            $duty->duty_expression_id               = $row['duty_expression_id'];
            $duty->duty_amount                      = $row['duty_amount'];
            $duty->monetary_unit_code               = $row['monetary_unit_code'];
            $duty->measurement_unit_code            = $row['measurement_unit_code'];
            $duty->measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
            $duty->get_duty_string();
            array_push($duties, $duty);
        }
    }
    //print (count($duties));
// Form the duties into measure objects
    $measures = array();
    foreach ($duties as $duty) {
        $measure_sid = $duty->measure_sid;
        $matched = false;
        foreach ($measures as $measure) {
            $measure_sid2 = $measure->measure_sid;
            if ($measure_sid == $measure_sid2) {
                array_push($measure->duty_list, $duty);
                $matched = true;
                break;
            }
        }
        if ($matched == false) {
            $measure = new measure();
            $measure->measure_sid = $duty->measure_sid;
            $measure->commodity_code = $duty->commodity_code;
            $measure->measure_type_id = $duty->measure_type_id;
            array_push($measure->duty_list, $duty);
            array_push($measures, $measure);
        }
    }

    foreach ($measures as $measure) {
        $measure->combine_duties();
    }


// Next - SQL to get the commodity codes
    $sql = "select goods_nomenclature_item_id, producline_suffix, number_indents,
    description, leaf, significant_digits, validity_start_date, validity_end_date
    from ml.goods_nomenclature_export_new ('" . $commodity_range . "%', '" . $critical_date . "')
    order by goods_nomenclature_item_id, producline_suffix";

    //echo ($sql);

    $result = pg_query($conn, $sql);
    $commodities = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $commodity	= new goods_nomenclature;
            $commodity->goods_nomenclature_item_id  = $row['goods_nomenclature_item_id'];
            $commodity->productline_suffix          = $row['producline_suffix'];
            $commodity->number_indents              = $row['number_indents'];
            $commodity->description                 = $row['description'];
            $commodity->leaf                        = $row['leaf'];
            $commodity->significant_digits          = $row['significant_digits'];
            $commodity->validity_start_date         = $row['validity_start_date'];
            $commodity->validity_end_date           = $row['validity_end_date'];

            if ($commodity->significant_digits != 2) {
                $commodity->number_indents += 1;
            }
            array_push($commodities, $commodity);
        }
    }

// Finally, assign the measures to the commodities
    foreach ($measures as $measure) {
        foreach ($commodities as $commodity) {
            if ($measure->commodity_code == $commodity->goods_nomenclature_item_id) {
                if ($commodity->productline_suffix == "80") {
                    array_push($commodity->measure_list, $measure);
                    $commodity->measure_sid = $measure->measure_sid;
                    $commodity->measure_type_id = $measure->measure_type_id;
                    $commodity->get_measure_type_description();
                    $commodity->assigned = true;
                    break;
                }
            }
        }
    }
    foreach ($commodities as $commodity) {
        $commodity->combine_duties();
    }

// Now inherit down where appropriate
    $count = count($commodities);
    for ($i = 0; $i < $count; $i++) {
        $commodity = $commodities[$i];
        if ($commodity->assigned == true) {
            for ($j = ($i + 1); $j < $count; $j++) {
                $commodity2 = $commodities[$j];
                if ($commodity2->number_indents > $commodity->number_indents) {
                    if ($commodity2->assigned == false) {
                        if ($commodity2->productline_suffix == "80") {
                            $commodity2->combined_duty = $commodity->combined_duty;
                            $commodity2->measure_type_id = $commodity->measure_type_id; 
                            $commodity2->measure_type_desc = $commodity->measure_type_desc; 
                        }
                    } else {
                        if ($commodity2->measure_type_id != '105') {
                            break;
                        }
                    }
                } else {
                    break;
                }
            }
        }
    }

    if (count($commodities) > 0) {

?>
    <h3 id="table_intro">Notes on the table below</h3>
    <p style="text-align:justify">The table below shows the entire commodity code tree and show the prevalent duties applicable
        for the jurisdiction or duty type selected. Any rows that are highlighted in green show that a duty is explicity
        assigned to that node. Any row highlighted in pink illustrates that there is a commodity to which an MFN is
        not assigned, which must be corrected. Rows where the description text is greyed out are those rows where
        the commodity code is not declarable (either not a leaf or has a product line suffix that is not 80).
    </p>
    <p>You can also <a target="_blank" href="coverage_extract.html?scope=<?=$scope?>">extract the entire data set to a single CSV</a> (Warning - this will take up to 10 minutes to generate).</p>
    <table cellspacing="0" class="govuk-table" id="table">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:15%">Commodity code</th>
            <th class="govuk-table__header c" style="width:5%">Suffix</th>
            <th class="govuk-table__header" style="width:5%">Indent</th>
            <th class="govuk-table__header" style="width:35%">Description</th>
            <th class="govuk-table__header l" style="width:10%">Measure type</th>
            <th class="govuk-table__header r" style="width:10%">Duty</th>
            <th class="govuk-table__header c" style="width:10%">End-line?</th>
        </tr>
<?php
        foreach ($commodities as $commodity) {
            $is_cn10 = false;
            if (substr($commodity->goods_nomenclature_item_id, -2) != "00") {
                $is_cn10 = true;
            }
            //h1 ($commodity->goods_nomenclature_item_id . $is_cn10);
            $padding_string = "padding:" . (8 + ($commodity->number_indents * 16)) . "px;";
            if (($commodity->leaf == 0) or ($commodity->productline_suffix != "80")) {
                $padding_string .= "color:#999;";
            }
            $match_class = "";
            if ($commodity->assigned == true) {
                $match_class = " assigned";
            }
            elseif ($scope == "mfn") {
                if (($commodity->leaf == 1) && ($commodity->combined_duty == "")) {
                    $match_class = " match_error";
                }
            }
            if ($suppress_cn10 == true) {
                if (($is_cn10 == true) and ($commodity->assigned == false)) {
                    $match_class .= " hidden";
                }
            }
            $number_indents_real = $commodity->number_indents - 1;
            if ($number_indents_real == -1) {
                $number_indents_real = 0;
            }
?>
        <tr class="govuk-table__row<?=$match_class?>">
            <td class="govuk-table__cell">
                <a class="nodecorate" target="_blank" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=<?=$commodity->goods_nomenclature_item_id?>&productline_suffix=<?=$commodity->productline_suffix?>"><?=format_commodity_code($commodity->goods_nomenclature_item_id)?></a>
            </td>
            <td class="govuk-table__cell c"><?=$commodity->productline_suffix?></a></td>
            <td class="govuk-table__cell c"><?=$number_indents_real?></a></td>
            <td class="govuk-table__cell" style="<?=$padding_string?>"><?=$commodity->format_description()?>
<?php
    if ($commodity->validity_end_date != "") {
        echo (" (ends " . short_date($commodity->validity_end_date) . ")");
    }
?>
            </td>
            <td class="govuk-table__cell l"><?=$commodity->measure_type_id?><?=$commodity->measure_type_desc?></td>
            <td class="govuk-table__cell r">
<?php
    if ($commodity->assigned == true) {
        echo ("<a href='measure_view.html?measure_sid=" . $commodity->measure_sid . "'>" . $commodity->combined_duty . "</a>");
    } else {
        echo ($commodity->combined_duty);
    }
?>
            </td>
            <td class="govuk-table__cell c"><?=$commodity->leaf?></td>
        </tr>

<?php            
        }
?>
    </table>
<?php
    }
?>

</div>

<?php
    require ("includes/footer.php")
?>