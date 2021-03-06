<?php
class inset_control
{
    // Class properties and methods go here
    public $text = "";
    public $control_scope = "";

    public function __construct($text, $control_scope = "")
    {
        global $application, $measure_activity;
        $text = str_replace("[WORKBASKET]", "<br /><br /><span class='inset_1'>Workbasket:</span><span class='highlighted_text inset_2'><a href='/workbaskets/view.html'>{workbasket_name}</a></span>", $text);
        $text = str_replace("[WORKBASKET_ACTIVITY]", "<br /><br /><span class='inset_1'>Workbasket:</span><span class='highlighted_text inset_2'>{workbasket_name}</span><span class='inset_1'>Activity:</span><span class='highlighted_text inset_2'>{activity_name}</span>", $text);

        if (strpos($text, "{workbasket_name}") !== false) {
            $text = str_replace("{workbasket_name}", "<strong>" . $application->session->workbasket->title . "</strong>", $text);
        }
        if (strpos($text, "{activity_name}") !== false) {
            $text = str_replace("{activity_name}", "<strong>" . $measure_activity->activity_name . "</strong>", $text);
        }
        if (strpos($text, "{measure_count}") !== false) {
            $text = str_replace("{measure_count}", $measure_activity->measure_count, $text);
        }
        if (strpos($text, "{measure_count_plural}") !== false) {
            if ($measure_activity->measure_count == 1) {
                $text = str_replace("{measure_count_plural}", "", $text);
            } else {
                $text = str_replace("{measure_count_plural}", "s", $text);
            }
        }
        preg_match_all('/{(.*?)}/', $text, $matches);
        foreach ($matches[1] as $match) {
            if (isset($_GET[$match])) {
                $text = str_replace($match, $_GET[$match], $text);
            } else {
                $text = str_replace($match, "", $text);
            }
        }
        $text = str_replace("{", "", $text);
        $text = str_replace("}", "", $text);


        $this->text = $text;
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
            <!-- Start inset text //-->
            <div class="govuk-inset-text"><?= $this->text ?></div>
            <!-- End inset text //-->
<?php
        }
    }
}
?>