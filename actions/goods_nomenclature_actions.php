<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    #echo ($phase);
    #exit();
    if ($phase == "goods_nomenclature_item_view") {
        get_formvars_goods_nomenclature_item_view();
    } elseif ($phase == "goods_nomenclature_item_view_filter") {
        get_formvars_goods_nomenclature_item_view_filter();
    }

    function get_formvars_goods_nomenclature_item_view() {
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
        if (strlen($goods_nomenclature_item_id) < 10) {
            $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
        }

        $url  = "/goods_nomenclature_item_view.php";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }

    function get_formvars_goods_nomenclature_item_view_filter() {
        $geographical_area_id = get_querystring("geographical_area_id");
        $measure_type_id = get_querystring("measure_type_id");
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
        if (strlen($goods_nomenclature_item_id) < 10) {
            $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
        }

        $url  = "/goods_nomenclature_item_view.php";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        $url .= "&measure_type_id=" . $measure_type_id;
        $url .= "&geographical_area_id=" . $geographical_area_id;
        $url .= "#assigned";
        #echo ($url);
        #exit();
        header("Location: " . $url);

    }
?>