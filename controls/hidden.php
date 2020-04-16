<?php
class hidden_control
{
    // Class properties and methods go here
    public $control_name = "";

    public function __construct($control_name, $value, $parse = true)
    {
        global $application;
        if ($application->mode == "") {
            $application->mode = "insert";
        }
        $this->control_name = $control_name;
        if ($parse == true) {
            $this->value = parse_placeholders($value);
        } else {
            $this->value = $value;
        }
        $this->value = str_replace('"', "'", $this->value);
        $this->display();
    }

    private function display()
    {
?>
        <!-- Start hidden control //-->
        <input type="hidden" name="<?= $this->control_name ?>" id="<?= $this->control_name ?>" value="<?= $this->value ?>" />
        <!-- End hidden control //-->
<?php
    }
}
?>