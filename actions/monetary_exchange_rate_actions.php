<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");
    if ($phase == "measure_filter_geographical_area_view") {
        get_formvars_measure_filter_geographical_area_view();
    } else {
        get_formvars_phase1();
    }

    function get_formvars_phase1() {
        $geographical_area_text = get_querystring("geographical_area_text");

        $url  = "/geographical_areas.php";
        $url .= "?geographical_area_text=" . $geographical_area_text;

        header("Location: " . $url);

    }
    function get_formvars_measure_filter_geographical_area_view() {
        $geographical_area_id   = get_querystring("geographical_area_id");
        $measure_scope          = get_querystring("measure_scope");

        $url  = "/geographical_area_view.php";
        $url .= "?geographical_area_id=" . $geographical_area_id;
        $url .= "&measure_scope=" . $measure_scope;

        header("Location: " . $url);

    }
?>