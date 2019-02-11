<?php
class application
{
	// Class properties and methods go here
	public $measure_types = array ();

	public function get_measure_types() {
		global $conn;
        $sql = "SELECT mt.measure_type_id, validity_start_date, validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code, order_number_capture_code, measure_explosion_level,
        measure_type_series_id, mtd.description
        FROM measure_types mt, measure_type_descriptions mtd
        WHERE mt.measure_type_id = mtd.measure_type_id
        AND (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
        ORDER BY mt.measure_type_id";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measure_type_id      				= $row['measure_type_id'];
                $validity_start_date     			= string_to_date($row['validity_start_date']);
                $validity_end_date     				= string_to_date($row['validity_end_date']);
                $trade_movement_code      			= $row['trade_movement_code'];
                $priority_code      				= $row['priority_code'];
                $measure_component_applicable_code  = $row['measure_component_applicable_code'];
                $origin_dest_code                   = $row['origin_dest_code'];
                $order_number_capture_code      	= $row['order_number_capture_code'];
                $measure_explosion_level      		= $row['measure_explosion_level'];
                $measure_type_series_id      		= $row['measure_type_series_id'];
                $description      					= $row['description'];
                $measure_type = new measure_type;

                $measure_type->set_properties($measure_type_id, $validity_start_date, $validity_end_date, $trade_movement_code,
                $priority_code, $measure_component_applicable_code, $origin_dest_code, $order_number_capture_code, $measure_explosion_level,
                $measure_type_series_id, $description);
                array_push($temp, $measure_type);
            }
            $this->measure_types = $temp;
        }
    }
} 