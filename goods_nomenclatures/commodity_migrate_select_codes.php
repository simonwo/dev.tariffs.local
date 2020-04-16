<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("migrate_select_codes", "config_migrate_commodities.json");
$error_handler = new error_handler();

$gn = new goods_nomenclature;
$gn->goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
$gn->goods_nomenclature_sid = get_querystring("goods_nomenclature_sid");
$gn->productline_suffix = get_querystring("productline_suffix");
$gn->get_hierarchy("down");

$commodity_migrate_activity = new commodity_migrate_activity();
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
        new data_entry_form($control_content, $commodity_migrate_activity, $left_nav = "", "commodity_migrate_actions.php");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>