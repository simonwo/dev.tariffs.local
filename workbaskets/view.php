<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("workbaskets");
$error_handler = new error_handler();
$workbasket = new workbasket();
$workbasket->workbasket_id = get_querystring("workbasket_id");
if (($workbasket->workbasket_id == "") || ($workbasket->workbasket_id == null)) {
    $workbasket->workbasket_id = $application->session->workbasket->workbasket_id;
}
$workbasket->populate();
if (isset($application->session->workbasket->workbasket_id)) {
    $test = $application->session->workbasket->workbasket_id;
} else {
    $test = -1;
}
if ($workbasket->workbasket_id == $test) {
    $current = true;
    $active = " (active)";
} else {
    $current = false;
    $active = "";
}

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

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/#workbaskets">Workbaskets</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">View workbasket</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Workbasket &quot;<?= $workbasket->title ?>&quot;</h1>
                    <!-- End main title //-->

                    <table class="govuk-table">
                        <caption class="govuk-table__caption--m">About this workbasket</caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header" style="width:25%;display:none;">Field</th>
                                <th scope="col" class="govuk-table__header" style="width:75%;display:none;">Value</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket ID</th>
                                <td class="govuk-table__cell" style="width:75%"><?= $workbasket->workbasket_id ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket name</th>
                                <td class="govuk-table__cell" style="width:75%"><?= $workbasket->title ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Reason</th>
                                <td class="govuk-table__cell"><?= $workbasket->reason ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">User</th>
                                <td class="govuk-table__cell">Matt Lavis</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Created</th>
                                <td class="govuk-table__cell">01 Jan 2020 09:09</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Last amended</th>
                                <td class="govuk-table__cell">01 Jan 2020 09:39</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Status</th>
                                <td class="govuk-table__cell status_cell"><?= status_image($workbasket->status) ?><span><?= $workbasket->status ?><?= $active ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php

                    if ($application->session->uid == $workbasket->user_id) {
                        if (in_array($workbasket->status, array("In progress", "Rejected"))) {
                    ?>
                            <p class="govuk-body"><a class="govuk-link" href="edit_workbasket.html?workbasket_id=<?= $workbasket->workbasket_id ?>">Edit workbasket detail</a></p>
                            <?php
                            if ($current == false) {
                            ?>
                                <p class="govuk-body"><a class="govuk-link" title='Open this workbasket' href='/workbaskets/actions.php?action=open&workbasket_id=<?= $workbasket->workbasket_id ?>'>Open this workbasket</a></p>
                            <?php
                            } else {
                            ?>
                                <p class="govuk-body"><a class="govuk-link" title='Close this workbasket' href='/workbaskets/actions.php?action=close&workbasket_id=<?= $workbasket->workbasket_id ?>'>Close this workbasket</a></p>
                            <?php
                            }
                            ?>

                    <?php
                        }
                    }
                    ?>

                    <h2 id="workbasket_activities" class="govuk-heading-m">Workbasket activities</h2>
                    <?php
                    if ($workbasket->activity_count == 0) {
                        echo ('<p class="govuk-body">No activities have yet been added to this workbasket.</p>');
                    } else {
                        $count_by_status_string = "";
                        foreach ($workbasket->counts_by_status as $item) {
                            $s = $item->status_count . " x " . $item->status;
                            $count_by_status_string .= $s . ", ";
                        }
                        $count_by_status_string = trim($count_by_status_string);
                        $count_by_status_string = trim($count_by_status_string, ",");

                        echo ('<p class="govuk-body">This workbasket contains contains the following ' . $workbasket->activity_count . ' changes: (' . $count_by_status_string . ')</p>');
                        echo ('<div class="govuk-accordion" data-module="govuk-accordion" id="accordion-with-summary-sections">');
                        $workbasket->workbasket_get_footnote_types();
                        $workbasket->workbasket_get_certificate_types();
                        $workbasket->workbasket_get_additional_code_types();
                        $workbasket->workbasket_get_measure_types();
                        $workbasket->workbasket_get_footnotes();
                        $workbasket->workbasket_get_certificates();
                        $workbasket->workbasket_get_additional_codes();
                        $workbasket->workbasket_get_regulations();
                        $workbasket->workbasket_get_geographical_areas();
                        $workbasket->workbasket_get_measure_activities();
                        $workbasket->workbasket_get_quota_suspension_periods();
                        $workbasket->workbasket_get_quota_blocking_periods();
                        echo ('</div>');

                    ?>

                        <form method="get" action="actions.php">

                        <?php
                        new hidden_control("workbasket_id", $workbasket->workbasket_id);
                        //h1($workbasket->status);
                        switch ($workbasket->status) {
                            case "In progress":
                                new hidden_control("action", "submit_for_approval");
                                new button_control("Submit workbasket for approval", "submit_workbasket", "primary", true);
                                break;
                            case "Awaiting approval":
                                if ($application->session->permissions == "Approver") {
                                    //pre($workbasket->counts_by_status);
                                    $approved_count = 0;
                                    foreach ($workbasket->counts_by_status as $item) {
                                        if ($item->status == 'Approved') {
                                            $approved_count = $item->status_count;
                                            break;
                                        }
                                    }
                                    if ($workbasket->activity_count == $approved_count) {
                                        new hidden_control("action", "send_to_cds");
                                        new button_control("Send workbasket to CDS", "send_to_cds", "primary", true);
                                    } else {
                                        p ("The workbasket cannot be submitted until all activities have been approved.");
                                    }
                                }
                                break;
                        }
                    }
                        ?>
                        </form>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>