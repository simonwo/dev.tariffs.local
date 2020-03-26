<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("fta_reference");
$application->get_reference_documents();
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
                    <a class="govuk-breadcrumbs__link" href="/reference_documents/">Reference documents</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Trade Agreements</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Trade Agreement reference documents</h1>
                    <!-- End main title //-->

                    <p class="govuk-body"><a class="govuk-link" href="./create_edit.html?mode=insert">Create a new reference document</a></p>

                    <?php
                    //pre ($application->reference_documents);
                    new warning_control(
                        $text = "Download and regenerate links are dummies. Edit link takes you the edit page, where the form does not 'do anything' for show only. Create new reference document link above the table and below the table take you to the create form (which again does nothing).",
                    );
            
                    new table_control($application->reference_documents, "", "<p class='govuk-body' style='margin-top:2em;margin-bottom:2em'>There are currently no reference documents created.</p>", "");
                    ?>
                    <p class="govuk-body"><a class="govuk-link" href="./create_edit.html?mode=insert">Create a new reference document</a></p>

                </div>
            </div>


        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>