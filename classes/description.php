<?php
class description
{
    // Class properties and methods go here
    public $validity_start_date = null;
    public $period_sid = null;
    public $description = "";

    public function __construct($validity_start_date, $description, $period_sid)
    {
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->period_sid = $period_sid;
    }
}
