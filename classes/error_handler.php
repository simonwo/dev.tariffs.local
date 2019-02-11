<?php
class error_handler
{
	// Class properties and methods go here
	public $error_string = "";
	public $measure_types = array ();

    public function __construct() {
        $err = get_querystring("err");
        $this->error_list = array();
        if ($err != "1") {
            setcookie("errors", "", time() + (86400 * 30), "/");
        } else {
            if(isset($_COOKIE["errors"])) {
                $error_string = $_COOKIE["errors"];
                $this->error_list = unserialize($error_string);
            } else {
                $errors = "";
            }
        }
    }

    public function get_value_on_error($scope) {
        if (count($this->error_list) > 0) {
            $value = get_cookie($scope);
        } else {
            $value = "";
        }
        return ($value);
    }

    public function get_errors($scope) {
        if (count($this->error_list) > 0) {
            switch ($scope) {
            case "create_measure_phase1":
                /*foreach ($this->error_list as $err) {
                    echo ($err);
                }*/
                break;
            }
        }
    }
    public function get_error($scope) {
        if (in_array($scope, $this->error_list)) {
            return ("govuk-form-group--error");
        } else {
            return ("");
        }
    }
} 