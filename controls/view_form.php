<?php
class view_form
{
    // Class properties and methods go here
    public $dataset = Null;
    public $editable = true;
    public $control_content = Null;
    public $workbasket_advisory = "xx";
    public $root = "./";

    public function __construct($control_content, $object)
    {
        $this->control_content = $control_content;
        $this->object = $object;

        //prend ($object);
        $this->get_config();
        $this->display();
    }

    private function get_config()
    {
        global $application;
        $config = $application->data[$application->tariff_object]["config"];

        if (isset($config["editable"])) {
            $this->editable = $config["editable"];
        }

        $this->object_name = $config["object_name"];
        $this->url_edit = $this->detokenise($config["url_edit"]);
        $this->url_delete = str_replace("create_edit", "delete", $this->url_edit);
        $this->url_delete = str_replace("update", "delete", $this->url_delete);

        if (isset($config["override_root"])) {
            $this->root = $config["override_root"];
        } else {
            $this->root = "./";
        }
        $this->page_title = $config["title_view"];

        $this->freetext_fields = $config["freetext_fields"];
        $this->default_sort_fields = $config["default_sort_fields"];
        $application->default_sort_fields_array = explode("|", $this->default_sort_fields);

        $this->parse_title();

        // Get workbasket text
        if ($application->session->workbasket == null) {
            $this->workbasket_advisory = "You are viewing this " . $this->singularise($config["object_name"]) . " in read-only mode.<br /><br /><br />If you edit the data, you will be asked to create a new workbasket or select an existing workbasket first.";
        } else {
            $this->workbasket_advisory = "You are viewing this  " . $this->singularise($config["object_name"]) . "  in read-only mode.<br /><br />If you edit the data, your changes will be added to your current workbasket <strong>" . $application->session->workbasket->title . "</strong>.";
        }
    }

    private function parse_title()
    {
        $text = $this->page_title;
        preg_match_all('/{(.*?)}/', $text, $matches);
        foreach ($matches[1] as $match) {
            if (isset($_GET[$match])) {
                $text = str_replace($match, $this->object->{$match}, $text);
            } else {
                $text = str_replace($match, "", $text);
            }
            $text = str_replace("{", "", $text);
            $text = str_replace("}", "", $text);
        }
        $this->page_title = $text;
    }

    private function singularise($s, $replace_spaces = false)
    {
        $s = strtolower($s);
        if (substr($s, -1) == "s") {
            $s = substr($s, 0, -1);
        }
        if ($replace_spaces) {
            $s = str_replace(" ", "_", $s);
        }
        $s = str_replace("serie", "series", $s);
        return ($s);
    }

    private function detokenise($s)
    {
        preg_match_all('/{(.*?)}/', $s, $matches);
        foreach ($matches[1] as $match) {
            if (strpos($match, "date") !== false) {
                $s = str_replace("{" . $match . "}", short_date($this->object->{$match}), $s);
            } elseif ($match == "goods_nomenclature_item_id") {
                $s = str_replace("{" . $match . "}", format_goods_nomenclature_item_id($this->object->{$match}), $s);
            } else {
                $s = str_replace("{" . $match . "}", $this->object->{$match}, $s);
            }
        }
        return ($s);
    }

    private function display()
    {
        global $application;
        $my_field_content = $application->data[$application->tariff_object]["view"]["fields"];
        $control_list = $application->data[$application->tariff_object]["view"]["controls"];
        $config = $application->data[$application->tariff_object]["config"];
        //pre ($this->object->status);
?>
        <!-- Start view form control //-->
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $this->root ?>"><?= $this->object_name ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page"><?= $this->page_title ?></li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl"><?= $this->page_title ?></h1>
                    <!-- End main title //-->

                    <?php
                    new inset_control($this->workbasket_advisory, "");
                    ?>

                    <div class="govuk-tabs" data-module="govuk-tabs">
                        <h2 class="govuk-tabs__title">
                            Contents
                        </h2>
                        <ul class="govuk-tabs__list">
                            <li class="govuk-tabs__list-item govuk-tabs__list-item--selected">
                                <a class="govuk-tabs__tab" href="#core">
                                    Core <?= $this->singularise($config["object_name"]) ?> data
                                </a>
                            </li>
                            <?php
                            foreach ($control_list as $item) {
                                $control_name = $item["control_name"];
                                if (isset($this->control_content[$control_name])) {
                                    $type = gettype($this->control_content[$control_name]);
                                    //h1 ($control_name . " : " . $type);
                                    if ((gettype($this->control_content[$control_name]) == "array") || (gettype($this->control_content[$control_name]) == "resource")) {
                            ?>
                                        <li class="govuk-tabs__list-item">
                                            <a class="govuk-tabs__tab" href="#tab_<?= $control_name ?>">
                                                <?= $item["caption"] ?>
                                            </a>
                                        </li>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                        <section class="govuk-tabs__panel" id="core">
                            <div class="govuk-grid-row">

                                <div class="govuk-grid-column-three-quarters">

                                    <h2 class="govuk-heading-l">Core <?= $this->singularise($config["object_name"]) ?> data</h2>
                                    <!-- Start primary fields //-->
                                    <table class="govuk-table">
                                        <thead class="govuk-table__head">
                                            <tr class="govuk-table__row">
                                                <th scope="col" class="govuk-table__header govuk-visually-hidden">Field</th>
                                                <th scope="col" class="govuk-table__header govuk-visually-hidden">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody class="govuk-table__body">
                                            <?php
                                            foreach ($my_field_content as $item) {
                                                $label = $item["label"];
                                                $value = $item["value"];
                                                $value = $this->detokenise($value);


                                            ?>
                                                <tr class="govuk-table__row">
                                                    <td class="govuk-table__cell b" style="width:30%"><?= $label ?></td>
                                                    <td class="govuk-table__cell" style="width:70%"><?= $value ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                                <!-- End primary fields //-->

                                <div class="govuk-grid-column-one-quarter">
                                    <?php
                                    if ($this->editable) {
                                    ?>
                                        <div class="gem-c-contextual-sidebar">
                                            <div class="gem-c-related-navigation">
                                                <h2 class="gem-c-related-navigation__main-heading" data-track-count="sidebarRelatedItemSection">
                                                    Actions
                                                </h2>
                                                <?php
                                                if ($this->object->status == "Awaiting approval") {
                                                    new lock_control("This item has been locked for editing as is current in a status of " . $this->object->status);
                                                } else {
                                                ?>

                                                <nav role="navigation" class="gem-c-related-navigation__nav-section" aria-labelledby="related-nav-related_items-90f47a0c" data-module="gem-toggle">
                                                    <ul class="gem-c-related-navigation__link-list" data-module="track-click">
                                                        <li class="govuk-link gem-c-related-navigation__link"><a class="govuk-link" href="<?= $this->url_edit ?>"><img class='inline_icon' src='/assets/images/edit.png'/>Edit this <?= $this->singularise($config["object_name"]) ?></a></li>
                                                        <li class="govuk-link gem-c-related-navigation__link"><a class="govuk-link" href="<?= $this->url_delete ?>"><img class='inline_icon' src='/assets/images/delete.png'/>Delete this <?= $this->singularise($config["object_name"]) ?></a></li>
                                                    </ul>
                                                </nav>
                                                <?php
                                                }
                                                $warning_array = array("Footnotes", "Additional codes", "Geographical areas", "Certificates");
                                                if (in_array($config["object_name"], $warning_array)) {
                                                    $tab_name = "#tab_" . $this->singularise($config["object_name"], true) . "_descriptions";
                                                ?>

                                                    <p class="govuk-body">
                                                        Please note that you are unable to modify descriptions on this tab.
                                                        Please select the '<a class="govuk-link" href="<?= $tab_name ?>">Descriptions</a>' tab to create and modify descriptions.
                                                    </p>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </section>



                        <!-- Start secondary fields //-->
                        <?php
                        foreach ($control_list as $item) {
                            $table_class = "";
                            if (isset($item["table_class"])) {
                                $table_class = $item["table_class"];
                            }
                        ?>
                            <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="tab_<?= $item["control_name"] ?>">
                                <?php
                                $control_type = $item["control_type"];
                                switch ($control_type) {
                                    case "version_control":
                                        new version_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "detail_table_control":
                                        new detail_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "misc_data_table_control":
                                        new misc_data_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $intro_text = $item["intro_text"],
                                            $edit_text = $item["edit_text"],
                                            $edit_url = $item["edit_url"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"],
                                            $table_class = $table_class
                                        );
                                        break;

                                    case "additional_code_measure_type_table_control":
                                        new additional_code_measure_type_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "membership_table_control":
                                        new membership_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "roo_membership_table_control":
                                        new roo_membership_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;


                                    case "footnote_assignment_table_control":
                                        new footnote_assignment_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $application_code_description = $this->control_content["application_code_description"],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;
                                }
                                ?>
                            </section>
                        <?php
                        }
                        ?>
                        <!-- End secondary fields //-->
                    </div>
                </div>
            </div>
        </main>
<?php
    }
}
?>