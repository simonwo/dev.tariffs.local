<?php
class application
{
    // Class properties and methods go here
    public $page_size;
    public $measures = array();
    public $measure_types = array();
    public $regulation_groups = array();
    public $countries_and_regions = array();
    public $geographical_areas = array();
    public $members = array();
    public $rules_of_origin_schemes = array();

    public $min_additional_code_description_periods = 20000;
    public $min_additional_codes = 20000;
    public $min_certificate_description_periods = 10000;
    public $min_footnote_description_periods = 200000;
    public $min_geographical_area_description_periods = 10000;
    public $min_geographical_areas = 10000;
    public $min_goods_nomenclature = 200000;
    public $min_goods_nomenclature_description_periods = 200000;
    public $min_goods_nomenclature_indents = 200000;
    public $min_measure_conditions = 2000000;
    public $min_measures = 5000000;
    public $min_quota_blocking_periods = 1000;
    public $min_quota_definitions = 20000;
    public $min_quota_order_number_origins = 10000;
    public $min_quota_order_numbers = 10000;
    public $min_quota_suspension_periods = 1000;
    public $min_monetary_exchange_periods = 10000;
    public $tariff_object = "";
    public $sort_clause = "";
    public $mode = "";
    public $notification_text = "";
    public $row_count = 0;
    public $session = null;
    public $conditional_duty_application_options = array();
    public $quotas = array();
    public $show_workbasket_icons = true;

    public function __construct()
    {
        $this->name = "Manage the UK Tariff";
        $this->create_session();

        // Insert, edit or view mode
        if (isset($_REQUEST["mode"])) {
            $this->mode = $_REQUEST["mode"];
        } else {
            if (strpos($_SERVER['PHP_SELF'], "create_edit") !== false) {
                $this->mode = "insert";
            } elseif (strpos($_SERVER['PHP_SELF'], "view") !== false) {
                $this->mode = "view";
            }
        }


        // Paging parameters
        $this->page_size = 20;
        $this->page = intval(get_querystring("p"));
        if (($this->page == 0) || (!empty($_POST))) {
            $this->page = 1;
        }

        $notify = get_querystring("notify");
        if ($notify != "") {
            $this->notify($notify);
        }
    }

    public function notify($notify)
    {
        $this->notification_text = "<script>";
        $this->notification_text .= "$(document).ready(function () {";
        $this->notification_text .= '$.notify(';
        $this->notification_text .= '"' . $notify . '",';
        $this->notification_text .= '{';
        $this->notification_text .= 'position: "right bottom",';
        $this->notification_text .= 'autoHide: true,';
        $this->notification_text .= 'autoHideDelay: 3000,';
        $this->notification_text .= 'arrowShow: true,';
        $this->notification_text .= 'arrowSize: 5,';
        $this->notification_text .= "style: 'govuk-body'},";
        $this->notification_text .= ');';
        $this->notification_text .= '});';
        $this->notification_text .= "</script>";
    }

    public function create_session()
    {
        $this->session = new session();
    }


    public function init($tariff_object, $config_file = "config.json")
    {
        $this->tariff_object = $tariff_object;
        $uri = $_SERVER["REQUEST_URI"];
        // If the user does not have a workbasket open, then any attempt to create or amend data should result in redirecting
        // to the create or open workbasket page; 

        // But if the workbasket belongs to someone else, then there needs to be a check, so that the user does not
        // accidentally move from approval into creation mode.

        if (strpos($uri, 'reference_documents') === false) {
            if (strpos($uri, 'create') !== false) {
                if ($this->session->workbasket == null) {
                    //prend ($_SERVER);
                    $request_uri = urlencode($_SERVER["REQUEST_URI"]);
                    $url = "/workbaskets/create_or_open_workbasket.html?request_uri=" . $request_uri;
                    header("Location: " . $url);
                } elseif ($this->session->workbasket->user_id != $this->session->user_id) {
                    // You are in someone else's workbasket
                    // Are you sure you want to carry on
                    if (get_session_variable("confirm_operate_others_workbasket") == "") {
                        $request_uri = urlencode($_SERVER["REQUEST_URI"]);
                        $url = "/workbaskets/confirm_operate_others_workbasket.html?request_uri=" . $request_uri;
                        header("Location: " . $url);
                    }
                }
            }
        }

        // Config settings globally
        $cdr = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
        $path = $cdr . "/data/application_config.json";
        $data = file_get_contents($path);
        $this->global_config = json_decode($data, true);
        $this->common_measurement_units = $this->global_config["config"]["common_measurement_units"];
        $this->minimum_sids = $this->global_config["config"]["minimum_sids"];

        // Config settings for the specific object
        $sfn = $_SERVER['SCRIPT_FILENAME'];
        $path = str_replace(basename($_SERVER["SCRIPT_NAME"]), $config_file, $sfn);
        if (file_exists($path)) {
            $this->filters_content = file_get_contents($path);
            $this->data = json_decode($this->filters_content, true);
            $this->object_name =  $this->data[$this->tariff_object]["config"]["object_name"];
        }
    }

    public function get_duty_expressions()
    {
        global $conn;
        $sql = "SELECT de.duty_expression_id, description, validity_start_date, validity_end_date,
        duty_amount_applicability_code, measurement_unit_applicability_code, monetary_unit_applicability_code
        FROM duty_expressions de, duty_expression_descriptions ded
        WHERE de.duty_expression_id = ded.duty_expression_id
        --AND validity_end_date IS NULL
        AND de.duty_expression_id NOT IN ('37')
        ORDER BY 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $duty_expression_id = $row['duty_expression_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $duty_amount_applicability_code = $row['duty_amount_applicability_code'];
                $measurement_unit_applicability_code = $row['measurement_unit_applicability_code'];
                $monetary_unit_applicability_code = $row['monetary_unit_applicability_code'];

                $duty_expression = new duty_expression;
                $duty_expression->duty_expression_id = $duty_expression_id;
                $duty_expression->description = $description;
                $duty_expression->validity_start_date = $validity_start_date;
                $duty_expression->validity_end_date = $validity_end_date;
                $duty_expression->duty_amount_applicability_code = $duty_amount_applicability_code;
                $duty_expression->measurement_unit_applicability_code = $measurement_unit_applicability_code;
                $duty_expression->monetary_unit_applicability_code = $monetary_unit_applicability_code;

                array_push($temp, $duty_expression);
            }
            $this->duty_expressions = $temp;
        }
    }

    public function get_measure_actions()
    {
        global $conn;
        $sql = "SELECT ma.action_code, description, abbreviation, validity_start_date, validity_end_date
        FROM measure_actions ma, measure_action_descriptions mad
        WHERE ma.action_code = mad.action_code
        AND validity_end_date IS NULL ORDER BY ma.action_code;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $action_code = $row['action_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measure_action = new measure_action;
                $measure_action->action_code = $action_code;
                $measure_action->description = put_spaces_round_slashes($description);
                $measure_action->abbreviation = $row['abbreviation'];
                $measure_action->validity_start_date = $validity_start_date;
                $measure_action->validity_end_date = $validity_end_date;
                $measure_action->optgroup = "";

                $measure_action->id = $measure_action->action_code;
                $measure_action->string = $measure_action->action_code . ' - ' . $measure_action->description;
                array_push($temp, $measure_action);
            }
            $this->measure_actions = $temp;
        }
    }


    public function get_measure_condition_codes()
    {
        global $conn;
        $sql = "SELECT mcc.condition_code, description, validity_start_date, validity_end_date
        FROM measure_condition_codes mcc, measure_condition_code_descriptions mccd
        WHERE mcc.condition_code = mccd.condition_code
        AND validity_end_date IS NULL ORDER BY mcc.condition_code;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $condition_code = $row['condition_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measure_condition_code = new measure_condition_code;
                $measure_condition_code->measure_condition_code = $condition_code;
                $measure_condition_code->description = put_spaces_round_slashes($description);
                $measure_condition_code->validity_start_date = $validity_start_date;
                $measure_condition_code->validity_end_date = $validity_end_date;

                $measure_condition_code->id = $measure_condition_code->measure_condition_code;
                $measure_condition_code->string = $measure_condition_code->id . " - " . $measure_condition_code->description;
                $measure_condition_code->optgroup = "";


                array_push($temp, $measure_condition_code);
            }
            $this->measure_condition_codes = $temp;
        }
    }


    public function get_regulation_groups()
    {
        global $conn;
        $sql = "SELECT rg.regulation_group_id, description, validity_start_date, validity_end_date
        FROM regulation_groups rg, regulation_group_descriptions rgd
        WHERE rg.regulation_group_id = rgd.regulation_group_id
        AND validity_end_date IS NULL and display = true ORDER BY rgd.regulation_group_id;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $regulation_group_id = $row['regulation_group_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $regulation_group = new regulation_group;
                $regulation_group->regulation_group_id = $regulation_group_id;
                $regulation_group->description = $description;
                $regulation_group->validity_start_date = $validity_start_date;
                $regulation_group->validity_end_date = $validity_end_date;
                $regulation_group->id = $regulation_group_id;
                $regulation_group->string = "<b>" . $regulation_group_id . "</b> - " . $description;

                $regulation_group->url = "/regulations/?filter_regulations_regulation_group_id=" . $regulation_group->id;
                $regulation_group->regulation_url = "<a class='govuk-link' href='" . $regulation_group->url . "'>View regulations</a>";


                array_push($temp, $regulation_group);
            }
            $this->regulation_groups = $temp;
        }
    }



    public function xx_get_geographical_members($parent_id)
    {
        global $conn;
        $sql = "SELECT child_id as geographical_area_id, child_description as description FROM ml.ml_geo_memberships WHERE parent_id = '" . $parent_id . "'
 AND (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
 ORDER BY child_id";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_sid = 0;
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = 0;
                $validity_start_date = "";
                $validity_end_date = "";

                $member = new geographical_area;
                $member->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                array_push($temp, $member);
            }
            $this->countries_and_regions = $temp;
        }
    }


    public function get_countries_and_regions()
    {
        global $conn;
        $sql = "SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
 validity_end_date FROM ml.ml_geographical_areas WHERE geographical_code != '1' AND
 (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
 ORDER BY geographical_area_id;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_sid = $row['geographical_area_sid'];
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = $row['geographical_code'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $geographical_area = new geographical_area;
                $geographical_area->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                array_push($temp, $geographical_area);
            }
            $this->countries_and_regions = $temp;
        }
    }

    public function get_regulations_api()
    {
        global $conn;
        $sql = "with cte as 
        (
            select br.base_regulation_id as base_regulation_id, validity_start_date, validity_end_date, effective_end_date,
            information_text, br.regulation_group_id, rgd.description as regulation_group_description,
            'Base' as regulation_type,
            case
                when (validity_end_date is not null or effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, br.trade_remedies_case,
            case
            	when (officialjournal_number = '1' and officialjournal_page = '1') then 'UK'
            	else 'EU'
            end as regulation_scope, br.status
            from base_regulations br, regulation_group_descriptions rgd
            where br.regulation_group_id = rgd.regulation_group_id
            /*
            union 
            
            select mr.modification_regulation_id as base_regulation_id, mr.validity_start_date, mr.validity_end_date, mr.effective_end_date,
            mr.information_text, br.regulation_group_id as regulation_group_id, rgd.description as regulation_group_description,
            'Modification' as regulation_type, 
            case
                when (mr.validity_end_date is not null or mr.effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, '' as trade_remedies_case, 'EU' as regulation_scope, mr.status
            from modification_regulations mr, base_regulations br, regulation_group_descriptions rgd
            where mr.base_regulation_id = br.base_regulation_id 
            and mr.base_regulation_role = br.base_regulation_role
            and br.regulation_group_id = rgd.regulation_group_id 
            */
        )
        select base_regulation_id, information_text, count(*) OVER() AS full_count
        from cte where 1 > 0 and validity_end_date is null and effective_end_date is null order by base_regulation_id";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $base_regulation = new base_regulation;
                $base_regulation->base_regulation_id = $row['base_regulation_id'];
                $base_regulation->information_text = $row['information_text'];
                array_push($temp, $base_regulation);
            }
            $this->base_regulations = $temp;
        }
    }



    public function get_all_regulations()
    {
        global $conn;
        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with cte as 
        (
            select br.base_regulation_id as base_regulation_id, validity_start_date, validity_end_date, effective_end_date,
            information_text, br.regulation_group_id, rgd.description as regulation_group_description,
            'Base' as regulation_type,
            case
                when (validity_end_date is not null or effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, br.trade_remedies_case,
            case
            	when (officialjournal_number = '1' and officialjournal_page = '1') then 'UK'
            	else 'EU'
            end as regulation_scope, br.status
            from base_regulations br, regulation_group_descriptions rgd
            where br.regulation_group_id = rgd.regulation_group_id
            
            union 
            
            select mr.modification_regulation_id as base_regulation_id, mr.validity_start_date, mr.validity_end_date, mr.effective_end_date,
            mr.information_text, br.regulation_group_id as regulation_group_id, rgd.description as regulation_group_description,
            'Modification' as regulation_type, 
            case
                when (mr.validity_end_date is not null or mr.effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, '' as trade_remedies_case, 'EU' as regulation_scope, mr.status
            from modification_regulations mr, base_regulations br, regulation_group_descriptions rgd
            where mr.base_regulation_id = br.base_regulation_id 
            and mr.base_regulation_role = br.base_regulation_role
            and br.regulation_group_id = rgd.regulation_group_id 
        )
        select *, count(*) OVER() AS full_count
        from cte where 1 > 0 ";

        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $base_regulation = new base_regulation;
                $base_regulation->base_regulation_id = $row['base_regulation_id'];
                $base_regulation->base_regulation_url = "<a class='govuk-link' href='view.html?mode=view&base_regulation_id=" . $row['base_regulation_id'] . "'>" . $row['base_regulation_id'] . "</a>";
                $base_regulation->information_text = $row['information_text'];
                $base_regulation->regulation_type = $row['regulation_type'];
                $base_regulation->regulation_group_id = $row['regulation_group_id'];
                $base_regulation->regulation_group_description = $row['regulation_group_description'];
                $base_regulation->regulation_group_id_description = $base_regulation->regulation_group_id . " " . $base_regulation->regulation_group_description;
                $base_regulation->regulation_group_url = '<a class="govuk-link" href="/regulation_groups/create_edit.html?mode=update&regulation_group_id=' . $base_regulation->regulation_group_id . '">' . $base_regulation->regulation_group_id_description . '</a>';
                $base_regulation->validity_start_date = short_date($row['validity_start_date']);
                $base_regulation->validity_end_date = short_date($row['validity_end_date']);
                $base_regulation->effective_end_date = short_date($row['effective_end_date']);

                $url = "/measures/?filter_measures_freetext=" . $base_regulation->base_regulation_id;
                $base_regulation->measures_url = "<a class='govuk-link' href='" . $url . "'>View measures</a>";
                $workbasket->status = $row['status'];
                $base_regulation->status = status_image($workbasket->status);
                array_push($temp, $base_regulation);
            }
            $this->base_regulations = $temp;
        }
    }

    public function get_geographical_codes()
    {
        $this->geographical_codes = array();
        array_push($this->geographical_codes, new simple_object("0", "Country", "Country", "This will have two-letter ISO code. You can add countries to geographical area groups, but a country cannot itself be a group."));
        array_push($this->geographical_codes, new simple_object("1", "Geographical area group", "Group", "Create a group when you want to reference multiple countries and/or regions together. A group must have four-character (letters and/or numbers) code."));
        array_push($this->geographical_codes, new simple_object("2", "Region", "Region", "Use this only in exceptional cases, to represent a geographical entity that is not a country. Functionally, a region is the same as a country."));
    }

    public function get_regulation_scopes()
    {
        $this->regulation_scopes = array();
        array_push($this->regulation_scopes, new simple_object("UK", "UK regulation", "", ""));
        array_push($this->regulation_scopes, new simple_object("EU", "EU regulation", "", ""));
    }

    public function get_regulation_types()
    {
        $this->regulation_types = array();
        array_push($this->regulation_types, new simple_object("Base", "Base", "Base", ""));
        array_push($this->regulation_types, new simple_object("Modification", "Modification", "Modification", ""));
    }

    public function get_current_geographical_areas()
    {
        global $conn;
        $this->current_geographical_areas = array();
        $sql = "SELECT geographical_area_sid, geographical_area_id, description
        FROM ml.ml_geographical_areas ga
        WHERE (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
        order by description";
        $stmt = "get_current_geographical_areas" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $obj = new reusable();
                $obj->id = $row["geographical_area_id"];
                $obj->sid = $row["geographical_area_sid"];
                $obj->string = $row["geographical_area_id"] . " - " . str_replace('"', '', $row["description"]);
                array_push($this->current_geographical_areas, $obj);
            }
        }
    }

    public function get_geographical_areas()
    {
        global $conn;
        $this->get_geographical_codes();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "with cte as (SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
        validity_end_date,
        case
            when ga.validity_end_date is not null then 'Terminated'
        else 'Active'
        end as active_state, status
        FROM ml.ml_geographical_areas ga
        --WHERE (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
        )
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $geographical_area_sid = $row['geographical_area_sid'];
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = $row['geographical_code'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $geographical_area = new geographical_area;
                $geographical_area->status = $row['status'];
                $geographical_area->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                $geographical_area->geographical_code_description = $this->geographical_codes[$geographical_area->geographical_code]->string;
                $geographical_area->geographical_code_id_description = $geographical_area->geographical_code . ' ' . $geographical_area->geographical_code_description;
                //$geographical_area->geographical_description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&geographical_area_id=' . $geographical_area->geographical_area_id . '&geographical_area_sid=' . $geographical_area->geographical_area_sid . '">' . $geographical_area->description . '</a>';
                $geographical_area->geographical_description_url = '<a class="govuk-link" href="./view.html?mode=view&geographical_area_id=' . $geographical_area->geographical_area_id . '&geographical_area_sid=' . $geographical_area->geographical_area_sid . '">' . $geographical_area->description . '</a>';
                array_push($temp, $geographical_area);
            }
            $this->geographical_areas = $temp;
        }
    }


    public function get_additional_codes()
    {
        global $conn;
        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "with cte as (select ac.additional_code_type_id, ac.additional_code,
        ac.validity_start_date, ac.validity_end_date, ac.description,
        actd.description as additional_code_type_description, ac.additional_code_sid,
        case
	        when ac.validity_end_date is not null then 'Terminated'
	        else 'Active'
	    end as active_state, ac.status
        from ml.ml_additional_codes ac, additional_code_type_descriptions actd 
        where ac.additional_code_type_id = actd.additional_code_type_id )
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $additional_code = new additional_code;
                $additional_code->additional_code_type_id = $row['additional_code_type_id'];
                $additional_code->additional_code_sid = $row['additional_code_sid'];
                $additional_code->additional_code = $row['additional_code'];
                $additional_code->additional_code_plus_type = $additional_code->additional_code_type_id . $additional_code->additional_code;
                $additional_code->description = $row['description'];
                //$additional_code->description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->description_url = '<a class="govuk-link" href="./view.html?mode=view&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->additional_code_type_description = $row['additional_code_type_description'];
                $additional_code->additional_code_type_description = str_replace("/", " / ", $additional_code->additional_code_type_description);
                $additional_code->additional_code_type_description = str_replace("  ", " ", $additional_code->additional_code_type_description);

                $additional_code->additional_code_type_id_description = $additional_code->additional_code_type_id . "&nbsp;" . $additional_code->additional_code_type_description;
                $additional_code->validity_start_date = short_date($row['validity_start_date']);
                $additional_code->validity_end_date = short_date($row['validity_end_date']);

                $measures_url = "#";
                $additional_code->measures_link = '<a class="govuk-link" href="' . $measures_url . '">View measures</a>';
                $workbasket->status = $row['status'];
                $additional_code->status = status_image($workbasket->status);

                array_push($temp, $additional_code);
            }
            $this->additional_codes = $temp;
        }
    }

    public function get_goods_nomenclature_sections()
    {
        global $conn;
        $sql = "select position, numeral, title from sections s order by 1;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $section = new section($row["position"], $row["numeral"], $row["title"]);
            }
        }
    }

    public function get_goods_nomenclatures()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "select ac.additional_code_type_id, ac.additional_code, ac.validity_start_date, ac.validity_end_date, ac.description,
        actd.description as additional_code_type_description, count(*) OVER() AS full_count, ac.additional_code_sid
        from ml.ml_additional_codes ac, additional_code_type_descriptions actd 
        where ac.validity_end_date is null
        and ac.additional_code_type_id = actd.additional_code_type_id ";
        $sql .= $filter_clause;
        $sql .= " order by 1, 2 limit $this->page_size offset $offset";
        //h2($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $additional_code = new additional_code;
                $additional_code->additional_code_type_id = $row['additional_code_type_id'];
                $additional_code->additional_code_sid = $row['additional_code_sid'];
                $additional_code->additional_code = $row['additional_code'];
                $additional_code->additional_code_plus_type = $additional_code->additional_code_type_id . $additional_code->additional_code;
                $additional_code->description = $row['description'];
                $additional_code->description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->additional_code_type_description = $row['additional_code_type_description'];
                $additional_code->additional_code_type_id_description = $additional_code->additional_code_type_id . "&nbsp;" . $additional_code->additional_code_type_description;
                $additional_code->validity_start_date = short_date($row['validity_start_date']);
                $additional_code->validity_end_date = short_date($row['validity_end_date']);

                array_push($temp, $additional_code);
            }
            $this->additional_codes = $temp;
        }
    }


    public function get_certificates($api = false)
    {
        global $conn;
        $workbasket = new workbasket();
        $sql = "with cte as (select c.certificate_type_code, c.certificate_code, c.code, c.description, c.validity_start_date, c.validity_end_date,
        ctd.description as certificate_type_description,
        case
            when c.validity_end_date is not null then 'Terminated'
            else 'Active'
	    end as active_state, c.status
        from ml.ml_certificate_codes c, certificate_type_descriptions ctd
        where c.certificate_type_code = ctd.certificate_type_code)
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";

        if ($api) {
            $sql .= "order by c.certificate_type_code, c.certificate_code;";
        } else {
            $filter_clause = $this->get_filter_clause();
            $offset = ($this->page - 1) * $this->page_size;
            $sql .= $filter_clause;
            $sql .= $this->sort_clause;
            $sql .= " limit $this->page_size offset $offset";
        }
        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $certificate = new certificate;
                $certificate->certificate_type_code = $row['certificate_type_code'];
                $certificate->certificate_code = $row['certificate_code'];
                $certificate->certificate_code_plus_type = $certificate->certificate_type_code . $certificate->certificate_code;
                $certificate->description = $row['description'];
                $certificate->description_url = '<a class="govuk-link" href="view.html?mode=view&certificate_type_code=' . $certificate->certificate_type_code . '&certificate_code=' . $certificate->certificate_code . '">' . $certificate->description . '</a>';
                $certificate->certificate_type_description = $row['certificate_type_description'];
                $certificate->certificate_type_code_description = $certificate->certificate_type_code . "&nbsp;" . $certificate->certificate_type_description;
                $certificate->validity_start_date = short_date($row['validity_start_date']);
                $certificate->validity_end_date = short_date($row['validity_end_date']);
                $certificate->measures_url = "<a class='govuk-link' href=''>View measures</a>";
                $workbasket->status = $row['status'];
                $certificate->status = status_image($workbasket->status);
                array_push($temp, $certificate);
            }
            $this->certificates = $temp;
        }
    }

    public function get_measure_types()
    {
        global $conn;
        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with cte as (SELECT mt.measure_type_id, validity_start_date, validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code, order_number_capture_code, measure_explosion_level,
        mt.measure_type_series_id, mtd.description, mtsd.description as measure_type_series_description,
        case
            when mt.validity_end_date is not null then 'Terminated'
            else 'Active'
        end as active_state, mt.status
        FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
        WHERE mt.measure_type_id = mtd.measure_type_id
        AND mt.measure_type_series_id = mtsd.measure_type_series_id )
        select *,
        count(*) OVER() AS full_count from cte where 1 > 0 ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $measure_type_id = $row['measure_type_id'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $trade_movement_code = $row['trade_movement_code'];
                $priority_code = $row['priority_code'];
                $measure_component_applicable_code = $row['measure_component_applicable_code'];
                $origin_dest_code = $row['origin_dest_code'];
                $order_number_capture_code = $row['order_number_capture_code'];
                $measure_explosion_level = $row['measure_explosion_level'];
                $measure_type_series_id = $row['measure_type_series_id'];
                $measure_type_series_description = $row['measure_type_series_description'];
                $description = $row['description'];
                $measure_type = new measure_type;

                $quota_list = array(122, 123, 143, 145);
                if (in_array($measure_type_id, $quota_list)) {
                    $is_quota = True;
                } else {
                    $is_quota = False;
                }

                $measure_type->set_properties(
                    $measure_type_id,
                    $validity_start_date,
                    $validity_end_date,
                    $trade_movement_code,
                    $priority_code,
                    $measure_component_applicable_code,
                    $origin_dest_code,
                    $order_number_capture_code,
                    $measure_explosion_level,
                    $measure_type_series_id,
                    $description,
                    $is_quota
                );
                $workbasket->status = $row['status'];
                $measure_type->status = status_image($workbasket->status);
                $measure_type->measure_type_series_description = $measure_type_series_description;
                $measure_type->measure_type_series_id_description = $measure_type_series_id . '&nbsp;' . $measure_type_series_description;

                $measure_type->measure_type_series_url = '<a class="govuk-link" href="/measure_type_series/view.html?mode=view&measure_type_series_id=' . $measure_type_series_id . '">' . $measure_type->measure_type_series_id_description . '</a>';
                //$measure_type->measure_type_url = '<a class="govuk-link" href="/measure_types/create_edit.html?mode=update&measure_type_id=' . $measure_type_id . '">' . $measure_type->description . '</a>';
                $measure_type->measure_type_url = '<a class="govuk-link" href="/measure_types/view.html?mode=view&measure_type_id=' . $measure_type_id . '">' . $measure_type->description . '</a>';

                $url = "/measures/?filter_measures_measure_type_id=" . $measure_type_id;
                $measure_type->measure_url = '<a class="govuk-link" href="' . $url . '">View measures</a>';

                array_push($temp, $measure_type);
            }
            $this->measure_types = $temp;
        }
    }

    public function get_relation_types()
    {
        $this->relation_types = array();
        array_push($this->relation_types, new simple_object("EQ", "EQ - Equivalent to main quota", "", "This means that, when the subquota is decremented, the main quota will be decremented by a different amount, as dictated by the coefficient."));
        array_push($this->relation_types, new simple_object("NM", "NM - Normal (restrictive to main quota)", "", "This means that, when the subquota is decremented, the main quota will be decremented by the same amount. This is used to place product- or geography-specific quantitative restrictions on sub-quotas."));
    }

    public function get_additional_code_application_codes()
    {
        $this->additional_code_application_codes = array();
        array_push($this->additional_code_application_codes, new simple_object("0", "0 Export refund nomenclature", "ERN"));
        array_push($this->additional_code_application_codes, new simple_object("1", "1 Additional codes", "Additional codes"));
        array_push($this->additional_code_application_codes, new simple_object("3", "3 Meursing additional codes", "Meursing codes"));
        array_push($this->additional_code_application_codes, new simple_object("4", "4 Export refund for processed agricultural goods", "Agri"));
    }

    public function get_footnote_application_codes()
    {
        $this->footnote_application_codes = array();
        array_push($this->footnote_application_codes, new simple_object("1", "CN Nomenclature (can be applied to <= 8-digit commodity codes)", "CN Nomenclature"));
        array_push($this->footnote_application_codes, new simple_object("2", "TARIC nomenclature (can be applied to any commodity codes)", "TARIC nomenclature"));
        array_push($this->footnote_application_codes, new simple_object("6", "CN measures (can be applied to measures at <= 8-digits)", "CN measures"));
        array_push($this->footnote_application_codes, new simple_object("7", "Other measures (can be applied to any measure)", "Other measures"));
    }

    public function get_footnotes()
    {
        global $conn;

        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with status_cte as (
            select f.footnote_type_id, f.footnote_id, f.description, f.validity_start_date, f.validity_end_date,
            ftd.description as footnote_type_description, ft.application_code,
            date_part('year', f.validity_start_date) as start_year,
            case
                when f.validity_end_date is not null then 'Terminated'
                else 'Active'
            end as active_state, f.status
            from ml.ml_footnotes f, footnote_type_descriptions ftd, footnote_types ft
            where f.footnote_type_id = ftd.footnote_type_id
            and f.footnote_type_id = ft.footnote_type_id
        )
        select *, count(*) OVER() AS full_count
        from status_cte f where 1 > 0 ";
        //and f.validity_end_date is null ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            $this->get_footnote_application_codes();
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $footnote = new footnote;
                $footnote->footnote_type_id = $row['footnote_type_id'];
                $footnote->footnote_id = $row['footnote_id'];
                $footnote->application_code = $row['application_code'];
                $footnote->usage = "";
                foreach ($this->footnote_application_codes as $ac) {
                    if ($ac->id == $footnote->application_code) {
                        $footnote->usage = $ac->string;
                        break;
                    }
                }
                $footnote->code = $footnote->footnote_type_id . $footnote->footnote_id;
                $footnote->validity_start_date = short_date($row['validity_start_date']);
                $footnote->validity_end_date = short_date($row['validity_end_date']);
                $footnote->description = $row['description'];
                $footnote->footnote_type_description = $row['footnote_type_description'];
                $workbasket->status = $row['status'];
                $footnote->status = status_image($workbasket->status);
                $footnote->footnote_type_id_description = $footnote->footnote_type_id . ' ' . $footnote->footnote_type_description;
                //$footnote->footnote_description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&footnote_id=' . $footnote->footnote_id . '&footnote_type_id=' . $footnote->footnote_type_id . '">' . $footnote->description . '</a>';
                $footnote->footnote_description_url = '<a class="govuk-link" href="./view.html?mode=view&footnote_id=' . $footnote->footnote_id . '&footnote_type_id=' . $footnote->footnote_type_id . '">' . $footnote->description . '</a>';

                array_push($temp, $footnote);
            }
            $this->footnotes = $temp;
        }
    }

    public function get_active_states()
    {
        $this->active_states = array();
        array_push($this->active_states, new simple_object("Active", "Active", "Active", ""));
        array_push($this->active_states, new simple_object("Terminated", "Terminated", "Terminated", ""));
    }

    public function get_measure_type_series()
    {
        global $conn;
        $sql = "select mts.measure_type_series_id, mtsd.description,
        mts.validity_start_date, mts.validity_end_date, mts.measure_type_combination
        from measure_type_series mts, measure_type_series_descriptions mtsd 
        where mts.measure_type_series_id = mtsd.measure_type_series_id
        and mts.validity_end_date is null
        order by 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measure_type_series_id = $row['measure_type_series_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $measure_type_combination = $row['measure_type_combination'];
                $measure_type_series = new measure_type_series;

                $measure_type_series->measure_type_series_id = $measure_type_series_id;
                $measure_type_series->description = $description;
                $measure_type_series->validity_start_date = $validity_start_date;
                $measure_type_series->validity_end_date = $validity_end_date;
                $measure_type_series->measure_type_combination = $measure_type_combination;
                if ($measure_type_series->measure_type_combination == 0) {
                    $measure_type_series->measure_type_combination_string = $measure_type_combination . " - Only 1 measure at export and 1 at import from the series";
                } else {
                    $measure_type_series->measure_type_combination_string = $measure_type_combination . " - All measure types in the series to be considered";
                }
                $measure_type_series->id = $measure_type_series->measure_type_series_id;
                $measure_type_series->string = $measure_type_series->measure_type_series_id . " - " . $measure_type_series->description;

                $url = "/measure_type_series/create_edit.html?mode=update&measure_type_series_id=" . $measure_type_series->id;
                $measure_type_series->measure_type_series_url = "<a class='govuk-link' href='" . $url . "'>" . $measure_type_series->description . "</a>";

                $url = "/measure_types/?filter_measure_types_measure_type_series_id=" . $measure_type_series->id;
                $measure_type_series->measure_type_url = "<a class='govuk-link' href='" . $url . "'>View measure types</a>";

                array_push($temp, $measure_type_series);
            }
            $this->measure_type_series = $temp;
        }
    }

    public function get_rules_of_origin_schemes()
    {
        global $conn;
        $sql = "select rules_of_origin_scheme_sid, description, abbreviation, validity_start_date, validity_end_date
        from ml.rules_of_origin_schemes roos order by 1, 2";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $rules_of_origin_scheme_sid = $row['rules_of_origin_scheme_sid'];
                $description = $row['description'];
                $abbreviation = $row['abbreviation'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $roo = new rules_of_origin_scheme;
                $roo->rules_of_origin_scheme_sid = $rules_of_origin_scheme_sid;
                $roo->description = $description;
                $roo->abbreviation = $abbreviation;
                $roo->validity_start_date = $validity_start_date;
                $roo->validity_end_date = $validity_end_date;

                $roo_url = "/rules_of_origin_schemes/create_edit.html?mode=update&rules_of_origin_scheme_sid=" . $rules_of_origin_scheme_sid;
                $roo->link = '<a class="govuk-link" href="' . $roo_url . '">' . $roo->description . '</a>';
                $roo_table_url = "/rules_of_origin_schemes/rules_table.html?rules_of_origin_scheme_sid=" . $rules_of_origin_scheme_sid;
                $roo->table_link = '<a class="govuk-link" href="' . $roo_table_url . '">View rules</a>';

                array_push($temp, $roo);
            }
            $this->rules_of_origin_schemes = $temp;
        }
    }

    public function get_additional_code_types()
    {
        global $conn;
        $workbasket = new workbasket();
        $sql = "select act.additional_code_type_id, actd.description,
        act.validity_start_date, act.validity_end_date, act.application_code, act.status
        from additional_code_types act, additional_code_type_descriptions actd 
        where act.additional_code_type_id = actd.additional_code_type_id
        and act.validity_end_date is null
        order by 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $additional_code_type_id = $row['additional_code_type_id'];
                $description = $row['description'];
                $description = str_replace("/", " / ", $description);
                $description = str_replace("  ", " ", $description);
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $application_code = $row['application_code'];
                $additional_code_type = new additional_code_type;

                $additional_code_type->additional_code_type_id = $additional_code_type_id;
                $additional_code_type->description = $description;

                $additional_code_type->validity_start_date = $validity_start_date;
                $additional_code_type->validity_end_date = $validity_end_date;
                $additional_code_type->application_code = $application_code;
                $additional_code_type->id = $additional_code_type->additional_code_type_id;
                $additional_code_type->string = $additional_code_type->additional_code_type_id . " - " . $additional_code_type->description;
                $workbasket->status = $row['status'];
                $additional_code_type->status = status_image($workbasket->status);
                $url = "/additional_code_types/view.html?mode=view&additional_code_type_id=" . $additional_code_type->additional_code_type_id;
                $additional_code_type->description_url = '<a class="govuk-link" href="' . $url . '">' . $additional_code_type->description . '</a>';

                $url = "/additional_codes/?filter_additional_codes_additional_code_type_id=" . $additional_code_type->additional_code_type_id;
                $additional_code_type->additional_code_url = '<a class="govuk-link" href="' . $url . '">View additional codes</a>';


                array_push($temp, $additional_code_type);
            }
            $this->additional_code_types = $temp;
        }


        foreach ($this->additional_code_types as $additional_code_type) {
            if (!in_array($additional_code_type->additional_code_type_id, array('V', 'X'))) {
                $additional_code_type_id = $additional_code_type->additional_code_type_id;
                $sql = "select lpad(additional_code::text, 3, '0') as next_id
                from ( select generate_series (1, 999) as additional_code
                except select additional_code::int from additional_codes where additional_code_type_id = '" . $additional_code_type_id . "') s
                order by additional_code limit 1;
                ";
                $result = pg_query($conn, $sql);
                $temp = array();
                if ($result) {
                    while ($row = pg_fetch_array($result)) {
                        $additional_code_type->next_id = $row['next_id'];
                    }
                }
            } else {
                $additional_code_type->next_id = 999;
            }
        }
    }

    public function get_footnote_types()
    {
        global $conn;

        $sql = "with footnote_types_cte as
        (select ft.footnote_type_id, ftd.description, ft.validity_start_date,
        ft.validity_end_date, ft.application_code,
        case
        when application_code in ('1', '2') then 'Nomenclature-related footnote'
        when application_code in ('6', '7') then 'Measure-related footnote'
        end as application_code_description, ft.status, ft.workbasket_id
        from footnote_types ft, footnote_type_descriptions ftd
        where ft.footnote_type_id = ftd.footnote_type_id
        and application_code not in ('3', '4', '5', '8', '9')
        and ft.footnote_type_id not in ('01', '02', '03', 'MX') and validity_end_date is null order by 1)
        select * from footnote_types_cte order by application_code, footnote_type_id
        ";

        $result = pg_query($conn, $sql);
        $temp = array();
        $workbasket = new workbasket();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $footnote_type_id = $row['footnote_type_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $application_code = $row['application_code'];
                $application_code_description = $row['application_code_description'];
                $workbasket->status = $row['status'];
                $image = status_image($workbasket->status);
                $workbasket_id = $row['workbasket_id'];

                $footnote_type = new footnote_type;
                $footnote_type->footnote_type_id = $footnote_type_id;
                $footnote_type->description = $description;
                $footnote_type->validity_start_date = $validity_start_date;
                $footnote_type->validity_end_date = $validity_end_date;
                $footnote_type->application_code = $application_code;
                $footnote_type->application_code_plus_description = $application_code . " - " . $application_code_description;
                $footnote_type->status = $image; // . $workbasket->status;
                $footnote_type->workbasket_id = $workbasket_id;
                $footnote_type->id = $footnote_type->footnote_type_id;
                $footnote_type->optgroup = $application_code_description;
                $footnote_type->string = "<b>" . $footnote_type->footnote_type_id . "</b> - " . $footnote_type->description;

                //$url = "/footnote_types/create_edit.html?mode=update&footnote_type_id=" . $footnote_type->footnote_type_id;
                $url = "/footnote_types/view.html?mode=view&footnote_type_id=" . $footnote_type->footnote_type_id;
                $footnote_type->footnote_type_url = '<a class="govuk-link" href="' . $url . '">' . $footnote_type->description . '</a>';

                $url = "/footnotes/?filter_footnotes_footnote_type_id=" . $footnote_type->footnote_type_id;
                $footnote_type->footnote_url = '<a class="govuk-link" href="' . $url . '">View footnotes</a>';
                array_push($temp, $footnote_type);
            }
            $this->footnote_types = $temp;
        }


        foreach ($this->footnote_types as $footnote_type) {
            $footnote_type_id = $footnote_type->footnote_type_id;
            $sql = "select lpad(footnote_id::text, 3, '0') as next_id
            from ( select generate_series (1, 999) as footnote_id
            except select footnote_id::int from footnotes where footnote_type_id = '" . $footnote_type_id . "') s
            order by footnote_id limit 1;
            ";
            $result = pg_query($conn, $sql);
            $temp = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $footnote_type->next_id = $row['next_id'];
                }
            }
        }
    }

    public function get_reference_documents()
    {
        global $conn;

        $sql = "select unique_id, area_name, country_codes, agreement_title, agreement_date, agreement_version, date_created, last_updated
        from reference_documents order by unique_id;";

        $result = pg_query($conn, $sql);
        $this->reference_documents = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $reference_document = new reference_document;
                $reference_document->unique_id = $row['unique_id'];
                $reference_document->area_name = $row['area_name'];
                $reference_document->country_codes = $row['country_codes'];
                $reference_document->agreement_title = $row['agreement_title'];
                $reference_document->agreement_date = $row['agreement_date'];
                $reference_document->agreement_date_string = short_date($row['agreement_date']);
                $reference_document->agreement_version = $row['agreement_version'];
                $reference_document->date_created = $row['date_created'];
                $reference_document->last_updated = $row['last_updated'];
                $reference_document->download_link = '<li><a href="">Download</a></li>';
                $reference_document->edit_link = '<li><a class="govuk-link" href="./create_edit.html?mode=update&unique_id=' . $reference_document->unique_id . '">Edit</a></li>';
                $reference_document->regenerate_link = '<li><a href="">Regenerate</a></li>';
                $reference_document->action_column = '<ul class="measure_activity_action_list" style="margin-bottom:0.5em !important">';
                $reference_document->action_column .= $reference_document->download_link;
                $reference_document->action_column .= $reference_document->edit_link;
                $reference_document->action_column .= $reference_document->regenerate_link;
                $reference_document->action_column .= '</ul>';
                $reference_document->action_column .= '<p class="govuk-body-xs">Last updated: Mon Mar 09 2020 13:36:22</p>';

                array_push($this->reference_documents, $reference_document);
            }
        }
    }

    public function get_measurement_units($use_common = false)
    {
        global $conn;

        $sql = "SELECT mu.measurement_unit_code, description, validity_start_date, validity_end_date
        FROM measurement_units mu, measurement_unit_descriptions mud
        WHERE mu.measurement_unit_code = mud.measurement_unit_code ORDER BY 1";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measurement_unit_code = $row['measurement_unit_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measurement_unit = new measurement_unit;
                $measurement_unit->measurement_unit_code = $measurement_unit_code;
                $measurement_unit->description = $description;
                $measurement_unit->id = $measurement_unit_code;
                $measurement_unit->string = $measurement_unit->measurement_unit_code . ' - ' . $measurement_unit->description;
                $measurement_unit->validity_start_date = $validity_start_date;
                $measurement_unit->validity_end_date = $validity_end_date;

                if ($use_common == true) {
                    if (in_array($measurement_unit_code, $this->common_measurement_units)) {
                        array_push($temp, $measurement_unit);
                    }
                } else {
                    array_push($temp, $measurement_unit);
                }
            }
            $this->measurement_units = $temp;
        }
    }

    public function get_measurement_unit_qualifiers()
    {
        global $conn;
        $sql = "SELECT muq.measurement_unit_qualifier_code, description, validity_start_date, validity_end_date
        FROM measurement_unit_qualifiers muq, measurement_unit_qualifier_descriptions muqd
        WHERE muq.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code ORDER BY 1";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measurement_unit_qualifier = new measurement_unit_qualifier;

                $measurement_unit_qualifier->measurement_unit_qualifier_code = $measurement_unit_qualifier_code;
                $measurement_unit_qualifier->description = $description;
                $measurement_unit_qualifier->id = $measurement_unit_qualifier->measurement_unit_qualifier_code;
                $measurement_unit_qualifier->string = $measurement_unit_qualifier->measurement_unit_qualifier_code . ' - ' . $measurement_unit_qualifier->description = $description;
                $measurement_unit_qualifier->validity_start_date = $validity_start_date;
                $measurement_unit_qualifier->validity_end_date = $validity_end_date;

                array_push($temp, $measurement_unit_qualifier);
            }
            $this->measurement_unit_qualifiers = $temp;
        }
    }

    public function get_action_codes()
    {
        global $conn;
        $sql = "select ma.action_code, mad.description from measure_actions ma, measure_action_descriptions mad 
 where ma.action_code = mad.action_code and validity_end_date is null order by 1;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $action_code = $row['action_code'];
                $description = $row['description'];
                $measure_action_code = new measure_action_code;
                $measure_action_code->action_code = $action_code;
                $measure_action_code->description = $description;
                array_push($temp, $measure_action_code);
            }
            $this->action_codes = $temp;
        }
    }

    public function get_certificate_types()
    {
        global $conn;
        $workbasket = new workbasket();

        $sql = "select ct.certificate_type_code, ctd.description, ct.validity_start_date, ct.validity_end_date, ct.status
        from certificate_types ct, certificate_type_descriptions ctd 
        where ct.certificate_type_code = ctd.certificate_type_code and validity_end_date is null
        order by 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $certificate_type_code = $row['certificate_type_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $certificate_type = new certificate_type;
                $certificate_type->certificate_type_code = $certificate_type_code;
                $certificate_type->description = str_replace("/", " / ", $description);
                $certificate_type->id = $certificate_type_code;
                $certificate_type->string = "<b>" . $certificate_type_code . "</b> " . $certificate_type->description;
                $certificate_type->validity_start_date = $validity_start_date;
                $certificate_type->validity_end_date = $validity_end_date;
                $workbasket->status = $row['status'];
                $certificate_type->status = status_image($workbasket->status);
                //$url = "/certificate_types/create_edit.html?mode=update&certificate_type_code=" . $certificate_type_code;
                $url = "/certificate_types/view.html?mode=view&certificate_type_code=" . $certificate_type_code;
                $certificate_type->certificate_type_url = "<a class='govuk-link' href='" . $url . "'>" . $certificate_type->description . "</a>";

                $url = "/certificates/?filter_certificates_certificate_type_code=" . $certificate_type_code;
                $certificate_type->certificate_url = "<a class='govuk-link' href='" . $url . "'>View certificates</a>";

                array_push($temp, $certificate_type);
            }
            $this->certificate_types = $temp;
        }


        foreach ($this->certificate_types as $certificate_type) {
            $certificate_type_code = $certificate_type->certificate_type_code;
            $sql = "select lpad(certificate_code::text, 3, '0') as next_id
            from ( select generate_series (1, 999) as certificate_code
            except select certificate_code::int from certificates where certificate_type_code = '" . $certificate_type_code . "' and certificate_code < 'A') s
            order by certificate_code limit 1;
            ";
            $result = pg_query($conn, $sql);
            $temp = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $certificate_type->next_id = $row['next_id'];
                }
            }
        }
    }

    public function get_maximum_precisions()
    {
        $array = array(1, 2, 3, 4, 5);
        $this->maximum_precisions = $array;
    }

    public function get_critical_states()
    {
        $array = array("Y", "N");
        $this->critical_states = $array;
    }

    public function get_monetary_units()
    {
        $array = array("EUR");
        $this->monetary_units = $array;
    }

    function pre($data)
    {
        print '<pre>' . print_r($data, true) . '</pre>';
    }

    function get_single_value($sql)
    {
        global $conn;
        $result = pg_query($conn, $sql);
        if ($result) {
            $val = pg_fetch_result($result, 0, 0);
        }
        return ($val);
    }

    function get_next_quota_definition()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(quota_definition_sid) FROM quota_definitions");
        if ($s < $this->min_quota_definitions) {
            $s = $this->min_quota_definitions;
        }
        $s += 1;
        return ($s);
    }

    function get_next_quota_order_number()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(quota_order_number_sid) FROM quota_order_numbers");
        if ($s < $this->min_quota_order_numbers) {
            $s = $this->min_quota_order_numbers;
        }
        $s += 1;
        return ($s);
    }

    function get_next_geographical_area_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(geographical_area_description_period_sid) FROM geographical_area_description_periods");
        if ($s < $this->min_geographical_area_description_periods) {
            $s = $this->min_geographical_area_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_geographical_area()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(geographical_area_sid) FROM geographical_areas");
        if ($s < $this->min_geographical_areas) {
            $s = $this->min_geographical_areas;
        }
        $s += 1;
        return ($s);
    }

    function get_next_footnote_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(footnote_description_period_sid) FROM footnote_description_periods");
        if ($s < $this->min_footnote_description_periods) {
            $s = $this->min_footnote_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_additional_code_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(additional_code_description_period_sid) FROM additional_code_description_periods");
        if ($s < $this->min_additional_code_description_periods) {
            $s = $this->min_additional_code_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_additional_code()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(additional_code_sid) FROM additional_codes");
        if ($s < $this->min_additional_codes) {
            $s = $this->min_additional_codes;
        }
        $s += 1;
        return ($s);
    }

    function get_next_certificate_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(certificate_description_period_sid) FROM certificate_description_periods");
        if ($s < $this->min_certificate_description_periods) {
            $s = $this->min_certificate_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_goods_nomenclature_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(goods_nomenclature_description_period_sid) FROM goods_nomenclature_description_periods");
        if ($s < $this->min_goods_nomenclature_description_periods) {
            $s = $this->min_goods_nomenclature_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_monetary_exchange_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(monetary_exchange_period_sid) FROM monetary_exchange_periods");
        if ($s < $this->min_monetary_exchange_periods) {
            $s = $this->min_monetary_exchange_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_operation_date()
    {
        $date = date('Y-m-d H:i:s');
        return ($date);
    }

    function show_page_controls($show_paging = true, $dataset = null, $hide_export_link = null)
    {
        //pre ($dataset);
        $control_count = 7;
        if ($this->row_count == 0) {
            return;
        }
        $page_count = ceil($this->row_count / $this->page_size);
        echo ('<p class="govuk-body-s">Page ' . $this->page . ' of ' . $page_count . ' - showing ' . min($this->page_size, $this->row_count) . ' records of ' . $this->row_count . '. ');
        if ($dataset != null) {
            if ($hide_export_link == null) {
                echo ('<a class="govuk-link" href=""><img src="/assets/images/export.png" style="margin:0px 0.2em 0px 0.5em;top:3px;position:relative;" />Export data to CSV</a>');
            }
        }
        echo ('</p>');
        if ($show_paging) {
            echo ('<nav style="display:block"><ul class="pagination">');
            if ($page_count > 20) {
                echo ('<li><a class="pagination-link" href="./?p=1#results">First</a></li>');
                $min = $this->page - $control_count;
                $max = $this->page + $control_count;
                if ($min <= 1) {
                    $max = $max + (-1 * $min);
                    $min = 1;
                } else {
                    echo ('<li class="ellipsis"><span>...</span></li>');
                }
                if ($max >= $page_count) {
                    $min = $min - ($max - $page_count);
                    $max = $page_count - 2;
                }
                for ($i = $min; $i <= $max; $i++) {
                    if ($i + 1 == $this->page) {
                        echo ('<li><span>' . ($i + 1) . '</span></li>');
                    } else {
                        echo ('<li><a class="pagination-link" href="./?p=' . ($i + 1) . '#results">' . ($i + 1) . '</a></li>');
                    }
                }
                if ($this->page < $page_count - $control_count) {
                    echo ('<li class="ellipsis"><span>...</span></li>');
                }
                echo ('<li><a class="pagination-link" href="./?p=' . $page_count . '#results">Last</a></li>');
            } else {
                for ($i = 0; $i < $page_count; $i++) {
                    if ($i + 1 == $this->page) {
                        echo ('<li><span>' . ($i + 1) . '</span></li>');
                    } else {
                        echo ('<li><a class="pagination-link" href="./?p=' . ($i + 1) . '#results">' . ($i + 1) . '</a></li>');
                    }
                }
            }
            echo ('</ul></nav>');
        }
    }

    public function clear_filter_cookies()
    {
        $match = "filter_";
        $match2 = "workbaskets";
        //$match = "filter_" . $this->tariff_object . "_";
        foreach ($_COOKIE as $key => $value) {
            if (contains($match, $key)) {
                if (!contains($match2, $key)) {
                    setcookie($key, "", time() + (86400 * 30), "/");
                }
            }
        }
        /*
        $footnote = new footnote();
        $footnote->clear_cookies();
        */
    }

    public function get_filter_options()
    {
        /*
            This function is used to work out which criteria to add to the filter for displaying search result tables
            - the freetext search
            - the checkboxed filters
            - derived from cookie or post (form)
            - also the sort field and sort direction
        */

        // Get the sort order and sort field
        $config = $this->data[$this->tariff_object]["config"];
        $this->freetext_fields = $config["freetext_fields"];
        if (isset($_GET["s"])) {
            $raw = $_GET["s"];
            $array = explode("~", $raw);
            $sort_order = $array[0];
            $sort_field = $array[1];
            $this->sort_clause = " order by " . $sort_field . " " . $sort_order;
            $this->default_sort_fields = $config["default_sort_fields"];
            $this->default_sort_fields_array = explode("|", $this->default_sort_fields);
            foreach ($this->default_sort_fields_array as $field) {
                $this->sort_clause .= ", " . $field;
            }
        } else {
            $this->sort_clause = "";
            $this->sort_clause = " order by ";
            $this->default_sort_fields = $config["default_sort_fields"];
            $this->default_sort_fields_array = explode("|", $this->default_sort_fields);
            foreach ($this->default_sort_fields_array as $field) {
                $this->sort_clause .= $field . ", ";
            }
            $this->sort_clause = trim($this->sort_clause);
            $this->sort_clause = trim($this->sort_clause, ",");
        }

        if (!empty($_POST)) {
            $this->filter_options = $_POST;
            $this->clear_filter_cookies();
            foreach ($_POST as $key => $value) {
                $serialized = serialize($value);
                $match = "filter_" . $this->tariff_object . "_";
                if (contains($match, $key)) {
                    setcookie($key, $serialized, time() + (86400 * 30), "/");
                }
            }
            $control_name = "search_" . $this->tariff_object;
            if (isset($_POST["search"])) {
                $search_term = $_POST["search"];
                setcookie($control_name, $search_term, time() + (86400 * 30), "/");
            }
        } elseif ((!empty($_GET) && (strpos(serialize($_GET), "filter_") > -1))) {
            // This is a link taken from another object list
            $this->filter_options = array();
            foreach ($_GET as $key => $value) {
                $match = "filter_" . $this->tariff_object . "_";
                if (contains($match, $key)) {
                    $temp = array($value);
                    $serialized = serialize($temp);
                    $this->filter_options[$key] = $temp;
                    setcookie($key, $serialized, time() + (86400 * 30), "/");
                }
            }
        } else {
            $this->filter_options = $_COOKIE;
            $this->filter_options = array();
            foreach ($_COOKIE as $key => $value) {
                if (strpos($key, 'filter_' . $this->tariff_object) !== false) {
                    $unserialized_cookie = unserialize($value);
                    $this->filter_options[$key] = $unserialized_cookie;
                }
            }
        }
    }

    public function display_filters($freetext_fields, $datasets)
    {
        global $application;
        if ($freetext_fields != "") {
            new filter_control(
                $label = "Search",
                $control_name = "filter_" . $this->tariff_object . "_freetext",
                $dataset = Null,
                $truncate_at = "",
                $height = 0,
                $type = "input"
            );
        }
        $my_filter_content = $application->data[$this->tariff_object]["filters"];
        $i = 0;
        foreach ($my_filter_content as $item) {
            $label = $item["label"];
            if (!empty($item["height"])) {
                $height = $item["height"];
            } else {
                $height = 0;
            }
            $truncate_at = $item["truncate_at"];
            $form_value = $item["form_value"];
            $data_field = $item["data_field"];
            $data_type = $item["data_type"];
            $dataset = $datasets[$i];

            new filter_control(
                $label = $label,
                $control_name = $form_value,
                $dataset = $dataset,
                $truncate_at = $truncate_at,
                $height = $height,
                $type = "checkboxes"
            );
            $i++;
        }
    }
    private function get_filter_clause()
    {
        /*
            This function is used to calculate the filter clause that is then inserted into
            the relevant SQL statement to determine which results to show

            It does not work out the necessary values from POST / COOKIES, however: look in get_filter_options instead
        */

        global $application;
        $my_filter_content = $application->data[$this->tariff_object]["filters"];
        //pre ($this->filter_options);
        //pre ($_COOKIE);
        $filter_clause = "";

        foreach ($this->filter_options as $key => $values) {
            $value_count = 0;
            try {
                $value_count = @count($values);
            } catch (exception $e) {
                $value_count = 0;
            }

            $match = "filter_" .  $this->tariff_object . "_";
            if (contains($match, $key)) {
                if ($value_count > 0) {
                    $key = str_replace($match, "", $key);
                    if ($key == "freetext") {
                        if (trim($values) != "") {
                            if (strlen(trim($this->freetext_fields)) > 0) {
                                $values = strtolower($values);
                                $fields = explode("|", $this->freetext_fields);
                                $filter_clause .= " AND (";
                                $field_count = count($fields);
                                $field_index = 1;

                                foreach ($fields as $field) {
                                    $field = str_replace("+", "||", $field);
                                    $filter_clause .= " lower(" . $field  . ") LIKE '%" . trim($values) . "%' ";
                                    if ($field_index != $field_count) {
                                        $filter_clause .= " OR ";
                                    }
                                    $field_index++;
                                }
                                $filter_clause .= ") ";
                            }
                        }
                    } else {
                        foreach ($my_filter_content as $item) {
                            $form_value = $item["form_value"];
                            $data_field = $item["data_field"];
                            $data_type = $item["data_type"];
                            if ($data_type == "string") {
                                $delimiter = "'";
                            } else {
                                $delimiter = "";
                            }
                            if ($key == $form_value) {
                                $in_clause = "(";
                                //$value_count = count($values);
                                for ($i = 0; $i < $value_count; $i++) {
                                    $value = $values[$i];
                                    $in_clause .= $delimiter . $value . $delimiter;
                                    if ($i != ($value_count - 1)) {
                                        $in_clause .= ", ";
                                    }
                                }
                                $in_clause .= ")";
                                if ($value_count > 0) {
                                    $filter_clause .= " AND $data_field IN " . $in_clause;
                                }
                            }
                        }
                    }
                }
            }
        }
        return ($filter_clause);
    }
    public function get_commodity_code($commodity_code)
    {
        global $conn;
        $sql = "select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, description, chapter, node, leaf, significant_digits
        from ml.goods_nomenclature_export_new($1, '2019-01-01');";
        pg_prepare($conn, "get_commodity_code", $sql);
        $result = pg_execute($conn, "get_commodity_code", array($commodity_code));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $ret = new goods_nomenclature();
            $ret->description = $row[4];
            return ($ret);
        }
    }

    public function get_other_users()
    {
        global $conn;
        $users = array();
        $sql = "select user_id, name from users where user_id != $1 order by name";
        pg_prepare($conn, "get_other_users", $sql);
        $result = pg_execute($conn, "get_other_users", array($this->session->user_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                //$row = pg_fetch_row($result);
                $user = new reusable();
                $user->id = $row[0];
                $user->string = $row[1];
                array_push($users, $user);
            }
            return ($users);
        }
    }

    public function get_users()
    {
        global $conn;
        $this->users = array();
        $sql = "select u.name as user_name, u.user_id, u.user_login, u.email as user_email
        from users u order by 1;";
        pg_prepare($conn, "get_users", $sql);
        $result = pg_execute($conn, "get_users", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                //$row = pg_fetch_row($result);
                $user = new user();
                $user->user_name = $row[0];
                $user->user_id = $row[1];
                $user->user_login = $row[2];
                $user->user_email = $row[3];
                $user->id = $user->user_id;
                $user->string = $user->user_name;
                array_push($this->users, $user);
            }
        }
    }

    public function get_sections()
    {
        global $conn;
        $this->sections = array();
        $sql = "select lpad(position::text, 2, '0') as section, title from sections order by position";
        pg_prepare($conn, "get_sections", $sql);
        $result = pg_execute($conn, "get_sections", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $section = new section($row[0], $row[0], $row[1]);
                $section->id = $section->numeral;
                $section->string = "<b>" . $section->id . "</b> " . $section->title;
                array_push($this->sections, $section);
            }
        }
    }


    public function get_blocking_period_types()
    {
        global $conn;
        $this->blocking_period_types = array();
        $sql = "select blocking_period_type, description from blocking_period_types bpt order by 1";
        $stmt = "get_blocking_period_types_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $obj = new reusable();
                $obj->id = $row["blocking_period_type"];
                $obj->string = $obj->id . " - " . $row["description"];
                array_push($this->blocking_period_types, $obj);
            }
        }
    }

    public function get_current_quota_order_numbers()
    {
        global $conn;
        $this->current_quota_order_numbers = array();
        $sql = "select distinct quota_order_number_id from quota_order_numbers qon where validity_end_date is null order by 1;";
        $stmt = "get_current_quota_order_numbers" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $obj = new reusable();
                $obj->id = $row["quota_order_number_id"];
                array_push($this->current_quota_order_numbers, $obj);
            }
        }
    }


    public function get_my_workbaskets_or_new()
    {
        //prend ($_SESSION);
        global $conn;
        $offset = ($this->page - 1) * $this->page_size;
        $this->workbaskets = array();
        $sql = "select -1 as workbasket_id, '<b>New workbasket</b>' as title, '1970-01-01' as created_at
        union 
        select workbasket_id, title, created_at
        from workbaskets
        where status = 'In progress'
        and user_id = $1
        order by created_at desc;";

        // pre ($sql);

        $stmt = "get_my_workbaskets_or_new" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $workbaskets = array();
        $result = pg_execute($conn, $stmt, array($this->session->user_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $obj = new reusable();
                $obj->id = $row["workbasket_id"];
                $obj->string = $row["title"];
                array_push($workbaskets, $obj);
            }
        }
        return ($workbaskets);
    }

    public function get_my_workbaskets()
    {
        global $conn;
        $offset = ($this->page - 1) * $this->page_size;
        $this->workbaskets = array();
        $sql = "select u.name as user_name, u.user_id, u.user_login, u.email as user_email,
        w.title, w.reason, w.type, w.status, w.created_at, w.updated_at, w.workbasket_id,
        count(*) OVER() AS full_count
        from workbaskets w, users  u
        where w.user_id = u.user_id and w.user_id = '" . $this->session->user_id . "' order by w.created_at desc";
        //prend ($sql);
        pg_prepare($conn, "get_my_workbaskets", $sql);
        $result = pg_execute($conn, "get_my_workbaskets", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $workbasket = new workbasket();
                $workbasket->user_name = $row[0];
                $workbasket->user_id = $row[1];
                $workbasket->user_login = $row[2];
                $workbasket->user_email = $row[3];
                $workbasket->title = $row[4];
                $workbasket->reason = $row[5];
                $workbasket->type = $row[6];
                $workbasket->status = $row[7];
                $workbasket->created_at = string_to_time($row[8]);
                $workbasket->updated_at = string_to_time($row[9]);
                $workbasket->workbasket_id = $row[10];
                array_push($this->workbaskets, $workbasket);
            }
            return ($this->workbaskets);
        }
    }

    public function get_workbaskets()
    {
        global $conn;
        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with cte as (select u.name as user_name, u.user_login, u.user_id, u.email as user_email,
        w.title, w.reason, w.status, w.created_at, w.updated_at, w.workbasket_id, ws.sequence_id, 
        case 
        when u.user_id = " . $this->session->user_id . " then 'own'
        else 'other'
        end as ownership        
        from workbaskets w, users u, workbasket_statuses ws
        where w.user_id = u.user_id 
        and w.status = ws.status )
        select *, count(*) OVER() AS full_count from cte where 1 > 0 "; // order by w.created_at asc";

        //pre($sql);
        $sql .= $filter_clause;
        $sql .= " " . $this->sort_clause;
        $this->sort_clause = "order by created_at asc";
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $this->workbaskets = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $wb = new workbasket;
                $wb->workbasket_id = $row['workbasket_id'];
                $wb->title = $row['title'];
                $wb->reason = $row['reason'];
                $wb->title_link = "<a class='govuk-link' href='/workbaskets/view.html?workbasket_id=" . $wb->workbasket_id . "'>" . $row['title'] . "</a>";
                $wb->user_id = $row['user_id'];
                $wb->user_name = $row['user_name'];
                $wb->created_at = $row['created_at'];
                $wb->updated_at = $row['updated_at'];

                $wb->created_at_string = short_date_time($wb->created_at);
                $wb->updated_at_string = short_date($wb->updated_at);
                $wb->status = $row['status'];
                $wb->actions = "<ul class='measure_activity_action_list'>";
                $wb->actions .= $wb->show_workbasket_icon_view();
                $wb->actions .= $wb->show_workbasket_icon_open_close();
                $wb->actions .= $wb->show_workbasket_icon_withdraw();
                $wb->actions .= $wb->show_workbasket_icon_submit();
                $wb->actions .= $wb->show_workbasket_icon_delete();
                $wb->actions .= $wb->show_workbasket_icon_archive();
                $wb->actions .= "</ul>";
                $status_text = $wb->status;

                if (isset($this->session->workbasket->workbasket_id)) {
                    $test = $this->session->workbasket->workbasket_id;
                } else {
                    $test = -1;
                }

                if ($wb->workbasket_id == $test) {
                    $status_text .= " (active)";
                    $wb->row_class = "b";
                } else {
                    $wb->row_class = "";
                }
                $wb->status_image = status_image($row['status']) . "<span>" . $status_text . "</span>";
                array_push($this->workbaskets, $wb);
            }
        }
    }

    public function get_workbasket_count()
    {
        global $conn;
        $sql = "SELECT count(*) from workbaskets;";
        $result = pg_query($conn, $sql);
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $count = $row[0];
        } else {
            $count = 0;
        }
        return ($count);
    }

    public function get_workbasket_statuses()
    {
        global $conn;

        $this->workbasket_statuses = array();
        $sql = "SELECT status from workbasket_statuses where workbasket_scope = true order by sequence_id;";
        $result = pg_query($conn, $sql);
        $this->workbasket_statuses = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $wb_status = new reusable;
                $wb_status->id = $row['status'];
                $wb_status->string = $row['status'];
                array_push($this->workbasket_statuses, $wb_status);
            }
        }
    }

    public function get_regulation_sources()
    {
        $this->regulation_sources = array();
        array_push($this->regulation_sources, new simple_object("uksi", "UK regulation", "", ""));
        array_push($this->regulation_sources, new simple_object("eur", "Adopted EU regulation", "", ""));
        array_push($this->regulation_sources, new simple_object("eud", "Adopted EU decision", "", ""));
        array_push($this->regulation_sources, new simple_object("eudr", "Adopted EU directive", "", ""));
    }

    public function get_workbasket_ownerships()
    {
        $this->workbasket_ownerships = array();
        array_push($this->workbasket_ownerships, new simple_object("own", "Owned by me", "", ""));
        array_push($this->workbasket_ownerships, new simple_object("other", "Owned by others", "", ""));
    }


    public function get_yes_no()
    {
        $yes_no = array();
        array_push($yes_no, new simple_object("Yes", "Yes", "Yes", ""));
        array_push($yes_no, new simple_object("No", "No", "No", ""));
        return ($yes_no);
    }

    public function get_yes_no_continue()
    {
        $yes_no_continue = array();
        array_push($yes_no_continue, new simple_object("Yes", "Yes, use this workbasket", "Yes", "Any changes you make will be added to the currently open workbasket."));
        array_push($yes_no_continue, new simple_object("No", "No, select another workbasket", "No", "Your current workbasket will be closed and you will be able to open or create anothre workbasket."));
        return ($yes_no_continue);
    }


    public function get_create_measures_yes_no()
    {
        $this->create_measures_yes_no = array();
        array_push($this->create_measures_yes_no, new simple_object("Yes", "Yes - create measures", "Yes", "By selecting this option, you will be asked to confirm the commodity codes, measure type and origin(s) to for the measures that will be created."));
        array_push($this->create_measures_yes_no, new simple_object("No", "No - I will create measures separately", "No", "If you select this option, just the quota definition will be created. You will need to manually create the measures separately."));
        //return ($create_measures_yes_no);
    }


    public function get_start_dates()
    {
        $this->start_dates = array();
        $y = intval(date("Y"));
        for ($i = $y + 1; $i > ($y - 9); $i--) {
            $i = strval($i);
            array_push($this->start_dates, new simple_object($i, $i, $i, ""));
        }
    }


    public function search_commodities()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        /*
        $sql = "select distinct on (gn.goods_nomenclature_item_id, gn.producline_suffix)
        gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description, gn.goods_nomenclature_sid, gn.validity_start_date, gn.validity_end_date,
        cs.section_id, count(gn.*) over() as full_count
        from goods_nomenclatures gn, goods_nomenclature_descriptions gnd,
        goods_nomenclature_description_periods gndp, goods_nomenclatures gn2, chapters_sections cs
        where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
        and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and rpad(left(gn.goods_nomenclature_item_id, 2), 10, '0') = gn2.goods_nomenclature_item_id
        and gn2.producline_suffix = '80' and cs.goods_nomenclature_sid = gn2.goods_nomenclature_sid 
        and gn.validity_start_date < '2019-12-31' and (gn.validity_end_date >  '2019-12-31' or gn.validity_end_date is null) ";
        */

        $sql = "select goods_nomenclature_item_id, producline_suffix, description, goods_nomenclature_sid,
        validity_start_date, validity_end_date, number_indents, significant_digits, count (gn.*) over() as full_count
        from ml.ml_commodity_codes gn
        where (validity_end_date > '2012-12-31' or validity_end_date is null) ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];

                $goods_nomenclature = new goods_nomenclature;
                $goods_nomenclature->goods_nomenclature_sid = $row['goods_nomenclature_sid'];
                $goods_nomenclature->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                $goods_nomenclature->productline_suffix = $row['producline_suffix'];
                $edit_url = 'view.html?goods_nomenclature_item_id=' . $goods_nomenclature->goods_nomenclature_item_id . '&productline_suffix=' . $goods_nomenclature->productline_suffix . '&goods_nomenclature_sid=' . $goods_nomenclature->goods_nomenclature_sid;
                $goods_nomenclature->goods_nomenclature_item_id_formatted = format_goods_nomenclature_item_id($goods_nomenclature->goods_nomenclature_item_id);
                $goods_nomenclature->goods_nomenclature_item_link = '<a class="nodecorate" href="' . $edit_url . '">' . $goods_nomenclature->goods_nomenclature_item_id_formatted . '</a>';
                $goods_nomenclature->description = $row['description'];
                $goods_nomenclature->number_indents = $row['number_indents'];
                $goods_nomenclature->number_indents = $row['significant_digits'];
                $goods_nomenclature->description_formatted = $goods_nomenclature->format_description();
                $goods_nomenclature->validity_start_date = short_date($row['validity_start_date']);
                $goods_nomenclature->validity_end_date = short_date($row['validity_end_date']);
                $goods_nomenclature->actions = '<a class="govuk-link" href="' . $edit_url . '"><img src="/assets/images/edit.png" /></a>';


                array_push($temp, $goods_nomenclature);
            }
            $this->goods_nomenclature_search_results = $temp;
        }
    }



    public function get_quota_measures()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with temp as (
            select distinct m.ordernumber, m.measure_type_id, m.geographical_area_id,
            ga.description as geographical_area_description, mtd.description as measure_type_description, qon.quota_category, qon.description,
            CASE
            WHEN LEFT(m.ordernumber, 3) = '094' THEN 'Licensed'
            ELSE 'FCFS'
            END As mechanism, qon.validity_start_date
            from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
            left outer join quota_order_numbers qon on qon.quota_order_number_id = m.ordernumber
            where m.measure_type_id in ('122', '123', '143', '146')
            and m.geographical_area_sid = ga.geographical_area_sid 
            and m.measure_type_id = mtd.measure_type_id
            and qon.quota_category is not null
            and (qon.validity_start_date is not null or LEFT(m.ordernumber, 3) = '094')
            and m.validity_start_date >= '2010-01-01'
            )
            select temp.*, count(*) over() as full_count from temp where 1 > 0 ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];

                $qon = new quota_order_number;
                //$qon->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                //$qon->goods_nomenclature_item_id_formatted = format_goods_nomenclature_item_id($qon->goods_nomenclature_item_id);

                $qon->ordernumber = $row['ordernumber'];
                $qon->measure_type_id = $row['measure_type_id'];
                //$qon->regulation_id = $row['regulation_id'];
                $qon->quota_category = $row['quota_category'];
                $qon->mechanism = $row['mechanism'];
                $qon->description = $row['description'];
                $qon->geographical_area_id = $row['geographical_area_id'];
                //$qon->validity_start_date = short_date($row['validity_start_date']);
                //$qon->validity_end_date = short_date($row['validity_end_date']);
                $qon->actions = '<a class="govuk-link" href="">View / edit</a>';

                array_push($temp, $qon);
            }
            $this->quota_measures = $temp;
        }
    }

    public function get_commodity_tiers()
    {
        $this->commodity_tiers = array();
        array_push($this->commodity_tiers, new simple_object("2", "HS chapter (HS2)", "", ""));
        array_push($this->commodity_tiers, new simple_object("4", "HS heading (HS4)", "", ""));
        array_push($this->commodity_tiers, new simple_object("6", "HS heading (HS6)", "", ""));
        array_push($this->commodity_tiers, new simple_object("8", "HS heading (CN8)", "", ""));
        array_push($this->commodity_tiers, new simple_object("10", "HS heading (CN10)", "", ""));
    }

    public function get_quota_mechanisms()
    {
        $this->quota_mechanisms = array();
        array_push($this->quota_mechanisms, new simple_object("FCFS", "First Come First Served", "", ""));
        array_push($this->quota_mechanisms, new simple_object("licensed", "Licensed", "", ""));
    }

    public function get_quota_categories()
    {
        $this->quota_categories = array();
        array_push($this->quota_categories, new simple_object("WTO", "WTO quota", "", ""));
        array_push($this->quota_categories, new simple_object("ATQ", "ATQ (Autonomous tariff rate quota)", "", ""));
        array_push($this->quota_categories, new simple_object("PRF", "Preferential quota", "", ""));
        array_push($this->quota_categories, new simple_object("SAF", "Safeguard quota", "", ""));
    }

    public function get_quota_measure_types()
    {
        $this->quota_measure_types = array();
        array_push($this->quota_measure_types, new simple_object("122", "Non-preferential quota", "", ""));
        array_push($this->quota_measure_types, new simple_object("123", "Non-preferential quota under authorised use", "", ""));
        array_push($this->quota_measure_types, new simple_object("143", "Preferential quota", "", ""));
        array_push($this->quota_measure_types, new simple_object("146", "Preferential quota under authorised use", "", ""));
    }

    public function get_quota_period_types()
    {
        $this->quota_period_types = array();
        array_push($this->quota_period_types, new simple_object("Annual", "Annual - one period per year, lasting all year", "", ""));
        array_push($this->quota_period_types, new simple_object("Bi-annual", "Bi-annual - two periods per year of 6 months each", "", ""));
        array_push($this->quota_period_types, new simple_object("Quarterly", "Quarterly - four periods per year of 3 months each", "", ""));
        array_push($this->quota_period_types, new simple_object("Custom", "Custom period", "", ""));
    }

    public function get_quota_precisions()
    {
        $this->quota_precisions = array();
        array_push($this->quota_precisions, new simple_object("3", "3", "", ""));
        array_push($this->quota_precisions, new simple_object("2", "2", "", ""));
        array_push($this->quota_precisions, new simple_object("1", "1", "", ""));
        array_push($this->quota_precisions, new simple_object("0", "0", "", ""));
    }

    public function get_quota_origin_quota_options()
    {
        $this->quota_origin_quota_options = array();
        array_push($this->quota_origin_quota_options, new simple_object("0", "No", "", ""));
        array_push($this->quota_origin_quota_options, new simple_object("1", "Yes", "", ""));
    }

    public function get_quota_introductory_period_options()
    {
        $this->quota_introductory_period_options = array();
        array_push($this->quota_introductory_period_options, new simple_object("0", "No introductory periods", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("1", "1 introductory period", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("2", "2 introductory periods", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("3", "3 introductory periods", "", ""));
    }

    public function get_measure_type_combinations()
    {
        $this->measure_type_combinations = array();
        array_push($this->measure_type_combinations, new simple_object("0", "Only 1 measure at export and 1 at import from the series", "One"));
        array_push($this->measure_type_combinations, new simple_object("1", "All measure types in the series to be considered", "All"));
    }

    public function get_next_available_quota_order_number($quota_mechanism, $quota_category)
    {
        global $conn;
        /*
            -- Licensed: 094
            -- FCFS WTO 090, 091, 092, 093, 095
            -- FCFS ATQ 096, 097
            -- FCFS PRF 098, 099
        */

        if ($quota_mechanism == 'licensed') {
            $sql = "select lpad(ordernumber::text, 6, '0') as quota_order_number_id
            from (
                select generate_series (94001, 94999) as ordernumber
                except select distinct ordernumber::int from measures where left(ordernumber, 3) = '094'
            ) subquery
            order by ordernumber limit 1;
            ";
        } else {
            if ($quota_category == "WTO") {
                $sql = "with cte as (
                    select generate_series (90000, 90999) as quota_order_number_id
                    union select generate_series (91000, 91999) as quota_order_number_id
                    union select generate_series (92000, 92999) as quota_order_number_id
                    union select generate_series (93000, 93999) as quota_order_number_id
                    union select generate_series (94000, 94999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 100;
                ";
            } elseif ($quota_category == "ATQ") {
                $sql = "with cte as (
                    select generate_series (96000, 96999) as quota_order_number_id
                    union select generate_series (97000, 97999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 1;
                ";
            } elseif ($quota_category == "PRF") {
                $sql = "with cte as (
                    select generate_series (98000, 98999) as quota_order_number_id
                    union select generate_series (99000, 99999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 1;
                ";
            }
        }
        pg_prepare($conn, "get_next_quota_order_number", $sql);
        $result = pg_execute($conn, "get_next_quota_order_number", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $quota_order_number_id = $row[0];
            return ($quota_order_number_id);
        }
        return ($quota_order_number_id);
    }



    public function get_measurement_combinations()
    {
        global $conn;
        $this->measurement_combinations = array();
        $sql = "select m.measurement_unit_code, m.measurement_unit_qualifier_code, description
        from measurements m, measurement_unit_qualifier_descriptions muqcd
        where m.measurement_unit_qualifier_code = muqcd.measurement_unit_qualifier_code
        and m.validity_end_date is null
        order by m.measurement_unit_code, m.measurement_unit_qualifier_code;";
        pg_prepare($conn, "get_measurement_combinations", $sql);
        $result = pg_execute($conn, "get_measurement_combinations", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $combination = new measurement;
                $combination->measurement_unit_code = $row[0];
                $combination->measurement_unit_qualifier_code = $row[1];
                $combination->description = $row[2];
                array_push($this->measurement_combinations, $combination);
            }
        }
    }

    public function get_measures()
    {
        $this->page_size = 10;
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
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
                $clause .= " and validity_start_date >= '" . $validity_start_date . "' ";
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

        //prend ($sql);


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

    public function get_conditional_duty_application_options()
    {
        $this->conditional_duty_application_options = array();
        array_push($this->conditional_duty_application_options, new simple_object("0", "Common to all permutations", "Common to all permutations", "The duty specified below will be common to all specified permutations."));
        array_push($this->conditional_duty_application_options, new simple_object("1", "Different per permutation", "Different per permutation", "Duties will vary depending on the permutation specified on the previous screen."));
    }


    public function get_quotas()
    {
        $this->page_size = 100;
        global $conn;
        $sql = "with cte as (
            select q.quota_order_number_id, q.quota_order_number_sid, q.origin_quota, 'FCFS' as mechanism,
            q.quota_category, q.validity_start_date, q.validity_end_date, q.description,
            string_agg(distinct qono.geographical_area_id, ', ' order by qono.geographical_area_id) as geographical_area_ids
            from quota_order_numbers q left outer join quota_order_number_origins qono on q.quota_order_number_sid = qono.quota_order_number_sid 
            where 1 > 0
            PLACEHOLDER_GEOGRAPHY1
            group by q.quota_order_number_id, q.quota_order_number_sid, q.origin_quota,
q.quota_category, q.validity_start_date, q.validity_end_date, q.description

            
            union 
            
            select q.quota_order_number_id, q.quota_order_number_sid, q.origin_quota, 'Licensed' as mechanism,
            q.quota_category, q.validity_start_date, q.validity_end_date, q.description,
            string_agg(distinct m.geographical_area_id, ', ' order by m.geographical_area_id) as geographical_area_ids
            from licensed_quotas q left outer join measures m on q.quota_order_number_id = m.ordernumber 
            where 1 > 0 
            PLACEHOLDER_GEOGRAPHY2
            group by q.quota_order_number_id, q.quota_order_number_sid, q.origin_quota,
q.quota_category, q.validity_start_date, q.validity_end_date, q.description
            )
            select *, count(*) OVER() AS full_count from cte where 1 > 0 PLACEHOLDER ";

        $clause = "";

        // Get quota order number clause
        $quota_order_number_id_operator = get_formvar("quota_order_number_id_operator");
        $quota_order_number_id = get_formvar("quota_order_number_id");
        if (strlen($quota_order_number_id) > 2) {
            if ($quota_order_number_id_operator == "starts_with") {
                $clause .= " and quota_order_number_id like '" . $quota_order_number_id . "%' ";
            } elseif ($quota_order_number_id_operator == "is_one_of") {
                $quota_order_number_id = standardise_form_string($quota_order_number_id);
                $quota_order_number_id_clause = "";
                $quota_order_numbers = explode(",", $quota_order_number_id);
                $count = count($quota_order_numbers);
                $index = 0;
                $quota_order_number_id_clause .= "and quota_order_number_id in (";
                foreach ($quota_order_numbers as $quota_order_number) {
                    $quota_order_number_id_clause .= "'" . $quota_order_number . "'";
                    $index += 1;
                    if ($index < $count) {
                        $quota_order_number_id_clause .= ", ";
                    }
                }
                $quota_order_number_id_clause .= ")";
                $clause .= $quota_order_number_id_clause;
            }
        }

        //$sql = str_replace("PLACEHOLDER1", $clause, $sql);

        // Get administration mechanism
        $administration_mechanisms = get_formvar("administration_mechanism");
        if (is_array($administration_mechanisms)) {
            if (count($administration_mechanisms) == 1) {
                $administration_mechanism = $administration_mechanisms[0];
                $administration_mechanism_clause = " and mechanism = '" . $administration_mechanism . "' ";
                $clause .= $administration_mechanism_clause;
            }
        }

        // Get category
        $quota_categories = get_formvar("quota_category");
        $quota_category_clause = "";
        if (is_array($quota_categories)) {
            if ((count($quota_categories) > 0) and (count($quota_categories) < 4)) {
                $quota_category_clause = " and quota_category in (PLACEHOLDER_CATEGORY) ";
                $placeholder = "";
                foreach ($quota_categories as $quota_category) {
                    $placeholder .= "'" . $quota_category . "', ";
                }
                $placeholder = trim($placeholder);
                $placeholder = trim($placeholder, ",");
                $quota_category_clause = str_replace("PLACEHOLDER_CATEGORY", $placeholder, $quota_category_clause);

                $clause .= $quota_category_clause;
            }
        }

        // Get description clause
        $description = get_formvar("description");
        if ($description != "") {
            $clause .= " and lower(description) like '%" . strtolower($description) . "%' ";
        }

        // Get origin clause
        $geo_clause1 = "";
        $geo_clause2 = "";
        $origins = get_formvar("origin");
        if (strlen($origins) >= 2) {
            $origins = strtoupper(standardise_form_string($origins));
            $origin_clause = "";
            $origin_list = explode(",", $origins);
            $count = count($origin_list);
            $index = 0;
            $origin_clause .= "and qono.geographical_area_id in (";
            foreach ($origin_list as $origin) {
                $origin_clause .= "'" . $origin . "'";
                $index += 1;
                if ($index < $count) {
                    $origin_clause .= ", ";
                }
            }
            $origin_clause .= ")";
            $geo_clause1 = $origin_clause;
            $geo_clause2 = str_replace("qono", "m", $origin_clause);
        }

        // Get origin quota
        $origin_quotas = get_formvar("origin_quota");
        if (is_array($origin_quotas)) {
            if (count($origin_quotas) == 1) {
                $origin_quota = $origin_quotas[0];
                if ($origin_quota == "yes") {
                    $origin_quota_clause = " and origin_quota = true ";
                } else {
                    $origin_quota_clause = " and origin_quota = false ";
                }
                $clause .= $origin_quota_clause;
            }
        }

        $sql = str_replace("PLACEHOLDER_GEOGRAPHY1", $geo_clause1, $sql);
        $sql = str_replace("PLACEHOLDER_GEOGRAPHY2", $geo_clause2, $sql);
        $sql = str_replace("PLACEHOLDER", $clause, $sql);



        $offset = ($this->page - 1) * $this->page_size;
        //$this->sort_clause = " order by m.validity_start_date desc, m.goods_nomenclature_item_id";
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";
        //pre ($sql);

        // Get the measures
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $this->row_count = $row['full_count'];
                    $quota_order_number = new quota_order_number;
                    $quota_order_number->quota_order_number_id = $row['quota_order_number_id'];
                    $quota_order_number->quota_order_number_sid = $row['quota_order_number_sid'];
                    $quota_order_number->validity_start_date = short_date($row['validity_start_date']);
                    $quota_order_number->validity_end_date = short_date($row['validity_end_date']);
                    $quota_order_number->origin_quota = yn4($row['origin_quota']);
                    $quota_order_number->mechanism = $row['mechanism'];
                    $quota_order_number->description = $row['description'];
                    $quota_order_number->quota_category = $row['quota_category'];
                    $quota_order_number->geographical_area_ids = $row['geographical_area_ids'];
                    $quota_order_number->status = "In progress"; //$row['status'];
                    $quota_order_number->active_state = "active"; //$row['active_state'];

                    array_push($temp, $quota_order_number);
                }
                $this->quotas = $temp;
            }
        }
    }
    /*
    function get_minimum_sids() {}


    def get_minimum_sids(self):
        with open(self.CONFIG_FILE, 'r') as f:
            my_dict = json.load(f)

            min_list = my_dict['minimum_sids'][self.DBASE]

            self.last_additional_code_description_period_sid = self.larger(self.get_scalar("SELECT MAX(additional_code_description_period_sid) FROM additional_code_description_periods_oplog;"), min_list['additional.code.description.periods']) + 1
            self.last_additional_code_sid = self.larger(self.get_scalar("SELECT MAX(additional_code_sid) FROM additional_codes_oplog;"), min_list['additional.codes']) + 1

            self.last_certificate_description_period_sid = self.larger(self.get_scalar("SELECT MAX(certificate_description_period_sid) FROM certificate_description_periods_oplog;"), min_list['certificate.description.periods']) + 1
            self.last_footnote_description_period_sid = self.larger(self.get_scalar("SELECT MAX(footnote_description_period_sid) FROM footnote_description_periods_oplog;"), min_list['footnote.description.periods']) + 1
            self.last_geographical_area_description_period_sid = self.larger(self.get_scalar("SELECT MAX(geographical_area_description_period_sid) FROM geographical_area_description_periods_oplog;"), min_list['geographical.area.description.periods']) + 1
            self.last_geographical_area_sid = self.larger(self.get_scalar("SELECT MAX(geographical_area_sid) FROM geographical_areas_oplog;"), min_list['geographical.areas']) + 1

            self.last_goods_nomenclature_sid = self.larger(self.get_scalar("SELECT MAX(goods_nomenclature_sid) FROM goods_nomenclatures_oplog;"), min_list['goods.nomenclature']) + 1
            self.last_goods_nomenclature_indent_sid = self.larger(self.get_scalar("SELECT MAX(goods_nomenclature_indent_sid) FROM goods_nomenclature_indents_oplog;"), min_list['goods.nomenclature.indents']) + 1
            self.last_goods_nomenclature_description_period_sid = self.larger(self.get_scalar("SELECT MAX(goods_nomenclature_description_period_sid) FROM goods_nomenclature_description_periods_oplog;"), min_list['goods.nomenclature.description.periods']) + 1

            self.last_measure_sid = self.larger(self.get_scalar("SELECT MAX(measure_sid) FROM measures_oplog;"), min_list['measures']) + 1
            self.last_measure_condition_sid = self.larger(self.get_scalar("SELECT MAX(measure_condition_sid) FROM measure_conditions_oplog"), min_list['measure.conditions']) + 1

            self.last_quota_order_number_sid = self.larger(self.get_scalar("SELECT MAX(quota_order_number_sid) FROM quota_order_numbers_oplog"), min_list['quota.order.numbers']) + 1
            self.last_quota_order_number_origin_sid = self.larger(self.get_scalar("SELECT MAX(quota_order_number_origin_sid) FROM quota_order_number_origins_oplog"), min_list['quota.order.number.origins']) + 1
            self.last_quota_definition_sid = self.larger(self.get_scalar("SELECT MAX(quota_definition_sid) FROM quota_definitions_oplog"), min_list['quota.definitions']) + 1
            self.last_quota_suspension_period_sid = self.larger(self.get_scalar("SELECT MAX(quota_suspension_period_sid) FROM quota_suspension_periods_oplog"), min_list['quota.suspension.periods']) + 1
            self.last_quota_blocking_period_sid = self.larger(self.get_scalar("SELECT MAX(quota_blocking_period_sid) FROM quota_blocking_periods_oplog"), min_list['quota.blocking.periods']) + 1
*/
}
