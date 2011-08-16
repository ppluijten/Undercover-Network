<?php

require_once("header.php");

// Game Info data
$twittertemplate = new Template("twitterwall", "", "", true);

$prevars['templates']['twitterwall'] = $twittertemplate->ReturnOutput();

?>