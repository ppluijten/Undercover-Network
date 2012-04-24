<?php

class Settings {

    private static $settings = array();

    /**
     * Fetch a setting
     *
     * @param string $name the name of the setting
     * @return string the value of the setting
     */
    public static function GetSetting($name) {
        return (string) self::$settings[$name];
    }

    /**
     * Check whether a setting exists
     *
     * @param string $name the name of the setting
     * @return boolean whether the setting exists
     */
    public static function SettingExists($name) {
        if (isset(self::$settings[$name])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the value of a setting
     *
     * @param string $name the name of the setting
     * @param string $value the new value of the setting
     */
    public static function SetSetting($name, $value) {
        self::$settings[(string) $name] = (string) $value;
    }

    /**
     * Set a set of settings according to an input array with key-value pairs
     *
     * @param array $array the array of key-value pairs with new settings
     */
    public static function SetSettings($array) {
        foreach($array as $key => $value) {
            self::SetSetting("$key", "$value");
        }
    }

}

?>