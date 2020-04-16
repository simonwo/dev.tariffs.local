<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$gn = new goods_nomenclature;
$gn->goods_nomenclature_item_id = get_formvar("goods_nomenclature_item_id");
$gn->goods_nomenclature_sid = get_formvar("goods_nomenclature_sid");
$gn->productline_suffix = get_formvar("productline_suffix");
//prend($_REQUEST);

$action = get_formvar("action");
switch ($action) {
    case "start":
        $commodity_migrate_activity = new commodity_migrate_activity();
        $commodity_migrate_activity->create();
        $url = "commodity_migrate_activity_name.html?" . $gn->query_string();
        header("Location: " . $url);
        break;

    case "activity_name":
        $commodity_migrate_activity = new commodity_migrate_activity();
        $commodity_migrate_activity->create();
        $url = "commodity_migrate_select_codes.html?" . $gn->query_string();
        header("Location: " . $url);
        break;

    case "select_codes":
        $commodity_migrate_activity = new commodity_migrate_activity();
        $commodity_migrate_activity->select_codes();
        //$url = "commodity_migrate_select_codes.html?" . $gn->query_string();
        //header("Location: " . $url);
        break;
}
