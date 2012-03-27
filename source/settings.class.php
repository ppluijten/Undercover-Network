<?php

class Settings {

    private static $settings = array();

    public static function GetSetting($name) {
        return (string) self::$settings[$name];
    }

    public static function SettingExists($name) {
        if (isset(self::$settings[$name])) {
            return true;
        } else {
            return false;
        }
    }

    public static function SetSetting($name, $value) {
        self::$settings[(string) $name] = (string) $value;
    }

    public static function SetSettings($array) {
        foreach($array as $key => $value) {
            self::SetSetting("$key", "$value");
        }
    }

}

?>