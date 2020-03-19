<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	#pre($_REQUEST);
	#exit();
	$phase = get_formvar("phase");
	if ($phase == "perform_rollback") {
		get_formvars_perform_rollback();
	}

	function get_formvars_perform_rollback() {
        global $conn;
		$import_started = get_formvar("import_started");
		$import_file    = get_formvar("import_file");

        $sql = "select * from ml.clear_data($1, $2)";

        pg_prepare($conn, "perform_rollback", $sql);
        pg_execute($conn, "perform_rollback", array($import_started, $import_file));

        header('Location: ./load_history.html');
	}
?>