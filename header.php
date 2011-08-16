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
$db = new DB($db_host, $db_username, $db_password, $db_database);

// Content modification and usage class
require "source/content.class.php";
$content = new Content();

// Settings
require "source/settings.class.php";
$settings = new Settings();

// Settings configuration data
require "config_settings.php";

// User modification and usage class
require "source/user.class.php";
$user = new User();

// Block admin to anyone other than the allowed users
if((!in_array((int) $user->userdata['userid'], array(576, 589))) && (strpos($_SERVER['PHP_SELF'], "admin") !== FALSE)) {
    exit("You do not have access to this page.");
}

// Templates
require "source/template.class.php";

// HTTP
require "source/http.class.php";

// Pre-variables
$prevars = array();

?>