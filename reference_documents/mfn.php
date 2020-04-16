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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">MFN schedules</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">MFN schedules</h1>
                    <!-- End main title //-->

                    <?php
                    //pre ($application->reference_documents);
                    new inset_control(
                        $text = "Use this screen to generate the tariff schedule (a list of all commodity codes and the applicable MFN (third country) duties, and the goods classification document, which lists the full set of UK commodity codes by chapter.",
                    );

                    ?>


                    <table class="govuk-table  govuk-table--l sticky" id="results">
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header  tip ">Document type</th>
                                <th scope="col" class="govuk-table__header  tip  nw">Chapters</th>
                                <th scope="col" class="govuk-table__header  tip  nw">Last time checked for update</th>
                                <th scope="col" class="govuk-table__header  tip ">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                        <tr class="govuk-table__row ">
                                <td class="govuk-table__cell ">Schedule</td>
                                <td class="govuk-table__cell nw">1 - 99</td>
                                <td class="govuk-table__cell nw">March 26, 2020, 7:23 a.m.</td>
                                <td class="govuk-table__cell ">
                                    <ul class="measure_activity_action_list" style="margin-bottom:0.5em !important">
                                        <li><a class="govuk-link" href="#">Download</a></li>
                                        <li><a class="govuk-link" href="#">Regenerate</a></li>
                                    </ul>
                                    <p class="govuk-body-xs">Last updated: Mon Mar 09 2020 13:36:22</p>
                                </td>
                            </tr>
                            <tr class="govuk-table__row ">
                                <td class="govuk-table__cell ">Classification</td>
                                <td class="govuk-table__cell nw">1 - 99</td>
                                <td class="govuk-table__cell nw">March 26, 2020, 7:23 a.m.</td>
                                <td class="govuk-table__cell ">
                                    <ul class="measure_activity_action_list" style="margin-bottom:0.5em !important">
                                        <li><a class="govuk-link" href="#">Download</a></li>
                                        <li><a class="govuk-link" href="#">Regenerate</a></li>
                                    </ul>
                                    <p class="govuk-body-xs">Last updated: Mon Mar 09 2020 13:36:22</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>



                </div>
            </div>


        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>