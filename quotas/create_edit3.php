<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers3");

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

//pre ($_REQUEST);


if ($submitted == 1) {
    $_SESSION["commodity_codes"] = get_formvar("commodity_codes");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step3();
} else {
    $quota_order_number = new quota_order_number();
    $quota_order_number->get_parameters();
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
        $control_content = array();
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); // , "create_edit4.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

