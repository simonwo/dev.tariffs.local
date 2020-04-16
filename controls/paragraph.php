<?php
class paragraph_control
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
        <p class="govuk-body"><?= $this->text ?></p>
        <!-- End paragraph //-->
<?php
    }
}
?>