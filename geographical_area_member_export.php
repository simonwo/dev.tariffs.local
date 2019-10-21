<?php
    //$write_to_screen = true; // false;
    $write_to_screen = false;
    require ("includes/db.php");
	$geographical_area_id = get_querystring("geographical_area_id");
    
    if ($geographical_area_id != "") {
        $filename = $geographical_area_id . ".csv";
    } else {
        die();
    }

    
    if ($write_to_screen == false) {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=members_" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $delimiter = "\n";
    } else {
        $delimiter = "<br />";
    }


    echo "Child ID,Child SID,Description,Start date,End date";
    echo ($delimiter);

    /* Get the members */
    $sql = "SELECT child_sid, child_id, child_description, validity_start_date, validity_end_date
    FROM ml.ml_geo_memberships WHERE parent_id = '" . $geographical_area_id . "'
    AND (validity_end_date::date > CURRENT_DATE OR validity_end_date IS NULL) ORDER BY 3 ";

    $result = pg_query($conn, $sql);
	if  ($result) {
		while ($row = pg_fetch_array($result)) {
            $child_id = $row['child_id'];
            $child_sid = $row['child_sid'];
            $child_description = $row['child_description'];
            $validity_start_date = $row['validity_start_date'];
            $validity_end_date = $row['validity_end_date'];

            echo ($child_id . ',');
            echo ($child_sid . ',');
            echo ('"' . $child_description . '",');
            echo ('"' . short_date($validity_start_date) . '",');
            echo ('"' . short_date($validity_end_date)  . '"');

            echo ($delimiter);
        }
    }
    die;

?>