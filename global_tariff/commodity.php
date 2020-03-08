    <?php
    require(dirname(__FILE__) . "../../includes/db.php");
    $application = new application;
    $application->init("goods_nomenclatures");
    $snapshot = new snapshot();
    $snapshot->get_parameters();
    $section = new section(get_querystring("section_id"));
    $section->get_section();
    $goods_nomenclature = new goods_nomenclature();
    $goods_nomenclature->section_id = get_querystring("section_id");
    $goods_nomenclature->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
    $goods_nomenclature->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
    $goods_nomenclature->producline_suffix = get_querystring("producline_suffix");
    $goods_nomenclature->populate();
    $heading_text = new heading_text();

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
                        <?php
                        new title_control("", "", "", "The UK Global Tariff");
                        if ($heading_text->text != "") {
                            new inset_control($heading_text->text);
                        }

                        ?>


                        <form action="#table_intro" name="frmSnapshot" id="frmSnapshot">
                            <input type="hidden" name="wts" id="wts" value="1" />
                            <!-- Start error handler //-->
                            <!--
                        <?= $snapshot->error_handler->get_primary_error_block() ?>
                        //-->
                            <!-- End error handler //-->

                            <!-- Begin validity start date fields //-->
                            <!--
                        <div class="govuk-form-group <?= $snapshot->error_handler->get_error("snapshot_date_start"); ?>">
                            <fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter date range for snapshot</h1>
                                </legend>
                                <span id="snapshot_hint" class="govuk-hint">Please enter the start date for which you would like to take a database snapshot.</span>
                                <?= $snapshot->error_handler->display_error_message("snapshot_date_start"); ?>
                                <div class="govuk-date-input" id="measure_start">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="day_start">Day</label>
                                            <input required value="<?= $snapshot->day_start ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_start" maxlength="2" name="day_start" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="month_start">Month</label>
                                            <input required value="<?= $snapshot->month_start ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_start" maxlength="2" name="month_start" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="year_start">Year</label>
                                            <input required value="<?= $snapshot->year_start ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_start" maxlength="4" name="year_start" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        //-->
                            <!-- End validity start date fields //-->

                            <!-- Begin validity end date fields //-->
                            <!--
                        <div class="govuk-form-group <?= $snapshot->error_handler->get_error("snapshot_date_end"); ?>">
                            <fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
                                <span id="snapshot_hint" class="govuk-hint">Optionally, if you would like to select data that spans a range of dates,
                                    please enter the end date of this range. If you would like to take a snapshot on a single day only, please leave
                                    these fields blank.</span>
                                <?= $snapshot->error_handler->display_error_message("snapshot_date_end"); ?>
                                <div class="govuk-date-input" id="measure_end">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="day_end">Day</label>
                                            <input value="<?= $snapshot->day_end ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="day_end" maxlength="2" name="day_end" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="month_end">Month</label>
                                            <input value="<?= $snapshot->month_end ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="month_end" maxlength="2" name="month_end" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="year_end">Year</label>
                                            <input value="<?= $snapshot->year_end ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="year_end" maxlength="4" name="year_end" type="text" pattern="[0-9]*">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        //-->
                            <!-- End validity end date fields //-->

                            <!-- Begin commodity range field //-->
                            <!--
                        <div class="govuk-form-group <?= $snapshot->error_handler->get_error("commodity_range"); ?>">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                <h1 id="heading_trade_movement_code" class="govuk-fieldset__heading" style="max-width:100%;"><label for="range">Which commodity range do you want to check?</label></h1>
                            </legend>
                            <span class="govuk-hint" style="max-width:70%">Enter the leading digits of the commodity range you would like to review. If
                                you leave this field blank, the entire dataset will be extracted.</span>
                            <div class="govuk-warning-text">
                                <span class="govuk-warning-text__icon" aria-hidden="true">!</span>
                                <strong class="govuk-warning-text__text">
                                    <span class="govuk-warning-text__assistive">Warning</span>A full extract may take up to 10 minutes to complete.
                                </strong>
                            </div>
                            <?= $snapshot->error_handler->display_error_message("commodity_range"); ?>
                            <input required value="<?= $snapshot->range ?>" class="govuk-input govuk-date-input__input govuk-input--width-10" id="range" maxlength="10" pattern="[0-9]{0,10}" name="range" type="text">
                        </div>
                        //-->
                            <!-- End commodity range field //-->

                            <!-- Begin scope fields //-->
                            <!--
                        <div class="govuk-form-group <?= $snapshot->error_handler->get_error("scope"); ?>">
                            <fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading" style="max-width:100%;">Enter the geographical scope</h1>
                                </legend>
                                <span id="snapshot_hint" class="govuk-hint">If you don't know the geographical area ID, you can <a target="_blank" href="#">find geographical
                                        area IDs</a> here. To extract MFNs, please enter 1011 (the geographical area ID for Erga Omnes).</span>
                                <?= $snapshot->error_handler->display_error_message("scope"); ?>
                                <div class="govuk-date-input" id="measure_start">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <input required value="<?= $snapshot->scope ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="scope" maxlength="4" name="scope" type="text">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        //-->
                            <!-- End validity scope fields //-->

                            <!-- Format field //-->
                            <!--
                        <div class="govuk-form-group <?= $snapshot->error_handler->get_error("snapshot_format"); ?>">
                            <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">In which format would you like to extract the data?</h1>
                                </legend>
                                <span id="changed-name-hint" class="govuk-hint"><abbr title="Comma-Separated Values">CSV</abbr> and <abbr title="JavaScript Object Notation">JSON</abbr> format will open in a new browser window.</span>
                                <?= $snapshot->error_handler->display_error_message("snapshot_format"); ?>
                                <div class="govuk-radios govuk-radios--inline">
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="fmt_screen" name="fmt" type="radio" value="screen">
                                        <label class="govuk-label govuk-radios__label" for="fmt_screen">On screen</label>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="fmt_csv" name="fmt" type="radio" value="csv">
                                        <label class="govuk-label govuk-radios__label" for="fmt_csv"><abbr title="Comma-Separated Values">CSV</abbr> file</label>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="fmt_json" name="fmt" type="radio" value="json">
                                        <label class="govuk-label govuk-radios__label" for="fmt_json"><abbr title="JavaScript Object Notation">JSON</abbr> file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        //-->
                            <!-- Format field //-->
                            <!--
                        <?php
                        if (1 > 2) {
                        ?>
                            <div class="hidden" id="json_fields">

                                <fieldset class="govuk-fieldset" aria-describedby="snapshot_hint" role="group" style="min-width:100%;max-width:100%;">
                                    <legend class="govuk-fieldset__legend govuk-fieldset__legend--m" style="min-width:100%;max-width:100%;">
                                        <h1 class="govuk-fieldset__heading" style="padding-top:1.5em;min-width:100%;border-top:2px #ccc solid">JSON-extract specific fields</h1>
                                    </legend>
                                    <div class="govuk-form-group <?= $snapshot->error_handler->get_error("depth"); ?>">
                                        <span id="snapshot_hint" class="govuk-hint">Enter the required depth : 2, 4, 6 or 8</span>
                                        <?= $snapshot->error_handler->display_error_message("depth"); ?>
                                        <div class="govuk-date-input" id="measure_start">
                                            <div class="govuk-date-input__item">
                                                <div class="govuk-form-group">
                                                    <input value="<?= $snapshot->depth ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="depth" maxlength="4" name="depth" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="govuk-form-group">
                                        <span id="changed-name-hint" class="govuk-hint">Would you like to omit duties?</span>
                                        <div class="govuk-radios govuk-radios--inline">
                                            <div class="govuk-radios__item">
                                                <input class="govuk-radios__input" id="omit_duties_yes" name="omit_duties" type="radio" value="1">
                                                <label class="govuk-label govuk-radios__label" for="omit_duties_yes">Yes</label>
                                            </div>
                                            <div class="govuk-radios__item">
                                                <input class="govuk-radios__input" id="omit_duties_no" name="omit_duties" type="radio" value="0">
                                                <label class="govuk-label govuk-radios__label" for="omit_duties_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        <?php
                        }
                        ?>
                        //-->
                            <!-- Start Submit button //-->
                            <!--
                        <div class="govuk-form-group" style="padding:0px;margin:0px">
                            <button type="submit" class="govuk-button">Generate snapshot</button>
                        </div>
                        //-->
                            <!-- End Submit button //-->


                        </form>
                        <?php
                        if (($snapshot->form_submitted == true) && (count($snapshot->error_handler->error_list) == 0)) {
                            $last_goods_nomenclature_item_id = "";
                            if (count($snapshot->commodities) > 0) {
                        ?>
                                <!--<h3 class="govuk-heading-m" id="table_intro">Notes on the table below - snapshot for <?= short_date($snapshot->snapshot_date_start) ?>
                                <?php
                                if ($snapshot->snapshot_date_end != "") {
                                    echo (" to " . short_date($snapshot->snapshot_date_end));
                                }
                                ?>
                                for <?= $snapshot->geographical_area_description ?></h3>//-->
                                <!--
                                <p class="govuk-body">The table below shows the entire commodity code tree and show the prevalent duties applicable
                                for the jurisdiction or duty type selected. Rows where the description text is grey are those rows where
                                the commodity code is not declarable (either not an end-line or has a product line suffix that is not 80).
                            </p>
                            //-->
                                <?php
                                //require("commodity_search.php");
                                require("commodity_nav.php");
                                ?>
                                <table cellspacing="0" class="govuk-table govuk-table sticky" id="table">
                                    <tr class="govuk-table__row">
                                        <th class="govuk-table__header nopad small" scope="col" style="width:10%">Commodity</th>
                                        <th class="govuk-table__header small" scope="col" style="width:61%">Description</th>
                                        <th class="govuk-table__header r small" scope="col" style="width:12%">Common External Tariff rate</th>
                                        <th class="govuk-table__header r small" scope="col" style="width:12%">Future UK Global Tariff rate</th>
                                        <th class="govuk-table__header r small" scope="col" style="width:5%">Change</th>
                                    </tr>
                                    <?php
                                    foreach ($snapshot->commodities as $commodity) {
                                        if ($commodity->productline_suffix == "80") {
                                            $pls_class = "pls_80";
                                        } else {
                                            $pls_class = "pls_intermediate";
                                        }
                                        $number_indents_real = $commodity->number_indents - 1;
                                        if ($number_indents_real == -1) {
                                            $number_indents_real = 0;
                                        }

                                        if (count($commodity->measure_list) == 0) {
                                            // Just show the commodity
                                    ?>
                                            <tr class="govuk-table__row" valign="top">
                                                <td class="govuk-table__cell vsmall nopad">
                                                    <?php
                                                    if ($commodity->productline_suffix == "80") {
                                                        echo (format_goods_nomenclature_item_id2($commodity->goods_nomenclature_item_id, $commodity->leaf));
                                                    } else {
                                                        echo ("&nbsp;");
                                                    }
                                                    ?>
                                                </td>
                                                <td class="govuk-table__cell gt_indent gt_indent_<?= $number_indents_real ?> <?= $pls_class ?>"><?= $commodity->format_description() ?></td>
                                                <td class="govuk-table__cell vsmall r">&nbsp;</td>
                                                <td class="govuk-table__cell vsmall r">&nbsp;</td>
                                                <td class="govuk-table__cell vsmall r">&nbsp;</td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($commodity->measure_list as $measure) {
                                            ?>
                                                <tr class="govuk-table__row" valign="top">
                                                    <td class="govuk-table__cell vsmall nopad">
                                                        <?php
                                                        if ($commodity->productline_suffix == "80") {
                                                            echo (format_goods_nomenclature_item_id2($commodity->goods_nomenclature_item_id, $commodity->leaf));
                                                        } else {
                                                            echo ("&nbsp;");
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="govuk-table__cell gt_indent gt_indent_<?= $number_indents_real ?> <?= $pls_class ?>"><?= $commodity->format_description() ?></td>
                                                    <td class="govuk-table__cell vsmall nw r">
                                                        <?php
                                                        if (($commodity->productline_suffix == "80") && ($commodity->leaf == "Y")) {
                                                            echo ($measure->combined_duty);
                                                        } else {
                                                            echo ("&nbsp;");
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="govuk-table__cell vsmall nw r">
                                                        <?php
                                                        if (($commodity->productline_suffix == "80") && ($commodity->leaf == "Y")) {
                                                            if ($measure->check_ad_valorem() == true) {
                                                                if ($measure->combined_duty == "0.0%") {
                                                                    $uk_duty = "0.0%";
                                                                } else {
                                                                    $temp = floatval(str_replace("%", "", $measure->combined_duty));
                                                                    if (rand(0, 1) == 0) {
                                                                        $uk_duty = ($temp - 1) . "%";
                                                                    } else {
                                                                        $uk_duty = ($temp + 1) . "%";
                                                                    }
                                                                    // $uk_duty = "Ad valorem";
                                                                }
                                                            } else {
                                                                $uk_duty = $measure->combined_duty;
                                                            }
                                                        } else {
                                                            $uk_duty = "";
                                                        }

                                                        //echo rand(0, 1);
                                                        echo ($uk_duty);
                                                        ?>
                                                    </td>
                                                    <td class="govuk-table__cell vsmall nw c change">
                                                        <?php
                                                        if (floatval($measure->combined_duty) > floatval($uk_duty)) {
                                                            echo ('<img src="/assets/images/down.png" />');
                                                        } elseif (floatval($measure->combined_duty) < floatval($uk_duty)) {
                                                            echo ('<img src="/assets/images/up.png" />');
                                                        } else {
                                                            echo ("&nbsp;");
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </table>
                        <?php
                            }
                        }
                        ?>

                        <a href="javascript:history.back();" class="govuk-back-link">Back</a>
                    </div>



                </div>
        </div>

        </main>
        </div>
        <?php
        //require("../includes/footer.php");
        ?>

    </body>

    </html>