<?php
class span_control
{
    // Class properties and methods go here
    public $text = "";
    public $control_scope = "";
    public $control_name = "";
    public $group_class = "";
    public $name_id_string = "";

    public function __construct($text, $control_scope = "", $control_name = "", $group_class = "")
    {
        $this->text = $text;
        $this->control_scope = $control_scope;
        $this->control_name = $control_name;
        $this->group_class = $group_class;
        $this->suppress_control = false;

        $this->display();
    }

    private function display()
    {
        global $application;
        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
            }
        }
        if ($this->control_name != "") {
            $this->name_id_string = ' name="' . $this->control_name . '" id="' . $this->control_name . '"';
        } else {
            $this->name_id_string = "";
        }
        
        if (!$this->suppress_control) {
?>
        <!-- Start inset text //-->
        <span <?= $this->name_id_string ?> class="conditional_span govuk-hint <?= $this->group_class ?>"><?= $this->text ?></span>
        <!-- End inset text //-->
<?php
        }
    }
}
?>