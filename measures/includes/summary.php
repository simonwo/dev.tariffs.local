<?php
global $measure_activity;
?>
<h2 class="govuk-heading-m">Shared data</h2>
<dl class="govuk-summary-list govuk-!-margin-bottom-2">
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Measure start date</dt>
        <dd class="govuk-summary-list__value"><?=short_date($measure_activity->validity_start_date) ?></dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Measure end date</dt>
        <dd class="govuk-summary-list__value"><?=short_date($measure_activity->validity_end_date) ?></dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Regulation</dt>
        <dd class="govuk-summary-list__value">
            <p class="govuk-body"><?=$measure_activity->measure_generating_regulation_id ?></p>
            <p class="govuk-body"><?=$measure_activity->regulation_information_text ?></p>
        </dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Measure type</dt>
        <dd class="govuk-summary-list__value"><?=$measure_activity->measure_type_id ?> - <?=$measure_activity->measure_type_description ?></dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Quota order number</dt>
        <dd class="govuk-summary-list__value"><?=$measure_activity->quota_order_number_id ?></dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Geography</dt>
        <dd class="govuk-summary-list__value">
            <p class="govuk-body"><?=$measure_activity->geographical_area_id ?></p>
            <p class="govuk-body">IN, TR, EG</p>
        </dd>
    </div>
</dl>
<p class="govuk-body"><a class="govuk-link" href="./create_edit_core.html">Edit shared data</a></p>



<h2 class="govuk-heading-m">Commodities and additional codes</h2>
<dl class="govuk-summary-list govuk-!-margin-bottom-2">
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Commodities</dt>
        <dd class="govuk-summary-list__value mono-s">
        <?=$measure_activity->commodity_codes ?>
        </dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">Additional codes</dt>
        <dd class="govuk-summary-list__value mono-s"><?=na($measure_activity->additional_codes, "n/a") ?></dd>
    </div>
</dl>
<p class="govuk-body"><a class="govuk-link" href="./create_edit_conditions.html">Edit commodities and additional codes</a></p>




<h2 class="govuk-heading-m">Conditions</h2>
<?php 
if (count($measure_activity->measure_conditions) == 0) {
    p("There are no measure conditions for this activity.");
} else {
    echo ('<dl class="govuk-summary-list govuk-!-margin-bottom-2">');
    $last_condition_code = "";
    foreach ($measure_activity->measure_conditions as $c) {
        if ($c->condition_code != $last_condition_code) {
            if ($last_condition_code != "") {
                echo ('</ol>');
                echo ('</dd>');
                echo ('</div>');
            }
            echo ('<div class="govuk-summary-list__row">');
            echo ('<dt class="govuk-summary-list__key">Condition code ' . $c->condition_code . '<br />');
            echo ('<span class="normal">Presentation of an anti-dumping/countervailing document</span>');
            echo ('</dt>');
            echo ('<dd class="govuk-summary-list__value">');
            echo ('<ol class="govuk-list govuk-list--number">');
        }
        if ($c->condition_code_type == 0)  {
            if ($c->certificate_type_code != '') {
                $s = "On presentation of document <strong>" . $c->certificate_type_code . $c->certificate_code . "</strong>, ";
            } else {
                $s = "In the absence of the specified document, ";
            }
        } else {
            $s = "If the 'reference price' > <strong>" . $c->reference_price . "</strong>, ";
        }
        $s .= $c->action_abbreviation . ' [action code ' . $c->action_code . ']';
        if ($c->applicable_duty != "") {
            $s .= ". The applicable duty is " . $c->applicable_duty;
        }
        echo ('<li>' . $s . '</li>');
        $last_condition_code = $c->condition_code;
    }
    echo ('</ol>');
    echo ('</dd>');
    echo ('</div>');
    echo ('</dl>');
?>

<p class="govuk-body"><a class="govuk-link" href="./create_edit_conditions.html">Edit conditions</a></p>
<?php
}
?>


<h2 class="govuk-heading-m">Duties</h2>
<table class="govuk-table govuk-table--m sticky">
    <thead class="govuk-table__head">
        <tr class="govuk-table__row">
            <th scope="col" class="govuk-table__header">Commodity code</th>
            <th scope="col" class="govuk-table__header">Additional code</th>
            <th scope="col" class="govuk-table__header">Duty - condition F1</th>
            <th scope="col" class="govuk-table__header">Duty - condition F2</th>
            <th scope="col" class="govuk-table__header">Duty - condition F3</th>
        </tr>
    </thead>
    <tbody class="govuk-table__body">
        <?php
        for ($i = 0; $i < 100; $i++) {
        ?>
            <tr class="govuk-table__row">
                <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0123456789", "s") ?></th>
                <td class="govuk-table__cell">C233</td>
                <td class="govuk-table__cell">4%</td>
                <td class="govuk-table__cell">1%</td>
                <td class="govuk-table__cell">0%</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<p class="govuk-body"><a class="govuk-link" href="./create_edit_duties.html">Edit duties</a></p>



<h2 class="govuk-heading-m">Footnotes</h2>
<dl class="govuk-summary-list govuk-!-margin-bottom-2">
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">TM001</dt>
        <dd class="govuk-summary-list__value">Itaque his sapiens semper vacabit. At enim sequor utilitatem. Iam enim adesse poterit.
            Quae similitudo in genere etiam humano apparet. Eam tum adesse, cum dolor omnis absit;</dd>
    </div>
    <div class="govuk-summary-list__row">
        <dt class="govuk-summary-list__key">TM002</dt>
        <dd class="govuk-summary-list__value">Efficiens dici potest. A mene tu? Non potes, nisi retexueris illa. Ut optime,
            secundum naturam affectum esse possit. Et non ex maxima parte de tota iudicabis? Idem iste, inquam, de voluptate
            quid sentit? </dd>
    </div>
</dl>

<p class="govuk-body"><a class="govuk-link" href="./create_edit_footnotes.html">Edit footnotes</a></p>

