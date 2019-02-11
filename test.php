<?php
	$conn = pg_connect("host=127.0.0.1 port=5432 dbname=tariff_eu user=postgres password=zanzibar");
	$sql = "SELECT * FROM additional_code_type_descriptions";
	$result = pg_query($conn, $sql);
	if  (!$result) {
		echo "query did not execute";
	}
	if (pg_num_rows($result) == 0) {
		echo "0 records";
	}
	else {
		while ($row = pg_fetch_array($result)) {
			//do stuff with $row
			echo ("<p>" . $row["language_id"] . "</p>");
		}
	}
?>