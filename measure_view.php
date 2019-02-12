<?php
    require ("includes/db.php");
    require ("includes/header.php");
    $measure_sid = get_querystring("measure_sid");
?>
<div id="wrapper" class="direction-ltr">
    <!-- Start breadcrumbs //-->
    <div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                Measures
            </li>
        </ol>
    </div>
    <!-- End breadcrumbs //-->
    <div class="app-content__header">
        <h1 class="govuk-heading-xl">Measure <?=$measure_sid?></h1>
    </div>

    <h2 id="measure_details">Measure details</h2>
    <table cellspacing="0" class="govuk-table">
        <tr class="govuk-table__row">
            <th class="govuk-table__header" style="width:25%">Item</th>
            <th class="govuk-table__header" style="width:75%">Value</th>
        </tr>

<?php
    $sql = "SELECT m.measure_type_id, m.geographical_area_id, goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date,
    measure_generating_regulation_role, measure_generating_regulation_id, justification_regulation_role, justification_regulation_id,
    stopped_flag, ordernumber, additional_code_type_id, additional_code_id, reduction_indicator, mtd.description as measure_type_description,
    ga.description as geographical_area_description, rrtd.description as regulation_role_type_description,
    rrtd2.description as justification_role_type_description
    FROM measures m, ml.ml_geographical_areas ga, measure_type_descriptions mtd,
    regulation_role_type_descriptions as rrtd, regulation_role_type_descriptions as rrtd2
    WHERE measure_sid = " . $measure_sid . " AND m.measure_type_id = mtd.measure_type_id
    AND m.geographical_area_id = ga.geographical_area_id
    AND CAST(rrtd.regulation_role_type_id as INTEGER) = CAST(m.measure_generating_regulation_role as INTEGER)
    AND CAST(rrtd2.regulation_role_type_id as INTEGER) = CAST(m.justification_regulation_role as INTEGER)";
    $result = pg_query($conn, $sql);
	if  ($result) {
        while ($row = pg_fetch_array($result)) {
            $measure_type_id                        = $row['measure_type_id'];
            $geographical_area_id                   = $row['geographical_area_id'];
            $goods_nomenclature_item_id             = $row['goods_nomenclature_item_id'];
            $validity_start_date                    = $row['validity_start_date'];
            $validity_end_date                      = $row['validity_end_date'];
            $measure_generating_regulation_role     = $row['measure_generating_regulation_role'];
            $measure_generating_regulation_id       = $row['measure_generating_regulation_id'];
            $justification_regulation_role          = $row['justification_regulation_role'];
            $justification_regulation_id            = $row['justification_regulation_id'];
            $stopped_flag                           = $row['stopped_flag'];
            $ordernumber                            = $row['ordernumber'];
            $additional_code_type_id                = $row['additional_code_type_id'];
            $additional_code_id                     = $row['additional_code_id'];
            $reduction_indicator                    = $row['reduction_indicator'];
            $measure_type_description               = $row['measure_type_description'];
            $geographical_area_description          = $row['geographical_area_description'];
            $regulation_role_type_description       = $row['regulation_role_type_description'];
            $justification_role_type_description    = $row['justification_role_type_description'];
?>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Measure type ID</td>
            <td class="govuk-table__cell"><?=$measure_type_id?> - <?=$measure_type_description?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Geographical area ID</td>
            <td class="govuk-table__cell"><a href="geographical_area_view.php?geographical_area_id=<?=$geographical_area_id?>"><?=$geographical_area_id?> - <?=$geographical_area_description?></a></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Validity start date</td>
            <td class="govuk-table__cell"><?=string_to_date($validity_start_date)?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Validity end date</td>
            <td class="govuk-table__cell"><?=string_to_date($validity_end_date)?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Measure generating regulation</td>
            <td class="govuk-table__cell"><a href="regulation_view.php?regulation_id=<?=$measure_generating_regulation_id?>"><?=$measure_generating_regulation_id?></a> - Role type (<?=$measure_generating_regulation_role?> - <?=$regulation_role_type_description?>)</td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Justification regulation</td>
            <td class="govuk-table__cell"><a href="regulation_view.php?regulation_id=<?=$justification_regulation_id?>"><?=$justification_regulation_id?></a> - Role type (<?=$justification_regulation_role?> - <?=$justification_role_type_description?>)</td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Quota order number ID</td>
            <td class="govuk-table__cell"><a href="quota_order_number_view.php?quota_order_number_id=<?=$quota_order_number_id?>"><?=$ordernumber?></a></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Additional code</td>
            <td class="govuk-table__cell"><?=$additional_code_type_id?><?=$additional_code_id?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Stopped flag</td>
            <td class="govuk-table__cell"><?=$stopped_flag?></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell">Reduction indicator</td>
            <td class="govuk-table__cell"><?=$reduction_indicator?></td>
        </tr>
<?php
        }
    }
?>
        </table>
</div>

<?php
    require ("includes/footer.php")
?>