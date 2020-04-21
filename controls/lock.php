<?php
class lock_control
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
        <p class="govuk-body lock">
            <div class="lock_img">
                <img src="/assets/images/lock.png" />
            </div>
            <div class="lock_text">
                <?= $this->text ?>
            </div>
        </p>
        <!-- End paragraph //-->
<?php
    }
}
?>