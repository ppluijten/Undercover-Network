<?php

require_once("header.php");

// Game Info data
$bannertemplate = new Template("bannerright", "", "", true);

$prevars['templates']['bannerright'] = $bannertemplate->ReturnOutput();

?>