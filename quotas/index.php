<?php
ini_set('memory_limit', '2048M');
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers");
$application->get_filter_options();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $application->get_quotas();
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
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Find and edit quotas</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <form method="post" action="#results">
                        <?php
                        new title_control("oc", "", "", "Find and edit quotas");
                        new inset_control("Enter search criteria to find quotas. Please separate multiple terms with commas, semi-colons or spaces. Alternatively <a class='govuk-link' href='create_edit.html'>create a new quota</a>.");
                        ?>

                        <!-- Start order number row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="quota_order_number_id">
                                        Order number
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="quota_order_number_id_operator" name="quota_order_number_id_operator">
                                        <!--<option value="">-- Unspecified --</option>//-->
                                        <option <?php if (get_formvar("quota_order_number_id_operator") == "starts_with") { echo ("selected");} ?> value="starts_with">starts with</option>
                                        <option <?php if (get_formvar("quota_order_number_id_operator") == "is_one_of") { echo ("selected");} ?> value="is_one_of">is one of</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input value="<?= get_formvar("quota_order_number_id") ?>" class="govuk-input" id="quota_order_number_id" name="quota_order_number_id" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End order number row //-->



                        <!-- Start mechanism row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label">
                                        Administration mechanism
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label">
                                        &nbsp;
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <div class="govuk-radios govuk-radios--inline">
                                        <div class="govuk-checkboxes__item">
                                            <input <?php if (in_array("FCFS", get_form_array("administration_mechanism"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="administration_mechanism_fcfs" name="administration_mechanism[]" type="checkbox" value="FCFS">
                                            <label class="govuk-label govuk-checkboxes__label" for="administration_mechanism_fcfs">
                                                First come, first served (FCFS) quotas
                                            </label>
                                        </div>
                                        <div class="govuk-checkboxes__item">
                                            <input <?php if (in_array("Licensed", get_form_array("administration_mechanism"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="administration_mechanism_licensed" name="administration_mechanism[]" type="checkbox" value="Licensed">
                                            <label class="govuk-label govuk-checkboxes__label" for="administration_mechanism_licensed">
                                                Licensed quotas
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End mechanism row //-->



                        <!-- Start quota category row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label">
                                        Quota category
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label">
                                        &nbsp;
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">

                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("WTO", get_form_array("quota_category"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="quota_category_wto" name="quota_category[]" type="checkbox" value="WTO">
                                        <label class="govuk-label govuk-checkboxes__label" for="quota_category_wto">
                                            WTO quota
                                        </label>
                                    </div>
                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("ATQ", get_form_array("quota_category"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="quota_category_atq" name="quota_category[]" type="checkbox" value="ATQ">
                                        <label class="govuk-label govuk-checkboxes__label" for="quota_category_atq">
                                            ATQ (Autonomous tariff quota)
                                        </label>
                                    </div>
                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("PRF", get_form_array("quota_category"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="quota_category_preferential" name="quota_category[]" type="checkbox" value="PRF">
                                        <label class="govuk-label govuk-checkboxes__label" for="quota_category_preferential">
                                            Preferential tariff rate quota
                                        </label>
                                    </div>
                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("SAF", get_form_array("quota_category"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="quota_category_safeguard" name="quota_category[]" type="checkbox" value="SAF">
                                        <label class="govuk-label govuk-checkboxes__label" for="quota_category_safeguard">
                                            Safeguard
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End quota category row //-->




                        <!-- Start description row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="description">
                                        Description
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="description">
                                        contains
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input value="<?= get_formvar("description") ?>" class="govuk-input" id="description" name="description" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End description row //-->


                        <!-- Start origin row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="origin">
                                        Origin(s)
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="origin">
                                        is one of
                                    </label>
                                </div>

                                <div class="complex_search_form complex_search_form_column2">
                                    <input value="<?= strtoupper(get_formvar("origin")) ?>" class="govuk-input" id="origin" name="origin" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End origin row //-->

                        <!-- Start origin quota row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="origin_quota">
                                        Origin quota?
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="origin_quota">

                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">

                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("yes", get_form_array("origin_quota"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="origin_quota_yes" name="origin_quota[]" type="checkbox" value="yes">
                                        <label class="govuk-label govuk-checkboxes__label" for="origin_quota_yes">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="govuk-checkboxes__item">
                                        <input <?php if (in_array("no", get_form_array("origin_quota"))) { echo ("checked");} ?> class="govuk-checkboxes__input" id="origin_quota_no" name="origin_quota[]" type="checkbox" value="no">
                                        <label class="govuk-label govuk-checkboxes__label" for="origin_quota_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End origin quota row //-->


                        <div class="govuk-!-margin-top-2">
                            <?php
                            new button_control("Search", "search", "primary", true, "");
                            new button_control("Clear form", "clear_button", "text", true, "x");
                            new button_control("Cancel", "cancel", "text", false, "/");
                            ?>
                        </div>

                        <input type="hidden" name="submitted" id="submitted" value="1" />

                    </form>
                    <?php
                    if ($submitted == 1) {
                        $filter_content = array();
                        $application->search_form = new search_form($application->quotas, $filter_content);
                    }
                    ?>

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>