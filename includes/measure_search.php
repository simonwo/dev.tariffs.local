<?php
#$vars = get_defined_vars();
#print_r($vars);
?>
<form action="/actions/measure_actions.html" method="get" class="inline_form">
    <input type="hidden" name="phase" value="<?=$phase?>" />
    <h3 style="margin-bottom:0px">Search by ...</h3>
    <table class="govuk-table" cellspacing="0" style="width:75%">
        <tr>
            <td class="medium nopad" style="width:20%"><label for="measure_sid">Measure SID</label></td>
            <td><input value="" class="govuk-input small" id="measure_sid" maxlength="10" style="width:200px" name="measure_sid" type="text"></td>
        </tr>
        <tr>
            <td class="medium nopad"><label for="goods_nomenclature_item_id">Commodity code</label></td>
            <td><input value="" class="govuk-input small" id="goods_nomenclature_item_id" maxlength="14" style="width:200px" name="goods_nomenclature_item_id" type="text"></td>
        </tr>
        <tr>
            <td class="medium nopad"><label for="measure_type_id">Measure type</label></td>
            <td><input value="" class="govuk-input small" id="measure_type_id" maxlength="3" style="width:100px" name="measure_type_id" type="text"></td>
        </tr>
        <tr>
            <td class="medium nopad"><label for="geographical_area_id">Geographical area ID</label></td>
            <td><input value="" class="govuk-input small" id="geographical_area_id" maxlength="4" style="width:100px" name="geographical_area_id" type="text"></td>
        </tr>


        <tr>
            <td colspan="2" class="medium nopad"><button type="submit" class="govuk-button small">Search</button></td>
        </tr>
    </table>
</form>
