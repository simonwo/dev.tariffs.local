<?php
class heading_text
{
    // Class properties and methods go here
    public $key = "";
    public $text = "";
    public $scope = "";

    public function __construct()
    {
        global $application;

        $s = $_SERVER["SCRIPT_FILENAME"];
        if (strpos($s, "chapter") !== false) {
            $this->scope = "section";
            $this->key = "section " . get_querystring("section_id");
        } elseif (strpos($s, "heading") !== false) {
            $this->scope = "chapter";
            $this->key = substr(get_querystring("goods_nomenclature_item_id"), 0, 2);
        } elseif (strpos($s, "commodity") !== false) {
            $this->scope = "heading";
            $this->key = substr(get_querystring("goods_nomenclature_item_id"), 0, 4);
        } else {
            $this->scope = "";
        }

        $heading_texts = $application->data[$application->tariff_object]["heading_texts"];
        //pre($heading_texts);
        foreach ($heading_texts as $key => $value) {
            //pre($this->key . " --- " . $value);
            if ($this->key == $key) {
                $this->text = $value;
                break;
            }
        }
    }
}
