
    public function get_quotas()
    {
        $this->page_size = 100;
        global $conn;
        $sql = "select m.measure_sid, m.measure_generating_regulation_id, m.validity_start_date, m.validity_end_date,
        m.goods_nomenclature_item_id, m.additional_code, m.additional_code_sid,
        m.geographical_area_id, 'tbc' as exclusions, m.measure_type_id, m.measure_generating_regulation_id, m.ordernumber, m.status,
        case
            when m.validity_end_date is null then 'Terminated'
            else 'Active'
        end as active_state, count(*) OVER() AS full_count
        from ml.measures_real_end_dates m
        where 1 > 0 ";

        $clause = "";

        // Get measure SID field
        $measure_sid = standardise_form_string(get_formvar("measure_sid"));
        $measures = array();
        $measure_sid_clause = "";
        if ($measure_sid != "") {
            $measures = explode(",", $measure_sid);
            $count = count($measures);
            $index = 0;
            $measure_sid_clause .= "and m.measure_sid in (";
            foreach ($measures as $measure) {
                $measure_sid_clause .= $measure;
                $index += 1;
                if ($index < $count) {
                    $measure_sid_clause .= ", ";
                }
            }
            $measure_sid_clause .= ")";
        }
        $clause .= $measure_sid_clause;

        // Get commodity code clause
        $goods_nomenclature_item_id_operator = get_formvar("goods_nomenclature_item_id_operator");
        $goods_nomenclature_item_id = get_formvar("goods_nomenclature_item_id");
        if (strlen($goods_nomenclature_item_id) > 2) {
            if ($goods_nomenclature_item_id_operator == "starts_with") {
                $clause .= " and m.goods_nomenclature_item_id like '" . $goods_nomenclature_item_id . "%' ";
            } elseif ($goods_nomenclature_item_id_operator == "is_one_of") {
                $goods_nomenclature_item_id = standardise_form_string($goods_nomenclature_item_id);
                $goods_nomenclature_item_id_clause = "";
                $commodities = explode(",", $goods_nomenclature_item_id);
                $count = count($commodities);
                $index = 0;
                $goods_nomenclature_item_id_clause .= "and m.goods_nomenclature_item_id in (";
                foreach ($commodities as $commodity) {
                    $goods_nomenclature_item_id_clause .= "'" . $commodity . "'";
                    $index += 1;
                    if ($index < $count) {
                        $goods_nomenclature_item_id_clause .= ", ";
                    }
                }
                $goods_nomenclature_item_id_clause .= ")";
                $clause .= $goods_nomenclature_item_id_clause;
            }
        }

        // Get additional code clause
        $additional_code = get_formvar("additional_code");
        if (strlen($additional_code) > 2) {
            $additional_code = standardise_form_string($additional_code);
            $additional_code_clause = "";
            $additional_codes = explode(",", $additional_code);
            $count = count($additional_codes);
            $index = 0;
            $additional_code_clause .= "and m.additional_code in (";
            foreach ($additional_codes as $additional_code) {
                $additional_code_clause .= "'" . $additional_code . "'";
                $index += 1;
                if ($index < $count) {
                    $additional_code_clause .= ", ";
                }
            }
            $additional_code_clause .= ")";
            $clause .= $additional_code_clause;
        }

        // Get regulation clause
        $measure_generating_regulation_id_operator = get_formvar("measure_generating_regulation_id_operator");
        $measure_generating_regulation_id = get_formvar("measure_generating_regulation_id");
        if (strlen($measure_generating_regulation_id) > 2) {
            if ($measure_generating_regulation_id_operator == "starts_with") {
                $len = strlen($measure_generating_regulation_id);
                $measure_generating_regulation_id = get_before_hyphen($measure_generating_regulation_id);
                $clause .= " and left(measure_generating_regulation_id, " . $len . ") = '" . $measure_generating_regulation_id . "' ";
            } elseif ($measure_generating_regulation_id_operator == "is_one_of") {
                $measure_generating_regulation_id = standardise_form_string($measure_generating_regulation_id);
                $measure_generating_regulation_id_clause = "";
                $regulations = explode(",", $measure_generating_regulation_id);
                $count = count($regulations);
                $index = 0;
                $measure_generating_regulation_id_clause .= "and measure_generating_regulation_id in (";
                foreach ($regulations as $regulation) {
                    $measure_generating_regulation_id_clause .= "'" . $regulation . "'";
                    $index += 1;
                    if ($index < $count) {
                        $measure_generating_regulation_id_clause .= ", ";
                    }
                }
                $measure_generating_regulation_id_clause .= ")";
                $clause .= $measure_generating_regulation_id_clause;
            }
        }

        // Get measure type clause
        $measure_type_id = get_before_hyphen(get_formvar("measure_type_id"));
        if (strlen($measure_type_id) == 3) {
            $measure_types = explode(",", $measure_type_id);
            $count = count($measure_types);
            $index = 0;
            $measure_type_id_clause = "and measure_type_id in (";
            foreach ($measure_types as $measure_type) {
                $measure_type_id_clause .= "'" . $measure_type . "'";
                $index += 1;
                if ($index < $count) {
                    $measure_type_id_clause .= ", ";
                }
            }
            $measure_type_id_clause .= ")";
            $clause .= $measure_type_id_clause;
        }

        // Get geography field
        $geographical_area_id = strtoupper(standardise_form_string(get_formvar("geographical_area_id")));
        $geographies = array();
        $geographies_clause = "";
        if ($geographical_area_id != "") {
            $geographies = explode(",", $geographical_area_id);
            $count = count($geographies);
            $index = 0;
            $geographies_clause .= "and geographical_area_id in (";
            foreach ($geographies as $geography) {
                $geographies_clause .= "'" . $geography . "'";
                $index += 1;
                if ($index < $count) {
                    $geographies_clause .= ", ";
                }
            }
            $geographies_clause .= ")";
        }
        $clause .= $geographies_clause;


         // Get order number clause
         $ordernumber = get_formvar("ordernumber");
         $ordernumber = str_replace(" ", ",", $ordernumber);
         if (strlen($ordernumber) >= 6) {
             $ordernumbers = explode(",", $ordernumber);
             $count = count($ordernumbers);
             $index = 0;
             $ordernumber_clause = "and ordernumber in (";
             foreach ($ordernumbers as $measure_type) {
                 $ordernumber_clause .= "'" . $measure_type . "'";
                 $index += 1;
                 if ($index < $count) {
                     $ordernumber_clause .= ", ";
                 }
             }
             $ordernumber_clause .= ")";
             $clause .= $ordernumber_clause;
         }

 
        // Get start date field
        $validity_start_date_operator = get_formvar("validity_start_date_operator");
        $validity_start_date_day = get_formvar("validity_start_date_day");
        $validity_start_date_month = get_formvar("validity_start_date_month");
        $validity_start_date_year = get_formvar("validity_start_date_year");
        $valid_start_date = checkdate($validity_start_date_month, $validity_start_date_day, $validity_start_date_year);
        if ($valid_start_date == 1) {
            $validity_start_date = to_date_string($validity_start_date_day, $validity_start_date_month, $validity_start_date_year);
            if ($validity_start_date_operator == "is") {
                $clause .= " and validity_start_date = '" . $validity_start_date . "' ";
            } elseif ($validity_start_date_operator == "is_on_or_after") {
                $clause .= " and validity_start_date > '" . $validity_start_date . "' ";
            } elseif ($validity_start_date_operator == "is_before") {
                $clause .= " and validity_start_date < '" . $validity_start_date . "' ";
            }
        }

        // Get end date field
        $validity_end_date_operator = get_formvar("validity_end_date_operator");
        $validity_end_date_day = get_formvar("validity_end_date_day");
        $validity_end_date_month = get_formvar("validity_end_date_month");
        $validity_end_date_year = get_formvar("validity_end_date_year");
        $valid_end_date = checkdate($validity_end_date_month, $validity_end_date_day, $validity_end_date_year);
        if ($valid_end_date == 1) {
            $validity_end_date = to_date_string($validity_end_date_day, $validity_end_date_month, $validity_end_date_year);
            if ($validity_end_date_operator == "is") {
                $clause .= " and validity_end_date = '" . $validity_end_date . "' ";
            } elseif ($validity_end_date_operator == "is_on_or_after") {
                $clause .= " and validity_end_date > '" . $validity_end_date . "' ";
            } elseif ($validity_end_date_operator == "is_before") {
                $clause .= " and validity_end_date < '" . $validity_end_date . "' ";
            }
        } elseif ($validity_end_date_operator == "is_specified") {
            $clause .= " and validity_end_date is not null";
        } elseif ($validity_end_date_operator == "is_unspecified") {
            $clause .= " and validity_end_date is null";
        }


        $offset = ($this->page - 1) * $this->page_size;
        $sql .= $clause;
        $this->sort_clause = " order by m.validity_start_date desc, m.goods_nomenclature_item_id";
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";


        // Get the measure components
        $sql_components = "select m.measure_type_id, mc.measure_sid, mc.duty_expression_id, mc.duty_amount, mc.measurement_unit_code,
        mc.measurement_unit_qualifier_code, mc.monetary_unit_code from measure_components mc, measures m 
        where m.measure_sid = mc.measure_sid ";
        $sql_components .= $clause;

        $result = pg_query($conn, $sql_components);
        $temp = array();
        $duty_list = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $duty = new duty;
                    $duty->measure_type_id = $row['measure_type_id'];
                    $duty->measure_sid = $row['measure_sid'];
                    $duty->duty_expression_id = $row['duty_expression_id'];
                    $duty->duty_amount = $row['duty_amount'];
                    $duty->measurement_unit_code = $row['measurement_unit_code'];
                    $duty->measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
                    $duty->monetary_unit_code = $row['monetary_unit_code'];
                    $duty->get_duty_string(1);
                    array_push($temp, $duty);
                }
                $duty_list = $temp;
            }
        }

        // Get the measure conditions
        $sql_conditions = "select m.measure_sid, mc.condition_code, mc.component_sequence_number, mc.condition_duty_amount, mc.condition_monetary_unit_code,
        mc.condition_measurement_unit_code, mc.condition_measurement_unit_qualifier_code,
        mc.certificate_type_code, mc.certificate_code, mc.action_code, mccd.description as condition_code_description, mad.description as action_code_description,
        string_agg(
            mcc.duty_expression_id || '|' ||
            coalesce (mcc.duty_amount::text, '') || '|' ||
            coalesce (mcc.monetary_unit_code, '') || '|' ||
            coalesce (mcc.measurement_unit_code, '') || '|' ||
            coalesce (mcc.measurement_unit_qualifier_code, ''),
            
            ',' order by mcc.duty_expression_id) as duties
        from measure_condition_code_descriptions mccd, measure_action_descriptions mad, measures m, measure_conditions mc
        left outer join measure_condition_components mcc on mc.measure_condition_sid = mcc.measure_condition_sid 
        where mccd.condition_code = mc.condition_code
        and mad.action_code = mc.action_code
        and m.measure_sid = mc.measure_sid ";

        $groupby  = " group by m.measure_sid, mc.condition_code, mc.component_sequence_number, mc.condition_duty_amount, mc.condition_monetary_unit_code,
        mc.condition_measurement_unit_code, mc.condition_measurement_unit_qualifier_code,
        mc.certificate_type_code, mc.certificate_code, mc.action_code, mccd.description, mad.description;";
        $sql_conditions .= $clause;
        $sql_conditions .= $groupby;

        $result = pg_query($conn, $sql_conditions);
        $condition_list = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $mc = new measure_condition;
                    $mc->measure_sid = $row['measure_sid'];
                    $mc->condition_code = $row['condition_code'];
                    $mc->condition_code_description = $row['condition_code_description'];
                    $mc->component_sequence_number = $row['component_sequence_number'];
                    $mc->condition_duty_amount = $row['condition_duty_amount'];
                    $mc->condition_monetary_unit_code = $row['condition_monetary_unit_code'];
                    $mc->condition_measurement_unit_code = $row['condition_measurement_unit_code'];
                    $mc->condition_measurement_unit_qualifier_code = $row['condition_measurement_unit_qualifier_code'];
                    $mc->certificate_type_code = $row['certificate_type_code'];
                    $mc->certificate_code = $row['certificate_code'];
                    $mc->action_code = $row['action_code'];
                    $mc->action_code_description = $row['action_code_description'];
                    $mc->duties = $row['duties'];
                    $mc->get_reference_price_string();
                    $mc->get_condition_string();

                    array_push($condition_list, $mc);
                }
            }
        }

        // Get the footnotes
        $sql_footnotes = "select fam.measure_sid, fam.footnote_type_id, fam.footnote_id
        from footnote_association_measures fam, measures m
        where m.measure_sid = fam.measure_sid ";
        $sql_footnotes .= $clause;

        $footnote_list = array();

        $result = pg_query($conn, $sql_footnotes);
        $temp = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $f = new footnote;
                    $f->measure_sid = $row['measure_sid'];
                    $f->footnote_type_id = $row['footnote_type_id'];
                    $f->footnote_id = $row['footnote_id'];
                    array_push($temp, $f);
                }
                $footnote_list = $temp;
            }
        }

        // Get the geo exclusions
        $sql_exclusions = "select mega.excluded_geographical_area, mega.geographical_area_sid, m.measure_sid
        from measure_excluded_geographical_areas mega, measures m
        where mega.measure_sid = m.measure_sid  ";
        $sql_exclusions .= $clause;

        $exclusion_list = array();

        $result = pg_query($conn, $sql_exclusions);
        $temp = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $ex = new measure_excluded_geographical_area;
                    $ex->measure_sid = $row['measure_sid'];
                    $ex->excluded_geographical_area = $row['excluded_geographical_area'];
                    $ex->geographical_area_sid = $row['geographical_area_sid'];
                    array_push($temp, $ex);
                }
                $exclusion_list = $temp;
            }
        }

        // Get the measures
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $this->row_count = $row['full_count'];
                    $measure = new measure;
                    $measure->measure_sid = $row['measure_sid'];
                    $measure->measure_generating_regulation_id = $row['measure_generating_regulation_id'];
                    $measure->validity_start_date = short_date($row['validity_start_date']);
                    $measure->validity_end_date = short_date($row['validity_end_date']);
                    $measure->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                    $measure->additional_code = $row['additional_code'];
                    $measure->additional_code_sid = $row['additional_code_sid'];
                    $measure->geographical_area_id = $row['geographical_area_id'];
                    $measure->exclusions = $row['exclusions'];
                    $measure->measure_type_id = $row['measure_type_id'];
                    $measure->measure_generating_regulation_id = $row['measure_generating_regulation_id'];
                    $measure->ordernumber = $row['ordernumber'];
                    $measure->duties = "";
                    $measure->conditions = "tbc";
                    $measure->footnotes = "tbc";
                    $measure->status = $row['status'];
                    $measure->active_state = $row['active_state'];

                    array_push($temp, $measure);
                }
                $this->measures = $temp;
            }
        }

        // Apply the duties to the measures
        foreach ($duty_list as $duty) {
            foreach ($this->measures as $measure) {
                if ($measure->measure_sid == $duty->measure_sid) {
                    array_push($measure->duty_list, $duty);
                    break;
                }
            }
        }

        foreach ($this->measures as $measure) {
            $measure->combine_duties();
            $measure->duties = $measure->combined_duty;
        }

        // Apply the footnotes to the measure
        foreach ($footnote_list as $f) {
            foreach ($this->measures as $measure) {
                if ($measure->measure_sid == $f->measure_sid) {
                    array_push($measure->footnote_list, $f);
                    break;
                }
            }
        }

        foreach ($this->measures as $measure) {
            $measure->combine_footnotes();
            $measure->footnotes = $measure->combined_footnotes;
        }


        // Apply the exclusions to the measures
        foreach ($exclusion_list as $ex) {
            foreach ($this->measures as $measure) {
                if ($measure->measure_sid == $ex->measure_sid) {
                    array_push($measure->exclusion_list, $ex);
                    break;
                }
            }
        }

        foreach ($this->measures as $measure) {
            $measure->combine_exclusions();
            $measure->exclusions = $measure->combined_exclusions;
        }

        // Apply the conditions to the measures
        foreach ($condition_list as $c) {
            foreach ($this->measures as $measure) {
                if ($measure->measure_sid == $c->measure_sid) {
                    array_push($measure->condition_list, $c);
                    break;
                }
            }
        }

        foreach ($this->measures as $measure) {
            $measure->combine_conditions();
            $measure->conditions = $measure->combined_conditions;
        }
    }

