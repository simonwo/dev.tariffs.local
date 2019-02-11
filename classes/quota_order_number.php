<?php
class quota_order_number
{
	// Class properties and methods go here
	public $quota_order_number_id           = "";
	public $validity_start_date             = "";
	public $validity_end_date               = "";
	public $origins = array ();
	
	#$quota_order_number_origin    = new quota_order_number_origin;

	public function set_properties($quota_order_number_id, $validity_start_date, $validity_end_date) {
		$this->quota_order_number_id    = $quota_order_number_id;
		$this->validity_start_date		= $validity_start_date;
		$this->validity_end_date		= $validity_end_date;
	}

	public function get_origins() {
		global $conn;
		# Get all the quota order number exclusions
		$sql = "SELECT qonoe.quota_order_number_origin_sid, excluded_geographical_area_sid, ga.description
		FROM quota_order_number_origin_exclusions qonoe, ml.ml_geographical_areas ga, quota_order_number_origins qono, quota_order_numbers qon
		WHERE qonoe.excluded_geographical_area_sid = ga.geographical_area_sid
		AND qono.quota_order_number_origin_sid = qonoe.quota_order_number_origin_sid
		AND qon.quota_order_number_sid = qono.quota_order_number_sid
		AND qon.quota_order_number_id = '" . $this->quota_order_number_id . "'";
		#p ($sql);
		$result = pg_query($conn, $sql);
		$quota_order_number_origin_exclusions = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$quota_order_number_origin_sid      = $row['quota_order_number_origin_sid'];
				$excluded_geographical_area_sid     = $row['excluded_geographical_area_sid'];
				$description                        = $row['description'];
				$qonoe = new quota_order_number_origin_exclusion;
				$qonoe->set_properties($quota_order_number_origin_sid, $excluded_geographical_area_sid, $description);
				array_push($quota_order_number_origin_exclusions, $qonoe);
			}
		}

		# Get the complete list of quota order number origins
		$sql = "SELECT qono.quota_order_number_origin_sid, qono.quota_order_number_sid, qono.geographical_area_id, ga.description, qon.quota_order_number_id
		FROM quota_order_number_origins qono, ml.ml_geographical_areas ga, quota_order_numbers qon
		WHERE ga.geographical_area_id = qono.geographical_area_id
		AND qon.quota_order_number_sid = qono.quota_order_number_sid
		AND (qono.validity_end_date IS NULL OR qono.validity_end_date > CURRENT_DATE)
		AND quota_order_number_id = '" . $this->quota_order_number_id . "'
		ORDER BY qono.quota_order_number_sid, ga.description";
		#p ($sql);

		$result = pg_query($conn, $sql);
		$quota_order_number_origins = array();
		if ($result) {
			while ($row = pg_fetch_array($result)) {
				$geographical_area_id           = $row['geographical_area_id'];
				$quota_order_number_origin_sid  = $row['quota_order_number_origin_sid'];
				$quota_order_number_sid         = $row['quota_order_number_sid'];
				$quota_order_number_id          = $row['quota_order_number_id'];
				$description                    = $row['description'];
				$qono = new quota_order_number_origin;
				$qono->set_properties($quota_order_number_origin_sid, $geographical_area_id, $quota_order_number_id, $quota_order_number_sid, $description);
				$qonoe_count = count($quota_order_number_origin_exclusions);
				for($i = 0; $i < $qonoe_count; $i++) {
					$t = $quota_order_number_origin_exclusions[$i];
					if ($t->quota_order_number_origin_sid == $quota_order_number_origin_sid) {
						array_push($qono->exclusions, $t);
						#p ("Adding exclusion");
					}
				}
				array_push($quota_order_number_origins, $qono);
			}
		}
		$this->origins = $quota_order_number_origins;
	}
}    