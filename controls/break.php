<?php
class break_control
{
    // Class properties and methods go here
    public $text = "";

    public function __construct($text)
    {
        $this->text = $text;
        $this->display();
    }

    private function display()
    {
?>
        <!-- Start paragraph //-->
        <hr class="scenario_divider" />
        <p class="govuk-body scenario_divider" style="margin:0px !important"><?= $this->text ?></p>
        <hr class="scenario_divider" />
        <!-- End paragraph //-->
<?php
    }
}
?>