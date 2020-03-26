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
                    new inset_control("This screen lists the EU Tariff files ... the detail of this will all need to worked out with real data and scenarios with the policy team.");

                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">

                    <table cellspacing="0" class="govuk-table govuk-table--m sticky">
                        <tr class="govuk-table__row">
                            <th class="govuk-table__header nopad" scope="col">EU Taric data file</th>
                            <th class="govuk-table__header nopad" scope="col">Date of receipt</th>
                            <th class="govuk-table__header nopad" scope="col">Date represented</th>
                            <th class="govuk-table__header" scope="col">Commodity code changes</th>
                            <th class="govuk-table__header" scope="col">Action</th>
                        </tr>
                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell">TGB21001.xml</td>
                            <td class="govuk-table__cell">01 Jan 2021</td>
                            <td class="govuk-table__cell">02 Jan 2021</td>
                            <td class="govuk-table__cell">
                                <b>New commodity codes (5)</b><br />
                                <span class='mono-s'>0102030405<br />
                                    0102030406<br />
                                    0102030407<br />
                                    0102030408<br />
                                    0102030409</span><br /><br />

                                <b>Modified commodity codes (1)</b><br />
                                <span class='mono-s'>0102030405<br />
                                    0402030409</span><br /><br />

                                <b>Updated commodity code descriptions (3)</b><br />
                                <span class='mono-s'>0202030405<br />
                                    0202030406<br />
                                    0202030407</span>
                            </td>
                            <td class="govuk-table__cell nw">
                                <ul class="measure_activity_action_list">
                                    <li><a class="govuk-link" href='./detail.html'><img src="/assets/images/view.png" /><span>View detail</span></a></li>
                                </ul>
                            </td>
                        </tr>

                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell">TGB21002.xml</td>
                            <td class="govuk-table__cell">02 Jan 2021</td>
                            <td class="govuk-table__cell">03 Jan 2021</td>
                            <td class="govuk-table__cell">No commodity code-related change
                            </td>
                            <td class="govuk-table__cell nw">
                                <ul class="measure_activity_action_list">
                                    <li><a class="govuk-link" href='./detail.html'><img src="/assets/images/view.png" /><span>View detail</span></a></li>
                                </ul>
                            </td>
                        </tr>
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