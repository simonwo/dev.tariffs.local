<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">EU Taric data changes</h1>
                    <!-- End main title //-->
                    <?php
                    new inset_control("This screen identifies the changes that the European Commission has made to the
                    Taric commodiy code classification 'The Combined Nomenclature'. It is crucial, in order to ensure that
                    the Northern Ireland Protocol's requirement to apply EU regulatory controls to all goods entering NI
                    from GB and Rest of World, that the UK maintains dynamic alignment with the EU's commodity code
                    hierarchy.<br /><br />The following changes have been received:");

                    new warning_control("This table should contain a list of the latest EU commodity code changes that have not been 'dealt with' - this should include a 
                    list of all iems that have come in on the taric file after the date of the end of the Transition period that fall into the following groups
                    0 but ideally grouped together:<br/><br/>
                    40000 GOODS NOMENCLATURE<br/>
                    40005 GOODS NOMENCLATURE INDENT<br/>
                    40010 GOODS NOMENCLATURE DESCRIPTION PERIOD<br/>
                    40015 GOODS NOMENCLATURE DESCRIPTION<br/>
                    40035 GOODS NOMENCLATURE ORIGIN<br/>
                    40040 GOODS NOMENCLATURE SUCCESSOR");
                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <?php
                    //global $conn_eu;
                    $sql = "with cte as 
                    (
                    select distinct on (gn.goods_nomenclature_sid)
                    gn.goods_nomenclature_sid, gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description,
                    gn.operation_date, gn.operation, gn.validity_start_date, gn.validity_end_date 
                    from goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp 
                    where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid 
                    and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid 
                    order by gn.goods_nomenclature_sid, gndp.validity_start_date desc
                    )
                    select * from cte order by operation_date desc
                    limit 100";
                    $stmt = "get_comm_codes_" . uniqid();
                    pg_prepare($conn_eu, $stmt, $sql);
                    $result = pg_execute($conn_eu, $stmt, array());
                    $gns = array();
                    if ($result) {
                        $row_count = pg_num_rows($result);
                        if ($row_count > 0) {
                            while ($row = pg_fetch_array($result)) {
                                $gn = new goods_nomenclature();
                                $gn->goods_nomenclature_sid = $row["goods_nomenclature_sid"];
                                $gn->goods_nomenclature_item_id = $row["goods_nomenclature_item_id"];
                                $gn->producline_suffix = $row["producline_suffix"];
                                $gn->description = $row["description"];
                                $gn->operation_date = $row["operation_date"];
                                $gn->operation = $row["operation"];
                                $gn->validity_start_date = $row["validity_start_date"];
                                $gn->validity_end_date = $row["validity_end_date"];

                                array_push($gns, $gn);
                            }
                    ?>
                            <table cellspacing="0" class="govuk-table govuk-table--m sticky">
                                <tr class="govuk-table__row">
                                    <th class="govuk-table__header nopad" scope="col">Commodity code</th>
                                    <th class="govuk-table__header nopad" scope="col">PLS</th>
                                    <th class="govuk-table__header nopad c" scope="col">Indent</th>
                                    <th class="govuk-table__header" scope="col">Description</th>
                                    <th class="govuk-table__header nw" scope="col">Start date</th>
                                    <th class="govuk-table__header nw" scope="col">End date</th>
                                    <th class="govuk-table__header c" scope="col">Operation</th>
                                    <th class="govuk-table__header nw" scope="col">Operation date</th>
                                    <th class="govuk-table__header nw" scope="col">EU Taric file</th>
                                    <th class="govuk-table__header" scope="col">Action</th>
                                </tr>
                                <?php
                                foreach ($gns as $gn) {

                                ?>

                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell m"><?= format_goods_nomenclature_item_id($gn->goods_nomenclature_item_id) ?></td>
                                        <td class="govuk-table__cell"><?= $gn->producline_suffix ?></td>
                                        <td class="govuk-table__cell c"><?= $gn->indent ?></td>
                                        <td class="govuk-table__cell"><?= $gn->description ?></td>
                                        <td class="govuk-table__cell nw"><?= short_date($gn->validity_start_date) ?></td>
                                        <td class="govuk-table__cell nw"><?= short_date($gn->validity_end_date) ?></td>
                                        <td class="govuk-table__cell c"><?= $gn->operation ?></td>
                                        <td class="govuk-table__cell nw"><?= short_date($gn->operation_date) ?></td>
                                        <td class="govuk-table__cell">TGB200001.xml</td>
                                        <td class="govuk-table__cell nw">
                                            <ul class="measure_activity_action_list">
                                                <li><a class="govuk-link" href='./edit.html?goods_nomenclature_sid=<?= $gn->goods_nomenclature_sid ?>'><img src="/assets/images/view.png" /><span>View transaction detail</span></a></li>
                                                <li><a class="govuk-link" href='./include.html?goods_nomenclature_sid=<?= $gn->goods_nomenclature_sid ?>'><img src="/assets/images/include.png" /><span>Incorporate into UK tariff</span></a></li>
                                                <li><a class="govuk-link" href='./edit.html?goods_nomenclature_sid=<?= $gn->goods_nomenclature_sid ?>'><img src="/assets/images/edit.png" /><span>Edit</span></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                    <?php

                        }
                    }

                    ?>

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>