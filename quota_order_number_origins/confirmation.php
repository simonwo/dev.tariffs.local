<?php
require(dirname(__FILE__) . "../../includes/db.php");
$error_handler = new error_handler();
$application = new application;
$key = get_querystring("key");
$quota_order_number_sid = get_querystring("quota_order_number_sid");
$quota_order_number_id = get_querystring("quota_order_number_id");
if ($application->mode == "insert") {
    $verb = "created";
} else {
    $verb = "updated";
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

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Quota suspension period <?= $key ?> has been <?= $verb ?>.
                        </h1>
                        <div class="govuk-panel__body">
                            This change has been added to your workbasket<br /><br />&quot;<?=$application->session->workbasket->title?>&quot;
                        </div>
                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <ul class="govuk-list">
                        <li><a class="govuk-link" href="/quotas/view.html?mode=view&quota_order_number_sid=<?=$quota_order_number_sid?>&quota_order_number_id=<?=$quota_order_number_id?>">View quota <?=$quota_order_number_id?></a></li>
                        <li><a class="govuk-link" href="/workbaskets/view.html">View content of your workbasket</a></li>
                        <li><a class="govuk-link" href="/">Return to main menu</a></li>
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