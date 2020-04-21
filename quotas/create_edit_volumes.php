<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers_volumes");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

//pre ($_REQUEST);

if ($submitted == 1) {
    $periods = array();
    foreach ($_POST as $name => $value) {
        if (strpos($name, "validity_start_date_day_intro_period_") !== false) {
            // Get intro periods
            $quota_definition = new quota_definition();
            $quota_definition->index_number = str_replace("validity_start_date_day_intro_period_", "", $name);
            $quota_definition->period_type = "introductory";

            $validity_start_date_day = $_POST["validity_start_date_day_intro_period_" . $quota_definition->index_number];
            $validity_start_date_month = $_POST["validity_start_date_month_intro_period_" . $quota_definition->index_number];
            $validity_start_date_year = $_POST["validity_start_date_year_intro_period_" . $quota_definition->index_number];
            $quota_definition->validity_start_date = to_date_string($validity_start_date_day, $validity_start_date_month, $validity_start_date_year);

            $validity_end_date_day = $_POST["validity_end_date_day_intro_period_" . $quota_definition->index_number];
            $validity_end_date_month = $_POST["validity_end_date_month_intro_period_" . $quota_definition->index_number];
            $validity_end_date_year = $_POST["validity_end_date_year_intro_period_" . $quota_definition->index_number];
            $quota_definition->validity_end_date = to_date_string($validity_end_date_day, $validity_end_date_month, $validity_end_date_year);

            $quota_definition->period_type = "introductory";
            $quota_definition->critical_state = string_to_bool($_POST["critical_intro_period_" . $quota_definition->index_number]);
            $quota_definition->volume = $_POST["volume_intro_period_" . $quota_definition->index_number];

            array_push($periods, $quota_definition);
        } elseif ((strpos($name, "validity_start_date_year_") !== false) && (strpos($name, "intro") === false)) {
            $quota_definition = new quota_definition();
            $period = substr($name, -1);
            $year = str_replace("validity_start_date_year_", "", $name);
            $year = str_replace("_period_" . $period, "", $year);

            $start_template = "validity_start_date_year_" . $year . "_period_" . $period;
            $end_template = "validity_end_date_year_" . $year . "_period_" . $period;
            $volume_template = "volume_year_" . $year . "_period_" . $period;
            $critical_template = "critical_year_" . $year . "_period_" . $period;

            $quota_definition->validity_start_date = $value;
            $quota_definition->validity_end_date = $_POST[$end_template];
            $quota_definition->critical_state = $_POST[$critical_template];
            $quota_definition->volume = $_POST[$volume_template];
            $quota_definition->period_type = "annual";
            
            //pre ($quota_definition);

        }
        //pre($name . " = " . $value);
    }
    //die();
    //prend($_REQUEST);
    $_SESSION["period_type"] = get_formvar("period_type");
    $_SESSION["validity_start_date_day"] = get_formvar("validity_start_date_day");
    $_SESSION["validity_start_date_month"] = get_formvar("validity_start_date_month");
    $_SESSION["validity_start_date_year"] = get_formvar("validity_start_date_year");
    $_SESSION["validity_end_date_day"] = get_formvar("validity_end_date_day");
    $_SESSION["validity_end_date_month"] = get_formvar("validity_end_date_month");
    $_SESSION["validity_end_date_year"] = get_formvar("validity_end_date_year");
    $_SESSION["year_count"] = get_formvar("year_count");
    $_SESSION["introductory_period_option"] = get_formvar("introductory_period_option");
    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_volumes();
}

$quota_order_number = new quota_order_number;
$quota_order_number->commodity_codes = get_formvar("commodity_codes");
$quota_order_number->get_commodity_code_array();

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
        $control_content = array();
        new data_entry_form($control_content, $quota_order_number, $left_nav = "", "");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>