<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
//$application->init("measures_conditions");
$application->get_conditional_duty_application_options();
$error_handler = new error_handler();
$measure_activity = new measure_activity();
$measure_activity->get_sid();
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
                    <a class="govuk-breadcrumbs__link" href="/measures">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Cancel new measures</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Measure creation has been cancelled.
                        </h1>
                        <div class="govuk-panel__body">
                            This change has been removed from your workbasket<br /><br />&quot;<?= $application->session->workbasket->title ?>&quot;
                        </div>

                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <ul class="govuk-list">
                        <li><a href="/measures">Find and edit measures</a></li>
                        <li><a href="/measures/create_edit.html">Create new measures</a></li>
                        <li><a href="/workbaskets/view.html">View content of your workbasket</a></li>
                        <li><a href="/">Return to main menu</a></li>
                    </ul>
                </div>
            </div>
        </main>


    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>