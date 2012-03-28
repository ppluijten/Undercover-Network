<?php

require_once("../header.php");
require("usercontrol.common.php");

// User data
$usertemplate = new Template("usercontrol", "", "../", true);
$logintemplate = new Template("login", "", "../", true);
if(User::isLoggedIn()) {
    $userdatatemplate = new Template("userdata_loggedin", "", "../", true);
    $userdatatemplate->setVariable("userid", (int) User::getUserId());
    $userdatatemplate->setVariable("username", (string) User::getUserData('username'));
    $usertemplate->setVariable("userdata", $userdatatemplate->ReturnOutput());
} else {
    $usertemplate->setVariable("userdata", "<img style='width: 65px; height: 65px;' src='images/avatar.jpg' class='avatar'>");
    //TODO: Login form werkend maken
    //$usertemplate->setVariable("loginform", $logintemplate->ReturnOutput());
    $usertemplate->setVariable("loginform", "<a href='forum/forum.php'><img src='layout/layout_right/loginbutton.png' alt='Login' class='loginbutton'></a>");
}

$prevars['templates']['usercontrol'] = $usertemplate->ReturnOutput();
$prevars['templates']['xajaxJavascript'] = $xajax->printJavascript();

if(stripos($_SERVER['HTTP_REFERER'], "undercover-gaming.nl/forum/login.php")) {
    $prevars['templates']['body_onload'] = "notifyParent();";
}

?>