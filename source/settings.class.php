<?php

class Settings {

    var $settings = array();

    function GetSetting($name) {
        return (string) $this->settings[$name];
    }

    function SettingExists($name) {
        if (isset($this->settings[$name])) {
            return true;
        } else {
            return false;
        }
    }

    function SetSetting($name, $value) {
        $this->settings[(string) $name] = (string) $value;
    }

    /*function LoadSettings() {
        $settings = array();
        $this->settings = $settings;
    }*/

    function __construct() {
        //$this->LoadSettings();
    }

}

?>