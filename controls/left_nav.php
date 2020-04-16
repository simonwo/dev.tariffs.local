<?php
class left_nav_control
{
    // Class properties and methods go here
    public $text = "";
    public $control_scope = "";
    public $control_name = "";
    public $name_id_string = "";
    public $roll_forward = false;

    public function __construct()
    {
        global $application;
        $this->navigation = $application->data["navigation"];
        $this->title = $this->navigation["title"];
        $this->links = $this->navigation["links"];
        if (isset($this->navigation["roll_forward"])) {
            $this->roll_forward = $this->navigation["roll_forward"];
        }
        $this->display();
    }

    private function display()
    {
        $REQUEST_URI = $_SERVER["REQUEST_URI"];
        $qpos = strpos($REQUEST_URI, "?");
        if ($qpos) {
            $REQUEST_URI = substr($REQUEST_URI, 0, $qpos);
        }
?>
        <nav class="app-subnav">
            <h4 class="app-subnav__theme"><?= $this->title ?></h4>
            <ul class="app-subnav__section">
                <?php
                $index = 0;
                $found_index = 999;
                foreach ($this->links as $link) {
                    $link_url = parse_placeholders($link["url"]);
                    $qpos = strpos($link_url, "?");
                    if ($qpos) {
                        $link_url2 = substr($link_url, 0, $qpos);
                    } else {
                        $link_url2 = $link_url;
                    }
                    $link_text = parse_placeholders($link["text"]);
                    if ($link_url2 == $REQUEST_URI) {
                        $current_class = "app-subnav__section-item--current";
                        $found_index = $index;
                    } else {
                        $current_class = "";
                    }
                    $link_id = $this->fmt($link["text"]);
                    echo ('<li id="' . $link_id . '" class="app-subnav__section-item ' . $current_class . '">');
                    if (!$this->roll_forward) {
                        if ($index > $found_index) {
                            echo ('<a class="app-subnav__link govuk-link">' . $link_text . '</a>');
                        } else {
                            echo ('<a class="app-subnav__link govuk-link" href="' . $link_url . '">' . $link_text . '</a>');
                        }
                    } else {
                        echo ('<a class="app-subnav__link govuk-link" href="' . $link_url . '">' . $link_text . '</a>');
                    }
                    echo ("</li>");
                ?>
                    </li>
                <?php
                    $index += 1;
                }
                ?>
            </ul>
        </nav>
<?php
    }

    private function fmt($s)
    {
        $s = strtolower($s);
        $s = str_replace(" ", "_", $s);
        return ($s);
    }
}
?>