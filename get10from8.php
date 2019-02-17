<?php
    require ("includes/db.php");
    require ("classes/goods_nomenclature.php");
    $csv = array_map('str_getcsv', file('csv/commodities.csv'));
    foreach ($csv as $commodity) {
        $c = $commodity[0] . "00";
        echo ("<b>$c|Key</b><br />");

        $obj      = new goods_nomenclature;
        $obj->set_properties($c, "80", "", 0);
        #array_push($temp, $geographical_area);
        #exit();
    }

?>