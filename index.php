<?php
require(dirname(__FILE__) . "/includes/db.php");
$application = new application;
$application->clear_filter_cookies();
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("includes/phase_banner.php");
        ?>


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Main menu</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <!-- Start column one //-->
                <div class="govuk-grid-column-one-quarter">
                    <!--
                    <h2 class="govuk-heading-m govuk-!-margin-0">Workbaskets</h2>
                    <?php
                    $application->session->show_workbasket_component_home();
                    ?>
                    //-->

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage regulations</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/regulations/create_edit.html">Create a new regulation</a></li>
                        <li><a class="govuk-link" href="/regulations/">Find and edit regulations</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage measures</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/measures/create_edit.html">Create new measures</a></li>
                        <li><a class="govuk-link" href="/measures/">Find and edit measures</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage quotas</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/quotas/create_edit.html">Create a new quota</a></li>
                        <li><a class="govuk-link" href="/quotas/">Find and edit quotas</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">View goods classification</h2>
                    <!--<p class="govuk-body-xs">Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet </p>//-->
                    <ul class="menu">
                        <li><a class="govuk-link" href="/goods_nomenclatures/">Find commodities</a></li>
                        <!--<li><a class="govuk-link" href="/goods_nomenclatures/create_edit.html">Create commodities</a></li>//-->
                    </ul>

                </div>
                <!-- End column one //-->

                <!-- Start column two //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage additional codes</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/additional_codes/create_edit.html">Create new additional code</a></li>
                        <li><a class="govuk-link" href="/additional_codes/">Find and edit additional codes</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage footnotes</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/footnotes/create_edit.html">Create a new footnote</a></li>
                        <li><a class="govuk-link" href="/footnotes/">Find and edit footnotes</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage certificates</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/certificates/create_edit.html">Create a new certificate</a></li>
                        <li><a class="govuk-link" href="/certificates/">Find and edit certificates</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage geographical areas</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/geographical_areas/create_edit.html">Create a new geographical area</a></li>
                        <li><a class="govuk-link" href="/geographical_areas/">Find and edit geographical areas</a></li>
                    </ul>

                </div>
                <!-- End column two //-->

                <!-- Start column three //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Reference data</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/rules_of_origin_schemes/">Rules of origin schemes</a></li>
                        <li><a class="govuk-link" href="/measure_types/">Measure types</a></li>
                        <li><a class="govuk-link" href="/certificate_types/">Certificate types</a></li>
                        <li><a class="govuk-link" href="/additional_code_types/">Additional code types</a></li>
                        <li><a class="govuk-link" href="/footnote_types/">Footnote types</a></li>
                    </ul>
                    <h2 class="govuk-heading-m govuk-!-margin-0">Read-only reference data</h2>
                    <p class="govuk-body-xs">These data sets may not be modified, as they are critical
                        to the functioning of the downstream systems.
                    </p>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/measure_type_series/">Measure type series</a></li>
                        <li><a class="govuk-link" href="/regulation_groups/">Regulation groups</a></li>
                        <li><a class="govuk-link" href="/measure_actions/">Measure actions</a></li>
                        <li><a class="govuk-link" href="/measure_condition_codes/">Measure condition codes</a></li>
                        <li><a class="govuk-link" href="/measurement_units/">Measurement units</a></li>
                        <li><a class="govuk-link" href="/measurement_unit_qualifiers/">Measurement qualifier units</a></li>
                        <li><a class="govuk-link" href="/duty_expressions/">Duty expressions</a></li>
                    </ul>

                </div>
                <!-- End column three //-->

                <!-- Start column four //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Reference documents</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="">Manage reference documents</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Reporting and auditing</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/reporting/load_history.html">Load history</a></li>
                        <li><a class="govuk-link" href="">Generate audit report</a></li>
                        <li><a class="govuk-link" href="/snapshot/">Measure snapshots</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Help</h2>
                    <ul class="menu">
                        <li><a class="govuk-link" href="/help">Help</a></li>
                    </ul>
                </div>
                <!-- End column four //-->

            </div>
            <hr>
            <div class="govuk-grid-row">
                <!-- Start column one //-->
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-m" id="workbaskets">Workbaskets</h1>
                    <!-- End main title //-->
                    <p class="govuk-body">Below is a list of all of the workbaskets that you have created. Alternatively, <a class="govuk-link" href="/workbaskets/create_edit.html">create new workbasket</a>.</p>
                    <form>
                        <!-- Start text input //-->
                        <div class="govuk-form-group" style='margin:0px'>
                            <label class="govuk-label govuk-visually-hidden" for="workbasket_filter">
                                Filter workbasket list
                            </label>
                            <input class="govuk-input workbasket_filter" id="workbasket_filter" name="workbasket_filter" type="text" />
                            <button class="govuk-button" data-module="govuk-button">Filter list</button>
                            <a id="clear_filter" href="#" class="textual_button govuk-link" style="top:0px !important">Clear filter</a>
                        </div>
                        <!-- End text input //-->

                    </form>
                    <?php
                    if ($application->session->permissions == "Tariff manager") {
                        $application->get_my_workbaskets();
                    } else {
                        $application->get_all_workbaskets();
                    }
                    ?>
                    <table class="govuk-table workbaskets">
                        <thead>
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header">Workbasket ID</th>
                                <th scope="col" class="govuk-table__header">Workbasket name</th>
                                <th scope="col" class="govuk-table__header">Created by</th>
                                <th scope="col" class="govuk-table__header">Date created</th>
                                <th scope="col" class="govuk-table__header">Last event</th>
                                <th scope="col" class="govuk-table__header">Status</th>
                                <th scope="col" class="govuk-table__header r">Next step</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <?php
                            foreach ($application->workbaskets as $workbasket) {
                                if ($application->session->workbasket != null) {
                                    if ($workbasket->workbasket_id == $application->session->workbasket->workbasket_id) {
                                        $row_class = " b";
                                        $current = true;
                                        $active = " (active)";
                                    } else {
                                        $row_class = "";
                                        $current = false;
                                        $active = "";
                                    }
                                } else {
                                    $row_class = "";
                                    $current = false;
                                    $active = "";
                                }
                            ?>
                                <tr class="govuk-table__row <?= $row_class ?>">
                                    <td class="govuk-table__cell"><?= $workbasket->workbasket_id ?></td>
                                    <td class="govuk-table__cell"><?= $workbasket->title ?></td>
                                    <td class="govuk-table__cell"><?= $workbasket->user_name ?></td>
                                    <td class="govuk-table__cell"><?= $workbasket->created_at ?></td>
                                    <td class="govuk-table__cell"><?= $workbasket->updated_at ?></td>
                                    <td class="govuk-table__cell nowrap"><?= status_image($workbasket->status) ?><?= $workbasket->status ?><?= $active ?></td>
                                    <td class="govuk-table__cell r">
                                        <?php
                                        // The buttons to show for the tariff manager
                                        if ($application->session->permissions == "Tariff manager") {

                                            # Open / close icon
                                            if ($current == true) {
                                                echo ("<a title='Close this workbasket' href='/workbaskets/actions.php?action=close'><img alt='Close workbasket' src='/assets/images/close.png' /></a>\r\n");
                                            } else {
                                                if ($workbasket->status == 'In Progress') {
                                                    echo ("<a title='Open this workbasket' href='/workbaskets/actions.php?action=open&workbasket_id=" . $workbasket->workbasket_id . "'><img alt='Open workbasket' src='/assets/images/open.png' /></a>\r\n");
                                                } elseif ($workbasket->status == 'Published') {
                                                    echo ("<a title='Archive this workbasket' href='/workbaskets/actions.php?action=archive'><img alt='Archive workbasket' src='/assets/images/archive.png' /></a>\r\n");
                                                } else {
                                                    echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                                }
                                            }
                                            # Withdraw icon
                                            if (($workbasket->status == 'In Progress') || ($workbasket->status == 'Awaiting Approval') || ($workbasket->status == 'Approval Rejected') || ($workbasket->status == 'Re-editing')) {
                                                echo ("<a title='Withdraw this workbasket' href='/workbaskets/withdraw.html?workbasket_id=" . $workbasket->workbasket_id . "'><img alt='Withdraw workbasket' src='/assets/images/withdraw.png' /></a>\r\n");
                                            } else {
                                                echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                            }
                                            # View icon
                                            echo ("<a class='govuk-link' href='/workbaskets/view.html?workbasket_id=" . $workbasket->workbasket_id . "' title='View this workbasket'><img alt='View workbasket' src='/assets/images/view.png' /></a>\r\n");

                                            # Submit icon
                                            if (($workbasket->status == 'In Progress') || ($workbasket->status == 'xApproval Rejected')) {
                                                echo ("<a title='Submit workbasket for approval' href=''><img alt='Submit workbasket' src='/assets/images/submit.png' /></a>\r\n");
                                            } else {
                                                echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                            }
                                        } else {
                                            // The buttons to show for the approver
                                            # Open / close icon
                                            if ($workbasket->user_id == $application->session->user_id) {
                                                if ($current == true) {
                                                    echo ("<a title='Close this workbasket' href='/workbaskets/actions.php?action=close'><img alt='Close workbasket' src='/assets/images/close.png' /></a>\r\n");
                                                } else {
                                                    if ($workbasket->status == 'In Progress') {
                                                        echo ("<a title='Open this workbasket' href='/workbaskets/actions.php?action=open&workbasket_id=" . $workbasket->workbasket_id . "'><img alt='Open workbasket' src='/assets/images/open.png' /></a>\r\n");
                                                    } elseif ($workbasket->status == 'Published') {
                                                        echo ("<a title='Archive this workbasket' href='/workbaskets/actions.php?action=archive'><img alt='Archive workbasket' src='/assets/images/archive.png' /></a>\r\n");
                                                    } else {
                                                        echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                                    }
                                                }
                                            } else {
                                                echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                            }




                                            # Withdraw icon
                                            if ($workbasket->user_id == $application->session->user_id) {
                                                if (($workbasket->status == 'In Progress') || ($workbasket->status == 'Awaiting Approval') || ($workbasket->status == 'Approval Rejected') || ($workbasket->status == 'Re-editing')) {
                                                    echo ("<a title='Withdraw this workbasket' href='/workbaskets/withdraw.html?workbasket_id=" . $workbasket->workbasket_id . "'><img alt='Withdraw workbasket' src='/assets/images/withdraw.png' /></a>\r\n");
                                                } else {
                                                    echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                                }
                                            }


                                            # View icon
                                            echo ("<a class='govuk-link' href='/workbaskets/view.html?workbasket_id=" . $workbasket->workbasket_id . "' title='View this workbasket' href=''><img src='/assets/images/view.png' /></a>\r\n");

                                            # Submit icon
                                            if ($workbasket->user_id == $application->session->user_id) {
                                                if (($workbasket->status == 'In Progress') || ($workbasket->status == 'xApproval Rejected')) {
                                                    echo ("<a title='Submit workbasket for approval' href=''><img alt='Submit workbasket' src='/assets/images/submit.png' /></a>\r\n");
                                                } else {
                                                    echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                                }
                                            } else {
                                                echo ("<img alt='' src='/assets/images/blank.png' />\r\n");
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>


                </div>
            </div>

        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>