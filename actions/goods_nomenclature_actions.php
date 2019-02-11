<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    get_formvars_phase1();

    function get_formvars_phase1() {
        $measure_type_id            = get_querystring("measure_type_id");
        $goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
        $productline_suffix         = get_querystring("productline_suffix");
        $geographical_area_id       = get_querystring("geographical_area_id");

        $url  = "/goods_nomenclature_item_view.php";
        $url .= "?goods_nomenclature_item_id=" . $goods_nomenclature_item_id;
        $url .= "&productline_suffix=" . $productline_suffix;
        $url .= "&measure_type_id=" . $measure_type_id;
        $url .= "&geographical_area_id=" . $geographical_area_id;
        #echo ($url);
        #phpInfo();
        #exit();
        header("Location: " . $url);

    }
?>