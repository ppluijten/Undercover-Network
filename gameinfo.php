<?php

require_once("header.php");

// Game Info data
$gameinfotemplate = new Template("gameinfo", "", "", true);

$prevars['templates']['gameinfo'] = $gameinfotemplate->ReturnOutput();

?>