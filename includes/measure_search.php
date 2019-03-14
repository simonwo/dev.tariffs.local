<?php
#$vars = get_defined_vars();
#print_r($vars);
?>
<form action="/actions/measure_actions.html" method="get" class="inline_form">
    <input type="hidden" name="phase" value="<?=$phase?>" />
    <h3>Measure search</h3>
    <div class="column-one-third" style="width:320px">
        <div class="govuk-form-group">
            <fieldset class="govuk-fieldset" aria-describedby="base_regulation_hint" role="group">
                <span id="base_regulation_hint" class="govuk-hint">Enter commodity code</span>
                <div class="govuk-date-input" id="measure_start">
                    <div class="govuk-date-input__item">
                        <div class="govuk-form-group" style="padding:0px;margin:0px">
                            <input value="" class="govuk-input govuk-date-input__input govuk-input--width-16" id="goods_nomenclature_item_id" maxlength="100" style="width:300px" name="goods_nomenclature_item_id" type="text">
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    
    <div class="column-one-third">
        <div class="govuk-form-group" style="padding:0px;margin:0px">
            <button type="submit" class="govuk-button" style="margin-top:36px">Search</button>
        </div>
    </div>
    <div class="clearer"><!--&nbsp;//--></div>
</form>
