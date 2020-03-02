<?php
require(dirname(__FILE__) . "../../includes/db.php");
$error_handler = new error_handler();
$application = new application;
$key = get_querystring("key");
$certificate = new certificate();
$certificate->certificate_type_code = get_querystring("certificate_type_code");
$certificate->certificate_code = get_querystring("certificate_code");
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
                    <a class="govuk-breadcrumbs__link" href="/certificates">Certificates</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/certificates/view.html?mode=view&certificate_code=<?= $certificate->certificate_code ?>&certificate_type_code=<?= $certificate->certificate_type_code ?>#tab_certificate_descriptions">Certificate <?= $certificate->certificate_type_code ?><?= $certificate->certificate_code ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete certificate description</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Certificate description <?= $key ?> has been deleted.
                        </h1>
                        <div class="govuk-panel__body">
                            This change has been added to your workbasket<br /><br />&quot;<?= $application->session->workbasket->title ?>&quot;
                        </div>

                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <ul class="govuk-list">
                        <li><a class="govuk-link" href="<?= $certificate->view_url() ?>">View certificate <?= $certificate->certificate_type_code ?><?= $certificate->certificate_code ?></a></li>
                        <li><a href="/certificates">Manage more certificates</a></li>
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