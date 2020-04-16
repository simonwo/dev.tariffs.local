<?php
class summary_detail_control
{
    // Class properties and methods go here
    public $text1 = "";
    public $text2 = "";
    public $control_scope = "";
    public $object = null;

    public function __construct($text1, $text2, $control_scope = "", $object = null)
    {
        global $application, $measure_activity;

        $this->object = $object;
        $this->text1 = $text1;
        $this->text2 = parse_placeholders($text2);
        $this->control_scope = $control_scope;
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
        if (!$this->suppress_control) {
?>
            <!-- Start summary / detail //-->
            <details class="govuk-details" data-module="govuk-details">
                <summary class="govuk-details__summary">
                    <span class="govuk-details__summary-text">
                        <?= $this->text1 ?>
                    </span>
                </summary>
                <div class="govuk-details__text">
                    <?= $this->text2 ?>
                </div>
            </details>
            <!-- End summary / detail //-->

<?php
        }
    }
}
?>