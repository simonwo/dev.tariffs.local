<?php
require(dirname(__FILE__) . "../../includes/db.php");
$error_handler = new error_handler();
$application = new application;
$request_uri = get_querystring("request_uri");
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
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Your workbasket has been created.
                        </h1>
                        <div class="govuk-panel__body">
                            Workbasket name<br><strong><?=$application->session->workbasket->title?></strong>
                        </div>
                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <ul class="govuk-list">
                        <li>Return to main menu to <a class='govuk-link' href="/">begin making changes</a> or <a class='govuk-link' href="<?=$request_uri?>">carry on where you left off</a>.</li>
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