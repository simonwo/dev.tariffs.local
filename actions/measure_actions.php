<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    #pre($_REQUEST);
    #exit();
    $phase = get_formvar("phase");
    if ($phase == "1") {
        get_formvars_phase1();
    }
    elseif ($phase == "measure_search") {
        get_formvars_measure_search();
    }

    function get_formvars_phase1() {
        $error_list             = array();
        $base_regulation        = strtoupper(get_formvar("base_regulation"));
        $measure_start_day      = get_formvar("measure_start_day");
        $measure_start_month    = get_formvar("measure_start_month");
        $measure_start_year     = get_formvar("measure_start_year");
        $measure_end_day        = get_formvar("measure_end_day");
        $measure_end_month      = get_formvar("measure_end_month");
        $measure_end_year       = get_formvar("measure_end_year");
        $measure_type           = get_formvar("measure_type");
        $workbasket             = get_formvar("workbasket");
        $goods_nomenclatures    = get_formvar("goods_nomenclatures");
        $additional_codes       = get_formvar("additional_codes");
        $geographical_area_id   = get_formvar("geographical_area_id");

        setcookie("base_regulation", $base_regulation, time() + (86400 * 30), "/");



        # Check that base regulation is 8 characters long exactly
        if (strlen($base_regulation) != 8) {
            array_push($error_list, "base_regulation");
        }
        # Check that the workbasket is not blank
        if (strlen($workbasket) == "") {
            array_push($error_list, "workbasket");
        }

        # Check that the measure type is selected
        if ($measure_type == "0") {
            array_push($error_list, "measure_type");
        }

        # Nomenclature
        $goods_nomenclatures_exploded = string_to_filtered_list($goods_nomenclatures);
        foreach ($goods_nomenclatures_exploded as $g) {
            if (strlen($g) != 10) {
                array_push($error_list, "goods_nomenclatures");
                break;
            }
        }

        # Additional codes
        $additional_codes_exploded = string_to_filtered_list($additional_codes);
        foreach ($additional_codes_exploded as $ac) {
            if (strlen($ac) != 4) {
                array_push($error_list, "additional_codes");
                break;
            }
        }

        if (count($error_list) > 0) {
            $error_string = serialize($error_list);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            #echo ($error_string);
            #exit();
            header('Location: /measure_create.html?err=1');
        }

    }


    function get_formvars_measure_search() {
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
        if (strlen($goods_nomenclature_item_id) < 10) {
            $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
        }

        $url  = "/goods_nomenclature_item_view.html";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id . "#assigned";
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }


?>