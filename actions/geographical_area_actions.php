<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    get_formvars_phase1();

    function get_formvars_phase1() {
        $geographical_area_text = get_querystring("geographical_area_text");

        $url  = "/geographical_areas.php";
        $url .= "?geographical_area_text=" . $geographical_area_text;

        header("Location: " . $url);

    }
?>