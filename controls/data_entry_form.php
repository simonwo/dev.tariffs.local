<?php
class data_entry_form
{
    // Class properties and methods go here
    public $dataset = Null;
    public $control_content = Null;
    public $class_name = Null;
    public $root = "./";
    public $show_left_nav = false;
    public $navigation = null;
    public $show_navigation = false;
    public $breadcrumb_insert_text = null;
    public $breadcrumb_insert_url = null;

    public function __construct($control_content, $object, $left_nav, $action = "")
    {
        $this->control_content = $control_content;
        $this->object = $object;
        $this->action = $action;
        $this->get_class_name();

        $this->get_config();
        $this->display();
    }

    private function get_class_name()
    {
        $s = strtolower(get_class($this->object));
        $s = str_replace(" ", "_", $s);
        $this->class_name = $s;
    }

    private function get_config()
    {
        global $application;
        $config = $application->data[$application->tariff_object]["config"];

        // Work out whether to show a left nav or not
        if (isset($application->data["navigation"])) {
            $this->navigation = $application->data["navigation"];
            $this->show_navigation = true;
        }

        $this->object_name = $config["object_name"];
        if (isset($config["override_root"])) {
            $this->root = $config["override_root"];
        } else {
            $this->root = "./";
        }
        if (isset($config["title_create"])) {
            $this->page_title_create = $config["title_create"];
        } else {
            $this->page_title_create = "";
        }
        if (isset($config["title_edit"])) {
            $this->page_title_edit = $config["title_edit"];
        } else {
            $this->page_title_edit = "";
        }
        if (isset($config["title_view"])) {
            $this->page_title_view = $config["title_view"];
        } else {
            $this->page_title_view = "";
        }
        if (isset($config["title_duplicate"])) {
            $this->page_title_duplicate = $config["title_duplicate"];
        } else {
            $this->page_title_duplicate = "";
        }
        $this->validate = $config["validate"];
        if ($this->validate == true) {
            $this->validate_string = "";
        } else {
            $this->validate_string = " novalidate";
        }
        if ($application->mode == "update") {
            $this->page_title = $this->page_title_edit;
        } elseif ($application->mode == "duplicate") {
            $this->page_title = $this->page_title_duplicate;
        } else {
            $this->page_title = $this->page_title_create;
        }
        if (isset($config["freetext_fields"])) {
            $this->freetext_fields = $config["freetext_fields"];
        } else {
            $this->freetext_fields = "";
        }
        if (isset($config["default_sort_fields"])) {
            $this->default_sort_fields = $config["default_sort_fields"];
        } else {
            $this->default_sort_fields = "";
        }
        $application->default_sort_fields_array = explode("|", $this->default_sort_fields);

        // Get breadcrumb insert
        if ((isset($config["breadcrumb_insert_text"])) && (isset($config["breadcrumb_insert_url"]))) {
            $this->breadcrumb_insert_text = $config["breadcrumb_insert_text"];
            $this->breadcrumb_insert_url = $config["breadcrumb_insert_url"];

            $this->breadcrumb_insert_text = parse_placeholders($this->breadcrumb_insert_text, $this->object);
            $this->breadcrumb_insert_url = parse_placeholders($this->breadcrumb_insert_url, $this->object);
        }

        //pre ($this);
        if (isset($config["url_view"])) {
            $this->url_view = $config["url_view"];
        } else {
            //$this->url_view = parse_placeholders($this->url_view, $this->object);

            $this->url_view = "";
        }

        $this->page_title = parse_placeholders($this->page_title, $this->object);
        $this->page_title_view = parse_placeholders($this->page_title_view, $this->object);
        $this->url_view = parse_placeholders($this->url_view, $this->object);
    }

    private function display()
    {
        global $application, $error_handler;
        $my_control_content = $application->data[$application->tariff_object]["form"];
        foreach ($my_control_content as $item) {
            if (in_array($item["control_type"], array("start_form", "end_form"))) {
                $this->show_form = false;
            } else {
                $this->show_form = true;
            }
        }
?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $this->root ?>"><?= $this->object_name ?></a>
                </li>
                <?php
                //pre ($this->page_title_create);
                if (($application->mode == "update") || (strpos($this->page_title_create, "description") !== false)) {
                    if ($this->page_title_view != "") {
                ?>
                        <li class="govuk-breadcrumbs__list-item">
                            <a class="govuk-breadcrumbs__link" href="<?= $this->url_view ?>"><?= $this->page_title_view ?></a>
                        </li>
                    <?php
                    }
                }
                if (($this->breadcrumb_insert_text != null) && ($this->breadcrumb_insert_url != null)) {
                    ?>
                    <li class="govuk-breadcrumbs__list-item">
                        <a class="govuk-breadcrumbs__link" href="<?= $this->breadcrumb_insert_url ?>"><?= $this->breadcrumb_insert_text ?></a>
                    </li>
                <?php
                }
                ?>
                <li class="govuk-breadcrumbs__list-item" aria-current="page"><?= $this->page_title ?></li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-one-fifth nav-sticky">
                    <?php
                    //$root = $_SERVER["DOCUMENT_ROOT"];
                    //require($root . "quotas/includes/left_nav.php");
                    if ($this->show_navigation) {
                        new left_nav_control();
                    }
                    ?>
                </div>
                <div class="govuk-grid-column-four-fifths">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl"><?= $this->page_title ?></h1>
                    <!-- End main title //-->
                    <?php
                    if ($this->show_form) {
                    ?>
                        <form name="form_<?= $this->class_name ?>" id="form_<?= $this->class_name ?>" action="<?= $this->action ?>" class="data_entry_form" method="post" <?= $this->validate_string ?>>
                        <?php
                    }
                        ?>
                        <div class="govuk-grid-row">
                            <div class="govuk-grid-column-full">
                                <?php
                                new mode_control();
                                $config = $application->data[$application->tariff_object]["config"];

                                $i = 0;
                                foreach ($my_control_content as $item) {
                                    $control_type = $item["control_type"];

                                    // Set some defaults to prevent errors -- control_scope
                                    if (isset($item["control_scope"])) {
                                        $control_scope = $item["control_scope"];
                                    } else {
                                        $control_scope = "";
                                    }

                                    // Set some defaults to prevent errors -- control_name
                                    if (isset($item["control_name"])) {
                                        $control_name = $item["control_name"];
                                    } else {
                                        $control_name = "";
                                    }

                                    // Set some defaults to prevent errors -- disabled_on_edit
                                    if (isset($item["disabled_on_edit"])) {
                                        $disabled_on_edit = $item["disabled_on_edit"];
                                    } else {
                                        $disabled_on_edit = "";
                                    }

                                    // Set some defaults to prevent errors -- default_on_insert
                                    if (isset($item["default"])) {
                                        $default_on_insert = $item["default"];
                                    } else {
                                        $default_on_insert = "";
                                    }

                                    // Set some defaults to prevent errors -- pattern
                                    if (isset($item["pattern"])) {
                                        $pattern = $item["pattern"];
                                    } else {
                                        $pattern = "";
                                    }

                                    // Set some defaults to prevent errors -- group_class
                                    if (isset($item["group_class"])) {
                                        $group_class = $item["group_class"];
                                    } else {
                                        $group_class = "";
                                    }

                                    // Set some defaults to prevent errors -- custom_errors
                                    if (isset($item["custom_errors"])) {
                                        $custom_errors = $item["custom_errors"];
                                    } else {
                                        $custom_errors = "";
                                    }

                                    // Set some defaults to prevent errors -- custom_errors
                                    if (isset($item["control_class"])) {
                                        $control_class = $item["control_class"];
                                    } else {
                                        $control_class = "";
                                    }


                                    switch ($control_type) {
                                        case "detail_table_control":
                                            new detail_table_control(
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $caption = $item["caption"],
                                                $dataset = $this->control_content[$control_name],
                                                $description_keys = $config["description_keys"]
                                            );
                                            break;
                                        case "membership_table_control":
                                            new membership_table_control(
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $caption = $item["caption"],
                                                $dataset = $this->control_content[$control_name],
                                                $description_keys = $config["description_keys"]
                                            );
                                            break;

                                        case "roo_membership_table_control":
                                            new roo_membership_table_control(
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $caption = $item["caption"],
                                                $dataset = $this->control_content[$control_name],
                                                $description_keys = $config["description_keys"]
                                            );
                                            break;

                                        case "additional_code_measure_type_table_control":
                                            new additional_code_measure_type_table_control(
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $caption = $item["caption"],
                                                $dataset = $this->control_content[$control_name],
                                                $description_keys = $config["description_keys"]
                                            );
                                            break;
                                        case "footnote_assignment_table_control":
                                            new footnote_assignment_table_control(
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $caption = $item["caption"],
                                                $dataset = $this->control_content[$control_name],
                                                $application_code_description = "",
                                                $description_keys = $config["description_keys"]
                                            );
                                            break;
                                        case "button_control":
                                            new button_control(
                                                $text = $item["text"],
                                                $name = $item["name"],
                                                $type = $item["type"],
                                                $include_submitted = $item["include_submitted"],
                                                $link_href = $item["link_href"]
                                            );
                                            break;
                                        case "button_cluster_control":
                                            new button_cluster_control();
                                            break;
                                        case "workbasket_control":
                                            new workbasket_control();
                                            break;
                                        case "inset_control":
                                            new inset_control(
                                                $text = $item["text"],
                                                $control_scope = $control_scope
                                            );
                                            break;
                                        case "span_control":
                                            new span_control(
                                                $text = $item["text"],
                                                $control_scope = $control_scope,
                                                $control_name = $control_name,
                                                $group_class = $group_class
                                            );
                                            break;
                                        case "start_form":
                                            echo ('<form action="' . $this->action . '" class="data_entry_form" method="post" ' . $this->validate_string . '>');
                                            break;

                                        case "hidden_control":
                                            new hidden_control(
                                                $control_name = $item["control_name"],
                                                $value = $item["value"]
                                            );
                                            break;
                                        case "include_control":
                                            new include_control(
                                                $path = $item["path"],
                                                $control_scope = $control_scope
                                            );
                                            break;
                                        case "warning_control":
                                            new warning_control(
                                                $text = $item["text"]
                                            );
                                            break;
                                        case "summary_detail_control":
                                            new summary_detail_control(
                                                $text1 = $item["text1"],
                                                $text2 = $item["text2"],
                                                $control_scope = $control_scope
                                            );
                                            break;

                                        case "back_control":
                                            new back_control();
                                            break;

                                        case "error_block":
                                            /* Start error handler */
                                            echo ($error_handler->get_primary_error_block());
                                            /*  End error handler */
                                            break;
                                        case "input_control":
                                            new input_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $control_style = $item["control_style"],
                                                $size = $item["size"],
                                                $maxlength = $item["maxlength"],
                                                $pattern = $item["pattern"],
                                                $required = $item["required"],
                                                $default = $this->object->{$item["control_name"]},
                                                $default_on_insert,
                                                $disabled_on_edit = $disabled_on_edit,
                                                $custom_errors = $item["custom_errors"],
                                                $group_class
                                            );
                                            break;
                                        case "character_count_control":
                                            new character_count_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $rows = $item["rows"],
                                                $maxlength = $item["maxlength"],
                                                $required = $item["required"],
                                                $default = $this->object->{$item["control_name"]},
                                                $pattern = $pattern,
                                                $control_scope = $control_scope,
                                                $custom_errors,
                                                $group_class
                                            );
                                            break;
                                        case "date_picker_control":
                                            new date_picker_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $default = $this->object->{$item["control_name"]},
                                                $required = $item["required"]
                                            );
                                            break;

                                        case "conditional_date_picker_control":
                                            new conditional_date_picker_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $control_scope = $control_scope,
                                                $default = $this->object->{$item["control_name"]},
                                                $required = $item["required"]
                                            );
                                            break;

                                        case "select_control":
                                            //h1 ($this->object->{$item["control_name"]});
                                            new select_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $dataset = $this->control_content[$control_name],
                                                $default_value = $item["default_value"],
                                                $default_string = $item["default_string"],
                                                $default_on_insert,
                                                $selected = $this->object->{$item["control_name"]},
                                                $required = $item["required"],
                                                $disabled_on_edit = $disabled_on_edit,
                                                $group_by = $item["group_by"],
                                                $custom_errors,
                                                $group_class,
                                                $control_class
                                            );
                                            break;
                                        case "radio_control":
                                            new radio_control(
                                                $label = $item["label"],
                                                $label_style = $item["label_style"],
                                                $hint_text = $item["hint_text"],
                                                $control_name = $item["control_name"],
                                                $dataset = $this->control_content[$control_name],
                                                $selected = $this->object->{$item["control_name"]},
                                                $radio_control_style = $item["radio_control_style"],
                                                $required = $item["required"],
                                                $disabled_on_edit = $disabled_on_edit,
                                                $custom_errors,
                                                $group_class
                                            );
                                            break;
                                    }
                                }
                                ?>
                            </div>
                        </form>
                </div>
            </div>
            </div>
        </main>
<?php
    }
}
?>