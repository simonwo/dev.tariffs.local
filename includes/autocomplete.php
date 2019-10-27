<?php 
// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = "root"; 
$dbName     = "codexworld"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 
 
// Get search term 
$searchTerm = $_GET['term']; 
 
// Fetch matched data from the database 
$query = $db->query("SELECT base_regulation_id FROM base_regulations ORDER BY base_regulation_id ASC"); 
$result = pg_query($conn, $sql);
 
// Generate array with skills data 
$my_data = array();
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $data['id'] = $row['id']; 
        $data['value'] = $row['skill']; 
        array_push($my_data, $data); 
    } 
} 
 
// Return results as json encoded array 
echo json_encode($my_data); 




$sql = "SELECT m.additional_code_type_id, m.additional_code_id, m.measure_type_id,
			mc.measure_sid, duty_expression_id, duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
			FROM /* measures */ ml.measures_real_end_dates m, measure_components mc WHERE m.measure_sid = mc.measure_sid ";
			if ($goods_nomenclature_item_id != "") {
				$sql .= " AND m.goods_nomenclature_item_id = '" . $goods_nomenclature_item_id . "' ";
			}
			if ($geographical_area_id != "") {
				$geographical_area_string = explode_string($geographical_area_id);
				$sql .= " AND m.geographical_area_id in (" . $geographical_area_string . ") ";
			}


			$sql .= "ORDER BY m.measure_sid, mc.duty_expression_id";

			$result = pg_query($conn, $sql);
			$duty_list = array();
			if  (($result) && (pg_num_rows($result) > 0)) {
				while ($row = pg_fetch_array($result)) {

?>                    