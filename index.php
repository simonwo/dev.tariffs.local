<?php
require(dirname(__FILE__) . "/includes/db.php");
$application = new application;
$application->clear_filter_cookies();
$application->init("workbaskets", "/workbaskets/config.json");
$application->get_filter_options();
$application->get_workbasket_statuses();
$application->get_workbaskets();
$application->get_workbasket_ownerships();
$filter_content = array();
array_push($filter_content, $application->workbasket_ownerships);
array_push($filter_content, $application->workbasket_statuses);


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
                    <!--<h2 class="govuk-heading-s">You are logged on as <?= $application->session->user_id ?> with permissions <?= $application->session->permissions ?></h2>//-->
                    <?php
                    $workbasket_count = $application->get_workbasket_count();
                    if ($workbasket_count > 0) {
                    ?>
                        <!-- End main title //-->
                        <p class="govuk-body">Use the form below to search for existing workbaskets. Alternatively, <a class="govuk-link" href="/workbaskets/create_edit.html">create new workbasket</a>.</p>

                    <?php
                        new search_form(
                            $application->workbaskets,
                            $filter_content
                        );
                    } else {
                        ?>
                        <p class="govuk-body">There are currently no workbaskets. Click to <a class="govuk-link" href="/workbaskets/create_edit.html">create new workbasket</a>.</p>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>
</body>

</html>