<?php
class commodity_migrate_activity
{
    public $activity_name = null;
    public $migration_date = null;
    public $activity_date = null;
    public $commodities_to_migrate = null;
    public $first_commodity_code = null;
    public $gap = null;
    public $indent_increment = null;

    public function create() {
        global $conn, $application;
        $operation_date = $application->get_operation_date();

        $this->commodities_to_migrate = get_formvar("commodities_to_migrate");

        # Create the commodity_migrate_activity record
        $sql = "INSERT INTO commodity_migrate_activity (
            date_created, commodities_to_migrate, workbasket_id
            )
            VALUES ($1, $2, $3)
            RETURNING commodity_migrate_activity_sid;";

        $stmt = "create_commodity_migrate_activity_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $operation_date, $this->commodities_to_migrate, $application->session->workbasket->workbasket_id
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $commodity_migrate_activity_sid = $row[0];
            $_SESSION["commodity_migrate_activity_sid"] = $commodity_migrate_activity_sid;
        }
        //die();
    }

    public function select_codes() {
        //pre ($_REQUEST);
        $codes = array();
        $commodities = array();
        foreach ($_REQUEST as $item => $value) {
            if (strpos($item, "commodity_new") !== false) {
                array_push($codes, str_replace("commodity_new_", "", $item));
            }
        }
        //sort($codes);
        //pre ($codes);
        foreach ($codes as $code) {
            $gn = new goods_nomenclature();
            $gn->get_details_for_migration();
        }
        die();
    }
}
