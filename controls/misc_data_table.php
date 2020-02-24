<?php
class misc_data_table_control
{
    // Class properties and methods go here
    public $control_name = "";
    public $caption = "";
    public $intro_text = "";
    public $edit_text = "";
    public $edit_url = "";
    public $table_class = "";
    public $dataset = array();
    public $fields = array();
    public $description_keys = array();
    public $suppress_control = false;

    public function __construct($control_name, $control_scope, $caption, $intro_text, $edit_text, $edit_url, $dataset, $description_keys, $table_class = "")
    {
        global $application;
        $this->control_name = $control_name;
        $this->control_scope = $control_scope;
        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
                return;
            }
        }

        $this->caption = $caption;
        $this->intro_text = $intro_text;
        $this->table_class = $table_class;
        $this->edit_text = $edit_text;
        $this->edit_url = $edit_url;
        $this->dataset = $dataset;
        $this->object_name = strtolower($application->object_name);
        $this->object_name = rtrim($this->object_name, "s");
        $this->object_description = $this->object_name . " description";
        $this->description_key_string = $description_keys;
        $this->description_keys = explode("|", $this->description_key_string);
        $this->querystring = "";
        foreach ($this->description_keys as $description_key) {
            $this->querystring .= $description_key . "=" .  $_GET[$description_key] . "&";
        }
        $this->querystring = rtrim($this->querystring, "&");

        preg_match_all('/{(.*?)}/', $this->caption, $matches);
        foreach ($matches[1] as $match) {
            if (isset($_GET[$match])) {
                $this->caption = str_replace("{" . $match . "}", $_GET[$match], $this->caption);
            } else {
                $this->caption = str_replace("{" . $match . "}", "", $this->caption);
            }
        }
        $this->caption = str_replace("{", "", $this->caption);
        $this->caption = str_replace("}", "", $this->caption);

        preg_match_all('/{(.*?)}/', $this->edit_url, $matches);
        foreach ($matches[1] as $match) {
            if (isset($_GET[$match])) {
                $this->edit_url = str_replace("{" . $match . "}", $_GET[$match], $this->edit_url);
            } else {
                $this->edit_url = str_replace("{" . $match . "}", "", $this->edit_url);
            }
        }
        $this->edit_url = str_replace("{", "", $this->edit_url);
        $this->edit_url = str_replace("}", "", $this->edit_url);



        if (gettype($this->dataset) == "array") {
            $controls = $application->data[$application->tariff_object]["view"]["controls"];
            foreach ($controls as $control) {
                if ($control["control_name"] == $this->control_name) {
                    $this->fields = $control["fields"];
                    break;
                }
            }
            $this->display_array();
        } else {
            $this->display_resource();
        }
    }

    private function display_array()
    {
        echo ("<h2 class='govuk-heading-m'>" . $this->caption . "</h2>");
        echo ("<p class='govuk-body'>" . $this->intro_text . "</p>");
        if (count($this->fields) == 0) {
            return;
        }
        if (count($this->dataset) == 0) {
            echo ("<p class='govuk-body'>There are currently no " . strtolower($this->caption) . ".</p>");
        } else {
?>
            <!-- Start array table //-->
            <table class="govuk-table sticky <?= $this->table_class ?>" id="<?= $this->control_name ?>">
                <!--<caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>//-->
                <thead class="govuk-table__head">
                    <tr class="govuk-table__row">
                        <?php
                        foreach ($this->fields as $field) {
                            echo ('<th scope="col" class="govuk-table__header ' . $field["class"] . '">' . $field["label"] . '</th>');
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->dataset as $item) {
                        echo ('<tr class="govuk-table__row">');
                        foreach ($this->fields as $field) {
                            echo ('<td class="govuk-table__cell ' . $field["class"] . '">' . format_array_value($item->{$field["value"]}, $field["value"]) . '</td>');
                        }
                        echo ('</tr>');
                    }
                    ?>
                </tbody>
            </table>
            <!-- End array table //-->
            <?php
        }
        if (($this->edit_text != "") && ($this->edit_url != "")) {
            echo ("<p class='govuk-body'><a class='govuk-link' href='" . $this->edit_url . "'><img class='inline_icon' src='/assets/images/new.png'/>" . $this->edit_text . "</a></p>");
        }
}



    private function display_resource()
    {
        $data_found = false;
        if ($this->suppress_control == false) {
            if ($this->dataset) {
                $row_count = pg_num_rows($this->dataset);
                if ($row_count > 0) {
                    $data_found = true;
                    $field_count = pg_num_fields($this->dataset);

            ?>
                    <!-- Start table //-->
                    <table class="govuk-table sticky" id="<?= $this->control_name ?>">
                        <caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <?php
                                for ($i = 0; $i < $field_count; $i++) {
                                    $field = pg_field_name($this->dataset, $i);
                                    echo ('<th scope="col" class="govuk-table__header">' . format_field_name($field) . '</th>');
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <?php
                            while ($row = pg_fetch_object($this->dataset)) {
                                echo ('<tr class="govuk-table__row">');
                                for ($i = 0; $i < $field_count; $i++) {
                                    $field = pg_field_name($this->dataset, $i);
                                    echo ('<td class="govuk-table__cell">' . format_value($row, $field) . '</td>');
                                }
                                echo ('</tr>');
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- End table //-->
<?php
                }
            }
            if (!$data_found) {
                echo ("<h1 class='govuk-heading-m'>" . $this->caption . "</h1>");
                echo ("<p class='govuk-body'>No data found</p>");
            }
            if (($this->edit_text != "") && ($this->edit_url != "")) {
                echo ("<p class='govuk-body'><a class='govuk-link' href='" . $this->edit_url . "'>" . $this->edit_text . "</a></p>");
            }
        }
    }
}
?>