<?php
    require (dirname(__FILE__) . "../../includes/db.php");
    $phase = get_formvar("phase");

    if ($phase == "extract_data") {
        $extract = new extract;
        $extract->set_parameters();
        $extract->create_subclasses();
        $extract->extract_data();
    }
?>