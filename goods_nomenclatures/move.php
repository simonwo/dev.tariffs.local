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
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Move commodity code</h1>
                    <!-- End main title //-->
                    <?php
                    new inset_control("Use this screen to move commodity code " . format_goods_nomenclature_item_id("0102030405") . " to a new position in the hierarchy.");
                    ?>
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <p class="govuk-body">You are moving commodity code <?=format_goods_nomenclature_item_id("0102030405")?> from its position in the goods classification as described below:</p>
                    <table class="govuk-table govuk-table--m sticky" cellspacing="0">
                        <tbody><tr class="govuk-table__row">
                            <th style="width:10%" scope="col" class="govuk-table__header nopad">Commodity</th>
                            <th style="width:6%" scope="col" class="govuk-table__header c">Suffix</th>
                            <th style="width:6%" scope="col" class="govuk-table__header c">Indents</th>
                            <th style="width:73%" scope="col" class="govuk-table__header">Description</th>
                            <th style="width:5%" scope="col" class="govuk-table__header c nw">End line</th>
                        </tr>
                                                    <tr class="govuk-table__row  selected">
                                <td class="govuk-table__cell nopad"><a class="nodecorate" href="view.html?goods_nomenclature_item_id=0500000000&amp;productline_suffix=80#hierarchy"><span class="rpad mauve ">05</span><span class="rpad mauve ">00</span><span class="rpad mauve ">00</span><span class="rpad blue ">00</span><span class="rpad green ">00</span></a></td>
                                <td class="govuk-table__cell c">80</td>
                                <td class="govuk-table__cell c">0</td>
                                <td class="govuk-table__cell indent-1">PRODUCTS OF ANIMAL ORIGIN, NOT ELSEWHERE SPECIFIED OR INCLUDED</td>
                                <td class="govuk-table__cell c"></td>
                            </tr>

                                                    <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nopad"><a class="nodecorate" href="view.html?goods_nomenclature_item_id=0501000000&amp;productline_suffix=80#hierarchy"><span class="rpad mauve ">05</span><span class="rpad mauve ">01</span><span class="rpad mauve ">00</span><span class="rpad blue ">00</span><span class="rpad green ">00</span></a></td>
                                <td class="govuk-table__cell c">80</td>
                                <td class="govuk-table__cell c">1</td>
                                <td class="govuk-table__cell indent0">Human hair, unworked, whether or not washed or scoured; waste of human hair</td>
                                <td class="govuk-table__cell c">Y</td>
                            </tr>

                                                    <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nopad"><a class="nodecorate" href="view.html?goods_nomenclature_item_id=0502000000&amp;productline_suffix=80#hierarchy"><span class="rpad mauve ">05</span><span class="rpad mauve ">02</span><span class="rpad mauve ">00</span><span class="rpad blue ">00</span><span class="rpad green ">00</span></a></td>
                                <td class="govuk-table__cell c">80</td>
                                <td class="govuk-table__cell c">1</td>
                                <td class="govuk-table__cell indent0">Pigs', hogs' or boars' bristles and hair; badger hair and other brush making hair; waste of such bristles or hair</td>
                                <td class="govuk-table__cell c"></td>
                            </tr>


                                            </tbody></table>

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>