<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler;
$application->init("workbaskets");
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
        $workbasket_id = $application->session->workbasket->workbasket_id;
        $workbasket_item_id = get_querystring("id");
        $workbasket = $application->session->get_workbasket_for_withdrawal($workbasket_id);
        //prend ($application->session);
        //h1 ($workbasket_item_id);
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Delete workbasket item</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Delete workbasket item '<?= $workbasket->get_workbasket_item($workbasket_item_id) ?>'</h1>
                    <!-- End main title //-->


                    <form action="actions.php" method="get">

                        <?php
                        new radio_control(
                            $label = "Are you sure you want to delete this workbasket activity?",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "The content of the activity will be deleted and this action cannot be undone.",
                            $control_name = "withdraw_workbasket_item",
                            $dataset = $application->get_yes_no(),
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true,
                            $disabled_on_edit = false
                        );

                        ?>
                        <?php
                        new hidden_control("workbasket_item_id", $workbasket_item_id);
                        new hidden_control("action", "delete_workbasket_item");
                        $btn = new button_control("Continue", "withdraw", "primary");
                        $btn = new button_control("Cancel", "cancel", "text", "", "/");
                        ?>
                    </form>
                </div>
            </div>
        </main>

    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>