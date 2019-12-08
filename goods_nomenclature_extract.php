<?php
    ini_set('max_execution_time', 1800); // 30 minutes
    $write_to_screen = true; // false;
    //$write_to_screen = false;
    require ("includes/db.php");
    $chapter_id = get_querystring("chapter_id");
    $day = get_querystring("day");
    $month = get_querystring("month");
    $year = get_querystring("year");
    $valid_date = checkdate($month, $day, $year);
    if ($valid_date != 1) {
        $date_string = date('Y-m-d');
    } else {
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $date_string = $year . "-" . $month . "-" . $day;
    }

    $depth = get_querystring("depth");
    if ($depth == "") {
        $depth = 10;
    }
    $depth = intval($depth);
    switch ($depth) {
        case 10:
            $suffix = "cn10";
            break;
        case 8:
            $suffix = "cn8";
            break;
        case 6:
            $suffix = "hs6";
            break;
        case 4:
            $suffix = "hs4";
            break;
        default:
            $suffix = "";
            die();
    }
    if ($write_to_screen == false) {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=commodities_" . $suffix . "_" . $chapter_id . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $delimiter = "\n";
        $start_string = "";
        $end_string = "";
    } else {
        $delimiter = "<br />";
        $start_string = "<pre>";
        $end_string = "</pre>";
    }

    echo ($start_string);
// Get the commodity codes
    $sql = "select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, validity_start_date,
    validity_end_date, description, number_indents, leaf
    from ml.goods_nomenclature_export_new('" . $chapter_id . "%', '" . $date_string . "') order by 2, 3;";
    $result = pg_query($conn, $sql);
	if ($result) {
        echo ("goods_nomenclature_sid,goods_nomenclature_item_id,productline_suffix,validity_start_date,validity_end_date,number_indents,leaf,description");
        echo ($delimiter);
        while ($row = pg_fetch_array($result)) {
            $gnd = $row["goods_nomenclature_item_id"];
            if (($depth == 10) || ($depth == 8 && substr($gnd, -2) == "00") || ($depth == 6 && substr($gnd, -4) == "0000") || ($depth == 4 && substr($gnd, -6) == "000000")) {
                $description = $row["description"];
                $description = str_replace("<br>", " ", $description);
                $description = str_replace("\"", "'", $description);
                $description = trim(preg_replace('/\s\s+/', ' ', $description));
                echo ("\"" . $row["goods_nomenclature_sid"] . "\",");
                echo ("\"" . $gnd . "\",");
                echo ("\"" . $row["producline_suffix"] . "\",");
                echo ("\"" . short_date_rev($row["validity_start_date"]) . "\",");
                echo ("\"" . $row["validity_end_date"] . "\",");
                echo ($row["number_indents"] . ",");
                echo ($row["leaf"] . ",");
                echo ("\"" . $description . "\"");
                echo ($delimiter);
            }
        }
    }
    echo ($end_string);
?>
