<?php

//TODO: Find a better way to do this
// Making sure the Cookies of the forum can be read
if((strpos($_SERVER['HTTP_HOST'], "www") === FALSE) && (strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)) {
    header('Location: http://www.undercover-network.nl/' . $_SERVER['PHP_SELF']) ;
}

// Turn off error reporting, except actual errors
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// Configuration data
require "config.php";

// Database modification and usage class
require "source/db.class.php";
DB::Connect($db_host, $db_username, $db_password, $db_database);
//$db = new DB($db_host, $db_username, $db_password, $db_database);

// Content modification and usage class
require "source/content.class.php";
$content = new Content();

// Settings
require "source/settings.class.php";

// Settings configuration data
require "config_settings.php";

// Defines
require "define.php";

// User modification and usage class
require "source/user.class.php";
User::checkUser();
//$user = new User();

// Block admin to anyone other than the allowed users
if((!in_array((int) User::getUserId(), array(576, 15))) && (strpos($_SERVER['PHP_SELF'], "admin") !== FALSE)) {
    if($_SERVER['SERVER_NAME'] != 'localhost') { //DEBUG
        exit("You do not have access to this page.");
    } //DEBUG
}

// Templates
require "source/template.class.php";

// HTTP
require "source/http.class.php";

// Pre-variables
$prevars = array();

?>