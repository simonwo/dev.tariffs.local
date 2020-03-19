<?php
class workbasket
{
    public $workbasket_id = "";
    public $title = "";
    public $reason = "";
    public $type = "";
    public $status = "";
    public $user_id = "";
    public $user_name = "";
    public $date_created = "";
    public $date_last_updated = "";

    function populate()
    {
        global $conn;

        // Get the basic details
        $sql = "select title, reason, u.user_id, status, w.created_at, w.updated_at,
        last_status_change_at, last_update_by_id, u.name
        from workbaskets w, users u
        where w.user_id = u.user_id
        and w.workbasket_id = $1;";
        $stmt = "populate_workbasket_" . $this->workbasket_id;
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id
        ));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->title = $row[0];
            $this->reason = $row[1];
            $this->user_id = $row[2];
            $this->status = $row[3];
            $this->created_at = $row[4];
            $this->updated_at = $row[5];
            $this->last_status_change = $row[6];
            $this->last_updated_by = $row[7];
            $this->user_name = $row[8];
        }

        // Get the count of items
        $sql = "select status, count(*) as status_count
        from workbasket_items
        where workbasket_id = $1
        group by status;";
        $stmt = "populate_workbasket_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id
        ));

        $this->activity_count = 0;
        if ($result) {
            $this->counts_by_status = array();
            $row_count = pg_num_rows($result);
            if ($row_count > 0) {
                while ($row = pg_fetch_array($result)) {
                    $item = new reusable();
                    $item->status =  $row["status"];
                    $item->status_count =  $row["status_count"];
                    $this->activity_count += $item->status_count;
                    array_push($this->counts_by_status, $item);
                }
            }
        }

        // get the history of this workbasket
        $sql = "with cte as (
            select event_type, description, u.name as user_name, we.created_at
            from workbasket_events we, users u
            where we.user_id = u.user_id 
            and workbasket_id = $1
            union
            select event_type, description, u.name as user_name, wie.created_at 
            from workbasket_item_events wie, users u
            where wie.user_id = u.user_id 
            and workbasket_id = $1
            )
            select * from cte order by created_at;";
        $stmt = "get_events_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id
        ));
        $this->history = array();

        if ($result) {
            $row_count = pg_num_rows($result);
            if ($row_count > 0) {
                while ($row = pg_fetch_array($result)) {
                    $event = new reusable();
                    $event->event_type = $row["event_type"];
                    $event->description = format_json_key_value_pairs($row["description"]);
                    $event->user_name = $row["user_name"];
                    $event->created_at = short_date_time($row["created_at"]);
                    array_push($this->history, $event);
                }
            }
        }
    }

    public function show_section($object_type, $result)
    {
        global $application;
        $control_id = "accordion-with-summary-sections-" . underscore($object_type);
        $row_count = pg_num_rows($result);
        if ($row_count == 0) {
            return;
        }
        $field_count = pg_num_fields($result) - 6;
        //h1("Field count is " . $field_count);
        switch ($field_count) {
                // These should add up to 90, and the status, whjich is always last needs to be 5% always
            case 6:
                $widths = [20, 10, 10, 10, 32, 10];
                break;
            case 7:
                // footnotes & regulations
                $widths = [10, 10, 32, 10, 10, 10, 10];
                break;
            default:
                $cell_width = floor(100 / $field_count);
                $widths = array();
                for ($i = 0; $i < $cell_width; $i++) {
                    array_push($widths, $cell_width);
                }
        }
?>
        <!-- Start accordion section - <?= $object_type ?> //-->
        <div class="govuk-accordion__section ">
            <div class="govuk-accordion__section-header">
                <h2 class="govuk-accordion__section-heading">
                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-<?= $control_id ?>">
                        <?= ucfirst($object_type) ?> (<?= $row_count ?>)
                    </span>
                </h2>
            </div>
            <div id="accordion-with-summary-sections-content-<?= $control_id ?>" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-<?= $control_id ?>">
                <table class="govuk-table">
                    <thead class="govuk-table__head">
                        <tr class="govuk-table__row">
                            <?php
                            for ($i = 0; $i < $field_count; $i++) {
                                $field = pg_field_name($result, $i);
                                if ($field == "status") {
                                    $align = "c";
                                } else {
                                    $align = "";
                                }
                                echo ('<th width="' . $widths[$i] . '%" scope="col" class="govuk-table__header ' . $align . '">' . format_field_name($field) . '</th>');
                            }
                            ?>
                            <th scope="col" class="govuk-table__header nw l">Action</th>
                        </tr>
                    </thead>
                    <tbody class="govuk-table__body">
                        <?php
                        while ($row = pg_fetch_object($result)) {
                            //prend ($row);
                            echo ('<tr id="workbasket_item_sid_' . $row->workbasket_item_sid . '" class="govuk-table__row">');
                            for ($i = 0; $i < $field_count; $i++) {
                                $field = pg_field_name($result, $i);
                                if ($field == "status") {
                                    $align = "c";
                                } else {
                                    $align = "";
                                }
                                echo ('<td class="govuk-table__cell ' . $align . '">' . format_value($row, $field, $workbasket = true) . '</td>');
                            }
                            // Now do the action cells
                            $delete_url = "workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=" . $this->workbasket_id . "&workbasket_item_sid=" . $row->workbasket_item_sid;
                            $approve_url = "actions.php?action=approve_workbasket_item&workbasket_id=" . $this->workbasket_id . "&workbasket_item_sid=" . $row->workbasket_item_sid;
                            $reject_url = "reject.html?action=reject_workbasket_item&workbasket_id=" . $this->workbasket_id . "&workbasket_item_sid=" . $row->workbasket_item_sid;

                            echo ('<td class="govuk-table__cell nw" style="width:8%">');
                            echo ('<ul class="measure_activity_action_list">');
                            echo ('<li><a class="govuk-link" title="View or edit this activity" href="' . $row->view_url . '"><img src="/assets/images/view.png" /><span>View</span></a></li>');
                            if ($application->session->permissions == "Approver") {
                                if ($row->status == 'Awaiting approval') {
                                    echo ('<li><a class="govuk-link" title="Approve this activity" href="' . $approve_url . '"><img src="/assets/images/approve.png" /><span>Approve</span></a></li>');
                                    echo ('<li><a class="govuk-link modaal-ajax" title="Reject this activity" href="' . $reject_url . '"><img src="/assets/images/reject.png" /><span>Reject</span></a></li>');
                                }
                            }
                            if ($this->user_id == $application->session->user_id) {
                                echo ('<li><a class="govuk-link" title="Delete this activity" href="' . $delete_url . '"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>');
                            }
                            echo ('</ul>');
                            echo ('</td>');
                            echo ('</tr>');
                            if (($row->rejection_reason != "") && ($row->status == "Rejected")) {
                                echo ("<tr>");
                                echo ("<td colspan='" . ($field_count + 1) . "'>");
                                new warning_control("This activity has been rejected for the following reason:<br /><br /><span class='normal'>" . $row->rejection_reason . "</span>");
                                echo ("</td>");
                                echo ("</tr>");
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $('.modaal-ajax').modaal({
                type: 'ajax'
            });
        </script>

        <!-- End accordion section - <?= $object_type ?> //-->
<?php
    }

    public function get_view_url($object_type, $record_id)
    {
        h1($object_type . $record_id);
    }

    public function workbasket_get_footnote_types()
    {
        global $conn;
        $sql = "select wi.operation, ft.footnote_type_id, ft.validity_start_date, ft.validity_end_date,
        ftd.description as footnote_type_description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/footnote_types/view.html?mode=view&footnote_type_id=' || ft.footnote_type_id as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, footnote_types ft, footnote_type_descriptions ftd
        where wi.record_id = ft.oid
        and ft.footnote_type_id = ftd.footnote_type_id 
        and wi.record_type = 'footnote_type'
        and wi.workbasket_id = $1
        and ft.workbasket_id = $1
        and ftd.workbasket_id = $1
        order by wi.created_at ";
        //prend ($sql);
        pg_prepare($conn, "workbasket_get_footnote_types", $sql);
        $result = pg_execute($conn, "workbasket_get_footnote_types", array(
            $this->workbasket_id
        ));
        $this->show_section("footnote types", $result);
    }

    public function workbasket_get_certificate_types()
    {
        global $conn;
        $sql = "select wi.operation, ct.certificate_type_code, ct.validity_start_date, ct.validity_end_date,
        ctd.description as certificate_description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/certificate_types/view.html?mode=view&certificate_type_code=' || ct.certificate_type_code as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, certificate_types ct, certificate_type_descriptions ctd
        where wi.record_id = ct.oid
        and ct.certificate_type_code = ctd.certificate_type_code 
        and wi.record_type = 'certificate_type'
        and wi.workbasket_id = $1
        order by wi.created_at ";
        pg_prepare($conn, "workbasket_get_certificate_types", $sql);
        $result = pg_execute($conn, "workbasket_get_certificate_types", array(
            $this->workbasket_id
        ));
        $this->show_section("certificate types", $result);
    }

    public function workbasket_get_additional_code_types()
    {
        global $conn;
        $sql = "select wi.operation, act.additional_code_type_id, act.validity_start_date, act.validity_end_date,
        actd.description as additional_code_type_description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/additional_code_types/view.html?mode=view&additional_code_type_id=' || act.additional_code_type_id as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, additional_code_types act, additional_code_type_descriptions actd
        where wi.record_id = act.oid
        and act.additional_code_type_id = actd.additional_code_type_id 
        and wi.record_type = 'additional_code_type'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_additional_code_types", $sql);
        $result = pg_execute($conn, "workbasket_get_additional_code_types", array(
            $this->workbasket_id
        ));
        $this->show_section("additional code types", $result);
    }

    public function workbasket_get_measure_types()
    {
        global $conn;
        $sql = "select wi.operation,
        mt.measure_type_id,
        (
            '<b>Description</b>: ' || mtd.description  ||
            '<br /><b>Import / export</b>: ' || mt.trade_movement_code || ' ' || tmc.description ||
            '<br /><b>Requires duties</b>: ' || mt.measure_component_applicable_code || ' ' || mcac.description ||
            '<br /><b>Requires order number</b>: ' || mt.order_number_capture_code || ' ' || oncc.description
        ) as measure_type_description_and_key_fields,
        mt.validity_start_date, mt.validity_end_date,
        (mt.measure_type_series_id || ' ' || mtsd.description) as series,
        wi.status,
        wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/measure_types/view.html?mode=view&measure_type_id=' || mt.measure_type_id as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, measure_types mt, measure_type_descriptions mtd,
        measure_type_series_descriptions mtsd, trade_movement_codes tmc,
        measure_component_applicable_codes mcac, order_number_capture_codes oncc
        where wi.record_id = mt.oid
        and mt.measure_type_id = mtd.measure_type_id 
        and mt.measure_type_series_id = mtsd.measure_type_series_id
        and mt.trade_movement_code = tmc.trade_movement_code
        and mt.measure_component_applicable_code = mcac.measure_component_applicable_code
        and mt.order_number_capture_code = oncc.order_number_capture_code
        and wi.record_type = 'measure type'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_measure_types", $sql);
        $result = pg_execute($conn, "workbasket_get_measure_types", array(
            $this->workbasket_id
        ));
        $this->show_section("measure types", $result);
    }


    public function workbasket_get_footnotes()
    {
        global $conn;
        $sql = "with cte as (
            select 
            wi.operation,
            (f.footnote_type_id || ' ' || f.footnote_id) as footnote_id,
            Coalesce(fd.description, 'Not updated') as footnote_description,
            f.footnote_type_id || ' ' || ftd.description as footnote_type_id,
            f.validity_start_date, f.validity_end_date,
            wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/footnotes/view.html?mode=view&footnote_id=' || f.footnote_id || '&footnote_type_id=' || f.footnote_type_id as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, footnote_type_descriptions ftd, footnotes_oplog f
            left outer join footnote_descriptions_oplog fd
            on f.workbasket_item_sid = fd.workbasket_item_sid  
            where wi.record_id = f.oid
            and f.footnote_type_id = ftd.footnote_type_id 
            and wi.record_type = 'footnote'
            and wi.workbasket_id = $1
            union 
            select wi.operation,
            (fd.footnote_type_id || ' ' || fd.footnote_id) as footnote_id,
            fd.description as footnote_description,
            fd.footnote_type_id || ' ' || ftd.description as footnote_type_id,
            fdp.validity_start_date, null as validity_end_date,
            wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/footnotes/view.html?mode=view&footnote_id=' || fd.footnote_id || '&footnote_type_id=' || fd.footnote_type_id as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, footnote_descriptions_oplog fd, footnote_type_descriptions ftd, footnote_description_periods_oplog fdp
            where wi.record_id = fd.oid
            and fdp.footnote_description_period_sid = fd.footnote_description_period_sid
            and fd.footnote_type_id = ftd.footnote_type_id
            and fd.workbasket_item_sid = fd.workbasket_item_sid
            and wi.record_type = 'footnote description'
            and wi.workbasket_id = $1
        )
        select * from cte order by created_at;";

        pg_prepare($conn, "workbasket_get_footnotes", $sql);
        $result = pg_execute($conn, "workbasket_get_footnotes", array(
            $this->workbasket_id
        ));
        $this->show_section("footnotes", $result);
    }


    public function workbasket_get_certificates()
    {
        global $conn;
        $sql = "with cte as (
            select 
            wi.operation,
            (c.certificate_type_code || ' ' || c.certificate_code) as certificate_code,
            Coalesce(cd.description, 'Not updated') as certificate_code_description,
            c.certificate_type_code || ' ' || ctd.description as certificate_type_code,
            c.validity_start_date, c.validity_end_date,
            wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/certificates/view.html?mode=view&certificate_code=' || c.certificate_code || '&certificate_type_code=' || c.certificate_type_code as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, certificate_type_descriptions ctd, certificates_oplog c
            left outer join certificate_descriptions_oplog cd
            on c.workbasket_item_sid = cd.workbasket_item_sid  
            where wi.record_id = c.oid
            and c.certificate_type_code = ctd.certificate_type_code 
            and wi.record_type = 'certificate'
            and wi.workbasket_id = $1
            
            union 
            
            select wi.operation,
            cd.certificate_type_code || ' ' || ctd.description as certificate_type_code,
            cd.description as certificate_description,
            (cd.certificate_type_code || ' ' || cd.certificate_code) as certificate_code,
            cdp.validity_start_date, null as validity_end_date,
            wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/certificates/view.html?mode=view&certificate_code=' || cd.certificate_code || '&certificate_type_code=' || cd.certificate_type_code as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, certificate_descriptions_oplog cd, certificate_type_descriptions ctd, certificate_description_periods_oplog cdp
            where wi.record_id = cd.oid
            and cdp.certificate_description_period_sid = cd.certificate_description_period_sid
            and cd.certificate_type_code = ctd.certificate_type_code
            and cd.workbasket_item_sid = cd.workbasket_item_sid
            and wi.record_type = 'certificate description'
            and wi.workbasket_id = $1
        )
        select * from cte order by created_at;";
        pg_prepare($conn, "workbasket_get_certificates", $sql);
        $result = pg_execute($conn, "workbasket_get_certificates", array(
            $this->workbasket_id
        ));
        $this->show_section("certificates", $result);
    }

    public function workbasket_get_additional_codes()
    {
        global $conn;
        $sql = "with cte as (
            select 
            wi.operation, ac.additional_code_type_id || ' ' || actd.description as additional_code_type,
            (ac.additional_code_type_id || ' ' || ac.additional_code) as additional_code,
            ac.validity_start_date, ac.validity_end_date, Coalesce(acd.description, 'Not updated') as additional_code_description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/additional_codes/view.html?mode=view&additional_code=' || ac.additional_code || '&additional_code_type_id=' || ac.additional_code_type_id as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, additional_code_type_descriptions actd, additional_codes_oplog ac
            left outer join additional_code_descriptions_oplog acd
            on ac.workbasket_item_sid = acd.workbasket_item_sid  
            where wi.record_id = ac.oid
            and ac.additional_code_type_id = actd.additional_code_type_id 
            and wi.record_type = 'additional code'
            and wi.workbasket_id = $1
            
            union 
            
            select wi.operation, acd.additional_code_type_id || ' ' || actd.description as additional_code_type_id,
            (acd.additional_code_type_id || ' ' || acd.additional_code) as additional_code,
            acdp.validity_start_date, null as validity_end_date,
            acd.description as additional_code_description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
            '/additional_codes/view.html?mode=view&additional_code=' || acd.additional_code || '&additional_code_type_id=' || acd.additional_code_type_id as view_url,
            wi.record_type, wi.created_at 
            from workbasket_items wi, additional_code_descriptions_oplog acd, additional_code_type_descriptions actd, additional_code_description_periods_oplog acdp
            where wi.record_id = acd.oid
            and acdp.additional_code_description_period_sid = acd.additional_code_description_period_sid
            and acd.additional_code_type_id = actd.additional_code_type_id
            and acd.workbasket_item_sid = acd.workbasket_item_sid
            and wi.record_type = 'additional code description'
                and wi.workbasket_id = $1
        )
        select * from cte order by created_at;";
        pg_prepare($conn, "workbasket_get_additional_codes", $sql);
        $result = pg_execute($conn, "workbasket_get_additional_codes", array(
            $this->workbasket_id
        ));
        $this->show_section("additional codes", $result);
    }


    public function workbasket_get_regulations()
    {
        global $conn;
        $sql = "select wi.operation,
        br.base_regulation_id,
        (
        '<b>Public identifier</b>: ' || br.public_identifier ||
        '<br /><b>Description</b>: ' || br.information_text ||
        '<br /><b>URL</b>: ' || br.url ||
        '<br /><b>Trade Remedies case</b>: ' || coalesce(br.trade_remedies_case, 'n/a')) as regulation_description_and_key_fields,
        br.validity_start_date, br.validity_end_date, 
        (br.regulation_group_id || ' - ' || rgd.description) as regulation_group_id,
        wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '' as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, base_regulations br, regulation_group_descriptions rgd 
        where wi.record_id = br.oid
        and br.regulation_group_id = rgd.regulation_group_id 
        and wi.record_type = 'regulation'
        and wi.workbasket_id = $1
        order by wi.created_at  ";
        pg_prepare($conn, "workbasket_get_regulations", $sql);
        $result = pg_execute($conn, "workbasket_get_regulations", array(
            $this->workbasket_id
        ));
        $this->show_section("regulations", $result);
    }


    public function workbasket_get_geographical_areas()
    {
        global $conn;
        $sql = "select wi.operation, ga.geographical_code || ' - ' || gc.description as geographical_code,
        ga.geographical_area_id, ga.validity_start_date, ga.validity_end_date, gad.description as geographical_area_description,
        wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/geographical_areas/view.html?mode=view&geographical_area_id=' || ga.geographical_area_id || '&geographical_area_sid=' || ga.geographical_area_sid  as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, geographical_areas ga, geographical_area_descriptions gad, geographical_codes gc 
        where wi.record_id = ga.oid
        and wi.record_type = 'geographical_area'
        and ga.geographical_code = gc.geographical_code 
        and ga.geographical_area_sid = gad.geographical_area_sid 
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_geographical_areas", $sql);
        $result = pg_execute($conn, "workbasket_get_geographical_areas", array(
            $this->workbasket_id
        ));
        $this->show_section("geographical areas", $result);
    }

    public function workbasket_get_measure_activities()
    {
        global $conn;
        $sql = "select ma.activity_name, wi.sub_record_type, ma.validity_start_date, ma.validity_end_date,
        ma.measure_generating_regulation_id, /* ma.commodity_list, */
        wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        '/measures/create_edit_summary.html?mode=view&measure_activity_sid=' || wi.record_id as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, measure_activities ma
        where wi.record_id = ma.measure_activity_sid 
        and wi.record_type = 'measure_activity'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_measure_activities", $sql);
        $result = pg_execute($conn, "workbasket_get_measure_activities", array(
            $this->workbasket_id
        ));
        $this->show_section("measure activities", $result);
    }

    public function workbasket_get_quota_suspension_periods()
    {
        global $conn;
        $sql = "select wi.operation, qd.quota_order_number_id, qsp.suspension_start_date, qsp.suspension_end_date,
        qsp.description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        'test.html' as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, quota_suspension_periods qsp, quota_definitions qd
        where wi.record_id = qsp.oid
        and wi.record_type = 'quota_suspension_period'
        and wi.workbasket_id = $1
        and qsp.quota_definition_sid = qd.quota_definition_sid
        and qsp.workbasket_id = $1
        order by wi.created_at ";
        $stmt = "workbasket_get_quota_suspension_periods_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id
        ));
        $this->show_section("quota suspension periods", $result);
    }


    public function workbasket_get_quota_blocking_periods()
    {
        global $conn;
        $sql = "select wi.operation, qd.quota_order_number_id, qsp.blocking_start_date, qsp.blocking_end_date,
        qsp.description, wi.status, wi.workbasket_item_sid, wi.record_id, wi.rejection_reason,
        'test.html' as view_url,
        wi.record_type, wi.created_at 
        from workbasket_items wi, quota_blocking_periods qsp, quota_definitions qd
        where wi.record_id = qsp.oid
        and wi.record_type = 'quota_blocking_period'
        and wi.workbasket_id = $1
        and qsp.quota_definition_sid = qd.quota_definition_sid
        and qsp.workbasket_id = $1
        order by wi.created_at ";
        $stmt = "workbasket_get_quota_blocking_periods_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id
        ));
        $this->show_section("quota blocking periods", $result);
    }

    private function get_operation_string($operation)
    {
        if ($operation == "U") {
            return ("Update to ");
        } elseif ($operation == "D") {
            return ("Deletion of ");
        } elseif ($operation == "C") {
            return ("New ");
        }
    }

    public function insert_workbasket_item($oid, $record_type, $status, $operation, $operation_date, $description)
    {
        global $conn, $application;

        $sql = "INSERT INTO workbasket_items (
                workbasket_id, record_id, record_type, status, operation, created_at, description
                )
                VALUES ($1, $2, $3, $4, $5, $6, $7)
                RETURNING workbasket_item_sid;";
        $stmt = "insert_workbasket_item_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id, $oid, $record_type, $status, $operation, $operation_date, $description
        ));
        $row = pg_fetch_row($result);
        $workbasket_item_sid = $row[0];

        // Also insert a workbasket item event
        $sql = "INSERT INTO workbasket_item_events (
            workbasket_id, workbasket_item_sid, created_at, user_id, event_type, description
            )
            VALUES ($1, $2, $3, $4, $5, $6)
            RETURNING workbasket_item_sid;";
        $stmt = "insert_workbasket_item_event_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->workbasket_id, $workbasket_item_sid, $operation_date, $application->session->user_id, "Create activity", $description
        ));
        //die();


        return ($workbasket_item_sid);
    }

    public function get_workbasket_item($workbasket_item_sid)
    {
        global $conn;
        $title = "";
        if (($workbasket_item_sid == null) || ($workbasket_item_sid == "")) {
            return "";
        }
        $record_type = "";
        $sql = "select record_id, record_type, title from workbasket_items wi where workbasket_item_sid = $1;";
        $stmt = "get_workbasket_item_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $workbasket_item_sid
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $record_type = $row[1];
            $title = $row[2];
        }
        return ($title);
    }

    public function delete_workbasket_item($workbasket_item_sid)
    {
        global $conn;
        if (($workbasket_item_sid == null) || ($workbasket_item_sid == "")) {
            return;
        }
        $sql = "select record_id, record_type from workbasket_items wi where workbasket_item_sid = $1;";
        pg_prepare($conn, "get_workbasket_item" . $workbasket_item_sid, $sql);
        $result = pg_execute($conn, "get_workbasket_item" . $workbasket_item_sid, array(
            $workbasket_item_sid
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $record_type = $row[1];
        } else {
            return;
        }
        //h1end ("here " . $record_type);
        switch ($record_type) {
            case "measure_activity":
                $sql = "delete from measure_activity_additional_codes_oplog where measure_activity_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from measure_activity_footnotes_oplog where measure_activity_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from measure_activity_commodities_oplog where measure_activity_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from measure_activities_oplog where measure_activity_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "footnote_type":
                $sql = "delete from footnote_types_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from footnote_type_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "certificate_type":
                $sql = "delete from certificate_types_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from certificate_type_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "additional_code_type":
                $sql = "delete from additional_code_types_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from additional_code_type_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "measure_type":
                $sql = "delete from measure_types_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from measure_type_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "regulation":
                $sql = "delete from base_regulations_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "footnote":
                $sql = "delete from footnotes_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from footnotes_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from footnotes_description_periods_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "certificate":
                $sql = "delete from certificates_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from certificates_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from certificates_description_periods_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;

            case "geographical_area":
                $sql = "delete from geographical_areas_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from geographical_area_descriptions_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from geographical_area_description_periods_oplog where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                $sql = "delete from workbasket_items where workbasket_item_sid = $1;";
                db_execute($sql, array($workbasket_item_sid));
                break;
        }
    }

    function show_workbasket_icon_open_close()
    {
        global $application;
        $ret = "";
        if (isset($application->session->workbasket->workbasket_id)) {
            $test = $application->session->workbasket->workbasket_id;
        } else {
            $test = -1;
        }

        if ($application->show_workbasket_icons) {
            $content_open = "<img alt='Open workbasket' src='/assets/images/open.png' /><span>Open workbasket</span>";
            $content_close = "<img alt='Close workbasket' src='/assets/images/close.png' /><span>Close workbasket</span>";
            $content_archive = "<img alt='Archive workbasket' src='/assets/images/archive.png' /><span>Archive workbasket</span>";
        } else {
            $content_open = "Open workbasket";
            $content_close = "Close workbasket";
            $content_archive = "Archive workbasket";
        }
        if ($this->workbasket_id == $test) {
            $ret = "<li><a class='govuk-link' title='Close this workbasket' href='/workbaskets/actions.php?action=close'>" . $content_close . "</a></li>\r\n";
        } else {
            if ($application->session->permissions == "Approver") {
                if (($this->status == 'In progress') || ($this->status == 'Awaiting approval')) {
                    $ret = "<li><a class='govuk-link' title='Open this workbasket' href='/workbaskets/actions.php?action=open&workbasket_id=" . $this->workbasket_id . "'>" . $content_open . "</a></li>\r\n";
                } elseif ($this->status == 'Published') {
                    $ret = "<li><a class='govuk-link' title='Archive this workbasket' href='/workbaskets/actions.php?action=archive'>" . $content_archive . "</a><li>\r\n";
                } else {
                    $ret = "";
                }
            } else {
                if ($this->status == 'In progress') {
                    $ret = "<li><a class='govuk-link' title='Open this workbasket' href='/workbaskets/actions.php?action=open&workbasket_id=" . $this->workbasket_id . "'>" . $content_open . "</a></li>\r\n";
                } elseif ($this->status == 'Published') {
                    $ret = "<li><a class='govuk-link' title='Archive this workbasket' href='/workbaskets/actions.php?action=archive'>" . $content_archive . "</a></li>\r\n";
                } else {
                    $ret = "";
                }
            }
        }
        return ($ret);
    }

    function show_workbasket_icon_withdraw()
    {
        // Withdraw is used to withdraw workbasket from "Awaiting approval" back to "In progress"
        global $application;
        if ($application->show_workbasket_icons) {
            $content = "<img alt='Withdraw workbasket' src='/assets/images/withdraw.png' /><span>Withdraw</span>";
        } else {
            $content = "Withdraw workbasket";
        }
        $ret = "";

        if ($this->user_id == $application->session->user_id) {
            if ($this->status == 'Awaiting approval') {
                $ret = "<li><a class='govuk-link' title='Withdraw this workbasket' href='/workbaskets/withdraw.html?workbasket_id=" . $this->workbasket_id . "'>" . $content . "</a></li>\r\n";
            } else {
                $ret = "";
            }
        } else {
            $ret = "";
        }

        return ($ret);
    }

    function show_workbasket_icon_view()
    {
        global $application;
        if ($application->show_workbasket_icons) {
            $content = "<img alt='View workbasket' src='/assets/images/view.png' /><span>View</span>";
        } else {
            $content = "View workbasket";
        }

        $ret = "<li><a class='govuk-link' title='View this workbasket' href='/workbaskets/view.html?mode=view&workbasket_id=" . $this->workbasket_id . "'>" . $content . "</a></li>\r\n";

        return ($ret);
    }

    function show_workbasket_icon_submit()
    {
        global $application;
        if ($application->show_workbasket_icons) {
            $content = "<img alt='Submit workbasket' src='/assets/images/submit.png' /><span>Submit for approval</span>";
        } else {
            $content = "Submit for approval";
        }
        $ret = "";

        if ($this->user_id == $application->session->user_id) {
            if (($this->status == 'In progress') || ($this->status == 'Re-editing')) {
                $ret = "<li><a class='govuk-link' title='Submit workbasket for approval' href='/workbaskets/actions.php?action=submit_for_approval&workbasket_id=" . $this->workbasket_id . "'>" . $content . "</a></li>\r\n";
            } else {
                $ret = "";
            }
        } else {
            $ret = "";
        }
        return ($ret);
    }


    function show_workbasket_icon_delete()
    {
        global $application;
        if ($application->show_workbasket_icons) {
            $content = "<img alt='Delete workbasket' src='/assets/images/delete.png' /><span>Delete</span>";
        } else {
            $content = "Delete workbasket";
        }
        $ret = "";

        if ($this->user_id == $application->session->user_id) {
            if (($this->status == 'In progress') || ($this->status == 'Re-editing')) {
                $ret = "<li><a class='govuk-link' title='Delete this workbasket' href='/workbaskets/workbasket_delete.html?workbasket_id=" . $this->workbasket_id . "'>" . $content . "</a></li>\r\n";
            } else {
                $ret = "";
            }
        } else {
            $ret = "";
        }
        return ($ret);
    }

    function submit_workbasket_for_approval()
    {
        global $conn;

        $sql = "select * from ml.update_status($1, $2);";
        $stmt = "change_status";
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array('Awaiting approval', $this->workbasket_id));


        $url = "./workbasket_submission_confirmation.html?workbasket_id=" . $this->workbasket_id;
        header("Location: " . $url);
    }

    public function update_workbasket()
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();

        $this->workbasket_id = get_formvar("workbasket_id");
        $this->title = get_formvar("title");
        $this->reason = get_formvar("reason");
        $sql = "update workbaskets set title = $1, reason = $2, updated_at = $3 where workbasket_id = $4";
        $stmt = "update_workbasket_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($this->title, $this->reason, $operation_date, $this->workbasket_id));

        // Create the 'update workbasket' event
        $description = '[{';
        $description .= '"Action": "UPDATE WORKBASKET",';
        $description .= '"Title": "' . $this->title . '",';
        $description .= '"Reason for creation": "' . $this->reason . '"';
        $description .= '}]';

        $sql = "insert into workbasket_events (workbasket_id, user_id, event_type, created_at, description) values ($1, $2, $3, $4, $5);";
        $stmt = "workbasket_event_insert_" . uniqid();
        pg_prepare($conn, $stmt, $sql);

        $result = pg_execute($conn, $stmt, array($this->workbasket_id, $application->session->user_id, "Update workbasket", $operation_date, $description));
        //die();

        $url = "./view.html?workbasket_id=" . $this->workbasket_id;
        header("Location: " . $url);
    }

    public function close_workbasket()
    {
        global $application;
        $application->session->workbasket = null;
        $_SESSION["confirm_operate_others_workbasket"] = "";
        $_SESSION["workbasket_id"] = "";
        $_SESSION["workbasket_title"] = "";
        $url = "/?notify=The+workbasket+has+been+closed#workbaskets";
        header("Location: " . $url);
    }


    public function withdraw_workbasket()
    {
        global $conn;

        $sql = "select * from ml.update_status($1, $2);";
        $stmt = "change_status";
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array('In progress', $this->workbasket_id));

        $url = "/#workbaskets";
        header("Location: " . $url);
    }


    public function delete_workbasket()
    {
        // This actually does a delete
        global $conn;
        if ($this->workbasket_id == $_SESSION["workbasket_id"]) {
            $this->close_workbasket();
        }

        // Delete the workbasket and all its items
        $sql = "select * from ml.delete_workbasket($1);";
        $stmt = "delete_workbasket_items_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($this->workbasket_id));
    }

    public function approve_workbasket_item($workbasket_item_sid)
    {
        global $conn;
        $sql = "update workbasket_items set status = 'Approved', rejection_reason = '' where workbasket_item_sid = $1";
        $stmt = "approve_workbasket_item_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($workbasket_item_sid));
    }

    public function reject_workbasket_item($workbasket_item_sid, $rejection_reason)
    {
        global $conn;
        $sql = "update workbasket_items set status = 'Rejected', rejection_reason = $2 where workbasket_item_sid = $1";
        $stmt = "approve_workbasket_item_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($workbasket_item_sid, $rejection_reason));
    }

    public function take_ownership()
    {
        global $conn, $application;

        // Create a 'Take ownership' workbasket event
        $this->create_workbasket_event("Take ownership");

        // Update the owner of the workbasket
        $sql = "update workbaskets set user_id = $1 where workbasket_id = $2";
        $stmt = "take_ownership" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($application->session->user_id, $this->workbasket_id));
    }

    public function create_workbasket_event($event_type)
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();
        $description = "";

        switch ($event_type) {
            case "Take ownership":
                // Get the existing owner
                $existing_user_name = "";
                $sql = "select u.name as user_name from workbaskets w, users u where w.user_id = u.user_id and workbasket_id = $1;";
                $stmt = "get_user_name_" . uniqid();
                pg_prepare($conn, $stmt, $sql);
                $result = pg_execute($conn, $stmt, array($this->workbasket_id));
                if ($result) {
                    $row = pg_fetch_row($result);
                    $existing_user_name = $row[0];
                    $description = "Ownership moved from " . $existing_user_name . " to " . $application->session->name;
                } else {
                    $description = "Ownership moved to " . $application->session->name;
                }

                break;
        }

        $sql = "insert into workbasket_events (workbasket_id, user_id, event_type, description, created_at) values ($1, $2, $3, $4, $5);";
        $stmt = "workbasket_event_insert_" . uniqid();
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($this->workbasket_id, $application->session->user_id, $event_type, $description, $operation_date));
    }
}
