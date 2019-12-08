<?php
class section
{
	// Class properties and methods go here
	public $section_id = "";
	public $numeral = "";
	public $title = "";
	public $chapter_string = "";

    public function __construct($section_id, $numeral, $title) {
		$this->section_id = $section_id;
		$this->numeral = $numeral;
        $this->title = $title;
        $this->chapters = array();
    }
    
    public function get_chapter_string() {
        if (count($this->chapters) == 1) {
            $this->chapter_string = $this->chapters[0];
        } else {
            $this->chapter_string = $this->chapters[0] . " to " . $this->chapters[count($this->chapters) - 1];
        }
    }
}